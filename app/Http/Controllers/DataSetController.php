<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Models\MemberModel;
use App\Models\MailModel;
use App\Models\Package;
use App\Helper\Helpers;
use App\Models\Setting;


class DataSetController extends Controller
{
    public function test_package(Request $request)
    {




        // $sponser_id = 24455;
        // MemberModel::direct_income($insert_id);
        // MemberModel::team_income($insert_id);
        // Package::package_upgrade_count(193128);
    }
    

    public static function set_activepackage()
    {


        // $orders = DB::table('orders')
        // // ->where('status',1)
        // // ->where('payment_date_time',null)
        // // ->limit(10)
        // ->get();

        // // print_r($orders);

        // foreach ($orders as $key => $value)
        // {
        //     $package = DB::table('package')->select('name')->where('id',$value->product_id)->first();
        //     // print_r($package);
        //     DB::table('orders')->where('id',$value->id)->update(["product_name"=>$package->name,]);
        // }




        // $user = DB::table('users')->select('id','package')->where('is_paid',1)
        // // ->limit(10)
        // ->get();

        // // print_r($user);

        // foreach ($user as $key => $value) {
        //     $package = DB::table('package')->where('id',$value->package)->first();

        //     $insert_data = array(
        //         "user_id"=>$value->id,
        //         "type"=>3,
        //         "amount"=>$package->sale_price,
        //         "tds"=>0,
        //         "gst"=>0,
        //         "final_amount"=>$package->sale_price,
        //         "package_name"=>$package->name,
        //         "package_id"=>$package->id,
        //         "currency"=>"INR",
        //         "status"=>1,
        //     );
        //     // DB::table('user_package')->insert($insert_data);
        //     // print_r($insert_data);
        // }

    }
    
    public static function revert_report()
    {
        $only_date = "2024-07-18";
        // $report = DB::table('report')->where('payment',0)
        // ->where('only_date',$only_date)
        // ->get();
        
        // foreach($report as $key=>$value)
        // {
        //     $all_time_earning = 0;
        //     $user = DB::table('users')->where('affiliate_id',$value->affiliate_id)
        //     ->first();
        //     if(!empty($user))
        //     {
        //         $user_id = $user->id;
        //     //     DB::table("users")->where('affiliate_id',$value->affiliate_id)->update(["all_time_earning"=>$user->all_time_earning-$value->final_amount,]);
        //     //     $check_amount_data = DB::table('day_wise_income')
        //      //  ->where('user_id',$user_id)
        //      //  ->where("date",$only_date)
        //      //  ->first();
        //      //  if(!empty($check_amount_data))
        //      //  {
        //      //      DB::table("day_wise_income")->where('user_id',$user->id)->where('date',$only_date)->update(["income"=>$check_amount_data->income-$value->final_amount,]);
        //      //  }
        //     }
            
        // }
        
        
        
        
        // $user_package = DB::table('user_package')
        // ->where('date_time','>',"2024-07-18 00:00:00")
        // // ->limit(2)
        // ->get();
        
        // foreach($user_package as $key=>$value)
        // {
        //     $all_time_earning = 0;
        //     $user = DB::table('users')->where('id',$value->user_id)->first();
        //     if(!empty($user))
        //     {
        //         $user_id = $user->id;
        //         $current_package = DB::table('user_package')->orderBy('id','desc')->where('date_time','<','2024-07-18 00:00:00')->where('user_id',$value->user_id)->first();
        //         if(!empty($current_package))
        //         {
        //             // DB::table("users")->where('id',$value->user_id)->update(["package"=>$current_package->package_id,"package_name"=>$current_package->package_name,]);
        //             // print_r($current_package);
        //         }
                
                
                
                
           
        //     }
            
        // }
        
        
        
        // print_r($user_package);
    }


    public static function set_member_log()
    {
        $users = DB::table('users')->where('add_date_time','>=',"2024-07-31 00:00:00")
        ->get();
        foreach ($users as $key => $value)
        {
            $check_exst = DB::table("member_log")
            ->where(["id"=>$value->id,])->first();

            if(empty($check_exst))
            {
                $sponser_data = MemberModel::GetSponserData($value->sponser_id);
                $data = [
                            "id"=>$value->id,
                            "name"=>$value->name,
                            "parent_id"=>$sponser_data->id,
                            "sponser_id"=>$sponser_data->id,
                            "side"=>0,
                            "space_full"=>0,
                        ];
                // print_r($data);
                // print_r($sponser_data);
                MemberModel::AddMemberLog($data);
            }
        }
    }

