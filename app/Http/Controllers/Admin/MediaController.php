<?php
namespace App\Http\Controllers\Admin;
use App\Models\MediaImage;
use App\Models\Testimonial;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Helpers\CustomHelper;
use Illuminate\Validation\Rule;
use Validator;
use Storage;
use Session;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use DB;
class MediaController extends Controller {

    private $limit;
    private $ADMIN_ROUTE_NAME;
    private static $_invalidCharacters = array('*', ':', '/', '\\', '?', '[', ']');

    public function __construct() {
        $this->limit = 50;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request) {
        $data = [];
        $data['page_heading'] = 'Media';
        $mediaObj = MediaImage::query();
        $type = "";
        $keyword = "";
        if(isset($request->type) && !empty($request->type)) {
            $type = $request->type;
            $mediaObj->where('mime_type','like','%'.$type.'%');
        }
        if(isset($request->keyword) && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $mediaObj->where(function($q1) use($keyword){
                $q1->where('alt_text','like','%'.$keyword.'%');
                $q1->orWhere('caption','like','%'.$keyword.'%');
            });
        }
        $data['medias'] = $mediaObj->orderBy('created_at','DESC')->paginate($this->limit);
        $data['action'] = $request->action??'';
        $data['type'] = $type??'';
        $data['keyword'] = $keyword??'';
        return view('admin.media.index', $data);
    }

    public function pop(Request $request) {
        $data = [];
        $keyword = (isset($request->keyword))?$request->keyword:'';
        $data['page_heading'] = 'Media';
        $mediaObj = MediaImage::query();
        $type = "";
        if(isset($request->type) && !empty($request->type)) {
            $type = $request->type;
            $mediaObj->where('mime_type','like','%'.$type.'%');
        }
        if(isset($request->keyword) && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $mediaObj->where(function($q1) use($keyword){
                $q1->where('alt_text','like','%'.$keyword.'%');
                $q1->orWhere('caption','like','%'.$keyword.'%');
            });
        }
        $data['medias'] = $mediaObj->orderBy('created_at','DESC')->paginate($this->limit);
        $data['action'] = $request->action??'';
        $data['type'] = $type??'';
        $data['keyword'] = $keyword??'';
        return view('admin.media.media_frame', $data);
    }

    /*public function store(Request $request)
    {
        $path = storage_path('tmp/uploads');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);
        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }*/

    public function store(Request $request) {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            // file not uploaded
        }

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName = $fileName.'-'.date("dmyhis").'.'.$extension; // a unique file name
            $fileName = CustomHelper::sanitizeUploadFile($fileName); // a sanitize file name            

            $disk = Storage::disk(config('filesystems.default'));
            $path = $disk->putFileAs('tmp', $file, $fileName);

            // delete chunked file
            unlink($file->getPathname());

            $data['caption'] = str_replace('.'.$extension, '', $file->getClientOriginalName());
            $data['alt_text'] = $data['caption'];
            $data['mime_type'] = "";
            $media = MediaImage::create($data);
            $media_id = $media->id??'';
            if($media){
                $media->addMedia(storage_path('app/tmp/' . $fileName))->toMediaCollection('media');
                $mediaObj = $media->getFirstMedia('media');
                $mediaMime = $mediaObj->mime_type;

                $media->mime_type = $mediaMime;
                $media->save();
            }
            //=============Activity Logs=====================

                $user_id = auth()->user()->id;
                $user_name = auth()->user()->name;

                $new_data = DB::table('media_manager')->where('id',$media_id)->first();
                $module_desc =  !empty($new_data->caption)?$new_data->caption:'';
                $new_data =(array) $new_data;
                $new_data = json_encode($new_data);

                $module_id = $media_id;

                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Media Gallery ';
                $params['module_desc'] = $module_desc;
                $params['module_id'] = $module_id;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data;
                //$params['action'] = (!empty($media_id)) ? "Update" : "Add";
                $params['action'] =  "Add";

                CustomHelper::RecordActivityLog($params);

