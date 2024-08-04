<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use App\Models\BannerImage;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use App\Models\BannerImageGallery;
use App\Http\Controllers\Controller;

use Validator;
use Storage;
use Image;
use DB;

class BannerImageGalleryController extends Controller{

    private $page_arr;
    private $limit;
    private $ADMIN_ROUTE_NAME;

    public function __construct(){
        $this->page_arr = array(
            'tour-packages/india'=>'Tour Packages',
            'packages'=>'Packages',
            'blog-news'=>'Blog & News',
            'themes'=>'Themes',
            'destinations'=>'Destinations',
            'destinations-details/india'=>'Destinations Details',
            'contact-us'=>'Contact-us',
            'about'=>'About-Us',
            
            
        );
        $this->limit = 20;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request){
    //echo route('admin.banners.index');die;
        $data = [];
        $limit = $this->limit;

        $title = isset($request->title) ? $request->title:'';
        // prd($title);
        $status = isset($request->status) ? $request->status:'';

        $banner = BannerImageGallery::orderBy('sort_order','asc');

        if (!empty($title)) {        
            $banner->where("title", "like", "%" . $title . "%");
        }
        if(strlen($status) > 0){
            $banner->where('status',$status);

        }
        //prd($status);
        $total_banner = $banner->count();       
        //pr($categories->toArray());
        $banners = $banner->paginate($limit);
        //prd($banners->toArray());
        $data['total_banner'] = $total_banner;
        $data['banners'] = $banners;
        $data['page_arr'] = $this->page_arr;

        return view('admin.bannerimagegallery.index', $data);

    }

