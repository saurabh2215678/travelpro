<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\BlogImage;
use App\Models\BlogCategory;
use App\Models\BlogToCategories;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Validator;
use Storage;
use Image;
use Auth;
use DB;
use Carbon\Carbon;

class BlogController extends Controller
{
    private $limit;
    private $typeArr;
    private $ADMIN_ROUTE_NAME;
    protected $currentUrl;

    public function __construct(){
        $this->limit = 40;
        $this->typeArr = config('custom.blog_type_arr');

        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        $type = (request()->has('type'))?request('type'):'';

        // $this->middleware('allowedmodule:'.$type);
        $this->currentUrl = url()->current();

       if(empty($type) || !in_array($type, $this->typeArr)){
            redirect(url($this->ADMIN_ROUTE_NAME));
        }
    }

    public function index(Request $request){
        
        $data = [];
        $limit = $this->limit;
        $type = (isset($request->type))?$request->type:'';
        $id = (isset($request->id))?$request->id:0;
        $category_id = (isset($request->category_id))?$request->category_id:'';
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;

       // if($type == 'blogs' || $type == 'news'){

        // DB::enableQueryLog();

        $blog_query = Blog::with('blogToCategory')->orderBy('blog_date','DESC')->orderBy('featured','DESC');

        $blog_query->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        if (!empty($title)) {
            $blog_query->where("title", "like", "%" . $title . "%");
        }

        if($request->category_id) {
            // $blog_query->where("category_id", $request->category_id);

                $blog_query->whereHas('BlogsCat', function ($query) use ($category_id) {
                    $query->whereRaw('FIND_IN_SET("'.$category_id.'", category_id)');
                });

        }

        $blog_query->where("status", $status);
          
        if($type !='')
        {
            $blog_query->where('type', $type);
        }
        $blogs = $blog_query->paginate($limit);

        // dd(DB::getQueryLog());

        $data['categories'] = BlogCategory::where('status',1)->where('type',$type)->orderBy('created_at','desc')->get();
        $data['blogs'] = $blogs;
        $data['type'] = $type;

        return view('admin.blogs.index', $data);
        //}
        //else{
           //return redirect(url($this->ADMIN_ROUTE_NAME)); 
        //}
    }

