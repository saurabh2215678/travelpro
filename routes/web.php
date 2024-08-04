<?php
use Illuminate\Support\Facades\Route;
use App\Helpers\CustomHelper;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Sitemap
    Route::get('/sitemap.xml','SitemapController@sitemap_file');
    Route::get('/download_itinerary','HomeController@download_itinerary');

// Route::get('/sitemap/sitemap_home.xml', 'SitemapController@home');
// Route::get('/sitemap_hotels.xml', 'SitemapController@hotels');
// Route::get('/sitemap_packages.xml', 'SitemapController@packages');
// Route::get('/sitemap_blogs.xml', 'SitemapController@blogs');

Route::match(['get','post'],'/activate', 'LicenseCheckController@activate')->name('licenseActivate');

Route::group(['prefix' => 'webhooks', 'as' => 'webhooks.'], function() {
    Route::match(['get','post'], '/razorpay', 'WebhookController@razorpay')->name('razorpay');
});

$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
//=========Admin-Related-Routes===========
    Route::match(['get', 'post'], $ADMIN_ROUTE_NAME.'/login', 'Admin\LoginController@index')->name('adminLogin');
    // Route::match(['get', 'post'], 'vendor/login', 'Admin\LoginController@index')->name('adminLogin');

//===Social Login
    Route::get($ADMIN_ROUTE_NAME.'/login/{provider}', 'Admin\SocialController@redirect')->name('social_login');
    Route::get($ADMIN_ROUTE_NAME.'/login/{provider}/callback','Admin\SocialController@Callback');
//=============

    Route::post($ADMIN_ROUTE_NAME.'/ajax_login', 'Admin\LoginController@ajax_auth')->name('adminAjaxLogin');
    Route::post($ADMIN_ROUTE_NAME.'/ajax_login_otp_check', 'Admin\LoginController@ajax_authOtpCheck')->name('adminAjaxLoginOtpCheck');
    Route::match(['get', 'post'], $ADMIN_ROUTE_NAME.'/reset-password', 'Admin\LoginController@forgot')->name('adminResetPassword');
    Route::match(['get', 'post'], $ADMIN_ROUTE_NAME.'/reset-password-otp', 'Admin\LoginController@forgotOtp')->name('adminResetPasswordOtp');
    Route::match(['get', 'post'], $ADMIN_ROUTE_NAME.'/reset-password-otp-check', 'Admin\LoginController@forgotOtpCheck')->name('adminResetPasswordOtpCheck');
    Route::match(['get', 'post'], $ADMIN_ROUTE_NAME.'/reset-password-change', 'Admin\LoginController@forgotPasswordReset')->name('adminForgotPasswordReset');

    Route::group(['prefix' => 'forms', 'as' => 'forms'], function () {
    Route::post('/', 'FormsController@index')->name('.forms');
});

Route::group(['prefix' => 'crons', 'as' => 'crons.'], function() {
    Route::get('/check_order_amendments_status', 'CronController@check_order_amendments_status')->name('check_order_amendments_status');
    Route::get('/check_order_status', 'CronController@check_order_status')->name('check_order_status');
    Route::get('/check_offer_fare_cancel', 'CronController@check_offer_fare_cancel')->name('check_offer_fare_cancel');
});


