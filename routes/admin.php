<?php 

use Illuminate\Support\Facades\Route;





/*admin routes*/

use App\Http\Controllers\Admin\AuthController;

use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Admin\ChangePasswordController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BranchController;

use App\Http\Controllers\Admin\BlogController;

use App\Http\Controllers\Admin\PackageController;

use App\Http\Controllers\Admin\PrivacyPolicyController;

use App\Http\Controllers\Admin\TermConditionController;

use App\Http\Controllers\Admin\RefundPolicyController;

use App\Http\Controllers\Admin\PricingPolicyController;

use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Admin\BankController;



use App\Http\Controllers\Admin\DepositController;

use App\Http\Controllers\Admin\WithdrawalController;

use App\Http\Controllers\Admin\KycController;

use App\Http\Controllers\Admin\OrderController;

use App\Http\Controllers\Admin\KycOptionController;

use App\Http\Controllers\Admin\IncomeHistoryController;

use App\Http\Controllers\Admin\PayoutHistoryController;

use App\Http\Controllers\Admin\SettingController;





use App\Http\Controllers\Admin\SupportController;

use App\Http\Controllers\Admin\HomeBannerController;







use App\Http\Controllers\Admin\AdminExcelController;

use App\Http\Controllers\Admin\PaymentSettingController;

use App\Http\Controllers\Admin\InvoiceController;

use App\Http\Controllers\Admin\CouponController;

use App\Http\Controllers\Admin\OfferController;

/*admin routes end*/













// for admin









Route::post('get-package-category', [WebController::class, 'get_package_category'])->name('get-package-category');



