<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Schema;
use App\Models\ImageCategory;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\LoginHistory;
use App\Models\OrderStatusHistory;
use App\Models\Blog;
use App\Models\Country;
use App\Models\State;
use App\Models\Admin;
use App\Models\Widget;
use App\Models\Testimonial;
use App\Models\Form;
use App\Models\FormElement;
use App\Models\Category;
use App\Models\CmsPages;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\ServiceLevel;
use App\Models\EnquiriesMaster;
use App\Models\Destination;
use App\Models\Package;
use App\Models\ThemeCategories;
use App\Models\CommonImage;
use App\Models\LoginToken;
use App\Models\Itenary;
use App\Models\EmailTemplate;
use App\Models\SmtpSetting;
use App\Models\PackagePriceInfo;
use App\Models\DestinationInfo;
use App\Models\PackageInventory;
use App\Models\DiscountModuleToGroup;
use App\Models\CustomModuleRecordDiscount;
use App\Models\TeamManagement;
use App\Models\AgentGroup;
use App\Models\SeoMetaTag;
use App\Models\CabMaster;
use App\Models\PackageSlot;
use App\Models\CabRoutePrice;
use App\Models\MailErrorLog;
use App\Models\Accommodation;
use App\Models\AccommodationRoom;
use App\Models\AccommodationFeature;
use App\Models\AccommodationFacility;
use App\Models\AccommodationInventory;
use App\Models\RoomPlanIncludes;
use App\Models\CabRoute;
use App\Models\Order;
use App\Models\AirlineMarkup;
use App\Models\UserWallet;
use App\Models\PaymentGateway;
use App\Models\OrderPayments;
use App\Models\AirlineDc;
use App\Models\AgentAirlineMarkup;
use App\Models\AirportCodesMaster;
use App\Models\AirlineMaster;
use App\Models\AirlineRoute;
use App\Models\AirlineRouteInventory;
use App\Models\AirlineRouteSearch;
use App\Models\CabRouteInventory;
use App\Models\OrderServiceVoucher;
use App\Models\Coupon;
use App\Models\BikeMaster;
use App\Models\BikecityPrice;
use App\Models\BikeCityInventory;
use App\Models\RoomFeature;
use App\Models\OrderAmendments;
use App\Models\UserGstInfo;
use App\Models\CabsCityPrice;
use App\Models\CabsInventory;
use App\Models\Banner;
use App\Models\FlightReviewDetails;
use App\Helpers\FlightHelper;
use Carbon\CarbonPeriod;
use App\Jobs\SendEmailJob;
use App\Libraries\MobileDetect;
use App\Libraries\CurrencyConverter;
use App\Mail\ParseEmail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use App\Jobs\BulkUpdates;
use Validator;
use Image;
use File;
use Request;
use Cache;
use DB;
use Mail;
use Storage;
use Session;
use Config;
use Carbon\Carbon;
use PDF;
use Agent;

class CustomHelper {
    /**
     * Render S3 image URL
     *
     * @param $name
     * @param bool $thumbnail
     * @return string
     */
    public static function getAdminRouteName() {
        $ADMIN_ROUTE_NAME = config("custom.ADMIN_ROUTE_NAME");
        if (empty($ADMIN_ROUTE_NAME)) {
            $ADMIN_ROUTE_NAME = "admin";
        }
        return $ADMIN_ROUTE_NAME;
    }

    public static function isAllowedModule_24_03_2023($moduleName) {
        $isAllowed = false;
        $allowedModulesArr = [];
        $moduleData = SeoMetaTag::select('identifier')->where('is_disable',0)->get();
        foreach ($moduleData as $key => $row) {
            if($row->identifier) {
                $allowedModulesArr[] = $row->identifier ?? '';
            }
        }
        // $allowedModulesArr = $moduleData->toArray();
        // prd($allowedModulesArr);

        $moduleNameArrAnd = [];
        $moduleNameArrOr = [];
        $isAnd = strpos($moduleName, "&");
        $isOr = strpos($moduleName, "|");
        if ($isAnd >= 0 && $isAnd !== false) {
            $moduleNameArrAnd = explode("&", $moduleName);
        } else {
            $moduleNameArrOr = explode("|", $moduleName);
        }
        if (!empty($moduleNameArrAnd) && count($moduleNameArrAnd) > 0) {
            $isAndAllowed = true;
            foreach ($moduleNameArrAnd as $module) {
                if (!in_array($module, $allowedModulesArr)) {
                    $isAndAllowed = false;
                }
            }
            $isAllowed = $isAndAllowed;
        } else if (!empty($moduleNameArrOr) && count($moduleNameArrOr) > 0) {
            $isOrAllowed = false;
            foreach ($moduleNameArrOr as $module) {
                if (in_array($module, $allowedModulesArr)) {
                    $isOrAllowed = true;
                }
            }
            $isAllowed = $isOrAllowed;
        }
        return $isAllowed;
    }


    public static function isAllowedModule($moduleName) {
        $isAllowed = false;
        $allowedModulesArr = config('custom.allowedModulesArr');
        if(empty($allowedModulesArr)) {
            $allowedModulesArr = \Cache::rememberForever("identifier", function () {
                $moduleData = SeoMetaTag::select('identifier')->where('is_disable',0)->get();
                $identifier_arr = [];
                if(!empty($moduleData)) {
                    foreach ($moduleData as $key => $row) {
                        if($row->identifier) {
                            $identifier_arr[] = $row->identifier ?? '';
                        }
                    }
                }
                return $identifier_arr;
            });
            config(['custom.allowedModulesArr'=>$allowedModulesArr]);
        }
        // prd($allowedModulesArr);        
        
        $moduleNameArrAnd = [];
        $moduleNameArrOr = [];
        $isAnd = strpos($moduleName, "&");
        $isOr = strpos($moduleName, "|");
        if ($isAnd >= 0 && $isAnd !== false) {
            $moduleNameArrAnd = explode("&", $moduleName);
        } else {
            $moduleNameArrOr = explode("|", $moduleName);
        }
        // prd($moduleNameArrOr);
        if (!empty($moduleNameArrAnd) && count($moduleNameArrAnd) > 0) {
            $isAndAllowed = true;
            foreach ($moduleNameArrAnd as $module) {
                if (!in_array($module, $allowedModulesArr)) {
                    $isAndAllowed = false;
                }
            }
            $isAllowed = $isAndAllowed;
        } else if (!empty($moduleNameArrOr) && count($moduleNameArrOr) > 0) {
            $isOrAllowed = false;
            foreach ($moduleNameArrOr as $module) {
                if (in_array($module, $allowedModulesArr)) {
                    $isOrAllowed = true;
                }
            }
            $isAllowed = $isOrAllowed;
        }
        // prd($isAllowed);
        return $isAllowed;
    }
    

    // Add GoogleRecaptcha Code CustomHelper
    public static function checkGoogleReCaptcha($token){
        $websiteSettingsArr = self::getSettings('RECAPTCHA_SECRET_KEY');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $websiteSettingsArr['RECAPTCHA_SECRET_KEY'],'response' => $token)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
       
        $arrResponse = json_decode($response, true);
        //prd($arrResponse);

