<?php
namespace App\Http\Controllers\APi\User;


use App\Helper\Helpers;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use App\Models\Orders;
 
class MyOrder extends Controller
{

    protected $arr_values = array(
        'table_name'=>'orders',
       );  

        protected $user_id = null;
        public function __construct()
        {
            $authToken = request()->header('Authorization');
            $user = Helpers::decode_token($authToken);
            if ($user) {
                $this->user_id = $user->user_id;
            }
        }

    
    public function list(Request $request)
    {
      $user_id = $this->user_id;
      $limit = 12;
      $page = $request->page? $request->page : 0;
      $offset = 0;
      $search = '';      
      $order_by = "desc";

      $offset = $page * $limit;

      if(!empty($request->search)) $search = $request->search;
      $status = $request->status;
      
      $data_list = Orders::orderBy('id', $order_by)
        ->where('user_id', $user_id)
        ->offset($offset)
        ->limit($limit);

        if($status=='') $statusArr = [0,1,2,3,4,5,6,7,8];
          else if($status==0) $statusArr = [0];
          else if($status==1) $statusArr = [1];
          else if($status==2) $statusArr = [2];
          else if($status==3) $statusArr = [3];
          else if($status==4) $statusArr = [4];
        

        $data_list = $data_list->whereIn('status',$statusArr)->get();

        $data_list->transform(function ($item) {            

            $item->add_date_time1 = date("d M, Y h: A", strtotime($item->add_date_time));

            return $item;
        });


        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'return';
        $result['data'] = $data_list;
        return response()->json($result, $responseCode);
    }

    public function detail(Request $request)
    {
      $user_id = $this->user_id;
      $id = $request->id;
      
      $table_name = $this->arr_values['table_name'];
      $data_list = Orders::where($table_name.'.id', $id)

      ->leftJoin('states as states', 'states.id', '=', $table_name . '.state')
      ->select($this->arr_values['table_name'] . '.*','states.name as state_name');

        // $data_list->where('user_id', $user_id);
        
        $data_list = $data_list->first();
        $orderProducts = [];
        if(!empty($data_list))
        {
            $orderProducts = DB::table("order_products")->where(["order_id"=>$data_list->order_id,])->get();
        }

        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'return';
        $result['data'] = $data_list;
        $result['orderProducts'] = $orderProducts;
        return response()->json($result, $responseCode);
    }
   
    


}