    public static function set_direct_team_incomes()
    {
        $users = DB::table('users')->where('add_date_time','>=',"2024-07-31 00:00:00")
        ->where('is_paid',1)
        ->get();

        $not_income = [];
        foreach ($users as $key => $value)
        {
            $check_inome = DB::table('report')->where('user_id',$value->id)->first();
            if(empty($check_inome))
            {
                $not_income[] = $value;
                $insert_id = $value->id;

                $orders = DB::table('orders')->where("user_id",$insert_id)->where("status",0)->first();

                // Package::package_sale_count($orders->product_id);
                // MemberModel::direct_income($insert_id);
                // MemberModel::team_income($insert_id);
                // MemberModel::update_affiliate_id($orders->user_id);
                // MemberModel::update_order($orders->user_id);
                // MemberModel::income_mails($insert_id);
                // print_r($not_income);
            }
        }
    }
    public static function set_team_income()
    {

        // $user_id = 40896;
        // $get_parent_ids = MemberModel::get_parent_ids($user_id);
        // print_r($get_parent_ids);


        // die;
        // SELECT * FROM `report` WHERE slug='https://knowledgewaveindia.com/set_team_income' AND type='2'
        // $report = DB::table('report')
        // ->where('type',2)
        // ->where("slug",'https://knowledgewaveindia.com/set_team_income')
        // ->get();

        // foreach ($report as $key => $value) {
        //     DB::table('member_log')->where('id',$value->user_id)->delete();
        // }
        // print_r($report);

        

        // die;



        $users = DB::table('users')->where('add_date_time','>=',"2024-07-31 00:00:00")
        ->where('is_paid',1)
        // ->where('id',$user_id)
        ->get();

        // foreach ($users as $key => $value) {
        //     $user_id = $value->id;
        //     MemberModel::team_income($user_id,'');
        // }

        die;

        $not_income = [];
        $user_ids = [];
        foreach ($users as $key => $value)
        {
            $user_id = $value->id;
            $gst = Setting::get()['gst'];
            $tds = Setting::get()['tds'];
            $get_user = MemberModel::GetUserData($user_id);
            $sponser_id = $get_user->sponser_id;

            $user_package = Package::get_package($get_user->package);
            $package_id = $get_user->package;
            $package_name = $user_package->name;    
            // $user_package_postion = $user_package->position;
            $income_amounts = [];
            $get_parent_ids = MemberModel::get_parent_ids($user_id);
            if(empty($date_time)) $report_date_time = date("Y-m-d H:i:s");
            else $report_date_time = $date_time;
            foreach ($get_parent_ids as $key => $value)
            {
                if($key>0)
                {
                    $affiliate_id = 0;
                    $amount = 0;
                    $sponser_data = MemberModel::GetUserData($value);
                    if(!empty($sponser_data))
                    {
                        $affiliate_id = $sponser_data->affiliate_id;
                        $sponser_package_id = $sponser_data->package;
                        $sponser_package = Package::get_package($sponser_data->package);
                        if(!empty($sponser_package))
                        {
                            if($key==1)
                            {
                                $type=2;
                                $amount = $sponser_package->lavel2;
                            }
                            if($key==2)
                            {
                                $type=3;
                                $amount = $sponser_package->lavel3;
                            }
                            if($key==3)
                            {
                                $type=4;
                                $amount = $sponser_package->lavel4;
                            }

                            $user_package_postion = $user_package->position;
                            $sponser_package_postion = $sponser_package->position;
                            if($user_package_postion<$sponser_package_postion)
                            {
                                if($key==1)
                                {
                                    $type=2;
                                    $amount = $user_package->lavel2;
                                }
                                if($key==2)
                                {
                                    $type=3;
                                    $amount = $user_package->lavel3;
                                }
                                if($key==3)
                                {
                                    $type=4;
                                    $amount = $user_package->lavel4;
                                }
                            }

                            $amount = $amount;
                            $gst_amount = $amount/100*$gst;
                            $tds_amount = $amount/100*$tds;
                            $final_amount = $amount-($gst_amount+$tds_amount);


                            $check_exst = DB::table("report")->where(["user_id"=>$user_id,"affiliate_id"=>$sponser_data->affiliate_id,"sponser_id"=>$sponser_id,])->first();

                            if(empty($check_exst))
                            {
                                // DB::table('report')->insert([
                                //     "user_id"=>$user_id,
                                //     "affiliate_id"=>$sponser_data->affiliate_id,
                                //     "sponser_id"=>$sponser_id,
                                //     "amount"=>$amount,
                                //     "gst"=>$gst_amount,
                                //     "tds"=>$tds_amount,
                                //     "final_amount"=>$final_amount,
                                //     "currency"=>"INR",
                                //     "type"=>$type,
                                //     "package_name"=>$package_name,
                                //     "package_id"=>$package_id,
                                //     "add_date_time"=>$report_date_time,
                                //     "package_payment_date_time"=>$report_date_time,
                                //     "status"=>1,
                                //     "payment"=>0,
                                //     "is_delete"=>0,
                                //     "only_date"=>date("Y-m-d"),
                                // ]);
                            }                                
                        }
                    }
                }
            }              
        }
    }

