<?php
namespace App\Http\Controllers\Admin;
use App\Models\CommonImage;
use App\Models\ImageCategory;
use App\Models\Package;
use App\Models\Destination;
use App\Models\Accommodation;
use App\Models\AccommodationRoom;
use App\Models\CabRoute;
use App\Models\CmsPages;
use App\Models\MediaImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Helpers\CustomHelper;
use Illuminate\Validation\Rule;
use Validator;
use DB;
use Storage;
use Image;
use Hash;
use File;

class ImageController extends Controller {


    private $imgPath;
    private $thumbPath;

    private $ADMIN_ROUTE_NAME;

    // private static $_invalidCharacters = array('*', ':', '/', '\\', '?', '[', ']');

    public function __construct(){
        $folderName = (request()->has('tbl'))?request('tbl'):((request()->has('module'))?request('module'):'');
        $this->imgPath = $folderName.'/';
        $this->thumbPath = "$folderName/thumb/";
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request){
        //pr($request->toArray());
        $data = [];

        $keyword = (isset($request->keyword))?$request->keyword:'';

        $data['page_heading'] = 'Images';

        return view('admin.images.index', $data);
    }

    public function upload(Request $request) {
        $data = [];
        $tbl_id = (isset($request->tid))?$request->tid:0;
        $child_table_id = (isset($request->table_id))?$request->table_id : 0;
        $tbl_name = $request->tbl??($request->module??'');
        $category_id = (isset($request->category_id))?$request->category_id:'';
        
        $category = (isset($request->category))?$request->category:'gallery';

        if(!empty($tbl_name)) {
            $qry = CommonImage::orderBy('sort_order','asc')->orderBy('created_at','desc');
            if(!empty($category_id)) {
                $qry->where('category_id', $category_id);
            }
            if(isset($child_table_id) && !empty($child_table_id) && $child_table_id > 0){
                $images = $qry->where(['tbl_id'=>$child_table_id, 'tbl_name'=>$tbl_name])->where('category',$category)->get();
            }else{
                $images = $qry->where(['tbl_id'=>$tbl_id, 'tbl_name'=>$tbl_name])->where('category',$category)->get();
            }
            $categories = ImageCategory::where('status',1)->orderBy('created_at','desc')->get();
            $path = $this->imgPath;
            $thumb_path = $this->thumbPath;

            $module_name = '';
            if($tbl_name == 'packages') {
                $record = Package::find($tbl_id);
                // prd($record->toArray());
                if($record->title) {
                    $module_name = ' ('.$record->title.')';
                }
            } else if($tbl_name == 'accommodations') {
                $record = Accommodation::find($tbl_id);
                // prd($record->toArray());
                if($record->name) {
                    $module_name = ' ('.$record->name.')';
                }
            } else if($tbl_name == 'destinations') {
                $record = Destination::find($tbl_id);
                // prd($record->toArray());
                if($record->name) {
                    $module_name = ' ('.$record->name.')';
                }
            } else if($tbl_name == 'accommodation_rooms') {
                $record = AccommodationRoom::find($child_table_id);
                if($record->room_type_name) {
                    $module_name = ' ('.$record->room_type_name.')';
                }
            }else if($tbl_name == 'cms_pages') {
                $record = CmsPages::find($tbl_id);
                // prd($record->toArray());
                if($record->title) {
                    $module_name = ' ('.$record->title.')';
                }
            }

            $data['page_heading'] = 'Upload Images'.$module_name;
            $data['tbl_id'] = $tbl_id;
            $data['table_id'] = $child_table_id;
            $data['package'] = $record??'';
            $data['tbl_name'] = $tbl_name;
            $data['images'] = $images;
            $data['segment'] = 'packages';
            $data['path'] = $path;
            $data['thumb_path'] = $thumb_path;
            $data['categories'] = $categories;
            return view('admin.images.upload', $data);
        }
        return redirect(url($this->ADMIN_ROUTE_NAME));

    }

