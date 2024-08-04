<?php
namespace App\Http\Controllers\Admin;

use App\Models\CmsPages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Storage;
use Validator;
use DB;

class CmsController  extends Controller {

    private $limit;
    protected $select_cols;
    protected $ADMIN_ROUTE_NAME;

    public function __construct() {
        $this->limit = 20;
        $this->select_cols = ['id','cms_type','title','slug','heading','brief','content','old_content','default_content','meta_title','meta_keyword','meta_description','status','sort_order','created_at','updated_at'];
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request) {
        $data = [];
        $limit = $this->limit;
        $data['page_title'] = 'CMS Page';
        $data['title'] = 'CMS Page';

        $select_cols = $this->select_cols;
        $pageQuery = CmsPages::select($select_cols)->where('is_deleted',0)->orderBy('sort_order','ASC');
        if ($request->title) {
            $title = $request->title??'';
            $pageQuery->where("title", "like", "%".$title."%");
        }

        $status = ($request->has('status'))?$request->status:1;
        if($status != '') {
            $pageQuery->where("status", $status);
        }

        if($request->parent_id) {
            $parent_id = $request->parent_id??0;
            $pageQuery->where('parent_id', $parent_id);
        } else {
            $pageQuery->where(function($query){
                $query->where('parent_id', 0);
                $query->orWhereNull('parent_id');
            });
        }

        $cms_type = ($request->has('cms_type'))?$request->cms_type:'page';
        if($cms_type != '') {
            $pageQuery->where("cms_type", $cms_type);
        }
        $pages = $pageQuery->paginate($limit);
        $data['pages'] = $pages;
        return view('admin.cms.index',$data);
    }


    public function add(Request $request) {
        $id = $request->id??0;
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $parent_id = $request->parent_id??0;
        $page = [];
        $title = 'Add Page';
        if(is_numeric($id) && $id > 0) {
            $page = CmsPages::find($id);
            if($page) {
                $parent_id = $page->parent_id;
                $title = 'Edit Page ('.$page->title.")";
            } else {
                abort(404);
            }
        }
        if(is_numeric($parent_id) && $parent_id > 0) {
            $parentData = CmsPages::find($parent_id);
            if($parentData) {
                $parent_id = $parentData->id;
                $cmsTitle = isset($parentData->title)?$parentData->title:'';
                $title = 'Add Page ('.$cmsTitle." )";
            } else {
                abort(404);
            }
        }

        if($request->method() == 'POST') {
            $ext = 'jpg,jpeg,png,gif';
            $ext2 = 'jpg,jpeg,png,gif,pdf';

            $rules = [];
            $rules['title'] = 'required|max:255';
            if (!empty($page) && $page->id == $id) {
                $rules['slug'] = 'required|max:255';
            }
            $rules['image'] = 'nullable|image|mimes:'.$ext;
            $rules['banner_image'] = 'nullable|image|mimes:'.$ext;
            $rules['document'] = 'nullable|mimes:'.$ext2;
            $this->validate($request, $rules);
            $req_data = [
                'title' => $request->title,
                'heading' => $request->heading,
                'template' => $request->template,
                'brief' => $request->brief,
                'content' => $request->content,
                'sort_order' => $request->sort_order,
                'status' => $request->status??0
            ];
            if($request->cms_type) {
                $req_data['cms_type'] = $request->cms_type??'page';
            }
            //prd($req_data);
            if (!empty($page) && $page->id == $id) {
                $req_data["slug"] = CustomHelper::GetSlug('cms_pages', 'id', $id, $request->slug);
                $isSaved = CmsPages::where('id', $page->id)->update($req_data);
                $cms_id = $page->id;
                $msg = "Page has been updated successfully.";
            } else {
                $req_data["parent_id"] = $parent_id;
                $req_data["slug"] = CustomHelper::GetSlug('cms_pages', 'id', $id, $request->title);
                $isSaved = CmsPages::create($req_data);
                $cms_id = $isSaved->id;
                $msg = "Page has been added successfully.";
            }

            if ($isSaved) {
                if($request->hasFile('image')) {
                    $file = $request->file('image');
                    $image_result = $this->saveImage($file,$cms_id,'image');
                    if($image_result['success'] == false){     
                        session()->flash('alert-danger', 'Image could not be added');
                    }
                }

                if($request->hasFile('banner_image')) {
                    $file = $request->file('banner_image');
                    $image_result = $this->saveImage($file,$cms_id,'banner_image');
                    if($image_result['success'] == false){     
                        session()->flash('alert-danger', 'Image could not be added');
                    }
                }

                if($request->hasFile('document')) {
                    $file = $request->file('document');
                    $image_result = $this->saveFile($file,$cms_id,'document');
                    if($image_result['success'] == false){     
                        session()->flash('alert-danger', 'document could not be added');
                    }
                }

                $new_data = DB::table('cms_pages')->where('id',$cms_id)->first();
                $title =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $cms_id;
                $module_desc = $title;

                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'CMS Pages';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                return redirect(url($this->ADMIN_ROUTE_NAME.'/cms?parent_id='.$parent_id))->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', 'The Cms could be added, please try again or contact the administrator.');
            }
        }

        $data = [];
        $data['page_heading'] = $title;
        $data['page'] = $page;
        $data['id'] = $id;
        $data['parent_id'] = $parent_id;
        return view('admin.cms.form', $data);
    }

