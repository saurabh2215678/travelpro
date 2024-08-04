<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Storage;
use App\Helpers\CustomHelper;
use DB;
use Image;

class BlogCategoryController extends Controller{

    private $limit;
    private $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct(){
        $this->limit = 20;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request){

        $data = [];
        // prd($request->toArray())

        $limit = $this->limit;
        $id = (isset($request->id))?$request->id:0;
        $type = (isset($request->type))?$request->type:'';
        $name = isset($request->name) ? $request->name : "";
        $status = isset($request->status) ? $request->status : 1;
        // prd($type);

        if($type == 'blogs' || $type == 'news'){
        $category_query = BlogCategory::withCount('Blogs')->orderBy('sort_order', 'asc')->orderBy('id', 'desc');
        if (!empty($name)) {
            $category_query->where("name", "like", "%" . $name . "%");
        }
        $category_query->where("status", $status);

            if($type !='')
            {
                $category_query->where('type', $type);
            }
            $categories = $category_query->paginate($limit);
            $data['categories'] = $categories;
            $data['limit'] = $limit;
            $data['type'] = $type;
            // prd('Hi');
        }
        else
        {
            //prd('Hello');
            return redirect(url($this->ADMIN_ROUTE_NAME));
        }
        return view('admin.blogs_categories.index', $data);

    }


    public function add(Request $request){
        //prd($request->toArray());
        $data = [];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = (isset($request->id))?$request->id:0;
        $type = (isset($request->type))?$request->type:'';
        $page_heading = '';
        //prd($type);
        if($type == 'blogs' || $type == 'news'){

        $category = '';

        if(is_numeric($id) && $id > 0){
            $category = BlogCategory::find($id);
        }

        if($request->method() == 'POST' || $request->method() == 'post'){
            //prd($request->toArray());

            $back_url = (isset($request->back_url))?$request->back_url:'';

            if(empty($back_url)){
                $back_url = $this->ADMIN_ROUTE_NAME.'/blogs_categories';
            }

            $req_id = (isset($request->id))?$request->id:0;

            $rules = [];
            $rules['name'] = 'required|max:255';
            $rules['status'] = 'required';
            if(!empty($id)) {
                $rules["slug"] = 'required';
            }

            $this->validate($request, $rules);
            //prd($request->toArray());
            $req_data = [];

            $req_data = $request->except(['_token', 'id','back_url']);
            
            // $slug = CustomHelper::GetSlug('blog_categories', 'id', $id, $request->name);
            // $req_data['slug'] = $slug;
            $req_data['content'] = (!empty($request->content))?$request->content:'';
            $req_data['meta_title'] = (!empty($request->meta_title))?$request->meta_title:'';
            $req_data['meta_keyword'] = (!empty($request->meta_keyword))?$request->meta_keyword:'';
            $req_data['meta_description'] = (!empty($request->meta_description))?$request->meta_description:'';
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            $req_data['hot_categories'] = $request->hot_categories??0;
        
            if(!empty($category) && $req_id == $id){
                $req_data["slug"] = CustomHelper::GetSlug('blog_categories', 'id', $id, $request->slug);
                $isSaved = BlogCategory::where(['id'=>$category->id, 'type'=>$type])->update($req_data);
                $blog_categories_id = $category->id;
                $msg = ucwords($type)." Category has been updated successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'blog_categories';
                $row_id = $id;
                $action_type = 'Edit On Blog Category List';
                $action_description = 'Edit On ('.$request->name.")";
                $description = 'Update('.$request->name.") ".$description;*/
                //prd($isSaved);
            }
            else{
                $req_data["slug"] = CustomHelper::GetSlug('blog_categories', 'id', $id, $request->name);
                $isSaved = BlogCategory::create($req_data);
                $blog_categories_id = $isSaved->id;
                $msg = ucwords($type)." Category has been added successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'blog_categories';
                $row_id = $id;
                $action_type = 'Add On Blog Category List';
                $action_description = 'Add On ('.$request->title.")";
                $description = 'Add('.$request->title.") ".$description;*/

            }
            
            if ($isSaved) {

                $new_data = DB::table('blog_categories')->where('id',$blog_categories_id)->first();
                $module_desc =  !empty($new_data->name)?$new_data->name:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $blog_categories_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] =  ucwords($type).' '.'Category';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($req_id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                /*CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);
*/
                return redirect(url($back_url))->with('alert-success',$msg);
            } else {
                return back()->with('alert-danger', 'Blog Category cannot be added, please try again or contact the administrator.');
            }
        }
        
        if($type == 'blogs'){
            $page_heading = 'Add Blog Category';
            if(isset($category->name)){
                $page_heading = 'Edit Blog Category ('.$category->name.')';
            }
        }
        elseif($type == 'news'){
            $page_heading = 'Add News Category';
            if(isset($category->name)){
                $page_heading = 'Edit News Category ('.$category->name.')';
            }
        }
        $data['page_heading'] = $page_heading;
        $data['id'] = $id;
        $data['category'] = $category;
        $data['type'] = $type;
        }
        else{
            return redirect(url($this->ADMIN_ROUTE_NAME));
        }
        return view('admin.blogs_categories.form', $data);

    }

