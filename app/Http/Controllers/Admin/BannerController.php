<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use App\Models\BannerImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

use App\Helpers\CustomHelper;

use Validator;
use Storage;
use Image;
use File;
use DB;

class BannerController extends Controller{

    // private $page_arr;
    private $limit;
    private $ADMIN_ROUTE_NAME;
    // private $currentUrl;


    public function __construct(){
        $this->limit = 20;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        // $this->currentUrl = url()->current();
    }

    public function index(Request $request){
    //echo route('admin.banners.index');die;
        $data = [];
        $limit = $this->limit;
        $title = isset($request->title) ? $request->title : "";
        $status = isset($request->status) ? $request->status : 1;
        $banner = Banner::orderBy('created_at','desc');
        if(!empty($title)){
            $banner->where("title", "like", "%" . $title . "%");
        }
            $banner->where("status", $status);

        $banners = $banner->paginate($limit);
        $data['banners'] = $banners;     
        $data['limit'] = $limit;
        return view('admin.banners.index', $data);
    }

    public function add(Request $request){

        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $banner_id = (isset($request->banner_id))?$request->banner_id:0;
        $banner = '';
        $banner_images = '';
        $title = 'Add Banner';

        if(is_numeric($banner_id) && $banner_id > 0){
            $banner = Banner::find($banner_id);
            $banner_images = BannerImage::where('banner_id', $banner_id)->get();
            $title = 'Edit Banner (' .$banner->title.')';
        }
        if($request->method() == 'POST' || $request->method() == 'post'){
            
            $rules = [];
            $validation_msg = [];

            $rules['title'] = 'required|max:255';
            $rules['type'] = 'required';
            if(!empty($banner_id)) {
                $rules["slug"] = 'required';
            }
            $rules['status'] = 'required';

            if(!empty($request->type) && $request->type==2){
                if(!empty($request->video_type) && $request->video_type==2){
                    $rules['video_embed'] = 'required';
                }
                if(!empty($request->video_type) && $request->video_type==1){
                    $rules['video_name'] = 'required';
                    $validation_msg['video_name.required'] = "Video is required.";
                }
            }

            $this->validate($request, $rules, $validation_msg);

            $req_data = [];
            $req_data = $request->except(['_token', 'back_url','old_video','video_name','banner_id','slug']);
            $req_data['video'] = (isset($request->video_name)) ? $request->video_name:"";
            //$req_data['banner_id'] = (!empty($request->banner_id)) ? $request->banner_id:0;
            // $req_data['image_name'] = (!empty($request->image_name)) ? $request->image_name:"";
            $req_data['title'] = (!empty($request->title)) ? $request->title:"";
            // $req_data['sub_title'] = (!empty($request->sub_title)) ? $request->sub_title:"";
            // $req_data['link_text_1'] = (!empty($request->link_text_1)) ? $request->link_text_1:"";
            // $req_data['link_1'] = (!empty($request->link_1)) ? $request->link_1:"";
            // $req_data['link_text_2'] = (!empty($request->link_text_2)) ? $request->link_text_2:"";
            // $req_data['link_2'] = (!empty($request->link_2)) ? $request->link_2:"";
            //prd($req_data);

            // if(empty($banner_id)){
            //     $slug = CustomHelper::GetSlug('banners', 'id', $banner_id, $request->title);
            // }
            // else{
            //     $slug = CustomHelper::GetSlug('banners', 'id', $banner_id, $request->slug);
            // }

            // // $req_data['slug'] = isset($request->slug) ? $request->slug:"";
            // $req_data['slug'] = $slug ? $slug: "";

            if(!empty($banner)){
                $req_data["slug"] = CustomHelper::GetSlug('banners', 'id', $banner_id, $request->slug);
                $isSaved = Banner::where('id', $banner->id)->update($req_data);
                $banners_id = $banner->id;
                $msg="The Banner has been updated successfully.";

               /* $description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "banners";
                $row_id = $banner_id;
                $action_type = "Edit On Banner";
                $action_description ="Edit On (" . $request->title . ")";
                $description ="Update(" .$request->title .") " .$description;*/
            }
            else{
                //prd($req_data);
                $req_data["slug"] = CustomHelper::GetSlug('banners', 'id', $banner_id, $request->title);
                $isSaved = Banner::create($req_data);
                $banners_id = $isSaved->id;

                // if(empty($req_data['slug']) && !empty($banner_id)){
                //     $bannerID = 'banner_'.$banner_id;
                //     Banner::where("id",$banner_id)->update(['slug' => $bannerID]);
                // }

                $msg="The Banner has been added successfully.";

                /*$description = json_encode($req_data);
                $function_name = $this->currentUrl;
                $action_table = "banners";
                $row_id = $banner_id;
                $action_type = "Add On Banner";
                $action_description = "Add On (" . $request->title . ")";
                $description =
                    "Add(" . $request->title . ") " . $description;*/
            }

            /*if($request->hasFile('image')) {
                $files = $request->file('image');
                $images_result = $this->saveImages($files, $banner_id, $ext);
            }*/

            if ($isSaved) {

                cache()->forget('banners');

                /*CustomHelper::recordActionLog($function_name, $action_table, $row_id, $action_type, $action_description, $description);*/

                //=============Activity Logs=====================

                $new_data = DB::table('banners')->where('id',$banners_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $banners_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Banner';
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

                return redirect(route('admin.banners.index'))->with('alert-success', $msg);
                //return redirect(url($this->ADMIN_ROUTE_NAME.'/banners'))->with('alert-success', $msg);
            } else {
                return back()->with('alert-danger', 'The Banner cannot be added, please try again or contact the administrator.');

            }
        }
            $data = [];
            $data['page_heading'] = $title;
            $data['banner'] = $banner;
            $data['banner_images'] = $banner_images;
            $data['banner_id'] = $banner_id;

            return view('admin.banners.form', $data);
        }

    public function images(Request $request)
    {
        $data['page_heading'] = "Banner Images";
        $data['banner_id'] = $request->banner_id;

        $bannerImages = BannerImage::where('banner_id',$request->banner_id)->orderBy('sort_order','ASC')->get();
        $data['bannerImages'] = $bannerImages;

        return view('admin.banners.upload', $data);
    }

    public function ajax_delete_image(Request $request){

        $storage = Storage::disk('public');
        $result['success'] = false;
        $is_deleted = 0;
        $id = ($request->has('id'))?$request->id:0;
        $path = 'banners/';
        if (is_numeric($id) && $id > 0) {
            $banner = BannerImage::find($id);
            if($banner)
            {
                $is_deleted =  $banner->delete();
                if(!empty($banner->image_name)){
                    $image = $banner->image_name;
                    if(!empty($image) && $storage->exists($path.$image)){
                        $is_deleted = $storage->delete($path.$image);
                    }

                    if(!empty($image) && $storage->exists($path.'thumb/'.$image)){
                        $is_deleted = $storage->delete($path.'thumb/'.$image);
                    }

                    if($is_deleted){

                        $banner->delete();

                        $result['success'] = true;
                        $result['msg'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Banner Image has been deleted successfully.</div>';
                    }
                }
            }
        }

        if($result['success'] == false){
            $result['msg'] = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
        }
        return response()->json($result);
    }

    public function ajax_delete_video(Request $request){

        $storage = Storage::disk('public');
        $result['success'] = false;
        $is_deleted = '';
        $id = ($request->has('id'))?$request->id:0;
        $path = 'banners/video/';
        if (is_numeric($id) && $id > 0) {
            $banner = Banner::find($id);
            if($banner)
            {
                if(!empty($banner->video)){
                    $video = $banner->video;
                    if(!empty($video) && $storage->exists($path.$video)){
                        $is_deleted = $storage->delete($path.$video);

                        $banner->video = "";
                        $banner->save();
                    }
                    if($is_deleted){
                        $result['success'] = true;
                        $result['msg'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Banner Video has been deleted successfully.</div>';
                    }
                }
            }
        }

        if($result['success'] == false){
            $result['msg'] = '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong, please try again.</div>';
        }
        return response()->json($result);
    }

    public function delete(Request $request){
        //$id = isset($request->id) ? $request->id:"";
        $method = $request->method();
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $is_deleted = 0;

        if($method == "POST"){
            $banner_id = isset($request->banner_id) ? $request->banner_id:"";

            if(is_numeric($banner_id) && $banner_id > 0){
                $banner = Banner::where('id', $banner_id)->first();
                /*$function_name = $this->currentUrl;
                $action_table = "banners";
                $row_id = $banner_id;
                $action_type = "Delete Banner";
                $bannerTitle = isset($banner->title) ? $banner->title:"";
                $action_description = "Delete (" . $bannerTitle . ")";
                $description = "Delete (" . $bannerTitle . ")";*/
                $new_data = DB::table('banners')->where('id',$banner_id)->first();
                $module_desc =  !empty($new_data->title)?$new_data->title:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);
                $is_delete = Banner::bannerImageDelete($banner_id);
                if ($is_delete['status'] != 'ok') {

                return redirect(url('admin/banners'))->with('alert-danger', $is_delete['message']);

                } 
                else {

                $delete_banner_name = $is_delete['name'];

                $is_deleted = true;

                }
                if($banner->type == 1){
                    $bannerImages = BannerImage::where('banner_id', $banner_id)->get();

                    if(!($bannerImages)->isEmpty()){
                        foreach ($bannerImages as $img) {
                            $image_id = $img->id;
                            $this->delete_banner_images($image_id);
                        }
                    }
                }

                if($banner->type == 2 && $banner->video_type == 1 && !empty($banner->video)){

                    $storage = Storage::disk('public');
                    $path = 'banners/video/';

                    $video = $banner->video;
                    if(!empty($video) && $storage->exists($path.$video)){
                        $is_deleted = $storage->delete($path.$video);
                    }
                }                

                //$is_deleted = $banner->delete();
            }
        }
        
        if($is_deleted){

        /*CustomHelper::recordActionLog(
        $function_name,$action_table,$row_id,$action_type,$action_description,$description );*/

        //=============Activity Logs=====================

            $params = [];
            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'Banner';
            $params['module_desc'] = $module_desc??'';
            $params['module_id'] = $banner_id??'';
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_after_action'] = $new_data??'';
            $params['action'] = 'Delete';

            CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================

            return redirect(url($this->ADMIN_ROUTE_NAME.'/banners'))->with('alert-success', 'The Banner has been deleted successfully.');
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

    /* ajax banner_update */
    public function ajax_banner_update(Request $request){

        $response = [];
        $err_msg = '';
        $response['success'] = false;

        $idArr = (isset($request->ids))?$request->ids:'';

        $titleArr = (isset($request->title))?$request->title:'';
        $subTitleArr = (isset($request->sub_title))?$request->sub_title:'';
        $linkTextArr1 = (isset($request->link_text_1))?$request->link_text_1:'';
        $linkTextArr2 = (isset($request->link_text_2))?$request->link_text_2:'';
        $linkArr1 = (isset($request->link_1))?$request->link_1:'';
        $linkArr2 = (isset($request->link_2))?$request->link_2:'';
        $sortOrderArr = (isset($request->sort_order))?$request->sort_order:'';

        $isSaved = false;

        if(!empty($idArr) && count($idArr) > 0){
            foreach($idArr as $id){
                $image = BannerImage::find($id);

                if(isset($image->id) && $image->id == $id){

                    $title = (isset($titleArr[$id]))?$titleArr[$id]:'';
                    $sub_title = (isset($subTitleArr[$id]))?$subTitleArr[$id]:'';
                    $link_1 = (isset($linkArr1[$id]))?$linkArr1[$id]:'';
                    $link_2 = (isset($linkArr2[$id]))?$linkArr2[$id]:'';
                    $link_text_1 = (isset($linkTextArr1[$id]))?$linkTextArr1[$id]:'';
                    $link_text_2 = (isset($linkTextArr2[$id]))?$linkTextArr2[$id]:'';
                    $sort_order = (isset($sortOrderArr[$id]))?$sortOrderArr[$id]:'';

                    if(is_numeric($sort_order)){

                    $image->title = $title;
                    $image->sub_title = $sub_title;
                    $image->link_1 = $link_1;
                    $image->link_2 = $link_2;
                    $image->link_text_1 = $link_text_1;
                    $image->link_text_2 = $link_text_2;

                    $image->sort_order = $sort_order;

                    $isSaved = $image->save();
                  }else{
                    $err_msg = 'Sort order can only be numeric';
                  }
                }
            }
        }

        if($isSaved){
            $response['success'] = true;
            session()->flash('alert-success', 'images has been saved successfully.');
        }else{
            $response['success'] = false;
            $response['err_msg'] = $err_msg;
            //session()->flash('alert-danger', 'images did not uploaded successfully.');
        }

        return response()->json($response);
    }


    /**
     * Handles the file upload
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws UploadMissingFileException
     * @throws \Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException
     */
    public function uploadImages(Request $request) {
        // create the file receiver
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        // receive the file
        $save = $receiver->receive();
        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
            // save the file and return any response you need, current example uses `move` function. If you are
            // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())

            $file = $save->getFile();
            $fileName = $this->createFilename($file);
            // Group files by mime type
            $mime = str_replace('/', '-', $file->getMimeType());
            // Group files by the date (week
            //$dateFolder = date("Y-m-W");
            // Build the file path
            $filePath = "banners/";
            $thumb_path = 'banners/thumb/';
            // $finalPath = public_path("storage/" . $filePath);
            // move the file name
            //$saveImg = $file->move($finalPath, $fileName);

            $IMG_WIDTH = CustomHelper::WebsiteSettings('BANNER_IMG_WIDTH');
            $IMG_HEIGHT = CustomHelper::WebsiteSettings('BANNER_IMG_HEIGHT');
            $THUMB_WIDTH = CustomHelper::WebsiteSettings('BANNER_THUMB_WIDTH');
            $THUMB_HEIGHT = CustomHelper::WebsiteSettings('BANNER_THUMB_HEIGHT');

            $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:768;
            $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:768;
            $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:336;
            $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$THUMB_HEIGHT:336;

            /*if (!File::exists(public_path("storage/" . $filePath))) {
                File::makeDirectory(public_path("storage/" . $filePath), $mode = 0777, true, true);
            }
            if (!File::exists(public_path("storage/" . $filePath. 'thumb/'))) {
                File::makeDirectory(public_path("storage/" . $filePath. 'thumb/'), $mode = 0777, true, true);
            }

            $is_uploaded = Image::make($file)->resize($IMG_WIDTH, $IMG_HEIGHT, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('jpg', 1)->save($finalPath . $fileName);

            if ($is_uploaded) {
                $thumb = Image::make($file)->resize($THUMB_WIDTH, $THUMB_HEIGHT, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg', 1)->save(public_path("storage/" . $filePath . 'thumb/' . $fileName));
            }*/

            $is_uploaded = CustomHelper::UploadImage($file, $filePath, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

            //prd($is_uploaded);

            if($is_uploaded['success']){
                $fileName = $is_uploaded['file_name'];
                $reqData = [];
                $reqData['banner_id'] = $request->banner_id;
                $reqData['image_name'] = $fileName;
                $reqData['title'] = NULL;
                $reqData['sub_title'] = NULL;
                $reqData['link_text_1'] = NULL;
                $reqData['link_text_2'] = NULL;
                $reqData['link_1'] = NULL;
                $reqData['link_2'] = NULL;
                BannerImage::insert($reqData);
            }
             
            return response()->json([
                'path' => $filePath,
                'name' => $fileName,
                'mime_type' => $mime
            ]);
        }
        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();
        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    public function uploadVideo(Request $request) {
        // create the file receiver
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        // receive the file
        $save = $receiver->receive();
        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
            // save the file and return any response you need, current example uses `move` function. If you are
            // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
            return $this->saveVideoFile($save->getFile());
        }
        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();
        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    /**
     * Saves the file to S3 server
     *
     * @param UploadedFile $file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function saveFileToS3($file)
    {
        $fileName = $this->createFilename($file);
        $disk = Storage::disk('s3');
        // It's better to use streaming Streaming (laravel 5.4+)
        $disk->putFileAs('photos', $file, $fileName);
        // for older laravel
        // $disk->put($fileName, file_get_contents($file), 'public');
        $mime = str_replace('/', '-', $file->getMimeType());
        // We need to delete the file when uploaded to s3
        unlink($file->getPathname());
        return response()->json([
            'path' => $disk->url($fileName),
            'name' => $fileName,
            'mime_type' =>$mime
        ]);
    }

    /**
     * Saves the file
     *
     * @param UploadedFile $file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function saveVideoFile(UploadedFile $file)
    {
        $fileName = $this->createFilename($file);
        // Group files by mime type
        $mime = str_replace('/', '-', $file->getMimeType());
        // Group files by the date (week
        //$dateFolder = date("Y-m-W");
        // Build the file path
        $filePath = "banners/video/";
        $finalPath = public_path("storage/" . $filePath);
        // move the file name
        $file->move($finalPath, $fileName);
        return response()->json([
            'path' => $filePath,
            'name' => $fileName,
            'mime_type' => $mime
        ]);
    }

    /**
     * Create unique filename for uploaded file
     * @param UploadedFile $file
     * @return string
     */
    protected function createFilename(UploadedFile $file) {
        $extension = $file->getClientOriginalExtension();
        $fileOriginalName = $file->getClientOriginalName();
        $fileOriginalName = CustomHelper::sanitizeUploadFile($fileOriginalName);
        $fileName = date("dmyhis")."-".$fileOriginalName;
        return $fileName;
    }

    /* end of controller */
}