// Admin
    Route::group(['namespace' => 'Admin', 'prefix' => $ADMIN_ROUTE_NAME, 'as' => $ADMIN_ROUTE_NAME.'.', 'middleware' => ['authadmin']], function() {

        Route::post('/flightApiBalance', 'HomeController@flightApiBalance')->name('flightApiBalance');
        Route::post('/search', 'HomeController@search')->name('adminSearch');

    Route::get('phpartisan', function(){
        //prd(request()->all());
        $cmd = request('cmd');
        if(!empty($cmd)){
            $exitCode = Artisan::call("$cmd");
            //dd($exitCode);
        }
    });

    //=========Import - URL: /admin==========
    Route::group(['prefix' => 'tools', 'as' => 'tools'], function() {
        Route::get('/phpinfo', 'ToolsController@phpinfo')->name('.phpinfo');
        Route::get('/crons', 'ToolsController@crons')->name('.crons');
        
        // Route::get('/flight_booking/{order_id}', 'ToolsController@flight_booking')->name('.flight_booking');
        // Route::get('/flight_booking_email/{order_id}', 'ToolsController@flight_booking_email')->name('.flight_booking_email');
        // Route::get('/flight_booking_pdf/{order_id}', 'ToolsController@flight_booking_pdf')->name('.flight_booking_pdf');
        

        Route::get('/import_destinations', 'ToolsController@import_destinations')->name('.import_destinations');
        Route::get('/import_packages', 'ToolsController@import_packages')->name('.import_packages');
        Route::get('/import_teams', 'ToolsController@import_teams')->name('.import_teams');
        Route::get('/import_faqs', 'ToolsController@import_faqs')->name('.import_faqs');
         Route::get('/import_blogs', 'ToolsController@import_blogs')->name('.import_blogs');
         Route::get('/import_testimonials', 'ToolsController@import_testimonials')->name('.import_testimonials');
         Route::get('/import_booking_enquiry', 'ToolsController@import_booking_enquiry')->name('.import_booking_enquiry');
         Route::get('/import_accommodations', 'ToolsController@import_accommodations')->name('.import_accommodations');
         Route::get('/import_cms', 'ToolsController@import_cms')->name('.import_cms');
         Route::get('/import_gallery', 'ToolsController@import_gallery')->name('.import_gallery');
         Route::get('/import_banner', 'ToolsController@import_banner')->name('.import_banner');
         Route::get('/copy_gallery', 'ToolsController@copy_gallery')->name('.copy_gallery');
         Route::get('/copy_custom_gallery/{old_table}/{old_path}/{new_path}/{main?}', 'ToolsController@copy_custom_gallery')->name('.copy_custom_gallery');
         Route::get('/package_accommodation', 'ToolsController@package_accommodation')->name('.package_accommodation');
         Route::get('/syncAccommodationPublishPrice', 'ToolsController@syncAccommodationPublishPrice')->name('.syncAccommodationPublishPrice');

         Route::get('/syncPackageActivityTheme', 'ToolsController@syncPackageActivityTheme')->name('.syncPackageActivityTheme');
         Route::get('/syncPackageActivityInclusion', 'ToolsController@syncPackageActivityInclusion')->name('.syncPackageActivityInclusion');
         Route::get('/syncPackageActivityFlag', 'ToolsController@syncPackageActivityFlag')->name('.syncPackageActivityFlag');
    });

        Route::post('/logout', 'AdminController@logout')->name('logout');
        Route::match(['get','post'],'change_password','AdminController@change_password')->name('change_password');

//=========Dashboard - URL: /admin==========
        Route::get('/', 'HomeController@index')->name('home');
        Route::match(['get', 'post'], 'verify_password', 'HomeController@verify_password');
        Route::post('ck_upload', 'HomeController@ckUpload')->name('ck_upload');

    // Roles
    Route::group(['prefix' => 'roles', 'as' => 'roles'], function() {
        Route::get('/', 'RoleController@index')->name('.index')->middleware('checkpermission:roles,view');
        Route::match(['get', 'post'], 'save/', 'RoleController@save')->name('.roles_save')->middleware('checkpermission:roles,add');
        Route::match(['get', 'post'], 'edit/{id?}', 'RoleController@save')->name('.roles_edit')->middleware('checkpermission:roles,edit');
        Route::get('showRoleDetail/{id}', 'RoleController@showRoleDetail')->name('.showRoleDetail')->middleware('checkpermission:roles,view');

    });

//========Admins==========
    Route::group(['prefix' => 'users', 'as' => 'users'], function() {

        Route::get('/', 'AdminController@index')->name('.index')->middleware('checkpermission:staff,view');
        //prd('H');
        Route::match(['get', 'post'], 'add/', 'AdminController@add')->name('.add')->middleware('checkpermission:staff,add');
        Route::match(['get', 'post'], 'edit/{id}', 'AdminController@add')->where(['id'=>'[0-9]+'])->name('.edit')->middleware('checkpermission:staff,edit');
        Route::post('delete/{id}', 'AdminController@delete')->name('.delete')->middleware('checkpermission:staff,delete');

     /* Route::get('roles', 'RoleController@index')->name('.roles')->middleware('checkpermission:roles,view');
        Route::match(['get', 'post'], 'roles/save', 'RoleController@save')->name('.roles_save')->middleware('checkpermission:roles,add');
        Route::match(['get', 'post'], 'roles/edit/{id?}', 'RoleController@save')->name('.roles_edit')->middleware('checkpermission:roles,edit');
        */
    });

//========Register-User============
    Route::group(['prefix' => 'register-users', 'as' => 'register-users','middleware' => ['allowedmodule:customer|agentuser']], function() {
        Route::get('/', 'RegisterUserController@index')->name('.index')->middleware('checkpermission:users,view');
        Route::match(['get', 'post'], 'add/', 'RegisterUserController@add')->name('.add');
        Route::match(['get', 'post'], 'edit/{id}', 'RegisterUserController@add')->where(['id'=>'[0-9]+'])->name('.edit')->middleware('checkpermission:users,edit');
        Route::post('delete/{id}', 'RegisterUserController@delete')->name('.delete')->middleware('checkpermission:users,delete');

        Route::get('login', 'RegisterUserController@login')->name('.login')->middleware('checkpermission:users|agents,edit');
        
        Route::match(['get', 'post'], 'view/{id}', 'RegisterUserController@view')->name('.view')->middleware('checkpermission:users,view');
        Route::match(['get', 'post'], 'wallet/{id}', 'RegisterUserController@walletList')->name('.wallet')->middleware('checkpermission:users,edit');
        Route::match(['get', 'post'], 'transaction_add/{id}', 'RegisterUserController@transactionAdd')->name('.transaction_add');
    });

//========Register-Agent===========
    Route::group(['prefix' => 'register-agents', 'as' => 'register-agents', 'middleware' => ['allowedmodule:agentuser']],function() {
        Route::get('/', 'UserAgentController@index')->name('.index')->middleware('checkpermission:agents,view');
        Route::match(['get', 'post'], 'add/', 'UserAgentController@add')->name('.add')->middleware('checkpermission:agents,add');
        Route::match(['get', 'post'], 'changeGroupDefault/', 'UserAgentController@changeGroupDefault')->name('.changeGroupDefault')->middleware('checkpermission:agents,edit');
        Route::match(['get', 'post'], 'updateApproved/', 'UserAgentController@updateApproved')->name('.updateApproved')->middleware('checkpermission:agents,edit');
        Route::match(['get', 'post'], 'edit/{id}', 'UserAgentController@add')->where(['id'=>'[0-9]+'])->name('.edit')->middleware('checkpermission:agents,edit');
        Route::post('ajax_delete_image', 'UserAgentController@ajax_delete_image')->name('.ajax_delete_image');
        Route::post('agent_logo_delete', 'UserAgentController@agent_logo_delete')->name('.agent_logo_delete');
        Route::post('delete/{id}', 'UserAgentController@delete')->name('.delete')->middleware('checkpermission:agents,delete');
        Route::match(['get', 'post'], 'view/{id}', 'UserAgentController@view')->name('.view')->middleware('checkpermission:agents,view');
        Route::get('/groups', 'UserAgentController@groups')->name('.groups')->middleware('checkpermission:agents,view');
        Route::match(['get', 'post'], '/addgroups', 'UserAgentController@addgroups')->name('.group_add')->middleware('checkpermission:agents,add');
        Route::match(['get', 'post'], '/editgroup/{id}', 'UserAgentController@addgroups')->name('.group_edit')->middleware('checkpermission:agents,edit');
        Route::match(['get', 'post'], 'wallet/{id}', 'UserAgentController@walletList')->name('.wallet')->middleware('checkpermission:agents,edit');
        Route::match(['get', 'post'], 'transaction_add/{id}', 'UserAgentController@transactionAdd')->name('.transaction_add')->middleware('checkpermission:agents,add');
        Route::post('ajax_offline_flight_inventory', 'UserAgentController@ajax_offline_flight_inventory')->name('.ajax_offline_flight_inventory')->middleware('checkpermission:agents,edit');
        Route::post('ajax_agent_list', 'UserAgentController@ajax_agent_list')->name('.ajax_agent_list')->middleware('checkpermission:agents,view');
    });
    //========Register-Agent===========

    Route::group(['prefix' => 'discount', 'as' => 'discount','middleware' => ['allowedmodule:agentuser']], function() {

        Route::get('/category', 'ModuleDiscountController@category')->name('.category')->middleware('checkpermission:agents,view');
        Route::match(['get', 'post'], '/addcategory', 'ModuleDiscountController@addcategory')->name('.addcategory')->middleware('checkpermission:agents,add');
        Route::match(['get', 'post'], '/editcategory/{id}', 'ModuleDiscountController@addcategory')->name('.editcategory')->middleware('checkpermission:agents,edit');

        Route::get('/agent_groups', 'ModuleDiscountController@agent_groups')->name('.agent_groups')->middleware('checkpermission:agents,view');
        Route::match(['get', 'post'], '/add_agent_groups', 'ModuleDiscountController@add_agent_groups')->name('.add_agent_groups')->middleware('checkpermission:agents,add');
        Route::match(['get', 'post'], '/edit_agent_groups/{id}', 'ModuleDiscountController@add_agent_groups')->name('.edit_agent_groups')->middleware('checkpermission:agents,edit');

        Route::match(['get', 'post'], '/add_agent_groups_discount', 'ModuleDiscountController@add_agent_groups_discount')->name('.add_agent_groups_discount')->middleware('checkpermission:agents,add');

        Route::match(['get', 'post'], '/add_custom_module_record_discount', 'ModuleDiscountController@add_custom_module_record_discount')->name('.add_custom_module_record_discount');
      });


    //========Vendors==========
    Route::group(['prefix' => 'vendors', 'as' => 'vendors','middleware' => ['allowedmodule:vendor']], function() {

        Route::get('/', 'AdminController@index')->name('.index')->middleware('checkpermission:vendor,view');
        
        Route::match(['get', 'post'], 'add/', 'AdminController@add')->name('.add')->middleware('checkpermission:vendor,add');
        Route::match(['get', 'post'], 'edit/{id}', 'AdminController@add')->where(['id'=>'[0-9]+'])->name('.edit')->middleware('checkpermission:vendor,edit');
        Route::post('delete/{id}', 'AdminController@delete')->name('.delete')->middleware('checkpermission:vendor,delete');

        Route::post('vendor_logo_delete', 'AdminController@vendor_logo_delete')->name('.vendor_logo_delete');

     /* Route::get('roles', 'RoleController@index')->name('.roles')->middleware('checkpermission:roles,view');
        Route::match(['get', 'post'], 'roles/save', 'RoleController@save')->name('.roles_save')->middleware('checkpermission:roles,add');
        Route::match(['get', 'post'], 'roles/edit/{id?}', 'RoleController@save')->name('.roles_edit')->middleware('checkpermission:roles,edit');
        */
    });

//=======Manage-Enquires=============

    Route::group(['prefix' => 'enquiries_new', 'as' => 'enquiries_new'], function() {
        Route::get('/', 'EnquiryController@new_index')->name('.index')->middleware('checkpermission:enquiries,view');
    });
    Route::group(['prefix' => 'enquiries', 'as' => 'enquiries'], function() {

       Route::get('/', 'EnquiryController@index')->name('.index')->middleware('checkpermission:enquiries,view');
       Route::match(['get', 'post'], '/add', 'EnquiryController@add')->name('.add')->middleware('checkpermission:enquiries,add');
       Route::match(['get', 'post'], '/edit/{id}', 'EnquiryController@add')->name('.edit')->middleware('checkpermission:enquiries,edit');
       Route::get('/view/{id}', 'EnquiryController@view')->name('.view')->middleware('checkpermission:enquiries,view');
       Route::post('/delete/{id}', 'EnquiryController@delete')->name('.delete')->middleware('checkpermission:enquiries,delete');
       Route::post('/ajax_enquiry_interaction', 'EnquiryController@ajax_enquiry_interaction')->name('.ajax_enquiry_interaction')->middleware('checkpermission:enquiries,view');

       Route::post('/ajax_enquiry_last_interaction', 'EnquiryController@get_last_interaction')->name('.ajax_enquiry_last_interaction')->middleware('checkpermission:enquiries,view');
       Route::post('/add_interaction', 'EnquiryController@add_interaction')->name('.add_interaction')->middleware('checkpermission:enquiries,view');
       Route::post('/ajax_enquiry_users', 'EnquiryController@ajax_enquiry_users')->name('.ajax_enquiry_users')->middleware('checkpermission:enquiries,view');
       Route::get('/assign/{enquiry_id}/{user_id}', 'EnquiryController@assign')->name('.assign')->middleware('checkpermission:enquiries,assign');
       Route::get('/remove_assign/{enquiry_id}/{user_id}', 'EnquiryController@remove_assign')->name('.remove_assign')->middleware('checkpermission:enquiries,remove_assign');

        // Route::get('/', 'ContactEnquiryController@index')->name('.index')->middleware('checkpermission:enquiries,view');
        // Route::post('/delete/{id}', 'ContactEnquiryController@delete')->name('.delete')->middleware('checkpermission:enquiries,delete');
        // Route::get('/view/{id}', 'ContactEnquiryController@view')->name('.view')->middleware('checkpermission:enquiries,view');

        /*Route::get('customize_packages', 'ContactEnquiryController@customize_this_trip')->name('.customize_packages')->middleware('checkpermission:enquiries,view');
        Route::post('customize_packages/customize_this_trip_delete/{id}', 'ContactEnquiryController@customize_this_trip_delete')->name('.customize_this_trip_delete')->middleware('checkpermission:enquiries,delete');
        Route::get('customize_packages/customize_this_trip_view/{id}', 'ContactEnquiryController@customize_this_trip_view')->name('.customize_this_trip_view')->middleware('checkpermission:enquiries,view');

        Route::get('request_details', 'ContactEnquiryController@request_details')->name('.request_details')->middleware('checkpermission:enquiries,view');
        Route::post('request_details/request_detail_delete/{id}', 'ContactEnquiryController@request_detail_delete')->name('.request_detail_delete')->middleware('checkpermission:enquiries,delete');
        Route::get('request_details/request_detail_view/{id}', 'ContactEnquiryController@request_detail_view')->name('.request_detail_view')->middleware('checkpermission:enquiries,view');

        Route::get('book_now', 'ContactEnquiryController@bookingEnquiry')->name('.book_now')->middleware('checkpermission:enquiries,view');
        Route::post('book_now/bookingDelete/{id}', 'ContactEnquiryController@bookingDelete')->name('.bookingDelete')->middleware('checkpermission:enquiries,delete');
        Route::get('book_now/bookingView/{id}', 'ContactEnquiryController@bookingView')->name('.bookingView')->middleware('checkpermission:enquiries,view');*/

        // Route::get('order_details', 'ContactEnquiryController@bookingOrderEnquiry')->name('.order_details')->middleware('checkpermission:enquiries,view');
        // Route::post('order_details/orderDelete/{id}', 'ContactEnquiryController@orderDelete')->name('.orderDelete')->middleware('checkpermission:enquiries,delete');
        // Route::get('order_details/orderView/{id}', 'ContactEnquiryController@orderView')->name('.orderView')->middleware('checkpermission:enquiries,view');

        /*Route::get('customer_reviews', 'ContactEnquiryController@customerReview')->name('.customer_reviews')->middleware('checkpermission:customer_review,view');
        Route::match(['get', 'post'],'customer_reviews/customerDelete/{id}', 'ContactEnquiryController@customerDelete')->name('.customerDelete')->middleware('checkpermission:customer_review,delete');
        Route::get('customer_reviews/customerView/{id}', 'ContactEnquiryController@customerView')->name('.customerView')->middleware('checkpermission:customer_review,view');
        Route::match(['get', 'post'], 'customer_reviews/add', 'ContactEnquiryController@add')->name('.customer_review_add')->middleware('checkpermission:customer_review,add');
        Route::match(['get', 'post'], 'customer_reviews/edit/{id}', 'ContactEnquiryController@add')->name('.customer_review_edit')->middleware('checkpermission:customer_review,edit');*/
    });
    Route::group(['prefix' => 'customer_reviews', 'as' => 'customer_reviews'], function() {
        Route::get('/', 'ContactEnquiryController@customerReview')->name('.index')->middleware('checkpermission:customer_review,view');
        Route::match(['get', 'post'],'customerDelete/{id}', 'ContactEnquiryController@customerDelete')->name('.customerDelete')->middleware('checkpermission:customer_review,delete');
        Route::get('customerView/{id}', 'ContactEnquiryController@customerView')->name('.customerView')->middleware('checkpermission:customer_review,view');
        Route::match(['get', 'post'], 'add', 'ContactEnquiryController@add')->name('.customer_review_add')->middleware('checkpermission:customer_review,add');
        Route::match(['get', 'post'], 'edit/{id}', 'ContactEnquiryController@add')->name('.customer_review_edit')->middleware('checkpermission:customer_review,edit');
    });

    Route::group(['prefix' => 'orders', 'as' => 'orders'], function() {
        Route::get('/', 'OrderController@index')->name('.index')->middleware('checkpermission:orders,view');
        Route::post('/bulk_action', 'OrderController@bulk_action')->name('.bulk_action')->middleware('checkpermission:orders,view');
        Route::get('/view/{order_id}', 'OrderController@view')->name('.view')->middleware('checkpermission:orders,view');
        Route::match(['get','post'],'/details/{order_id}', 'OrderController@details')->name('.details')->middleware('checkpermission:orders,view');

        Route::match(['get', 'post'],'/cab_driver/{order_id}', 'OrderController@cab_driver')->name('.cab_driver')->middleware('checkpermission:cabs,driver_details');
        Route::match(['get', 'post'],'/cab_driver_mail/{order_id}', 'OrderController@cab_driver_mail')->name('.cab_driver_mail')->middleware('checkpermission:cabs,driver_details');

        Route::get('/view_payments/{order_id}', 'OrderController@view_payments')->name('.view_payments')->middleware('checkpermission:orders,view');
        Route::match(['get', 'post'],'/service_voucher/{order_id?}', 'OrderController@service_voucher')->name('.service_voucher')->middleware('checkpermission:orders,view');
        
        Route::match(['get', 'post'],'/refund', 'OrderController@refund')->name('.refund')->middleware('checkpermission:orders,edit');
        Route::match(['get', 'post'],'/reject', 'OrderController@cancel_request_reject')->name('.reject')->middleware('checkpermission:orders,edit');
        Route::match(['get', 'post'],'/service_voucher_view/{order_id?}', 'OrderController@service_voucher_view')->name('.service_voucher_view')->middleware('checkpermission:orders,view');
        Route::get('/transactions', 'OrderController@transactions')->name('.transactions')->middleware('checkpermission:orders,view');

        Route::get('/transactions_view/{order_id}', 'OrderController@transactions_view')->name('.transactions_view')->middleware('checkpermission:orders,view');
         
        Route::match(['get', 'post'],'/update/{order_id}', 'OrderController@updateStatus')->name('.update')->middleware('checkpermission:orders,view');
        Route::post('/check_booking_status', 'OrderController@checkBookingStatus')->name('.checkBookingStatus')->middleware('checkpermission:orders,edit');
        Route::post('/updateOrder', 'OrderController@updateOrder')->name('.updateOrder')->middleware('checkpermission:orders,edit');

        Route::match(['get', 'post'],'/cancel_flight/{order_id?}', 'OrderController@cancel_flight')->name('.cancel_flight')->middleware('checkpermission:orders,edit');
        Route::get('/calendar_booking', 'OrderController@calendarBooking')->name('.calendar_booking')->middleware('checkpermission:orders,view');

        Route::match(['get', 'post'],'/add_payment/{order_id}', 'OrderController@addPayment')->name('.addPayment')->middleware('checkpermission:orders,edit');

        Route::post('/cancelOfflineFlight', 'OrderController@cancelOfflineFlight')->name('.cancelOfflineFlight')->middleware('checkpermission:orders,edit');
        Route::post('/changeFareOfflineFlight', 'OrderController@changeFareOfflineFlight')->name('.changeFareOfflineFlight')->middleware('checkpermission:orders,edit');
        Route::post('/confirmOfflineFlight', 'OrderController@confirmOfflineFlight')->name('.confirmOfflineFlight')->middleware('checkpermission:orders,edit');
        Route::match(['get', 'post'],'/updateOfflineFlightPassenger/{order_id}', 'OrderController@updateOfflineFlightPassenger')->name('.updateOfflineFlightPassenger')->middleware('checkpermission:orders,edit');
        Route::post('/change_status', 'OrderController@changeOrderStatus')->name('.change_status')->middleware('checkpermission:orders,edit');
    });

//=======

    Route::group(['prefix' => 'vendor-orders', 'as' => 'vendor-orders'], function() {

        Route::get('/', 'OrderController@index')->name('.index')->middleware('checkpermission:orders,view');
        Route::get('/view/{order_id}', 'OrderController@view')->name('.view')->middleware('checkpermission:orders,view');

        Route::match(['get', 'post'],'/cab_driver/{order_id}', 'OrderController@cab_driver')->name('.cab_driver')->middleware('checkpermission:cabs,driver_details');
        Route::match(['get', 'post'],'/cab_driver_mail/{order_id}', 'OrderController@cab_driver_mail')->name('.cab_driver_mail')->middleware('checkpermission:cabs,driver_details');

        Route::get('/view_payments/{order_id}', 'OrderController@view_payments')->name('.view_payments')->middleware('checkpermission:orders,view');
        Route::match(['get', 'post'],'/service_voucher/{order_id?}', 'OrderController@service_voucher')->name('.service_voucher')->middleware('checkpermission:orders,view');
        
        Route::match(['get', 'post'],'/refund', 'OrderController@refund')->name('.refund')->middleware('checkpermission:orders,edit');


        Route::match(['get', 'post'],'/service_voucher_view/{order_id?}', 'OrderController@service_voucher_view')->name('.service_voucher_view')->middleware('checkpermission:orders,view');
        Route::get('/transactions', 'OrderController@transactions')->name('.transactions')->middleware('checkpermission:orders,view');
        Route::match(['get', 'post'],'/update/{order_id}', 'OrderController@updateStatus')->name('.update')->middleware('checkpermission:orders,view');
        Route::post('/check_booking_status', 'OrderController@checkBookingStatus')->name('.checkBookingStatus')->middleware('checkpermission:orders,edit');

        Route::match(['get', 'post'],'/cancel_flight/{order_id?}', 'OrderController@cancel_flight')->name('.cancel_flight')->middleware('checkpermission:orders,edit');
        Route::get('/calendar_booking', 'OrderController@calendarBooking')->name('.calendar_booking')->middleware('checkpermission:orders,view');
    

    });

//======   

    Route::group(['prefix' => 'voucher', 'as' => 'voucher'], function() {

        Route::match(['get', 'post'],'/cab/{order_id?}', 'VoucherController@cab')->name('.cab')->middleware('checkpermission:cabs,edit_voucher');

        Route::match(['get', 'post'],'/rental/{order_id?}', 'VoucherController@rental')->name('.rental')->middleware('checkpermission:rental,edit_voucher');

        Route::match(['get', 'post'],'/activity/{order_id?}', 'VoucherController@activity')->name('.activity')->middleware('checkpermission:activity,edit_voucher');
        Route::match(['get', 'post'],'/activity_voucher_view/{order_id?}', 'VoucherController@activity_voucher_view')->name('.activity_voucher_view')->middleware('checkpermission:activity,view_voucher');
        Route::match(['get', 'post'],'/activity_voucher_pdf/{order_id?}', 'VoucherController@activity_voucher_pdf')->name('.activity_voucher_pdf')->middleware('checkpermission:activity,view_voucher');

        Route::match(['get', 'post'],'/cab_voucher_view/{order_id?}', 'VoucherController@cab_voucher_view')->name('.cab_voucher_view')->middleware('checkpermission:cabs,view_voucher');

        Route::match(['get', 'post'],'/rental_voucher_view/{order_id?}', 'VoucherController@rental_voucher_view')->name('.rental_voucher_view')->middleware('checkpermission:rental,view_voucher');

        Route::match(['get', 'post'],'/cab_voucher_sendmail/{order_id?}', 'VoucherController@cab_voucher_sendmail')->name('.cab_voucher_sendmail')->middleware('checkpermission:cabs,view_voucher');

        Route::match(['get', 'post'],'/rental_voucher_sendmail/{order_id?}', 'VoucherController@rental_voucher_sendmail')->name('.rental_voucher_sendmail')->middleware('checkpermission:rental,view_voucher');

        Route::match(['get', 'post'],'/activity_voucher_sendmail/{order_id?}', 'VoucherController@activity_voucher_sendmail')->name('.activity_voucher_sendmail')->middleware('checkpermission:activity,view_voucher');
        Route::match(['get', 'post'],'/cab_voucher_pdf/{order_id?}', 'VoucherController@cab_voucher_pdf')->name('.cab_voucher_pdf')->middleware('checkpermission:cabs,view_voucher');
        Route::match(['get', 'post'],'/rental_voucher_pdf/{order_id?}', 'VoucherController@rental_voucher_pdf')->name('.rental_voucher_pdf')->middleware('checkpermission:rental,view_voucher');
        Route::match(['get', 'post'],'/package/{order_id?}', 'VoucherController@package')->name('.package')->middleware('checkpermission:packages,edit_voucher');
        Route::match(['get', 'post'],'/package_voucher_view/{order_id?}', 'VoucherController@package_voucher_view')->name('.package_voucher_view')->middleware('checkpermission:packages,view_voucher');
        Route::match(['get', 'post'],'/package_voucher_pdf/{order_id?}', 'VoucherController@package_voucher_pdf')->name('.package_voucher_pdf')->middleware('checkpermission:packages,view_voucher');
        Route::match(['get', 'post'],'/package_voucher_sendmail/{order_id?}', 'VoucherController@package_voucher_sendmail')->name('.package_voucher_sendmail')->middleware('checkpermission:packages,view_voucher');


        Route::match(['get', 'post'],'/flight_voucher_view/{order_id?}', 'VoucherController@flight_voucher_view')->name('.flight_voucher_view')->middleware('checkpermission:orders,view');
        Route::match(['get', 'post'],'/flight_voucher_pdf/{order_id?}', 'VoucherController@flight_voucher_view')->name('.flight_voucher_pdf')->middleware('checkpermission:orders,view');
        Route::match(['get', 'post'],'/flight_voucher_sendmail/{order_id?}', 'VoucherController@flight_voucher_view')->name('.flight_voucher_sendmail')->middleware('checkpermission:orders,view');

        Route::match(['get', 'post'],'/hotel/{order_id?}', 'VoucherController@hotel')->name('.hotel')->middleware('checkpermission:accommodations,edit_voucher');
        Route::match(['get', 'post'],'/hotel_voucher_view/{order_id?}', 'VoucherController@hotel_voucher_view')->name('.hotel_voucher_view')->middleware('checkpermission:accommodations,view_voucher');

        Route::match(['get', 'post'],'/hotel_voucher_pdf/{order_id?}', 'VoucherController@hotel_voucher_pdf')->name('.hotel_voucher_pdf')->middleware('checkpermission:accommodations,view_voucher');
        Route::match(['get', 'post'],'/hotel_voucher_sendmail/{order_id?}', 'VoucherController@hotel_voucher_sendmail')->name('.hotel_voucher_sendmail')->middleware('checkpermission:accommodations,view_voucher');
    });


    //=========Coupons============
    Route::group(['prefix' => 'coupons', 'as' => 'coupons'], function () {
        Route::get('/', 'CouponController@index')->name('.index')->middleware('checkpermission:coupons,view');
        Route::match(['get', 'post'], 'add', 'CouponController@add')->name('.add')->middleware('checkpermission:coupons,add');
        Route::match(['get', 'post'], 'edit/{id}', 'CouponController@edit')->name('.edit')->middleware('checkpermission:coupons,edit');
        Route::post('delete/{id}', 'CouponController@delete')->name('.delete')->middleware('checkpermission:coupons,delete');
    });


//=========Add-Manage-Activity-Log============

    /*Route::group(['prefix' => 'activities', 'as' => 'activities'], function() {
        Route::get('/', 'ActivityLogController@index')->name('.index')->middleware('checkpermission:activities,view');;
        Route::match(['get', 'post'], 'view/{id}', 'ActivityLogController@view')->name('.view')->middleware('checkpermission:activities,view');;
    });*/

    Route::group(['prefix' => 'activitylogs', 'as' => 'activitylogs'], function() {
        Route::get('/', 'ActivityLogController@index')->name('.index')->middleware('checkpermission:activities,view');
        Route::get('/view/{id}', 'ActivityLogController@view')->name('.view')->middleware('checkpermission:activities,view');
        Route::get('/flight', 'ActivityLogController@Flight')->name('.Flight')->middleware('checkpermission:activities,view');
        Route::get('/flight-view/{id}', 'ActivityLogController@FlightView')->name('.FlightView')->middleware('checkpermission:activities,view');
    });

    Route::group(['prefix' => 'login_history', 'as' => 'login_history'], function() {
        Route::get('/', 'LoginHistoryController@index')->name('.index')->middleware('checkpermission:activities,view');
        Route::get('/view/{id}', 'LoginHistoryController@view')->name('.view')->middleware('checkpermission:activities,view');
        Route::get('delete', 'LoginHistoryController@delete_log')->name('.delete')->middleware('checkpermission:activities,view');
    });

    Route::group(['prefix' => 'apilogs', 'as' => 'apilogs'], function() {
        Route::get('/', 'ApiLogController@index')->name('.index')->middleware('checkpermission:activities,view');
        Route::get('/view/{id}', 'ApiLogController@view')->name('.view')->middleware('checkpermission:activities,view');
    });

   // Forms
    //, 'middleware' => ['allowedmodule:forms']
    Route::group(['prefix' => 'forms', 'as' => 'forms'], function () {
        Route::get('/', 'FormController@index')->name('.index')->middleware('checkpermission:forms,view');
        Route::match(['get', 'post'], 'add', 'FormController@add')->name('.add')->middleware('checkpermission:forms,add');
        Route::match(['get', 'post'], 'edit/{id}', 'FormController@add')->name('.edit')->middleware('checkpermission:forms,edit');
        Route::post('ajax_delete_image', 'FormController@ajax_delete_image')->name('.ajax_delete_image');
        Route::post('ajax_delete_element', 'FormController@ajaxDeleteElement')->name('.ajax_delete_element');
        Route::post('delete/{id}', 'FormController@delete')->name('.delete')->middleware('checkpermission:forms,delete');
    });

    //////// Formm Data , 'middleware' => ['allowedmodule:forms']
    Route::group(['prefix' => 'forms_data', 'as' => 'forms_data'], function () {
        Route::get('/', 'FormdataController@index')->name('.index')->middleware('checkpermission:forms,view');
        Route::post('delete/{id}', 'FormController@delete')->name('.delete')->middleware('checkpermission:forms,delete');
        Route::match(['get', 'post'], 'showDetail/{id}', 'FormdataController@showDetail')->name('.showDetail')->middleware('checkpermission:forms,view');
    });


//=========Manage Footer Menu============

    Route::group(['prefix' => 'downloads', 'as' => 'downloads'], function() {

        Route::get('/', 'DownloadController@index')->name('.index')->middleware('checkpermission:downloads,view');
        Route::match(['get', 'post'], 'add', 'DownloadController@add')->name('.add')->middleware('checkpermission:downloads,add');
        Route::match(['get', 'post'], 'edit/{id}', 'DownloadController@add')->name('.edit')->middleware('checkpermission:downloads,edit');
        Route::post('ajax_delete_image', 'DownloadController@ajax_delete_image')->name('.ajax_delete_image')->middleware('checkpermission:downloads,delete');
        Route::match(['get', 'post'], 'view/{id}', 'DownloadController@view')->name('.view')->middleware('checkpermission:downloads,view');
        Route::post('ajax_delete_documents', 'DownloadController@ajax_delete_documents')->name('.ajax_delete_documents');
        Route::match(['get', 'post'],'delete/{id}', 'DownloadController@delete')->name('.delete')->middleware('checkpermission:downloads,delete');

        Route::get('others', 'DownloadController@others')->name('.others')->middleware('checkpermission:downloads,view');
        Route::match(['get', 'post'], 'others/add', 'DownloadController@add_other')->name('.other_add')->middleware('checkpermission:downloads,add');
        Route::match(['get', 'post'], 'others/edit/{id}', 'DownloadController@add_other')->name('.other_edit')->middleware('checkpermission:downloads,edit');
        Route::match(['get', 'post'], 'other_view/{id}', 'DownloadController@other_view')->name('.other_view')->middleware('checkpermission:downloads,view');
        Route::post('ajax_delete_other_image', 'DownloadController@ajax_delete_other_image')->name('.ajax_delete_other_image')->middleware('checkpermission:others,delete');
        Route::match(['get', 'post'],'others/delete/{id}', 'DownloadController@others_delete')->name('.others_delete')->middleware('checkpermission:downloads,delete');

    });
//===========Manage Footer Menu-Closed=============

//==========Faqs============

    Route::group(['prefix' => 'faqs', 'as' => 'faqs'], function() {
        Route::get('/', 'FaqController@index')->name('.index')->middleware('checkpermission:faqs,view');
        Route::match(['get', 'post'], 'add', 'FaqController@add')->name('.add')->middleware('checkpermission:faqs,add');
        Route::match(['get', 'post'], 'edit/{id}', 'FaqController@add')->name('.edit')->middleware('checkpermission:faqs,edit');
        Route::match(['get', 'post'], 'view/{id}', 'FaqController@view')->name('.view')->middleware('checkpermission:faqs,view');
        Route::match(['get','post'],'delete/{id}', 'FaqController@delete')->name('.delete')->middleware('checkpermission:faqs,delete');
    });

Route::group(['prefix' => 'faq_categories', 'as' => 'faq_categories'], function() {

        Route::get('/', 'FaqCategoryController@index')->name('.index')->middleware('checkpermission:faqs,view');
        Route::match(['get', 'post'], 'add', 'FaqCategoryController@add')->name('.add')->middleware('checkpermission:faqs,add');
        Route::match(['get', 'post'], 'edit/{id}', 'FaqCategoryController@add')->name('.edit')->middleware('checkpermission:faqs,edit');
        Route::match(['get', 'post'], 'view/{id}', 'FaqCategoryController@view')->name('.view')->middleware('checkpermission:faqs,view');
        Route::post('ajax_delete_image', 'FaqCategoryController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get', 'post'],'delete/{id}', 'FaqCategoryController@delete')->name('.delete')->middleware('checkpermission:faqs,delete');
    });

//=============Settings============
    Route::group(['prefix' => 'settings', 'as' => 'settings', 'middleware' => ['checkpermission:settings,view']], function() {


        //==========Sitemap ============
            Route::get('/sitemap', 'SitemapController@index')->name('.sitemap');
            Route::get('/sitemap/home', 'SitemapController@home')->name('.sitemap_home');
            Route::get('/sitemap/hotels', 'SitemapController@hotels')->name('.sitemap_hotels');
            Route::get('/sitemap/blogs', 'SitemapController@blogs')->name('.sitemap_blogs');
            Route::get('/sitemap/packages', 'SitemapController@packages')->name('.sitemap_packages');
            Route::get('/sitemap/activities', 'SitemapController@activities')->name('.sitemap_activities');


        Route::match(['get', 'post'], '/general', 'SettingsController@general')->name('.general');
        Route::match(['get', 'post'], '/comapany-info', 'SettingsController@comapany_info')->name('.comapany_info');
        Route::match(['get', 'post'], '/sms-setting', 'SettingsController@sms_setting')->name('.sms_setting');
        Route::match(['get', 'post'], '/google-maps-api', 'SettingsController@google_maps_api')->name('.google_maps_api');
        Route::match(['get', 'post'], '/google-recaptcha', 'SettingsController@google_recaptcha')->name('.google_recaptcha');
        Route::match(['get', 'post'], '/social-configuration', 'SettingsController@social_configuration')->name('.social_configuration');

        Route::match(['get', 'post'], '/cab-tabs', 'SettingsController@display_cab_tabs')->name('.display_cab_tabs');
        Route::match(['get', 'post'], '/email-headerfooter', 'SettingsController@email_header_footer')->name('.email_header_footer');
        Route::match(['get', 'post'], '/analytics_tool', 'SettingsController@analytics_tool')->name('.analytics_tool');
        Route::match(['get', 'post'], '/images', 'SettingsController@images')->name('.images');
        Route::match(['get', 'post'], '/smtp-setting', 'SettingsController@smtpSetting')->name('.smtpSetting');
        Route::match(['get', 'post'], '/mail_smtp', 'SettingsController@mailsmtp')->name('.mailsmtp');
        Route::match(['get', 'post'], '/social_plateform', 'SettingsController@social_plateform')->name('.social_plateform');
        Route::match(['get', 'post'], '/social_sharing', 'SettingsController@social_sharing')->name('.social_sharing');
        Route::match(['get', 'post'], '/robot_txt', 'SettingsController@robot_txt')->name('.robot_txt');

        Route::match(['get', 'post'], '/cookies_consent', 'SettingsController@cookies_consent')->name('.cookies_consent');
        Route::match(['get', 'post'], '/currency_setting', 'SettingsController@currency_setting')->name('.currency_setting');
        Route::any('/flight-apis', 'FlightApiController@index')->name('.flight_apis');

        Route::match(['get', 'post'], '/{setting_id?}', 'SettingsController@index')->name('.index');
      //Route::any('/{setting_id}', 'SettingsController@index')->name('.index');


    });

    Route::group(['prefix' => 'newsletter', 'as' => 'newsletter'], function() {
     Route::get('/', 'NewsletterController@index')->name('.index')->middleware('checkpermission:newsletter,view');
     Route::post('/delete/{id}', 'NewsletterController@delete')->name('.delete')->middleware('checkpermission:newsletter,delete');

 });

//=======+Modules ==========
    Route::group(['prefix' => 'modules', 'as' => 'modules'], function() {
     Route::match(['get', 'post'], '/', 'ModuleController@index')->name('.index');
 });

//=========For-Countries=========
    Route::group(['prefix' => 'countries', 'as' => 'countries'], function() {
        Route::get('/', 'CountryController@index')->name('.index')->middleware('checkpermission:countries,view');
        Route::match(['get', 'post'], '/save/{id?}', 'CountryController@save')->name('.save')->middleware('checkpermission:countries,add');
    });

//========State===========

    Route::group(['prefix' => 'states', 'as' => 'states'], function() {
        Route::get('/', 'StateController@index')->name('.index')->middleware('checkpermission:states,view');
        Route::match(['get', 'post'], '/save/{id?}', 'StateController@save')->name('.save')->middleware('checkpermission:states,add');
    });

//=========City=============
    Route::group(['prefix' => 'cities', 'as' => 'cities'], function() {
        Route::get('/', 'CityController@index')->name('.index')->middleware('checkpermission:cities,view');
        Route::match(['get', 'post'], '/save/{id?}', 'CityController@save')->name('.save')->middleware('checkpermission:cities,add');
    });

//===========Destination==============
    Route::group(['prefix' => 'destinations', 'as' => 'destinations' , 'middleware' => ['allowedmodule:destination_listing'] ], function() {

        Route::get('/', 'DestinationController@index')->name('.index')->middleware('checkpermission:destinations,view');

        Route::match(['get', 'post'], 'add', 'DestinationController@add')->name('.add')->middleware('checkpermission:destinations,add');
        Route::match(['get', 'post'], 'edit/{id}', 'DestinationController@add')->name('.edit')->middleware('checkpermission:destinations,edit');
        Route::post('ajax_delete_image', 'DestinationController@ajax_delete_image')->name('.ajax_delete_image')->middleware('checkpermission:destinations,delete');
        Route::match(['get', 'post'],'delete/{id}', 'DestinationController@delete')->name('.delete')->middleware('checkpermission:destinations,delete');
        Route::match(['get', 'post'], 'view/{id}', 'DestinationController@view')->name('.view');
        Route::match(['get', 'post'], 'locations/{id}', 'DestinationController@locations')->name('.locations');
        Route::post('locations_add', 'DestinationController@locations_add')->name('.locations_add');
        Route::post('locations_get', 'DestinationController@locations_get')->name('.locations_get');
        Route::post('location_delete', 'DestinationController@location_delete')->name('.location_delete');
        Route::post('ajax_locations', 'DestinationController@ajax_locations')->name('.ajax_locations');

//=========Add-Destination-Flags==============
         Route::get('flags','DestinationController@flags_index')->name('.flags')->middleware('checkpermission:all_masters,view');
         Route::match(['get','post'],'flags/add','DestinationController@flags_add')->name('.flags_add')->middleware('checkpermission:all_masters,add');
         Route::match(['get','post'],'flags/edit/{id}', 'DestinationController@flags_add')->name('.flags_edit')->middleware('checkpermission:all_masters,edit');
         Route::post('flags/delete/{id}','DestinationController@flags_delete')->name('.flags_delete')->middleware('checkpermission:all_masters,delete');

          Route::get('changesStatus', 'DestinationController@changeFlagStatus')->name('.changeFlagStatus');

//============Destination-Type==============

        Route::get('types', 'DestinationController@types')->name('.destination_types')->middleware('checkpermission:all_masters,view');

        Route::match(['get', 'post'], 'types/add', 'DestinationController@type_add')->name('.type_add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'type/edit/{id}', 'DestinationController@type_add')->name('.type_edit')->middleware('checkpermission:all_masters,edit');
        Route::match(['get', 'post'],'type/delete/{id}', 'DestinationController@destination_delete')->name('.destination_delete')->middleware('checkpermission:all_masters,delete');

//=============Destination-SEO Meta=============
        Route::match(['get', 'post'], '{destination_id?}/seo_meta', 'DestinationController@seoMeta')->name('.seo_meta')->middleware('checkpermission:destinations,add');

        Route::match(['get', 'post'], '{destination_id?}/seo_view', 'DestinationController@seoView')->name('.seo_view');

//===============Destination-Additional-info================

        Route::get('{id}/additional-info', 'DestinationController@additional_info')->name('.additional_info')->middleware('checkpermission:destinations,view');
        Route::match(['get', 'post'], '{id}/additional-info/add', 'DestinationController@additional_info_add')->name('.info_add')->middleware('checkpermission:destinations,add');
        Route::match(['get', 'post'], '{id}/additional-info/edit/{info_id}', 'DestinationController@additional_info_add')->name('.info_edit')->middleware('checkpermission:destinations,edit');
        Route::post('{id}/additional-info/delete/{info_id}', 'DestinationController@additional_info_delete')->name('.info_delete')->middleware('checkpermission:destinations,delete');
        Route::post('ajax_delete_images', 'DestinationController@ajax_delete_images')->name('.ajax_delete_images')->middleware('checkpermission:destinations,delete');

        Route::match(['get', 'post'], 'types/add', 'DestinationController@type_add')->name('.type_add');
        Route::match(['get', 'post'], 'type/edit/{id}', 'DestinationController@type_add')->name('.type_edit');
        Route::get('changeStatus', 'DestinationController@ChangeUserStatus')->name('.ChangeUserStatus');

        Route::post('ajax_destinations', 'DestinationController@ajax_destinations')->name('.ajax_destinations');//->middleware('checkpermission:destinations,view');
    });
//=============Add-Manage-Package===============

    Route::group(['prefix' => 'packages', 'as' => 'packages' , 'middleware' => ['allowedmodule:package_listing'] ], function() {

        Route::get('/', 'PackageController@index')->name('.index')->middleware('checkpermission:packages,view');
        Route::match(['get', 'post'], 'add', 'PackageController@add')->name('.add')->middleware('checkpermission:packages,add');
        Route::match(['get', 'post'], 'edit/{id}', 'PackageController@add')->name('.edit')->middleware('checkpermission:packages,edit');
        Route::match(['get', 'post'],'delete/{id}', 'PackageController@delete')->name('.delete')->middleware('checkpermission:packages,delete');
        Route::post('ajax_delete_image', 'PackageController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get', 'post'], 'package_view/{id}', 'PackageController@package_view')->name('.package_view');

        Route::get('{package_id}/itenaries', 'PackageController@itenaries')->name('.itenaries');
        Route::get('{package_id}/agent_price', 'PackageController@agent_price')->middleware('allowedmodule:agentuser')->name('.agent_price');

        Route::post('add_agent_price', 'PackageController@add_agent_price')->middleware('allowedmodule:agentuser')->name('.add_agent_price');

        Route::match(['get', 'post'], '{package_id}/itenary/add', 'PackageController@itenary_add')->name('.itenary_add')->middleware('checkpermission:packages,add');
        Route::match(['get', 'post'], '{package_id}/itenary/edit/{id}', 'PackageController@itenary_add')->name('.itenary_edit')->middleware('checkpermission:packages,edit');
        Route::post('{package_id}/itenary/delete-image', 'PackageController@ajax_delete_itenary_image')->name('.ajax_delete_itenary_image');
        Route::match(['get', 'post'],'{package_id}/itenary/delete/{id}', 'PackageController@itenary_delete')->name('.itenary_delete')->middleware('checkpermission:packages,delete');
        Route::match(['get', 'post'], '{package_id}/itenary/view/{id}', 'PackageController@itenary_view')->name('.itenary_view');
        Route::match(['get', 'post'],'update_vendor/{id}', 'PackageController@update_vendor')->name('.update_vendor')->middleware('checkpermission:packages,edit');
        Route::match(['get', 'post'],'update_expert/{id}', 'PackageController@update_expert')->name('.update_expert')->middleware('checkpermission:packages,edit');

    //=============Package-Additional-info==============
        Route::match(['get', 'post'],'{id}/additional-info', 'PackageController@additional_info')->name('.additional_info')->middleware('checkpermission:packages,view');

        Route::match(['get', 'post'], '{id}/additional-info/add', 'PackageController@additional_info_add')->name('.info_add')->middleware('checkpermission:packages,add');
        Route::match(['get', 'post'], '{id}/additional-info/edit/{info_id}', 'PackageController@additional_info_add')->name('.info_edit')->middleware('checkpermission:packages,edit');
        Route::post('{id}/additional-info/delete/{info_id}', 'PackageController@additional_info_delete')->name('.info_delete')->middleware('checkpermission:packages,delete');

     //=============Package-Accommodations =============

        Route::get('{package_id}/accommodation', 'PackageController@accommodation')->name('.accommodation')->middleware('checkpermission:packages,view');

        Route::match(['get', 'post'], '{package_id}/accommodation/add', 'PackageController@accommodation_add')->name('.accommodation_add')->middleware('checkpermission:packages,add');


        Route::match(['get', 'post'], '{package_id}/accommodation/edit/{accommodation_id?}/', 'PackageController@accommodation_add')->name('.accommodation_edit')->middleware('checkpermission:packages,edit');

        Route::post('{package_id}/accommodation/delete/{accommodation_id?}', 'PackageController@accommodation_delete')->name('.accommodation_delete')->middleware('checkpermission:packages,delete');


    //=============Package-SEO Meta=============
        Route::match(['get', 'post'], '{package_id?}/seo_meta', 'PackageController@seoMeta')->name('.seo_meta')->middleware('checkpermission:packages,add');

        Route::match(['get', 'post'], '{package_id?}/seo_view', 'PackageController@seoView')->name('.seo_view');

        Route::match(['get', 'post'], 'getHotelList', 'PackageController@getHotelList')->name('.getHotelList');


    //=============Package-Accommodations-&-price-info=============

        /*Route::get('{package_id}/price-info', 'PackageController@price_info')->name('.price_info')->middleware('checkpermission:packages,view');

        Route::match(['get', 'post'], '{package_id}/price-info/add', 'PackageController@price_info_add')->name('.price_add')->middleware('checkpermission:packages,add');
        Route::match(['get', 'post'], '{package_id}/price-info/edit/{info_id}', 'PackageController@price_info_add')->name('.price_edit')->middleware('checkpermission:packages,edit');
        Route::post('{package_id}/price-info/delete/{info_id}', 'PackageController@price_info_delete')->name('.price_delete')->middleware('checkpermission:packages,delete');*/

        Route::get('{package_id}/flight', 'PackageController@flight')->name('.flight')->middleware('checkpermission:packages,view');
        Route::post('{package_id}/flight_add', 'PackageController@flight_add')->name('.flight_add')->middleware('checkpermission:packages,add');
        Route::post('{package_id}/flight_list', 'PackageController@flight_list')->name('.flight_list')->middleware('checkpermission:packages,view');
        Route::post('{package_id}/flight_get', 'PackageController@flight_get')->name('.flight_get')->middleware('checkpermission:packages,view');
        Route::post('{package_id}/flight_delete', 'PackageController@flight_delete')->name('.flight_delete')->middleware('checkpermission:packages,delete');

        Route::get('{package_id}/packageprice', 'PackageController@packageprice')->name('.packageprice')->middleware('checkpermission:packages,view');
        Route::post('{package_id}/packageprice_add', 'PackageController@packageprice_add')->name('.packageprice_add')->middleware('checkpermission:packages,add');
        Route::post('{package_id}/packageprice_list', 'PackageController@packageprice_list')->name('.packageprice_list')->middleware('checkpermission:packages,view');

        Route::match(['get','post'],'{package_id}/packageprice_slot/{price_id}', 'PackageController@packageprice_slot')->name('.packageprice_slot')->middleware('checkpermission:packages,view');

        Route::match(['get','post'],'{package_id}/packageprice_slot_delete/{price_id}', 'PackageController@packageprice_slot_delete')->name('.packageprice_slot_delete')->middleware('checkpermission:packages,view');

        Route::post('save-booking-mode', 'PackageController@ajax_save_booking_mode')->name('.save_booking_mode');

        Route::post('{package_id}/packageprice_get', 'PackageController@packageprice_get')->name('.packageprice_get')->middleware('checkpermission:packages,view');
        Route::post('{package_id}/packageprice_delete', 'PackageController@packageprice_delete')->name('.packageprice_delete')->middleware('checkpermission:packages,delete');
        Route::post('/update_packageprice_order', 'PackageController@update_packageprice_order')->name('.update_packageprice_order');
        Route::post('/update_default_packageprice', 'PackageController@update_default_packageprice')->name('.update_default_packageprice');


        // Package Settings
        Route::match(['get','post'],'{package_id}/packagesetting', 'PackageController@packagesetting')->name('.packagesetting')->middleware('checkpermission:packages,view');

        // Package Settings
        Route::match(['get','post'],'{package_id}/departure', 'PackageController@departure')->name('.departure')->middleware('checkpermission:packages,view');
        Route::match(['get','post'],'{package_id}/departure_bulk', 'PackageController@departure_bulk')->name('.departure_bulk')->middleware('checkpermission:packages,view');
        Route::match(['get','post'],'{package_id}/departure_next', 'PackageController@departure_next')->name('.departure_next')->middleware('checkpermission:packages,view');
        Route::post('ajax_departure_row', 'PackageController@ajax_departure_row')->name('.ajax_departure_row')->middleware('checkpermission:packages,view');
        Route::post('ajax_departure_date', 'PackageController@ajax_departure_date')->name('.ajax_departure_date')->middleware('checkpermission:packages,view');

        // Route::match(['get', 'post'], '{package_id}/packageprice/edit/{info_id}', 'PackageController@packageprice_info_add')->name('.packageprice_edit')->middleware('checkpermission:packages,edit');
        // Route::post('{package_id}/packageprice/delete/{info_id}', 'PackageController@packageprice_info_delete')->name('.packageprice_delete')->middleware('checkpermission:packages,delete');

       //=========Add-Package-Type==============
        Route::get('types', 'PackageController@type_index')->name('.type_index')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'types/add', 'PackageController@type_add')->name('.type_add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'types/edit/{id}', 'PackageController@type_add')->name('.type_edit')->middleware('checkpermission:all_masters,edit');
        Route::post('types/delete/{id}', 'PackageController@type_delete')->name('.type_delete')->middleware('checkpermission:all_masters,delete');
        Route::match(['get', 'post'], 'view/{id}', 'PackageController@type_view')->name('.type_view');
        Route::get('change_status', 'PackageController@ChangeStatus')->name('.ChangeStatus');

        //=========Add-Package-Flags==============
         Route::get('flags','PackageController@flags_index')->name('.flags')->middleware('checkpermission:all_masters,view');
         Route::match(['get','post'],'flags/add','PackageController@flags_add')->name('.flags_add')->middleware('checkpermission:all_masters,add');
         Route::match(['get','post'],'flags/edit/{id}', 'PackageController@flags_add')->name('.flags_edit')->middleware('checkpermission:all_masters,edit');
         Route::post('flags/delete/{id}','PackageController@flags_delete')->name('.flags_delete')->middleware('checkpermission:all_masters,delete');

 //=========Add-Package-Image-Category==============

        Route::get('package-category', 'PackageCategoryController@packageCategories')->name('.package-category')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'package-category/add', 'PackageCategoryController@packageCategoryAdd')->name('.package_category_add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'package-category/edit/{id}', 'PackageCategoryController@packageCategoryAdd')->name('.package_category_edit')->middleware('checkpermission:all_masters,edit');
        Route::post('package-category/delete/{id}', 'PackageCategoryController@packageCategoryDelete')->name('.package_category_delete')->middleware('checkpermission:all_masters,delete');
        Route::match(['get', 'post'], 'view/{id}', 'PackageCategoryController@packageCategoryview')->name('.package_category_view');
        Route::get('cat_change_status', 'PackageCategoryController@ChangeStatus')->name('.CategoryChangeStatus');
//=========Add-Package-Inclusion-List=============

        Route::get('lists', 'PackageController@lists')->name('.package_inclusion_lists')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'lists/add', 'PackageController@add_list')->name('.list_add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'lists/edit/{id}', 'PackageController@add_list')->name('.list_edit')->middleware('checkpermission:all_masters,edit');
        Route::match(['get', 'post'],'lists/delete/{id}', 'PackageController@list_delete')->name('.list_delete')->middleware('checkpermission:all_masters,delete');
        Route::match(['get', 'post'], 'list_view/{id}', 'PackageController@list_view')->name('.list_view');
        Route::get('changeStatus', 'PackageController@ChangeUserStatus')->name('.ChangeUserStatus');

        Route::get('airlines', 'PackageController@airlines')->name('.package_airlines')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'airlines/add', 'PackageController@add_airline')->name('.airline_add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'airlines/edit/{id}', 'PackageController@add_airline')->name('.air_edit')->middleware('checkpermission:all_masters,edit');
        Route::match(['get', 'post'],'airlines/delete/{id}', 'PackageController@delete_airline')->name('.delete_airline')->middleware('checkpermission:all_masters,delete');
        Route::post('ajax_delete_image_airline', 'PackageController@ajax_delete_image_airline')->name('.ajax_delete_image_airline');
        Route::match(['get', 'post'], 'airline_view/{id}', 'PackageController@airline_view')->name('.airline_view');

        //============Add-Service-Level================
        Route::get('services', 'PackageController@serviceLevel')->name('.serviceLevel')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'services/add', 'PackageController@service_add')->name('.service_add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'services/edit/{id}', 'PackageController@service_add')->name('.service_edit')->middleware('checkpermission:all_masters,edit');
        Route::match(['get', 'post'],'services/delete/{id}', 'PackageController@service_delete')->name('.service_delete')->middleware('checkpermission:all_masters,delete');
        Route::match(['get', 'post'], 'view/{id}', 'PackageController@service_view')->name('.service_view');
        Route::get('service_status', 'PackageController@serviceStatus')->name('.serviceStatus');

        //============Add-Package-Setting================
        Route::get('settings', 'PackageController@settings')->name('.settings')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'settings/add', 'PackageController@settings_add')->name('.settings_add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'settings/edit/{id}', 'PackageController@settings_add')->name('.settings_edit')->middleware('checkpermission:all_masters,edit');
        Route::match(['get', 'post'],'settings/delete/{id}', 'PackageController@settings_delete')->name('.settings_delete')->middleware('checkpermission:all_masters,delete');



    //==========Manage-Theme-Category=============

    Route::get('theme', 'ThemeController@index')->name('.theme_index')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'], 'theme/add', 'ThemeController@add')->name('.theme_add')->middleware('checkpermission:all_masters,add');
    Route::match(['get', 'post'], 'theme/edit/{id}', 'ThemeController@add')->name('.theme_edit')->middleware('checkpermission:all_masters,edit');
    Route::match(['get', 'post'], 'theme/view/{id}', 'ThemeController@view')->name('.theme_view')->middleware('checkpermission:all_masters,view');
    Route::post('ajax_delete_image', 'ThemeController@ajax_delete_image')->name('.ajax_delete_image');
    Route::match(['get', 'post'],'theme/delete/{id}', 'ThemeController@delete')->name('.theme_delete')->middleware('checkpermission:all_masters,delete');

        //============Sortable itenary data================
        Route::post('/update_itenaries_order', 'PackageController@update_itenaries_order')->name('.update_itenaries_order');


    });

 Route::group(['prefix' => 'activity', 'as' => 'activity' , 'middleware' => ['allowedmodule:activity_listing'] ], function() {

        Route::get('/', 'PackageController@index')->name('.index')->middleware('checkpermission:activity,view');
        Route::match(['get', 'post'], 'add', 'PackageController@add')->name('.add')->middleware('checkpermission:activity,add');
        Route::match(['get', 'post'], 'edit/{id}', 'PackageController@add')->name('.edit')->middleware('checkpermission:activity,edit');
        Route::match(['get', 'post'], 'activity_view/{id}', 'PackageController@package_view')->name('.activity_view')->middleware('checkpermission:activity,view');
        Route::match(['get', 'post'],'delete/{id}', 'PackageController@delete')->name('.delete')->middleware('checkpermission:activity,delete');
        Route::post('ajax_delete_image', 'PackageController@ajax_delete_image')->name('.ajax_delete_image');

        //=========Add-Package-Type==============
        Route::get('types', 'PackageController@type_index')->name('.type_index')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'types/add', 'PackageController@type_add')->name('.type_add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'types/edit/{id}', 'PackageController@type_add')->name('.type_edit')->middleware('checkpermission:all_masters,edit');
        Route::post('types/delete/{id}', 'PackageController@type_delete')->name('.type_delete')->middleware('checkpermission:all_masters,delete');
        Route::match(['get', 'post'], 'view/{id}', 'PackageController@type_view')->name('.type_view');
        Route::get('change_status', 'PackageController@ChangeStatus')->name('.ChangeStatus');

        //=========Add-Package-Flags==============
         Route::get('flags','PackageController@flags_index')->name('.flags')->middleware('checkpermission:all_masters,view');
         Route::match(['get','post'],'flags/add','PackageController@flags_add')->name('.flags_add')->middleware('checkpermission:all_masters,add');
         Route::match(['get','post'],'flags/edit/{id}', 'PackageController@flags_add')->name('.flags_edit')->middleware('checkpermission:all_masters,edit');
         Route::post('flags/delete/{id}','PackageController@flags_delete')->name('.flags_delete')->middleware('checkpermission:all_masters,delete');

 //=========Add-Package-Image-Category==============

        Route::get('package-category', 'PackageCategoryController@packageCategories')->name('.package-category')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'package-category/add', 'PackageCategoryController@packageCategoryAdd')->name('.package_category_add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'package-category/edit/{id}', 'PackageCategoryController@packageCategoryAdd')->name('.package_category_edit')->middleware('checkpermission:all_masters,edit');
        Route::post('package-category/delete/{id}', 'PackageCategoryController@packageCategoryDelete')->name('.package_category_delete')->middleware('checkpermission:all_masters,delete');
        Route::match(['get', 'post'], 'view/{id}', 'PackageCategoryController@packageCategoryview')->name('.package_category_view');
        Route::get('cat_change_status', 'PackageCategoryController@ChangeStatus')->name('.CategoryChangeStatus');

        //=========Add-Package-Inclusion-List=============

        Route::get('lists', 'PackageController@lists')->name('.package_inclusion_lists')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'lists/add', 'PackageController@add_list')->name('.list_add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'lists/edit/{id}', 'PackageController@add_list')->name('.list_edit')->middleware('checkpermission:all_masters,edit');
        Route::match(['get', 'post'],'lists/delete/{id}', 'PackageController@list_delete')->name('.list_delete')->middleware('checkpermission:all_masters,delete');
        Route::match(['get', 'post'], 'list_view/{id}', 'PackageController@list_view')->name('.list_view');
        Route::get('changeStatus', 'PackageController@ChangeUserStatus')->name('.ChangeUserStatus');

        Route::get('airlines', 'PackageController@airlines')->name('.package_airlines')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'airlines/add', 'PackageController@add_airline')->name('.airline_add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'airlines/edit/{id}', 'PackageController@add_airline')->name('.air_edit')->middleware('checkpermission:all_masters,edit');
        Route::match(['get', 'post'],'airlines/delete/{id}', 'PackageController@delete_airline')->name('.delete_airline')->middleware('checkpermission:all_masters,delete');
        Route::post('ajax_delete_image_airline', 'PackageController@ajax_delete_image_airline')->name('.ajax_delete_image_airline');
        Route::match(['get', 'post'], 'airline_view/{id}', 'PackageController@airline_view')->name('.airline_view');


        //============Add-Package-Setting================
        Route::get('settings', 'PackageController@settings')->name('.settings')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'settings/add', 'PackageController@settings_add')->name('.settings_add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'settings/edit/{id}', 'PackageController@settings_add')->name('.settings_edit')->middleware('checkpermission:all_masters,edit');
        Route::match(['get', 'post'],'settings/delete/{id}', 'PackageController@settings_delete')->name('.settings_delete')->middleware('checkpermission:all_masters,delete');

        //==========Manage-Theme-Category=============

    Route::get('theme', 'ThemeController@index')->name('.theme_index')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'], 'theme/add', 'ThemeController@add')->name('.theme_add')->middleware('checkpermission:all_masters,add');
    Route::match(['get', 'post'], 'theme/edit/{id}', 'ThemeController@add')->name('.theme_edit')->middleware('checkpermission:all_masters,edit');
    Route::match(['get', 'post'], 'theme/view/{id}', 'ThemeController@view')->name('.theme_view')->middleware('checkpermission:all_masters,view');
    Route::post('ajax_delete_image', 'ThemeController@ajax_delete_image')->name('.ajax_delete_image');
    Route::match(['get', 'post'],'theme/delete/{id}', 'ThemeController@delete')->name('.theme_delete')->middleware('checkpermission:all_masters,delete');


    //=============Activity-Additional-info==============
        Route::match(['get', 'post'],'{id}/additional-info', 'PackageController@additional_info')->name('.additional_info')->middleware('checkpermission:packages,view');

        Route::match(['get', 'post'], '{id}/additional-info/add', 'PackageController@additional_info_add')->name('.info_add')->middleware('checkpermission:packages,add');
        Route::match(['get', 'post'], '{id}/additional-info/edit/{info_id}', 'PackageController@additional_info_add')->name('.info_edit')->middleware('checkpermission:packages,edit');
        Route::post('{id}/additional-info/delete/{info_id}', 'PackageController@additional_info_delete')->name('.info_delete')->middleware('checkpermission:packages,delete');

        //=============Activity-Itenaries & Agent Price==============
        Route::get('{package_id}/itenaries', 'PackageController@itenaries')->name('.itenaries');
        Route::get('{package_id}/agent_price', 'PackageController@agent_price')->middleware('allowedmodule:agentuser')->name('.agent_price');

        Route::post('add_agent_price', 'PackageController@add_agent_price')->middleware('allowedmodule:agentuser')->name('.add_agent_price');

        Route::match(['get', 'post'], '{package_id}/itenary/add', 'PackageController@itenary_add')->name('.itenary_add')->middleware('checkpermission:packages,add');
        Route::match(['get', 'post'], '{package_id}/itenary/edit/{id}', 'PackageController@itenary_add')->name('.itenary_edit')->middleware('checkpermission:packages,edit');
        Route::post('{package_id}/itenary/delete-image', 'PackageController@ajax_delete_itenary_image')->name('.ajax_delete_itenary_image');
        Route::match(['get', 'post'],'{package_id}/itenary/delete/{id}', 'PackageController@itenary_delete')->name('.itenary_delete')->middleware('checkpermission:packages,delete');
        Route::match(['get', 'post'], '{package_id}/itenary/view/{id}', 'PackageController@itenary_view')->name('.itenary_view');

        // Activity Flight
        Route::get('{package_id}/flight', 'PackageController@flight')->name('.flight')->middleware('checkpermission:packages,view');
        Route::post('{package_id}/flight_add', 'PackageController@flight_add')->name('.flight_add')->middleware('checkpermission:packages,add');
        Route::post('{package_id}/flight_list', 'PackageController@flight_list')->name('.flight_list')->middleware('checkpermission:packages,view');
        Route::post('{package_id}/flight_get', 'PackageController@flight_get')->name('.flight_get')->middleware('checkpermission:packages,view');
        Route::post('{package_id}/flight_delete', 'PackageController@flight_delete')->name('.flight_delete')->middleware('checkpermission:packages,delete');

        // Activity Departure
        Route::match(['get','post'],'{package_id}/departure', 'PackageController@departure')->name('.departure')->middleware('checkpermission:packages,view');
        Route::match(['get','post'],'{package_id}/departure_bulk', 'PackageController@departure_bulk')->name('.departure_bulk')->middleware('checkpermission:packages,view');
        Route::match(['get','post'],'{package_id}/departure_next', 'PackageController@departure_next')->name('.departure_next')->middleware('checkpermission:packages,view');
        Route::post('ajax_departure_row', 'PackageController@ajax_departure_row')->name('.ajax_departure_row')->middleware('checkpermission:packages,view');
        Route::post('ajax_departure_date', 'PackageController@ajax_departure_date')->name('.ajax_departure_date')->middleware('checkpermission:packages,view');

        //=============Activity-SEO Meta=============
        Route::match(['get', 'post'], '{package_id?}/seo_meta', 'PackageController@seoMeta')->name('.seo_meta')->middleware('checkpermission:packages,add');

        Route::match(['get', 'post'], '{package_id?}/seo_view', 'PackageController@seoView')->name('.seo_view');

        Route::get('{package_id}/packageprice', 'PackageController@packageprice')->name('.packageprice')->middleware('checkpermission:packages,view');
        Route::post('{package_id}/packageprice_add', 'PackageController@packageprice_add')->name('.packageprice_add')->middleware('checkpermission:packages,add');
        Route::post('{package_id}/packageprice_list', 'PackageController@packageprice_list')->name('.packageprice_list')->middleware('checkpermission:packages,view');

        Route::match(['get','post'],'{package_id}/packageprice_slot/{price_id}', 'PackageController@packageprice_slot')->name('.packageprice_slot')->middleware('checkpermission:packages,view');

        Route::match(['get','post'],'{package_id}/packageprice_slot_delete/{price_id}', 'PackageController@packageprice_slot_delete')->name('.packageprice_slot_delete')->middleware('checkpermission:packages,view');

        Route::post('save-booking-mode', 'PackageController@ajax_save_booking_mode')->name('.save_booking_mode');

    });

//=============Manage-Accommodation===============

Route::group(['prefix' => 'accommodations', 'as' => 'accommodations','middleware' => ['allowedmodule:hotel_listing'] ], function() {
    Route::get('/', 'AccommodationController@index')->name('.index')->middleware('checkpermission:accommodations,view');
    Route::match(['get', 'post'], 'add', 'AccommodationController@add')->name('.add')->middleware('checkpermission:accommodations,add');
    Route::match(['get', 'post'], 'edit/{id}', 'AccommodationController@add')->name('.edit')->middleware('checkpermission:accommodations,edit');
    Route::post('save-booking-mode', 'AccommodationController@ajax_save_booking_mode')->name('.save_booking_mode');
    Route::match(['get', 'post'], 'view/{id}', 'AccommodationController@view')->name('.view');
    Route::post('ajax_delete_image', 'AccommodationController@ajax_delete_image')->name('.ajax_delete_image')->middleware('checkpermission:accommodations,delete');
    Route::match(['get', 'post'],'delete/{id}', 'AccommodationController@delete')->name('.delete')->middleware('checkpermission:accommodations,delete');
   
    Route::match(['get', 'post'],'update_vendor/{id}', 'AccommodationController@update_vendor')->name('.update_vendor')->middleware('checkpermission:accommodations,edit');

    Route::post('get-accommodation-types', 'AccommodationController@ajax_get_accommodation_types')->name('.ajax_get_accommodation_types');

    Route::get('/inventory/{id?}', 'AccommodationController@inventory')->name('.inventory')->middleware('checkpermission:accommodations,view');

    Route::match(['get','post'],'{accommodation_id}/plan/{room_id}', 'AccommodationController@roomPlans')->name('.roomPlans')->middleware('checkpermission:accommodations,view');
    Route::post('{id}/plan/delete/{room_id}', 'AccommodationController@accommodation_room_plan_delete')->name('.room_plan_delete')->middleware('checkpermission:accommodations,delete');

    Route::match(['get','post'],'{accommodation_id}/rates/{room_id}', 'AccommodationController@planRates')->name('.planRates')->middleware('checkpermission:accommodations,edit');

    Route::match(['get', 'post'], 'nextInventory', 'AccommodationController@nextInventory')->name('.nextInventory')->middleware('checkpermission:accommodations,edit');

    Route::match(['get', 'post'], 'saveAllInventory/{save_id?}', 'AccommodationController@saveAllInventory')->name('.saveAllInventory')->middleware('checkpermission:accommodations,edit');

    Route::match(['get', 'post'], 'bulk_inventory', 'AccommodationController@bulk_inventory')->name('.bulk_inventory')->middleware('checkpermission:accommodations,edit');

    Route::get('feature', 'AccommodationController@feature')->name('.accommodations_features')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'], 'feature/add', 'AccommodationController@add_feature')->name('.features_add')->middleware('checkpermission:all_masters,add');
    Route::match(['get', 'post'], 'feature/edit/{id}', 'AccommodationController@add_feature')->name('.feature_edit')->middleware('checkpermission:all_masters,edit');
    Route::match(['get', 'post'], 'feature_view/{id}', 'AccommodationController@feature_view')->name('.feature_view')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'],'feature/delete/{id}', 'AccommodationController@feature_delete')->name('.feature_delete')->middleware('checkpermission:all_masters,delete');

//plan includes
  Route::get('plan_includes', 'AccommodationController@plan_include')->name('.room_plan_includes')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'], 'plan_include/add', 'AccommodationController@add_plan_include')->name('.plan_include_add')->middleware('checkpermission:all_masters,add');
    Route::match(['get', 'post'], 'plan_include/edit/{id}', 'AccommodationController@add_plan_include')->name('.plan_include_edit')->middleware('checkpermission:all_masters,edit');
    Route::match(['get', 'post'], 'plan_include_view/{id}', 'AccommodationController@plan_include_view')->name('.plan_include_view')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'],'plan_include/delete/{id}', 'AccommodationController@plan_include_delete')->name('.plan_include_delete')->middleware('checkpermission:all_masters,delete');
//===


    Route::get('facility', 'AccommodationController@facility')->name('.accommodations_facilities')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'], 'facility/add', 'AccommodationController@add_facility')->name('.facility_add')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'], 'facility/edit/{id}', 'AccommodationController@add_facility')->name('.facility_edit')->middleware('checkpermission:all_masters,edit');
    Route::match(['get', 'post'], 'facility_view/{id}', 'AccommodationController@facility_view')->name('.facility_view')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'],'facility/delete/{id}', 'AccommodationController@facility_delete')->name('.facility_delete')->middleware('checkpermission:all_masters,delete');

    Route::get('category', 'AccommodationController@category')->name('.accommodations_categories')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'], 'category/add', 'AccommodationController@add_category')->name('.category_add')->middleware('checkpermission:all_masters,add');
    Route::match(['get', 'post'], 'category/edit/{id}', 'AccommodationController@add_category')->name('.category_edit')->middleware('checkpermission:all_masters,edit');
    Route::match(['get', 'post'], 'category_view/{id}', 'AccommodationController@category_view')->name('.category_view')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'],'category/delete/{id}', 'AccommodationController@category_delete')->name('.category_delete')->middleware('checkpermission:all_masters,delete');

    Route::get('room_type', 'AccommodationController@roomTypes')->name('.room_types')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'], 'room_type/add', 'AccommodationController@add_roomtype')->name('.room_type_add')->middleware('checkpermission:all_masters,add');
    Route::match(['get', 'post'], 'room_type/edit/{id}', 'AccommodationController@add_roomtype')->name('.roomtype_edit')->middleware('checkpermission:all_masters,edit');
    Route::match(['get', 'post'], 'room_type_view/{id}', 'AccommodationController@room_type_view')->name('.room_type_view')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'],'room_type/delete/{id}', 'AccommodationController@roomTyp_delete')->name('.roomTyp_delete')->middleware('checkpermission:all_masters,delete');
    Route::get('status', 'AccommodationController@changeStatusUser')->name('.changeStatusUser');
    
    // Room Master View

    Route::group(['prefix' => 'room_master', 'as' => '.room_master'], function() {

        Route::get('/{slug?}', 'AccommodationController@roomView')->name('.view')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], '{slug?}/add', 'AccommodationController@add_roomview')->name('.add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], '{slug?}/edit/{id?}', 'AccommodationController@add_roomview')->name('.edit')->middleware('checkpermission:all_masters,edit');

        Route::match(['get', 'post'], '{slug?}/{id?}', 'AccommodationController@room_view')->name('.roomView')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'],'{slug?}/delete/{id?}', 'AccommodationController@room_view_delete')->name('.room_view_delete')->middleware('checkpermission:all_masters,delete');

  


    });



    Route::get('room_feature', 'AccommodationController@roomFeature')->name('.room_features')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'], 'room_feature/add', 'AccommodationController@add_roomfeature')->name('.roomfeature_add')->middleware('checkpermission:all_masters,add');
    Route::match(['get', 'post'], 'room_feature/edit/{id}', 'AccommodationController@add_roomfeature')->name('.roomfeatured_edit')->middleware('checkpermission:all_masters,edit');
    Route::match(['get', 'post'], 'room_feature_view/{id}', 'AccommodationController@room_feature_view')->name('.room_feature_view')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'],'room_feature/delete/{id}', 'AccommodationController@roomFea_delete')->name('.roomFea_delete')->middleware('checkpermission:all_masters,delete');
    Route::get('changeStatus', 'AccommodationController@ChangeUserStatus')->name('.ChangeUserStatus');

    Route::get('accoommodation_type', 'AccommodationController@accoomodationType')->name('.accoomodation_types')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'], 'accoommodation_type/add', 'AccommodationController@add_accoomodationType')->name('.accoomodation_type_add')->middleware('checkpermission:all_masters,add');
    Route::match(['get', 'post'], 'accoommodation_type/edit/{id}', 'AccommodationController@add_accoomodationType')->name('.accoomodation_type_edit')->middleware('checkpermission:all_masters,edit');
    Route::match(['get', 'post'], 'accoommodation_type_view/{id}', 'AccommodationController@accoomodation_type_view')->name('.accoomodation_type_view')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'],'accoommodation_type/delete/{id}', 'AccommodationController@accoomodationType_delete')->name('.accoomodationType_delete')->middleware('checkpermission:all_masters,delete');
    Route::get('changeAccTypeStatus', 'AccommodationController@changeAccTypeStatus')->name('.changeAccTypeStatus');

    //=============Accommodation-SEO Meta=============
        Route::match(['get', 'post'], '{accommodation_id?}/seo_meta', 'AccommodationController@seoMeta')->name('.seo_meta')->middleware('checkpermission:accommodations,add');

        Route::match(['get', 'post'], '{accommodation_id?}/seo_view', 'AccommodationController@seoView')->name('.seo_view');

    //=============Add-Accommodation-Room===========

    Route::get('{id}/accommodation-room', 'AccommodationController@accommodation_room')->name('.accommodation_room')->middleware('checkpermission:accommodations,view');
    Route::match(['get', 'post'], '{id}/accommodation-room/add', 'AccommodationController@accommodation_room_add')->name('.room_add')->middleware('checkpermission:accommodations,add');
    Route::match(['get', 'post'], '{id}/accommodation-room/edit/{room_id}', 'AccommodationController@accommodation_room_add')->name('.room_edit')->middleware('checkpermission:accommodations,edit');
    Route::post('/update_hotel_order', 'AccommodationController@update_hotel_order')->name('.update_hotel_order');
    Route::match(['get', 'post'], 'accommodation_room_view/{id}', 'AccommodationController@accommodation_room_view')->name('.room_view');
    Route::post('{id}/accommodation-room/delete/{room_id}', 'AccommodationController@accommodation_room_delete')->name('.room_delete')->middleware('checkpermission:accommodations,delete');

    //===============Accommodation-Additional-info================

        Route::get('{id}/additional-info', 'AccommodationController@additional_info')->name('.additional_info')->middleware('checkpermission:accommodations,view');
        Route::match(['get', 'post'], '{id}/additional-info/add', 'AccommodationController@additional_info_add')->name('.info_add')->middleware('checkpermission:accommodations,add');
        Route::match(['get', 'post'], '{id}/additional-info/edit/{info_id}', 'AccommodationController@additional_info_add')->name('.info_edit')->middleware('checkpermission:accommodations,edit');
        Route::post('{id}/additional-info/delete/{info_id}', 'AccommodationController@additional_info_delete')->name('.info_delete')->middleware('checkpermission:accommodations,delete');
        Route::post('ajax_delete_images', 'AccommodationController@ajax_delete_images')->name('.ajax_delete_images')->middleware('checkpermission:accommodations,delete');

        Route::get('{accommodation_id}/agent_price', 'AccommodationController@agent_price')->middleware('allowedmodule:agentuser')->name('.agent_price');

        Route::post('add_agent_price', 'AccommodationController@add_agent_price')->middleware('allowedmodule:agentuser')->name('.add_agent_price');

});

