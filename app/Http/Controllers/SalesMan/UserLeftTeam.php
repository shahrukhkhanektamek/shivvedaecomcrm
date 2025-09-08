<?php

namespace App\Http\Controllers\User;





use App\Helper\Helpers;

use Illuminate\Support\Facades\View;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Crypt;

use App\Models\MemberModel;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 

class UserLeftTeam extends Controller

{

     protected $arr_values = array(

        'routename'=>'user.left-team.', 

        'title'=>'Left Team', 

        'table_name'=>'users',

        'page_title'=>'Left Team',

        "folder_name"=>user_view_folder.'/left-team',

        "upload_path"=>'upload/',

        "page_name"=>'support-detail.php',

        "keys"=>'id,name',

        "all_image_column_names"=>array("image"),

       );  



      public function __construct()

      {

        Helpers::create_importent_columns($this->arr_values['table_name']);

      }



    public function index(Request $request)

    {

        $data['title'] = "".$this->arr_values['title'];

        $data['page_title'] = "All ".$this->arr_values['page_title'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['back_btn'] = route($this->arr_values['routename'].'index');

        $data['keys'] = $this->arr_values['keys'];          

        $data['pagenation'] = array($this->arr_values['title']);

        $data['trash'] = '';

        return view($this->arr_values['folder_name'].'/index',compact('data'));

    }

    public function load_data(Request $request)

    {

        $limit = 12;

        $status = 1;

        $order_by = "asc";

        $is_delete = 0;



        $session = Session::get('user');

        $user_id = $session['id'];



        $data['table_name'] = $this->arr_values['table_name'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['back_btn'] = route($this->arr_values['routename'].'index');



        $checkId = 0;

        $checkUser = DB::table('users')->where(['parent_id'=>$user_id,"position"=>1])->first();

        if(!empty($checkUser)) $checkId = $checkUser->id;



        $allIds[] = $checkId;

        $getAllChildIds = MemberModel::getAllChildIds($checkId);

        if(!empty($getAllChildIds)) $allIds = $getAllChildIds;



        $data_list = DB::table('users')->whereIn('id',$allIds)->orderBy('id',$order_by);

      



        $data_list = $data_list->paginate($limit);

        $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list','data'))->render();

        $responseCode = 200;

        $result['status'] = $responseCode;

        $result['message'] = 'Success';

        $result['action'] = 'view';

        $result['data'] = ["list"=>$view,];

        return response()->json($result, $responseCode);

    }

    public function excel_export(Request $request)
    {

        $session = Session::get('user');
        $user_id = $session['id'];


        $limit = 12000000000000;
        

        $checkId = 0;
        $checkUser = DB::table('users')->where(['parent_id'=>$user_id,"position"=>1])->first();
        if(!empty($checkUser)) $checkId = $checkUser->id;
        $allIds[] = $checkId;
        $getAllChildIds = MemberModel::getAllChildIds($checkId);
        if(!empty($getAllChildIds)) $allIds = $getAllChildIds;



        $data_list = DB::table('users')->whereIn('id',$allIds)->orderBy('id','desc');
        $data_list = $data_list->paginate($limit);




        $columns = "Member ID.,Name,Position,Status,All Time Income,Join Date";
        $fileName = "Left-Team-".date("Y-m-d H-i-s")."-".$user_id.".xlsx";
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $a_to_z = Helpers::a_to_z();
        
        $columns_array = explode(",", $columns);
        $i = 0;
        $j = 1;
        foreach ($columns_array as $key => $value)
        {
            $excel_field = $a_to_z[$i].$j;
            $col_name = ucfirst(str_replace("_", " ", $value));
            $sheet->setCellValue($excel_field, $col_name);
            $i++;
        }
        $rows = 2;
        foreach ($data_list as $value)
        {

            $all_time_income = \App\Models\MemberModel::all_time_income($value->id);

            $statusHtml = '';
            if($value->is_paid==1) $statusHtml = 'Paid';
            else if($value->is_paid==0) $statusHtml = 'UnPaid';

            $position = 'Left';


            $sheet->setCellValue("A" . $rows, @$value->user_id);
            $sheet->setCellValue("B" . $rows, @$value->name);
            $sheet->setCellValue("C" . $rows, @$position);
            $sheet->setCellValue("D" . $rows, @$statusHtml);
            $sheet->setCellValue("E" . $rows, @$all_time_income);
            $sheet->setCellValue("F" . $rows, @date("d M, Y h:i A", strtotime($value->add_date_time)));
            
           
           $rows++;
        } 
        $writer = new Xlsx($spreadsheet);

        $directory = base_path('excel');
        if (!file_exists($directory)) mkdir($directory, 0777, true);

        $writer->save(base_path('')."/excel/".$fileName);
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'exportdata';
        $result['url'] = url('')."/excel/".$fileName;
        $result['data'] = ["fileName"=>$fileName,];
        return response()->json($result, $responseCode);
    }

}