<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormElement;
use DB;
use Illuminate\Http\Request;

class FormController extends Controller
{

    protected $select_cols;
    protected $ADMIN_ROUTE_NAME;
    private $limit;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->limit = 20;
    }

    public function index(Request $request)
    {

        $data = [];
        $limit = $this->limit;
        $parent_id = (isset($request->parent_id)) ? $request->parent_id : 0;
        $name = isset($request->name) ? $request->name:'';
        $status = isset($request->status) ? $request->status:1;

        $form_query = Form::orderBy('created_at', 'desc');

        if(!empty($name)){
            $form_query->where("name", "like", "%" . $name . "%");

        }

        $form_query->where('status',$status);

        $forms = $form_query->paginate($limit);
        //prd($forms);

        $data['forms'] = $forms;

        return view('admin.forms.index', $data);
    }

    public function add(Request $request) {
        $id = (isset($request->id)) ? $request->id : 0;
        $parent_id = (isset($request->parent_id)) ? $request->parent_id : 0;
        $eventID = (isset($request->eventID)) ? $request->eventID : 0;
        $form = [];
        $title = '';
        if (is_numeric($id) && $id > 0) {
            $form = Form::find($id);
            $title = 'Edit Form('.$form->name." )";
        } else {
            $title = 'Add Form';
        }
        if ($request->method() == 'POST' || $request->method() == 'post') {
            return $this->save($request, $form, $id);
        }
        $data = [];
        $data['page_heading'] = $title;
        $data['form'] = $form;
        $data['id'] = $id;
        $data['parent_id'] = $parent_id;
        $formElementQuery = FormElement::orderBy('position','asc');
        if (isset($form->id) && $form->id == $id) {
            $formElementQuery->where('form_id', $id);
        } else {
            $formElementQuery->whereNull('form_id');
            $formElementQuery->where('is_static', 1);
        }
        $formElements = $formElementQuery->get();
        $data['formElements'] = $formElements;
        $data['form_attribute'] = DB::table('form_attributes')->where('status', 1)->get();

        return view('admin.forms.form', $data);
    }

    public function save(Request $request, $form, $id) {
        $back_url = (isset($request->back_url))?$request->back_url:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        if (empty($back_url)) {
            $back_url = 'admin/forms';
        }

        $rules = [];
        $validation_msg = [];
        $rules['name'] = 'required';
        $rules['status'] = 'required';

        $validations_msg['name.required'] = 'The Name field is required.';
        if($request->sendMail) {
            $rules['thank_you_msg'] = 'required';
            $rules['to_email'] = 'required';
            $rules['subject'] = 'required';
            $rules['email_template'] = 'required';
        }
        $this->validate($request, $rules, $validation_msg);
        $req_data = [];
        
        $req_data['name'] = $request->name;
        $req_data['thank_you_msg'] = $request->thank_you_msg;
        $req_data['captcha'] = $request->captcha;
        $req_data['status'] = $request->status;
        $req_data['thank_page_url'] = $request->thank_page_url;
        $req_data['to_email'] = $request->to_email;
        $req_data['cc_email'] = $request->cc_email;
        $req_data['bcc_email'] = $request->bcc_email;
        $req_data['subject'] = $request->subject;
        $req_data['email_template'] = $request->email_template;
        $req_data['btnName'] = isset($request->btnName)?$request->btnName:'Submit';
        $req_data['is_reset'] = isset($request->is_reset)?$request->is_reset:0;
        $req_data['sendMail'] = isset($request->sendMail)?$request->sendMail:0;
        $req_data['captcha'] = isset($request->captcha)?$request->captcha:0;

        if (!empty($form) && count($form->toArray()) > 0 && $form->id == $id) {
            $isSaved = Form::where('id', $form->id)->update($req_data);
            $form_id = $form->id;
        } else {
            $slug = CustomHelper::GetSlug('forms', 'id', $id, $request->name,$separater='_');
        $req_data['slug'] = $slug;
            $isSaved = Form::create($req_data);
            $form_id = $isSaved->id;
        }

        if(is_numeric($form_id) && $form_id > 0) {
            $is_static_arr = (isset($request->is_static))?$request->is_static:[];
            $field_label_arr = (isset($request->field_label))?$request->field_label:[];
            $field_type_arr = (isset($request->field_type))?$request->field_type:[];
            $validations_arr = (isset($request->validations))?$request->validations:[];
            $data_arr = (isset($request->data))?$request->data:[];
            $defaultValue_arr = (isset($request->defaultValue))?$request->defaultValue:[];
            $is_display_arr = (isset($request->is_display))?$request->is_display:[];
            $position_arr = (isset($request->position))?$request->position:[];
            $className_arr = (isset($request->className))?$request->className:[];
            $classNameInner_arr = (isset($request->classNameInner))?$request->classNameInner:[];
            $enquiryMapping_arr = (isset($request->enquiryMapping))?$request->enquiryMapping:[];
            $is_hide_arr = (isset($request->is_hide))?$request->is_hide:[];
            $form_element_ids_arr = (isset($request->form_element_ids))?$request->form_element_ids:[];
            $placeHolder_arr = (isset($request->placeHolder))?$request->placeHolder:[];

            foreach ($field_label_arr as $fKey => $label){
                if($label!='') {
                    $field_type = (isset($field_type_arr[$fKey]))?$field_type_arr[$fKey]:'';
                    $validation = (isset($validations_arr[$fKey]))?$validations_arr[$fKey]:'';
                    $data = (isset($data_arr[$fKey]))?$data_arr[$fKey]:'';
                    $defaultValue = (isset($defaultValue_arr[$fKey]))?$defaultValue_arr[$fKey]:'';
                    $is_display = (isset($is_display_arr[$fKey]))?$is_display_arr[$fKey]:0;
                    $position = (isset($position_arr[$fKey]))?$position_arr[$fKey]:999;
                    $className = (isset($className_arr[$fKey]))?$className_arr[$fKey]:'';
                    $classNameInner = (isset($classNameInner_arr[$fKey]))?$classNameInner_arr[$fKey]:'';
                    $enquiryMapping = (isset($enquiryMapping_arr[$fKey]))?$enquiryMapping_arr[$fKey]:'';
                    $placeHolder = (isset($placeHolder_arr[$fKey]))?$placeHolder_arr[$fKey]:'';
                    $is_static = (isset($is_static_arr[$fKey]))?$is_static_arr[$fKey]:0;
                    $is_hide = (isset($is_hide_arr[$fKey]))?$is_hide_arr[$fKey]:0;
                    $element_id = (isset($form_element_ids_arr[$fKey]))?$form_element_ids_arr[$fKey]:'';
                    $elementData = [];
                    $element = new FormElement;
                    if($element_id!='' && is_numeric($element_id) && $element_id > 0){
                        $exist = FormElement::find($element_id);
                        if(isset($exist->id) && $exist->id == $element_id) {
                            $element = $exist;
                        }
                    }

                    # if(!isset($element->is_static) || $element->is_static != '1'){
                        $element->form_id = $form_id;
                    #  }

                    $element->label = $label;
                    $element->type = $field_type;
                    $element->validation = $validation;
                    $element->is_static = $is_static;
                    $element->data = $data;  
                    $element->defaultValue = $defaultValue;   
                    $element->is_display = $is_display;   
                    $element->position = $position;   
                    $element->className = $className;
                    $element->placeHolder = $placeHolder;   
                    $element->classNameInner = $classNameInner;   
                    $element->enquiryMapping = $enquiryMapping;   
                    $element->is_hide = $is_hide;
                    $isSaved = $element->save();
                }
            }
        }
        if ($isSaved) {
            $new_data = DB::table('forms')->where('id',$form_id)->first();
            $module_desc =  !empty($new_data->name)?$new_data->name:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $module_id = $form_id;

            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Form';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = (!empty($id)) ? "Update" : "Add";
            CustomHelper::RecordActivityLog($params);

            return redirect(url($back_url))->with('alert-success', 'The Form has been saved successfully.');
        } else {
            return back()->with('alert-danger', 'The Form cannot be added, please try again or contact the administrator.');
        }

    }

