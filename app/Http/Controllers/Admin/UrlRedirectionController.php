<?php
namespace App\Http\Controllers\Admin;

use App\Models\UrlRedirection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\CustomHelper;

use Validator;
use Storage;
use Image;
use DB;

class UrlRedirectionController extends Controller
{
    private $limit;
    private $page_type;
    public function __construct(){
        $this->limit = 20;
        $this->page_type = request()->segment(2);
    }

    public function url_type() {
      $url_type = 0;
      if(request()->segment(2) == 'seo_url_redirection') {
        $url_type = 1;
      }
      return $url_type;
    }

    public function index(Request $request) {
        $data = [];
        $limit = $this->limit;
        $query = UrlRedirection::orderBy('id','desc');
        $query->where('url_type',$this->url_type());
        if($request->source_url) {
          $query->where('source_url','like','%'.$request->source_url.'%');
        }
        if($request->destination_url) {
          $query->where('destination_url','like','%'.$request->destination_url.'%');
        }
        if($request->show != '') {
          $query->where('show',$request->show);
        }
        if(!empty($request->status_code)) {
          $query->where('status_code',$request->status_code);
        }

        $results = $query->paginate($limit);
        $data['results'] = $results;
        $data['page_type'] = $this->page_type;
        $page_heading = "Manage URL Redirection";
        $data["page_heading"] = $page_heading;
        return view('admin.url_redirection.index', $data);
    }

    public function add(Request $request) {

        $id = (isset($request->id))?$request->id:0;
        $urlredirection = '';
        $title = 'Add URL Redirection';
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;

        if(is_numeric($id) && $id > 0) {
            $urlredirection = UrlRedirection::find($id);
            $title = 'Edit URL Redirection';
        }
        if($request->method() == 'POST' || $request->method() == 'post') {
          $validation_msg = [];
          $rules['source_url'] = 'nullable|unique:seo_url_redirection,source_url,'.$id;
          /*if($request->status_code == 410) {
            $rules['destination_url'] = 'nullable';
          } else {
            $rules['destination_url'] = 'required';
          }*/
          $rules['status_code'] = 'required';
          $validation_msg['source_url.required'] = 'The source field is required.';
          //$validation_msg['destination_url.required'] = 'The destination field is required.';
          $validation_msg['status_code.required'] = 'The status code field is required.';
          $this->validate($request, $rules,$validation_msg);

          $req_data = [];
          $req_data = $request->except(['_token','back_url','id']);
          $req_data['show'] = isset($request->show)?$request->show:0;
          $req_data['destination_url'] = isset($request->destination_url)?$request->destination_url:'';

          if(!empty($urlredirection)) {
            $isSaved = UrlRedirection::where('id', $id)->update($req_data);
            $urlredirection_id = $id;
            $msg = "The URL Redirection has been updated successfully.";
          } else {
            $req_data['url_type'] = $this->url_type();
            $isSaved = UrlRedirection::create($req_data);
            $urlredirection_id = $isSaved->id;
            $msg = "The URL Redirection has been added successfully.";
          }

          if ($isSaved) {
            $new_data = DB::table('seo_url_redirection')->where('id',$urlredirection_id)->first();
            $name = !empty($new_data->name)?$new_data->name:'';
            $new_data = (array) $new_data;
            $new_data = json_encode($new_data);

            $module_id = $urlredirection_id;
            $module_desc = $name;

            $params['user_id'] = $user_id;
            $params['user_name'] = $user_name;
            $params['module'] = 'URL Redirection';
            $params['module_desc'] = $module_desc;
            $params['module_id'] = $module_id;
            $params['sub_module'] = "";
            $params['sub_module_id'] = 0;
            $params['sub_sub_module'] = "";
            $params['sub_sub_module_id'] = 0;
            $params['data_before_action'] = $new_data;
            $params['action'] = (!empty($id)) ? "Update" : "Add";
            CustomHelper::RecordActivityLog($params);

            return redirect(url(CustomHelper::getAdminRouteName().'/'.$this->page_type))->with('alert-success', $msg);
          } else {
            return back()->with('alert-danger', 'The URL Redirection cannot be added, please try again or contact the administrator.');
          }
        }
        $data = [];
        $data['page_heading'] = $title;
        $data['urlredirection'] = $urlredirection;
        $data['id'] = $id;
        return view('admin.url_redirection.form', $data);
      }

    public function delete(Request $request)
    {
      $id = $request->id;
      $method = $request->method();
      $is_deleted = 0;

      if($method=="POST")
      {
        try{

          if(is_numeric($id) && $id > 0)
          {
            $urlredirection = UrlRedirection::find($id);
            if(!empty($urlredirection))
            {
              $name = !empty($urlredirection->name)?$urlredirection->name:'';
              $new_data = (array) $urlredirection->toArray();
              $new_data = json_encode($new_data);

              $module_id = $id;
              $module_desc = $name;

              $params = [];
              $params['user_id'] = auth()->user()->id;
              $params['user_name'] = auth()->user()->name;
              $params['module'] = 'URL Redirection';
              $params['module_desc'] = $module_desc;
              $params['module_id'] = $module_id;
              $params['sub_module'] = "";
              $params['sub_module_id'] = 0;
              $params['sub_sub_module'] = "";
              $params['sub_sub_module_id'] = 0;
              $params['data_before_action'] = $new_data;
              $params['action'] = "Delete";
              CustomHelper::RecordActivityLog($params);

              $is_deleted = $urlredirection->delete();
            }
          }
        }
        catch(\Illuminate\Database\QueryException $ex)
        {
          if($ex->getCode() === '23000')
          {
            // return redirect(url(CustomHelper::getAdminRouteName().'/features/add'))->with('alert-danger', 'There is already data exist in Other module');

            return redirect(url(CustomHelper::getAdminRouteName().'/'.$this->page_type))->with('alert-danger', 'The URL Redirection cannot be deleted, There is already data exist in Other module ');
          }
        }
      }

      if($is_deleted)
      {
        CustomHelper::cache_flush('seo_url_redirection');

        return redirect(url(CustomHelper::getAdminRouteName().'/'.$this->page_type))->with('alert-success', 'The URL Redirection has been deleted successfully.');
      }
      else
      {
        return redirect(route('admin.'.$this->page_type))->with('alert-danger', 'The URL Redirection cannot be deleted, please try again or contact the administrator.');
      }
    }
    /* end of controller */
}