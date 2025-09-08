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

use App\Models\Bank;

 

class BankController extends Controller

{

     protected $arr_values = array(

        'routename'=>'bank.', 

        'title'=>'Bank', 

        'table_name'=>'users',

        'page_title'=>'Bank',

        "folder_name"=>'/bank',

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

      if(!empty($request->status)) $status = $request->status;

      if(!empty($request->order_by)) $order_by = $request->order_by;

      if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;







        $data['table_name'] = $this->arr_values['table_name'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['back_btn'] = route($this->arr_values['routename'].'list');

        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');

        







      $data_list = Bank::where(['kyc_step' => 1]);

      

      if(!empty($filter_search_value))

      {

        $filter_search_value = explode(" ", $filter_search_value);

        foreach ($filter_search_value as $key => $value)

        {

            $data_list = $data_list->where('name','LIKE',"%{$value}%");            

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



        $row = Bank::where(["id"=>$id,])->first();

        if(!empty($row))

        {
            return view($this->arr_values['folder_name'].'/form',compact('data','row'));
        }

        else

        {

            return view('/404',compact('data'));            

        }

    }



    public function update(Request $request)

    {

        $id = Crypt::decryptString($request->id);

        if(empty($id)) $data = new Bank;

        else $data = Bank::find($id);



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

        $data->kyc_step = $request->kyc_step;





        if($request->kyc_step==1 && !empty($request->bank_holder_name1))

        {

            $data->bank_holder_name = $request->bank_holder_name1;

            $data->bank_name = $request->bank_name1;

            $data->account_number = $request->account_number1;

            $data->account_type = $request->account_type1;

            $data->ifsc = $request->ifsc1;

            $data->pan = $request->pan1;

            $data->rg_mobile = $request->rg_mobile1;

            $data->rg_email = $request->rg_email1;

        }

        else

        {

            $data->bank_holder_name1 = $request->bank_holder_name1;

            $data->bank_name1 = $request->bank_name1;

            $data->account_number1 = $request->account_number1;

            $data->account_type1 = $request->account_type1;

            $data->ifsc1 = $request->ifsc1;

            $data->pan1 = $request->pan1;

            $data->rg_mobile1 = $request->rg_mobile1;

            $data->rg_email1 = $request->rg_email1;

        }





























        if(empty($id))

        {

            $data->passbook_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('passbook_image'));

        }

        else

        {

            if ($request->passbook_image)

                $data->passbook_image = ImageManager::update($this->arr_values['upload_path'], $data->passbook_image, 'png', $request->file('passbook_image'));

        }





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