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
use App\Models\Withdrawal;
use App\Models\MemberModel;
 
class UserWithdrawal extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.withdrawal.', 
        'title'=>'Withdrawal', 
        'table_name'=>'withdrawal_request',
        'page_title'=>'Withdrawal',
        "folder_name"=>user_view_folder.'/withdrawal',
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
        $data['page_title'] = "".$this->arr_values['page_title'];
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
      
      $order_by = "id desc";
      $is_delete = 0;
      
      $session = Session::get('user');
      $user_id = $session['id'];
        
      
      if(!empty($request->status)) $status = $request->status;

      $data_list = Withdrawal::where(["user_id"=>$user_id,])
      ->whereIn('status',[0,1,2,3,4]);
      

      $data_list = $data_list->latest()->paginate($limit);
      $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list'))->render();
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
    

    public function update(Request $request)
    {

        $data = new Withdrawal;        

        $session = Session::get('user');
        $add_by = $session['id'];
            
        $amount = $request->amount;


        $data->upi = $request->upi;
        $data->amount = $request->amount;
        $data->user_id = $add_by;
        $data->request_id = time().$add_by;



        $commision_wallet = MemberModel::getTypeAllIncome(Session::get('user')['id'])->commision_wallet;
        if($commision_wallet<$amount)
        {
            $action = 'add';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'You hav low balance!';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }

        $withdrawdeduct = json_decode(DB::table('setting')->where('name','main')->first()->data)->withdrawal_amount;
        $withdrawal_amount = $amount-($amount/100*$withdrawdeduct);
        $data->withdrawal_amount = $withdrawal_amount;        
        $data->charge = ($amount/100*10);
        // $data->image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('image'));

        $data->add_by = $add_by;
        $data->is_delete = 0;
        if($data->save())
        {
            $action = 'add';
            // $action = 'edit';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
    }
    

}