    public function add(Request $request){

        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $type = (isset($request->type))?$request->type:'';

        //if($type == 'blogs' || $type == 'news'){

        $id = (isset($request->id))?$request->id:0;
        $type = (isset($request->type))?$request->type:'';
        $popular = (isset($request->popular))?$request->popular:0;
        $is_home = (isset($request->is_home))?$request->is_home:0;

        $blog = '';
        $image = '';
        $title = '';

        if($type == 'blogs'){
            $title = 'Add Blog';
        }
        elseif($type == 'news'){
            $title = 'Add News';
        }

        $categories = BlogCategory::where('status',1)->where('type',$type)->orderBy('created_at','desc')->get();

        if(is_numeric($id) && $id > 0){
            $blog = Blog::find($id);
            //prd($blog->toArray());
            if($blog) {
                $title = 'Edit '.ucfirst($type).' ('.$blog->title." )";
            }
        }
        if($request->method() == 'POST' || $request->method() == 'post'){


            //$req_id = (isset($request->id))?$request->id:0;
            // prd($request->toArray());  
            $rules = [];       
            $validation_msg = [];       
            $ext = 'jpg,jpeg,png,gif';

            $rules['title'] = 'required|max:255';
            if($type == 'blogs'){
                $rules['post_by'] = 'required';
            }
            if(!empty($id)) {
                $rules["slug"] = 'required';
            }

            $rules['category_id'] = 'required';
            $rules['status'] = 'required';
            $rules['image'] = 'nullable|image|mimes:'.$ext;

            $validation_msg['title.required'] = 'The Title field is required.';
            if($type == 'blogs'){
            $validation_msg['post_by.required'] = 'The Author field is required.';
            }
            $validation_msg['category_id.required'] = 'Please Select Category field.';

            $this->validate($request, $rules,$validation_msg);
            // $req_data = $request->except(['_token', 'image', 'tags', 'back_url', 'old_image','blog_id','featured','popular','is_home']);
            $req_data = [];
            // $slug = CustomHelper::GetSlug('blogs', 'id', $id, $request->title);

            // $req_data['slug'] = $slug;
            $req_data['title'] = (!empty($request->title))?$request->title:'';
            $req_data['post_by'] = (!empty($request->post_by))?$request->post_by:'';
            $req_data['type'] = (!empty($request->type))?$request->type:'';
            $req_data['brief'] = (!empty($request->brief))?$request->brief:'';
            $req_data['content'] = (!empty($request->content))?$request->content:'';
            $req_data['sort_order'] = (!empty($request->sort_order))?$request->sort_order:0;
            // $req_data['meta_title'] = (!empty($request->meta_title))?$request->meta_title:'';
            // $req_data['meta_keyword'] = (!empty($request->meta_keyword))?$request->meta_keyword:'';
            // $req_data['meta_description'] = (!empty($request->meta_description))?$request->meta_description:'';
            $req_data['source_title'] = $request->source_title??'';
            $req_data['source_url'] = $request->source_url??'';
            $req_data['add_media'] = $request->add_media??'';
            $req_data['post_title_url'] = $request->post_title_url??'';
            $req_data['show_comments'] = $request->show_comments?1:0;
            $req_data['allow_comments'] = $request->allow_comments?1:0;
            $req_data['status'] = $request->status ? 1 : 0;
            
            // $blog_date = (isset($blog->blog_date))?$blog->blog_date:'';

            $req_data['featured'] = (isset($request->featured)) ? 1:0;
            $req_data['updated_at'] = Carbon::now()->toDateTimeString();
            $blog_date = (isset($request->blog_date))?$request->blog_date:'';
            $date = CustomHelper::DateFormat($blog_date, 'Y-m-d H:i:s', 'd/m/y');
            $req_data['blog_date'] = (!empty($date))?$date:Carbon::now()->toDateTimeString();
            $req_data['posted_by'] = Auth::user()->id;
            $req_data['popular']=$popular; 
            $req_data['is_home']=$is_home; 
            
            
            if(!empty($blog->id) && $blog->id == $id){
                $req_data["slug"] = CustomHelper::GetSlug('blogs', 'id', $id, $request->slug);
                if($popular==1 && $type=='news'){
                  $isSaved = Blog::where(['type'=>$type])->update(['popular'=>0]); 
                  
                }


                $blog_id = $blog->id;
                $isSaved = Blog::where(['id'=>$blog->id,'type'=>$type])->update($req_data);

                if(!empty($request->tags)){
                    DB::table('blog_tags')->where('blog_id',$blog_id)->delete();
                    $blog_tag_arr = explode(",",$request->tags);
                    foreach($blog_tag_arr as $name)
                    {
                        //Check tag master
                        $tags_data = DB::table('tags')->where('name',$name)->first();
                        if (!empty($tags_data)) 
                        {
                            $tags_id  =  $tags_data->id;
                        }
                        else
                        {
                            $tags_slug = CustomHelper::GetSlug('tags', 'id', 0, $name);
                            $tags_id = DB::table('tags')->insertGetId([
                                'name' =>  $name,
                                'slug' =>  $tags_slug,
                                'status' => 1,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);

                        }

                        $data_record = array(
                                'blog_id' => $blog_id,
                                'tag_id' => $tags_id,
                                'created_at' => Carbon::now()
                            );

                        DB::table('blog_tags')->insert($data_record);                  
                    }
                }

                $msg = ucwords($type)." has been updated successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'blogs';
                $row_id = $id;
                $action_type = 'Edit On Blog List';
                $action_description = 'Edit On ('.$request->title.")";
                $description = 'Update('.$request->title.") ".$description;*/
            }
            else{
                $req_data["slug"] = CustomHelper::GetSlug('blogs', 'id', $id, $request->title);

                if($popular==1 && $type=='news'){
                  $isSaved = Blog::where(['type'=>$type])->update(['popular'=>0]); 
                }


                $req_data['created_at'] = Carbon::now()->toDateTimeString();
                $isSaved = Blog::create($req_data);
                $blog_id = $isSaved->id;

             
                
                    if(!empty($request->tags))
                    {
                        DB::table('blog_tags')->where('blog_id',$blog_id)->delete();
                        $blog_tag_arr = explode(",",$request->tags);
                        foreach($blog_tag_arr as $name)
                        {
                            //Check tag master
                            $tags_data = DB::table('tags')->where('name',$name)->first();
                            if (!empty($tags_data)) 
                            {
                                $tags_id  =  $tags_data->id;
                            }
                            else
                            {

                                $tags_slug = CustomHelper::GetSlug('tags', 'id', 0, $name);
                                $tags_id = DB::table('tags')->insertGetId([
                                    'name' =>  $name,
                                    'slug' =>  $tags_slug,
                                    'status' => 1,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);
                            }

                            $data_record = array(
                                'blog_id' => $blog_id,
                                'tag_id' => $tags_id,
                                'created_at' => Carbon::now()
                            );

                            DB::table('blog_tags')->insert($data_record);                  
                        }
                    }

                $msg = ucwords($type)." has been added successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = 'blogs';
                $row_id = $id;
                $action_type = 'Add On Blog List';
                $action_description = 'Edit On ('.$request->title.")";
                $description = 'Add('.$request->title.") ".$description;*/
            }

            $oldImg = '';
            $blogClass = new Blog;

            if(is_numeric($id) && $id > 0){
                $exist = Blog::find($id);

                if(isset($exist->id) && $exist->id == $id){
                    $blogClass = $exist;
                    $oldImg = $exist->image;
                }
            } else if($blog_id){
                $exist = Blog::find($blog_id);

                if(isset($exist->id) && $exist->id == $blog_id){
                    $blogClass = $exist;
                    $oldImg = $exist->image;
                }
            }

            if ($isSaved) {

                //============

                   if(!empty($request->category_id))
                    {

                         DB::table('blog_tags')->where('blog_id',$blog_id)->delete();
                         
                         if(!empty($blog->id) && $blog->id == $id){
                            $cat_data = BlogToCategories::where('blog_id', $id)->delete();
                        }

                        // DB::table('blog_to_categories')->where('blog_id',$blog_id)->get();
                        $blog_cat_arr = $request->category_id;// explode(",",$request->category_id);
                        foreach($blog_cat_arr as $category_id)
                        {
                            //Check tag master
                            $blogToCat_data = DB::table('blog_categories')->where('id',$category_id)->first();
                            if (!empty($blogToCat_data)) 
                            {
                               
                                $data_record = array(
                                    'blog_id' => $blog_id,
                                    'category_id' => $category_id,
                                );

                                DB::table('blog_to_categories')->insert($data_record);                  
                            }
                        }
                    }

                //=============Activity Logs=====================
                $new_data = DB::table('blogs')->where('id',$blog_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id= $blog_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = ucwords($type);
                $params['type'] = $type;
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

                 //=============Activity Logs=====================

                cache()->forget('blogs');

                $this->saveImage($request, $blogClass, $oldImg);

                /* CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);*/

                return redirect(url($this->ADMIN_ROUTE_NAME.'/blogs?type='.$type))->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', ucwords($type). 'could be added, please try again or contact the administrator.');
            }
        }

        $data = [];
        $data['page_heading'] = $title;
        $data['blog'] = $blog;
        $data['image'] = $image;
        $data['categories'] = $categories;
        $data['id'] = $id;

        return view('admin.blogs.form', $data);
      
    }

    public function blog_view(Request $request){

        $type = (isset($request->type))?$request->type:'';
        $id = (isset($request->id))?$request->id:0;
        $blog = '';
        $title = ucfirst($type).' Details';

        if(is_numeric($id) && $id > 0){

            $blog = Blog::where('id', $id)->first();
            //prd($blog_query);
            $title = 'Blog Details ('.$blog->title.')';

        }
        
        $data = [];
        $data['page_heading'] = $title;
        $data['blog'] = $blog;
        $data['id'] = $id;
        $data['type'] = $type;
        return view('admin.blogs.view', $data);
    }

    private function saveImage($request, $blogs, $oldImg=''){

        $file = $request->file('image');

        if ($file) {
            $path = 'blogs/';
            $thumb_path = 'blogs/thumb/';
            $storage = Storage::disk('public');
            //prd($storage);

            $IMG_HEIGHT = CustomHelper::WebsiteSettings("BLOG_IMG_HEIGHT");
            $IMG_WIDTH = CustomHelper::WebsiteSettings("BLOG_IMG_WIDTH");
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings("BLOG_THUMB_HEIGHT");
            $THUMB_WIDTH = CustomHelper::WebsiteSettings("BLOG_THUMB_WIDTH");
            
            $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:768;
            $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:768;
            $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:336;
            $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:336;

            $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

            if($uploaded_data['success']){

                if(!empty($oldImg)){
                    if($storage->exists($path.$oldImg)){
                        $storage->delete($path.$oldImg);
                    }
                    if($storage->exists($thumb_path.$oldImg)){
                        $storage->delete($thumb_path.$oldImg);
                    }
                }

                $image = $uploaded_data['file_name'];

                $blogs->image = $image;
                $blogs->save();         
            }

            if(!empty($uploaded_data)){   
                return $uploaded_data;
            }   

        }
    }  
    
    /*delete image*/
    public function ajax_delete_image(Request $request){
        $response = [];

        $response['success'] = false;

        $message = '';
        $id = (isset($request->id))?$request->id:0;

        $is_deleted = 0;

        if(is_numeric($id) && $id > 0){

            $blog = Blog::find($id);
            //prd($blog);
            if(!empty($blog)){

                $storage = Storage::disk('public');

                $path = 'blogs/';
                $thumb_path = 'blogs/thumb/';

                $image = $blog->image;

                if(!empty($image) && $storage->exists($path.$image)){
                    $is_deleted = $storage->delete($path.$image);
                }

                if(!empty($image) && $storage->exists($thumb_path.$image)){
                    $is_deleted = $storage->delete($thumb_path.$image);
                }

                if($is_deleted){
                    $blog->image = '';
                    $blog->save();
                }
            }
        

        if($is_deleted){

            $response['success'] = true;

            $message = '<div class="alert alert-success alert-dismissible"> <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> Image has been deleted succesfully. </div';
        }
        else{
            $message = '<div class="alert alert-danger alert-dismissible"> <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> Something went wrong, please try again... </div';
        }

        $response['message'] = $message;

        return response()->json($response);

    }
}

public function delete(Request $request){
        $type = (isset($request->type))?$request->type:'';
        $id= isset($request->id)?$request->id:'';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $method=$request->method();
        $is_deleted = 0;

        if($method=="POST"){
            if(is_numeric($id) && $id > 0)
            {
                $blog = Blog::find($id);
                $last_data = DB::table('blogs')->where('id',$id)->first();
                $module_desc =  !empty($last_data->title)?$last_data->title:'';
                $last_data =(array) $last_data;
                $last_data = json_encode($last_data);
                $blog_img = $blog->image;
                /*$function_name = $this->currentUrl;
                $action_table = 'blogs';
                $row_id = $id;
                $action_type = 'Delete Blog List';
                $blogTitle = isset($blog->title) ? $blog->title:"";
                $action_description = 'Delete ('.$blogTitle.")";
                $description = 'Delete ('.$blogTitle.")";*/
                $is_delete = Blog::blogTagDelete($id);
                if ($is_delete['status'] != 'ok') {

                return redirect(url($this->ADMIN_ROUTE_NAME.'/blogs?type='.$type))->with('alert-danger', $is_delete['message']);

                } 
                else {

                $delete_category_name = $is_delete['name'];

                $is_deleted = true;

                }
                //prd($blog_img);
                if(!empty($blog_img))
                {
                    $storage = Storage::disk('public');

                    $path = 'blogs/';
                    $thumb_path = 'blogs/thumb/';

                    if(!empty($blog_img) && $storage->exists($path.$blog_img)){
                        $is_deleted = $storage->delete($path.$blog_img);
                    }

                    if(!empty($blog_img) && $storage->exists($thumb_path.$blog_img)){
                        $is_deleted = $storage->delete($thumb_path.$blog_img);
                    }                    
                }
                 //$is_delete = Blog::where('id', $id)->delete();
            }
        }
        
        if(!empty($is_delete)){

             /*CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);*/

             $params = [];
             $params['user_id'] = $user_id;
             $params['user_name'] = $user_name;
             $params['module'] = ucwords($type);
             $params['module_desc'] = $module_desc;
             $params['module_id'] = $id;
             $params['sub_module'] = "";
             $params['sub_module_id'] = 0;
             $params['sub_sub_module'] = "";
             $params['sub_sub_module_id'] = 0;
             $params['data_after_action'] = $last_data??'';
             $params['action'] = "Delete";

             CustomHelper::RecordActivityLog($params);

            return back()->with('alert-success',ucwords($type).' has been deleted successfully.');
        }
        else{
            return back()->with('alert-danger', 'something went wrong, please try again...');
        }

    }

    public function seoView(Request $request)
    {
        $type = (isset($request->type))?$request->type:'';
        $blogs = [];
        $data = [];
        $id = isset($request->id) ? $request->id : 0;
        $blogs = Blog::find($id);
        $blogSeoView = "";
        $blogTitle = isset($blogs->title) ? $blogs->title:'';
        $title = "SEO Meta Details (".$blogTitle.")";
        
        $data = [];
        $data["page_heading"] = $title;
        $data["blogs"] = $blogs;
        $data["id"] = $id;
        $data["type"] = $type;
        return view("admin.blogs.seo_view", $data);
    }


    public function seoMeta(Request $request){
    
        $blogs = [];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $id = isset($request->id) ? $request->id : 0;
        $type = isset($request->type) ? $request->type : '';
        $blogs = Blog::find($id);
        $blogSeo = [];
        $blogTitle = isset($blogs->title) ? $blogs->title:'';
        $name = "SEO Meta (".$blogTitle.")";
    
        if ($request->method() == "POST" || $request->method() == "post") {
    
            $blogSeo = Blog::find($id);
            $req_data = [];
            $req_data = $request->except(["_token", 'id', "page", "back_url",'created_at','updated_at']);
            if (!empty($blogSeo) && $blogSeo->count() > 0) {
                $isSaved = Blog::where("id",$blogSeo->id)->update($req_data);
                $blog_seo_id = $blogSeo->id;
                $msg ="Blog Seo Meta has been updated successfully.";
            } else {
                $isSaved = Blog::create($req_data);
                $blog_seo_id = $isSaved->id;
                $msg =
                "Blog Seo Meta has been added successfully.";
            }
    
            if ($isSaved) {
    
                    //===================Activity Logs==============================
    
                $new_data = DB::table('blogs')->where(['id'=>$id])->first();
                $module_id = !empty($new_data->id)?$new_data->id:'';
                $sub_module_desc = !empty($new_data->name)?$new_data->name:'';
                $submodule_id = $blog_seo_id;
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
    
                $blog_list = Blog::where(['id'=>$module_id])->first();
                $module_desc = !empty($blog_list->title)?$blog_list->title:'';
    
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Blog';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "Blog Seo";
                $params['sub_module_desc'] = $sub_module_desc;
                $params['sub_module_id'] = $submodule_id;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($blogSeo->id)) ? "Update" : "Add";
    
                CustomHelper::RecordActivityLog($params);
    
                    //===================Activity Logs==============================
    
                cache()->forget("blogs");    
                return redirect(route($this->ADMIN_ROUTE_NAME.'.blogs.seo_view',['id'=>$id, 'type'=>$type]))->with('alert-success', $msg);
            } else {
                return back()->with(
                    "alert-danger",
                    "The Blog Seo Meta could be added, please try again or contact the administrator."
                );
            }
        }

        $data = [];
        $data["blogs"] = $blogs;
        $data["type"] = $type;
        $data["id"] = $id;
        $data["page_heading"] = $name;
        $data["blogSeo"] = $blogSeo;
        return view("admin.blogs.seo_meta", $data);
    }

    /* end of controller */
}