        return $arrResponse;
    }
    // Closed GoogleRecaptcha Code CustomHelper

    public static function isAdmin() {
        $admin_role = auth()->user();
        //pr($admin_role->toArray());
        $role = "";
        $is_admin = "";
        if (!empty($admin_role)) {
            $role = isset($admin_role->role_id) ? $admin_role->role_id : "";
        }
        if ($role == 1) {
            $is_admin = true;
        } else {
            $is_admin = false;
        }
        return $is_admin;
    }

    public static function IND_money_format($number,$showDecimal=false) {
        $result = 0;
        if(is_numeric($number)) {
            $decimal = (string)($number - floor($number));
            if($decimal > 0 || $showDecimal) {
                return number_format($number,2);
            }
            $money = floor($number);
            $length = strlen($money);
            $delimiter = '';
            $money = strrev($money);

            for($i=0;$i<$length;$i++){
                if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
                    $delimiter .=',';
                }
                $delimiter .=$money[$i];
            }

            $result = strrev($delimiter);
            $decimal = preg_replace("/0\./i", ".", $decimal);
            $decimal = substr($decimal, 0, 3);

            if( $decimal != '0'){
                $result = $result.$decimal;
            }
        }
        return $result;
    }
    // Add getPrice For CurrencyConverter
    public static function getPrice($price=0, $currencySymbol='', $showDecimal=false) {
        if(empty($currencySymbol)) {
            $currencySymbol = '₹';
        }
        return $currencySymbol.(self::IND_money_format($price,$showDecimal));
    }

     // Add getOrder 
    public static function getTotalPaxUnit($orderData) {

        $total_pax = 0;
        $price_category_choice_record_arr = json_decode($orderData->price_category_choice_record,true)??[];
        if(!empty($price_category_choice_record_arr)) {
          foreach($price_category_choice_record_arr as $pccr) {
            $input_value = $pccr['input_value']??0;
            if($input_value > 0) {
              $total_pax += $input_value;
          }
      }
    }
    return $total_pax;

    }

    


    // getPrice Closed Code 

   /* public static function checkPermission($module_name, $permission_method) {
        $permission_method_arr = explode('|', $permission_method);
        $user = auth()->user();
        //$moduleData = Module::orderBy("sort_order", "asc")->get();
        $seconds = 60 * 60 * 24 * 365;
        $moduleData = cache()->remember('moduleData', $seconds, function () use ($module_name) {
               return Module::orderBy('sort_order', 'asc')->pluck('name');
           });
        if (self::isAdmin()) {
            return true;
        } else {
             $permissionData = Permission::select("permissions")->where("user_id", $user->id)->first();

             // prd($permissionData);
            if(!empty($permissionData)){

            if (!empty($permissionData->permissions) && $permissionData->permissions != null) {
                $permission = json_decode($permissionData->permissions, true);*/
                /*foreach ($moduleData as $module) {
                    $name = !empty($module->name) ? $module->name : '';
                    if ($module_name == $name) {
                        if (!empty($permission[$name])) {
                            if (in_array($permission_method, $permission[$name])) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            return false;
                        }
                    }
                }*/
                /*foreach ($moduleData as $name) {
                    if ($module_name == $name) {
                        if (!empty($permission[$name])) {
                            if (count($permission_method_arr) > 1) {
                                foreach ($permission_method_arr as $key => $new_permission) {
                                    if (in_array($new_permission, $permission[$name])) {
                                        return true;
                                    } else {
                                        return false;
                                    }
                                }
                            } else {
                                if (in_array($permission_method, $permission[$name])) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        } else {
                            return false;
                        }
                    }
                }
            } else {
                return false;
            }
        }else{
          return false;
      }
  }
}*/
public static function checkPermission_06feb2022($module_name,$permission_method) {
    $permission_method_arr = explode('|', $permission_method);
    $roles_pemission = config('custom.roles_pemission');
    $roles_data = [];
    $moduleData = [];
    if($roles_pemission) {
        $roles_data = $roles_pemission['roles_data'];
        $moduleData = $roles_pemission['moduleData'];
    } else {
//prd(auth()->user());
        $role_id = (auth()->user()) ? auth()->user()->role_id : '';
        if(!empty($role_id)){
// $roles_pemission = \Cache::rememberForever("roles_pemission-$role_id", function () use ($role_id) {
            $roles_data =  DB::table('roles')->where('id',$role_id)->first();
            $moduleData = Module::orderBy('sort_order','asc')->pluck('name');

            $roles_pemission = array(
                'roles_data' =>$roles_data,
                'moduleData' =>$moduleData->toArray(),
            );
// return $roles_pemission;
// });

            config(['custom.roles_pemission' =>$roles_pemission ]);

            $roles_data = $roles_pemission['roles_data'];
            $moduleData = $roles_pemission['moduleData'];
        }

    }

    if(Self::isAdmin()) {
        return true;
    } else {
        if(!empty($roles_data->permissions) && $roles_data->permissions!=NULL && !empty($moduleData)) {

            $permission = json_decode($roles_data->permissions,true);

            foreach ($moduleData as $name) {
                //$module = (object)$module;
               // $name = !empty($module->name)?$module->name:'';
                if($module_name == $name) {
                    if(!empty($permission[$name])) {
                        if(count($permission_method_arr) > 1) {
                            foreach ($permission_method_arr as $key => $new_permission) {
                                if(in_array($new_permission, $permission[$name])) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        } else {
                            if(in_array($permission_method, $permission[$name])) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    } else {
                        return false;
                    }
                }
            }
        } else {
            return false;
        }
    }
}


    public static function checkPermission($module_name,$permission_method) {        
        $permission_method_arr = explode('|', $permission_method);      

        $roles_permission = config('custom.roles_permission');
        $permissions = [];
        if($roles_permission) {
            $permissions = $roles_permission['permissions'];
        } else {
            $role_id = Self::getAdminRoleId();
            if(!empty($role_id)){
                $roles_permission = \Cache::rememberForever("roles_permission-$role_id", function () use ($role_id) {
                    $roles_data =  DB::table('roles')->where('id',$role_id)->first();
                    $permissions = json_decode($roles_data->permissions,true);
                    $roles_permission = array(
                        'permissions' => $permissions,
                    );
                    return $roles_permission;
                });
                config(['custom.roles_permission' =>$roles_permission]);
                $permissions = $roles_permission['permissions'];
            }
        }

        if(Self::isAdmin()) {
            return true;
        } else {
            if(!empty($permissions)) {
                $per = false;

                $module_name_arr = explode('|', $module_name);
                foreach ($permissions as $name => $permission) {
                    $per = false;

                    foreach($module_name_arr as $module_name) {
                        if($module_name == $name) {
                            if(!empty($permission)) {                           
                                if(count($permission_method_arr) > 1) {
                                    foreach ($permission_method_arr as $key => $new_permission) {
                                        if(in_array($new_permission, $permission)) {
                                            $per = true;
                                            break;
                                        }
                                    }
                                } else {
                                    if(in_array($permission_method, $permission)) {
                                        $per = true;
                                    }
                                }
                            }
                            if($per) {
                                return $per;
                                break;
                            }
                        }
                        if($per) {
                            return $per;
                            break;
                        }
                        //Loop End
                    }
                    if($per) {
                        return $per;
                        break;
                    }
                }
                if($per) {
                    return $per;
                }
            } else {
                return false;
            }
        }
    }

    public static function getAdminRoleId($default='') {
        $role_id = Session::get('role_id');
        if(empty($role_id) || !empty($default)) {
            $user = auth()->guard('admin')->user();
            if($user) {
                $role_id = $user->role_id??'';
                /*$adminRoles = AdminRoles::select('role_id')->where(['user_id'=>$user->id])->orderBy('is_default','desc')->first();
                if($adminRoles && $adminRoles->role_id) {
                    $role_id = $adminRoles->role_id;
                }*/
            }
        }
        return $role_id;
    }

    public static function getTemplates($folder_path = "") {
        $result = [];
        $path = "";
        // prd("/resources/views/".config('custom.theme')."/templates/" . $folder_path);
        $exists = file_exists(base_path() . "/resources/views/".str_replace('.','/',config('custom.theme'))."/templates/" . $folder_path);
        if ($exists) {
            $path = base_path("/resources/views/".str_replace('.','/',config('custom.theme'))."/templates/") . $folder_path;
        } else {
            //$path='';
            
        }
        if (is_dir($path)) {
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if (strlen($file) > 3) {
                        $expoldes = explode(".", $file);
                        $dir = $folder_path . "." . $expoldes[0];
                        $name = $expoldes[0];
                        $name = preg_replace('/[^A-Za-z0-9\-\']/', " ", $name);
                        $result[$dir] = ucfirst($name);
                        //echo "filename:" . $file . "<br>";
                        
                    }
                }
                closedir($dh);
            }
        }
        return $result;
    }

    /**
     * Format a 10-digit phone number from xxxxxxxxxx to (xxx) xxx-xxxx
     *
     * @param $phone
     * @return string
     */
    public static function formatPhoneNumber($phone) {
        return strlen($phone) == 10 ? "(" . substr($phone, 0, 3) . ") " . substr($phone, 3, 3) . "-" . substr($phone, 6) : $phone;
    }

    public static function GetSlugBySelf($slug_array, $text) {
        $slug = "";
        // echo $text; die;
        // replace non letter or digits by -
        $text = preg_replace("~[^\pL\d]+~u", "-", $text);
        // transliterate
        $text = iconv("utf-8", "us-ascii//TRANSLIT", $text);
        // remove unwanted characters
        $text = preg_replace("~[^-\w]+~", "", $text);
        // trim
        $text = trim($text, "-");
        // remove duplicate -
        $text = preg_replace("~-+~", "-", $text);
        // lowercase
        $text = strtolower($text);
        // echo $text; die;
        if (empty($text)) {
            // return 'n-a';
            
        }
        $slug = self::GetUniqueSlugBySelf($slug_array, $text);
        // echo $slug; die;
        return $slug;
    }

    public static function GetUniqueSlugBySelf($slug_array, $slug = "", &$num = "") {
        $new_slug = $slug . $num;
        //pr($new_slug);
        $slug = $new_slug;
        if (is_array($slug_array) && in_array($slug, $slug_array)) {
            $num = (int)$num + 1;
            $slug = self::GetUniqueSlugBySelf($slug_array, $new_slug, $num);
        }
        return $slug;
    }

    public static function GetSlug($tbl_name, $id_field, $row_id = "", $text = "",$separater='-') {
        // echo $text; die;
        // replace non letter or digits by -
        // $text = preg_replace("~[^\pL\d]+~u", $separater, $text);
        // // transliterate
        // $text = iconv("utf-8", "us-ascii//TRANSLIT", $text);
        // // remove unwanted characters
        // $text = preg_replace("~[^-\w]+~", "", $text);
        // // trim
        // $text = trim($text, $separater);
        // // remove duplicate -
        // $text = preg_replace("~-+~", $separater, $text);
        // // lowercase
        // $text = strtolower($text);
        // // echo $text; die;
        // if (empty($text)) {
        //     // return 'n-a';
            
        // }
        $text = self::slugify($text,$separater);

        // echo $text; die;
        $slug = self::GetUniqueSlug($tbl_name, $id_field, $row_id, $text);
        // echo $slug; die;
        return $slug;
    }

    public static function GetUniqueSlug($tbl_name, $id_field, $row_id = "", $slug = "", &$num = "") {
        $new_slug = $slug . $num;
        $query = DB::table($tbl_name);
        $query->where("slug", $new_slug);
        $row = $query->first();
        if (empty($row)) {
            $slug = $new_slug;
        } else {
            if (!empty($row_id) && $row->$id_field == $row_id) {
                $slug = $new_slug;
            } else {
                $num = (int)$num + 1;
                $slug = self::GetUniqueSlug($tbl_name, $id_field, $row_id, $new_slug, $num);
            }
        }
        return $slug;
    }

    public static function getStatusStr($status) {
        if (is_numeric($status) && strlen($status) > 0) {
            if ($status == 1) {
                $status = "Active";
            } else {
                $status = "Inactive";
            }
        }
        return $status;
    }

    public static function getStatusHTML($status, $tbl_id = 0, $class = "", $id = "", $type = "status", $activeTxt = "Active", $inActiveTxt = "In-active") {
        $status_str = "";
        if (is_numeric($status) && strlen($status) > 0) {
            $status_name = "";
            $a_label = "";
            if ($status == 1) {
                $status_name = $activeTxt;
                $a_label = "label-success";
            } else {
                $status_name = $inActiveTxt;
                $a_label = "label-warning";
            }
            $status_str = '<a href="javascript:void(0)" class="label ' . $a_label . " " . $class . '" id="' . $id . '" data-id="' . $tbl_id . '" data-status="' . $status . '" data-type="' . $type . '" >' . $status_name . "</a>";
        }
        if (empty($status_str)) {
            $status_str = $status;
        }
        return $status_str;
    }

    public static function CheckAndFormatDate($date, $toFormat = "Y-m-d H:i:s", $fromFormat = "") {
        $new_date = $date;
        $date = preg_replace(["/\//", "/\./"], "-", $date);
        //echo $date; die;
        $new_date = self::DateFormat($date, $toFormat, $fromFormat = "y-m-d");
        return $new_date;
    }

    public static function get_to_from_date($date_between= '', $from = '',$to = '') {
        $data =  array();
        if(!empty($date_between)) {
            switch($date_between) {
                case 'yesterday':
                        $from_date=date("Y-m-d", strtotime("yesterday"))." 00:00:00";
                        $to_date= date("Y-m-d", strtotime("yesterday"))." 23:59:59";
                        $data['from'] = $from_date;
                        $data['to'] = $to_date;
                    break;
                case 'today':
                        $from_date= date('Y-m-d')." 00:00:00";
                        $to_date= date('Y-m-d')." 23:59:59";
                        $data['from'] = $from_date;
                        $data['to'] = $to_date;
                    break;
                case 'tomorrow':
                        $from_date=date("Y-m-d", strtotime("tomorrow"))." 00:00:00";
                        $to_date= date("Y-m-d", strtotime("tomorrow"))." 23:59:59";
                        $data['from'] = $from_date;
                        $data['to'] = $to_date;
                    break;
                case 'this_week':
                        $today = date("l");
                        if($today == "Monday") {
                            $this_monday = strtotime("this Monday");
                            $week_end_date = strtotime("this ".$today);
                        } else {
                            $this_monday = strtotime("last Monday");
                            $week_end_date = strtotime("this ".$today);
                        }
                        $from_date = date('Y-m-d', $this_monday)." 00:00:00";
                        $to_date = date('Y-m-d',$week_end_date)." 23:59:59";
                        $data['from'] = $from_date;
                        $data['to'] = $to_date;
                    break;
                case 'last_week':
                        $today = date("l");
                        if($today == "Monday") {
                            $last_monday = date("Y-m-d",strtotime("last Monday"));
                            $last_sunday  = date("Y-m-d",strtotime("last Sunday"));
                        } else {
                            $last_monday = date("Y-m-d",strtotime("last Monday",strtotime("last Monday")));
                            $last_sunday  = date("Y-m-d",strtotime("last Sunday"));
                        }

                        $sStartTS = Self::WeekToDate(date('W')-2, date('Y'));
                        $sStartDate = $last_monday." 00:00:00";
                        $sEndDate =  $last_sunday." 23:59:59";
                        $data['from'] = $sStartDate;
                        $data['to'] = $sEndDate;
                    break;

                case 'this_month':
                        $from_date = date('Y-m-01')." 00:00:00";
                        $to_date = date('Y-m-t',strtotime($from_date))." 23:59:59";
                        $data['from'] = $from_date;
                        $data['to'] = $to_date;
                    break;
                case 'last_month':
                        $from_date= date("Y-m-d", strtotime((date('m')-1).'/01/'.date('Y').' 00:00:00'))." 00:00:00";
                        $to_date= date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime((date('m')-1).'/01/'.date('Y').' 00:00:00'))))." 23:59:59";
                        $data['from'] = $from_date;
                        $data['to'] = $to_date;
                    break;
                case 'last_3_month':
                        $past_y_m = explode("-",date('Y-m', strtotime('-3 month')));
                        $past_month = $past_y_m[1];
                        $past_year = $past_y_m[0];
                        $from_date = $past_year."-".$past_month."-"."01"." 00:00:00";
                        $to_date= date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime((date('m')-1).'/01/'.date('Y').' 00:00:00'))))." 23:59:59";
                        $data['from'] = $from_date;
                        $data['to'] = $to_date;
                    break;
                case 'last_7_days':
                        $from_date = date('Y-m-d', strtotime('-7 days'))." 00:00:00";
                        $to_date = date('Y-m-d H:i:s');
                        $data['from'] = $from_date;
                        $data['to'] = $to_date;
                    break;
                case 'last_30_days':
                        $from_date = date('Y-m-d', strtotime('-30 days'))." 00:00:00";
                        $to_date = date('Y-m-d H:i:s');
                        $data['from'] = $from_date;
                        $data['to'] = $to_date;
                    break;
                case 'this_year':
                        $curYear = date('Y');
                        $from_date = $curYear."-01-01 00:00:00" ;
                        $to_date = date('Y-m-d')." 23:59:59";
                        $data['from'] = $from_date;
                        $data['to'] = $to_date;
                    break;
                case 'last_year':
                        $lastYear = date('Y')-1;
                        $from_date = $lastYear."-01-01 00:00:00";
                        $to_date = $lastYear."-12-31 23:59:59";
                        $data['from'] = $from_date;
                        $data['to'] = $to_date;
                    break;
            }
        } else {

            if(empty($from) && empty($to)) {
                $from_date = "";
                $to_date = "";
            } elseif(empty($from) && !empty($to)) {
                $to = explode("-",$to);
                $to_date = $to[2]."-".$to[1]."-".$to[0]." 23:59:59";
                $from_date = $to[2]."-".$to[1]."-".$to[0]." 00:00:00";
            } elseif(!empty($from) && empty($to)) {
                $from  = explode("-",$from);
                $from_date  = $from[2]."-".$from[1]."-".$from[0]." 00:00:00";
                $to_date = date("Y-m-d")." 23:59:59";
            } elseif(!empty($from) && !empty($to)) {
                $from  = explode("-",$from);
                $from_date  = $from[2]."-".$from[1]."-".$from[0]." 00:00:00";
                $to = explode("-",$to);
                $to_date = $to[2]."-".$to[1]."-".$to[0]." 23:59:59";
            }
            $data['from'] = $from_date;
            $data['to'] = $to_date;
        }
        return $data;
    }

    public static function WeekToDate ($week, $year) {
    $Jan1 = mktime (1, 1, 1, 1, 1, $year);
    $iYearFirstWeekNum = (int) strftime("%W",mktime (1, 1, 1, 1, 1, $year));

    if ($iYearFirstWeekNum == 1) {
    $week = $week - 1;
    }

    $weekdayJan1 = date ('w', $Jan1);
    $FirstMonday = strtotime(((4-$weekdayJan1)%7-3) . ' days', $Jan1);
    $CurrentMondayTS = strtotime(($week) . ' weeks', $FirstMonday);
    return ($CurrentMondayTS);
    }

    public static function DateFormat($date, $toFormat = "Y-m-d H:i:s", $fromFormat = "") {
        $new_date = $date;
        $formatArr = ["d-m-y", "d-m-Y", "d/m/Y", "d/m/y", "d/m/Y H:i:s", "d/m/y H:i:s", "d/m/Y H:i A", "d/m/y H:i A", "dmY"];
        if (empty($toFormat)) {
            $toFormat = "Y-m-d H:i:s";
        }
        if ($date != "0000-00-00 00:00:00" && $date != "0000-00-00" && $date != "") {
            if (empty($fromFormat) || $fromFormat == "" || !in_array($fromFormat, $formatArr)) {
                $new_date = date($toFormat, strtotime($date));

                if(config('custom.theme_name') == 'viaggindia') {
                    if($toFormat == 'M Y') {
                        $monthsItArr = config('custom.months_it');
                        $month = date('M', strtotime($date));
                        $monthIt = $monthsItArr[$month]??$month;
                        $new_date = str_replace($month,$monthIt,$new_date);
                    }
                }

            } elseif ($fromFormat == "dmY") {
                $new_date = substr($date, -4).'-'.substr($date, 2,2).'-'.substr($date, 0, 2);
            } elseif ($fromFormat == "d-m-y" || $fromFormat == "d-m-Y") {
                $date_arr = explode("-", $date);
                if(isset($date_arr[2])) {
                    $date_str = $date_arr[2] . "-" . $date_arr[1] . "-" . $date_arr[0];
                    $new_date = date($toFormat, strtotime($date_str));
                }
            } elseif ($fromFormat == "d/m/Y" || $fromFormat == "d/m/y") {
                $datetime_arr = explode(" ", $date);
                $date_arr = explode("/", $datetime_arr[0]);
                if(isset($date_arr[2])) {
                    $date_str = $date_arr[2] . "-" . $date_arr[1] . "-" . $date_arr[0];
                    $new_date = date($toFormat, strtotime($date_str));
                }
            } elseif ($fromFormat == "d/m/Y H:i:s" || $fromFormat == "d/m/y H:i:s") {
                $datetime_arr = explode(" ", $date);
                $time = $datetime_arr[1];
                $date_arr = explode("/", $datetime_arr[0]);
                if($date_arr[2]) {
                    $date_str = $date_arr[2] . "-" . $date_arr[1] . "-" . $date_arr[0];
                    $new_date = date($toFormat, strtotime($date_str . " " . $time));
                }
            } elseif ($fromFormat == "d/m/Y H:i A" || $fromFormat == "d/m/y H:i A") {
                $datetime_arr = explode(" ", $date);
                $time = $datetime_arr[1] . " " . $datetime_arr[2];
                $date_arr = explode("/", $datetime_arr[0]);
                if($date_arr[2]) {
                    $date_str = $date_arr[2] . "-" . $date_arr[1] . "-" . $date_arr[0];
                    $new_date = date($toFormat, strtotime($date_str . " " . $time));
                }
            }
        } else {
            $new_date = "";
        }
        return $new_date;
    }

    public static function DateDiff($date1, $date2) {
        $date_diff = "";
        $date1 = self::DateFormat($date1, "Y-m-d");
        $date2 = self::DateFormat($date2, "Y-m-d");
        if (!empty($date1) && !empty($date2)) {
            $date1 = date_create($date1);
            $date2 = date_create($date2);
            $diff = date_diff($date1, $date2);
            $date_diff = $diff->format("%a");
        }
        return $date_diff;
    }

    public static function getStartAndEndDateOfWeek($week, $year, $format = "Y-m-d H:i:s") {
        $dateTime = new \DateTime();
        $dateTime->setISODate($year, $week);
        $result["start_date"] = $dateTime->format($format);
        $dateTime->modify("+6 days");
        $result["end_date"] = $dateTime->format($format);
        return $result;
    }

    /* Note: this function requires laravel intervention/image package */
    public static function UploadImage($file, $path, $ext="", $width=768, $height=768, $is_thumb=false, $thumb_path="", $thumb_width=300, $thumb_height=300) {

        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        ini_set('memory_limit', -1);

        if (empty($ext)) {
            $ext = "jpg,jpeg,png,gif";
        }
        if (!File::exists(public_path("storage/" . $path))) {
            File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
        }
        if (!File::exists(public_path("storage/" . $thumb_path))) {
            File::makeDirectory(public_path("storage/" . $thumb_path), $mode = 0777, true, true);
        }
        list($img_width, $img_height, $type, $attr) = getimagesize($file);
        if ($img_width < $width) {
            $width = $img_width;
        }
        if ($img_height < $height) {
            $height = $img_height;
        }
        if ($img_width < $thumb_width) {
            $thumb_width = $img_width;
        }
        if ($img_height < $thumb_height) {
            $thumb_height = $img_height;
        }
        $result["success"] = false;
        $result["org_name"] = "";
        $result["file_name"] = "";
        if ($file) {
            $validator = Validator::make(["file" => $file], ["file" => "mimes:" . $ext]);
            if ($validator->passes()) {
                $handle = fopen($file, "r");
                $opening_bytes = fread($handle, filesize($file));
                fclose($handle);
                if (strlen(strpos($opening_bytes, "<?php")) > 0 && (strpos($opening_bytes, "<?php") >= 0 || strpos($opening_bytes, "<?PHP") >= 0)) {
                    $result["errors"]["file"] = "Invalid image!";
                } else {
                    $extension = $file->getClientOriginalExtension();
                    $fileOriginalName = $file->getClientOriginalName();
                    // $fileOriginalName = pathinfo($fileOriginalName, PATHINFO_FILENAME);
                    $fileOriginalName = self::sanitizeUploadFile($fileOriginalName,'.webp');
                    $fileName = date("dmyhis")."-".$fileOriginalName;
                    // prd($fileName);
                    $is_uploaded = Image::make($file)->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->encode('webp', 80)->save(public_path("storage/" . $path . $fileName));
                    //prd($is_uploaded);
                    if ($is_uploaded) {
                        $result["success"] = true;
                        if ($is_thumb) {
                            $thumb = Image::make($file)->resize($thumb_width, $thumb_height, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })->encode('webp', 80)->save(public_path("storage/" . $thumb_path . $fileName));
                        }
                        $result["org_name"] = $fileOriginalName;
                        $result["file_name"] = $fileName;
                    }
                }
            } else {
                $result["errors"] = $validator->errors();
            }
        }
        return $result;
    }

    public static function UploadFile($file, $path, $ext = "") {
        ini_set('memory_limit', -1);
        
        if (empty($ext)) {
            $ext = "jpg,jpeg,png,gif,doc,docx,txt,pdf";
        }
        if (!File::exists(public_path("storage/" . $path))) {
            File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
        }
        $path = "public/" . $path;
        $result["success"] = false;
        $result["org_name"] = "";
        $result["file_name"] = "";
        $result["file_path"] = "";
        if ($file) {
            $validator = Validator::make(["file" => $file], ["file" => "mimes:" . $ext]);
            if ($validator->passes()) {
                $handle = fopen($file, "r");
                $opening_bytes = fread($handle, filesize($file));
                fclose($handle);
                if (strlen(strpos($opening_bytes, "<?php")) > 0 && (strpos($opening_bytes, "<?php") >= 0 || strpos($opening_bytes, "<?PHP") >= 0)) {
                    $result["errors"]["file"] = "Invalid file!";
                } else {
                    $extension = $file->getClientOriginalExtension();
                    $fileOriginalName = $file->getClientOriginalName();
                    $fileOriginalName = self::sanitizeUploadFile($fileOriginalName);
                    $fileName = date("dmyhis")."-".$fileOriginalName;
                    $path = $file->storeAs($path, $fileName);
                    if ($path) {
                        $result["success"] = true;
                        $result["org_name"] = $fileOriginalName;
                        $result["file_name"] = $fileName;
                        $result["file_path"] = $path;
                    }
                }
            } else {
                $result["errors"] = $validator->errors();
            }
        }
        return $result;
    }

    public static function sanitizeUploadFile($filename,$extension='') {
        $imgName   = $filename;
        $indexOFF  = strrpos($imgName, '.');
        $nameFile  = substr($imgName, 0,$indexOFF);
        if(empty($extension)) {
            $extension = substr($imgName, $indexOFF);
        }
        $clean     = preg_replace("([^\w\s\d\-_~,;\[\]\(\)])", "",
            $nameFile);
        $NAMEFILE  = Self::slugify(str_replace(' ', '', $clean));
        if (strlen($NAMEFILE) > 50){
            $NAMEFILE = substr($NAMEFILE, 0, 50);
        }
        $NAMEFILE = $NAMEFILE.strtolower($extension);
        return $NAMEFILE;
    }

    /*public static function sanitizeUploadFile_06apr2023($filename) {
        $imgName = $filename;
        $indexOFF = strrpos($imgName, ".");
        $nameFile = substr($imgName, 0, $indexOFF);
        $extension = substr($imgName, $indexOFF);
        $clean = preg_replace("([^\w\s\d\-_~,;\[\]\(\)])", "", $nameFile);
        $NAMEFILE = str_replace(" ", "", $clean) . $extension;
        if (strlen($NAMEFILE) > 50) {
            $NAMEFILE = substr($NAMEFILE, 0, 50);
        }
        return $NAMEFILE;
    }*/

    public static function WebsiteSettings($key) {
        $value = '';
        $website_settings = config('custom.website_settings');
        if(empty($website_settings)) {
            $website_settings = \Cache::rememberForever("website_settings", function () {
                $result = DB::table('website_settings')->get();
                $website_settings_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $website_settings_arr[$row->name] = $row;
                    }
                }
                return $website_settings_arr;
            });
            config(['custom.website_settings'=>$website_settings]);
        }
        if(isset($website_settings[$key])) {
            $value = isset($website_settings[$key]->value) ? $website_settings[$key]->value : '';
        }
        return $value;
    }

    public static function websiteSettingsArray($nameArr) {
        $settings = [];
        $website_settings = config('custom.website_settings');
        if(empty($website_settings)) {
            $website_settings = \Cache::rememberForever("website_settings", function () {
                $result = DB::table('website_settings')->get();
                $website_settings_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $website_settings_arr[$row->name] = $row;
                    }
                }
                return $website_settings_arr;
            });
            config(['custom.website_settings'=>$website_settings]);
        }

        if(!empty($nameArr)) {
            foreach($nameArr as $name) {
                if(isset($website_settings[$name])) {
                    $settings[$name] = $website_settings[$name];
                }
            }
        }
        return $settings;
    }

    public static function getSettings($nameArr = []) {
        $settings = "";
        if (!empty(Cache::get('settings'))) {
            $settings = Cache::get('settings');
            if (!empty($nameArr)) {
                $settings = $settings->whereIn('name', $nameArr)->pluck('value', 'name');
                $settings = $settings->all();
            }
        } else {
            $settings = DB::table("website_settings")->get();
            Cache::put('settings', $settings);
            if (!empty($nameArr)) {
                $settings = $settings->whereIn('name', $nameArr)->pluck('value', 'name');
                $settings = $settings->all();
            }
        }
        return $settings;
    }

    public static function checkInventory($params=[]) {
        $is_inventory = false;
        $inventory = $params['inventory']??1;
        $action = $params['action']??'';
        if($params && isset($params['action'])) {
            if($action == 'package_booking') {
                $trip_date = $params['trip_date'] ?? '';
                $slot_time = $params['slot_time'] ?? '';
                $inventory = $params['inventory'] ?? 0;
                $package = $params['package'] ?? 0;
                $package_type = $params['package_type'] ?? 0;
                $slot_id = $params['slot_id'] ?? '';
                $slot_time = $params['slot_time'] ?? '';

                $inventory_query = PackageInventory::where(function($q1) use($slot_time){
                    $q1->whereHas('packageSlot',function($q2) use($slot_time){
                        $q2->where('status',1);
                        $q2->where('start_time',$slot_time);
                    });
                    $q1->orWhereNull('slot_id');
                });
                $inventory_query->where("package_id",$package);
                $inventory_query->where("price_id",$package_type);
                $inventory_query->whereDate('trip_date',$trip_date);
                $inventory_data = $inventory_query->first();
                // prd($inventory_data->toArray());

                if($inventory_data && isset($inventory_data->inventory) && $inventory_data->inventory > 0 && $inventory_data->inventory > $inventory_data->booked && ((int)$inventory_data->inventory - (int)$inventory_data->booked) >= $inventory){
                    $is_inventory = true;
                }

            }elseif ($action == 'hotel_booking') {

                $inventory_id = $params['inventory_id'] ?? 0;
                $checkin = $params['checkin'];
                $checkout = $params['checkout'];
                $inventory = $params['inventory'];
                $inventory_row = AccommodationInventory::find($inventory_id);

                $room_id = $inventory_row->room_id ?? '';
                $plan_id = $inventory_row->plan_id ?? '';
                $accommodation_id = $inventory_row->accommodation_id ??'';

                $query = AccommodationInventory::where('status',1);
                $query->where('room_id',$room_id);
                if($plan_id) {
                    $query->where('plan_id',(int)$plan_id);
                }
                $query->where('inventory','>=',$inventory);
                $date_arr = [];
                if($checkin && $checkout) {
                    $checkout_date = date('Y-m-d', strtotime($checkout. ' - 1 days'));
                    $period = CustomHelper::DateRange($checkin,$checkout_date);
                    foreach ($period as $key => $value) {
                        $date_arr[] = $value->format('Y-m-d');
                    }
                    $query->whereIn('date',$date_arr);
                } else {
                    $date_arr[] = $checkin;
                    $query->whereIn('date',$date_arr);
                }
                $inventory_data = $query->orderBy('date','ASC')->get();

                $check_inventory_id = '';
                $price = 0;
                if($inventory_data) {
                    foreach($inventory_data as $inv) {
                        if(isset($date_arr) && !empty($date_arr) && in_array($inv->date, $date_arr)) {
                            if(empty($check_inventory_id)) {
                                $check_inventory_id = $inv->id;
                            }
                            $date = $inv->date;
                            if (($key = array_search($date, $date_arr)) !== false) {
                                if($inv->plan_id) {
                                    $price += ($inv->price*$inventory);
                                } else {
                                    $base_price = $inv->AccommodationRoom->base_price??0;
                                    $price += ($base_price*$inventory);
                                }
                                unset($date_arr[$key]);
                            }
                        }
                    }
                }

                if(empty($date_arr)) {
                    $is_inventory = true;
                }
            } elseif ($action == 'cab_booking') {
                if(config('custom.CAB_VERSION')==2) {
                    $price_id = $params['price_id']??'';
                    if($price_id) {
                        $cabsCityPrice = CabsCityPrice::find($price_id);
                        if($cabsCityPrice) {
                            $city_id = $cabsCityPrice->city_id;
                            $cab_id = $cabsCityPrice->cab_id;

                            $cab_route_group_id = $params['cab_route_group_id'] ?? 0;
                            $trip_date = $params['trip_date'] ?? '';
                            $cabsInventory = CabsInventory::where('city_id',$city_id)->where('cab_id',$cab_id)->where('trip_date',$trip_date)->first();
                            if($cabsInventory && $cabsInventory->inventory && ($cabsInventory->inventory > $cabsInventory->booked)) {
                                $is_inventory = true;
                            }
                        }
                    }
                } else {
                    $cab_id = $params['cab_id'] ;
                    $cab_route_group_id = $params['cab_route_group_id'] ?? 0;
                    $trip_date = $params['trip_date'] ?? '';
                    $CabRouteInventory = CabRouteInventory::where('cab_id',$cab_id)->where('cab_route_group_id',$cab_route_group_id)->where('trip_date',$trip_date)->first();
                    if($CabRouteInventory && isset($CabRouteInventory->inventory) && ($CabRouteInventory->inventory > $CabRouteInventory->booked) && $CabRouteInventory->inventory > 0 ){

                        $is_inventory = true;
                    }
                }
            } elseif ($action == 'rental_booking') {
                $bike_id = $params['bike_id'] ;
                $city_id = $params['city_id'] ;
                $pickupDate = $params['pickupDate'];
                $dropDate = $params['dropDate'];
                $inventory = $params['inventory'];

                $period = CustomHelper::DateRange($pickupDate,$dropDate);
                $date_arr = [];
                foreach ($period as $key => $value) {
                    $date_arr[] = $value->format('Y-m-d');
                }

                $inventory_data =BikeCityInventory::where('bike_id',$bike_id)->where('city_id',$city_id)->whereIn('trip_date',$date_arr)
                ->where(DB::raw('inventory - booked'), '>=', $inventory)->get();

                $check_inventory_id = '';
                $price = 0;
                if($inventory_data) {
                    foreach($inventory_data as $inv) {
                        if(isset($date_arr) && !empty($date_arr) && in_array($inv->trip_date, $date_arr)) {
                            $date = $inv->trip_date;
                            if (($key = array_search($date, $date_arr)) !== false) {
                                unset($date_arr[$key]);
                            }
                        }
                    }
                }
                if(empty($date_arr)) {
                    $is_inventory = true;
                }
            } elseif ($action == 'flight_booking') {
                $supplier_id = $params['supplier_id']??0;
                $inventory_id = $params['inventory_id']??0;
                $trip_date = CustomHelper::DateFormat($params['trip_date'],'Y-m-d')??'';
                $inventory = $params['inventory']??0;
                if($inventory_id && $trip_date && $inventory) {
                    $record_count = AirlineRouteInventory::where('id',$inventory_id)->where('user_id',$supplier_id)->where('inventory','>=',$inventory)->whereDate('start_date',$trip_date)->count();
                    if($record_count > 0) {
                        $is_inventory = true;
                    }
                }
            }

        }

        return $is_inventory;   
    }

    public static function GetCountry($id = 0, $col_name = "") {
        $value = "";
        if (is_numeric($id) && $id > 0) {
            $country = DB::table("countries")->where("id", $id)->first();
            if (!empty($col_name) && isset($country->{$col_name})) {
                $value = $country->{$col_name};
            } else {
                $value = $country;
            }
        } else {
            $value = $id;
        }
        return $value;
    }

    public static function GetParentCategory($category) {
        $parent = "";
        if (isset($category->parent) && count($category->parent) > 0) {
            $parent = $category->parent;
        }
        return $parent;
    }

    public static function GetParentCategories($id = "", $type = "", $params = []) {
        $categories = "";
        $orderBy = isset($params["orderBy"]) && ($params["orderBy"] == "desc" || $params["orderBy"] == "asc") ? $params["orderBy"] : "asc";
        $order_type = isset($params["order_type"]) && ($params["order_type"] == "desc" || $params["order_type"] == "asc") ? $params["order_type"] : "asc";
        $category_query = Category::where("status", 1);
        if ($type == "design") {
            $category_query->where("parent_id", 0);
        }
        if (!empty($type)) {
            $category_query->where("type", $type);
        }
        if (isset($params["orderBy"]) && !empty($params["orderBy"])) {
            $category_query->orderBy($params["orderBy"], $order_type);
        }
        if (isset($params["limit"]) && is_numeric($params["limit"]) && $params["limit"] > 0) {
            $category_query->limit($params["limit"]);
        }
        if (is_numeric($id) && $id > 0) {
            $category_query->where("id", $id);
            $categories = $category_query->first();
        } else {
            $categories = $category_query->get();
        }
        return $categories;
    }

    public static function getCategories($id = "", $parent_id = 0, $params = []) {
        $categories = "";
        $category_query = Category::where("status", 1);
        $category_query->where("parent_id", $parent_id);
        if (!empty($id)) {
            //$category_query->where('id', $id);
            $category_query->where(function ($query) use ($id) {
                $query->where("id", $id)->orWhere("slug", $id);
            });
            $categories = $category_query->first();
        } else {
            $categories = $category_query->orderBy("sort_order", "asc")->get();
        }
        return $categories;
    }

    public static function CategoriesMenu($type = "", $className = "", $idName = "") {
        $CatParams = [];
        $CatParams["orderBy"] = "sort_order";
        $CatParams["order_type"] = "asc";
        $ParentCategories = self::GetParentCategories("", $type, $CatParams);
        //pr($ParentCategories); die;
        $all_menu = url("designs");
        $menu_list = "";
        $menu_list.= '<ul class="' . $className . '" id="' . $idName . '">';
        $menu_list.= '<li><a href="' . $all_menu . '">All Designs</a></li>';
        $menu_list.= '<li><a href="#">All Best Sellers</a></li>';
        if (!empty($ParentCategories) && count($ParentCategories)) {
            foreach ($ParentCategories as $parentCat) {
                $childrenCat = $parentCat->children;
                $cat_url = url("designs?cat=" . $parentCat->slug);
                if (isset($childrenCat) && count($childrenCat) > 0) {
                    $cat_url = "javascript:void(0)";
                }
                $menu_list.= '<li><a href="' . $cat_url . '">' . $parentCat->name . "</a>";
                if (isset($childrenCat) && count($childrenCat) > 0) {
                    $childrenCat = $childrenCat->sortBy("sort_order");
                    $menu_list.= self::CategoriesMenuChild($childrenCat, $className, $idName);
                }
                $menu_list.= "</li>";
            }
        }
        $menu_list.= "</ul>";
        return $menu_list;
    }

    public static function CategoriesMenuChild($childCategories, $className = "", $idName = "") {
        $menu_list_child = "";
        if (!empty($childCategories) && count($childCategories) > 0) {
            $menu_list_child.= '<ul class="' . $className . '" id="' . $idName . '">';
            foreach ($childCategories as $childCat) {
                $childrenCat = $childCat->children;
                $cat_url = url("designs?cat=" . $childCat->slug);
                if (isset($childrenCat) && count($childrenCat) > 0) {
                    $cat_url = "javascript:void(0)";
                }
                $menu_list_child.= '<li><a href="' . $cat_url . '">' . $childCat->name . "</a>";
                if (isset($childrenCat) && count($childrenCat) > 0) {
                    $childrenCat = $childrenCat->sortBy("sort_order");
                    $menu_list_child.= self::CategoriesMenuChild($childrenCat, $className, $idName);
                }
                $menu_list_child.= "</li>";
            }
            $menu_list_child.= "</ul>";
        }
        return $menu_list_child;
    }

    private static $parentCatArr = [];

    public static function categoryParentForBreadcrumb($category) {
        if (isset($category->parent) && count($category->parent) > 0) {
            $parent_category = $category->parent;
            self::$parentCatArr[] = $parent_category->toArray();
            if (isset($parent_category->parent) && count($parent_category->parent) > 0) {
                self::categoryParentForBreadcrumb($parent_category);
            }
        }
    }

    public static function CategoryBreadcrumb($category, $first_uri, $first_uri_name, $is_last_link = false) {
        self::$parentCatArr = [];
        $BackUrl = self::BackUrl();
        //prd($category->toArray());
        $breadcrumb = "";
        if (!empty($first_uri_name)) {
            $breadcrumb.= '<a href="' . url($first_uri) . '" class="btn-link" >' . $first_uri_name . "</a>";
        }
        $hierarchy_arr = [];
        if (!empty($category) && count(array($category)) > 0) {
            self::categoryParentForBreadcrumb($category);
            $hierarchy_arr = self::$parentCatArr;
            $hierarchy_arr_rev = array_reverse($hierarchy_arr);
            //prd($hierarchy_arr_rev);
            if (!empty($hierarchy_arr_rev) && count($hierarchy_arr_rev) > 0) {
                foreach ($hierarchy_arr_rev as $cat) {
                    $cat = (object)$cat;
                    if (isset($cat->name)) {
                        if (!empty($first_uri_name)) {
                            $breadcrumb.= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
                        }
                        $breadcrumb.= '<a href="' . url($first_uri . "&parent_id=" . $cat->id) . '" class="btn-link" >' . $cat->name . "</a>";
                        $breadcrumb.= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
                    }
                }
                //$breadcrumb .= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
                
            } elseif (!empty($first_uri_name)) {
                $breadcrumb.= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
            }
            if ($is_last_link) {
                $breadcrumb.= '<a href="' . url("admin/categories?parent_id=" . $category->id . "&back_url=" . $BackUrl) . '">' . $category->name . "</a>";
            } else {
                $breadcrumb.= '<a href="javascript:void(0)">' . $category->name . "</a>";
            }
        }
        return $breadcrumb;
    }

    public static function CategoryBreadcrumbFrontend($category, $first_uri, $first_uri_name, $is_last_link = false) {
        self::$parentCatArr = [];
        //prd($category->toArray());
        $breadcrumb = "";
        if (!empty($first_uri_name)) {
            $breadcrumb.= '<a href="' . url($first_uri) . '" >' . $first_uri_name . "</a>";
        }
        $hierarchy_arr = [];
        if (!empty($category) && count($category) > 0) {
            $category_id = isset($category->pivot->id) ? $category->pivot->id : 0;
            $p1_cat = isset($category->pivot->p1_cat) ? $category->pivot->p1_cat : 0;
            $p2_cat = isset($category->pivot->p2_cat) ? $category->pivot->p2_cat : 0;
            self::categoryParentForBreadcrumb($category);
            $hierarchy_arr = self::$parentCatArr;
            $hierarchy_arr_rev = array_reverse($hierarchy_arr);
            //prd($hierarchy_arr_rev);
            $pcat = "";
            if (!empty($hierarchy_arr_rev) && count($hierarchy_arr_rev) > 0) {
                foreach ($hierarchy_arr_rev as $cat) {
                    $cat = (object)$cat;
                    if (isset($cat->name)) {
                        if (!empty($first_uri_name)) {
                            $breadcrumb.= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
                        }
                        $pCatUrl = route("products.list", ["pcat" => $cat->slug, ]);
                        if ($cat->id == $p1_cat) {
                            $pcat = $cat->slug;
                            $pCatUrl = route("products.list", ["pcat" => $cat->slug, ]);
                        } elseif ($cat->id == $p2_cat) {
                            //$pCatUrl = 'javascript:void(0)';
                            $pCatUrl = route("products.list", ["pcat" => $pcat, "p2cat" => $cat->slug, ]);
                        }
                        $breadcrumb.= '<a href="' . $pCatUrl . '" >' . $cat->name . "</a>";
                        $breadcrumb.= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
                    }
                }
                //$breadcrumb .= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
                
            } elseif (!empty($first_uri_name)) {
                $breadcrumb.= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
            }
            if ($is_last_link) {
                $catUrl = route("products.list", ["pcat" => $pcat, "cat[]" => $category->slug, ]);
                $breadcrumb.= '<a href="' . $catUrl . '">' . $category->name . "</a>";
            } else {
                //$breadcrumb .= '<a href="javascript:void(0)">'.$category->name.'</a>';
                $breadcrumb.= $category->name;
            }
        }
        return $breadcrumb;
    }

    public static function categoryDropDown($dropdown_name, $classAttr = "", $idAtrr = "", $selected_value = "", $allow_multiple = false) {
        $dropdown = '<select name="' . $dropdown_name . '" class="' . $classAttr . '" id="' . $idAtrr . '" >';
        if ($allow_multiple) {
            $dropdown = '<select name="' . $dropdown_name . '[]" class="' . $classAttr . '" id="' . $idAtrr . '" multiple>';
        }
        $dropdown.= '<option value="">--Select--</option>';
        $categories = Category::where(["parent_id" => 0])->orderBy("name")->get();
        if (!empty($categories) && count($categories) > 0) {
            foreach ($categories as $category) {
                $dropdown.= self::makeCategoryDropDown($category, $selected_value);
            }
        }
        $dropdown.= "</select>";
        return $dropdown;
    }

    public static function makeCategoryDropDown($category, $selected_value = "") {
        $selected = "";
        if (is_array($selected_value)) {
            if (in_array($category->id, $selected_value)) {
                $selected = "selected";
            }
        } else {
            if ($category->id == $selected_value) {
                $selected = "selected";
            }
        }
        $category_name = $category->name;
        if (isset($category->parent) && count($category->parent) > 0) {
            $mark = self::markCategoryParent($category);
            $category_name = $mark . $category_name;
        }
        $options = '<option value="' . $category->id . '" ' . $selected . " >" . $category_name . "</option>";
        if (isset($category->children) && count($category->children) > 0) {
            foreach ($category->children as $child_cat) {
                $options.= self::makeCategoryDropDown($child_cat, $selected_value);
            }
        }
        return $options;
    }

    public static function markCategoryParent($category) {
        $mark = "";
        if (isset($category->parent) && count($category->parent) > 0) {
            $mark.= " - ";
            $category_parent = $category->parent;
            $mark.= self::markCategoryParent($category_parent);
        }
        return $mark;
    }

    public static function getMenu($slug = "top-menu", $parent = 0) {
        $result = "";
        if (!empty($slug)) {

            $result = Menu::with('menuParentItems')->where(["slug" => $slug, "status" => 1])->first();
        }
        return $result;
    }
  public static function getMenuParentItems($slug = "top-menu", $parent = 0) {
        $menuParentItems = "";
        if (!empty($slug)) {
            // $result = Menu::with('menuParentItems')->where(["slug" => $slug, "status" => 1])->first();
            // $menuParentItems = $result->menuParentItems;

            $menuParentItems = [];
            $slug_url = 'menu_items-'.$slug;
            $menuParentItems = config('custom.'.$slug_url);
            if(empty($menuParentItems)) {
                    $menuParentItems = \Cache::rememberForever($slug_url, function () use($slug) {
                    
                        $result = Menu::with('menuParentItems')->where(["slug" => $slug, "status" => 1])->first();

                        $menuParentItems = [];
                 
                        $menuParentItems = $result?$result->menuParentItems:[];
                        if($menuParentItems){
                            return $menuParentItems;
                            
                        }
                });
                    if($menuParentItems){

                        config(['custom.'.$slug_url=>$menuParentItems]);
                    }
            }

        }
        return $menuParentItems;
    }

    public static function getMenuItemsList($menuItems, $menu_id, $is_parent = true, $parent_class = "", $child_class = "") {
        $routeName = self::getAdminRouteName();
        $list = "";
        if ($is_parent) {
            $list.= '<ol class="' . $parent_class . '">';
        }
        if (!empty($menuItems) && $menuItems->count() > 0) {
            foreach ($menuItems as $mi) {
                $list.= '<li class="' . $child_class . '" id="item_id_' . $mi->id . '">';
                $list.= '<span> <em><i class="fa fa-arrows-alt" style="padding-right: 10px;"></i>' .$mi->title.'</em>';
                $item_url = route($routeName . ".menus.items", $menu_id . "/" . $mi->id);
               
                if($mi->status == 1){
                    $status = '<span style="color:green">Active</span>';
                }else{
                    $status = '<span style="color:red">Inactive</span>';
                }

                $list.= '&nbsp;&nbsp;'.$status.'&nbsp;
                <label><a href="' . $item_url . '" title="Edit"><i class="fas fa-edit"></i></a>';
                /*$list.= '&nbsp;&nbsp;<div class="ac-inac">Active: <input type="radio" name="'.$mi->id.'" value="1" '.$active_checked.'>
                    &nbsp;
                    Inactive: <input type="radio" name="'.$mi->id.'" value="0" '.$inactive_checked.'></div><label><a href="' . $item_url . '" title="Edit"><i class="fas fa-edit"></i></a>';*/
                //if(self::checkPermission('menus','delete')){
                $list.= '&nbsp;&nbsp;<a href="javascript:void(0)" data-id="' . $mi->id . '" class="delItem" title="Delete"><i class="fas fa-trash-alt"></i></a></label> </span>';
               // }
                if (isset($mi->children) && $mi->children->count() > 0) {
                    $list.= '<ol class="">';
                    $list.= self::getMenuItemsList($mi->children, $menu_id, false, $parent_class, $child_class);
                    $list.= "</ol>";
                }
                $list.= "</li>";
            }
        }
        if ($is_parent) {
            $list.= "</ol>";
        }
        return $list;
    }

    public static function getMenuForFront($menuItems, $is_parent = true, $parent_class = "", $child_class = "", $child_parent_class = "", $have_child_class = "") {
        $routeName = self::getAdminRouteName();
        $list = "";
        if ($is_parent) {
            $list.= '<ul class="' . $parent_class . '">';
        }
        if (!empty($menuItems) && $menuItems->count() > 0) {
            foreach ($menuItems as $mi) {
                if($mi->status==1) {
                    $menuUrl = '';
                    if(!empty($mi->url)) {
                        $menuUrl = url($mi->url);
                    }
                    $target = $mi->target;
                    if ($mi->link_type == "external" && !empty($mi->url)) {
                        $menuUrl = $mi->url;
                    }
                    if(empty($menuUrl)) {
                        $menuUrl = 'javascript:void(0);';
                    }
                    if(!empty($have_child_class) && $mi->children->count() > 0){
                        $list.= '<li class="' . $child_class.' '. $have_child_class . '" id="item_id_' . $mi->id . '">';
                    }else{
                        $list.= '<li class="' . $child_class . '" id="item_id_' . $mi->id . '">';
                    }
                    $list.= '<a href="' . $menuUrl . '" target="' . $target . '">';
                    $list.= $mi->title;
                    $list.= "</a>";
                    if (isset($mi->children) && $mi->children->count() > 0) {
                        $list.= '<ul class="' . $child_parent_class . '">';
                        $list.= self::getMenuForFront($mi->children, false, $parent_class, $child_class, $child_parent_class, $have_child_class);
                        $list.= "</ul>";
                    }
                    
                    $list.= "</li>";
                }
            }
        }
        if ($is_parent) {
            $list.= "</ul>";
        }
        return $list;
    }

    public static function getNameFromNumber($num) {
        $index = 0;
        $index = abs($index * 1);
        $numeric = ($num - $index) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num - $index) / 26);
        if ($num2 > 0) {
            return self::getNameFromNumber($num2 - 1 + $index) . $letter;
        } else {
            return $letter;
        }
    }

    public static function BackUrl() {
        $uri = request()->path();
        if (count(request()->input()) > 0) {
            $request_input = request()->input();
            if (isset($request_input["back_url"])) {
                unset($request_input["back_url"]);
            }
            $uri.= "?" . http_build_query($request_input, "", "&");
        }
        //rawurlencode(str)
        //return rawurlencode($uri);
        return $uri;
    }

    public static function sendEmail_28_02_2023($viewPath, $viewData, $to, $from, $replyTo, $subject, $params = []) {
        try {
            Mail::send($viewPath, $viewData, function ($message) use ($to, $from, $replyTo, $subject, $params) {
                $attachment = isset($params["attachment"]) ? $params["attachment"] : "";
                if (!empty($replyTo)) {
                    $message->replyTo($replyTo);
                }
                if (!empty($from)) {
                    $message->from($from);
                }
                if (!empty($attachment)) {
                    $message->attach($attachment);
                }
                $message->to($to);
                $message->subject($subject);
            });
        }
        catch(\Exception $e) {
            // Never reached
            
        }
        // if (count(Mail::failures()) > 0) {
        //     return false;
        // } else {
        return true;
        //}
        
    }

    public static function sendEmailRaw_28_02_2023($html, $plainText, $to, $from, $replyTo, $subject, $params = []) {
        try {
            Mail::raw([], function ($message) use ($html, $plainText, $to, $from, $replyTo, $subject, $params) {
                $attachment = isset($params["attachment"]) ? $params["attachment"] : "";
                if (!empty($replyTo)) {
                    $message->replyTo($replyTo);
                }
                if (!empty($from)) {
                    $message->from($from);
                }
                if (!empty($attachment)) {
                    $message->attach($attachment);
                }
                $message->setBody($html, "text/html");
                $message->addPart($plainText, "text/plain");
                $message->to($to);
                $message->subject($subject);
            });
        }
        catch(\Exception $e) {
            // Never reached
            
        }
        if (count(Mail::failures()) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function CommonMail_28_02_2023($from_name='', $template_title='', $subject='', $reciver_emails='', $sender_email='', $email_params=[], $attachments=[], $reply_to='')
    {
        $getSenderName = SmtpSetting::where('id',1)->first();
        $mail_authenticate = $getSenderName->mail_gateway ?? '';
        $sender_email = $getSenderName->mail_gateway=='smtp' ? $getSenderName->from_mail : $getSenderName->sender_mail;
        $from_name = $getSenderName->mail_gateway=='smtp' ? $getSenderName->from_name : $getSenderName->sender_name;

        if (!empty($template_title)) {
            $email_content = EmailTemplate::where('title', $template_title)->where('status', 1)->first();
            $email_content_data = isset($email_content->content) ? $email_content->content : '';
            $email_params = isset($email_params) ? $email_params : [];
            $email_content_data = strtr($email_content_data, $email_params);

            $subject_data = isset($email_content->subject) ? $email_content->subject : '';
            if (!empty($subject)) {
                if (is_array($subject)) {
                    $subject = strtr($subject_data, $subject);
                } else {
                    $subject = $subject;
                }
            } else {
                $subject = $subject_data;
            }
        } else {
            $email_content_data = '';
        }

        $subject = isset($subject) ? $subject : '';
        $reciver_emails = isset($reciver_emails) ? $reciver_emails : '';
        $sender_email = isset($sender_email) ? $sender_email : config("custom.MAIL_FROM_ADDRESS");
        $attachments_files = isset($attachments) ? $attachments : [];
        $reply_to = isset($reply_to) ? $reply_to : '';

        // self::setMailConfig();
        if(isset($mail_authenticate) && $mail_authenticate == 'smtp'){

        try {
            \Mail::to($reciver_emails)->send(new \App\Mail\TestMail($from_name, $email_content_data, $subject, $sender_email, $attachments_files, $reply_to));
        } catch (\Exception$e) {

            // return back()->with('error', $e->getMessage());
            return ['status' => false, 'mail_data' => $e->getMessage()];

        }
        
        return ['status' => true, 'mail_data' => $email_content_data];
        }elseif(isset($mail_authenticate) && $mail_authenticate == 'phpmail'){
                        
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From:".$from_name."<".$sender_email.">" . "\r\n";
            $headers .= "Reply-To:".$reply_to . "\r\n";

            try {
                mail($reciver_emails,$subject,$email_content_data,$headers);
            } catch (\Exception$e) {
                return ['status' => false, 'mail_data' => $e->getMessage()];
            }
            
            return ['status' => true, 'mail_data' => $email_content_data];
        }

    }


    public static function sendCommonMail($params=[]) {
        $added_by = isset(auth()->user()->id) && auth()->user()->id != 0 ? auth()->user()->id : 'User not login';
        $result = false;
        $msg = '';
        $smtp_setting = SmtpSetting::where('id',1)->first();
        $mail_authenticate = $smtp_setting->mail_gateway ?? '';
        $is_mail_queue = $smtp_setting->is_queue ?? '';
        try {
            $mailer = $params['mailer']??'smtp';
            $from = '';
            if(isset($params['from']) && !empty($params['from'])) {
                $from = $params['from'];
            } else {
                $from = $smtp_setting['from_mail']??'';
                if(empty($from)) {
                    $from = CustomHelper::WebsiteSettings('FROM_EMAIL');
                }
            }

            $from_name = '';
            if(isset($params['from_name']) && !empty($params['from_name'])) {
                $from_name = $params['from_name'];
            } else {
                $from_name = $smtp_setting['from_name']??'';
                if(empty($from_name)) {
                    $from_name =  CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                }
            }
            $to = $params['to']??'';            
            $cc = $params['cc']??'';
            $bcc = $params['bcc']??'';
            $reply_to = $params['reply_to']??'';
            $subject = $params['subject']??'None';
            $filename = $params['filename']??'';
            $file_content = $params['file_content']??'';
            $module_name = $params['module_name']??'';
            $record_id = $params['record_id']??0;
            $IP = request()->ip();
            $data = [];
            $data['email_content'] = $params['email_content']??'';
            if(!empty($from) && !empty($to)) {                
                $attachments = [];
                if(isset($params['attachments'])) {
                    if(is_array($params['attachments'])) {
                        $attachments = $params['attachments'];
                    } else {
                        $attachments = explode(',', $params['attachments']);
                    }
                }

                if(!empty($is_mail_queue) && $is_mail_queue == 1) {
                    dispatch(new SendEmailJob($to, $cc, $bcc, $reply_to, $mailer, $from, $data, $subject, $filename, $file_content, $from_name, $attachments, $module_name, $record_id, $added_by, $IP, $mail_authenticate));
                } else {
                    $res =  Mail::mailer($mailer);
                    $res = $res->to($to);
                    if(!empty($cc) && $to != $cc) {
                        $cc = explode(",",$cc);
                        $res = $res->cc($cc);
                    }                
                    if($bcc) {
                        $bcc = explode(",",$bcc);
                        $res = $res->bcc($bcc);
                    }
                    if($reply_to) {
                        // $res = $res->replyTo($reply_to);
                    }
                    if(isset($mail_authenticate) && $mail_authenticate == 'smtp'){
                        $res = $res->send(new ParseEmail($from, $data, $subject, $filename, $file_content, $from_name, $attachments));
                    } else if(isset($mail_authenticate) && $mail_authenticate == 'phpmail') {

                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $headers .= "From:<".$from.">" . "\r\n";
                        $headers .= "Reply-To:".$reply_to . "\r\n";

                        try {
                            mail($to,$subject,$data['email_content'],$headers);
                        } catch (\Exception$e) {
                            $msg = $e->getMessage();

                            $MailErrorLog['msg_body'] = $data['email_content'];
                            $MailErrorLog['msg_subject'] = $subject;
                            $MailErrorLog['attachments'] = json_encode($attachments);
                            $MailErrorLog['msg_from'] = $from;

                            if (isset($cc) && is_array($cc)) {
                                $cc = implode(',', $cc);
                            }
                            $cc_email = isset($cc) && !empty($cc) ? ' , '.$cc : '';
                            if (isset($bcc) && is_array($bcc)) {
                                $bcc = implode(',', $bcc);
                            }
                            $bcc_email = isset($bcc) && !empty($bcc) ? ' , '.$bcc : '';
                            $MailErrorLog['msg_to'] = $to.$cc_email.$bcc_email;

                            $MailErrorLog['module_name'] = $module_name;
                            $MailErrorLog['record_id'] = $record_id;
                            $MailErrorLog['added_by'] = $added_by;
                            $MailErrorLog['ip'] = $IP;
                            $MailErrorLog['status'] = 'failed';
                            $MailErrorLog['error_message'] = $msg;
                            $MailErrorLog['type'] = $mail_authenticate;
                            MailErrorLog::create($MailErrorLog);
                        }
                        $result = true;
                    }
                }
            } else {
                $msg = 'From or To emails is missing!';
            }
        } catch (\Exception $e) {
            $msg = $e->getMessage();

            $MailErrorLog['msg_body'] = $data['email_content'];
            $MailErrorLog['msg_subject'] = $subject;
            $MailErrorLog['attachments'] = json_encode($attachments);
            $MailErrorLog['msg_from'] = $from;

            if (isset($cc) && is_array($cc)) {
                $cc = implode(',', $cc);
            }
            $cc_email = isset($cc) && !empty($cc) ? ' , '.$cc : '';
            if (isset($bcc) && is_array($bcc)) {
                $bcc = implode(',', $bcc);
            }
            $bcc_email = isset($bcc) && !empty($bcc) ? ' , '.$bcc : '';
            $MailErrorLog['msg_to'] = $to.$cc_email.$bcc_email;

            $MailErrorLog['module_name'] = $module_name;
            $MailErrorLog['record_id'] = $record_id;
            $MailErrorLog['added_by'] = $added_by;
            $MailErrorLog['ip'] = $IP;
            $MailErrorLog['status'] = 'failed';
            $MailErrorLog['error_message'] = $msg;
            $MailErrorLog['type'] = $mail_authenticate;
            MailErrorLog::create($MailErrorLog);

            $result = true;
        }
        // return $result;
        return ['status' => $result, 'mail_data' => $msg];
    }


    public static function getCMSPage($slug='', $cols = ["*"]) {
        //prd($slug);
        $data = [];
        $data['cms_type'] = '';
        $data['title'] = '';
        $data['image'] = '';
        $data['banner_image'] = '';
        $data['heading'] = '';
        $data['content'] = '';
        $data['meta_title'] = '';
        $data['meta_keyword'] = '';
        $data['meta_description'] = '';
        $data['status'] = 0;
        if (!empty($slug)) {
            $cms = CmsPages::where("slug", $slug)->where('status',1)->select($cols)->first();
            if (!empty($cms)) {
                $storage = Storage::disk('public');
                $path = 'cms_pages/';
                $thumb_path = 'cms_pages/';

                $imageSrc = '';//asset(config('custom.assets').'/images/noimagebig.jpg');
                // $thumbSrc = asset('assets/img/default_user.png');
                if(!empty($cms->image) && $storage->exists($path.$cms->image)) {
                    $imageSrc = asset('/storage/'.$path.$cms->image);
                    $thumbSrc = asset('/storage/'.$thumb_path.$cms->image);
                }

                $bannerImageSrc = asset(config('custom.assets').'/images/default_common_banner.jpg');;
                $bannerThumbSrc = '';
                if(!empty($cms->banner_image) && $storage->exists($path.$cms->banner_image)) {
                    $bannerImageSrc = asset('/storage/'.$path.$cms->banner_image);
                    $bannerThumbSrc = asset('/storage/'.$thumb_path.$cms->banner_image);
                }

                $data["cms_type"] = $cms->cms_type??'';
                $data["title"] = $cms->title??'';
                $data["id"] = $cms->id??'';
                $data["slug"] = $cms->slug??'';
                $data["parent_id"] = $cms->parent_id??'';
                $data["heading"] = $cms->heading??'';
                $data["brief"] = $cms->brief??'';
                $data["template"] = $cms->template??'';
                $data["imageSrc"] = $imageSrc??'';
                $data["thumbSrc"] = $thumbSrc??'';
                $data["bannerImageSrc"] = $bannerImageSrc??'';
                $data["bannerThumbSrc"] = $bannerThumbSrc??'';
                $data["content"] = CustomHelper::parseOutput($cms->content??'');
                $data["meta_title"] = (!empty($cms->meta_title))?$cms->meta_title:($cms->title??'');
                $data["meta_keyword"] = $cms->meta_keyword??'';
                $data["meta_description"] = $cms->meta_description??'';
                $data["status"] = $cms->status??'';

                $images_arr = [];
                $images = $cms->cmsImages??[];
                if(!empty($images)) {
                    foreach($images as $image){
                        $name = $image->name??'';
                        if($storage->exists($path.$name)){
                            $imageSrc = asset('/storage/'.$path.$name);
                            $thumbSrc = asset('/storage/'.$path.'thumb/'.$name);
                            $images_arr[] = array(
                                'name' => $name,
                                'title' => $image->title,
                                'imageSrc' => $imageSrc,
                                'thumbSrc' => $thumbSrc,
                            );
                        }
                    }
                }
                $data['images'] = $images_arr;
                
                $cms_child_data = CmsPages::where(['status'=>1, 'parent_id'=>$cms->id])->orderBy('sort_order','ASC')->get();
                $child_data_arr = [];
                if($cms_child_data->count() > 0) {
                    foreach ($cms_child_data as $child) {
                        if($child->slug) {
                            $child_data_arr[] = CustomHelper::getCMSPage($child->slug);
                        }
                    }
                }
                $data["child_data"] = $child_data_arr;
            }
        }
        return $data;
    }

    public static function updateData($tbl, $id_col, $id, $data) {
        $is_updated = 0;
        if (!empty($tbl) && !empty($id_col) && is_numeric($id) && $id > 0 && is_array($data) && count($data) > 0) {
            $is_updated = DB::table($tbl)->where($id_col, $id)->update($data);
        }
        return $is_updated;
    }

    public static function isSerialized($value, &$result = null) {
        if (empty($value)) {
            return false;
        }
        // Bit of a give away this one
        if (!is_string($value)) {
            return false;
        }
        // Serialized false, return true. unserialize() returns false on an
        // invalid string or it could return false if the string is serialized
        // false, eliminate that possibility.
        if ($value === "b:0;") {
            $result = false;
            return true;
        }
        $length = strlen($value);
        $end = "";
        switch ($value[0]) {
            case "s":
                if ($value[$length - 2] !== '"') {
                    return false;
                }
            case "b":
            case "i":
            case "d":
                // This looks odd but it is quicker than isset()ing
                $end.= ";";
            case "a":
            case "O":
                $end.= "}";
                if ($value[1] !== ":") {
                    return false;
                }
                switch ($value[2]) {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                    break;
                    default:
                        return false;
                }
            case "N":
                $end.= ";";
                if ($value[$length - 1] !== $end[0]) {
                    return false;
                }
            break;
            default:
                return false;
        }
        if (($result = @unserialize($value)) === false) {
            $result = null;
            return false;
        }
        return true;
    }

    public static function makeStarRatingArr($rating = 5) {
        $ratingArr = explode(".", $rating);
        $count = $ratingArr[0];
        $revCount = 5 - $count;
        $starColorArr = [];
        for ($i = 1;$i <= $count;$i++) {
            $starColorArr[] = '<span class="fa fa-star color"></span>';
        }
        $starArr = [];
        if ($revCount > 0) {
            for ($r = 1;$r <= $revCount;$r++) {
                $starArr[] = '<span class="fa fa-star"></span>';
            }
        }
        $starArray = array_merge($starColorArr, $starArr);
        return $starArray;
    }

    public static function wordsLimit($str, $limit = 150, $isStripTags = true, $allowTags = "",$add_new='') {
        $newStr = "";
        if (strlen($str) <= $limit) {
            $newStr = $str;
        } else {
            $newStr = substr($str, 0, $limit) . " ...".$add_new;
        }
        if ($isStripTags) {
            if (!empty($allowTags)) {
                $newStr = strip_tags($newStr, $allowTags);
            } else {
                $newStr = strip_tags($newStr);
            }
        }
        return $newStr;
    }
    
    public static function convertCurrency($amount, $from = "INR", $to = "USD", $decimals = 0) {
        $CurrencyConverter = new CurrencyConverter();
        $converted = $CurrencyConverter->convert($amount, $from, $to, $decimals);
        return $converted;
    }
    
    public static function isMobile($userAgent = null, $httpHeaders = null) {
        $detect = new MobileDetect();
        $detected = $detect->isMobile($userAgent = null, $httpHeaders = null);
        return $detected;
    }

    private static $categoryAttributes = [];

    public static function getParentCategoryAttributes($category) {
        if (!empty($category) && count($category) > 0) {
            if (isset($category->parent) && count($category->parent) > 0) {
                self::getParentCategoryAttributes($category->parent);
            }
            $attributes = isset($category->categoryAttributes) ? $category->categoryAttributes : "";
            if (!empty($attributes) && count($attributes) > 0) {
                self::$categoryAttributes[] = $attributes;
            }
        }
        return self::$categoryAttributes;
    }

    public static function getData($tbl, $id = 0, $where = "", $selectArr = ["*"], $params = []) {
        $result = "";
        $orderByArr = isset($params["orderBy"]) ? $params["orderBy"] : "";
        $featured = isset($params["featured"]) ? $params["featured"] : "0";
        $query = DB::table($tbl);
        $query->select($selectArr);
        if (!empty($where) && count($where) > 0) {
            $query->where($where);
        }
        if (!empty($orderByArr) && count($orderByArr) > 0) {
            foreach ($orderByArr as $orderKey => $orderVal) {
                $query->orderBy($orderKey, $orderVal);
            }
        }
        if (isset($featured) && !empty($featured)) {
            $query->where("featured", $featured);
        }
        if (isset($params["limit"]) && is_numeric($params["limit"]) && $params["limit"] > 0) {
            $query->limit($params["limit"]);
        }
        if (is_numeric($id) && $id > 0) {
            $query->where("id", $id);
            $result = $query->first();
        } else {
            $result = $query->get();
        }
        return $result;
    }

    // Common Function for GetEvents
    public static function getEvents($id = "", $limit = "", $params = []) {
        $events = "";
        //$params['orderBy'] = ['id'=>'asc', 'name'=>'desc'];
        $orderByArr = isset($params["orderBy"]) ? $params["orderBy"] : "";
        $featured = isset($params["featured"]) ? $params["featured"] : "0";
        $event_query = Event::where("status", 1);
        if (!empty($orderByArr) && count($orderByArr) > 0) {
            foreach ($orderByArr as $orderKey => $orderVal) {
                $event_query->orderBy($orderKey, $orderVal);
            }
        }
        if (isset($featured) && !empty($featured)) {
            $event_query->where("featured", $featured);
        }
        if (isset($params["limit"]) && is_numeric($params["limit"]) && $params["limit"] > 0) {
            $event_query->limit($params["limit"]);
        }
        if (is_numeric($id) && $id > 0) {
            $event_query->where("id", $id);
            $events = $event_query->first();
        } else {
            $events = $event_query->get();
        }
        return $events;
    }

    public static function getBlogs($id = "", $type = "blogs", $limit = "", $params = []) {
        $data = "";
        $blog_type_arr = config("custom.blog_type_arr");
        $type = isset($blog_type_arr[$type]) ? $type : "blogs";
        //$params['orderBy'] = ['id'=>'asc', 'name'=>'desc'];
        $orderByArr = isset($params["orderBy"]) ? $params["orderBy"] : "";
        $featured = isset($params["featured"]) ? $params["featured"] : "0";
        $news_query = Blog::where(["status" => 1, "type" => $type]);
        if (!empty($orderByArr) && count($orderByArr) > 0) {
            foreach ($orderByArr as $orderKey => $orderVal) {
                $news_query->orderBy($orderKey, $orderVal);
            }
        }
        if (isset($featured) && !empty($featured)) {
            $news_query->where("featured", $featured);
        }
        if (isset($params["limit"]) && is_numeric($params["limit"]) && $params["limit"] > 0) {
            $news_query->limit($params["limit"]);
        }
        if (is_numeric($id) && $id > 0) {
            $news_query->where("id", $id);
            $data = $news_query->first();
        } else {
            $data = $news_query->get();
        }
        return $data;
    }

    public static function getCmsData($id = 0, $parent_id = 0, $limit = "", $params = []) {
        $data = "";
        $parent_id = isset($parent_id) ? $parent_id : 0;
        $featured = isset($params["featured"]) ? $params["featured"] : "0";
        $query = CmsPages::where("status", 1);
        if (is_numeric($parent_id) && $parent_id > 0) {
            $query->where("parent_id", $parent_id);
        }
        if (isset($featured) && !empty($featured)) {
            $query->where("featured", $featured);
        }
        if (isset($params["limit"]) && is_numeric($params["limit"]) && $params["limit"] > 0) {
            $query->limit($params["limit"]);
        }
        if (is_numeric($id) && $id > 0) {
            $query->where("id", $id);
            $data = $query->first();
        } else {
            $data = $query->get();
        }
        return $data;
    }

    /* End Common Function */
    // For Menus
    public static function getHeaderMenu($menu_name = "", $menu_id = "", $parent_id = 0, $ul_class = "", $child_ul_class = "", $li_class = "", $child_li_class = "") {
        $mess = "";
        $id = 0;
        if ($menu_name != "") {
            $menu = Menu::where(["slug" => $menu_name])->first();
            if (!empty($menu)) {
                $id = $menu->id;
            }
        }
        $query = MenuItem::where("menu_id", $id)->orderBy("sort_order", "asc");
        if (!empty($menu_id)) {
            $query->where("id", $menu_id);
        }
        if (!empty($parent_id)) {
            $query->where("parent_id", $parent_id);
        }
        $itemData = $query->get();
        $res = $itemData;
        //prd($itemData);
        $total_menu = count($itemData);
        $menu_count = 2;
        if (!empty($res)) {
            $mess.= '<ul class="' . $ul_class . '">';
            foreach ($res as $r) {
                $url = "";
                if (!empty($r->slug_url)) {
                    $url = url($r->slug_url);
                }
                if (empty($url)) {
                    $url = "javascript:void(0)";
                    if ($r->url == "/") {
                        $url = url("");
                    }
                    if ($r->link_type == "external" && !empty($r->url)) {
                        $url = $r->url;
                    }
                    if ($r->link_type == "internal" && !empty($r->url)) {
                        $url = url("/") . $r->url;
                    }
                }
                $name = $r->title;
                $target = $r->target;
                $mess.= "<li class=" . $li_class . "><a target=" . $target . " href=" . $url . ">" . $name . "</a>";
                $mess.= self::getHeaderMenu($menu_name = "", $r->menu_id, $r->id, $child_ul_class, "", $child_li_class, "");
                $mess.= "</li>";
                if ($r->parent_id == 0) {
                    $menu_count++;
                }
            }
            $mess.= "</ul>";
        }
        return $mess;
    }

    public static function getStaticFormElements() {
        $result = [];
        $formElements = FormElement::where("is_static", 1)->get();
        return $formElements;
    }    

    // Add Activity Log Code Start....
    // public static function recordActionLog($function_name = "", $action_table = "", $row_id = "", $action_type = "", $action_description = "", $description = "", $user_id = "") {
    //     $isSaved = "";
    //     $insertData = [];
    //     $user = auth()->user();
    //     if (!empty($function_name)) {
    //         $insertData["function_name"] = $function_name;
    //     }
    //     if (!empty($action_table)) {
    //         $insertData["action_table"] = $action_table;
    //     }
    //     if (!empty($row_id)) {
    //         $insertData["row_id"] = $row_id;
    //     }
    //     if (!empty($action_type)) {
    //         $insertData["action_type"] = $action_type;
    //     }
    //     if (!empty($action_description)) {
    //         $insertData["action_description"] = $action_description;
    //     }
    //     if (!empty($description)) {
    //         $insertData["description"] = $description;
    //     }
    //     $insertData["user_id"] = $user->id;
    //     $insertData["ip_address"] = Request::ip();
    //     //prd($insertData);
    //     $isSaved = DB::table("activity_logs")->insert($insertData);
    //     return $isSaved;
    // }
    // Add Activity Log Code Closed....

    public static function RecordOrderStatusHistory($params=array())
    {
        $user_id = 0;
        $user_name = '';
        $user = auth()->user();
        $admin_user = auth()->guard('admin')->user();
        if($user) {
            $user_id = $user->id??0;
            $user_name = $user->name??'';
            $params['created_type'] = 'customer';
        } else if($admin_user) {
            $user_id = $admin_user->id??0;
            $user_name = $admin_user->name??'';
            $params['created_type'] = 'backend';
        }
        if(!empty($user_id)) {
            $params['created_by'] = $user_id;
        }
        if(!empty($user_name)) {
            $params['created_by_name'] = $user_name;
        }
        $create_data = OrderStatusHistory::create($params);
    }


    public static function RecordLoginHistory($params=array())
    {
        //prd($params);
        //try{
            $operating_system = Agent::platform();
            $browser = Agent::browser();
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $params['ip_address'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $params['ip_address'] = (isset($params['ip_address']))?$params['ip_address']:$_SERVER['REMOTE_ADDR'];
            }
            if(!empty($operating_system)) {
                $params['os_details'] = $operating_system;
            }
            if(!empty($browser)) {
                $params['browser_details'] = $browser;
            }
            $params['created_at'] = date('Y-m-d H:i:s');
            $params['updated_at'] = date('Y-m-d H:i:s');

            $create = LoginHistory::create($params);
            //prd($create);

            /*if($create){
                // Clear the catche Start
                $module = $params['module']??'';
                $module_id = $params['module_id']??'';
                switch ($module) {
                    case 'Package':
                    case 'Destination':
                    case 'Accommodation':
                            Self::cache_flush('destinations');
                            Self::cache_flush('accommodations');
                            Self::cache_flush('packages');
                            Self::cache_flush('packages-slot-'.$module_id);
                        break;
                    case 'Enquiries Master Group':
                    case 'Enquiries Master':
                            Self::cache_flush('enquiries_master');
                        break;
                    case 'Role':
                            Self::cache_flush("roles_permission-$module_id");
                            Self::cache_flush("roles");
                        break;
                    case 'User':
                            Self::cache_flush('backend_users');
                        break;
                    case 'Seo Modules':
                            Self::cache_flush('seo_meta_tags');
                        break;
                    case 'WebsiteSettings':
                            Self::cache_flush('settings');
                            Self::cache_flush('website_settings');
                        break;
                    case 'Price Category':
                            Self::cache_flush('price_categories');
                        break;
                    default:
                        # code...
                        break;
                }
                // Clear the catche End
                return true;
            }else{
                return false;
            }
        /*} catch (\Exception $e) {
            // prd($e->getMessage());
            Self::captureSentryMessage('RecordActivityLog error='.$e->getMessage());
            $result = false;
            }*/
    }
    public static function RecordActivityLog($params=array())
    {
        //try{
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $params['ip_address'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $params['ip_address'] = (isset($params['ip_address']))?$params['ip_address']:$_SERVER['REMOTE_ADDR'];
            }
            if(!isset($params['user_agent'])) {
                $params['user_agent'] = (isset($_SERVER['HTTP_USER_AGENT']))?$_SERVER['HTTP_USER_AGENT']:'';
            }

            $params['created_at'] = date('Y-m-d H:i:s');
            $params['updated_at'] = date('Y-m-d H:i:s');

            $create = ActivityLog::create($params);
            //prd($create);

            if($create){
                // Clear the catche Start
                $module = $params['module']??'';
                $module_id = $params['module_id']??'';
                switch ($module) {
                    case 'Package':
                    case 'Activity':
                    case 'Destination':
                    case 'Accommodation':
                            Self::cache_flush('destinations');
                            Self::cache_flush('accommodations');
                            Self::cache_flush('packages');
                            Self::cache_flush('packages-slot-'.$module_id);
                            Self::cache_flush('accommodation_room');
                        break;
                    case 'Enquiries Master Group':
                    case 'Enquiries Master':
                            Self::cache_flush('enquiries_master');
                        break;
                    case 'Accommodation Feature':
                    case 'Room Features':
                    case 'Accommodation Facility':
                        Self::cache_flush('accommodations');
                        Self::cache_flush('accommodation_types');
                        Self::cache_flush('accommodation_features');
                        Self::cache_flush('room_features');
                        Self::cache_flush('accommodation_facilities');
                        Self::cache_flush('accommodation_room');
                        break;
                    case 'Role':
                            Self::cache_flush("roles_permission-$module_id");
                            Self::cache_flush("roles");
                        break;
                    case 'User':
                            Self::cache_flush('backend_users');
                        break;
                    case 'Seo Modules':
                            Self::cache_flush('seo_meta_tags');
                        break;
                    case 'WebsiteSettings':
                            Self::cache_flush('settings');
                            Self::cache_flush('website_settings');
                        break;
                    case 'Price Category':
                            Self::cache_flush('price_categories');
                        break;
                    case 'Menu':
                            Self::cache_flush('menu_items-main-header-menu');
                            Self::cache_flush('menu_items-main-footer-menu');
                            Self::cache_flush('menu_items-top-menu');
                        break;
                    case 'CMS Pages':
                            Self::cache_flush('cms');
                            Self::cache_flush('cms_pages');
                        break;
                    case 'Cab':
                    case 'Cab City':
                            Self::cache_flush('cab_master');
                            Self::cache_flush('cabs');
                            Self::cache_flush('cab_cities');
                        break;
                    case 'URL Redirection':
                            Self::cache_flush('redirections');
                        break;
                    default:
                        # code...
                        break;
                }
                // Clear the catche End
                return true;
            }else{
                return false;
            }
        /*} catch (\Exception $e) {
            // prd($e->getMessage());
            Self::captureSentryMessage('RecordActivityLog error='.$e->getMessage());
            $result = false;
            }*/
    }

    // get Widget Code Start....
    public static function getWidget($widgetID) {
        if (!empty($widgetID) && is_numeric($widgetID)) {
            $widgetDetail = Widget::where('id', $widgetID)->where('status', 1)->first();
            return $widgetDetail;
        } else {
            $widgetDetail = Widget::where('slug', $widgetID)->where('status', 1)->first();
            return $widgetDetail;
        }
        return false;
    }
    // get Widget Code Closed....

    // get Package Price itenary Accommodations Start....
    public static function getPackagePriceAccommodations($package_id,$priceInfoId,$price_required=1) {
        if (!empty($package_id) && is_numeric($package_id) && $priceInfoId && is_numeric($priceInfoId)) {
            $packageAccommodations = DB::table('package_accommodations')->where('package_id', $package_id)->where('package_price_id', $priceInfoId)->get();
            $itenaryResults = [];
            foreach ($packageAccommodations as $packageAccommodation) {
                $accommodationDetails = Accommodation::find($packageAccommodation->accommodation_id);
                $itenaryResults[$packageAccommodation->itenary_id] = $accommodationDetails;
            }
            return $itenaryResults;
        }else if($price_required==0){


  $packageAccommodations=$accommodation_without_pricNew=array();    
  $packageAccommodations = DB::table('package_accommodations')->where('package_id',$package_id)->where(['package_price_id'=>0])->get()->toArray();

 

            $itenaryResults = [];
            foreach ($packageAccommodations as $packageAccommodation) {

          #  echo "|".$packageAccommodation->accommodation_id."|".$packageAccommodation->itenary_id."|<hr>"; 
                $accommodationDetails = Accommodation::find($packageAccommodation->accommodation_id);
                #prd($accommodationDetails);
                $itenaryResults[$packageAccommodation->itenary_id] = $accommodationDetails;
            }

        // prd($itenaryResults); 
            return $itenaryResults;

        }
        return false;
    }

    // Get Service Levels
    public static function getServiceLevels() {
        $service_level_arr = ServiceLevel::where('status',1)->pluck('name','id')->toArray();
        //prd($service_level_arr);
        return $service_level_arr;
    }

     public static function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0)
            return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }


    public function parseFormData($content, $className = '', $params = [])
    {
        // prd($params);
        $refURL = url()->current();
        $newContent = $element = '';
        if ($content != '') {
            $data = [];
            if(isset($params['search_data'])) {
                $data['search_data'] = $params['search_data'];
            }
            $oldShortCode = 0;
            if (stripos($content, '[')) 
            {
                $oldShortCode = 1;
                $formSlug = $this->get_string_between($content, '[', ']');
            } 
            else 
            {
                $formSlug = $content;
            }
            
            if (strpos($formSlug, '[') !== false && strpos($formSlug, ']') !== false) 
            {
                 $formSlug = str_replace(['[', ']'], '', $formSlug);
            }

            else
            {
                $formSlug = $formSlug; 
            }
  

            $formHtml = '';
            $formName = '';
            if (!empty($formSlug)) {
                $section_title = $params['section_title'] ?? '';
                //$data['countries'] = DB::table("countries")->where("status", 1)->orderBy('phonecode','ASC')->get();
                $data['countries'] = Country::orderBy('name', 'ASC')->get();
                $data['states'] = State::where('status', 1)->orderBy('name', 'ASC')->get();
                $forms = Form::where('slug', $formSlug)->first();
                // prd($forms->toArray());
                if (!empty($forms)) {
                    $formID = $forms->id;
                    $formName = "[" . $formSlug . "]";
                    $matchThese = ['form_id' => $formID, 'is_hide' => 0];
                    $orThose = ['is_static' => 1, 'is_hide' => 0];
                    $formDetails = FormElement::where($matchThese)->selectRaw('*,case when position>0 THEN position else 999 end as position')->orderBy('position', 'asc')->get()->toArray();
                    // prd($formDetails);
                    if (!empty($formDetails)) {
                        $formHtml = '<div class="book-space"><div class="bookspace-inner"><h3>'.$section_title.'</h3><form method="POST" action="" id="form_' . $formSlug . '" class="common_main_form needs-validation ' . $className . '" novalidate  accept-charset="UTF-8" enctype="multipart/form-data"><div class="ajax_msg"></div><input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response"><input type="hidden" name="action" value="validate_captcha"><div class="formMessage"></div>';
                        $formHtml .= csrf_field();
                        foreach ($formDetails as $rec) {
                            $data['element'] = $rec;
                           // $formHtml .= view('includes._form_fields', $data)->render();
                            $formHtml .=view(config('custom.theme').'.layouts._form_fields',$data)->render();
                        }
                        if (array_key_exists('hidden', $params)) {
                            $hidden = $params['hidden'];
                            if (is_array($hidden)) {
                                foreach ($hidden as $hidden_key => $hidden_val) {
                                    $formHtml .= '<input type="hidden" name="hidden_' . $hidden_key . '" value="' . $hidden_val . '">';
                                }
                            }
                        }
                        $formHtml .= '<input type="hidden" name="form_slug" value="' . $formSlug . '">';
                        $formHtml .= '<input type="hidden" name="refer_url" value="' . $refURL . '">
                        <div class="btn-book-space">
                        <button class="btn btn-outline btn-theme btnSubmit">' . $forms->btnName . '</button>';
                        if ($forms->is_reset == 1) {
                            $formHtml .= '<button class="btn btn-outline btn-theme btn-green btnReset" >Reset</button>';
                        }
                        $formHtml .= '</div>
                        </form>
                        </div>
                        </div>';
                    }
                    if ($oldShortCode) {
                        $newContent = str_replace($formName, $formHtml, $content);
                    } else {
                        $newContent = $formHtml;
                    }
                }
            } else {
                $newContent = $content;
            }
        }
        return $newContent;
    }

    public function parseFormDataOld($content,$className='',$params=[]) {
        // prd($params);
        $refURL = url()->current();
        $newContent = $element = '';
        if($content!='') {
            $data = [];
            //$oldShortCode = 0;
           $formSlug = $this->get_string_between($content,'[',']');      
           

           
          

            // if (stripos($content, '[')) {
            //     $oldShortCode = 1;
            //     $formSlug = $this->get_string_between($content, '[', ']');
            // } else {
            //     $formSlug = $content;
            // }



            $formHtml = '';
            $formName = '';
            if(!empty($formSlug)) {
                //$data['countries'] = DB::table("countries")->where("status", 1)->orderBy('phonecode','ASC')->get();
                $data['countries'] = Country::where("status", 1)->orderBy('phonecode','ASC')->get();
                $data['slug'] = $content;
                $forms = Form::where('slug',$formSlug)->first();

                if(!empty($forms)) {
                    $formID = $forms->id;
                    $formName = "[".$formSlug."]";
                    $matchThese = ['form_id'=>$formID,'is_hide'=>0];
                    $orThose = ['is_static'=>1,'is_hide'=>0];
                    $formDetails = FormElement::where($matchThese)->selectRaw('*,case when position>0 THEN position else 999 end as position')->orderBy('position','asc')->get()->toArray();
                    if(!empty($formDetails)) {
                        $formHtml='<div class="book-space"><div class="bookspace-inner"><form method="POST" action="" id="form_'.$formSlug.'" class="common_main_form needs-validation '.$className.'" novalidate  accept-charset="UTF-8" role="form" enctype="multipart/form-data"><div class="ajax_msg"></div><input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response"><input type="hidden" name="action" value="validate_captcha"><div class="formMessage"></div>';
                        $formHtml.=csrf_field();
                        foreach($formDetails as $rec) {
                            $data['element'] = $rec;
                            $formHtml .=view(config('custom.theme').'.layouts._form_fields',$data)->render();
                        }
                        if(array_key_exists('hidden', $params)) {
                            $hidden = $params['hidden'];
                            if(is_array($hidden)) {
                                foreach($hidden as $hidden_key => $hidden_val) {
                                    $formHtml .='<input type="hidden" name="hidden_'.$hidden_key.'" value="'.$hidden_val.'">';
                                }
                            }
                        }
                        $formHtml .='<input type="hidden" name="form_slug" value="'.$formSlug.'">';
                        $formHtml .='<input type="hidden" name="refer_url" value="'.$refURL.'">
                        <div class="btn-book-space">
                        <button class="btn btn-outline btn-theme btnSubmit">'.$forms->btnName.'</button>';
                        if($forms->is_reset==1) {
                            $formHtml .='<button class="btn btn-outline btn-theme btn-green btnReset" >Reset</button>';
                        }
                        $formHtml .='</div>
                        </form>
                        </div>
                        </div>';
                    }
                    // if ($oldShortCode) {
                    //     $newContent = str_replace($formName, $formHtml, $content);
                    // } else {
                    //     $newContent = $formHtml;
                    // }
                    $newContent = str_replace($formName, $formHtml, $content);
                }
            } else {
                $newContent = $content;
            }
        }
        return $newContent;
    }
    
    public function parseFormData_04apr2023($content,$className='',$params=[]) {
        // prd($params);
        $refURL = url()->current();
        $newContent = $element = '';
        if($content!='') {
            $data = [];
            $formID = $this->get_string_between($content,'[forms_',']');
            $formHtml = '';
            $formName = '';
            if(is_numeric($formID) && $formID > 0) {
                $data['countries'] = DB::table("countries")->where("status", 1)->orderBy('phonecode','ASC')->get();
                $forms = Form::where('id',$formID)->first();
                $formName="[forms_".$formID."]";
                $matchThese = ['form_id'=>$formID,'is_hide'=>0];
                $orThose = ['is_static'=>1,'is_hide'=>0];
                $formDetails=FormElement::where($matchThese)->selectRaw('*,case when position>0 THEN position else 999 end as position')->orderBy('position','asc')->get()->toArray();
                if(!empty($formDetails)) {
                    $formHtml='<div class="book-space"><div class="bookspace-inner"><form method="POST" action="" id="form_'.$formID.'" class="common_main_form needs-validation '.$className.'" novalidate  accept-charset="UTF-8" role="form" enctype="multipart/form-data"><div class="ajax_msg"></div><input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response"><input type="hidden" name="action" value="validate_captcha"><div class="formMessage"></div>';
                    $formHtml.=csrf_field();
                    foreach($formDetails as $rec) {
                        $data['element'] = $rec;
                        $formHtml .=view(config('custom.theme').'.layouts._form_fields',$data)->render();
                    }
                    if(array_key_exists('hidden', $params)) {
                        $hidden = $params['hidden'];
                        if(is_array($hidden)) {
                            foreach($hidden as $hidden_key => $hidden_val) {
                                $formHtml .='<input type="hidden" name="hidden_'.$hidden_key.'" value="'.$hidden_val.'">';
                            }
                        }
                    }
                    $formHtml .='<input type="hidden" name="form_id" value="'.$formID.'">';
                    $formHtml .='<input type="hidden" name="refer_url" value="'.$refURL.'">
                    <div class="btn-book-space">
                    <button class="btn btn-outline btn-theme btnSubmit">'.$forms->btnName.'</button>';
                    if($forms->is_reset==1) {
                        $formHtml .='<button class="btn btn-outline btn-theme btn-green btnReset" >Reset</button>';
                    }
                    $formHtml .='</div>
                    </form>
                    </div>
                    </div>';
                }
                $newContent = str_replace($formName,$formHtml,$content);
            } else {
                $newContent = $content;
            }
        }
        return $newContent;
    }

    public static function getDashbordFormElements($formID,$is_display=0) {
        $result = [];
        if($is_display==1) {
            $matchThese = ['form_id'=>$formID,'is_display'=>1];
            $orThose = ['is_static'=>1,'is_display'=>1];
            $formElements = FormElement::where($matchThese)->orWhere(function ($query) {
                $query->where('is_static', '=', 1)
                ->where('is_display', '=', 1);
            })->selectRaw('*,case when position>0 THEN position else 999 end as position')->orderBy('position','asc')->get();           
        } else {
            $formElements = FormElement::where('form_id', '=', $formID)
            ->orWhere('is_static', '=', 1)->orderBy('position','asc')->get();   
        }
        return $formElements;
    }

    public static function form_short_code($params = '') {
        $formHtml = '';
        $slug = isset($params['slug']) ? $params['slug'] : '';
        $class = isset($params['class']) ? $params['class'] : '';
        
        $custHelper = new CustomHelper();
        $formHtml = $custHelper->parseFormData($slug,$class,$params);

        
        return $formHtml;
    }

    public static function themeCategoriesSection($params = '') {
        $Html = '';
        $data = [];
        $data['section_title'] = $params['section_title']??'';
        $data['section_brief'] = $params['section_brief']??'';
        $orderBy_name = $params['orderBy_name'] ?? 'sort_order';
        $orderBy = $params['orderBy']??'asc';
        $limit = $params['limit']??10;
        $query = ThemeCategories::where('status',1);
        if(isset($params['featured']) && $params['featured'] != '') {
            $featured = $params['featured']??1;
            $query->where('featured',$featured);
        }
        if(isset($params['theme_id']) && $params['theme_id'] != '') {
            $theme_id = $params['theme_id'];
            $theme_id_arr = [];
            if(stripos($theme_id, ',')) {
                $theme_id_arr = explode(',', $theme_id);
            } else if(stripos($theme_id, '-')) {
                $theme_id_arr = explode('-', $theme_id);
            }
            if(is_array($theme_id_arr)) {
                $query->whereIn('id',$theme_id_arr);
            }
        }
        $ThemeCategories = $query->orderBy($orderBy_name ,$orderBy)->limit($limit)->get();
        $result = [];
        if($ThemeCategories && $ThemeCategories->count() > 0) {
            $storage = Storage::disk('public');
            $theme_path = "themes/";
            foreach ($ThemeCategories as $key => $theme) {
                $theme_image = $theme->image;
                $themeSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
                if(!empty($theme_image) && $storage->exists($theme_path.$theme_image)){
                    $themeSrc = asset('/storage/'.$theme_path.$theme_image);
                }
                $result[] = array(
                    'title' => $theme->title,
                    'brief' => $theme->brief,
                    'slug' => $theme->slug,
                    'image_src' => $themeSrc,
                ); 
            }
        }

        $data['result'] = $result;
        $Html .= view(config('custom.theme').'.includes._theme_categories_section',$data)->render();
        return $Html;
    }

    public static function packageSection($params = '') {
        $Html = '';
        $data = [];
        $data['section_title'] = $params['section_title']??'';
        $data['section_brief'] = $params['section_brief']??'';
        $is_activity = $params['is_activity']??0;
        $limit = $params['limit']??10;
        $orderBy_name = $params['orderBy_name'] ?? 'sort_order';
        $orderBy = $params['orderBy'] ?? 'asc';
        $is_international = $params['is_international']??0;
        $flag = $params['flag']??'';
        $url = '';

        $query_data = [];
        $query = Package::with('packagePrices','packageDestination')->where('status',1);
        $query->where(function($query1){
            $query1->where('is_deleted',0);
            $query1->orWhereNull('is_deleted');
        });

        if(isset($params['featured']) && $params['featured'] != '') {
            $featured = $params['featured']??1;
            $query->where('featured',$featured);
        }

        if(isset($params['package_id']) && $params['package_id'] != '') {
            $package_id = $params['package_id'];
            $package_id_arr = [];
            if(stripos($package_id, ',')) {
                $package_id_arr = explode(',', $package_id);
            } else if(stripos($package_id, '-')) {
                $package_id_arr = explode('-', $package_id);
            }
            if(is_array($package_id_arr)) {
                $query->whereIn('id',$package_id_arr);
            }
        }

        if(isset($params['theme_category_slug']) && $params['theme_category_slug'] != '') {
            $theme_category_slug = $params['theme_category_slug']??'';
            $query->whereHas('packageCategories',function($q1) use($theme_category_slug) {
                $q1->where('slug',$theme_category_slug);
            });
            $url = route('tourCategoryDetail',[$theme_category_slug]);
        }

        if(isset($params['is_international']) && $params['is_international'] != '') {
            $is_international = $params['is_international']??0;
            $query->whereHas('packageDestination',function($q1) use($is_international) {
                $q1->where('is_international',$is_international);
            });
            $query_data['is_international'] = $is_international;
        }

        if(isset($params['tour_type']) && $params['tour_type'] != '') {
            $tour_type = $params['tour_type'];
            $query->where('tour_type',$tour_type);
            $query_data['tour_type'] = $tour_type;
        }

        if(isset($params['flag']) && $params['flag'] != '') {
            $flag = $params['flag'];
            $query->whereHas('packageFlags',function($q1) use($flag) {
                $q1->where('flag_id',$flag);
            });
            $query_data['flag'] = $flag;
        }

        if($is_activity == 1) {
            $query->where('is_activity',1);
        } else {
            $query->where('is_activity',0);
        }

        $packages = $query->orderBy($orderBy_name ,$orderBy)->limit($limit)->get();

        $result = [];    
        $data['packages'] = $packages;
        if(!empty($url)) {
            $view_all_url = $url;
        } else {
            if($is_activity == 1){
                $view_all_url = route('activityListing');
                if(isset($params['destination_slug']) && $params['destination_slug'] != '') {
                    $destination_slug = $params['destination_slug'];
                    $view_all_url = route('tourActivities',[$destination_slug]);
                }
            } else {
                $view_all_url = route('packageListing');
                if(isset($params['destination_slug']) && $params['destination_slug'] != '') {
                    $destination_slug = $params['destination_slug'];
                    $view_all_url = route('tourPackages',[$destination_slug]);
                }
            }
        }

        
        if(!empty($query_data)) {
            $view_all_url .= '?'.http_build_query($query_data);
        }
        $data['view_all_url'] = $view_all_url;

        $Html .= view(config('custom.theme').'.includes._packages_section',$data)->render();
        return $Html;
    }

    public static function destinationSection($params = '') {
        $Html ='';
        $data = [];
        $data['section_title'] = $params['section_title']??'';
        $data['section_brief'] = $params['section_brief']??'';
        $is_international = $params['is_international'] ?? 0;

        $destination =  Destination::where('is_city',0)->where('is_international',$is_international)->where('status',1)->where('featured',1)->orderBy('sort_order','asc')->paginate(7);

        $result = [];
        $data['destinations'] = $destination;

        $data['view_all_url'] = route('destinationListing');
        if($is_international == 1){

            $Html .= view(config('custom.theme').'.includes._international_destination_section',$data)->render();
        }else{
            $Html .= view(config('custom.theme').'.includes._destination_section',$data)->render();

        }
        return $Html;
    }

    public static function staticSection($params = '') {
        $Html = '';
        $data = [];
        $data['section_title'] = $params['section_title']??'';
        $data['section_brief'] = $params['section_brief']??'';
        $Html .=view(config('custom.theme').'.includes._static_section',$data)->render();
        return $Html;
    }


    public static function accommodationSection($params = '') {
        $Html = '';
        $data = [];
        $data['section_title'] = $params['section_title']??'';
        $data['section_brief'] = $params['section_brief']??'';
        $limit = $params['limit'] ?? 10;
        $orderBy_name = $params['orderBy_name'] ?? 'sort_order';
        $orderBy = $params['orderBy'] ?? 'asc';

        $query = Accommodation::with('accommodationDestination')->where('status',1)->where('featured',1);
        $accommodation = $query->orderBy($orderBy_name ,$orderBy)->limit($limit)->get();

        $result = [];
        $data['featuredAccommodations'] = $accommodation;

        $data['view_all_url'] = route('hotelListing');

        $Html .= view(config('custom.theme').'.includes._accommodation_section',$data)->render();
        return $Html;
    }

    public static function testimonialSection($params = '') {
        $Html = '';
        $data = [];
        $data['section_title'] = $params['section_title']??'';
        $data['section_brief'] = $params['section_brief']??'';
        $limit = $params['limit'] ?? 10;
        $orderBy_name = $params['orderBy_name'] ?? 'sort_order';
        $orderBy = $params['orderBy'] ?? 'asc';

        $query = Testimonial::with('testimonialImages')->where('featured',1)->where('status',1)->orderBy('sort_order','asc');
        $query->where(function($q1){
            $q1->where('is_deleted', 0);
            $q1->orWhereNull('is_deleted');
        });
        $data['testimonials'] = $query->limit(10)->get();
        $data['view_all_url'] = route('testimonialListing');
        $Html .= view(config('custom.theme').'.includes._testimonial_section',$data)->render();
        return $Html;
    }

    public static function blogSection($params = '') {
        $Html = '';
        $data = [];
        $data['section_title'] = $params['section_title']??'';
        $data['section_brief'] = $params['section_brief']??'';
        $limit = $params['limit'] ?? 10;
        $orderBy_name = $params['orderBy_name'] ?? 'sort_order';
        $orderBy = $params['orderBy'] ?? 'asc';

        $blogData = Blog::where('status',1)->where('featured',1)->orderBy('blog_date','desc')->orderBy('featured','desc');
        $blogData->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        $data['blogs'] = $blogData->limit(6)->get();
        $data['view_all_url'] = route('blogsListing');
        $Html .= view(config('custom.theme').'.includes._blogNews_section',$data)->render();
        return $Html;
    }

    public static function teamSection($params = '') {
        $Html ='';
        $data = [];
        $data['section_title'] = $params['section_title']??'';
        $data['section_brief'] = $params['section_brief']??'';
        $limit = $params['limit'] ?? 10;
        $orderBy_name = $params['orderBy_name'] ?? 'sort_order';
        $orderBy = $params['orderBy'] ?? 'asc';

        $teamData = TeamManagement::where('status',1)->where('featured',1)->orderBy('sort_order','asc')->orderBy('featured');
        $teamData->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        $data['teams'] = $teamData->limit(10)->get();
        $data['view_all_url'] = route('hotelListing');
        $Html .= view(config('custom.theme').'.includes._team_section',$data)->render();
        return $Html;
    }

    public static function cmsSection($params = '') {
        $Html = '';
        $data = [];
        $data['section_title'] = $params['section_title']??'';
        $data['section_brief'] = $params['section_brief']??'';
        $page_name = $params['cms_slug']??'';
        if(!empty($page_name)) {
            $select_cols = '*';
            $cms = CustomHelper::getCMSPage($page_name, $select_cols);
            if(isset($cms['id'])) {
                $data['cms'] = $cms;
                $Html .=view(config('custom.theme').'.includes._cms_section',$data)->render();
            }
        }
        return $Html;
    }   

    public static function total_user_by_roles($role_id){
         $total_user_by_roles = Admin::where('role_id',$role_id)->count();
         return $total_user_by_roles;
    }

    public static function getImageCategories($module = '') {
        $category_query = ImageCategory::orderBy('id', 'desc');
        if($module) {
            $category_query->where('module',$module);
        }
        $category_query->where(function($query){
            $query->where('parent_id', 0);
            $query->orWhereNull('parent_id');
        });
        $category_query = $category_query->get();
        return $category_query;
    }

    public static function enquiryMasterBreadcrumb($parent_id='',$breadcrumb = [], $level=0) {
        $level++;
        $routeName = Self::getAdminRouteName();
        if($parent_id) {
            $record = EnquiriesMaster::where('id',$parent_id)->first();
            if(!empty($record)) {
                if($level == 1) {
                    // $breadcrumb[] = '<li>'.$record->name.' ('.$record->Children->count().')</li>';
                    $breadcrumb[] = '<li>'.$record->name.'</li>';
                } else {
                    // $breadcrumb[] = '<li><a href="'.route($routeName.'.enquiries_master.index',['parent_id'=>$parent_id]).'">'.$record->name.' ('.$record->Children->count().')</a></li>';
                    $breadcrumb[] = '<li><a href="'.route($routeName.'.enquiries_master.index',['parent_id'=>$parent_id]).'">'.$record->name.'</a></li>';
                }
                $breadcrumb[] = Self::enquiryMasterBreadcrumb($record->parent_id,[],$level);
            }
        } else {
            // $count = EnquiriesMaster::where('parent_id',0)->count();
            if($level == 1) {
                // $breadcrumb[] = '<li>Enquiries Master ('.$count.')</li>';
                $breadcrumb[] = '<li>Enquiries Master</li>';
            }else {
                // $breadcrumb[] = '<li><a href="'.route($routeName.'.enquiries_master.index').'">Enquiries Master ('.$count.')</a></li>';
                $breadcrumb[] = '<li><a href="'.route($routeName.'.enquiries_master.index').'">Enquiries Master</a></li>';
            }

        }

        $breadcrumb = array_reverse($breadcrumb);
        $breadcrumb = implode('', $breadcrumb);
        return $breadcrumb;
    }

    public static function genrateEnquiryNoId($user_id=0,$type="E") {
        // $timestamp = time();
        // $enquiry_no_id = $type.$user_id.$timestamp.randomNumber(4);
        // $enquiry_no_id = $type.rand(00,99).randomNumber(4).randomNumber(4).randomNumber(4);
        $enquiry_no_id = self::random_strings();
        return $enquiry_no_id;
    }

    public static function genrateOrderNoId($user_id=0,$type="O") {
        // $timestamp = time();
        // $order_no_id = $type.$user_id.$timestamp.randomNumber(4);
        $order_no_id = self::random_strings();
        return $order_no_id;
    }

    public static function genrateFlightSearchId($type="IIAIR") {
        $search_id = $type.rand(00,99).'_'.self::random_strings();
        return $search_id;
    }

    // ========Use This code For uniqid=========== 
    public static function unique_code($limit)
    {
      return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    public static function getDestinationOptions($destinations=[], $selected_ids=[], $params=[], $options='', $level=0) {
        // prd($destinations->toArray());
        $show_packages_count = $params['show_packages_count']??'';
        $segment = $params['segment']??'packages';
        $show_locations = $params['show_locations']??'';
        $show_active = $params['show_active']??false;
        if(empty($destinations)) {
            $query = Destination::where('is_deleted',0)->where('is_city',0)->where('parent_id',0)->where('show',1)->with('childDestinations')->orderby('sort_order', 'asc')->orderby('name', 'asc');
            if($show_active) {
                $query->where('status',1);
            }
            $destinations = $query->get();
        }
        if($destinations) {
            if(!is_array($selected_ids)) {
                $selected_ids = explode(',', $selected_ids);
            }
            foreach($destinations as $destination) {
                $selected = '';
                if(!empty($selected_ids) && in_array($destination->id, $selected_ids)) {
                    $selected = 'selected';
                }
                $dash = str_repeat('--', $level);
                $name = $dash.$destination->name;
                if($show_packages_count) {
                    $packages_counts = Self::showDestination([$destination->id],$segment.'_count');
                    $packages_count = $packages_counts[$destination->id]??0;
                    $name = $name.' ('.$packages_count.')';
                }
                if($level > 0) {
                    $name = $name.' (Sub)';
                }
                if(!$destination->status) {
                    $name = $name.' (Inactive)';
                }
                $options .= '<option value="'.$destination->id.'" '.$selected.'>'.$name.'</option>';
                if($show_locations) {
                    if($show_active) {
                        $destinationLocations = $destination->destinationLocations->where('status',1)->where('show',1)??[];
                    } else {
                        $destinationLocations = $destination->destinationLocations->where('show',1)??[];
                    }
                    if(!empty($destinationLocations)) {
                        $new_level = $level+1;
                        foreach($destinationLocations as $location) {
                            $selected = '';
                            if(!empty($selected_ids) && in_array($location->id, $selected_ids)) {
                                $selected = 'selected';
                            }
                            $dash = str_repeat('--', $new_level);
                            $name = $dash.$location->name.' (Place)';
                            if(!$location->status) {
                                $name = $name.' (Inactive)';
                            }
                            $options .= '<option value="'.$location->id.'" '.$selected.'>'.$name.'</option>';
                        }
                    }
                }
                
                if($show_active) {
                    $childDestinations = $destination->childDestinations->where('status',1)->where('show',1)??[];
                } else {
                    $childDestinations = $destination->childDestinations->where('show',1)??[];
                }
                if(!empty($childDestinations) && count($childDestinations) > 0) {
                    $new_level = $level+1;
                    foreach($childDestinations as $subDestinations) {
                        $selected = '';
                        if(!empty($selected_ids) && in_array($subDestinations->id, $selected_ids)) {
                            $selected = 'selected';
                        }
                        $dash = str_repeat('--', $new_level);
                        $name = $dash.$subDestinations->name;
                        if($show_packages_count) {
                            $packages_counts = Self::showDestination([$subDestinations->id],$segment.'_count');
                            $packages_count = $packages_counts[$subDestinations->id]??0;
                            $name = $name.' ('.$packages_count.')';
                        }
                        $name = $name.' (Sub)';
                        if(!$subDestinations->status) {
                            $name = $name.' (Inactive)';
                        }
                        $options .= '<option value="'.$subDestinations->id.'" '.$selected.'>'.$name.'</option>';
                        if(count($subDestinations->destinations)) {
                            $new_level2 = $new_level+1;
                            $options = Self::getDestinationOptions($subDestinations->destinations, $selected_ids, $params, $options, $new_level2);
                        }
                    }
                }
            }
        }
        return $options;
    }

    public static function showEnquiryForType($enquiry_for) {
        $enq_for_types_arr = [];
        if(!empty($enquiry_for)) {
            $enq_for_types = config('custom.enq_for_types');
            foreach($enquiry_for as $enquiry_for_id) {
                $enq_for_val = $enq_for_types[$enquiry_for_id]??'';
                if($enq_for_val) {
                    $enq_for_types_arr[] = $enq_for_val;
                }
            }
        }
        return $enq_for_types_arr;
    }

    public static function showDestination($destination_ids=[],$field='name') {
        $destination_values = [];
        $destinations = config('custom.destinations');
        if(empty($destinations)) {
            $destinations = \Cache::rememberForever("destinations", function () {
                $result = Destination::where('is_city',0)->get();
                $destinations_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $packages_count = $row->destinationPackagesCount()??0;
                        $activity_count = $row->destinationActivityCount()??0;
                        $destinations_arr[$row->id] = (object)[
                            'id' => $row->id,
                            'name' => $row->name,
                            'packages_count' => $packages_count,
                            'activity_count' => $activity_count,
                        ];
                    }
                }
                return $destinations_arr;
            });
            config(['custom.destinations'=>$destinations]);
        }

        if(!empty($destination_ids)) {
            foreach($destination_ids as $destination_id) {
                if(isset($destinations[$destination_id])) {
                    $value = $destinations[$destination_id]->$field??'';
                    if($value) {
                        $destination_values[$destination_id] = $value;
                    }
                }
            }
        }
        return $destination_values;
    }

    public static function showAccommodation($accommodation_ids=[]) {
        $accommodation_values = [];
        $accommodations = config('custom.accommodations');
        if(empty($accommodations)) {
            $accommodations = \Cache::rememberForever("accommodations", function () {
                $result = DB::table('accommodations')->get();
                $accommodations_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $accommodations_arr[$row->id] = (object)[
                            'id' => $row->id,
                            'name' => $row->name,
                        ];
                    }
                }
                return $accommodations_arr;
            });
            config(['custom.accommodations'=>$accommodations]);
        }

        if(!empty($accommodation_ids)) {
            if(is_array($accommodation_ids)) {
                foreach($accommodation_ids as $accommodation_id) {
                    if(isset($accommodations[$accommodation_id])) {
                        $name = $accommodations[$accommodation_id]->name??'';
                        if($name) {
                            $accommodation_values[$accommodation_id] = $name;
                        }
                    }
                }
            } else {
                $accommodation_values = $accommodations[$accommodation_ids]->name??'';
            }
        }
        return $accommodation_values;
    }

    public static function showAccommodationType($accommodation_type_ids=[]) {
        $accommodation_type_values = [];
        $accommodation_types = config('custom.accommodation_types');
        if(empty($accommodation_types)) {
            $accommodation_types = \Cache::rememberForever("accommodation_types", function () {
                $result = DB::table('accommodation_type')->get();
                $accommodation_types_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $accommodation_types_arr[$row->id] = (object)[
                            'id' => $row->id,
                            'name' => $row->title,
                        ];
                    }
                }
                return $accommodation_types_arr;
            });
            config(['custom.accommodation_types'=>$accommodation_types]);
        }

        if(!empty($accommodation_type_ids)) {
            if(is_array($accommodation_type_ids)) {
                foreach($accommodation_type_ids as $accommodation_id) {
                    if(isset($accommodation_types[$accommodation_id])) {
                        $name = $accommodation_types[$accommodation_id]->name??'';
                        if($name) {
                            $accommodation_type_values[$accommodation_id] = $name;
                        }
                    }
                }
            } else {
                $accommodation_type_values = $accommodation_types[$accommodation_type_ids]->name??'';
            }
        }
        return $accommodation_type_values;
    }

    public static function showEnquiryMaster($id='') {
        $enquiryMasterName = '';
        if(!empty($id)){
            $enquiries_master = config('custom.enquiries_master');
            if(empty($enquiries_master)) {
                $enquiries_master = \Cache::rememberForever("enquiries_master", function () {
                    $result = DB::table('enquiries_master')->get();
                    $enquiries_master_arr = [];
                    if(!empty($result)) {
                        foreach($result as $row) {
                            $enquiries_master_arr[$row->id] = (object)[
                                'id' => $row->id,
                                'name' => $row->name,
                            ];
                        }
                    }
                    return $enquiries_master_arr;
                });
                config(['custom.enquiries_master'=>$enquiries_master]);
            }
            if(isset($enquiries_master[$id])) {
                $enquiryMasterName = isset($enquiries_master[$id]->name) ? $enquiries_master[$id]->name : '';
            }
        }
        return $enquiryMasterName;
    }


    public static function showAdmin($id,$column_name='name') {
        $user_data = [];
        $name = '';
        $backend_users = config('custom.backend_users');
        if($backend_users) {
            $user_data = !empty($backend_users[$id])?$backend_users[$id]:'';
            if($user_data) {
                if($column_name == 'all') {
                    $name = (object) $user_data;
                } else {
                    $name = !empty($user_data[$column_name])?$user_data[$column_name]:'';
                }
            }
        } else {
            $backend_users = \Cache::rememberForever("backend_users", function() {
                $total_user = Admin::all();
                $backend_users = [];
                foreach($total_user as $user) {
                    $backend_users[$user->id] = array(
                        'id'=>$user->id,
                        'name'=>$user->name,
                        'email'=>$user->email,
                        'status'=>$user->status,
                    );
                }
                return $backend_users;
            });
            config(['custom.backend_users' =>$backend_users]);
            $user_data = !empty($backend_users[$id])?$backend_users[$id]:'';
            if($user_data) {
                if($column_name == 'all') {
                    $name = (object) $user_data;
                } else {
                    $name = !empty($user_data[$column_name])?$user_data[$column_name]:'';
                }
            }
        }

        if($id == 'all') {
            $backend_users = array_filter($backend_users, function ($value) { 
                return ($value["is_deleted"] == 0); 
            });
            return $backend_users;
        } else {
            return $name;
        }
    }

    public static function cache_flush($key='') {
        if($key) {
            //\Cache::forget("config-*");
            \Cache::forget($key);
            \Cache::forget($key.'-*');
            if(config("custom.CACHE_DRIVER")=='redis') {
                // $redis = \Cache::getRedis();
                $redis = Redis::connection('cache');
                $keys = $redis->keys("*$key*");
                if(!empty($keys)) {
                    foreach($keys as $key) {
                        $key_arr = explode(':', $key);
                        if(isset($key_arr[1])) {
                            \Cache::forget($key_arr[1]);
                        }
                    }
                }
            }
        } else {
            //Stopped this call, as all login user, if session driver is redis get logout from the system.
            // \Cache::flush();
        }
    }


    public static function destinationsBreadcrumb($parent_id='',$breadcrumb = [], $level=0) {
        $level++;
        $routeName = Self::getAdminRouteName();
        if($parent_id) {
            $record = Destination::where('id',$parent_id)->first();
            if(!empty($record)) {
                if($level == 1) {
                    $breadcrumb[] = '<li>'.$record->name.'</li>';
                } else {
                    $breadcrumb[] = '<li><a href="'.route($routeName.'.destinations.index',['parent_id'=>$parent_id]).'">'.$record->name.'</a></li>';
                }
                $breadcrumb[] = Self::destinationsBreadcrumb($record->parent_id,[],$level);
            }
        } else {
            if($level == 1) {
                $breadcrumb[] = '<li>Destinations</li>';
            }else {
                $breadcrumb[] = '<li><a href="'.route($routeName.'.destinations.index').'">Destinations</a></li>';
            }
        }
        $breadcrumb = array_reverse($breadcrumb);
        $breadcrumb = implode('', $breadcrumb);
        return $breadcrumb;
    }

    public static function getSelectedAccommodation($days_arr=[]) {

        // prd($days_arr);
        $accommodations = [];
        if($days_arr){
            foreach ($days_arr as $key => $days_val) {
                $iti_data = Itenary::where('id',$days_val)->first();
                $accommodations = CustomHelper::getItineraryAccommodations($iti_data);
                // $total_accommodations[] = $accommodations;

            }
        }

        return $accommodations;
    }

    public static function getitenarydata($itenary_id) {
        $iti_data = [];
        $iti_data = Itenary::where('id',$itenary_id)->first();
        return $iti_data;
    }
   public static function getPackageSlots($package_id,$price_id) {
        $data = [];
        $data = PackageSlot::where('package_id',$package_id)->where('price_id',$price_id)->where('status',1)->get();
        return $data;
    }
    
    public static function getAccommodationdata($accommodation) {
        $accommodation_data = [];
        $accommodation_data = Accommodation::where('id',$accommodation)->first();
        return $accommodation_data;
    }
    
    public static function getItineraryAccommodations($itinerary=[]) {
        $accommodations = [];
        if(!empty($itinerary) && isset($itinerary->package_id)) {
          $location_id = $itinerary->location_id??'';
            $package = $itinerary->Package??[];
            if($package) {
            $destination_id = $package->destination_id??'';
                $related_destinations = $package->related_destinations ? json_decode($package->related_destinations):[];

                // prd($related_destinations);
                if($destination_id) {
                    $query = Accommodation::where(function($query) use($destination_id,$related_destinations,$location_id){
                        $query->where('destination_id', $destination_id);
                        $query->orWhereIn('destination_id',$related_destinations);
                    if($location_id) {
                        $query->orWhere('destination_id',$location_id);
                        $query->orWhereHas('AccommodationLocation',function($q1) use($location_id) {
                            $q1->where('destination_location_id',$location_id);
                        });
                    }
                    });

                    $accommodations = $query->get();

                }
            }
        }
        return $accommodations;
    }

    
    public static function showPackageInclusions($package_inclusion_ids=[]) {
        // prd($package_inclusion_ids);
        $package_inclusion_values = [];
        $package_inclusions = config('custom.package_inclusions');
        if(empty($package_inclusions)) {
            $package_inclusions = \Cache::rememberForever("package_inclusions", function () {
                $result = DB::table('package_inclusion_lists')->get();
                $package_inclusions_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $package_inclusions_arr[$row->id] = (object)[
                            'id' => $row->id,
                            'title' => $row->title,
                        ];
                    }
                }
                return $package_inclusions_arr;
            });
            config(['custom.package_inclusions'=>$package_inclusions]);
        }
        // prd($package_inclusions);

        if(!empty($package_inclusion_ids)) {
            if(is_array($package_inclusion_ids)) {
                foreach($package_inclusion_ids as $package_inclusion_id) {
                    if(isset($package_inclusions[$package_inclusion_id])) {
                        $title = $package_inclusions[$package_inclusion_id]->title??'';
                        if($title) {
                            $slug = Self::slugify($title);
                            $package_inclusion_values[$slug] = $title;
                        }
                    }
                }
            } else {
                // $package_inclusion_values = Self::slugify($package_inclusions[$package_inclusion_ids]->title)??'';
                $package_inclusion_values = $package_inclusions[$package_inclusion_ids]->title??'';
            }
        }
        return $package_inclusion_values;
    }
    
    public static function showPackageInclusionsOther($package_inclusion_ids=[]) {
        $package_inclusion_values = [];
        $package_inclusions = config('custom.package_inclusions_other');
        if(empty($package_inclusions)) {
            $package_inclusions = \Cache::rememberForever("package_inclusions_other", function () {
                $result = DB::table('package_inclusion_lists')->get();
                $package_inclusions_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $package_inclusions_arr[$row->id] = (object)[
                            'id' => $row->id,
                            'title' => $row->title,
                            'image' => $row->image,
                        ];
                    }
                }
                return $package_inclusions_arr;
            });
            config(['custom.package_inclusions_other'=>$package_inclusions]);
        }
        if(!empty($package_inclusion_ids)) {
            if(is_array($package_inclusion_ids)) {
                foreach($package_inclusion_ids as $package_inclusion_id) {
                    if(isset($package_inclusions[$package_inclusion_id])) {
                        $title = $package_inclusions[$package_inclusion_id]->title??'';
                        $image = $package_inclusions[$package_inclusion_id]->image??'';
                        if($title) {
                            $slug = Self::slugify($title);
                            if($image) {
                                $package_inclusion_values[$image] = $title;
                            } else {
                                $package_inclusion_values[] = $title;
                            }
                        }
                    }
                }
            } else {
                $package_inclusion_values = $package_inclusions[$package_inclusion_ids]->title??'';
            }
        }
        return $package_inclusion_values;
    }

    public static function slugify($text = '',$separater='-') {
        // echo $text; die;
        // replace non letter or digits by -
        $text = preg_replace ( '~[^\pL\d]+~u', $separater, $text );

        // transliterate
        $text = iconv ( 'utf-8', 'us-ascii//TRANSLIT', $text );

        // remove unwanted characters
        $text = preg_replace ( '~[^-\w]+~', '', $text );

        // trim
        $text = trim ( $text, $separater );

        // remove duplicate -
        $text = preg_replace ( '~-+~', $separater, $text );

        // lowercase
        $text = strtolower ( $text );
        // echo $text; die;
        if (empty ( $text )) {
            // return 'n-a';
        }
        // echo $text; die;
        $slug = $text;
        // echo $slug; die;
        return $slug;
    }

    
    public static function setModuleDefaultImage($module='',$module_id='') {
        if(!empty($module) && !empty($module_id)) {
            $image = CommonImage::where('tbl_name',$module)->where('tbl_id',$module_id)->where('category','gallery')->orderBy('is_default','desc')->orderBy('sort_order','asc')->orderBy('created_at','desc')->first();
            // prd($image->toArray());
            if($image) {
                $default_image = $image->name;
            } else {
                $default_image = '';
            }
            if($module == 'packages') {
                $record = Package::find($module_id);
                if($record) {
                    $record->image = $default_image;
                    $record->save();
                    if(!empty($image) && empty($image->is_default)) {
                        $image->is_default = 1;
                        $image->save();
                    }
                }
            } else if($module == 'destinations') {
                $record = Destination::find($module_id);
                if($record) {
                    $record->image = $default_image;
                    $record->save();
                    if(!empty($image) && empty($image->is_default)) {
                        $image->is_default = 1;
                        $image->save();
                    }
                }
            } else if($module == 'accommodations') {
                $record = Accommodation::find($module_id);
                if($record) {
                    $record->image = $default_image;
                    $record->save();
                    if(!empty($image) && empty($image->is_default)) {
                        $image->is_default = 1;
                        $image->save();
                    }
                }
            } else if($module == 'cab_route') {
                $record = CabRoute::find($module_id);
                if($record) {
                    $record->image = $default_image;
                    $record->save();
                    if(!empty($image) && empty($image->is_default)) {
                        $image->is_default = 1;
                        $image->save();
                    }
                }
            }

        }
    }


    public static function getSeoModules($identifier='',$params=[]) {
        $record = [];
        $seo_meta_tags = config('custom.seo_meta_tags');
        if(empty($seo_meta_tags)) {
            $seo_meta_tags = \Cache::rememberForever("seo_meta_tags", function () {
                $result = DB::table('seo_meta_tags')->get();
                $seo_meta_tags_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $seo_meta_tags_arr[$row->identifier] = (object)[
                            'id' => $row->id,
                            'page_title' => $row->page_title,
                            'page_brief' => $row->page_brief,
                            'page_description' => $row->page_description,
                            'page_url' => $row->page_url,
                            'page_detail_url' => $row->page_detail_url,
                            'identifier' => $row->identifier,
                            'meta_title' => $row->meta_title,
                            'admin_email' => $row->admin_email,
                            'admin_phone' => $row->admin_phone,
                            'module_type' => $row->module_type,
                            'online_booking' => $row->online_booking,
                            'meta_keyword' => $row->meta_keyword,
                            'meta_description' => $row->meta_description,
                            'other_meta_tag' => $row->other_meta_tag,
                            'image' => $row->image,
                            'status' => $row->status,
                        ];
                    }
                }
                return $seo_meta_tags_arr;
            });
            config(['custom.seo_meta_tags'=>$seo_meta_tags]);
        }

        if(!empty($identifier)) {
            if(isset($seo_meta_tags[$identifier])) {
                if(isset($params['size']) && $params['size'] == 'box') {
                    $record = (object)[
                        'page_url' => $seo_meta_tags[$identifier]->page_url,
                        'page_detail_url' => $seo_meta_tags[$identifier]->page_detail_url,
                    ];
                } else {
                    $record = $seo_meta_tags[$identifier];
                }
            } else {
                switch ($identifier) {
                    case 'package_listing':
                            $record = (object)[
                                'identifier' => 'package_listing',
                                'page_url' => 'packages',
                                'page_detail_url' => 'package/',
                            ];
                        break;                    
                    default:
                        # code...
                        break;
                }
            }
        }
        return $record;
    }

    public static function isOnlineBooking($module='') {
        // prd($module);
        $flight = self::getSeoModules('flight');
        $cab = self::getSeoModules('cab');
        $package_listing = self::getSeoModules('package_listing');
        $activity_listing = self::getSeoModules('activity_listing');
        $hotel_listing = self::getSeoModules('hotel_listing');
        $bike = self::getSeoModules('rental');
            // prd($cab);
        $online_booking = 0;
        $module_booking = 0;
        if($flight && $flight->online_booking==1) {
            $online_booking++;
            if($module == 'flight') {
                $module_booking = 1;
            }
        } if($cab && $cab->online_booking==1) {
            $online_booking++;
            if($module == 'cab') {
                $module_booking = 1;
            }
        } if($package_listing && $package_listing->online_booking==1) {
            $online_booking++;
            if($module == 'package_listing') {
                $module_booking = 1;
            }
        } if($activity_listing && $activity_listing->online_booking==1) {
            $online_booking++;
            if($module == 'activity_listing') {
                $module_booking = 1;
            }
        } if($hotel_listing && $hotel_listing->online_booking==1) {
            $online_booking++;
            if($module == 'hotel_listing') {
                $module_booking = 1;
            }
        } if($bike && $bike->online_booking==1) {
            $online_booking++;
            if($module == 'rental') {
                $module_booking = 1;
            }
        }
        if(!empty($module)) {
            $module_allowed = CustomHelper::isAllowedModule($module);
            if($module_allowed && $module_booking == 1) {
                $online_booking = 1;
            } else {
                $online_booking = 0;
            }
        }
        return $online_booking;
    }


    public static function CreateLoginToken($user_id) {
        if($user_id) {
            $expire_hours = Self::WebsiteSettings('BACKEND_LOGIN_DEVICE_RECOGNITION_IN_HOURS');
            if($expire_hours == '-1') {

            } else if(empty($expire_hours)) {

            } else {
                $create_date = date('Y-m-d H:i:s');
                $expire_date = date('Y-m-d H:i:s', strtotime('+'.$expire_hours.' hours'));
                $token = uniqid();
                //prd($expire_hours);
                $minutes = 60*$expire_hours;
                //prd($minutes);
                $cookie_name = md5('balogin_token'.$user_id);
                Cookie::queue(Cookie::make($cookie_name, $token, $minutes));
                $admin_data = array(
                    'user_id' => $user_id,
                    'token' => $token,
                    'create_date' => $create_date,
                    'expire_date' => $expire_date,
                );
                $isdata =  LoginToken::create($admin_data);
            }
        }
    }

    public static function updatePackageDayNight($package_id) {
        $output = '';
        if(!empty($package_id)) {
            // config()->set('database.connections.mysql.strict', false);
            $data = Itenary::select(DB::raw("count(location_id) as days"),'location_id')->where('package_id',$package_id)->groupBy('location_id')->orderby('day','asc')->get();
            // prd($data->toArray());
            $days = 0;
            $nights = '';
            $days_nights_city = array();
            if(!empty($data))
            {
                foreach($data as $itin)
                {
                    $destination_name = '';
                    if(!empty($itin->location_id))
                    {
                        $destination_name = $itin->Location->name??'';
                    }
                    // prd($destination_name);
                    $days += $itin->days;
                    $days_nights_city[$destination_name] = $itin->days;
                }
            }

            $update_data = array(
                'days' => $days,
                'nights' => $days-1,
                'days_nights_city' => serialize($days_nights_city)
            );
            // prd($update_data);
            Package::where('id',$package_id)->update($update_data);
        }
        return $output;
    }


