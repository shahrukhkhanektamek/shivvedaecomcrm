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
 
class LavelEarningController extends Controller
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
      
        $data_list = MemberModel::levelWiseIncome($user_id);
        if(!empty($request->level))
        {
            $data_list = $data_list[$request->level];
        }

        // print_r($data_list);

        $rData = [];
        foreach ($data_list as $key => $value) {

            if(empty($request->level))
            {

                $rData[] = [
                    "key"=>$key,
                    "total_rbv"=>$value['total_rbv'],
                    "totalMembers"=>$value['totalMembers'],
                    "amount"=>Helpers::price_formate($value['amount']),
                    "tds_amount"=>Helpers::price_formate($value['tds_amount']),
                    "final_amount"=>Helpers::price_formate($value['final_amount']),
                    "wallet_amount"=>Helpers::price_formate($value['wallet_amount']),
                ];
            }
            else
            {
                if(!empty($value['name']))
                {

                    $rData[] = [
                        "name"=>$value['name'],
                        "sponser_id"=>$value['sponser_id'],
                        "member_id"=>$value['member_id'],
                        "rbv"=>$value['rbv'],

                        "amount"=>Helpers::price_formate($value['amount']),
                        "tds_amount"=>Helpers::price_formate($value['tds_amount']),
                        "final_amount"=>Helpers::price_formate($value['final_amount']),
                        "wallet_amount"=>Helpers::price_formate($value['wallet_amount']),
                    ];
                }
            }
        }


        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'return';
        $result['data'] = $rData;
        $result['level'] = $request->level;
        return response()->json($result, $responseCode);

    }

 }