    public function categories_view(Request $request){

    $type = (isset($request->type))?$request->type:'';
    $id = (isset($request->id))?$request->id:0;
    $category = '';
    $title = 'Blog Category List';

    if(is_numeric($id) && $id > 0){
        $category = BlogCategory::where('id', $id)->first();
        //prd($category);
        $title = 'Blog Category Details ('.$category->name.')';
    }
    
    $data = [];
    $data['page_heading'] = $title;
    $data['category'] = $category;
    $data['id'] = $id;
    $data['type'] = $type;
    return view('admin.blogs_categories.view', $data);
    }

    public function delete(Request $request){

        $user_id = auth()->user()->id;
        $id= isset($request->id)?$request->id:'';
        $user_name = auth()->user()->name;
        $type = (isset($request->type))?$request->type:'';

        $category = BlogCategory::find($id);
        $new_data = DB::table('blog_categories')->where('id',$id)->first();
        $module_desc =  !empty($new_data->name)?$new_data->name:'';
        $new_data =(array) $new_data;
        $new_data = json_encode($new_data);
       /* $function_name = $this->currentUrl;
        $action_table = 'blog_categories';
        $row_id = $id;
        $action_type = 'Delete Blog Category';
        $blogCategoryName = isset($category->name) ? $category->name:"";
        $action_description = 'Delete ('.$blogCategoryName.")";
        $description = 'Delete ('.$blogCategoryName.")";*/
        $is_delete = BlogCategory::blogCategoryDelete($id);
        if ($is_delete['status'] != 'ok') {

            return redirect(url('admin/blogs_categories?type='.$type))->with('alert-danger', $is_delete['message']);
        } 
        else {
            $delete_blog_category_name = $is_delete['name'];
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

            if ($category->blogs() && $category->blogs()->count() > 0) {
                return back()->with('alert-danger', 'This category cannot be removed because there are currently ' . $category->blogs()->count() . ' blogs associated with it. Please remove the blogs first.');
            }
        // The Category must not have any associated Sub-categories to be deleted
            if ($category->children() && $category->children()->count() > 0) {
                return back()->with('alert-danger', 'This category cannot be removed because there are currently ' . $category->children()->count() . ' blog sub-categories associated with it. Please remove the blog sub-categories first.');
            }
            else {      
            //$category->delete();

                /*CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);*/
                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] =  ucwords($type).' '.'Category';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = 'Delete';

                CustomHelper::RecordActivityLog($params);

                return back()->with('alert-success',ucwords($type).' has been removed successfully.');
            }
        }

        /* end of controller */
    }