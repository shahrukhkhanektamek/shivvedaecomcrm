<?php
namespace App\Http\Controllers\Admin;


use App\Helper\Helpers;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Custom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use App\Helper\ImageManager;
use App\Models\IncomeHistory;
use App\Models\MemberModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IncomeHistoryController extends Controller
{
     protected $arr_values = array(
        'routename'=>'income-history.', 
        'title'=>'KYC', 
        'table_name'=>'users',
        'page_title'=>'KYC',
        "folder_name"=>'/income-report',
        "upload_path"=>'upload/',
        "page_name"=>'income-report.php',
        "keys"=>'id,name',
       );  

      public function __construct()
      {
         $this->arr_values['folder_name'] = env("admin_view_folder") . $this->arr_values['folder_name'];
        Helpers::create_importent_columns($this->arr_values['table_name']);
      }

    public function index(Request $request)
    {
        $user_id = $request->id;
        
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['excel_export'] = route($this->arr_values['routename'].'excel_export');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';

        $user_name = '';
        if(!empty($user_id))
        {
            $user_id = Crypt::decryptString($user_id);
            $user = DB::table('users')->where(['id'=>$user_id,])->first();
            if(!empty($user)) $user_name = $user->name;
        }
        return view($this->arr_values['folder_name'].'/index',compact('data','user_id','user_name'));
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
      $order_by = "id desc";
      $is_delete = 0;
      $type = 0;
      

      if(!empty($request->type)) $type = $request->type;
      if(!empty($request->limit)) $limit = $request->limit;
      // if(isset($request->status)) $status = $request->status;
      if(!empty($request->order_by)) $order_by = $request->order_by;
      if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;



        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');


        $data_list = IncomeHistory::where(['status' => $status])
        ->select(
            '*',
            'amount',
            DB::raw('(amount * tds / 100) as tds_amount'),
            DB::raw('(amount * wallet / 100) as wallet_amount'),
            DB::raw('(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount'),
            DB::raw("
                CASE 
                    WHEN type = 1 THEN 'Direct Income'
                    WHEN type = 2 THEN 'Pair Income'
                    WHEN type = 3 THEN 'Downline Income'
                    WHEN type = 4 THEN 'Upline Income'
                    WHEN type = 5 THEN 'Rank Bonus Income'
                    WHEN type = 6 THEN 'Repurchase Income'
                    ELSE 'Other'
                END AS type_name
            ")
        );

        if(!empty($request->from_date) && !empty($request->to_date))
        {
            $from_date = $request->from_date." 00:00:00";
            $to_date = $request->to_date." 23:59:00";
            $data_list->whereBetween('add_date_time', [$from_date, $to_date]);
        }
        if(!empty($request->user_id))
        {
            // echo $request->user_id;
            $user = MemberModel::GetUserData($request->user_id);
            $affiliate_id = $user->id;
            $data_list->where('member_id',$affiliate_id);
        }
        if(!empty($type)) $data_list->where('type',$type);

      
        $data_list = $data_list->latest()->paginate($limit);
        $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list','data'))->render();
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = ["list"=>$view,];
        return response()->json($result, $responseCode);
    }
    public function edit($id='')
    {   
        $id = Crypt::decryptString($id);
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "Edit ".$this->arr_values['page_title'];
        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';

        $row = IncomeHistory::where(["id"=>$id,])->first();
        if(!empty($row))
        {
            $course_category = DB::table("course_category")->where(["status"=>1,])->get();
            return view($this->arr_values['folder_name'].'/form',compact('data','row','course_category'));
        }
        else
        {
            return view('/404',compact('data'));            
        }
    }

    public function update(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        if(empty($id)) $data = new IncomeHistory;
        else $data = IncomeHistory::find($id);

        $session = Session::get('admin');
        $add_by = $session['id'];
        

        
        $data->Kyc_holder_name = $request->Kyc_holder_name;
        $data->Kyc_name = $request->Kyc_name;
        $data->account_number = $request->account_number;
        $data->account_type = $request->account_type;
        $data->ifsc = $request->ifsc;
        $data->pan = $request->pan;
        $data->rg_mobile = $request->rg_mobile;
        $data->rg_email = $request->rg_email;
        $data->address = $request->address;
        $data->kyc_message = $request->kyc_message;
        $data->kyc_step = $request->kyc_step;

        if(empty($id))
        {
            $data->passbook_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('passbook_image'));
        }
        else
        {
            if ($request->passbook_image)
                $data->passbook_image = ImageManager::update($this->arr_values['upload_path'], $data->passbook_image, 'png', $request->file('passbook_image'));
        }


        $data->add_by = $add_by;
        $data->is_delete = 0;
        if($data->save())
        {
            $action = 'add';
            if(!empty($id)) $action = 'edit';
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
    }




    public function excel_export(Request $request)
    {
       $limit = 12000000;
      $page = 1;
      $page1 = 1;
      $offset = 0;
      $status = 1;
      $table_id = 1;
      $listcheckbox = [];
      $filter_search_value = '';
      $keys = '';
      $where_query = "";
      $order_by = "id desc";
      $is_delete = 0;
      $type = 0;
      

      if(!empty($request->type)) $type = $request->type;
      // if(!empty($request->limit)) $limit = $request->limit;
      // if(isset($request->status)) $status = $request->status;
      if(!empty($request->order_by)) $order_by = $request->order_by;
      if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;



        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');


        $data_list = IncomeHistory::where(['status' => $status])
        ->select(
            '*',
            'amount',
            DB::raw('(amount * tds / 100) as tds_amount'),
            DB::raw('(amount * wallet / 100) as wallet_amount'),
            DB::raw('(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount'),
            DB::raw("
                CASE 
                    WHEN type = 1 THEN 'Direct Income'
                    WHEN type = 2 THEN 'Pair Income'
                    WHEN type = 3 THEN 'Downline Income'
                    WHEN type = 4 THEN 'Upline Income'
                    WHEN type = 5 THEN 'Rank Bonus Income'
                    WHEN type = 6 THEN 'Repurchase Income'
                    ELSE 'Other'
                END AS type_name
            ")
        );

        if(!empty($request->from_date) && !empty($request->to_date))
        {
            $from_date = $request->from_date." 00:00:00";
            $to_date = $request->to_date." 23:59:00";
            $data_list->whereBetween('add_date_time', [$from_date, $to_date]);
        }
        if(!empty($request->user_id))
        {
            // echo $request->user_id;
            $user = MemberModel::GetUserData($request->user_id);
            $affiliate_id = $user->id;
            $data_list->where('member_id',$affiliate_id);
        }
        if(!empty($type)) $data_list->where('type',$type);

      
        $data_list = $data_list->latest()->paginate($limit);





        $session = Session::get('admin');
        $Admin_id = $session['id'];
        $columns = "Affiliate ID,Name,Email,Mobile,Amount,TDS,R. Wallet,Final Amount,Type,Date Time";
        $fileName = "Income-History-".date("Y-m-d H-i-s")."-".$Admin_id.".xlsx";
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

            $user = \App\Models\MemberModel::GetUserData($value->member_id);

            $sheet->setCellValue("A" . $rows, $user->user_id);
            $sheet->setCellValue("B" . $rows, $user->name);
            $sheet->setCellValue("C" . $rows, $user->email);
            $sheet->setCellValue("D" . $rows, $user->phone);

            $sheet->setCellValue("E" . $rows, @$value->amount);
            $sheet->setCellValue("F" . $rows, @$value->tds_amount);
            $sheet->setCellValue("G" . $rows, @$value->wallet_amount);
            $sheet->setCellValue("H" . $rows, @$value->final_amount);
            $sheet->setCellValue("I" . $rows, @$value->type_name);

            $sheet->setCellValue("J" . $rows, $value->package_payment_date_time);
           
           $rows++;
        } 
        $writer = new Xlsx($spreadsheet);
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