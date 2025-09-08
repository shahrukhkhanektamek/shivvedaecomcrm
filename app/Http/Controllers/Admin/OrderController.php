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
use App\Models\Orders;
use App\Models\User;
use App\Models\MemberModel;
use App\Models\MailModel;
 
class OrderController extends Controller
{
     protected $arr_values = array(
        'routename'=>'order.', 
        'title'=>'Orders', 
        'table_name'=>'orders',
        'page_title'=>'Orders',
        "folder_name"=>'/order',
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
      $status = '';
      $table_id = 1;
      $listcheckbox = [];
      $filter_search_value = '';
      $keys = '';
      $where_query = "";
      $order_by = "id desc";
      $is_delete = 0;
      

      if(!empty($request->limit)) $limit = $request->limit;
      if(isset($request->status)) $status = $request->status;
      if(!empty($request->order_by)) $order_by = $request->order_by;
      if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;



        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        

        if($status=='')
        {
            $status = [0,1,2,3,4,5,6,7];
        }
        else
        {
            $status = [$status];
        }


      $data_list = Orders::whereIn($this->arr_values['table_name'].'.status', $status)
      ->leftJoin("branch","branch.id","=",$this->arr_values['table_name'].".branch")
      ->select($this->arr_values['table_name'].".*","branch.name as branch_name");
      
      if(!empty($filter_search_value))
      {
        $filter_search_value = explode(" ", $filter_search_value);
        foreach ($filter_search_value as $key => $value)
        {
            $data_list = $data_list->where($this->arr_values['table_name'].'.name','LIKE',"%{$value}%");            
            $data_list = $data_list->whereOr($this->arr_values['table_name'].'.order_id','LIKE',"%{$value}%");            
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

        $row = Orders::where(["id"=>$id,])->first();
        if(!empty($row))
        {
            $course_category = DB::table("course_category")->where(["status"=>1,])->get();
            return view($this->arr_values['folder_name'].'/form',compact('data','row','course_category'));
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
        $data['page_title'] = "Edit ".$this->arr_values['page_title'];
        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';

        $row = Orders::where(["id"=>$id,])->first();
        if(!empty($row))
        {
            $user = DB::table("users")->where(["id"=>$row->user_id,])->orderBy('id','desc')->first();
            return view($this->arr_values['folder_name'].'/view',compact('data','row','user'));
        }
        else
        {
            return view('/404',compact('data'));            
        }
    }

    public function update(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        if(empty($id)) $data = new Orders;
        else $data = Orders::find($id);

        $date_time = date("Y-m-d H:i:s");
        $session = Session::get('admin');
        $add_by = $session['id'];

        $status = $request->status;
        $oldStatus = $data->status;
        $data->status = $request->status;

        $total_bv = $data->bv;

        $incomePlan = DB::table('income_plan')->first();
        $user = DB::table('users')->where('id', $data->user_id)->first();
        $count = DB::table('orders')->where('status',"!=",4)->where('user_id', $data->user_id)->count();
        $finalBv = $data->bv+$user->total_bv;
        $data->branch  = $request->branch;

        if($data->save())
        {
            // $total_bv = 0;
            // $orderProducts = DB::table("order_products")->where("order_id",$data->order_id)->get();
            // foreach ($orderProducts as $key => $value)
            // {
            //     $product = DB::table("product")->where("id",$value->product_id)->first();
            //     if(!empty($product))
            //     {
            //         $total_bv += $product->bv;
            //     }
            // }

            if($status==1 && $oldStatus!=1)
            {
                if($count==1 && $user->is_paid==0)
                {
                    if($finalBv>=$incomePlan->id_bv)
                    {
                        MemberModel::activeId($user->id);
                        $smsData = [
                            "number"=>$user->phone,
                            "type"=>"shoppingDetail",
                        ];
                        Helpers::sms($smsData);
                    }
                    DB::table("users")->where('id',$data->user_id )->update(["total_bv"=>DB::raw("total_bv + $total_bv")]);
                }
                else
                {
                    DB::table("users")->where('id',$data->user_id )->update(["total_rbv"=>DB::raw("total_rbv + $total_bv")]);
                    MemberModel::repurchase_income($data->user_id, $total_bv);
                }
                $order_products = DB::table('order_products')->where('order_id', $data->order_id)->get();
                $detail = [];
                foreach ($order_products as $key => $value) {
                    $detail[] = ["name"=>$value->name,"qty"=>$value->qty,"amount"=>$value->price,];
                }
                $transactionData = [
                    "user_id"=>$user->id,
                    "amount"=>$data->final_amount-$data->wallet_amount,
                    "type"=>1,
                    "detail"=>$detail,
                    "amount_detail"=>$data->amount_detail,
                ];
                MemberModel::createOrderTransaction($transactionData);

                $user_id = $user->id;
                $email = $user->email;
                $transaction = DB::table("transaction")->where(["user_id"=>$user_id,"status"=>1,])->orderBy('id','desc')->first();
                $user = DB::table("users")->where(["id"=>$user_id,])->first();
                $details = [
                    'to'=>$email,
                    'view'=>'mailtemplate.invoice',
                    'subject'=>'Invoice '.env('APP_NAME').'!',
                    'body' => ["detail"=>json_decode($transaction->detail),"user"=>$user,"transaction"=>$transaction,],
                ];
                MailModel::invoice($details);


                
                $data->process_date_time = $date_time;
                $data->save();

                


            }



            if($status==3 && $oldStatus!=3)
            {
                // DB::table("users")->where('id',$data->user_id )->update(["total_bv"=>DB::raw("total_bv + $total_bv")]);
                $data->delivered_date_time = $date_time;
                $data->save();
            }

            else if($status==4)
            {
                if($oldStatus==3 || $oldStatus==1)
                {
                    DB::table("users")->where('id',$data->user_id )->update(["total_bv"=>DB::raw("total_bv - $total_bv"),]);
                    $data->save();                    
                }
            }

            if($status==4 && $oldStatus!=4)
            {
                $repurchase_wallet_deduct = $data->wallet_amount;
                if($repurchase_wallet_deduct>0)
                {
                    MemberModel::repurchase_wallet_update($data->user_id,$repurchase_wallet_deduct,1);
                }
            }


            $action = 'edit';
            // $action = 'redirect';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['url'] = route("order.list");
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
    }

}