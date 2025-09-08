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
use App\Models\Package;
 
class UserProfileController extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.profile.', 
        'title'=>'Profile', 
        'table_name'=>'users',
        'page_title'=>'Profile',
        "folder_name"=>user_view_folder.'/profile',
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
        $data['page_title'] = "All ".$this->arr_values['page_title'];
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
        $session = Session::get('user');
        $id = $add_by = $session['id'];


        $data = User::find($id);

        
        if($data->is_paid==1)
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Contact to '.env('APP_NAME').' for any change!';
            $result['action'] = 'add';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        
        
        $data->name = $request->name.' '.$request->lname;
        $data->address = $request->address;
        $data->city = $request->city;
        $data->state = $request->state;
        $data->country = $request->country;
        $data->phone = $request->phone;
        $data->email = $request->email;


        $email = $request->email;

        // $check_exist_email = DB::table('users')
        // ->where('email',$email)
        // ->whereNotIn('id', [$add_by])
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


        $data->add_by = $add_by;
        $data->is_delete = 0;
        if($data->save())
        {
            $action = 'add';
            if(!empty($id)) $action = 'edit';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
    }
 

}