<?php 
use Illuminate\Support\Facades\Route;


/*user routes*/
// use App\Http\Controllers\SalesMan\SalesManAuthController;
use App\Http\Controllers\SalesMan\SalesManDashboardController;
use App\Http\Controllers\SalesMan\SalesManChangePassword;
use App\Http\Controllers\SalesMan\SalesManProfileController;
use App\Http\Controllers\SalesMan\SalesManScanFace;
use App\Http\Controllers\SalesMan\SalesManNewRegisterController;
use App\Http\Controllers\SalesMan\SalesManActivationController;
use App\Http\Controllers\SalesMan\SalesManDepositController;
use App\Http\Controllers\SalesMan\SalesManWallet;
use App\Http\Controllers\SalesMan\SalesManEarning;
use App\Http\Controllers\SalesMan\SalesManLavel;
use App\Http\Controllers\SalesMan\SalesManWithdrawal;
use App\Http\Controllers\SalesMan\SalesManReward;



use App\Http\Controllers\SalesMan\SalesManKyc;
use App\Http\Controllers\SalesMan\SalesManProduct;
use App\Http\Controllers\SalesMan\SalesManTeam;
use App\Http\Controllers\SalesMan\SalesManDirectTeam;
use App\Http\Controllers\SalesMan\SalesManLeftTeam;
use App\Http\Controllers\SalesMan\SalesManRightTeam;
use App\Http\Controllers\SalesMan\SalesManIncomeHistory;
use App\Http\Controllers\SalesMan\SalesManSupport;
use App\Http\Controllers\SalesMan\SalesManMyReferral;
use App\Http\Controllers\SalesMan\SalesManTeamReferral;
use App\Http\Controllers\SalesMan\SalesManPayoutDetail;
use App\Http\Controllers\SalesMan\SalesManProfileImageController;

use App\Http\Controllers\SalesMan\SalesManCart;
use App\Http\Controllers\SalesMan\SalesManCheckout;
use App\Http\Controllers\SalesMan\SalesManMyOrder;
/*user routes end*/

// for user





Route::group(['prefix'=>'salesman','as'=>'salesman.', 'namespace'=>'User'], function(){

    

    
    
    Route::post('update-gender', [SalesManProfileController::class, 'update_gender'])->name('update-gender');

    Route::get('payment-pending', [SalesManProfileController::class, 'payment_pending'])->name('payment-pending');

    


    Route::group(['middleware' => ['salesman']], function () {
        Route::get('dashboard', [SalesManDashboardController::class, 'index'])->name('salesman-dashboard');
        


        Route::group(['prefix'=>'profile', 'as'=>'profile.', 'namespace'=>'User'], function(){
            Route::get('/', [SalesManProfileController::class, 'index'])->name('index');
            Route::get('load_data', [SalesManProfileController::class, 'load_data'])->name('load_data');
            Route::get('edit/{id?}', [SalesManProfileController::class, 'edit'])->name('edit');
            Route::post('update', [SalesManProfileController::class, 'update'])->name('update');
        });

        Route::group(['prefix'=>'change-password', 'as'=>'change-password.', 'namespace'=>'User'], function(){
            Route::get('/', [SalesManChangePassword::class, 'index'])->name('index');
            Route::get('load_data', [SalesManChangePassword::class, 'load_data'])->name('load_data');
            Route::get('edit/{id?}', [SalesManChangePassword::class, 'edit'])->name('edit');
            Route::post('update', [SalesManChangePassword::class, 'update'])->name('update');
        });



        Route::group(['prefix'=>'profile-image', 'as'=>'profile-image.', 'namespace'=>'User'], function(){
            Route::get('/', [SalesManProfileImageController::class, 'edit'])->name('edit');
            Route::post('update', [SalesManProfileImageController::class, 'update'])->name('update');
            Route::post('upload-temp', [SalesManProfileImageController::class, 'upload_temp'])->name('upload-temp');
        });
        

        Route::group(['prefix'=>'product', 'as'=>'product.', 'namespace'=>'Admin'], function(){
            Route::get('/', [SalesManProduct::class, 'index'])->name('list');
            Route::get('load_data', [SalesManProduct::class, 'load_data'])->name('load_data');
            Route::get('add', [SalesManProduct::class, 'add'])->name('add');
            Route::get('view/{id?}', [SalesManProduct::class, 'view'])->name('view');
            Route::post('update', [SalesManProduct::class, 'update'])->name('update');
        });

        Route::group(['prefix'=>'scan-face', 'as'=>'scan-face.', 'namespace'=>'Admin'], function(){
            Route::get('/', [SalesManScanFace::class, 'index'])->name('list');
        });


        Route::group(['prefix'=>'cart', 'as'=>'cart.', 'namespace'=>'Admin'], function(){
            Route::get('/', [SalesManCart::class, 'index'])->name('list');
            Route::get('load_data', [SalesManCart::class, 'load_data'])->name('load_data');
            Route::post('add', [SalesManCart::class, 'add'])->name('add');
        });
        Route::group(['prefix'=>'checkout', 'as'=>'checkout.', 'namespace'=>'Admin'], function(){
            Route::get('/', [SalesManCheckout::class, 'index'])->name('list');
            Route::post('use_wallet', [SalesManCheckout::class, 'use_wallet'])->name('use_wallet');
            Route::post('check', [SalesManCheckout::class, 'check'])->name('check');
            Route::post('place_order', [SalesManCheckout::class, 'place_order'])->name('place_order');
            Route::get('success', [SalesManCheckout::class, 'success'])->name('success');

            Route::post('compare-faces', [SalesManCheckout::class, 'compare'])->name('compare-faces');
        });

        
        Route::group(['prefix'=>'my-order', 'as'=>'my-order.', 'namespace'=>'Admin'], function(){
            Route::get('/', [SalesManMyOrder::class, 'index'])->name('list');
            Route::get('load_data', [SalesManMyOrder::class, 'load_data'])->name('load_data');
            Route::get('view/{id?}', [SalesManMyOrder::class, 'view'])->name('view');
            Route::post('rbv', [SalesManMyOrder::class, 'rbv'])->name('rbv');
        });
        



    });
});


// for user end