<?php
namespace App\Http\Controllers\User;


use App\Helper\Helpers;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Custom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use App\Helper\ImageManager;
use App\Models\Kyc;
use App\Models\User;
use App\Models\MailModel;
 
class UserKyc extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.kyc.', 
        'title'=>'Kyc', 
        'table_name'=>'kyc',
        'page_title'=>'Kyc',
        "folder_name"=>user_view_folder.'/kyc',
        "upload_path"=>'upload/',
        "keys"=>'id,name',
       );  

      public function __construct()
      {
        Helpers::create_importent_columns($this->arr_values['table_name']);
      }

    public function index(Request $request)
    {
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['kyc_change_url'] = route($this->arr_values['routename'].'kyc-change-update');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $row = Helpers::get_user_user();
        $user_id = $row->id;
        $kyc = DB::table("kyc")->where(["user_id"=>$user_id,])->orderBy('id','desc')->first();
        if(!empty($row))
        {
            return view($this->arr_values['folder_name'].'/index',compact('data','row','kyc'));
        }
        else
        {
            return view(user_view_folder.'/404',compact('data'));            
        }
    }
  
  

    public function update(Request $request)
    {
        $row = Helpers::get_user_user();
        $user_id = $row->id;
        $id = '';
        if(empty($id)) $data = new Kyc;
        else $data = Kyc::find($id);

        $session = Session::get('user');
        $add_by = $session['id'];


        $lastKyc = DB::table('kyc')->where(["user_id"=>$user_id,])->orderBy('id','desc')->first();
        $user = DB::table('users')->where(["id"=>$user_id,])->orderBy('id','desc')->first();
        
        
        
        $data->user_id = $user_id;
        $data->bank_holder_name = $request->bank_holder_name;
        $data->father_name = $request->father_name;
        $data->nomani_relation = $request->nomani_relation;
        $data->nomani = $request->nomani;
        $data->bank_name = $request->bank_name;
        $data->account_number = $request->account_number;
        $data->account_type = $request->account_type;
        $data->ifsc = $request->ifsc;
        $data->pan = $request->pan;
        $data->rg_mobile = $request->rg_mobile;
        $data->rg_email = $request->rg_email;
        $data->address = $request->address;
        // $data->kyc_message = $request->kyc_message;

        if(empty($lastKyc))
        {
            $data->status = 1;
        }
        else
        {
            $data->status = 0;            
        }


        // if($user->name!=$request->bank_holder_name)
        // {
        //     $responseCode = 400;
        //     $result['status'] = $responseCode;
        //     $result['message'] = 'Your Kyc Name and Profile Name Not Match!';
        //     $result['action'] = 'reload';
        //     $result['data'] = [];
        //     return response()->json($result, $responseCode);
        // }
        

        if($row->kyc_step==2)
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Your Kyc Is Under Review';
            $result['action'] = 'reload';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        // else if($row->kyc_step==1)
        // {
        //     $responseCode = 400;
        //     $result['status'] = $responseCode;
        //     $result['message'] = 'Your Kyc Already Approve';
        //     $result['action'] = 'reload';
        //     $result['data'] = [];
        //     return response()->json($result, $responseCode);
        // }




        if(empty($id))
        {
            if (!empty($request->passbook_image))
            {
                $data->passbook_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('passbook_image'));
            }
            else
            {
                if(!empty($lastKyc))
                {
                    $data->passbook_image = $lastKyc->passbook_image;
                }
            }

            if (!empty($request->pancard_image))
            {
                $data->pancard_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('pancard_image'));
            }
            else
            {
                if(!empty($lastKyc))
                {
                    $data->pancard_image = $lastKyc->pancard_image;
                }
            }

            if (!empty($request->aadharfront_image))
            {
                $data->aadharfront_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('aadharfront_image'));
            }
            else
            {
                if(!empty($lastKyc))
                {
                    $data->aadharfront_image = $lastKyc->aadharfront_image;
                }
            }

            if (!empty($request->aadharback_image))
            {
                $data->aadharback_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('aadharback_image'));
            }
            else
            {
                if(!empty($lastKyc))
                {
                    $data->aadharback_image = $lastKyc->aadharback_image;
                }
            }
        }
        $data->add_by = $add_by;
        $data->is_delete = 0;


        
        if($data->save())
        {
            if(empty($lastKyc))
            {
                DB::table('users')->where('id',$user_id)->update(["kyc_step"=>1,]);
            }
            else
            {
                DB::table('users')->where('id',$user_id)->update(["kyc_step"=>2,]);
            }

            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = 'reload';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }

    }

    public function kyc_change_update(Request $request)
    {
        $row = Helpers::get_user_user();
        $id = $row->id;
        $email = $row->email;
        if(empty($id)) $data = new User;
        else $data = User::find($id);

        $session = Session::get('user');
        $add_by = $session['id'];

        $otp = $request->otp;
        
        
        $data->kyc_change_message = $request->kyc_change_message;
        $data->bank_holder_name1 = $request->bank_holder_name1;
        $data->bank_name1 = $request->bank_name1;
        $data->account_number1 = $request->account_number1;
        $data->account_type1 = $request->account_type1;
        $data->ifsc1 = $request->ifsc1;
        $data->pan1 = $request->pan1;
        $data->rg_mobile1 = $request->rg_mobile1;
        $data->rg_email1 = $request->rg_email1;
        
        $data->kyc_step = 4;

        if(empty($id))
        {
            $data->kyc_change_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('kyc_change_image'));
            $data->pancard_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('pancard_image'));
            $data->aadharfront_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('aadharfront_image'));
            $data->aadharback_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('aadharback_image'));
        }
        else
        {
            if ($request->kyc_change_image)
                $data->kyc_change_image = ImageManager::update($this->arr_values['upload_path'], $data->kyc_change_image, 'png', $request->file('kyc_change_image'));
        }
        $data->add_by = $add_by;
        $data->is_delete = 0;




        $check = DB::table("users_temp")
        ->where('otp',$otp)
        ->where('email',$email)->first();

        if(empty($check))
        {
            $responseCode = 400;
            $result['message'] = 'Wrong OTP...';
            $result['action'] = 'reload';
            $result['data'] = [];
            $result['status'] = $responseCode;
            return response()->json($result, $responseCode);
        }
        if($check->exp_time<date("Y-m-d H:i:s"))
        {
            $responseCode = 200;
            $result['message'] = 'OTP Expired...';
            $result['action'] = 'reload';
            $result['data'] = [];
            $result['status'] = $responseCode;
            return response()->json($result, $responseCode);
        }





        if($row->kyc_step!=1 && $row->kyc_step!=3)
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Your Kyc Not Approve Yet!';
            $result['action'] = 'reload';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        if($data->save())
        {
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = 'reload';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }

    }






    public function kyc_change_otp_send(Request $request)
    {
        $row = $user = Helpers::get_user_user();
        $id = $user_id = $row->id;        
        

        $session = Session::get('user');
        $add_by = $session['id'];   

        if($row->kyc_step!=1 && $row->kyc_step!=3)
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Your Kyc Not Approve Yet!';
            $result['action'] = 'reload';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }


       
        $otp = rand(99999,999999);


        $email = $user->email;
        $details = [
            'to'=>$user->email,
            'view'=>'mailtemplate.otp',
            'subject'=>'One Time Password!',
            'body' => [
                        "otp"=>$otp,
                      ],
        ];
        MailModel::otp($details);


        $date_time = date("Y-m-d H:i:s");
        $data = [
            "role"=>2,
            "data"=>'',
            "email"=>$email,
            "mobile"=>'',
            "otp"=>$otp,
            "device_id"=>2,
            "type"=>2,
            "exp_time"=>date('Y-m-d H:i:s',strtotime($date_time."+15 minute")),
        ];
        $check = DB::table("users_temp")->where('email',$email)->first();
        if(empty($check))
            DB::table('users_temp')->insert($data);
        else
            DB::table('users_temp')->where('email',$email)->update($data);

        
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = [];
        return response()->json($result, $responseCode);
        

    }




 

}