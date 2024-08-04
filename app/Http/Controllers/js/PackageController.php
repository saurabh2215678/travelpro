<?php
namespace App\Http\Controllers\js;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Testimonial;
use App\Models\Package;
use App\Models\BannerImageGallery;
use App\Models\ThemeCategories;
use App\Models\Destination;
use App\Models\Accommodation;
use App\Models\AccommodationFacility;
use App\Models\PackagePriceInfo;
use App\Models\PackageInclusion;
use App\Models\SeoMetaTag;
use App\Models\PackageAccommodation;
use App\Models\PackageInventory;
use App\Models\Faq;
use App\Models\AccommodationFeature;
use App\Models\UrlRedirection;
use App\Models\Flag;
use App\Helpers\CustomHelper;
use Inertia\Inertia;
use DB;
use File;
use Response;
use Storage;
use Validator;

class PackageController extends Controller {

    private $limit;
    private $theme;

    public function __construct() {
        $this->limit = CustomHelper::WebsiteSettings('FRONT_PAGE_LIMIT');
        $this->theme = config('custom.themejs');
    }

    public function index(Request $request) {
        $currentUrl = \Request::path();
        $url_redirection = UrlRedirection::where('source_url',$currentUrl)->where('show',1)->first();
        if(!empty($url_redirection) && !empty($url_redirection->destination_url)) {
            $redirectUrl = url($url_redirection->destination_url);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirectUrl);
            exit();
        }

        $data = [];
        $seo_data = [];
        $segments1 = $request->segment(1);
        $segments2 = $request->segment(2);
        $query = SeoMetaTag::where('status',1);
        $query->where(function($q1) use($segments1) {
            $q1->where('page_url',$segments1);
            $q1->orWhere(function($q2) use($segments1) {
                $q2->where('identifier','tour_category');
                $q2->where('page_detail_url',$segments1);
            });
        });
        $seo_tags = $query->first();

        // search-packages and activities List FAQs
        if($segments1 == 'search-packages'){
            $seo_meta_tags_id = $seo_tags->id??0;
            $faq_modules = Faq::where('tbl_name','=','seo_meta_tags')->where('status',1)->where('tbl_id',$seo_meta_tags_id)->orderBy("sort_order", "ASC");
            $faqs = $faq_modules->take(40)->get();
            if($faqs) {
                $faqs_arr = [];
                foreach($faqs as $faq) {
                    $faqs_arr[] = $faq->toArray();
                }
                $data['faqs'] = $faqs_arr;
            }
            View::share('faqs', $faqs);
        }
        if($segments1 == 'activities'){
            $seo_meta_tags_id = $seo_tags->id??0;
            $faq_modules_activity = Faq::where('tbl_name','=','seo_meta_tags')->where('status',1)->where('tbl_id',$seo_meta_tags_id)->orderBy("sort_order", "ASC");
            $faqs = $faq_modules_activity->take(40)->get();
            if($faqs) {
                $faqs_arr = [];
                foreach($faqs as $faq) {
                    $faqs_arr[] = $faq->toArray();
                }
                $data['faqs'] = $faqs_arr;
            }
            View::share('faqs', $faqs);
        }

        $banner_image = asset(config('custom.assets').'/images/default_common_banner.jpg');
        $identifier = '';
        if(!empty($seo_tags)) {
            $identifier = $seo_tags->identifier;
            $seo_data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
            $seo_data['page_url_label'] = $seo_tags->page_url_label??'';
            $seo_data['page_brief'] = $seo_tags->page_brief??'';
            $seo_data['page_description'] = $seo_tags->page_description??'';
            $seo_data['page_url'] = $seo_tags->page_url??'';
            $seo_data['page_detail_url'] = $seo_tags->page_detail_url??'';
            $seo_data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
            $seo_data['meta_description'] = $seo_tags->meta_description??'';
            $seo_data['meta_keyword'] = $seo_tags->meta_keyword??'';
            $seo_data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
            if($seo_tags->image) {
                $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
            }
        }

        if($request->slug) {
            $slug = $request->slug??'';
            if($slug) {
                $slug_start = substr($slug, 0, 1);
                if($slug_start == 'd') {
                    $destination_id = str_replace($slug_start, '', $slug);
                    if(!empty($destination_id) && is_numeric($destination_id)) {
                        $query = Destination::where('is_city',0)->where('status',1)->where('id',$destination_id);
                        $query->where(function($query1){
                            $query1->where('is_deleted',0);
                            $query1->orWhereNull('is_deleted');
                        });
                        $destination = $query->first();
                        if($destination) {
                            $params = [];
                            $params['slug'] = $destination->slug;
                            $params['search_text'] = $request->text??'';
                            $dep = $request->dep??'';
                            if($dep) {
                                $params['dep'] = $dep;
                            }
                            $sno_of_days = $request->sno_of_days??'';
                            if($sno_of_days) {
                                $params['sno_of_days'] = $sno_of_days;
                            }
                            $smonth = $request->smonth??'';
                            if($smonth) {
                                $params['smonth'] = $smonth;
                            }
                            if($identifier == 'activity_listing') {
                                $actual_link = route('tourActivities',$params);
                            } else {
                                $actual_link = route('tourPackages',$params);
                            }
                            return redirect($actual_link);
                        }
                    }
                }else if($slug_start == 's') {
                    $sub_destination_id = str_replace($slug_start.'d', '', $slug);
                    if(!empty($sub_destination_id) && is_numeric($sub_destination_id)) {
                        $query = Destination::where('is_city',0)->where('status',1)->where('id',$sub_destination_id);
                        $query->where(function($query1){
                            $query1->where('is_deleted',0);
                            $query1->orWhereNull('is_deleted');
                        });
                        $sub_destination = $query->first();
                        if($sub_destination) {
                            $params = [];
                            $params['slug'] = $sub_destination->slug;
                            $params['search_text'] = $request->text??'';
                            $dep = $request->dep??'';
                            if($dep) {
                                $params['dep'] = $dep;
                            }
                            $sno_of_days = $request->sno_of_days??'';
                            if($sno_of_days) {
                                $params['sno_of_days'] = $sno_of_days;
                            }
                            $smonth = $request->smonth??'';
                            if($smonth) {
                                $params['smonth'] = $smonth;
                            }
                            if($identifier == 'activity_listing') {
                                $actual_link = route('tourActivities',$params);
                            } else {
                                $actual_link = route('tourPackages',$params);
                            }
                            return redirect($actual_link);
                        }
                    }
                }
                else if($slug_start == 'c') {
                    $theme_id = str_replace($slug_start, '', $slug);
                    if(!empty($theme_id) && is_numeric($theme_id)) {
                        $query = ThemeCategories::where('status',1)->where('id',$theme_id);
                        $query->where(function($query1){
                            $query1->where('is_deleted',0);
                            $query1->orWhereNull('is_deleted');
                        });
                        $theme = $query->first();
                        if($theme) {
                            $params = [];
                            $params['tour_category'] = $theme->slug;
                            $params['search_text'] = $request->text??'';
                            $dep = $request->dep??'';
                            if($dep) {
                                $params['dep'] = $dep;
                            }
                            $sno_of_days = $request->sno_of_days??'';
                            if($sno_of_days) {
                                $params['sno_of_days'] = $sno_of_days;
                            }
                            $smonth = $request->smonth??'';
                            if($smonth) {
                                $params['smonth'] = $smonth;
                            }
                            $actual_link = route('tourCategoryDetail',$params);
                            return redirect($actual_link);
                        }
                    }
                } else if($slug_start == 'p') {
                    $package_id = str_replace($slug_start, '', $slug);
                    if(!empty($package_id) && is_numeric($package_id)) {
                        $query = Package::with('packagePrices','packageCategories','packagePriceCategory')->selectRaw('packages.*')->where('packages.status',1);
                        $query->where(function($query1){
                            $query1->where('packages.is_deleted',0);
                            $query1->orWhereNull('packages.is_deleted');
                        });
                        $query->where('id',$package_id);
                        $package = $query->first();
                        if($package) {
                            $packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';
                            $actual_link = route($packageDetailName,['slug'=>$package->slug]);
                            return redirect($actual_link);
                        }
                    }
                }
            }
        }

