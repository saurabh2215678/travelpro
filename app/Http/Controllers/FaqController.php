<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CustomHelper;

use App\Models\Banner;
use App\Models\Faq;
use App\Models\FaqCategory;

use Mail;
use DB;
use Validator;

class FaqController extends Controller {

	private $limit;
    public function __construct(){
    	$this->limit = 20;
    }

    public function index(Request $request){
        $data = [];
        $testimonial = '';
        $limit = $this->limit;
        $data['meta_title'] = CustomHelper::WebsiteSettings('FAQ_META_TITLE');
        $data['meta_description'] = CustomHelper::WebsiteSettings('FAQ_META_DESCRIPTION');

        $faqs = Faq::where('status',1)->orderBy('sort_order','asc')->get();
        $data['faqs'] = $faqs;

        $banner = Banner::where('status',1)->where('slug','home-page-main-banner')->first();
        $data['banner'] = $banner;
        $data['page_title'] = 'Faqs';


        return view(config('custom.theme').'.faq', $data);
    }


     public function list(Request $request)
     {

        $data = [];
        $slug  = isset($request->slug) ? $request->slug : '';
     
        if(isset($slug) && !empty($slug))
        {
           $faq_data = FaqCategory::where('slug',$slug)->first();
           if(isset($faq_data) && !empty($faq_data)){
            $faq_category_id = $faq_data->id;

            $faqs = Faq::where(['status'=>1])->whereRaw('FIND_IN_SET("'.$faq_category_id.'", category)')->get();
 
            $faq_data = FaqCategory::where('status',1)->where('slug',$slug)->first();
            $data['faq_categories'] = $faq_data;
            
            $data['meta_title'] = (isset($faq_data->meta_title) && !empty($faq_data->meta_title)) ? $faq_data->meta_title : $faq_data->title;
            $data['meta_description'] = (isset($faq_data->meta_description) && !empty($faq_data->meta_description)) ? $faq_data->meta_description : $faq_data->title;
            $data['faqs'] = $faqs;
            $data['page_title'] = 'Faqs';
 
            return view(config('custom.theme').'.faq', $data);
           }else{

            abort(404);
           }

        }
        else
        {
         abort(404);
        }
          
     
    }
    

    /* end of controller */
        }

