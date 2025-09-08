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

use App\Models\Support;

 

class SupportController extends Controller

{

     protected $arr_values = array(

        'routename'=>'support.', 

        'title'=>'Support', 

        'table_name'=>'support',

        'page_title'=>'Support',

        "folder_name"=>'/support',

        "upload_path"=>'upload/',

        "page_name"=>'support-detail.php',

        "keys"=>'id,name',

        "all_image_column_names"=>array("image"),

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

        $data['view_btn_url'] = route($this->arr_values['routename'].'view');

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

      $status = 0;

      $table_id = 1;

      $listcheckbox = [];

      $filter_search_value = '';

      $keys = '';

      $where_query = "";

      $order_by = "desc";

      $is_delete = 0;

      



      if(!empty($request->limit)) $limit = $request->limit;

      if(!empty($request->status)) $status = $request->status;

      if(!empty($request->order_by)) $order_by = $request->order_by;

      if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;



      // echo $status;



        $data['table_name'] = $this->arr_values['table_name'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['back_btn'] = route($this->arr_values['routename'].'list');

        $data['add_btn_url'] = route($this->arr_values['routename'].'add');

        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');

        $data['view_btn_url'] = route($this->arr_values['routename'].'view');

        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');







      $data_list = Support::where([$this->arr_values['table_name'].'.status' => $status])->orderBy($this->arr_values['table_name'].'.id',$order_by)
      ->leftJoin("users as users","users.id","=",$this->arr_values['table_name'].".user_id")
      ->select($this->arr_values['table_name'].".*",
        "users.name as user_name",
        "users.phone as user_phone",
        "users.user_id as member_id",
        "users.email as user_email",
        )
      ;

      

      if(!empty($filter_search_value))

      {

        $filter_search_value = explode(" ", $filter_search_value);

        foreach ($filter_search_value as $key => $value)

        {

            $data_list = $data_list->where($this->arr_values['table_name'].'.subject','LIKE',"%{$value}%");            
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

    public function add()

    {

        $data['title'] = "".$this->arr_values['title'];

        $data['page_title'] = "All ".$this->arr_values['page_title'];

        $data['table_name'] = $this->arr_values['table_name'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['submit_url'] = route($this->arr_values['routename'].'update');

        $data['back_btn'] = route($this->arr_values['routename'].'list');

        $data['add_btn_url'] = route($this->arr_values['routename'].'add');

        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');

        $data['view_btn_url'] = route($this->arr_values['routename'].'view');

        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');

        $data['keys'] = $this->arr_values['keys'];          

        $data['pagenation'] = array($this->arr_values['title']);

        $data['trash'] = '';

        $row = [];

        $blog_category = DB::table("blog_category")->where(["status"=>1,])->get();

        return view($this->arr_values['folder_name'].'/form',compact('data','row','blog_category'));

    }

    public function edit($id='')

    {   

        $id = Crypt::decryptString($id);

        $data['title'] = "".$this->arr_values['title'];

        $data['page_title'] = "All ".$this->arr_values['page_title'];

        $data['table_name'] = $this->arr_values['table_name'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['submit_url'] = route($this->arr_values['routename'].'update');

        $data['back_btn'] = route($this->arr_values['routename'].'list');

        $data['add_btn_url'] = route($this->arr_values['routename'].'add');

        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');

        $data['view_btn_url'] = route($this->arr_values['routename'].'view');

        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');

        $data['keys'] = $this->arr_values['keys'];          

        $data['pagenation'] = array($this->arr_values['title']);

        $data['trash'] = '';



        $row = Support::where(["id"=>$id,])->first();

        if(!empty($row))

        {

            return view($this->arr_values['folder_name'].'/form',compact('data','row'));

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

        $data['page_title'] = "All ".$this->arr_values['page_title'];

        $data['table_name'] = $this->arr_values['table_name'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['submit_url'] = route($this->arr_values['routename'].'update');

        $data['back_btn'] = route($this->arr_values['routename'].'list');

        $data['add_btn_url'] = route($this->arr_values['routename'].'add');

        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');

        $data['view_btn_url'] = route($this->arr_values['routename'].'view');

        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');

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

        $id = Crypt::decryptString($request->id);

        if(empty($id)) $data = new Support;

        else $data = Support::find($id);



        $session = Session::get('admin');

        $add_by = $session['id'];



        

        

        $data->status = $request->status;



        // if(empty($id))

        // {

        //     $data->display_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('display_image'));

        //     $data->banner_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('banner_image'));

        // }

        // else

        // {

        //     if ($request->display_image)

        //         $data->display_image = ImageManager::update($this->arr_values['upload_path'], $data->display_image, 'png', $request->file('display_image'));

        //     if ($request->banner_image)

        //     {

        //         $data->banner_image = ImageManager::update($this->arr_values['upload_path'], $data->banner_image, 'png', $request->file('banner_image'));

        //     }

        // }





        $data->add_by = $add_by;

        $data->is_delete = 0;

        if($data->save())

        {

            $responseCode = 200;

            $result['status'] = $responseCode;

            $result['message'] = 'Success';

            $result['action'] = 'redirect';

            $result['url'] = route('support.list');

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