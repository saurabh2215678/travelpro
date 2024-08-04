<?php
namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use App\Models\EmailTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

// use Storage;
use DB;
use Mail;
use Cache;

class SettingsController extends Controller {

    private $typeArr;
    private $ADMIN_ROUTE_NAME;

    public function __construct() {
        $this->typeArr = config('custom.setting_types_arr');
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request) {        
        $settingRow = '';
        $method = $request->method();
        $type = (isset($request->type))?$request->type:'website';
        $setting_id = $request->setting_id;
        $page_heading = 'Manage Settings';
        $admin_id = auth('admin')->user()->id;

        if(is_numeric($setting_id) && $setting_id > 0){
            $settingRow = Setting::find($setting_id);
        }

        if($method == 'POST' || $method == 'post'){

            //prd($request->toArray());
            $rules = [];
            $validation_msg = [];
            $rules['label'] = 'required';
            $rules['name'] = 'required|unique:website_settings,name,'.$setting_id;
            $rules['value'] = 'nullable';
            $rules['default_value'] = 'nullable';

            $validation_msg['label.required'] = 'The Label field is required.';
            $validation_msg['name.required'] = 'The Name field is required.';
            $validator = $this->validate($request, $rules,$validation_msg);

            //$description = (isset($request->description))? $request->description:'';

            $setting = new Setting;
            $old_value = '';
            $old_file = '';
            $action = 'Add';
            $success_msg = 'Setting has been added successfully';
            if(isset($settingRow->id) && $settingRow->id == $setting_id){
                $setting = $settingRow;
                $old_value = $settingRow->value;
                $old_file = $settingRow->value;
                $action = 'Update';
                $success_msg = 'Setting has been updated successfully';
            }

            $fieldType['field_type'] = (!empty($request->field_type))?$request->field_type:'';
            $setting['type'] = (!empty($request->type))?$request->type:'';
            $setting['name'] = (!empty($request->name))?$request->name:'';
            $setting['description'] = (!empty($request->description))?$request->description:'';
            $setting['label'] = (!empty($request->label))?$request->label:'';
            $setting['css_class'] = (!empty($request->css_class))?$request->css_class:'';
            $setting['field_type'] = (!empty($request->field_type))?$request->field_type:'';
            $setting['old_value'] = (!empty($request->old_value))?$request->old_value:'';
            $setting['default_value'] = (!empty($request->default_value))?$request->default_value:'';
            // $fieldType = $request->field_type;
            // $setting->type = $request->type;
            // $setting->name = $request->name;
            // $setting->description = $description??'';
            // $setting->label = $request->label;
            // $setting->css_class = $request->css_class;
            // $setting->field_type = $request->field_type;
            // $setting->old_value = $old_value;
            // $setting->default_value = $request->default_value??'';

            if($fieldType != 'file'){
                $setting->value = $request->value;
            }
            $isSaved = $setting->save();
            $website_setting_id = $setting->id??'';
            
            if($isSaved){

                $this->saveFile($request, $setting, $old_file);

                //=============Activity Logs=================

                $user_id = auth()->user()->id;
                $user_name = auth()->user()->name;

                $new_data = DB::table('website_settings')->where('id',$website_setting_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $website_setting_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'WebsiteSettings';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = $action;

                CustomHelper::RecordActivityLog($params);

                //=============Activity Logs================

                session()->flash('alert-success', $success_msg);
                return redirect(url('admin/settings?type='.$type));
            }
        }

        //prd($this->typeArr);
        $data = [];
        $name = (isset($request->name))?$request->name:'';
        $settingsQuery = Setting::where('is_dedicated',0)->orderBy('id', 'asc');        
        if(!empty($type) && isset($this->typeArr[$type])){
            $settingsQuery->where('type', $type);
        }
        if (!empty($name)) {
            $settingsQuery->where(function($q1) use($name){
               $q1->where("name", "like", "%" . $name . "%");
               $q1->orWhere("label", "like", "%" . $name . "%");
             //$q1->orWhere("css_class", "like", "%" . $name . "%");
               $q1->orWhere("value", "like", "%" . $name . "%");
           });
        }
        $settings = $settingsQuery->get();
        $data['page_heading'] = $page_heading;
        $data['type'] = $type;
        $data['settings'] = $settings;
        $data['settingRow'] = $settingRow;
        //Cache Flush
        Cache::forget('settings');
        return view('admin.settings.index', $data);
    }

    private function saveFile($request, $setting, $old_file='' ,$value='value'){

        $file = $request->file($value);
        //prd($old_file);
        if($file && $setting){
            $path = 'settings';
            $result = CustomHelper::UploadFile($file, $path);
            //prd($result);

            if($result['success']){
                $setting->value = $result['file_name'];
                $setting->save();
                $filePath = 'settings/';
                $storage = Storage::disk('public');
                if(!empty($old_file) && $storage->exists($filePath.$old_file)){
                    $storage->delete($filePath.$old_file);
                }
            }
        }
    }

    /**
     * Admin - Update Setting
     * URL: /admin/settings/{setting} (PUT)
     *
     * @param Request $request
     * @param $setting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $setting){

        $data = $request->all();
        $setting->state = isset($data['state']);
        $result = $setting->save();

        //Cache Flush
        Cache::forget('settings');

        if($result) {
            return redirect(route('admin.settings.index'))->with('alert-success', 'The setting has been updated successfully.');
        }else {
            return back()->with('alert-danger', 'The setting cannot be updated, please try again or contact the administrator.');
        }
    }

    public function general(Request $request){

        $data= [];
        $page_heading = 'Manage General Setting';
        $method = $request->method();

        if($method == 'POST' || $method == 'post'){

            $FRONTEND_LOGO_AlT_TEXT = $request->FRONTEND_LOGO_AlT_TEXT ?? '';
            $SYSTEM_TITLE = $request->SYSTEM_TITLE ?? '';
            $SALES_EMAIL = $request->SALES_EMAIL ?? '';
            $SALE_PHONE = $request->SALE_PHONE ?? '';
            $GOOGLE_TRANSLATOR_MANAGEMENT = $request->GOOGLE_TRANSLATOR_MANAGEMENT ?? '';

            $new_array =[];
            array_push($new_array,'FRONTEND_LOGO_AlT_TEXT','COMPANY_NAME','SYSTEM_TITLE','SALES_EMAIL','SALE_PHONE','GOOGLE_TRANSLATOR_MANAGEMENT','FROM_EMAIL','COMPANY_ADDRESS','BOOKING_QUERIES_NO');
            $isSaved = $this->save_settings($request,$new_array);

        //==========

            $FRONTEND_LOGO = $request->FRONTEND_LOGO ?? '';
            if($FRONTEND_LOGO){
                $old_value = '';
                $old_file = '';
                // pr($update_arr);
                $setting = new Setting;
                $is_exist = Setting::where('name','FRONTEND_LOGO')->first();
                if($is_exist){
                 $setting = $is_exist;
             }
             $setting->type = 'website';
             $setting->name = 'FRONTEND_LOGO';
             $setting->label = 'FRONTEND_LOGO';
             $setting->field_type = 'file';
             // $file = $request->file('FRONTEND_LOGO');
             // prd($file);
             $this->saveFile($request, $setting, $old_file , 'FRONTEND_LOGO');
         }
        //======

         $FAVICON_LOGO = $request->FAVICON_LOGO ?? '';
         if($FAVICON_LOGO){
            $old_value = '';
            $old_file = '';
            // pr($update_arr);
            $setting = new Setting;
            $is_exist = Setting::where('name','FAVICON_LOGO')->first();
            if($is_exist){
               $setting = $is_exist;
           }
           $setting->type = 'website';
           $setting->name = 'FAVICON_LOGO';
           $setting->label = 'FAVICON_LOGO';
           $setting->field_type = 'file';
            // $file = $request->file('FAVICON_LOGO');
            // prd($file);
           $this->saveFile($request, $setting, $old_file , 'FAVICON_LOGO');
       }

       if($isSaved){
        $success_msg = 'General Setting has been saved successfully.';
        session()->flash('alert-success', $success_msg);
        return redirect(url('admin/settings/general'));
    }else {
        return back()->with('alert-danger', 'General setting cannot be saved, please try again or contact the administrator.');
    }
        //======
}
$settings =  Setting::select('name','value')->where('type','website')->get();
$data['page_heading'] = $page_heading;
$data['settings'] = $settings;
return view('admin.settings.general', $data);
}

public function comapany_info(Request $request){
    $data= [];
    $page_heading = 'Manage Company Information ';
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){
        $COMPANY_NAME = $request->COMPANY_NAME ?? '';
        $COMPANY_ADDRESS = $request->COMPANY_ADDRESS ?? '';
        $SALES_EMAIL = $request->SALES_EMAIL ?? '';
        $SALE_PHONE = $request->SALE_PHONE ?? '';
        $GOOGLE_TRANSLATOR_MANAGEMENT = $request->GOOGLE_TRANSLATOR_MANAGEMENT ?? '';
        $new_array =[];
        array_push($new_array,'COMPANY_NAME','SALES_EMAIL','SALE_PHONE','COMPANY_ADDRESS','BOOKING_QUERIES_NO');
        $isSaved = $this->save_settings($request,$new_array);

        if($isSaved){
            $success_msg = 'Company Information has been saved successfully.';
            session()->flash('alert-success', $success_msg);
            return redirect(url('admin/settings/comapany-info'));
        }else {
            return back()->with('alert-danger', 'Company Information cannot be saved, please try again or contact the administrator.');
        }
    }
    $settings =  Setting::select('name','value')->where('type','website')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.company_info', $data);
}


public function sms_setting(Request $request){
    $data= [];
    $page_heading = 'Manage Sms Setting ';
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){
        $SMS_USERNAME = $request->SMS_USERNAME ?? '';
        $SMS_PASSWORD = $request->SMS_PASSWORD ?? '';
        $SMS_SENDERID = $request->SMS_SENDERID ?? '';
        $new_array =[];
        array_push($new_array,'SMS_USERNAME','SMS_SENDERID','SMS_PASSWORD');
        $isSaved = $this->save_settings($request,$new_array);

        if($isSaved){
            $success_msg = 'Sms Setting has been saved successfully.';
            session()->flash('alert-success', $success_msg);
            return redirect(url('admin/settings/sms-setting'));
        }else {
            return back()->with('alert-danger', 'Sms Setting cannot be saved, please try again or contact the administrator.');
        }
    }
    $settings =  Setting::select('name','value')->where('type','website')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.sms_setting', $data);
}


public function google_maps_api(Request $request){
    $data= [];
    $page_heading = 'Manage Google Maps API';
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){
        $GOOGLE_API_KEY = $request->GOOGLE_API_KEY ?? '';
        $AUTOCOMPLETE_RESTRICTED_COUNTRIES = $request->AUTOCOMPLETE_RESTRICTED_COUNTRIES ?? '';
        $new_array =[];
        array_push($new_array,'GOOGLE_API_KEY','AUTOCOMPLETE_RESTRICTED_COUNTRIES');
        $this->save_settings($request,$new_array);
    }
    $settings =  Setting::select('name','value')->where('type','website')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.google_maps_api', $data);

}


public function google_recaptcha(Request $request){

    $data= [];
    $page_heading = 'Manage Google Recaptcha';
    $method = $request->method();

    if($method == 'POST' || $method == 'post'){
        $RECAPTCHA_SITE_KEY = $request->RECAPTCHA_SITE_KEY ?? '';
        $RECAPTCHA_SECRET_KEY = $request->RECAPTCHA_SECRET_KEY ?? '';
        $RECAPTCHA_FRONT_SCORE = $request->RECAPTCHA_FRONT_SCORE ?? '';
        $RECAPTCHA_FRONT_DISABLED = $request->RECAPTCHA_FRONT_DISABLED ?? '';
        $RECAPTCHA_ADMIN_SCORE = $request->RECAPTCHA_ADMIN_SCORE ?? '';
        $RECAPTCHA_ADMIN_DISABLED = $request->RECAPTCHA_ADMIN_DISABLED ?? '';
        $new_array =[];
        array_push($new_array,'RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY','RECAPTCHA_FRONT_SCORE','RECAPTCHA_FRONT_DISABLED','RECAPTCHA_ADMIN_SCORE','RECAPTCHA_ADMIN_DISABLED');

        $isSaved = $this->save_settings($request,$new_array);

        if($isSaved){
            $success_msg = 'Google Recaptcha has been saved successfully.';
            session()->flash('alert-success', $success_msg);
            return redirect(url('admin/settings/google-recaptcha'));
        }else {
            return back()->with('alert-danger', 'Google Recaptcha cannot be saved, please try again or contact the administrator.');
        }
    }
    $settings =  Setting::select('name','value')->where('type','website')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.google_recaptcha', $data);

}

public function display_cab_tabs(Request $request) {
    $data = [];
    $page_heading = 'Display Cab Tabs';
    $method = $request->method();
    $settings_arr = ['INTERCITY_TRIP','OUTSTATION','AIRPORT','RAILWAYS','SIGHTSEEING'];
    if($method == 'POST' || $method == 'post') {
        $rules = [];
        $validation_msg = [];
        $INTERCITY_TRIP = $request->INTERCITY_TRIP ?? '';
        $OUTSTATION = $request->OUTSTATION ?? '';
        $AIRPORT = $request->AIRPORT ?? '';
        $RAILWAYS = $request->RAILWAYS ?? '';
        $SIGHTSEEING = $request->SIGHTSEEING ?? '';
        if($INTERCITY_TRIP == 1 || $OUTSTATION == 1 || $AIRPORT == 1 || $RAILWAYS == 1 || $SIGHTSEEING == 1) {
                //Done
        } else {
            $rules['INTERCITY_TRIP'] = 'required';
        }
        $validation_msg['INTERCITY_TRIP.required'] = 'Atleast one should be selected.';

        $validator = $this->validate($request, $rules, $validation_msg);

        $isSaved = $this->save_settings($request, $settings_arr);
        if($isSaved) {
            $success_msg = 'Cab Tabs has been saved successfully.';
            session()->flash('alert-success', $success_msg);
            return redirect(url('admin/settings/cab-tabs'));
        } else {
            return back()->with('alert-danger', 'Cab Tabs cannot be saved, please try again or contact the administrator.');
        }
    }
    $data['page_heading'] = $page_heading;
    $data['settings_arr'] = $settings_arr;
    return view('admin.settings.display_cab_tabs', $data);
}

public function social_configuration(Request $request) {
    $data= [];
    $page_heading = 'Manage Social Login';
    $method = $request->method();

    if($method == 'POST' || $method == 'post'){

        $FACEBOOK_CLIENT_ID = $request->FACEBOOK_CLIENT_ID ?? '';
        $FACEBOOK_CLIENT_SECRET = $request->FACEBOOK_CLIENT_SECRET ?? '';
        $GOOGLE_CLIENT_ID = $request->GOOGLE_CLIENT_ID ?? '';
        $GOOGLE_CLIENT_SECRET = $request->GOOGLE_CLIENT_SECRET ?? '';
        $GOOGLE_REDIRECT_URL = $request->GOOGLE_REDIRECT_URL ?? '';
        $FACEBOOK_REDIRECT_URL = $request->FACEBOOK_REDIRECT_URL ?? '';

        $new_array = [];
        array_push($new_array,'FACEBOOK_CLIENT_ID','FACEBOOK_CLIENT_SECRET','FACEBOOK_REDIRECT_URL','GOOGLE_CLIENT_ID','GOOGLE_CLIENT_SECRET','GOOGLE_REDIRECT_URL');

        $isSaved = $this->save_settings($request,$new_array);
        if($isSaved){
            $success_msg = 'Social Login has been saved successfully';
            session()->flash('alert-success', $success_msg);
            return redirect(url('admin/settings/social-configuration'));
        }else {
            return back()->with('alert-danger', 'Social Login cannot be saved, please try again or contact the administrator.');
        }

    }

    $settings =  Setting::select('name','value')->where('type','website')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.social-configuration', $data);
}

public function email_header_footer(Request $request){

    $data= [];
    $page_heading = 'Manage Email Header Footer';
    $method = $request->method();

    if($method == 'POST' || $method == 'post'){

        $B2B_HEADER = $request->B2B_HEADER ?? '';
        $B2B_FOOTER = $request->B2B_FOOTER ?? '';
        $B2C_HEADER = $request->B2C_HEADER ?? '';
        $B2C_FOOTER = $request->B2C_FOOTER ?? '';

        $new_array =[];
        array_push($new_array,'B2B_HEADER','B2B_FOOTER','B2C_HEADER','B2C_FOOTER');

        $isSaved = $this->save_settings($request,$new_array);
        if($isSaved){
            $success_msg = 'Email Header Footer has been saved successfully';
            session()->flash('alert-success', $success_msg);
            return redirect(url('admin/settings/email-headerfooter'));
        }else {
            return back()->with('alert-danger', 'Social Login cannot be saved, please try again or contact the administrator.');
        }
    }

    $settings =  Setting::select('name','value')->where('type','website')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.email_header_footer', $data);
}

public function analytics_tool(Request $request){

    $data= [];
    $page_heading = 'Manage Header & Footer Tool';
    $method = $request->method();

    if($method == 'POST' || $method == 'post'){

        $new_array =[];
        array_push($new_array,'HTML_HEAD_TOP','HTML_BODY_TOP','HTML_HEAD_BOTTOM','HTML_BODY_BOTTOM');
        
        $isSaved = $this->save_settings($request,$new_array);

        if($isSaved){
            $success_msg = 'Analytics Tool has been saved successfully';
            session()->flash('alert-success', $success_msg);
            return redirect(url('admin/settings/analytics_tool'));
        }else {
            return back()->with('alert-danger', 'Analytics Tool cannot be saved, please try again or contact the administrator.');
        }

    }
    $settings =  Setting::select('name','value')->where('type','website')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.analytics-tool', $data);
}

public function cookies_consent(Request $request){

    $data= [];
    $page_heading = 'Manage Cookie Consent';
    $method = $request->method();

    if($method == 'POST' || $method == 'post'){

        $new_array =[];
        array_push($new_array,'COOKIES_MESSAGE','COOKIES_BUTTON_TEXT','COOKIES_TEXT_COLOR','COOKIES_BACKGROUND_COLOR','COOKIES_BUTTON_COLOR','COOKIES_BACKGROUND_COLOR','COOKIES_CONSENT_TEXT','COOKIES_CONSENT_STATUS');
        $isSaved = $this->save_settings($request,$new_array);

        if($isSaved){
            $success_msg = 'Cookie Consent has been saved successfully';
            session()->flash('alert-success', $success_msg);
            return redirect(url('admin/settings/cookies_consent'));
        }
    }

    $settings =  Setting::select('name','value')->where('type','website')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.cookies_consent', $data);
}


public function currency_setting(Request $request){

    $data= [];
    $page_heading = 'Manage Currency Setting';
    $method = $request->method();

    if($method == 'POST' || $method == 'post'){

        $new_array =[];
        array_push($new_array,'CURRENCY_NAME','CURRENCY_SIGN_PREVIEW','CURRENCY_SIGN_FONTAWESOME','CURRENCY_VALUE');
        $isSaved = $this->save_settings($request,$new_array);

        if($isSaved){
            $success_msg = 'Currency Setting has been saved successfully.';
            session()->flash('alert-success', $success_msg);
            return redirect(url('admin/settings/currency_setting'));
        }
    }

    $settings =  Setting::select('name','value')->where('type','website')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.currency_setting', $data);
}

public function images(Request $request){
    $data= [];
    $page_heading = 'Manage Images';
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){

        $new_array =[];
        array_push($new_array,'CATEGORY_IMG_HEIGHT','CATEGORY_IMG_WIDTH','CATEGORY_THUMB_WIDTH','CATEGORY_THUMB_HEIGHT','BANNER_IMG_HEIGHT','BANNER_IMG_WIDTH','BANNER_THUMB_WIDTH','BANNER_THUMB_HEIGHT');
        $isSaved = $this->save_settings($request,$new_array);

        if($isSaved){
            $success_msg = 'Images has been saved successfully.';
            session()->flash('alert-success', $success_msg);
            return redirect(url('admin/settings/images'));
        }
    }
    $settings =  Setting::select('name','value')->where('type','website')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.images', $data);
}

public function social_plateform(Request $request){
    $data= [];
    $page_heading = 'Manage Social Platform';
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){
        $new_array =[];
        array_push($new_array,'WHATSAPP','FACEBOOK','TWITTER','LINKEDIN','INSTAGRAM','YOUTUBE');
        $isSaved = $this->save_settings($request,$new_array,'social_links');

        if($isSaved){
            $success_msg = 'Social Platform has been saved successfully';
            session()->flash('alert-success', $success_msg);
            return redirect(url('admin/settings/social_plateform'));
        }
    }

    // $settings =  Setting::select('name','value')->where('type','website')->get();
    $settings =  Setting::select('name','value')->where('type','social_links')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.social_plateform', $data);
}
public function social_sharing(Request $request){
    $data= [];
    $page_heading = 'Manage Social Sharing';
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){

        $FACEBOOK_SHARE = $request->FACEBOOK_SHARE ?? '';
        $INSTAGRAM_SHARE = $request->INSTAGRAM_SHARE ?? '';
        $TWITTER_SHARE = $request->TWITTER_SHARE ?? '';
        $WHATSAPP_SHARE = $request->WHATSAPP_SHARE ?? '';
        
        $new_array =[];
        array_push($new_array,'FACEBOOK_SHARE','INSTAGRAM_SHARE','WHATSAPP_SHARE','TWITTER_SHARE');
        $isSaved = $this->save_settings($request,$new_array,'social_links');

        if($isSaved){
            $success_msg = 'Social Sharing has been saved successfully';
            session()->flash('alert-success', $success_msg);
            return redirect(url('admin/settings/social_sharing'));
        }

    }
    $settings =  Setting::select('name','value')->where('type','social_links')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.social_sharing', $data);
}

public function robot_txt(Request $request){
    $data= [];
    $page_heading = 'Manage Robot TXT';
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){

        $new_array =[];
        array_push($new_array,'ROBOT_TXT');

        $robot_txt = $request->ROBOT_TXT;
        if($robot_txt){

        //===Delete local file

            $fileName = 'robots.txt';
            $storage = Storage::disk('public_uploads');
            $is_deleted = $storage->delete($fileName);

            Storage::disk('public_uploads')->put($fileName, $robot_txt);

            $isSaved = $this->save_settings($request,$new_array);

            if($isSaved) {
                return redirect(route('admin.settings.robot_txt'))->with('alert-success', 'The Robot TXT has been saved successfully.');
            }else {
                return back()->with('alert-danger', 'The Robot TXT cannot be updated, please try again or contact the administrator.');
            }
        }
    }

    $settings =  Setting::select('name','value')->where('type','website')->get();
    $data['page_heading'] = $page_heading;
    $data['settings'] = $settings;
    return view('admin.settings.robot_txt', $data);
}

public function save_settings($request,$new_array,$type='') {
    foreach ($new_array as $key => $name) {
        $update_arr = array(
            'type' => isset($type) && !empty($type) ? $type : 'website',
            'label' => $name,
            'name' => $name,
            'field_type' => 'text',
            'value' => $request[$name] ?? null,
            'is_dedicated' => 1,
        );
            // prd($update_arr);
        $is_exist =  Setting::where('name',$name)->first();
        if($is_exist){
            $isUpdate =  Setting::where('name',$name)->update($update_arr);
            $action = 'Update';
        } else {
            $isUpdate =  Setting::where('name',$name)->insert($update_arr);
            $action = 'Add';
        }

        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;

        $new_data = DB::table('website_settings')->where('name',$name)->first();
        $module_desc =  !empty($new_data->name)?$new_data->name:'';
        $new_data_id =  !empty($new_data->id)?$new_data->id:0;
        $new_data =(array) $new_data;
        $new_data = json_encode($new_data);

        $module_id = $new_data_id;

        $params['user_id'] = $user_id;
        $params['user_name'] = $user_name;
        $params['module'] = 'WebsiteSettings';
        $params['module_desc'] = $module_desc;
        $params['module_id'] = $module_id;
        $params['sub_module'] = "";
        $params['sub_module_id'] = 0;
        $params['sub_sub_module'] = "";
        $params['sub_sub_module_id'] = 0;
        $params['data_after_action'] = $new_data;
        $params['action'] = $action;
        CustomHelper::RecordActivityLog($params);
    }
    return true;
}


public function smtpSetting(Request $request){

    $data= [];
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $page_heading = 'Manage Email Settings';
    $method = $request->method();

    if($method == 'POST' || $method == 'post'){

        $rules = [];
        if($request->mail_gateway == 'phpmail'){
            $rules['sender_name'] = 'required';
            $rules['sender_mail'] = 'required|email';
        }else{
            $rules['from_name'] = 'required';
            $rules['from_mail'] = 'required|email';
            $rules['mail_host'] = 'required';
            $rules['mail_port'] = 'required';
            $rules['mail_username'] = 'required';
            // $rules['mail_password'] = 'required';
        }

        $validator = $this->validate($request, $rules);
        $id = isset($request->id) ? $request->id : 0;
        $req_data = $request->except('_token', 'id', 'mail_password', 'mail_password_hidden');
        if(!empty($request->mail_password)){
            $req_data['mail_password'] = encrypt($request->mail_password);
        }
        $req_data['is_queue'] = isset($request->is_queue) ? 1 : 0;
        $isUpdate =  SmtpSetting::where('id',$id)->update($req_data);
        if ($isUpdate) {

            $new_data = DB::table('smtp_settings')->where('id',$id)->first();
            $module_desc =  !empty($new_data->from_name)?$new_data->from_name:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);
            $module_id = $id;
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Email Settings';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = "Update";

            CustomHelper::RecordActivityLog($params);

            return redirect(route('admin.settings.smtpSetting'))->with('alert-success', 'The Email Setting has been updated successfully.');
        }
    }
    $smtp_data =  SmtpSetting::where('id','1')->first();
    $data['page_heading'] = $page_heading;
    $data['smtp_data'] = $smtp_data;
    return view('admin.settings.smtp_setting', $data);
}

public function mailsmtp(Request $request){

    $isSent = '';
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){
        $rules = [];
        $rules['email_to'] = 'required';
        $rules['mail_text'] = 'required';

        $validator = $this->validate($request, $rules);

        // $getSenderName = SmtpSetting::where('id',1)->first();

        // $attachments = [];
        // $mail_authenticate = $getSenderName->mail_gateway ?? '';
        // $sender_email = $getSenderName->from_mail;
        // $sender_email = $getSenderName->mail_gateway=='smtp' ? $getSenderName->from_mail : $getSenderName->sender_mail;
        // $from_name = $getSenderName->from_name;
        // $from_name = $getSenderName->mail_gateway=='smtp' ? $getSenderName->from_name : $getSenderName->sender_name;
        
        $reciver_emails = isset($request->email_to) ? $request->email_to : '';
        $mail_text = isset($request->mail_text) ? $request->mail_text : '';
        // $mail_title = 'Test Email';
        // $email_params = array(
        //     '{text}' => $mail_text,
        // );
        // $res = CustomHelper::CommonMail($from_name, $mail_title, '', $reciver_emails, $sender_email, $email_params, $attachments);

        $websiteSettingsArr = CustomHelper::getSettings(['FRONTEND_LOGO']);
        $storage = Storage::disk('public');
        $path = "settings/";
        $logoSrc =asset(config('custom.assets').'/images/logo.png');
        if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
            $logo = $websiteSettingsArr['FRONTEND_LOGO'];
            if($storage->exists($path.$logo)){
                $logoSrc = asset('/storage/'.$path.$logo);
            }
        }

        $common_logo = '';
        $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
        $b2c_logo_params = array(
         '{company_logo}' => $logoSrc
     );
        $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);

        $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
        $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
        $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
        $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

        $sales_phone = CustomHelper::getPhoneHref($company_phone);
        $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
        $sales_email = CustomHelper::getEmailHref($company_email);

        $contact_details = '';
        $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
        $b2c_email_params = array(
            '{company_name}' => $company_name,
            '{sales_mobile}' => $sales_mobile,
            '{sales_phone}' => $sales_phone,
            '{sales_email}' => $sales_email,
            '{company_title}' => $system_title
        );
        $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);

        $email_params = array(
            '{text}' => $mail_text,
            '{header}' => $common_logo??'',
            '{logo}' => $logoSrc??'',
            '{contact_details}' => $contact_details??'',
            '{date}' => date("l jS \of F Y h:i A"),
        );
        $template_slug = 'test-email';
        $email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();

        $email_content = isset($email_content_data->content) ? $email_content_data->content : '';
        // $email_params = isset($email_params) ? $email_params : [];
        $email_content = strtr($email_content, $email_params);
        $email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';

        if(isset($email_content_data) && !empty($email_content_data)){
            $to_email = $reciver_emails;
            $cc_email = '';
            $bcc_email = '';

            if($email_content_data->email_type == 'customer' && $email_content_data->bcc_admin == 1){
                $bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL'); 
            }
            $params = [];
            $params['to'] = $to_email;
            $params['cc'] = $cc_email;
            $params['bcc'] = $bcc_email;
            $params['subject'] = $email_subject;
            $params['email_content'] = $email_content;
            $params['module_name'] = 'Email Settings';
            $isSent = CustomHelper::sendCommonMail($params);
        }
        
        if(!empty($isSent['mail_data']) == ''){
            return redirect(route('admin.settings.smtpSetting'))->with('alert-success', 'Mail sent successfully.');
        }else{
            return redirect(route('admin.settings.smtpSetting'))->with('alert-danger', 'There was an error when attempting to send a test email from your email server. The error returned is :- <br> <br>'.$isSent['mail_data']);
        }
    }
}

public function fare_rules(Request $request) {
    $data = [];
    $page_heading = 'Airline Offer Fare Rule';
    $method = $request->method();
    $settings_arr = [
        'FARE_RULE_DOMESTIC_CANCELLATION_FEE',
        'FARE_RULE_DOMESTIC_DATE_CHANGE_FEE',
        'FARE_RULE_DOMESTIC_NO_SHOW',
        'FARE_RULE_DOMESTIC_SEAT_CHARGEABLE',
        'FARE_RULE_INTERNATIONAL_CANCELLATION_FEE',
        'FARE_RULE_INTERNATIONAL_DATE_CHANGE_FEE',
        'FARE_RULE_INTERNATIONAL_NO_SHOW',
        'FARE_RULE_INTERNATIONAL_SEAT_CHARGEABLE'
    ];
    if($method == 'POST' || $method == 'post') {
        $rules = [];
        $validation_msg = [];
        $validator = $this->validate($request, $rules, $validation_msg);
        $isSaved = $this->save_settings($request, $settings_arr);
        if($isSaved) {
            $success_msg = 'Agent Offer Fare Rules has been saved successfully.';
            session()->flash('alert-success', $success_msg);
            return redirect(route($this->ADMIN_ROUTE_NAME. '.airline.fare_rules'));
        } else {
            return back()->with('alert-danger', 'Agent Offer Fare Rules cannot be saved, please try again or contact the administrator.');
        }
    }
    $data['page_heading'] = $page_heading;
    $data['settings_arr'] = $settings_arr;
    return view('admin.settings.fare_rules', $data);
}
}