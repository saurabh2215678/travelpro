<?php
namespace App\Http\Controllers\Admin;
use App\Models\Package;
use App\Models\PriceCategory;
use App\Models\PriceCategoryElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Mail;
use Validator;
use Illuminate\Validation\Rule;
use Storage;
use DB;

class PriceCategoryController extends Controller {

    protected $limit;
    protected $currentUrl;
    protected $ADMIN_ROUTE_NAME;

    public function __construct() {
        $this->limit = 10;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    // Add Price Category Code Here......

    public function index(Request $request)
    {
        $data = [];
        $limit = 50;
        $price_category_name = isset($request->name) ? $request->name : "";
        $status = isset($request->status) ? $request->status : 1;
        $query = PriceCategory::withCount('Packages')->orderBy("created_at", "desc");

        if (!empty($price_category_name)) {
            $query->where("name","like","%" . $price_category_name . "%");
        }
        $query->where("status", $status);
        $price_categories = $query->paginate($limit);

        $data["price_categories"] = $price_categories;

        return view("admin.price_categories.index", $data);
    }

    public function add(Request $request) {
        $id = isset($request->id) ? $request->id : null;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $query = [];
        $title = "Add Price Category";
        $data = [];
        $data['price_category_used'] = false;
        if (is_numeric($id) && $id > 0) {
            $query = PriceCategory::find($id);
            if($query) {
                $title = "Edit Price Category(" . $query->name . ")";               
                $price_category_used = Package::where('price_category',$query->id)->first();
                if($price_category_used) {
                    $data['price_category_used'] = true;
                }
            }
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            // prd($request->toArray());
            $ext = "jpg,jpeg,png,gif";
            $rules["name"] = "required|max:255";
            $rules["display_title"] = "required|max:255";
            $rules["status"] = "required";
            $this->validate($request, $rules);
            $req_data = [
                'name' => $request->name??'',
                'display_title' => $request->display_title??'',
                'status' => $request->status??'',
            ];
            $price_categories_id = 0;
            if (!empty($query) && $query->id == $id) {
                $isSaved = PriceCategory::where("id",$query->id)->update($req_data);
                if($isSaved){
                    $price_categories_id = $query->id;
                    $old_element = $request->old_element;
                    $priceLabel = $request->price_label;
                    $inputLabel = $request->input_label;
                    $inputType = $request->input_type;
                    $inputChoices = $request->input_choices;
                    // prd($inputChoices);
                    if(!empty($priceLabel)) {
                        $sort_order = 0;
                        foreach($priceLabel as $key => $val) {
                            if(!empty($val)) {
                                $sort_order++;
                                $priceElement = [
                                    'pc_id' => $price_categories_id,
                                    'price_label' => $priceLabel[$key],
                                    'input_label' => $inputLabel[$key],
                                    'input_type' => $inputType[$key],
                                    'input_choices' => (!empty($inputChoices) && isset($inputChoices[$key]) && $inputType[$key] == "select") ? json_encode(array_values($inputChoices[$key])) : '[]',
                                    'sort_order' => $sort_order,
                                    'status' => 1
                                ];
                                if(!empty($key) && !empty($old_element) && in_array($key, $old_element)) {
                                    $record = PriceCategoryElement::where('id',$key)->where('pc_id',$price_categories_id)->first();
                                    if($record) {
                                        $priceElement['updated_at'] = date('Y-m-d H:i:s');
                                        PriceCategoryElement::where('id',$record->id)->update($priceElement);
                                    } else {
                                        prd('Something went wrong, please try again.');
                                    }
                                } else {
                                    $priceElement['created_at'] = date('Y-m-d H:i:s');
                                    $priceElement['updated_at'] = date('Y-m-d H:i:s');
                                    PriceCategoryElement::create($priceElement);
                                }
                            }
                        }
                    }
                }
                $msg = "Price Cateory has been updated successfully.";
            } else {
                $isSaved = PriceCategory::create($req_data);
                if($isSaved){
                    $price_categories_id = $isSaved->id;
                    $priceLabel = $request->price_label;
                    $inputLabel = $request->input_label;
                    $inputType = $request->input_type;
                    $inputChoices = $request->input_choices;

                    if(!empty($priceLabel)) {
                        $sort_order = 0;
                        $priceElementArr = [];
                        foreach($priceLabel as $key => $val) {
                            if(!empty($val)) {
                                $sort_order++;
                                $priceElementArr[] = [
                                    'pc_id' => $price_categories_id,
                                    'price_label' => $priceLabel[$key],
                                    'input_label' => $inputLabel[$key],
                                    'input_type' => $inputType[$key],
                                    'input_choices' => (!empty($inputChoices) && isset($inputChoices[$key]) && $inputType[$key] == "select") ? json_encode(array_values($inputChoices[$key])) : '[]',
                                    'sort_order' => $sort_order,
                                    'status' => 1,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                ];
                            }
                        }
                        if(!empty($priceElementArr) && count($priceElementArr) > 0) {
                            PriceCategoryElement::insert($priceElementArr);
                        }
                    }
                }
                $msg = "Price Category has been added successfully.";
            }

            if ($isSaved) {
                $new_data = DB::table('price_categories')->where('id',$price_categories_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $module_id = $price_categories_id??0;

                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Price Category';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                return redirect(url($this->ADMIN_ROUTE_NAME . "/price-category"))->with("alert-success", $msg);
            } else {
                return back()->with("alert-danger","The Price Category could be added, please try again or contact the administrator.");
            }
        }

        
        $data["page_heading"] = $title;
        $data["price_category"] = $query;
        $data["id"] = $id;

        return view("admin.price_categories.form", $data);
    }

    public function delete(Request $request)
    {
        $id = isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method = $request->method();
        $is_deleted = false;
        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $query = PriceCategory::find($id);
                /*$function_name = $this->currentUrl;
                $action_table = "price_categories";
                $row_id = $id;
                $action_type = "Delete Price Category";
                $priceName = isset($query->name) ? $query->name:'';
                $action_description = "Delete (" . $priceName . ")";
                $description = "Delete (" . $priceName . ")";*/

                $new_data = DB::table('price_categories')->where('id',$id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                PriceCategoryElement::where('pc_id',$id)->delete();

                $is_delete = PriceCategory::priceCategoryDelete($id);
                if ($is_delete['status'] != 'ok') {
                $is_deleted = false;
                return redirect(url($this->ADMIN_ROUTE_NAME . "/price-category"))->with('alert-danger', $is_delete['message']);

                } 
                else {

                $delete_price_category_name = $is_delete['name'];

                $is_deleted = true;

                }
                // if (!empty($query)) {
                //     $is_deleted = $query->delete();
                // }
            }
        }
        if ($is_deleted) {
            
             /* CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type,$action_description,$description
            );*/

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Price Category';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = 'Delete';

            CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================

            

            return redirect(url($this->ADMIN_ROUTE_NAME . "/price-category"))->with(
                "alert-success",
                "The Price Category has been deleted successfully."
            );
        } else {
            return redirect(url($this->ADMIN_ROUTE_NAME . "/price-category"))->with(
                "alert-danger",
                "The Price Category cannot be deleted, please try again or contact the administrator."
            );
        }
    }

    /* end of controller */
}