    public function upload_view(Request $request) {
        $data = [];
        $tbl_id = (isset($request->tid))?$request->tid:0;
        $tbl_name = $request->tbl??($request->module??'');
        $category_id = (isset($request->category_id))?$request->category_id:'';
        $category = (isset($request->category))?$request->category:'gallery';
        if(!empty($tbl_name)) {
            $qry = CommonImage::orderBy('sort_order','asc')->orderBy('created_at','desc');
            if(!empty($category_id)) {
                $qry->where('category_id', $category_id);
            }
            $images = $qry->where(['tbl_id'=>$tbl_id, 'tbl_name'=>$tbl_name])->where('category',$category)->get();
            $categories = ImageCategory::where('status',1)->orderBy('created_at','desc')->get();
            $path = $this->imgPath;
            $thumb_path = $this->thumbPath;

            $module_name = '';
            if($tbl_name == 'packages') {
                $record = Package::find($tbl_id);
                // prd($record->toArray());
                if($record->title) {
                    $module_name = ' ('.$record->title.')';
                }
            } else if($tbl_name == 'accommodations') {
                $record = Accommodation::find($tbl_id);
                // prd($record->toArray());
                if($record->name) {
                    $module_name = ' ('.$record->name.')';
                }
            } else if($tbl_name == 'destinations') {
                $record = Destination::find($tbl_id);
                // prd($record->toArray());
                if($record->name) {
                    $module_name = ' ('.$record->name.')';
                }
            }else if($tbl_name == 'cab_route') {
                $record = CabRoute::find($tbl_id);
                // prd($record->toArray());
                if($record->name) {
                    $module_name = ' ('.$record->name.')';
                }
            }else if($tbl_name == 'cms_pages') {
                $record = CmsPages::find($tbl_id);
                // prd($record->toArray());
                if($record->title) {
                    $module_name = ' ('.$record->title.')';
                }
            }

            $data['page_heading'] = 'Images'.$module_name;
            $data['tbl_id'] = $tbl_id;
            $data['package'] = $record??'';
            $data['tbl_name'] = $tbl_name;
            $data['images'] = $images;
            $data['segment'] = 'packages';
            $data['path'] = $path;
            $data['thumb_path'] = $thumb_path;
            $data['categories'] = $categories;
            return view('admin.images.upload_view', $data);
        }
        return redirect(url($this->ADMIN_ROUTE_NAME));
    }

    public function old_upload(Request $request){
        //prd($request->toArray());
        $data = [];

        $tbl_id = (isset($request->tid))?$request->tid:0;
        $tbl_name = (isset($request->tbl))?$request->tbl:'';

        if(!empty($tbl_name)){

            $images = CommonImage::where(['tbl_id'=>$tbl_id, 'tbl_name'=>$tbl_name])->get();

            $path = $this->imgPath;
            $thumb_path = $this->thumbPath;

            $data['page_heading'] = 'Upload Images';
            $data['tbl_id'] = $tbl_id;
            $data['tbl_name'] = $tbl_name;
            $data['images'] = $images;
            $data['path'] = $path;
            $data['thumb_path'] = $thumb_path;

            return view('admin.images.upload', $data);
        }

        return redirect(url($this->ADMIN_ROUTE_NAME));
    }

    /* ajax_check_exist */
    public function ajaxCheckExist(Request $request){
        //prd($request->toArray());

        if($request->method() == 'POST' || $request->method() == 'post'){

            $filename = (isset($request->filename))?$request->filename:'';

            $storage = Storage::disk('public');

            $path = $this->imgPath;
            $thumb_path = $this->thumbPath;

            if(!empty($filename) && $storage->exists($path.$filename)){
                return 1;
            }
        }
        return 0;
    }

