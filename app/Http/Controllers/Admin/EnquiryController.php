<?php
namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Enquiry;
use App\Models\Package;
use App\Models\Destination;
use App\Models\Accommodation;
use App\Models\EmailTemplate;
use App\Models\EnquiriesMaster;
use App\Models\AccomodationType;
use App\Models\EnquiriesMasterGroup;
use App\Models\EnquiryInteraction;

use App\Helpers\CustomHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Storage;
use Response;
use Validator;

class EnquiryController extends Controller {
    private $limit;

    public function __construct() {
        $this->limit = 20;
    }

    public function new_index(Request $request) {
        $data = [];
        return view('admin.enquiries_new.index', $data);
    }

    public function index(Request $request) {

        $GetUser = Auth()->user();
        $GetUserId = $GetUser->id ?? 0;
        $GetUserRoleId = $GetUser->role_id ?? 0;

        // prd($request->toArray());

        $rules = [];
        $niceNames = [
            'name' => 'Name',
            'email' => 'Email ID',
            'phone' => 'Contact Number',
            'date_type' => 'Search Date Type',
            'range' => 'Range',
            'from' => 'From',
            'to' => 'To',
        ];
        $rules['name'] = 'nullable|regex:/^[\pL\s\-]+$/u';
        $rules['email'] = 'nullable|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/';
        $rules['phone'] = 'nullable|digits:10|regex:/^([0-9\s\-\+\(\)]*)$/';
        $rules['date_type'] = 'required_if:range,custom,yesterday,today,tomorrow,this_week,next_week,last_week,this_month,last_month,last_7_days,last_30_days,this_year,last_year';
        $rules['range'] = 'required_with:date_type';
        $rules['from'] = 'required_if:range,custom';
        $rules['to'] = 'required_if:range,custom';
        $validation_msg = [];
        $validation_msg['required'] = ':attribute is required.';
        $this->validate($request, $rules, $validation_msg, $niceNames);

        $data = [];
        $limit = $this->limit;

        $query = Enquiry::with('EnquiryForType','Destination','SubDestination','Accommodation','LastInteraction');

        $query->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });

        if ( !empty($GetUserId) && is_numeric($GetUserId) && $GetUserRoleId != 1 ) {
            if (!CustomHelper::checkPermission('enquiries', 'view_all_leads')) {
                if (CustomHelper::checkPermission('enquiries', 'view_unassigned_lead')) {
                    $query->where(function ($sub_query) use ($GetUserId) {
                        $sub_query->where('cc_id', '=', $GetUserId);
                        $sub_query->orWhereNull('cc_id');
                    });
                }
                elseif (CustomHelper::checkPermission('enquiries', 'view')) {
                    $query->where(function ($sub_query) use ($GetUserId) {
                        $sub_query->where('cc_id', '=', $GetUserId);
                        $sub_query->whereNotNull('cc_id');
                    });
                }
            }
        }

        if($request->enquiry == 'assigned') {
            $query->where(function ($sub_query){
                $sub_query->whereNotNull('cc_id');
                $sub_query->where('cc_id','!=',0);
            });
        } if($request->enquiry == 'unassigned') {
            $query->where(function ($sub_query){
                $sub_query->whereNull('cc_id');
                $sub_query->orWhere('cc_id','=',0);
            });
        } if($request->name) {
            $query->where("name", "like", "%".$request->name."%");
        } if($request->phone) {
            $query->where("phone", $request->phone);
        } if($request->email) {
            $query->where("email", $request->email);
        }
        if($request->enquiry_for) {
            if(is_array($request->enquiry_for)) {
                $enquiry_for_ids = $request->enquiry_for;
            } else {
                $enquiry_for_ids = explode(',', $request->enquiry_for);
            }
            if(!empty($enquiry_for_ids)) {
                $query->whereHas("EnquiryForType", function($q1) use($enquiry_for_ids) {
                    $q1->whereIn('enquiry_for_id',$enquiry_for_ids);
                });
            }
        }

        if(!empty($request->tab)) {
            $tab = $request->tab;
            $query->whereHas("EnquiryForType", function($q1) use($tab) {
                $q1->where('enquiry_for_id',$tab);
            });
        }

        if($request->destination) {
            if(is_array($request->destination)) {
                $destination_ids = $request->destination;
            } else {
                $destination_ids = explode(',', $request->destination);
            }
            if(!empty($destination_ids)) {
                $query->where(function($q1) use($destination_ids) {
                    $q1->whereHas("Destination", function($q2) use($destination_ids) {
                        $q2->whereIn('destination_id',$destination_ids);
                    })->orWhereHas("SubDestination", function($q2) use($destination_ids) {
                        $q2->whereIn('destination_id',$destination_ids);
                    });
                });
            }
        } if($request->package_id) {
            $query->where("package_id", $request->package_id);
        } if($request->accommodation) {
            if(is_array($request->accommodation)) {
                $accommodation_ids = $request->accommodation;
            } else {
                $accommodation_ids = explode(',', $request->accommodation);
            }
            if(!empty($accommodation_ids)) {
                $query->whereHas("Accommodation", function($q1) use($accommodation_ids) {
                    $q1->whereIn('accommodation_id',$accommodation_ids);
                });
            }
        } if($request->lead_source) {
            $query->where("lead_source", $request->lead_source);
        } if($request->lead_status) {
            $query->where("lead_status", $request->lead_status);
        } if($request->lead_sub_status) {
            $query->where("lead_sub_status", $request->lead_sub_status);
        } if($request->rating) {
            $query->where("rating", $request->rating);
        }
        if(empty($request->all_enquiries) && empty($request->pending_enquiries) && empty($request->tomorrow_later)) {
            if($request->status && is_array($request->status)) {
                // $getIds = EnquiriesMaster::where('category', $request->status)->select('id')->get();
                $getIds = EnquiriesMaster::whereIn('category', $request->status)->select('id')->get();

                $xArray=[];
                foreach ($getIds as $key => $get_id) {
                    $xArray[]=$get_id->id;
                }

                if(in_array('new',$request->status)){
                    // $query->whereNull('lead_status');
                    // $query->orWhereIn('lead_status', $xArray);
                    $query->where(function ($sub_query) use ($xArray) {
                        $sub_query->whereNull('lead_status');
                        $sub_query->orWhereIn('lead_status', $xArray);
                    });
                }else{
                    $query->whereNotNull('lead_status');
                    $query->WhereIn('lead_status', $xArray);
                    // $query->whereIn('lead_status', $xArray);
                }
            }else{
                $autoSelStatus = ['new','open','in_process'];
                $getIds = EnquiriesMaster::whereIn('category', $autoSelStatus)->select('id')->get();

                $xArray=[];
                foreach ($getIds as $get_id) {
                    $xArray[]=$get_id->id;
                }
                    // $query->whereIn('lead_status', $xArray);
                    // $query->whereNull('lead_status');
                    // $query->orWhereIn('lead_status', $xArray);
                    $query->where(function ($sub_query) use ($xArray) {
                        $sub_query->whereNull('lead_status');
                        $sub_query->orWhereIn('lead_status', $xArray);
                    });
            }
        }

        if(isset($request->pending_enquiries) && $request->pending_enquiries == 'pending_enquiries') {
            $today_date = date("Y-m-d");
            $getIds = EnquiriesMaster::where('category', 'new')->select('id')->get();
            $xArray=[];
            foreach ($getIds as $key => $get_id) {
                $xArray[]=$get_id->id;
            }
            $query->where(function ($sub_query) use ($xArray) {
                $sub_query->whereNull('lead_status');
                $sub_query->orWhereIn('lead_status', $xArray);
            });
            $query->where(function ($sub_query) use ($today_date) {
                $sub_query->whereNull('followup_date');
                $sub_query->orWhere(DB::raw('DATE(followup_date)'), '<=', $today_date);
            });
            // $query->orderBy('lead_status')->orderBy('followup_date','desc')->orderBy('created_at','desc');
            // $query->orderBy('followup_date','desc')->orderBy('created_at','desc');
            $query->orderBy('updated_at','desc');
        }else{
            $query->orderBy('id', 'desc');
        }

        if(isset($request->tomorrow_later) && $request->tomorrow_later == 'tomorrow_later') {
            $today_date = date("Y-m-d");
            $query->where(DB::raw('DATE(followup_date)'), '>', $today_date);
            $query->orderBy('lead_status')->orderBy('followup_date','desc')->orderBy('created_at','desc');
        }else{
            $query->orderBy('id', 'desc');
        }

        if(isset($request->all_enquiries) && $request->all_enquiries == 'all_enquiries') {
            $query->orderBy('created_at','desc');
        }else{
            $query->orderBy('id', 'desc');
        }

        $from_date = '';
        $to_date = '';
        $range = $request->range;
        if(!empty($range) && $range != 'custom') {
            $date_between_arr = CustomHelper::get_to_from_date($range);
            $from_date = $date_between_arr['from'];
            $to_date = $date_between_arr['to'];
        }else {
            if($request->from && $request->to) {
                $from_date = CustomHelper::DateFormat($request->from,'Y-m-d','d/m/Y');
                $to_date = CustomHelper::DateFormat($request->to,'Y-m-d','d/m/Y');
                $from_date = $from_date.' 00:00:00';
                $to_date = $to_date.' 23:59:59';
            } else if($request->from) {
                $from_date = CustomHelper::DateFormat($request->from,'Y-m-d','d/m/Y');
                $from_date = $from_date.' 00:00:00';
            } else if($request->to) {
                $to_date = CustomHelper::DateFormat($request->to,'Y-m-d','d/m/Y');
                $to_date = $to_date.' 23:59:59';
            }
        }
        if($request->date_type && ($from_date || $to_date)) {
            switch ($request->date_type) {
                case 'enquiry_date':
                        $date_column = 'created_at';
                    break;
                case 'updated_date':
                        $date_column = 'updated_at';
                    break;
                case 'followup_date':
                        $date_column = 'followup_date';
                    break;
                default:
                    $date_column = 'followup_date';
                    break;
            }
            if($date_column == 'updated_at') {
                $query->whereHas('EnquiryInteraction', function($q1) use($from_date, $to_date) {
                    if(!empty($from_date)) {
                        $q1->whereDate('created_at','>=',$from_date);
                    }
                    if(!empty($to_date)) {
                        $q1->whereDate('created_at','<=',$to_date);
                    }
                });
            } else {
                if(!empty($from_date)) {
                    $query->whereDate($date_column,'>=',$from_date);
                }
                if(!empty($to_date)) {
                    $query->whereDate($date_column,'<=',$to_date);
                }
            }
        }

        $enquiries = $query->paginate($limit);

        $data['enquiries'] = $enquiries;

        //$data['destinations'] = Destination::where('is_city',0)->where('status',1)->where('parent_id',0)->orderby('sort_order', 'asc')->get();
        $data['packages'] = Package::where('status',1)->orderBy("sort_order", "asc")->get();
        $data['accommodations'] = Accommodation::with('accommodationDestination')->where('status',1)->orderBy("sort_order", "asc")->get();

        $websiteSettingsNamesArr = ['LEAD_SOURCE_GROUP_ID','LEAD_STATUS_GROUP_ID','RATING_GROUP_ID'];
        $websiteSettingsArr = CustomHelper::websiteSettingsArray($websiteSettingsNamesArr);
        $LEAD_SOURCE_GROUP_ID = (isset($websiteSettingsArr['LEAD_SOURCE_GROUP_ID']))?$websiteSettingsArr['LEAD_SOURCE_GROUP_ID']->value:'1';
        $LEAD_STATUS_GROUP_ID = (isset($websiteSettingsArr['LEAD_STATUS_GROUP_ID']))?$websiteSettingsArr['LEAD_STATUS_GROUP_ID']->value:'2';
        $RATING_GROUP_ID = (isset($websiteSettingsArr['RATING_GROUP_ID']))?$websiteSettingsArr['RATING_GROUP_ID']->value:'3';

        // $data['lead_source_arr'] = EnquiriesMaster::where('status',1)->where('group_id',$LEAD_SOURCE_GROUP_ID)->orderBy('sort_order','asc')->get();
        $data['lead_source_arr'] = EnquiriesMaster::where('status',1)->where('type','lead-source')->orderBy('sort_order','asc')->get();

        $data['lead_status_arr'] = EnquiriesMaster::where('status',1)->where('group_id',$LEAD_STATUS_GROUP_ID)->orderBy('sort_order','asc')->get();

        // $data['lead_statuses'] = EnquiriesMaster::whereNotNull('category')->where(['parent_id'=>0,'group_id'=>null,'status'=>1])->orderBy('sort_order','asc')->get();
        $data['lead_statuses'] = EnquiriesMaster::whereNotNull('category')->where(['parent_id'=>0,'group_id'=>null,'status'=>1,'type'=>'lead-status'])->orderBy('sort_order','asc')->get();

        // $data['rating_arr'] = EnquiriesMaster::where('status',1)->where('group_id',$RATING_GROUP_ID)->orderBy('sort_order','asc')->get();
        $data['rating_arr'] = EnquiriesMaster::where('status',1)->where('type','rating')->orderBy('sort_order','asc')->get();

        return view('admin.enquiries.index', $data);
    }

    public function add(Request $request) {
        $data = [];
        $isSaved = false;
        $id = $request->id??'';
        $cc_id = $request->cc_id??'';
        if($request->method() == 'POST') {
            $response = [];
            $response['success'] = false;
            $nicknames = [
                'enquiry_for' => 'Enquiry For',
                'name' => 'Name',
                'country_code' => 'Country Code',
                'phone' => 'Phone',
                'email' => 'Email',
                'content' => 'Content',
                'destination' => 'Destination',
                'sub_destination' => 'Sub Destination',
                'package_id' => 'Package',
                'accommodation' => 'Accommodation',
                'activity' => 'Activity',
                'lead_source' => 'Lead Source',
                'lead_status' => 'Lead Status',
                'lead_sub_status' => 'Lead Sub Status',
                'rating' => 'Rating',
                'followup_date' => 'Followup Date',
            ];
            $rules = [];
            $rules['enquiry_for'] = 'required';
            $rules['name'] = 'required|regex:/^[\pL\s\-]+$/u';
            $rules['country_code'] = 'nullable';
            $rules['phone'] = 'required|digits:10|regex:/^([0-9\s\-\+\(\)]*)$/';
            $rules['email'] = 'required|email';
            $rules['content'] = 'nullable';
            $rules['destination'] = 'nullable';
            $rules['sub_destination'] = 'nullable';
            $rules['package_id'] = 'nullable';
            $rules['accommodation'] = 'nullable';
            $rules['activity'] = 'nullable';
            $rules['lead_source'] = 'nullable';
            $rules['lead_status'] = 'nullable';
            $rules['lead_sub_status'] = 'nullable';
            $rules['rating'] = 'nullable';
            $rules['followup_date'] = 'nullable';
            $validation_msg['required'] = ':attribute is required.';
            $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
            if ($validator->fails()) {
                return Response::json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ), 400); // 400 being the HTTP code for an invalid request.
            } else {

                $user_id = auth()->user()->id??NULL;
                $now = date('Y-m-d H:i:s');
                $data = $request->toArray();
                $data = $request->except([
                    "_token",
                    "cc_id",
                ]);

                $followup_date = '';
                if($request->followup_date) {
                    $followup_date = CustomHelper::DateFormat($request->followup_date, 'Y-m-d', 'd/m/Y');
                }
                $date_of_travel = '';
                if($request->date_of_travel) {
                    $date_of_travel = CustomHelper::DateFormat($request->date_of_travel, 'Y-m-d', 'd/m/Y');
                }
                $data['followup_date'] = $followup_date??'';
                $data['date_of_travel'] = $date_of_travel??'';
                $data['owner_id'] = $user_id;
                $data['owner_date'] = $now;
                $params = [];
                $params['id'] = $id;
                $params['created_by'] = $user_id;
                $params['created_type'] = 'backend';

                $record=[];
                $record_cc_id=0;
                if($id) {
                    $enquiry = Enquiry::find($id);
                    $record_cc_id = $enquiry->cc_id ;
                    $enquiry_id =$id;
                }

                $isSaved = Enquiry::CreateEnquiry($data, $params);
                $enquiry_id =$isSaved['id'];
                $record = Enquiry::find($enquiry_id);

                if((empty($id)) || (isset($id) && $cc_id && $record_cc_id != $cc_id) ){

                    $user_id = auth()->user()->id??NULL;
                    $data = [];
                    $data['cc_id'] = $cc_id??NULL;
                    $cc_name = CustomHelper::showAdmin($data['cc_id']);
                    $cc_email = CustomHelper::showAdmin($data['cc_id'],'email');
                    //$data['interaction_content'] = 'User "'.$cc_name.'" has been assigned';
                    $params = [];
                    $params['id'] = $enquiry_id;
                    $params['created_by'] = $user_id;
                    $params['created_type'] = 'backend';
                    $is_saved = Enquiry::CreateEnquiry($data, $params);

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

                        $form_values = [];
                        $form_values['{name}'] = $record->name??'';
                        $form_values['{email}'] = $record->email??'';
                        $phone = '';
                        if($record->phone) {
                            $phone = '+'.$record->country_code.'-'.$record->phone;
                        }
                        //$form_values['{logo}'] = $logoSrc;
                        $form_values['{header}'] = $common_logo??'';
                        $form_values['{contact_details}'] = $contact_details??'';
                        $form_values['{phone}'] = $phone;
                        $form_values['{date}'] =  CustomHelper::DateFormat($record->created_at,'d/m/Y','Y-m-d');
                        $form_values['{e_date}'] =  date("l jS \of F Y h:i A");

                        $form_values['{enquiry_id}'] = $record->enquiry_no_id ?? '';
                        $form_values['{customer_name}'] = $record->name ?? '';;
                        $form_values['{url}'] = route('admin.enquiries.index', ['all_enquiries'=>'all_enquiries']);

                        $emailTemplate = EmailTemplate::where('slug', 'enquiry-assigned-email-cc')->where('status', 1)->first();
                        $email_content = isset($emailTemplate->content) ? $emailTemplate->content : '';
                        $email_subject = isset($emailTemplate->subject) ? $emailTemplate->subject : '';

                        // $search_arr = array_keys($form_values)??[];
                        // $replace_arr = array_values($form_values)??[];
                        // $email_subject = str_replace($search_arr, $replace_arr, $email_subject);

                        $search_arr = array_keys($form_values);
                        $replace_arr = array_values($form_values);
                        $email_content = str_replace($search_arr, $replace_arr, $email_content);
                        $email_subject = str_replace($search_arr, $replace_arr, $email_subject);
                        $bcc_email = '';
						if($emailTemplate->email_type == 'customer' && $emailTemplate->bcc_admin == 1){
							$bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL'); 
						}
                        if(isset($emailTemplate) && !empty($emailTemplate)){
                            $reply_to = $record->email??'';
                            $params = [];
                            $params['to'] = $cc_email;
                            $params['cc'] = '';
                            $params['bcc'] = $bcc_email;
                            $params['reply_to'] = $reply_to;
                            $params['subject'] = $email_subject;
                            $params['email_content'] = $email_content;
                            $params['module_name'] = 'Admin enquiry assign';
							$params['record_id'] = $enquiry_id ?? 0;
                            $isSent = CustomHelper::sendCommonMail($params);
                        }
                    }

                    if($isSaved) {
                    $response['success'] = true;
                    if($id) {
                        $response['msg'] = 'Enquiry updated successfully.';
                    } else {
                        $response['msg'] = 'Enquiry created successfully.';
                    }
                } else {
                    $response['msg'] = 'Enquiry not saved';
                }
                return response()->json($response);
          }
        }
        if($id) {
            $data['record'] = Enquiry::find($id);
        }
        $data['id'] = $id;

        $data['destinations'] = Destination::where('is_city',0)->where('status',1)->where('parent_id','0')->orderBy('name','asc')->get();
        $data['sub_destinations'] = Destination::where('is_city',0)->where('status',1)->where('parent_id','!=',0)->orderBy('name','asc')->get();
        $data['packages'] = Package::where('status',1)->orderBy("sort_order", "asc")->get();
        $data['accommodations'] = Accommodation::with('accommodationDestination')->where('status',1)->orderBy("sort_order", "asc")->get();
        $data['accommodation_types'] = AccomodationType::where('status',1)->orderBy("sort_order", "asc")->get();


        $websiteSettingsNamesArr = ['LEAD_SOURCE_GROUP_ID','LEAD_STATUS_GROUP_ID','RATING_GROUP_ID'];
        $websiteSettingsArr = CustomHelper::websiteSettingsArray($websiteSettingsNamesArr);
        $LEAD_SOURCE_GROUP_ID = (isset($websiteSettingsArr['LEAD_SOURCE_GROUP_ID']))?$websiteSettingsArr['LEAD_SOURCE_GROUP_ID']->value:'1';
        $LEAD_STATUS_GROUP_ID = (isset($websiteSettingsArr['LEAD_STATUS_GROUP_ID']))?$websiteSettingsArr['LEAD_STATUS_GROUP_ID']->value:'2';
        $RATING_GROUP_ID = (isset($websiteSettingsArr['RATING_GROUP_ID']))?$websiteSettingsArr['RATING_GROUP_ID']->value:'3';

        // $data['lead_source_arr'] = EnquiriesMaster::where('status',1)->where('group_id',$LEAD_SOURCE_GROUP_ID)->orderBy('sort_order','asc')->get();
        $data['lead_source_arr'] = EnquiriesMaster::where('status',1)->where('type','lead-source')->orderBy('sort_order','asc')->get();

        // $data['lead_status_arr'] = EnquiriesMaster::where('status',1)->where('group_id',$LEAD_STATUS_GROUP_ID)->orderBy('sort_order','asc')->get();
        $data['lead_status_arr'] = EnquiriesMaster::where('status',1)->where('type','lead-status')->orderBy('sort_order','asc')->get();

        // $data['rating_arr'] = EnquiriesMaster::where('status',1)->where('group_id',$RATING_GROUP_ID)->orderBy('sort_order','asc')->get();
        $data['rating_arr'] = EnquiriesMaster::where('status',1)->where('type','rating')->orderBy('sort_order','asc')->get();

        $data['users'] = Admin::where('status',1)->get();

        return view('admin.enquiries.form', $data);
    }

    public function view(Request $request) {
        if($request->id) {
            $data = [];
            $record = Enquiry::find($request->id);
            if($record){
                $user_id = '';
                $user_name = '';
                $auth_user = auth()->guard('admin')->user();
                if($auth_user){
                    $user_id = $auth_user->id??0;
                    $user_name =  $auth_user->name??'';
                }
                if($record->viewed == 0) {
                    $params = [];
                    $params['id'] = $record->id;
                    $data['viewed'] = 1;
                    $data['interaction_content'] = 'Enquiry Viewed by  '.$user_name;
                    $data['created_by'] = $user_id;
                    $data['created_type'] = 'backend';
                    //prd($params);
                    $enquiry = Enquiry::CreateEnquiry($data,$params);
                    if($enquiry['success']) {
                        //$response['success'] = true;
                    }                    
                }
            }
            $data['enquiry'] = $record;
            return view("admin.enquiries.view", $data);
        }
    }

    public function delete(Request $request) {
        if($request->id) {
            try {
                $record = Enquiry::find($request->id);
                if($record) {
                    // $record->delete();
                    $record->is_deleted = 1;
                    $record->save();
                    return back()->with('alert-success', 'Enquiry deleted successfully.');
                } else {
                    return back()->with('alert-danger', 'The Enquiry cannot be Deleted, please try again or contact the administrator.');
                }
            } catch (\Exception $e) {
                return back()->with('alert-danger', $e->getMessage());
            }
        } else {
            return back()->with('alert-danger', 'The Enquiry cannot be Deleted, please try again or contact the administrator.');
        }
    }

    public function ajax_enquiry_interaction(Request $request) {
        $response = [];
        $rating_arr = '';
        $lead_source_arr = '';
        $lead_status_arr = '';
        $response['success'] = false;
        $response['msg'] = '';
        $enquiry_id = $request->enquiry_id??'';
        if($enquiry_id) {
            $data = [];
            $websiteSettingsNamesArr = ['LEAD_SOURCE_GROUP_ID','LEAD_STATUS_GROUP_ID','RATING_GROUP_ID'];
            $websiteSettingsArr = CustomHelper::websiteSettingsArray($websiteSettingsNamesArr);
            $LEAD_SOURCE_GROUP_ID = (isset($websiteSettingsArr['LEAD_SOURCE_GROUP_ID']))?$websiteSettingsArr['LEAD_SOURCE_GROUP_ID']->value:'1';
            $LEAD_STATUS_GROUP_ID = (isset($websiteSettingsArr['LEAD_STATUS_GROUP_ID']))?$websiteSettingsArr['LEAD_STATUS_GROUP_ID']->value:'2';
            $RATING_GROUP_ID = (isset($websiteSettingsArr['RATING_GROUP_ID']))?$websiteSettingsArr['RATING_GROUP_ID']->value:'3';

            // $data['lead_source_arr'] = EnquiriesMaster::where('status',1)->where('group_id',$LEAD_SOURCE_GROUP_ID)->orderBy('sort_order','asc')->get();
            // $getLeadSourceSlug = EnquiriesMasterGroup::where('slug', 'lead-source')->where('status',1)->first();
            // if(isset($getLeadSourceSlug) && !empty($getLeadSourceSlug)){
            //     $lead_source_arr = EnquiriesMaster::where('status',1)->where('group_id',$getLeadSourceSlug->id)->orderBy('sort_order','asc')->get();
            // }

            $lead_source_arr = EnquiriesMaster::where('status',1)->where('type','lead-source')->orderBy('sort_order','asc')->get();

            // $data['lead_status_arr'] = EnquiriesMaster::where('status',1)->where('group_id',$LEAD_STATUS_GROUP_ID)->orderBy('sort_order','asc')->get();
            $getLeadStatusSlug = EnquiriesMasterGroup::where('slug', 'lead-status')->where('status',1)->first();
            if(isset($getLeadStatusSlug) && !empty($getLeadStatusSlug)){
                $lead_status_arr = EnquiriesMaster::where('status',1)->where('group_id',$getLeadStatusSlug->id)->orderBy('sort_order','asc')->get();
            }

            // $data['lead_statuses'] = EnquiriesMaster::whereNotNull('category')->where(['parent_id'=>0,'group_id'=>null,'status'=>1])->orderBy('sort_order','asc')->get();
            $data['lead_statuses'] = EnquiriesMaster::whereNotNull('category')->where(['parent_id'=>0,'group_id'=>null,'status'=>1,'type'=>'lead-status'])->orderBy('sort_order','asc')->get();

            // $data['rating_arr'] = EnquiriesMaster::where('status',1)->where('group_id',$RATING_GROUP_ID)->orderBy('sort_order','asc')->get();
            // $getRatingSlug = EnquiriesMasterGroup::where('slug', 'rating')->where('status',1)->first();
            // if(isset($getRatingSlug) && !empty($getRatingSlug)){
            //     $rating_arr = EnquiriesMaster::where('status',1)->where('group_id',$getRatingSlug->id)->orderBy('sort_order','asc')->get();
            // }
            $rating_arr = EnquiriesMaster::where('status',1)->where('type','rating')->orderBy('sort_order','asc')->get();

            $data['lead_source_arr'] = $lead_source_arr;
            $data['lead_status_arr'] = $lead_status_arr;
            $data['rating_arr'] = $rating_arr;

            $data['enquiry'] = Enquiry::find($enquiry_id);
            $htmlData = view('admin.enquiries._interactions', $data)->render();
            $response['success'] = true;
            $response['htmlData'] = $htmlData;
        }
        return response()->json($response);
    }

    public function add_interaction(Request $request) {
        $data = [];
        $id = $request->enquiry_id??'';
        if($request->method() == 'POST') {
            $response = [];
            $response['success'] = false;
            $nicknames = [
                // 'enquiry_for' => 'Enquiry For',
                // 'name' => 'Name',
                // 'country_code' => 'Country Code',
                // 'phone' => 'Phone',
                // 'email' => 'Email',
                // 'content' => 'Content',
                // 'destination' => 'Destination',
                // 'sub_destination' => 'Sub Destination',
                // 'package' => 'Package',
                // 'accommodation' => 'Accommodation',
                // 'activity' => 'Activity',
                // 'lead_source' => 'Lead Source',
                'lead_status' => 'Lead Status',
                'lead_sub_status' => 'Lead Sub Status',
                'rating' => 'Rating',
                'followup_date' => 'Followup Date',
            ];
            $rules = [];
            // $rules['enquiry_for'] = 'required';
            // $rules['name'] = 'required';
            // $rules['country_code'] = 'nullable';
            // $rules['phone'] = 'required';
            // $rules['email'] = 'required';
            // $rules['content'] = 'nullable';
            // $rules['destination'] = 'nullable';
            // $rules['sub_destination'] = 'nullable';
            // $rules['package'] = 'nullable';
            // $rules['accommodation'] = 'nullable';
            // $rules['activity'] = 'nullable';
            // $rules['lead_source'] = 'nullable';
            $rules['lead_status'] = 'required';
            $rules['lead_sub_status'] = 'nullable';
            $rules['rating'] = 'nullable';
            $rules['followup_date'] = 'nullable';
            $validation_msg['required'] = ':attribute is required.';
            $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
            if ($validator->fails()) {
                return Response::json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ), 400); // 400 being the HTTP code for an invalid request.
            } else {
                $user_id = auth()->user()->id??NULL;
                $now = date('Y-m-d H:i:s');
                $data = $request->toArray();
                $followup_date = '';
                if($request->followup_date) {
                    $followup_date = CustomHelper::DateFormat($request->followup_date, 'Y-m-d', 'd/m/Y');
                }
                $data['followup_date'] = $followup_date??'';
                // $data['owner_id'] = $user_id;
                // $data['owner_date'] = $now;
                $params = [];
                $params['id'] = $id;
                $params['created_by'] = $user_id;
                $params['created_type'] = 'backend';
                $enquiry = Enquiry::CreateEnquiry($data, $params);
                if($enquiry['success']) {
                    $response['success'] = true;
                    $response['msg'] = 'Interaction added successfully.';
                    $enquiry_id = $enquiry['id'];
                    if($enquiry_id) {
                        $data = [];
                        $data['enquiry'] = Enquiry::find($enquiry_id);
                        $htmlData = view('admin.enquiries._last_interaction', $data)->render();
                        $response['htmlData'] = $htmlData;


                        $data['lead_statuses'] = EnquiriesMaster::whereNotNull('category')->where(['parent_id'=>0,'group_id'=>null,'status'=>1,'type'=>'lead-status'])->orderBy('sort_order','asc')->get();
                        $rating_arr = EnquiriesMaster::where('status',1)->where('type','rating')->orderBy('sort_order','asc')->get();
                        $data['rating_arr'] = $rating_arr;
                        $data['enquiry'] = Enquiry::find($enquiry_id);
                        $htmlData1 = view('admin.enquiries._interactions', $data)->render();
                        $response['htmlData1'] = $htmlData1;

                    }
                } else {
                    $response['msg'] = $enquiry['msg'];
                }
                return response()->json($response);
            }
        }
    }

    public function get_last_interaction(Request $request) {
        $response = [];
        $response['success'] = false;
        $enquiry_id = $request->enquiry_id??'';
        if($enquiry_id && $request->method() == 'POST') {
            $data = [];
            $data['enquiry'] = Enquiry::find($enquiry_id);
            $htmlData = view('admin.enquiries._last_interaction', $data)->render();
            $response['htmlData'] = $htmlData;
            $response['success'] = true;        
        }
        return response()->json($response);
    }

    public function ajax_enquiry_users(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['msg'] = '';
        $enquiry_id = $request->enquiry_id??'';
        if($enquiry_id) {
            $data = [];
            $data['enquiry'] = Enquiry::find($enquiry_id);
            $data['users'] = Admin::where('status',1)->get();
            $htmlData = view('admin.enquiries._users', $data)->render();
            $response['success'] = true;
            $response['htmlData'] = $htmlData;
        }
        return response()->json($response);
    }

    public function assign(Request $request) {
        if($request->enquiry_id && $request->user_id) {
            try {
                $record = Enquiry::find($request->enquiry_id);
                if($record) {
                    $user_id = auth()->user()->id??NULL;
                    $data = [];
                    $data['cc_id'] = $request->user_id??NULL;
                    $cc_name = CustomHelper::showAdmin($data['cc_id']);
                    $cc_email = CustomHelper::showAdmin($data['cc_id'],'email');
                    $data['viewed'] = 1;
                    $data['interaction_content'] = 'User "'.$cc_name.'" has been assigned';

                    $params = [];
                    $params['id'] = $request->enquiry_id;
                    $params['created_by'] = $user_id;
                    $params['created_type'] = 'backend';
                    $enquiry = Enquiry::CreateEnquiry($data, $params);
                    if($enquiry['success']) {

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

                        $form_values = [];
                        $form_values['{header}'] = $common_logo??'';
                        $form_values['{name}'] = $record->name??'';
                        $form_values['{contact_details}'] = $contact_details??'';
                        $form_values['{email}'] = $record->email??'';
                        $phone = '';
                        if($record->phone) {
                            $phone = '+'.$record->country_code.'-'.$record->phone;
                        }
                        $form_values['{phone}'] = $phone;
                        $form_values['{date}'] =  CustomHelper::DateFormat($record->created_at,'d/m/Y','Y-m-d');
                        $form_values['{e_date}'] =  date("l jS \of F Y h:i A");

                        $form_values['{enquiry_id}'] = $record->enquiry_no_id ?? '';
                        $form_values['{customer_name}'] = $record->name ?? '';;
                        $form_values['{url}'] = route('admin.enquiries.index', ['all_enquiries'=>'all_enquiries']);

                        $emailTemplate = EmailTemplate::where('slug', 'enquiry-assigned-email-cc')->where('status', 1)->first();
                        $email_content = isset($emailTemplate->content) ? $emailTemplate->content : '';
                        $email_subject = isset($emailTemplate->subject) ? $emailTemplate->subject : '';

                        // $search_arr = array_keys($form_values)??[];
                        // $replace_arr = array_values($form_values)??[];
                        // $email_subject = str_replace($search_arr, $replace_arr, $email_subject);

                        $search_arr = array_keys($form_values);
                        $replace_arr = array_values($form_values);
                        $email_content = str_replace($search_arr, $replace_arr, $email_content);
                        $email_subject = str_replace($search_arr, $replace_arr, $email_subject);
                        $bcc_email = '';
						if($emailTemplate->email_type == 'customer' && $emailTemplate->bcc_admin == 1){
							$bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL'); 
						}
                        if(isset($emailTemplate) && !empty($emailTemplate)){
                            $reply_to = $record->email??'';
                            $params = [];
                            $params['to'] = $cc_email;
                            $params['cc'] = '';
                            $params['bcc'] = $bcc_email;
                            $params['reply_to'] = $reply_to;
                            $params['subject'] = $email_subject;
                            $params['email_content'] = $email_content;
                            $params['module_name'] = 'Admin enquiry assign';
							$params['record_id'] = $request->enquiry_id ?? 0;
                            $isSent = CustomHelper::sendCommonMail($params);
                        }

                        return back()->with('alert-success', 'User "'.$cc_name.'" has been assigned');
                    } else {
                        prd($enquiry);
                        return back()->with('alert-danger', $enquiry['msg']);
                    }
                } else {
                    return back()->with('alert-danger', 'The Enquiry cannot be assigned, please try again or contact the administrator.');
                }
            } catch (\Exception $e) {
                return back()->with('alert-danger', $e->getMessage());
            }
        } else {
            return back()->with('alert-danger', 'The Enquiry cannot be assigned, please try again or contact the administrator.');
        }
    }


    public function remove_assign(Request $request) {
        if($request->enquiry_id && $request->user_id) {
            try {
                $record = Enquiry::find($request->enquiry_id);
                if($record) {
                    $user_id = auth()->user()->id??NULL;
                    $data = [];
                    $data['cc_id'] = NULL;
                    $cc_name = CustomHelper::showAdmin($request->user_id);
                    $data['interaction_content'] = 'User "'.$cc_name.'" has been removed from assignee';
                    $params = [];
                    $params['id'] = $request->enquiry_id;
                    $params['created_by'] = $user_id;
                    $params['created_type'] = 'backend';
                    $enquiry = Enquiry::CreateEnquiry($data, $params);
                    if($enquiry['success']) {
                        return back()->with('alert-success', 'User "'.$cc_name.'" has been removed from assignee');
                    } else {
                        prd($enquiry);
                        return back()->with('alert-danger', $enquiry['msg']);
                    }
                } else {
                    return back()->with('alert-danger', 'The Enquiry assignee cannot be removed, please try again or contact the administrator.');
                }
            } catch (\Exception $e) {
                return back()->with('alert-danger', $e->getMessage());
            }
        } else {
            return back()->with('alert-danger', 'The Enquiry assignee cannot be removed, please try again or contact the administrator.');
        }
    }


}