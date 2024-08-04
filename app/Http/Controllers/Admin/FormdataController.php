<?php
namespace App\Http\Controllers\Admin;

use App\Models\Form;
use App\Models\FormElement;
use App\Models\Form_data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Storage;
use DB;

class FormdataController  extends Controller {

    //protected $foo;

   protected $select_cols;
   protected $ADMIN_ROUTE_NAME;
   private $limit;

    public function __construct(){
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->limit = 20;
    }

    public function index(Request $request)
    {

        $data = [];
        $form_data=[];

        $limit = $this->limit;

$from = (isset($request->from))?$request->from:'';
$to = (isset($request->to))?$request->to:'';
$range = (isset($request->range))?$request->range:'';
$dateType = (isset($request->dateType))?$request->dateType:'created_at';
$status = (isset($request->status))?$request->status:'';

if(!empty($range) && $range != 'custom') {
$date_between_arr = CustomHelper::get_to_from_date($range);
$from = $date_between_arr['from'];
$to = $date_between_arr['to'];
} else {
if($request->from && $request->to) {
    $from = CustomHelper::DateFormat($request->from,'Y-m-d','Y-m-d');
    $to = CustomHelper::DateFormat($request->to,'Y-m-d','Y-m-d');
} else if($request->from) {
    $from = CustomHelper::DateFormat($request->from,'Y-m-d','Y-m-d');
} else if($request->to) {
    $to = CustomHelper::DateFormat($request->to,'Y-m-d','Y-m-d');
}
}



           $formID = (isset($request->formID))?$request->formID:0;

        if($formID>0){   
        
        $s_query = Form_data::orderBy('id', 'desc');
        // $data =$s_query[0]['data'];
        // $unserializeData = unserialize($data);
        // dd($unserializeData);

         $s_query->where('form_id', '=', $formID);

            // if(!empty($name)){
            // $s_query->where('data', 'like', '%'.$name.'%');
            // }
if(!empty($from)){
if($dateType!=''){
   $s_query=$s_query->whereRaw('date('.$dateType.') >= "'.$from.'"');
}
}

if(!empty($to)){
if($dateType!=''){
 $s_query=$s_query->whereRaw('date('.$dateType.') <= "'.$to.'"');
}
}

#prd($s_query->toSql()); 
        $form_data = $s_query->paginate($limit);
    }

        $showOnPage='';
        $data['forms'] = $form_data;     
        $data['limit'] = $limit;
        return view('admin.form_data.index', $data);
    }

    public function showDetail(Request $request)
    {   
        $data = [];
        $dashbordFormElements=array();
        $id = isset($request->id)?$request->id:0;
        $formElements = [];
        $enquiry = [];
        if(is_numeric($id) && $id > 0){
            $enquiry = Form_data::find($id);
            $formElementsList  = CustomHelper::getDashbordFormElements($enquiry->form_id,0); 

            if(!empty($formElementsList)){
                foreach($formElementsList as $val){
                        $formElements['input'.$val->id]=$val; 
                }
            }
        }
         $data['formElements'] = $formElements;
        $data['form_data'] = $enquiry;
        return view('admin.form_data.view', $data);

    }


    public function delete(Request $request)
    {   
        $id = isset($request->id)?$request->id:0;
        //prd($id);
        if(is_numeric($id) && $id > 0){
        $model = Form_data::find($id);
        $model->delete();
        return back()->with('alert-success', 'Form Data deleted successfully.');
        }

        else{
            return back()->with('alert-danger', 'The Form Data cannot be Deleted, please try again or contact the administrator.');
        }
       
    }

// End of controller
}
?>