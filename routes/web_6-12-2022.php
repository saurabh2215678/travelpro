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

Route::get('phpartisan', function(){
        //prd(request()->all());
    $cmd = request('cmd');
    if(!empty($cmd)){
        $exitCode = Artisan::call("$cmd");
        //dd($exitCode);
    }
});

//=========Admin-Related-Routes===========
Route::match(['get', 'post'], 'admin/login', 'Admin\LoginController@index');

$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

// Admin
Route::group(['namespace' => 'Admin', 'prefix' => $ADMIN_ROUTE_NAME, 'as' => $ADMIN_ROUTE_NAME.'.', 'middleware' => ['authadmin']], function() {

//==========logout - URL: /admin/logout============
Route::post('/logout', 'LoginController@logout')->name('logout');

Route::match(['get','post'],'change_password','AdminController@change_password')->name('change_password');
    

//=========Dashboard - URL: /admin==========
Route::get('/', 'HomeController@index')->name('home');
Route::match(['get', 'post'], 'verify_password', 'HomeController@verify_password');
Route::post('ck_upload', 'HomeController@ckUpload')->name('ck_upload');

//========Admins==========    
Route::group(['prefix' => 'users', 'as' => 'users'], function() {
    Route::get('/', 'AdminController@index')->name('.index');
    Route::match(['get', 'post'], 'add/', 'AdminController@add')->name('.add');
    Route::match(['get', 'post'], 'edit/{id}', 'AdminController@add')->where(['id'=>'[0-9]+'])->name('.edit');
    //->middleware('checkpermission:blogs,add')
    Route::post('delete/{id}', 'AdminController@delete')->name('.delete');
});

//========Register-User==========    
Route::group(['prefix' => 'register-users', 'as' => 'register-users'], function() {
    Route::get('/', 'RegisterUserController@index')->name('.index');
    Route::match(['get', 'post'], 'add/', 'RegisterUserController@add')->name('.add');
    Route::match(['get', 'post'], 'edit/{id}', 'RegisterUserController@add')->where(['id'=>'[0-9]+'])->name('.edit');
    //->middleware('checkpermission:blogs,add')
    Route::post('delete/{id}', 'RegisterUserController@delete')->name('.delete');
    Route::match(['get', 'post'], 'view/{id}', 'RegisterUserController@view')->name('.view');
});

//===========Manage-Enquires=============
Route::group(['prefix' => 'enquiries', 'as' => 'enquiries'], function() {
    Route::get('/', 'EnquiryController@index')->name('.index')->middleware('checkpermission:enquiries,view');
    Route::post('/delete/{id}', 'EnquiryController@delete')->name('.delete')->middleware('checkpermission:enquiries,delete');
    Route::get('/view/{id}', 'EnquiryController@view')->name('.view')->middleware('checkpermission:enquiries,view');

    Route::get('customize_packages', 'EnquiryController@customize_this_trip')->name('.customize_packages')->middleware('checkpermission:customize_packages,view');
    Route::post('customize_packages/customize_this_trip_delete/{id}', 'EnquiryController@customize_this_trip_delete')->name('.customize_this_trip_delete')->middleware('checkpermission:customize_packages,delete');
    Route::get('customize_packages/customize_this_trip_view/{id}', 'EnquiryController@customize_this_trip_view')->name('.customize_this_trip_view')->middleware('checkpermission:customize_packages,view');

    Route::get('request_details', 'EnquiryController@request_details')->name('.request_details')->middleware('checkpermission:request_details,view');
    Route::post('request_details/request_detail_delete/{id}', 'EnquiryController@request_detail_delete')->name('.request_detail_delete')->middleware('checkpermission:request_details,delete');
    Route::get('request_details/request_detail_view/{id}', 'EnquiryController@request_detail_view')->name('.request_detail_view')->middleware('checkpermission:request_details,view');

    Route::get('book_now', 'EnquiryController@bookingEnquiry')->name('.book_now')->middleware('checkpermission:book_now,view');
    Route::post('book_now/bookingDelete/{id}', 'EnquiryController@bookingDelete')->name('.bookingDelete')->middleware('checkpermission:book_now,delete');
    Route::get('book_now/bookingView/{id}', 'EnquiryController@bookingView')->name('.bookingView')->middleware('checkpermission:book_now,view');

    Route::get('order_details', 'EnquiryController@bookingOrderEnquiry')->name('.order_details')->middleware('checkpermission:order_details,view');
    Route::post('order_details/orderDelete/{id}', 'EnquiryController@orderDelete')->name('.orderDelete')->middleware('checkpermission:order_details,delete');
    Route::get('order_details/orderView/{id}', 'EnquiryController@orderView')->name('.orderView')->middleware('checkpermission:order_details,view');

    Route::get('customer_reviews', 'EnquiryController@customerReview')->name('.customer_reviews')->middleware('checkpermission:customer_reviews,view');
    Route::post('customer_reviews/customerDelete/{id}', 'EnquiryController@customerDelete')->name('.customerDelete')->middleware('checkpermission:customer_reviews,delete');
    Route::get('customer_reviews/customerView/{id}', 'EnquiryController@customerView')->name('.customerView')->middleware('checkpermission:customer_reviews,view');
});

//=========Add-Manage-Activity-Log============

Route::group(['prefix' => 'activities', 'as' => 'activities'], function() {
    Route::get('/', 'ActivityLogController@index')->name('.index');
    Route::match(['get', 'post'], 'view/{id}', 'ActivityLogController@view')->name('.view');
});


//=========Manage Footer Menu============

    Route::group(['prefix' => 'downloads', 'as' => 'downloads'], function() {
    Route::get('/', 'DownloadController@index')->name('.index');
    Route::match(['get', 'post'], 'add', 'DownloadController@add')->name('.add');
    Route::match(['get', 'post'], 'edit/{id}', 'DownloadController@add')->name('.edit')
    ;
    Route::post('ajax_delete_image', 'DownloadController@ajax_delete_image')->name('.ajax_delete_image')->middleware('checkpermission:downloads,delete');
    Route::match(['get', 'post'], 'view/{id}', 'DownloadController@view')->name('.view');
    Route::post('ajax_delete_documents', 'DownloadController@ajax_delete_documents')->name('.ajax_delete_documents');
    Route::match(['get', 'post'],'delete/{id}', 'DownloadController@delete')->name('.delete');

    Route::get('others', 'DownloadController@others')->name('.others');
    Route::match(['get', 'post'], 'others/add', 'DownloadController@add_other')->name('.add_other');
    Route::match(['get', 'post'], 'others/edit/{id}', 'DownloadController@add_other')->name('.other_edit');
    Route::match(['get', 'post'], 'other_view/{id}', 'DownloadController@other_view')->name('.other_view');
    Route::post('ajax_delete_other_image', 'DownloadController@ajax_delete_other_image')->name('.ajax_delete_other_image')->middleware('checkpermission:others,delete');
    Route::match(['get', 'post'],'others/delete/{id}', 'DownloadController@others_delete')->name('.others_delete');
    
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

        Route::get('/', 'FaqCategoryController@index')->name('.index')->middleware('checkpermission:faq_categories,view');
        Route::match(['get', 'post'], 'add', 'FaqCategoryController@add')->name('.add')->middleware('checkpermission:faq_categories,add');
        Route::match(['get', 'post'], 'edit/{id}', 'FaqCategoryController@add')->name('.edit')->middleware('checkpermission:faq_categories,edit');
        Route::match(['get', 'post'], 'view/{id}', 'FaqCategoryController@view')->name('.view')->middleware('checkpermission:faq_categories,view');
        Route::post('ajax_delete_image', 'FaqCategoryController@ajax_delete_image')->name('.ajax_delete_image');
        Route::match(['get', 'post'],'delete/{id}', 'FaqCategoryController@delete')->name('.delete')->middleware('checkpermission:faq_categories,delete');
    });

//=============Settings============ 
Route::group(['prefix' => 'settings', 'as' => 'settings', 'middleware' => ['allowedmodule:settings'] ], function() {
    Route::match(['get', 'post'], '/{setting_id?}', 'SettingsController@index')->name('.index');
    //Route::any('/{setting_id}', 'SettingsController@index')->name('.index');
});

Route::group(['prefix' => 'newsletter', 'as' => 'newsletter', 'middleware' => ['allowedmodule:newsletter'] ], function() {
   Route::get('/', 'NewsletterController@index')->name('.index')->middleware('checkpermission:newsletter,view');
   Route::post('/delete/{id}', 'NewsletterController@delete')->name('.delete')->middleware('checkpermission:newsletter,delete');
   
}); 

//=======+Modules ==========
Route::group(['prefix' => 'modules', 'as' => 'modules'], function() {
   Route::match(['get', 'post'], '/', 'ModuleController@index')->name('.index');
}); 

//=========For-Countries========= 
Route::group(['prefix' => 'countries', 'as' => 'countries', 'middleware' => ['allowedmodule:countries']], function() {
    Route::get('/', 'CountryController@index')->name('.index')->middleware('checkpermission:countries,view');
    Route::match(['get', 'post'], '/save/{id?}', 'CountryController@save')->name('.save')->middleware('checkpermission:countries,add');
});

//========State===========

Route::group(['prefix' => 'states', 'as' => 'states' , 'middleware' => ['allowedmodule:states'] ], function() {
    Route::get('/', 'StateController@index')->name('.index')->middleware('checkpermission:states,view');
    Route::match(['get', 'post'], '/save/{id?}', 'StateController@save')->name('.save')->middleware('checkpermission:states,add');
});  

//=========City=============
Route::group(['prefix' => 'cities', 'as' => 'cities' , 'middleware' => ['allowedmodule:cities'] ], function() {
    Route::get('/', 'CityController@index')->name('.index')->middleware('checkpermission:cities,view');
    Route::match(['get', 'post'], '/save/{id?}', 'CityController@save')->name('.save')->middleware('checkpermission:cities,add');
});

//===========Destination==============
Route::group(['prefix' => 'destinations', 'as' => 'destinations' , 'middleware' => ['allowedmodule:destinations'] ], function() {

    Route::get('/', 'DestinationController@index')->name('.index')->middleware('checkpermission:destinations,view');

    Route::match(['get', 'post'], 'add', 'DestinationController@add')->name('.add')->middleware('checkpermission:destinations,add');
    Route::match(['get', 'post'], 'edit/{id}', 'DestinationController@add')->name('.edit')->middleware('checkpermission:destinations,edit');
    Route::post('ajax_delete_image', 'DestinationController@ajax_delete_image')->name('.ajax_delete_image')->middleware('checkpermission:destinations,delete');
    Route::match(['get', 'post'],'delete/{id}', 'DestinationController@delete')->name('.delete')->middleware('checkpermission:destinations,delete');
    Route::match(['get', 'post'], 'view/{id}', 'DestinationController@view')->name('.view');
    
//============Destination-Type==============

    Route::get('types', 'DestinationController@types')->name('.destination_types')->middleware('checkpermission:destinations,view');

    Route::match(['get', 'post'], 'types/add', 'DestinationController@type_add')->name('.type_add')->middleware('checkpermission:destinations,add');
    Route::match(['get', 'post'], 'type/edit/{id}', 'DestinationController@type_add')->name('.type_edit')->middleware('checkpermission:destinations,edit');
        Route::match(['get', 'post'],'type/delete/{id}', 'DestinationController@destination_delete')->name('.destination_delete')->middleware('checkpermission:destinations,delete');

//===============Destination-Additional-info================

    Route::get('{id}/additional-info', 'DestinationController@additional_info')->name('.additional_info')->middleware('checkpermission:destinations,view');
    Route::match(['get', 'post'], '{id}/additional-info/add', 'DestinationController@additional_info_add')->name('.info_add')->middleware('checkpermission:destinations,add');
    Route::match(['get', 'post'], '{id}/additional-info/edit/{info_id}', 'DestinationController@additional_info_add')->name('.info_edit')->middleware('checkpermission:destinations,edit');
    Route::post('{id}/additional-info/delete/{info_id}', 'DestinationController@additional_info_delete')->name('.info_delete')->middleware('checkpermission:destinations,delete');

    Route::match(['get', 'post'], 'types/add', 'DestinationController@type_add')->name('.type_add');
    Route::match(['get', 'post'], 'type/edit/{id}', 'DestinationController@type_add')->name('.type_edit');
    Route::post('type/delete/{id}', 'DestinationController@destination_delete')->name('.destination_delete');
    Route::get('changeStatus', 'DestinationController@ChangeUserStatus')->name('.ChangeUserStatus');

});
//=============Add-Manage-Package===============

    Route::group(['prefix' => 'packages', 'as' => 'packages' , 'middleware' => ['allowedmodule:packages'] ], function() {

    Route::get('/', 'PackageController@index')->name('.index');
    Route::match(['get', 'post'], 'add', 'PackageController@add')->name('.add');
    Route::match(['get', 'post'], 'edit/{id}', 'PackageController@add')->name('.edit');
    Route::match(['get', 'post'],'delete/{id}', 'PackageController@delete')->name('.delete');
    Route::post('ajax_delete_image', 'PackageController@ajax_delete_image')->name('.ajax_delete_image');
    Route::match(['get', 'post'], 'package_view/{id}', 'PackageController@package_view')->name('.package_view');

    Route::get('{package_id}/itenaries', 'PackageController@itenaries')->name('.itenaries');
    Route::match(['get', 'post'], '{package_id}/itenary/add', 'PackageController@itenary_add')->name('.itenary_add');
    Route::match(['get', 'post'], '{package_id}/itenary/edit/{id}', 'PackageController@itenary_add')->name('.itenary_edit');
    Route::post('{package_id}/itenary/delete-image', 'PackageController@ajax_delete_itenary_image')->name('.ajax_delete_itenary_image');
    Route::match(['get', 'post'],'{package_id}/itenary/delete/{id}', 'PackageController@itenary_delete')->name('.itenary_delete');
    Route::match(['get', 'post'], '{package_id}/itenary/view/{id}', 'PackageController@itenary_view')->name('.itenary_view');

    //=============Package-Additional-info==============
    Route::match(['get', 'post'],'{id}/additional-info', 'PackageController@additional_info')->name('.additional_info')->middleware('checkpermission:packages,view');
    
    Route::match(['get', 'post'], '{id}/additional-info/add', 'PackageController@additional_info_add')->name('.info_add')->middleware('checkpermission:packages,add');
    Route::match(['get', 'post'], '{id}/additional-info/edit/{info_id}', 'PackageController@additional_info_add')->name('.info_edit')->middleware('checkpermission:packages,edit');
    Route::post('{id}/additional-info/delete/{info_id}', 'PackageController@additional_info_delete')->name('.info_delete')->middleware('checkpermission:packages,delete');

     //=============Package-Accommodations =============

    Route::get('{package_id}/accommodation', 'PackageController@accommodation')->name('.accommodation')->middleware('checkpermission:packages,view');

  Route::match(['get', 'post'], '{package_id}/accommodation/add', 'PackageController@accommodation_add')->name('.accommodation_add')->middleware('checkpermission:packages,add');


    Route::match(['get', 'post'], '{package_id}/accommodation/edit/{accommodation_id}/', 'PackageController@accommodation_add')->name('.accommodation_edit')->middleware('checkpermission:packages,edit');
    Route::post('{package_id}/accommodation/delete/{accommodation_id}', 'PackageController@accommodation_delete')->name('.accommodation_delete')->middleware('checkpermission:packages,delete');




    //=============Package-Accommodations-&-price-info=============

    Route::get('{package_id}/price-info', 'PackageController@price_info')->name('.price_info')->middleware('checkpermission:packages,view');

    Route::match(['get', 'post'], '{package_id}/price-info/add', 'PackageController@price_info_add')->name('.price_add')->middleware('checkpermission:packages,add');
    Route::match(['get', 'post'], '{package_id}/price-info/edit/{info_id}', 'PackageController@price_info_add')->name('.price_edit')->middleware('checkpermission:packages,edit');
    Route::post('{package_id}/price-info/delete/{info_id}', 'PackageController@price_info_delete')->name('.price_delete')->middleware('checkpermission:packages,delete');

   //=========Add-Package-Type==============

    Route::get('types', 'PackageController@type_index')->name('.type_index');
    Route::match(['get', 'post'], 'types/add', 'PackageController@type_add')->name('.type_add');
    Route::match(['get', 'post'], 'types/edit/{id}', 'PackageController@type_add')->name('.type_edit');
    Route::post('types/delete/{id}', 'PackageController@type_delete')->name('.type_delete');
    Route::match(['get', 'post'], 'view/{id}', 'PackageController@type_view')->name('.type_view');
    Route::get('change_status', 'PackageController@ChangeStatus')->name('.ChangeStatus');

//=========Add-Package-Inclusion-List=============

    Route::get('lists', 'PackageController@lists')->name('.package_inclusion_lists');
    Route::match(['get', 'post'], 'lists/add', 'PackageController@add_list')->name('.add_list');
    Route::match(['get', 'post'], 'lists/edit/{id}', 'PackageController@add_list')->name('.list_edit');
    Route::match(['get', 'post'],'lists/delete/{id}', 'PackageController@list_delete')->name('.list_delete');
    Route::match(['get', 'post'], 'list_view/{id}', 'PackageController@list_view')->name('.list_view');
    Route::get('changeStatus', 'PackageController@ChangeUserStatus')->name('.ChangeUserStatus');

    Route::get('airlines', 'PackageController@airlines')->name('.package_airlines');
    Route::match(['get', 'post'], 'airlines/add', 'PackageController@add_airline')->name('.add_airline');
    Route::match(['get', 'post'], 'airlines/edit/{id}', 'PackageController@add_airline')->name('.air_edit');
    Route::match(['get', 'post'],'airlines/delete/{id}', 'PackageController@delete_airline')->name('.delete_airline');
    Route::post('ajax_delete_image_airline', 'PackageController@ajax_delete_image_airline')->name('.ajax_delete_image_airline');
    Route::match(['get', 'post'], 'airline_view/{id}', 'PackageController@airline_view')->name('.airline_view');

//============Add-Service-Level================

    Route::get('services', 'PackageController@serviceLevel')->name('.serviceLevel');
    Route::match(['get', 'post'], 'services/add', 'PackageController@service_add')->name('.service_add');
    Route::match(['get', 'post'], 'services/edit/{id}', 'PackageController@service_add')->name('.service_edit');
    Route::match(['get', 'post'],'services/delete/{id}', 'PackageController@service_delete')->name('.service_delete');
    Route::match(['get', 'post'], 'view/{id}', 'PackageController@service_view')->name('.service_view');
    Route::get('service_status', 'PackageController@serviceStatus')->name('.serviceStatus');

});

//=============Manage-Accommodation===============

    Route::group(['prefix' => 'accommodations', 'as' => 'accommodations'], function() {
    Route::get('/', 'AccommodationController@index')->name('.index');
    Route::match(['get', 'post'], 'add', 'AccommodationController@add')->name('.add');
    Route::match(['get', 'post'], 'edit/{id}', 'AccommodationController@add')->name('.edit')
    ;
    Route::match(['get', 'post'], 'view/{id}', 'AccommodationController@view')->name('.view');
    Route::post('ajax_delete_image', 'AccommodationController@ajax_delete_image')->name('.ajax_delete_image')->middleware('checkpermission:accommodations,delete');
    Route::match(['get', 'post'],'delete/{id}', 'AccommodationController@delete')->name('.delete');

    Route::post('get-accommodation-types', 'AccommodationController@ajax_get_accommodation_types')->name('.ajax_get_accommodation_types');

    Route::get('feature', 'AccommodationController@feature')->name('.accommodations_features');
    Route::match(['get', 'post'], 'feature/add', 'AccommodationController@add_feature')->name('.add_feature');
    Route::match(['get', 'post'], 'feature/edit/{id}', 'AccommodationController@add_feature')->name('.feature_edit');
    Route::match(['get', 'post'], 'feature_view/{id}', 'AccommodationController@feature_view')->name('.feature_view');
    Route::match(['get', 'post'],'feature/delete/{id}', 'AccommodationController@feature_delete')->name('.feature_delete');

    Route::get('facility', 'AccommodationController@facility')->name('.accommodations_facilities');
    Route::match(['get', 'post'], 'facility/add', 'AccommodationController@add_facility')->name('.add_facility');
    Route::match(['get', 'post'], 'facility/edit/{id}', 'AccommodationController@add_facility')->name('.facility_edit');
    Route::match(['get', 'post'], 'facility_view/{id}', 'AccommodationController@facility_view')->name('.facility_view');
    Route::match(['get', 'post'],'facility/delete/{id}', 'AccommodationController@facility_delete')->name('.facility_delete');

    Route::get('category', 'AccommodationController@category')->name('.accommodations_categories');
    Route::match(['get', 'post'], 'category/add', 'AccommodationController@add_category')->name('.add_category');
    Route::match(['get', 'post'], 'category/edit/{id}', 'AccommodationController@add_category')->name('.category_edit');
    Route::match(['get', 'post'], 'category_view/{id}', 'AccommodationController@category_view')->name('.category_view');
    Route::match(['get', 'post'],'category/delete/{id}', 'AccommodationController@category_delete')->name('.category_delete');

    Route::get('room_type', 'AccommodationController@roomTypes')->name('.room_types');
    Route::match(['get', 'post'], 'room_type/add', 'AccommodationController@add_roomtype')->name('.add_roomtype');
    Route::match(['get', 'post'], 'room_type/edit/{id}', 'AccommodationController@add_roomtype')->name('.roomtype_edit');
    Route::match(['get', 'post'], 'room_type_view/{id}', 'AccommodationController@room_type_view')->name('.room_type_view');
    Route::match(['get', 'post'],'room_type/delete/{id}', 'AccommodationController@roomTyp_delete')->name('.roomTyp_delete');
    Route::get('status', 'AccommodationController@changeStatusUser')->name('.changeStatusUser');

    Route::get('room_feature', 'AccommodationController@roomFeature')->name('.room_features');
    Route::match(['get', 'post'], 'room_feature/add', 'AccommodationController@add_roomfeature')->name('.add_roomfeature');
    Route::match(['get', 'post'], 'room_feature/edit/{id}', 'AccommodationController@add_roomfeature')->name('.roomfeatured_edit');
    Route::match(['get', 'post'], 'room_feature_view/{id}', 'AccommodationController@room_feature_view')->name('.room_feature_view');
    Route::match(['get', 'post'],'room_feature/delete/{id}', 'AccommodationController@roomFea_delete')->name('.roomFea_delete');
    Route::get('changeStatus', 'AccommodationController@ChangeUserStatus')->name('.ChangeUserStatus');

    //=============Add-Accommodation-Room===========

    Route::get('{id}/accommodation-room', 'AccommodationController@accommodation_room')->name('.accommodation_room')->middleware('checkpermission:accommodations,view');
    Route::match(['get', 'post'], '{id}/accommodation-room/add', 'AccommodationController@accommodation_room_add')->name('.room_add')->middleware('checkpermission:accommodations,add');
    Route::match(['get', 'post'], '{id}/accommodation-room/edit/{room_id}', 'AccommodationController@accommodation_room_add')->name('.room_edit')->middleware('checkpermission:accommodations,edit');
    Route::match(['get', 'post'], 'accommodation_room_view/{id}', 'AccommodationController@accommodation_room_view')->name('.room_view');
    Route::post('{id}/accommodation-room/delete/{room_id}', 'AccommodationController@accommodation_room_delete')->name('.room_delete')->middleware('checkpermission:accommodations,delete');
}); 
 
//==========Manage-Theme-Category=============

    Route::group(['prefix' => 'theme', 'as' => 'theme'], function() {
    Route::get('/', 'ThemeController@index')->name('.index')->middleware('checkpermission:theme,view');
    Route::match(['get', 'post'], 'add', 'ThemeController@add')->name('.add')->middleware('checkpermission:theme,add');
    Route::match(['get', 'post'], 'edit/{id}', 'ThemeController@add')->name('.edit')->middleware('checkpermission:theme,edit');
    Route::match(['get', 'post'], 'view/{id}', 'ThemeController@view')->name('.view')->middleware('checkpermission:theme,view');
    Route::post('ajax_delete_image', 'ThemeController@ajax_delete_image')->name('.ajax_delete_image');
    Route::match(['get', 'post'],'delete/{id}', 'ThemeController@delete')->name('.delete')->middleware('checkpermission:theme,delete');
});
//=========Team-Management============

    Route::group(['prefix' => 'teammanagements', 'as' => 'teammanagements'], function() {
    Route::get('/', 'TeamManagementController@index')->name('.index');
    Route::match(['get', 'post'], 'add', 'TeamManagementController@add')->name('.add');
    Route::match(['get', 'post'], 'edit/{id}', 'TeamManagementController@add')->name('.edit')
    ;
    Route::match(['get', 'post'], 'view/{id}', 'TeamManagementController@view')->name('.view');
    Route::post('ajax_delete_image', 'TeamManagementController@ajax_delete_image')->name('.ajax_delete_image')->middleware('checkpermission:teammanagements,delete');
    Route::match(['get', 'post'],'delete/{id}', 'TeamManagementController@delete')->name('.delete');

     Route::get('categories', 'TeamManagementController@category')->name('.team_categories');

    Route::match(['get', 'post'], 'categories/add', 'TeamManagementController@add_category')->name('.add_category');
    Route::match(['get', 'post'], 'categories/edit/{id}', 'TeamManagementController@add_category')->name('.category_edit');
    Route::get('changeStatus', 'TeamManagementController@ChangeUserStatus')->name('.ChangeUserStatus');
    Route::match(['get', 'post'],'categories/delete/{id}', 'TeamManagementController@category_delete')->name('.category_delete');
    
});
//===========Team-Management-Closed=============

//===========Widget============

    Route::group(['prefix' => 'widget', 'as' => 'widget'], function() {
    Route::get('/', 'WidgetController@index')->name('.index');
    Route::match(['get', 'post'], 'add', 'WidgetController@add')->name('.add');
    Route::match(['get', 'post'], 'edit/{id}', 'WidgetController@add')->name('.edit');
    Route::match(['get', 'post'], 'view/{id}', 'WidgetController@view')->name('.view');
    Route::post('ajax_delete_image', 'WidgetController@ajax_delete_image')->name('.ajax_delete_image')->middleware('checkpermission:widget,delete');
    Route::post('ajax_delete2', 'WidgetController@ajax_delete2')->name('.ajax_delete2')->middleware('checkpermission:widget,delete');
    Route::match(['get', 'post'],'delete/{id}', 'WidgetController@delete')->name('.delete');
});
//==========Widget-Closed=========

//==========Menus===========

Route::group(['prefix' => 'menus', 'as' => 'menus' , 'middleware' => ['allowedmodule:menus'] ], function() {

    Route::get('/', 'MenuController@index')->name('.index')->middleware('checkpermission:menus,view');

    Route::match(['get', 'post'], 'add', 'MenuController@add')->name('.add')->middleware('checkpermission:menus,add');
    Route::match(['get', 'post'], 'edit/{id}', 'MenuController@add')->name('.edit')->middleware('checkpermission:menus,edit');
    Route::match(['get', 'post'], 'items/{id}/{item_id?}', 'MenuController@items')->name('.items');

    Route::post('ajax_get_link_type_list', 'MenuController@ajaxGetLinkTypeList')->name('.ajax_get_link_type_list');
    Route::post('ajax_update_items', 'MenuController@ajaxUpdateItems')->name('.ajax_update_items');
    Route::post('ajax_delete_item', 'MenuController@ajaxDeleteItem')->name('.ajax_delete_item');
    
    Route::post('ajax_delete_image', 'MenuController@ajax_delete_image')->name('.ajax_delete_image');
    Route::post('ajax_delete_element', 'MenuController@ajaxDeleteElement')->name('.ajax_delete_element');

    Route::post('delete/{id}', 'MenuController@delete')->name('.delete')->middleware('checkpermission:menus,delete');
});


//=============Banners============
Route::group(['prefix' => 'banners', 'as' => 'banners' , 'middleware' => ['allowedmodule:banners'] ], function() {
    Route::get('/', 'BannerController@index')->name('.index')->middleware('checkpermission:banners,view');
    Route::match(['get', 'post'], 'add', 'BannerController@add')->name('.add')->middleware('checkpermission:banners,add');
    Route::match(['get', 'post'], 'edit/{banner_id}', 'BannerController@add')->name('.edit')->middleware('checkpermission:banners,edit');

    Route::get('{banner_id}/images', 'BannerController@images')->name('.images');
    Route::post('{banner_id}/images/upload', 'BannerController@uploadImages')->name('.uploadImages');
    Route::post('{banner_id}/images/ajax_banner_update', 'BannerController@ajax_banner_update')->name('.ajax_banner_update');
    Route::post('ajax_delete_image', 'BannerController@ajax_delete_image')->name('.ajax_image_delete');

    Route::post('ajax_delete_video', 'BannerController@ajax_delete_video')->name('.ajax_delete_video');
    Route::post('delete/{banner_id}', 'BannerController@delete')->name('.delete')->middleware('checkpermission:banners,delete');

    Route::post('upload-video', 'BannerController@uploadVideo')->name('.uploadVideo');
});

//==========CMS-Pages============

Route::group(['prefix' => 'cms', 'as' => 'cms' , 'middleware' => ['allowedmodule:cms'] ], function() {

    Route::get('/', 'CmsController@index')->name('.index')->middleware('checkpermission:cms,view');
    Route::match(['get', 'post'], 'add', 'CmsController@add')->name('.add')->middleware('checkpermission:cms,add');
    Route::match(['get', 'post'], 'edit/{id}', 'CmsController@add')->name('.edit')->middleware('checkpermission:cms,edit');
    Route::match(['get', 'post'], 'view/{id}', 'CmsController@view')->name('.view')->middleware('checkpermission:cms,view');
    Route::post('ajax_delete_image', 'CmsController@ajax_delete_image')->name('.ajax_delete_image');
    Route::match(['get', 'post'],'delete/{id}', 'CmsController@delete')->name('.delete')->middleware('checkpermission:cms,delete');
});

//==========Blog-Categories===========

Route::group(['prefix' => 'blogs_categories', 'as' => 'blogs_categories' , 'middleware' => ['allowedmodule:blogs_categories'] ], function() {

    Route::get('/', 'BlogCategoryController@index')->name('.index')->middleware('checkpermission:blogs_categories,view');
    Route::match(['get', 'post'], 'add', 'BlogCategoryController@add')->name('.add')->middleware('checkpermission:blogs_categories,add');
    Route::match(['get', 'post'], 'edit/{id}', 'BlogCategoryController@add')->name('.edit')->middleware('checkpermission:blogs_categories,edit');
    Route::get('categories_view/{id}', 'BlogCategoryController@categories_view')->name('.categories_view')->middleware('checkpermission:cms,categories_view');
    Route::post('ajax_delete_image', 'BlogCategoryController@ajax_delete_image')->name('.ajax_delete_image');
    Route::match(['get', 'post'],'delete/{id}', 'BlogCategoryController@delete')->name('.delete')->middleware('checkpermission:blogs_categories,delete');
});

//==========Blogs===========

Route::group(['prefix' => 'blogs', 'as' => 'blogs' , 'middleware' => ['allowedmodule:blogs|news'] ], function() {
    
    Route::get('/', 'BlogController@index')->name('.index')->middleware('checkpermission:blogs,view');
    Route::match(['get', 'post'], 'add', 'BlogController@add')->name('.add')->middleware('checkpermission:blogs,add');
    Route::match(['get', 'post'], 'edit/{id}', 'BlogController@add')->name('.edit')->middleware('checkpermission:blogs,edit');
    Route::match(['get','post'],'blog_view/{id}','BlogController@blog_view')->name('.blog_view')->middleware('checkpermission:blogs,view');
    Route::match(['get','post'],'blog_view/{id}','BlogController@blog_view')->name('.blog_view');
    Route::post('ajax_delete_image', 'BlogController@ajax_delete_image')->name('.ajax_delete_image');
    Route::post('ajax_delete_pdf', 'BlogController@ajax_delete_pdf')->name('.ajax_delete_pdf');
    Route::match(['get', 'post'],'delete/{id}', 'BlogController@delete')->name('.delete')->middleware('checkpermission:blogs,delete');
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

Route::group(['prefix' => 'images', 'as' => 'images' , 'middleware' => ['allowedmodule:images']], function() {
    Route::get('/', 'ImageController@index')->name('.index')->middleware('checkpermission:images,view');
    Route::match(['get','post'],'upload', 'ImageController@upload')->name('.upload')->middleware('checkpermission:images,add');
    Route::post('ajax_check_exist', 'ImageController@ajaxCheckExist')->name('.ajax_check_exist');
    Route::post('ajax_upload', 'ImageController@ajaxUpload')->name('.ajax_upload');
    Route::post('ajax_update', 'ImageController@ajaxUpdate')->name('.ajax_update');
    Route::post('ajax_delete', 'ImageController@ajaxDelete')->name('.ajax_delete');
    Route::post('ajax_delete_images', 'ImageController@ajaxDeleteImages')->name('.ajax_delete_images');
});

//========Media==========

Route::group(['prefix' => 'media', 'as' => 'media'], function() {
    Route::get('/', 'MediaController@index')->name('.index');

    Route::get('media-frame', 'MediaController@pop')->name('.pop');
    Route::post('ajax-media-details-view', 'MediaController@getMediaAttachmentView')->name('.mediadetailsView');
    Route::post('ajax-media-delete', 'MediaController@ajaxMediaDelete')->name('.ajaxMediaDelete');

    Route::post('upload', 'MediaController@upload')->name('.upload');
    Route::post('store', 'MediaController@store')->name('.store');
    Route::post('delete/{id}', 'MediaController@delete')->name('.delete');
});

//========Price-Category==========

Route::group(['prefix' => 'price-category', 'as' => 'price_category'], function() {
    Route::get('/', 'PriceCategoryController@index')->name('.index');
    Route::match(['get', 'post'], 'add', 'PriceCategoryController@add')->name('.add');
    Route::match(['get', 'post'], 'edit/{id}', 'PriceCategoryController@add')->name('.edit');
    Route::match(['get', 'post'], 'view/{id}', 'PriceCategoryController@view')->name('.view');
    Route::post('delete/{id}', 'PriceCategoryController@delete')->name('.delete');
});
//========Price-Category-Closed============

/* End - Admin group*/
});

//=============Common-Routes=========

Route::group(['prefix' => 'common', 'as' => 'common'], function(){
    Route::post('ajax_load_states', 'CommonController@ajaxLoadStates')->name('.ajax_load_states');
    Route::post('ajax_load_cities', 'CommonController@ajaxLoadCities')->name('.ajax_load_cities');
    Route::match(['post','get'],'ajax_load_sub_destinations', 'CommonController@ajaxLoadSubDestinations')->name('.ajax_load_sub_destinations');
});

//===========Front-Related-Routes===========

Route::get('/', 'HomeController@index');
Route::get('experiences/{slug}', 'HomeController@experiences')->name('experiences');
Route::get('testimonials', 'HomeController@testimonials')->name('testimonials');
Route::get('group-partner', 'HomeController@group_partner')->name('group_partner');
Route::match(['post','get'],'customer-review', 'HomeController@customerReview')->name('customer-review');
// Route::get('travel-insurance', 'HomeController@travelInsurance')->name('travel-insurance');
// Route::get('car-rentals', 'HomeController@carRentals')->name('car-rentals');
Route::match(['post','get'],'enquire-this-trip', 'HomeController@customize_this_trip')->name('enquire-this-trip');
Route::match(['post','get'],'request-itinerary', 'HomeController@requestDetails')->name('request-itinerary');
Route::match(['post','get'],'thankyou', 'HomeController@thankYou')->name('thankyou');
Route::match(['post','get'],'downloads', 'HomeController@downLoads')->name('downloads');
Route::match(['post','get'],'others', 'HomeController@other')->name('others');


//===========Account Login For User Routes======
Route::redirect('account/', 'login', 301);
Route::redirect('login', 'account/login', 301)->name('login');

Route::group(['prefix' => 'account', 'as' => 'account'], function(){

    Route::match(['get', 'post'], 'login', 'AccountController@login');
    Route::match(['get', 'post'], 'signup', 'AccountController@signup')->name('.signup');
    Route::match(['get', 'post'], 'forgot-password', 'AccountController@forgotPassword')->name('.forgot-password');
    Route::match(['get', 'post'], 'otp', 'AccountController@signup_otp');
    Route::match(['get', 'post'], 'forgot-otp', 'AccountController@forgot_otp');
    Route::match(['get','post'],'change-password', 'AccountController@changePassword')->name('.forgot');

    Route::get('resend-otp', 'AccountController@resend_otp')->name('.resend_otp');
    Route::get('resend-forgot_otp', 'AccountController@forgot_resend_otp')->name('.resend_forgot_otp');

    Route::post('ajax_update_profile_image', 'AccountController@uploadUserImage')->name('.uploadUserImage');

    Route::post('ajax_forgot_password', 'AccountController@ajaxForgot')->name('.forgot');
    Route::post('ajax_verify_otp', 'AccountController@ajaxVerify')->name('.verify');
    Route::post('ajax_reset_password', 'AccountController@ajaxReset')->name('.reset');

    Route::post('ajax_resend_otp', 'AccountController@ajaxResend')->name('.resendOtp');

});

Route::group(['prefix' => 'user', 'as' => 'user', 'middleware' => ['auth']], function(){

    Route::match(['get', 'post'], 'my-profile', 'UserController@index')->name('.profile');
    Route::get('/', 'UserController@index')->name('.index');
    Route::match('get', 'logout', 'UserController@signout')->name('.logout');
    Route::match(['get', 'post'],'ajax_update_details', 'UserController@updateUserDetails')->name('.updateUserDetails');
    //Route::match(['get', 'post'],'ajax_update_profile_image', 'UserController@uploadUserImage')->name('.uploadUserImage');
    Route::match(['get', 'post'], 'change-password', 'UserController@changePassword')->name('.changePwd');
    Route::match(['post','get'],'my-booking', 'UserController@myBooking')->name('.mybooking');
    Route::match(['post','get'],'my-favorite', 'UserController@myFavorite')->name('.myfavorite');

    Route::match(['post','get'],'order-details/{id}', 'UserController@orderDetail')->name('.orderDetails');

    Route::match(['post','get'],'record-package-favourite', 'UserController@record_package_favourite')->name('.recordfavorite');

});

Route::group(['middleware' => ['auth']], function(){
//==========Start Package-Booking-Routing============

    Route::match(['post','get'],'book-now', 'BookingController@index')->name('book-now');
    Route::match(['get','post'],'pay-response', 'BookingController@response')->name('payResponse');
    Route::match(['get','post'],'booking/payment/{order_no}', 'BookingController@bookingPayment')->name('bookingPayment');
    Route::match(['get','post'],'paypal-failure', 'BookingController@paypal_failure')->name('paypalFailure');

    Route::match(['post','get'],'order-details', 'BookingController@orderDetails')->name('order-details');
    Route::match(['post','get'],'successfull', 'BookingController@success')->name('successfull');

//==========End Package-Booking-Routing============
});
//===========Package-Routing============

Route::match(['post','get'],'packages', 'PackageController@index')->name('packageListing');
Route::match(['post','get'],'package/{slug}', 'PackageController@details')->name('packageDetail');
Route::match(['post','get'],'packagespopup/hotel-details/{slug}', 'PackageController@hotelPackagepopupDetails')->name('hotel-details');

//==========Front-Blog-Routes==========

Route::get('blogs', 'BlogController@index')->name('blogsListing');
Route::get('blog/{slug}', 'BlogController@details')->name('blogsDetail');

//==========Front-Team-Routes==========

Route::get('team', 'TeamController@index')->name('team');
Route::get('team/{slug}', 'TeamController@details')->name('expertDetail');

//==========Front-Contact-us-Routes==========

Route::match(['get','post'],'contact-us', 'HomeController@contact')->name('contacts');

//==========Front=-NewsletterSubscribe-Routes==========

Route::match(['get','post'],'subscriber-newsletter', 'HomeController@newsletterSubscribe')->name('newsletterSubscribe');

// Front Flights Routes
//Route::get('/flights', 'HomeController@flights')->name('flights');
//Route::get('/flight-details', 'HomeController@details')->name('flight-details');
// Front Hotel-listing Routes
Route::get('hotels', 'AccommodationController@index')->name('hotel-listing');
Route::get('hotel/{slug}', 'AccommodationController@detail')->name('hotel-detail');
//==========Front-Theme-Listing-Routes==========

Route::get('/themes', 'ThemeController@theme_listing')->name('theme-listing');
Route::get('/destinations', 'DestinationController@destinationsListing')->name('destinationListings');

// Route::get('/faqs', 'FaqController@index')->name('faqs');

// Route::get('/faqs/{slug}', 'FaqController@list')->name('faq-lisitng');

//==========Front-What's-New-Routes==========

Route::get('news', 'NewsController@index')->name('newsListing');
Route::get('new/{slug}', 'NewsController@details')->name('newsDetail');

//==========Front-Cms-Routes==========

Route::get('/{slug}', 'HomeController@cmsPage');

