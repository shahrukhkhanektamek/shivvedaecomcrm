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
use App\Models\Product;
use App\Models\MemberModel;
 
class UserProduct extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.product.', 
        'title'=>'Product', 
        'table_name'=>'deposit_request',
        'page_title'=>'Product',
        "folder_name"=>user_view_folder.'/product',
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
        $data['page_title'] = $this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $this->arr_values['folder_name'];
        $row = Helpers::get_user_user();
        $id = $row->id;
        return view($this->arr_values['folder_name'].'/index',compact('data','row'));
    }
    public function load_data(Request $request)
    {
      $limit = 20;
      $page = 1;
      $page1 = 1;
      $offset = 0;
      $status = 1;
      
      $order_by = "id desc";
      $is_delete = 0;
      
      $session = Session::get('user');
      $user_id = $session['id'];
        
      
      if(!empty($request->status)) $status = $request->status;

      $data_list = Product::whereIn('product.status',[1])
      ->leftJoin('cart as cart', function($join) use ($user_id) {
        $join->on('cart.product_id', '=', 'product.id')
             ->where('cart.user_id', '=', $user_id);
      })

      ->select("product.*",DB::raw("COALESCE(cart.qty, 0) as qty"));
      

      $data_list = $data_list->latest()->paginate($limit);

      $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list'))->render();

      $cartDetail = MemberModel::cartDetail($user_id);

        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = ["list"=>$view,"cartDetail"=>$cartDetail,];
        return response()->json($result, $responseCode);
    }
   
    public function pay(Request $request)
    {   
        
        $data['title'] = "".$this->arr_values['title']." Scan & Pay";
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';        
        $data['amount'] = $request->amount;
        $data['upi'] = "test@upi";
        return view($this->arr_values['folder_name'].'/pay',compact('data'));
    }

    public function pay_submit(Request $request)
    {    
        
        $min_deposit = json_decode(DB::table('setting')->where('name','main')->first()->data)->min_deposit;

        $amount = $request->amount;
        if($amount<$min_deposit)
        {
            $action = 'redirect';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Min Deposit '.Helpers::price_formate($min_deposit);
            $result['action'] = $action;
            $result['url'] = route($this->arr_values['routename'].'pay').'?amount='.$request->amount;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }


        $action = 'redirect';
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = $action;
        $result['url'] = route($this->arr_values['routename'].'pay').'?amount='.$request->amount;
        $result['data'] = [];
        return response()->json($result, $responseCode);
    
    }  
   
    

    public function update(Request $request)
    {

        $data = new Deposit;        

        $session = Session::get('user');
        $add_by = $session['id'];
        
        $min_deposit = json_decode(DB::table('setting')->where('name','main')->first()->data)->min_deposit;
        $amount = $request->amount;
        if($amount<$min_deposit)
        {
            $action = 'redirect';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Min Deposit '.Helpers::price_formate($min_deposit);
            $result['action'] = $action;
            $result['url'] = route($this->arr_values['routename'].'pay').'?amount='.$request->amount;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }


        $data->amount = $request->amount;
        $data->user_id = $add_by;
        $data->payment_mode = $request->payment_mode;
        $data->request_id = time().$add_by;
        
        
        $data->image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('image'));

        $data->add_by = $add_by;
        $data->is_delete = 0;
        if($data->save())
        {
            $action = 'redirect';
            // $action = 'edit';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['url'] = route($this->arr_values['routename'].'index');
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
    }
   
    


}