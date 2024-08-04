<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\AirportCodesMaster;
use App\Models\AirlineMaster;
use App\Helpers\CustomHelper;
use Session;
use Exception;

class AjaxController extends Controller {

    public function index(Request $request) {
        return "ok";
    }

    public function ajax_more_segments(Request $request) {
        $response = [];
        $response['success'] = false;
        if($request->method() == 'POST') {
            $data = [];
            $data['counter'] = $request->counter??0;
            $html = view('admin.airline_route._segment',$data)->render();
            $response['success'] = true;
            $response['html'] = $html;
            return response()->json($response);
        }
    }

    public function searchAirports(Request $request) {
        $response = [];
        $response['success'] = false;
        if($request->method() == 'POST') {
            $action = $request->action??'';
            $query = AirportCodesMaster::select('id','code','name','citycode','city','country','countrycode','sort_order','featured','status','created_at','updated_at')->where('status',1);
            if($request->term) {
                $search_text = addslashes($request->term);
                $query->where(function($q1) use($search_text) {
                    $q1->where('code','like',$search_text.'%');
                    $q1->orWhere('name','like',$search_text.'%');
                    $q1->orWhere('citycode','like',$search_text.'%');
                    $q1->orWhere('city','like',$search_text.'%');
                });
                $query->orderByRaw("IF(`code` = '$search_text', 1, 0)  DESC");
            }
            $result = $query->orderBy('featured', 'desc')->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->limit(15)->get();
            $items_arr = [];
            if($result) {
                foreach($result as $row) {
                    if($action=='flight_route') {
                        $row_arr = [
                            'id' => $row->code,
                            'text' => $row->city.' ('.$row->code.') - '.$row->name
                        ];
                    } else {
                        $row_arr = $row->toArray();
                    }
                    $items_arr[] = $row_arr;                    
                }
                $response['success'] = true;
            }
            $response['items'] = $items_arr;
            return response()->json($response);
        }
    }

    public function ajax_airline(Request $request) {
        $response = [];
        $response['success'] = false;
        if($request->method() == 'POST') {
            $query = AirlineMaster::where('status',1);
            $value = $request->term??'';
            if($value) {
                $query->where(function($q1) use($value) {
                    $q1->where('code','like',$value.'%');
                    $q1->orWhere('name','like','%'.$value.'%');
                });
                $query->orderByRaw("IF(`code` = '$value', 1, 0)  DESC");
            }
            $result = $query->orderBy('featured','desc')->orderBy('sort_order','asc')->orderBy('name','asc')->take(15)->get();
            $items_arr = [];
            if($result) {
                foreach($result as $row) {
                    $items_arr[] = [
                        'id' => $row->code,
                        'text' => $row->name
                    ];
                }
            }
            $response['success'] = true;
            $response['items'] = $items_arr;
            return response()->json($response);
        }
    }


}