//===========

    public static function getBank_details()
    {
        $merchant =  DB::table('tbl_merchant')->where('payment_type','BankDetails')->first();

        $bank_detail = $merchant->description ?? '';

        return $bank_detail;
    }


    public static function budgetDataArr($budget='') {

        if(config('custom.theme_name') == 'viaggindia'){
            $data = ['0-100'=>'0 - 100','100-1000'=>'100 - 1000','1000-10_000'=>'Più di 1000'];
        }
        if(config('custom.theme_name') == 'holidaybooking'){
            $data = ['0-5000'=>'0 - 5,000','5_000-10_000'=>'5,000 - 10,000','10_000-40_000'=>'10,000 - 40,000','40_000-1_00_000'=>'40,000 - 1,00,000','1_00_000-10_00_000'=>'Above 1,00,000'];
        }else{
           $data = ['100-10_000'=>'100 - 10,000','10_000-40_000'=>'10,000 - 40,000','40_000-1_00_000'=>'40,000 - 1,00,000','1_00_000-10_00_000'=>'Above 1,00,000'];
       }        
        if($budget!='')
        {
          return $data[$budget]??'';
        }
        
        return $data;
    }

//============


public static function showPackage($package_ids=[],$field='title') {
        $package_values = [];
        $packages = config('custom.packages');
        if(empty($packages)) {
            $packages = \Cache::rememberForever("packages", function () {
                $result = Package::get();
                $packages_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $packages_arr[$row->id] = (object)[
                            'id' => $row->id,
                            'title' => $row->title,
                        ];
                    }
                }
                return $packages_arr;
            });
            config(['custom.packages'=>$packages]);
        }

        if(!empty($package_ids)) {
            if(is_array($package_ids)) {
                foreach($package_ids as $package_id) {
                    if(isset($packages[$package_id])) {
                        $value = $packages[$package_id]->$field??'';
                        if($value) {
                            $package_values[$package_id] = $value;
                        }
                    }
                }
            } else {
                $package_values = $packages[$package_ids]->$field??'';
            }
        }
        return $package_values;
    }


    public static function getSMTPDetails($key) {
        $value = '';
        if($key!='')
        {
             if(Schema::hasTable('smtp_settings')){
            $getData = SmtpSetting::whereNotNull('id')->where('id', '=', '1')->select($key)->first();
            $value = isset($getData) && !empty($getData) ? $getData[$key] : '';
        }
        }
        
        return $value ;
    }


    public static function getPackagePriceFrom($package_id,$price_id='') {
        $price_value = 0;
        $query = PackagePriceInfo::where('package_id',$package_id);
        if($price_id) {
            $query->where('id',$price_id);
        }
        $query->orderBy('is_default','DESC');
        $query->orderBy('sort_order','ASC');
        $package_price = $query->first();
        // prd($package_price->toArray());
        // prd($package_price);
        if($package_price && $package_price->show_choices_record != '') {
            $category_choices_record = json_decode($package_price->category_choices_record);
            // prd($category_choices_record);
            $show_choices_record = json_decode($package_price->show_choices_record);
            // prd($show_choices_record);
            if(!empty($show_choices_record)) {
                foreach($show_choices_record as $key => $val) {
                    if(isset($val->default) && $val->default != $key.'_null') {
                        // prd($val->default);
                        $val_arr = explode('_', $val->default);
                        $elem = $val_arr[1];
                        // prd($elem);
                        // prd($category_choices_record->$key);
                        if(isset($category_choices_record->$key)) {
                            $price_arr = (array)$category_choices_record->$key;
                            if(isset($price_arr[$elem])) {
                                $price_value = $price_arr[$elem];
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $price_value;
    }

    public static function getPackagePriceTotal($package_id,$price_id='') {
        $totalPrice = 0;
        $query = PackagePriceInfo::where('package_id',$package_id);
        if($price_id) {
            $query->where('id',$price_id);
        }
        $query->orderBy('is_default','DESC');
        $query->orderBy('sort_order','ASC');
        $package_price = $query->first();
        // prd($package_price->toArray());
        // prd($package_price);
        if($package_price && $package_price->show_choices_record != '') {
            $category_choices_record = json_decode($package_price->category_choices_record);
            // prd($category_choices_record);
            $show_choices_record = json_decode($package_price->show_choices_record);
            // prd($show_choices_record);
            if(!empty($show_choices_record)) {
                foreach($show_choices_record as $key => $val) {
                    if(isset($val->default) && $val->default != $key.'_null') {
                        // prd($val->default);
                        $val_arr = explode('_', $val->default);
                        $elem = $val_arr[1];
                        // prd($elem);
                        // prd($category_choices_record->$key);
                        if(isset($category_choices_record->$key)) {
                            $price_arr = (array)$category_choices_record->$key;
                            // prd($price_arr);
                            if(isset($price_arr[$elem])) {
                                $price_value = $price_arr[$elem];
                                $totalPrice += $price_value*(int)$elem;
                            }
                        }
                    }
                }
            }
        }
        return $totalPrice;
    }

    public static function getDestinationTypeInfo($destination_id,$type) {
        $destinationinfo = [];
        if($destination_id && $type) {
            $destinationinfo = DestinationInfo::where(["destination_id"=>$destination_id,"type"=>$type,"status"=>1])->orderBy("sort_order", "ASC")->get();
        }
        return $destinationinfo;
    }

    public static function getLocationName($location_id) {
        $location_name = '';
        if($location_id) {
            $location_data = Destination::select('name')->where("id",$location_id)->first();
            $location_name = $location_data->name ?? '';
        }
        return $location_name;
    }

  public static function getPackageInventory($package_id,$price_id) {
        $PackageInventory = [];
        if($package_id && $price_id) {
            $cur_date = date('Y-m-d');

            /*$package_slot = PackageSlot::where("package_id",$package_id)->where("price_id",$price_id)->where('status',1)->orderBy('id','desc')->first();
            $duration_type = '';
            if($package_slot) {
                $duration_type = $package_slot->duration_type??'';
            }*/
            // prd($duration_type);

            $query = PackageInventory::where(function($q1){
                $q1->whereHas('packageSlot',function($q2){
                    $q2->where('status',1);
                });
                $q1->orWhereNull('slot_id');
            });
            $query->where("status",1);
            $query->where("package_id",$package_id);
            $query->where("price_id",$price_id);
            $query->where('inventory','>',0);
            $query->where(function($q1) use($cur_date) {
                $q1->where(function($q2) use($cur_date) {
                    $q2->whereHas('packageSlot',function($q3){
                        $q3->where('status',1);
                    });
                    $q2->whereDate('trip_date','>=',$cur_date);
                });
                $q1->orWhere(function($q2) use($cur_date) {
                    $q2->whereDate('trip_date','>',$cur_date);
                });
            });
            $PackageInventory = $query->get();
            // prd($PackageInventory->toArray());
        }
        return $PackageInventory;
    }

  public static function AvailblePackageInventory($package_id,$price_id,$date,$slot_id = '') {
        $PackageInventory = [];
        if($package_id && $price_id) {
            $cur_date = date('Y-m-d');           
            $query = PackageInventory::where("status",1);
            $query->where("package_id",$package_id);
            $query->where("price_id",$price_id);
            $query->where('inventory','>',0);
            if($slot_id){
              $query->where('slot_id',$slot_id);
            }
            $query->whereDate('trip_date',$date);
            $PackageInventory = $query->first();
        }
            // prd($PackageInventory->toArray());
        return $PackageInventory;
    }

  public static function AvailbleCabInventory($cab_route_group_id,$cab_id,$date){
        $CabInventory = [];
        if(isset($cab_id) && isset($cab_route_group_id)) {

            $cur_date = date('Y-m-d');           
            $query = CabRouteInventory::where("status",1);
            $query->where("cab_id",$cab_id);
            $query->where("cab_route_group_id",$cab_route_group_id);
            $query->whereDate('trip_date',$date);
            $CabInventory = $query->first();
            
        }
        return $CabInventory;
    }

    public static function userFavourite(){

        if(!empty(Auth::check())) {
            $userId = Auth::user()->id;
            $package_fab =DB::table('users_favourite_packages')->where('user_id',$userId)->get()->keyBy('package_id')->toArray();
            $package_fab = $package_fab;
        } else {
            $package_fab =array();
        }
        return $package_fab;;
    }
    

    // Add Custom Message in Sentry
    
    public static function captureSentryMessage($message = '',$level='info') {
        if (app()->bound('sentry')) {
            \Sentry\withScope(function (\Sentry\State\Scope $scope) use($message,$level): void {
                switch ($level) {
                    case 'debug':
                        $scope->setLevel(\Sentry\Severity::debug());
                        break;
                    case 'info':
                        $scope->setLevel(\Sentry\Severity::info());
                        break;
                    case 'warning':
                        $scope->setLevel(\Sentry\Severity::warning());
                        break;
                    case 'error':
                        $scope->setLevel(\Sentry\Severity::error());
                        break;
                    case 'fatal':
                        $scope->setLevel(\Sentry\Severity::fatal());
                        break;
                    default:
                        $scope->setLevel(\Sentry\Severity::error());
                        break;
                }               
                \Sentry\captureMessage($message);
            });
        }
    }

    public static function airBaggageDesc($tripInfos,$code='',$field='desc') {
        $desc = $code;
        if(!empty($tripInfos)) {
            foreach($tripInfos['tripInfos'] as $trip) {
                foreach($trip['sI'] as $flight) {
                    if(isset($flight['ssrInfo']) && isset($flight['ssrInfo']['BAGGAGE'])) {
                        foreach($flight['ssrInfo']['BAGGAGE'] as $row) {
                            if($row['code'] == $code) {
                                $desc = $row[$field]??'';
                            }
                        }
                    }
                }
            }
        }
        return $desc;
    }

    public static function airMealDesc($tripInfos,$code='',$field='desc') {
        $desc = $code;
        if(!empty($tripInfos)) {
            foreach($tripInfos['tripInfos'] as $trip) {
                foreach($trip['sI'] as $flight) {
                    if(isset($flight['ssrInfo']) && isset($flight['ssrInfo']['MEAL'])) {
                        foreach($flight['ssrInfo']['MEAL'] as $row) {
                            if($row['code'] == $code) {
                                $desc = $row[$field]??'';
                            }
                        }
                    }
                }
            }
        }
        return $desc;
    }

    public static function getDiscountData($module_name='', $discount_category_id='', $package_price=0, $group_id=0, $module_record_id='') {
        if(empty($group_id)) {
            $AgentGroup = AgentGroup::select('id')->where('is_default',1)->first();
            $group_id = ($AgentGroup)?$AgentGroup->id :0;
        }
        $discount_result = [];
        $discount_amount = 0;
        if($group_id) {
            if($discount_category_id) {
                if($discount_category_id == '-1' && $module_record_id) {
                    $discount_result = CustomModuleRecordDiscount::where('module_name',$module_name)->where('module_record_id',$module_record_id)->where('agent_group_id',$group_id)->first();
                } else {
                    $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('module_discount_type_id',$discount_category_id)->where('agent_group_id',$group_id)->first();
                }
            } else {
                $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('agent_group_id',$group_id)->where('is_default',1)->first();
            }
        } else {
            if($discount_category_id) {
                $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('module_discount_type_id',$discount_category_id)->first();
            } else {
                $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();    
            }
        }

        if($discount_result && $discount_result->id) {
            $discount_result = $discount_result;
        } else if($group_id != '-1') {
            $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->orderBy('id','asc')->first();
        }

        if($discount_result && $discount_result->id) {
            $discount_result = $discount_result;
        } else if($group_id != '-1') {
            $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->orderBy('id','asc')->first();
        }
        return $discount_result ;
    }

    public static function getDiscountPrice($module_name='', $discount_category_id='', $package_price=0, $group_id='', $module_record_id='',$total_pax=0) {
        if(empty($group_id)) {
            $AgentGroup = AgentGroup::select('id')->where('is_default',1)->first();
            $group_id = ($AgentGroup)?$AgentGroup->id:0;
        }
        $discount_amount = 0;
        if($discount_category_id !== 0) {
            if($group_id) {
                if($discount_category_id) {
                    if($discount_category_id == '-1' && $module_record_id) {
                        $discount_result = CustomModuleRecordDiscount::where('module_name',$module_name)->where('module_record_id',$module_record_id)->where('agent_group_id',$group_id)->first();
                    } else {
                        $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('module_discount_type_id',$discount_category_id)->where('agent_group_id',$group_id)->first();
                        if($discount_result && $discount_result->id) {
                            // Ok
                        } else {
                            $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('agent_group_id',$group_id)->where('is_default',1)->first();
                        }
                    }
                } else {
                    $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('agent_group_id',$group_id)->where('is_default',1)->first();
                }
            } else {
                if($discount_category_id) {
                    $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('module_discount_type_id',$discount_category_id)->first();
                } else {
                    $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();    
                }
            }

            if($discount_result && $discount_result->id) {
                $discount_result = $discount_result;
            } else if($group_id != '-1') {
                $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->orderBy('id','asc')->first();
            }

            if($discount_result && $discount_result->id) {
                $discount_result = $discount_result;
            } else if($group_id != '-1') {
                $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->orderBy('id','asc')->first();
            }
            $discount_type = $discount_result->discount_type??'';
            $discount_value = $discount_result->discount_value??'';
            if($discount_type && $discount_type == 'flat') {
                $discount_amount = $discount_value;
                if($total_pax && ($module_name=='package_listing' || $module_name=='activity_listing' || $module_name=='hotel_listing')) {
                    $discount_amount = $discount_amount * $total_pax;
                }
            } elseif($discount_type && $discount_type == 'percentage') {
                $discount_amount = ($package_price*$discount_value)/100 ;
            }
            if($discount_amount >= $package_price) {
                $discount_amount = 0;
            }
        }
        return $discount_amount ;
    }

    public static function getDiscountType($module_name='', $discount_category_id='', $package_price=0, $group_id='', $module_record_id='') {
        if(empty($group_id)) {
            $AgentGroup = AgentGroup::select('id')->where('is_default',1)->first();
            $group_id = ($AgentGroup)?$AgentGroup->id:0;
        }
        $discount_type_label = '';
        if($discount_category_id !== 0) {
            if($group_id) {
                if($discount_category_id) {
                    if($discount_category_id == '-1' && !empty($module_record_id)) {
                        $discount_result = CustomModuleRecordDiscount::where('module_name',$module_name)->where('module_record_id',$module_record_id)->where('agent_group_id',$group_id)->first();
                    } else {
                        $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('module_discount_type_id',$discount_category_id)->where('agent_group_id',$group_id)->first();
                        if($discount_result && $discount_result->id) {
                            // Ok
                        } else {
                            $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('agent_group_id',$group_id)->where('is_default',1)->first();
                        }
                    }
                } else {
                    $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('agent_group_id',$group_id)->where('is_default',1)->first();
                }
            } else {
                if($discount_category_id) {
                    $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('module_discount_type_id',$discount_category_id)->first();
                } else {
                    $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->first();    
                }
            }

            if($discount_result && $discount_result->id) {
                $discount_result = $discount_result;
            } else if($group_id != '-1') {
                $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->where('is_default',1)->orderBy('id','asc')->first();
            }

            if($discount_result && $discount_result->id) {
                $discount_result = $discount_result;
            } else if($group_id != '-1') {
                $discount_result = DiscountModuleToGroup::where('module_name',$module_name)->orderBy('id','asc')->first();
            }

            $discount_type = $discount_result->discount_type??'';
            $discount_value = $discount_result->discount_value??'';
            if($discount_type && $discount_type == 'flat') {
                $discount_type_label = 'Flat '.$discount_value;
            } elseif($discount_type && $discount_type == 'percentage') {
                $discount_type_label = $discount_value.'%';
            }
        }
        return $discount_type_label ;
    }

    public static function module_to_group_data($module_name,$module_type_id){
       $discount_result=DiscountModuleToGroup::where('module_name',$module_name)->where('module_discount_type_id',$module_type_id)->get();
       return $discount_result ;
    }

    public static function m2h($mins) {
        if ($mins < 0) {
            $min = Abs($mins);
        } else {
            $min = $mins;
        }
        $H = Floor($min / 60);
        $M = ($min - ($H * 60)) / 100;
        $hours = $H + $M;
        if ($mins < 0) {
            $hours = $hours * (-1);
        }

        $expl = explode(".", $hours);
        $H = $expl[0];
        if (empty($expl[1])) {
            $expl[1] = 00;
        }

        $M = $expl[1];
        if (strlen($M) < 2) {
            $M = $M . 0;
        }

        $H = $H <10?'0'.$H:$H;
        $hours = $H . "h" ." ". $M."m";

        return $hours;
    }


public static function minify_html_pdf($html)
    {
        $search = array(
            '/(\n|^)(\x20+|\t)/',
            '/(\n|^)\/\/(.*?)(\n|$)/',
            '/\n/',
            '/\<\!--.*?-->/',
            '/(\x20+|\t)/', # Delete multispace (Without \n)
            '/\>\s+\</', # strip whitespaces between tags
            '/(\"|\')\s+\>/', # strip whitespaces between quotation ("') and end tags
            '/=\s+(\"|\')/'); # strip whitespaces between = "'

        $replace = array(
            "\n",
            "\n",
            " ",
            "",
            " ",
            "><",
            "$1>",
            "=$1");

        $html = preg_replace($search, $replace, $html);
        $html = str_replace(['<p></p>','<p>&nbsp;</p>','<p>','</p>'],['','','','<br>'],$html);
        return $html;
    }
    

    // public static function setMailConfig() {
    //     $userName = self::getSMTPDetails('mail_username');
    //     $passWord = self::getSMTPDetails('mail_password');
    //     Config::set('mail.mailers.smtp.username',$userName);
    //     Config::set('mail.mailers.smtp.password',$passWord);
    //     // echo Config(mail.mailers.smtp.username);
    // }

    public static function random_strings() {
        $arr_str = array();
        $arr_str[1] = 'A';
        $arr_str[2] = 'B';
        $arr_str[3] = 'C';
        $arr_str[4] = 'D';
        $arr_str[5] = 'E';
        $arr_str[6] = 'F';
        $arr_str[7] = 'G';
        $arr_str[8] = 'H';
        $arr_str[9] = 'K';
        $arr_str[10] = 'M';
        $arr_str[11] = 'N';
        $arr_str[12] = 'P';
        $arr_str[13] = 'R';
        $arr_str[14] = 'S';
        $arr_str[15] = 'T';
        $arr_str[16] = 'A';
        $arr_str[17] = 'B';
        $arr_str[18] = 'C';
        $arr_str[19] = 'D';
        $arr_str[20] = 'E';
        $arr_str[21] = 'F';
        $arr_str[22] = 'G';
        $arr_str[23] = 'H';
        $arr_str[24] = 'K';
        $arr_str[25] = 'M';
        $arr_str[26] = 'N';
        $arr_str[27] = 'P';
        $arr_str[28] = 'R';
        $arr_str[29] = 'S';
        $arr_str[30] = 'T';
        $arr_str[31] = 'A';
        $arr_str[32] = 'B';
        $arr_str[33] = 'C';
        $arr_str[34] = 'D';
        $arr_str[35] = 'E';
        $arr_str[36] = 'F';
        $arr_str[37] = 'G';
        $arr_str[38] = 'H';
        $arr_str[39] = 'K';
        $arr_str[40] = 'M';
        $arr_str[41] = 'N';
        $arr_str[42] = 'P';
        $arr_str[43] = 'R';
        $arr_str[44] = 'S';
        $arr_str[45] = 'T';
        $arr_str[46] = 'A';
        $arr_str[47] = 'B';
        $arr_str[48] = 'C';
        $arr_str[49] = 'D';
        $arr_str[50] = 'E';
        $arr_str[51] = 'F';
        $arr_str[52] = 'G';
        $arr_str[53] = 'H';
        $arr_str[54] = 'K';
        $arr_str[55] = 'M';
        $arr_str[56] = 'N';
        $arr_str[57] = 'P';
        $arr_str[58] = 'R';
        $arr_str[59] = 'S';
        $arr_str[0] = 'T';
        $year = (int) date('Y');
        $year = $year - 2022;
        $year = $arr_str[$year];
        $month = $arr_str[(int) date('m')];
        $day = $arr_str[(int) date('d')];
        $hour = $arr_str[(int) date('H')];
        $minute = $arr_str[(int) date('i')];
        $str_str = $year . $month . $day . $hour . $minute;
        $intstr = '0123456789';
        $str_int = substr(str_shuffle($intstr),0, 7);
        return $str_str . $str_int;
    }


    public static function getPackageSlot($package_id='',$slot_id='') {
        $slot_title = '';
        if(!empty($package_id) && !empty($slot_id)) {
            // prd('Hello');
            $slug_url = 'packages-slot-'.$package_id;
            $slots = config('custom.'.$slug_url);
            // prd($slots);
            if(empty($slots)) {
                $slots = \Cache::rememberForever($slug_url, function () use($package_id) {
                    $result = PackageSlot::where('package_id',$package_id)->get();
                    $slots_arr = [];
                    if(!empty($result)) {
                        foreach($result as $row) {
                            $slots_arr[$row->id] = (object)[
                                'id' => $row->id,
                                'package_id' => $row->package_id,
                                'price_id' => $row->price_id,
                                'duration' => $row->duration,
                                'duration_type' => $row->duration_type,
                                'start_time' => $row->start_time,
                                'status' => $row->status,
                            ];
                        }
                    }
                    return $slots_arr;
                });
                // prd($slots);
                config(['custom.'.$slug_url=>$slots]);
            }
            // prd($slots);

            if(isset($slots[$slot_id])) {
                $slot_status = $slots[$slot_id]->status??'';
                if($slot_status == 1) {
                    $start_time = $slots[$slot_id]->start_time??'';
                    if($start_time) {
                        $slot_title = $start_time;
                    }
                }
            }
        }        
        return $slot_title;
    }

    public static function getPackageSlotTitle($slot_key='') {
        $slot_title = '';
        if($slot_key) {
            $tripTimeArr = config('custom.tripTimeArr');
            $slot_title = $tripTimeArr[$slot_key]??'';
        }
        return $slot_title;
    }

    public static function showAccommodationFeature($feature_id='',$field='') {
        $value = '';
        $accommodation_features = config('custom.accommodation_features');
        if(empty($accommodation_features)) {
            $accommodation_features = \Cache::rememberForever("accommodation_features", function () {
                $result = AccommodationFeature::where("status", 1)->orderBy('is_featured','DESC')->orderBy('sort_order','ASC')->orderBy('title','ASC')->get();
                $result_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $result_arr[$row->id] = (object)[
                            'id' => $row->id,
                            'title' => $row->title,
                            'icon' => $row->icon,
                            'is_featured' => $row->is_featured,
                            'feature_type' => $row->feature_type,
                            'sort_order' => $row->sort_order,
                            'status' => $row->status,
                        ];
                    }
                }
                return $result_arr;
            });
            config(['custom.accommodation_features'=>$accommodation_features]);
        }

        if(!empty($accommodation_features)) {
            if(isset($accommodation_features[$feature_id])) {
                if(!empty($field)) {
                    if($field == 'icon') {
                        $value = asset(config('custom.assets').'/images/ico3.png');
                        $icon = $accommodation_features[$feature_id]->icon??'';
                        if($icon) {
                            $path = 'accommodation_feature/';
                            $storage = Storage::disk('public');
                            if($storage->exists($path.$icon)) {
                                $value = asset('/storage/'.$path.$icon);
                            }
                            $value = $value;
                        }
                    } else {
                        $value = $accommodation_features[$feature_id]->$field??'';
                    }
                } else {
                    $value = $accommodation_features[$feature_id]->title??'';
                }
            }
        }
        return $value;
    }

  public static function showRoomFeature($feature_id='',$field='') {
        $value = '';
        $accommodation_features = config('custom.room_features');
        if(empty($accommodation_features)) {
            $accommodation_features = \Cache::rememberForever("room_features", function () {
                $result = RoomFeature::where("status", 1)->orderBy('sort_order','ASC')->orderBy('title','ASC')->get();
                $result_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $result_arr[$row->id] = (object)[
                            'id' => $row->id,
                            'title' => $row->title,
                            'icon' => $row->icon,
                            'is_featured' => $row->is_featured,
                            'feature_type' => $row->feature_type,
                            'sort_order' => $row->sort_order,
                            'status' => $row->status,
                        ];
                    }
                }
                return $result_arr;
            });
            config(['custom.room_features'=>$accommodation_features]);
        }

        if(!empty($accommodation_features)) {
            if(isset($accommodation_features[$feature_id])) {
                if(!empty($field)) {
                    if($field == 'icon') {
                        $value = asset(config('custom.assets').'/images/ico3.png');
                    } else {
                        $value = $accommodation_features[$feature_id]->$field??'';
                    }
                } else {
                    $value = $accommodation_features[$feature_id]->title??'';
                }
            }
        }
        return $value;
    }

    public static function showAccommodationFacility($facility_id='',$field='') {
        $value = '';
        $accommodation_facilities = config('custom.accommodation_facilities');
        if(empty($accommodation_facilities)) {
            $accommodation_facilities = \Cache::rememberForever("accommodation_facilities", function () {
                $result = AccommodationFacility::where("status", 1)->orderBy('sort_order','ASC')->orderBy('title','ASC')->get();
                $result_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $result_arr[$row->id] = (object)[
                            'id' => $row->id,
                            'title' => $row->title,
                            'sort_order' => $row->sort_order,
                            'status' => $row->status,
                        ];
                    }
                }
                return $result_arr;
            });
            config(['custom.accommodation_facilities'=>$accommodation_facilities]);
        }

        if(!empty($accommodation_facilities)) {
            if(isset($accommodation_facilities[$facility_id])) {
                if(!empty($field)) {
                    $value = $accommodation_facilities[$facility_id]->$field??'';
                } else {
                    $value = $accommodation_facilities[$facility_id]->title??'';
                }
            }
        }
        return $value;
    }

    public static function DateRange($startDate,$endDate) {
        $dateRange = CarbonPeriod::create($startDate, $endDate);
        // prd($dateRange);
        return $dateRange;
    }

   public static function CabDateSelect($dep,$pre_date,$type='') {

        //==============

            $currentHour = date('H');
            $Date = date('Y-m-d');
            $currentDate =  date('Y-m-d', strtotime($Date. ' +1 days'));    
            if($currentHour > 16){
             $currentDate =  date('Y-m-d', strtotime($Date. ' +2 days'));    
            }
            if(strtotime($dep) <= strtotime($currentDate)){     
                $dep = $currentDate;// date('Y-m-d', strtotime($currentDate. ' +1 days'));    
            }
            if(strtotime($pre_date) < strtotime($currentDate)){ 
                $pre_date = '';   
            }

            if($type == 'dep'){

            return $dep;
            }else{

            return $pre_date;
            }


        //=============
    }


    public static function getAccommodationInventory($params=[]) {
        // prd($params);
        $output = [];
        $output['success'] = false;
        if(CustomHelper::isOnlineBooking('hotel_listing')) {
            $room_id = (int)$params['room_id']??'';
            $plan_id = $params['plan_id']??'';
            $checkin = $params['checkin']??'';
            $checkout = $params['checkout']??'';
            $inventory = (int)$params['inventory']??1;
            $query = AccommodationInventory::where('status',1);
            $query->where('room_id',$room_id);
            if($plan_id) {
                $query->where('plan_id',(int)$plan_id);
            }
            $query->where('inventory','>=',$inventory);
            $date_arr = [];
            if($checkin && $checkout) {
                $checkout_date = date('Y-m-d', strtotime($checkout. ' - 1 days'));
                $period = CustomHelper::DateRange($checkin,$checkout_date);
                foreach ($period as $key => $value) {
                    $date_arr[] = $value->format('Y-m-d');
                }
                $query->whereIn('date',$date_arr);
            } else {
                $date_arr[] = $checkin;
                $query->whereIn('date',$date_arr);
            }
            $inventory_data = $query->orderBy('date','ASC')->get();
            // prd($inventory_data->toArray());
            $check_inventory_id = '';
            $price = 0;
            $publish_price = 0;
            $display_price = 0;
            if($inventory_data) {
                $accommodation = $inventory_data[0]->Accommodation??'';
                if($accommodation) {
                    $discount_category_id = $accommodation->discount_category_id??0;
                    $accommodation_id = $accommodation->id??0;
                
                    $is_agent = Auth::user()->is_agent??0;
                    $agent_group_id = '-1';
                    if($is_agent==1) {
                        $agent_group_id = Auth::user()->group_id??0;
                    }

                    foreach($inventory_data as $inv) {
                        $acc_price = $inv->AccommodationPlan->price??0;
                        $display_price += ($acc_price*$inventory);

                        if(isset($date_arr) && !empty($date_arr) && in_array($inv->date, $date_arr)) {
                            if(empty($check_inventory_id)) {
                                $check_inventory_id = $inv->id;
                            }
                            $date = $inv->date;
                            if (($key = array_search($date, $date_arr)) !== false) {
                                if( ((int)$inv->inventory-(int)$inv->booked) >= $inventory ) {
                                    if($inv->plan_id && $inv->price > 0) {
                                        $publish_price += ((int)$inv->price*$inventory);
                                    } else {
                                        // $base_price = (int)$inv->AccommodationRoom->base_price??0;
                                        // $base_price = CustomHelper::getAccommodationRoomPublishPrice($inv->AccommodationRoom);
                                        $base_price = $inv->AccommodationPlan->price??0;
                                        $publish_price += ($base_price*$inventory);
                                    }
                                    unset($date_arr[$key]);
                                }
                            }
                        }
                    }
                    if($publish_price) {
                        $price = $publish_price;
                        $discount_category_id = $discount_category_id;
                        if($discount_category_id !== 0) {
                            $module_name = 'hotel_listing';
                            $agent_group_id = $agent_group_id;
                            $module_record_id = $inv->AccommodationRoom->accommodation_id;
                            $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$publish_price,$agent_group_id,$module_record_id, $inventory);
                            if($publish_price > $discount_amount) {
                                $price = $publish_price - $discount_amount;
                            }
                        }
                    }
                }
            }
            if(empty($date_arr)) {
                $output['success'] = true;
                $output['inventory_id'] = $check_inventory_id;
                $output['price'] = $price;
                $output['publish_price'] = $publish_price;
                $output['display_price'] = $display_price;
            }
        }
        return $output;
    }

    public static function showInteractionCreatedBy($interaction=[]) {
        $created_by = 'Customer';
        if($interaction) {
            $created_type = $interaction->created_type??'';
            if($created_type == 'backend') {
                $created_by = self::showAdmin($interaction->created_by)??'Backend';
            } else if($created_type == 'agent') {
                $created_by = 'Agent';
            }
        }
        return $created_by;
    }


    public static function showRoomPlanInclude($include_id='',$field='') {
        $value = '';
        $room_plan_includes = config('custom.room_plan_includes');
        if(empty($room_plan_includes)) {
            // $room_plan_includes = \Cache::rememberForever("room_plan_includes", function () {
                $result = RoomPlanIncludes::where("status", 1)->orderBy('is_featured','DESC')->orderBy('sort_order','ASC')->orderBy('name','ASC')->get();
                $room_plan_includes = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        $room_plan_includes[$row->id] = (object)[
                            'id' => $row->id,
                            'name' => $row->name,
                            'available' => $row->available,
                            'is_featured' => $row->is_featured,
                            'sort_order' => $row->sort_order,
                        ];
                    }
                }
            //     return $room_plan_includes;
            // });
            config(['custom.room_plan_includes'=>$room_plan_includes]);
        }

        if(!empty($room_plan_includes)) {
            if(isset($room_plan_includes[$include_id])) {
                if(!empty($field)) {
                    $value = $room_plan_includes[$include_id]->$field??'';
                } else {
                    $value = $room_plan_includes[$include_id]->name??'';
                }
            }
        }
        return $value;
    }

    public static function fetchIframeSrc($iframe_url='') {
        $iframe_src = '';
        if(!empty($iframe_url) && strpos($iframe_url, '<iframe') !== false) {
            preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $iframe_url, $matches);
            //echo ($matches[0]); //only the <iframe ...></iframe> part
            //echo ($matches[1]); //the src part. (http://www.youtube.com/embed/IIYeKGNNNf4?rel=0)
            $iframe_src = $matches[1]??'';
        }
        return $iframe_src;
    }

    public static function getIp() {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return the server IP if the client IP is not found using this method.
    }

    public static function applyFlightCommission($order_id='') {
        if($order_id) {
            $order = Order::find($order_id);
            if($order && $order->agent_id) {
                $order_no = $order->order_no;
                $agent_id = $order->agent_id;
                $agent_group_id = $order->User->group_id??0;
                $total_amount = $order->total_amount;
                $booking_details = json_decode($order->booking_details,true)??[];
                $booking_status = $booking_details['order']['status']??'';
                // $order_markup = $booking_details['order']['markup']??0;
                $order_markup = $order->admin_markup??0;
                if($booking_status == 'SUCCESS') {
                    $flight_details = json_decode($order->flight_details,true)??[];
                    $tripInfos = $flight_details['info']??[];
                    $isDomestic = $tripInfos['searchQuery']['isDomestic']??'';
                    $ADULT = (int)$tripInfos['searchQuery']['paxInfo']['ADULT']??0;
                    $CHILD = (int)$tripInfos['searchQuery']['paxInfo']['CHILD']??0;
                    $INFANT = (int)$tripInfos['searchQuery']['paxInfo']['INFANT']??0;
                    $total_pax = $ADULT + $CHILD + $INFANT;

                    $fareIdentifier = $tripInfos['tripInfos'][0]['totalPriceList'][0]['fareIdentifier']??'';
                    $commission_data = AirlineDc::where('agent_group_id',$agent_group_id)->where('type','commission')->first();
                    if($commission_data) {
                        if($fareIdentifier && ($fareIdentifier == 'IIAIR_OFFER_FARE_WITH_PNR' || $fareIdentifier == 'IIAIR_OFFER_FARE_WITHOUT_PNR')) {
                            if($isDomestic==1) {
                                $commission_type = $commission_data->domestic_agent_type??'';
                                $commission_value = $commission_data->domestic_agent_value??'';
                            } else {
                                $commission_type = $commission_data->international_agent_type??'';
                                $commission_value = $commission_data->international_agent_value??'';
                            }
                        } else if($fareIdentifier && ($fareIdentifier == 'OFFER_FARE_WITH_PNR' || $fareIdentifier == 'OFFER_FARE_WITHOUT_PNR')) {
                            if($isDomestic==1) {
                                $commission_type = $commission_data->domestic_offer_type??'';
                                $commission_value = $commission_data->domestic_offer_value??'';
                            } else {
                                $commission_type = $commission_data->international_offer_type??'';
                                $commission_value = $commission_data->international_offer_value??'';
                            }
                        } else {
                            if($isDomestic==1) {
                                $commission_type = $commission_data->domestic_online_type??'';
                                $commission_value = $commission_data->domestic_online_value??'';
                            } else {
                                $commission_type = $commission_data->international_online_type??'';
                                $commission_value = $commission_data->international_online_value??'';
                            }
                        }

                        if($commission_type && $commission_value) {
                            $commission = 0;
                            if($order_markup > 0) {
                                if($commission_type == 'fixed') {
                                    $commission = $commission_value;
                                    $commission = $commission * $total_pax;
                                    if($commission > $order_markup) {
                                        $commission = 0;
                                    }
                                } else {
                                    $commission = (($order_markup * $commission_value)/100);
                                }
                            }
                            $user_id = $agent_id;
                            $exists = UserWallet::where('user_id',$user_id)->where('type','credit')->where('txn_id',$order_no)->where('comment','Flight Commission Credited')->first();
                            if($exists) {
                                // Do nothing, Prevent multiple commission.
                            } else if($commission > 0 && $commission < $total_amount) {
                                $old_balance = UserWallet::where('user_id',$user_id)->sum('amount');
                                $balance = $old_balance + $commission;
                                $walletData  = array(
                                    'user_id' => $user_id,
                                    'type' => 'credit',
                                    'amount' => $commission,
                                    'fees' => 0,
                                    'payment_method' => '',
                                    'balance' => $balance,
                                    'for_date' => date('Y-m-d H:i:s'),
                                    'txn_id' => $order_no,
                                    'comment' => 'Flight Commission Credited',
                                );
                                $isSaved = UserWallet::create($walletData);
                                if($isSaved) {
                                    $commission_details = [];
                                    $commission_details['commission_type'] = $commission_type;
                                    $commission_details['commission_value'] = $commission_value;
                                    $commission_details['commission'] = $commission;
                                    $commission_details['total_pax'] = $total_pax;

                                    $order->commission = $commission;
                                    $order->commission_details = json_encode($commission_details, true);
                                    $order->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function getAdminMarkupDetails($BF, $searchQuery, $getTotal, $fareIdentifier='') {
        $markup_type = '';
        $markup_value = 0;
        $markup_price = 0;

        $query = AirlineMarkup::where('status',1)->orderBy('sort_order','asc');
        $records = $query->get();
        if($records) {
            $isDomestic = true;
            if($searchQuery) {
                if($searchQuery['isDomestic']) {
                    $isDomestic = true;
                } else {
                    $isDomestic = false;
                }
            }

            foreach($records as $markup) {
                if($fareIdentifier && ($fareIdentifier == 'IIAIR_OFFER_FARE_WITH_PNR' || $fareIdentifier == 'IIAIR_OFFER_FARE_WITHOUT_PNR')) {
                    if($markup->code == 'domestic' && $isDomestic == true) {
                        $markup_type = $markup->agent_markup_type;
                        $markup_value = $markup->agent_markup_value;
                    } else if($markup->code == 'international' && $isDomestic == false) {
                        $markup_type = $markup->agent_markup_type;
                        $markup_value = $markup->agent_markup_value;
                    }
                } else if($fareIdentifier && ($fareIdentifier == 'OFFER_FARE_WITH_PNR' || $fareIdentifier == 'OFFER_FARE_WITHOUT_PNR')) {
                    if($markup->code == 'domestic' && $isDomestic == true) {
                        $markup_type = $markup->offer_markup_type;
                        $markup_value = $markup->offer_markup_value;
                    } else if($markup->code == 'international' && $isDomestic == false) {
                        $markup_type = $markup->offer_markup_type;
                        $markup_value = $markup->offer_markup_value;
                    }
                } else {
                    if($markup->code == 'domestic' && $isDomestic == true) {
                        $markup_type = $markup->online_markup_type;
                        $markup_value = $markup->online_markup_value;
                    } else if($markup->code == 'international' && $isDomestic == false) {
                        $markup_type = $markup->online_markup_type;
                        $markup_value = $markup->online_markup_value;
                    }                    
                }                
            }
        }        

        if($markup_type == 'fixed') {
            if($searchQuery && $searchQuery['paxInfo'] && $getTotal) {
                $totalPax = ($searchQuery['paxInfo']['ADULT']??0) + ($searchQuery['paxInfo']['CHILD']??0) + ($searchQuery['paxInfo']['INFANT']??0);
                $markup_price = (int)$markup_value * (int)$totalPax;
            } else {
                $markup_price = $markup_value;
            }
        } else if($markup_type == 'percent') {
            $markup_price = (($BF*$markup_value)/100);
        }

        $markup_details = [
            'markup_type' => $markup_type,
            'markup_value' => $markup_value,
            'markup_price' => $markup_price,
        ];
        return $markup_details;
    }

    public static function getMarkupDetails($BF, $searchQuery, $getTotal, $fareIdentifier='') {
        $markup_type = '';
        $markup_value = 0;
        $markup_price = 0;
        $is_agent = Auth::user()->is_agent??0;
        if($is_agent==1) {
            $agent_id = Auth::user()->id;
            $query = AgentAirlineMarkup::where('agent_id',$agent_id);
            $records = $query->get();
            if($records) {
                $isDomestic = true;
                if($searchQuery) {
                    if($searchQuery['isDomestic']) {
                        $isDomestic = true;
                    } else {
                        $isDomestic = false;
                    }
                }

                foreach($records as $markup) {
                    if($fareIdentifier && ($fareIdentifier == 'OFFER_FARE_WITH_PNR' || $fareIdentifier == 'OFFER_FARE_WITHOUT_PNR' || $fareIdentifier == 'IIAIR_OFFER_FARE_WITH_PNR' || $fareIdentifier == 'IIAIR_OFFER_FARE_WITHOUT_PNR')) {
                        if($markup->code == 'domestic' && $isDomestic == true) {
                            $markup_type = $markup->offer_markup_type;
                            $markup_value = $markup->offer_markup_value;
                        } else if($markup->code == 'international' && $isDomestic == false) {
                            $markup_type = $markup->offer_markup_type;
                            $markup_value = $markup->offer_markup_value;
                        }
                    } else {
                        if($markup->code == 'domestic' && $isDomestic == true) {
                            $markup_type = $markup->online_markup_type;
                            $markup_value = $markup->online_markup_value;
                        } else if($markup->code == 'international' && $isDomestic == false) {
                            $markup_type = $markup->online_markup_type;
                            $markup_value = $markup->online_markup_value;
                        }                        
                    }
                }
            }
        }

        if($markup_type == 'fixed') {
            if($searchQuery && $searchQuery['paxInfo'] && $getTotal) {
                $totalPax = $searchQuery['paxInfo']['ADULT'] + $searchQuery['paxInfo']['CHILD'] + $searchQuery['paxInfo']['INFANT'];
                $markup_price = $markup_value * $totalPax;
            } else {
                $markup_price = $markup_value;
            }
        } else if($markup_type == 'percent') {
            $markup_price = (($BF*$markup_value)/100);
        }

        $markup_details = [
            'markup_type' => $markup_type,
            'markup_value' => $markup_value,
            'markup_price' => $markup_price,
        ];
        return $markup_details;
    }

    public static function getAgentDiscountDetails($BF, $searchQuery, $getTotal, $fareIdentifier='') {
        $discount_type = '';
        $discount_value = 0;
        $discount_price = 0;
        $is_agent = Auth::user()->is_agent??0;
        if($is_agent==1) {
            $agent_group_id = Auth::user()->group_id??0;
            $discount = AirlineDc::where('agent_group_id',$agent_group_id)->where('type','discount')->first();
            if($discount) {
                $isDomestic = true;
                if($searchQuery) {
                    if($searchQuery['isDomestic']) {
                        $isDomestic = true;
                    } else {
                        $isDomestic = false;
                    }
                }

                if($fareIdentifier && ($fareIdentifier == 'IIAIR_OFFER_FARE_WITH_PNR' || $fareIdentifier == 'IIAIR_OFFER_FARE_WITHOUT_PNR')) {
                    if($isDomestic == true) {
                        $discount_type = $discount->domestic_agent_type;
                        $discount_value = $discount->domestic_agent_value;
                    } else {
                        $discount_type = $discount->international_agent_type;
                        $discount_value = $discount->international_agent_value;
                    }
                } else if($fareIdentifier && ($fareIdentifier == 'OFFER_FARE_WITH_PNR' || $fareIdentifier == 'OFFER_FARE_WITHOUT_PNR')) {
                    if($isDomestic == true) {
                        $discount_type = $discount->domestic_offer_type;
                        $discount_value = $discount->domestic_offer_value;
                    } else {
                        $discount_type = $discount->international_offer_type;
                        $discount_value = $discount->international_offer_value;
                    }
                } else {
                    if($isDomestic == true) {
                        $discount_type = $discount->domestic_online_type;
                        $discount_value = $discount->domestic_online_value;
                    } else {
                        $discount_type = $discount->international_online_type;
                        $discount_value = $discount->international_online_value;
                    }
                }
            }
            

            if($discount_type == 'fixed') {
                if($searchQuery && $searchQuery['paxInfo'] && $getTotal) {
                    $totalPax = $searchQuery['paxInfo']['ADULT'] + $searchQuery['paxInfo']['CHILD'] + $searchQuery['paxInfo']['INFANT'];
                    $discount_price = $discount_value * $totalPax;
                } else {
                    $discount_price = $discount_value;
                }
            } else if($discount_type == 'percent') {
                $discount_price = (($BF*$discount_value)/100);
            }
        }

        $discount_details = [
            'discount_type' => $discount_type,
            'discount_value' => $discount_value,
            'discount_price' => $discount_price,
        ];
        return $discount_details;
    }

    public static function send_sms($params=[])
    {
        try {
            $SMS_USERNAME = self::WebsiteSettings('SMS_USERNAME');
            $SMS_PASSWORD = self::WebsiteSettings('SMS_PASSWORD');
            $SMS_SENDERID = self::WebsiteSettings('SMS_SENDERID');

            $phone = $params['phone'] ?? '';            
            $content = $params['content'] ?? '';
            $template_id = $params['template_id'] ?? '';
            if($phone && stripos($phone, '+')) {
                $phone = str_replace('+91-', '', $phone);
                $phone = str_replace('+91', '', $phone);
                $phone = str_replace('+', '', $phone);
            }

            if(!empty($SMS_USERNAME) && !empty($SMS_PASSWORD) && !empty($SMS_SENDERID)) {
                if(!empty($phone) && !empty($template_id) && !empty($content)) {
                    $url = 'http://www.smsjust.com/blank/sms/user/urlsms.php?username='.$SMS_USERNAME.'&pass='.$SMS_PASSWORD.'&senderid='.$SMS_SENDERID.'&dest_mobileno='.$phone.'&tempid='.$template_id.'&message='.$content.'&response=Y';

                    $ch = curl_init(); 
                    curl_setopt($ch, CURLOPT_URL, $url); 
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                    $output = curl_exec($ch); 
                    $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                } else {
                    $response = 'Invalid parameters!';
                    Self::captureSentryMessage('send_sms handle error='.$response);
                }
            } else {
                $response = 'Sms authentication failed!';
                Self::captureSentryMessage('send_sms handle error='.$response);
            }
            return $response;
        } catch (\Exception $e) {
            $response = $e->getMessage();
            Self::captureSentryMessage('send_sms handle error='.$response);
            return $response;
        }
    }

    public static function sendVoucherMail($OrderServiceVoucher,$order) {

        $order_type = $order->order_type??'';

        if($order_type == 5){

            $voucher_id = $OrderServiceVoucher->id ?? '';

            $hotel_data = $OrderServiceVoucher->hotel_data ? json_decode($OrderServiceVoucher->hotel_data): [];

            $contact_phone = $hotel_data->contact_phone??'';
            $contact_email = $hotel_data->contact_email??'';
            $contact_person = $hotel_data->contact_person??'';

            $title = $OrderServiceVoucher->title ?? '';
            $room_type = $hotel_data->room_name ?? '';
            $plan_name = $hotel_data->plan_name ?? '';
            //$checkin = $hotel_data->checkin ?? '';
            //$checkout = $hotel_data->checkout ?? '';
            $checkin = !empty($hotel_data->checkin)?date('d, M Y',strtotime($hotel_data->checkin)):'';
            $checkout = !empty($hotel_data->checkout)?date('d, M Y',strtotime($hotel_data->checkout)):'';
            $checkin_time = $hotel_data->checkin_time ?? '';
            $checkout_time = $hotel_data->checkout_time ?? '';
            $inventory = $hotel_data->inventory ?? '';
            $destination = $hotel_data->destination_name ?? '';
            $address = $hotel_data->address ?? '';
            $inclusions = $hotel_data->inclusions ?? '';
            $trip_date = !empty($hotel_data->trip_date)?date('D, M dS Y h:i A',strtotime($hotel_data->trip_date)):'';
            $created_at = !empty($hotel_data->created_at)?date('D, M dS Y',strtotime($hotel_data->created_at)):'';
            $adult = $hotel_data->adult ?? '';
            $child = $hotel_data->child ?? '';
            $hotel_charge = $hotel_data->hotel_charge ?? '';
            $paid_amount = $hotel_data->paid_amount ?? '';
            $total_amount = $order->total_amount ?? 0;

          $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
            $storage = Storage::disk('public');
            $path = "settings/";
            $logoSrc = asset(config('custom.assets').'/images/logo.png');
            $logoPdfSrc = public_path().'/'.(config('custom.assets').'/images/logo.png');
            if(!empty($FRONTEND_LOGO)){
                if($storage->exists($path.$FRONTEND_LOGO)){
                    $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                    $logoPdfSrc = public_path().'/storage/'.$path.$FRONTEND_LOGO;
                }
            }

        // AGENT LOGO
        $userAgentInfo = ''; $agentLogo = '';
        $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
        if($agent_id && $agent_id > 0){
            $userAgentInfo = $order->agentInfo ?? '';
            if($userAgentInfo){
                $path = 'agent_logo/';
                $agentLogo = $order->agentInfo->agent_logo ?? '';

                $userData = $userAgentInfo->User ?? '';
                $agent_phone = '';
                $agent_email = '';
                if($userData->phone) {
                    $country_code = $userData->country_code ?? '91';
                    $agent_phone = '+'.$country_code.'-'.$userData->phone;
                }
                $agent_email = !empty($userData->email)?$userData->email:'';
            }
            if(!empty($agentLogo)){
                if($storage->exists($path.$agentLogo)){
                    $logoSrc = asset('/storage/'.$path.$agentLogo);
                    $logoPdfSrc = public_path().'/storage/'.$path.$agentLogo;
                }
            }
            $total_amount = $order->sub_total_amount ?? 0;
        }

        $common_logo = '';
        if(!empty($agent_id)){
            $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
            $b2b_logo_params = array(
                '{agent_logo}' => $logoSrc
            );
            $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
        }else{
            $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
            $b2c_logo_params = array(
             '{company_logo}' => $logoSrc
         );
            $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
        }

        $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
        $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
        $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
        $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

        $sales_phone = CustomHelper::getPhoneHref($company_phone);
        $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
        $sales_email = CustomHelper::getEmailHref($company_email);


        $contact_details = '';
        if(!empty($agent_id)){
            $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
            $b2b_email_params = array(
                '{agent_phone}' => $agent_phone,
                '{agent_email}' => $agent_email
            );
            $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
           /* $contact_details .= 
            '<tr>
             <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
             <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Mobile:</b> <a href="tel:'.$agent_phone.'" style="color: #fff;text-decoration: none;">'.$agent_phone.'</a>; <b>Email:</b> <a href="mailto:'.$agent_email.'" style="color: #fff;text-decoration: none;">'.$agent_email.'</a></p>

             <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>
             </td>
             </tr>';*/
         }else{

            $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
            $b2c_email_params = array(
                '{company_name}' => $company_name,
                '{sales_mobile}' => $sales_mobile,
                '{sales_phone}' => $sales_phone,
                '{sales_email}' => $sales_email,
                '{company_title}' => $system_title
            );
            $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
            /*$contact_details .= '<tr>
            <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
            <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

            <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$system_title.'. All Rights Reserved.</p>
            </td>
            </tr>';*/
        } 
    
        $email_params = array(
            '{header}' => $common_logo,
            '{logo_pdf}' => $logoPdfSrc,
            '{booking_id}' =>  $order->order_no??'',
            '{hotel_name}' =>  $OrderServiceVoucher->title??'',
            '{customer_name}' =>  $OrderServiceVoucher->name??'',
            '{phone}' =>  $contact_phone??'',
            '{email}' =>  $contact_email??'',
            '{contact_person}' =>  $contact_person??'',
            '{room_type}' =>  $room_type??'',
            '{plan_name}' =>  $plan_name??'',
            '{checkin_date}' =>  $checkin??'',
            '{checkout_date}' =>  $checkout??'',
            '{checkin_time}' =>  $checkin_time??'',
            '{checkout_time}' =>  $checkout_time??'',
            '{inventory}' =>  $inventory??'',
            '{destination}' =>  $destination??'',
            '{address}' =>  $address??'',
            '{contact_details}' =>  $contact_details??'',
            '{inclusions}' =>  $inclusions??'',
            '{adult}' =>  $adult??'',
            '{child}' =>  $child??'',
            //'{booking_date}' =>  $trip_date??'',
            '{booking_date}' =>  $created_at??'',
            '{hotel_charge}' =>  $hotel_charge??'',
            '{amount}' =>  $total_amount??0,
            '{paid_amount}' =>  $paid_amount??'',
        );

            $price_details = '';
            if($order->hide_price_details != 1){
                $price_details .= '<tr>
                                        <td style="padding: 10px 15px 10px 15px;border: 0;">
                                        <p style="color: #333;font-size: 15px;font-family: Roboto, sans-serif, Arial;margin: 0;line-height: normal;"><strong>Total Charges</strong></p>
                                        </td>
                                        <td colspan="3" style="padding: 10px 15px 10px 15px;border: 0;">
                                        <p style="color: #333;font-size: 15px;font-family: Roboto, sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
                                        </td>
                                    </tr>';
                if(empty($agent_id)){
                    $price_details .= '<tr>
                                        <td style="padding: 10px 15px 10px 15px;border: 0;">
                                        <p style="color: #333;font-size: 15px;font-family: Roboto, sans-serif, Arial;margin: 0;"><strong>Amount Paid</strong></p>
                                        </td>
                                        <td colspan="3" style="padding: 10px 15px 10px 15px;border: 0;">
                                        <p style="color: #333;font-size: 15px;font-family: Roboto, sans-serif, Arial;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                                        </td>
                                    </tr>';
                }
            }
            $email_params['{price_details}'] = $price_details;

            $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');

           $email_template = EmailTemplate::where('slug', 'hotel-booking-voucher')->where('status', 1)->first();
           $email_content = isset($email_template->content) ? $email_template->content : '';
           $email_params = isset($email_params) ? $email_params : [];

           //Send mail

           $email_content = strtr($email_content, $email_params);
           $email_subject = isset($email_template->subject) ? $email_template->subject : '';
           $email_subject = strtr($email_subject, $email_params);
           $email_bcc_admin = isset($email_template->bcc_admin) ? $email_template->bcc_admin : 0;
           $email_type = isset($email_template->email_type) ? $email_template->email_type : '';

            //=PDF

           //$pdf_content = view("admin.vouchers.hotel_voucher_pdf", $email_params)->render();
           $pdf_content = view(config('custom.theme').'.common.vouchers.hotel_voucher_pdf', $email_params)->render();
           $html = strtr($pdf_content, $email_params);

           $pdf = PDF::loadHTML($html);
           $path = 'temp/';
           $pdf_name = $order->order_no.'.pdf';
           if (!File::exists(public_path("storage/" . $path))) {
            File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
           }
           $pdf->save(public_path("storage/" . $path).$pdf_name);
           $attachments = public_path("storage/".$path).$pdf_name;

            //============

            $REPLYTO = $ADMIN_EMAIL;
            //$to_email = $order->email;
            $to_email = $OrderServiceVoucher->email ? $OrderServiceVoucher->email : '';
            $cc_email = '';
           $bcc_email = '';
           $params = [];
           $params['to'] = $to_email;
           $params['cc'] = $cc_email;
           $params['bcc'] = $bcc_email;
           $params['reply_to'] = $to_email;
           $params['subject'] = $email_subject;
           $params['email_content'] = $email_content;
           if(isset($attachments)) {
                    $params['attachments'] = $attachments;
                }
           $isSent = CustomHelper::sendCommonMail($params);

           $voucherCreated = CustomHelper::getOrderStatus('voucher sent'); 

           $order->orders_status = $voucherCreated;
           $order->save();

           $params = [];
           $params['order_id'] = $order->id??'';
           $params['comments'] = "Voucher sent successfully";
           $params['orders_status'] = CustomHelper::showEnquiryMaster($voucherCreated);

           CustomHelper::RecordOrderStatusHistory($params);

                //voucher Send email log
                //=============Activity Logs=====================

                $user_id = $order->user_id ??'';
                $user_name = $order->name??'';
                $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
                $module_desc =  !empty($title)?$title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id= $order->id??'';
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Hotel Voucher';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = '';
                $params['sub_module_id'] = $voucher_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = "Email Sent";

               CustomHelper::RecordActivityLog($params);

                //=============Activity Logs=====================
                $msg = "Mail sent successfully.";

        }else if($order_type == 6){

        $booking_details = $order->booking_details ? json_decode($order->booking_details): [];
        $total_pax = $booking_details->total_pax ?? '';

        $voucher_id = $OrderServiceVoucher->id ?? '';

        $package_data = $OrderServiceVoucher->package_data ? json_decode($OrderServiceVoucher->package_data): [];

        $order_id = $OrderServiceVoucher->order_id??'';
        $user_phone = '';
        if($order_id) {
            $country_code = $order->country_code ?? '91';
            $user_phone = '+'.$country_code.'-'.$OrderServiceVoucher->phone;
        }

        $title = $OrderServiceVoucher->title ?? '';
        $package_name = $package_data->package_name ?? '';
        $package_charges = $package_data->package_charges ?? '';
        $package_charges = (int)$package_charges+(int)$order->markup;
        $paid_amount = $package_data->paid_amount ?? '';
        $paid_amount = (int)$paid_amount+(int)$order->markup;
        $trip_date = !empty($package_data->trip_date)?date('D, M dS Y h:i A',strtotime($package_data->trip_date)):'';
        //$trip_date = $package_data->trip_date??'';
        $duration = $package_data->duration ?? '';
        $destination = $package_data->destination ?? '';
        $due_amount = $package_data->due_amount ?? '';
        if($package_data->address){    
            $address = $package_data->address ?? '';
        }
        $contact_person = $package_data->contact_person ?? '';
        $contact_phone = $package_data->contact_phone ?? '';
        $contact_email = $package_data->contact_email ?? '';
        $total_amount = $order->total_amount ?? 0;
        $booking_date = !empty($order->created_at)?date('d M,Y h:i A',strtotime($order->created_at)):'';
        // $orders_status = CustomHelper::showOrderStatus($order->orders_status)??'';

        $voucherSent = CustomHelper::getOrderStatus('voucher sent');
        $orders_status = CustomHelper::showOrderStatus($voucherSent)??'';

        $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
        $storage = Storage::disk('public');
        /*$path = "settings/";
        $logoSrc =public_path(config('custom.assets').'/images/logo.png');
        if(!empty($FRONTEND_LOGO)){
            if($storage->exists($path.$FRONTEND_LOGO)){
                $logoSrc = public_path('/storage/'.$path.$FRONTEND_LOGO);
            }
        }*/
        $path = "settings/";
        $logoSrc =asset(config('custom.assets').'/images/logo.png');
        if(!empty($FRONTEND_LOGO)){
            if($storage->exists($path.$FRONTEND_LOGO)){
                $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
            }
        }

        // AGENT LOGO
        $userAgentInfo = ''; $agentLogo = '';
        $agent_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
        $COMPANY_ADDRESS = CustomHelper::WebsiteSettings('COMPANY_ADDRESS');
        $agent_id = isset($order->agent_id) ? $order->agent_id : 0;
        if($agent_id && $agent_id > 0){
            $userAgentInfo = $order->agentInfo ?? '';
            if($userAgentInfo){
                $path = 'agent_logo/';
                $agentLogo = $order->agentInfo->agent_logo ?? '';
                $COMPANY_ADDRESS = $order->agentInfo->company_name ?? '';

                $userData = $userAgentInfo->User ?? '';
                $agent_phone = '';
                $agent_email = '';
                if($userData->phone) {
                    $country_code = $userData->country_code ?? '91';
                    $agent_phone = '+'.$country_code.'-'.$userData->phone;
                }
                $agent_email = !empty($userData->email)?$userData->email:'';
            }
            if(!empty($agentLogo)){
                if($storage->exists($path.$agentLogo)){
                    $logoSrc = asset('/storage/'.$path.$agentLogo);
                }
            }
            $total_amount = $order->sub_total_amount ?? 0;
        }

        //Vendor Logo
        $packages = $OrderServiceVoucher->Package??'';
        $vendor_data = $packages->Admin??'';

        $is_vendor = isset($vendor_data->is_vendor)?$vendor_data->is_vendor:'';
        //$user_id = isset($vendor_data->id)?$vendor_data->id:'';
        $vendor_logo = '';
        $vendor_company_name = '';
        if($is_vendor == 1){
            $path = 'vendor_logo/';
            $vendor_logo = $vendor_data->vendor_logo ?? '';
            $vendor_company_name = $vendor_data->vendor_company_name ?? '';
            //$vendorLogo = public_path(config('custom.assets').'/images/vendor_logo.jpg');
            $vendorLogo = asset(config('custom.assets').'/images/default_vendor_logo.jpg');
            if(!empty($vendor_logo)){
                if($storage->exists($path.$vendor_logo)){
                    //$vendorLogo = public_path('/storage/'.$path.$vendor_logo);
                    $vendorLogo = asset('/storage/'.$path.$vendor_logo);
                }
            }
        }

        $common_logo = '';
        if(!empty($agent_id)){
            $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
            $b2b_logo_params = array(
                '{agent_logo}' => $logoSrc
            );
            $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
        }else{
            $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
            $b2c_logo_params = array(
             '{company_logo}' => $logoSrc
         );
            $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
        }

        $price_category_choice_record_arr = json_decode($order->price_category_choice_record,true)??[];
        $person = '';
        $total_amount = 0;
        if(!empty($price_category_choice_record_arr)) {
            foreach($price_category_choice_record_arr as $key => $pccr) {
                $input_label = $pccr['input_label']??'';
                $input_value = $pccr['input_value']??0;
                $input_price = $pccr['input_price']??0;
                $sub_total = $input_value*$input_price;
                $total_amount += $sub_total;

                $price = CustomHelper::getPrice($input_price);
                if($key != 0){
                  $person.= ", ";
                }
                $person.= $input_label.':'.$input_value.'X Rs.'.$input_price.' = Rs.'.$sub_total;
            }
        }

        $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
        $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
        $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
        $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

        $sales_phone = CustomHelper::getPhoneHref($company_phone);
        $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
        $sales_email = CustomHelper::getEmailHref($company_email);


        $contact_details = '';
        if(!empty($agent_id)){
            $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
            $b2b_email_params = array(
                '{agent_phone}' => $agent_phone,
                '{agent_email}' => $agent_email
            );
            $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
           /* $contact_details .= 
            '<tr>
             <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
             <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Mobile:</b> <a href="tel:'.$agent_phone.'" style="color: #fff;text-decoration: none;">'.$agent_phone.'</a>; <b>Email:</b> <a href="mailto:'.$agent_email.'" style="color: #fff;text-decoration: none;">'.$agent_email.'</a></p>

             <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>
             </td>
             </tr>';*/
         }else{

            $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
            $b2c_email_params = array(
                '{company_name}' => $company_name,
                '{sales_mobile}' => $sales_mobile,
                '{sales_phone}' => $sales_phone,
                '{sales_email}' => $sales_email,
                '{company_title}' => $system_title
            );
            $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
            /*$contact_details .= '<tr>
            <td colspan="2" style="padding: 10px 18px 2px 18px;background: #12968e;">
            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">Questions or concerns? Get in touch with us!<br />
            <a href="'.$company_name.'" style="color: #fff;text-decoration: none;" target="_blank"><b>Website:</b> '.$company_name.'</a></p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 4px 0px;text-align: center;">For any further clarifications, write to us or call us at 24x7 Helpline numbers, mentioned below. <b>Phone:</b> '.CustomHelper::getPhoneHref($sales_mobile).'; <b>Mobile:</b> '.CustomHelper::getPhoneHref($sales_phone).'; <b>Email:</b> '.CustomHelper::getEmailHref($sales_email).'</p>

            <p style="color: #fff;font-size: 12px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">If you have received this communication in error, please do not forward its contents; notify the sender and delete it and any attachments. This message may contain information that is confidential and legally privileged. Unless you are the addressee, you may not use, copy or disclose to anyone this message or any information contained within.</p>

            <p style="color: #fff;font-size: 14px;font-weight: 400;font-family: \'Roboto\', sans-serif, Arial;margin: 10px 0px;text-align: center;border-top: 1px solid #dddddd73;padding-top: 10px;">'.$system_title.'. All Rights Reserved.</p>
            </td>
            </tr>';*/
        }

        $email_params = array(
            '{header}' => $common_logo,
            '{order_no}' =>  $order->order_no??'',
            '{orders_status}' =>  $orders_status??'',
            '{package_name}' =>  $package_name??'',
            '{itinerary}' =>  $itinerary??'',
            '{title}' =>  $OrderServiceVoucher->title??'',
            '{trip_date}' =>  $trip_date??'',
            '{booking_date}' =>  $booking_date??'',
            '{duration}' =>  $duration??'',
            '{destination}' =>  $destination??'',
            '{name}' =>  $OrderServiceVoucher->name??'',
            '{phone}' =>  $OrderServiceVoucher->phone??'',
            '{email}' =>  $OrderServiceVoucher->email??'',
            '{vendor_logo}' =>  $vendorLogo??'',
            '{vendor_company_name}' =>  $vendor_company_name??'',
            '{amount}' =>  $total_amount??0,
            '{paid_amount}' =>  $paid_amount??'',
            '{due_amount}' =>  $due_amount??'',
            '{address}' =>  $address??'',
            '{company_agent_phone}' =>  $agent_phone??'',
            '{company_address}' =>  $COMPANY_ADDRESS??'',
            // '{person}' => $person,
            '{person}' => $total_pax,
            '{contact_person}' => $contact_person,
            '{contact_details}' => $contact_details??'',
            '{contact_phone}' => $contact_phone,
            '{contact_email}' => $contact_email,
        );

        /*$email_params = array(
             '{header}' => $common_logo,
             '{order_no}' =>  $order->order_no??'',
             '{package_name}' =>  $package_name??'',
             '{itinerary}' =>  $itinerary??'',
             '{title}' =>  $OrderServiceVoucher->title??'',
             '{trip_date}' =>  $trip_date??'',
             '{duration}' =>  $duration??'',
             '{destination}' =>  $destination??'',
             '{name}' =>  $OrderServiceVoucher->name??'',
             '{phone}' =>  $user_phone ??'',
             '{email}' =>  $OrderServiceVoucher->email??'',
             '{amount}' =>  $total_amount??0,
             '{paid_amount}' =>  $paid_amount??'',
             '{due_amount}' =>  $due_amount??'',
             '{address}' =>  $address??'',
             //'{person}' => $person,
             '{person}' => $total_pax,
             '{contact_person}' => $contact_person,
             '{contact_details}' => $contact_details??'',
             '{contact_phone}' => $contact_phone,
             '{contact_email}' => $contact_email,
        );*/

        $price_details = '';
        /*if($order->hide_price_details != 1){
            $price_details = '';
            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 2px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;line-height: normal;">Amount paid to Overland Escape</p>
                </td>
                <td style="padding: 2px 15px 2px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                </td>
                </tr>';
            }

            $price_details .= '<tr>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">Total Booking Amount</p>
            </td>
            <td style="padding: 2px 15px 2px 15px;border: 0;">
            <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
            </td>
            </tr>';

            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 2px 15px 20px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;">Amount Due</p>
                </td>
                <td style="padding: 2px 15px 20px 15px;border: 0;">
                <p style="color: #333;font-size: 15px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$due_amount.'</p>
                </td>
                </tr>';
            }
        }*/
        if($order->hide_price_details != 1){

            $price_details .= '<table border="0" style="width:100%; border-collapse: collapse;">
            <tr>
            <td style="padding: 4px 10px 2px 10px;border:1px solid #cfcfcf;border-top:0;border-left:0;">
            <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;">Total</p>
            </td>
            <td style="padding: 4px 10px 2px 10px;border:1px solid #cfcfcf;border-top:0;border-right:0;">
            <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$total_amount.'</p>
            </td>
            </tr>';
            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 4px 15px 2px 10px;border:1px solid #cfcfcf;border-left:0;">
                <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;line-height: normal;">Amount paid</p>
                </td>
                <td style="padding: 4px 10px 2px 10px;border:1px solid #cfcfcf;border-right:0;">
                <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$paid_amount.'</p>
                </td>
                </tr>';
            }
            if(empty($agent_id)){
                $price_details .= '<tr>
                <td style="padding: 4px 10px 20px 10px;border:1px solid #cfcfcf;border-left:0;">
                <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;">Amount Due</p>
                </td>
                <td style="padding: 4px 10px 20px 10px;border:1px solid #cfcfcf;border-right:0;">
                <p style="color: #333;font-size: 13px;font-weight: 500;font-family: Roboto;margin: 0;text-align: left;">Rs. '.$due_amount.'</p>
                </td>
                </tr>';
            }
            $price_details .= '</table>';
        }
        $email_params['{price_details}'] = $price_details;

        $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');

        $email_template = EmailTemplate::where('slug', 'activity-voucher')->where('status', 1)->first();
           $email_content = isset($email_template->content) ? $email_template->content : '';
           // $email_params = isset($email_params) ? $email_params : [];

           //Send mail
           $email_content = strtr($email_content, $email_params);
           $email_subject = isset($email_template->subject) ? $email_template->subject : '';
           $email_subject = strtr($email_subject, $email_params);
           $email_bcc_admin = isset($email_template->bcc_admin) ? $email_template->bcc_admin : 0;
           $email_type = isset($email_template->email_type) ? $email_template->email_type : '';

           //=PDF

           //$pdf_content= view("admin.vouchers.activity_voucher_pdf", $email_params)->render();
           $pdf_content = view(config('custom.theme').'.common.vouchers.activity_voucher_pdf', $email_params)->render();
           $html = strtr($pdf_content, $email_params);

               $pdf = PDF::loadHTML($html);
               $path = 'temp/';
               $pdf_name = $order->order_no.'.pdf';
               if (!File::exists(public_path("storage/" . $path))) {
                File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
               }
               $pdf->save(public_path("storage/" . $path).$pdf_name);
               $attachments = public_path("storage/".$path).$pdf_name;

           //============

           $REPLYTO = $ADMIN_EMAIL;
           $to_email = $OrderServiceVoucher->email ? $OrderServiceVoucher->email : '';
           $cc_email = '';
           $bcc_email = '';
           $params = [];
           $params['to'] = $to_email;
           $params['cc'] = $cc_email;
           $params['bcc'] = $bcc_email;
           $params['reply_to'] = $to_email;
           $params['subject'] = $email_subject;
           $params['email_content'] = $email_content;
           if(isset($attachments)) {
                    $params['attachments'] = $attachments;
                }
           $isSent = CustomHelper::sendCommonMail($params);

           //voucher Send email log

           $voucherCreated = CustomHelper::getOrderStatus('voucher sent'); 

           $order->orders_status = $voucherCreated;
           $order->status = CustomHelper::showEnquiryMaster($voucherCreated);
           $order->save();

           $params = [];

           $params['order_id'] = $order->id??'';
           $params['comments'] = "Voucher sent successfully";
           $params['orders_status'] = CustomHelper::showEnquiryMaster($voucherCreated);

           CustomHelper::RecordOrderStatusHistory($params);

            //=============Activity Logs=====================

            $user_id = $order->user_id ??'';
            $user_name = $order->name??'';
            $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
            $module_desc =  !empty($title)?$title:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);
            $module_id= $order->id??'';
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Activity Voucher';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = '';
            $params['sub_module_id'] = $voucher_id;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = "Email Sent";

            CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================
            $msg = "Mail sent successfully.";
        }
        return true;
    }

    public static function createVoucher($order_no) {

        // $order_no = $request->order_no;
       $success = false;
       $msg = '';
       $order = Order::where('order_no',$order_no)->first();
       $order_type = $order->order_type?? '' ;

        //Check Iventory =====

       if($order_type == 5){

           $booking_details = json_decode($order->booking_details);
           $inventory = $order->inventory;
           $params = [];
           $params['action'] = 'hotel_booking';
           $params['inventory_id'] = $booking_details->inventory_id ?? 0;
           $params['checkin'] = $booking_details->checkin ? CustomHelper::DateFormat($booking_details->checkin,'Y-m-d','d/m/Y') : '';
           $params['checkout'] = $booking_details->checkout ? CustomHelper::DateFormat($booking_details->checkout,'Y-m-d','d/m/Y') : '';
           $params['inventory']= $inventory ?? 1;
           $check_inventory = CustomHelper::checkInventory($params);
            //===================

           if($check_inventory){

            $order_id = $order->id;
            $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();
            $booking_details = json_decode($order->booking_details);

            $hotel_id = $booking_details->hotel_id??'';
            $hotel_info = Accommodation::find($hotel_id);
            $checkin_time = $hotel_info->checkin_time ?? '';
            $checkout_time = $hotel_info->checkout_time ?? '';

            $total_amount = $order->total_amount??0;
            $sub_total_amount = $order->sub_total_amount??0;
                /*$agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                if($agent_id && $agent_id > 0) {
                    $total_amount = $order->sub_total_amount??0;
                }*/

                $hotel_data = array(
                    'hotel_name' => $booking_details->hotel_name ?? '',
                    'contact_phone' => $booking_details->contact_phone ?? '',
                    'contact_email' => $booking_details->contact_email ?? '',
                    'contact_person' => $booking_details->contact_person ?? '',
                    'room_name' => $booking_details->room_name ?? '',
                    'plan_name' => $booking_details->plan_name ?? '',
                    'destination_name' => $booking_details->destination_name ?? '',
                    'checkin' => $booking_details->checkin ?? '',
                    'checkin_time' => !empty($checkin_time)?date('h:i A',strtotime($checkin_time)):'',
                    'checkout' => $booking_details->checkout ?? '',
                    'checkout_time' => !empty($checkout_time)?date('h:i A',strtotime($checkout_time)):'',
                    'trip_date' => $booking_details->trip_date ?? '',
                    'created_at' => $order->created_at ? CustomHelper::DateFormat($order->created_at,'Y-m-d h:i A'): '',
                    'adult' => $booking_details->adult ?? '',
                    'child' => $booking_details->child ?? '',
                    'hotel_charge' => $total_amount ?? '',
                    'sub_total_amount' => $sub_total_amount ?? '',
                    'paid_amount' => $order->partial_amount ?? '',
                    'address' => $booking_details->address ?? '',
                    'inventory' => $booking_details->inventory ?? '',
                    'inclusions' => $booking_details->inclusions ?? '',
                );

                $req_data  = array(
                    'order_id' => $order_id ?? '',
                    'package_id' => $order->package_id ?? '',
                    'title' => $booking_details->hotel_name ?? '',
                    'name' => $order->name ?? '',
                    'phone' => $order->phone ?? '',
                    'email' => $order->email ?? '',
                    'hotel_data' => json_encode($hotel_data) ?? '',
                    // 'remarks' => $request->remarks ?? '',
                );

                if($OrderServiceVoucher){
                    $is_saved = OrderServiceVoucher::where('order_id',$order_id)->update($req_data);
                    $voucher_id = $OrderServiceVoucher->id ?? '';
                    $msg = 'Hotel Voucher updated successfully.';
                    $orderStatusId = $order->orders_status;
                }else{
                    $is_saved = OrderServiceVoucher::create($req_data);
                    $voucher_id = $is_saved->id ?? '';
                    $msg = 'Hotel Voucher generated successfully.';

                    $orderStatusId = CustomHelper::getOrderStatus('voucher created');
                    $order->orders_status = $orderStatusId;
                    $order->save();
                }
                if($is_saved){

                    $success = true;
                    $order_id = $order->id??'';

                    $params = [];
                    $params['order_id'] = $order_id??'';
                    $params['comments'] = $msg;
                    $params['orders_status'] = CustomHelper::showEnquiryMaster($orderStatusId);
                    CustomHelper::RecordOrderStatusHistory($params);


                    //================================
                    $user_id = 0;
                    $user_name = '';
                    $user = auth()->guard('admin')->user();
                    if($user) {
                        $user_id = $user->id??0;
                        $user_name = $user->name??'';

                        $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
                        $module_desc =  !empty($new_data->title)?$new_data->title:'';
                        $new_data =(array) $new_data;
                        $new_data = json_encode($new_data);
                        $module_id= $order_id??'';
                        $params['user_id'] = $user_id;
                        $params['user_name'] = $user_name;
                        $params['module'] = 'Hotel Voucher';
                        $params['module_desc'] = $module_desc;
                        $params['module_id'] = $module_id;
                        $params['sub_module'] = '';
                        $params['sub_module_id'] = $voucher_id;
                        $params['sub_sub_module'] = "";
                        $params['sub_sub_module_id'] = 0;
                        $params['data_after_action'] = $new_data;
                        $params['action'] = (!empty($OrderServiceVoucher->id)) ? "Update" : "Generate";
                        CustomHelper::RecordActivityLog($params);
                    }

                    $OrderServiceVoucher = OrderServiceVoucher::where('id',$voucher_id)->first();
                    $sendVoucherMail = CustomHelper::sendVoucherMail($OrderServiceVoucher,$order);
                    //=================

                }else{
                  $msg = 'Hotel Voucher not generated';
              }

          }else{
            $msg = "We're sold out for your dates";          
        }

    }else if($order_type == 6){

      $booking_details = json_decode($order->booking_details);

      $inventory = $order->inventory;
      $params = [];
      $params['action'] = 'package_booking';
      $params['inventory'] = $inventory;
      $params['package'] = $order->package_id;
      $params['package_type'] = $order->package_type;
      $params['trip_date'] = CustomHelper::DateFormat($order->trip_date, 'Y-m-d');
      $package = Package::find($order->package_id);

      if($package && $package->is_activity == 1){
         $params['slot_time'] = CustomHelper::DateFormat($order->trip_date, 'H:i');
     }
     $check_inventory = CustomHelper::checkInventory($params);

     if($check_inventory){

        $order_id = $order->id;
        $OrderServiceVoucher = OrderServiceVoucher::where('order_id',$order_id)->first();

        $booking_details = json_decode($order->booking_details);

        $packageDestination = $package->packageDestination ?? '';
        $destination = $packageDestination->name ?? '';

        $total_amount = $order->total_amount??0;
        $sub_total_amount = $order->sub_total_amount??0;
       /*$agent_id = isset($order->agent_id) ? $order->agent_id : 0;
        if($agent_id && $agent_id > 0) {
            $total_amount = $order->sub_total_amount??0;
        }*/

        $package_data = array(
            'package_name' => $order->package_name ?? '',
            'trip_date' => $order->trip_date ?? '',
            'destination' => $destination ?? '',
            'duration' => $booking_details->duration ?? '',
            'package_charges' => $total_amount,
            'sub_total_amount' => $sub_total_amount,
            'paid_amount' => $order->partial_amount ?? '',
            'due_amount' => (int)$order->total_amount - (int)$order->partial_amount ?? '',
            'address' => $package->address ?? '',
            'contact_person' => $package->contact_person ?? '',
            'contact_phone' => $package->contact_phone ?? '',
            'contact_email' => $package->contact_email ?? '',
            'total_pax' =>  $booking_details->total_pax ?? '',
        );

        $req_data  = array(
            'order_id' => $order_id ?? '',
            'package_id' => $order->package_id ?? '',
            'title' => $order->package_name ?? '',
            'name' => $order->name ?? '',
            'phone' => $order->phone ?? '',
            'email' => $order->email ?? '',
            'package_data' => json_encode($package_data) ?? '',
        );

        if($OrderServiceVoucher) {
            $is_saved = OrderServiceVoucher::where('order_id',$order_id)->update($req_data);
            $voucher_id = $OrderServiceVoucher->id ?? '';
            $msg = 'Activity Voucher updated successfully.';
            $orderStatusId = $order->orders_status;
        } else {
            $is_saved = OrderServiceVoucher::create($req_data);
            $voucher_id = $is_saved->id ?? '';
            $msg = 'Activity Voucher generated successfully.';

            $orderStatusId = CustomHelper::getOrderStatus('voucher created');
            $order->orders_status = $orderStatusId;
            $order->save();
        }
        if($is_saved){

            $success = true;
            $order_id = $order->id??'';

            $params = [];
            $params['order_id'] = $order_id??'';
            $params['comments'] = $msg;
            $params['orders_status'] = CustomHelper::showEnquiryMaster($orderStatusId);
            CustomHelper::RecordOrderStatusHistory($params);

            $user_id = 0;
            $user_name = '';
            $user = auth()->guard('admin')->user();
            if($user) {
                $user_id = $user->id??0;
                $user_name = $user->name??'';

                $new_data = DB::table('order_service_voucher')->where('id',$voucher_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data = (array) $new_data;
                $new_data = json_encode($new_data);
                $module_id= $order_id??'';
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Activity Voucher';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = '';
                $params['sub_module_id'] = $voucher_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($OrderServiceVoucher->id)) ? "Update" : "Generate";

                CustomHelper::RecordActivityLog($params);
            }
            $OrderServiceVoucher = OrderServiceVoucher::where('id',$voucher_id)->first();
            $sendVoucherMail = CustomHelper::sendVoucherMail($OrderServiceVoucher,$order);
        }else{
          $msg = 'Activity Voucher not generated';
      }

  }else{
     $msg = "We're sold out for your dates";          
 }
}

$result['msg'] = $msg;
$result['success'] = $success;
return $result;

}

    public static function showPaymentStatus($payment_status=0) {
        $status = '';
        if($payment_status == 1) {
            $status ='<span style="color:green">Paid</span>';
        } else if($payment_status == 2) {
            $status = '<span style="color:red">Failed</span>';
        } elseif($payment_status == 3) {
            $status = '<span style="color:blue">Refunded</span>';
        } elseif($payment_status == 4) {
            $status = '<span style="color:blue">Debit</span>';
        } else {
            $status = '<span style="color:orange">Unpaid</span>';
        }
        return $status;
    }

    public static function PaymentGatewayRefund($order,$params=[]) {
        $payment_method = $order->method??'';
        $amount = $order->partial_amount??0;
        $payment = PaymentGateway::where(['value'=>'tpsl','status'=>1])->first();
        $tpsl_type = $payment->perameter_1;
        $merchantCode = $payment->perameter_2;
        $key = $payment->perameter_3;
        $iv = $payment->perameter_4;
        $schemeCode = $payment->perameter_5;

        $payment_description = $order->payment_description ? json_decode($order->payment_description):'';
        $txn_id  = $payment_description->txn_id ?? '';

        $reason = $params['reason']??'Booking payment reversed.';

        // For Test account of tpsl
        if($tpsl_type=='test'){
            $amount = '1.00';
        }

        include public_path("/tpsl_payment_gatway/TransactionRequestBean.php");
        $webServiceLocator = 'https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl';

        //Setting all values here
        $transactionRequestBean = new \TransactionRequestBean();

        $transactionRequestBean->requestType = 'R';
        $transactionRequestBean->merchantCode = $merchantCode;
        $transactionRequestBean->amount = $amount;
        $transactionRequestBean->currencyCode ='INR';
        $transactionRequestBean->email = $order->email ?? '';
        $transactionRequestBean->customerName = $order->name;
        $transactionRequestBean->returnURL = route('response_tpsl');
        $transactionRequestBean->shoppingCartDetails = $schemeCode.'_'.$amount.'_0.0';
        $transactionRequestBean->txnDate = date('Y-m-d');
        $transactionRequestBean->TPSLTxnID = $txn_id;
        $transactionRequestBean->merchantTxnRefNumber = $order->order_no;
        $transactionRequestBean->mobileNumber = $order->phone;
        $transactionRequestBean->bankCode = '470';
        $transactionRequestBean->custId = $order->id ?? rand(000000,999999);
        $transactionRequestBean->key = $key;
        $transactionRequestBean->iv = $iv;
        $transactionRequestBean->accountNo = '';
        $transactionRequestBean->webServiceLocator = $webServiceLocator;
        $transactionRequestBean->timeOut = 30;

        try {
            $response = $transactionRequestBean->getTransactionToken();
            // $response = $transactionResponseBean->getResponsePayload();
            if($response) {
                CustomHelper::captureSentryMessage($response);
            } else {
                CustomHelper::captureSentryMessage('TPSL Payment gateway no response');
            }
            $response1 = explode('|', $response);

            // prd($response1);
            if(!$response1) {
                $sendtoSentry  = 'No response from gateway';
                CustomHelper::captureSentryMessage($message = $response);
            } else {
                CustomHelper::captureSentryMessage(json_encode($response1));
                $firstToken = [];
                if(isset($response1[0])) {
                    $firstToken = explode('=', $response1[0]);
                }
                $txn_msg = [];
                if(isset($response1[1])) {
                    $txn_msg = explode('=', $response1[1]);
                }
                $clnt_txn_ref = [];
                $tpsl_txn_id = [];
                $txn_amt = [];
                if(isset($response1[3])) {
                    $clnt_txn_ref = explode('=', $response1[3]);
                }

                if(isset($response1[5])) {
                    $tpsl_txn_id = explode('=', $response1[5]);
                }
                if(isset($response1[6])) {
                    $txn_amt = explode('=', $response1[6]);
                }
                $status = $firstToken[1]??'';
                $clnt_txn_ref = $clnt_txn_ref[1]??'';
                $txn_msg = $txn_msg[1]??'';
                $tpsl_txn_id = $tpsl_txn_id[1]??'';
                $txn_amt = $txn_amt[1]??0;

                if($status == '0400' || $status == '400') {
                    $payDetails = array();
                    $payDetails['txn_id'] = $tpsl_txn_id; //$keyarray['txn_id'];
                    $payDetails['response'] = $response;
                    $payDetails['name'] = $order->name??'';
                    $payDetails['email'] = $order->email??'';
                    $payDetails['amount'] = $order->partial_amount??0;
                    $payDetails['payment_date'] = date('Y-m-d H:i A');
                    $payDetails['itemname'] = $order->package_name??'';
                    $payDetails['payer_email']= $order->email??'';
                    $payDetails['payer_name']=  $order->name??'';
                    $payDetails['comment'] = $reason??'';
                    $order_payments =[];
                    $order_payments['payment_method'] = CustomHelper::getPaymentGatewayName($payment_method);
                    $order_payments['order_id'] = $order->id;
                    $order_payments['order_no'] = $order->order_no;
                    $order_payments['user_id'] = $order->user_id;
                    $order_payments['amount'] = $order->partial_amount??0;
                    $order_payments['description'] = $reason??'';
                    $order_payments['status'] = 3; //refund

                    $order_payments['pg_description'] = json_encode($payDetails); //Recieved
                    $order_payments['pg_response'] = json_encode($response1);
                    $order_payments['pg_payment_id'] = $tpsl_txn_id;
                    $order_payments['pg_response_date'] = date('Y-m-d H:i:s');
                    $order_payments['pg_payment_status'] = 3;
                    $order_payments['payment_type'] = 'full_refund';
                    $is_saved = OrderPayments::create($order_payments);

                    $last_payment_id = $is_saved->id??NULL;
                    $order_id = $order->id ;
                    $order = Order::find($order_id);
                    $order->last_payment_id = $last_payment_id;
                    $order->payment_status = 3;
                    $order->comment = $reason??'';
                    $order->save();
                    return true;
                } else {
                    return false;
                }
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            CustomHelper::captureSentryMessage($message);
        }
    }

    public static function getOrderStatus($booking_status='') {
        $orders_status = 0;
        if(!empty($booking_status)) {
            $query = EnquiriesMaster::where('status',1)->where(['type'=>'order-status']);
            if($booking_status == 'new' || $booking_status == 'New') {
                $query->where(['slug'=>'new']);
            }
             if($booking_status == 'SUCCESS') {
                $query->where(['slug'=>'confirmed']);
            }
            if($booking_status == 'PENDING' || $booking_status == 'ON_HOLD' || $booking_status == 'HOLD' || $booking_status == 'Processing'  ) {
                $query->where(['slug'=>'processing']);
            }
            if($booking_status == 'FAILED' || $booking_status == 'ABORTED' || $booking_status == 'CANCELLED' || $booking_status == 'cancelled' || $booking_status == 'UNCONFIRMED' || $booking_status == 'DUPLICATE') {
                $query->where(['slug'=>'cancelled']);
            }
            if(strtolower($booking_status) == 'voucher created') {
                $query->where(['slug'=>'voucher_created']);
            }
            if(strtolower($booking_status) == 'voucher sent') {
                $query->where(['slug'=>'voucher_sent']);
            }
            $record = $query->first();
            $orders_status = $record->id??0;
        }
        return $orders_status;
    }

    public static function showOrderStatus($orders_status='') {
        $status = '';
        $query = EnquiriesMaster::where('status',1)->where('id',$orders_status)->where(['type'=>'order-status'])->first();
        $category = $query->category??'';
        if(!empty($category)){
            if($category == 'New' || $category == 'new') {
                $status ='New'; 
            } else if($category == 'confirmed' || $category == 'Confirmed') {
                $status = 'Confirmed';
            } elseif($category == 'processing' || $category == 'Processing') {
                $status = 'Processing';
            }elseif($category == 'Cancelled' || $category == 'cancelled') {
                $status = 'Cancelled';
            }
        }
        return $status;
    }

    public static function showOrderStatusColor($orders_status='') {
        $status = '';
        $query = EnquiriesMaster::where('status',1)->where('id',$orders_status)->where(['type'=>'order-status'])->first();
        $category = $query->category??'';
        if(!empty($category)){
            if($category == 'New' || $category == 'new') {
                $status ='<strong><span style="background: #0275d8;color: #fff;padding: 5px 10px;border-radius: 100px;">New</span></strong>'; 
            } else if($category == 'confirmed' || $category == 'Confirmed') {
                $status = '<strong><span style="background: #20a720;color: #fff;padding: 5px 10px;border-radius: 100px;">Confirmed</span></strong>';
            } elseif($category == 'processing' || $category == 'Processing') {
                $status = '<strong><span style="background: #ffa500;color: #fff;padding: 5px 10px;border-radius: 100px;">Processing</span></strong>';
            }elseif($category == 'Cancelled' || $category == 'cancelled') {
                $status = '<strong><span style="background: #ff1100;color: #fff;padding: 5px 10px;border-radius: 100px;">Cancelled</span></strong>';
            }
        }
        return $status;
    }

    public static function insufficientBalanceAlert($order_id='') {
        $booking_id = '';
        if($order_id) {
            $order = Order::find($order_id);
            if($order) {
                $booking_id = $order->order_no??'';

                $FRONTEND_LOGO = CustomHelper::WebsiteSettings('FRONTEND_LOGO');
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc = asset(config('custom.assets').'/images/logo.png');
                if(!empty($FRONTEND_LOGO)){
                    if($storage->exists($path.$FRONTEND_LOGO)){
                        $logoSrc = asset('/storage/'.$path.$FRONTEND_LOGO);
                    }
                }

                // AGENT LOGO
                $agent_id = '';
                $agent_phone = '';
                $agent_email = '';
                /*$agent_id = isset($order->agent_id) ? $order->agent_id : 0;
                if($agent_id && $agent_id > 0){
                    $agentLogo = '';
                    $userAgentInfo = $order->agentInfo ?? '';
                    if($userAgentInfo && $userAgentInfo->count() > 0){
                        $path = 'agent_logo/';
                        $agentLogo = $order->agentInfo->agent_logo ?? '';

                        $userData = $userAgentInfo->User ?? '';
                        $agent_phone = '';
                        $agent_email = '';
                        if($userData->phone) {
                            $country_code = $userData->country_code ?? '91';
                            $agent_phone = '+'.$country_code.'-'.$userData->phone;
                        }
                        $agent_email = !empty($userData->email)?$userData->email:'';
                    }
                    if(!empty($agentLogo)){
                        if($storage->exists($path.$agentLogo)){
                            $logoSrc = asset('/storage/'.$path.$agentLogo);
                        }
                    }
                }*/

                $common_logo = '';
                if(!empty($agent_id)){
                    $B2B_HEADER = CustomHelper::WebsiteSettings('B2B_HEADER');
                    $b2b_logo_params = array(
                        '{agent_logo}' => $logoSrc
                    );
                    $common_logo .= strtr($B2B_HEADER, $b2b_logo_params);
                } else {
                    $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                    $b2c_logo_params = array(
                       '{company_logo}' => $logoSrc
                   );
                    $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);
                }

                $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                $sales_phone = CustomHelper::getPhoneHref($company_phone);
                $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                $sales_email = CustomHelper::getEmailHref($company_email);


                $contact_details = '';
                if(!empty($agent_id)) {
                    $B2B_FOOTER = CustomHelper::WebsiteSettings('B2B_FOOTER');
                    $b2b_email_params = array(
                        '{agent_phone}' => $agent_phone,
                        '{agent_email}' => $agent_email
                    );
                    $contact_details .= strtr($B2B_FOOTER, $b2b_email_params);
                } else {
                    $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                    $b2c_email_params = array(
                        '{company_name}' => $company_name,
                        '{sales_mobile}' => $sales_mobile,
                        '{sales_phone}' => $sales_phone,
                        '{sales_email}' => $sales_email,
                        '{company_title}' => $system_title
                    );
                    $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);
                }

                $template_slug = 'insufficient-balance-alert';
                $email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                if($email_content_data) {
                    $email_params = [];
                    $email_params['{booking_id}'] = $booking_id;
                    $email_params['{header}'] = $common_logo;
                    $email_params['{logo}'] = $logoSrc;
                    $email_params['{contact_details}'] = $contact_details;

                    $subject_params = [];
                    $subject_params['{booking_id}'] = $booking_id;

                    $email_content = $email_content_data->content??'';
                    $email_content = strtr($email_content, $email_params);

                    $email_subject = $email_content_data->subject??'';
                    $email_subject = strtr($email_subject, $email_params);
                    $email_type = $email_content_data->email_type??'';
                    $email_bcc_admin = $email_content_data->bcc_admin??'';

                    $ADMIN_EMAIL = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                    $to_email = $ADMIN_EMAIL;
                    $cc_email = '';
                    $bcc_email = '';
                    $REPLYTO = '';
                    if($email_type == 'admin') {
                        $to_email = $ADMIN_EMAIL;
                    }
                    if($email_bcc_admin == 1) {
                        $bcc_email = $ADMIN_EMAIL;
                    }
                    if($email_subject && $email_content) {
                        $params = [];
                        $params['to'] = $to_email;
                        $params['reply_to'] = $REPLYTO;
                        $params['cc'] = $cc_email;
                        $params['bcc'] = $bcc_email;
                        $params['subject'] = $email_subject;
                        $params['email_content'] = $email_content;
                        $params['module_name'] = 'Insufficient Balance Alert';
                        $params['record_id'] = $order_id;
                        $is_mail1 = CustomHelper::sendCommonMail($params);
                    }
                }
            }
        }
        CustomHelper::captureSentryMessage('Tripjack insufficient balance alert for booking_id: '.$booking_id);
    }

    public static function getPendingOrdersCount() {
        $status_query = EnquiriesMaster::where('status',1)->where(['type'=>'order-status']);
        $status_query->where(function($q1){
            $q1->where('category','processing');
            $q1->orWhere('category','new');
        });
        $status_arr = $status_query->get()->pluck('id')->toArray();

        $query = Order::orderBy('id', 'desc');
        $query->where('payment_status',1);
        $query->where('cancel_request',0);
        $query->where(function($q1) use ($status_arr) {
            $q1->where(function($q2) use ($status_arr) {
                $q2->where('order_type','!=',3);
                $q2->whereIn('orders_status',$status_arr);
            });
            $q1->orWhere(function($q2) {
                $q2->where('order_type',3);
                $q2->whereNotIn('status',['SUCCESS','PENDING','ON_HOLD']);
            });
        });
        $user = auth()->user();
        if($user) {
            $user_id = $user->id??0;
            $is_vendor = $user->is_vendor??0;
            if($is_vendor ==1) {
                $query->where('vendor_id',$user_id);
            }
        }
        $from_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
        $to_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        if(!empty($from_date)) {
            $query->whereDate('created_at','>=',$from_date);
        }
        if(!empty($to_date)) {
            $query->whereDate('created_at','<=',$to_date);
        }
        $count = $query->count();
        return $count;
    }


    public static function pendingCancellationCount() {
        // $status_query = EnquiriesMaster::where('status',1)->where(['type'=>'order-status']);
        // $status_query->where(function($q1){
        //     $q1->where('category','processing');
        //     $q1->orWhere('category','new');
        // });
        // $status_arr = $status_query->get()->pluck('id')->toArray();

        $query = Order::orderBy('id', 'desc');
        // $query->where('payment_status',1);
        $query->where('cancel_request',1);
        // $query->where(function($q1) use ($status_arr) {
        //     $q1->where(function($q2) use ($status_arr) {
        //         $q2->where('order_type','!=',3);
        //         $q2->whereIn('orders_status',$status_arr);
        //     });
        //     $q1->orWhere(function($q2) {
        //         $q2->where('order_type',3);
        //         $q2->whereNotIn('status',['SUCCESS','PENDING','ON_HOLD']);
        //     });
        // });
        $user = auth()->user();
        if($user) {
            $user_id = $user->id??0;
            $is_vendor = $user->is_vendor??0;
            if($is_vendor ==1) {
                $query->where('vendor_id',$user_id);
            }
        }
        $from_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
        $to_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        if(!empty($from_date)) {
            $query->whereDate('created_at','>=',$from_date);
        }
        if(!empty($to_date)) {
            $query->whereDate('created_at','<=',$to_date);
        }
        $count = $query->count();
        return $count;
    }


    public static function pendingAmendmentCount() {
        $query = OrderAmendments::orderBy('id', 'desc');
        $query->where('status',0);
        $user = auth()->user();
        if($user) {
            $user_id = $user->id??0;
            $is_vendor = $user->is_vendor??0;
            if($is_vendor == 1) {
                // $query->where('vendor_id',$user_id);
            }
        }
        $from_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
        $to_date = CustomHelper::DateFormat(date('Y-m-d'),'Y-m-d','d/m/Y');
        $from_date = $from_date.' 00:00:00';
        $to_date = $to_date.' 23:59:59';
        if(!empty($from_date)) {
            $query->whereDate('created_at','>=',$from_date);
        }
        if(!empty($to_date)) {
            $query->whereDate('created_at','<=',$to_date);
        }
        $count = $query->count();
        return $count;
    }

    public static function getJsMenu() {
        $response = [];
        $response['success'] = false;

        $topMenu = CustomHelper::getMenu('main-header-menu');
        $menuItems = (isset($topMenu->menuParentItems))?$topMenu->menuParentItems:'';
        $headerMenuList = CustomHelper::getMenuForFront($menuItems, $is_parent = true, $parent_class='theme-nav', $child_class='theme-nav-li', $child_parent_class='sub-menu', $have_child_class='has-dropdown');

        $topMenu = CustomHelper::getMenu('main-footer-menu');
        $menuItems = (isset($topMenu->menuParentItems))?$topMenu->menuParentItems:'';
        $footerMenuList = CustomHelper::getMenuForFront($menuItems, $is_parent = true, $parent_class='theme-nav', $child_class='', $child_parent_class='sub-menu', $have_child_class='');

        $websiteSettingsArr = CustomHelper::getSettings(['HTML_HEAD_TOP','HTML_BODY_TOP','HTML_HEAD_BOTTOM','HTML_BODY_BOTTOM','SYSTEM_TITLE', 'FRONTEND_LOGO', 'SITE_EMAIL', 'WHATSAPP1', 'WHATSAPP2', 'GOOGLE_TRANSLATOR_MANAGEMENT', 'SALES_PHONE', 'SALES_EMAIL', 'WHATSAPP', 'FACEBOOK', 'TWITTER', 'INSTAGRAM', 'LINKEDIN','YOUTUBE','SITE_ADDRESS', 'SITE_PHONE', 'SITE_PHONE1', 'SITE_PHONE2','FLIGHTS_FEW_SEATS_LEFT_ALERT','FAVICON_LOGO','BOOKING_QUERIES_NO','FACEBOOK_SHARE','TWITTER_SHARE','INSTAGRAM_SHARE','WHATSAPP_SHARE','GOOGLE_API_KEY','INTERCITY_TRIP','OUTSTATION','AIRPORT','RAILWAYS','SIGHTSEEING','PACKAGE_GST_TAX','PACKAGE_TCS_TAX','CAB_BOOKING_AFTER_HOURS','FRONTEND_LOGO_AlT_TEXT']);

        $storage = Storage::disk('public');
        $path = "settings/";
        $logoSrc = asset(config('custom.assets').'/images/header_logo.png');
        if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
            $logo = $websiteSettingsArr['FRONTEND_LOGO'];
            if($storage->exists($path.$logo)){
                $logoSrc = asset('/storage/'.$path.$logo);
            }
        }

        $path = "settings/";
        $favIconSrc = asset(config('custom.assets').'/img/fav-icon.png');
        if(!empty($websiteSettingsArr['FAVICON_LOGO'])){
            $logo = $websiteSettingsArr['FAVICON_LOGO'];
            if($storage->exists($path.$logo)){
                $favIconSrc = asset('/storage/'.$path.$logo);
            }
        }

        $websiteSettings_arr = [];
        $websiteSettings_arr['FRONTEND_LOGO'] = $logoSrc;
        $websiteSettings_arr['FAVICON_LOGO'] = $favIconSrc;
        $websiteSettings_arr['FRONTEND_URL'] = url('/');
        $websiteSettings_arr['FRONTEND_LOGIN_URL'] = route('account.login');
        $websiteSettings_arr['AGENT_SIGNUP_URL'] = route('account.agent_signup');
        $websiteSettings_arr['FRONTEND_ASSETS_URL'] = asset(config('custom.assets'));
        $websiteSettings_arr['SYSTEM_TITLE'] = $websiteSettingsArr['SYSTEM_TITLE']??'';
        $websiteSettings_arr['SYSTEM_YEAR'] = date('Y');
        $websiteSettings_arr['SITE_EMAIL'] = $websiteSettingsArr['SITE_EMAIL']??'';
        $websiteSettings_arr['WHATSAPP1'] = $websiteSettingsArr['WHATSAPP1']??'';
        $websiteSettings_arr['WHATSAPP2'] = $websiteSettingsArr['WHATSAPP2']??'';
        $websiteSettings_arr['GOOGLE_TRANSLATOR_MANAGEMENT'] = $websiteSettingsArr['GOOGLE_TRANSLATOR_MANAGEMENT']??'';
        $websiteSettings_arr['SALES_PHONE'] = $websiteSettingsArr['SALES_PHONE']??'';
        $websiteSettings_arr['SALES_EMAIL'] = $websiteSettingsArr['SALES_EMAIL']??'';
        $websiteSettings_arr['BOOKING_QUERIES_NO'] = $websiteSettingsArr['BOOKING_QUERIES_NO']??'';
        $websiteSettings_arr['WHATSAPP'] = $websiteSettingsArr['WHATSAPP']??'';
        $websiteSettings_arr['FACEBOOK'] = $websiteSettingsArr['FACEBOOK']??'';
        $websiteSettings_arr['TWITTER'] = $websiteSettingsArr['TWITTER']??'';
        $websiteSettings_arr['INSTAGRAM'] = $websiteSettingsArr['INSTAGRAM']??'';
        $websiteSettings_arr['FACEBOOK_SHARE'] = $websiteSettingsArr['FACEBOOK_SHARE']??'';
        $websiteSettings_arr['TWITTER_SHARE'] = $websiteSettingsArr['TWITTER_SHARE']??'';
        $websiteSettings_arr['INSTAGRAM_SHARE'] = $websiteSettingsArr['INSTAGRAM_SHARE']??'';
        $websiteSettings_arr['WHATSAPP_SHARE'] = $websiteSettingsArr['WHATSAPP_SHARE']??'';
        $websiteSettings_arr['INTERCITY_TRIP'] = $websiteSettingsArr['INTERCITY_TRIP']??'';
        $websiteSettings_arr['OUTSTATION'] = $websiteSettingsArr['OUTSTATION']??'';
        $websiteSettings_arr['AIRPORT'] = $websiteSettingsArr['AIRPORT']??'';
        $websiteSettings_arr['RAILWAYS'] = $websiteSettingsArr['RAILWAYS']??'';
        $websiteSettings_arr['SIGHTSEEING'] = $websiteSettingsArr['SIGHTSEEING']??'';

        $websiteSettings_arr['PACKAGE_GST_TAX'] = $websiteSettingsArr['PACKAGE_GST_TAX']??0;

        $websiteSettings_arr['PACKAGE_TCS_TAX'] = $websiteSettingsArr['PACKAGE_TCS_TAX']??0;

        $websiteSettings_arr['GOOGLE_API_KEY'] = $websiteSettingsArr['GOOGLE_API_KEY']??'';
        
        $websiteSettings_arr['LINKEDIN'] = $websiteSettingsArr['LINKEDIN']??'';
        $websiteSettings_arr['FRONTEND_LOGO_AlT_TEXT'] = $websiteSettingsArr['FRONTEND_LOGO_AlT_TEXT']??'';
        $websiteSettings_arr['YOUTUBE'] = $websiteSettingsArr['YOUTUBE']??'';
        $websiteSettings_arr['SITE_ADDRESS'] = $websiteSettingsArr['SITE_ADDRESS']??'';
        $websiteSettings_arr['SITE_PHONE'] = $websiteSettingsArr['SITE_PHONE']??'';
        $websiteSettings_arr['SITE_PHONE1'] = $websiteSettingsArr['SITE_PHONE1']??'';
        $websiteSettings_arr['SITE_PHONE2'] = $websiteSettingsArr['SITE_PHONE2']??'';
        $websiteSettings_arr['FLIGHTS_FEW_SEATS_LEFT_ALERT'] = $websiteSettingsArr['FLIGHTS_FEW_SEATS_LEFT_ALERT']??'';
        $websiteSettings_arr['HTML_HEAD_TOP'] = $websiteSettingsArr['HTML_HEAD_TOP']??'';
        $websiteSettings_arr['HTML_BODY_TOP'] = $websiteSettingsArr['HTML_BODY_TOP']??'';
        $websiteSettings_arr['HTML_HEAD_BOTTOM'] = $websiteSettingsArr['HTML_HEAD_BOTTOM']??'';
        $websiteSettings_arr['HTML_BODY_BOTTOM'] = $websiteSettingsArr['HTML_BODY_BOTTOM']??'';
        $websiteSettings_arr['CAB_BOOKING_AFTER_HOURS'] = (int)($websiteSettingsArr['CAB_BOOKING_AFTER_HOURS']??0);
        $websiteSettings_arr['COUNTRY_CODE_URL'] = route('common.ajax_load_country_code');

        $websiteSettings_arr['APP_NAME'] = CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        $cms_listing = CustomHelper::getSeoModules('cms_listing');
        $cmsListing = $cms_listing->page_url??'';
        $cmsDetail = $cms_listing->page_detail_url??'';

        $websiteSettings_arr['PRIVACY_LINK'] = url($cmsDetail.'/terms-conditions');
        $websiteSettings_arr['TERMS_SERVICE_LINK'] = url($cmsDetail.'/terms-conditions');

        $onlineBooking = [];
        $FLIGHT_URL = '';
        if(CustomHelper::isAllowedModule('flight')) {
            $FLIGHT_URL = route('flight.index');
            $onlineBooking['flight'] = self::isOnlineBooking('flight');
        }

        $PACKAGE_URL = '';
        if(CustomHelper::isAllowedModule('package_listing')) {
            $PACKAGE_URL = route('packageListing');
            $onlineBooking['package_listing'] = self::isOnlineBooking('package_listing');
        }

        $ACTIVITY_URL = '';
        if(CustomHelper::isAllowedModule('activity_listing')) {
            $ACTIVITY_URL = route('activityListing');
            $onlineBooking['activity_listing'] = self::isOnlineBooking('activity_listing');
        }

        $HOTEL_URL = '';
        if(CustomHelper::isAllowedModule('hotel_listing')) {
            $HOTEL_URL = route('hotelListing');
            $onlineBooking['hotel_listing'] = self::isOnlineBooking('hotel_listing');
        }

        $CAB_URL = '';
        if(CustomHelper::isAllowedModule('cab')) {
            $CAB_URL = route('cab.index');
            $onlineBooking['cab'] = self::isOnlineBooking('cab');
        }

        $RENTAL_URL = '';
        if(CustomHelper::isAllowedModule('rental')) {
            $RENTAL_URL = route('bikeListing');
            $onlineBooking['rental'] = self::isOnlineBooking('rental');
        }
        $websiteSettings_arr['onlineBooking'] = $onlineBooking;


        $DESTINATION_URL = '';
        $INTERNATIONAL_URL = '';
        $DOMESTIC_URL = '';
        if(CustomHelper::isAllowedModule('destination_listing')) {
            $DESTINATION_URL = route('destinationListing');
            $INTERNATIONAL_URL = route('destinationListing',['international'=>1]);
            $DOMESTIC_URL = route('destinationListing',['international'=>0]);
        }

        $BLOG_URL = route('blogsListing');
        $TEAM_URL = route('teamListing');
        $TESTIMONIAL_URL = route('testimonialListing');
        $ajaxSearchPackages = route('ajaxSearchPackages');
        $ajaxSearchHotels = route('ajaxSearchHotels');
        $noOfDays = ['1-4'=>'1-4 Days','5-8'=>'5-8 Days','9-15'=>'9-15 Days','16-30'=>'16-30 Days'];

        $months = [];
        for($i=0; $i < 12; $i++) {
            $month = date('Y-m',strtotime('+'.$i.'month'));
            //$months[$month] = CustomHelper::DateFormat($month,'M Y');
            $months[$month] = CustomHelper::DateFormat($month,'M');
        }

        $start_time = CustomHelper::WebsiteSettings('RENTAL_START_TIME');
        $end_time = CustomHelper::WebsiteSettings('RENTAL_END_TIME');
        $interval_minutes = 15;

        $timeArr = CustomHelper::generate_time_intervals($start_time, $end_time, $interval_minutes);

        // Output the array of time intervals
        // prd($timeArr);

        $websiteSettings_arr['FLIGHT_URL'] = $FLIGHT_URL;
        $websiteSettings_arr['PACKAGE_URL'] = $PACKAGE_URL;
        $websiteSettings_arr['ACTIVITY_URL'] = $ACTIVITY_URL;
        $websiteSettings_arr['HOTEL_URL'] = $HOTEL_URL;
        $websiteSettings_arr['CAB_URL'] = $CAB_URL;
        $websiteSettings_arr['RENTAL_URL'] = $RENTAL_URL;
        $websiteSettings_arr['DESTINATION_URL'] = $DESTINATION_URL;
        $websiteSettings_arr['INTERNATIONAL_URL'] = $INTERNATIONAL_URL;
        $websiteSettings_arr['DOMESTIC_URL'] = $DOMESTIC_URL;
        $websiteSettings_arr['BLOG_URL'] = $BLOG_URL;
        $websiteSettings_arr['TEAM_URL'] = $TEAM_URL;
        $websiteSettings_arr['TESTIMONIAL_URL'] = $TESTIMONIAL_URL;

        $tour_category = CustomHelper::getSeoModules('tour_category');
        if($tour_category) {
            $websiteSettings_arr['TOUR_CATEGORY_URL'] = $tour_category->page_url;            
            $websiteSettings_arr['TOUR_CATEGORY_DETAIL_URL'] = $tour_category->page_detail_url;            
        }

        $destination_listing = CustomHelper::getSeoModules('destination_listing');
        if($destination_listing) {
            $websiteSettings_arr['DESTINATION_DETAIL_URL'] = $destination_listing->page_detail_url;
        }

        $websiteSettings_arr['ajaxSearchPackages'] = $ajaxSearchPackages;
        $websiteSettings_arr['ajaxSearchHotels'] = $ajaxSearchHotels;
        $websiteSettings_arr['noOfDays'] = $noOfDays;
        $websiteSettings_arr['months'] = $months;
        $websiteSettings_arr['timeArr'] = $timeArr;

        $is_agent = Auth::user()->is_agent??0;

        $query = AirlineMarkup::where('status',1)->orderBy('sort_order','asc');
        $records = $query->get();
        $data['records'] = $records;
        $supplier_markup = [];
        if($records) {
            foreach($records as $row) {
                $supplier_markup[] = [
                    'code' => $row->code,
                    'offer_markup_type' => $row->offer_markup_type,
                    'offer_markup_value' => $row->offer_markup_value,
                    'online_markup_type' => $row->online_markup_type,
                    'online_markup_value' => $row->online_markup_value,
                    'agent_markup_type' => $row->agent_markup_type,
                    'agent_markup_value' => $row->agent_markup_value,
                ];
            }
        }
        $websiteSettings_arr['supplier_markup'] = $supplier_markup;        
        
        if($is_agent==1) {
            $agent_id = Auth::user()->id;
            $agent_group_id = Auth::user()->group_id;

            $agent_discount = [];
            $discount = AirlineDc::where('agent_group_id',$agent_group_id)->where('type','discount')->first();
            if($discount) {
                $agent_discount[] = [
                    'code' => 'domestic',
                    'offer_discount_type' => $discount->domestic_offer_type,
                    'offer_discount_value' => $discount->domestic_offer_value,
                    'online_discount_type' => $discount->domestic_online_type,
                    'online_discount_value' => $discount->domestic_online_value
                ];

                $agent_discount[] = [
                    'code' => 'international',
                    'offer_discount_type' => $discount->international_offer_type,
                    'offer_discount_value' => $discount->international_offer_value,
                    'online_discount_type' => $discount->international_online_type,
                    'online_discount_value' => $discount->international_online_value
                ];
            }
            $websiteSettings_arr['agent_discount'] = $agent_discount;

            $query = AgentAirlineMarkup::where('agent_id',$agent_id);
            $records = $query->get();
            $data['records'] = $records;
            $agent_markup = [];
            if($records) {
                foreach($records as $row) {
                    $agent_markup[] = [
                        'code' => $row->code,
                        'offer_markup_type' => $row->offer_markup_type,
                        'offer_markup_value' => $row->offer_markup_value,
                        'online_markup_type' => $row->online_markup_type,
                        'online_markup_value' => $row->online_markup_value
                    ];
                }
            }               
            $websiteSettings_arr['agent_markup'] = $agent_markup;
        }

        $response['success'] = true;
        $response['headerMenuList'] = $headerMenuList;
        $response['footerMenuList'] = $footerMenuList;
        $response['websiteSettings'] = $websiteSettings_arr;
        return $response;
    }

    public static function getSearchData($module,$params=[]) {
        $search_text = $params['search_text']??'';
        $search_slug = $params['search_slug']??'';
        $text = (request()->has('text')) ? request()->text : $search_text;
        $page = (request()->has('page')) ? request()->page : '';
        $start_time = CustomHelper::WebsiteSettings('RENTAL_START_TIME');
        $end_time = CustomHelper::WebsiteSettings('RENTAL_END_TIME');

        $search_data = [];
        if($text) {
            $search_data['text'] = $text;
        }
        if($search_slug) {
            $search_data['search_slug'] = $search_slug;
        }
        if($page) {
            $search_data['page'] = $page;
        }
        if($module == 'package') {
            $segments1 = request()->segment(1);
            if($segments1) {
                $search_data['segments1'] = $segments1;
            }
            $segments2 = request()->segment(2);
            if($segments2) {
                $search_data['segments2'] = $segments2;
            }

            if(request()->has('filter_tour_type')) {
                $search_data['filter_tour_type'] = request()->filter_tour_type;
            } if(request()->has('categories')) {
                $search_data['categories'] = request()->categories;
            } if(request()->has('destinations')) {
                $search_data['destinations'] = request()->destinations;
            } if(request()->has('filter_package_budget')) {
                $search_data['filter_package_budget'] = request()->filter_package_budget;
            } if(request()->has('filter_month')) {
                $search_data['filter_month'] = request()->filter_month;
            }if(request()->has('sort_by_price')) {
                $search_data['sort_by_price'] = request()->sort_by_price;
            }if(request()->has('tour_category')) {
                $search_data['tour_category'] = request()->tour_category;
            }if(request()->has('smonth')) {
                $search_data['smonth'] = request()->smonth;
            }if(request()->has('sno_of_days')) {
                $search_data['sno_of_days'] = request()->sno_of_days;
            }if(request()->has('flag')) {
                $search_data['flag'] = request()->flag;
            }
        } else if($module == 'activity') {
            $segments1 = request()->segment(1);
            if($segments1) {
                $search_data['segments1'] = $segments1;
            }
            $segments2 = request()->segment(2);
            if($segments2) {
                $search_data['segments2'] = $segments2;
            }
            
            if(request()->has('dep')) {
                $search_data['dep'] = request()->dep;
            } if(request()->has('filter_tour_type')) {
                $search_data['filter_tour_type'] = request()->filter_tour_type;
            } if(request()->has('categories')) {
                $search_data['categories'] = request()->categories;
            } if(request()->has('destinations')) {
                $search_data['destinations'] = request()->destinations;
            } if(request()->has('filter_package_budget')) {
                $search_data['filter_package_budget'] = request()->filter_package_budget;
            } if(request()->has('filter_month')) {
                $search_data['filter_month'] = request()->filter_month;
            }if(request()->has('sort_by_price')) {
                $search_data['sort_by_price'] = request()->sort_by_price;
            }if(request()->has('tour_category')) {
                $search_data['tour_category'] = request()->tour_category;
            }if(request()->has('smonth')) {
                $search_data['smonth'] = request()->smonth;
            }if(request()->has('sno_of_days')) {
                $search_data['sno_of_days'] = request()->sno_of_days;
            }if(request()->has('flag')) {
                $search_data['flag'] = request()->flag;
            }
        } else if($module == 'hotel') {
            if(request()->has('dep')) {
                $search_data['dep'] = request()->dep;
            } if(request()->has('features')) {
                $search_data['features'] = request()->features;
            }  if(request()->has('stars')) {
                $search_data['stars'] = request()->stars;
            }  if(request()->has('class')) {
                $search_data['class'] = request()->class;
            } if(request()->has('sdest')) {
                $search_data['sdest'] = request()->sdest;
            } if(request()->has('sno_of_days')) {
                $search_data['sno_of_days'] = request()->sno_of_days;
            } if(request()->has('smonth')) {
                $search_data['smonth'] = request()->smonth;
            } if(request()->has('categories')) {
                $search_data['categories'] = request()->categories;
            } if(request()->has('details')) {
                $search_data['details'] = request()->details;
            } if(request()->has('destination')) {
                $search_data['destination'] = request()->destination;
            }
            if(isset($params['destination'])) {
                $search_data['destination'] = $params['destination'];
            }

            $checkin = date('Y-m-d',strtotime('+6 days'));
            $checkout = date('Y-m-d',strtotime('+7 days'));
            $inventory = 1;
            $adult = 2;
            $child = 0;
            if(request()->has('checkin')) {
                $checkin = request()->checkin;
                if(request()->has('checkout')) {
                    $checkout = request()->checkout;
                }

                $search_data['checkin'] = $checkin;
                $search_data['checkout'] = $checkout;
                $search_data['checkin_picker'] = CustomHelper::DateFormat($checkin,'d/m/Y');
                $search_data['checkout_picker'] = CustomHelper::DateFormat($checkout,'d/m/Y');

                if(request()->has('inventory')) {
                    $inventory = request()->inventory;
                } if(request()->has('adult')) {
                    $adult = request()->adult;
                } if(request()->has('child')) {
                    $child = request()->child;
                }
                $search_data['inventory'] = $inventory;
                $search_data['adult'] = $adult;
                $search_data['child'] = $child;
                $guest_title = $adult.' Adults';
                if($child) {
                  $guest_title = $guest_title.' + '.$child.' Child';
                }
                if($inventory) {
                  $guest_title = $guest_title.' | '.$inventory.' Room(s)';
                }
                $search_data['guest_title'] = $guest_title;
            } else {
                $default_filters = [];
                $default_filters['checkin'] = $checkin;
                $default_filters['checkout'] = $checkout;
                $default_filters['checkin_picker'] = CustomHelper::DateFormat($checkin,'d/m/Y');
                $default_filters['checkout_picker'] = CustomHelper::DateFormat($checkout,'d/m/Y');
                $default_filters['inventory'] = $inventory;
                $default_filters['adult'] = $adult;
                $default_filters['child'] = $child;
                $guest_title = $adult.' Adults';
                if($child) {
                  $guest_title = $guest_title.' + '.$child.' Child';
                }
                if($inventory) {
                  $guest_title = $guest_title.' | '.$inventory.' Room(s)';
                }
                $default_filters['guest_title'] = $guest_title;
                $search_data['default_filters'] = $default_filters;
            }
        } else if($module == 'destination') {
            if(request()->has('international')) {
                $search_data['international'] = request()->international;
            }
            if(request()->has('flag')) {
                $search_data['flag'] = request()->flag;
            }
            if(request()->has('destination_type')) {
                $search_data['destination_type'] = request()->destination_type;
            }
        } else if($module == 'rental') {

            $currentHour = date('H');
            $Date = date('Y-m-d');
            $currentDate =  date('Y-m-d', strtotime($Date. ' +1 days'));    
            if($currentHour > 16) {
                $currentDate =  date('Y-m-d', strtotime($Date. ' +2 days'));    
            }

            if(request()->has('key')) {
                $search_data['key'] = request()->key;
            } if(request()->has('model')) {
                $search_data['model'] = request()->model;
            } if(request()->has('types')) {
                $search_data['types'] = request()->types;
            } if(request()->has('locations')) {
                $search_data['locations'] = request()->locations;
            } if(request()->has('locations')) {
                $search_data['locations'] = request()->locations;
            } if(request()->has('pickupDate')) {
                if($currentDate )
                    $search_data_pickupDate = CustomHelper::DateFormat(request()->pickupDate,'Y-m-d','d/m/Y');
                    if(strtotime($search_data_pickupDate) <= strtotime($currentDate)){ 
                        $search_data_pickupDate = $currentDate;
                    }    
                $search_data['pickupDate'] = $search_data_pickupDate;
            } else {
                $search_data['pickupDate'] = $currentDate;
            } if(request()->has('pickupTime')) {
                $search_data['pickupTime'] = request()->pickupTime;
            } else {
                $search_data['pickupTime'] = $start_time;
            } if(request()->has('dropDate')) {

                $search_data_dropDate = CustomHelper::DateFormat(request()->dropDate,'Y-m-d','d/m/Y');
                    if(strtotime($search_data_dropDate) < strtotime($search_data_pickupDate)){ 
                       $search_data_dropDate =  date('Y-m-d',strtotime($search_data_pickupDate));
                    }
                $search_data['dropDate'] = $search_data_dropDate;

            } else {
                $dropOff_date = date('Y-m-d',strtotime($currentDate));
                $search_data['dropDate'] = $dropOff_date;
            } if(request()->has('dropTime')) {
                $search_data['dropTime'] = request()->dropTime;
            } else {
                $search_data['dropTime'] = $end_time;
            }
        }
        return $search_data;
    }

    public static function showFlightCancellationReason($key='') {
        $value = '';
        if($key) {
            $seo_tags = CustomHelper::getSeoModules('flight');
            $manager_email = $seo_tags->admin_email??'';
            $manager_phone = $seo_tags->admin_phone??'';
            $flight_cancellation_reasons = config('custom.flight_cancellation_reasons');
            $value = $flight_cancellation_reasons[$key]??'';
            if($value) {
                $value = str_replace(['{manager_email}','{manager_phone}'], [$manager_email,$manager_phone], $value);
            }
        }
        return $value;

    }

     public static function getBikeData($id) {

        $data = BikeMaster::where('id', $id)->first();
           return $data ??'';
    }

    public static function getBikeprice($city_id,$bike_id) {

       $priceData = BikecityPrice::where('city_id',$city_id)->where('bike_id',$bike_id)->first();
       $price = $priceData->price ?? 0;
       return $price ??'';
   }

   

   public static function getPaymentGatewayName($value='') {
        $name = $value;
        $payment_gateways = config('custom.payment_gateways');
        if(empty($payment_gateways)) {
            $payment_gateways = \Cache::rememberForever("payment_gateways", function () {
                $result = PaymentGateway::orderBy('id','desc')->get();
                $payment_gateways_arr = [];
                if(!empty($result)) {
                    foreach($result as $row) {
                        if($row->value) {
                            $payment_gateways_arr[$row->value] = $row->toArray();
                        }
                    }
                }
                return $payment_gateways_arr;
            });
            config(['custom.payment_gateways'=>$payment_gateways]);
        }
        if(!empty($value) && isset($payment_gateways[$value])) {
            $name = $payment_gateways[$value]['display_name']??$value;
        }
        return $name;
    }

    public static function generate_time_intervals($start_time, $end_time, $interval_minutes) {
        $start = strtotime($start_time);
        $end = strtotime($end_time);
        $intervals = array();

        while ($start <= $end) {
            
            $value  = date('h:i A', $start);
            $key = date('H:i', $start);
            // $intervals[$key] = $value;

            $intervals[] = array(
                    'key' => $key,
                    'title' =>$value,
                );

            $start += $interval_minutes * 60;
        }

        return $intervals;
    }

    public static function updateAccommodationPublishPrice($id='') {
        if($id) {
            $accommodation = Accommodation::find($id);
            if($accommodation) {
                $accommodation_room = AccommodationRoom::with('planData')->where('accommodation_id',$id)->where('status',1)->orderBy('is_default','desc')->orderBy('sort_order','asc')->first();
                $search_price = 0;
                $publish_price = 0;
                if($accommodation_room) {
                    /*$search_price = $accommodation_room->base_price;
                    if($accommodation_room->publish_price > $accommodation_room->base_price) {
                        $publish_price = $accommodation_room->publish_price;
                    }*/
                    $planData = $accommodation_room->planData??[];
                    if($planData && $planData->count() > 0) {
                        $publish_price_arr = [];
                        foreach($planData as $plan) {
                            if($plan->price) {
                                $publish_price_arr[] = $plan->price;
                            }
                        }
                        if(!empty($publish_price_arr)) {
                            $publish_price = min($publish_price_arr);
                        }
                    }
                }
                if($publish_price) {
                    $search_price = $publish_price;
                    $module_name = 'hotel_listing';
                    $discount_category_id = $accommodation->discount_category_id;
                    if($discount_category_id !== 0) {
                        $agent_group_id = '-1';
                        $module_record_id = $accommodation->id;
                        $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$publish_price,$agent_group_id,$module_record_id);
                        if($publish_price > $discount_amount) {
                            $search_price = $publish_price - $discount_amount;
                        }
                    }
                }
                $accommodation->search_price = $search_price;
                $accommodation->publish_price = $publish_price;
                $accommodation->save();
            }
        }
    }

    public static function getAccommodationRoomPublishPrice($accommodation_room='') {
        $publish_price = 0;
        if($accommodation_room && $accommodation_room->planData) {
            $planData = $accommodation_room->planData??[];
            if($planData) {
                $publish_price_arr = [];
                foreach($planData as $plan) {
                    if($plan->price) {
                        $publish_price_arr[] = $plan->price;
                    }
                }
                if(!empty($publish_price_arr)) {
                    $publish_price = min($publish_price_arr);
                }
            }
        }
        return $publish_price;
    }

    public static function updatePackagePublishPrice($id='') {
        if($id) {
            $package = Package::find($id);
            if($package) {
                $is_activity = $package->is_activity??0;
                $search_price = 0;
                $publish_price = 0;
                $query = PackagePriceInfo::where('package_id',$id);
                $query->orderBy('is_default','DESC');
                $query->orderBy('sort_order','ASC');
                $package_price = $query->first();
                if($package_price && $package_price->show_choices_record != '') {
                    $category_choices_record = json_decode($package_price->category_choices_record);
                    $show_choices_record = json_decode($package_price->show_choices_record);
                    if(!empty($show_choices_record)) {
                        foreach($show_choices_record as $key => $val) {
                            if(isset($val->default) && $val->default != $key.'_null') {
                                $val_arr = explode('_', $val->default);
                                $elem = $val_arr[1];
                                if(isset($category_choices_record->$key)) {
                                    $price_arr = (array)$category_choices_record->$key;
                                    if(isset($price_arr[$elem])) {
                                        $publish_price = $price_arr[$elem];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                if($publish_price) {
                    $search_price = $publish_price;
                    $discount_category_id = $package->discount_category_id;
                    if($discount_category_id !== 0) {
                        $module_name = 'package_listing';
                        if($is_activity == 1) {
                            $module_name = 'activity_listing';
                        }
                        $agent_group_id = '-1';
                        $module_record_id = $package->id;
                        $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$publish_price,$agent_group_id,$module_record_id);
                        if($publish_price > $discount_amount) {
                            $search_price = $publish_price - $discount_amount;
                        }
                    }
                }
                $package->search_price = $search_price;
                $package->publish_price = $publish_price;
                $package->save();
            }
        }
    }

    public static function updateCabRoutePublishPrice($id='') {
        if($id) {
            $CabRoute = CabRoute::find($id);
            if($CabRoute) {

                $CabRouteToGroup = $CabRoute->CabRouteToGroup->pluck('id')->toArray();
                $cab_ids = [];
                $CabRouteToGroupData = $CabRoute->CabRouteToGroup;
                foreach ($CabRouteToGroupData as $key => $groupRow) {
                    $cab_data = $groupRow->cab_data ? json_decode($groupRow->cab_data) : [];
                    foreach ($cab_data as $key => $cab_row) {
                        $cab_ids[] = $cab_row->id?? 0;
                    }
                }
                $query = CabRoutePrice::where('cab_route_id',$CabRoute->id)->whereHas('CabData',function($q1){
                    $q1->where('status',1);
                });
                if($CabRouteToGroup) {
                    $query->whereIn('cab_route_group_id',$CabRouteToGroup);
                }
                if($cab_ids) {
                    $query->whereIn('cab_id',$cab_ids);
                }
                $cab_price_data = $query->where('price','>',0)->orderBy('price', 'asc')->first();
                $publish_price = $cab_price_data->price??0;
                $search_price = $cab_price_data->price??0;

                if($publish_price) {
                    $module_name = 'cab';
                    $discount_category_id = $CabRoute->discount_category_id;
                    if($discount_category_id !== 0) {
                        $agent_group_id = '-1';
                        $module_record_id = $CabRoute->id;
                        $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$publish_price,$agent_group_id,$module_record_id);
                        if($publish_price > $discount_amount) {
                            $search_price = $publish_price - $discount_amount;
                        }
                    }
                }
                $CabRoute->search_price = $search_price;
                $CabRoute->publish_price = $publish_price;
                $CabRoute->save();
            }
        }
    }

    public static function updateRentalPublishPrice($id='') {
        if($id) {

        }
    }

    public static function parseOutput($text = '')
    {
        if ($text) {
            preg_match_all('#\[(.*?)\]#', $text, $matches);
            // prd($matches);
            // $package_short_code = $match[1];
            if (!empty($matches) && isset($matches[1]) && is_array($matches[1])) {
                foreach ($matches[1] as $key => $value) {
                    // prd($value);
                    preg_match('#(.*?)\((.*?)\)#', $value, $search_matches);
                    // prd($search_matches);
                    if (isset($search_matches[2])) {
                        $function_name = $search_matches[1];
                        $search_matches_arr = explode(',', $search_matches[2]);
                        $search = [];
                        foreach ($search_matches_arr as $search_match) {
                            // prd($search_match);
                            $search_match_arr = explode('=', $search_match);
                            if (isset($search_match_arr[0]) && !empty($search_match_arr[0])) {
                                $search_key = str_replace(['"', "'", '&#39;'], '', $search_match_arr[0]);
                                $search_value = str_replace(['"', "'", '&#39;'], '', $search_match_arr[1]);
                                if ($search_key) {
                                    $search[$search_key] = $search_value;
                                }
                            }
                        }
                        // prd($function_name);
                        // prd($search);
                        if (!empty($search) && isset($matches[0][$key])) {
                            $short_code = $matches[0][$key];
                            try {
                                $short_code_html = $function_name($search);
                                $text = str_replace($short_code, $short_code_html, $text);
                            } catch (\Exception $e) {
                                $message = $e->getMessage();
                                $short_code_html = 'Shortcode could not be parsed for reason: ' . $message;
                                $text = str_replace($short_code, $short_code_html, $text);
                            }
                        }
                    }
                }
            }
        }

       

        return $text;
    }



    public static function parseOutputOld($text='') {             
        if($text) {
           
            preg_match_all('#\[(.*?)\]#', $text, $matches);
            // prd($matches);
            // $package_short_code = $match[1];
            if(!empty($matches) && isset($matches[1]) && is_array($matches[1])) {
                foreach($matches[1] as $key => $value) {
                    // prd($value);
                    preg_match('#(.*?)\((.*?)\)#', $value, $search_matches);
                    if(isset($search_matches[2])) {
                        $function_name = $search_matches[1];
                        $search_matches_arr = explode(',', $search_matches[2]);
                        $search = [];
                        foreach($search_matches_arr as $search_match) {
                            // prd($search_match);
                            $search_match_arr = explode('=', $search_match);
                            if(isset($search_match_arr[0]) && !empty($search_match_arr[0])) {
                                $search_key = str_replace(['"',"'",'&#39;'],'',$search_match_arr[0]);
                                $search_value = str_replace(['"',"'",'&#39;'],'',$search_match_arr[1]);
                                if($search_key) {
                                    $search[$search_key] = $search_value;
                                }
                            }
                        }
                        // prd($function_name);
                        // prd($search);
                        if(!empty($search) && isset($matches[0][$key])) {
                            $short_code = $matches[0][$key];
                            try {
                                $short_code_html = $function_name($search);
                                $text = str_replace($short_code, $short_code_html, $text);
                            } catch (\Exception $e) {
                                $message = $e->getMessage();
                                $short_code_html = 'Shortcode could not be parsed for reason: '.$message;
                                $text = str_replace($short_code, $short_code_html, $text);
                            }
                        }
                    }
                }
            }
        }

      
        return $text;
    }

    public static function cmsBreadcrumb($parent_id='',$breadcrumb = [], $level=0) {
        $level++;
        $routeName = Self::getAdminRouteName();
        if($parent_id) {
            $record = CmsPages::where('id',$parent_id)->first();
            if(!empty($record)) {
                if($level == 1) {
                    $breadcrumb[] = '<li>'.$record->title.'</li>';
                } else {
                    $breadcrumb[] = '<li><a href="'.route($routeName.'.cms.index',['parent_id'=>$parent_id]).'">'.$record->title.'</a></li>';
                }
                $breadcrumb[] = Self::cmsBreadcrumb($record->parent_id,[],$level);
            }
        } else {
            if($level == 1) {
                $breadcrumb[] = '<li>CMS Pages</li>';
            } else {
                $breadcrumb[] = '<li><a href="'.route($routeName.'.cms.index').'">CMS Pages</a></li>';
            }
        }
        $breadcrumb = array_reverse($breadcrumb);
        $breadcrumb = implode('', $breadcrumb);
        return $breadcrumb;
    }

    public static function BulkUpdates($params=[]) {
        BulkUpdates::dispatch($params);
    }

    public static function getUserInfo($userId='') {
        $userInfo = [];
        if(empty($userId)) {
            $userId = Auth::user()->id??0;
        }
        if($userId) {
            $user = User::find($userId);
            if($user && $user->id) {
                $agentInfo = $user->agentInfo??[];
                $name = $user->name??'';
                if($agentInfo && $agentInfo->company_brand) {
                    $name = $agentInfo->company_brand??'';
                }
                $balance = UserWallet::where('user_id',$userId)->sum('amount');
                $gstInfos = UserGstInfo::where('user_id',$userId)->orderBy('id','desc')->get();

                $package_fab = DB::table('users_favourite_packages')->where('user_id',$userId)->get()->keyBy('package_id')->toArray();

                $userInfo = [
                    'is_agent' => $user->is_agent??0,
                    'name' => $name,
                    'email' => $user->email??'',
                    'phone' => $user->phone??'',
                    'country_code' => $user->country_code??'',
                    'balance' => $balance,
                    'gstInfos' => $gstInfos,
                    'package_fab' => $package_fab,
                    'MY_BOOKING_URL' => route('user.mybooking'),
                    'MY_WALLET_URL' => route('user.myWallet'),
                    'MY_PROFILE_URL' => route('user.profile'),
                    'MY_FAVOURITE_URL' => route('user.myfavorite'),
                    'LOGOUT_URL' => route('user.logout'),
                ];

                if($user->is_agent == 1 && CustomHelper::isAllowedModule('agentuser')) {
                    $userInfo['company_brand'] = $user->agentInfo->company_brand??'';
                    $userInfo['company_name'] = $user->agentInfo->company_name??'';
                    $userInfo['company_owner_name'] = $user->agentInfo->company_owner_name??'';
                    if(CustomHelper::isOnlineBooking('flight')) {
                        $is_allowed_offline_flight_inventory = $user->agentInfo->is_allowed_offline_flight_inventory??0;
                        if($is_allowed_offline_flight_inventory) {
                            $userInfo['PENDING_BOOKING_URL'] = route('user.myFlightBooking',['order_type'=>'pending','range'=>'all']);
                            $userInfo['PENDING_BOOKING_COUNT'] = CustomHelper::getUserPendingBookingCount($user->id);                        
                        }
                    }
                }
            }
        }
        return $userInfo;
    }

    public static function showUserName($userId='') {
        $name = '';
        if($userId) {
            $user = User::find($userId);
            if($user && $user->id) {
                $name = $user->name??'';
                $agentInfo = $user->agentInfo??[];
                if($agentInfo && $agentInfo->company_brand) {
                    $name = $agentInfo->company_brand??'';
                }
            }
        }
        return $name;
    }

    public static function getUserPendingBookingCount($userId='') {
        $count = 0;
        if($userId) {
            $query = Order::where('order_type',3);
            $query->where('supplier_id',$userId);
            $order_type = 'pending';
            switch ($order_type) {
                case 'bookings':
                    $query->where('payment_status',1)->where('status','SUCCESS');
                break;
                case 'failed':
                    $query->where('payment_status',0);
                break;
                default:
                    $query->where('payment_status',1)->where('status','!=','SUCCESS');
                break;
            }
            $count = $query->count();
        }
        return $count;
    }

    public static function getFlashMessages($userId='') {
        $flash_messages = [];
        foreach (['danger', 'warning', 'success', 'info'] as $msg) {
            if (session()->has('alert-' . $msg)) {
                $flash_messages[] = [
                    'key' => $msg,
                    'value' => session()->get('alert-'.$msg)
                ];
                session()->forget('alert-'.$msg);
            }
        }
        if (session()->has('status')) {
            $flash_messages[] = [
                'key' => $msg,
                'value' => session()->get('status')
            ];
            session()->forget('status');
        }
        return $flash_messages;
    }

    public static function getPhoneHref($phone='') {
       $href = '';
       if($phone) {
           $phone_arr = explode(',', $phone);
           $href_arr = [];
           if(count($phone_arr) > 0) {
               foreach($phone_arr as $ph) {
                   $href_arr[] = '<a href="tel:'.$ph.'" style="color: #fff;text-decoration: none;">'.$ph.'</a>';
               }
           }
           if(!empty($href_arr)) {
               $href = implode(', ', $href_arr);
           }
       }
       return $href;
   }
    public static function getEmailHref($email='') {
       $href = '';
       if($email) {
           $email_arr = explode(',', $email);
           $href_arr = [];
           if(count($email_arr) > 0) {
               foreach($email_arr as $em) {
                   $href_arr[] = '<a href="mailto:'.$em.'" style="color: #fff;text-decoration: none;">'.$em.'</a>';
               }
           }
           if(!empty($href_arr)) {
               $href = implode(', ', $href_arr);
           }
       }
       return $href;
    }


    public static function getAddressLatLng($address) {
        $response = [];
        $response['success'] = false;
        try {
            $GOOGLE_MAP_API_KEY = CustomHelper::WebsiteSettings('GOOGLE_MAP_API_KEY');
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=".$GOOGLE_MAP_API_KEY;
            $client = new \GuzzleHttp\Client(); //self::getHttpHeaders()
            $resp = $client->get($url); //['verify' => false]
            $response['statusCode'] = $resp->getStatusCode();
            $res = $resp->getBody()->getContents();
            $obj =  json_decode($res, true);
            // prd($obj);
            // $distance =  $obj['rows'][0]['elements'][0]['distance']['value']??0;
            // $distance =  $obj['rows'][0]['elements'][0]['distance']['value']??0;
            // $distance = ceil($distance/1000);

            // $response = $obj['rows'][0]['elements'][0]??[];

            $status = $obj['status']??'';
            if($status == 'OK') {
                $response = $obj;
                $response['success'] = true;
            }
        } catch(\Exception $e) {

        }
        return $response;    
    }

    public static function getDirectionDistance($origins, $distinations) {
        $response = [];
        $response['success'] = false;
        try {
            $key = md5($origins.$distinations);
            $response = \Cache::rememberForever("direction-".$key, function () use($origins, $distinations) {
                $response = [];
                $response['success'] = false;
                $GOOGLE_MAP_API_KEY = CustomHelper::WebsiteSettings('GOOGLE_MAP_API_KEY');
                $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origins&destinations=$distinations&key=".$GOOGLE_MAP_API_KEY;
                $client = new \GuzzleHttp\Client();
                $resp = $client->get($url);
                $response['statusCode'] = $resp->getStatusCode();
                $res = $resp->getBody()->getContents();
                $obj =  json_decode($res, true);
                $status = $obj['status']??'';
                if($status == 'OK') {
                    $response = $obj;
                    $response['success'] = true;
                }
                return $response;
            });
        } catch(\Exception $e) {

        }
        return $response;
    }

    public static function getSingleBanner($params){
        $limit = $params['limit']??'';

        $response = [];
        $response['success'] = false;

        $banner_images_arr = [];
        $identifier = 'home';
        $seo_tags = SeoMetaTag::where(['identifier'=>$identifier,'status'=>1])->first();
        if(!empty($seo_tags)) {
            $identifier = $seo_tags->identifier;
            $seo_data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
            $seo_data['page_url_label'] = $seo_tags->page_url_label??'';
            $seo_data['page_brief'] = $seo_tags->page_brief??'';
            $seo_data['page_description'] = $seo_tags->page_description??'';
            $seo_data['page_url'] = $seo_tags->page_url??'';
            $seo_data['page_detail_url'] = $seo_tags->page_detail_url??'';
            $seo_data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
            $seo_data['meta_description'] = $seo_tags->meta_description??'';
            $seo_data['meta_keyword'] = $seo_tags->meta_keyword??'';
            $seo_data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
            if($seo_tags->image) {
                $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
                $banner_images_arr[] = [
                    'title' => $seo_data['page_title'],
                    'sub_title' => '',
                    'link_text_1' => $seo_data['page_title'],
                    'link_text_2' => '',
                    'link_1' => '',
                    'link_2' => '',
                    'src' => $banner_image,
                ];
            }
        }

        $imageBanner = Banner::where('status',1)->where('slug','home-page-main-banner')->first();
        if(!empty($imageBanner) && $imageBanner->type == 1) {
            $bannerImages = $imageBanner->Images;
            if(!$bannerImages->isEmpty()) {
                $storage = Storage::disk('public');
                $path = "banners/";
                foreach ($bannerImages as $image) {
                    if(!empty($image->image_name)) {
                        if($storage->exists($path.$image->image_name)) {
                            $bannerSrc = asset('/storage/'.$path.$image->image_name);
                            $banner_images_arr[] = [
                                'title' => $image->title,
                                'sub_title' => $image->sub_title,
                                'link_text_1' => $image->link_text_1,
                                'link_text_2' => $image->link_text_2,
                                'link_1' => $image->link_1,
                                'link_2' => $image->link_2,
                                'src' => $bannerSrc,
                            ];
                        }
                    }
                }
            }
        }
        if(empty($banner_images_arr)) {
            $bannerSrc = asset(config('custom.assets').'/images/default_common_banner.jpg');
            $banner_images_arr[] = [
                'title' => CustomHelper::WebsiteSettings('SYSTEM_TITLE'),
                'sub_title' => '',
                'link_text_1' => CustomHelper::WebsiteSettings('SYSTEM_TITLE'),
                'link_text_2' => '',
                'link_1' => '',
                'link_2' => '',
                'src' => $bannerSrc,
            ];
        }
        $response['banner_images'] = ($limit == 1) ? $banner_images_arr[0] : $banner_images_arr ;
        $response['success'] = true;
        return $response;
    }

    public static function showCabinClass($cabinClass) {
        $cabinClassName = $cabinClass;
        if($cabinClass=='PREMIUM_ECONOMY' || $cabinClass=='Premium Economy') {
            $cabinClassName = 'Premium Economy';
        } else if($cabinClass=='BUSINESS' || $cabinClass=='Business') {
            $cabinClassName = 'Business';
        } else if($cabinClass=='FIRST' || $cabinClass=='First') {
            $cabinClassName = 'First';
        } else if($cabinClass=='ECONOMY' || $cabinClass=='Economy') {
            $cabinClassName = 'Economy';
        }
        return $cabinClassName;
    }

    public static function parseIIAIRSearchId($search_id='') {
        $response = [];
        if($search_id) {
            $search_id_arr = explode('-', $search_id);
            $price_id = $search_id_arr[0]??'';
            $search_slug = str_replace($price_id.'-', '', $search_id);
            $routeSearch = AirlineRouteSearch::where('slug',$search_slug)->first();
            if($routeSearch && $routeSearch->id) {
                $response = $routeSearch->toArray();
            }
            $response['price_id'] = $price_id;
        }
        return $response;
    }

    public static function flightReviewDetails($review_slug, $priceIds) {
        $response = \Cache::rememberForever("review-".$review_slug, function () use($review_slug, $priceIds) {
            $record = FlightReviewDetails::where('review_slug',$review_slug)->first();
            if($record && $record->id) {
                $resp = $record->response_json;
            } else {
                if(!empty($priceIds) && isset($priceIds[0]) && strpos($priceIds[0], 'IIAIR') !== false ) {
                    $userId = Auth::user()->id??0;
                    $is_agent = Auth::user()->is_agent??0;
                    $agent_id = 0;
                    if($is_agent == 1){
                        $agent_id = $userId;
                    }
                    $params = self::parseIIAIRSearchId($priceIds[0]);
                    $price_id = $params['price_id']??0;
                    $inventoryData = AirlineRouteInventory::find($price_id);
                    if($inventoryData) {
                        $params['inventoryData'] = $inventoryData->toArray();
                        $inventory_id = $inventoryData->id??0;
                        $supplier_id = $inventoryData->user_id??0;
                        $fare_type = $inventoryData->fare_type??'';
                        $routeData = $inventoryData->routeData??[];
                        $trip_type = $routeData->trip_type??'';                    
                        $pnrDetails = '';
                        if($fare_type==2) {
                            $pnrDetails = $inventoryData->pnr;
                        }
                        $isDomestic = 0;
                        if($trip_type == 'Domestic') {
                            $isDomestic = 1;
                        }

                        $routeInfos = [
                            '0' => [
                                'fromCityOrAirport' => [
                                    'code' => $routeData->source??'',
                                    'name' => $routeData->sourceData->name??'',
                                    'cityCode' => $routeData->sourceData->citycode??'',
                                    'city' => $routeData->sourceData->city??'',
                                    'country' => $routeData->sourceData->country??'',
                                    'countryCode' => $routeData->sourceData->countrycode??'',
                                    'terminal' => $routeData->source_terminal??''
                                ],
                                'toCityOrAirport' => [
                                    'code' => $routeData->destination??'',
                                    'name' => $routeData->destinationData->name??'',
                                    'cityCode' => $routeData->destinationData->citycode??'',
                                    'city' => $routeData->destinationData->city??'',
                                    'country' => $routeData->destinationData->country??'',
                                    'countryCode' => $routeData->destinationData->countrycode??'',
                                    'terminal' => $routeData->destination_terminal??''
                                ],
                                'travelDate' => ''
                            ]
                        ];

                        $searchQuery = [
                            'routeInfos' => $routeInfos,
                            'cabinClass' => $inventoryData->flight_class,
                            'paxInfo' => [
                                'ADULT' => $params['ADT']??0,
                                'CHILD' =>  $params['CHD']??0,
                                'INFANT' =>  $params['INF']??0,
                            ],
                            'requestId' => '',
                            'searchType' => 'ONEWAY',
                            'searchModifiers' => [
                                'isDirectFlight' => 0,
                                'isConnectingFlight' => 0,
                                'pft' => 'REGULAR'
                            ],
                            'isDomestic' => $isDomestic
                        ];

                        $response = [];
                        $route_params = $params;
                        $route_params['price_id'] = $price_id;
                        $route_params['agent_id'] = $agent_id;
                        $tripInfos = AirlineRoute::parseAirlineRoute($routeData, $route_params);

                        $BF = 0;
                        $NF = 0;
                        $TAF = 0;
                        $TF = 0;

                        $OT = 0;
                        $MF = 0;
                        $MFT = 0;
                        $AGST = 0;
                        $YQ = 0;
                        $YR = 0;
                        if(isset($tripInfos['totalPriceList'])) {
                            foreach($tripInfos['totalPriceList'] as $row) {
                                foreach($searchQuery['paxInfo'] as $paxType => $paxCount) {
                                    $BF += ($row['fd'][$paxType]['fC']['BF']??0)*$paxCount;
                                    $NF += ($row['fd'][$paxType]['fC']['NF']??0)*$paxCount;
                                    $TAF += ($row['fd'][$paxType]['fC']['TAF']??0)*$paxCount;
                                    $TF += ($row['fd'][$paxType]['fC']['TF']??0)*$paxCount;

                                    $OT += ($row['fd'][$paxType]['afC']['TAF']['OT']??0)*$paxCount;
                                    $MF += ($row['fd'][$paxType]['afC']['TAF']['MF']??0)*$paxCount;
                                    $MFT += ($row['fd'][$paxType]['afC']['TAF']['MFT']??0)*$paxCount;
                                    $AGST += ($row['fd'][$paxType]['afC']['TAF']['AGST']??0)*$paxCount;
                                    $YQ += ($row['fd'][$paxType]['afC']['TAF']['YQ']??0)*$paxCount;
                                    $YR += ($row['fd'][$paxType]['afC']['TAF']['YR']??0)*$paxCount;
                                }
                            }
                        }

                        $totalPriceInfo = [
                            'totalFareDetail' => [
                                'fC' => [
                                    'BF' => $BF,
                                    'NF' => $NF,
                                    'TAF' => $TAF,
                                    'TF' => $TF,
                                ],
                                'afC' => [
                                    'TAF' => [
                                        'OT' => $OT,
                                        'MF' => $MF,
                                        'MFT' => $MFT,
                                        'AGST' => $AGST,
                                        'YQ' => $YQ,
                                        'YR' => $YR,
                                    ]
                                ]
                            ],
                            'supplier_id' => $supplier_id,
                            'inventory_id' => $inventory_id,
                            'pnrDetails' => $pnrDetails,
                        ];

                        $conditions = [
                            'pcs' => [
                                'pped' => 0,
                                'pid' => 0,
                                'pm' => 1,
                                'dobe' => 0,
                            ],
                            'ffas' => [
                                '0' => 'QR'
                            ],
                            'isa' => 1,
                            'dob' => [
                                'adobr' => 0,
                                'cdobr' => 0,
                                'idobr' => 1
                            ],
                            'iecr' => 0,
                            'dc' => [
                                'ida' => 0,
                                'idm' => 0,
                            ],
                            'isBA' => 0,
                            'st' => 780,
                            'sct' => date('Y-m-d H:i:s'),
                            'gst' => [
                                'gstappl' => 1,
                                'igm' => 0,
                            ]
                        ];
                        if($isDomestic) {
                            unset($conditions['pcs']);
                        }
                        $response['tripInfos'][] = $tripInfos;
                        $response['searchQuery'] = $searchQuery;
                        $response['totalPriceInfo'] = $totalPriceInfo;
                        $response['conditions'] = $conditions;
                        $response['status']['success'] = true;
                        $response['status']['httpStatus'] = 200;
                        $resp = json_encode($response);
                        $reqParams = [
                            "priceIds" => $priceIds,
                        ];
                    }
                } else {
                    $reqParams = [
                        "priceIds" => $priceIds,
                    ];
                    $endPoint = '/fms/v1/review';
                    $resp = FlightHelper::PostDataByEndPoint($endPoint, $reqParams);
                }
                $record = [
                    'review_slug' => $review_slug,
                    'request_json' => json_encode($reqParams),
                    'response_json' => $resp,
                ];
                FlightReviewDetails::create($record);
            }
            return $resp;
        });
        $last_2_hours = date('Y-m-d H:i:s', strtotime('-2 hours'));
        FlightReviewDetails::where('created_at','<',$last_2_hours)->delete();
        return $response;
    }

    public static function flightApiBalance($refresh=false) {
        if($refresh) {
            Self::cache_flush('tripjack-balance');
        }
        $response = \Cache::rememberForever("tripjack-balance", function () {
            $response = [];
            $response['success'] = false;
            $response['message'] = '';
            $endPoint = '/ums/v1/user-detail';
            $resp = FlightHelper::GetDataByEndPoint($endPoint);
            $apiResult = json_decode($resp, true);
            $statusCode = $apiResult['status']['httpStatus']??'';
            $success = $apiResult['status']['success']??'';
            $message = $apiResult['errors'][0]['message']??'';
            if (isset($statusCode) && $statusCode == 200  && isset($success)) {
                $totalBalance = $apiResult['user']['bs']['totalBalance']??0;
                $walletBalance = $apiResult['user']['bs']['walletBalance']??0;
                $response['success'] = true;
                $response['totalBalance'] = self::getPrice($totalBalance);
                $response['walletBalance'] = self::getPrice($walletBalance);
            } else {
                $response['message'] = $message;
            }
            return $response;
        });
        return $response;
    }

    public static function showAirportName($code) {
        $airport_name = '';
        if($code) {
            $record = AirportCodesMaster::select('code','name','city')->where("code",$code)->first();
            if($record) {
                $airport_name = $record->city.' ('.$record->code.') - '.$record->name;
            }
        }
        return $airport_name;
    }

    public static function showAirlineName($code) {
        $airline_name = '';
        if($code) {
            $record = AirlineMaster::select('name')->where("code",$code)->first();
            if($record) {
                $airline_name = $record->name;
            }
        }
        return $airline_name;
    }

    public static function showAirlineRouteName($id) {
        $airline_route_name = '';
        if($id) {
            $record = AirlineRoute::where("id",$id)->first();
            if($record) {
                $airline_route_name = self::parseAirlineRouteName($record);
            }
        }
        return $airline_route_name;
    }

    public static function parseAirlineRouteName($record) {
        $airline_route_name = '';
        if($record) {
            $route_name_arr = [];
            $route_name_arr[] = ($record->name??'');
            $route_name_arr[] = ($record->source??'').'/'.($record->destination??'');
            $route_name_arr[] = (CustomHelper::DateFormat($record->start_time,'H:i')).'/'.(CustomHelper::DateFormat($record->end_time,'H:i'));
            $route_name_arr[] = ($record->airline??'').'/'.($record->flight_number??'');
            $route_name_arr[] = ($record->stops??'').' Stop(s)';
            $airline_route_name = implode(', ', $route_name_arr);
        }
        return $airline_route_name;
    }

    public static function create_time_range($start, $end, $interval = '15 mins', $format = '12') {
        $startTime = strtotime($start); 
        $endTime   = strtotime($end);
        $returnTimeFormat = ($format == '12')?'h:i A':'H:i';

        $current   = time(); 
        $addTime   = strtotime('+'.$interval, $current); 
        $diff      = $addTime - $current;

        $times = []; 
        while ($startTime < $endTime) { 
            $times[date('H:i', $startTime)] = date($returnTimeFormat, $startTime); 
            $startTime += $diff; 
        } 
        $times[date('H:i', $startTime)] = date($returnTimeFormat, $startTime);
        return $times; 
    }

    public static function showAirlineLogo($logoname,$isPdf=false) {
        $logo_src = '';
        if($logoname) {
            if($logoname == 'IX'){
                $logoname = 'AIE';
            }
            if($isPdf) {
                $logo_src = public_path('assets/AirlinesLogo/'.$logoname.'.png');
            } else {
                $logo_src = asset('assets/AirlinesLogo/'.$logoname.'.png');
            }
        }
        return $logo_src;
    }

/* End of helper class */
}