<?php
namespace App\Http\Controllers\APi\User;


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
 
class Checkout extends Controller
{
     

      protected $arr_values = array(
        'table_name'=>'cart',
       );  

    protected $user_id = null;
    public function __construct()
    {
        $authToken = request()->header('Authorization');
        $user = Helpers::decode_token($authToken);
        if ($user) {
            $this->user_id = $user->user_id;
        }
    }


    public function token_session($request, $user)
    {
        $device_id = $request->device_id;
        $password = $user->password;
        $firebase_token = $request->firebase_token;
        $date_time = date("Y-m-d H:i:s");
        $token_data = array("user_id"=>$user->id,"password"=>$user->password,"date_time"=>$date_time,"role"=>$user->role,"device_id"=>$device_id,);
        $access_token = Helpers::encode_token($token_data);
        $login_detail = array(
            "user_id"=>$user->id,
            "role"=>$user->role,
            "ip_address"=>$_SERVER['REMOTE_ADDR'],
            "date"=>date("Y-m-d"),
            "time"=>date("H:i:s"),
            "status"=>1,
            "device_id"=>$device_id,
            "password"=>$password,
            "firebase_token"=>$firebase_token,
            "access_token"=>$access_token,
        );
        if(DB::table('login_history')->insert($login_detail))
        {
        }
        return $access_token;
    }
    
   
    public function place_order(Request $request)
    {   
        $user_id = $this->user_id;


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
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Your cart is empty!';
            $result['action'] = $action;
            $result['url'] = route('user.product.list');
            $result['data'] = [];
            return response()->json($result, $responseCode);

        }


        if(empty($request->name))
        {
            $result['status'] = 400;
            $result['message'] = 'Enter name!';
            $result['action'] = 'return';
            $result['data'] = [];
            return response()->json($result, 400);
        }
        else if(empty($request->phone))
        {
            $result['status'] = 400;
            $result['message'] = 'Enter phone!';
            $result['action'] = 'return';
            $result['data'] = [];
            return response()->json($result, 400);
        }
        else if(empty($request->state))
        {
            $result['status'] = 400;
            $result['message'] = 'Select state!';
            $result['action'] = 'return';
            $result['data'] = [];
            return response()->json($result, 400);
        }
        else if(empty($request->city))
        {
            $result['status'] = 400;
            $result['message'] = 'Enter city!';
            $result['action'] = 'return';
            $result['data'] = [];
            return response()->json($result, 400);
        }
        else if(empty($request->pincode))
        {
            $result['status'] = 400;
            $result['message'] = 'Enter pincode!';
            $result['action'] = 'return';
            $result['data'] = [];
            return response()->json($result, 400);
        }
        else if(empty($request->address))
        {
            $result['status'] = 400;
            $result['message'] = 'Enter address!';
            $result['action'] = 'return';
            $result['data'] = [];
            return response()->json($result, 400);
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


        $data['screenshot'] = ImageManager::upload('upload/', 'png', $request->file('image'));


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

        $user = DB::table("users")->where("id", $user_id)->first();
        $user->image = Helpers::image_check($user->image,'user.png');


        $action = 'placeOrder';
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Order Place Successfully';
        $result['url'] = route('user.checkout.success').'?order_id='.$order_id;
        $result['action'] = $action;
        $result['data'] = $user;
        $token = $this->token_session($request,$user);
        $result['token'] = $token;
        return response()->json($result, $responseCode);
    }

    public function use_wallet(Request $request)
    {   
        $id = $request->id;
        $qty = $request->qty;
        $user_id = $this->user_id;

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
        $data['payable_amount_int'] = $cartFinalAmount-$repurchase_wallet_deduct;

        $upiUrl = "upi://pay?pa=" . urlencode($upi) . "&am=" . urlencode($cartFinalAmount-$repurchase_wallet_deduct) . "&cu=INR";

       
        $action = 'return';
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = '';
        $result['action'] = $action;
        $result['data'] = $data;
        $result['image'] = 'https://api.qrserver.com/v1/create-qr-code/?data='.urlencode('upi://pay?pa='.$upi.'&am='.$cartFinalAmount-$repurchase_wallet_deduct.'&cu=INR').'&size=300x300';
        $result['upi'] = $upi;
        $result['upiUrl'] = $upiUrl;
        return response()->json($result, $responseCode);
    }
    public function check(Request $request)
    {   
        $user_id = $this->user_id;

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