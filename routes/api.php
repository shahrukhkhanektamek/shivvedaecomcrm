<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\CommonController;
use App\Http\Controllers\Api\User\HomeController;

use App\Http\Controllers\Api\User\PostController;
use App\Http\Controllers\Api\User\PostCommentController;
use App\Http\Controllers\Api\User\FriendController;
use App\Http\Controllers\Api\User\MessageController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\User\NewRegisterController;
use App\Http\Controllers\Api\User\ActivationController;
use App\Http\Controllers\Api\User\DepositController;
use App\Http\Controllers\Api\User\TeamController;
use App\Http\Controllers\Api\User\KycController;
use App\Http\Controllers\Api\User\WalletController;
use App\Http\Controllers\Api\User\WithdrawalController;
use App\Http\Controllers\Api\User\EarningController;
use App\Http\Controllers\Api\User\LavelEarningController;
use App\Http\Controllers\Api\User\SupportController;
use App\Http\Controllers\Api\User\ProductController;
use App\Http\Controllers\Api\User\CartController;
use App\Http\Controllers\Api\User\Checkout;
use App\Http\Controllers\Api\User\MyOrder;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix'=>'user','as'=>'user.', 'namespace'=>'User'], function(){
    Route::post('login', [AuthController::class, 'login']);

    Route::post('register-otp-send', [AuthController::class, 'register_otp_send']);
    Route::post('register', [AuthController::class, 'register']);

    Route::post('send-otp', [AuthController::class, 'send_otp']);
    Route::post('submit-otp', [AuthController::class, 'submit_otp']);
    Route::post('create-password', [AuthController::class, 'create_password']);

    /*Common apis*/
    Route::get('country', [CommonController::class, 'country']);
    Route::get('package', [CommonController::class, 'package']);
    Route::get('state', [CommonController::class, 'state']);
    Route::post('search-sponser', [CommonController::class, 'search_sponser']);
    Route::post('check-sponser', [CommonController::class, 'check_sponser']);



    Route::get('logout', [AuthController::class, 'logout']);

    Route::group(['middleware' => ['api']], function () {

        Route::get('home-detail', [HomeController::class, 'detail']);


        Route::post('update-profile', [AuthController::class, 'update_profile']);
        Route::post('update-profile-image', [AuthController::class, 'update_profile_image']);
        Route::post('update-password', [AuthController::class, 'update_password']);
        Route::get('get-profile', [AuthController::class, 'get_profile']);
        Route::post('logout', [AuthController::class, 'logout']);



        Route::post('add-post', [PostController::class, 'add']);
        Route::get('post-list', [PostController::class, 'list']);
        Route::post('update-post', [PostController::class, 'update']);
        Route::post('delete-post', [PostController::class, 'delete']);
        Route::post('post-like-unlike', [PostController::class, 'post_like_unlike']);
        Route::post('post-share', [PostController::class, 'post_share']);


        Route::post('add-post-comment', [PostCommentController::class, 'add']);
        Route::get('post-comment-list', [PostCommentController::class, 'list']);
        Route::post('delete-post-comment', [PostCommentController::class, 'delete']);


        Route::get('find-friends', [FriendController::class, 'find_friends']);
        Route::get('my-friends', [FriendController::class, 'my_friends']);
        Route::get('my-friend-requests', [FriendController::class, 'my_requests']);
        Route::post('send-friend-request', [FriendController::class, 'send_request']);
        Route::get('my-send-friend-request', [FriendController::class, 'my_send_requests']);
        Route::post('response-friend-request', [FriendController::class, 'response_request']);


        Route::post('send-message', [MessageController::class, 'send']);
        Route::get('message-list', [MessageController::class, 'list']);
        Route::post('message-detail', [MessageController::class, 'detail']);


        Route::get('profile', [UserController::class, 'detail']);

        Route::post('new-register', [NewRegisterController::class, 'add']);
        Route::post('account-activation', [ActivationController::class, 'update']);

        Route::post('deposit-list', [DepositController::class, 'list']);
        Route::post('deposit-add', [DepositController::class, 'add']);
        Route::post('deposit-submit', [DepositController::class, 'pay']);


        Route::post('team-tree', [TeamController::class, 'tree']);
        Route::post('team-direct', [TeamController::class, 'direct']);
        Route::post('team-left', [TeamController::class, 'leftMember']);
        Route::post('team-right', [TeamController::class, 'rightMember']);

        
        Route::get('kyc-detail', [KycController::class, 'kycDetail']);
        Route::post('kyc-add', [KycController::class, 'kycAdd']);


        Route::get('wallet-list', [WalletController::class, 'list']);

        Route::get('withdrawal-list', [WithdrawalController::class, 'list']);
        Route::post('withdrawal-add', [WithdrawalController::class, 'add']);

        Route::get('earning-list', [EarningController::class, 'list']);
        Route::get('lavel-earning-list', [LavelEarningController::class, 'list']);

        Route::get('support-list', [SupportController::class, 'list']);
        Route::post('support-add', [SupportController::class, 'add']);
        
        Route::get('product-list', [ProductController::class, 'list']);


        Route::get('cart-list', [CartController::class, 'list']);
        Route::post('cart-add', [CartController::class, 'add']);

        Route::post('checkout', [Checkout::class, 'place_order']);
        Route::post('use-wallet', [Checkout::class, 'use_wallet']);
        Route::post('checkout-check', [Checkout::class, 'check']);

        Route::get('my-order', [MyOrder::class, 'list']);
        Route::get('my-order-detail', [MyOrder::class, 'detail']);

    });


});

