<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductImage;
use App\Models\ColorsMaster;

use App\Models\Module;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Storage;

use App\Helpers\CustomHelper;

use Image;
use DB;

class ModuleController extends Controller{


    // private $limit;
    // private $ADMIN_ROUTE_NAME;


     public function index(Request $request){

        $data = [];

        $method = $request->method();

        if($method == 'POST'){

            $ids = ($request->ids) ? $request->ids:'';
            $modules = ($request->modules) ? $request->modules:'';

            if(!empty($modules) && count($modules) > 0){
                DB::table('modules')->update(['status'=>0]);

                foreach ($modules as $key => $val){
                    Module::where('id',$val)->update(['status'=>1]);
                }

                //http://laravelcms.ii71.com/phpartisan?cmd=config:cache

                // ==========++++CURL+++=====

                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => url('/')."phpartisan?cmd=config:cache",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                        // Set Here Your Requesred Headers
                    'Content-Type: application/json',
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                // =============================

                return back()->with('alert-success', 'Module updated successfully.');
            }
        }

        $module_data = Module::orderBy('id')->get();

        $data['module_data'] = $module_data;
        
        return view('admin.module.index', $data);
    }

    /* end of controller */
}