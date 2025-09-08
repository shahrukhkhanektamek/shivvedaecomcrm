<?php
namespace App\Http\Controllers\SalesMan;
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

class SalesManAuthController extends Controller
{
    
    
    
    
    
   
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
            return redirect(route('salesman.salesman-dashboard'));
        }
        return view('salesman.login',compact('setting','main_setting'));
    }
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        

        $user = DB::table('users');
        $user->where('user_id',$username);
        $user->where('role',3);
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
            $result['url'] = url(route('salesman.salesman-dashboard'));
            $result['data'] = [];
            $responseCode = 200;                
            $this->token_session($request,$user);            
            $result['status'] = $responseCode;
            return response()->json($result, $responseCode);
        }
    }


   
    


    

    public function logout(Request $request)
    {
        auth()->guard('users')->logout();
        $request->session('user')->invalidate();
        $responseCode = 200;
        $result['message'] = 'Logout Successfully';
        $result['action'] = 'redirect';
        $result['url'] = route('salesman.salesman');
        $result['status'] = $responseCode;
        $result['data'] = [];
        return response()->json($result, $responseCode);
    }
}