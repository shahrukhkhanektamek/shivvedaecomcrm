<?php
namespace App\Http\Controllers\User;


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
 
class UserCheckout extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.checkout.', 
        'title'=>'Checkout', 
        'table_name'=>'deposit_request',
        'page_title'=>'Checkout',
        "folder_name"=>user_view_folder.'/checkout',
        "upload_path"=>'upload/',
        "keys"=>'id,name',
       );  

      public function __construct()
      {
        Helpers::create_importent_columns($this->arr_values['table_name']);
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



        $cartDetail = MemberModel::cartDetail($user_id);
        $cartTotal = $cartDetail['cartTotal'];
        $gst = $cartDetail['gst'];
        $cartFinalAmount = $cartDetail['cartFinalAmount'];



        if($wallet_use==1)
        {
            $wallet = MemberModel::getTypeAllIncome($user_id);
            $repurchase_wallet = @\App\Models\MemberModel::repurchase_wallet($user_id);
            if($repurchase_wallet>=$cartFinalAmount)
            {
                $repurchase_wallet_deduct = $cartFinalAmount;
            }
            else
            {
                $repurchase_wallet_deduct = $repurchase_wallet;
            }
        }


        $checkFirstOrder = DB::table("orders")->where(["user_id"=>$user_id,])->where("status","!=",4)->first();
        if(!empty($checkFirstOrder) && $user->is_paid==0)
        {
            $action = 'edit';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Your first order is under review!';
            $result['action'] = $action;
            $result['url'] = route('user.product.list');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }



        $totalBv = $cartDetail['totalBv'];
        $incomePlan = DB::table('income_plan')->first();
        if($totalBv<$incomePlan->id_bv && $user->is_paid==0)
        {
            $action = 'edit';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'You need minimum '.$incomePlan->id_bv.' BV!';
            $result['action'] = $action;
            $result['url'] = route('user.product.list');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }

        



        
        // else if($wallet_use==2)
        // {
        //     $action = 'redirect';
        //     $responseCode = 200;
        //     $result['status'] = $responseCode;
        //     $result['message'] = 'Your cart is empty!';
        //     $result['action'] = $action;
        //     $result['url'] = route('user.product.list');
        //     $result['data'] = [];
        //     return response()->json($result, $responseCode);

        // }


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


        $data['screenshot'] = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('image'));


        $amount_detail['Sub Total'] = $subTotal;
        $amount_detail['GST'] = $gst_amount;
        $amount_detail['Total Amount'] = $final_income;
        $amount_detail['Wallet Amount'] = $repurchase_wallet_deduct;
        $amount_detail['Payable Amount'] = $subTotal;

        $data['amount_detail'] = json_encode($amount_detail);



        DB::table("orders")->insert($data);

        if($repurchase_wallet_deduct>0)
        {
            MemberModel::repurchase_wallet_update($user_id,$repurchase_wallet_deduct,2);
        }

        DB::table('cart')->where("user_id",$user_id)->delete();


        $action = 'placeOrder';
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Order Place Successfully';
        $result['url'] = route('user.checkout.success').'?order_id='.$order_id;
        $result['action'] = $action;
        $result['data'] = [];
        return response()->json($result, $responseCode);
    }




    public function use_wallet(Request $request)
    {   
        $id = $request->id;
        $session = Session::get('user');
        $user_id = $session['id'];

        $setting = json_decode(DB::table('setting')->where('name','main')->first()->data);
        $upi = @$setting->upi;

        $user = DB::table("users")->where('id', $user_id)->first();
        $repurchase_wallet_deduct = 0;

        $wallet_use = $request->payment_mode;


        $cartDetail = MemberModel::cartDetail($user_id);
        $cartTotal = $cartDetail['cartTotal'];
        $gst = $cartDetail['gst'];
        $cartFinalAmount = $cartDetail['cartFinalAmount'];



        if($wallet_use==1)
        {

            $wallet = MemberModel::getTypeAllIncome($user_id);
            $repurchase_wallet = @\App\Models\MemberModel::repurchase_wallet($user_id);
            if($repurchase_wallet>=$cartFinalAmount)
            {
                $repurchase_wallet_deduct = $cartFinalAmount;
            }
            else
            {
                $repurchase_wallet_deduct = $repurchase_wallet;
            }
        }
        $totalBv = $cartDetail['totalBv'];
        $incomePlan = DB::table('income_plan')->first();
        if($totalBv<$incomePlan->id_bv && $user->is_paid==0)
        {
            $action = 'edit';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'You need minimum '.$incomePlan->id_bv.' BV!';
            $result['action'] = $action;
            $result['url'] = route('user.product.list');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }


        $data = [];
        $data['cartTotal'] = $cartTotal;
        $data['wallet_amount'] = Helpers::price_formate($repurchase_wallet_deduct);
        $data['final_amount'] = Helpers::price_formate($cartFinalAmount);
        $data['payable_amount'] = Helpers::price_formate($cartFinalAmount-$repurchase_wallet_deduct);


       
        $action = 'view';
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = '';
        $result['action'] = $action;
        $result['data'] = $data;
        $result['image'] = 'https://api.qrserver.com/v1/create-qr-code/?data='.urlencode('upi://pay?pa='.$upi.'&am='.$cartFinalAmount-$repurchase_wallet_deduct.'&cu=INR').'&size=300x300';
        return response()->json($result, $responseCode);
    }


    public function check(Request $request)
    {   
        $id = $request->id;
        $session = Session::get('user');
        $user_id = $session['id'];
        $user = DB::table("users")->where('id', $user_id)->first();
        
        



        $cartDetail = MemberModel::cartDetail($user_id);
        $cartTotal = $cartDetail['cartTotal'];
        $gst = $cartDetail['gst'];
        $cartFinalAmount = $cartDetail['cartFinalAmount'];


        $checkFirstOrder = DB::table("orders")->where(["user_id"=>$user_id,])->where("status","!=",4)->first();
        if(!empty($checkFirstOrder) && $user->is_paid==0)
        {
            $action = 'view';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Your first order is under review!';
            $result['action'] = $action;
            $result['url'] = route('user.product.list');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }



        $totalBv = $cartDetail['totalBv'];
        $incomePlan = DB::table('income_plan')->first();
        if($totalBv<$incomePlan->id_bv && $user->is_paid==0)
        {
            $action = 'view';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'You need minimum '.$incomePlan->id_bv.' BV!';
            $result['action'] = $action;
            $result['url'] = route('user.product.list');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }


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
       
        $action = 'view';
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = $action;
        $result['data'] = [];
        return response()->json($result, $responseCode);
    }


   

   
    


}