    /* ajax_upload */
    public function ajaxUpload(Request $request) {
        $response = [];
        $is_inserted = '';
        $response['success'] = false;
        $status_code = 503;
        if($request->method() == 'POST' || $request->method() == 'post') {
            $tbl_id = (isset($request->tid))?$request->tid:0;
            $child_table_id = (isset($request->table_id))?$request->table_id : 0;
            $tbl_name = (isset($request->tbl))?$request->tbl:'';
            $category = (isset($request->cat))?$request->cat:'';
            $file = ($request->hasFile('Filedata'))?$request->file('Filedata'):'';
            if(isset($child_table_id) && !empty($child_table_id) && $child_table_id > 0){
                $id_to_add = $child_table_id;
            }else{
                $id_to_add = $tbl_id;
            }
            if ($file && !empty($tbl_name)) {
                $path = $this->imgPath;
                $thumb_path = $this->thumbPath;
                $IMG_HEIGHT = CustomHelper::WebsiteSettings('COMMON_IMG_HEIGHT');
                $IMG_WIDTH = CustomHelper::WebsiteSettings('COMMON_IMG_WIDTH');
                $THUMB_HEIGHT = CustomHelper::WebsiteSettings('COMMON_THUMB_HEIGHT');
                $THUMB_WIDTH = CustomHelper::WebsiteSettings('COMMON_THUMB_WIDTH');

                $IMG_WIDTH = (!empty($IMG_WIDTH))?$IMG_WIDTH:1600;
                $IMG_HEIGHT = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:640;
                $THUMB_WIDTH = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:400;
                $THUMB_HEIGHT = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:400;

                $images_data = [];
                $ext = 'jpg,jpeg,png';
                $upload_result = CustomHelper::UploadImage($file, $path, $ext, $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

                //prd($upload_result);
                if($upload_result['success']) {
                    $imagesData = [];
                    $imagesData['tbl_id'] = $id_to_add;
                    $imagesData['tbl_name'] = $tbl_name;
                    $imagesData['category'] = $category;
                    $imagesData['name'] = $upload_result['file_name'];
                    $imagesData['sort_order'] = 0;
                    $imagesData['is_default'] = 0;
                    $isSaved = CommonImage::create($imagesData);

                    if($isSaved) {
                        $storage = Storage::disk('public');
                        $viewData = [];
                        $viewData['image'] = $isSaved;
                        $viewData['storage'] = $storage;
                        $viewData['path'] = $path;
                        $viewData['thumb_path'] = $thumb_path;
                        $viewData['module'] = $tbl_name;
                        //prd($viewData);
                        $imgRow = view('admin.images._row', $viewData)->render();
                        $response['success'] = true;
                        $response['imgRow'] = $imgRow;
                        $status_code = 200;

                        $module = $tbl_name;
                        $module_id = $id_to_add;
                        if(!empty($module) && !empty($module_id)) {
                            CustomHelper::setModuleDefaultImage($module,$module_id);
                        }
                    }
                }
            }
            return response()->json($response, $status_code);
        }
    }

    public function ajaxUploadFromGallery(Request $request) {
        $response = [];
        $is_inserted = '';
        $response['success'] = false;
        $status_code = 503;        
        if($request->method() == 'POST' || $request->method() == 'post') {
            $media_id = $request->media_id??'';
            $tbl_id = (isset($request->tid))?$request->tid:0;
            $child_table_id = (isset($request->table_id))?$request->table_id : 0;
            $tbl_name = (isset($request->tbl))?$request->tbl:'';
            $category = (isset($request->cat))?$request->cat:'';
            if(isset($child_table_id) && !empty($child_table_id) && $child_table_id > 0) {
                $id_to_add = $child_table_id;
            } else {
                $id_to_add = $tbl_id;
            }
            if (!empty($media_id) && !empty($tbl_name)) {
                $storage = Storage::disk('public');
                $file_name = '';
                $file_path = '';
                if($media_id) {
                    $media = MediaImage::find($media_id);
                    $mediaCollection = $media->getFirstMedia('media');
                    if(!empty($mediaCollection) && $mediaCollection->mime_type == "video/mp4"){
                        $mainImg = asset('assets/img/mp4.png');
                    } else if(!empty($mediaCollection) && $mediaCollection->mime_type == "application/msword") {
                        $mainImg = asset('assets/img/doc.png');
                    } else if(!empty($mediaCollection) && $mediaCollection->mime_type == "audio/mpeg") {
                        $mainImg = asset('assets/img/mp3.png');
                    } else if(!empty($mediaCollection) && $mediaCollection->mime_type == "application/pdf") {
                        $mainImg = asset('assets/img/pdf.png');
                    } else if(!empty($mediaCollection) && $mediaCollection->mime_type == "application/vnd.ms-excel") {
                        $mainImg = asset('assets/img/xlsx.png');
                    } else {
                        $mainImg = $mediaCollection->getUrl('medium');
                    }
                    $original_path = $mediaCollection->getPath();
                    if(!empty($mediaCollection->getUrl()) && file_exists($original_path)){
                        $path_arr = explode('app/public/', $original_path);
                        if(isset($path_arr[1])) {
                            $file_name = $mediaCollection->file_name;
                            $file_path = $path_arr[1];
                        }
                    }
                }
                if(!empty($file_name) && !empty($file_path)) {
                    $path = $this->imgPath;
                    $thumb_path = $this->thumbPath;

                    if (!File::exists(public_path("storage/" . $path))) {
                        File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
                    }
                    if (!File::exists(public_path("storage/" . $thumb_path))) {
                        File::makeDirectory(public_path("storage/" . $thumb_path), $mode = 0777, true, true);
                    }

                    $IMG_HEIGHT = CustomHelper::WebsiteSettings('COMMON_IMG_HEIGHT');
                    $IMG_WIDTH = CustomHelper::WebsiteSettings('COMMON_IMG_WIDTH');
                    $THUMB_HEIGHT = CustomHelper::WebsiteSettings('COMMON_THUMB_HEIGHT');
                    $THUMB_WIDTH = CustomHelper::WebsiteSettings('COMMON_THUMB_WIDTH');

                    $width = (!empty($IMG_WIDTH))?$IMG_WIDTH:1600;
                    $height = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:640;
                    $thumb_width = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:400;
                    $thumb_height = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:400;

                    // $is_thumb = true;
                    $path = $this->imgPath;
                    $thumb_path = $this->thumbPath;
                    $IMG_HEIGHT = CustomHelper::WebsiteSettings('COMMON_IMG_HEIGHT');
                    $IMG_WIDTH = CustomHelper::WebsiteSettings('COMMON_IMG_WIDTH');
                    $THUMB_HEIGHT = CustomHelper::WebsiteSettings('COMMON_THUMB_HEIGHT');
                    $THUMB_WIDTH = CustomHelper::WebsiteSettings('COMMON_THUMB_WIDTH');

                    $width = (!empty($IMG_WIDTH))?$IMG_WIDTH:1600;
                    $height = (!empty($IMG_HEIGHT))?$IMG_HEIGHT:640;
                    $thumb_width = (!empty($THUMB_WIDTH))?$THUMB_WIDTH:400;
                    $thumb_height = (!empty($THUMB_HEIGHT))?$IMG_WIDTH:400;

                    $fileOriginalName = $file_name;
                    $file = $storage->get($file_path);
                    $fileName = date("dmyhis")."-".$fileOriginalName;
                    $is_uploaded = Image::make($file)->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg', 1)->save(public_path("storage/" . $path . $fileName));
                    //prd($is_uploaded);
                    $upload_result = [];
                    $upload_result["success"] = false;
                    if ($is_uploaded) {
                        $upload_result["success"] = true;
                        // if ($is_thumb) {
                            $thumb = Image::make($file)->resize($thumb_width, $thumb_height, function ($constraint) {
                                $constraint->aspectRatio();
                            })->encode('jpg', 1)->save(public_path("storage/" . $thumb_path . $fileName));
                        // }
                        $upload_result["org_name"] = $fileOriginalName;
                        $upload_result["file_name"] = $fileName;
                    }

                    //prd($upload_result);
                    if($upload_result['success']) {
                        $imagesData = [];
                        $imagesData['tbl_id'] = $id_to_add;
                        $imagesData['tbl_name'] = $tbl_name;
                        $imagesData['category'] = $category;
                        $imagesData['name'] = $upload_result['file_name'];
                        $imagesData['title'] = $media->alt_text??'';
                        $imagesData['sort_order'] = 0;
                        $imagesData['is_default'] = 0;
                        $isSaved = CommonImage::create($imagesData);

                        if($isSaved) {
                            $storage = Storage::disk('public');
                            $viewData = [];
                            $viewData['image'] = $isSaved;
                            $viewData['storage'] = $storage;
                            $viewData['path'] = $path;
                            $viewData['thumb_path'] = $thumb_path;
                            $viewData['module'] = $tbl_name;
                            //prd($viewData);
                            $imgRow = view('admin.images._row', $viewData)->render();
                            $response['success'] = true;
                            $response['imgRow'] = $imgRow;
                            $status_code = 200;

                            $module = $tbl_name;
                            $module_id = $id_to_add;
                            if(!empty($module) && !empty($module_id)) {
                                CustomHelper::setModuleDefaultImage($module,$module_id);
                            }
                        }
                    }
                }
            }
            return response()->json($response, $status_code);
        }
    }

    /* ajax_delete_images */
    public function ajaxDeleteImages(Request $request) {
        //prd($request->toArray());
        $response = [];
        $response['success'] = false;
        $img = (isset($request->img))?$request->img:'';
        if(!empty($img)) {
            $storage = Storage::disk('public');
            $path = $this->imgPath;
            $thumb_path = $this->thumbPath;
            $is_deleted = '';
            if($storage->exists($path.$img)) {
                $is_deleted = $storage->delete($path.$img);
            }
            if($storage->exists($thumb_path.$img)) {
                $is_deleted = $storage->delete($thumb_path.$img);
            }
            if($is_deleted) {
                $response['success'] = true;
            }
        }
        return response()->json($response);
    }

    /* ajax_update */
    public function ajaxUpdate(Request $request) {
        // prd($request->toArray());
        $response = [];
        $response['success'] = false;
        $idArr = (isset($request->ids))?$request->ids:'';
        $titleArr = (isset($request->title))?$request->title:'';
        $category = (isset($request->category))?$request->category:'gallery';
        $categoryArr = (isset($request->category_id))?$request->category_id:'';
        $linkArr = (isset($request->link))?$request->link:'';
        $sortOrderArr = (isset($request->sort_order))?$request->sort_order:0;
        $isSaved = false;

        $module = '';
        $module_id = '';
        if(!empty($idArr) && count($idArr) > 0) {
            foreach($idArr as $id) {
                $image = CommonImage::find($id);
                if(isset($image->id) && $image->id == $id) {
                    $module = $image->tbl_name??'';
                    $module_id = $image->tbl_id??'';
                    $title = (isset($titleArr[$id]))?$titleArr[$id]:'';
                    // $category_id = (isset($categoryArr[$id]))?$categoryArr[$id]:NUll;
                    $link = (isset($linkArr[$id]))?$linkArr[$id]:'';
                    $sort_order = (isset($sortOrderArr[$id]))?$sortOrderArr[$id]:0;
                    $is_default = 0;
                    if($request->is_default==$id) {
                        $is_default = 1;
                    }
                    $image->title = $title;
                    // $image->category_id = $category_id;
                    $image->category = $category;
                    $image->link = $link;
                    $image->sort_order = $sort_order;
                    $image->is_default = $is_default;
                    //prd($image->toArray());
                    $isSaved = $image->save();
                }
            }
        }

        if($isSaved) {
            if(!empty($module) && !empty($module_id)) {
                CustomHelper::setModuleDefaultImage($module,$module_id);
            }
            $response['success'] = true;
            session()->flash('alert-success', 'Image(s) has been updated successfully.');
        }
        return response()->json($response);
    }

    /* ajax_delete */
    public function ajaxDelete(Request $request) {
        //prd($request->toArray());
        $response = [];
        $response['success'] = false;
        $id = (isset($request->id))?$request->id:0;
        if(is_numeric($id) && $id > 0) {
            $image = CommonImage::find($id);
            if(isset($image->id) && $image->id == $id) {
                $module = $image->tbl_name??'';
                $module_id = $image->tbl_id??'';

                $storage = Storage::disk('public');
                $img = $image->name;
                $path = $image->tbl_name."/";
                $thumb_path = "$path/thumb/";
                $is_deleted = $image->delete();

                if($is_deleted) {
                    if($storage->exists($path.$img)){
                        $storage->delete($path.$img);
                    }
                    if($storage->exists($thumb_path.$img)){
                        $storage->delete($thumb_path.$img);
                    }

                    if(!empty($module) && !empty($module_id)) {
                        CustomHelper::setModuleDefaultImage($module,$module_id);
                    }

                    $response['success'] = true;
                    session()->flash('alert-success', 'Image has been deleted successfully.');
                }
            }
            
        }

        return response()->json($response);
    }

/* End of controller */
}