<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Module;
use App\Models\Role;
use App\Models\Permission;
use App\Helpers\CustomHelper;
//use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;

use Validator;
use Storage;
use Image;
use DB;

class RoleController extends Controller{
    private $limit;
    private $ADMIN_ROUTE_NAME;
    // private $module_id;
    // private $module_key;
    protected $currentUrl;


    public function __construct(){
        $this->limit = 25;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        // $this->module_data = config('custom.modules_arr');
        $this->currentUrl = url()->current();
    }
    public function index(Request $request){

        $data = [];
        $limit = $this->limit;
        $d_query = Role::where('id','!=', 1)->orderBy('name', 'asc');

        if(!empty($request->name)){
            $d_query->where('name', 'like', '%'.$request->name.'%');
        }
        
        // $total_records = $d_query->get()->count();
        $roles = $d_query->paginate($limit);

        $data['roles'] = $roles;
        // $data['total_records'] = $total_records;
        $data['page_heading'] = 'Roles List';
        $data['limit'] = $limit;
        return view('admin.roles.index', $data);
    }
    public function save(Request $request, $id= ''){
     $data= [];
     $state= array();
     $page_heading= 'Add Role';
     $id = (isset($request->id))?$request->id:'';
     $user_id = auth()->user()->id;
     $user_name = auth()->user()->name;

     if(!empty($id))
     {
        $page_heading= 'Edit Role';
        $role= Role::where(['id'=>$id])->first();
        $data['role']= $role;
    }

    $method= $request->method();
    if($method=='POST')
    {
       $rules = [];
       $rules['name'] = 'required';
               //$rules['user_type'] = 'required';
       $this->validate($request, $rules);
               //$req_data['user_type']=$request->user_type;
       $req_data['name']=$request->name;
       $req_data['created_by']=$user_id;
       $req_data['updated_by']=$user_id;

       if(!empty($id))
       {

         $req_data['updated_at']= date('Y-m-d H:i:s');
         $isSaved =  DB::table('roles')->where('id',$id)->update($req_data);
         $roles_id = $id;

      /* $description = json_encode($req_data);
       $function_name = $this->currentUrl;
       $action_table = 'roles';
       $row_id = $roles_id;
       $action_type = 'Edit Role';
       $action_description = 'Edit ('.$request->name.")";
       $description = 'Update('.$request->name.") ".$description;*/
       $success_msg = 'The Role has been updated successfully.';

        //  $isSaved = Role::where('id',$id)->update($req_data);
       if($isSaved)
       {
           $this->savePermission($request, $id);
       }
   }
   else
   {
    $req_data['created_at']= date('Y-m-d H:i:s');
    $req_data['updated_at']= date('Y-m-d H:i:s');
                   // $isSaved = Role::create($req_data);
    $isSaved =  DB::table('roles')->insertGetId($req_data);
    $roles_id = $isSaved;

   /* $description = json_encode($req_data);
    $function_name = $this->currentUrl;
    $action_table = 'roles';
    $row_id = $roles_id;
    $action_type = 'Add Role';
    $action_description = 'Add ('.$request->name.")";
    $description = 'Add('.$request->name.") ".$description;*/
    $success_msg = 'The Role has been added successfully.';

    if($isSaved)
    {
      $this->savePermission($request, $isSaved);
  }

}
if ($isSaved){

    /*CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);*/

    $new_data = DB::table('roles')->where('id',$roles_id)->first();
    $name =  !empty($new_data->name)?$new_data->name:'';
    $new_data =(array) $new_data;
    $new_data = json_encode($new_data);
    $module_desc = $name;
    $module_id = $roles_id;

    $params['user_id'] = $user_id;
    $params['user_name'] = $user_name;
    $params['module'] = 'Role';
    $params['module_desc'] = $module_desc;
    $params['module_id'] = $module_id;
    $params['sub_module'] = "";
    $params['sub_module_id'] = 0;
    $params['sub_sub_module'] = "";
    $params['sub_sub_module_id'] = 0;
    $params['data_after_action'] = $new_data;
    $params['action'] = (!empty($id)) ? "Update" : "Add";
    CustomHelper::RecordActivityLog($params);

    session()->flash('alert-success', $success_msg);
    return redirect(url('admin/roles'));
}
else
{
    return back()->with('alert-danger', 'The Role cannot be saved, please try again or contact the administrator.');
}
}
$data['page_heading']= $page_heading;
$data['roles']= Role::where('id','!=', 1)->get();
            // prd($data['roles']);
$modules = Module::orderBy('sort_order', 'asc')->get();
            //prd($modules);
$data['state']=   $state;
$data['modules']=   $modules;
return view('admin.roles.form', $data);
}
private function savePermission($request, $id){

    $permissionArr = (isset($request->permission))?$request->permission:'';
    $existData = Role::where('id',$id)->first();
    $user_id = auth()->user()->id;
    $name = (isset($existData->name))?$existData->name:'';
        // if(!empty($permissionArr)){
    $jsonData = json_encode($permissionArr);
    $dataArr = [];
    $dataArr['permissions'] = $jsonData;
            //$dataArr['id'] = $id;
    $dataArr['name'] = $name;
    $dataArr['created_by']=$user_id;
    $dataArr['updated_by']=$user_id;
        //  prd($existData->permissions);

        // if(isset($existData->permissions) && !empty($existData->permissions))
        // {
        //  prd($existData->permissions);
        // DB::enableQueryLog();
    DB::table('roles')->where('id',$id)->update($dataArr);
        //dd(DB::getQueryLog());
        //}
       // }
}
public function showRoleDetail(Request $request,$id){
    $data = [];
    $permissionArr = (isset($request->permission))?$request->permission:'';
    $jsonData = json_encode($permissionArr);
    $dataArr['permission'] = $jsonData;
    $modules = Module::orderBy('sort_order', 'asc')->get();
    if(!empty($id))
    {
        $role = Role::where(['id'=>$id])->first();
        $data['role']= $role;
    }
    $data['modules']=   $modules;
    return view('admin.roles.showRoleDetail', $data);

}
public function delete(Request $request)
{
    $id = !empty($request->id)? $request->id:'';
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $method = $request->method();
    $is_deleted = 0;

    if ($method == "POST") {
        if (is_numeric($id) && $id > 0) {
            $roles = Role::find($id);
            $new_data = DB::table('roles')->where('id',$id)->first();
            $module_desc =  !empty($new_data->name)?$new_data->name:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);
           /* $function_name = $this->currentUrl;
            $action_table = "roles";
            $row_id = $id;
            $action_type = "Delete Role";
            $role_name = isset($roles->name) ? $roles->name:'';
            $action_description = "Delete (" . $role_name . ")";
            $description = "Delete (" . $role_name . ")";*/
            $is_delete = Role::roleDelete($id);

            if ($is_delete['status'] != 'ok') {
                return redirect(url('admin/roles'))->with('alert-danger', $is_delete['message']);
            } 
            else {
                $delete_role_name = $is_delete['name'];
                $is_deleted = true;
            }
        }
    }
    if ($is_deleted) {
        /*CustomHelper::recordActionLog(
            $function_name,
            $action_table,
            $row_id,
            $action_type,
            $action_description,
            $description
        );*/

        $params = [];
        $params['user_id'] = $user_id;
        $params['user_name'] = $user_name;
        $params['module'] = 'Role';
        $params['module_desc'] = $module_desc;
        $params['module_id'] = $id;
        $params['sub_module'] = "";
        $params['sub_module_id'] = 0;
        $params['sub_sub_module'] = "";
        $params['sub_sub_module_id'] = 0;
        $params['data_after_action'] = $new_data;
        $params['action'] = 'Delete';
        
        CustomHelper::RecordActivityLog($params);

        return redirect(url($this->ADMIN_ROUTE_NAME . "/roles"))->with(
            "alert-success",
            "Role has been deleted successfully."
        );
    } else {
        return redirect(url($this->ADMIN_ROUTE_NAME . "/roles"))->with(
            "alert-danger",
            "Role cannot be deleted, please try again or contact the administrator."
        );
    }
}
/* end of controller */
}