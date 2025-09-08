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
use App\Models\Withdrawal;
 
class WithdrawalController extends Controller
{
     protected $arr_values = array(
        'routename'=>'withdrawal.', 
        'title'=>'Withdrawal', 
        'table_name'=>'withdrawal_request',
        'page_title'=>'Withdrawal',
        "folder_name"=>'/withdrawal',
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
      $order_by = "desc";
      $is_delete = 0;
      

      if(!empty($request->limit)) $limit = $request->limit;
      if(isset($request->status)) $status = $request->status;
      // if(!empty($request->order_by)) $order_by = $request->order_by;
      if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;



        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        



      $data_list = Withdrawal::where([$this->arr_values['table_name'].'.status' => $status])

      ->leftJoin("users","users.id","=",$this->arr_values['table_name'].".user_id")


        ->select($this->arr_values['table_name'].".*","users.name as user_name","users.phone as user_phone","users.user_id as uuser_id","users.email as user_email")

        ->orderBy($this->arr_values['table_name'].'.id',$order_by);
      
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
    

    public function update(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        $data = Withdrawal::find($id);

        $session = Session::get('admin');
        $add_by = $session['id'];
        
        $user_id = $data->user_id;
        
        if($data->status!=0)
        {
            $action = 'approveReject';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Action No Nedd!';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        

        $wallet = DB::table('wallet')->where('user_id',$user_id)->first();
        $commision_wallet = $wallet->commision_wallet;

        


        $data->status = $request->status;


        if($request->status==1)
        {

            if($commision_wallet<1)
            {
                $action = 'approveReject';
                $responseCode = 400;
                $result['status'] = $responseCode;
                $result['message'] = 'No balance!';
                $result['action'] = $action;
                $result['data'] = [];
                return response()->json($result, $responseCode);
            }


            $walletData = [
                "user_id"=>$data->user_id,
                "amount"=>$data->amount,
                "message"=>'Withdrawal Successfully',
                "type"=>2,
                "wallet_type"=>2, // 1=depoit,2=earning wallet
            ];
            Helpers::wallet_credir_debit($walletData);
        }
        
       

        if($data->save())
        {
            $action = 'approveReject';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
    }

}