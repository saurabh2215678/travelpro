<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Blog;
use App\Models\Event;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\CmsPages;
use App\Models\Destination;
use App\Models\FaqCategory;
use App\Models\ThemeCategories;
use App\Helpers\CustomHelper;
use Illuminate\Http\RedirectResponse;

use DB;
use Validator;

class MenuController extends Controller{
	private $currentUrl;
	private $limit;

	private $ADMIN_ROUTE_NAME;

	public function __construct(){
		$this->limit = 20;
		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
		$this->currentUrl = url()->current();

	}


	public function index(Request $request){ 
		$data = [];

		$limit = $this->limit;
		$id = (isset($request->id))?$request->id:'';
		$title = (isset($request->title))?$request->title:'';
		$status = (isset($request->status))?$request->status:1;
        $menu_query = Menu::orderBy('id', 'desc');
        //prd($menu_query);
        if (!empty($title)) {
            $menu_query->where("title", "like", "%" . $title . "%");
        }

        $menu_query->where("status", $status);

        $menus = $menu_query->paginate($limit);
        //$menus = $menu_query->toSql();
        //prd($menus);
		$data['menus'] = $menus;

		return view('admin.menus.index', $data);
	}


	public function add(Request $request){

		 // Creating the object User model 
		$data = [];

		$id = (isset($request->id))?$request->id:0;

		//prd($id);

		$menu = '';

		if(is_numeric($id) && $id > 0){
			$menu = Menu::find($id);
			if(!isset($menu->id) && $menu->id != $id){
				return back();
			}
		}

		if($request->method() == 'POST'){
			return $this->save($request, $id);
		}

		$page_heading = 'Add Menu';

		if(isset($menu->title)){
			$page_heading = 'Update Menu ('.$menu->title.')';
		}


		$data['page_heading'] = $page_heading;

		$data['menu'] = $menu;


		return view('admin.menus.form', $data);

	}
	
	
	private function save($request, $id=''){
		//echo 'save';
		//prd($request->toArray());

		$rules = [];
		$validation_msg = [];
		$user_id = auth()->user()->id;
        $user_name = auth()->user()->name;

		$rules['title'] = 'required';
		$rules['position'] = 'required';
		$rules['status'] = 'required|numeric';

		$this->validate($request, $rules, $validation_msg);

		$data = $request->except(['_token', 'id', 'back_url']);

		$title = $request->title;
		$back_url = $request->back_url;

		if(empty($back_url)){
			$back_url = $this->ADMIN_ROUTE_NAME.'/menus';
		}

		$slug = CustomHelper::GetSlug('menus', 'id', $id, $title);

		$data['slug'] = $slug;

		//prd($data);

		$menu = new Menu;

		if(is_numeric($id) && $id > 0){
			$exist = Menu::find($id);

			if(isset($exist->id) && $exist->id == $id){
				$menu = $exist;
			}
		}

		foreach($data as $key=>$val){
			$menu->$key = $val;
		}

		if(isset($exist)){
			$isSaved = $menu->save();
            $params['action'] = "Update";
		}else{
			$isSaved = $menu->create($menu->toArray());
			$id = $isSaved->id;
            $params['action'] = "Add";
		}

		if($isSaved){

			//=============Activity Logs=====================

                $new_data = DB::table('menus')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Menu';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;

                CustomHelper::RecordActivityLog($params);

			return redirect(url($back_url))->with('alert-success', 'Menu has been updated successfully.');
		}
		else{
			return back()->withInput()->with('alert-danger', 'something went wrong, please try again.');
		}

	}