        $limit = $this->limit;
        $DestinationId = '';
        $getDestination = isset($request->package) ? $request->package : '';

        $is_activity = 0;
        switch ($identifier) {
            case 'activity_listing':
                    $is_activity = 1;
                break;
            case 'tour_category':
                    $tour_category_slug = $request->tour_category;
                    $tour_category = ThemeCategories::where('status',1)->where('slug',$tour_category_slug)->first();
                    //prd($tour_category);
                    if($tour_category) {
                        if($tour_category->identifier == 'activity'){
                            $is_activity = 1;
                        }
                        $storage = Storage::disk('public');
                        $path = "themes/";
                        $theme_id = $tour_category->id??0;
                        $faq_theme = Faq::where('tbl_name','=','themes')->where('status',1)->where('tbl_id',$theme_id)->orderBy("sort_order", "ASC");
                        $faqs = $faq_theme->take(40)->get();
                        if($faqs) {
                            $faqs_arr = [];
                            foreach($faqs as $faq) {
                                $faqs_arr[] = $faq->toArray();
                            }
                            $data['faqs'] = $faqs_arr;
                        }
                        $banners = $tour_category->themeBanners??'';
                        $banners_arr = [];
                        $image_arr = [];
                        if($banners) {
                            foreach($banners as $banner) {
                                $bannerThumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
                                if($banner->name && !in_array($banner->name, $image_arr)) {
                                    $image_arr[] = $banner->name;
                                    if($storage->exists($path.$banner->name)){
                                        $bannerSrc = asset('/storage/'.$path.$banner->name);
                                        $bannerThumbSrc = asset('/storage/'.$path.'thumb/'.$banner->name);
                                    } else {
                                        $bannerSrc = $bannerThumbSrc;
                                        $bannerThumbSrc = $bannerThumbSrc;
                                    }
                                    $banners_arr[] = [
                                        'title' => $banner->title,
                                        'src' => $bannerSrc,
                                        'thumbSrc' => $bannerThumbSrc,
                                    ];
                                }
                            }
                        }
                        // prd($tour_category->toArray());
                        $seo_data['banners'] = $banners_arr??[];
                        View::share('faqs', $faqs);
                        $data['tour_category_id'] = $tour_category->id??0;
                        $seo_data['page_title'] = $tour_category->title??'';
                        if(config('custom.theme_name') == 'overlandescape'){
                        $seo_data['page_title'] = $tour_category->title.' '."in Ladakh" ??'';
                        }
                        $seo_data['page_brief'] = $tour_category->brief??'';
                        $seo_data['page_description'] = $tour_category->description??'';
                        $seo_data['meta_title'] = $tour_category->page_title??'';
                        $seo_data['meta_description'] = $tour_category->page_description??'';
                        $seo_data['meta_keyword'] = $tour_category->page_keywords??'';
                        $seo_data['search_text'] = $request->search_text??'';
                        if($tour_category->image) {
                            $banner_image = url('/storage/themes/'.$tour_category->image);
                        }
                    }else{
                        abort(404);
                    }
                break;
            default:
                break;
        }

        $query = Package::with('packagePrices','packageCategories','packagePriceCategory')->selectRaw('packages.*')->where('packages.status',1);
        $query->where(function($query1){
            $query1->where('packages.is_deleted',0);
            $query1->orWhereNull('packages.is_deleted');
        });

        if($segments1 == 'tour-packages') {
            $destination_slug = $segments2;
            if($destination_slug) {
                $query->whereHas('packageDestination',function($q1) use($destination_slug) {
                    $q1->where('slug',$destination_slug);
                });
                $q2 = Destination::where('status',1);                
                $q2->where(function($q3){
                    $q3->where('is_deleted',0);
                    $q3->orWhereNull('is_deleted');
                });
                $q2->where('slug',$destination_slug);
                $destination = $q2->first();
                if($destination) {
                    $seo_data['page_title'] = $destination->name??'';
                    if(config('custom.theme_name') == 'andamanisland'){
                        $seo_data['page_title'] = "Tour Packages in ".$destination->name ??'';
                    }if(config('custom.theme_name') == 'overlandescape'){
                        $seo_data['page_title'] = $destination->name.' '."Tour Packages" ??'';
                    }
                    $seo_data['dest_id'] = $destination->id??0;
                    $seo_data['page_brief'] = $destination->brief??'';
                    if(!empty($destination->description)){    
                        $seo_data['page_description'] = $destination->description??'';
                    }
                    $seo_data['meta_title'] = $destination->package_meta_title??'';
                    $seo_data['meta_description'] = $destination->package_meta_description??'';
                    $seo_data['meta_keyword'] = $destination->package_meta_keyword??'';

                    $destination_id = $destination->id??0;
                    $faq_tour_package = Faq::where('tbl_name','=','destination_packages')->where('status',1)->where('tbl_id',$destination_id)->orderBy("sort_order", "ASC");
                    $faqs = $faq_tour_package->take(40)->get()->toArray();
                    $data['faqs'] = $faqs;
                    
                    View::share('faqs', $faqs);

                    //$data['search_text'] = $request->search_text??'';
                    /*if(isset($destination->destinationBanners) && !empty($destination->destinationBanners)){
                        $banner_image_name = $destination->destinationBanners[0]->name ?? '';
                        if(isset($banner_image_name) && !empty($banner_image_name)){
                            $banner_image = asset('/storage/destinations/'.$banner_image_name);
                        }
                    }*/
                    // if($destination->image) {
                    //     $banner_image = url('/storage/destinations/'.$destination->image);
                    // }
                }else{
                    abort(404);
                }
            }
        } else if($segments1 == 'tour-activities') {
            $is_activity = 1;
            $destination_slug = $segments2;
            if($destination_slug) {
                $query->whereHas('packageDestination',function($q1) use($destination_slug) {
                    $q1->where('slug',$destination_slug);
                });
                $q2 = Destination::where('status',1);                
                $q2->where(function($q3){
                    $q3->where('is_deleted',0);
                    $q3->orWhereNull('is_deleted');
                });
                $q2->where('slug',$destination_slug);
                $destination = $q2->first();
                if($destination) {
                    $destination_id = $destination->id??0;
                    $seo_data['page_title'] = $destination->name??'';
                    if(config('custom.theme_name') == 'andamanisland'){
                        $seo_data['page_title'] = "Adventure Activities in ".$destination->name ??'';
                    }if(config('custom.theme_name') == 'overlandescape'){
                        $seo_data['page_title'] = "Adventure Activities in ".$destination->name ??'';
                    }
                    $seo_data['page_brief'] = $destination->brief??'';
                    $seo_data['page_description'] = '';
                    $seo_data['meta_title'] = $destination->activity_meta_title??'';
                    $seo_data['meta_description'] = $destination->activity_meta_description??'';
                    $seo_data['meta_keyword'] = $destination->activity_meta_keyword??'';

                    $faq_tour_activity = Faq::where('tbl_name','=','destination_activity')->where('status',1)->where('tbl_id',$destination_id)->orderBy("sort_order", "ASC");
                    $faqs = $faq_tour_activity->take(40)->get()->toArray();
                    $data['faqs'] = $faqs;
                    
                    View::share('faqs', $faqs); 

                    /*if(isset($destination->destinationBanners) && !empty($destination->destinationBanners)){
                        $banner_image_name = $destination->destinationBanners[0]->name ?? '';
                        if(isset($banner_image_name) && !empty($banner_image_name)){
                            $banner_image = asset('/storage/destinations/'.$banner_image_name);
                        }
                    }*/
                }else{
                    abort(404);
                }
            }            
        }

