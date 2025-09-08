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
use App\Models\MemberModel;
use App\Models\Package;
use App\Models\CourseCategory;
use App\Models\CourseVideo;

class UserCourse extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.course.', 
        'title'=>'Course Category', 
        'table_name'=>'course_category',
        'page_title'=>'Course Category',
        "folder_name"=>user_view_folder.'/course',
        "upload_path"=>'upload/',
        "keys"=>'id,name',
       );  

      public function __construct()
      {
        Helpers::create_importent_columns($this->arr_values['table_name']);
      }

    public function index(Request $request)
    {
        $session = Session::get('user');
        $id = $session['id'];

        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $data['user_id'] = $id;
        $data['list_url'] = route('user.course.get_courses');
        $get_banner = DB::table('course_banner')->where('status',1)->get();


        $user_package_id = DB::table('users')->select('package')->where('id',$id)->first()->package;
        $user_category = explode(",",DB::table('package')->select('category')->where('id',$user_package_id)->first()->category);

        $data_list = CourseCategory::where(['status' => 1,])
        ->whereIn('id',$user_category);
        
        
        $data_list = $data_list->latest()->paginate(100);

        $data['user_id'] = $id;

        // $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list','data'))->render();


        return view($this->arr_values['folder_name'].'/index',compact('data','get_banner','data_list'));
    }
    public function get_courses(Request $request)
    {
        $session = Session::get('user');
        $id = $session['id'];

        $user_data = MemberModel::GetUserData($id);
        $affiliate_id = $user_data->affiliate_id;
        $user_id = $user_data->affiliate_id;
        $package_id = $user_data->package;

        $limit = 1200;
        $page = 1;
        $page1 = 1;
        $offset = 0;
        $status = 1;
        $table_id = 1;
        $listcheckbox = [];
        $filter_search_value = '';
        $keys = '';
        $where_query = "";
        $order_by = "desc";
        $is_delete = 0;      

        $status = $request->status;

        // if(!empty($request->limit)) $limit = $request->limit;
        if(!empty($request->order_by)) $order_by = $request->order_by;
        if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;

        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        
        $user_package_id = DB::table('users')->select('package')->where('id',$id)->first()->package;
        $user_category = explode(",",DB::table('package')->select('category')->where('id',$user_package_id)->first()->category);

        $data_list = CourseCategory::where(['status' => 1,])
        ->whereIn('id',$user_category);


        if(!empty($filter_search_value))
        {
            $data_list = $data_list->where('name','LIKE',"%{$filter_search_value}%");
        }
        
        
        $data_list = $data_list->latest()->paginate($limit);

        $data['user_id'] = $id;

        $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list','data'))->render();
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = ["list"=>$view,];
        return response()->json($result, $responseCode);
    }
    public function all_course(Request $request)
    {
        $session = Session::get('user');
        $id = $session['id'];

        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $data['user_id'] = $id;
        $data['list_url'] = route('user.course.get-all-course');

        $get_banner = DB::table('all_course_banner')->where('status',1)->get();
        $data_list = DB::table('course_category as course_category')
        ->select('course_category.*','course_category.id as category_id')
        ->get();



        return view($this->arr_values['folder_name'].'/index',compact('data','get_banner','data_list'));
    }
    public function get_all_course(Request $request)
    {
        $session = Session::get('user');
        $id = $session['id'];

        $user_data = MemberModel::GetUserData($id);
        $affiliate_id = $user_data->affiliate_id;
        $user_id = $user_data->affiliate_id;
        $package_id = $user_data->package;

        $limit = 12000;
        $page = 1;
        $page1 = 1;
        $offset = 0;
        $status = 1;
        $table_id = 1;
        $listcheckbox = [];
        $filter_search_value = '';
        $keys = '';
        $where_query = "";
        $order_by = "desc";
        $is_delete = 0;      

        $status = $request->status;

        // if(!empty($request->limit)) $limit = $request->limit;
        if(!empty($request->order_by)) $order_by = $request->order_by;
        if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;

        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];

        $data_list = DB::table('course_category as course_category')
        ->select('course_category.*','course_category.id as category_id');

        if(!empty($filter_search_value))
        {
            $data_list = $data_list->where('course_category.name','LIKE',"%{$filter_search_value}%");
        }
        $data_list = $data_list->get();

        $data['user_id'] = $id;
        $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list','data'))->render();
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = ["list"=>$view,];
        return response()->json($result, $responseCode);
    }
    public function course_detail(Request $request)
    {

        $session = Session::get('user');
        $user_id = $session['id'];

        $id = Crypt::decryptString($request->id);
        
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $data['user_id'] = $id;

        $row = CourseCategory::where(["id"=>$id,])->first();
        if(!empty($row))
        {
            $course_video = CourseVideo::where(["category"=>$row->id,'status'=>1,])->first();
            if(!empty($course_video))
                return redirect(url('user/course/course-video-detail/'.Crypt::encryptString($course_video->id)));
            else                
                return view($this->arr_values['folder_name'].'/video-not-available',compact('data','row','course_video'));
        }
        else
        {
            return view('user/block',compact('data','row'));            
        }
    }
    public function course_video_detail(Request $request, $id)
    {
        $session = Session::get('user');
        $user_id = $session['id'];

        $id = Crypt::decryptString($id);        
        
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $data['video_id'] = $id;

        $row = CourseVideo::where(["id"=>$id,'status'=>1,])->first();


        $user_package_id = DB::table('users')->select('package')->where('id',$user_id)->first()->package;
        $user_category = explode(",",DB::table('package')->select('category')->where('id',$user_package_id)->first()->category);
 
        
        if(!in_array($row->category,$user_category)) return redirect(url('user/dashboard'));


        if(!empty($row))
        {
            $course_video = CourseVideo::where(["category"=>$row->category,'status'=>1,])->get();

            $category = DB::table('course_category')->where('id',$row->category)->first();

            return view($this->arr_values['folder_name'].'/video-detail',compact('data','row','course_video','category'));
        }
        else
        {
            return view('user/block',compact('data','row'));            
        }
    }
    public function video_start(Request $request)
    {
        $session = Session::get('user');
        $user_id = $session['id'];
        $id = Crypt::decryptString($request->video_id);        
        $row = CourseVideo::where(["id"=>$id,'status'=>1,])->first();
        if(!empty($row))
        {

            $start_date_time = date("Y-m-d H:i:s");

            $insert_data = [
                "video_id"=>$id,
                "user_id"=>$user_id,
                "duration"=>0,
                "start_date_time"=>$start_date_time,
                "end_date_time"=>$start_date_time,
                "status"=>1,
            ];

            $insert_id = DB::table('video_play_detail')->insertGetId($insert_data);
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = 'view';
            $result['data'] = ["insert_id"=>$insert_id,];
            return response()->json($result, $responseCode);
        }
        else
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = 'view';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }        
    }
    public function video_end(Request $request)
    {
        $session = Session::get('user');
        $user_id = $session['id'];
        $id = Crypt::decryptString($request->video_id);        
        $insert_id = $request->insert_id;  

        $row = CourseVideo::where(["id"=>$id,'status'=>1,])->first();
        $video_play_detail = DB::table("video_play_detail")->where("id",$insert_id)->first();


        if(!empty($row) && !empty($video_play_detail))
        {

            $end_date_time = date("Y-m-d H:i:s");
            $startTime = $video_play_detail->start_date_time;
            $endTime = $end_date_time;
            $start = new \DateTime($startTime);
            $end = new \DateTime($endTime);
            $interval = $start->diff($end);
            $durationInSeconds = ($interval->h * 3600) + ($interval->i * 60) + $interval->s;


            $insert_data = [
                "video_id"=>$id,
                "user_id"=>$user_id,
                "duration"=>$durationInSeconds,
                "end_date_time"=>$end_date_time,
                "status"=>1,
            ];

            if($durationInSeconds>5)
                DB::table('video_play_detail')->where(["id"=>$insert_id,])->update($insert_data);
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = 'view';
            $result['data'] = ["insert_id"=>$insert_id,];
            return response()->json($result, $responseCode);
        }
        else
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Error!';
            $result['action'] = 'view';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }        
    }
   
       
    

}