	// For Deleting
// public function delete(Request $request, $id='')
// {
// 	$logged_user_info=Common::UserData();
// 	if(Adminhelper::check_permission(array('delete'),$logged_user_info['permissions'], true)==false)
// 	{
// 		return redirect('admin/dashboard'); 

// 	} 

// 	$Model= new menu; 
// 	$MenuInfo=$Model ->find($id);
// 	// $is_delete = Menu::menuItemsDelete($id);
//  //    if ($is_delete['status'] != 'ok') {

//  //    return redirect(url('admin/menus'))->with('alert-danger', $is_delete['message']);

//  //    } 
//  //    else {

//  //    $delete_menu_name = $is_delete['name'];

//  //    $is_deleted = true;

//  //    }
// 	$Model->destroy($id);

// 	   // Saving the log
// 	$Logged_User= Common::userdata(); 
// 	$action= 'Deleted the menu-'.$MenuInfo->title ?? '';
// 	Activitylog::saveLogs($user_id=$Logged_User['user_id'], $name=$Logged_User['user_name'], $email=$Logged_User['email'], $module_name='Menu', $action);

// 	DB::table('tbl_menus_items')->where(array('menu_id'=>$id))->delete();
// 	$request->session()->flash('succ_mess', 'Menu deleted successfully.');
// 	return redirect('admin/menus'); 
// }
public function delete(Request $request)
    {
        $id = isset($request->id) ? $request->id:"";
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;

        if ($method == "POST") {
            if (is_numeric($id) && $id > 0) {
                $menu = Menu::find($id);
                /*$function_name = $this->currentUrl;
                $action_table = "menus";
                $row_id = $id;
                $action_type = "Delete Menu";
                $menusTitle = isset($menu->title) ? $menu->title:"";
                $action_description = "Delete (" . $menusTitle . ")";
                $description = "Delete (" . $menusTitle . ")";*/
                $new_data = DB::table('menus')->where('id',$id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $is_delete = Menu::menuItemsDelete($id);
                if ($is_delete['status'] != 'ok') {

                return redirect(url('admin/menus'))->with('alert-danger', $is_delete['message']);

                } 
                else {

                $delete_menu_name = $is_delete['name'];

                $is_deleted = true;

                }
                //$is_deleted = $menu->delete();
            }
        }
        if ($is_deleted) {
            /*CustomHelper::recordActionLog($function_name,$action_table,$row_id,$action_type, $action_description,$description);*/

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Menu';
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

            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/menus")
            )->with(
                "alert-success",
                "Menu  has been deleted successfully."
            );
        } else {
            return redirect(
                url($this->ADMIN_ROUTE_NAME . "/menus")
            )->with(
                "alert-danger",
                "Menu  cannot be deleted, please try again or contact the administrator."
            );
        }
}

public function items(Request $request){

	$data = [];

	$id = (isset($request->id))?$request->id:0;
	$item_id = (isset($request->id))?$request->item_id:0;

	$menu = '';
	$menuItem = '';

	if(is_numeric($item_id) && $item_id > 0){
		$menuItem = MenuItem::find($item_id);
		if(!isset($menuItem->id) && $menuItem->id != $item_id){
			return back();
		}
	}

	//prd($menuItem->toArray());

	

	if(is_numeric($id) && $id > 0){
		$menu = Menu::find($id);

	}
	if(!isset($menu->id) && $menu->id != $id){
		return back();
	}

	if($request->method() == 'POST'){
		return $this->saveMenuItems($request, $id, $item_id);
	}

	$page_heading = $menu->title;

	$data['menu'] = $menu;
	$data['menuItem'] = $menuItem;
	$data['page_heading'] = $page_heading;

	return view('admin.menus.items', $data);

}

private function saveMenuItems($request, $id, $item_id=''){
		//echo 'saveMenuItems';
		//prd($request->toArray());

		$rules = [];
		$validation_msg = [];

		//$rules['title'] = 'required|min:2|regex:/^[\pL\s\-]+$/u';
		$rules['title'] = 'required';
		//$rules['position'] = 'required';
		//$rules['status'] = 'required|numeric';

		$this->validate($request, $rules, $validation_msg);

		//prd($request->toArray());

		$data = $request->except(['_token', 'id', 'back_url', 'item_id']);

		$title = $request->title;
		$back_url = $request->back_url;

		if(empty($back_url)){
			$back_url = $this->ADMIN_ROUTE_NAME.'/menus';
		}

		$slug = CustomHelper::GetSlug('menu_items', 'id', $item_id, $title);

		$data['slug'] = $slug;

		//prd($data);

		$menuItem = new MenuItem;
		$action = 'Add';
		$success_msg = 'Menu Item(s) has been added successfully';
		if(is_numeric($item_id) && $item_id > 0){
			$exist = MenuItem::find($item_id);

			if(isset($exist->id) && $exist->id == $item_id){
				$menuItem = $exist;
				$action = 'Update';
				$success_msg = 'Menu Item(s) has been updated successfully';
			}
		}

		$menuItem->menu_id = $id;

		foreach($data as $key=>$val){
			$menuItem->$key = $val;
		}

		//prd($menuItem->toArray());

		$isSaved = $menuItem->save();
		$menuItem_id = $menuItem->id??'';

		if($isSaved){

			//=============Activity Logs=====================

			$user_id = auth()->user()->id;
			$user_name = auth()->user()->name;

			$new_data = DB::table('menu_items')->where(['id'=>$menuItem_id])->first();
			$module_id =  !empty($new_data->menu_id)?$new_data->menu_id:'';
			$sub_module_desc =  !empty($new_data->title)?$new_data->title:'';
			$new_data =(array) $new_data;
			$new_data = json_encode($new_data);

			$submodule_id = $menuItem_id;

			$menu_list = Menu::where(['id'=>$module_id])->first();
			$module_desc = !empty($menu_list->title)?$menu_list->title:'';

			$params['user_id'] = $user_id;
			$params['user_name'] = $user_name;
			$params['module'] = 'Menu';
			$params['module_desc'] = $module_desc;
			$params['module_id'] = $module_id;
			$params['sub_module'] = 'Menu Item';
			$params['sub_module_id'] = $submodule_id;
			$params['sub_module_desc'] = $sub_module_desc;
			$params['sub_sub_module'] = "";
			$params['sub_sub_module_id'] = 0;
			$params['data_after_action'] = $new_data;
			$params['action'] = $action;

			CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================

			return redirect(url('admin/menus/items/'.$id))->with('alert-success', $success_msg);
		}
		else{
			return back()->withInput()->with('alert-danger', 'something went wrong, please try again.');
		}

	}

