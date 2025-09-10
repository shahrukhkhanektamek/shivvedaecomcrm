<?php
namespace App\Http\Controllers\SalesMan;


use App\Helper\Helpers;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use App\Helper\ImageManager;


class SalesManScanFace extends Controller
{
     protected $arr_values = array(
        'routename'=>'salesman.scan-face.', 
        'title'=>'Scan Face', 
        'table_name'=>'deposit_request',
        'page_title'=>'Scan Face',
        "folder_name"=>'salesman/scan-face',
        "upload_path"=>'upload/',
        "keys"=>'id,name',
       );  
       

    public function index(Request $request, $p_id='')
    {
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = $this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['back_btn'] = route($this->arr_values['routename'].'list');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $this->arr_values['folder_name'];
        $row = Helpers::get_user_user();
        $id = $row->id;
        return view($this->arr_values['folder_name'].'/index',compact('data','row','p_id'));
    }
    

   
    

    


}