    public function add(Request $request){
        $banner_id = (isset($request->banner_id))?$request->banner_id:0;
        $banner = '';
        $banner_images = '';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $title = 'Add Banner';

        if(is_numeric($banner_id) && $banner_id > 0){
            $banner = BannerImageGallery::find($banner_id);
            $banner_images = BannerImage::where('banner_id', $banner_id)->get();
            $title = "Edit Banner (" . $banner->title . ")";
        }
        if($request->method() == 'POST' || $request->method() == 'post'){

            $IMG_HEIGHT = CustomHelper::WebsiteSettings('BANNER_IMG_HEIGHT');
            $IMG_WIDTH = CustomHelper::WebsiteSettings('BANNER_IMG_WIDTH');

            $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:1600;
            $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:640;
            
            $rules = [];
            $validation_msg = [];

            $rules['title'] = 'required';
            $rules['page'] = 'required';
            $rules['device_type'] = 'required';
            $rules['status'] = 'required';

            $ext = 'jpg,jpeg,png,gif';

            $rules['image.*'] = 'nullable|image|mimes:'.$ext.'|dimensions:max_width='.$IMG_WIDTH.',max_height='.$IMG_HEIGHT;

            $validation_msg['image.*.dimensions'] = 'Image width/height should be '.$IMG_WIDTH.'/'.$IMG_HEIGHT.'px';

            if($request->page == 'home_link'){
                $rules['link'] = 'required';
            }
            $this->validate($request, $rules, $validation_msg);

            $req_data = [];
            $req_data = $request->except(['_token', 'image', 'back_url', 'old_image','banner_id']);

            if(!empty($banner) && count(array($banner)) > 0){
                $isSaved = BannerImageGallery::where('id', $banner->id)->update($req_data);
                $banners_image_id = $banner->id;
                $msg="The Banner has been updated successfully.";
            }
            else{
                $isSaved = BannerImageGallery::create($req_data);
                $banners_image_id = $isSaved->id;
                $msg="The Banner has been added successfully.";
            }

            if($request->hasFile('image')) {
                $files = $request->file('image');
                $images_result = $this->saveImages($files, $banners_image_id, $ext);
            }

            if ($isSaved) {


                //=============Activity Logs=====================

                $new_data = DB::table('banner_image_gallery')->where('id',$banners_image_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $banners_image_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Banner Image Gallery';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                $params['action'] = (!empty($banner->id)) ? "Update" : "Add";

                CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================

                cache()->forget('managebanners');

                return redirect(route('admin.managebanners.index'))->with('alert-success', $msg);
                //return redirect(url($this->ADMIN_ROUTE_NAME.'/managebanners'))->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', 'The Banner cannot be added, please try again or contact the administrator.');
            }
        }

        $data = [];
        $data['page_heading'] = $title;
        $data['banner'] = $banner;
        $data['banner_images'] = $banner_images;
        $data['banner_id'] = $banner_id;
        $data['page_arr'] = $this->page_arr;

        return view('admin.bannerimagegallery.form', $data);
    }


    public function saveImages($files, $banner_id, $ext='jpg,jpeg,png,gif'){

        $is_inserted = '';

        if ($files && count($files) > 0) {

            //prd($files);

            $path = 'banners/';
            $thumb_path = 'banners/thumb/';

            //UploadImage($file, $path, $ext='', $width=768, $height=768, $is_thumb=false, $thumb_path, $thumb_width=300, $thumb_height=300)

            $IMG_HEIGHT = CustomHelper::WebsiteSettings('BANNER_IMG_HEIGHT');
            $IMG_WIDTH = CustomHelper::WebsiteSettings('BANNER_IMG_WIDTH');
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings('BANNER_THUMB_HEIGHT');
            $THUMB_WIDTH = CustomHelper::WebsiteSettings('BANNER_THUMB_WIDTH');

            $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:1600;
            $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:640;
            $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:400;
            $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:400;

            $images_data = [];

            foreach($files as $file){
                $upload_result = CustomHelper::UploadImage($file, $path, $ext, $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

                if($upload_result['success']){
                    $images_data[] = array(
                        'banner_id' => $banner_id,
                        'image_name' => $upload_result['file_name']
                    );
                }
            }
            if(!empty($images_data) && count($images_data) > 0){
                $is_inserted = BannerImage::insert($images_data);
            }

        }

        return $is_inserted;

    }


    public function ajax_delete_image(Request $request){

        $result['success'] = false;

        $image_id = ($request->has('image_id'))?$request->image_id:0;

        if (is_numeric($image_id) && $image_id > 0) {
            $is_img_deleted = $this->delete_banner_images($image_id);
            if($is_img_deleted)
            {
                $result['success'] = true;
                $result['msg'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Banner image has been delete successfully.</div>';
            }
        }

        if($result['success'] == false){
            $result['msg'] = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
        }
        return response()->json($result);
    }

    public function delete(Request $request){
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;

        if($method == "POST"){
            $banner_id = $request->banner_id;

            if(is_numeric($banner_id) && $banner_id > 0){
                $banner = BannerImageGallery::where('id', $banner_id)->first();

                $bannerImages = BannerImage::where('banner_id', $banner_id)->get();

                $new_data = DB::table('banner_image_gallery')->where('id',$banner_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                if(count($bannerImages) > 0){
                    foreach ($bannerImages as $img) {
                        $image_id = $img->id;
                        $this->delete_banner_images($image_id);
                    }
                }
                $is_deleted = $banner->delete();
            }
        }
        
        if($is_deleted){

            //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Banner Image Gallery';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $banner_id??'';
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data??'';
            $params['action'] = 'Delete';

            CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================

            return redirect(url($this->ADMIN_ROUTE_NAME.'/managebanners'))->with('alert-success', 'The Banner has been deleted successfully.');
        }
        else{
            return back()->with('alert-danger', 'something went wrong, please try again.');
        }

    }

    public function delete_banner_images($id){
        $storage = Storage::disk('public');
        $path = 'banners/';
        $banner = BannerImage::where('id', $id)->first();
        $image = (isset($banner['image_name']))?$banner['image_name']:'';

        $is_deleted = $banner->delete();

        if($is_deleted){
            if(!empty($image) && $storage->exists($path.'thumb/'.$image)){
                $is_deleted = $storage->delete($path.'thumb/'.$image);
            }
            if(!empty($image) && $storage->exists($path.$image)){
                $is_deleted = $storage->delete($path.$image);
            }
            return true;
        }
        else{
            return false;
        }
    }


    /* end of controller */
}