            //=============Activity Logs=====================
            
            Session::flash('alert-success', 'Media Added successfully.'); 

            return [
                'status' => 'done',
                'filename' => $fileName
            ];
        }

        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }

    public function upload(Request $request)
    {
        foreach ($request->input('document', []) as $file) {
            $data['caption'] = $file;
            $data['alt_text'] = $data['caption']??'';
            $media = MediaImage::create($data);
            $media->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('media');
            //$media->addMedia(storage_path('tmp/uploads/Airbus_Pleiades_50cm_8bit_RGB_Yogyakarta.jpg'))->toMediaCollection('media');
        }

        return redirect()->route($this->ADMIN_ROUTE_NAME.'.media.index')->with('alert-success', 'Media Added successfully.');
    }

    /* ajax_delete_images */
    public function delete(Request $request){

        $response = [];
        $response['success'] = false;
        $user_id = auth()->user()->id?? 0;
        $user_name = auth()->user()->name??'';
        $id = (isset($request->id))?$request->id:'';
        if(!empty($id)){

            $is_deleted = '';
            $media = MediaImage::find($id);
            
            $new_data = DB::table('media_manager')->where('id',$id)->first();
            $module_desc =  !empty($new_data->caption)?$new_data->caption:'';
            $new_data =(array) $new_data;
            $new_data = json_encode($new_data);

            if(!empty($media)){
                $is_deleted = $media->delete();
            }
            if($is_deleted){

                $params = [];
                $params['user_id'] = $user_id;
                $params['user_name'] = $user_name;
                $params['module'] = 'Media Gallery';
                $params['module_desc'] = $module_desc??'';
                $params['module_id'] = $id??0;
                $params['sub_module'] = "";
                $params['sub_module_id'] = 0;
                $params['sub_sub_module'] = "";
                $params['sub_sub_module_id'] = 0;
                $params['data_after_action'] = $new_data??'';
                $params['action'] = "Delete";

                CustomHelper::RecordActivityLog($params);

                return redirect()->route($this->ADMIN_ROUTE_NAME.'.media.index')->with('alert-success', 'Media deleted successfully.');
            }
        }

        return redirect()->back()->with('alert-dander', 'Something went wrong.');
    }

    public function getMediaAttachmentView(Request $request){

        $response = [];
        $response['success'] = false;
        $response['pageView'] = "";

        $mediaId = (isset($request->media_id))?$request->media_id:'';

        if(!empty($mediaId)){
            $media = MediaImage::find($mediaId);
            if(!empty($media)){
                $data['media'] = $media;
                $data['mediaDetails'] = $media->getFirstMedia('media');
                $attacmentDetails = view('admin.media.media_attachment_view',$data)->render();

                $response['success'] = true;
                $response['pageView'] = $attacmentDetails;
            }else{
                $response['success'] = false;
                $response['pageView'] = "";
            }
        }

        return response()->json($response);
    }

    /* Delete Media By ajax */
    public function ajaxMediaDelete(Request $request){
        $response = [];
        $response['success'] = false;

        $media_id = (isset($request->media_id))?$request->media_id:'';
        $media = MediaImage::find($media_id);

        if(!empty($media)){
            $media->delete();

            $response['success'] = true;
            session()->flash('alert-success', 'Media(s) has been deleted successfully.');

        }
        return response()->json($response);
    }

    /* ajax_update */
    public function ajaxUpdate(Request $request){
        $response = [];
        $response['success'] = false;
        $media_id = $request->media_id??'';
        if($media_id) {
            $media = MediaImage::find($media_id);
            if($media) {
                $field = $request->field??'';
                $value = $request->value??'';
                if($field) {
                    $media->$field = $value;
                    $media->save();
                }
                $response['success'] = true;
                $response['message'] = '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Media has been updated successfully.</div>';
                // session()->flash('alert-success', 'Media(s) has been deleted successfully.');
            }
        }
        return response()->json($response);
    }

/* End of controller */
}