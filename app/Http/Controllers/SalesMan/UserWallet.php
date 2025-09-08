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
use App\Models\Wallet;
 
class UserWallet extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.wallet.', 
        'title'=>'Wallet', 
        'table_name'=>'users',
        'page_title'=>'Wallet',
        "folder_name"=>user_view_folder.'/wallet',
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
      $status = 1;
      $table_id = 1;
      $listcheckbox = [];
      $filter_search_value = '';
      $keys = '';
      $where_query = "";
      $order_by = "desc";
      $is_delete = 0;

      $session = Session::get('user');
      $user_id = $session['id'];
      

      // if(!empty($request->limit)) $limit = $request->limit;
      // if(!empty($request->status)) $status = $request->status;
      // if(!empty($request->order_by)) $order_by = $request->order_by;
      // if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;

      // echo $order_by;

        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['add_btn_url'] = route($this->arr_values['routename'].'add');
        $data['view_btn_url'] = route($this->arr_values['routename'].'view');

      $data_list = Wallet::where('user_id',$user_id)->where(['status' => $status])->orderBy('id',$order_by);
      

      $data_list = $data_list->latest()->paginate($limit);
      $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list','data'))->render();
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = ["list"=>$view,];
        return response()->json($result, $responseCode);
    }
}