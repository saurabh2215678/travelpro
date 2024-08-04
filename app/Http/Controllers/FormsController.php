<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormAttributes;
use App\Models\Enquiry;
use App\Models\Package;
use App\Models\EnquiriesMaster;
use App\Helpers\CustomHelper;
use Mail;
use Validator;
use Response;

class FormsController extends Controller {

    //private $limit;

    public function __construct(){
        //$this->limit = 20;
    }

    public function index(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $form_slug = $request->form_slug??'';
        if($request->method() == 'POST' && !empty($form_slug)) {
            $data = $request->toArray();
            $form_values = [];
            $form = Form::where('slug',$form_slug)->first();
            if($form) {
                // prd($form->toArray());
                $data['form_id'] = $form->id;
                $form_status = $form->status??'';
                $formElements = $form->formElements??[];
                if(empty($form_status) || empty($formElements)) {
                    return Response::json(array(
                        'success' => false,
                        'message' => 'The form is inactive/empty, please contact the administrator.'
                    ), 400);
                }
                $record = [];
                $form_data = [];
                $nicknames = [];
                $nicknames['form_slug'] = 'Form';
                $rules = [];
                $rules['form_slug'] = 'required';
                // prd($formElements->toArray());
                $form_attributes = FormAttributes::where('status',1)->pluck('validations','type')->toArray();
                // prd($form_attributes);
                $getWebId = EnquiriesMaster::where('status',1)->where(['type'=>'lead-source', 'slug'=>'website'])->first();
                foreach($formElements as $element_key => $element) {
                    switch ($element->type) {
                        case 'file':
                            break;
                        case 'name':
                        case 'email':
                        case 'phone':
                        case 'country':
                        case 'zipcode':
                        case 'text':
                        case 'number':
                        case 'textarea':
                        case 'text_only':
                        case 'checkbox':
                        case 'radio':
                        case 'select':
                        case 'url':
                        case 'datepicker':
                        case 'timepicker':
                        case 'datetimepiker':
                        case 'monthpicker':
                        case 'yearpicker':
                        default:
                                $nicknames['input'.$element->id] = $element->label;
                                $validations = [];
                                if($element->validation) {
                                    $validations[] = $element->validation;
                                } else {
                                    $validations[] = 'nullable';
                                }
                                if(isset($form_attributes[$element->type]) && !empty($form_attributes[$element->type])) {
                                    $validations[] = $form_attributes[$element->type];
                                }
                                if(!empty($validations)) {
                                    if($element->type=='phone') {
                                        $country_code = $data['input'.$formElements[1]->id.'_country_code']??91;
                                        if(!empty($country_code) && $country_code != 91) {
                                            $rules['input'.$element->id] = 'regex:/^\d{4,12}$/';
                                        } else {
                                            $rules['input'.$element->id] = 'digits:10';
                                        }
                                    } else if($element->type=='zipcode') {
                                        $country_code = $data['input'.$formElements[1]->id.'_country_code']??91;
                                        if(!empty($country_code) && $country_code != 91) {
                                            $rules['input'.$element->id] = 'max:10|regex:/^[\s\w-]*$/';
                                        } else {
                                            $rules['input'.$element->id] = 'digits:6';
                                        }
                                    } else {
                                        $rules['input'.$element->id] = implode('|', $validations);
                                    }
                                }
                                if($element->label) {
                                    $label_key = str_replace(' ', '_', $element->label);
                                    $form_values["[".$label_key."]"] = $data['input'.$element->id]??'';
                                }
                                //Add to form data except name, email, phone
                                if($element->type == 'radio') {
                                    if($element_key > 2) {
                                        $element_data = $element->data;
                                        if($element_data) {
                                            $element_data_arr = explode('#', $element_data);
                                            foreach($element_data_arr as $element_data_row) {
                                                $element_data_row_arr = explode(':', $element_data_row);
                                                $input_name = $data['input'.$element->id]??'';
                                                if($element_data_row_arr[0]==$input_name) {
                                                    $form_data[$element->label] = $element_data_row_arr[1]??'';
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if($element_key > 2) {
                                        $form_data[$element->label] = $data['input'.$element->id]??'';
                                    }
                                }
                                if(!empty($element->enquiryMapping) && !empty($form_data[$element->label])) {
                                    $record[$element->enquiryMapping] = $form_data[$element->label];
                                    unset($form_data[$element->label]);
                                }
                            break;
                    }                
                }
                // prd($rules);
                $validation_msg['required'] = ':attribute is required.';
                $validation_msg['regex'] = ':attribute is invalid.';
                $validation_msg['min'] = ':attribute should be minimum :min character.';
                $validation_msg['max'] = ':attribute should not be greater than :max character.';
                $validation_msg['digits'] = ':attribute must be :digits digits.';
                $validation_msg['date_format'] = ':attribute is invalid.';
                $validator = Validator::make($request->all(), $rules, $validation_msg, $nicknames);
                if ($validator->fails()) {
                    return Response::json(array(
                        'success' => false,
                        'errors_fields' => $validator->getMessageBag()->toArray()
                    ), 400); // 400 being the HTTP code for an invalid request.
                } else {
                    // prd($form_data);
                    // prd($formElements->toArray());
                    $record['form_id'] = $data['form_id']??'';
                    $record['refer_url'] = $data['refer_url'] ?? null;
                    $record['form_data'] = $form_data;
                    $record['name'] = $data['input'.$formElements[0]->id]??'';
                    $record['phone'] = $data['input'.$formElements[1]->id]??'';
                    if($record['phone']) {
                        /*$full_number = $data['input'.$formElements[1]->id.'_full_number']??'';
                        $country_code = '';
                        if($full_number) {
                            $country_code = str_replace(['+',$record['phone']], ['',''], $full_number);
                        }
                        $record['country_code'] = (!empty($country_code))?$country_code:91;*/
                       
                            $record['country_code'] = $data['input'.$formElements[1]->id.'_country_code']??91;
                                                
                    }
                    $record['email'] = $data['input'.$formElements[2]->id]??'';
                    $hidden_package = $data['hidden_package']??'';
                    if($hidden_package) {

                        session(['download_package_itinerary'=>$hidden_package]);

                        $record['package_id'] = $hidden_package;
                        $package = Package::find($hidden_package);
                        if($package && $package->destination_id) {
                            $record['destination'] = $package->destination_id;
                        }
                        $is_activity = isset($package->is_activity) ? $package->is_activity:0;
                        $record['enquiry_for'] = 1;
                        if($is_activity == 1){
                            $record['enquiry_for'] = 4;
                        }
                    }
                    $record['followup_date'] = date('Y-m-d H:i:s');
                    $record['interaction_content'] = $form->name.' form enquiries.';
                    $record['lead_source'] = $getWebId->id ?? 0;
                    if(!empty(Auth::check())){
                        $record['user_id'] = Auth::user()->id??0;
                    }
                    $enquiry = Enquiry::CreateEnquiry($record);
                    if($enquiry['success']) {
                        if($form->sendMail == 1) {
                            $email_subject = $form->subject;
                            $email_content = $form->email_template;
                            $to_email = $form->to_email;
                            $cc_email = $form->cc_email;
                            $bcc_email = $form->bcc_email;
                            if(!empty($email_content) && $email_subject && $to_email ){
                                $search_arr = array_keys($form_values)??[];
                                $replace_arr = array_values($form_values)??[];
                                $email_content = str_replace($search_arr, $replace_arr, $email_content);
                                $reply_to = $record['email']??'';
                                $params = [];
                                $params['to'] = $to_email;
                                $params['cc'] = $cc_email;
                                $params['bcc'] = $bcc_email;
                                $params['reply_to'] = $reply_to;
                                $params['subject'] = $email_subject;
                                $params['email_content'] = $email_content;
                                $params['module_name'] = $form->name ?? '';
                                // $params['record_id'] = 0;
                                $isSent = CustomHelper::sendCommonMail($params);
                            }
                        }

                        $response['success'] = true;
                        //$response['message'] = $form->thank_you_msg;
                        $response['redirect_url'] = $form->thank_page_url;
                        if($form->thank_page_url) {
                            session(['alert-success'=>$form->thank_you_msg]);
                        }
                    } else {
                        $response['message'] = $enquiry['msg'];
                    }
                    return response()->json($response);
                }
            } else {
                return Response::json(array(
                    'success' => false,
                    'message' => 'The form did not found, please contact the administrator.'
                ), 400);
            }
        }
    }

    /* end of controller */
}