<?php
namespace App\Http\Controllers\Admin;


use App\Helper\Helpers;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

 
class PricingPolicyController extends Controller
{
     protected $arr_values = array(
        'routename'=>'pricing-policy.', 
        'title'=>'Pricing Policy', 
        'table_name'=>'pricing_policy',
        'page_title'=>'Pricing Policy',
        "folder_name"=>'/pricing-policy',
        "upload_path"=>'upload/',
        "page_name"=>'cource-detail.php',
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
        $data['page_title'] = "Update ".$this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['back_btn'] = route($this->arr_values['routename'].'index');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $row = DB::table($this->arr_values['table_name'])->where(["id"=>1,])->first();
        return view($this->arr_values['folder_name'].'/form',compact('data','row'));
    }
   
  
   

    public function update(Request $request)
    {
        $id = Crypt::decryptString($request->id);

        $session = Session::get('admin');
        $add_by = $session['id'];
        
        $data['status'] = $request->status;       
        $data['description'] = $request->description;       

        DB::table($this->arr_values['table_name'])->where('id',$id)->update($data);
        $action = 'edit';
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = $action;
        $result['data'] = [];
        return response()->json($result, $responseCode);

    }
   

}