Route::group(['middleware' => ['admin']], function () {



    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('admin_earning_calendar', [DashboardController::class, 'admin_earning_calendar'])->name('admin_earning_calendar');



    

    Route::group(['prefix'=>'admin-change-password', 'as'=>'admin-change-password.'], function(){

        Route::get('/', [ChangePasswordController::class, 'index'])->name('index');

        Route::get('load_data', [ChangePasswordController::class, 'load_data'])->name('load_data');

        Route::get('edit/{id?}', [ChangePasswordController::class, 'edit'])->name('edit');

        Route::post('update', [ChangePasswordController::class, 'update'])->name('update');

    });



    Route::group(['prefix'=>'product', 'as'=>'product.'], function(){

        Route::get('/', [ProductController::class, 'index'])->name('list');

        Route::get('load_data', [ProductController::class, 'load_data'])->name('load_data');

        Route::get('add', [ProductController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [ProductController::class, 'edit'])->name('edit');

        Route::post('update', [ProductController::class, 'update'])->name('update');

        Route::post('delete/{id?}', [ProductController::class, 'delete'])->name('delete');

        Route::get('excel_import', [ProductController::class, 'excel_import'])->name('excel_import');

        Route::post('excel_import_action', [ProductController::class, 'excel_import_action'])->name('excel_import_action');

    });



    Route::group(['prefix'=>'branch', 'as'=>'branch.'], function(){

        Route::get('/', [BranchController::class, 'index'])->name('list');

        Route::get('load_data', [BranchController::class, 'load_data'])->name('load_data');

        Route::get('add', [BranchController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [BranchController::class, 'edit'])->name('edit');

        Route::post('update', [BranchController::class, 'update'])->name('update');

        Route::post('delete/{id?}', [BranchController::class, 'delete'])->name('delete');

        Route::get('excel_import', [BranchController::class, 'excel_import'])->name('excel_import');

        Route::post('excel_import_action', [BranchController::class, 'excel_import_action'])->name('excel_import_action');

    });



    Route::group(['prefix'=>'blog', 'as'=>'blog.'], function(){

        Route::get('/', [BlogController::class, 'index'])->name('list');

        Route::get('load_data', [BlogController::class, 'load_data'])->name('load_data');

        Route::get('add', [BlogController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [BlogController::class, 'edit'])->name('edit');

        Route::post('update', [BlogController::class, 'update'])->name('update');

        Route::post('delete/{id?}', [BlogController::class, 'delete'])->name('delete');

    });



    Route::group(['prefix'=>'package', 'as'=>'package.'], function(){

        Route::get('/', [PackageController::class, 'index'])->name('list');

        Route::get('load_data', [PackageController::class, 'load_data'])->name('load_data');

        Route::get('add', [PackageController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [PackageController::class, 'edit'])->name('edit');

        Route::post('update', [PackageController::class, 'update'])->name('update');

        Route::post('delete/{id?}', [PackageController::class, 'delete'])->name('delete');

    });







  

 

    Route::group(['prefix'=>'user', 'as'=>'user.'], function(){

        Route::get('/', [UserController::class, 'index'])->name('list');

        Route::get('load_data', [UserController::class, 'load_data'])->name('load_data');

        Route::get('add', [UserController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [UserController::class, 'edit'])->name('edit');

        Route::post('update', [UserController::class, 'update'])->name('update');

        Route::post('delete/{id?}', [UserController::class, 'delete'])->name('delete');

        Route::get('account-action/{id?}', [UserController::class, 'account_action'])->name('account-action');





        // Route::post('add-income', [UserController::class, 'add_income'])->name('add-income');

        // Route::get('add-income-history', [UserController::class, 'add_income_history'])->name('add-income-history');

        // Route::post('add-income-delete/{id?}', [UserController::class, 'add_income_delete'])->name('add-income-delete');

        

        Route::get('change-password/{id?}', [UserController::class, 'change_password'])->name('change-password');

        Route::post('change-password-action', [UserController::class, 'change_password_action'])->name('change-password-action');



        // Route::get('upgrade/{id?}', [UserController::class, 'upgrade'])->name('upgrade');

        // Route::post('upgrade-action', [UserController::class, 'upgrade_action'])->name('upgrade-action');

        

        // Route::get('upgrade-without-income/{id?}', [UserController::class, 'upgrade_without_income'])->name('upgrade-without-income');

        // Route::post('upgrade-without-income-action', [UserController::class, 'upgrade_without_income_action'])->name('upgrade-without-income-action');



        // Route::get('change-package/{id?}', [UserController::class, 'change_package'])->name('change-package');

        // Route::post('change-package-action', [UserController::class, 'change_package_action'])->name('change-package-action');



        Route::get('change-sponser/{id?}', [UserController::class, 'change_sponser'])->name('change-sponser');

        Route::post('change-sponser-action', [UserController::class, 'change_sponser_action'])->name('change-sponser-action');



        // Route::get('change-sponser-revert-income/{id?}', [UserController::class, 'change_sponser_revert_income'])->name('change-sponser-revert-income');

        // Route::post('change-sponser-revert-income-action', [UserController::class, 'change_sponser_revert_income_action'])->name('change-sponser-revert-income-action');





        Route::get('activate-with-income/{id?}', [UserController::class, 'activate_with_income'])->name('activate-with-income');

        Route::post('activate-with-income-action', [UserController::class, 'activate_with_income_action'])->name('activate-with-income-action');



        // Route::get('activate-without-income/{id?}', [UserController::class, 'activate_without_income'])->name('activate-without-income');

        // Route::post('activate-without-income-action', [UserController::class, 'activate_without_income_action'])->name('activate-without-income-action');



        Route::get('reffral/{id?}', [UserController::class, 'reffral'])->name('reffral');

        Route::get('load_reffral_data', [UserController::class, 'load_reffral_data'])->name('load_reffral_data');



        Route::get('team-reffral/{id?}', [UserController::class, 'team_reffral'])->name('team-reffral');

        Route::get('load_team_reffral_data', [UserController::class, 'load_team_reffral_data'])->name('load_team_reffral_data');



        Route::get('team/{id?}', [UserController::class, 'team'])->name('team');



        Route::get('dashboard/{id?}', [UserController::class, 'dashboard'])->name('dashboard');

        Route::get('earning_calendar', [UserController::class, 'earning_calendar'])->name('earning_calendar');



        



        

        // Route::post('leaderboard_show_hide', [UserController::class, 'leaderboard_show_hide'])->name('leaderboard_show_hide');

        Route::post('send_password', [UserController::class, 'send_password'])->name('send_password');

        Route::post('block_unblock', [UserController::class, 'block_unblock'])->name('block_unblock');



        Route::get('excel_export', [UserController::class, 'excel_export'])->name('excel_export');

    });



    Route::group(['prefix'=>'bank', 'as'=>'bank.'], function(){

        Route::get('/', [BankController::class, 'index'])->name('list');

        Route::get('load_data', [BankController::class, 'load_data'])->name('load_data');

        Route::get('edit/{id?}', [BankController::class, 'edit'])->name('edit');

        Route::post('update', [BankController::class, 'update'])->name('update');

    });



    Route::group(['prefix'=>'kyc', 'as'=>'kyc.'], function(){

        Route::get('/', [KycController::class, 'index'])->name('list');

        Route::get('load_data', [KycController::class, 'load_data'])->name('load_data');

        Route::get('edit/{id?}', [KycController::class, 'edit'])->name('edit');

        Route::get('view/{id?}', [KycController::class, 'view'])->name('view');

        Route::post('update', [KycController::class, 'update'])->name('update');

    });



    Route::group(['prefix'=>'order', 'as'=>'order.'], function(){

        Route::get('/', [OrderController::class, 'index'])->name('list');

        Route::get('load_data', [OrderController::class, 'load_data'])->name('load_data');

        Route::get('edit/{id?}', [OrderController::class, 'edit'])->name('edit');

        Route::get('view/{id?}', [OrderController::class, 'view'])->name('view');

        Route::post('update', [OrderController::class, 'update'])->name('update');

    });



    Route::group(['prefix'=>'deposit', 'as'=>'deposit.'], function(){

        Route::get('/', [DepositController::class, 'index'])->name('list');

        Route::get('load_data', [DepositController::class, 'load_data'])->name('load_data');

        Route::get('edit/{id?}', [DepositController::class, 'edit'])->name('edit');

        Route::post('update', [DepositController::class, 'update'])->name('update');

    });



    Route::group(['prefix'=>'withdrawal', 'as'=>'withdrawal.'], function(){

        Route::get('/', [withdrawalController::class, 'index'])->name('list');

        Route::get('load_data', [withdrawalController::class, 'load_data'])->name('load_data');

        Route::get('edit/{id?}', [withdrawalController::class, 'edit'])->name('edit');

        Route::post('update', [withdrawalController::class, 'update'])->name('update');

    });



    Route::group(['prefix'=>'kyc-option', 'as'=>'kyc-option.'], function(){

        Route::get('/', [KycOptionController::class, 'index'])->name('list');

        Route::get('load_data', [KycOptionController::class, 'load_data'])->name('load_data');

        Route::get('add', [KycOptionController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [KycOptionController::class, 'edit'])->name('edit');

        Route::post('update', [KycOptionController::class, 'update'])->name('update');

        Route::post('delete/{id?}', [KycOptionController::class, 'delete'])->name('delete');

    });





    Route::group(['prefix'=>'income-history', 'as'=>'income-history.'], function(){

        Route::get('/', [IncomeHistoryController::class, 'index'])->name('list');

        Route::get('load_data', [IncomeHistoryController::class, 'load_data'])->name('load_data');

        Route::get('add', [IncomeHistoryController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [IncomeHistoryController::class, 'edit'])->name('edit');

        Route::post('update', [IncomeHistoryController::class, 'update'])->name('update');

        Route::post('delete/{id?}', [IncomeHistoryController::class, 'delete'])->name('delete');

        Route::get('excel_export', [IncomeHistoryController::class, 'excel_export'])->name('excel_export');            

    });







    Route::group(['prefix'=>'payout-history', 'as'=>'payout-history.'], function(){

        Route::get('/', [PayoutHistoryController::class, 'index'])->name('list');

        Route::get('load_data', [PayoutHistoryController::class, 'load_data'])->name('load_data');

        Route::get('add', [PayoutHistoryController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [PayoutHistoryController::class, 'edit'])->name('edit');

        Route::post('update', [PayoutHistoryController::class, 'update'])->name('update');

        Route::post('payout_submit', [PayoutHistoryController::class, 'payout_submit'])->name('payout_submit');

        Route::post('delete/{id?}', [PayoutHistoryController::class, 'delete'])->name('delete');



        Route::get('excel_export', [PayoutHistoryController::class, 'excel_export'])->name('excel_export');

    });



    Route::group(['prefix'=>'setting', 'as'=>'setting.'], function(){

        

        Route::get('main/{id?}', [SettingController::class, 'main'])->name('main');
        Route::post('main-update', [SettingController::class, 'main_update'])->name('main-update');



        Route::get('gst/{id?}', [SettingController::class, 'gst'])->name('gst');
        Route::post('gst-update', [SettingController::class, 'gst_update'])->name('gst-update');



        Route::get('payoutpin/{id?}', [SettingController::class, 'payoutpin'])->name('payoutpin');
        Route::post('payoutpin-update', [SettingController::class, 'payoutpin_update'])->name('payoutpin-update');



        Route::get('emails/{id?}', [SettingController::class, 'emails'])->name('emails');
        Route::post('emails-update', [SettingController::class, 'emails_update'])->name('emails-update');


        Route::get('plan/{id?}', [SettingController::class, 'plansetting'])->name('plan');
        Route::post('plan-update', [SettingController::class, 'plansetting_update'])->name('plan-update');



    });



    



    Route::group(['prefix'=>'coupon', 'as'=>'coupon.'], function(){

        Route::get('/', [CouponController::class, 'index'])->name('list');

        Route::get('load_data', [CouponController::class, 'load_data'])->name('load_data');

        Route::get('add', [CouponController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [CouponController::class, 'edit'])->name('edit');

        Route::post('update', [CouponController::class, 'update'])->name('update');

        Route::post('submit_date', [CouponController::class, 'submit_date'])->name('submit_date');

        Route::post('delete/{id?}', [CouponController::class, 'delete'])->name('delete');

    });
 



    Route::group(['prefix'=>'offer', 'as'=>'offer.'], function(){

        Route::get('/', [OfferController::class, 'index'])->name('list');

        Route::get('load_data', [OfferController::class, 'load_data'])->name('load_data');

        Route::get('add', [OfferController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [OfferController::class, 'edit'])->name('edit');

        Route::post('update', [OfferController::class, 'update'])->name('update');

        Route::post('submit_date', [OfferController::class, 'submit_date'])->name('submit_date');

        Route::post('delete/{id?}', [OfferController::class, 'delete'])->name('delete');

    });





    Route::group(['prefix'=>'home-banner', 'as'=>'home-banner.'], function(){

        Route::get('/', [HomeBannerController::class, 'index'])->name('list');

        Route::get('load_data', [HomeBannerController::class, 'load_data'])->name('load_data');

        Route::get('add', [HomeBannerController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [HomeBannerController::class, 'edit'])->name('edit');

        Route::post('update', [HomeBannerController::class, 'update'])->name('update');

        Route::post('submit_date', [HomeBannerController::class, 'submit_date'])->name('submit_date');

        Route::post('delete/{id?}', [HomeBannerController::class, 'delete'])->name('delete');

    });



    Route::group(['prefix'=>'privacy-policy', 'as'=>'privacy-policy.'], function(){

        Route::get('/', [PrivacyPolicyController::class, 'index'])->name('index');

        Route::post('update', [PrivacyPolicyController::class, 'update'])->name('update');

    });



    Route::group(['prefix'=>'pricing-policy', 'as'=>'pricing-policy.'], function(){

        Route::get('/', [PricingPolicyController::class, 'index'])->name('index');

        Route::post('update', [PricingPolicyController::class, 'update'])->name('update');

    });



    Route::group(['prefix'=>'refund-policy', 'as'=>'refund-policy.'], function(){

        Route::get('/', [RefundPolicyController::class, 'index'])->name('index');

        Route::post('update', [RefundPolicyController::class, 'update'])->name('update');

    });



    Route::group(['prefix'=>'term-condition', 'as'=>'term-condition.'], function(){

        Route::get('/', [TermConditionController::class, 'index'])->name('index');

        Route::post('update', [TermConditionController::class, 'update'])->name('update');

    });







    Route::group(['prefix'=>'support', 'as'=>'support.'], function(){

        Route::get('/', [SupportController::class, 'index'])->name('list');

        Route::get('load_data', [SupportController::class, 'load_data'])->name('load_data');

        Route::get('add', [SupportController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [SupportController::class, 'edit'])->name('edit');

        Route::get('view/{id?}', [SupportController::class, 'view'])->name('view');

        Route::post('update', [SupportController::class, 'update'])->name('update');

        Route::post('delete/{id?}', [SupportController::class, 'delete'])->name('delete');

    });











    Route::group(['prefix'=>'payment-setting', 'as'=>'payment-setting.'], function(){

        Route::get('/', [PaymentSettingController::class, 'index'])->name('list');

        Route::get('load_data', [PaymentSettingController::class, 'load_data'])->name('load_data');

        Route::get('add', [PaymentSettingController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [PaymentSettingController::class, 'edit'])->name('edit');

        Route::post('update', [PaymentSettingController::class, 'update'])->name('update');

        Route::post('delete/{id?}', [PaymentSettingController::class, 'delete'])->name('delete');

        Route::post('status-change/{id?}', [PaymentSettingController::class, 'status_change'])->name('status-change');

    });



    Route::group(['prefix'=>'invoice', 'as'=>'invoice.'], function(){

        Route::get('/', [InvoiceController::class, 'index'])->name('list');

        Route::get('load_data', [InvoiceController::class, 'load_data'])->name('load_data');

        Route::get('add', [InvoiceController::class, 'add'])->name('add');

        Route::get('edit/{id?}', [InvoiceController::class, 'edit'])->name('edit');

        Route::post('update', [InvoiceController::class, 'update'])->name('update');

        Route::post('delete/{id?}', [InvoiceController::class, 'delete'])->name('delete');

        Route::post('status-change/{id?}', [InvoiceController::class, 'status_change'])->name('status-change');

    });





});



// for admin end

