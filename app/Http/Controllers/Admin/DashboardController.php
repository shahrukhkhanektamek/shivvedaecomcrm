<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helper\Helpers;
use Custom_model;

 
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        if(empty($from_date) && empty($to_date))
        {
            // $from_date = date("Y-m-d")." 00:00:00";
            // $to_date = date("Y-m-d")." 23:59:00";            
        }
        else
        {
            $from_date .= " 00:00:00";
            $to_date .= " 23:59:00";
        }
        
        $all_time_income = 0;
        $all_time_data = DB::table("transaction")
        ->select(DB::raw('SUM(final_amount) as final_amount'))        
        ->where(["status"=>1,]);
        if(!empty($from_date) && !empty($to_date)) $all_time_data->whereBetween('payment_date_time', [$from_date, $to_date]);

        $all_time_data = $all_time_data->first();

        if(!empty($all_time_data->final_amount)) $all_time_income = $all_time_data->final_amount;
        else $all_time_income = 0;


        $all_time_gst = 0;
        $all_time_gst_data = DB::table("transaction")
        ->select(DB::raw('SUM(gst) as gst'))        
        ->where(["status"=>1,]); 
        if(!empty($from_date) && !empty($to_date)) $all_time_gst_data->whereBetween('payment_date_time', [$from_date, $to_date]);
        $all_time_gst_data = $all_time_gst_data->first();

        if(!empty($all_time_gst_data->gst)) $all_time_gst = $all_time_gst_data->gst;
        else $all_time_gst = 0;        
        $all_time_gst = (int) $all_time_gst;



        $total_paybele_amount = 0;
        $total_paybele_amount_data = DB::table("report")
        ->select(
            DB::raw('SUM(amount) as amount'),
            DB::raw('SUM(amount * tds / 100) as tds_amount'),
            DB::raw('SUM(amount * wallet / 100) as wallet_amount'),
            DB::raw('SUM(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
            )
        ->where(["status"=>1,"payment"=>0,])->first();
        if(!empty($total_paybele_amount_data->final_amount))
        {
            
            $total_paybele_amount = $total_paybele_amount_data->final_amount;
        } 
        else
        {
            $total_paybele_amount = (int) $total_paybele_amount;
        }





        $total_paybele_amount_data = DB::table("report")
        ->select(
            DB::raw('SUM(amount) as amount'),
            DB::raw('SUM(amount * tds / 100) as tds_amount'),
            DB::raw('SUM(amount * wallet / 100) as wallet_amount'),
            DB::raw('SUM(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
            )
        ->where(["status"=>1,])->first();
        if(!empty($total_paybele_amount_data->final_amount))
        {
            $all_users_earning = $total_paybele_amount_data->amount;
            $all_users_tds = $total_paybele_amount_data->tds_amount;
            $all_users_wallet = $total_paybele_amount_data->wallet_amount;
        } 
        else
        {
            $all_users_earning = 0;
            $all_users_tds = 0;
            $all_users_wallet = 0;
        }


        $total_paid_amount = 0;
        $total_paid_amount_data = DB::table("report")
        ->select(
            DB::raw('SUM(amount) as amount'),
            DB::raw('SUM(amount * tds / 100) as tds_amount'),
            DB::raw('SUM(amount * wallet / 100) as wallet_amount'),
            DB::raw('SUM(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
            )
        ->where(["status"=>1,"payment"=>1,]);
        if(!empty($from_date) && !empty($to_date)) $total_paid_amount_data->whereBetween('package_payment_date_time', [$from_date, $to_date]);

        $total_paid_amount_data = $total_paid_amount_data->first();

        if(!empty($total_paid_amount_data->final_amount)) $total_paid_amount = $total_paid_amount_data->final_amount;
        else $total_paid_amount = 0;        
        $total_paid_amount = (int) $total_paid_amount;


        $profit_loss = $all_time_income-($total_paid_amount+$all_time_gst+$all_users_tds+$all_users_wallet);



        $total_bv = 0;
        $total_bv_data = DB::table("users")
        ->select(
            DB::raw('SUM(total_bv) as total_bv')
            )
        ->first();
        if(!empty($total_bv_data->total_bv))
        {
            
            $total_bv = $total_bv_data->total_bv;
        } 
        else
        {
            $total_bv = (int) $total_bv;
        }


        $total_rbv = 0;
        $total_rbv_data = DB::table("users")
        ->select(
            DB::raw('SUM(total_rbv) as total_rbv')
            )
        ->first();
        if(!empty($total_rbv_data->total_rbv))
        {
            
            $total_rbv = $total_rbv_data->total_rbv;
        } 
        else
        {
            $total_rbv = (int) $total_rbv;
        }




        $all_time_income = floatval(str_replace(",", "", number_format($all_time_income,2)));
        $all_time_gst = floatval(str_replace(",", "", number_format($all_time_gst,2)));
        $profit_loss = floatval(str_replace(",", "", number_format($profit_loss,2)));
        $total_paid_amount = floatval(str_replace(",", "", number_format($total_paid_amount,2)));
        $total_paybele_amount = floatval(str_replace(",", "", number_format($total_paybele_amount,2)));
        $total_after_gst_amount = floatval(str_replace(",", "", number_format($all_time_income-$all_time_gst,2)));
        
        $all_users_earning = floatval(str_replace(",", "", number_format($all_users_earning,2)));
        $all_users_tds = floatval(str_replace(",", "", number_format($all_users_tds,2)));
        $all_users_wallet = floatval(str_replace(",", "", number_format($all_users_wallet,2)));

        
        return view('admin.dashboard.index',compact('all_time_income','all_time_gst','profit_loss','total_paid_amount','total_paybele_amount','total_after_gst_amount','all_users_earning','all_users_tds','all_users_wallet','from_date','to_date','total_bv','total_rbv'));
    }
    public function admin_earning_calendar(Request $request)
    {   
        $start = $request->start;
        $end = $request->end;
        $start_date = $start;
        $end_date = $end;
        $data = DB::table('transaction')
        ->select(
            DB::raw('CONCAT(YEAR(payment_date_time), "-", MONTH(payment_date_time), "-", DAY(payment_date_time)) as date'),
            DB::raw('SUM(final_amount) as total_amount')
        )
        ->whereBetween('transaction.payment_date_time', [$start_date, $end_date])
        ->where('status',1)
        ->groupBy('date')
        ->get();
        $events = [];
        foreach ($data as $key => $value) {
            $events[] = ["title"=>Helpers::price_formate($value->total_amount),"start"=>date("Y-m-d",strtotime($value->date)),];
        }        
        return response()->json(["events"=>$events], 200);
    }
}