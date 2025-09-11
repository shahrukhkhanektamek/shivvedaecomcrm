<?php
namespace App\Http\Controllers\SalesMan;


use App\Helper\Helpers;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use App\Models\Cart;
use App\Models\MemberModel;
use App\Helper\ImageManager;

use Illuminate\Support\Facades\Storage;
use App\Providers\RekognitionService;

use Aws\S3\S3Client;
 
class SalesManCheckout extends Controller
{
     protected $arr_values = array(
        'routename'=>'salesman.checkout.', 
        'title'=>'Checkout', 
        'table_name'=>'deposit_request',
        'page_title'=>'Checkout',
        "folder_name"=>'salesman/checkout',
        "upload_path"=>'upload/',
        "keys"=>'id,name',
       );  

   

    protected $rekognition;
    protected $s3;
    protected $bucket;

    public function __construct(RekognitionService $rekognition)
    {
        Helpers::create_importent_columns($this->arr_values['table_name']);
        $this->rekognition = $rekognition;

        // S3 client
        $this->s3 = new S3Client([
            'region'    => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'version'   => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $this->bucket = env('AWS_BUCKET');
    }


    public function index(Request $request)
    {
        $session = Session::get('user');
        $user_id = $session['id'];
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = $this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $this->arr_values['folder_name'];
        $row = Helpers::get_user_user();
        $id = $row->id;

        $orders = DB::table("orders")->where("user_id",$user_id)->first();

        return view($this->arr_values['folder_name'].'/index',compact('data','row','orders'));
    }
    public function success(Request $request)
    {
        $session = Session::get('user');
        $user_id = $session['id'];
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = $this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $this->arr_values['folder_name'];
        $row = Helpers::get_user_user();
        $id = $row->id;

        $orders = DB::table("orders")->where(["user_id"=>$user_id,"order_id"=>$request->order_id,])->first();

        return view($this->arr_values['folder_name'].'/success',compact('data','row','orders'));
    }
   
    public function place_order(Request $request)
    {   
        $id = $request->id;
        $session = Session::get('user');
        $user_id = $session['id'];
        $user = DB::table("users")->where('id', $user_id)->first();
        $repurchase_wallet_deduct = 0;
        $wallet_use = $request->payment_mode;

        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $image = $request->image;
        $faceID = $request->faceId;
        $faceID = @explode("faces/",@$faceID)[1];
        



        $cartDetail = MemberModel::cartDetail($user_id);
        $cartTotal = $cartDetail['cartTotal'];
        $gst = $cartDetail['gst'];
        $cartFinalAmount = $cartDetail['cartFinalAmount'];



        $checkFirstOrder = DB::table("orders")->where(["face"=>$faceID,])->first();
        

    

        $count = count($cartDetail['cartProducts']);
        if($count<1)
        {
            $action = 'redirect';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Your cart is empty!';
            $result['action'] = $action;
            $result['url'] = route('user.product.list');
            $result['data'] = [];
            return response()->json($result, $responseCode);

        }
        
        $order_id = time().$user_id;
        foreach ($cartDetail['cartProducts'] as $key => $value)
        {            
            $order_product['order_id'] = $order_id;
            $order_product['product_id'] = $value->id;
            $order_product['bv'] = $value->bv;
            $order_product['name'] = $value->name;
            $order_product['price'] = $value->sale_price*$value->qty;
            $order_product['qty'] = $value->qty;
            $order_product['user_id'] = $user_id;
            $order_product['add_by'] = $user_id;
            $order_product['status'] = 0;
            $order_product['add_date_time'] = date("Y-m-d H:i:s");
            $order_product['update_date_time'] = date("Y-m-d H:i:s");
            DB::table("order_products")->insert($order_product);
        }


        $gst_amount = $cartFinalAmount*12/100;
        $subTotal = $cartFinalAmount-$gst_amount;
        $final_income = $cartFinalAmount;



        $data['order_id'] = $order_id;
        $data['user_id'] = $user_id;
        $data['amount'] = $subTotal;
        $data['gst'] = $gst_amount;
        $data['final_amount'] = $final_income;
        $data['bv'] = $cartDetail['totalBv'];

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['state'] = $request->state;
        $data['city'] = $request->city;
        $data['pincode'] = $request->pincode;
        $data['address'] = $request->address;

        $data['add_by'] = $user_id;
        $data['status'] = 0;
        $data['wallet_use'] = $wallet_use;
        $data['wallet_amount'] = $repurchase_wallet_deduct;
        $data['payment_by'] = 'COD';
        $data['payment_date_time'] = date("Y-m-d H:i:s");
        $data['add_date_time'] = date("Y-m-d H:i:s");
        $data['update_date_time'] = date("Y-m-d H:i:s");


        // $data['screenshot'] = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('image'));


        $amount_detail['Sub Total'] = $subTotal;
        $amount_detail['GST'] = $gst_amount;
        $amount_detail['Total Amount'] = $final_income;
        $amount_detail['Wallet Amount'] = $repurchase_wallet_deduct;
        $amount_detail['Payable Amount'] = $subTotal;

        $data['amount_detail'] = json_encode($amount_detail);

        if(empty($checkFirstOrder))
        {
            $uploadBase64ToS3 = $this->uploadToS3($request->file('face'), $user_id);
            $imageName = @explode("faces/",@$uploadBase64ToS3['path'])[1];
            $data['face'] = $imageName;
        }
        else
        {
            $data['face'] = $faceID;            
        }


        DB::table("orders")->insert($data);


        DB::table('cart')->where("user_id",$user_id)->delete();


        $action = 'placeOrder';
        $responseCode = 400;
        $result['status'] = $responseCode;
        $result['message'] = 'Order Place Successfully';
        $result['url'] = route('salesman.checkout.success').'?order_id='.$order_id;
        $result['action'] = $action;
        $result['data'] = [];
        return response()->json($result, $responseCode);
    }


    public function compare(Request $request)
    {

        // $request->validate([
        //     'image' => 'required|image|max:4096', // 4MB
        // ]);

        // $sourceFile = $request->file('image');
        // $sourcePath = $sourceFile->getRealPath();
        $sourcePath = $request->image;

        // List all images in S3 folder
        $objects = $this->s3->listObjectsV2([
            'Bucket' => $this->bucket,
            'Prefix' => 'faces/',
        ]);

        if (!isset($objects['Contents']) || empty($objects['Contents'])) {
            return response()->json(['match' => false]);
        }

        $rdata = [];

        foreach ($objects['Contents'] as $obj) {
            $targetKey = $obj['Key'];

            try {
                $matches = $this->rekognition->compareFacesLocalVsS3(
                    $sourcePath,
                    $this->bucket,
                    $targetKey,
                    70 // similarity threshold, you can change to 80
                );

                if (!empty($matches)) {
                    $rdata = [
                        'match' => true,
                        'target' => $targetKey,
                        'similarity' => $matches[0]['Similarity'],
                    ];
                    break;
                }
            } catch (\Exception $e) {
                // skip errors
                continue;
            }
        }

        if(!empty(@$rdata['target']) && !empty(@$rdata['match']))
        {

            // $faceID = '1757576903_1757576903';
            $faceID = @explode("faces/",@$rdata['target'])[1];
            $row = Helpers::get_user_user();
            $data=[];

            $products = [];
            $order = DB::table("orders")->where(["face"=>$faceID,])->orderBy('id','desc')->first();

            $is_header = true;
            $view = '';
            if(!empty($order))
            {
                $view = View::make('salesman/my-order/view',compact('data','row','order','is_header'))->render();
                $products = DB::table("order_products")->where(["order_id"=>@$order->order_id,])->get();
            }
            $data = [
                "match"=>true,
                "target"=>$rdata['target'],
                "similarity"=>$rdata['similarity'],
                "order"=>$order,
                "products"=>$products,
                "orderView"=>$view,
            ];
            return response()->json($data);
        }
        else
        {
            return response()->json(['match' => false]);
        }

    }

    public function uploadToS3($file, $user_id)
    {

        // original file name
        $fileName = time() . '_' . time();

        // upload to bucket root or specific folder
        $path = Storage::disk('s3')->putFileAs('faces', $file, $fileName);

        // make file public (optional)
        $url = Storage::disk('s3')->url($path);


        return [
            'message' => 'Image uploaded successfully',
            'path' => $path,
            'url' => $url,
        ];

    }




}