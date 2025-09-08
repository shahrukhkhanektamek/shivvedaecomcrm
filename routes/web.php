<?php



use Illuminate\Support\Facades\Route;



date_default_timezone_set('Asia/Kolkata');

define("user_view_folder", 'user');

define("sort_name", 'SV');

define("brevo_api", '');











use App\Http\Controllers\Web\HomeController;

use App\Http\Controllers\Web\WebPackageController;







use App\Http\Controllers\MlmTestingController;

use App\Http\Controllers\TestingController;

use App\Http\Controllers\WebController;

use App\Http\Controllers\CronJobController;

use App\Http\Controllers\MailController;

use App\Http\Controllers\DataSetController;









/*data set*/

Route::get('set_activepackage', [DataSetController::class, 'set_activepackage']);

Route::get('revert_report', [DataSetController::class, 'revert_report']);

Route::get('set_direct_team_incomes', [DataSetController::class, 'set_direct_team_incomes']);

Route::get('set_team_income', [DataSetController::class, 'set_team_income']);

Route::get('set_member_log', [DataSetController::class, 'set_member_log']);

Route::get('set_all_time_rearning', [DataSetController::class, 'set_all_time_rearning']);

Route::get('set_day_wiese_income', [DataSetController::class, 'set_day_wiese_income']);

Route::get('user_package_sale', [DataSetController::class, 'user_package_sale']);





/*data set end*/





/*test*/

Route::get('test-mlm', [MlmTestingController::class, 'index'])->name('test-mlm');

Route::get('active-id', [MlmTestingController::class, 'active_id'])->name('active-id');

Route::get('test_package', [TestingController::class, 'test_package'])->name('test_package');

Route::get('welcome_mail', [TestingController::class, 'welcome_mail']);

Route::get('income_mail', [TestingController::class, 'income_mail']);

Route::get('report', [TestingController::class, 'send_income_mails']);

Route::get('test_team_ids', [TestingController::class, 'test_team_ids'])->name('test_team_ids');

Route::get('encription_test', [TestingController::class, 'encription_test'])->name('encription_test');

Route::get('payutest', [TestingController::class, 'payutest'])->name('payutest');

Route::get('videotest', [TestingController::class, 'videotest'])->name('videotest');

/*test end*/





/*cron jobs*/



Route::get('seven-day-mails', [CronJobController::class, 'seven_day_mails']);

Route::get('next-step', [CronJobController::class, 'next_step']);



/*cron jobs end*/





Route::get('set_incomes', [WebController::class, 'set_incomes']);



Route::get('export', [AdminExcelController::class, 'export'])->name('export');

Route::get('send-email', [MailController::class, 'sendEmail']);

Route::post('crop-image', [WebController::class, 'crop_image'])->name("crop-image");







Route::post('search-sponser', [WebController::class, 'search_sponser'])->name('search-sponser');

Route::post('search-my-member', [WebController::class, 'search_my_member'])->name('search-my-member');

Route::post('check-sponser', [WebController::class, 'check_sponser'])->name('check-sponser');

Route::post('check-coupon', [WebController::class, 'check_coupon'])->name('check-coupon');

Route::post('search-category', [WebController::class, 'search_category'])->name('search-category');

Route::post('search-package', [WebController::class, 'search_package'])->name('search-package');

Route::post('get-videos-detail/{u1?}', [WebController::class, 'get_video_data'])->name('get-videos-detail');









Route::group(['prefix'=>'packages', 'as'=>'packages.', 'namespace'=>'User'], function(){

    Route::get('/', [WebPackageController::class, 'index'])->name('index');

    Route::get('load_data', [WebPackageController::class, 'load_data'])->name('load_data');

});


Route::get('tree', [HomeController::class, 'tree']);

Route::get('/{page?}', [HomeController::class, 'all'])->name('home');

Route::get('package/{slug}', [WebPackageController::class, 'detail'])->name('package-detail');



