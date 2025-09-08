<?php

namespace App\Models;

use App\CPU\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Models\Package;
use App\Models\MLMTree;

class MemberModel extends Model {

	private $Member = 'member_log';


	public static function GetSponserData($id) 
	{  
		return DB::table("users")->select('id','parent_id','user_id','name','email','phone','sponser_id','image','package','is_paid')->where("user_id",$id)->first();
   	}
	public static function GetUserData($id) 
	{  
		return DB::table("users")->select('id','name','email','phone','sponser_id','image','package','user_id','package_name','kyc_step','is_paid')->where("id",$id)->first();
  }
 	public static function GetChildMemberById($parent_id) 
	{  
		return DB::table("member_log")
				->leftJoin('users','users.id','=','member_log.id')
				->select('users.id','users.name','member_log.parent_id','member_log.sponser_id','users.image','users.user_id','users.phone','users.package_name','users.kyc_step','users.is_paid','users.position')
				// ->limit(10)
				->orderBy('member_log.position','asc')
				->where("member_log.parent_id",$parent_id)
				->get();
  }


   	public static function getParentIdForHybridPlan($sponsorId, $side = null)
		{
				$parent_id =0;
		    $sponser = MemberModel::GetSponserData($sponsorId);
		    if(!empty($sponser)) $parent_id = $sponser->id;
		    $where['position'] = $side;
		    $where['parent_id'] = $parent_id;
		    $check = DB::table('users')->where($where)->first();
		    while($check)
		    {
		    	$parent_id = $check->id;
		    	$where['parent_id'] = $parent_id;
		    	$check = DB::table('users')->where($where)->first();
		    }
		    return $parent_id;
		}

		
		public static function getAllChildIds($parentId, &$childIds = [])
		{
		    // Fetch immediate children of the sponsor
		    $children = DB::table('member_log')
		        ->where('parent_id', $parentId)
		        ->pluck('id')
		        ->toArray();

		    // Add the children to the array
		    $childIds = array_merge($childIds, $children);

		    // Recursively fetch their children
		    foreach ($children as $childId) {
		        MemberModel::getAllChildIds($childId, $childIds);
		    }

		    return $childIds;
		}
	
		public static function get_sponser($user_id)
    {
      return DB::table("member_log")->where("id",$user_id)->first();
    }

    public static function new_sponser($user_id)
    {
      return DB::table("users")->select('id','name','user_id')->where("id",$user_id)->first();
    }
    public static function change_sponser($user_id,$sponser_id,$placement)
    {
        $active_sponser = MemberModel::get_sponser($user_id);
        $new_sponser = MemberModel::new_sponser($sponser_id);
        $new_sponser_user_id = $new_sponser->user_id;
        $parent_id = MemberModel::getParentIdForHybridPlan($new_sponser_user_id, $placement);
        DB::table("member_log")
          ->where(['id'=>$user_id,])
          ->update([
            "parent_id"=>$parent_id,
            "sponser_id"=>$new_sponser_user_id,
          ]);
          DB::table("users")
          ->where(['id'=>$user_id,])
          ->update([
            "sponser_id"=>$new_sponser->user_id,
            "parent_id"=>$parent_id,
            "sponser_name"=>$new_sponser->name,
          ]);
    }






	public static function AddMemberLog($data)
	{
		DB::table('member_log')->insert([
            "id"=>$data['id'],
            "user_id"=>$data['user_id'],
            "name"=>$data['name'],
            "parent_id"=>$data['parent_id'],
            "sponser_id"=>$data['sponser_id'],
            "position"=>$data['side'],
            "space_full"=>0,
        ]);
	}
	public static function GetUserId() 
	{
		return DB::table("users")
		->select('user_id')
		->orderBy('user_id','desc')
		->where('is_paid',1)
		->first()->user_id;
   	}
   	public static function update_user_id($user_id)
    {
    	$GetUserData = MemberModel::GetUserData($user_id);
    	if(empty($GetUserData->user_id))
    	{
	        $user_id = MemberModel::GetUserId();
	        DB::table("users")
	         ->where("id",$GetUserData->id)
	         ->update(["user_id"=>$user_id+1,]);

	        DB::table("member_log")
	         ->where("id",$GetUserData->id)
	         ->update(["user_id"=>$user_id+1,]);
    	}
    	DB::table("users")
	         ->where("id",$GetUserData->id)
	         ->update(["mail_date_time"=>date("Y-m-d H:i:s"),"activate_date_time"=>date("Y-m-d H:i:s"),"is_paid"=>1,]);
    }

