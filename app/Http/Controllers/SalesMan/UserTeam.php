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

 

class UserTeam extends Controller

{

     protected $arr_values = array(

        'routename'=>'user.team.', 

        'title'=>'My Team', 

        'table_name'=>'users',

        'page_title'=>'My Team',

        "folder_name"=>user_view_folder.'/team',

        "upload_path"=>'upload/',

        "keys"=>'id,name',

       );  



      public function __construct()

      {

        Helpers::create_importent_columns($this->arr_values['table_name']);

      }



    public function index(Request $request,$id='')
    {

        $session = Session::get('user');
        $id = $request->id;
        if(!empty($id))
        {
            $id = Crypt::decryptString($id);
        }
        else
        {
            $id = $session['id'];
        }



        $data['title'] = "".$this->arr_values['title'];

        $data['page_title'] = $this->arr_values['page_title'];

        $data['upload_path'] = $this->arr_values['upload_path'];

        $data['keys'] = $this->arr_values['keys'];          

        $data['pagenation'] = array($this->arr_values['title']);

        $data['trash'] = '';

        $tree_view = MemberModel::tree_view($id);

        $id =  Crypt::encryptString($id);

        return view($this->arr_values['folder_name'].'/index',compact('data','tree_view','id'));

    }

    public function get_tree(Request $request)
    {
        
        $id = Crypt::decryptString($request->id);        
        $windowWidth = $request->windowWidth;
        
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = $this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $tree_view = MemberModel::tree_view($id);

        // if($windowWidth>767)
        // {
        //     $view = View::make($this->arr_values['folder_name'].'/computer-tree',compact('data','tree_view','id'))->render();
        // }
        // else
        // {
        //     $view = View::make($this->arr_values['folder_name'].'/mobile-tree',compact('data','tree_view','id'))->render();            
        // }

            $view = View::make($this->arr_values['folder_name'].'/mobile-tree',compact('data','tree_view','id'))->render();            


        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = 'view';
        $result['data'] = ["list"=>$view,];
        return response()->json($result, $responseCode);

    }

}