        $package_arr = [];
        $budgetdata = Customhelper::budgetDataArr();
        $budgetList = [];
        foreach ($budgetdata as $key => $budget) {
            $budgetList[] = array('key' => $key , 'value'=>$budget );
        }
        $data['budgetList'] = $budgetList;

        $identifier = "package";
        if($is_activity == 1){
           $identifier = "activity";
        }
        $themes = ThemeCategories::where('status',1)->where('identifier',$identifier)->get();
        $data['themes'] = $themes;
        //prd($data['themes']);

        $destinations = Destination::where('is_city',0)->where('status',1)->where('parent_id',0)->orderBy('featured','desc')->take(20)->get();
        $data['destinations'] = $destinations;
        $seo_data['banner_image'] = $banner_image;
        $data['seo_data'] = $seo_data;
        //prd($data['seo_data']);
        View::share('seo_data', $seo_data);
        if($is_activity==1) {
            $data['data'] = 'activity';
            $params = [];
            $search_data = CustomHelper::getSearchData('activity',$params);
            $search_data['module'] = 'Activity';
            $data['search_data'] = $search_data;
            return Inertia::render('activity/Listing', $data);
        } else {
            $data['data'] = 'package';
            $params = [];
            $search_data = CustomHelper::getSearchData('package',$params);
            $search_data['module'] = 'Package';
            $data['search_data'] = $search_data;
            return Inertia::render('packages/Listing', $data);
        }
    }

    public function ajaxSearchPackage(Request $request) {
        // prd($request->toArray());
        $data = [];
        $response = [];
        $seo_data = [];
        $response['success'] = false;
        $response['message'] = '';
        //$limit = $this->limit;
        $featured = $request->featured??0;
        $limit = $request->limit??25;
        $segments1 = $request->segments1??'search-packages';
        $segments2 = $request->segments2??'';
        if($request->theme_category_slug) {
            $seo_tags = SeoMetaTag::where(['identifier'=>'tour_category','status'=>1])->first();
            if($seo_tags) {
                $segments1 = $seo_tags->page_url??'';
                $segments2 = $request->theme_category_slug??'';
            }
        }
        $query = SeoMetaTag::where('status',1);
        $query->where(function($q1) use($segments1) {
            $q1->where('page_url',$segments1);
            $q1->orWhere(function($q2) use($segments1) {
                $q2->where('identifier','tour_category');
                $q2->where('page_detail_url',$segments1);
            });
        });
        $seo_tags = $query->first();
        $banner_image = '';
        $identifier = '';
        if(!empty($seo_tags)) {
            $identifier = $seo_tags->identifier;
            $seo_data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
            $seo_data['page_url_label'] = $seo_tags->page_url_label??'';
            $seo_data['page_brief'] = $seo_tags->page_brief??'';
            $seo_data['page_description'] = $seo_tags->page_description??'';
            $seo_data['page_url'] = $seo_tags->page_url??'';
            $seo_data['page_detail_url'] = $seo_tags->page_detail_url??'';
            $seo_data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
            $seo_data['meta_description'] = $seo_tags->meta_description??'';
            $seo_data['meta_keyword'] = $seo_tags->meta_keyword??'';
            $seo_data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
            if($seo_tags->image) {
                $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
            }
        }
        $banner_image = '';
        //$limit = $this->limit;
        $DestinationId = '';
        $getDestination = isset($request->package) ? $request->package : '';
        $type = isset($request->type) ? $request->type : 'Package';
        $flag = $request->flag??'';
        $FlagTitle = '';
        $FlagBrief = '';
        if($request->flag_slug || ($flag && !is_numeric($flag))) {
            if($flag) {
                $flag_slug = $flag;
            } else {
                $flag_slug = $request->flag_slug;
            }
            $flagData = Flag::where('slug',$flag_slug)->where('status',1)->first();
            if($flagData) {
                $flag = $flagData->id;
                $flagData = [
                    'slug' => $flagData->slug,
                    'title' => $flagData->title,
                    'brief' => $flagData->brief,
                ];
                $data['flagData'] = $flagData;
            } else {
                $flag = '-1';
            }
        }
        $withPath = '';
        $query = Package::with('packagePrices','packageCategories','packagePriceCategory','packageDestination')->selectRaw('packages.*')->where('packages.status',1);
        $query->where(function($query1){
            $query1->where('packages.is_deleted',0);
            $query1->orWhereNull('packages.is_deleted');
        });

        $is_activity = $request->is_activity??0;
        if($type == 'Activity'){
            $is_activity = 1;
        }
        switch ($identifier) {
            case 'activity_listing':
                    $is_activity = 1;
                break;
            case 'tour_category':
                    $tour_category_slug = $segments2;
                    $withPath = $seo_data['page_detail_url'].'/'.$tour_category_slug;
                    $query->whereHas('packageCategories',function($q1) use($tour_category_slug) {
                        $q1->where('slug',$tour_category_slug);
                    });
                    $tour_category = ThemeCategories::where('status',1)->where('slug',$tour_category_slug)->first();
                    if($tour_category) {
                        if($tour_category->identifier == 'activity'){
                            $is_activity = 1;
                        }
                        $seo_data['tour_category_id'] = $tour_category->id??0;
                        $seo_data['page_title'] = $tour_category->title??'';
                        $seo_data['page_brief'] = $tour_category->brief??'';
                        $seo_data['page_description'] = $tour_category->description??'';
                        $seo_data['meta_title'] = $tour_category->page_title??'';
                        $seo_data['meta_description'] = $tour_category->page_description??'';
                        $seo_data['meta_keyword'] = $tour_category->page_keywords??'';
                        $data['search_text'] = $request->search_text??'';
                        if($tour_category->image) {
                            // $banner_image = url('/storage/themes/'.$tour_category->image);
                        }
                    }
                break;
            default:
                break;
        }

        if($segments1 == 'tour-packages') {
            $destination_slug = $segments2;
            $withPath = 'tour-packages/'.$destination_slug;
            if($destination_slug) {
                /*$query->whereHas('packageDestination',function($q1) use($destination_slug) {
                    $q1->where('slug',$destination_slug);
                });*/
                $q2 = Destination::where('status',1);                
                $q2->where(function($q3){
                    $q3->where('is_deleted',0);
                    $q3->orWhereNull('is_deleted');
                });
                $q2->where('slug',$destination_slug);
                $destination = $q2->first();
                if($destination) {

                    $destination_id = $destination->id;
                    $query->where(function($q1) use($destination_id) {
                        $q1->where('packages.destination_id',$destination_id);
                        $q1->orWhere('packages.sub_destination_id',$destination_id);
                        $q1->orWhere(function($q2) use ($destination_id) {
                            $q2->whereNotNull('related_destinations');
                            $q2->where('related_destinations','!=','');
                            $q2->where('related_destinations','!=','[]');
                            $q2->whereJsonContains('packages.related_destinations',[(string)$destination_id]);
                        });
                    });
                    $seo_data['page_title'] = $destination->name??'';
                    /*if(config('custom.theme_name') == 'andamanisland'){
                        $seo_data['page_title'] = "Tour Packages in ".$destination->name ??'';
                    }*/
                    $seo_data['dest_id'] = $destination->id??0;
                    $seo_data['page_brief'] = $destination->brief??'';
                    $seo_data['page_description'] = '';//$destination->description??'';
                    $seo_data['meta_title'] = $destination->package_meta_title??'';
                    $seo_data['meta_description'] = $destination->package_meta_description??'';
                    $seo_data['meta_keyword'] = $destination->package_meta_keyword??'';
                    $data['search_text'] = $request->search_text??'';
                    if(isset($destination->destinationBanners) && !empty($destination->destinationBanners)){
                        $banner_image_name = $destination->destinationBanners[0]->name ?? '';
                        if(isset($banner_image_name) && !empty($banner_image_name)){
                            $banner_image = asset('/storage/destinations/'.$banner_image_name);
                        }
                    }
                    // if($destination->image) {
                    //     $banner_image = url('/storage/destinations/'.$destination->image);
                    // }
                }
            }
        } else if($segments1 == 'tour-activities') {
            $is_activity = 1;
            $destination_slug = $segments2;
            $withPath = 'tour-activities/'.$destination_slug;
            if($destination_slug) {
                $query->whereHas('packageDestination',function($q1) use($destination_slug) {
                    $q1->where('slug',$destination_slug);
                });
                $q2 = Destination::where('status',1);                
                $q2->where(function($q3){
                    $q3->where('is_deleted',0);
                    $q3->orWhereNull('is_deleted');
                });
                $q2->where('slug',$destination_slug);
                $destination = $q2->first();
                if($destination) {
                    $seo_data['page_title'] = $destination->name??'';
                    /*if(config('custom.theme_name') == 'andamanisland'){
                        $seo_data['page_title'] = "Adventure Activities in ".$destination->name ??'';
                    }*/
                    $seo_data['page_brief'] = $destination->brief??'';
                    $seo_data['page_description'] = '';
                    $seo_data['meta_title'] = $destination->activity_meta_title??'';
                    $seo_data['meta_description'] = $destination->activity_meta_description??'';
                    $seo_data['meta_keyword'] = $destination->activity_meta_keyword??'';

                    if(isset($destination->destinationBanners) && !empty($destination->destinationBanners)){
                        $banner_image_name = $destination->destinationBanners[0]->name ?? '';
                        if(isset($banner_image_name) && !empty($banner_image_name)){
                            $banner_image = asset('/storage/destinations/'.$banner_image_name);
                        }
                    }
                    // if($destination->image) {
                    //     $banner_image = url('/storage/destinations/'.$destination->image);
                    // }
                }
            }            
        }

        if($is_activity == 1) {
            $query->where('packages.is_activity',1);
        } else if($is_activity == 0) {
            $query->where('packages.is_activity',0);
        }

        if($featured) {
            $query->where('packages.featured',$featured);
        }

        if($request->filter_tour_type) {
            if(is_array($request->filter_tour_type)) {
                $filter_tour_type_arr = $request->filter_tour_type;
            } else {
                $filter_tour_type_arr = explode(',', $request->filter_tour_type);
            }
            if(!empty($filter_tour_type_arr)) {
                $query->where(function($q1) use($filter_tour_type_arr) {
                    foreach($filter_tour_type_arr as $tour_type) {
                        $q1->orWhere('packages.tour_type',$tour_type);
                    }
                });
            }
        }
        if($request->categories) {
            if(is_array($request->categories)) {
                $categories_arr = $request->categories;
            } else {
                $categories_arr = explode(',', $request->categories);
            }
            if(!empty($categories_arr)) {
                $query->whereHas('packageCategories',function($q1) use($categories_arr) {
                    $q1->whereIn('theme_id',$categories_arr);
                });
            }
        }
        if($request->destinations) {
            if(is_array($request->destinations)) {
                $destinations_arr = $request->destinations;
            } else {
                $destinations_arr = explode(',', $request->destinations);
            }
            if(!empty($destinations_arr)) {
                $query->where(function($q1) use($destinations_arr){
                    $q1->whereIn('packages.destination_id',$destinations_arr);
                    $q1->orWhereIn('packages.sub_destination_id',$destinations_arr);
                });
            }
        }
        if($request->filter_package_budget) {
            if(is_array($request->filter_package_budget)) {
                $filter_package_budget_arr = $request->filter_package_budget;
            } else {
                $filter_package_budget_arr = explode(',', $request->filter_package_budget);
            }
            if(!empty($filter_package_budget_arr)) {
                $query->where(function($q1) use($filter_package_budget_arr){
                    foreach($filter_package_budget_arr as $budget) {
                        $budget_arr = explode('-', $budget);
                        $budget_min = str_replace('_', '', $budget_arr[0])??0;
                        $budget_max = str_replace('_', '', $budget_arr[1])??0;
                        $q1->orWhereBetween('packages.search_price',[$budget_min,$budget_max]);
                    }
                });
            }
        }
        if($request->filter_month) {
            if(is_array($request->filter_month)) {
                $filter_month_arr = $request->filter_month;
            } else {
                $filter_month_arr = explode(',', $request->filter_month);
            }
            if(!empty($filter_month_arr)) {
                $query->where(function($q1) use($filter_month_arr) {
                    foreach($filter_month_arr as $smonth) {
                        $month = (int)date('m',strtotime($smonth));
                        $q1->orWhereJsonContains('packages.best_months',[(string)$month]);
                        $q1->orWhereHas('PackageDeparture',function($q2) use($smonth){
                            $q2->where(function($q3) use($smonth){
                                $smonth_arr = explode('-', $smonth);
                                $year = $smonth_arr[0]??0;
                                $month = $smonth_arr[1]??0;
                                if(!empty($year) && !empty($month)) {
                                    $q3->whereYear('from_date',$year);
                                    $q3->whereMonth('from_date','>=',$month);

                                    $q3->whereYear('to_date',$year);
                                    $q3->whereMonth('to_date','<=',$month);
                                }
                            });
                        });
                    }
                });
            }
        }
        if(isset($request->sort_by_price) && !empty($request->sort_by_price)){
            $sort_by_price = $request->sort_by_price;
            if($sort_by_price == 'lth') {
                $query->orderBy('packages.search_price','ASC');
            } else {
                $query->orderBy('packages.search_price','DESC');
            }            
        }
        $query->orderBy('packages.featured','DESC');
        $query->orderBy('packages.sort_order','ASC');
        
        if($request->text) {
            $text = $request->text??'';
            if($text) {
                $query->where(function($q1) use($text) {
                    $q1->where('title','like','%'.$text.'%');
                    $q1->orWhereHas('packageDestination',function($q2) use ($text) {
                        $q2->where('name','like','%'.$text.'%');
                    });
                    $q1->orWhereHas('packageCategories',function($q2) use ($text) {
                        $q2->where('title','like','%'.$text.'%');
                    });                    
                });
            }
        }
        if($request->sno_of_days) {
            $sno_of_days = $request->sno_of_days;
            $sno_of_days_arr = explode('-', $sno_of_days);
            $days_from = (int)$sno_of_days_arr[0]??0;
            $days_to = $days_from;
            if(isset($sno_of_days_arr[1])) {
                $days_to = (int)$sno_of_days_arr[1]??0;
            }
            if($days_from != '' && $days_to != '') {
                $query->whereBetween('packages.package_duration_days',[$days_from,$days_to]);
            }
        }
        if($request->smonth) {
            $smonth = $request->smonth;
            $query->where(function($q1) use($smonth){
                $month = (int)date('m',strtotime($smonth));
                $q1->whereJsonContains('packages.best_months',[(string)$month]);
                $q1->orWhereHas('PackageDeparture',function($q2) use($smonth){
                    $q2->where(function($q3) use($smonth){
                        $smonth_arr = explode('-', $smonth);
                        $year = $smonth_arr[0]??0;
                        $month = $smonth_arr[1]??0;
                        if(!empty($year) && !empty($month)) {
                            $q3->whereYear('from_date',$year);
                            $q3->whereMonth('from_date','>=',$month);

                            $q3->whereYear('to_date',$year);
                            $q3->whereMonth('to_date','<=',$month);
                        }
                    });
                });
            });            
        }
        if(!empty($flag) && is_numeric($flag)) {
            $query->whereHas('packageFlags',function($q1) use($flag){
                $q1->where('flag_id',$flag);
            });
        }
        // prd($query->toSql());
        $packages = $query->paginate($limit);
        $data['total_count'] = $packages->total();
        $package_arr = [];
        if($packages) {
            foreach($packages as $key => $package) {
                $package_arr[] = Package::parsePackage($package,['size'=>'listing']);
            }
        }

        $params = [];
        if(!empty($withPath)) {
            $packages->withPath($withPath);
            if($is_activity==1) {
                $search_data = CustomHelper::getSearchData('activity',$params);
            } else {
                $search_data = CustomHelper::getSearchData('package',$params);
            }
        } else if($is_activity==1) {
            $search_data = CustomHelper::getSearchData('activity',$params);
            $activity_listing = CustomHelper::getSeoModules('activity_listing');
            $activityListing = $activity_listing->page_url??'activities';
            $packages->withPath($activityListing);
        } else {
            $search_data = CustomHelper::getSearchData('package',$params);
            $package_listing = CustomHelper::getSeoModules('package_listing');
            $packageListing = $package_listing->page_url??'search-packages';
            $packages->withPath($packageListing);
        }
        $data['search_data'] = $search_data;
        $data['seo_data'] = $seo_data;
        View::share('seo_data', $seo_data);

        $results = [];
        $results['data'] = $package_arr;

        $link_params = $search_data;
        if(isset($link_params['segments1'])) {
            unset($link_params['segments1']);
        }
        if(isset($link_params['segments2'])) {
            unset($link_params['segments2']);
        }
        $results['links'] = $packages->appends($link_params)->links('vendor.pagination.bootstrap-4')->render();
        $data['results'] = $results;
        //prd($data['results']);

        $data['success'] = true;
        return Response::json($data, 200);
    }

    public function details(Request $request) {
        $currentUrl = \Request::path();
        $url_redirection = UrlRedirection::where('source_url',$currentUrl)->where('show',1)->first();
        if(!empty($url_redirection) && !empty($url_redirection->destination_url)) {
            $redirectUrl = url($url_redirection->destination_url);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirectUrl);
            exit();
        }
        $data = [];
        $seo_data = [];
        $segments1 = $request->segment(1);
        $is_activity = $request->is_activity??0;
        //$slug = $request->slug;
        if($segments1 == 'activity'){
            $is_activity = 1;
            $slug = $request->slug;
        }
        if($segments1 == 'package'){
            $slug = $request->slug;
        }
        if(!empty($slug)) {
            $storage = Storage::disk('public');
            $query = Package::where('is_activity',$is_activity)->where('slug',$slug)->where('status',1);
            $query->where(function($q1){
                $q1->where('packages.is_deleted',0);
                $q1->orWhereNull('packages.is_deleted');
            });
            $package = $query->groupBy('id')->orderBy('packages.created_at','desc')->first();
            if($package) {


                $package_id = $package->id;
                $package_slug = $package->slug;

                $related_packages = (!empty($package->related_packages)) ? json_decode($package->related_packages,true) : [];
                $destination_id = (!empty($package->destination_id)) ? $package->destination_id :0;
                $is_activity = (!empty($package->is_activity)) ? $package->is_activity :0;
                $default_price_id = '';
                if($request->type) {
                    $packageType = $request->type;
                    $packagePrices = $package->packagePrices??[];
                    if($packagePrices) {
                        foreach($packagePrices as $packagePrice) {
                            if(trim($packagePrice->title) == trim($packageType)) {
                                $default_price_id = $packagePrice->id;
                                break;
                            }
                        }
                    }
                }

                if(empty($default_price_id)) {
                    $packageDefaultPrice = $package->packageDefaultPrice??[];
                    $default_price_id = $packageDefaultPrice->id??'';
                }
                $data['default_price_id'] = $default_price_id;

                $seo_data['meta_title'] = (!empty($package->meta_title))?$package->meta_title:$package->title . ', '.$package->packageDestination->name??'';
                $seo_data['meta_description'] = $package->meta_description??'';
                $seo_data['meta_keywords'] = $package->meta_keywords??'';

                $package_arr = Package::parsePackage($package,['size'=>'detail']);
                $idArr = (string)$package->id;
                $testimonialQuery = Testimonial::whereJsonContains('package_id',[$idArr])->where('status',1);
                $testimonialQuery->where(function($q1){
                    $q1->where('is_deleted',0);
                    $q1->orWhereNull('is_deleted');
                });
                $testimonials = $testimonialQuery->limit(6)->get();
                // prd($testimonials->toArray());
                if($testimonials->count() == 0) {
                    $testimonialQuery = Testimonial::where('status',1);
                    $testimonialQuery->where(function($q1){
                        $q1->where('is_deleted',0);
                        $q1->orWhereNull('is_deleted');
                    });
                    $testimonials = $testimonialQuery->limit(6)->get();
                }
                $testimonials_arr = [];
                if($testimonials) {
                    $path = 'testimonials/';
                    $thumbPath = 'testimonials/thumb/';
                    $testimonialThumbSrc = asset(config('custom.assets').'/images/testimonial-main-default.png');
                    $testimonialSrc = asset(config('custom.assets').'/images/testimonial-main-default.png');
                    foreach($testimonials as $testimonial) {
                        $tImage = $testimonial->image;
                        if(!empty($tImage)){
                            if($storage->exists($path.$tImage)){
                                $testimonialSrc = asset('/storage/'.$path.$tImage);
                            }
                            if($storage->exists($thumbPath.$tImage)){
                                $testimonialThumbSrc = asset('/storage/'.$thumbPath.$tImage);
                            }
                        }
                        $testimonialImages = $testimonial->testimonialImages??[];
                        $images = [];
                        if($testimonialImages && count($testimonialImages) > 0) {
                            foreach($testimonialImages as $image) {
                                $src = $testimonialThumbSrc;
                                if($storage->exists($thumbPath.$image->name)) {
                                    $src = asset('/storage/'.$thumbPath.$image->name);
                                }
                                $images[] = [
                                    'alt' => $image->title,
                                    'src' => $src,
                                ];
                            }
                        } else {
                            $images[] = [
                                'alt' => $testimonial->title,
                                'src' => $testimonialThumbSrc,
                            ];
                        }
                        $testimonials_arr[] = [
                            'name' => $testimonial->name,
                            'title' => CustomHelper::wordsLimit($testimonial->title),
                            'description' => CustomHelper::wordsLimit($testimonial->description),
                            'testimonialSrc' => $testimonialSrc,
                            'thumbSrc' => $testimonialThumbSrc,
                            'images' => $images,
                            'url' => route('testimonialDetails',['slug'=>$testimonial->slug]) ,
                        ];
                    }
                }

                $faq_package = Faq::where('tbl_name','=','packages')->where('status',1)->where('tbl_id',$package_id)->orderBy("sort_order", "ASC");
                $faqs = $faq_package->take(10)->get()->toArray();

                $destination_flags_arr = $package->packageDestination->destinationFlags->pluck('id')->toArray()??[];
                $destinations_query = Destination::where('is_city',0)->where('parent_id',0)->where('status',1); //->where('featured',1)
                if($destination_flags_arr) {
                    $destinations_query->whereHas('destinationFlags',function($q1) use($destination_flags_arr) {
                        $q1->where('flag_id',$destination_flags_arr);
                    });
                }
                $destinations_result = $destinations_query->get();//limit(6)-> newredmine ticket #1229
                $destinations = [];
                $destination_path = "destinations/";
                foreach ($destinations_result as $key => $destinations_row) {
                    $destination_image = $destinations_row->image;
                    $destinationThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
                    if(!empty($destination_image)){
                        if($storage->exists($destination_path.$destination_image)){
                            $destinationThumbSrc = asset('/storage/'.$destination_path.'thumb/'.$destination_image);
                        }
                    }
                    if($is_activity) {
                        $slug = route('tourActivities',['slug'=>$destinations_row->slug]);
                    } else {
                        $slug = route('tourPackages',['slug'=>$destinations_row->slug]);
                    }
                    $package_data = $destinations_row->Packages??'';
                    $package_data_count = $package_data->where('status',1);
                    $package_count = '';
                    if(!empty($package_data_count) && count($package_data_count) > 0){
                        $package_count = $package_data_count->count();
                    }
                    $destinations[] = array(
                        'id' => $destinations_row->id, 
                        'name' => $destinations_row->name, 
                        'slug' => $slug, 
                        'image' => $destinationThumbSrc,
                        'package_count' => $package_count, 

                    );
                }
                $package_itenaries = (!($package->packageItenaries->isEmpty())) ? $package->packageItenaries : [];

                  // prd($package_itenaries);

                $itenaries = [];
                if(!empty($package_itenaries) && $package_itenaries->count() > 0) {
                     $itenaries_path = 'itenaries/';
                    foreach ($package_itenaries as $itenary) {

                        $meal_option_arr = $itenary->meal_option ? json_decode($itenary->meal_option):'';
                        $itenary_day_title = $itenary->day_title;
                        $itenary_title = $itenary->title;
                        $itenary_description = $itenary->description;
                        $itenary_image = $itenary->image;
                        $itenary_location = $itenary->Location->name??'';
                        $itenaryTags = (!empty($itenary->tags)) ? explode(',', $itenary->tags):[];
                        $itenary_inclusions = $itenary->itenary_inclusions??[];
                        $itenary_inclusions_arr = [];
                        if(!empty($itenary_inclusions)) {
                            $itenary_inclusions = json_decode($itenary_inclusions);
                            $itenary_inclusions_arr = CustomHelper::showPackageInclusionsOther($itenary_inclusions);
                        }

                        $itenary_inclusions_arr_new = [];
                        if(!empty($itenary_inclusions_arr)) {
                            $i_path = 'inclusion/';
                            foreach($itenary_inclusions_arr as $inclusion_key => $inclusion_val) {
                                $i_image = asset(config('custom.assets').'/images/ico3.png');
                                if(!empty($inclusion_key) && File::exists(public_path('storage/inclusion/'.$inclusion_key))) {
                                    $i_image = url('storage/'.$i_path.'thumb/'.$inclusion_key);
                                }

                                $itenary_inclusions_arr_new[] = [
                                    'image' => $i_image,
                                    'title' => ucwords($inclusion_val),
                                ];
                            }
                        }
                        $itenary_inclusions_arr = $itenary_inclusions_arr_new;
                        // prd($itenary_inclusions_arr);


                        $itenarySrc = '';
                        if(!empty($itenary_image)){
                            if($storage->exists($itenaries_path.$itenary_image)){
                                $itenarySrc = asset('/storage/'.$itenaries_path.$itenary_image);
                            }
                        }

                        $itenaryThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

                        if(!empty($itenary_image)){
                            if($storage->exists($itenaries_path.$itenary_image)){
                                $itenaryThumbSrc = asset('/storage/'.$itenaries_path.'thumb/'.$itenary_image);
                            }
                        }
                        // prd($meal_option_arr);
                        $itenaries[] = array( 
                            'meal_option_arr' => $meal_option_arr ,
                            'itenary_day_title' => $itenary_day_title ,
                            'itenary_title' => $itenary_title ,
                            'itenary_description' => $itenary_description ,
                            'itenary_image' => $itenary_image ,
                            'itenary_location' => $itenary_location ,
                            'itenaryTags' => $itenaryTags ,
                            'itenary_inclusions_arr' => $itenary_inclusions_arr ,
                            'itenaryThumbSrc' => $itenaryThumbSrc ,
                            'itenarySrc' => $itenarySrc ,
                        );
                        // prd($itenaryTags);
                    }
                }

                $PackageFlightsResult = $package->PackageFlights??[];
                $PackageFlights = [];
                if(!empty($PackageFlightsResult)) {
                    foreach ($PackageFlightsResult as $key => $flight_row) {
                        $PackageFlights[] = array(
                            'airline_name' => $flight_row->airline_name,
                            'flight_number' => $flight_row->flight_number,
                            'flight_departure' => $flight_row->flight_departure,
                            'flight_arrival' => $flight_row->flight_arrival,
                        );
                    }
                }
                $package_arr['PackageFlights'] = $PackageFlights;

                $query = Package::with('packagePrices')->where('status',1);
                $query->where('id','!=',$package_id);
                $query->where(function($q1){
                    $q1->where('is_deleted',0);
                    $q1->orWhereNull('is_deleted');
                });
                $query->where(function($query1) use($destination_id,$related_packages){
                    $query1->where('destination_id',$destination_id);
                    if(!empty($related_packages)) {
                        $query1->orWhereIn('id',$related_packages);
                    }
                });
                if($is_activity) {
                    $query->where('is_activity',1);
                } else {
                    $query->where('is_activity',0);
                }

                if(!empty($related_packages)) {
                    foreach($related_packages as $key => $value) {
                        $query->orderByRaw("FIELD(id,$value) DESC");
                    }
                }
                $query->orderBy('featured','DESC');
                $query->orderBy('sort_order','ASC');
                
                $relatedPackagestObj = $query->limit(10)->get();

                $relatedPackage_arr = [];
                if($relatedPackagestObj) {
                    foreach ($relatedPackagestObj as $key => $relatedPackage) {
                        $relatedPackage_arr[] = Package::parsePackage($relatedPackage);
                    }
                }
                //prd($relatedPackage_arr);

                $packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';
                $actual_link = route($packageDetailName,['slug'=>$package_slug]);
                $websiteSetting = CustomHelper::getSettings(['FACEBOOK_SHARE','TWITTER_SHARE','INSTAGRAM_SHARE','WHATSAPP_SHARE','LINKEDIN','HTML_VALIDATION']);
                $facebook = $websiteSetting['FACEBOOK_SHARE'] ?? '';
                $twitter = $websiteSetting['TWITTER_SHARE']?? '';
                $instagram = $websiteSetting['INSTAGRAM_SHARE']?? '';
                $whatsapp = $websiteSetting['WHATSAPP_SHARE']?? '';
                $linkedin = $websiteSetting['LINKEDIN']?? '';
            // $HTML_VALIDATION = $websiteSetting['HTML_VALIDATION']?? '';

                $sharing_links = [
                    'facebook' => str_replace('{current_url}', $actual_link, $facebook),
                    'twitter' => str_replace('{current_url}', $actual_link, $twitter),
                    'instagram' => str_replace('{current_url}', $actual_link, $instagram),
                    'whatsapp' => str_replace('{current_url}', $actual_link, $whatsapp),
                    'linkedin' => str_replace('{current_url}', $actual_link, $linkedin),
                ];

                $data['sharing_links'] = $sharing_links;
                $data['similarPackages'] = $relatedPackage_arr;
                $data['package'] = $package_arr;
                $data['itenaries'] = $itenaries;
                $data['faqs'] = $faqs;
                $data['testimonials'] = $testimonials_arr;
                $data['destinations'] = $destinations;
                $data['seo_data'] = $seo_data;
View::share('seo_data', $seo_data);
View::share('faqs', $faqs);

                $is_activity = $package->is_activity;

                if($is_activity) {
                    $params = [];
                    $search_data = CustomHelper::getSearchData('activity',$params);
                    $search_data['module'] = 'Activity';
                    $data['search_data'] = $search_data;
                    // prd($data);
                    return Inertia::render('activity/Details', $data);
                } else {
                    $params = [];
                    $search_data = CustomHelper::getSearchData('package',$params);
                    $search_data['module'] = 'Package';
                    $data['search_data'] = $search_data;
                    // prd($data);
                    return Inertia::render('packages/Details', $data);
                }
            } else {
                
            abort(404);
            }
        } else {
            abort(404);
        }
    }


    public function ajaxPriceDetails(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        if($request->price_id) {
            $price = PackagePriceInfo::find($request->price_id);
            if($price) {
                $package = $price->Package??[];
                if($package) {
                    $search_data = $request->search_data??[];
                    $dep_date = '';
                    if(isset($search_data['dep']) && !empty($search_data['dep'])) {
                        $dep = CustomHelper::DateFormat($search_data['dep'],'Y-m-d','d/m/Y')??'';
                        if(!empty($dep) && strtotime($dep) > strtotime(date('Y-m-d H:i:s'))) {
                            $dep_date = $dep;
                        }
                    }

                    $is_activity = $package->is_activity??'';
                    $data = [];
                     $package_arr = Package::parsePackage($package,['size'=>'detail']);
                    $data['package'] = $package_arr;
                    $data['package_price_id'] = $price->id;
                    $data['packageSelectedPrice'] = Package::parsePackagePrice($price);
                    $data['package_price_title'] = $price->title ??'';
                    $data['success'] = true;

                    $package_id = $package->id;
                    $package_price_id = $price->id;
                    $startingPrice = CustomHelper::getPackagePriceFrom($package_id,$package_price_id);
                    $calender_price = CustomHelper::getPrice($startingPrice);
                    $ii = 0;
                    $package_inventory = CustomHelper::getPackageInventory($package_id,$package_price_id);
                    $trip_date = '';
                    $full_calender_data_ar = [];
                    if(!empty($package_inventory)) {
                        $tripTimeArr = config('custom.tripTimeArr');
                        $unique_date = [];
                        foreach ($package_inventory as $key => $pi) {
                            if($pi->inventory > $pi->booked) {
                                if($is_activity == 0 || ($is_activity == 1 && !in_array($pi->trip_date, $unique_date))) {
                                    if(empty($trip_date)) {
                                        $trip_date = CustomHelper::DateFormat($pi->trip_date,'d/m/Y','Y-m-d');
                                    }
                                    $unique_date[] = $pi->trip_date;

                                    $date_title = $calender_price;
                                    $slot_key = '';
                                    $slot_title = '';

                                    if($is_activity == 0 && $pi->slot_id) {
                                        $slot_key = CustomHelper::getPackageSlot($pi->package_id,$pi->slot_id);
                                        if($slot_key) {
                                            $slot_title = CustomHelper::getPackageSlotTitle($slot_key);
                                            if($slot_title) {
                                                $date_title = $slot_title;
                                            }
                                        }
                                    }
                                    $full_calender_data_ar[$ii]['title'] = $date_title;
                                    $full_calender_data_ar[$ii]['start'] = $pi->trip_date;
                                    $full_calender_data_ar[$ii]['slot_key'] = $slot_key;
                                    $full_calender_data_ar[$ii]['slot_title'] = $slot_title;
                                    $ii++;
                                }
                            }
                        }

                        if(!empty($dep_date) && !empty($unique_date) && in_array($dep_date, $unique_date)) {
                            $trip_date = CustomHelper::DateFormat($dep_date,'d/m/Y','Y-m-d');
                        }

                        $mostRecent = 0;
                        $mostRecentDate = [];
                        $mostRecent2 = 0;
                        $now = time();
                        foreach($full_calender_data_ar as $fDate) {
                            $curDate = strtotime($fDate['start']);
                            if ($curDate > $mostRecent && $curDate > $now) {
                                $mostRecent = $curDate;
                                $mostRecentDate[] = $fDate['start'];
                            }
                        }
                        if(!empty($mostRecentDate)) {
                            $min = min(array_map('strtotime', $mostRecentDate));
                        } else {
                            $min = $now;
                        }
                        $mostRecent2 = date('Y-m-d', $min);
                        $full_calender_data = $full_calender_data_ar;
                        $data['defaultDate'] = (!empty($mostRecent2))?$mostRecent2:date('Y-m-d');
                        $data['startDate'] = date('Y-m-d');
                        $data['myEvents'] = $full_calender_data;
                        $data['startingPrice'] = $startingPrice;
                        $data['trip_date'] = $trip_date;
                    }

                    $discount_data = [];
                    $discount_category_id = $package->discount_category_id;
                    if($discount_category_id !== 0) {
                        $module_name = 'package_listing';
                        if($is_activity == 1) {
                            $module_name = 'activity_listing';
                        }
                        $module_record_id = $package->id;
                        $group_id = Auth::user()->group_id??'';
                        if(empty($group_id)) {
                            $group_id = '-1';
                        }
                        $discount_result = CustomHelper::getDiscountData($module_name,$discount_category_id,0,$group_id, $module_record_id);
                        if($discount_result && $discount_result->id) {
                            $discount_data = $discount_result->toArray();
                        }
                    }
                    $data['discount_data'] = $discount_data;
                }
            }
        }
        return response()->json($data);    
    }

    public function ajaxPackagePriceAccommodations(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        if($request->price_id) {
            $price = PackagePriceInfo::find($request->price_id);
            if($price) {
                $package_price_id = $request->price_id;
                $package = $price->Package??[];
                if($package) {
                    $data = [];
                    $package_id = $package->id??'';
                    $data['package'] = $package;
                    $ppas = [];
                    $accommodation_days_arr = PackageAccommodation::where('package_id',$package_id)->where('package_price_id',$package_price_id)->get();
                    $accommodation_days = [];
                    if(!empty($accommodation_days_arr)) {
                        $storage = Storage::disk('public');
                        $accommodation_path = 'accommodations/';
                        foreach($accommodation_days_arr as $accommodation_day) {
                            $itenary_arr = $accommodation_day->itenary_data?json_decode($accommodation_day->itenary_data):[];
                            $itineraries_title = [];
                            if(!empty($itenary_arr) && count($itenary_arr) > 0) {
                                foreach($itenary_arr as $key => $itenary_id){
                                    $itenary_data = CustomHelper::getitenarydata($itenary_id);
                                    if($itenary_data) {
                                        $itineraries_title[] = $itenary_data->day_title ?? '' ;
                                    }
                                }
                            }
                            $accommodation_data = $accommodation_day->accommodation_data?json_decode($accommodation_day->accommodation_data):[];
                            $accommodations = [];
                            if(!empty($accommodation_data) && count($accommodation_data) > 0) {
                                foreach ($accommodation_data as $key => $accommodation_id) {
                                    $accommodation = CustomHelper::getAccommodationdata($accommodation_id);
                                    if($accommodation) {
                                        $accommodation_image = $accommodation->image??'';
                                        $accommodationThumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
                                        if(!empty($accommodation_image)){
                                            if($storage->exists($accommodation_path.$accommodation_image)){
                                                $accommodationThumbSrc = asset('/storage/'.$accommodation_path.'thumb/'.$accommodation_image);
                                            }
                                        }

                                        $name_arr = [];
                                        $place_name = $accommodation->AccommodationDefaultLocation->DestinationLocation->name??'';
                                        if($place_name) {
                                            $name_arr[] = $place_name;
                                        }
                                        $distination_name = $accommodation->accommodationDestination->name??'';
                                        if($distination_name) {
                                            $name_arr[] = $distination_name;
                                        }
                                        if(!empty($name_arr)) {
                                            $place_name = implode(', ',$name_arr);
                                        }

                                        $accommodations[] = [
                                            'name' => $accommodation->name,
                                            'place_name' => $place_name,
                                            'popup_url' => url('packagespopup/hotel-details',['slug'=>$accommodation->slug]),
                                            'star_classification' => $accommodation->star_classification,
                                            'brief' => $accommodation->brief ?? '',
                                            'thumbSrc' => $accommodationThumbSrc ?? '',
                                        ];
                                    }
                                }
                            }
                            $accommodation_days[] = [
                                'accommodation_data' => $accommodations,
                                'itenary_data' => implode('<i class="fa fa-long-arrow-right"> </i>',$itineraries_title),
                            ];
                        }
                    }
                    $data['accommodations_days'] = $accommodation_days;
                    $data['success'] = true;                  
                }
            }
        }
        return response()->json($data);
    }

    public function enquire_now(Request $request) {
        $canonical = $request->fullUrl();
        //prd($url);
        $data = [];
        $slug = $request->package;
        $type = $request->type;
        if(!empty($slug)) {
            $storage = Storage::disk('public');
            $query = Package::where('slug',$slug)->where('status',1);
            $query->where(function($q1){
                $q1->where('packages.is_deleted',0);
                $q1->orWhereNull('packages.is_deleted');
            });
            $package = $query->groupBy('id')->orderBy('packages.created_at','desc')->first();

            if($package){
                $package_id = $package->id;
                $package_slug = $package->slug;
                $package_arr = Package::parsePackage($package,['size'=>'detail']);
                $data['package'] = $package_arr;
                // prd($data['package']);
                $data['type'] = $type;
                $data['search_data'] = $request->toArray();

                $seo_data = [];
                $seo_data['meta_title'] = 'Enquire This Trip - '.($package_arr['title']??'');
                $seo_data['canonical'] = $canonical;
                $data['seo_data'] = $seo_data;
                View::share('seo_data', $seo_data);
                return Inertia::render('enquire-now', $data);
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }

    public function getPackageTypePrice(Request $request) {
        $response = [];
        $response['success'] = false;
        $packageId = $request->pkgId??'';
        $typeId = $request->typeId??'';
        if(!empty($packageId) && !empty($typeId)) {
            $priceData = PackagePriceInfo::find($typeId);
            if($priceData) {
                $package = $priceData->Package??'';
                if($package) {
                    // $publish_price = $package->publish_price??0; 
                    $publish_price = CustomHelper::getPackagePriceFrom($packageId,$typeId);

                    // $group_id = Auth::user()->group_id??'';
                    // if(empty($group_id)) {
                    //     $group_id = '-1';
                    // }
                    $group_id = '-1';

                    $discount_amount = 0;
                    $discount_category_id = $package->discount_category_id??'';
                    $module_name = 'package_listing';
                    $is_activity = $package->is_activity??'';
                    if($is_activity == 1) {
                        $module_name = 'activity_listing';
                    }
                    $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$publish_price,$group_id,$package->id);
                    // prd($publish_price.' == '.$discount_amount);
                    $search_price = $publish_price - $discount_amount;
                    $response['success'] = true;
                    $response['publish_price'] = $publish_price;
                    $response['search_price'] = $search_price;
                    $response['title'] = $priceData->title;
                    $response['package_price_title'] = $priceData->title ??'';
                }
            }
        }
        return response()->json($response);    
    }

    public function tour_category(Request $request) {
        $data = [];
        $seo_tags = CustomHelper::getSeoModules('tour_category');
        $data['page_title'] = $seo_tags->page_title??'Tour Category';
        $data['page_url_label'] = $seo_tags->page_url_label??'Tour Category';
        $data['page_brief'] = $seo_tags->page_brief??'';
        $data['page_description'] = $seo_tags->page_description??'';
        $data['meta_title'] = $seo_tags->meta_title??'';
        $data['meta_description'] = $seo_tags->meta_description??'';
        $data['meta_keyword'] = $seo_tags->meta_keyword??'';
        $data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
        $data['image'] = $seo_tags->image??'';

        $theme = ThemeCategories::where('status',1)->orderBy('sort_order','asc')->get();
        $data['themes'] = $theme;

        if($theme){
        $themeData = [];
        $data = [];
        $seo_data = [];
        $limit = $this->limit;

        $meta_title = '';
        $meta_description = '';
        $meta_keyword = '';
        $breadcrumb_title = '';

        $seo_tags = CustomHelper::getSeoModules('tour_category');
        $banner_image = '';
        $identifier = '';
        if(!empty($seo_tags)) {
            $identifier = $seo_tags->identifier;
            $seo_data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
            $seo_data['page_url_label'] = $seo_tags->page_url_label??'';
            $seo_data['page_brief'] = $seo_tags->page_brief??'';
            $seo_data['page_description'] = $seo_tags->page_description??'';
            $seo_data['page_url'] = $seo_tags->page_url??'';
            $seo_data['page_detail_url'] = $seo_tags->page_detail_url??'';
            $seo_data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
            $seo_data['meta_description'] = $seo_tags->meta_description??'';
            $seo_data['meta_keyword'] = $seo_tags->meta_keyword??'';
            $seo_data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
            
            if($seo_tags->image) {
                $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
            } else {
                $banner_image = asset(config('custom.assets').'/images/default_common_banner.jpg');
            }
            $breadcrumb_title = $seo_tags->page_url_label??'Tour Category';
            $meta_title = $seo_data['meta_title'];
            $meta_description = $seo_data['meta_description'];
            $meta_keyword = $seo_data['meta_keyword'];            
        }
        $seo_data['banner_image'] = $banner_image;
        // prd($banner_image);
        foreach ($theme as $themeLists) {
            $storage = Storage::disk('public');
            $manager_path = "themes/";
            $manager_thumb_path = "themes/thumb/";

            $managerSrc =asset(config('custom.assets').'/images/noimage.jpg');

            $image = $themeLists->image;

            if(!empty($image) && $storage->exists($manager_path.$image))
            {
               $managerSrc = asset('/storage/'.$manager_path.$image);
            }

            $data['title'] = CustomHelper::wordsLimit($themeLists->title);
            $data['slug'] = $themeLists->slug;
            $data['managerSrc'] = $managerSrc;

            $themeData[] = [
                'title' => $themeLists->title??'',
                'slug' => $themeLists->slug??'',
                'cat_url' => route('tourCategoryDetail',[$themeLists->slug]) ,
                'managerSrc' => $managerSrc,
            ];
        }
    }

        $data['theme'] = $themeData;
        //prd($teamData);

        $breadcrumbData = [];
        $TOUR_CATEHORY_VUE_JS = ['viaggindia'];
        if(in_array(config('custom.theme_name'), $TOUR_CATEHORY_VUE_JS)) {
            $breadcrumbData[] = ['url'=>'/','label'=>'Casa'];
        } else {
            $breadcrumbData[] = ['url'=>'/','label'=>'Home'];
        }
        $breadcrumbData[] = ['url'=>route('tourCategoryListing'),'label'=>$breadcrumb_title];
            
        $data['breadcrumbData'] = $breadcrumbData;
        $data['seo_data'] = $seo_data;
        View::share('seo_data', $seo_data);

        // prd($seo_data);

        return Inertia::render('themes/theme_listing', $data);
        // return view(config('custom.theme').'.themes.theme_listing', $data);
    }
}