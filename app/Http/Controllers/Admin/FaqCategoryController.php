<?php
namespace App\Http\Controllers\Admin;

use App\Models\FaqCategory;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Storage;
use Validator;
use DB;

class FaqCategoryController  extends Controller {

    //protected $foo;

    private $limit;
    protected $select_cols;
    protected $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct(){

        $this->limit = 20;
        $this->select_cols = ['id','name','title','slug','heading','brief','content','old_content','default_content','meta_title','meta_keyword','meta_description','status','created_at','updated_at'];

        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $this->currentUrl = url()->current();
    }

    public function index(Request $request) {

        $parent_id = (isset($request->parent_id))?$request->parent_id:0;
        $title = isset($request->title) ? $request->title: "";
        $status = isset($request->status) ? $request->status : 1;

        $data=array();
        $limit = $this->limit;
        $data['page_title'] = 'Faq categories Page';
        $data['title'] = 'Faq categories Page';

        $select_cols = $this->select_cols;

        $pageQuery = FaqCategory::select($select_cols);
        if (!empty($title)) {
            $pageQuery->where("title", "like", "%" . $title . "%");
        }
        if (strlen($status) > 0) {
            $pageQuery->where("status", $status);
        }


        if(is_numeric($parent_id) && $parent_id > 0){
            $pageQuery->where('parent_id', $parent_id);
        }
        else{
            $pageQuery->where(function($query){
                $query->where('parent_id', 0);
                $query->orWhereNull('parent_id');
            });
        }
        $pages = $pageQuery->orderBy("id", "desc")->paginate($limit);

        $data['pages']= $pages;

        return view('admin.faq_categories.index',$data);
    }


