<?php
namespace App\Http\Controllers\APi\User;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Helper\Helpers;

class CommonController extends Controller
{        
    public function country(Request $request)
    {
        $data = DB::table("countries")->orderBy('name','asc')->get();
        
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'return';
        $result['data'] = $data;

        return response()->json($result, $responseCode);
    }

    public function package(Request $request)
    {
        $data = DB::table("package")->get();
        
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'return';
        $result['data'] = $data;

        return response()->json($result, $responseCode);
    }
    public function state(Request $request)
    {
        $data = DB::table("states")->orderBy('name','asc')->get();
        
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'return';
        $result['data'] = $data;

        return response()->json($result, $responseCode);
    }
    public function search_sponser(Request $request)
    {
        $search = $request->search;
        $list = DB::table("users")->select('name','id','user_id')->limit(50);
        if(!empty($search))
        {
            $search = explode(" ", $search);
            foreach ($search as $key => $value)
            {
                $list = $list->where('name','LIKE',"%{$value}%");            
                $list = $list->orWhere('phone','LIKE',"%{$value}%");            
                $list = $list->orWhere('email','LIKE',"%{$value}%");            
                $list = $list->orWhere('user_id','LIKE',"%{$value}%");            
            }
        }
        $list = $list->get();
        $return_data[] = array("id"=>"","text"=>'All',);
        foreach ($list as $key => $value) {
            $return_data[] = array("id"=>$value->id,"text"=>$value->name.' ('.sort_name.$value->user_id.')',);
        }
        $data['results'] = $return_data;
        return response()->json($data, 200);
    }
    public function check_sponser(Request $request)
    {
        $sponser_id = $request->sponser_id;
        $placement = $request->placement;
        
        $package_id = $request->package_id;
        $sponser = DB::table("users")->select('name','id')->where(['user_id'=>$sponser_id])->first();

        $parent_id = 0;
        $parent_user_id = 0;
        $parent_name = '';
        if(!empty($placement))
        {

            $parent_id = MemberModel::getParentIdForHybridPlan($sponser_id, $placement);
            $GetUserData = MemberModel::GetUserData($parent_id);
            if(!empty($GetUserData))
            {
                $parent_user_id = $GetUserData->user_id;
                $parent_name = $GetUserData->name;
            } 
        }

        if(!empty($sponser) && !empty($sponser_id))
        {
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Sponser Fetch Successfuly';
            $result['action'] = 'return';
            $result['data'] = $sponser;
            $result['package'] = '';
            $result['parent_id'] = $parent_user_id;
            $result['parent_name'] = $parent_name;
            return response()->json($result, $responseCode);
        }
        else
        {

            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Wrong Sponser ID.';
            $result['action'] = 'return';
            $result['data'] = $sponser;
            $result['package'] = '';
            $result['parent_id'] = $parent_user_id;
            $result['parent_name'] = $parent_name;
            return response()->json($result, $responseCode);
        }
    }
    
}