//==========Manage-Theme-Category=============

/*Route::group(['prefix' => 'theme', 'as' => 'theme'], function() {
    Route::get('/', 'ThemeController@index')->name('.index')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'], 'add', 'ThemeController@add')->name('.add')->middleware('checkpermission:all_masters,add');
    Route::match(['get', 'post'], 'edit/{id}', 'ThemeController@add')->name('.edit')->middleware('checkpermission:all_masters,edit');
    Route::match(['get', 'post'], 'view/{id}', 'ThemeController@view')->name('.view')->middleware('checkpermission:all_masters,view');
    Route::post('ajax_delete_image', 'ThemeController@ajax_delete_image')->name('.ajax_delete_image');
    Route::match(['get', 'post'],'delete/{id}', 'ThemeController@delete')->name('.delete')->middleware('checkpermission:all_masters,delete');
});*/

//==========Manage-Theme-Category=============

Route::group(['prefix' => 'cab_route', 'as' => 'cab_route' ,'middleware' => ['allowedmodule:cab']], function() {
    Route::get('/', 'CabRouteController@index')->name('.index')->middleware('checkpermission:cabs,view');
    Route::match(['get', 'post'], 'add', 'CabRouteController@add')->name('.add')->middleware('checkpermission:cabs,add');
    Route::match(['get', 'post'], 'view/{id}', 'CabRouteController@view')->name('.view')->middleware('checkpermission:cabs,view');
    Route::match(['get', 'post'], 'edit/{id}', 'CabRouteController@add')->name('.edit')->middleware('checkpermission:cabs,edit');
    Route::get('/groups', 'CabRouteController@groups')->name('.groups')->middleware('checkpermission:cabs,view');
    Route::match(['get', 'post'], 'addgroup', 'CabRouteController@addgroup')->name('.groupadd')->middleware('checkpermission:cabs,add');
    Route::match(['get', 'post'], 'editgroup/{id}', 'CabRouteController@addgroup')->name('.groupedit')->middleware('checkpermission:cabs,edit');

    Route::get('agent_price/{id}', 'CabRouteController@agent_price')->middleware('allowedmodule:agentuser')->name('.agent_price')->middleware('checkpermission:cabs,view');

     Route::post('add_agent_price', 'CabRouteController@add_agent_price')->middleware('allowedmodule:agentuser')->name('.add_agent_price')->middleware('checkpermission:cabs,add');
     Route::post('/update_cab_route', 'CabRouteController@update_cab_route')->name('.update_cab_route')->middleware('checkpermission:cabs,edit');


});