    public static function totalTeam($id){
    	return count(MemberModel::getAllChildIds($id));
    }
    public static function totalDirect($id){
    	$GetUserData = MemberModel::GetUserData($id);
    	return DB::table('member_log')->where(["sponser_id"=>$GetUserData->user_id,])->count();
    }

    

		public static function calculatePairsForSponsor($id)
		{
		    // Get all descendants of the sponsor
		    $descendants = MemberModel::getAllChildIds($id);
		    // Fetch left and right counts for all descendants
		    $leftCount = DB::table('member_log')
		        ->whereIn('id', $descendants)
		        ->where('position', 1)
		        ->count();

		    $rightCount = DB::table('member_log')
		        ->whereIn('id', $descendants)
		        ->where('position', 2)
		        ->count();

		    // Calculate the number of pairs
		    $pairCount = min($leftCount, $rightCount);

		    return [
		        'left_count' => $leftCount,
		        'right_count' => $rightCount,
		        'pair_count' => $pairCount,
		    ];
		}
		public static function direct_income($user_id,$date_time='')
	  {
	      $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$gst = Setting::get()['gst'];
	  	$tds = Setting::get()['tds'];

	  	$get_user = MemberModel::GetUserData($user_id);
	  	$sponser_id = $get_user->sponser_id;

	  	$sponser_data = MemberModel::GetSponserData($sponser_id);


			if(!empty($get_user) && !empty($sponser_data))
			{	
				$package_id = $get_user->package;
        $package_name = '';
        $amount = 0;
        
        $packageData = DB::table('package')->where('id',$package_id)->first();
        if(!empty($packageData)) $amount = $packageData->income1;

        $gst_amount = $amount/100*$gst;
        $tds_amount = $amount/100*$tds;
        $final_amount = $amount-($gst_amount+$tds_amount);

        if(empty($date_time)) $report_date_time = date("Y-m-d H:i:s");
        else $report_date_time = $date_time;


        $orders = DB::table('orders')->where("user_id",$user_id)
        // ->where("status",0)
        ->orderBy('id','desc')
        ->first();
        $transaction_id = '';
        if(!empty($orders->transaction_id))
        {
        	$transaction_id = $orders->transaction_id;
        }
        $check_entr = DB::table('report')->where(["transaction_id"=>$transaction_id,"type"=>1,])->first();
        if(empty($check_entr))
        {
        	DB::table('report')->insert([
	                "member_id"=>$user_id,
	                "transaction_id"=>$transaction_id,
	                "user_id"=>$sponser_data->user_id,
	                "sponser_id"=>$sponser_id,
	                "amount"=>$amount,
	                "gst"=>$gst_amount,
	                "tds"=>$tds_amount,
	                "final_amount"=>$final_amount,
	                "currency"=>"INR",
	                "type"=>1,
	                "package_name"=>$package_name,
	                "package_id"=>$package_id,
	                "add_date_time"=>$report_date_time,
	                "package_payment_date_time"=>$report_date_time,
	                "status"=>1,
	                "payment"=>0,
	                "slug"=>$actual_link,
	                "only_date"=>date("Y-m-d", strtotime($report_date_time)),
	                "is_delete"=>0,
	            ]);
        		MemberModel::day_wise_income($sponser_data->id,$final_amount);
        		// MemberModel::set_all_time_earning($sponser_data->id,$final_amount);
        		// MemberModel::user_package_sale($sponser_data->id,$package_id);
        }
			}        
	  }
		





















