<?php
namespace App\Http\Controllers\APi\User;


use App\Helper\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Helper\ImageManager;
use App\Models\Report;
use App\Models\MemberModel;
 
class EarningController extends Controller
{
     protected $arr_values = array(
        'table_name'=>'report',
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

      $type = $request->type;

      $limit = 12;
      $status = 1;
      $page = $request->page? $request->page : 0;
      $offset = 0;
      $search = '';      
      $order_by = "desc";
      $user_id = $this->user_id;

      $offset = $page * $limit;

      if(!empty($request->search)) $search = $request->search;
      

      // $data_list = DB::table($this->arr_values['table_name'])
      // ->where(["member_id"=>$user_id,]) 
      // ->where(['status' => $status,])
      // ->offset($offset)
      // ->limit($limit)
      // ->orderBy($this->arr_values['table_name'].'.id', $order_by);
      // if(!empty($type)) $data_list->where('type',$type);
      // $data_list = $data_list->get();




      $data_list = Report::where('member_id', $user_id)
        ->where('status', $status)
        ->orderBy('id', $order_by)
        ->offset($offset)
        ->limit($limit)
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
          $data_list = $data_list->get();






      $data_list->transform(function ($item) {          
          $item->amount = Helpers::price_formate($item->amount);
          $item->tds_amount = Helpers::price_formate($item->tds_amount);
          $item->wallet_amount = Helpers::price_formate($item->wallet_amount);
          $item->final_amount = Helpers::price_formate($item->final_amount);
          $item->add_date_time2 = date("d M, Y h:i A", strtotime($item->add_date_time));

          $type_text = '';
          if($item->type==1) $type_text = 'Direct Income';
          if($item->type==2) $type_text = 'Pair Income';
          if($item->type==3) $type_text = 'Downline Income';
          if($item->type==4) $type_text = 'Upline Income';
          if($item->type==5) $type_text = 'Rank Bonus Income';
          if($item->type==6) $type_text = 'Repurchase Income';
          $item->type_text = $type_text;

          return $item;
      });

      
      




        $totalEarning = DB::table("report")->where(["member_id"=>$user_id,])->sum('amount');
        $totalTds = DB::table('report')
        ->where('member_id', $user_id)
        ->select(DB::raw('SUM(amount * tds / 100) as total_tds'))
        ->value('total_tds');

        $totalWallet = DB::table('report')
        ->where('member_id', $user_id)
        ->select(DB::raw('SUM(amount * wallet / 100) as total_tds'))
        ->value('total_tds');

        $unPaid = DB::table('report')
            ->where('member_id', $user_id)
            ->where('payment', 0)
            ->select(
                DB::raw('SUM(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
            )
            ->value('final_amount');

        $paid = DB::table('report')
            ->where('member_id', $user_id)
            ->where('payment', 1)
            ->select(
                DB::raw('SUM(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
            )
            ->value('final_amount');

        $finalEarning = $totalEarning-($totalTds+$totalWallet);

      $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'return';
        $result['data'] = [
            "paid"=>Helpers::price_formate($paid),
            "unPaid"=>Helpers::price_formate($unPaid),
            "totalTds"=>Helpers::price_formate($totalTds),
            "totalWallet"=>Helpers::price_formate($totalWallet),
            "totalEarning"=>Helpers::price_formate($totalEarning),
            "finalEarning"=>Helpers::price_formate($finalEarning),
            "list"=>$data_list,
        ];
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