/*
public function save(Request $request, $form, $id, $eventID)
{
//prd($request->toArray());

$back_url = (isset($request->back_url)) ? $request->back_url : '';
if (empty($back_url)) {
$back_url = 'admin/forms';
}

$rules = [];
$validation_msg = [];

//$rules['type'] = 'required';
$rules['name'] = 'required';
$rules['status'] = 'required';
$rules['eventID'] = 'required';

# $rules['template'] = 'required';
$this->validate($request, $rules, $validation_msg);

$req_data = [];

//$req_data = $request->except(['_token', 'id','back_url']);

// $slug = CustomHelper::GetSlug('forms', 'id', $id, $request->name);

$req_data['eventID'] = $request->eventID;

$req_data['name'] = $request->name;
$req_data['thank_you_msg'] = $request->thank_you_msg;

$req_data['captcha'] = $request->captcha;
$req_data['status'] = $request->status;
//prd($req_data);

if (!empty($form) && $form->id == $id) {

$isSaved = Form::where('id', $form->id)->update($req_data);
} else {
$isSaved = Form::create($req_data);
$id = $isSaved->id;

$req_data1['slug'] = 'forms_' . $id;
Form::where('id', $id)->update($req_data1);
}

if (is_numeric($id) && $id > 0) {

$is_static_arr = (isset($request->is_static)) ? $request->is_static : [];
$field_label_arr = (isset($request->field_label)) ? $request->field_label : [];
$field_type_arr = (isset($request->field_type)) ? $request->field_type : [];
$validations_arr = (isset($request->validations)) ? $request->validations : [];
$data_arr = (isset($request->data)) ? $request->data : [];
$defaultValue_arr = (isset($request->defaultValue)) ? $request->defaultValue : [];
$is_display_arr = (isset($request->is_display)) ? $request->is_display : [];
$position_arr = (isset($request->position)) ? $request->position : [];
$className_arr = (isset($request->className)) ? $request->className : [];

$form_element_ids_arr = (isset($request->form_element_ids)) ? $request->form_element_ids : [];

foreach ($field_label_arr as $fKey => $label) {
if ($label != '') {
//$field_label = (isset($field_label_arr[$fKey]))?$field_label_arr[$fKey]:'';
$field_type = (isset($field_type_arr[$fKey])) ? $field_type_arr[$fKey] : '';
$validation = (isset($validations_arr[$fKey])) ? $validations_arr[$fKey] : '';
$data = (isset($data_arr[$fKey])) ? $data_arr[$fKey] : '';
$defaultValue = (isset($defaultValue_arr[$fKey])) ? $defaultValue_arr[$fKey] : '';
$is_display = (isset($is_display_arr[$fKey])) ? $is_display_arr[$fKey] : 0;
$position = (isset($position_arr[$fKey])) ? $position_arr[$fKey] : 999;
$className = (isset($className_arr[$fKey])) ? $className_arr[$fKey] : '';

$is_static = (isset($is_static_arr[$fKey])) ? $is_static_arr[$fKey] : 0;
$element_id = (isset($form_element_ids_arr[$fKey])) ? $form_element_ids_arr[$fKey] : '';

$elementData = [];

$element = new FormElement;

if ($element_id != '' && is_numeric($element_id) && $element_id > 0) {
$exist = FormElement::find($element_id);

if (isset($exist->id) && $exist->id == $element_id) {
$element = $exist;
}
}

if (!isset($element->is_static) || $element->is_static != '1') {
$element->form_id = $id;
}

$element->label = $label;
$element->type = $field_type;
$element->validation = $validation;
$element->is_static = $is_static;
$element->data = $data;
$element->defaultValue = $defaultValue;
$element->is_display = $is_display;
$element->position = $position;
$element->className = $className;
$isSaved = $element->save();
}
}
}
if ($isSaved) {
//$this->saveCategories($request, $product_id);
return redirect(url($back_url))->with('alert-success', 'The Form has been saved successfully.');
} else {
return back()->with('alert-danger', 'The Form cannot be added, please try again or contact the administrator.');
}

}
*/