   	public static function day_wise_income($user_id,$amount,$date_time='',$type='')
   	{
   		if(empty($date_time)) $wise_date = date("Y-m-d");
   		else $wise_date = date("Y-m-d", strtotime($date_time));


   		$user = MemberModel::GetUserData($user_id);
   		$affiliate_id = $user->user_id;
   		
   		
        $day_wise_earning_data = DB::table("report")
        ->select(DB::raw('SUM(final_amount) as final_amount'))
        ->where('only_date', $wise_date)
        ->where('status', 1)
        ->where(["user_id"=>$affiliate_id,])
        ->first();
        if(!empty($day_wise_earning_data->final_amount)) $amount = $day_wise_earning_data->final_amount;
        else $amount = 0;
	    

   		$check_amount_data = DB::table('day_wise_income')
   		->where('user_id',$user_id)
   		->where("date",$wise_date)
   		->first();


   		if(empty($check_amount_data))
   		{
   			DB::table('day_wise_income')->insert([
   				"user_id"=>$user_id,
   				"date"=>$wise_date,
   				"income"=>$amount,
   			]);
   		}
   		else
   		{
   			DB::table('day_wise_income')
   			->where('user_id',$user_id)
   			->where("date",$wise_date)
   			->update([
   				"user_id"=>$user_id,
   				"income"=>$amount,
   			]);
   		}

   		MemberModel::month_wise_income($user_id,$wise_date);
   		MemberModel::year_wise_income($user_id,$wise_date);
   	}

