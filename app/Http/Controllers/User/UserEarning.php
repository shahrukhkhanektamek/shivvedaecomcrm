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
 
class UserEarning extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.earning.', 
        'title'=>'Earning', 
        'table_name'=>'report',
        'page_title'=>'Earning',
        "folder_name"=>user_view_folder.'/earning',
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
      $session = Session::get('user');
      $user_id = $session['id'];
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';


        
        


        $totalEarning = DB::table("report")->where(["member_id"=>$user_id,])->sum('amount');
        $data['totalEarning'] = $totalEarning;

        $totalTds = DB::table('report')
        ->where('member_id', $user_id)
        ->select(DB::raw('SUM(amount * tds / 100) as total_tds'))
        ->value('total_tds');
        $data['totalTds'] = $totalTds;


        $totalWallet = DB::table('report')
        ->where('member_id', $user_id)
        ->select(DB::raw('SUM(amount * wallet / 100) as total_tds'))
        ->value('total_tds');
        $data['totalWallet'] = $totalWallet;



        $unPaid = DB::table('report')
            ->where('member_id', $user_id)
            ->where('payment', 0)
            ->select(
                DB::raw('SUM(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
            )
            ->value('final_amount');
        $data['unPaid'] = $unPaid;



        $paid = DB::table('report')
            ->where('member_id', $user_id)
            ->where('payment', 1)
            ->select(
                DB::raw('SUM(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
            )
            ->value('final_amount');
        $data['paid'] = $paid;




        $data['finalEarning'] = $totalEarning-($totalTds+$totalWallet);









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

      $data_list = Report::where('member_id', $user_id)
    ->where('status', $status)
    ->orderBy('id', $order_by)
    ->select(
        'id',
        'amount',
        'tds',
        'wallet',
        'add_date_time',
        'type',
        DB::raw('(amount * tds / 100) as tds_amount'),
        DB::raw('(amount * wallet / 100) as wallet_amount'),
        DB::raw('(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
    );


      if(!empty($request->from_date) && !empty($request->to_date))
      {
          $from_date = $request->from_date." 00:00:00";
          $to_date = $request->to_date." 23:59:00";
          $data_list->whereBetween('report.package_payment_date_time', [$from_date, $to_date]);
      }

      if(!empty($type)) $data_list->where('type',$type);


      $data_list = $data_list->latest()->paginate($limit);


      // $data_list->getCollection()->transform(function ($item) {
      //     $tds = $item->amount * 0.05;
      //     $item->tds_amount = $tds;
      //     $item->final_amount = $item->final_amount - $tds;
      //     return $item;
      // });



      $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list','data'))->render();
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = ["list"=>$view,];
        return response()->json($result, $responseCode);
    }
    public function transfer(Request $request)
    {
        $limit = 12;
        $status = 1;
        $order_by = "desc";
        $is_delete = 0;

        $session = Session::get('user');
        $user_id = $session['id'];
      

        $unpaid = MemberModel::payoutAmount($user_id,0);

        if($unpaid<1)
        {
          $responseCode = 400;
          $result['status'] = $responseCode;
          $result['message'] = 'You have no payout!';
          $result['action'] = 'reload';
          $result['data'] = [];
          return response()->json($result, $responseCode);
        }

        $date_time = date("Y-m-d H:i:s");

        $walletData = [
            "user_id"=>$user_id,
            "amount"=>$unpaid,
            "message"=>'Add amount from payout by self',
            "type"=>1,
            "wallet_type"=>2, // 1=depoit,2=earning wallet,3=deduct both
        ];
        Helpers::wallet_credir_debit($walletData);

        DB::table('report')->where(["member_id"=>$user_id,"payment"=>0,])->update(["payment"=>1,"payout_date_time"=>$date_time,]);


        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'edit';
        $result['data'] = [];
        return response()->json($result, $responseCode);
    }
    

}