    public function add(Request $request){
        $id = (isset($request->id))?$request->id:0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $parent_id = (isset($request->parent_id))?$request->parent_id:0;
        $page = [];
        $title = 'Add Faq Category';

        if(is_numeric($id) && $id > 0){
            $page = FaqCategory::find($id);
            $title = 'Edit Faq Category('.$page->title." )";
        }
        if(is_numeric($parent_id) && $parent_id > 0){
            $pageData = FaqCategory::find($parent_id);
            $title = 'Add Faq Category('.$pageData->title." )";
        }


        if($request->method() == 'POST' || $request->method() == 'post'){

        //prd($request->toArray());
            $ext = 'jpg,jpeg,png,gif';
            $ext2 = 'jpg,jpeg,png,gif,pdf';

            //$rules['name'] = 'required';
            $rules['title'] = 'required|max:255';
            //$rules['status'] = 'required';
            $rules['image'] = 'nullable|image|mimes:'.$ext;
            $rules['banner_image'] = 'nullable|image|mimes:'.$ext;
            $rules['document'] = 'nullable|mimes:'.$ext2;

            /*if(empty($page->id)){
                $rules['slug'] = 'required';
            }*/
            $this->validate($request, $rules);
            $req_data = [];
            $req_data = $request->except(['_token', 'image','related_destination', 'document', 'back_url', 'old_image','banner_image','banner_old_image','id','featured','slug']);

            $req_data['related_destinations'] = (isset($request->related_destination) && !empty($request->related_destination)) ? json_encode($request->related_destination) : '[]';
            
            if(empty($page->id)){
                $slug = CustomHelper::GetSlug('cms_pages', 'id', $id, $request->title);
            }
            else{
                $slug = CustomHelper::GetSlug('cms_pages', 'id', $id, $request->slug);
            }

            $req_data['slug'] = $slug ? $slug:'';

            $req_data['name'] = (!empty($request->name))?$request->name:'';
            $req_data['heading'] = (!empty($request->heading))?$request->heading:'';
            $req_data['brief'] = (!empty($request->brief))?$request->brief:'';
            $req_data['template'] = (!empty($request->template))?$request->template:'';
            $req_data['content'] = (!empty($request->content))?$request->content:'';
            // $req_data['status'] = (!empty($page->status))?$page->status:0;
            $req_data['meta_title'] = (!empty($request->meta_title))?$request->meta_title:'';
            $req_data['meta_keyword'] = (!empty($request->meta_keyword))?$request->meta_keyword:'';
            $req_data['meta_description'] = (!empty($request->meta_description))?$request->meta_description:'';
            
            $req_data['featured'] = (isset($request->featured)) ? 1:0;
            $req_data['doc_link'] = (isset($request->doc_link)) ? $request->doc_link:'';

            //prd($req_data);
            if(!empty($page) && count(array($page)) > 0){
                $req_data['slug'] = $page->slug ? $page->slug: "";
                $isSaved = FaqCategory::where('id', $page->id)->update($req_data);
                $faq_categories_id = $page->id;
                $msg="Faq Category has been updated successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'faq_categories';
                $row_id = $id;
                $action_type = 'Edit On Faq Category';
                $action_description = 'Edit On ('.$request->title.")";
                $description = 'Update('.$request->title.") ".$description;*/
            }
            else{
                $isSaved = FaqCategory::create($req_data);
                $faq_categories_id = $isSaved->id;
                $msg="Faq Category has been added successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'faq_categories';
                $row_id = $id;
                $action_type = 'Add On Faq Category';
                $action_description = 'Add On ('.$request->title.")";
                $description = 'Add('.$request->title.") ".$description;*/
            }

            if ($isSaved) {

              if($request->hasFile('image')) {
                $file = $request->file('image');
                $image_result = $this->saveImage($file,$faq_categories_id,'image');
                if($image_result['success'] == false){     
                    session()->flash('alert-danger', 'Image could not be added');
                }
            }

            if($request->hasFile('banner_image')) {
                $file = $request->file('banner_image');
                $image_result = $this->saveImage($file,$faq_categories_id,'banner_image');
                if($image_result['success'] == false){     
                    session()->flash('alert-danger', 'Image could not be added');
                }
            }

            if($request->hasFile('document')) {
                $file = $request->file('document');
                $image_result = $this->saveFile($file,$faq_categories_id,'document');
                if($image_result['success'] == false){     
                    session()->flash('alert-danger', 'document could not be added');
                }
            }

             //=============Activity Logs=====================
            $new_data = DB::table('faq_categories')->where('id',$faq_categories_id)->first();
            $module_desc =  !empty($new_data->title)?$new_data->title:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            $module_id= $faq_categories_id;

            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Faq Category';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data;
            $params['action'] = (!empty($page->id)) ? "Update" : "Add";

            CustomHelper::RecordActivityLog($params);

                 //=============Activity Logs=====================



            cache()->forget('cms');

            /*CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);*/

            return redirect(url($this->ADMIN_ROUTE_NAME.'/faq_categories?parent_id='.$parent_id))->with('alert-success', $msg);
        } else {
            return back()->with('alert-danger', 'The Faq Category could be added, please try again or contact the administrator.');
        }
    }

    $data = [];
    $data['page_heading'] = $title;
    $data['page'] = $page;
    $data['id'] = $id;
    $data['parent_id'] = $parent_id;
    $data["destinations"] = Destination::where('is_city',0)->where('status',1)->get();
    

    return view('admin.faq_categories.form', $data);
}

//FAQ CATEGORIES IS NOT IN USE
// public function view(Request $request){

//     $id = (isset($request->id))?$request->id:0;
//     $page = '';
//     $title = 'Faq Category';

//     if(is_numeric($id) && $id > 0){

//         $page = CourseCategory::where('id', $id)->first();
//         //prd($page);
//         $title = 'Faq Category';

//     }
    
//     $data = [];
//     $data['page_heading'] = $title;
//     $data['page'] = $page;
//     $data['id'] = $id;
//     return view('admin.faq_categories.view', $data);
// }


public function saveImage($file, $id,$type){
        //prd($file); 
        //echo $type; die;

    $result['org_name'] = '';
    $result['file_name'] = '';

    if ($file) 
    {
        $path = 'faq_categories/';
        $thumb_path = 'faq_categories/thumb/';

        $IMG_HEIGHT = CustomHelper::WebsiteSettings('FAQ_CATEGORY_IMG_HEIGHT');
        $IMG_WIDTH = CustomHelper::WebsiteSettings('FAQ_CATEGORY_IMG_WIDTH');
        $THUMB_HEIGHT = CustomHelper::WebsiteSettings('FAQ_CATEGORY_THUMB_HEIGHT');
        $THUMB_WIDTH = CustomHelper::WebsiteSettings('FAQ_CATEGORY_THUMB_WIDTH');

        $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:768;
        $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:768;
        $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:336;
        $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:336;

        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

        if($uploaded_data['success']){
            $new_image = $uploaded_data['file_name'];

            if(is_numeric($id) && $id > 0){
                $page = FaqCategory::find($id);

                if(!empty($page) && count(array($page)) > 0){

                    $storage = Storage::disk('public');

                    if($type == 'image'){
                        $old_image = $page->image;
                        $page->image = $new_image;
                    }
                    elseif($type == 'banner_image'){
                        $old_image = $page->banner_image;
                        $page->banner_image = $new_image;
                    }


                    $isUpdated = $page->save();

                    if($isUpdated){

                        if(!empty($old_image) && $storage->exists($path.$old_image)){
                            $storage->delete($path.$old_image);
                        }

                        if(!empty($old_image) && $storage->exists($thumb_path.$old_image)){
                            $storage->delete($thumb_path.$old_image);
                        }
                    }
                }
            }
        }

        if(!empty($uploaded_data))
        {   
            return $uploaded_data;
        }
    }
}

public function saveFile($file, $id,$type){
        // prd($file); 
        // echo $type; die;

    $result['org_name'] = '';
    $result['file_name'] = '';

    if ($file) 
    {
        $path = 'faq_categories/';
        $thumb_path = 'faq_categories/thumb/';
        $ext='jpg,jpeg,png,gif,doc,docx,txt,pdf';

        $uploaded_data = CustomHelper::UploadFile($file, $path, $ext);


        if($uploaded_data['success']){
            $new_image = $uploaded_data['file_name'];

            if(is_numeric($id) && $id > 0){
                $page = FaqCategory::find($id);

                if(!empty($page) && count(array($page)) > 0){

                    $storage = Storage::disk('public');
                    
                    if($type == 'document'){
                        $old_image = $page->document;
                        $page->document = $new_image;
                    }

                    $isUpdated = $page->save();

                    if($isUpdated){

                        if(!empty($old_image) && $storage->exists($path.$old_image)){
                            $storage->delete($path.$old_image);
                        }

                        if(!empty($old_image) && $storage->exists($thumb_path.$old_image)){
                            $storage->delete($thumb_path.$old_image);
                        }
                    }
                }
            }
        }

        if(!empty($uploaded_data))
        {   
            return $uploaded_data;
        }
    }
}

public function ajax_delete_image(Request $request){
        //prd($request->toArray());
    $result['success'] = false;

    $image_id = ($request->has('image_id'))?$request->image_id:0;
    $type = ($request->has('type'))?$request->type:'';

    if (is_numeric($image_id) && $image_id > 0) {
        $is_img_deleted = $this->delete_images($image_id,$type);
        if($is_img_deleted)
        {
            $result['success'] = true;
            $result['msg'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Image has been delete successfully.</div>';
        }
    }

    if($result['success'] == false){
        $result['msg'] = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
    }
    return response()->json($result);
}

public function delete(Request $request)
{
    $parent_id = 0;
    $id = isset($request->id)?$request->id:'';
    $user_id = auth()->user()->id;
    $user_name = auth()->user()->name;
    $method = $request->method();

        //prd($id);
    $is_deleted = 0;
    $storage = Storage::disk('public');
    $path = 'faq_categories/';

    if($method=="POST"){
        if(is_numeric($id) && $id > 0)
        {
            $page = FaqCategory::find($id);
            $last_data = DB::table('faq_categories')->where('id',$id)->first();
            $module_desc =  !empty($last_data->title)?$last_data->title:'';
            $last_data =(array) $last_data;
            $last_data = json_encode($last_data);

           /* $function_name = $this->currentUrl;
            $action_table = 'faq_categories';
            $row_id = $id;
            $action_type = 'Delete Faq Category';
            $action_description = 'Delete ('.$page->title.")";
            $description = 'Delete ('.$page->title.")";*/
            if(!empty($page) && count(array($page)) > 0){
                $parent_id = $page->parent_id??0;
                if(!empty($page->image))
                {   
                   $image = $page->image;
                   $banner_image = $page->banner_image;
                   if(!empty($image) && $storage->exists($path.'thumb/'.$image))
                   {
                    $is_deleted = $storage->delete($path.'thumb/'.$image);
                }
                if(!empty($image) && $storage->exists($path.$image))
                {
                    $is_deleted = $storage->delete($path.$image);
                }

                if(!empty($banner_image) && $storage->exists($path.'thumb/'.$banner_image))
                {
                    $is_deleted = $storage->delete($path.'thumb/'.$banner_image);
                }
                if(!empty($banner_image) && $storage->exists($path.$banner_image))
                {
                    $is_deleted = $storage->delete($path.$banner_image);
                }
            }
            $is_deleted = $page->delete();

        }
    }
}   
if($is_deleted){

    /*CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);*/

    $params = [];
    $params['user_id'] = $user_id;
    $params['user_name'] = $user_name;
    $params['module'] = 'Faq Category';
    $params['module_desc'] = $module_desc;
    $params['module_id'] = $id;
    $params['sub_module'] = "";
    $params['sub_module_id'] = 0;
    $params['sub_sub_module'] = "";
    $params['sub_sub_module_id'] = 0;
    $params['data_after_action'] = $last_data??'';
    $params['action'] = "Delete";

    CustomHelper::RecordActivityLog($params);

    return redirect(url($this->ADMIN_ROUTE_NAME.'/faq_categories?parent_id='.$parent_id))->with('alert-success', 'The Page has been deleted successfully.');
}else
{
    return redirect(url($this->ADMIN_ROUTE_NAME.'/faq_categories'))->with('alert-danger', 'The Page cannot be deleted, please try again or contact the administrator.');
}

}


public function delete_images($id,$type)
{   
    $is_deleted = '';
    $is_updated = '';
    $storage = Storage::disk('public');
    $path = 'faq_categories/';
    $page = FaqCategory::find($id);

    $image = (isset($page->image))?$page->image:'';
    $banner_image = (isset($page->banner_image))?$page->banner_image:'';
    $document = (isset($page->document))?$page->document:'';

    if($type =='image'){
        if(!empty($image) && $storage->exists($path.'thumb/'.$image))
        {
            $is_deleted = $storage->delete($path.'thumb/'.$image);
        }
        if(!empty($image) && $storage->exists($path.$image))
        {
            $is_deleted = $storage->delete($path.$image);
        }
    }

    elseif($type =='banner_image'){
        if(!empty($banner_image) && $storage->exists($path.'thumb/'.$banner_image))
        {
            $is_deleted = $storage->delete($path.'thumb/'.$banner_image);
        }
        if(!empty($banner_image) && $storage->exists($path.$banner_image))
        {
            $is_deleted = $storage->delete($path.$banner_image);
        }
    }
    elseif($type =='document'){
        if(!empty($document) && $storage->exists($path.'thumb/'.$document))
        {
            $is_deleted = $storage->delete($path.'thumb/'.$document);
        }
        if(!empty($document) && $storage->exists($path.$document))
        {
            $is_deleted = $storage->delete($path.$document);
        }
    }

    if($is_deleted){
        if($type =='image'){
            $page->image = '';
        }
        elseif($type =='banner_image'){
            $page->banner_image = '';
        }
        $is_updated = $page->save();

    }
    return $is_updated;
}

/*    public function edit(Request $request){

        $data = [];

        $cms_id = (isset($request->cms_id))?$request->cms_id:0;

        if(is_numeric($cms_id) && $cms_id > 0){

            $data['page_heading']='Edit CMS Page';
            $data['title']='Edit CMS Page';
            $method = $request->method();

            if($method == 'POST' || $method == 'post'){

                //prd($request->toArray());

                $cms_page = CourseCategory::find($cms_id);

                $old_content = (isset($cms_page->content))?$cms_page->content:'';

                $post_data = $request->all();

                $rules = [];

                $rules['title'] = 'required';
                $rules['content'] = 'required';

                $this->validate($request, $rules);

                $update_data = $request->except(['_token', 'name', 'back_url', 'old_image','blog_id','featured']);

                $update_data['old_content'] = $old_content;

                //prd($update_data);

                $is_updated = CourseCategory::where('id', $cms_id)->update($update_data);

                if($is_updated){
                    session()->flash('alert-success', 'Template updated successfully!');
                }
                else{
                    session()->flash('alert-danger', 'Something went wrong, please try again!');
                }
                
                return redirect($this->ADMIN_ROUTE_NAME.'/cms');
            }

            $select_cols = $this->select_cols;

            $page = CourseCategory::where('id', $cms_id)->select($select_cols)->first();

            $data['page']= $page;

            return view('admin.cms.form',$data);

        }
        else{
            return redirect($this->ADMIN_ROUTE_NAME.'/cms');
        }
    }*/

// End of controller
}
?>