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
use App\Models\PayoutHistory;
use App\Models\MemberModel;
use App\Models\Setting;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
class PayoutHistoryController extends Controller
{
     protected $arr_values = array(
        'routename'=>'payout-history.', 
        'title'=>'KYC', 
        'table_name'=>'users',
        'page_title'=>'KYC',
        "folder_name"=>'/payout-report',
        "upload_path"=>'upload/',
        "page_name"=>'payout-report.php',
        "keys"=>'id,name',
       );  

      public function __construct()
      {
         $this->arr_values['folder_name'] = env("admin_view_folder") . $this->arr_values['folder_name'];
        Helpers::create_importent_columns($this->arr_values['table_name']);
      }

    public function index(Request $request)
    {
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "All ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        return view($this->arr_values['folder_name'].'/index',compact('data'));
    }
    public function load_data(Request $request)
    {
      $limit = 12;
      $page = 1;
      $page1 = 1;
      $offset = 0;
      $status = 0;
      $table_id = 1;
      $listcheckbox = [];
      $filter_search_value = '';
      $keys = '';
      $where_query = "";
      $order_by = "desc";
      $is_delete = 0;
      

      $search_type = $request->search_type;
      $kyc = $request->kyc;

        if(!empty($request->limit)) $limit = $request->limit;
        if(isset($request->status)) $status = $request->status;
        if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;



        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');


        $payment = '';
        if($status==1) $payment = 1;
        if($status==2) $payment = 0;
        $data_list = DB::table('report')
        ->leftJoin("users as users", "users.id", "=", "report.member_id")
        ->where(['report.status' => 1])
        ->whereIn("report.type", [1, 2, 3, 4, 5, 6])
        ->select(
            'report.member_id',
            'users.name',
            DB::raw('SUM(amount) as amount'),
            DB::raw('SUM(amount * tds / 100) as tds_amount'),
            DB::raw('SUM(amount * wallet / 100) as wallet_amount'),
            DB::raw('SUM(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
        )
        ->groupBy('report.member_id', 'users.name');

            
        if(!empty($status))
        {            
            $data_list->where(['report.payment' => $payment]);
        }    
        if($kyc==1)
        {
            $data_list->where(['users.kyc_step' => 1]);
        }


        if($search_type==1)
        {
            if(!empty($request->from_date) && !empty($request->to_date))
            {
                $from_date = $request->from_date." 00:00:00";
                $to_date = $request->to_date." 23:59:00";
                $data_list->whereBetween('report.package_payment_date_time', [$from_date, $to_date]);
            }
        }
        if(!empty($request->user_id))
        {
            $user = MemberModel::GetUserData($request->user_id);
            $affiliate_id = $user->id;
            $data_list->where('report.member_id',$affiliate_id);
        }

        if(empty($request->amount))
        {
            $data_list = $data_list->paginate($limit);
        }
        else
        {
            $data_list = DB::table(DB::raw("({$data_list->toSql()}) as subquery"))
            ->mergeBindings($data_list) // you need to get the bindings from the original query
            ->where('final_amount', '>=', $request->amount)
            ->paginate($limit);
        }





        

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

        $row = PayoutHistory::where(["id"=>$id,])->first();
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
        if(empty($id)) $data = new PayoutHistory;
        else $data = PayoutHistory::find($id);

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

    public function payout_submit(Request $request)
    {
        $pin = $request->pin;
        $setting = Setting::get();
        $setting_pin = $setting['payoutpin'];

        if(empty($pin))
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Invalid PIN!';
            $result['action'] = 'modalform';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        else if($setting_pin!=$pin)
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'PIN Not Match!';
            $result['action'] = 'modalform';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }

        $date_time = date("Y-m-d H:i:s");



        $limit = 12000000;
        $search_type = $request->search_type;
        $kyc = $request->kyc;
        if(isset($request->status)) $status = $request->status;
        if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;
        $payment = '';
        if($status==1) $payment = 1;
        if($status==2) $payment = 0;
        $data_list = DB::table('report')
        ->leftJoin("users as users", "users.id", "=", "report.member_id")
        ->where(['report.status' => 1])
        ->whereIn("report.type", [1, 2, 3, 4, 5, 6])
        ->select(
            'report.member_id',
            'users.name',
            DB::raw('SUM(amount) as amount'),
            DB::raw('SUM(amount * tds / 100) as tds_amount'),
            DB::raw('SUM(amount * wallet / 100) as wallet_amount'),
            DB::raw('SUM(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
        )
        ->groupBy('report.member_id', 'users.name');

            
        if(!empty($status))
        {            
            $data_list->where(['report.payment' => $payment]);
        }    
        if($kyc==1)
        {
            $data_list->where(['users.kyc_step' => 1]);
        }


        if($search_type==1)
        {
            if(!empty($request->from_date) && !empty($request->to_date))
            {
                $from_date = $request->from_date." 00:00:00";
                $to_date = $request->to_date." 23:59:00";
                $data_list->whereBetween('report.package_payment_date_time', [$from_date, $to_date]);
            }
        }
        if(!empty($request->user_id))
        {
            $user = MemberModel::GetUserData($request->user_id);
            $affiliate_id = $user->id;
            $data_list->where('report.member_id',$affiliate_id);
        }

        if(empty($request->amount))
        {
            $data_list = $data_list->paginate($limit);
        }
        else
        {
            $data_list = DB::table(DB::raw("({$data_list->toSql()}) as subquery"))
            ->mergeBindings($data_list) // you need to get the bindings from the original query
            ->where('final_amount', '>=', $request->amount)
            ->paginate($limit);
        }


        $affiliate_ids = [];
        foreach ($data_list as $key => $value) {
            $affiliate_ids[] = $value->member_id;
        }

        $update = DB::table('report')
        ->whereIn('member_id',$affiliate_ids)
        ->where(['status'=>1,'payment'=>0,]);

        if($search_type==1)
        {
            if(!empty($request->from_date) && !empty($request->to_date))
            {
                $from_date = $request->from_date." 00:00:00";
                $to_date = $request->to_date." 23:59:00";
                $update->whereBetween('report.package_payment_date_time', [$from_date, $to_date]);
            }
        }


        $update->update(['payment'=>1,'payout_date_time'=>$date_time,]);



        // die;
        // DB::table('report')->where(['status'=>1,'payment'=>0,])->update(['payment'=>1,'payout_date_time'=>$date_time,]);
        if(1==1)
        {
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = 'modalform';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        else
        {
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Success';
            $result['action'] = 'modalform';
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
    }

    public function excel_export(Request $request)
    {
      $limit = 1200000000000;
      $page = 1;
      $page1 = 1;
      $offset = 0;
      $status = 0;
      $table_id = 1;
      $listcheckbox = [];
      $filter_search_value = '';
      $keys = '';
      $where_query = "";
      $order_by = "desc";
      $is_delete = 0;
      $search_type = $request->search_type;
      $kyc = $request->kyc;

        if(isset($request->status)) $status = $request->status;
        if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;


        $payment = '';
        if($status==1) $payment = 1;
        if($status==2) $payment = 0;
        $data_list = DB::table('report')
        ->leftJoin("users as users", "users.id", "=", "report.member_id")
        ->where(['report.status' => 1])
        ->whereIn("report.type", [1, 2, 3, 4, 5, 6])
        ->select(
            'report.member_id',
            'users.name',
            DB::raw('SUM(amount) as amount'),
            DB::raw('SUM(amount * tds / 100) as tds_amount'),
            DB::raw('SUM(amount * wallet / 100) as wallet_amount'),
            DB::raw('SUM(amount - (amount * tds / 100) - (amount * wallet / 100)) as final_amount')
        )
        ->groupBy('report.member_id', 'users.name');

            
        if(!empty($status))
        {            
            $data_list->where(['report.payment' => $payment]);
        }    
        if($kyc==1)
        {
            $data_list->where(['users.kyc_step' => 1]);
        }


        if($search_type==1)
        {
            if(!empty($request->from_date) && !empty($request->to_date))
            {
                $from_date = $request->from_date." 00:00:00";
                $to_date = $request->to_date." 23:59:00";
                $data_list->whereBetween('report.package_payment_date_time', [$from_date, $to_date]);
            }
        }
        if(!empty($request->user_id))
        {
            $user = MemberModel::GetUserData($request->user_id);
            $affiliate_id = $user->id;
            $data_list->where('report.member_id',$affiliate_id);
        }

        if(empty($request->amount))
        {
            $data_list = $data_list->paginate($limit);
        }
        else
        {
            $data_list = DB::table(DB::raw("({$data_list->toSql()}) as subquery"))
            ->mergeBindings($data_list) // you need to get the bindings from the original query
            ->where('final_amount', '>=', $request->amount)
            ->paginate($limit);
        }





        $session = Session::get('admin');
        $Admin_id = $session['id'];
        $columns = "User Name,Bank Name,Bank Holder Name,Bank Account No.,IFSC Code,PanCard,Phone,Amount,TDS,R. Wallet,Final Amount,Status,KYC";
        $fileName = "Payout-Detail-".date("Y-m-d H-i-s")."-".$Admin_id.".xlsx";
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
            $user = \App\Models\User::where('id',$value->member_id)->select('bank_holder_name','account_number','ifsc','rg_mobile','bank_name','user_id','kyc_step','pan')->first();

            $statusHtml = '';
            if($status==1) $statusHtml = 'Paid';
            else if($status==2) $statusHtml = 'UnPaid';
            else if($status==0) $statusHtml = 'Income';

            $kycStatus = '';
            if($user->kyc_step==1)$kycStatus ='KYC Complete';            
            if($user->kyc_step==2)$kycStatus = 'KYC Under Review';
            if($user->kyc_step==3) $kycStatus = 'KYC Rejected';
            if($user->kyc_step==0) $kycStatus = 'KYC Not Update';
            


            $sheet->setCellValue("A" . $rows, @$user->bank_holder_name.', '.sort_name.$user->user_id);
            $sheet->setCellValue("B" . $rows, @$user->bank_name);
            $sheet->setCellValue("C" . $rows, @$user->bank_holder_name);
            $sheet->setCellValue("D" . $rows, @$user->account_number);
            $sheet->setCellValue("E" . $rows, @$user->ifsc);
            $sheet->setCellValue("F" . $rows, @$user->pan);
            $sheet->setCellValue("G" . $rows, @$user->rg_mobile);
            $sheet->setCellValue("H" . $rows, @$value->amount);
            $sheet->setCellValue("I" . $rows, @$value->tds_amount);
            $sheet->setCellValue("J" . $rows, @$value->wallet_amount);
            $sheet->setCellValue("K" . $rows, @$value->final_amount);
            $sheet->setCellValue("L" . $rows, $statusHtml);
            $sheet->setCellValue("M" . $rows, $kycStatus);
           
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