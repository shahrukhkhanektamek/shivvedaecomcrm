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
use App\Models\Report;
use App\Models\MemberModel;
 
class UserLavel extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.lavel.', 
        'title'=>'Lavel', 
        'table_name'=>'report',
        'page_title'=>'Lavel',
        "folder_name"=>user_view_folder.'/lavel',
        "upload_path"=>'upload/',
        "page_name"=>'support-detail.php',
        "keys"=>'id,name',
        "all_image_column_names"=>array("image"),
       );  

      public function __construct()
      {
        Helpers::create_importent_columns($this->arr_values['table_name']);
      }

    public function index(Request $request, $level='')
    {
        $session = Session::get('user');
        $user_id = $session['id'];

        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $data['level'] = $level;

        return view($this->arr_values['folder_name'].'/index',compact('data'));
    }
    public function load_data(Request $request)
    {
      $limit = 12;
      $status = 1;
      $order_by = "desc";
      $is_delete = 0;

      $session = Session::get('user');
      $user_id = $session['id'];
      

      $type = $request->type;

      $data['table_name'] = $this->arr_values['table_name'];
      $data['upload_path'] = $this->arr_values['upload_path'];
      $data['back_btn'] = route($this->arr_values['routename'].'list');
      $data['route'] = route($this->arr_values['routename'].'list');

      
        $data_list = MemberModel::levelWiseIncome($user_id);
        if(!empty($request->level))
        {
            $data_list = $data_list[$request->level];
        }
        $data['level'] = $request->level;

        $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list','data'))->render();
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = ["list"=>$view,];
        return response()->json($result, $responseCode);
    }
    
    

}