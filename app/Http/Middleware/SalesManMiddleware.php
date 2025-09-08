<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Helper\Helpers;
class SalesManMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session('user')) {
            return redirect(route('salesman.salesman'));
        }
        else
        {
            $user = Helpers::get_user_user();
            if(empty($user))
            {
                auth()->guard('users')->logout();
                $request->session('user')->invalidate();
                return redirect(route('salesman.salesman'));
            }
            else if($user->status==0)
            {
                auth()->guard('users')->logout();
                $request->session('user')->invalidate();
                return redirect(route('salesman.salesman'));
            }
            else if($user->password!=Session('user')['password'])
            {
                auth()->guard('users')->logout();
                $request->session('user')->invalidate();
                return redirect(route('salesman.salesman'));
            }
        }
        return $next($request);
    }
}