	// ajax_get_link_type_list
public function ajaxGetLinkTypeList(Request $request) {
	$response = [];
	$response['success'] = false;
	$link_type = (isset($request->link_type))?$request->link_type:'';
	$page_id = (isset($request->page_id))?$request->page_id:'';
	if(!empty($link_type)) {
		$categories = [];
		$destinations = [];
		$faq_categories = [];
		$blogs = [];
		$news = [];
		$events = [];
		$cms_pages = [];
		$tour_categories = [];
		$modules = [];
		if($link_type == 'category') {
			//SINCE Category MODAL NEVER DEFINED
			// $categories = Category::where('status', 1)->get();
		} else if($link_type == 'destination') {
			$destinations = Destination::where('is_city',0)->where('status', 1)->get();
		}  else if($link_type == 'faq_category') {
			$faq_categories = FaqCategory::where('status', 1)->get();
		} elseif($link_type == 'blog') {
			$blogs = Blog::where(['status'=>1, 'type'=>'blogs'])->get();
		} elseif($link_type == 'news') {
			$news = Blog::where(['status'=>1, 'type'=>'news'])->get();
		} elseif($link_type == 'event') {
			$events = Event::where(['status'=>1])->get();
		} elseif($link_type == 'cms') {
			$cms_pages = CmsPages::where('parent_id',0)->where('status',1)->get();//->where('type','page')
		} elseif($link_type == 'tour_category') {
			$tour_categories = ThemeCategories::where(['status'=>1])->get();
		} elseif($link_type == 'modules') {
			$modules = config('custom.seo_module_config_arr');
		}

		$viewData = [];
		$viewData['link_type'] = $link_type;
		$viewData['page_id'] = $page_id;
		$viewData['categories'] = $categories;
		$viewData['destinations'] = $destinations;
		$viewData['faq_categories'] = $faq_categories;
		$viewData['blogs'] = $blogs;
		$viewData['news'] = $news;
		$viewData['events'] = $events;
		$viewData['cms_pages'] = $cms_pages;
		$viewData['tour_categories'] = $tour_categories;
		$viewData['modules'] = $modules;

		$list = view('admin.menus._link_type_list', $viewData)->render();

		$response['list'] = $list;
		$response['success'] = true;
	}


	return response()->json($response);
}

// ajax_update_items
public function ajaxUpdateItems(Request $request){

	$response = [];
	$response['success'] = false;

	$item_id_arr = (isset($request->item_id))?$request->item_id:[];

	$save_count = 0;

	$message = '';

	if(!empty($item_id_arr) && count($item_id_arr) > 0){
		$menuOrder = 1;
		foreach($item_id_arr as $item_id=>$parent_id){

			$menuItem = MenuItem::find($item_id);
			//prd($menuItem);
			//$action = 'Add';
			if(isset($menuItem->id) && $menuItem->id == $item_id){

				$menuItem->parent_id = ($parent_id != 'null') ? $parent_id : 0;
				$menuItem->sort_order = $menuOrder;

				$isSaved = $menuItem->save();

				if($isSaved){
					$save_count++;
				}
			}
			$menuOrder++;
		}
	}

	if($save_count > 0){

		//=============Activity Logs=====================

			$user_id = auth()->user()->id;
			$user_name = auth()->user()->name;

			$new_data = DB::table('menu_items')->where(['id'=>$menuItem->id])->first();
			$module_id =  !empty($new_data->menu_id)?$new_data->menu_id:'';
			$sub_module_desc =  !empty($new_data->title)?$new_data->title:'';
			$new_data =(array) $new_data;
			$new_data = json_encode($new_data);

			$submodule_id = $menuItem->id;

			$menu_list = Menu::where(['id'=>$module_id])->first();
			$module_desc = !empty($menu_list->title)?$menu_list->title:'';

			$params['user_id'] = $user_id;
			$params['user_name'] = $user_name;
			$params['module'] = 'Menu';
			$params['module_desc'] = $module_desc;
			$params['module_id'] = $module_id;
			$params['sub_module'] = 'Menu Item';
			$params['sub_module_id'] = $submodule_id;
			$params['sub_module_desc'] = $sub_module_desc;
			$params['sub_sub_module'] = "";
			$params['sub_sub_module_id'] = 0;
			$params['data_after_action'] = $new_data;
			$params['action'] = "Update";

			CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================
		$message = '<div class="alert alert-success">Menu Items has been updated. </div>';
		$response['success'] = true;
	}
	else{
		$message = '<div class="alert alert-danger">something went wrong, please try again. </div>';
	}

	session()->flash('flash_msg', $message);

	$response['msg'] = $message;


	return response()->json($response);
}