    public function view(Request $request) {
        $id = (isset($request->id))?$request->id:0;
        $page = '';
        $title = 'Cms Page Details';
        if(is_numeric($id) && $id > 0) {
            $page = CmsPages::where('id', $id)->first();
            $title = 'Cms Page Details ('.$page->title.')';
        }
        $data = [];
        $data['page_heading'] = $title;
        $data['page'] = $page;
        $data['id'] = $id;
        return view('admin.cms.view', $data);
    }


    public function saveImage($file, $id,$type) {
        $result['org_name'] = '';
        $result['file_name'] = '';

        if ($file) 
        {
            $path = 'cms_pages/';
            $thumb_path = 'cms_pages/thumb/';

            $IMG_HEIGHT = CustomHelper::WebsiteSettings('CMS_IMG_HEIGHT');
            $IMG_WIDTH = CustomHelper::WebsiteSettings('CMS_IMG_WIDTH');
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings('CMS_THUMB_HEIGHT');
            $THUMB_WIDTH = CustomHelper::WebsiteSettings('CMS_THUMB_WIDTH');

            $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:768;
            $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:768;
            $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:336;
            $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:336;

            $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

            if($uploaded_data['success']){
                $new_image = $uploaded_data['file_name'];

                if(is_numeric($id) && $id > 0){
                    $page = CmsPages::find($id);

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

    public function saveFile($file, $id,$type) {
        $result['org_name'] = '';
        $result['file_name'] = '';

        if ($file) 
        {
            $path = 'cms_pages/';
            $thumb_path = 'cms_pages/thumb/';
            $ext='jpg,jpeg,png,gif,doc,docx,txt,pdf';

            $uploaded_data = CustomHelper::UploadFile($file, $path, $ext);


            if($uploaded_data['success']){
                $new_image = $uploaded_data['file_name'];

                if(is_numeric($id) && $id > 0){
                    $page = CmsPages::find($id);

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

    public function ajax_delete_image(Request $request) {
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

    public function delete(Request $request) {
        $parent_id = 0;
        $id = !empty($request->id)?$request->id:'';
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;
        $storage = Storage::disk('public');
        $path = 'cms_pages/';

        if($method=="POST") {
            if(is_numeric($id) && $id > 0) {
                $page = CmsPages::find($id);
                if(!empty($page)){
                    if($page->children->count() > 0) {
                        return back()->with('alert-danger', 'The page cannot be deleted, it may have child pages. Please delete all child pages before deleting the page.');
                    }
                    $new_data = DB::table('cms_pages')->where('id',$id)->first();
                    $module_desc =  !empty($new_data->title)?$new_data->title:'';
                    $new_data = (array) $new_data;
                    $new_data = json_encode($new_data);

                    $parent_id = $page->parent_id??0;
                    $image = $page->image;
                    if(!empty($image)) {
                        if($storage->exists($path.'thumb/'.$image)) {
                            $storage->delete($path.'thumb/'.$image);
                        }
                        if($storage->exists($path.$image)) {
                            $storage->delete($path.$image);
                        }
                    }

                    $banner_image = $page->banner_image;
                    if($banner_image) {
                        if($storage->exists($path.'thumb/'.$banner_image)) {
                            $storage->delete($path.'thumb/'.$banner_image);
                        }
                        if($storage->exists($path.$banner_image)) {
                            $storage->delete($path.$banner_image);
                        }
                    }

                    $document = $page->document;
                    if($document) {
                        if($storage->exists($path.'thumb/'.$document)) {
                            $storage->delete($path.'thumb/'.$document);
                        }
                        if($storage->exists($path.$document)) {
                            $storage->delete($path.$document);
                        }
                    }

                    $is_deleted = $page->delete();
                    if($is_deleted) {
                        $params = [];
                        $params['user_id'] = $user_id;
                        $params['user_name'] = $user_name;
                        $params['module'] = 'CMS Pages';
                        $params['module_desc'] = $module_desc;
                        $params['module_id'] = $id;
                        $params['sub_module'] = "";
                        $params['sub_module_id'] = 0;
                        $params['sub_sub_module'] = "";
                        $params['sub_sub_module_id'] = 0;
                        $params['data_after_action'] = $new_data??'';
                        $params['action'] = 'Delete';
                        CustomHelper::RecordActivityLog($params);
                    }
                }
            }
        }   
        if($is_deleted) {
            return redirect(url($this->ADMIN_ROUTE_NAME.'/cms?parent_id='.$parent_id))->with('alert-success', 'The Page has been deleted successfully.');
        } else {
            return redirect(url($this->ADMIN_ROUTE_NAME.'/cms'))->with('alert-danger', 'The Page cannot be deleted, please try again or contact the administrator.');
        }
    }


    public function delete_images($id,$type) {
        $is_deleted = '';
        $is_updated = '';
        $storage = Storage::disk('public');
        $path = 'cms_pages/';
        $page = CmsPages::find($id);

        $image = (isset($page->image))?$page->image:'';
        $banner_image = (isset($page->banner_image))?$page->banner_image:'';
        $document = (isset($page->document))?$page->document:'';

        if($type =='image' && !empty($image)) {
            if($storage->exists($path.'thumb/'.$image)) {
                $is_deleted = $storage->delete($path.'thumb/'.$image);
            }
            if($storage->exists($path.$image)) {
                $is_deleted = $storage->delete($path.$image);
            }
        } elseif($type =='banner_image' && !empty($banner_image)) {
            if($storage->exists($path.'thumb/'.$banner_image)) {
                $is_deleted = $storage->delete($path.'thumb/'.$banner_image);
            }
            if($storage->exists($path.$banner_image)) {
                $is_deleted = $storage->delete($path.$banner_image);
            }
        } elseif($type =='document' && !empty($document)) {
            if($storage->exists($path.'thumb/'.$document)) {
                $is_deleted = $storage->delete($path.'thumb/'.$document);
            }
            if($storage->exists($path.$document)) {
                $is_deleted = $storage->delete($path.$document);
            }
        }

        if($is_deleted) {
            if($type =='image') {
                $page->image = '';
            } elseif($type =='banner_image') {
                $page->banner_image = '';
            } elseif($type =='document') {
                $page->document = '';
            }
            $is_updated = $page->save();
        }
        return $is_updated;
    }

    public function seoView(Request $request) {
        $id = isset($request->id) ? $request->id : 0;
        $cms = CmsPages::find($id);
        $cmsTitle = isset($cms->title) ? $cms->title:'';
        $title = "SEO Meta Details (".$cmsTitle.")";

        $data = [];
        $data["page_heading"] = $title;
        $data["cms"] = $cms;
        $data["id"] = $id;
        return view("admin.cms.seo_view", $data);
    }

    public function seoMeta(Request $request) {
        $cms = [];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = isset($request->id) ? $request->id : 0;
        $cms = CmsPages::find($id);
        $cmsSeo = [];
        $cmsTitle = isset($cms->title) ? $cms->title:'';
        $name = "SEO Meta (".$cmsTitle.")";

        if ($request->method() == "POST") {
            $cmsSeo = CmsPages::find($id);
            $req_data = [];
            $req_data = $request->except(["_token", 'id', "page", "back_url",'created_at','updated_at']);
            if (!empty($cmsSeo) && $cmsSeo->count() > 0) {
                $isSaved = CmsPages::where("id",$cmsSeo->id)->update($req_data);
                $cms_seo_id = $cmsSeo->id;
                $msg = "Cms Seo Meta has been updated successfully.";
            } else {
                $isSaved = CmsPages::create($req_data);
                $cms_seo_id = $isSaved->id;
                $msg = "Cms Seo Meta has been added successfully.";
            }

            if ($isSaved) {
                $new_data = DB::table('cms_pages')->where(['id'=>$id])->first();
                $module_id = !empty($new_data->id)?$new_data->id:'';
                $sub_module_desc = !empty($new_data->title)?$new_data->title:'';
                $submodule_id = $cms_seo_id;
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $cms_list = CmsPages::where(['id'=>$module_id])->first();
                $module_desc = !empty($cms_list->title)?$cms_list->title:'';

                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Cms Pages';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "Cms Pages Seo";
                $params['sub_module_desc'] = $sub_module_desc;
                $params['sub_module_id'] = $submodule_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($cmsSeo->id)) ? "Update" : "Add";
                CustomHelper::RecordActivityLog($params);

                return redirect(route($this->ADMIN_ROUTE_NAME.'.cms.seo_view',['id'=>$id]))->with('alert-success', $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Blog Seo Meta could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["cms"] = $cms;
        $data["id"] = $id;
        $data["page_heading"] = $name;
        $data["cmsSeo"] = $cmsSeo;
        return view("admin.cms.seo_meta", $data);
    }


// End of controller
}
?>