//=========Team-Management============

Route::group(['prefix' => 'teammanagements', 'as' => 'teammanagements'], function() {
    Route::get('/', 'TeamManagementController@index')->name('.index')->middleware('checkpermission:teammanagements,view');
    Route::match(['get', 'post'], 'add', 'TeamManagementController@add')->name('.add')->middleware('checkpermission:teammanagements,add');
    Route::match(['get', 'post'], 'edit/{id}', 'TeamManagementController@add')->name('.edit')->middleware('checkpermission:teammanagements,edit')
    ;
    Route::match(['get', 'post'], 'view/{id}', 'TeamManagementController@view')->name('.view');
    Route::post('ajax_delete_image', 'TeamManagementController@ajax_delete_image')->name('.ajax_delete_image')->middleware('checkpermission:teammanagements,delete');
    Route::match(['get', 'post'],'delete/{id}', 'TeamManagementController@delete')->name('.delete');

    Route::get('categories', 'TeamManagementController@category')->name('.team_categories')->middleware('checkpermission:teammanagements,view');

    Route::match(['get', 'post'], 'categories/add', 'TeamManagementController@add_category')->name('.category_add')->middleware('checkpermission:teammanagements,add');
    Route::match(['get', 'post'], 'categories/edit/{id}', 'TeamManagementController@add_category')->name('.category_edit')->middleware('checkpermission:teammanagements,edit');
    Route::get('changeStatus', 'TeamManagementController@ChangeUserStatus')->name('.ChangeUserStatus');
    Route::match(['get', 'post'],'categories/delete/{id}', 'TeamManagementController@category_delete')->name('.category_delete')->middleware('checkpermission:teammanagements,delete');
    Route::post('/update_order', 'TeamManagementController@update_order')->name('.update_order');

});
//===========Team-Management-Closed=============

//===========Widget============

Route::group(['prefix' => 'widget', 'as' => 'widget'], function() {
    Route::get('/', 'WidgetController@index')->name('.index')->middleware('checkpermission:widget,view');
    Route::match(['get', 'post'], 'add', 'WidgetController@add')->name('.add')->middleware('checkpermission:widget,add');
    Route::match(['get', 'post'], 'edit/{id}', 'WidgetController@add')->name('.edit')->middleware('checkpermission:widget,edit');
    Route::match(['get', 'post'], 'view/{id}', 'WidgetController@view')->name('.view')->middleware('checkpermission:widget,view');
    Route::post('ajax_delete_image', 'WidgetController@ajax_delete_image')->name('.ajax_delete_image')->middleware('checkpermission:widget,delete');
    Route::post('ajax_delete2', 'WidgetController@ajax_delete2')->name('.ajax_delete2')->middleware('checkpermission:widget,delete');
    Route::match(['get', 'post'],'delete/{id}', 'WidgetController@delete')->name('.delete')->middleware('checkpermission:widget,delete');
});
//==========Widget-Closed=========

