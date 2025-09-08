<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Custom_model;
use App\Models\MemberModel;
use App\Models\User;
use Illuminate\Support\Facades\Session;
 
class UserDashboardController extends Controller
{
    public function index()
    {  
        // $user_id = 24461;
        // MemberModel::direct_income($user_id);
        $session = Session::get('user');
        $user_id = $session['id'];
        User::check_pending_payments($user_id);
        return view('user.dashboard.index');
    }
}