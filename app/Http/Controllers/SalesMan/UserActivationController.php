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
use App\Models\User;
use App\Models\Package;
use App\Models\MemberModel;
use App\Models\MailModel;
 
class UserActivationController extends Controller
{
     protected $arr_values = array(
        'routename'=>'user.activation.', 
        'title'=>'Activation', 
        'table_name'=>'users',
        'page_title'=>'Activation',
        "folder_name"=>user_view_folder.'/activation',
        "upload_path"=>'upload/',
        "keys"=>'id,name',
       );  

      public function __construct()
      {
        Helpers::create_importent_columns($this->arr_values['table_name']);
      }

    public function index(Request $request)
    {
        $data['title'] = "".$this->arr_values['title'];
        $data['page_title'] = $this->arr_values['page_title'];
        $data['upload_path'] = $this->arr_values['upload_path'];
        $data['submit_url'] = route($this->arr_values['routename'].'update');
        $data['edit_btn_url'] = route($this->arr_values['routename'].'edit');
        $data['keys'] = $this->arr_values['keys'];          
        $data['pagenation'] = array($this->arr_values['title']);
        $data['trash'] = '';
        $this->arr_values['folder_name'];
        $row = Helpers::get_user_user();
        $id = $row->id;
        return view($this->arr_values['folder_name'].'/index',compact('data','row'));
    }

    public function update(Request $request)
    {
        $session = Session::get('user');
        $user_id = $session['id'];
        
        $member_id = $request->member_id;
        $package_id = $request->package;

        $package = DB::table('package')->where('id',$package_id)->first();
        $package_amount = $package->sale_price;

        $walletData = DB::table('wallet')->select('wallet')->where('user_id',$user_id)->first();
        $wallet = $walletData->wallet;


        if($wallet<$package_amount)
        {
            $action = 'add';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'You have low balance!';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }

        $sponser = MemberModel::GetSponserData($member_id);
        if(empty($sponser))
        {
            $action = 'add';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'Enter Member ID!';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }
        if($sponser->is_paid==1)
        {
            $action = 'add';
            $responseCode = 400;
            $result['status'] = $responseCode;
            $result['message'] = 'This id already active!';
            $result['action'] = $action;
            $result['data'] = [];
            return response()->json($result, $responseCode);
        }

        DB::table('users')->where('id',$sponser->id)->update(["package"=>$package->id,"package_name"=>$package->name,]);

        $walletData = [
            "user_id"=>$user_id,
            "amount"=>$package_amount,
            "message"=>'ID Activate <br>Member Name: '.$sponser->name." <br>Member ID: ".$sponser->user_id,
            "type"=>2,
            "wallet_type"=>3, // 1=depoit,2=earning wallet,3=deduct both
        ];
        Helpers::wallet_credir_debit($walletData);

        MemberModel::activeId($sponser->id);

        
        $detail = [];
        $detail[] = ["name"=>$package->name,"qty"=>1,"amount"=>$package->sale_price,];
        $transactionData = [
            "user_id"=>$sponser->id,
            "amount"=>$package->sale_price,
            "type"=>1,
            "detail"=>$detail,
        ];
        MemberModel::createTransaction($transactionData);

        $user_id = $sponser->id;
        $email = $sponser->email;
        $transaction = DB::table("transaction")->where(["user_id"=>$user_id,"status"=>1,])->orderBy('id','desc')->first();
        $user = DB::table("users")->where(["id"=>$user_id,])->first();
        $details = [
            'to'=>$email,
            'view'=>'mailtemplate.invoice',
            'subject'=>'Invoice '.env('APP_NAME').'!',
            'body' => ["detail"=>json_decode($transaction->detail),"user"=>$user,"transaction"=>$transaction,],
        ];
        MailModel::invoice($details);

        $action = 'reload';
        $responseCode = 200;
        $result['status'] = $responseCode;
        $result['message'] = 'Success';
        $result['action'] = $action;
        $result['data'] = [];
        return response()->json($result, $responseCode);
    
    }
    


}