public function delete(Request $request)
{
    $id = isset($request->id)?$request->id:'';
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;

    $method = $request->method();
    $is_deleted = 0;
    $eventID = 0;
    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {
            $form = Form::find($id);
            $new_data = DB::table('forms')->where('id',$id)->first();
            $module_desc =  !empty($new_data->name)?$new_data->name:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);
            $eventID = $form->eventID;
            $is_delete = Form::formsDelete($id);
            if ($is_delete['status'] != 'ok') {

                return redirect(url('admin/forms'))->with('alert-danger', $is_delete['message']);

            } 
            else {

                $delete_form_name = $is_delete['name'];

                $is_deleted = true;

                    // if($is_deleted != null){
                    // return redirect(
                    // url($this->ADMIN_ROUTE_NAME . "/accommodations/category")
                    // )->with(
                    // "alert-danger",
                    // "You cannot delete ".$delete_category_name
                    // );
                    // }
            }
            if (!empty($form)) {

                $formEle = FormElement::where('form_id', $id)->delete();
                //$is_deleted = $form->delete();
            }
        }
    }

    if ($is_deleted) {

         //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Form';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data??'';
            $params['action'] = 'Delete';

            CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================

        return redirect(url($this->ADMIN_ROUTE_NAME . '/forms?eventID=' . $eventID))->with('alert-success', 'The Form has been deleted successfully.');
    } else {
        return redirect(url($this->ADMIN_ROUTE_NAME . '/forms?eventID=' . $eventID))->with('alert-danger', 'The Form cannot be deleted, please try again or contact the administrator.');
    }

}

/* ajax_delete_element */
public function ajaxDeleteElement(Request $request)
{
//prd($request->toArray());

    $response = [];
    $response['success'] = false;

    $form_element_id = (isset($request->form_element_id)) ? $request->form_element_id : 0;

    if (is_numeric($form_element_id) && $form_element_id > 0) {
        $formElement = FormElement::find($form_element_id);

        if (isset($formElement->id) && $formElement->id == $form_element_id) {
            $isDeleted = $formElement->delete();

            if ($isDeleted) {
                $response['success'] = true;
            }
        }
    }

    return response()->json($response);
}

// End of controller
}