//==========Menus===========

Route::group(['prefix' => 'menus', 'as' => 'menus' ], function() {

    Route::get('/', 'MenuController@index')->name('.index')->middleware('checkpermission:menus,view');

    Route::match(['get', 'post'], 'add', 'MenuController@add')->name('.add')->middleware('checkpermission:menus,add');
    Route::match(['get', 'post'], 'edit/{id}', 'MenuController@add')->name('.edit')->middleware('checkpermission:menus,edit');
    Route::match(['get', 'post'], 'items/{id}/{item_id?}', 'MenuController@items')->name('.items')->middleware('checkpermission:menus,edit');

    Route::post('ajax_get_link_type_list', 'MenuController@ajaxGetLinkTypeList')->name('.ajax_get_link_type_list');
    Route::post('ajax_update_items', 'MenuController@ajaxUpdateItems')->name('.ajax_update_items');
    Route::post('ajax_delete_item', 'MenuController@ajaxDeleteItem')->name('.ajax_delete_item');

    Route::post('ajax_delete_image', 'MenuController@ajax_delete_image')->name('.ajax_delete_image');
    Route::post('ajax_delete_element', 'MenuController@ajaxDeleteElement')->name('.ajax_delete_element');

    Route::match(['get', 'post'],'delete/{id}', 'MenuController@delete')->name('.delete')->middleware('checkpermission:menus,delete');
});


//=============Banners============
Route::group(['prefix' => 'banners', 'as' => 'banners'], function() {
    Route::get('/', 'BannerController@index')->name('.index')->middleware('checkpermission:banners,view');
    Route::match(['get', 'post'], 'add', 'BannerController@add')->name('.add')->middleware('checkpermission:banners,add');
    Route::match(['get', 'post'], 'edit/{banner_id}', 'BannerController@add')->name('.edit')->middleware('checkpermission:banners,edit');

    Route::match(['get', 'post'],'{banner_id}/images', 'BannerController@images')->name('.images');
    Route::match(['get', 'post'],'{banner_id}/images/upload', 'BannerController@uploadImages')->name('.uploadImages');
    Route::match(['get', 'post'],'{banner_id}/images/ajax_banner_update', 'BannerController@ajax_banner_update')->name('.ajax_banner_update');
    Route::match(['get', 'post'],'ajax_delete_image', 'BannerController@ajax_delete_image')->name('.ajax_image_delete');

    Route::match(['get', 'post'],'ajax_delete_video', 'BannerController@ajax_delete_video')->name('.ajax_delete_video');
    Route::match(['get', 'post'],'delete/{banner_id}', 'BannerController@delete')->name('.delete')->middleware('checkpermission:banners,delete');

    Route::match(['get', 'post'],'upload-video', 'BannerController@uploadVideo')->name('.uploadVideo');
});


//=============Banners Image Gallery============
    Route::group(['prefix' => 'managebanners', 'as' => 'managebanners' , 'middleware' => ['allowedmodule:managebanners'] ], function() {

        Route::get('/', 'BannerImageGalleryController@index')->name('.index')->middleware('checkpermission:banners,view');

        Route::match(['get', 'post'], 'add', 'BannerImageGalleryController@add')->name('.add')->middleware('checkpermission:banners,add');
        Route::match(['get', 'post'], 'edit/{banner_id}', 'BannerImageGalleryController@add')->name('.edit')->middleware('checkpermission:banners,edit');

        Route::post('ajax_delete_image', 'BannerImageGalleryController@ajax_delete_image')->name('.ajax_delete_image');

        Route::post('delete/{banner_id}', 'BannerImageGalleryController@delete')->name('.delete')->middleware('checkpermission:banners,delete');
    });

//==========CMS-Pages============

Route::group(['prefix' => 'cms', 'as' => 'cms'], function() {

    Route::get('/', 'CmsController@index')->name('.index')->middleware('checkpermission:cms,view');
    Route::match(['get', 'post'], 'add', 'CmsController@add')->name('.add')->middleware('checkpermission:cms,add');
    Route::match(['get', 'post'], 'edit/{id}', 'CmsController@add')->name('.edit')->middleware('checkpermission:cms,edit');
    Route::match(['get', 'post'], 'view/{id}', 'CmsController@view')->name('.view')->middleware('checkpermission:cms,view');
    Route::post('ajax_delete_image', 'CmsController@ajax_delete_image')->name('.ajax_delete_image');
    Route::match(['get', 'post'],'delete/{id}', 'CmsController@delete')->name('.delete')->middleware('checkpermission:cms,delete');
    Route::match(['get', 'post'], '{id}/seo_view', 'CmsController@seoView')->name('.seo_view')->middleware('checkpermission:cms,view');
    Route::match(['get', 'post'], '{id}/seo_meta', 'CmsController@seoMeta')->name('.seo_meta')->middleware('checkpermission:cms,view');
});

//==========Manage-Cabs===========
if(config('custom.CAB_VERSION') == 2) {
    Route::group(['prefix' => 'cabs', 'as' => 'cabs' ,'middleware' => ['allowedmodule:cab']], function() {

        Route::get('/', 'CabsController@index')->name('.index')->middleware('checkpermission:cabs,view');
        Route::match(['get', 'post'], 'add','CabsController@add')->name('.add')->middleware('checkpermission:cabs,add');
        Route::match(['get', 'post'], 'edit/{id}','CabsController@add')->name('.edit')->middleware('checkpermission:cabs,edit');
        Route::match(['get', 'post'],'delete/{id}','CabsController@delete')->name('.delete')->middleware('checkpermission:cabs,delete');
        Route::post('ajax_delete_image', 'CabsController@ajax_delete_image')->name('.ajax_delete_image');
        Route::post('/update_cab', 'CabsController@update_cab')->name('.update_cab');


        Route::get('/cities', 'CabsController@cities')->name('.cities')->middleware('checkpermission:cabs,view');
        Route::match(['get', 'post'], 'cities_view/{id}', 'CabsController@cities_view')->name('.cities_view');
        Route::match(['get', 'post'], 'cities_add', 'CabsController@cities_add')->name('.cities_add')->middleware('checkpermission:cabs,add');
        Route::match(['get', 'post'], 'cities_edit/{id}', 'CabsController@cities_add')->name('.cities_edit')->middleware('checkpermission:cabs,edit');
        Route::match(['get', 'post'],'cities_delete/{id}', 'CabsController@cities_delete')->name('.cities_delete')->middleware('checkpermission:cabs,delete');
        Route::match(['get', 'post'], 'agent_price/{id}', 'CabsController@cities_agent_price')->name('.cities_agent_price')->middleware('allowedmodule:agentuser');
        Route::post('add_agent_price', 'CabsController@cities_add_agent_price')->name('.cities_add_agent_price')->middleware('allowedmodule:agentuser');


        Route::get('/sightseeing', 'CabsController@sightseeing')->name('.sightseeing')->middleware('checkpermission:cabs,view');
        Route::match(['get', 'post'], 'sightseeing_add', 'CabsController@sightseeing_add')->name('.sightseeing_add')->middleware('checkpermission:cabs,add');
        Route::match(['get', 'post'], 'sightseeing_edit/{id}', 'CabsController@sightseeing_add')->name('.sightseeing_edit')->middleware('checkpermission:cabs,edit');
        Route::match(['get', 'post'], 'sightseeing_view/{id}', 'CabsController@sightseeing_view')->name('.sightseeing_view')->middleware('checkpermission:cabs,view');

        Route::get('/inventory/{city_id?}', 'CabsController@inventory')->name('.inventory')->middleware('checkpermission:cabs,view');
        Route::match(['get', 'post'], 'nextInventory', 'CabsController@nextInventory')->name('.nextInventory')->middleware('checkpermission:cabs,edit');
        Route::match(['get', 'post'], 'saveAllInventory/{save_id?}', 'CabsController@saveAllInventory')->name('.saveAllInventory')->middleware('checkpermission:cabs,edit');
        Route::match(['get', 'post'], 'bulk_inventory', 'CabsController@bulk_inventory')->name('.bulk_inventory')->middleware('checkpermission:cabs,edit');

        Route::get('/price/{city_id?}', 'CabsController@price')->name('.price')->middleware('checkpermission:cabs,view');
        Route::match(['get', 'post'], 'allPrice', 'CabsController@allPrice')->name('.allPrice')->middleware('checkpermission:cabs,edit');
        Route::match(['get', 'post'], 'saveAllPrice/{save_id?}', 'CabsController@saveAllPrice')->name('.saveAllPrice')->middleware('checkpermission:cabs,edit');
       

        /*Route::match(['get', 'post'], 'saveUnitInventory', 'CabMasterController@saveUnitInventory')->name('.saveUnitInventory')->middleware('checkpermission:cabs,edit');
        Route::match(['get', 'post'], 'view/{id}', 'CabMasterController@view')->name('.view');
        Route::match(['get', 'post'], 'saveUnitPrice', 'CabMasterController@saveUnitPrice')->name('.saveUnitPrice')->middleware('checkpermission:cabs,edit');*/
    });
} else {
    Route::group(['prefix' => 'cab_master', 'as' => 'cab_master' ,'middleware' => ['allowedmodule:cab']], function() {

        Route::get('/', 'CabMasterController@index')->name('.index')->middleware('checkpermission:cabs,view');
        Route::match(['get', 'post'], 'add','CabMasterController@add')->name('.add')->middleware('checkpermission:cabs,add');
        Route::match(['get', 'post'], 'edit/{id}','CabMasterController@add')->name('.edit')->middleware('checkpermission:cabs,edit');
        Route::match(['get', 'post'],'delete/{id}','CabMasterController@delete')->name('.delete')->middleware('checkpermission:cabs,delete');
        Route::match(['get', 'post'], 'saveUnitInventory', 'CabMasterController@saveUnitInventory')->name('.saveUnitInventory')->middleware('checkpermission:cabs,edit');
        Route::match(['get', 'post'], 'saveAllInventory/{save_id?}', 'CabMasterController@saveAllInventory')->name('.saveAllInventory')->middleware('checkpermission:cabs,edit');
        Route::get('/inventory/{group_id?}', 'CabMasterController@inventory')->name('.inventory')->middleware('checkpermission:cabs,view');
        Route::get('/price/{group_id?}', 'CabMasterController@price')->name('.price')->middleware('checkpermission:cabs,view');
        Route::post('ajax_delete_image', 'CabMasterController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get', 'post'], 'view/{id}', 'CabMasterController@view')->name('.view');

        Route::match(['get', 'post'], 'saveAllPrice/{save_id?}', 'CabMasterController@saveAllPrice')->name('.saveAllPrice')->middleware('checkpermission:cabs,edit');

        Route::match(['get', 'post'], 'nextInventory', 'CabMasterController@nextInventory')->name('.nextInventory')->middleware('checkpermission:cabs,edit');
        Route::match(['get', 'post'], 'saveUnitPrice', 'CabMasterController@saveUnitPrice')->name('.saveUnitPrice')->middleware('checkpermission:cabs,edit');
        Route::post('/update_cab', 'CabMasterController@update_cab')->name('.update_cab');
        Route::match(['get', 'post'], 'bulk_inventory', 'CabMasterController@bulk_inventory')->name('.bulk_inventory')->middleware('checkpermission:cabs,edit');
    });

    Route::group(['prefix' => 'cab_cities', 'as' => 'cab_cities','middleware' => ['allowedmodule:cab']], function() {
        Route::get('/', 'CabCitiesController@index')->name('.index')->middleware('checkpermission:cabs,view');
        Route::match(['get', 'post'], 'add', 'CabCitiesController@add')->name('.add')->middleware('checkpermission:cabs,add');
        Route::match(['get', 'post'], 'edit/{id}', 'CabCitiesController@add')->name('.edit')->middleware('checkpermission:cabs,edit');
        Route::match(['get', 'post'],'delete/{id}', 'CabCitiesController@delete')->name('.delete')->middleware('checkpermission:cabs,delete');
        Route::match(['get', 'post'], 'view/{id}', 'CabCitiesController@view')->name('.view');
    });

    Route::group(['prefix' => 'cab_addons', 'as' => 'cab_addons','middleware' => ['allowedmodule:cab']], function() {
        Route::get('/', 'CabAddonsController@index')->name('.index')->middleware('checkpermission:cabs,view');
        Route::match(['get', 'post'], 'add', 'CabAddonsController@add')->name('.add')->middleware('checkpermission:cabs,add');
        Route::match(['get', 'post'], 'edit/{id}', 'CabAddonsController@add')->name('.edit')->middleware('checkpermission:cabs,edit');
        Route::match(['get', 'post'],'delete/{id}', 'CabAddonsController@delete')->name('.delete')->middleware('checkpermission:cabs,delete');
        Route::match(['get', 'post'], 'view/{id}', 'CabAddonsController@view')->name('.view');
    });
}

//==========Manage-Bikes===========
Route::group(['prefix' => 'bike_master', 'as' => 'bike_master' ,'middleware' => ['allowedmodule:rental']], function() {
    Route::get('/', 'BikeMasterController@index')->name('.index')->middleware('checkpermission:rental,view');
    Route::match(['get', 'post'], 'add','BikeMasterController@add')->name('.add')->middleware('checkpermission:rental,add');
    Route::match(['get', 'post'], 'edit/{id}','BikeMasterController@add')->name('.edit')->middleware('checkpermission:rental,edit');
    Route::match(['get', 'post'],'delete/{id}','BikeMasterController@delete')->name('.delete')->middleware('checkpermission:rental,delete');
    Route::post('/update_bike', 'BikeMasterController@update_bike')->name('.update_bike');
    Route::post('ajax_delete_image', 'BikeMasterController@ajax_delete_image')->name('.ajax_delete_image');
    Route::get('/inventory/{city_id?}', 'BikeMasterController@inventory')->name('.inventory')->middleware('checkpermission:rental,view');
    Route::match(['get', 'post'], 'nextInventory', 'BikeMasterController@nextInventory')->name('.nextInventory')->middleware('checkpermission:rental,edit');
    Route::match(['get', 'post'], 'bulk_inventory', 'BikeMasterController@bulk_inventory')->name('.bulk_inventory')->middleware('checkpermission:rental,edit');
    Route::match(['get', 'post'], 'saveAllInventory/{save_id?}', 'BikeMasterController@saveAllInventory')->name('.saveAllInventory')->middleware('checkpermission:rental,edit');
    Route::get('/price/{city_id?}', 'BikeMasterController@price')->name('.price')->middleware('checkpermission:rental,view');
    Route::match(['get', 'post'], 'allPrice', 'BikeMasterController@allPrice')->name('.allPrice')->middleware('checkpermission:rental,edit');
    Route::match(['get', 'post'], 'saveAllPrice/{save_id?}', 'BikeMasterController@saveAllPrice')->name('.saveAllPrice')->middleware('checkpermission:rental,edit');
});

//==========Manage-Bike City===========

Route::group(['prefix' => 'bike_cities', 'as' => 'bike_cities','middleware' => ['allowedmodule:rental']], function() {
    Route::get('/', 'BikeCitiesController@index')->name('.index')->middleware('checkpermission:rental,view');
    Route::match(['get', 'post'], 'add', 'BikeCitiesController@add')->name('.add')->middleware('checkpermission:rental,add');
    Route::match(['get', 'post'], 'edit/{id}', 'BikeCitiesController@add')->name('.edit')->middleware('checkpermission:rental,edit');
    Route::match(['get', 'post'], 'changeRentalDefault/', 'BikeCitiesController@changeRentalDefault')->name('.changeRentalDefault')->middleware('checkpermission:rental,edit');
    Route::match(['get', 'post'],'delete/{id}', 'BikeCitiesController@delete')->name('.delete')->middleware('checkpermission:rental,delete');
    Route::match(['get', 'post'], 'view/{id}', 'BikeCitiesController@view')->name('.view');

    Route::match(['get', 'post'], 'locations/{id}', 'BikeCitiesController@locations')->name('.locations');
    Route::post('locations_add', 'BikeCitiesController@locations_add')->name('.locations_add');
    Route::post('locations_get', 'BikeCitiesController@locations_get')->name('.locations_get');
    Route::post('location_delete', 'BikeCitiesController@location_delete')->name('.location_delete');
    Route::post('ajax_locations', 'BikeCitiesController@ajax_locations')->name('.ajax_locations');

    Route::match(['get', 'post'], 'agent_price/{id}', 'BikeCitiesController@agent_price')->name('.agent_price');
    
    Route::post('add_agent_price', 'BikeCitiesController@add_agent_price')->middleware('allowedmodule:agentuser')->name('.add_agent_price');

});

//==========Blog-Categories===========

Route::group(['prefix' => 'blogs_categories', 'as' => 'blogs_categories' , 'middleware' => ['allowedmodule:blog_listing|news'] ], function() {

    Route::get('/', 'BlogCategoryController@index')->name('.index')->middleware('checkpermission:blogs|news,view');
    Route::match(['get', 'post'], 'add', 'BlogCategoryController@add')->name('.add')->middleware('checkpermission:blogs|news,add');
    Route::match(['get', 'post'], 'edit/{id}', 'BlogCategoryController@add')->name('.edit')->middleware('checkpermission:blogs|news,edit');
    Route::get('categories_view/{id}', 'BlogCategoryController@categories_view')->name('.categories_view')->middleware('checkpermission:blogs|news,view');
    Route::post('ajax_delete_image', 'BlogCategoryController@ajax_delete_image')->name('.ajax_delete_image');
    Route::match(['get', 'post'],'delete/{id}', 'BlogCategoryController@delete')->name('.delete')->middleware('checkpermission:blogs|news,delete');
});

//==========Blogs===========

Route::group(['prefix' => 'blogs', 'as' => 'blogs' , 'middleware' => ['allowedmodule:blog_listing|news'] ], function() {

    Route::get('/', 'BlogController@index')->name('.index')->middleware('checkpermission:blogs|news,view');
    Route::match(['get', 'post'], 'add', 'BlogController@add')->name('.add')->middleware('checkpermission:blogs|news,add');
    Route::match(['get', 'post'], 'edit/{id}', 'BlogController@add')->name('.edit')->middleware('checkpermission:blogs,edit');
    Route::match(['get','post'],'blog_view/{id}','BlogController@blog_view')->name('.blog_view')->middleware('checkpermission:blogs|news,view');
    Route::match(['get','post'],'blog_view/{id}','BlogController@blog_view')->name('.blog_view');
    Route::post('ajax_delete_image', 'BlogController@ajax_delete_image')->name('.ajax_delete_image');
    Route::post('ajax_delete_pdf', 'BlogController@ajax_delete_pdf')->name('.ajax_delete_pdf');
    Route::match(['get', 'post'],'delete/{id}', 'BlogController@delete')->name('.delete')->middleware('checkpermission:blogs|news,delete');
    Route::match(['get', 'post'], '{id}/seo_view', 'BlogController@seoView')->name('.seo_view')->middleware('checkpermission:blogs|news,view');
    Route::match(['get', 'post'], '{id}/seo_meta', 'BlogController@seoMeta')->name('.seo_meta')->middleware('checkpermission:blogs|news,edit');
});

 //===========Events=============

Route::group(['prefix' => 'events', 'as' => 'events' , 'middleware' => ['allowedmodule:events'] ], function() {

    Route::get('/', 'EventController@index')->name('.index')->middleware('checkpermission:events,view');
    Route::match(['get', 'post'], 'add', 'EventController@add')->name('.add')->middleware('checkpermission:events,add');
    Route::match(['get', 'post'], 'edit/{id}', 'EventController@add')->name('.edit')->middleware('checkpermission:events,edit');
    Route::post('ajax_delete_image', 'EventController@ajax_delete_image')->name('.ajax_delete_image');
    Route::post('delete/{id}', 'EventController@delete')->name('.delete')->middleware('checkpermission:events,delete');
});

//=============Testimonials==============

Route::group(['prefix' => 'testimonials', 'as' => 'testimonials'], function() {
    Route::get('/', 'TestimonialController@index')->name('.index')->middleware('checkpermission:testimonials,view');
    Route::match(['get', 'post'], 'add', 'TestimonialController@add')->name('.add')->middleware('checkpermission:testimonials,add');
    Route::match(['get', 'post'], 'edit/{id}', 'TestimonialController@add')->name('.edit')->middleware('checkpermission:testimonials,edit');
    Route::match(['get', 'post'], 'view/{id}', 'TestimonialController@view')->name('.view')->middleware('checkpermission:testimonials,view');
    Route::post('ajax_delete_image', 'TestimonialController@ajax_delete_image')->name('.ajax_delete_image');
    Route::match(['get','post'],'delete/{id}', 'TestimonialController@delete')->name('.delete')->middleware('checkpermission:testimonials,delete');
    Route::match(['get', 'post'], '{id}/seo_view', 'TestimonialController@seoView')->name('.seo_view')->middleware('checkpermission:testimonials,view');
    Route::match(['get', 'post'], '{id}/seo_meta', 'TestimonialController@seoMeta')->name('.seo_meta')->middleware('checkpermission:testimonials,view');
});

//============Image-Category=============

Route::group(['prefix' => 'image_categories', 'as' => 'image_categories'], function() {

    Route::get('/', 'ImageCategoryController@index')->name('.index')->middleware('checkpermission:image_categories,view');
    Route::match(['get', 'post'], 'add', 'ImageCategoryController@add')->name('.add')->middleware('checkpermission:image_categories,add');
    Route::match(['get', 'post'], 'edit/{id}', 'ImageCategoryController@add')->name('.edit')->middleware('checkpermission:image_categories,edit');
    Route::post('ajax_delete_image', 'ImageCategoryController@ajax_delete_image')->name('.ajax_delete_image');
    Route::post('delete/{id}', 'ImageCategoryController@delete')->name('.delete')->middleware('checkpermission:image_categories,delete');
});

