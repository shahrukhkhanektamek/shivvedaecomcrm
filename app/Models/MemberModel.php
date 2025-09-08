<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Models\Package;
use App\Models\MLMTree;
use App\Helper\Helpers;

class MemberModel extends Model {

	private $Member = 'member_log';


	public static function GetSponserData($id) 
	{  
		return DB::table("users")->select('id','parent_id','user_id','name','email','phone','sponser_id','image','package','is_paid','total_pairs','rank')->where("user_id",$id)->first();
   	}
	public static function GetUserData($id) 
	{  
		return DB::table("users")
			->leftJoin('wallet','wallet.user_id','=','users.id')
			->select('users.id','users.name','users.parent_id','users.sponser_id','users.image','users.user_id','users.phone','users.package_name','users.kyc_step','users.is_paid','users.position','users.rank','users.total_left_unpaid','users.total_left_paid','users.total_right_unpaid','users.total_right_paid','users.email',
					'users.total_pairs as total_pairs',
					'users.left_bv as left_bv',
					'users.right_bv as right_bv',
					'wallet.income1 as income1',
					'wallet.income2 as income2',
					'wallet.income3 as income3',
					'wallet.income4 as income4',
					'wallet.income5 as income5',
				)
		->where("users.id",$id)->first();
    }
 	public static function GetChildMemberById($parent_id) 
	{  
		return DB::table("member_log")
				->leftJoin('users','users.id','=','member_log.id')
				->leftJoin('wallet','wallet.user_id','=','member_log.id')
				->select('users.id','users.name','member_log.parent_id','member_log.sponser_id','users.image','users.user_id','users.phone','users.package_name','users.kyc_step','users.is_paid','users.position','users.rank','users.total_left_unpaid','users.total_left_paid','users.total_right_unpaid','users.total_right_paid',
					'users.total_pairs as total_pairs',
					'users.left_bv as left_bv',
					'users.right_bv as right_bv',
					'wallet.income1 as income1',
					'wallet.income2 as income2',
					'wallet.income3 as income3',
					'wallet.income4 as income4',
					'wallet.income5 as income5',
			)
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


  public static function allParentDirectIds($user_id)
	{
			$sponser_id =0;
			$ids =[];
	    $user = MemberModel::GetUserData($user_id,$is_paid='');
	    if(!empty($user))
	    {
	    	$sponser = MemberModel::GetSponserData($user->sponser_id);
		    $sponser_id = $user->sponser_id;
		    $where['user_id'] = $sponser_id;
		    if($is_paid==1) $where['is_paid'] = 1;
		    $check = DB::table('users')->where($where)->first();
		    while($check)
		    {
		      $ids[] = $check->id;
		    	$sponser = MemberModel::GetSponserData($user->sponser_id);
		    	$sponser_id = $check->sponser_id;
		    	$where['user_id'] = $sponser_id;
		    	$check = DB::table('users')->where($where)->first();
		    }
	    }
	    return $ids;
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
	

	public static function allDirectIds($user_id,$is_paid='')
	{
			$allIds = [];
			$get_user = MemberModel::GetUserData($user_id);
			$sponser_id = $get_user->user_id;
			$childs = DB::table('member_log')
			->where('sponser_id',$sponser_id);
			if($is_paid==1) $childs->where("is_paid", 1);
			$childs = $childs->get();
			foreach ($childs as $key => $value)
			{
				$allIds[] = $value->id;
			}
	    return $allIds;
	}


		public static function levelWiseIncome($user_id) {
			$setNewLevel = [];
	    $getDownlineLevels = self::getDownlineLevels($user_id,$user_id);
	    $i=1;
	    foreach ($getDownlineLevels as $key => $value) {
	    	$usersIds = [];
	    	$usersArray = $value;
	    	foreach ($usersArray as $key2 => $value2) {
	    		$usersIds[] = $value2['id'];
	    	}

	    	$usersRbv = DB::table("users")->whereIn('id', $usersIds)->sum('total_rbv');

	    	$final = DB::table("report")
		    ->select(
		        DB::raw('SUM(amount) as amount'),
		        DB::raw('SUM(amount * tds / 100) as tds_amount'),
		        DB::raw('SUM(amount * wallet / 100) as wallet_amount'),
		        DB::raw('SUM(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
		    )
		    ->where(['member_id' => $user_id, 'type' => 6])
		    ->whereIn('user_id', $usersIds)
		    ->first();




	    	$value['memberIds'] = $usersIds;
	    	$value['total_rbv'] = $usersRbv;
	    	$value['totalMembers'] = count($usersIds);
	    	$value['final_amount'] = $final->final_amount ?? 0;
	    	$value['amount'] = $final->amount ?? 0;
	    	$value['tds_amount'] = $final->tds_amount ?? 0;
	    	$value['wallet_amount'] = $final->wallet_amount ?? 0;

	    	$setNewLevel[$i] = $value;
	    	$i++;
	    }
	    return $setNewLevel;
		}
		public static function getDownlineLevels($fix_id, $user_id, $level = 1, &$levels = [])
		{
				$userTemp = MemberModel::GetUserData($user_id);
		    $users = DB::table("users")->where("sponser_id", $userTemp->user_id)->get();
		    foreach ($users as $user) {

		    		$final = DB::table("report")
				    ->select(
				        DB::raw('SUM(amount) as amount'),
				        DB::raw('SUM(amount * tds / 100) as tds_amount'),
				        DB::raw('SUM(amount * wallet / 100) as wallet_amount'),
				        DB::raw('SUM(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
				    )
				    ->where([
				        'member_id' => $fix_id,
				        'type' => 6,
				        'user_id' => $user->id
				    ])
				    ->first();

		        $levels[$level][] = [
		            'id' => $user->id,
		            'name' => $user->name,
		            'sponser_id' => $user->sponser_id,
		            'member_id' => $user->user_id,
		            'rbv' => DB::table("users")->where('id', $user->id)->sum('total_rbv'),
		            'final_amount' => $final->final_amount ?? 0,
		            'amount' => $final->amount ?? 0,
		            'tds_amount' => $final->tds_amount ?? 0,
		            'wallet_amount' => $final->wallet_amount ?? 0,
		        ];
		        self::getDownlineLevels($fix_id, $user->id, $level + 1, $levels);
		    }
		    return $levels;
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
            "create_date"=>date("Y-m-d"),
        ]);
	}
	public static function GetUserId() 
	{
		return DB::table("users")
		->select('user_id')
		->orderBy('user_id','desc')
		// ->where('is_paid',1)
		->first()->user_id;
   	}
   	public static function activeId($user_id)
    {
    	$incomePlan = DB::table('income_plan')->first();

    	$GetUserData = MemberModel::GetUserData($user_id);
    	if($incomePlan->type==1)
    	{
    		$package = DB::table('package')->where('id',$GetUserData->package)->first();
    		MemberModel::insert_user_package($user_id,$GetUserData->package,1);
    	}
    	if(empty($GetUserData->user_id))
    	{
	        $user_id = MemberModel::GetUserId();
	        DB::table("users")
	         ->where("id",$GetUserData->id)
	         ->update(["user_id"=>$user_id+1,]);

	        DB::table("member_log")
	         ->where("id",$GetUserData->id)
	         ->update(["user_id"=>$user_id+1,"is_paid"=>1,]);
    	}
    	DB::table("users")
	         ->where("id",$GetUserData->id)
	         ->update(["mail_date_time"=>date("Y-m-d H:i:s"),"activate_date_time"=>date("Y-m-d H:i:s"),"is_paid"=>1,]);
	    DB::table("member_log")
	         ->where("id",$GetUserData->id)
	         ->update(["is_paid"=>1,]);
    }


    public static function insert_user_package($user_id,$package_id,$type='')
    {
        $package = DB::table("package")->where("id",$package_id)->first();
        DB::table("user_package")->insert([
          "user_id"=>$user_id,
          "type"=>$type,
          "amount"=>$package->sale_price,
          "package_name"=>$package->name,
          "package_id"=>$package_id,
          "date_time"=>date("Y-m-d H:i:s"),
          "status"=>1,
        ]);
    }
    public static function active_package($user_id)
    {
        return DB::table("user_package")->limit(1)->orderBy("id","desc")->where(["user_id"=>$user_id,])->first();
    }

    public static function rank($value)
    {
    	$rankHtml = 'Not Upgrade';
    	if($value==1) $rankHtml = 'Sr. Executive';
    	if($value==2) $rankHtml = 'Star Executive';
    	if($value==3) $rankHtml = 'Super Star Executive';
    	if($value==4) $rankHtml = 'Silver Executive';
    	if($value==5) $rankHtml = 'Gold Executive';
    	if($value==6) $rankHtml = 'Super Gold Executive';
    	if($value==7) $rankHtml = 'Daimond Executive';
    	if($value==8) $rankHtml = 'Super Daimond Executive';
    	if($value==9) $rankHtml = 'Saphire Daimond Executive';
    	if($value==10) $rankHtml = 'Crown Daimond Executive';


    	return $rankHtml;
        
    }


    public static function totalTeam($id){
    	return count(MemberModel::getAllChildIds($id));
    }
    public static function totalDirect($id,$is_paid=0){
    	$GetUserData = MemberModel::GetUserData($id);
    	if($is_paid==1) return DB::table('member_log')->where(["sponser_id"=>$GetUserData->user_id,"is_paid"=>1,])->count();
    	else if($is_paid==2) return DB::table('member_log')->where(["sponser_id"=>$GetUserData->user_id,"is_paid"=>0,])->count();
    	else return DB::table('member_log')->where(["sponser_id"=>$GetUserData->user_id,])->count();
    }
	public static function calculatePairsForSponsor($id)
	{
			$leftMemberId = 0;
			$rightMemberId = 0;
			$leftdescendants = [];
			$rightdescendants = [];
	    // Get all descendants of the sponsor
	    // $descendants = MemberModel::getAllChildIds($id);
			$incomePlan = DB::table('income_plan')->first();
	    $leftMember = DB::table('member_log')->where(['parent_id'=>$id,'position'=>1,])->first();
	    $rightMember = DB::table('member_log')->where(['parent_id'=>$id,'position'=>2,])->first();
	    if(!empty($leftMember)) $leftMemberId = $leftMember->id;
	    if(!empty($rightMember)) $rightMemberId = $rightMember->id;

	    if(!empty($leftMemberId)) $leftdescendants = array_merge([$leftMemberId],MemberModel::getAllChildIds($leftMemberId));
	    if(!empty($rightMemberId)) $rightdescendants = array_merge([$rightMemberId],MemberModel::getAllChildIds($rightMemberId));

	    $descendants = array_merge($leftdescendants, $rightdescendants);

	    // print_r($descendants);
	    

	    $leftCount = DB::table('member_log')
	        ->whereIn('id', $leftdescendants);
	        // ->where('position', 1);
	    $leftCount = $leftCount->count();

	    $rightCount = DB::table('member_log')
	        ->whereIn('id', $rightdescendants);
	        // ->where('position', 2);
	   	$rightCount = $rightCount->count();



	   	$leftPaidCount = DB::table('member_log')
	        ->whereIn('id', $leftdescendants)
	        // ->where('position', 1);
	        ->where('is_paid', 1);
	    $leftPaidCount = $leftPaidCount->count();

	    $rightPaidCount = DB::table('member_log')
	        ->whereIn('id', $rightdescendants)
	        // ->where('position', 2);
	        ->where('is_paid', 1);
	   	$rightPaidCount = $rightPaidCount->count();

	   	$leftBv = DB::table('users')->whereIn('id', $leftdescendants)->sum('total_bv');
	   	$rightBv = DB::table('users')->whereIn('id', $rightdescendants)->sum('total_bv');


	   	if($incomePlan->type==1)
	   	{
		    // Calculate the number of pairs
		    // $pairCount = min($leftPaidCount, $rightPaidCount);
		    $leftGreenIds = $leftPaidCount;   // Example green IDs on left
				$rightGreenIds = $rightPaidCount;  // Example green IDs on right

				$totalPairs = 0;

				// First 2:1 pair
				if ($leftGreenIds >= 2 && $rightGreenIds >= 1) {
				    // $leftGreenIds -= 2;
				    // $rightGreenIds -= 1;
				    // $totalPairs += 1;

				    $totalPairs = min($leftGreen, $rightGreen);
				    
				} elseif ($leftGreenIds >= 1 && $rightGreenIds >= 2) {
				    // $leftGreenIds -= 1;
				    // $rightGreenIds -= 2;
				    // $totalPairs += 1;

				    $totalPairs = min($leftGreen, $rightGreen);
				}

				// Remaining 1:1 pairs
				// $pairs1to1 = min($leftGreenIds, $rightGreenIds);
				// $totalPairs += $pairs1to1;

				// $leftGreenIds -= $pairs1to1;
				// $rightGreenIds -= $pairs1to1;

				$pairCount = $totalPairs;

	   	}
	   	else
	   	{
	   		// $leftBv = 10000;   // Example left BV
				// $rightBv = 10000;  // Example right BV

				
				$leftGreen = floor($leftBv / 5000);
				$rightGreen = floor($rightBv / 5000);

				$totalPairs = 0;			
				if ($leftGreen >= 2 && $rightGreen >= 1) {
				    // $leftGreen -= 2;
				    // $rightGreen -= 1;
				    // $totalPairs += 1;
						
						$totalPairs = min($leftGreen, $rightGreen);

				} elseif ($leftGreen >= 1 && $rightGreen >= 2) {
				    // $leftGreen -= 1;
				    // $rightGreen -= 2;
				    // $totalPairs += 1;

						$totalPairs = min($leftGreen, $rightGreen);

				}

				// $pairs1to1 = min($leftGreen, $rightGreen);
				// $totalPairs += $pairs1to1;

				// $leftGreen -= $pairs1to1;
				// $rightGreen -= $pairs1to1;

				$pairCount = $totalPairs;
	   	}

	    $bvCount = min($leftBv, $rightBv);


	   
	    $allDirectIds = MemberModel::allDirectIds($id,0);
	    $todaySelfBv = DB::table('orders')->where(["user_id"=>$id,"status"=>3,])->whereDate("process_date_time", date("Y-m-d"))->sum('bv');
	    $todayDirectBv = DB::table('orders')->whereIn("user_id", $allDirectIds)->where(["status"=>3,])->whereDate("process_date_time", date("Y-m-d"))->sum('bv');


	    // if($leftPaidCount!=$rightPaidCount)
	    // if($pairCount>0) $pairCount -=1;

	    return [
	        'left_count' => $leftCount,
	        'right_count' => $rightCount,
	        'left_paid_count' => $leftPaidCount,
	        'right_paid_count' => $rightPaidCount,
	        'left_unpaid_count' => $leftCount-$leftPaidCount,
	        'right_unpaid_count' => $rightCount-$rightPaidCount,
	        'pair_count' => $pairCount,
	        'bv_count' => $bvCount,
	        'descendants' => $descendants,
	        'leftdescendants' => $leftdescendants,
	        'rightdescendants' => $rightdescendants,
	        'leftBv' => $leftBv,
	        'rightBv' => $rightBv,
	        'myBv' => DB::table('users')->where('id', $id)->first()->total_bv,
	        'todaySelfBv' => $todaySelfBv,
	        'todayDirectBv' => $todayDirectBv,
	    ];
	}

	public static function set_pairs_data($user_id,$date_time='')
	{
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	    $date = date("Y-m-d");
	    $incomePlan = DB::table('income_plan')->first();

	    $check_old_pairs = $user = DB::table('users')->where('id',$user_id)->first();

	    $pairsData = MemberModel::calculatePairsForSponsor($user_id);
	    $left_count = $pairsData['left_count'];
	    $right_count = $pairsData['right_count'];
	    $left_paid_count = $pairsData['left_paid_count'];
	    $right_paid_count = $pairsData['right_paid_count'];
	    $descendants = $pairsData['descendants'];
	    $leftdescendants = $pairsData['leftdescendants'];
	    $rightdescendants = $pairsData['rightdescendants'];

	    $left_unpaid_count = $pairsData['left_unpaid_count'];
	    $right_unpaid_count = $pairsData['right_unpaid_count'];
	    $pairs = $pairsData['pair_count'];
	    $bvs = $pairsData['bv_count'];
	    $leftBv = $pairsData['leftBv'];
	    $rightBv = $pairsData['rightBv'];
	    $todaySelfBv = $pairsData['todaySelfBv'];
	    $todayDirectBv = $pairsData['todayDirectBv'];

	    $pairsForRank = 0;

	    if($incomePlan->type==1)
	    {
		    if($left_paid_count>1 || $right_paid_count>1)
		    {
		    	if($left_paid_count>0 && $right_paid_count>0)
		    		$pairsForRank = 1;
		    }	    	
	    }
	    else
	    {
		    if($leftBv>=$incomePlan->id_bv || $rightBv>=$incomePlan->id_bv)
		    {
		    	if($leftBv>=$incomePlan->id_bv && $rightBv>=$incomePlan->id_bv)
		    		$pairsForRank = 1;
		    }
		    $pairsForRank = $pairs;
	    }


	    

	    $totalDirect = MemberModel::totalDirect($user_id,1);
	    
	    $totalTeam = ($left_count+$right_count);
	    DB::table('users')->where('id',$user_id)->update([
	    		"total_pairs"=>$pairs,
	    		"total_bvs"=>$bvs,
	    		"total_left_unpaid"=>$left_unpaid_count,
	    		"total_right_unpaid"=>$right_unpaid_count,
	    		"total_left_paid"=>$left_paid_count,
	    		"total_right_paid"=>$right_paid_count,
	    		"total_team"=>$totalTeam,
	    		"total_left_pair"=>$left_paid_count,
	    		"total_right_pair"=>$right_paid_count,
	    		"total_direct"=>$totalDirect,
	    		"left_bv"=>$leftBv,
	    		"right_bv"=>$rightBv,
	    	]);

	    	$day_wise_pair_rank_data = [
	    		"date"=>$date,
	    		"user_id"=>$user_id,
	    	];


	    	$check_day_wise_pair_rank_data = DB::table('day_wise_pair_rank')->where(['user_id'=>$user_id,"date"=>$date,])->first();
	    	
	    		

	    		if(empty($check_day_wise_pair_rank_data))
		    	{	
    				if($check_old_pairs->total_team<=$totalTeam)
    					$day_wise_pair_rank_data["total_team"] = $totalTeam-$check_old_pairs->total_team;
    				else
    					$day_wise_pair_rank_data["total_team"] = $totalTeam;
    			}
    			else
    			{
	  				if($check_old_pairs->total_team<$totalTeam)
    					$day_wise_pair_rank_data["total_team"] = $check_day_wise_pair_rank_data->total_team+($totalTeam-$check_old_pairs->total_team);
    			}
    			/**/


    			if(empty($check_day_wise_pair_rank_data))
		    	{	
    				if($check_old_pairs->total_pairs<=$pairs)
    					$day_wise_pair_rank_data["total_pairs"] = $pairs-$check_old_pairs->total_pairs;
    				else
    					$day_wise_pair_rank_data["total_pairs"] = $pairs;
    			}
    			else
    			{
	  				if($check_old_pairs->total_pairs<$pairs)
    					$day_wise_pair_rank_data["total_pairs"] = $check_day_wise_pair_rank_data->total_pairs+($pairs-$check_old_pairs->total_pairs);
    			}
    			/**/   	


    			if(empty($check_day_wise_pair_rank_data))
		    	{	
    				if($check_old_pairs->total_bvs<=$bvs)
    					$day_wise_pair_rank_data["total_bvs"] = $bvs-$check_old_pairs->total_bvs;
    				else
    					$day_wise_pair_rank_data["total_bvs"] = $bvs;
    			}
    			else
    			{
	  				if($check_old_pairs->total_bvs<$bvs)
    					$day_wise_pair_rank_data["total_bvs"] = $check_day_wise_pair_rank_data->total_bvs+($bvs-$check_old_pairs->total_bvs);
    			}
    			/**/   		


    			if(empty($check_day_wise_pair_rank_data))
		    	{	
    				if($check_old_pairs->total_left_unpaid<=$left_unpaid_count)
    					$day_wise_pair_rank_data["total_left_unpaid"] = $left_unpaid_count-$check_old_pairs->total_left_unpaid;
    				else
    					$day_wise_pair_rank_data["total_left_unpaid"] = $left_unpaid_count;
    			}
    			else
    			{
	  				if($check_old_pairs->total_left_unpaid<$left_unpaid_count)
    					$day_wise_pair_rank_data["total_left_unpaid"] = $check_day_wise_pair_rank_data->total_left_unpaid+($left_unpaid_count-$check_old_pairs->total_left_unpaid);
    			}
    			/**/


    			if(empty($check_day_wise_pair_rank_data))
		    	{	
    				if($check_old_pairs->total_right_unpaid<=$right_unpaid_count)
    					$day_wise_pair_rank_data["total_right_unpaid"] = $right_unpaid_count-$check_old_pairs->total_right_unpaid;
    				else
    					$day_wise_pair_rank_data["total_right_unpaid"] = $right_unpaid_count;
    			}
    			else
    			{
	  				if($check_old_pairs->total_right_unpaid<$right_unpaid_count)
    					$day_wise_pair_rank_data["total_right_unpaid"] = $check_day_wise_pair_rank_data->total_right_unpaid+($right_unpaid_count-$check_old_pairs->total_right_unpaid);
    			}
    			/**/


    			if(empty($check_day_wise_pair_rank_data))
		    	{	
    				if($check_old_pairs->total_left_paid<=$left_paid_count)
    					$day_wise_pair_rank_data["total_left_paid"] = $left_paid_count-$check_old_pairs->total_left_paid;
    				else
    					$day_wise_pair_rank_data["total_left_paid"] = $left_paid_count;
    			}
    			else
    			{
	  				if($check_old_pairs->total_left_paid<$left_paid_count)
    					$day_wise_pair_rank_data["total_left_paid"] = $check_day_wise_pair_rank_data->total_left_paid+($left_paid_count-$check_old_pairs->total_left_paid);
    			}
    			/**/


    			if(empty($check_day_wise_pair_rank_data))
		    	{	
    				if($check_old_pairs->total_right_paid<=$right_paid_count)
    					$day_wise_pair_rank_data["total_right_paid"] = $right_paid_count-$check_old_pairs->total_right_paid;
    				else
    					$day_wise_pair_rank_data["total_right_paid"] = $right_paid_count;
    			}
    			else
    			{
	  				if($check_old_pairs->total_right_paid<$right_paid_count)
    					$day_wise_pair_rank_data["total_right_paid"] = $check_day_wise_pair_rank_data->total_right_paid+($right_paid_count-$check_old_pairs->total_right_paid);
    			}
    			/**/


    			if(empty($check_day_wise_pair_rank_data))
		    	{	
    				if($check_old_pairs->total_left_pair<=$left_paid_count)
    					$day_wise_pair_rank_data["total_left_pair"] = $left_paid_count-$check_old_pairs->total_left_pair;
    				else
    					$day_wise_pair_rank_data["total_left_pair"] = $left_paid_count;
    			}
    			else
    			{
	  				if($check_old_pairs->total_left_pair<$left_paid_count)
    					$day_wise_pair_rank_data["total_left_pair"] = $check_day_wise_pair_rank_data->total_left_pair+($left_paid_count-$check_old_pairs->total_left_pair);
    			}
    			/**/


    			if(empty($check_day_wise_pair_rank_data))
		    	{	
    				if($check_old_pairs->total_right_pair<=$right_paid_count)
    					$day_wise_pair_rank_data["total_right_pair"] = $right_paid_count-$check_old_pairs->total_right_pair;
    				else
    					$day_wise_pair_rank_data["total_right_pair"] = $right_paid_count;
    			}
    			else
    			{
	  				if($check_old_pairs->total_right_pair<$right_paid_count)
    					$day_wise_pair_rank_data["total_right_pair"] = $check_day_wise_pair_rank_data->total_right_pair+($right_paid_count-$check_old_pairs->total_right_pair);
    			}
    			/**/


    			if(empty($check_day_wise_pair_rank_data))
		    	{	
    				if($check_old_pairs->total_direct<=$totalDirect)
    					$day_wise_pair_rank_data["total_direct"] = $totalDirect-$check_old_pairs->total_direct;
    				else
    					$day_wise_pair_rank_data["total_direct"] = $totalDirect;
    			}
    			else
    			{
	  				if($check_old_pairs->total_direct<$totalDirect)
    					$day_wise_pair_rank_data["total_direct"] = $check_day_wise_pair_rank_data->total_direct+($totalDirect-$check_old_pairs->total_direct);
    			}
    			/**/


    			if(empty($check_day_wise_pair_rank_data))
		    	{	
    				if($check_old_pairs->left_bv<=$leftBv)
    					$day_wise_pair_rank_data["left_bv"] = $leftBv-$check_old_pairs->left_bv;
    				else
    					$day_wise_pair_rank_data["left_bv"] = $leftBv;
    			}
    			else
    			{
	  				if($check_old_pairs->left_bv<$leftBv)
    					$day_wise_pair_rank_data["left_bv"] = $check_day_wise_pair_rank_data->left_bv+($leftBv-$check_old_pairs->left_bv);
    			}
    			/**/


    			if(empty($check_day_wise_pair_rank_data))
		    	{	
    				if($check_old_pairs->right_bv<=$rightBv)
    					$day_wise_pair_rank_data["right_bv"] = $rightBv-$check_old_pairs->right_bv;
    				else
    					$day_wise_pair_rank_data["right_bv"] = $rightBv;
    			}
    			else
    			{
	  				if($check_old_pairs->right_bv<$rightBv)
    					$day_wise_pair_rank_data["right_bv"] = $check_day_wise_pair_rank_data->right_bv+($rightBv-$check_old_pairs->right_bv);
    			}
    			/**/


 					$day_wise_pair_rank_data["self_bv"] = $todaySelfBv;
 					$day_wise_pair_rank_data["direct_bv"] = $todayDirectBv;

    			/**/
    			
    		


	    



	    	

	    	$rank = 0;
	    	if($pairsForRank>0)
	    	{
	    		DB::table('users')->where('id',$user_id)->update(["rank"=>1,]);
	    		DB::table('member_log')->where('id',$user_id)->update(["rank"=>1,]);
	    		$rank = 1;
	    	}

	    	$rankData = MemberModel::get_rank_data($user_id, $leftdescendants, $rightdescendants);
	    	if(isset($rankData[0]))
	    	{
	    		$rankDataCheck = $rankData[0];
	    		$total_left = $rankDataCheck['total_left'];
	    		$total_right = $rankDataCheck['total_right'];

	    		$maxRank = max($total_left, $total_right);
	    		$minRank = min($total_left, $total_right);
	    		if($maxRank>=2 && $minRank>=1)
	    		{
	    			$rank = 2;	    		
		    		DB::table('users')->where('id',$user_id)->update(["rank"=>$rank,]);
		    		DB::table('member_log')->where('id',$user_id)->update(["rank"=>$rank,]);	    			
	    		}
	    	}
	    	if(isset($rankData[1]))
	    	{
	    		$rankDataCheck = $rankData[1];
	    		$total_left = $rankDataCheck['total_left'];
	    		$total_right = $rankDataCheck['total_right'];

	    		$maxRank = max($total_left, $total_right);
	    		$minRank = min($total_left, $total_right);
	    		if($maxRank>=2 && $minRank>=1)
	    		{
	    			$rank = 3;	    		
		    		DB::table('users')->where('id',$user_id)->update(["rank"=>$rank,]);
		    		DB::table('member_log')->where('id',$user_id)->update(["rank"=>$rank,]);	    			
	    		}
	    	}
	    	if(isset($rankData[2]))
	    	{
	    		$rankDataCheck = $rankData[2];
	    		$total_left = $rankDataCheck['total_left'];
	    		$total_right = $rankDataCheck['total_right'];

	    		$maxRank = max($total_left, $total_right);
	    		$minRank = min($total_left, $total_right);
	    		if($maxRank>=2 && $minRank>=1)
	    		{
	    			$rank = 4;	    		
		    		DB::table('users')->where('id',$user_id)->update(["rank"=>$rank,]);
		    		DB::table('member_log')->where('id',$user_id)->update(["rank"=>$rank,]);	    			
	    		}
	    	}
	    	if(isset($rankData[3]))
	    	{
	    		$rankDataCheck = $rankData[3];
	    		$total_left = $rankDataCheck['total_left'];
	    		$total_right = $rankDataCheck['total_right'];

	    		$maxRank = max($total_left, $total_right);
	    		$minRank = min($total_left, $total_right);
	    		if($maxRank>=3 && $minRank>=2)
	    		{
	    			$rank = 5;	    		
		    		DB::table('users')->where('id',$user_id)->update(["rank"=>$rank,]);
		    		DB::table('member_log')->where('id',$user_id)->update(["rank"=>$rank,]);	    			
	    		}
	    	}
	    	if(isset($rankData[4]))
	    	{
	    		$rankDataCheck = $rankData[4];
	    		$total_left = $rankDataCheck['total_left'];
	    		$total_right = $rankDataCheck['total_right'];

	    		$maxRank = max($total_left, $total_right);
	    		$minRank = min($total_left, $total_right);
	    		if($maxRank>=3 && $minRank>=2)
	    		{
	    			$rank = 6;	    		
		    		DB::table('users')->where('id',$user_id)->update(["rank"=>$rank,]);
		    		DB::table('member_log')->where('id',$user_id)->update(["rank"=>$rank,]);	    			
	    		}
	    	}
	    	if(isset($rankData[5]))
	    	{
	    		$rankDataCheck = $rankData[5];
	    		$total_left = $rankDataCheck['total_left'];
	    		$total_right = $rankDataCheck['total_right'];

	    		$maxRank = max($total_left, $total_right);
	    		$minRank = min($total_left, $total_right);
	    		if($maxRank>=3 && $minRank>=2)
	    		{
	    			$rank = 7;	    		
		    		DB::table('users')->where('id',$user_id)->update(["rank"=>$rank,]);
		    		DB::table('member_log')->where('id',$user_id)->update(["rank"=>$rank,]);	    			
	    		}
	    	}
	    	if(isset($rankData[6]))
	    	{
	    		$rankDataCheck = $rankData[6];
	    		$total_left = $rankDataCheck['total_left'];
	    		$total_right = $rankDataCheck['total_right'];

	    		$maxRank = max($total_left, $total_right);
	    		$minRank = min($total_left, $total_right);
	    		if($maxRank>=3 && $minRank>=2)
	    		{
	    			$rank = 8;	    		
		    		DB::table('users')->where('id',$user_id)->update(["rank"=>$rank,]);
		    		DB::table('member_log')->where('id',$user_id)->update(["rank"=>$rank,]);	    			
	    		}
	    	}
	    	if(isset($rankData[7]))
	    	{
	    		$rankDataCheck = $rankData[7];
	    		$total_left = $rankDataCheck['total_left'];
	    		$total_right = $rankDataCheck['total_right'];

	    		$maxRank = max($total_left, $total_right);
	    		$minRank = min($total_left, $total_right);
	    		if($maxRank>=1 && $minRank>=1)
	    		{
	    			$rank = 9;	    		
		    		DB::table('users')->where('id',$user_id)->update(["rank"=>$rank,]);
		    		DB::table('member_log')->where('id',$user_id)->update(["rank"=>$rank,]);	    			
	    		}
	    	}
	    	if(isset($rankData[8]))
	    	{
	    		$rankDataCheck = $rankData[8];
	    		$total_left = $rankDataCheck['total_left'];
	    		$total_right = $rankDataCheck['total_right'];

	    		$maxRank = max($total_left, $total_right);
	    		$minRank = min($total_left, $total_right);
	    		if($maxRank>=1 && $minRank>=1)
	    		{
	    			$rank = 10;	    		
		    		DB::table('users')->where('id',$user_id)->update(["rank"=>$rank,]);
		    		DB::table('member_log')->where('id',$user_id)->update(["rank"=>$rank,]);	    			
	    		}
	    	}


	    	if(empty($check_day_wise_pair_rank_data))
	    	{	
  				if($check_old_pairs->rank<=$rank)
  					$day_wise_pair_rank_data["rank"] = $rank-$check_old_pairs->rank;
  				else
  					$day_wise_pair_rank_data["rank"] = $rank;
  			}
  			else
  			{
  				if($check_old_pairs->rank<$rank)
  					$day_wise_pair_rank_data["rank"] = $check_day_wise_pair_rank_data->rank+($rank-$check_old_pairs->rank);
  			}
  			/**/

	    	// $day_wise_pair_rank_data['rank'] = $rank;

  			// echo $rank;
	    	
	    	if(empty($check_day_wise_pair_rank_data)) DB::table('day_wise_pair_rank')->insert($day_wise_pair_rank_data);
	    	else DB::table('day_wise_pair_rank')->where('id',$check_day_wise_pair_rank_data->id)->update($day_wise_pair_rank_data);


	}




	public static function generate_incomes($page,$limit)
	{
		$date = date("Y-m-d");

		$users = DB::table('users')
		->limit($limit)
    ->offset($page*$limit)
		->orderBy('id','desc')
		// ->where("is_paid",1)
		// ->where('id',6)
		->where('status',1)->get();


		// $day_wise_pairs = DB::table('day_wise_pair_rank')
		// ->where('date',$date)
		// ->groupBy('user_id')
		// ->get();

		$gst = 0;
		$tds = 5;
		$report_date_time = date("Y-m-d H:i:s");
		$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


		foreach ($users as $key => $valueUser)
		{
				$pairsDataDayWise = $value = DB::table('day_wise_pair_rank')
				->where(['date'=>$date,"user_id"=>$valueUser->id,])
				// ->groupBy('user_id')
				->first();

				if(!empty($value))
				{
					$user_id = $value->user_id;
					$pairs = $value->total_pairs;
					$bvs = $value->total_bvs;
					$totalDirect = $value->total_direct;
					$selfBv = $value->self_bv;
					$directBv = $value->direct_bv;
					$getTypeAllIncome = MemberModel::getTypeAllIncome($user_id);
					$get_user = $valueUser;
			  	$sponser_id = $get_user->sponser_id;
			  	$sponser_data = MemberModel::GetSponserData($sponser_id);
			  	$totalPairs = $get_user->total_pairs;
	        $package_name = '';
	        $amount = 0;

	        $income1 = 0;
	        $income2 = 0;
	        $income3 = 0;
	        $income4 = 0;
	        $income5 = 0;
	        $incomePlan = DB::table('income_plan')->first();
	        if(!empty($incomePlan))
	        {
	        	$income1 = $incomePlan->income1;
	        	$amount = $income2 = $incomePlan->income2;
	        	$income3 = $incomePlan->income3;
	        	$income4 = $incomePlan->income4;
	        	$income5 = $incomePlan->income5;
	        }


	        
		        $now_pairs = 0;
		        $pending_pairs = $valueUser->pending_pairs;
		        $pairs = $pairs+$pending_pairs;
		        if($pairs>$incomePlan->per_day_pair_income)
		        {
		            $pending_pairs = $pairs-$incomePlan->per_day_pair_income;
		            $now_pairs = $pairs-$pending_pairs;
		        }
		        else
		        {
		            $pending_pairs = 0;
		            $now_pairs = $pairs;
		        }
		        DB::table("users")->where("id",$valueUser->id)->update(["pending_pairs"=>$pending_pairs,]);
	        	$pairIncome = $income2*$now_pairs;
	        



	        if($incomePlan->type==1)
        	{
        		$directIncome = $income1*$totalDirect;
        	}
	        else
	        {
	        	$directIncome = $income1*$totalDirect;;
	        }

	      	
	      
					$allDirectIds = MemberModel::allDirectIds($user_id);
					$directTeamPairAmount = DB::table("report")->whereIn("member_id",$allDirectIds)->where(["only_date"=>$date,"type"=>2,])->sum("amount");					
					$downlineIncome = 0;
					if(!empty($directTeamPairAmount))
					{
						$downlineIncome = $directTeamPairAmount*$income3/100; 
					}


			    MemberModel::direct_income($user_id,$directIncome);
	        MemberModel::pair_income($user_id,$pairIncome);
	        MemberModel::downline_income($user_id,$downlineIncome,$income3);
	        MemberModel::upline_income($user_id,$pairIncome,$income4);
	        MemberModel::rank_reward_income($user_id,$valueUser,$income5);

	   		}

    		
		}




	}


	public static function direct_income($user_id,$amount)
	{

			$report_date_time = date("Y-m-d H:i:s");
			$date = date("Y-m-d");
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	    $gst = 0;
	    $tds = 5;



	    $gst_amount = $amount/100*$gst;
	    // $tds_amount = $amount/100*$tds;
	    $tds_amount = 0;
	    $repurchase_amount = $amount/100*5;
      // $final_amount = $amount-($gst_amount+$tds_amount+$repurchase_amount);
      $final_amount = $amount;

	  

	    if(!empty($final_amount))
	    {
		    $data = [
		        "member_id"=>$user_id,
		        "user_id"=>0,
		        "sponser_id"=>0,
		        "transaction_id"=>'',
		        "amount"=>$amount,
		        "gst"=>$gst_amount,
		        "tds"=>$tds,
		        "wallet"=>5,
		        "final_amount"=>$final_amount,
		        "currency"=>"INR",
		        "type"=>1,
		        "package_name"=>'',
		        "package_id"=>'',
		        "add_date_time"=>$report_date_time,
		        "package_payment_date_time"=>$report_date_time,
		        "status"=>1,
		        "payment"=>0,
		        "slug"=>$actual_link,
		        "only_date"=>date("Y-m-d", strtotime($report_date_time)),
		        "is_delete"=>0,
		    ];
		    $check = DB::table('report')->where(["member_id"=>$user_id,"only_date"=>$date,"type"=>1,])->first();
	      if(empty($check)) DB::table('report')->insert($data);
	      else DB::table('report')->where('id',$check->id)->update($data);

	      MemberModel::day_wise_income($user_id,$date);
	      MemberModel::month_wise_income($user_id,$date);
	 			MemberModel::year_wise_income($user_id,$date);
	 			MemberModel::day_wise_repurchase($user_id);

	 		}


	}
	public static function pair_income($user_id, $amount)
	{
			/*pair income start*/

			$report_date_time = date("Y-m-d H:i:s");
			$date = date("Y-m-d");
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $gst = 0;
      $tds = 5;
      $gst_amount = $amount/100*$gst;
      // $tds_amount = $amount/100*$tds;
      $tds_amount = 0;
      $repurchase_amount = $amount/100*5;
      // $final_amount = $amount-($gst_amount+$tds_amount+$repurchase_amount);
      $final_amount = $amount;


      if($amount>0)
      {
	      $data = [
	          "member_id"=>$user_id,
	          "user_id"=>0,
	          "sponser_id"=>0,
	          "transaction_id"=>'',
	          "amount"=>$amount,
	          "gst"=>$gst_amount,
	          "tds"=>$tds,
	          "wallet"=>5,
	          "final_amount"=>$final_amount,
	          "currency"=>"INR",
	          "type"=>2,
	          "package_name"=>'',
	          "package_id"=>'',
	          "add_date_time"=>$report_date_time,
	          "package_payment_date_time"=>$report_date_time,
	          "status"=>1,
	          "payment"=>0,
	          "slug"=>$actual_link,
	          "only_date"=>date("Y-m-d", strtotime($report_date_time)),
	          "is_delete"=>0,
	      ];
	      $check = DB::table('report')->where(["member_id"=>$user_id,"only_date"=>$date,"type"=>2,])->first();
	      if(empty($check)) DB::table('report')->insert($data);
	      else DB::table('report')->where('id',$check->id)->update($data);

	      MemberModel::day_wise_income($user_id,$date);
	      MemberModel::month_wise_income($user_id,$date);
	 			MemberModel::year_wise_income($user_id,$date);
	 			MemberModel::day_wise_repurchase($user_id);
	 		}

    /*pair income end*/
	}
	public static function downline_income($user_id, $amount,$percent)
	{

		/*downline income start*/
			$report_date_time = date("Y-m-d H:i:s");
			$date = date("Y-m-d");
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $gst = 0;
      $tds = 5;


    	// $amount = $amount/100*$percent;
      $gst_amount = $amount/100*$gst;
      // $tds_amount = $amount/100*$tds;
      $tds_amount = 0;
      $repurchase_amount = $amount/100*5;
      // $final_amount = $amount-($gst_amount+$tds_amount+$repurchase_amount);
      $final_amount = $amount;

      if($amount>0)
      {
	      $data = [
	          "member_id"=>$user_id,
	          "user_id"=>0,
	          "sponser_id"=>0,
	          "transaction_id"=>'',
	          "amount"=>$amount,
	          "gst"=>$gst_amount,
	          "tds"=>$tds,
	          "wallet"=>5,
	          "final_amount"=>$final_amount,
	          "currency"=>"INR",
	          "type"=>3,
	          "package_name"=>'',
	          "package_id"=>'',
	          "add_date_time"=>$report_date_time,
	          "package_payment_date_time"=>$report_date_time,
	          "status"=>1,
	          "payment"=>0,
	          "slug"=>$actual_link,
	          "only_date"=>date("Y-m-d", strtotime($report_date_time)),
	          "is_delete"=>0,
	      ];      
	  		$check = DB::table('report')->where(["member_id"=>$user_id,"only_date"=>$date,"type"=>3,])->first();
	      if(empty($check)) DB::table('report')->insert($data);
	      else DB::table('report')->where('id',$check->id)->update($data);

	      MemberModel::day_wise_income($user_id,$date);
	      MemberModel::month_wise_income($user_id,$date);
	 			MemberModel::year_wise_income($user_id,$date);
	 			MemberModel::day_wise_repurchase($user_id);
	 		}

    /*downline income end*/
	}
	public static function upline_income($user_id, $amount, $percent)
	{
		/*upline income start*/

			$report_date_time = date("Y-m-d H:i:s");
			$date = date("Y-m-d");
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $gst = 0;
      $tds = 5;

      $allDirectIds = MemberModel::allDirectIds($user_id,1);
      $idsCount = count($allDirectIds);
      $amount = $amount/100*$percent;
      if(!empty($amount) && !empty($idsCount))
      {
      	$amount = floatval(number_format($amount/$idsCount,2));
	      foreach ($allDirectIds as $key2 => $value2)
	      {
	      	$uplineID = $value2;
	        $gst_amount = $amount/100*$gst;
	        // $tds_amount = $amount/100*$tds;
	        $tds_amount = 0;
	        $repurchase_amount = $amount/100*5;
      		// $final_amount = $amount-($gst_amount+$tds_amount+$repurchase_amount);
      		$final_amount = $amount;

	        if($amount>0)
		      {
			        $data = [
				          "member_id"=>$uplineID,
				          "user_id"=>0,
				          "sponser_id"=>0,
				          "transaction_id"=>'',
				          "amount"=>$amount,
				          "gst"=>$gst_amount,
				          "tds"=>$tds,
				          "wallet"=>5,
				          "final_amount"=>$final_amount,
				          "currency"=>"INR",
				          "type"=>4,
				          "package_name"=>'',
				          "package_id"=>'',
				          "add_date_time"=>$report_date_time,
				          "package_payment_date_time"=>$report_date_time,
				          "status"=>1,
				          "payment"=>0,
				          "slug"=>$actual_link,
				          "only_date"=>date("Y-m-d", strtotime($report_date_time)),
				          "is_delete"=>0,
				      ];           
			    		$check = DB::table('report')->where(["member_id"=>$uplineID,"only_date"=>$date,"type"=>4,])->first();
			        if(empty($check)) DB::table('report')->insert($data);
			        else DB::table('report')->where('id',$check->id)->update($data);

			        MemberModel::day_wise_income($uplineID,$date);
			        MemberModel::month_wise_income($uplineID,$date);
			   			MemberModel::year_wise_income($uplineID,$date);
			   			MemberModel::day_wise_repurchase($user_id);
			   	}
	      }
	    }

      /*upline income end*/
	}
	public static function rank_reward_income($user_id, $user, $percent)
	{
			$rank = $user->rank;			
			$report_date_time = date("Y-m-d H:i:s");
			$date = date("Y-m-d");
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	    $data = [
	        "member_id"=>$user_id,
	        "user_id"=>0,
	        "sponser_id"=>0,
	        "transaction_id"=>'',
	        "currency"=>"INR",
	        "type"=>5,
	        "package_name"=>'',
	        "package_id"=>'',
	        "add_date_time"=>$report_date_time,
	        "package_payment_date_time"=>$report_date_time,
	        "status"=>1,
	        "payment"=>0,
	        "slug"=>$actual_link,
	        "only_date"=>date("Y-m-d", strtotime($report_date_time)),
	        "is_delete"=>0,
	    ];



	    $insertArray = [];
	    if($rank>=1) $insertArray[] = ["rank"=>1,"amount"=>500,];
    	if($rank>=2) $insertArray[] = ["rank"=>2,"amount"=>1500,];
    	if($rank>=3) $insertArray[] = ["rank"=>3,"amount"=>2500,];
    	if($rank>=4) $insertArray[] = ["rank"=>4,"amount"=>10000,];
    	if($rank>=5) $insertArray[] = ["rank"=>5,"amount"=>50000,];
    	if($rank>=6) $insertArray[] = ["rank"=>6,"amount"=>250000,];
    	if($rank>=7) $insertArray[] = ["rank"=>7,"amount"=>100000000,];
    	if($rank>=8) $insertArray[] = ["rank"=>8,"amount"=>5000000,];
    	if($rank>=9) $insertArray[] = ["rank"=>9,"amount"=>10000000,];
    	if($rank>=10) $insertArray[] = ["rank"=>10,"amount"=>25000000,];

    	
  		foreach ($insertArray as $key => $value)
  		{
  			$checkCount = DB::table('report')->where(["member_id"=>$user_id,"type"=>5,"rank"=>$value['rank'],])->first();
  			if(empty($checkCount))
  			{
  				$amount = $value['amount'];
  				if($amount>0)
      		{
					    $gst = 0;
					    $tds = 5;
					    $gst_amount = $amount/100*$gst;
					    // $tds_amount = $amount/100*$tds;
					    $tds_amount = 0;
					    $repurchase_amount = $amount/100*5;
      				// $final_amount = $amount-($gst_amount+$tds_amount+$repurchase_amount);
      				$final_amount = $amount;
					    $data['amount'] = $amount;
					    $data['gst'] = $gst;
					    $data['tds'] = $tds;
					    $data['wallet'] = 5;
					    $data['final_amount'] = $final_amount;
					    $data['rank'] = $value['rank'];
				    	$check = DB::table('report')->where(["member_id"=>$user_id,"only_date"=>$date,"type"=>5,"rank"=>$value['rank'],])->first();
				    	if(empty($check)) DB::table('report')->insert($data);
				    	else DB::table('report')->where('id',$check->id)->update($data);
				  }
		    }
  		}

  		MemberModel::day_wise_income($user_id,$date);
      MemberModel::month_wise_income($user_id,$date);
 			MemberModel::year_wise_income($user_id,$date);
 			MemberModel::day_wise_repurchase($user_id);

		  

	}


	public static function repurchase_income($user_id, $totalBv)
	{
		/*upline income start*/

			$report_date_time = date("Y-m-d H:i:s");
			$date = date("Y-m-d");
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $gst = 0;
      $tds = 5;

      $allParentDirectIds = MemberModel::allParentDirectIds($user_id,1);
     
      $idsCount = count($allParentDirectIds);
      
      if(!empty($totalBv) && !empty($idsCount))
      {

	      foreach ($allParentDirectIds as $key2 => $value2)
	      {

	      	$lavel = $key2+1;
	      	if($lavel==1) $percent = 10;
	      	if($lavel==2) $percent = 5;
	      	if($lavel==3) $percent = 5;
	      	if($lavel==4) $percent = 5;
	      	if($lavel==5) $percent = 5;
	      	if($lavel==6) $percent = 3;
	      	if($lavel==7) $percent = 3;
	      	if($lavel==8) $percent = 3;
	      	if($lavel==9) $percent = 3;
	      	if($lavel==10) $percent = 3;

	      	$amount = number_format($totalBv/100*$percent,2);
	      	


	      	$uplineID = $value2;
	        $gst_amount = $amount/100*$gst;
	        // $tds_amount = $amount/100*$tds;
	        $tds_amount = 0;
	        // $repurchase_amount = $amount/100*5;
      		// $final_amount = $amount-($gst_amount+$tds_amount+$repurchase_amount);
      		$final_amount = $amount;

	        if($amount>0)
		      {
			        $data = [
				          "member_id"=>$uplineID,
				          "user_id"=>$user_id,
				          "sponser_id"=>0,
				          "transaction_id"=>'',
				          "amount"=>$amount,
				          "gst"=>$gst_amount,
				          "tds"=>$tds,
				          "wallet"=>0,
				          "final_amount"=>$final_amount,
				          "currency"=>"INR",
				          "type"=>6,
				          "package_name"=>'',
				          "package_id"=>'',
				          "add_date_time"=>$report_date_time,
				          "package_payment_date_time"=>$report_date_time,
				          "status"=>1,
				          "payment"=>0,
				          "slug"=>$actual_link,
				          "only_date"=>date("Y-m-d", strtotime($report_date_time)),
				          "is_delete"=>0,
				      ];
			    		$check = DB::table('report')->where(["member_id"=>$uplineID,"only_date"=>$date,"type"=>6,])->first();
			        if(empty($check)) DB::table('report')->insert($data);
			        else DB::table('report')->where('id',$check->id)->update($data);

			        MemberModel::day_wise_income($uplineID,$date);
			        MemberModel::month_wise_income($uplineID,$date);
			   			MemberModel::year_wise_income($uplineID,$date);
			   			// MemberModel::day_wise_repurchase($user_id);
			   	}

			   	if($lavel==10) break;

	      }
	    }

      /*upline income end*/
	}







	public static function get_rank_data($user_id, $leftdescendants=[], $rightdescendants=[])
	{	
		$descendants = [];
		if(empty($leftdescendants) || empty($rightdescendants))
		{
			$leftMemberId = 0;
			$rightMemberId = 0;
			$leftdescendants = [];
			$rightdescendants = [];
	    $leftMember = DB::table('member_log')->where(['parent_id'=>$user_id,'position'=>1,])->first();
	    $rightMember = DB::table('member_log')->where(['parent_id'=>$user_id,'position'=>2,])->first();
	    if(!empty($leftMember)) $leftMemberId = $leftMember->id;
	    if(!empty($rightMember)) $rightMemberId = $rightMember->id;
	    if(!empty($leftMemberId)) $leftdescendants = array_merge([$leftMemberId],MemberModel::getAllChildIds($leftMemberId));
	    if(!empty($rightMemberId)) $rightdescendants = array_merge([$rightMemberId],MemberModel::getAllChildIds($rightMemberId));
			$descendants = array_merge($leftdescendants, $rightdescendants);
		} 


			$is_paid = [1];
			$rankArray = [];
			$leftCounts = [];
			$rightCounts = [];

			
			if(!empty($leftdescendants))
	    	$leftCounts = DB::table('users')->select('rank', DB::raw('COUNT(*) as total_executives'))->whereIn('is_paid', $is_paid)->whereIn('id', $leftdescendants)->whereIn('rank', range(1, 10))->groupBy('rank')->pluck('total_executives', 'rank');
			if(!empty($rightdescendants))
	    	$rightCounts = DB::table('users')->select('rank', DB::raw('COUNT(*) as total_executives'))->whereIn('is_paid', $is_paid)->whereIn('id', $rightdescendants)->whereIn('rank', range(1, 10))->groupBy('rank')->pluck('total_executives', 'rank');
			for ($i = 1; $i <= 10; $i++) {
					$rankArray[$i-1] = ["rank"=>$i,"total_left"=>$leftCounts[$i] ?? 0,"total_right"=>$rightCounts[$i] ?? 0,];
			}
			// $rankArray = $rankArray;
			return $rankArray;
	}

		

		public static function day_wise_income($user_id,$wise_date)
   	{  		
				$day_wise_earning_data = DB::table("report")
				->select(DB::raw('SUM(final_amount) as final_amount'),'type')
				->where('only_date', $wise_date)
				->groupBy('type')
				->where('status', 1)
				->where(["member_id"=>$user_id,])
				->get();
        
        $data = [];
        foreach ($day_wise_earning_data as $key => $value) {
        	if($value->type==1) $data['income1'] = $value->final_amount;
        	else if($value->type==2) $data['income2'] = $value->final_amount;
        	else if($value->type==3) $data['income3'] = $value->final_amount;
        	else if($value->type==4) $data['income4'] = $value->final_amount;
        	else if($value->type==5) $data['income5'] = $value->final_amount;
        	else if($value->type==6) $data['income6'] = $value->final_amount;
        }

        if(!empty($data))
        {
        	$data['user_id'] = $user_id;
        	$data['date'] = $wise_date;
	        $check_amount_data = DB::table('day_wise_income')
		   		->where('user_id',$user_id)
		   		->where("date",$wise_date)
		   		->first();

		   		if(empty($check_amount_data))
		   		{
		   			DB::table('day_wise_income')->insert($data);
		   		}
		   		else
		   		{
		   			DB::table('day_wise_income')
		   			->where('user_id',$user_id)
		   			->where("date",$wise_date)
		   			->update($data);
		   		}        	
        }
   	}


   	public static function month_wise_income($user_id,$date_time)
   	{
   		$month = date("m", strtotime($date_time));
   		$year = date("Y", strtotime($date_time));

   		$month_wise_earning_data = DB::table("day_wise_income")
   		->selectRaw('SUM(income1) as income1, SUM(income2) as income2, SUM(income3) as income3, SUM(income4) as income4, SUM(income5) as income5, SUM(income6) as income6')
				->whereYear('date', $year)
        ->whereMonth('date', $month)
				->where(["user_id"=>$user_id,])
				->first();

				

         $data = [];
      	 $data['income1'] = $month_wise_earning_data->income1;
      	 $data['income2'] = $month_wise_earning_data->income2;
      	 $data['income3'] = $month_wise_earning_data->income3;
      	 $data['income4'] = $month_wise_earning_data->income4;
      	 $data['income5'] = $month_wise_earning_data->income5;
      	 $data['income6'] = $month_wise_earning_data->income6;
      

        if(!empty($data))
        {
        	$data['user_id'] = $user_id;
        	$data["month"]=$month;
   				$data["year"]=$year;
	        $check_amount_data = DB::table('month_wise_income')
		   		->where('user_id',$user_id)
		   		->where("month",$month)
   				->where("year",$year)
		   		->first();

		   		if(empty($check_amount_data))
		   		{
		   			DB::table('month_wise_income')->insert($data);
		   		}
		   		else
		   		{
		   			DB::table('month_wise_income')
		   			->where('user_id',$user_id)
		   			->where("month",$month)
   					->where("year",$year)
		   			->update($data);
		   		}        	
        }
   	}

   	public static function year_wise_income($user_id,$date_time)
   	{
   		$year = date("Y", strtotime($date_time));
				$month_wise_earning_data = DB::table("month_wise_income")
   		->selectRaw('SUM(income1) as income1, SUM(income2) as income2, SUM(income3) as income3, SUM(income4) as income4, SUM(income5) as income5, SUM(income6) as income6')
				->where('year', $year)
				->where(["user_id"=>$user_id,])
				->first();

        $data = [];
    	  $data['income1'] = $month_wise_earning_data->income1;
    	  $data['income2'] = $month_wise_earning_data->income2;
    	  $data['income3'] = $month_wise_earning_data->income3;
    	  $data['income4'] = $month_wise_earning_data->income4;
    	  $data['income5'] = $month_wise_earning_data->income5;
    	  $data['income6'] = $month_wise_earning_data->income6;
        

        if(!empty($data))
        {
        	$data['user_id'] = $user_id;
   				$data["year"]=$year;
	        $check_amount_data = DB::table('year_wise_income')
		   		->where('user_id',$user_id)
   				->where("year",$year)
		   		->first();

		   		if(empty($check_amount_data))
		   		{
		   			DB::table('year_wise_income')->insert($data);
		   		}
		   		else
		   		{
		   			DB::table('year_wise_income')
		   			->where('user_id',$user_id)
   					->where("year",$year)
		   			->update($data);
		   		}        	
        }

        $day_wise_earning_data = DB::table("year_wise_income")
		    ->selectRaw('SUM(income1) as income1, SUM(income2) as income2, SUM(income3) as income3, SUM(income4) as income4, SUM(income5) as income5, SUM(income6) as income6')
		    ->where("user_id", $user_id)
		    ->first();


		    MemberModel::getTypeAllIncome($user_id);

		    DB::table('wallet')->where('user_id',$user_id)->update([
		    	"income1"=>$day_wise_earning_data->income1,
		    	"income2"=>$day_wise_earning_data->income2,
		    	"income3"=>$day_wise_earning_data->income3,
		    	"income4"=>$day_wise_earning_data->income4,
		    	"income5"=>$day_wise_earning_data->income5,
		    	"income6"=>$day_wise_earning_data->income6,
		    ]);
   	}


   	public static function day_wise_repurchase($user_id)
   	{  		
   			$wise_date = date("Y-m-d");
   			// $wise_date = "2025-03-05";
				$day_wise_earning_data = DB::table("day_wise_income")
		    ->selectRaw('SUM(income1) as income1, SUM(income2) as income2, SUM(income3) as income3, SUM(income4) as income4, SUM(income5) as income5, SUM(income6) as income6')
		    ->where("user_id", $user_id)
		    ->where('date',$wise_date)
		    ->first();
        
        $total_amount = 0;
        if(!empty($day_wise_earning_data))
        {
        	$total_amount = $day_wise_earning_data->income1+$day_wise_earning_data->income2+$day_wise_earning_data->income3+$day_wise_earning_data->income4+$day_wise_earning_data->income5+$day_wise_earning_data->income6;
        }

        if(!empty($total_amount))
        {
        	$total_amount = $total_amount/100*5;
        	$data['user_id'] = $user_id;
        	$data['date'] = $wise_date;
        	$data['type'] = 1;
        	$data['amount'] = $total_amount;
	        $check_amount_data = DB::table('day_wise_repurchase')
		   		->where('user_id',$user_id)
		   		->where("date",$wise_date)
		   		->where("type",1)
		   		->first();

		   		if(empty($check_amount_data))
		   		{
		   			DB::table('day_wise_repurchase')->insert($data);
		   		}
		   		else
		   		{
		   			DB::table('day_wise_repurchase')
		   			->where('user_id',$user_id)
		   			->where("date",$wise_date)
		   			->update($data);
		   		}        	
        }
   	}


   	public static function repurchase_wallet_update($user_id, $amount, $type)
   	{
   			$wise_date = date("Y-m-d");
  			$data['user_id'] = $user_id;
      	$data['date'] = $wise_date;
      	$data['type'] = $type;
      	$data['amount'] = $amount;
  			DB::table('day_wise_repurchase')->insert($data);    	
   	}


   	public static function repurchase_wallet($user_id)
   	{
   			$addAmount = DB::table("day_wise_repurchase")->where(["user_id"=>$user_id,"type"=>1,])->sum('amount');
   			$deductAmount = DB::table("day_wise_repurchase")->where(["user_id"=>$user_id,"type"=>2,])->sum('amount');
   			return $addAmount-$deductAmount;
   	}
















   
   	public static function createTransaction($data)
    {   
    		$user_id = $data['user_id'];
    		$amount = $data['amount'];
    		$type = $data['type'];
    		$detail = $data['detail'];

        $transaction_id = 'MT'.rand ( 10000 , 99999 ).rand ( 10000 , 99999 ).rand ( 1000 , 9999 ).rand ( 10 , 99 );
        DB::table("transaction")->where(["user_id"=>$user_id,"status"=>0,])->delete();

        
        $date_time = date("Y-m-d H:i:s");
        $gst = 0;
        $gst_amount = $amount/100*$gst;
        $final_income = $amount-$gst_amount;

        
        DB::table('transaction')->insert([
            "user_id"=>$user_id,
            "transaction_id"=>$transaction_id,
            "type"=>$type,
            "transaction_type"=>1,
            "amount"=>$amount,
            "gst"=>$gst_amount,
            "final_amount"=>$final_income,
            "detail"=>json_encode($detail),
            "add_date_time"=>$date_time,
            "payment_date_time"=>$date_time,
            "status"=>1,
        ]);
    }
    public static function createOrderTransaction($data)
    {   
    		$user_id = $data['user_id'];
    		$amount = $data['amount'];
    		$type = $data['type'];
    		$detail = $data['detail'];
    		$amount_detail = $data['amount_detail'];

        $transaction_id = 'MT'.rand ( 10000 , 99999 ).rand ( 10000 , 99999 ).rand ( 1000 , 9999 ).rand ( 10 , 99 );
        DB::table("transaction")->where(["user_id"=>$user_id,"status"=>0,])->delete();

        
        $date_time = date("Y-m-d H:i:s");
        $gst = 0;
        // $gst_amount = $amount/100*$gst;
        $gst_amount = $amount*12/100;
        $subTotal = $amount-$gst_amount;
        $final_income = $subTotal+$gst_amount;

        
        DB::table('transaction')->insert([
            "user_id"=>$user_id,
            "transaction_id"=>$transaction_id,
            "type"=>$type,
            "transaction_type"=>1,
            "amount"=>$subTotal,
            "gst"=>$gst_amount,
            "final_amount"=>$final_income,
            "detail"=>json_encode($detail),
            "amount_detail"=>($amount_detail),
            "add_date_time"=>$date_time,
            "payment_date_time"=>$date_time,
            "status"=>1,
        ]);
    }
   	



   	public static function create_order($user_id,$package,$sponser_id='',$order_type='',$promo_code='')
    {   

    		$order_amount = $data['amount'];
        $transaction_id = 'MT'.rand ( 10000 , 99999 ).rand ( 10000 , 99999 ).rand ( 1000 , 9999 ).rand ( 10 , 99 );
        DB::table("orders")->where(["user_id"=>$user_id,"status"=>0,])->delete();        

        
        $order_id = time().$user_id;
        $date_time = date("Y-m-d H:i:s");
        $gst = 0;
        $gst_amount = $order_amount/100*$gst;
        $final_income = $order_amount-$gst_amount;


        DB::table('orders')->insert([
            "order_id"=>$order_id,
            "transaction_id"=>$transaction_id,
            "order_type"=>$order_type,
            "user_id"=>$user_id,
            "amount"=>$final_income,
            "gst"=>$gst_amount,
            "tax_amount"=>$order_amount,
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




	

	

	


 
    public static function update_order($user_id,$type='')
    {
        $date_time = date("Y-m-d H:i:s");
        $orders = DB::table('orders')->where("user_id",$user_id)->where("status",0)->first();
        DB::table("orders")
        ->where("user_id",$user_id)
        ->where("status",0)
        ->update(["status"=>1,"payment_date_time"=>$date_time,]);


        $package_id = $orders->product_id;
        MemberModel::insert_user_package($user_id,$package_id,$type);



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

  

    public static function payoutAmount($user_id,$type)
    {
    	$wAmt = DB::table("report")
        ->select(DB::raw('SUM(wallet) as wallet'))
        ->where('status', 1)
        ->where('payment', $type)
        ->where(["member_id"=>$user_id,])
        ->first();
        $wAmt = $wAmt->wallet?$wAmt->wallet:0;

    	$unpaid = DB::table("report")
        ->select(DB::raw('SUM(amount) as amount'))
        ->where('status', 1)
        ->where('payment', $type)
        ->where(["member_id"=>$user_id,])
        ->first();
        $unpaid = $unpaid->amount?$unpaid->amount:0;

        $tds = $unpaid*5/100;
        $unpaid = $unpaid-$tds;
        return $unpaid-$wAmt;
    } 

    public static function all_time_income($user_id)
    {
    	$totalIncome = 0;

    	$day_wise_earning_data1 = DB::table("wallet")->where(["user_id"=>$user_id,])->first();
      if(!empty($day_wise_earning_data1)) $totalIncome += $day_wise_earning_data1->income1;

      $day_wise_earning_data2 = DB::table("wallet")->where(["user_id"=>$user_id,])->first();
      if(!empty($day_wise_earning_data2)) $totalIncome += $day_wise_earning_data2->income2;

      $day_wise_earning_data3 = DB::table("wallet")->where(["user_id"=>$user_id,])->first();
      if(!empty($day_wise_earning_data3)) $totalIncome += $day_wise_earning_data3->income3;

      $day_wise_earning_data4 = DB::table("wallet")->where(["user_id"=>$user_id,])->first();
      if(!empty($day_wise_earning_data4)) $totalIncome += $day_wise_earning_data4->income4;

      $day_wise_earning_data5 = DB::table("wallet")->where(["user_id"=>$user_id,])->first();
      if(!empty($day_wise_earning_data5)) $totalIncome += $day_wise_earning_data5->income5;

      $day_wise_earning_data6 = DB::table("wallet")->where(["user_id"=>$user_id,])->first();
      if(!empty($day_wise_earning_data6)) $totalIncome += $day_wise_earning_data6->income6;

      return $totalIncome;
    }

    public static function today_income($user_id)
    {
    	$day_wise_earning_data = DB::table("day_wise_income")
		    ->selectRaw('SUM(income1) as income1, SUM(income2) as income2, SUM(income3) as income3, SUM(income4) as income4, SUM(income5) as income5, SUM(income6) as income6')
		    ->where("user_id", $user_id)
		    ->where('date',date("Y-m-d"))
		    ->first();
        return $day_wise_earning_data->income1+$day_wise_earning_data->income2+$day_wise_earning_data->income3+$day_wise_earning_data->income4+$day_wise_earning_data->income5+$day_wise_earning_data->income6;
    }

    public static function getTypeAllIncome($user_id)
    {
    	$wallet = DB::table('wallet')->where('user_id',$user_id)->first();
    	if(empty($wallet))
    	{
    		DB::table('wallet')->insert([
    			"user_id"=>$user_id,
    			"income1"=>0,
    			"income2"=>0,
    			"income3"=>0,
    			"income4"=>0,
    			"income5"=>0,
    			"income6"=>0,
    		]);
    	}
    	$wallet = DB::table('wallet')->where('user_id',$user_id)->first();
    	
    	return $wallet;
    }

 	
 	public static function cartDetail($user_id)
 	{
 		$cartDataQuery = DB::table('cart')
 		->rightJoin("product","product.id","=","cart.product_id")
    ->select("cart.*","product.*","cart.id as cat_id")
 		->where(["cart.user_id"=>$user_id,])
 		->orderBy('cart.id','asc');


 		$cartData = $cartDataQuery->get();


 		// $cartTotal = $cartDataQuery->sum('sale_price');

 		$cartTotal = 0;
 		$gst = 0;
 		$cartFinalAmount  = 0;
 		$totalBv  = 0;
 		foreach ($cartData as $key => $value) {
 			$cartTotal+=$value->sale_price*$value->qty;
 			$totalBv+=$value->bv*$value->qty;
 		}
 		// $gst = $cartTotal*18/100;
 		$gst = 0;
 		$cartFinalAmount = $cartTotal+$gst;




 		$cartData->transform(function ($item) {
        $item->image = url('storage/app/public/upload/'.$item->display_image);
        // $item->real_price = Helpers::price_formate($item->real_price);
        // $item->sale_price = Helpers::price_formate($item->sale_price);
        $item->add_date_time = date("d M, Y h: A", strtotime($item->add_date_time));
        $item->qty = $item->qty?$item->qty:0;

        return $item;
    });


 		$data['cartProducts'] = $cartData;
 		$data['cartCount'] = count($cartData);
 		$data['cartTotal'] = $cartTotal;
 		$data['gst'] = $gst;
 		$data['cartFinalAmount'] = $cartFinalAmount;
 		$data['totalBv'] = $totalBv;
 		return $data;
 	}
   	

 }