<?php
namespace App\Http\Controllers\User;


use App\Helper\Helpers;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use App\Models\Cart;
use App\Models\MemberModel;
 
class UserCart extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.cart.', 
        'title'=>'My Cart', 
        'table_name'=>'deposit_request',
        'page_title'=>'My Cart',
        "folder_name"=>user_view_folder.'/cart',
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
      $data_list = Cart::whereIn('status',[1]);

      $data_list = $data_list->latest()->paginate($limit);
      $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list'))->render();
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = ["list"=>$view,];
        return response()->json($result, $responseCode);
    }
   
    public function add(Request $request)
    {   
        $id = $request->id;
        $qty = $request->qty;
        $session = Session::get('user');
        $user_id = $session['id'];

        $product = DB::table("product")->where(["status"=>1,"id"=>$id,])->first();
        if(empty($product))
        {
            $action = 'add';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Wrong product!';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        $data['product_id'] = $product->id;
        $data['user_id'] = $user_id;
        $data['product_name'] = $product->name;
        $data['price'] = $product->sale_price;
        $data['qty'] = $qty;
        $data['add_by'] = $user_id;
        $data['status'] = 1;
        $data['add_date_time'] = date("Y-m-d H:i:s");
        $data['update_date_time'] = date("Y-m-d H:i:s");

        DB::table("cart")->where(["product_id"=>$id,"user_id"=>$user_id,])->delete();
        if($qty>0)
        {
            DB::table("cart")->insert($data);
        }

        $cartDetail = MemberModel::cartDetail($user_id);
        $action = 'view';
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Add Cart Success';
        $result['action'] = $action;
        $result['data'] = $cartDetail;
        return response()->json($result, $responseCode);
    }


   

   
    


}