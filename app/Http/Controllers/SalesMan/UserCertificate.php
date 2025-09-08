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
use App\Models\Certificate;
use App\Models\User;


class UserCertificate extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.certificate.', 
        'title'=>'Certificate', 
        'table_name'=>'certificate',
        'page_title'=>'Certificate',
        "folder_name"=>user_view_folder.'/certificate',
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
        $data['list_url'] = route($this->arr_values['routename'].'load_data');
        $data['insert_certificate'] = route($this->arr_values['routename'].'insert-certificate');
        $row = DB::table('users')->where('id',$id)->first();
        return view($this->arr_values['folder_name'].'/index',compact('data','row'));
    }
    public function load_data(Request $request)
    {
      $limit = 12;
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


      $session = Session::get('user');
      $user_id = $session['id'];
      

        if(!empty($request->status)) $status = $request->status;

        if(!empty($request->limit)) $limit = $request->limit;
        if(!empty($request->order_by)) $order_by = $request->order_by;
        if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;



        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['view_btn_url'] = route('user.target.view');

        
        $data_list = Certificate::where(['status' => $status])->orderBy('id',$order_by)
        ->where(['user_id'=>$user_id,'result'=>1,]);
         
        $data_list = $data_list->paginate($limit);


        $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list','data'))->render();
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = ["list"=>$view,];
        return response()->json($result, $responseCode);
    }
    
    public function insert_certificate(Request $request)
    {

        $date_time = date("Y-m-d H:i:s");
        $session = Session::get('user');
        $id = $add_by = $session['id'];

        $category_id = $request->category_id;
        $course_category = DB::table("course_category")->where('id',$category_id)->first();

        $check = DB::table("certificate")->where(["user_id"=>$id,"category_id"=>$category_id,])->orderBy('id','desc')->first();
        if(!empty($check))
        {
            if($check->result==1)
            {
                $responseCode = 400;
                $result['status'] = $responseCode;
                $result['message'] = 'Already Pass!';
                $result['action'] = 'redirect';
                $result['data'] = [];
                return response()->json($result, $responseCode);
            }
            else if($check->result==2)
            {
                $date1 = strtotime($check->add_date_time);
                $date2 = strtotime(date("Y-m-d H:i:s"));

                $diffInSeconds = $date2 - $date1;
                $diffInHours = $diffInSeconds / 3600;

                if($diffInHours<24)
                {
                    $responseCode = 400;
                    $result['status'] = $responseCode;
                    $result['message'] = 'Try After Sometime!';
                    $result['action'] = 'redirect';
                    $result['data'] = [];
                    return response()->json($result, $responseCode);                    
                }
            }
            else
            {
                $data = Certificate::find($check->id);
            }
        }
        else
        {
            $data = new Certificate;
        }

        

        
        $data->user_id = $id;
        $data->category_id = $request->category_id;
        $data->package_id = $request->package_id;
        $data->start_date_time = $date_time;
        $data->end_date_time = date("Y-m-d H:i:s",strtotime($date_time."$course_category->paper_duration minute"));
        $data->total_write = 0;
        $data->total_wrong = 0;
        $data->result = 0;
        $data->status = 1;    

        $data->add_by = $add_by;
        $data->add_date_time = date("Y-m-d H:i:s");
        $data->update_date_time = date("Y-m-d H:i:s");
        $data->is_delete = 0;
    

        if($data->save())
        {
            $quetions = DB::table('test')->where('category_id',$category_id)->first();
            if(empty($quetions))
            {
                $data = Certificate::find($data->id);
                $data->result = 1;
                $data->total_write = 10;
                $data->total_wrong = 0;
                $data->submit_date_time = date("Y-m-d H:i:s");
                $data->save();

                $url = route('user.certificate.generate').'/'.$data->id;
                $responseCode = 200;
                $result['status'] = $responseCode;
                $result['message'] = 'Success';
                $result['action'] = 'redirect';
                $result['url'] = $url;
                $result['data'] = [];
                return response()->json($result, $responseCode);  

            }
            else
            {
                $responseCode = 200;
                $result['status'] = $responseCode;
                $result['message'] = 'Success';
                $result['action'] = 'redirect';
                $result['url'] = route($this->arr_values['routename'].'start-test').'/'.$data->id;
                $result['data'] = [];
                return response()->json($result, $responseCode);                
            }

            


        }
    }
    public function start_test($id)
    {
        $session = Session::get('user');
        $user_id = $session['id'];
        $id = $id;

        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $data['test_submit'] = route($this->arr_values['routename'].'test-submit');


        $row = Certificate::where(["id"=>$id,"user_id"=>$user_id,])->first();

        if($row->result!=0)return redirect(url('user/dashboard'));  
        if(!empty($row))
        {
            $quetions = DB::table('test')->where('category_id',$row->category_id)->get();
            return view($this->arr_values['folder_name'].'/start-test',compact('data','row','quetions'));
        }
        else
        {
            return view('/404',compact('data'));            
        }
    }
    

    public static function test_submit(Request $request)
    {
        $session = Session::get('user');
        $user_id = $session['id'];
        $id = $request->id;
        $row = Certificate::where(["id"=>$id,"user_id"=>$user_id,])->first();
        if(!empty($row))
        {
            $quetions = DB::table('test')->where('category_id',$row->category_id)->get();
            $ans = explode(",",$request->ans);
            $submit_data = [];
            $total_write = 0;
            $total_wrong = 0;
            $test_result = 0;
            foreach ($quetions as $key => $value)
            {
                $submit_data[] = array(
                    "title"=>$value->qsn,
                    "option1"=>$value->option1,
                    "option2"=>$value->option2,
                    "option3"=>$value->option3,
                    "option4"=>$value->option4,
                    "ans"=>$value->ans,
                    "user_ans"=>$ans[$key],
                );

                if($value->ans==$ans[$key])
                {
                    $total_write +=1;
                }
                else
                {
                    $total_wrong +=1;
                }
            }

            $total_questions = count($quetions);
            $percentage = ($total_write / $total_questions) * 100;

            if($percentage>=60)
            {
                $test_result = 1;
            }
            else
            {
                $test_result = 2;
            }


            $row->submit = json_encode($submit_data);
            $row->submit_date_time = date("Y-m-d H:i:s");
            $row->total_write = $total_write;
            $row->total_wrong = $total_wrong;
            $row->result = $test_result;
            $row->save();
            

            if($test_result==1)
                $url = route('user.certificate.generate').'/'.$row->id;
            else
                $url = route('user.certificate.result-view').'/'.$row->id;                

            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success!';
            $result['action'] = 'redirect';
            $result['url'] = $url;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        else
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Something Wrong!';
            $result['action'] = 'redirect';
            $result['url'] = route('user.certificate.start-test').'/'.$row->id;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
    }
    public function result_view($id)
    {
        $session = Session::get('user');
        $user_id = $session['id'];
        $id = $id;

        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $data['test_submit'] = route($this->arr_values['routename'].'test-submit');


        $row = Certificate::where(["id"=>$id,])->first();
        if(!empty($row))
        {
            $quetions = DB::table('test')->where('category_id',$row->category_id)->get();
            return view($this->arr_values['folder_name'].'/result-view',compact('data','row','quetions'));
        }
        else
        {
            return view('/404',compact('data'));            
        }
    }
    public function generate($id)
    {
        $session = Session::get('user');
        $user_id = $session['id'];
        $id = $id;

        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $data['test_submit'] = route($this->arr_values['routename'].'test-submit');

        $row = Certificate::where(["id"=>$id,])->first();
        if(!empty($row))
        {
            $certificate_path = User::create_certificate($user_id,$row->id);
            return view($this->arr_values['folder_name'].'/certificate',compact('data','row','certificate_path'));
        }
        else
        {
            return view('/404',compact('data'));            
        }
    }
   
       
    

}