    public static function set_all_time_rearning()
    {
        $report = DB::table('report')
        // ->limit(11)
        ->select(DB::raw('SUM(report.final_amount) as final_amount'),'report.affiliate_id')
        ->groupBy('report.affiliate_id')
        ->get();



        die;
        foreach ($report as $key => $value) {
            DB::table('users')->where('affiliate_id',$value->affiliate_id)->update(["all_time_earning"=>$value->final_amount,]);
        }
    }

    public static function set_day_wiese_income()
    {
        $report = DB::table('report')
        ->select(DB::raw('SUM(report.final_amount) as final_amount'),'report.affiliate_id','report.only_date')
        ->groupBy('report.affiliate_id','report.only_date')
        ->get();

        die;
        foreach ($report as $key => $value)
        {
            $user = DB::table('users')->where('affiliate_id',$value->affiliate_id)->first();            
            DB::table('day_wise_income')->insert(["user_id"=>$user->id,"date"=>$value->only_date,"income"=>$value->final_amount,]);



            $user_id = $user->id;
            $amount = $value->final_amount;
            $month = date("m", strtotime($value->only_date));
            $year = date("Y", strtotime($value->only_date));

            $check_amount_data = DB::table('month_wise_income')
            ->where('user_id',$user_id)
            ->where("month",$month)
            ->where("year",$year)
            ->first();
            if(empty($check_amount_data))
            {
                DB::table('month_wise_income')->insert([
                    "user_id"=>$user_id,
                    "month"=>$month,
                    "year"=>$year,
                    "income"=>$amount,
                ]);
            }
            else
            {
                DB::table('month_wise_income')
                ->where('user_id',$user_id)
                ->where("month",$month)
                ->where("year",$year)
                ->update([
                    "user_id"=>$user_id,
                    // "income"=>$amount,
                    "income"=>$check_amount_data->income+$amount,
                ]);
            }





            $check_amount_data = DB::table('year_wise_income')
            ->where('user_id',$user_id)
            ->where("year",$year)
            ->first();
            if(empty($check_amount_data))
            {
                DB::table('year_wise_income')->insert([
                    "user_id"=>$user_id,
                    "year"=>$year,
                    "income"=>$amount,
                ]);
            }
            else
            {
                DB::table('year_wise_income')
                ->where('user_id',$user_id)
                ->where("year",$year)
                ->update([
                    "user_id"=>$user_id,
                    // "income"=>$amount,
                    "income"=>$check_amount_data->income+$amount,
                ]);
            }




        }
    }

    public static function user_package_sale()
    {
        $orders = DB::table('orders')
        // ->limit(10)
        ->where("status",1)->get();

        die;
        foreach ($orders as $key => $value)
        {
            $user = MemberModel::GetUserData($value->user_id);
            $sponser_data = MemberModel::GetSponserData($user->sponser_id);
            MemberModel::user_package_sale($sponser_data->id,$value->product_id);
            // print_r($sponser_data);
        }


    }






}