	// ajax_delete_item
public function ajaxDeleteItem(Request $request){
	//prd($request->toArray());

	$response = [];
	$user_id = auth()->user()->id;
	$user_name = auth()->user()->name;
	$response['success'] = false;


	$isDeleted = '';
	$message = '';

	$item_id = (isset($request->id))?$request->id:0;
	if(is_numeric($item_id) && $item_id > 0){
		$menuItem = MenuItem::find($item_id);

		$new_data = DB::table('menu_items')->where(['id'=>$item_id])->first();
		$module_id =  !empty($new_data->menu_id)?$new_data->menu_id:'';
		$sub_module_desc =  !empty($new_data->title)?$new_data->title:'';
		$new_data =(array) $new_data;
		$new_data = json_encode($new_data);

		$menu_list = Menu::where(['id'=>$module_id])->first();
		$module_desc = !empty($menu_list->title)?$menu_list->title:'';

		if(isset($menuItem->id) && $menuItem->id == $item_id){
			$isDeleted = $menuItem->delete();
			if($isDeleted){
				MenuItem::where('parent_id', $item_id)->delete();
			}
		}
	}

	if($isDeleted){

		//=============Activity Logs=====================

			$params['user_id'] = $user_id;
			$params['user_name'] = $user_name;
			$params['module'] = 'Menu';
			$params['module_desc'] = $module_desc;
			$params['module_id'] = $module_id;
			$params['sub_module'] = 'Menu Item';
			$params['sub_module_id'] = $item_id;
			$params['sub_module_desc'] = $sub_module_desc;
			$params['sub_sub_module'] = "";
			$params['sub_sub_module_id'] = 0;
			$params['data_after_action'] = $new_data;
			$params['action'] = "Delete";

			CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================
		$message = '<div class="alert alert-success">Menu Item(s) has been deleted. </div>';
		$response['success'] = true;
	}
	else{
		$message = '<div class="alert alert-danger">something went wrong, please try again. </div>';
	}

	session()->flash('flash_msg', $message);

	//$response['msg'] = $message;


	return response()->json($response);
}
/* end of controller */
}  