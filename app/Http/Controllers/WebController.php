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


class WebController extends Controller
{
    public function get_package_category(Request $request)
    {
        $package_id = $request->package_id;
        $package = DB::table("package")->where("id",$package_id)->first();
        if(!empty($package))
        {
            $category_ids = explode(",",$package->category);
            $category = DB::table("course_category")->whereIn("id",$category_ids)->get();
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['data'] = $category;
            return response()->json($result, $responseCode);
        }
        else
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Not Found...';
            $result['data'] = [];
            return response()->json($result, $responseCode);            
        }

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
    
    public function search_category(Request $request)
    {
        $search = $request->search;
        $list = DB::table("course_category")->select('name','id')->limit(50);
        if(!empty($search))
        {
            $search = explode(" ", $search);
            foreach ($search as $key => $value)
            {
                $list = $list->where('name','LIKE',"%{$value}%");
            }
        }
        $list = $list->get();
        $return_data = [];
        foreach ($list as $key => $value) {
            $return_data[] = array("id"=>$value->id,"text"=>$value->name,);
        }
        $data['results'] = $return_data;
        return response()->json($data, 200);
    }
    public function search_package(Request $request)
    {
        $search = $request->search;
        $list = DB::table("package")->select('name','id')->limit(50);
        if(!empty($search))
        {
            $search = explode(" ", $search);
            foreach ($search as $key => $value)
            {
                $list = $list->where('name','LIKE',"%{$value}%");
            }
        }
        $list = $list->get();
        $return_data = [];
        foreach ($list as $key => $value) {
            $return_data[] = array("id"=>$value->id,"text"=>$value->name,);
        }
        $data['results'] = $return_data;
        return response()->json($data, 200);
    }
    public function check_sponser(Request $request)
    {
        $sponser_id = $request->sponser_id;
        $placement = $request->placement;
        
        $package_id = $request->package_id;
        $sponser = DB::table("users")->select('name','id','sponser_id','sponser_name')->where(['user_id'=>$sponser_id])->first();

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
            $result['action'] = 'search';
            $result['data'] = $sponser;
            $result['package'] = '';
            $result['parent_id'] = $parent_user_id;
            $result['parent_name'] = $parent_name;
            $result['sponser_name'] = $sponser->sponser_name;
            $result['sponser_id'] = $sponser->sponser_id;
            return response()->json($result, $responseCode);
        }
        else
        {

            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Wrong Sponser ID.';
            $result['action'] = 'search';
            $result['data'] = $sponser;
            $result['package'] = '';
            $result['parent_id'] = $parent_user_id;
            $result['parent_name'] = $parent_name;
            return response()->json($result, $responseCode);
        }
    }
    public function check_coupon(Request $request)
    {
        $today = date('Y-m-d');
        $coupon = $request->coupon;
        $sponser_id = $request->sponser_id;
        $package_id = $request->package_id;

        $sponser_id = explode(sort_name,strtoupper($sponser_id));
        if(!empty($sponser_id[1])) $sponser_id = $sponser_id[1];
        else $sponser_id = 0;



        $coupon_data = DB::table("coupon")->where(['code'=>$coupon,'package_id'=>$package_id,])->first();
        
        if(!empty($coupon_data) && !empty($coupon))
        {
            $coupon_orders = DB::table("orders")->where(["sponser_id"=>$sponser_id,"promo_code"=>$coupon,"status"=>1,'product_id'=>$package_id,])->select('id')->count();
            
            if($coupon_data->to_date<$today)
            {
                $responseCode = 400;
                $result['status'] = $responseCode;
                $result['message'] = 'Coupon Expired!';
                $result['action'] = 'search';
                $result['data'] = $coupon_data;
                return response()->json($result, $responseCode);
            }
            else if($coupon_orders>=$coupon_data->limit_use)
            {
                $responseCode = 400;
                $result['status'] = $responseCode;
                $result['message'] = 'Limit Exceeded!';
                $result['action'] = 'search';
                $result['data'] = $coupon_data;
                return response()->json($result, $responseCode);
            }


            $sponser_data = MemberModel::GetSponserData($sponser_id);
            if(!empty($sponser_data))
            {
                if($sponser_data->package!=193131 && $sponser_data->package!=193132)
                {
                    $responseCode = 400;
                    $result['status'] = $responseCode;
                    $result['message'] = 'Invailid Coupon!';
                    $result['action'] = 'search';
                    $result['data'] = $coupon_data;
                    return response()->json($result, $responseCode);
                }
            }
            else
            {
                $responseCode = 400;
                $result['status'] = $responseCode;
                $result['message'] = 'Invailid Coupon!';
                $result['action'] = 'search';
                $result['data'] = $coupon_data;
                return response()->json($result, $responseCode);
            }


            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Coupon Apply Successfuly';
            $result['action'] = 'search';
            $result['data'] = $coupon_data;
            return response()->json($result, $responseCode);


        }
        else
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Invailid Coupon Code!';
            $result['action'] = 'search';
            $result['data'] = $coupon_data;
            return response()->json($result, $responseCode);
        }
    }
    public function get_video_data(Request $request)
    {
        $video_id = Crypt::decryptString($request->video_id);
        $video_data = DB::table("course_video")->select('video','gumlet_response')->where('id',$video_id)->first();
        if(!empty($video_data))
        {
            $video = url('/').'/videos/'.$video_data->video;
            $video360p = url('/').'/videos/output/360p/'.explode(".", $video_data->video)[0].'.webm';
            if(file_exists(base_path('videos/output/360p/').explode(".", $video_data->video)[0].'.webm'))
            {
                $video = $video360p;
            }

            $gumlet_response = json_decode($video_data->gumlet_response);
            if(!empty($gumlet_response))
            {
                $video = $gumlet_response->output->playback_url;
            }
            // print_r($gumlet_response);

            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Sponser Fetch Successfuly';
            $result['action'] = 'videodata';
            $result['data'] = ['video'=>$video];
            return response()->json($result, $responseCode);
        }
        else
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Wrong Sponser ID.';
            $result['action'] = 'videodata';
            $result['data'] = ['video'=>$video];
            return response()->json($result, $responseCode);
        }
    }
    public function search_my_member(Request $request)
    {
        $return_data = [];
        $id = Crypt::decryptString($request->user_id);
        $ids = MemberModel::get_child_ids($id);
        
        $search = $request->search;
        $list = DB::table("users")
        ->whereIn('id',$ids)
        ->select('name','id')->limit(100);
        if(!empty($search))
        {

            $sponser_id = $search;            
            $sponser_id = explode(sort_name,strtoupper($sponser_id));
            if(!empty($sponser_id[1])) $sponser_id = $sponser_id[1];
            else $sponser_id = 0;


            $list = $list->where('affiliate_id', $sponser_id);
            // $list = $list->where('name','LIKE',"%{$search}%");
            // $list = $list->orWhere('phone','LIKE',"%{$search}%");
            // $list = $list->orWhere('email','LIKE',"%{$search}%");
            // $list = $list->orWhere('affiliate_id','LIKE',"%{$search}%");            
            $list = $list->get();
            foreach ($list as $key => $value) {
                $return_data[] = array("id"=>Crypt::encryptString($value->id),"text"=>$value->name,);
            }
        }
        $data['results'] = $return_data;
        return response()->json($data, 200);
    }
   
    public function crop_image(Request $request)
    {
        $img_r = imagecreatefromjpeg($request->img);
        $dst_r = ImageCreateTrueColor( $request->w, $request->h ); 
        imagecopyresampled($dst_r, $img_r, 0, 0, $request->x, $request->y, $request->w, $request->h, $request->w,$request->h);
        header('Content-type: image/jpeg');
        imagejpeg($dst_r);
    }
   
    public function set_incomes(Request $request)
    {

        $page = $request->get('page',0);
        $limit = 1000;

        $user = DB::table('users')->select('id')
        ->limit($limit)
        ->offset($page*$limit)
        ->orderBy('id','desc')
        ->where('is_paid',1)
        ->get();


        foreach ($user as $key => $value)
        {   
            MemberModel::set_pairs_data($value->id);
        }
        MemberModel::generate_incomes($page,$limit);


        // $users = DB::table('users')->limit(500)->orderBy('id','desc')->select('id')->chunk(500, function ($users) {
        //     foreach ($users as $user) {
        //         MemberModel::set_pairs_data($user->id);
        //     }
        // });

        




        return redirect()->route('admin');

    }
   
    
}