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

use App\Models\Support;

 

class UserSupport extends Controller

{

     protected $arr_values = array(

        'routename'=>'user.support.', 

        'title'=>'Support', 

        'table_name'=>'support',

        'page_title'=>'Support',

        "folder_name"=>user_view_folder.'/support',

        "upload_path"=>'upload/',

        "page_name"=>'support-detail.php',

        "keys"=>'id,name',

        "all_image_column_names"=>array("image"),

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

        $data['back_btn'] = route($this->arr_values['routename'].'list');

        $data['add_btn_url'] = route($this->arr_values['routename'].'add');

        $data['view_btn_url'] = route($this->arr_values['routename'].'view');

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

      $status = 0;

      $table_id = 1;

      $listcheckbox = [];

      $filter_search_value = '';

      $keys = '';

      $where_query = "";

      $order_by = "desc";

      $is_delete = 0;



      $session = Session::get('user');

      $user_id = $session['id'];

      



      if(!empty($request->limit)) $limit = $request->limit;

      if(!empty($request->status)) $status = $request->status;

      if(!empty($request->order_by)) $order_by = $request->order_by;

      if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;



      // echo $order_by;



        $data['table_name'] = $this->arr_values['table_name'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['back_btn'] = route($this->arr_values['routename'].'list');

        $data['add_btn_url'] = route($this->arr_values['routename'].'add');

        $data['view_btn_url'] = route($this->arr_values['routename'].'view');





        $status = [0,1,2,3,4];

      $data_list = Support::where('user_id',$user_id)->whereIn('status',$status)->orderBy('id',$order_by);

      

      if(!empty($filter_search_value))

      {

        $filter_search_value = explode(" ", $filter_search_value);

        foreach ($filter_search_value as $key => $value)

        {

            $data_list = $data_list->where('subject','LIKE',"%{$value}%");            

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

    public function add()

    {

        $data['title'] = "".$this->arr_values['title'];

        $data['page_title'] = "All ".$this->arr_values['page_title'];

        $data['table_name'] = $this->arr_values['table_name'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['submit_url'] = route($this->arr_values['routename'].'update');

        $data['back_btn'] = route($this->arr_values['routename'].'list');

        $data['add_btn_url'] = route($this->arr_values['routename'].'add');

        $data['view_btn_url'] = route($this->arr_values['routename'].'view');

        $data['keys'] = $this->arr_values['keys'];          

        $data['pagenation'] = array($this->arr_values['title']);

        $data['trash'] = '';

        $row = [];

        return view($this->arr_values['folder_name'].'/form',compact('data','row'));

    }

    public function view($id='')

    {   

        $id = Crypt::decryptString($id);

        $data['title'] = "".$this->arr_values['title'];

        $data['page_title'] = "All ".$this->arr_values['page_title'];

        $data['table_name'] = $this->arr_values['table_name'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['submit_url'] = route($this->arr_values['routename'].'update');

        $data['back_btn'] = route($this->arr_values['routename'].'list');

        $data['add_btn_url'] = route($this->arr_values['routename'].'add');

        $data['view_btn_url'] = route($this->arr_values['routename'].'view');

        $data['keys'] = $this->arr_values['keys'];          

        $data['pagenation'] = array($this->arr_values['title']);

        $data['trash'] = '';



        $row = Support::where(["id"=>$id,])->first();

        if(!empty($row))

        {

            return view($this->arr_values['folder_name'].'/view',compact('data','row'));

        }

        else

        {

            return view('/404',compact('data'));            

        }

    }



    public function update(Request $request)

    {

        // $id = Crypt::decryptString($request->id);

        $data = new Support;

        



        $session = Session::get('user');

        $add_by = $session['id'];



        

        

        $data->user_id = $add_by;

        $data->ticket_id = time().$add_by;

        $data->subject = $request->subject;

        $data->message = $request->message;        

        $data->status = 0;



        if(empty($id))

        {

            $data->screenshot = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('screenshot'));

        }

        else

        {

            if ($request->screenshot)

                $data->screenshot = ImageManager::update($this->arr_values['upload_path'], $data->screenshot, 'png', $request->file('screenshot'));

        }





        $data->add_by = $add_by;

        $data->is_delete = 0;

        if($data->save())

        {

            $responseCode = 200;

            $result['status'] = $responseCode;

            $result['message'] = 'Success';

            $result['action'] = 'support';

            $result['url'] = route('user.support.list');

            $result['data'] = [];

            return response()->json($result, $responseCode);

        }



    }

    public function delete(Request $request, $id)

    {

        $id = Crypt::decryptString($request->id);

        $data = Support::find($id);

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