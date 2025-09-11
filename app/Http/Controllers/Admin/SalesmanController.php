<?php
namespace App\Http\Controllers\Admin;


use App\Helper\Helpers;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Custom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use App\Helper\ImageManager;
use App\Models\User;
use App\Models\MemberModel;
use Illuminate\Support\Str;
use App\Models\MailModel;
use App\Models\Package;
use App\Models\Setting;
use App\Models\IncomeHistory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
class SalesmanController extends Controller
{
     protected $arr_values = array(
        'routename'=>'sales-man.', 
        'title'=>'Sales Man', 
        'table_name'=>'users',
        'page_title'=>'Sales Man',
        "folder_name"=>'/sales-man',
        "upload_path"=>'upload/',
        "page_name"=>'sales-man-detail.php',
        "keys"=>'id,name',
       );  

      public function __construct()
      {
         $this->arr_values['folder_name'] = env("admin_view_folder") . $this->arr_values['folder_name'];
        Helpers::create_importent_columns($this->arr_values['table_name']);
      }

    public function index(Request $request)
    {
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['add_btn_url'] = route($this->arr_values['routename'].'add');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        return view($this->arr_values['folder_name'].'/index',compact('data'));
    }
    public function load_data(Request $request)
    {
      $limit = 12;
      $page = 1;
      $page1 = 1;
      $offset = 0;
      // $status = 1;
      $table_id = 1;
      $listcheckbox = [];
      $filter_search_value = '';
      $keys = '';
      $where_query = "";
      $order_by = "id desc";
      $is_delete = 0;
      $kyc = '';
      $search_type = '';
      $gender = 0;
      

      if(!empty($request->gender)) $gender = $request->gender;
      if(!empty($request->search_type)) $search_type = $request->search_type;
      if(!empty($request->limit)) $limit = $request->limit;
      $status = $request->status;
      if($request->kyc!='') $kyc = $request->kyc;
      if(!empty($request->order_by)) $order_by = $request->order_by;
      if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;
      $blockStatus = $request->block_status;



        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['add_btn_url'] = route($this->arr_values['routename'].'add');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        $data['change_password_btn_url'] = route($this->arr_values['routename'].'change-password');
        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');

        
        if($status==1) $is_paid = 1;
        else if($status==2) $is_paid = 0;
        else $is_paid = 0;

            

        $data_list = User::whereIn('is_paid',[0,1,null]);
        $data_list->where(["role"=>3,]);

  
      
      if(!empty($filter_search_value))
      {
        

        if(!empty($search_type))
        {
            if($search_type==1) $data_list = $data_list->where('user_id',$filter_search_value);
            if($search_type==2) $data_list = $data_list = $data_list->whereRaw('LOWER(CAST(name AS CHAR)) LIKE ?', ["%{$filter_search_value}%"]);
            if($search_type==3) $data_list = $data_list->where('email','LIKE',"%{$filter_search_value}%");
            if($search_type==4) $data_list = $data_list->where('phone','LIKE',"%{$filter_search_value}%");
        }

      }


      

        if($blockStatus==1)
        {
            $data_list->where('status', 0);
        } 
        else if($blockStatus==2)
        {
            $data_list->where('status', 1);
        }
        // $data_list = $data_list->where('email','LIKE',"k@gmail.com");            




      $data_list = $data_list->latest()->paginate($limit);
      $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list','data'))->render();
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = ["list"=>$view,];
        return response()->json($result, $responseCode);
    }
    public function add()
    {
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "Add ".$this->arr_values['page_title'];
        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['add_btn_url'] = route($this->arr_values['routename'].'add');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $row = [];
        $package = DB::table("package")->where(["status"=>1,])->get();
        return view($this->arr_values['folder_name'].'/form',compact('data','row','package'));
    }
    public function edit($id='')
    {   
        $id = Crypt::decryptString($id);
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "Edit ".$this->arr_values['page_title'];
        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['add_btn_url'] = route($this->arr_values['routename'].'add');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';

        $row = User::where(["id"=>$id,])->first();
        if(!empty($row))
        {
            $package = DB::table("package")->where(["status"=>1,])->get();
            return view($this->arr_values['folder_name'].'/form',compact('data','row','package'));
        }
        else
        {
            return view('/404',compact('data'));            
        }
    }
    
    public function update(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        if(empty($id)) $data = new User;
        else $data = User::find($id);

        $session = Session::get('admin');
        $add_by = $session['id'];        

        $email = $request->email;
        $phone = $request->phone;
        $name = $request->name.' '.$request->lname;

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
        $data->name = $request->name;
        $data->phone = $request->phone;
        // $data->address = $request->address;
        // $data->city = $request->city;
        // $data->state = $request->state;
        // $data->country = $request->country;
        $data->status = $request->status;
        




        if(empty($id))
        {
            $data->user_id = MemberModel::GetUserId()+1;
            $data->password = md5($request->password);
        }


        $data->add_by = $add_by;
        $data->is_delete = 0;
        $data->role = 3;
        if($data->save())
        {
            $insert_id = $data->id;
                    

            $action = 'add';
            if(!empty($id)) $action = 'edit';
            // $action = 'edit';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
    }





    public function change_password($id='')
    {   
        $id = Crypt::decryptString($id);
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "".$this->arr_values['page_title']." Change Password";
        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['add_btn_url'] = route($this->arr_values['routename'].'add');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';

        $row = User::where(["id"=>$id,])->first();
        if(!empty($row))
        {
            $package = DB::table("package")->where(["status"=>1,])->get();
            return view($this->arr_values['folder_name'].'/change-password',compact('data','row','package'));
        }
        else
        {
            return view('/404',compact('data'));            
        }
    }
    public function change_password_action(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        if(empty($id)) $data = new User;
        else $data = User::find($id);

        $session = Session::get('admin');
        $add_by = $session['id'];

        if($request->password!=$request->cpassword)
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Confirm Password Not Match!';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        $data->password = md5($request->cpassword);
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
    

    public function block_unblock(Request $request)
    {
        $id = Crypt::decryptString($request->user_id);
        if(empty($id)) $data = new User;
        else $data = User::find($id);

        $session = Session::get('admin');
        $add_by = $session['id'];
              
        $status = 1;
        if($data->status==1) $status = 0;
        else $status = 1;

        
        $data->status = $status;
        $data->add_by = $add_by;
        $data->is_delete = 0;

        if($data->save())
        {
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = 'view';
            $result['data'] = ["status"=>$status,];
            return response()->json($result, $responseCode);
        }
    }

    public function delete(Request $request, $id)
    {
        $id = Crypt::decryptString($request->id);
        $data = User::find($id);
        if($data->delete())
        {
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Delete Successfuly';
            $result['action'] = 'delete';
            $result['data'] = [];            
        }
        else
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Delete Not Successfuly';
            $result['action'] = 'delete';
            $result['data'] = [];            
        }
        return response()->json($result, $responseCode);
    }


}