//=========Images===========

Route::group(['prefix' => 'images', 'as' => 'images' ], function() {
    Route::get('/', 'ImageController@index')->name('.index')->middleware('checkpermission:images,view');
    Route::match(['get','post'],'upload', 'ImageController@upload')->name('.upload')->middleware('checkpermission:images,add');
    Route::match(['get','post'],'upload_view', 'ImageController@upload_view')->name('.upload_view')->middleware('checkpermission:images,view');
    Route::post('ajax_check_exist', 'ImageController@ajaxCheckExist')->name('.ajax_check_exist');
    Route::post('ajax_upload', 'ImageController@ajaxUpload')->name('.ajax_upload');
    Route::post('ajax_update', 'ImageController@ajaxUpdate')->name('.ajax_update');
    Route::post('ajax_delete', 'ImageController@ajaxDelete')->name('.ajax_delete');
    Route::post('ajax_delete_images', 'ImageController@ajaxDeleteImages')->name('.ajax_delete_images');
    Route::post('ajax_upload_from_gallery', 'ImageController@ajaxUploadFromGallery')->name('.ajax_upload_from_gallery');
});

//========Media==========

Route::group(['prefix' => 'media', 'as' => 'media'], function() {
    Route::get('/', 'MediaController@index')->name('.index')->middleware('checkpermission:media,view');
    Route::get('media-frame', 'MediaController@pop')->name('.pop');
    Route::post('ajax-media-details-view', 'MediaController@getMediaAttachmentView')->name('.mediadetailsView');
    Route::post('ajax-media-delete', 'MediaController@ajaxMediaDelete')->name('.ajaxMediaDelete');

    Route::post('upload', 'MediaController@upload')->name('.upload')->middleware('checkpermission:media,add');
    Route::post('store', 'MediaController@store')->name('.store')->middleware('checkpermission:media,edit');
    Route::post('delete/{id}', 'MediaController@delete')->name('.delete')->middleware('checkpermission:media,delete');
    Route::post('ajaxUpdate', 'MediaController@ajaxUpdate')->name('.ajaxUpdate');
});

//========Price-Category==========

Route::group(['prefix' => 'price-category', 'as' => 'price_category'], function() {
    Route::get('/', 'PriceCategoryController@index')->name('.index')->middleware('checkpermission:all_masters,view');
    Route::match(['get', 'post'], 'add', 'PriceCategoryController@add')->name('.add')->middleware('checkpermission:all_masters,add');
    Route::match(['get', 'post'], 'edit/{id}', 'PriceCategoryController@add')->name('.edit')->middleware('checkpermission:all_masters,edit');
    Route::match(['get', 'post'], 'view/{id}', 'PriceCategoryController@view')->name('.view')->middleware('checkpermission:all_masters,view');
    Route::post('delete/{id}', 'PriceCategoryController@delete')->name('.delete')->middleware('checkpermission:all_masters,delete');
});
//========Price-Category-Closed============

    //========Enquiries-Master==========
    Route::group(['prefix' => 'enquiries_master', 'as' => 'enquiries_master'], function() {
        Route::get('/', 'EnquiriesMasterController@index')->name('.index')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'add/', 'EnquiriesMasterController@add')->name('.add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'edit/{id}/{parent_id}', 'EnquiriesMasterController@add')->where(['id'=>'[0-9]+'])->name('.edit')->middleware('checkpermission:all_masters,edit');
        Route::post('delete/{id}', 'EnquiriesMasterController@delete')->name('.delete')->middleware('checkpermission:all_masters,delete');
        Route::post('ajax_enquiries_master', 'EnquiriesMasterController@ajax_enquiries_master')->name('.ajax_enquiries_master');//->middleware('checkpermission:enquiries_master,view');
    });
    //========Enquiries-Master-Closed==========

    //========Enquiries-Master-Group==========
    Route::group(['prefix' => 'enquiries_master_group', 'as' => 'enquiries_master_group'], function() {
        Route::get('/', 'EnquiriesMasterGroupController@index')->name('.index')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'add/', 'EnquiriesMasterGroupController@add')->name('.add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'edit/{id}', 'EnquiriesMasterGroupController@add')->where(['id'=>'[0-9]+'])->name('.edit')->middleware('checkpermission:all_masters,edit');
        Route::post('delete/{id}', 'EnquiriesMasterGroupController@delete')->name('.delete')->middleware('checkpermission:all_masters,delete');
    });
    //========Enquiries-Master-Group-Closed==========

    //======== New Enquiries-Master==========
    Route::group(['prefix' => 'master_enquiries', 'as' => 'master_enquiries'], function() {
        Route::get('/', 'MasterEnquiriesController@index')->name('.index')->middleware('checkpermission:all_masters,view');
        Route::match(['get', 'post'], 'add/', 'MasterEnquiriesController@add')->name('.add')->middleware('checkpermission:all_masters,add');
        Route::match(['get', 'post'], 'edit/{id}/{parent_id}', 'MasterEnquiriesController@add')->where(['id'=>'[0-9]+'])->name('.edit')->middleware('checkpermission:all_masters,edit');
        Route::post('delete/{id}', 'MasterEnquiriesController@delete')->name('.delete')->middleware('checkpermission:all_masters,delete');
        Route::post('ajax_enquiries_master', 'MasterEnquiriesController@ajax_enquiries_master')->name('.ajax_enquiries_master');//->middleware('checkpermission:enquiries_master,view');
    });
    //========New Enquiries-Master-Closed==========


    //===========Email Teamplates===============
    Route::group(['prefix' => 'email-templates', 'as' => 'email-templates'], function() {
        Route::get('/', 'EmailTeamplateController@index')->name('.index')->middleware('checkpermission:email_templates,view');
        Route::match(['get', 'post'], 'add', 'EmailTeamplateController@add')->name('.add')->middleware('checkpermission:email_templates,add');
        Route::match(['get', 'post'], 'edit/{id}', 'EmailTeamplateController@add')->name('.edit')->middleware('checkpermission:email_templates,edit');
         //Route::any('/email-templates', 'EmailTeamplateController@index')->name('.email-templates')->middleware('checkpermission:email_templates,view');
        // Route::any('/email-template-add', 'EmailTeamplateController@add')->name('.email-template-add')->middleware('checkpermission:email_templates,add');
        // Route::any('/email-template-edit/{id}', 'EmailTeamplateController@add')->name('.email-template-edit')->middleware('checkpermission:email_templates,edit');
    });
    

    //===========Sms Teamplates===============
    Route::group(['prefix' => 'sms-templates', 'as' => 'sms-templates'], function() {
        Route::get('/', 'SmsTeamplateController@index')->name('.index')->middleware('checkpermission:sms_templates,view');
        Route::match(['get', 'post'], 'add', 'SmsTeamplateController@add')->name('.add')->middleware('checkpermission:sms_templates,add');
        Route::match(['get', 'post'], 'edit/{id}', 'SmsTeamplateController@add')->name('.edit')->middleware('checkpermission:sms_templates,edit');
    });

// ===============SEO Meta Tag================

    Route::group(['prefix' => 'module_config', 'as' => 'module_config'], function () {
        Route::get('/', 'MetaTagsController@index')->name('.index')->middleware('checkpermission:module_config,view');
        Route::match(['get', 'post'], '/save/{id?}', 'MetaTagsController@save')->name('.save')->middleware('checkpermission:module_config,edit');
        Route::match(['get', 'post'], '/view/{id}', 'MetaTagsController@view')->name('.view')->middleware('checkpermission:module_config,view');

        Route::post('ajax_delete_image', 'MetaTagsController@ajax_delete_image')->name('.ajax_delete_image')->middleware('checkpermission:module_config,delete');
        Route::match(['get', 'post'],'module_config/delete/{id}', 'MetaTagsController@delete')->name('.delete')->middleware('checkpermission:module_config,delete');
    });


 //payment-gateways
    Route::group(['prefix' => 'payment-gateways', 'as' => 'payment-gateways', 'middleware' => ['checkpermission:settings,view'] ], function () {
        Route::any('/change_status', 'PaymentGatewayController@change_status')->name('.change_status');
        Route::any('/submitDispalyName', 'PaymentGatewayController@submitDispalyName')->name('.submitDispalyName');
        Route::any('/add/{id?}', 'PaymentGatewayController@add')->name('.add');
        Route::any('/{setting_id?}', 'PaymentGatewayController@index')->name('.index');
        // Route::any('/', 'PaymentGatewayController@index')->name('.index');
    });

    //========Airport-Master==========
    Route::group(['prefix' => 'airport_master', 'as' => 'airport_master'], function() {
        Route::get('/', 'AirportMasterController@index')->name('.index')->middleware('checkpermission:flight,view');
        Route::match(['get', 'post'], 'add', 'AirportMasterController@add')->name('.add')->middleware('checkpermission:flight,add');
        Route::match(['get', 'post'], 'edit/{id}', 'AirportMasterController@add')->where(['id'=>'[0-9]+'])->name('.edit')->middleware('checkpermission:flight,edit');
        Route::post('delete/{id}', 'AirportMasterController@delete')->name('.delete')->middleware('checkpermission:flight,delete');
        Route::match(['get', 'post'], '/price-markup', 'AirportMasterController@price_markup')->name('.price_markup')->middleware('checkpermission:flight,view');
        // Route::post('/ajax_airport_master', 'AirportMasterController@ajax_airport_master')->name('.ajax_airport_master')->middleware('checkpermission:flight,view');
    });

    //========Airline-Route==========
    Route::group(['prefix' => 'airline_route', 'as' => 'airline_route'], function() {
        Route::match(['get', 'post'], 'add', 'AirlineRouteController@add')->name('.add')->middleware('checkpermission:flight,add');
        Route::match(['get', 'post'], 'edit/{id}', 'AirlineRouteController@add')->where(['id'=>'[0-9]+'])->name('.edit')->middleware('checkpermission:flight,edit');
        Route::match(['get', 'post'], 'view/{id}', 'AirlineRouteController@view')->where(['id'=>'[0-9]+'])->name('.view')->middleware('checkpermission:flight,view');
        Route::post('delete/{id}', 'AirlineRouteController@delete')->name('.delete')->middleware('checkpermission:flight,delete');
        Route::post('/ajax_airline_route', 'AirlineRouteController@ajax_airline_route')->name('.ajax_airline_route')->middleware('checkpermission:flight,view');
        Route::post('/ajax_more_segments', 'AirlineRouteController@ajax_more_segments')->name('.ajax_more_segments')->middleware('checkpermission:flight,view');
        Route::get('/{type?}', 'AirlineRouteController@index')->name('.index')->middleware('checkpermission:flight,view');
    });

    Route::group(['prefix' => 'airline', 'as' => 'airline'], function() {
        Route::get('/', 'AirlineController@index')->name('.index')->middleware('checkpermission:flight,view');
        Route::match(['get', 'post'], 'add', 'AirlineController@add')->name('.add')->middleware('checkpermission:flight,add');
        Route::match(['get', 'post'], 'edit/{id}', 'AirlineController@add')->where(['id'=>'[0-9]+'])->name('.edit')->middleware('checkpermission:flight,edit');
        Route::post('delete/{id}', 'AirlineController@delete')->name('.delete')->middleware('checkpermission:flight,delete');
        Route::post('ajax_delete_image', 'AirlineController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get', 'post'], 'fare_rules', 'SettingsController@fare_rules')->name('.fare_rules')->middleware('checkpermission:flight,add');
        Route::match(['get', 'post'], 'offline_inventory/{type?}', 'AirlineController@offline_inventory')->name('.offline_inventory')->middleware('checkpermission:flight,add');
        Route::match(['get', 'post'], 'offline_inventory_add', 'AirlineController@offline_inventory_add')->name('.offline_inventory_add')->middleware('checkpermission:flight,edit');
        Route::match(['get', 'post'], 'offline_inventory_edit/{id}', 'AirlineController@offline_inventory_add')->name('.offline_inventory_edit')->middleware('checkpermission:flight,add');
        Route::match(['get', 'post'], 'offline_inventory_view/{id}', 'AirlineController@offline_inventory_view')->name('.offline_inventory_view')->middleware('checkpermission:flight,view');
        Route::match(['get', 'post'], 'offline_inventory_confirm/{id}', 'AirlineController@offline_inventory_confirm')->name('.offline_inventory_confirm')->middleware('checkpermission:flight,edit');

        Route::match(['get', 'post'], '/markup', 'AirlineController@Markup')->name('.FlightMarkup')->middleware('checkpermission:flight,view');
        Route::match(['get', 'post'], '/discount', 'AirlineController@DiscountCommission')->name('.FlightDiscount')->middleware('checkpermission:flight,view');
        Route::match(['get', 'post'], '/commission', 'AirlineController@DiscountCommission')->name('.FlightCommission')->middleware('checkpermission:flight,view');
        Route::post('/ajax_airline', 'AirlineController@ajax_airline')->name('.ajax_airline')->middleware('checkpermission:flight,view');

        Route::match(['get', 'post'], '/booking_list', 'AirlineController@booking_list')->name('.booking_list')->middleware('checkpermission:flight,view');
    });
    //========Airport-Master-Closed==========

    Route::get('/media-gallery', 'AdminController@media_gallery')->name('media_gallery')->middleware('checkpermission:all_masters,view');

    Route::post('/load-sales-data', 'HomeController@loadSalesData')->name('loadSalesData');



    Route::group(['prefix' => 'url_redirection', 'as' => 'url_redirection'], function() {
      Route::get('/', 'UrlRedirectionController@index')->name('.index')->middleware('checkpermission:url_redirection,list');
      Route::match(['get', 'post'], 'add/', 'UrlRedirectionController@add')->name('.add')->middleware('checkpermission:url_redirection,add');
      Route::match(['get', 'post'], 'edit/{id}', 'UrlRedirectionController@add')->where(['id'=>'[0-9]+'])->name('.edit')->middleware('checkpermission:url_redirection,edit');
      Route::post('delete/{id}', 'UrlRedirectionController@delete')->name('.delete')->middleware('checkpermission:url_redirection,delete');
    });

/* End - Admin group*/
});


Route::post('/ajax_airports', 'AjaxController@searchAirports')->name('ajax_airports');

//=============Common-Routes=========

Route::group(['prefix' => 'common', 'as' => 'common'], function(){
    Route::post('ajax_load_states', 'CommonController@ajaxLoadStates')->name('.ajax_load_states');
    Route::post('ajax_load_cities', 'CommonController@ajaxLoadCities')->name('.ajax_load_cities');
    Route::post('ajax_load_country_code', 'CommonController@ajaxLoadCountryCode')->name('.ajax_load_country_code');
    Route::match(['post','get'],'ajax_load_sub_destinations', 'CommonController@ajaxLoadSubDestinations')->name('.ajax_load_sub_destinations');
});


