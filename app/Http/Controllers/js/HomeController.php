<?php
namespace App\Http\Controllers\js;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ThemeCategories;
use App\Models\Package;
use App\Models\PackageAirline;
use App\Models\Faq;
use App\Models\SeoMetaTag;
use App\Models\UserWallet;
use App\Models\Testimonial;
use App\Models\Accommodation;
use App\Models\Blog;
use App\Models\Widget;
use App\Models\TeamManagement;
use App\Models\UserGstInfo;
use App\Models\AirlineFaretype;
use App\Models\Banner;
use App\Models\BannerImageGallery;
use App\Models\Country;
use App\Models\UrlRedirection;
use App\Helpers\CustomHelper;
use Inertia\Inertia;
use DB;
use File;
use Response;
use Validator;
use Storage;
class HomeController extends Controller {

    private $limit;
    private $theme;

    public function __construct() {
        $this->limit = 20;
        $this->theme = config('custom.theme');
    }

    public function index() {
        $currentUrl = \Request::root();
        if(stripos($currentUrl, 'index.php')) {
            $currentUrl = 'index.php';
            $url_redirection = UrlRedirection::where('source_url',$currentUrl)->where('show',1)->first();
            if(!empty($url_redirection)) {
                if(!empty($url_redirection->destination_url)) {
                    $redirectUrl = url($url_redirection->destination_url);
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: ".$redirectUrl);
                    exit();
                } else {
                    $redirectUrl = route('homePage');
                    $redirectUrl = str_replace('index.php', '', $redirectUrl);
                    // prd($redirectUrl);
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: ".$redirectUrl);
                    exit();                    
                }
            }
        }
        $data = [];
        $seo_data = [];
        $storage = Storage::disk('public');
        $identifier = 'home';
        $seo_tags = SeoMetaTag::where(['identifier'=>$identifier,'status'=>1])->first();
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
        }

        $featuredPackages = Package::with('packagePrices')->where('is_activity',0)->where('status',1)->where('featured',1)->orderBy('featured','desc')->orderBy('sort_order','asc')->limit(10)->get();
        /*->whereHas('packageFlags',function($q1) {
            $q1->where('flag_id',2);
        })*/
        $featuredPackages_arr = [];
        if($featuredPackages) {
            foreach ($featuredPackages as $package) {
                $featuredPackages_arr[] = Self::parsePackage($package, $storage);
            }
        }
        $data['featuredPackages'] = [
            'url' => route('packageListing'),
            'data' => $featuredPackages_arr,
        ];
        // prd($data['featuredPackages']);
        
        $teams =[];
        $teamData = TeamManagement::where('status',1)->where('featured',1)->orderBy('sort_order','asc')->orderBy('featured');
        $teamData->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        $teamResult = $teamData->limit(10)->get();
        foreach ($teamResult as $key => $teamManagenent) {
            $team_path = "teammanagements/";
            $team_thumb_path = "teammanagements/thumb/";
            $teamSrc = asset(config('custom.assets').'/images/noimage.jpg');
            $image = $teamManagenent->image;
            if(!empty($image) && $storage->exists($team_path.$image)) {
                $teamSrc = asset('/storage/'.$team_path.$image);
            }
            $teams[] = [
                'title' => $teamManagenent->title,
                'sub_title' => $teamManagenent->sub_title,
                'designation' => $teamManagenent->designation,
                'teamSrc' => $teamSrc,
            ];
        }

        $data['teams'] = $teams;
        $data['seo_data'] = $seo_data;
        
        $widgetDetail = CustomHelper::getWidget('pronto-per-un-viaggio-unico');
        if(!empty($widgetDetail)){
            $storage = Storage::disk('public');
            $path = 'widgets/';
            $section_heading = $widgetDetail->section_heading;
            $description = $widgetDetail->description;
            $image1 = $widgetDetail->image1;

            $widgetThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

            if(!empty($image1)){
                if($storage->exists($path.$image1)){
                    $widgetThumbSrc = asset('/storage/'.$path.$image1);
                }
            }
        }