   	public static function month_wise_income($user_id,$date_time,$type='')
   	{
   		$month = date("m", strtotime($date_time));
   		$year = date("Y", strtotime($date_time));

   		$user = MemberModel::GetUserData($user_id);
   		$affiliate_id = $user->affiliate_id;
   		
   		
        $day_wise_earning_data = DB::table("day_wise_income")
        ->select(DB::raw('SUM(income) as income'))
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->where(["user_id"=>$user_id,])
        ->first();
        if(!empty($day_wise_earning_data->income)) $amount = $day_wise_earning_data->income;
        else $amount = 0;
	    


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
   				"income"=>$amount,
   			]);
   		}
   	}

   	public static function year_wise_income($user_id,$date_time,$type='')
   	{
   		$year = date("Y", strtotime($date_time));

   		$user = MemberModel::GetUserData($user_id);
   		$affiliate_id = $user->affiliate_id;
   		
   		
        $day_wise_earning_data = DB::table("month_wise_income")
        ->select(DB::raw('SUM(income) as income'))
        ->whereYear('year', $year)
        ->where(["user_id"=>$user_id,])
        ->first();
        if(!empty($day_wise_earning_data->income)) $amount = $day_wise_earning_data->income;
        else $amount = 0;
	    

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
   				"income"=>$amount,
   			]);
   		}
   	}



   	public static function user_package_sale($user_id,$package_id)
   	{
   		$check_amount_data = DB::table('user_package_sale')
   		->where('user_id',$user_id)
   		->where('package_id',$package_id)
   		->first();
   		if(empty($check_amount_data))
   		{
   			DB::table('user_package_sale')->insert([
   				"count"=>1,
   				"user_id"=>$user_id,
   				"package_id"=>$package_id,
   			]);
   		}
   		else
   		{
   			DB::table('user_package_sale')
   			->where('user_id',$user_id)
   			->where('package_id',$package_id)
   			->update([
   				"count"=>$check_amount_data->count+1,
   			]);
   		}
   	}

   	public static function create_order($user_id,$package,$sponser_id='',$order_type='',$promo_code='')
    {   
        $transaction_id = 'MT'.rand ( 10000 , 99999 ).rand ( 10000 , 99999 ).rand ( 1000 , 9999 ).rand ( 10 , 99 );
        if(empty($sponser_id)) $order_amount = $package->sale_price;
        else $order_amount = $package->referral_price;
        DB::table("orders")->where(["user_id"=>$user_id,"status"=>0,])->delete();
        if(empty($order_type)) $order_type = 1;

        $discount = 0;
        $from_date = date("Y-m-d");
        $promo = DB::table('coupon')->where(['code'=>$promo_code,'status'=>1,'is_delete'=>0,]);
        $promo = $promo->where('from_date','<=',$from_date);
        $promo = $promo->where('to_date','>=',$from_date);
        $promo = $promo->first();


        
        $order_id = time().$user_id;
        $date_time = date("Y-m-d H:i:s");
        $gst = 18;
        $gst_amount = $order_amount/100*$gst;
        $final_income = $order_amount-$gst_amount;


        if(!empty($promo))
        {
            $finalPrice = 0;
            if ($promo->type==1)
            {
                $discount = $promo->amount;
                $finalPrice = $order_amount - $promo->amount;
            }
            else
            {
                $discount = ($order_amount * ($promo->amount / 100));
                $finalPrice = $order_amount - ($order_amount * ($promo->amount / 100));
            }            
            if ($finalPrice > 0) {
                $order_amount = $finalPrice;
            }
        }

        $sponser_id = explode(sort_name,strtoupper($sponser_id));
        if(!empty($sponser_id[1])) $sponser_id = $sponser_id[1];
        else $sponser_id = 0;

        DB::table('orders')->insert([
            "order_id"=>$order_id,
            "transaction_id"=>$transaction_id,
            "order_type"=>$order_type,
            "product_id"=>$package->id,
            "product_name"=>$package->name,
            "user_id"=>$user_id,
            "sponser_id"=>$sponser_id,
            "product_qty"=>1,
            "amount"=>$final_income,
            "gst"=>$gst_amount,
            "tax_amount"=>$order_amount,
            "promo_code"=>$promo_code,
            "discount"=>$discount,
            "add_date_time"=>$date_time,
            "status"=>0,
        ]);
    }
   
   




 	public static function tree_view($id) 
	{
		
 		
 		$data['member'] = MemberModel::GetUserData($id);		
		$getChildMember = MemberModel::GetChildMemberById($id);	
		
 		$arr=[];
 		
		
		$total_net_sale_v=[];			
		foreach($getChildMember as $row){///level 2
			

			$level_3_loop=MemberModel::GetChildMemberById($row->id);			
			$arr_level_3=[];
			foreach($level_3_loop as $level_3){///level 3
				
				 
				
				$level_4_loop=MemberModel::GetChildMemberById($level_3->id);
				$arr_level_4=[];
				
				foreach($level_4_loop as $level_4){///level 4
					
					 
					
						$level_5_loop=MemberModel::GetChildMemberById($level_4->id);
						$arr_level_5=[];
						
						foreach($level_5_loop as $level_5){///level 5
						
						 
							
								$level_6_loop=MemberModel::GetChildMemberById($level_5->id);
								$arr_level_6=[];
								foreach($level_6_loop as $level_6){///level 6
								
								 
									
										$level_7_loop=MemberModel::GetChildMemberById($level_6->id);
										$arr_level_7=[];
										$arr_level_8=[];
										
										foreach($level_7_loop as $level_7){///level 7
										
 											
											
											
											// $arr_level_7[] = ['ibo' => $level_7['name'].' ['.$level_7['id'].']','bv'=>$NetSaleVolume8 ];
											$arr_level_7[] = ['member_detail'=>$level_7,'L_8' => $arr_level_8,];
										}//Level6
								//
 									// $arr_level_6[] = [ 'ibo' => $level_6['name'].' ['.$level_6['id'].']','bv'=>$NetSaleVolume7, 'children' => $arr_level_7];
 									$arr_level_6[] = ['member_detail'=>$level_6,'L_7' => $arr_level_7,];
								}//Level6
								
							// $arr_level_5[] = ['ibo' => $level_5['name'].' ['.$level_5['id'].']','bv'=>$NetSaleVolume6,'children' => $arr_level_6 ];
							$arr_level_5[] = ['member_detail'=>$level_5,'L_6' => $arr_level_6,];
						}//Level5
						
					// $arr_level_4[] = [ 'ibo' => $level_4['name'].' ['.$level_4['id'].']', 'bv'=>$NetSaleVolume5, 'children' => $arr_level_5];
					$arr_level_4[] = ['member_detail'=>$level_4,'L_5' => $arr_level_5,];
 				}//Level4
				
				// $arr_level_3[] = [ 'ibo' => $level_3['name'].' ['.$level_3['id'].']','bv'=>$NetSaleVolume4,'children' => $arr_level_4 ];
				$arr_level_3[] = ['member_detail'=>$level_3,'L_4' => $arr_level_4,];
				
				 
			}//Level3
			
 			
			$arr[] = ['member_detail'=>$row,'L_3' => $arr_level_3,];	
						 
		}//Level2
		

$data['memberlist'] = $arr;		
		return $data;
 		
 	} 



	public static function tree_view_old($id) 
	{		 
		$data['member'] = MemberModel::GetUserData($id);		
		$getChildMember = MemberModel::GetChildMemberById($id);		
 		$arr=[];
		foreach($getChildMember as $row){///level 2			
			$level_3_loop=MemberModel::GetChildMemberById($row->id);			
			$arr_level_3=[];		
			foreach($level_3_loop as $level_3){///level 3				
				$level_4_loop=MemberModel::GetChildMemberById($level_3->id);				
				$arr_level_4=[];
				$arr_level_5=[];		
				foreach($level_4_loop as $level_4){///level 4					
					$arr_level_4[] = ['member_detail'=>$level_4,'L_5' => $arr_level_5,];
 				}//Level4
				$arr_level_3[] = ['member_detail'=>$level_3,'L_4' => $arr_level_4,];				 
			}//Level3 			
			$arr[] = [ 
						'member_detail'=>$row,
						'L_3' => $arr_level_3,
 					 ];						 
		}//Level2		
 		$data['memberlist'] = $arr;		
		return $data;
	} 
	

	

	public static function team_income($user_id,$date_time='')
    {
        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
				// print_r($value);
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

				        $orders = DB::table('orders')->where("user_id",$user_id)
				        // ->where("status",0)
				        ->orderBy('id','desc')
				        ->first();
				        $transaction_id = '';
				        if(!empty($orders->transaction_id))
				        {
				        	$transaction_id = $orders->transaction_id;
				        }
				        $check_entr = DB::table('report')->where(["transaction_id"=>$transaction_id,"type"=>$type,])->first();
				        if(empty($check_entr))
				        {
							DB::table('report')->insert([
				                "user_id"=>$user_id,
				                "transaction_id"=>$transaction_id,
				                "affiliate_id"=>$sponser_data->affiliate_id,
				                "sponser_id"=>$sponser_id,
				                "amount"=>$amount,
				                "gst"=>$gst_amount,
				                "tds"=>$tds_amount,
				                "final_amount"=>$final_amount,
				                "currency"=>"INR",
				                "type"=>$type,
				                "package_name"=>$package_name,
				                "package_id"=>$package_id,
				                "add_date_time"=>$report_date_time,
				                "package_payment_date_time"=>$report_date_time,
				                "status"=>1,
				                "payment"=>0,
				                "slug"=>$actual_link,
				                "is_delete"=>0,
				                "only_date"=>date("Y-m-d", strtotime($report_date_time)),
				            ]);
				            MemberModel::day_wise_income($sponser_data->id,$final_amount);
	        				MemberModel::set_all_time_earning($sponser_data->id,$final_amount);
	        			}
			        }
		        }
			}
		}		       
    }

    public static function all_time_earning_devide($user_id,$amount)
   	{
   		$user = MemberModel::GetUserData($user_id);
   		DB::table('users')->where('id',$user_id)->update(["all_time_earning"=>$user->all_time_earning-$amount]);
   	}

    public static function day_wise_income_devide($user_id,$amount,$date_time='')
    {
    	if(empty($date_time)) $wise_date = date("Y-m-d");
   		else $wise_date = date("Y-m-d", strtotime($date_time));

   		$check_amount_data = DB::table('day_wise_income')
   		->where('user_id',$user_id)
   		->where("date",$wise_date)
   		->first();
   		if(!empty($check_amount_data))
   		{
   			DB::table('day_wise_income')
   			->where('user_id',$user_id)
   			->where("date",date('Y-m-d'))
   			->update([
   				"user_id"=>$user_id,
   				"income"=>$check_amount_data->income-$amount,
   			]);
   		}
    }


    public static function check_double_income($user_id)
    {
    	$orders = DB::table('orders')->where("user_id",$user_id)
        ->orderBy('id','desc')
        ->first();
        if(!empty($orders))
        {
        	$check_user_package = DB::table('user_package')->where("user_id",$user_id)->where("transaction_id",$orders->transaction_id)
	        ->orderBy('id','desc')
	        ->get();
	        if(count($check_user_package)>1)
	        {
	        	$check_user_package = $check_user_package[0];
	          DB::table('user_package')->where("id",$check_user_package->id)->delete();
	        }


	    	$report = DB::table('report')->where(["user_id"=>$user_id,"type"=>1,"transaction_id"=>$orders->transaction_id,])->get();
	    	if(count($report)>1)
	    	{
	    		$report = $report[0];
	    		$affiliate_id = $report->affiliate_id;
	    		$sponser_data = MemberModel::GetSponserData($affiliate_id);
	    		if(!empty($sponser_data))
	    		{
	    			MemberModel::day_wise_income_devide($sponser_data->id,$report->final_amount,$report->only_date);
	    			MemberModel::all_time_earning_devide($sponser_data->id,$report->final_amount);
	    			DB::table('report')->where("id",$report->id)->delete();
	    		}
	    	}



	    	$report = DB::table('report')->where(["user_id"=>$user_id,"type"=>2,"transaction_id"=>$orders->transaction_id,])->get();
	    	if(count($report)>1)
	    	{
	    		$report = $report[0];
	    		$affiliate_id = $report->affiliate_id;
	    		$sponser_data = MemberModel::GetSponserData($affiliate_id);
	    		if(!empty($sponser_data))
	    		{
	    			MemberModel::day_wise_income_devide($sponser_data->id,$report->final_amount,$report->only_date);
	    			MemberModel::all_time_earning_devide($sponser_data->id,$report->final_amount);
	    			DB::table('report')->where("id",$report->id)->delete();
	    		}
	    	}



	    	$report = DB::table('report')->where(["user_id"=>$user_id,"type"=>3,"transaction_id"=>$orders->transaction_id,])->get();
	    	if(count($report)>1)
	    	{
	    		$report = $report[0];
	    		$affiliate_id = $report->affiliate_id;
	    		$sponser_data = MemberModel::GetSponserData($affiliate_id);
	    		if(!empty($sponser_data))
	    		{
	    			MemberModel::day_wise_income_devide($sponser_data->id,$report->final_amount,$report->only_date);
	    			MemberModel::all_time_earning_devide($sponser_data->id,$report->final_amount);
	    			DB::table('report')->where("id",$report->id)->delete();
	    		}
	    	}



	    	$report = DB::table('report')->where(["user_id"=>$user_id,"type"=>4,"transaction_id"=>$orders->transaction_id,])->get();
	    	if(count($report)>1)
	    	{
	    		$report = $report[0];
	    		$affiliate_id = $report->affiliate_id;
	    		$sponser_data = MemberModel::GetSponserData($affiliate_id);
	    		if(!empty($sponser_data))
	    		{
	    			MemberModel::day_wise_income_devide($sponser_data->id,$report->final_amount,$report->only_date);
	    			MemberModel::all_time_earning_devide($sponser_data->id,$report->final_amount);
	    			DB::table('report')->where("id",$report->id)->delete();
	    		}
	    	}
        }
    }



 
    public static function update_order($user_id,$type='')
    {
        $date_time = date("Y-m-d H:i:s");
        $orders = DB::table('orders')->where("user_id",$user_id)->where("status",0)->first();
        DB::table("orders")
        ->where("user_id",$user_id)
        ->where("status",0)
        ->update(["status"=>1,"payment_date_time"=>$date_time,]);


        $package_id = $orders->product_id;
        Custom::insert_user_package($user_id,$package_id,$type);



        $ordersInvoice = DB::table('orders')->orderBy('id','desc')->where("user_id",$user_id)->first();
        $user = DB::table('users')
        ->select('name','email','affiliate_id','phone','address','state')
        ->where("id",$user_id)->first();
        $details = [
            'to'=>$user->email,
            'view'=>'mailtemplate.invoice',
            'subject'=>'Invoice!',
            'body' => [
                        "user"=>$user,
                        "orders"=>$ordersInvoice,
                      ],
        ];
        MailModel::invoice($details);



        $setting = \App\Models\Setting::get();
		$admin_registration_email = $setting['emails']->registration_email;
        $details = [
            'to'=>$admin_registration_email,
            'view'=>'mailtemplate.invoice',
            'subject'=>'Invoice!',
            'body' => [
                        "user"=>$user,
                        "orders"=>$ordersInvoice,
                      ],
        ];
        MailModel::invoice($details);




        $ordersInvoice = DB::table('orders')->where("user_id",$user_id)->first();
        $user = DB::table('users')
        ->select('name','email','affiliate_id','phone','address','state')
        ->where("id",$user_id)->first();
        $details = [
            'to'=>$user->email,
            'view'=>'mailtemplate.welcome-purchase',
            'subject'=>'Welcome to KnowledgeWave India!',
            'body' => [
                        "name"=>$user->name,
                      ],
        ];
        MailModel::welcome_purchase($details);


    }
    public static function update_report($user_id)
    {
        $date_time = date("Y-m-d H:i:s");
        DB::table("report")
        ->where("user_id",$user_id)
        ->where("status",0)
        ->update(["status"=>1,"package_payment_date_time"=>$date_time,]);
    }
    
   

   
    public static function income_mails($user_id)
    {
        $report = DB::table('report')
        ->where('mail_send',null)
        ->where('user_id',$user_id)->get();
        foreach ($report as $key => $value)
        {
            $customer = DB::table('users')->select('name','email','sponser_id')->where('id',$value->user_id)->first();
            $user = DB::table('users')->select('name','email','sponser_id')->where('affiliate_id',$value->affiliate_id)->first();
            $sponser = DB::table('users')->select('name','email','sponser_id')->where('affiliate_id',$value->sponser_id)->first();
            $details = [
                'to'=>$user->email,
                'view'=>'mailtemplate.income',
                'subject'=>' Congrats on Your Earnings!',
                'body' => [
                            "name"=>$user->name,
                            "amount"=>$value->final_amount,
                            "sponser_id"=>$value->sponser_id,
                            "sponser_name"=>$sponser->name,
                            "customer_name"=>$customer->name,
                          ]
            ];
            if($key==0) 
        	{
        		// $setting = \App\Models\Setting::get();
        		// $admin_registration_email = $setting['emails']->registration_email;
        		// $details['bcc'] = $admin_registration_email;
        	}
            
            MailModel::income($details);
            DB::table('report')->where('id',$value->id)->update(['mail_send'=>1,]);
        }
    }

    public static function invoice_mails($user_id)
    {
    	
        $report = DB::table('report')->where('user_id',$user_id)->get();
        foreach ($report as $key => $value)
        {
            $user = DB::table('users')
            ->select('name','email','sponser_id')
            ->where('affiliate_id',$value->affiliate_id)
            ->where('type',1)
            ->first();
            
            $details = [
                'to'=>$user->email,
                'view'=>'mailtemplate.income',
                'subject'=>' Congrats on Your Earnings!',
                'body' => [
                            "name"=>$user->name,
                            "amount"=>$value->final_amount,
                          ]
            ];
            MailModel::test($details);
        }
    }

  

 
    

 }