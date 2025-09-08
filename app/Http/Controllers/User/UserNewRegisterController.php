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
use App\Models\User;
use App\Models\MemberModel;
 
class UserNewRegisterController extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.new-register.', 
        'title'=>'New Register', 
        'table_name'=>'users',
        'page_title'=>'New Register',
        "folder_name"=>user_view_folder.'/new-register',
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
        $data['page_title'] = $this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $this->arr_values['folder_name'];
        $row = Helpers::get_user_user();
        $id = $row->id;
        return view($this->arr_values['folder_name'].'/index',compact('data','row'));
    }
   
    
   
    

    public function update(Request $request)
    {
        $id = '';
        if(empty($id)) $data = new User;
        else $data = User::find($id);

        $session = Session::get('user');
        $add_by = $session['id'];
        

        $email = $request->email;
        $phone = $request->phone;
        $name = $request->name;


        if($request->password!=$request->confirm_password)
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Confirm Password Not Match!';
            $result['action'] = 'register';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }

        // $check_exist_email = DB::table('users')
        // ->where('email',$email)
        // ->whereNotIn('id', [$id])
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
        // ->whereNotIn('id', [$id])
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


        $data->email = $request->email;
        $data->name = $request->name.' '.$request->lname;
        $data->address = $request->address;
        $data->city = $request->city;
        $data->state = $request->state;
        $data->phone = $request->phone;
        $data->country = $request->country;
        $data->status = 1;
        $data->is_paid = 0;
        $data->user_id = MemberModel::GetUserId()+1;


        $sponser_id = $request->sponser_id;
        $data->placement = $request->placement;
        $data->sponser_id = $sponser_id;
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
        $data->sponser_name = $sponser->name;
        $data->password = md5($request->password);

        $data->add_by = $add_by;
        $data->is_delete = 0;
        $data->role = 2;
        if($data->save())
        {
            $insert_id = $data->id;
        
            $parent_id = MemberModel::getParentIdForHybridPlan($sponser_id, $request->placement);
            $data->parent_id = $parent_id;
            $data->sponser_id = $sponser_id;
            $data->position = $request->placement;

            $data->save();
            MemberModel::AddMemberLog(['id'=> $insert_id, 'user_id'=>$data->user_id, 'name' => $name, 'sponser_id' => $sponser_id, 'side' => $request->placement,"parent_id"=>$parent_id, ]);
            MemberModel::getTypeAllIncome($insert_id);
            

            $result['modalData'][] = ["key"=>"createAccountName","value"=>$data->name,];
            $result['modalData'][] = ["key"=>"createAccountEmail","value"=>$data->email,];
            $result['modalData'][] = ["key"=>"createAccountPhone","value"=>$data->phone,];
            $result['modalData'][] = ["key"=>"createAccountIDNo","value"=>$data->user_id,];
            $result['modalData'][] = ["key"=>"createAccountPassword","value"=>$request->password,];

             $smsData = [
                "number"=>$request->phone,
                "type"=>"loginDetail",
                // "type"=>"shoppingDetail",
                "name"=>$request->name.' '.$request->lname,
                "username"=>$data->user_id,
                "password"=>$request->password,
            ];
            Helpers::sms($smsData);



            $action = 'addModalShow';            
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['modalId'] = "#createAccountModal";
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
    
    


}