        $widgetData_arr = [];
        $widgets = Widget::where('status',1)->get();
        if($widgets) {
            foreach($widgets as $widget) {               

                $widgetData = CustomHelper::getWidget($widget->slug);
                if(!empty($widgetData)){
                    $widgetDetailData = [];
                    $storage = Storage::disk('public');
                    $path = 'widgets/';
                    $image1 = $widgetData->image1??'';
                    $image2 = $widgetData->image2??'';

                    $widgetSrc =asset(config('custom.assets').'/images/noimage.jpg');
                    if(!empty($image1)){
                        if($storage->exists($path.$image1)){
                            $widgetSrc = asset('/storage/'.$path.$image1);
                        }
                    }
                    if(!empty($image2)){
                        if($storage->exists($path.$image2)){
                            $widgetSrc2 = asset('/storage/'.$path.$image2);
                        }
                    }
                    $widgetDetailData = [
                        'widget_name' => $widgetData->widget_name??'',
                        'section_heading' => $widgetData->section_heading??'',
                        'about_widget_desc' => $widgetData->about_widget_desc??'',
                        'description' => $widgetData->description??'',
                        'widgetSrc' => $widgetSrc??'',
                        'widgetSrc2' => $widgetSrc2??'',
                    ];
                }
                $widgetData_arr[$widget->slug] = $widgetDetailData??'';
            }
        }
        $data['widgetData'] = $widgetData_arr;
        
        /*$widgetData = CustomHelper::getWidget('things-to-do-in');
        $widget_identifier = 'rajasthan';
        if($widget_identifier){
            $widgetData = CustomHelper::getWidget($widget_identifier);
        }
        if(!empty($widgetData)){
            $widgetDetailData = [];
            $storage = Storage::disk('public');
            $path = 'widgets/';
            $image1 = $widgetData->image1??'';

            $widgetSrc =asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($image1)){
                if($storage->exists($path.$image1)){
                    $widgetSrc = asset('/storage/'.$path.$image1);
                }
            }
            $widgetDetailData = [
                'widget_name' => $widgetData->widget_name??'',
                'section_heading' => $widgetData->section_heading??'',
                'description' => $widgetData->description??'',
                'widgetSrc' => $widgetSrc??'',
            ];
        }
        $data['widgetData'] = $widgetDetailData??'';


        $widget_world = CustomHelper::getWidget('discover-the-world-specially-curated-for-you');
        $slug = 'indian-himalayas';
        if($slug){
            $widget_world = CustomHelper::getWidget($slug);
        }
        if(!empty($widget_world)){
            $widgetWorld = [];
            $storage = Storage::disk('public');
            $path = 'widgets/';
            $image1 = $widget_world->image1??'';
            $image2 = $widget_world->image2??'';

            $widgetSrc =asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($image1)){
                if($storage->exists($path.$image1)){
                    $widgetSrc = asset('/storage/'.$path.$image1);
                }
            }
            if(!empty($image2)){
                if($storage->exists($path.$image2)){
                    $widgetSrc2 = asset('/storage/'.$path.$image2);
                }
            }
            $widgetWorld = [
                'widget_name' => $widget_world->widget_name??'',
                'section_heading' => $widget_world->section_heading??'',
                'description' => $widget_world->description??'',
                'widgetSrc' => $widgetSrc??'',
                'widgetSrc2' => $widgetSrc2??'',
            ];
        }
        $data['widgetWorldData'] = $widgetWorld??'';*/

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
        $data['widgetDetail'] = $widgetDetail;

        $tour_category = CustomHelper::getSeoModules('tour_category');
        $TOUR_CATEGORY_URL = $tour_category->page_url??'tour-category';

        $activity_listing = CustomHelper::getSeoModules('activity_listing');
        $ACTIVITY_URL = $activity_listing->page_url??'activities';

        $package_listing = CustomHelper::getSeoModules('package_listing');
        $PACKAGE_URL = $package_listing->page_url??'search-packages';

        $data['TOUR_CATEGORY_URL'] = $TOUR_CATEGORY_URL;
        $data['ACTIVITY_URL'] = $ACTIVITY_URL;
        $data['PACKAGE_URL'] = $PACKAGE_URL;