//==                ===                 ====
//===========Front-Related-Routes=============
//==                ===                 ====
Route::group(['middleware' => 'redirectToUrl'], function () {
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
Route::get('/', 'HomeController@index')->name('homePage');
// Route::get('testimonials', 'HomeController@testimonials')->name('testimonials');
Route::get('group-partner', 'HomeController@group_partner')->name('group_partner');
Route::match(['post','get'],'customer-review', 'HomeController@customerReview')->name('customer-review');
// Route::match(['post','get'],'ajax_home_section', 'HomeController@ajaxHomeSection')->name('ajaxHomeSection');

// Route::get('travel-insurance', 'HomeController@travelInsurance')->name('travel-insurance');
// Route::get('car-rentals', 'HomeController@carRentals')->name('car-rentals');
Route::match(['post','get'],'enquire-this-trip', 'HomeController@customize_this_trip')->name('enquire-this-trip');

//Route::match(['post','get'],'request-itinerary', 'HomeController@requestDetails')->name('request-itinerary');
Route::match(['post','get'],'download_itineray_form', 'HomeController@download_itineray_form')->name('download_itineray_form');

Route::match(['post','get'],'thankyou', 'HomeController@thankYou')->name('thankyou');
Route::match(['post','get'],'downloads', 'HomeController@downLoads')->name('downloads');
Route::match(['post','get'],'others', 'HomeController@other')->name('others');


//===========Account Login For User Routes======
Route::redirect('account/', 'account/login', 301);
Route::redirect('login', 'account/login', 301);

Route::get('/vendor/login', 'AccountController@vendorlogin')->name('vendorlogin');
Route::group(['prefix' => 'account', 'as' => 'account'], function(){

    Route::match(['get', 'post'], 'login', 'AccountController@login')->name('.login');
    Route::match(['get', 'post'], 'signup', 'AccountController@signup')->name('.signup');

    Route::match(['get', 'post'], 'agent-signup', 'AccountController@agent_signup')->middleware('allowedmodule:agentuser')->name('.agent_signup');

    Route::match(['get', 'post'], 'forgot-password', 'AccountController@forgotPassword')->name('.forgot-password');
    Route::match(['get', 'post'], 'otp', 'AccountController@signup_otp')->name('.signup_otp');
    Route::match(['get', 'post'], 'forgot-otp', 'AccountController@forgot_otp')->name('.forgot_otp');
    Route::match(['get','post'],'change-password', 'AccountController@changePassword')->name('.forgot');

    Route::get('resend-otp', 'AccountController@resend_otp')->name('.resend_otp');
    Route::get('resend-forgot_otp', 'AccountController@forgot_resend_otp')->name('.resend_forgot_otp');

    Route::post('ajax_update_profile_image', 'AccountController@uploadUserImage')->name('.uploadUserImage');

    Route::post('ajax_forgot_password', 'AccountController@ajaxForgot')->name('.ajaxForgot');
    Route::post('ajax_verify_otp', 'AccountController@ajaxVerify')->name('.verify');
    Route::post('ajax_reset_password', 'AccountController@ajaxReset')->name('.reset');
    Route::post('ajax_resend_otp', 'AccountController@ajaxResend')->name('.resendOtp');
});

/*$FRONT_VUE_JS = ['grandindiatour'];
if(in_array(config('custom.theme_name'), $FRONT_VUE_JS)) {
    Route::group(['prefix' => 'user', 'as' => 'user', 'middleware' => ['auth']], function(){
        
        Route::match(['get', 'post'], 'my-profile', 'js\UserController@index')->name('.profile');
    Route::match(['get', 'post'], 'dashboard', 'js\UserController@dashboard')->name('.dashboard');

    Route::match(['get', 'post'], 'load-sales-data', 'js\UserController@loadSalesData')->name('.loadSalesData');

    Route::match(['get', 'post'], 'agent-info', 'js\UserController@agent_info')->middleware('allowedmodule:agentuser')->name('.agent_info');
    Route::match(['get', 'post'], 'viewfile/{id}', 'js\UserController@viewfile')->middleware('allowedmodule:agentuser')->name('.viewfile');
    Route::post('delete-agent-logo', 'js\UserController@deleteAgentLogo')->name('.agent_logo_delete');
    
    Route::get('/', 'js\UserController@index')->name('.index');
    Route::match('get', 'logout', 'js\UserController@signout')->name('.logout');
    Route::match(['get', 'post'],'ajax_update_details', 'js\UserController@updateUserDetails')->name('.updateUserDetails');
    Route::match(['get', 'post'],'ajax_update_agents', 'js\UserController@updateAgentDetails')->name('.updateAgentDetails');
    //Route::match(['get', 'post'],'ajax_update_profile_image', 'UserController@uploadUserImage')->name('.uploadUserImage');
    Route::match(['get', 'post'], 'js\change-password', 'UserController@changePassword')->name('.changePwd');

    Route::match(['post','get'],'my-booking', 'js\UserController@myBooking')->name('.mybooking');
    Route::match(['post','get'],'my-enquiry', 'js\UserController@myEnquiry')->name('.myenquiry');
    Route::match(['post','get'],'calendar-booking', 'js\UserController@calendarBooking')->name('.calendarbooking');
    Route::match(['post','get'],'my-wallet', 'js\UserController@myWallet')->name('.myWallet');
    Route::match(['post','get'],'addwallet', 'js\UserController@addwallet')->name('.addwallet');

    Route::match(['post','get'],'gst', 'js\UserController@manageGst')->name('.gst');
    Route::match(['get', 'post'], 'gst/add', 'js\UserController@addGst')->name('.gst.add');
    Route::match(['get', 'post'], 'gst/edit/{id?}', 'js\UserController@addGst')->name('.gst.edit');

    Route::match(['post','get'],'my-favorite', 'js\UserController@myFavorite')->name('.myfavorite');
    Route::match(['post','get'],'order-details/{id}', 'UserController@orderDetail')->name('.orderDetails');
    Route::match(['get', 'post'],'refund', 'js\UserController@refund')->name('.refund');
    
    Route::match(['post','get'],'record-package-favourite', 'js\UserController@record_package_favourite')->name('.recordfavorite');

    Route::match(['post','get'],'packages', 'js\UserController@packages')->middleware('allowedmodule:agentuser')->name('.packages');
    Route::match(['post','get'],'activities', 'js\UserController@activities')->middleware('allowedmodule:agentuser')->name('.activities');
    Route::match(['post','get'],'hotels', 'js\UserController@hotels')->middleware('allowedmodule:agentuser')->name('.hotels');
    Route::match(['post','get'],'hotel-price/{id}', 'js\UserController@hotel_price')->middleware('allowedmodule:agentuser')->name('.hotel_price');
    Route::match(['post','get'],'cab', 'js\UserController@cab')->middleware('allowedmodule:agentuser')->name('.cab');
    Route::match(['post','get'],'cab-price/{id}', 'js\UserController@cab_price')->middleware('allowedmodule:agentuser')->name('.cab_price');


    Route::match(['post','get'],'rental', 'js\UserController@rental')->middleware('allowedmodule:agentuser')->name('.rental');

    Route::match(['post','get'],'rental-price/{id}', 'js\UserController@rental_price')->middleware('allowedmodule:agentuser')->name('.rental_price');

    Route::match(['get', 'post'],'package_voucher_view/{order_id}', 'js\UserController@package_voucher_view')->name('.package_voucher_view');
    Route::match(['get', 'post'],'/package_voucher_pdf/{order_id}', 'js\UserController@package_voucher_pdf')->name('.package_voucher_pdf');

    // Route::match(['get', 'post'],'/activity_voucher_pdf/{order_id}', 'UserController@activity_voucher_pdf')->name('.activity_voucher_pdf');
    Route::match(['get', 'post'],'activity_voucher_view/{order_id}', 'js\UserController@activity_voucher_view')->name('.activity_voucher_view');
    Route::match(['get', 'post'],'/activity_voucher_pdf/{order_id}', 'js\UserController@activity_voucher_view')->name('.activity_voucher_pdf');
    Route::get('/activity_voucher_sendmail/{order_id}', 'js\UserController@activity_voucher_view')->name('.activity_voucher_sendmail');
    Route::get('/activity_voucher_sendsms/{order_id}', 'js\UserController@activity_voucher_view')->name('.activity_voucher_sendsms');
    Route::post('/showhide-activity-markup/{order_id}', 'js\UserController@showHideActivitySettings')->name('.showHideActivitySettings');

    Route::match(['get', 'post'],'cab_voucher_view/{order_id}', 'js\UserController@cab_voucher_view')->name('.cab_voucher_view');
    Route::match(['get', 'post'],'cab_voucher_pdf/{order_id}', 'js\UserController@cab_voucher_pdf')->name('.cab_voucher_pdf');

    Route::match(['get', 'post'],'rental_voucher_view/{order_id}', 'js\UserController@rental_voucher_view')->name('.rental_voucher_view');
    Route::match(['get', 'post'],'rental_voucher_pdf/{order_id}', 'js\UserController@rental_voucher_pdf')->name('.rental_voucher_pdf');

    Route::match(['get', 'post'],'hotel_voucher_view/{order_id}', 'js\UserController@hotel_voucher_view')->name('.hotel_voucher_view');
    Route::match(['get', 'post'],'hotel_voucher_pdf/{order_id}', 'js\UserController@hotel_voucher_pdf')->name('.hotel_voucher_pdf');

    Route::match(['get', 'post'],'flight_voucher_view/{order_id}', 'js\UserController@flight_voucher_view')->name('.flight_voucher_view');
    Route::match(['get', 'post'],'/flight_voucher_pdf/{order_id}', 'js\UserController@flight_voucher_view')->name('.flight_voucher_pdf');
    Route::get('/flight_voucher_sendmail/{order_id}', 'js\UserController@flight_voucher_view')->name('.flight_voucher_sendmail');
    Route::get('/flight_voucher_sendsms/{order_id}', 'js\UserController@flight_voucher_view')->name('.flight_voucher_sendsms');

    Route::match(['get', 'post'], 'agent-signup-2', 'js\UserController@agent_signup_2')->middleware('allowedmodule:agentuser')->name('.agent_signup_2');
    Route::match(['get', 'post'], 'approval', 'js\UserController@approval')->name('.approval');
    Route::match(['get', 'post'], '/price-markup', 'js\UserController@price_markup')->name('.price_markup');

    Route::post('/get-cancel-flight-charges', 'js\UserController@getCancelFlightCharges')->name('.getCancelFlightCharges');
    Route::post('/cancel-flight', 'js\UserController@cancelFlight')->name('.cancelFlight');
    Route::post('/showhide-flight-markup/{order_id}', 'js\UserController@showHideFlightSettings')->name('.showHideFlightSettings');
    Route::match(['get', 'post'],'js\cancel_flight/{order_id}', 'UserController@cancel_flight')->name('.cancel_flight');
});
} else { */
Route::group(['prefix' => 'user', 'as' => 'user', 'middleware' => ['auth']], function(){

    Route::match(['get', 'post'], 'my-profile', 'UserController@index')->name('.profile');
    Route::match(['get', 'post'], 'dashboard', 'UserController@dashboard')->name('.dashboard');

    Route::match(['get', 'post'], 'load-sales-data', 'UserController@loadSalesData')->name('.loadSalesData');

    Route::match(['get', 'post'], 'agent-info', 'UserController@agent_info')->middleware('allowedmodule:agentuser')->name('.agent_info');
    Route::match(['get', 'post'], 'viewfile/{id}', 'UserController@viewfile')->middleware('allowedmodule:agentuser')->name('.viewfile');
    Route::post('delete-agent-logo', 'UserController@deleteAgentLogo')->name('.agent_logo_delete');
    
    Route::get('/', 'UserController@index')->name('.index');
    Route::match('get', 'logout', 'UserController@signout')->name('.logout');
    Route::match(['get', 'post'],'ajax_update_details', 'UserController@updateUserDetails')->name('.updateUserDetails');
    Route::match(['get', 'post'],'ajax_update_agents', 'UserController@updateAgentDetails')->name('.updateAgentDetails');
    //Route::match(['get', 'post'],'ajax_update_profile_image', 'UserController@uploadUserImage')->name('.uploadUserImage');
    Route::match(['get', 'post'], 'change-password', 'UserController@changePassword')->name('.changePwd');

    Route::match(['post','get'],'my-booking', 'UserController@myBooking')->name('.mybooking');
    Route::match(['post','get'],'my-enquiry', 'UserController@myEnquiry')->name('.myenquiry');
    Route::match(['post','get'],'calendar-booking', 'UserController@calendarBooking')->name('.calendarbooking');
    Route::match(['post','get'],'my-wallet', 'UserController@myWallet')->name('.myWallet');
    Route::match(['post','get'],'addwallet', 'UserController@addwallet')->name('.addwallet');

    Route::match(['post','get'],'gst', 'UserController@manageGst')->name('.gst');
    Route::match(['get', 'post'], 'gst/add', 'UserController@addGst')->name('.gst.add');
    Route::match(['get', 'post'], 'gst/edit/{id?}', 'UserController@addGst')->name('.gst.edit');

    Route::match(['post','get'],'my-favorite', 'UserController@myFavorite')->name('.myfavorite');
    Route::match(['post','get'],'order-details/{id}', 'UserController@orderDetail')->name('.orderDetails');
    Route::match(['get', 'post'],'refund', 'UserController@refund')->name('.refund');
    Route::match(['get', 'post'],'updatePNRDetails', 'UserController@updatePNRDetails')->name('.updatePNRDetails');
    Route::match(['get', 'post'],'cancelOfflineFlight', 'UserController@cancelOfflineFlight')->name('.cancelOfflineFlight');
    Route::post('/bulk_action', 'UserController@bulk_action')->name('.bulk_action');
    
    Route::match(['post','get'],'record-package-favourite', 'UserController@record_package_favourite')->name('.recordfavorite');

    Route::match(['post','get'],'packages', 'UserController@packages')->middleware('allowedmodule:agentuser')->name('.packages');
    Route::match(['post','get'],'activities', 'UserController@activities')->middleware('allowedmodule:agentuser')->name('.activities');
    Route::match(['post','get'],'hotels', 'UserController@hotels')->middleware('allowedmodule:agentuser')->name('.hotels');
    Route::match(['post','get'],'hotel-price/{id}', 'UserController@hotel_price')->middleware('allowedmodule:agentuser')->name('.hotel_price');
    Route::match(['post','get'],'cab', 'UserController@cab')->middleware('allowedmodule:agentuser')->name('.cab');
    Route::match(['post','get'],'cab-price/{id}', 'UserController@cab_price')->middleware('allowedmodule:agentuser')->name('.cab_price');


    Route::match(['post','get'],'rental', 'UserController@rental')->middleware('allowedmodule:agentuser')->name('.rental');

    Route::match(['post','get'],'rental-price/{id}', 'UserController@rental_price')->middleware('allowedmodule:agentuser')->name('.rental_price');

    Route::match(['get', 'post'],'package_voucher_view/{order_id}', 'UserController@package_voucher_view')->name('.package_voucher_view');
    Route::match(['get', 'post'],'/package_voucher_pdf/{order_id}', 'UserController@package_voucher_pdf')->name('.package_voucher_pdf');

    // Route::match(['get', 'post'],'/activity_voucher_pdf/{order_id}', 'UserController@activity_voucher_pdf')->name('.activity_voucher_pdf');
    Route::match(['get', 'post'],'activity_voucher_view/{order_id}', 'UserController@activity_voucher_view')->name('.activity_voucher_view');
    Route::match(['get', 'post'],'/activity_voucher_pdf/{order_id}', 'UserController@activity_voucher_view')->name('.activity_voucher_pdf');
    Route::get('/activity_voucher_sendmail/{order_id}', 'UserController@activity_voucher_view')->name('.activity_voucher_sendmail');
    Route::get('/activity_voucher_sendsms/{order_id}', 'UserController@activity_voucher_view')->name('.activity_voucher_sendsms');
    Route::post('/showhide-activity-markup/{order_id}', 'UserController@showHideActivitySettings')->name('.showHideActivitySettings');

    Route::match(['get', 'post'],'cab_voucher_view/{order_id}', 'UserController@cab_voucher_view')->name('.cab_voucher_view');
    Route::match(['get', 'post'],'cab_voucher_pdf/{order_id}', 'UserController@cab_voucher_pdf')->name('.cab_voucher_pdf');

    Route::match(['get', 'post'],'rental_voucher_view/{order_id}', 'UserController@rental_voucher_view')->name('.rental_voucher_view');
    Route::match(['get', 'post'],'rental_voucher_pdf/{order_id}', 'UserController@rental_voucher_pdf')->name('.rental_voucher_pdf');

    Route::match(['get', 'post'],'hotel_voucher_view/{order_id}', 'UserController@hotel_voucher_view')->name('.hotel_voucher_view');
    Route::match(['get', 'post'],'hotel_voucher_pdf/{order_id}', 'UserController@hotel_voucher_pdf')->name('.hotel_voucher_pdf');

    Route::match(['get', 'post'],'flight_voucher_view/{order_id}', 'UserController@flight_voucher_view')->name('.flight_voucher_view');
    Route::match(['get', 'post'],'/flight_voucher_pdf/{order_id}', 'UserController@flight_voucher_view')->name('.flight_voucher_pdf');
    Route::get('/flight_voucher_sendmail/{order_id}', 'UserController@flight_voucher_view')->name('.flight_voucher_sendmail');
    Route::get('/flight_voucher_sendsms/{order_id}', 'UserController@flight_voucher_view')->name('.flight_voucher_sendsms');

    Route::match(['get', 'post'], 'agent-signup-2', 'UserController@agent_signup_2')->middleware('allowedmodule:agentuser')->name('.agent_signup_2');
    Route::match(['get', 'post'], 'approval', 'UserController@approval')->name('.approval');
    Route::match(['get', 'post'], '/price-markup', 'UserController@price_markup')->name('.price_markup');

    Route::match(['get', 'post'], '/flight-route', 'UserController@flight_route')->name('.flight_route');
    Route::match(['get', 'post'], '/flight-route/add', 'UserController@flight_route_add')->name('.flight_route_add');
    Route::match(['get', 'post'], '/flight-route/edit/{id}', 'UserController@flight_route_add')->name('.flight_route_edit');
    Route::post('/ajax_more_segments', 'AjaxController@ajax_more_segments')->name('.ajax_more_segments');
    // Route::post('/ajax_airport_master', 'AjaxController@ajax_airport_master')->name('.ajax_airport_master');
    Route::post('/ajax_airline', 'AjaxController@ajax_airline')->name('.ajax_airline');
    Route::match(['get', 'post'], '/flight-route/view/{id}', 'UserController@flight_route_view')->name('.flight_route_view');

    Route::match(['get', 'post'], '/flight-inventory', 'UserController@flight_inventory')->name('.flight_inventory');
    Route::match(['get', 'post'], '/flight-inventory/add', 'UserController@flight_inventory_add')->name('.flight_inventory_add');
    Route::match(['get', 'post'], '/flight-inventory/edit/{id}', 'UserController@flight_inventory_add')->name('.flight_inventory_edit');
    Route::match(['get', 'post'], '/flight-inventory/view/{id}', 'UserController@flight_inventory_view')->name('.flight_inventory_view');

    Route::post('/ajax_airline_route', 'UserController@ajax_airline_route')->name('.ajax_airline_route');
    Route::match(['post','get'],'flight-booking', 'UserController@myFlightBooking')->name('.myFlightBooking');

    Route::post('/get-cancel-flight-charges', 'UserController@getCancelFlightCharges')->name('.getCancelFlightCharges');
    Route::post('/cancel-flight', 'UserController@cancelFlight')->name('.cancelFlight');
    Route::post('/showhide-flight-markup/{order_id}', 'UserController@showHideFlightSettings')->name('.showHideFlightSettings');
    Route::match(['get', 'post'],'cancel_flight/{order_id}', 'UserController@cancel_flight')->name('.cancel_flight');
});
//}

Route::match(['post','get'],'booknow', 'BookingController@booknow')->name('booknow');
Route::match(['post','get'],'booking', 'BookingController@booking')->name('booking');
Route::match(['post','get'],'pay_now/{order_no}', 'BookingController@pay_now')->name('pay_now');
Route::match(['get','post'],'response_tpsl', 'BookingController@response_tpsl')->name('response_tpsl');
Route::match(['get','post'],'response_razorpay/{order_no}', 'BookingController@response_razorpay')->name('response_razorpay');
// Route::match(['get','post'],'booking/payment/{order_no}', 'BookingController@bookingPayment')->name('bookingPayment');
Route::match(['get','post'],'payment/thankyou', 'BookingController@thankyou')->name('bookingPayment');
Route::match(['get','post'],'payment/failed', 'BookingController@thankyou')->name('bookingFailed');

Route::group(['middleware' => ['auth']], function(){
//==========Start Package-Booking-Routing============

    Route::match(['post','get'],'book-now', 'BookingController@index')->name('book-now');
    Route::match(['get','post'],'pay-response', 'BookingController@response')->name('payResponse');


    Route::match(['get','post'],'paypal-failure', 'BookingController@paypal_failure')->name('paypalFailure');
    Route::match(['post','get'],'order-details', 'BookingController@orderDetails')->name('order-details');
    Route::match(['post','get'],'successfull', 'BookingController@success')->name('successfull');

//==========End Package-Booking-Routing============

});

//===========Tour-Category-Routing============
$tour_category = CustomHelper::getSeoModules('tour_category');
$tourCategoryListing = $tour_category->page_url??'tour-category';
$tourCategoryDetail = $tour_category->page_detail_url??'tour-category';
Route::get($tourCategoryListing, 'PackageController@tour_category')->name('tourCategoryListing');
Route::get($tourCategoryDetail.'/{tour_category}', 'PackageController@index')->name('tourCategoryDetail');

//===========Activity-Routing============
$activity_listing = CustomHelper::getSeoModules('activity_listing');
$activityListing = $activity_listing->page_url??'activities';
$activityDetail = $activity_listing->page_detail_url??'activity';
Route::get($activityListing, 'PackageController@index')->name('activityListing');
Route::get($activityDetail.'/{slug}', 'PackageController@details')->name('activityDetail');

//===========Package-Routing============
$package_listing = CustomHelper::getSeoModules('package_listing');
$packageListing = $package_listing->page_url??'packages';
$packageDetail = $package_listing->page_detail_url??'package';
Route::get($packageListing, 'PackageController@index')->name('packageListing');
Route::get($packageDetail.'/{slug}', 'PackageController@details')->name('packageDetail');
Route::get($packageDetail.'/pdf/{slug}', 'HomeController@download_itinerary_view')->name('download_itinerary_view');
Route::post('packages/ajax-search-activities', 'PackageController@ajaxSearchPackages')->name('ajaxSearchPackages');


Route::match(['post','get'],'packagespopup/hotel-details/{slug}', 'PackageController@hotelPackagepopupDetails')->name('hotel-details');
Route::post('package/{package_id}/ajaxPriceDetails', 'PackageController@ajaxPriceDetails')->name('.ajaxPriceDetails');
Route::post('package/{package_id}/ajaxPriceSlots', 'PackageController@ajaxPriceSlots')->name('.ajaxPriceSlots');
Route::post('package/{package_id}/ajaxPackagePriceAccommodations', 'PackageController@ajaxPackagePriceAccommodations')->name('.ajaxPackagePriceAccommodations');
Route::post('package/getPackageTypePrice', 'PackageController@getPackageTypePrice')->name('getPackageTypePrice');

Route::get('tour-packages/{slug?}', 'PackageController@index')->name('tourPackages');
Route::get('tour-activities/{slug}', 'PackageController@index')->name('tourActivities');

//==========Blog-Routing==========
$blog_listing = CustomHelper::getSeoModules('blog_listing');
$blogsListing = $blog_listing->page_url??'blog';
$blogsDetail = $blog_listing->page_detail_url??'blogs';
Route::get($blogsListing.'/{category?}', 'BlogController@index')->name('blogsListing');
Route::get($blogsDetail.'/{slug}', 'BlogController@details')->name('blogsDetail');

$news_listing = CustomHelper::getSeoModules('news_listing');
$newsListing = $news_listing->page_url??'news';
$newsDetail = $news_listing->page_detail_url??'news';
Route::get($newsListing.'/{category?}', 'BlogController@index')->name('newsListing');
Route::get($newsDetail.'/{slug}', 'BlogController@details')->name('newsDetail');

//==========Team-Routes==========
$team_listing = CustomHelper::getSeoModules('team_listing');
$teamListing = $team_listing->page_url??'team';
$teamDetail = $team_listing->page_detail_url??'team';
Route::get($teamListing, 'TeamController@index')->name('teamListing');
Route::get($teamDetail.'/{slug}', 'TeamController@details')->name('teamDetail');

//==========Testimonial-Routes==========
$testimonial_listing = CustomHelper::getSeoModules('testimonial_listing');
$testimonialListing = $testimonial_listing->page_url??'testimonials';
$testimonialDetail = $testimonial_listing->page_detail_url??'testimonial';
Route::get($testimonialListing, 'TestimonialController@index')->name('testimonialListing');
Route::match(['get','post'],$testimonialListing.'/add', 'TestimonialController@add')->name('testimonialadd');
Route::get($testimonialDetail.'/{slug}', 'TestimonialController@details')->name('testimonialDetails');

//==========Pay-Online-Routes==========
Route::match(['post','get'],'payment/pay_online', 'PaymentController@payOnline')->name('pay-online');

//==========Contact-us-Routes==========
Route::match(['get','post'],'contact-us', 'HomeController@contact')->name('contacts');

//==========Front=-NewsletterSubscribe-Routes==========
Route::match(['get','post'],'subscriber-newsletter', 'HomeController@newsletterSubscribe')->name('newsletterSubscribe');

// Front Flights Routes
//Route::get('/flights', 'HomeController@flights')->name('flights');
//Route::get('/flight-details', 'HomeController@details')->name('flight-details');
// Front Hotel-listing Routes

//==========Hotel-Routes==========
$hotel_listing = CustomHelper::getSeoModules('hotel_listing');
$hotelListing = $hotel_listing->page_url??'hotels';
$hotelDetail = $hotel_listing->page_detail_url??'hotel';
Route::get($hotelListing, 'AccommodationController@index')->name('hotelListing');
Route::get($hotelDetail.'/{slug}', 'AccommodationController@detail')->name('hotelDetail');
Route::post('accommodations/ajax-search-hotels', 'AccommodationController@ajaxSearchHotels')->name('ajaxSearchHotels');

//==========Destination-Routes==========
$destination_listing = CustomHelper::getSeoModules('destination_listing');
$destinationListing = $destination_listing->page_url??'destinations';
$destinationDetail = $destination_listing->page_detail_url??'destination';
Route::get($destinationListing, 'DestinationController@index')->name('destinationListing');
Route::get($destinationDetail.'/{slug}', 'DestinationController@detail')->name('destinationDetail');

//==========Front-What's-New-Routes==========

/*Route::get('news', 'NewsController@index')->name('newsListing');
Route::get('new/{slug}', 'NewsController@details')->name('newsDetail');*/

//==========Front-Cms-Routes==========
//Route old cms pages to new pages
Route::redirect('content/activities', '/overland-activities', 301);
Route::redirect('content/{slug}', '/{slug}', 301);

// Flight Module Routes
Route::group(['prefix' => 'flight', 'as' => 'flight' , 'middleware' => ['allowedmodule:flight']], function () {
    Route::get('/', 'FlightController@index')->name('.index');
    Route::get('/get_menu', 'FlightController@get_menu')->name('.get_menu');
    // Route::post('/search_airports', 'FlightController@searchAirports')->name('.search_airports');
    Route::match(['get','post'],'/list', 'FlightController@searchResult')->name('.flightSearchResult');
    Route::get('/itinerary/{slug}', 'FlightController@flightDetails')->name('.flightDetail');
    Route::post('/validate_passengers', 'FlightController@validatePassengers')->name('.validatePassengers');
    Route::post('/getFareDetails', 'FlightController@getFareDetails')->name('.getFareDetails');
});

// Cab Module Routes
if(config('custom.CAB_VERSION') == 2) {
    Route::group(['prefix' => 'cab', 'as' => 'cab' , 'middleware' => ['allowedmodule:cab']], function () {
        Route::get('/', 'js\CabsController@index')->name('.index');
        Route::post('/search_filters', 'js\CabsController@searchFilters')->name('.searchFilters');
        Route::get('/list', 'js\CabsController@searchResult')->name('.cabSearchResult');
        Route::post('/search_cities', 'js\CabsController@search_cities')->name('.search_cities');
        Route::get('/book/{price_id}', 'js\CabsController@book')->name('.book');

        Route::get('/intercity', 'js\CabsController@searchResult')->name('.intercity');
        Route::get('/outstation', 'js\CabsController@searchResult')->name('.outstation');
        Route::get('/airport', 'js\CabsController@searchResult')->name('.airport');
        Route::get('/railway', 'js\CabsController@searchResult')->name('.railway');
        Route::get('/sightseeing', 'js\CabsController@searchResult')->name('.sightseeing');

        // Route::get('/get_menu', 'CabController@get_menu')->name('.get_menu');
        // // Route::match(['get','post'],'/list', 'CabController@searchResult')->name('.cabSearchResult');
        // Route::match(['get','post'],'/sightseeing', 'CabController@routeSearch')->name('.routeSearchResult');
        // Route::match(['get','post'],'/sightseeing/detail', 'CabController@routeDetail')->name('.routeDetail');
        // Route::post('/search_destinations', 'CabController@searchDestinations')->name('.search_destinations');

        // Route::post('/search_cities', 'CabController@search_cities')->name('.search_cities');
        // Route::post('/search_route', 'CabController@search_route')->name('.search_route');

    });
} else {
    Route::group(['prefix' => 'cab', 'as' => 'cab' , 'middleware' => ['allowedmodule:cab']], function () {
        Route::get('/', 'CabController@index')->name('.index');
        Route::match(['get','post'],'/addons/{cab_route_id}', 'CabController@addons')->name('.addons');
        Route::get('/book/{cab_route_id}', 'CabController@book')->name('.book');
        Route::get('/get_menu', 'CabController@get_menu')->name('.get_menu');
        Route::match(['get','post'],'/list', 'CabController@searchResult')->name('.cabSearchResult');
        Route::match(['get','post'],'/sightseeing/{slug}', 'CabController@searchResult')->name('.cabRouteResult');
        Route::match(['get','post'],'/sightseeing', 'CabController@routeSearch')->name('.routeSearchResult');
        Route::match(['get','post'],'/sightseeing/detail', 'CabController@routeDetail')->name('.routeDetail');
        Route::post('/search_destinations', 'CabController@searchDestinations')->name('.search_destinations');

        Route::post('/search_cities', 'CabController@search_cities')->name('.search_cities');
        Route::post('/search_route', 'CabController@search_route')->name('.search_route');

    });
}


//==========Bike-Routes==========
$bike_listing = CustomHelper::getSeoModules('rental');
$bikeListing = $bike_listing->page_url??'rental';
$bikeDetail = $bike_listing->page_detail_url??'rental-detail';
Route::get($bikeListing, 'js\RentalController@index')->name('bikeListing');
Route::get($bikeDetail.'/{slug}', 'js\RentalController@detail')->name('bikeDetail');
Route::post('rental/ajaxSearchBike', 'js\RentalController@ajaxSearchBike')->name('ajaxSearchBike');

// Package Module Routes
/*Route::group(['prefix' => 'holidaypackage', 'as' => 'holidaypackage' ], function () {
    Route::get('/', 'PackagejsController@vjs_index')->name('.index');
    Route::get('/details/{slug}', 'PackagejsController@vjs_detail')->name('.details');
    Route::get('/enquire-now', 'PackagejsController@vjs_enquire_now')->name('.enquire-now');
    Route::get('/booknow', 'PackagejsController@vjs_booknow')->name('.booknow');
});*/
// Activity Module Routes
/*Route::group(['prefix' => 'holidayactivity', 'as' => 'holidayactivity' ], function () {
    Route::get('/', 'ActivityController@index')->name('.index');
    Route::get('/details/{slug}', 'ActivityController@details')->name('.details');
  

});*/

// Hotels Routes
/*Route::group(['prefix' => 'hotels', 'as' => 'hotels' ], function () {
    Route::get('/', 'js\HotelsController@index')->name('.index');
    Route::get('/details/{slug}', 'js\HotelsController@vjs_details')->name('.details');
});*/

Route::post('getFormShortCode', 'js\HomeController@getFormShortCode')->name('getFormShortCode');

Route::get('/get_menu', 'js\HomeController@get_menu')->name('get_menu');

Route::match(['post','get'],'book_now', 'js\BookingController@book_now')->name('book_now');

$FRONT_VUE_JS = ['overlandescape','balaji','traveltour','andamanisland','viaggindia','mytravellite','ladakhbikerental','theholidayhaus'];
if(in_array(config('custom.theme_name'), $FRONT_VUE_JS)) {
    Route::get('/', 'js\HomeController@index')->name('homePage');    
    Route::post('/ajaxHomeBanners', 'js\HomeController@ajaxHomeBanners')->name('ajaxHomeBanners');
    Route::post('/ajaxHomeThemes', 'js\HomeController@ajaxHomeThemes')->name('ajaxHomeThemes');   
    Route::post('/ajaxCmsData', 'js\HomeController@ajaxCmsData')->name('ajaxCmsData');

    Route::post('/ajaxFaqsData', 'js\HomeController@ajaxFaqsData')->name('ajaxFaqsData');

    Route::match(['get','post'],'/subscriber-newsletter', 'js\HomeController@newsletterSubscribe')->name('newsletterSubscribe');

    //===========Tour-Category-Routing============
    $tour_category = CustomHelper::getSeoModules('tour_category');
    $tourCategoryListing = $tour_category->page_url??'tour-category';
    $tourCategoryDetail = $tour_category->page_detail_url??'tour-category';
    Route::get($tourCategoryListing, 'js\PackageController@tour_category')->name('tourCategoryListing');
    Route::get($tourCategoryDetail.'/{tour_category}', 'js\PackageController@index')->name('tourCategoryDetail');

    //===========Activity-Routes============
    $activity_listing = CustomHelper::getSeoModules('activity_listing');
    $activityListing = $activity_listing->page_url??'activities';
    $activityDetail = $activity_listing->page_detail_url??'activity';
    Route::get($activityListing, 'js\PackageController@index')->name('activityListing');
    Route::get($activityDetail.'/{slug}', 'js\PackageController@details')->name('activityDetail');

    //===========Package-Routes============
    $package_listing = CustomHelper::getSeoModules('package_listing');
    $packageListing = $package_listing->page_url??'packages';
    $packageDetail = $package_listing->page_detail_url??'package';
    Route::get($packageListing, 'js\PackageController@index')->name('packageListing');
    Route::post('package/ajaxSearchPackage', 'js\PackageController@ajaxSearchPackage')->name('packageSearch');


    Route::get('/enquire-this-trip', 'js\PackageController@enquire_now')->name('enquire-this-trip');    

    Route::get('tour-packages/{slug?}', 'js\PackageController@index')->name('tourPackages');
    Route::get('tour-activities/{slug}', 'js\PackageController@index')->name('tourActivities');
    Route::get($packageDetail.'/{slug}', 'js\PackageController@details')->name('packageDetail');
    Route::get($packageDetail.'/pdf/{slug}', 'js\HomeController@download_itinerary_view')->name('download_itinerary_view');
    Route::post($packageDetail.'/{package_id}/ajaxPriceDetails', 'js\PackageController@ajaxPriceDetails')->name('ajaxPriceDetails');
    Route::post($packageDetail.'/{package_id}/ajaxPackagePriceAccommodations', 'js\PackageController@ajaxPackagePriceAccommodations')->name('ajaxPackagePriceAccommodations');
    Route::post($packageDetail.'/getPackageTypePrice', 'js\PackageController@getPackageTypePrice')->name('getPackageTypePrice');

    //==========Destination-Routes==========
    $destination_listing = CustomHelper::getSeoModules('destination_listing');
    $destinationListing = $destination_listing->page_url??'destinations';
    $destinationDetail = $destination_listing->page_detail_url??'destination';
    Route::get($destinationListing, 'js\DestinationController@index')->name('destinationListing');
    Route::get($destinationDetail.'/{slug}', 'js\DestinationController@detail')->name('destinationDetail');
    Route::post('destination/ajaxDestinationList', 'js\DestinationController@ajaxDestinationList')->name('ajaxDestinationList');

    Route::post('testimonial/ajaxSearchTestimonial', 'js\TestimonialController@ajaxSearchTestimonial')->name('ajaxSearchTestimonial');

    Route::post('blog/ajaxSearchBlog', 'js\BlogController@ajaxSearchBlog')->name('ajaxSearchBlog');    

    //==========Hotel-Routes==========
    $hotel_listing = CustomHelper::getSeoModules('hotel_listing');
    $hotelListing = $hotel_listing->page_url??'hotels';
    $hotelDetail = $hotel_listing->page_detail_url??'hotel';
    Route::get($hotelListing, 'js\HotelController@index')->name('hotelListing');
    Route::post('hotel/ajaxAccommodationList', 'js\HotelController@ajaxAccommodationList')->name('ajaxAccommodationList');
    Route::get($hotelDetail.'/{slug}', 'js\HotelController@details')->name('hotelDetail');
    Route::get($hotelDetail.'/destination/{destination}', 'js\HotelController@index')->name('destinationHotelListing');

    $LOGIN_VUE_JS = ['traveltour','andamanisland','grandindiatour','viaggindia','mytravellite','ladakhbikerental','theholidayhaus'];
    if(in_array(config('custom.theme_name'), $LOGIN_VUE_JS)) {

        Route::post($ADMIN_ROUTE_NAME.'/vendor-ajax-auth', 'js\AccountController@vendorAjaxAuth')->name('vendorAjaxAuth');
        Route::post($ADMIN_ROUTE_NAME.'/vendor-login-otp-check', 'js\AccountController@vendorLoginOtpCheck')->name('vendorLoginOtpCheck');

        $testimonial_listing = CustomHelper::getSeoModules('testimonial_listing');
        $testimonialListing = $testimonial_listing->page_url??'testimonials';
        $testimonialDetail = $testimonial_listing->page_detail_url??'testimonial';

        Route::match(['get','post'],'contact-us', 'js\HomeController@contact')->name('contacts');
        Route::match(['get','post'],$testimonialListing, 'js\TestimonialController@index')->name('testimonialListing');
        Route::match(['get','post'],$testimonialListing.'/add', 'js\TestimonialController@add')->name('testimonialadd');
        Route::get($testimonialDetail.'/{slug}', 'js\TestimonialController@details')->name('testimonialDetails');

        $team_listing = CustomHelper::getSeoModules('team_listing');
        $teamListing = $team_listing->page_url??'team';
        $teamDetail = $team_listing->page_detail_url??'team';
        Route::get($teamListing, 'js\TeamController@index')->name('teamListing');
        Route::get($teamDetail.'/{slug}', 'js\TeamController@details')->name('teamDetail');

        $blog_listing = CustomHelper::getSeoModules('blog_listing');
        $blogsListing = $blog_listing->page_url??'blog';
        $blogsDetail = $blog_listing->page_detail_url??'blogs';
        Route::get($blogsListing.'/{category?}', 'js\BlogController@index')->name('blogsListing');
        Route::get($blogsDetail.'/{slug}', 'js\BlogController@details')->name('blogsDetail');

        $news_listing = CustomHelper::getSeoModules('news_listing');
        $newsListing = $news_listing->page_url??'news';
        $newsDetail = $news_listing->page_detail_url??'news';
        Route::get($newsListing.'/{category?}', 'js\BlogController@index')->name('newsListing');
        Route::get($newsDetail.'/{slug}', 'js\BlogController@details')->name('newsDetail');
        

        Route::match(['post','get'],'payment/pay_online', 'js\PaymentController@payOnline')->name('pay-online');
        
        Route::match(['post','get'],'thankyou', 'js\HomeController@thankYou')->name('thankyou');

        Route::redirect('account/', 'account/login', 301);
        Route::redirect('login', 'account/login', 301);

        Route::get('/vendor/login', 'js\AccountController@vendorlogin')->name('vendorlogin');
        Route::group(['prefix' => 'account', 'as' => 'account'], function(){

            Route::match(['get', 'post'], 'login', 'js\AccountController@login')->name('.login');
            Route::match(['get', 'post'], 'signup', 'js\AccountController@signup')->name('.signup');

            Route::match(['get', 'post'], 'agent-signup', 'js\AccountController@agent_signup')->middleware('allowedmodule:agentuser')->name('.agent_signup');

            Route::match(['get', 'post'], 'forgot-password', 'js\AccountController@forgotPassword')->name('.forgot-password');
            Route::match(['get', 'post'], 'otp', 'js\AccountController@signup_otp')->name('.signup_otp');
            Route::match(['get', 'post'], 'forgot-otp', 'js\AccountController@forgot_otp')->name('.forgot_otp');
            Route::match(['get','post'],'change-password', 'js\AccountController@changePassword')->name('.change_password');

            Route::post('resend-otp', 'js\AccountController@resend_otp')->name('.resend_otp');
            Route::post('resend-forgot_otp', 'js\AccountController@forgot_resend_otp')->name('.resend_forgot_otp');
            Route::post('ajax_update_profile_image', 'js\AccountController@uploadUserImage')->name('.uploadUserImage');
            Route::post('ajax_verify_otp', 'js\AccountController@ajaxVerify')->name('.verify');
            Route::post('ajax_reset_password', 'js\AccountController@ajaxReset')->name('.reset');
            Route::post('ajax_resend_otp', 'js\AccountController@ajaxResend')->name('.resendOtp');
        });

        Route::group(['prefix' => 'user', 'as' => 'user', 'middleware' => ['auth']], function(){
            Route::match(['get', 'post'], 'agent-signup-2', 'js\UserController@agent_signup_2')->middleware('allowedmodule:agentuser')->name('.agent_signup_2');
        });


    }
}elseif(config('custom.theme_name',$FRONT_VUE_JS) == 'grandindiatour'){

    Route::post($ADMIN_ROUTE_NAME.'/vendor-ajax-auth', 'js\AccountController@vendorAjaxAuth')->name('vendorAjaxAuth');
    Route::post($ADMIN_ROUTE_NAME.'/vendor-login-otp-check', 'js\AccountController@vendorLoginOtpCheck')->name('vendorLoginOtpCheck');

    //===========Tour-Category-Routing============
    $tour_category = CustomHelper::getSeoModules('tour_category');
    $tourCategoryDetail = $tour_category->page_detail_url??'tour-category';
    Route::get($tourCategoryListing, 'js\PackageController@tour_category')->name('tourCategoryListing');
    Route::get($tourCategoryDetail.'/{tour_category}', 'js\PackageController@index')->name('tourCategoryDetail');
    
    //===========Activity-Routes============
    $activity_listing = CustomHelper::getSeoModules('activity_listing');
    $activityListing = $activity_listing->page_url??'activities';
    $activityDetail = $activity_listing->page_detail_url??'activity';
    Route::get($activityListing, 'js\PackageController@index')->name('activityListing');
    Route::get($activityDetail.'/{slug}', 'js\PackageController@details')->name('activityDetail');

    //===========Package-Routes============
    $package_listing = CustomHelper::getSeoModules('package_listing');
    $packageListing = $package_listing->page_url??'packages';
    $packageDetail = $package_listing->page_detail_url??'package';
    Route::get($packageListing, 'js\PackageController@index')->name('packageListing');
    Route::post('package/ajaxSearchPackage', 'js\PackageController@ajaxSearchPackage')->name('packageSearch');

    Route::get('/enquire-this-trip', 'js\PackageController@enquire_now')->name('enquire-this-trip');

    Route::get('tour-packages/{slug?}', 'js\PackageController@index')->name('tourPackages');
    Route::get('tour-activities/{slug}', 'js\PackageController@index')->name('tourActivities');
    Route::get($packageDetail.'/{slug}', 'js\PackageController@details')->name('packageDetail');
    Route::get($packageDetail.'/pdf/{slug}', 'js\HomeController@download_itinerary_view')->name('download_itinerary_view');
    Route::post($packageDetail.'/{package_id}/ajaxPriceDetails', 'js\PackageController@ajaxPriceDetails')->name('ajaxPriceDetails');
    Route::post($packageDetail.'/{package_id}/ajaxPackagePriceAccommodations', 'js\PackageController@ajaxPackagePriceAccommodations')->name('ajaxPackagePriceAccommodations');
    Route::post($packageDetail.'/getPackageTypePrice', 'js\PackageController@getPackageTypePrice')->name('getPackageTypePrice'); 


    $blog_listing = CustomHelper::getSeoModules('blog_listing');
    $blogsListing = $blog_listing->page_url??'blog';
    $blogsDetail = $blog_listing->page_detail_url??'blogs';

    Route::get($blogsListing.'/{category?}', 'js\BlogController@index')->name('blogsListing');
    Route::get($blogsDetail.'/{slug}', 'js\BlogController@details')->name('blogsDetail');
    Route::post('blog/ajaxSearchBlog', 'js\BlogController@ajaxSearchBlog')->name('ajaxSearchBlog');  

    //==========Destination-Routes==========
    $destination_listing = CustomHelper::getSeoModules('destination_listing');
    $destinationListing = $destination_listing->page_url??'destinations';
    $destinationDetail = $destination_listing->page_detail_url??'destination';
    Route::get($destinationListing, 'js\DestinationController@index')->name('destinationListing');
    Route::get($destinationDetail.'/{slug}', 'js\DestinationController@detail')->name('destinationDetail');
    Route::post('destination/ajaxDestinationList', 'js\DestinationController@ajaxDestinationList')->name('ajaxDestinationList');

    //==========Team-Routes==========
    $team_listing = CustomHelper::getSeoModules('team_listing');
    $teamListing = $team_listing->page_url??'team';
    $teamDetail = $team_listing->page_detail_url??'team';
    Route::get($teamListing, 'js\TeamController@index')->name('teamListing');
    Route::get($teamDetail.'/{slug}', 'js\TeamController@details')->name('teamDetail');

    //==========Testimonials-Routes==========
    $testimonial_listing = CustomHelper::getSeoModules('testimonial_listing');
    $testimonialListing = $testimonial_listing->page_url??'testimonials';
    $testimonialDetail = $testimonial_listing->page_detail_url??'testimonial';
    Route::match(['get','post'],$testimonialListing, 'js\TestimonialController@index')->name('testimonialListing');
    Route::match(['get','post'],$testimonialListing.'/add', 'js\TestimonialController@add')->name('testimonialadd');
    Route::get($testimonialDetail.'/{slug}', 'js\TestimonialController@details')->name('testimonialDetails');
    Route::post('testimonial/ajaxSearchTestimonial', 'js\TestimonialController@ajaxSearchTestimonial')->name('ajaxSearchTestimonial');


    //==========Hotel-Routes==========
    $hotel_listing = CustomHelper::getSeoModules('hotel_listing');
    $hotelListing = $hotel_listing->page_url??'hotels';
    $hotelDetail = $hotel_listing->page_detail_url??'hotel';
    Route::get($hotelListing, 'js\HotelController@index')->name('hotelListing');
    Route::post('hotel/ajaxAccommodationList', 'js\HotelController@ajaxAccommodationList')->name('ajaxAccommodationList');
    Route::get($hotelDetail.'/{slug}', 'js\HotelController@details')->name('hotelDetail');
    Route::match(['get','post'],'contact-us', 'js\HomeController@contact')->name('contacts');
    Route::match(['post','get'],'payment/pay_online', 'js\PaymentController@payOnline')->name('pay-online');
    Route::match(['post','get'],'thankyou', 'js\HomeController@thankYou')->name('thankyou');

    /*Route::redirect('account/', 'account/login', 301);
        Route::redirect('login', 'account/login', 301);

        Route::get('/vendor/login', 'js\AccountController@vendorlogin')->name('vendorlogin');
        Route::group(['prefix' => 'account', 'as' => 'account'], function(){

            Route::match(['get', 'post'], 'login', 'js\AccountController@login')->name('.login');
            Route::match(['get', 'post'], 'signup', 'js\AccountController@signup')->name('.signup');

            Route::match(['get', 'post'], 'agent-signup', 'js\AccountController@agent_signup')->middleware('allowedmodule:agentuser')->name('.agent_signup');

            Route::match(['get', 'post'], 'forgot-password', 'js\AccountController@forgotPassword')->name('.forgot-password');
            Route::match(['get', 'post'], 'otp', 'js\AccountController@signup_otp')->name('.signup_otp');
            Route::match(['get', 'post'], 'forgot-otp', 'js\AccountController@forgot_otp')->name('.forgot_otp');
            Route::match(['get','post'],'change-password', 'js\AccountController@changePassword')->name('.change_password');

            Route::post('resend-otp', 'js\AccountController@resend_otp')->name('.resend_otp');
            Route::post('resend-forgot_otp', 'js\AccountController@forgot_resend_otp')->name('.resend_forgot_otp');
            Route::post('ajax_update_profile_image', 'js\AccountController@uploadUserImage')->name('.uploadUserImage');
            Route::post('ajax_verify_otp', 'js\AccountController@ajaxVerify')->name('.verify');
            Route::post('ajax_reset_password', 'js\AccountController@ajaxReset')->name('.reset');
            Route::post('ajax_resend_otp', 'js\AccountController@ajaxResend')->name('.resendOtp');
        });

        Route::group(['prefix' => 'user', 'as' => 'user', 'middleware' => ['auth']], function(){
            Route::match(['get', 'post'], 'agent-signup-2', 'js\UserController@agent_signup_2')->middleware('allowedmodule:agentuser')->name('.agent_signup_2');
        });*/
}

$cms_listing = CustomHelper::getSeoModules('cms_listing');
$cmsListing = $cms_listing->page_url??'';
$cmsDetail = $cms_listing->page_detail_url??'';
Route::get($cmsDetail.'/{slug}', 'HomeController@cmsPage');

$CMS_VUE_JS = ['traveltour','andamanisland','grandindiatour','viaggindia','mytravellite','ladakhbikerental','theholidayhaus'];
if(in_array(config('custom.theme_name'), $CMS_VUE_JS)) {
    Route::get($cmsDetail.'/{slug}', 'js\HomeController@cmsPage');
}

});

Route::any('{catchall}', 'js\HomeController@pageNotFound')->where('catchall', '.*')->name('redirectToUrl')->middleware('redirectToUrl');