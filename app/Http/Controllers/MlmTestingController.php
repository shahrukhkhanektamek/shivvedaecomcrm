<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\MemberModel;
use App\Models\User;







class MlmTestingController extends Controller
{
    
   
    public function index()
    {
        $j=1;
        $k=1;
        $sponser_id = 6;
        for ($i = 1; $i <= 0; $i++) {
            $placement = 1;
            if($j==2)
            {
                $placement=2;
                $j=0;
            }
            $j++;

            if($k==3)
            {
                $sponser_id+=1;
                $k=0;
            }
            $k++;

            $user_id = MemberModel::GetUserId()+1;
            $name = 'User ' . ($user_id + 1);
            $data = [
                'name' => $name,
                'email' => 'test' . ($user_id + 1) . '@gmail.com',
                'phone' => '987654' . str_pad($user_id + 1, 4, '0', STR_PAD_LEFT),
                'password' => md5("123456"),
                'user_id' => $user_id,
                'sponser_id' => $sponser_id,
                'parent_id' => 0,
                'status' => 1,
                'is_paid' => 0
            ];
            $insert_id = DB::table("users")->insertGetId($data);
            if($insert_id)
            {
                $parent_id = MemberModel::getParentIdForHybridPlan($sponser_id, $placement);
                $data['parent_id'] = $parent_id;
                $data['sponser_id'] = $sponser_id;
                $data['position'] = $placement;

                DB::table("users")->where('id',$insert_id)->update($data);
                MemberModel::AddMemberLog(['id'=> $insert_id, 'user_id'=>$data['user_id'], 'name' => $name, 'sponser_id' => $sponser_id, 'side' => $placement,"parent_id"=>$parent_id, ]);
            }   
        }
    }
    public function active_id()
    {
        $users = DB::table('users')->where("is_paid",0)->limit(5000)->get();
        $package = DB::table('package')->where('id',193132)->first();
        foreach ($users as $key => $value)
        {
            DB::table('users')->where('id',$value->id)->update(["package"=>$package->id,"package_name"=>$package->name,]);
            $detail = [];
            $detail[] = ["name"=>$package->name,"qty"=>1,"amount"=>$package->sale_price,];
            $transactionData = [
                "user_id"=>$value->id,
                "amount"=>$package->sale_price,
                "type"=>1,
                "detail"=>$detail,
            ];
            MemberModel::createTransaction($transactionData);
            MemberModel::activeId($value->id);
        }
    }
    

    


}