        View::share('seo_data', $seo_data);
        View::share('faqs', $faqs);
        return Inertia::render('Home', $data);
    }
     function parsePackage($package, $storage, $package_path='packages/') {
        // prd($package);
        $packageData = [];
        if($package) {
            $packageThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($package->image)) {
                if($storage->exists($package_path.$package->image)){
                    $packageThumbSrc = asset('/storage/'.$package_path.'thumb/'.$package->image);
                }
            }

            $packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';

            $packageData = [
                'id' => $package->id,
                'url' => route($packageDetailName,['slug'=>$package->slug]),
                'isActivity' => $package->is_activity??0,
                'title' => CustomHelper::wordsLimit($package->title),
                'thumbSrc' => $packageThumbSrc,
                'nights' => $package->nights,
                'days' => $package->days,
                'price' => (int)$package->search_price??0,
            ];
        }
        return $packageData;
    }

    public function ajaxHomeBanners(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';

        $banner_images_arr = [];
        $identifier = 'home';
        $seo_tags = SeoMetaTag::where(['identifier'=>$identifier,'status'=>1])->first();
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

                $banner_images_arr[] = [
                    'title' => $seo_data['page_title'],
                    'sub_title' => '',
                    'link_text_1' => $seo_data['page_title'],
                    'link_text_2' => '',
                    'link_1' => '',
                    'link_2' => '',
                    'src' => $banner_image,
                ];
            }
        }

        $banner_images_arr = [];
        $isMobile = CustomHelper::isMobile();
        if($isMobile) {
            $imageBanner = Banner::where('status',1)->where('slug','home-page-mobile-banner')->first();
            if(!empty($imageBanner) && $imageBanner->type == 1) {
                $bannerImages = $imageBanner->Images;
                if(!$bannerImages->isEmpty()) {
                    $storage = Storage::disk('public');
                    $path = "banners/";
                    foreach ($bannerImages as $image) {
                        if(!empty($image->image_name)) {
                            if($storage->exists($path.$image->image_name)) {
                                $bannerSrc = asset('/storage/'.$path.$image->image_name);
                                $banner_images_arr[] = [
                                    'title' => $image->title,
                                    'sub_title' => $image->sub_title,
                                    'link_text_1' => $image->link_text_1,
                                    'link_text_2' => $image->link_text_2,
                                    'link_1' => $image->link_1,
                                    'link_2' => $image->link_2,
                                    'src' => $bannerSrc,
                                ];
                            }
                        }
                    }
                }
            }
        } else {
            $imageBanner = Banner::where('status',1)->where('slug','home-page-main-banner')->first();
            if(!empty($imageBanner) && $imageBanner->type == 1) {
                $bannerImages = $imageBanner->Images;
                if(!$bannerImages->isEmpty()) {
                    $storage = Storage::disk('public');
                    $path = "banners/";
                    foreach ($bannerImages as $image) {
                        if(!empty($image->image_name)) {
                            if($storage->exists($path.$image->image_name)) {
                                $bannerSrc = asset('/storage/'.$path.$image->image_name);
                                $banner_images_arr[] = [
                                    'title' => $image->title,
                                    'sub_title' => $image->sub_title,
                                    'link_text_1' => $image->link_text_1,
                                    'link_text_2' => $image->link_text_2,
                                    'link_1' => $image->link_1,
                                    'link_2' => $image->link_2,
                                    'src' => $bannerSrc,
                                ];
                            }
                        }
                    }
                }
            }
        }

        if(empty($banner_images_arr)) {
            $bannerSrc = asset(config('custom.assets').'/images/default_common_banner.jpg');
            $banner_images_arr[] = [
                'title' => CustomHelper::WebsiteSettings('SYSTEM_TITLE'),
                'sub_title' => '',
                'link_text_1' => CustomHelper::WebsiteSettings('SYSTEM_TITLE'),
                'link_text_2' => '',
                'link_1' => '',
                'link_2' => '',
                'src' => $bannerSrc,
            ];
        }
        $response['banner_images'] = $banner_images_arr ;
        $response['success'] = true;
        return Response::json($response, 200);
    }

    public function ajaxHomeThemes(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $search_data = [];
        $limit = $this->limit;
        if($request->limit) {
            $limit = $request->limit??$limit;
        }
        $storage = Storage::disk('public');
        $query = ThemeCategories::where('status',1)->orderBy('sort_order','asc');
        if($request->featured) {
            $query->where('featured',$request->featured);
        }
        $tour_categories_result = $query->paginate($limit);

        $tour_categories_arr = [];
        if($tour_categories_result) {
            foreach ($tour_categories_result as $theme) {
                $theme_path = "themes/";
                $themeTitle = CustomHelper::wordsLimit($theme->title);
                $themeDesc = $theme->description ?? '';
                $themeSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
                if(!empty($theme->image) && $storage->exists($theme_path.$theme->image)) {
                    $themeSrc = asset('/storage/'.$theme_path.$theme->image);
                }
                $row = [
                    'url' => route('tourCategoryDetail',[$theme->slug]),
                    'thumbSrc' => $themeSrc,
                    'title' => $themeTitle,
                    'desc' => $themeDesc,
                ];
                $tour_categories_arr[] = $row;
            }
        }
        $data['results']['data'] = $tour_categories_arr;
        $tour_category = CustomHelper::getSeoModules('tour_category');
        $tourCategoryListing = $tour_category->page_url??'tour-category';
        $data['results']['url'] = $tourCategoryListing;
        $tour_categories_result->withPath($tourCategoryListing);
        $data['results']['links'] = $tour_categories_result->appends($search_data)->links('vendor.pagination.bootstrap-4')->render();
        $data['success'] = true;
        return response()->json($data);
    }

    public function get_menu() {
        $resp = CustomHelper::getJsMenu();
        $resp['userInfo'] = CustomHelper::getUserInfo();

        $airline_faretypes = \Cache::rememberForever("airline-airline_faretypes", function () {
            $airline_faretypes_arr = [];
            $airline_faretypes = AirlineFaretype::get();
            if($airline_faretypes) {
                foreach($airline_faretypes as $row) {
                    $airline_faretypes_arr[$row->api] = [
                        'fare_types' => $row->fare_types,
                        'description' => $row->description,
                        'color' => $row->color,
                        'ui' => $row->ui,
                        'api' => $row->api,
                    ];
                }
            }
            return $airline_faretypes_arr;
        });
        $resp['airline_faretypes'] = $airline_faretypes;

        return Response::json($resp, 200);
    }

    public function getFormShortCode(Request $request) {
        // prd($request->toArray());
        $params = [];
        $params['slug'] = $request->slug??'';
        $params['class'] = $request->class??'';
        $params['hidden'] = $request->hidden??'';
        $params['search_data'] = $request->searchData??[];
        $formHtml = CustomHelper::form_short_code($params);
        return Response::json(array(
            'success' => true,
            'formHtml' => $formHtml
        ), 200);
    }


    public function ajaxCmsData(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';

        $slug = $request->slug??'';
        if($slug) {
            $cms = CustomHelper::getCMSPage($slug);
            $response['cms'] = $cms;
            $response['success'] = true;
        }
        return Response::json($response, 200);
    }
    public function ajaxFaqsData(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';

        $identifier = 'home';
        $seo_tags = SeoMetaTag::where(['identifier'=>$identifier,'status'=>1])->first();
        $seo_meta_tags_id = $seo_tags->id??0;
        $faq_modules = Faq::where('tbl_name','=','seo_meta_tags')->where('status',1)->where('tbl_id',$seo_meta_tags_id)->orderBy("sort_order", "ASC");
        $faqs = $faq_modules->take(40)->get();
        if($faqs) {
            $faqs_arr = [];
            foreach($faqs as $faq) {
                $faqs_arr[] = $faq->toArray();
            }
            $response['faqs'] = $faqs_arr;
            $response['success'] = true;
        }
        return Response::json($response, 200);
    }
    public function cmsPage(Request $request) {
        $currentUrl = \Request::path();
        $url_redirection = UrlRedirection::where('source_url',$currentUrl)->where('show',1)->first();
        if(!empty($url_redirection) && !empty($url_redirection->destination_url)) {
            $redirectUrl = url($url_redirection->destination_url);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirectUrl);
            exit();
        }
        $page_name = $request->slug??'';
        $data = [];
        $seo_data = [];
        if(!empty($page_name)) {
            $select_cols = '*';
            $cms = CustomHelper::getCMSPage($page_name, $select_cols);
            if(isset($cms['id']) && $cms['cms_type'] == 'page') {
                $data['cms'] = $cms;

                $meta_title = $cms['meta_title']??'';
                if(empty($meta_title)) {
                    $meta_title = $cms['title']??'';
                }
                $meta_description = $cms['meta_description']??'';
                $meta_keyword = $cms['meta_keyword']??'';

                $data['page_name'] = $page_name;
                $data['blogs'] = Blog::where(['is_home'=>1,'type'=>'blogs'])->where('status',1)->limit(6)->get();
                $data['faqs'] = Faq::where('status',1)->orderBy('sort_order','desc')->get();
                $data['airlines'] = PackageAirline::where('status',1)->get();
                if(!empty($cms['template'])) {
                    //return view('.templates.'.$cms['template'], $data);
                    return view($this->theme.'.templates.'.$cms['template'], $data);
                }

                $breadcrumbData = [];
                $CMS_VUE_JS = ["viaggindia"];
                if(in_array(config('custom.theme_name'), $CMS_VUE_JS)) {
                $breadcrumbData[] = ['url'=>'/','label'=>'Casa'];
                } else {
                $breadcrumbData[] = ['url'=>'/','label'=>'Home'];
                }
                $breadcrumbData[] = ['label'=>$cms['title']];
                $data['breadcrumbData'] = $breadcrumbData;

                $seo_data['meta_title'] = $meta_title;
                $seo_data['meta_description'] = $meta_description;
                $seo_data['meta_keyword'] = $meta_keyword;
                $banner_image = $cms->banner_image??'';
                if($banner_image) {
                    $banner_image = url('/storage/themes/'.$banner_image);
                } else {
                    $banner_image = asset(config('custom.assets').'/images/default_common_banner.jpg');
                }

                $cms_id = $cms['id']??'';

                $faq_cms = Faq::where('tbl_name','=','cms_pages')->where('status',1)->where('tbl_id',$cms_id)->orderBy("sort_order", "ASC");
                $faq_row = $faq_cms->take(50)->get()->toArray();
                
                $data['faq_row'] = $faq_row;
                $seo_data['banner_image'] = $banner_image;
                $data['seo_data'] = $seo_data;
                View::share('seo_data', $seo_data);
               
                // return view('.cms_page', $data);
                return Inertia::render('cms-page', $data);
            } else {
                if($page_name == 'myadmin') {
                    $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
                    return redirect($ADMIN_ROUTE_NAME);
                }
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function contact(Request $request){
        $data = [];
        $seo_data = [];
        $banner_image = '';
        $meta_title = '';
        $meta_description = '';
        $meta_keyword = '';
        $identifier = '';
        $limit = $this->limit;

        $seo_tags = CustomHelper::getSeoModules('contact_us');
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
            $meta_title = $seo_data['meta_title'];
            $meta_description = $seo_data['meta_description'];
            $meta_keyword = $seo_data['meta_keyword'];            
        }
        $seo_data['banner_image'] = $banner_image;
        $data['seo_data'] = $seo_data;
        View::share('seo_data', $seo_data);
        // prd($banner_image);

        $websiteSetting = CustomHelper::getSettings(['CONTACT_RESERVATION_OFFICE','CONTACT_MAIN_OFFICE','CONTACT_DELHI_OFFICE','CONTACT_RESERVATION_OFFICE_IFRAME','CONTACT_MAIN_OFFICE_IFRAME','CONTACT_DELHI_OFFICE_IFRAME']);

        $contact_reservation_office = $websiteSetting['CONTACT_RESERVATION_OFFICE']??'';
        $CONTACT_RESERVATION_OFFICE_IFRAME = $websiteSetting['CONTACT_RESERVATION_OFFICE_IFRAME']??'';
        $contact_main_office = $websiteSetting['CONTACT_MAIN_OFFICE']??''; 
        $CONTACT_MAIN_OFFICE_IFRAME = $websiteSetting['CONTACT_MAIN_OFFICE_IFRAME']??'';
        $contact_delhi_office = $websiteSetting['CONTACT_DELHI_OFFICE']??'';
        $CONTACT_DELHI_OFFICE_IFRAME = $websiteSetting['CONTACT_DELHI_OFFICE_IFRAME']??'';

        $params=[];
        $params = [
            'CONTACT_RESERVATION_OFFICE' => $contact_reservation_office,
            'CONTACT_RESERVATION_OFFICE_IFRAME' => $CONTACT_RESERVATION_OFFICE_IFRAME,
            'CONTACT_MAIN_OFFICE' => $contact_main_office,
            'CONTACT_MAIN_OFFICE_IFRAME' => $CONTACT_MAIN_OFFICE_IFRAME,
            'CONTACT_DELHI_OFFICE' => $contact_delhi_office,
            'CONTACT_DELHI_OFFICE_IFRAME' => $CONTACT_DELHI_OFFICE_IFRAME
        ];
        $data['contactDetails'] = $params;
        //prd($data);
        return Inertia::render('contact', $data);
    }

    // Add Newesletter Code Here......
    public function newsletterSubscribe(Request $request){

        $response = [];
        $response['success'] = false;
        $message = '';
        if($request->method() == 'POST' || $request->method() == 'post'){
           //prd($request->toArray());
            $rules = [];
            $validation_msg = [];
            $rules['name'] = 'nullable|regex:/^[\pL\s\-]+$/u';
            $rules['email'] = 'required|max:255|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:newsletter_subscribers,email';
            $rules['phone'] = 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/';

            $validation_msg['email.required'] = 'The Email field is required.';
            $validation_msg['email.regex'] = 'The Email format is invalid.';
            $validation_msg['email.unique'] = 'You are already subscribed our newsletter.';
            $validation_msg['phone.regex'] = 'The Whatsapp Number format is Invalid.';

            $validator = Validator::make($request->all(), $rules,$validation_msg);
            if($validator->fails()) {
                   return Response::json(array(
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray()
                    ), 400); // 400 being the HTTP code for an invalid request.
            }else{

            $name = $request->name??'';
            $email = $request->email??'';
            $phone = $request->phone??'';
            $existEmail =  DB::table('newsletter_subscribers')->select('id')->where(['email'=>$email])->first();
            //prd($existEmail);
            if(!empty($existEmail)){
                $existEmail=true;
            }
            if(!$existEmail) {
                $id = DB::table('newsletter_subscribers')->insert(['name'=>$name,'email'=>$email,'phone'=>$phone, 'status'=>1]);
                $message = '';  
                //$message = 'Thank You for connecting Us.';
                //$message = Session::flash('alert-success', 'Subscribed Successfully.'); 
                $message = 'Subscribed Successfully.';
                return Response::json(array(
                        'success' => true,
                        'redirect_url' => url('/thankyou'),
                        'message' => $message,
                ), 200); 
            }
           /* elseif($existEmail){
                $message = 'You are already subscribed our newsletter.';
                return Response::json(array(
                    'success' => true,
                    'message' => $message,
                ), 200); 
            }*/else{
                $response['message'] = $e->getMessage();
                $error = $e->getMessage();
                $message = 'Something went wrong, please try again.'.$error;
                return Response::json(array(
                    'success' => false,
                    'message' => $message,
                ), 200);
            }
        }
            
        } // close post
        //echo json_encode($response); exit;
        //return Response::json_encode($response, 200);
        return Response::json($response, 200);
    }

    public function thankYou(Request $request){
        $data = [];
        $limit = $this->limit;

        $success_message = '';
        if(session()->has('alert-success')) {
            $success_message = session()->get('alert-success');
            session()->forget('alert-success');
        }
        $data['success_message'] = $success_message;
        //prd($success_message);

       
        $seo_data = [];

        $meta_title = '';
        $meta_description = '';
        $meta_keyword = '';
        $breadcrumb_title = '';

        $seo_tags = CustomHelper::getSeoModules('thank_you');
        $banner_image = '';
        $identifier = '';
        if(!empty($seo_tags)) {
            $identifier = $seo_tags->identifier;
            $seo_data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
            $seo_data['page_url_label'] = $seo_tags->page_url_label??'Thank You';
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
            $meta_title = $seo_data['meta_title'];
            $meta_description = $seo_data['meta_description'];
            $meta_keyword = $seo_data['meta_keyword'];            
        }
        $seo_data['banner_image'] = $banner_image;
        $data['seo_data'] = $seo_data;
        View::share('seo_data', $seo_data);

        return Inertia::render('thankyou', $data);
        //return view('.thankyou', $data);
    }


    public function payOnline(Request $request){

        $data = [];
        $method = $request->method();

        if($method == 'POST') {

           $action = $request->action ?? '';
           $userId = Auth::user()->id ?? 0;
           $is_agent = Auth::user()->is_agent??'';
           $approved_agent = Auth::user()->approved_agent??'';
           $agent_id = 0;
           if($is_agent == 1 && $approved_agent == 1){
               $agent_id = Auth::user()->id??'';
           }

           if($action == 'wallet'){

            $order_type = 7;

            $nicknames = [];
            $nicknames['amount'] = 'Amount';
            $rules = [];
            $rules['amount'] = 'required|numeric|min:1';

            $name =  Auth::user()->name??'';
            $email = Auth::user()->email??'';
            $phone = Auth::user()->phone??'';
            $country_code = Auth::user()->country_code??91;

           }else{

         //===============
           $order_type = 2;

           $nicknames = [];
           $nicknames['amount'] = 'Amount';
           $nicknames['name'] = 'Name';
           $nicknames['address'] = 'Address';
           $nicknames['description'] = 'Description';
           $nicknames['email'] = 'Email';
           $nicknames['country'] = 'Country';
           $nicknames['phone'] = 'Phone';
           $nicknames['state'] = 'State';
           $nicknames['city'] = 'City';
           $nicknames['zip_code'] = 'Postal Code';

           $rules = [];
           $rules['amount'] = 'required|numeric|min:1';
           $rules['name'] = 'required';
           $rules['address'] = 'required';
           $rules['description'] = 'required';
           $rules['email'] = 'required|email';
           $rules['country'] = 'required';
           $rules['state'] = 'nullable|regex:/^[\pL\s\-]+$/u';
           $rules['city'] = 'nullable|regex:/^[\pL\s\-]+$/u';

           if($request->country_code) {
               $country_code = $request->country_code??91;
           } else if($request->country) {
               $country_code = CustomHelper::GetCountry($request->country,'phonecode')??91;
           }
           if(empty($phone)) {
               if(!empty($country_code) && $country_code != 91) {
                   $rules['phone'] = 'regex:/^\d{4,12}$/';
               } else if($request->phone){
                   $rules['phone'] = 'digits:10';
               } else {
               $rules['phone'] = 'required';
               }
           }
           $zip_code = $request->zip_code??'';
           if($zip_code) {
               if(!empty($country_code) && $country_code != 91) {
                   $rules['zip_code'] = 'max:10|regex:/^[\s\w-]*$/';
               } else {
                   $rules['zip_code'] = 'digits:6';
               }
           }

           $name = $request->name??'';
           $email = $request->email??'';
           $phone = $request->phone??'';
       }

           $validation_msg = [];
           $validation_msg['required'] = ':attribute is required.';
           $validation_msg['numeric'] = ':attribute must be a number.';

           $this->validate($request, $rules, $validation_msg, $nicknames);


           //======================

                       try {

                               $country = $request->country ?? '';
                               if(!empty($country) && is_numeric($country)) {
                                   $country = CustomHelper::GetCountry($country,'name');
                               }


                               $order_no = CustomHelper::genrateOrderNoId($userId);

                               $order = new Order;
                               $order->order_no = $order_no;
                               $order->order_type = $order_type;
                               $order->name = $name;
                               $order->user_id = $userId;
                               $order->agent_id = $agent_id;
                               $order->email = $email;

                               $order->phone = $phone;
                               $order->country_code = $country_code??'';
                               $order->address1 = $request->address ?? '';
                               $order->city = $request->city ?? '';
                               $order->state = $request->state ?? '';
                               $order->country = $country ?? '';
                               $order->zip_code = $request->zip_code ?? '';
                               $order->payment_status = 0;
                               $order->comment =  $request->description ?? '';
                               $order->payment_response = NULL;
                               $order->discount_type = $request->discount_type??'';
                               $order->discount = $request->discount??0;

                               $total_amount = $request->amount ?? 0;
                               if($action == 'wallet'){
                                   $fees = 0;
                                   $WALLET_PAYMENT_GATEWAY_CHARGE = CustomHelper::WebsiteSettings('WALLET_PAYMENT_GATEWAY_CHARGE');
                                   if($request->amount > 0){
                                       $fees = ($WALLET_PAYMENT_GATEWAY_CHARGE / 100) * $request->amount;
                                       $total_amount = $fees + $request->amount;
                                       $order->fees = $fees ?? 0;
                                   }
                               }

                               $order->sub_total_amount = $request->amount ?? 0; //$packagePrice->sub_total_amount??0;
                               $order->total_amount = $total_amount ?? 0;     //$packagePrice->final_amount??0;
                               $order->trip_date = null;


                               $isSaved = $order->save();

                               if($isSaved) {
                                   // $order_id = $order->id;
                                   return redirect('pay_now/'.$order_no);
                               } else {
                                   $message = 'Something went wrong, please try again.';
                                  return back()->with('alert-danger', $message);
                               }
                           } catch (\Exception $e) {
                               $response['message'] = $e->getMessage();
                               $error = $e->getMessage();
                               $message = 'Something went wrong, please try again.'.$error;
                               return back()->with('alert-danger', $message);
                       }

           //====================
           }

       $data['countries'] = Country::where('status',1)->get();
       $data['meta_title'] = 'Online Payment - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
       $data['meta_description'] = 'Online Payment';
    //    return view(config('custom.theme').'.pay-online', $data);
        return Inertia::render('payOnline', $data);

    }

    /*public function widgetSection(Request $request) {
        // prd($request->toArray());
        $data = [];
        $limit = $this->limit;

        $widgetDetail = CustomHelper::getWidget('pronto-per-un-viaggio-unico');
// prd($widgetDetail);
        if(!empty($widgetDetail)){
            $storage = Storage::disk('public');
            $path = 'widgets/';
            $section_heading = $widgetDetail->section_heading;
            $description = $widgetDetail->description;
            $image1 = $widgetDetail->image1;

            $widgetThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

            if(!empty($image1)){
                if($storage->exists($path.$image1)){
                    $widgetThumbSrc = asset('/storage/'.$path.$image1);
                }
            }
        }
            $data['widgetDetail'] = $widgetDetail;
            // prd($data['widgetDetail']->toArray());

            return Inertia::render('Home', $data);
    }*/

    public function pageNotFound(Request $request) {
        $currentUrl = \Request::path();
        $query = UrlRedirection::where(function($q1) use($currentUrl) {
            $q1->where('source_url',$currentUrl);
            $q1->orWhere('source_url',$currentUrl.'/');
            $q1->orWhere('source_url',$currentUrl.'#');
            $q1->orWhere('source_url',$currentUrl.'?');
        });
        $query->where('show',1);
        $url_redirection = $query->first();
        if(!empty($url_redirection) && !empty($url_redirection->destination_url)) {
            $redirectUrl = url($url_redirection->destination_url);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirectUrl);
            exit();
        }
        abort(404);
    }

/* end of controller */
}
