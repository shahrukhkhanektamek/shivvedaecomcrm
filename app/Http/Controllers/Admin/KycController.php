<?php

namespace App\Http\Controllers\Admin;





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

 

class KycController extends Controller

{

     protected $arr_values = array(

        'routename'=>'kyc.', 

        'title'=>'KYC', 

        'table_name'=>'users',

        'page_title'=>'KYC',

        "folder_name"=>'/kyc',

        "upload_path"=>'upload/',

        "page_name"=>'cource-detail.php',

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

        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');

        

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

      $status = 1;

      $table_id = 1;

      $listcheckbox = [];

      $filter_search_value = '';

      $keys = '';

      $where_query = "";

      $order_by = "id desc";

      $is_delete = 0;

      



      if(!empty($request->limit)) $limit = $request->limit;

      if(isset($request->status)) $status = $request->status;

      if(!empty($request->order_by)) $order_by = $request->order_by;

      if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;







        $data['table_name'] = $this->arr_values['table_name'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['back_btn'] = route($this->arr_values['routename'].'list');

        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');

        







      $data_list = User::where(['kyc_step' => $status]);

      

      if(!empty($filter_search_value))

      {

        $filter_search_value = explode(" ", $filter_search_value);

        foreach ($filter_search_value as $key => $value)
        {
            $data_list = $data_list->orWhere('users.name','LIKE',"%{$value}%");            
            $data_list = $data_list->orWhere('users.phone','LIKE',"%{$value}%");            
            $data_list = $data_list->orWhere('users.email','LIKE',"%{$value}%");           

        }

      }









      $data_list = $data_list->latest()->paginate($limit);

        $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list','data'))->render();

        $responseCode = 200;

        $result['status'] = $responseCode;

        $result['message'] = 'Success';

        $result['action'] = 'view';

        $result['data'] = ["list"=>$view,];

        return response()->json($result, $responseCode);

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

        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');

        

        $data['keys'] = $this->arr_values['keys'];          

        $data['pagenation'] = array($this->arr_values['title']);

        $data['trash'] = '';



        $row = Kyc::where(["id"=>$id,])->first();

        if(!empty($row))

        {

            $course_category = DB::table("course_category")->where(["status"=>1,])->get();

            return view($this->arr_values['folder_name'].'/form',compact('data','row','course_category'));

        }

        else

        {

            return view('/404',compact('data'));            

        }

    }

    public function view($id='')

    {   

        $id = Crypt::decryptString($id);

        $data['title'] = "".$this->arr_values['title'];

        $data['page_title'] = "Edit ".$this->arr_values['page_title'];

        $data['table_name'] = $this->arr_values['table_name'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['submit_url'] = route($this->arr_values['routename'].'update');

        $data['back_btn'] = route($this->arr_values['routename'].'list');

        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');

        

        $data['keys'] = $this->arr_values['keys'];          

        $data['pagenation'] = array($this->arr_values['title']);

        $data['trash'] = '';



        $user = User::where(["id"=>$id,])->first();

        if(!empty($user))

        {

            $row = Kyc::where(["user_id"=>$id,])->orderBy('id','desc')->first();

            return view($this->arr_values['folder_name'].'/view',compact('data','row'));

        }

        else

        {

            return view('/404',compact('data'));            

        }

    }



    public function update(Request $request)

    {

        $id = Crypt::decryptString($request->id);

        if(empty($id)) $data = new Kyc;

        else $data = Kyc::find($id);



        $session = Session::get('admin');

        $add_by = $session['id'];

        



        

        $data->bank_holder_name = $request->bank_holder_name;

        $data->bank_name = $request->bank_name;

        $data->account_number = $request->account_number;

        $data->account_type = $request->account_type;

        $data->ifsc = $request->ifsc;

        $data->pan = $request->pan;

        $data->rg_mobile = $request->rg_mobile;

        $data->rg_email = $request->rg_email;

        $data->address = $request->address;

        $data->kyc_message = $request->kyc_message;

        $data->status = $request->kyc_step;



        

        
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
     



        $data->add_by = $add_by;

        $data->is_delete = 0;

        if($data->save())

        {

            $user_id = $data->user_id;

            $kycData = ["pan"=>$data->pan,"bank_holder_name"=>$data->bank_holder_name,"bank_name"=>$data->bank_name,"account_number"=>$data->account_number,"ifsc"=>$data->ifsc,"kyc_message"=>$request->kyc_message,"kyc_step"=>$request->kyc_step,"rg_mobile"=>$data->rg_mobile,];

            DB::table('users')->where('id',$user_id)->update($kycData);



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