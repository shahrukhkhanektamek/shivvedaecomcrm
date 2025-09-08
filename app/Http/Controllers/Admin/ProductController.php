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
use App\Models\Product;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
class ProductController extends Controller
{
     protected $arr_values = array(
        'routename'=>'product.', 
        'title'=>'Product', 
        'table_name'=>'product',
        'page_title'=>'Product',
        "folder_name"=>'/product',
        "upload_path"=>'upload/',
        "page_name"=>'product-detail.blade.php',
        "keys"=>'id,name',
        "all_image_column_names"=>array("image"),
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
        $data['excel_import'] = route($this->arr_values['routename'].'excel_import');
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['add_btn_url'] = route($this->arr_values['routename'].'add');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');
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
      $status = 1;
      $table_id = 1;
      $listcheckbox = [];
      $filter_search_value = '';
      $keys = '';
      $where_query = "";
      $order_by = "desc";
      $is_delete = 0;
      

      if(!empty($request->limit)) $limit = $request->limit;
      if($request->status==0) $status = $request->status;
      if(!empty($request->order_by)) $order_by = $request->order_by;
      if(!empty($request->filter_search_value)) $filter_search_value = $request->filter_search_value;

      // echo $status;

        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['add_btn_url'] = route($this->arr_values['routename'].'add');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');



      $data_list = Product::where(['status' => $status])->orderBy('id',$order_by);
      
      if(!empty($filter_search_value))
      {
        $filter_search_value = explode(" ", $filter_search_value);
        foreach ($filter_search_value as $key => $value)
        {
            $data_list = $data_list->where('name','LIKE',"%{$value}%");            
        }
      }




      $data_list = $data_list->latest()->paginate($limit);
      $view = View::make($this->arr_values['folder_name'].'/table',compact('data_list','data'))->render();
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = ["list"=>$view,];
        return response()->json($result, $responseCode);
    }
    public function add()
    {
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "Add ".$this->arr_values['page_title'];
        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['add_btn_url'] = route($this->arr_values['routename'].'add');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $row = [];
        return view($this->arr_values['folder_name'].'/form',compact('data','row'));
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
        $data['add_btn_url'] = route($this->arr_values['routename'].'add');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        $data['delete_btn_url'] = route($this->arr_values['routename'].'delete');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';

        $row = Product::where(["id"=>$id,])->first();
        if(!empty($row))
        {
            return view($this->arr_values['folder_name'].'/form',compact('data','row'));
        }
        else
        {
            return view('/404',compact('data'));            
        }
    }

    public function update(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        if(empty($id)) $data = new Product;
        else $data = Product::find($id);

        $session = Session::get('admin');
        $add_by = $session['id'];
        
        

        
        $data->name = $request->name;
        $data->real_price = $request->real_price;
        $data->sale_price = $request->sale_price;
        $data->description = $request->description;        
        $data->sort_description = $request->sort_description;        
        $data->information = $request->information;        
        $data->bv = $request->bv;        
        $data->status = $request->status;
        

        

        if(empty($id))
        {
            $data->display_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('display_image'));
            $data->inner_image = ImageManager::upload($this->arr_values['upload_path'], 'png', $request->file('inner_image'));
        }
        else
        {
            if ($request->display_image)
                $data->display_image = ImageManager::update($this->arr_values['upload_path'], $data->display_image, 'png', $request->file('display_image'));
            if ($request->inner_image) 
                $data->inner_image = ImageManager::update($this->arr_values['upload_path'], $data->inner_image, 'png', $request->file('inner_image'));
        }


        $data->add_by = $add_by;
        $data->is_delete = 0;
        if($data->save())
        {
            $name = $data->name;
            if(empty($request->slug)) $slug = Helpers::slug($name);
            else $slug = Helpers::slug($request->slug);
            $p_id = $data->id;
            $table_name = $this->arr_values['table_name'];
            $new_slug = Helpers::insert_slug($slug,$p_id,$table_name,$this->arr_values['page_name']);

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
    public function delete(Request $request, $id)
    {
        $id = Crypt::decryptString($request->id);
        $data = Product::find($id);
        if($data->delete())
        {
            $responseCode = 200;
            $result['status'] = $responseCode;
            $result['message'] = 'Delete Successfuly';
            $result['action'] = 'delete';
            $result['data'] = [];            
        }
        else
        {
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Delete Not Successfuly';
            $result['action'] = 'delete';
            $result['data'] = [];            
        }
        return response()->json($result, $responseCode);
    }

    public function excel_import()
    {
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = "Upload Excel";
        $data['table_name'] = $this->arr_values['table_name'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'excel_import_action');
        $row = [];
        return view($this->arr_values['folder_name'].'/excel_import',compact('data','row'));
    }
    public function excel_import_action(Request $request)
    {

        $table_name = 'product_temp';
        // Validate that a file is uploaded
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rowIterator = $sheet->getRowIterator();

        
        $i = 0;
        
        foreach ($rowIterator as $key => $row)
        {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $data = [];
            foreach ($cellIterator as $key => $cell) {
                $data[] = $cell->getValue();
            }

            if($i==0)
            {
                foreach($data as $keydata=>$valueData)
                {
                    $columan = Helpers::slug2($valueData);
                    $dataColumn[] = Helpers::check_column_and_ceate($columan,$table_name);
                }
            }
            if($i>0)
            {
                $insert_data = [];
                foreach($dataColumn as $keydata=>$valueData)
                {
                    $col = $dataColumn[$keydata];
                    $val = $data[$keydata];
                    $insert_data[$col] = $val;
                }
                    // print_r($dataColumn);
                $user_inserted_id  = DB::table($table_name)->insertGetId($insert_data);
                // die;

                // $insert_user_data = [
                //     "user_id"=>$user_id,
                //     "is_delete"=>0,
                //     "status"=>1,
                //     "role"=>2,
                //     "gender"=>1,
                //     "kyc_step"=>1,
                //     "add_by"=>1,
                //     "update_date_time"=>date("Y-m-d H:i:s"),
                //     "add_date_time"=>date("Y-m-d H:i:s"),
                //     "password"=>md5("Admin@!@#{}:@@@"),
                //     "email"=>'',
                //     "unit_number"=>$data[1],
                //     "name"=>$data[2],
                //     "phone"=>$data[4],
                //     "address"=>$data[5],
                // ];
                // $user_inserted_id  = DB::table($table_name)->insertGetId($insert_user_data);


            }
            $i++;
        }

        
        $action = 'add';
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = $action;
        $result['data'] = [];
        return response()->json($result, $responseCode);
    }

}