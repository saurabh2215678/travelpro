<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CustomHelper;
use App\Models\Banner;
use App\Models\BannerImageGallery;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\Blog;
use App\Models\User;
use App\Models\Widget;
use App\Models\Package;
use App\Models\PackagePriceInfo;
use App\Models\PackageAccommodation;
use App\Models\AccommodationFeature;
use App\Models\PackageAirline;
use App\Models\Accommodation;
use App\Models\CustomizePackage;
use App\Models\RequestDetail;
use App\Models\Destination;
use App\Models\DestinationInfo;
use App\Models\CmsPages;
use App\Models\ThemeCategories;
use App\Models\CustomerReview;
use App\Models\Download;
use App\Models\Other;
use App\Models\Enquiry;
use App\Models\SeoMetaTag;
use App\Models\TeamManagement;
use App\Models\EmailTemplate;
use App\Models\UrlRedirection;
use App\Models\Image;
use App\Models\BlogCategory;
use App\Models\BlogToCategories;
use App\Models\EnquiriesMaster;
use Mail;
use DB;
use Validator;
use Storage;
use PDF;

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


        
        // $data['national_destinations'] = Destination::where('is_city',0)->where('is_international',0)->where('status',1)->where('featured',1)->orderBy('sort_order','asc')->paginate(7);
        // $data['international_destinations'] = Destination::where('is_city',0)->where('is_international',1)->where('status',1)->where('featured',1)->orderBy('sort_order','asc')->paginate(7);
        
        // $data['tour_categories'] = ThemeCategories::where('status',1)->where('featured',1)->limit(10)->orderBy('sort_order','asc')->get();
        // $data['activityPackages'] = Package::with('packagePrices')->where('is_activity',1)->where('status',1)->where('featured',1)->limit(10)->orderBy('featured','desc')->orderBy('sort_order','asc')->get();
        //GroupTour
      /*  $data['featuredPackages'] = Package::with('packagePrices')->where('is_activity',0)->where('status',1)->where('featured',1)
                                        ->whereHas('packageFlags',function($q1) {
                                            $q1->where('flag_id',2);
                                        })
                                    // ->where('inladakh',2)
                                    ->orderBy('featured','desc')->orderBy('sort_order','asc')->limit(10)->get();*/
                                    
      /*  $data['nonFeaturedPackages'] = Package::with('packagePrices')->where('is_activity',0)->where('status',1)->where('featured',1)
                                    ->whereHas('packageFlags',function($q1) {
                                        $q1->where('flag_id',1);
                                    })
                                    // ->where('inladakh',1)
                                    ->orderBy('featured','desc')->orderBy('sort_order','asc')->limit(10)->get();*/
/*
      $data['international_packages'] = Package::where('is_activity',0)->where('status',1)->where('featured',1)
                                    ->whereHas('packageDestination',function($q1) {
                                        $q1->where('is_international',1);
                                    })
                                    // ->where('inladakh',1)
                                    ->orderBy('featured','desc')->orderBy('sort_order','asc')->limit(10)->get();

     $data['national_packages'] = Package::where('is_activity',0)->where('status',1)->where('featured',1)
                                    ->whereHas('packageDestination',function($q1) {
                                        $q1->where('is_international',0);
                                    })
                                    // ->where('inladakh',1)
                                    ->orderBy('featured','desc')->orderBy('sort_order','asc')->limit(10)->get();*/

        // $popular_tours = Package::where('is_activity',0)->where('status',1)->where('featured',1)->orderBy('featured','desc')->orderBy('sort_order','asc')->limit(10)->get();
        // $data['popular_tours'] = $popular_tours;

        
      /*  $getBestHPackage = [];
        $best_h_packages = '';
        $getBestHPackage = DB::table('themes')->where('slug','best-honeymoon-packages')->first();
        $data['cat_details2'] = $getBestHPackage;
        if(isset($getBestHPackage) && !empty($getBestHPackage)){
        $getbestHpackages = DB::table('package_themes')->where('theme_id',$getBestHPackage->id)->get();
        $xArray=[];
        if(isset($getbestHpackages) && !empty($getbestHpackages)){
            foreach ($getbestHpackages as $key => $get_id) {
                $xArray[]=$get_id->package_id;
            }
        }
        $best_h_packages = Package::where('is_activity',0)->where('status',1)->where('featured',1)->whereIn('id', $xArray)->orderBy('featured','desc')->orderBy('sort_order','asc')->limit(10)->get();
        }
        $data['best_h_packages'] = $best_h_packages;*/
        
      /*  $getFamilyHPakcage = [];
        $family_h_packages = '';
        $getFamilyHPakcage = DB::table('themes')->where('slug','family-holiday-tour-packages')->first();
        $data['cat_details3'] = $getFamilyHPakcage;
        if(isset($getFamilyHPakcage) && !empty($getFamilyHPakcage)){
        $getFamilyHapackages = DB::table('package_themes')->where('theme_id',$getFamilyHPakcage->id)->get();
        $xArray=[];
        if(isset($getFamilyHapackages) && !empty($getFamilyHapackages)){
            foreach ($getFamilyHapackages as $key => $get_id) {
                $xArray[]=$get_id->package_id;
            }
        }
        $family_h_packages = Package::where('is_activity',0)->where('status',1)->where('featured',1)->whereIn('id', $xArray)->orderBy('featured','desc')->orderBy('sort_order','asc')->limit(10)->get();
        }
        $data['family_h_packages'] = $family_h_packages;*/
 
        // $data['featuredAccommodations'] = Accommodation::with('accommodationDestination')->where('status',1)->where('featured',1)->orderBy('sort_order','asc')->limit(10)->get();

        // $testimonialData = Testimonial::with('testimonialImages')->where('featured',1)->where('status',1)->orderBy('sort_order','asc');
        // $testimonialData->where(function($query){
        //   $query->where('is_deleted', 0);
        //   $query->orWhereNull('is_deleted');
        // });
        // $data['testimonials'] = $testimonialData->limit(10)->get();

        $hpp = CmsPages::where(['status'=>1, 'slug'=>'honeymoon-package-photography'])->first();
        if(isset($hpp) && !empty($hpp)){
          $data['honeymoon_package_photography'] = CmsPages::where(['status'=>1, 'parent_id'=>$hpp->id])->get();
        }
        $data['DestinationWeddings'] = CmsPages::where(['status'=>1, 'slug'=>'destination-weddings'])->first();
        $celebrities_data = CmsPages::where(['status'=>1, 'slug'=>'celebrities'])->first();
        if(isset($celebrities_data) && !empty($celebrities_data)){
          $data['celebrities_images'] = Image::where(['status'=>1, 'tbl_name'=>'cms_pages', 'tbl_id'=>$celebrities_data->id])->get();
        }
        $category_data = BlogCategory::select('id','slug')->where('slug','video-blog')->first();
        $category_id = $category_data->id ?? '';
        $cat_slug = $category_data->slug ?? '';
        $blogArr = BlogToCategories::where('category_id',$category_id)->pluck('blog_id')->toArray();
        if(isset($blogArr) && !empty($blogArr)){
            $video_blogs = Blog::select('blogs.*')->where('blogs.is_deleted',0)->where('blogs.status',1)->where('blogs.type','=','blogs')->orderBy('blogs.sort_order','DESC')->orderBy('blogs.featured','DESC')->whereIn('id',$blogArr)->get();
            $data['video_blogs'] = $video_blogs;
            $data['cat_slug'] = $cat_slug;
        }
        $data['popularNews'] = Blog::where(['popular'=>1,'type'=>'news'])->where('status',1)->first();

        //DB::enableQueryLog();
       /* $blogData = Blog::where('status',1)->where('featured',1)->orderBy('sort_order','desc')->orderBy('featured','desc');
        $blogData->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        $data['blogs'] = $blogData->limit(4)->get();*/
        // dd(DB::getQueryLog());

      /*  $teamData = TeamManagement::where('status',1)->where('featured',1)->orderBy('sort_order','asc')->orderBy('featured');
        $teamData->where(function($query){
            $query->where('is_deleted', 0);
            $query->orWhereNull('is_deleted');
        });
        $data['teams'] = $teamData->limit(10)->get();*/
        if(!empty(Auth::check())) {
            $userId = Auth::user()->id;
            $package_fab =DB::table('users_favourite_packages')->where('user_id',$userId)->get()->keyBy('package_id')->toArray();
            $data['package_fab'] = $package_fab;
        } else {
            $data['package_fab'] =array();
        }

        $seo_tags = CustomHelper::getSeoModules('home');
        $meta_title = $seo_tags->meta_title?? '';
        $meta_description = $seo_tags->meta_description?? '';
        $meta_keyword = $seo_tags->meta_keyword?? '';
        if($meta_title){
            $meta_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        }

        $data['homeCms'] =[];
        $data['seo_tags'] = $seo_tags;
        // $data['meta_title'] = $meta_title;
        // $data['meta_description'] = $meta_description;
        // $data['meta_keyword'] = $meta_keyword;
        $seo_tags = CustomHelper::getSeoModules('home');
        $data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
        $data['page_url_label'] = (!empty($seo_tags->page_url_label))?$seo_tags->page_url_label:$seo_tags->page_title;
        $data['page_heading'] = $seo_tags->page_title??'';
        $data['page_brief'] = $seo_tags->page_brief??'';
        $data['page_description'] = $seo_tags->page_description??'';
        $data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
        $data['meta_description'] = $seo_tags->meta_description??'';
        $data['meta_keyword'] = $seo_tags->meta_keyword??'';
        $data['other_meta_tag'] = $seo_tags->other_meta_tag??'';

        $data['imageBanner'] = Banner::where('status',1)->where('slug','home-page-main-banner')->first();
        $data['videoBanner'] = Banner::where('status',1)->where('slug','home-page-banner-video')->first();
        return view($this->theme.'.index', $data);
    }

    public function ajaxHomeSection(Request $request){
        $response = [];
        $type = $request->type ?? '';
       
        $homeCms = [] ; //HomeCms::orderBy('sort_order','asc')->get();
        $params = [];   
         foreach ($homeCms as $key => $value) {
            $title = $value->show_title;
            $params = $value->params ? json_decode($value->params): '';
            $params = (array)$params;
            $params['section_title'] = $title;

            if($value->module == 'Packages'){      
                $section = CustomHelper::packageSection($params);
                $response[$value->name] = $section;
           
            }elseif($value->module == 'Activities'){
                $params['is_activity'] = 1;
                $section = CustomHelper::packageSection($params);
                $response[$value->name] = $section;
            
            } elseif($value->module == 'theme'){
                $params['is_activity'] = 1;
                $section = CustomHelper::themeCategoriesSection($params);
                $response[$value->name] = $section;
            
            }elseif($value->module == 'accommodation'){
                $params['is_activity'] = 1;
                $section = CustomHelper::accommodationSection($params);
                $response[$value->name] = $section;
            
            }elseif($value->module == 'Testimonials'){
                $params['is_activity'] = 1;
                $section = CustomHelper::testimonialSection($params);
                $response[$value->name] = $section;
            
            }elseif($value->module == 'Blog'){
                $params['is_activity'] = 1;
                $section = CustomHelper::blogSection($params);
                $response[$value->name] = $section;
           
            }elseif($value->module == 'Team'){
                $params['is_activity'] = 1;
                $section = CustomHelper::teamSection($params);
                $response[$value->name] = $section;
            }

         }

         $response['success'] = true;
        /* $response['team_section'] = $team_section;
         $response['blog_section'] = $blog_section;
         $response['themeCategoriese_section'] = $themeCategoriese_section;
         $response['activity_section'] = $activity_section;
         $response['package_section'] = $package_section;
         $response['nonfeaturedpackage_section'] = $nonfeaturedpackage_section;
         $response['accommodations_section'] = $accommodations_section;
         $response['testimonial_section'] = $testimonial_section;

*/
         return response()->json($response);

    }



    //Experiences static page
    public function experiences(Request $request){
        $limit = $this->limit;
        //prd($request->slug);
        $data = [];
        $destinationSlug = isset($request->slug) ? $request->slug:"";
       
        $destinationDetails = Destination::where('is_city',0)->where('slug',$destinationSlug)->first();
        if(empty($destinationDetails)){
            abort(404);
        }
        $data['destinationDetails'] = $destinationDetails;

        $isMobile = CustomHelper::isMobile();
        $bannerType = 'desktop';
        if($isMobile){
            $bannerType = 'mobile';
        }
        $bannerWhere = [];
        $bannerWhere['page'] = 'tour-packages/india';
        $bannerWhere['status'] = 1;
        $bannerWhere['device_type'] = $bannerType;
        $banners = BannerImageGallery::where($bannerWhere)->orderBy('sort_order')->limit($limit)->get();
        $data['banners'] = $banners;

        // $query = Package::query();

        // $query->select('packages.*');
        // // Join with users_favourite_packages Table
        //     $query = $query->leftJoin('users_favourite_packages as ufp',function ($join) 
        // {
        //     $join->on('ufp.package_id', '=' , 'packages.id');
        // });
        // $query->addSelect('ufp.package_id as users_fav_pack');
        if(!empty(Auth::check())){
        $userId = Auth::user()->id;
        $package_fab =DB::table('users_favourite_packages')->where('user_id',$userId)->get()->keyBy('package_id')->toArray();
        $data['package_fab'] = $package_fab;
        }else{
        $data['package_fab'] =array();
        }
        $package = Package::where('is_activity',0)->where('status',1)->where('destination_id',$destinationDetails->id)->groupBy('packages.id')->orderBy('created_at','desc')->get();

        $data['popular_packages'] =$package;



        /*$data['popular_packages'] = Package::where('status',1)->where('destination_id',$destinationDetails->id)->orderBy('created_at','desc')->get();*/
        //prd($data['popular_packages']);

        $themes = ThemeCategories::where('status',1)->get();
        $data['themes'] = $themes;

        $destinations = Destination::where('is_city',0)->where('status',1)->get();
        $data['destinations'] = $destinations;
        
        $destination = Destination::where('is_city',0)->where('status',1)->where('slug',$destinationSlug)->first();
        $data['btDestination'] = (!empty($destination)) ? $destination->children->take(6) :[];

        $destination_infos = DestinationInfo::where('status',1)->where('destination_id',$destinationDetails->id)->get();
        $data['destination_infos'] = $destination_infos;
        //prd($destination_infos);

        // Add CMS Code
        $segments1 = $request->segment(1);
        if(!empty($segments1)){
           $page_name = $segments1;
           $select_cols = '*';
           $cms_data = CustomHelper::getCMSPage($page_name, $select_cols);
           //prd($cms_data);
           if(isset($cms_data['cms']) && !empty($cms_data)){
               $meta_title = (isset($cms_data['meta_title']))?$cms_data['meta_title']:$cms_data['name'];
               $meta_description = (isset($cms_data['meta_description']))?$cms_data['meta_description']:'';
               $meta_keyword = (isset($cms_data['meta_keyword']))?$cms_data['meta_keyword']:'';
               if(empty($meta_title)){
                   $meta_title = (isset($cms_data['title']))?$cms_data['title']:'';
               }
               if(empty($meta_description)){
                   $meta_title = (isset($cms_data['meta_description']))?$cms_data['meta_description']:'';
               }
               if(empty($meta_keyword)){
                   $meta_title = (isset($cms_data['meta_keyword']))?$cms_data['meta_keyword']:'';
               }
               $data['meta_title'] = $meta_title;
               $data['meta_description'] = $meta_description;
               $data['meta_keyword'] = $meta_keyword;
               $data['cms'] = $cms_data;
           }
           else{
                abort(404);
           }
        }    
        return view($this->theme.'.experiences', $data);
    }

    //Testimonial static page
    // public function testimonials(){

    //     $data = [];
    //     $testimonial = '';
    //     $limit = $this->limit;
    //     $data['meta_title'] = 'Testimonial - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    //     $data['meta_description'] = 'Testimonial Description';
    //     $testimonials = Testimonial::where('status',1)->paginate(10);
    //     $data['testimonials'] = $testimonials;
    //     return view($this->theme.'.testimonials', $data);
    // }

    public function downLoads(Request $request){

        $data = [];
        $testimonial = '';
        $limit = $this->limit;
        $data['meta_title'] = 'Downloads - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Downloads Description';
        $downloads = Download::where('status',1)->paginate(10);
        $data['downloads'] = $downloads;
        return view($this->theme.'.downloads', $data);
    }

    public function thankYou(Request $request){
        // prd($request->toArray());
        $data = [];
        $testimonial = '';
        $limit = $this->limit;

        $success_message = '';
        if(session()->has('alert-success')) {
            $success_message = session()->get('alert-success');
            session()->forget('alert-success');
        }
        $data['success_message'] = $success_message;

        $data['meta_title'] = 'Thank You - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Thank You - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        return view($this->theme.'.thankyou', $data);
    }

    // public function login(Request $request){

    //     $data = [];
    //     $limit = $this->limit;
    //     $data['meta_title'] = 'Login - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    //     $data['meta_description'] = 'Login Description';
        
    //     return view($this->theme.'.login', $data);
    // }

    // public function singup(Request $request){

    //     $data = [];
    //     $limit = $this->limit;
    //     $data['meta_title'] = 'Singup - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    //     $data['meta_description'] = 'Singup Description';
        
    //     return view($this->theme.'.singup', $data);
    // }

    public function other(Request $request){

        $data = [];
        $testimonial = '';
        $limit = $this->limit;
        $data['meta_title'] = 'Others - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Others Description';
        $others = Other::where('status',1)->paginate(10);
        $data['others'] = $others;
        return view($this->theme.'.others', $data);
    }

    //Group partner static page
    public function group_partner(){

        $data = [];
        $limit = $this->limit;
        $data['meta_title'] = 'Group Partner - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Group Partner - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');

        return view($this->theme.'.group_partner', $data);
    }

    // public function downLoads(){

    //     $data = [];
    //     $limit = $this->limit;
    //     $data['meta_title'] = 'Downloads - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    //     $data['meta_description'] = 'Downloads Description';

    //     return view($this->theme.'.downloads', $data);
    // }

    // public function other(){

    //     $data = [];
    //     $limit = $this->limit;
    //     $data['meta_title'] = 'Others - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    //     $data['meta_description'] = 'Others Description';

    //     return view($this->theme.'.others', $data);
    // }

    public function customerReview_31jan2023(Request $request) {
        $data = [];
        $limit = $this->limit;
        $data['meta_title'] = 'Customer Review - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Customer Review Description';
        if($request->method() == 'POST') {
            // prd($request->toArray());
            $rules['trip_name_duration'] = 'required';
            $rules['local_guide_name'] = 'required';
            $rules['driver_name'] = 'required';
            $rules['your_name'] = 'required';
            $rules['email'] = 'required|email';
            $rules['address'] = 'required|max:255';
            $message = [];
            $validator = Validator::make($request->all(), $rules,$message);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            } else {
                $form_data = [];
                $getWebId = EnquiriesMaster::where('status',1)->where(['type'=>'lead-source', 'slug'=>'website'])->first();
                $form_data['Trip Name & Duration'] = $request->trip_name_duration??'';
                $form_data['Local Guide Name'] = $request->local_guide_name??'';
                $form_data['Driver\'s Name'] = $request->driver_name??'';
                $form_data['Was Your Guide Courteous'] = ($request->courteous??'0').'/5 Rating';
                $form_data['Was Your Guide Helpful'] = ($request->helpful??'0').'/5 Rating';
                $form_data['Was Your Guide Informative'] = ($request->informative??'0').'/5 Rating';
                $form_data['Was Your Guide Comments'] = $request->guide_comments??'';
                $form_data['Sightseeing Tour'] = ($request->sightseeing_tour??'0').'/5 Rating';
                $form_data['Sightseeing Tour Comments'] = $request->sight_tour_comments??'';
                $form_data['Transportation Driver'] = ($request->driver??'0').'/5 Rating';
                $form_data['Transportation Vehicle'] = ($request->vehicle??'0').'/5 Rating';
                $form_data['Transportation Comments'] = $request->transportation_comment??'';
                
                $form_data['Accommodation Comfort'] = ($request->comfort??'0').'/5 Rating';
                $form_data['Accommodation Services'] = ($request->services??'0').'/5 Rating';
                $form_data['Accommodation Food'] = ($request->food??'0').'/5 Rating';
                $form_data['Accommodation Comments'] = $request->accommodation_comments??'';
                $form_data['Trekking Food'] = ($request->foods??'0').'/5 Rating';
                $form_data['Trekking Camp Staff'] = ($request->camp_staff??'0').'/5 Rating';
                $form_data['Trekking Pony/Yak'] = ($request->pony_yak??'0').'/5 Rating';
                $form_data['Trekking Comments'] = $request->trekking_comments??'';
                $form_data['Garbage Disposal On Tour'] = ($request->on_tour??'0').'/5 Rating';
                $form_data['Garbage Disposal On Trek'] = ($request->on_trek??'0').'/5 Rating';
                $form_data['Garbage Disposal Comments'] = $request->garbage_disposal_coomments??'';
                $form_data['Outstanding Performance By Any Staff On The Trip/Name'] = $request->name??'';
                $form_data['If So Why?'] = $request->if_so_why??'';
                $form_data['What Was The Highlight Of Trip For You?'] = $request->highlight_of_trip??'';
                $form_data['What Was The Low Point?'] = $request->low_point??'';

                $staff_on_the_trip = $request->staff_on_the_trip??'';
                $form_data['Outstanding Performance By Any Staff On The Trip:'] = ($staff_on_the_trip=='1')?'Yes':'No';

                $trip_expectations = $request->trip_expectations??'';
                $form_data['Did Your Trip Live Up To Your Expectations?'] = ($trip_expectations=='1')?'Yes':'No';
                $form_data['Overall Comments'] = $request->overall_comments??'';
                $form_data['Address'] = $request->address??'';

                $record = [];
                $record['is_customer_review'] = '1';
                $record['name'] = $request->your_name??'';
                $record['email'] = $request->email??'';
                // $record['phone'] = $request->phone??'';
                $record['form_data'] = $form_data;
                $record['followup_date'] = date('Y-m-d H:i:s');
                $record['interaction_content'] = 'Customer Review enquiries.';
                $record['lead_source'] = $getWebId->id ?? 0;
                $enquiry = Enquiry::CreateEnquiry($record);
                if($enquiry['success']) {
                    $email_subject = "Customer Review Us Feedback | ".CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                    $email_content = view('emails.customer_review_email',$record)->render();
                    $to_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                    $cc_email = '';
                    $bcc_email = '';
                    $reply_to = $record['email']??'';
                    $params = [];
                    $params['to'] = $to_email;
                    $params['cc'] = $cc_email;
                    $params['bcc'] = $bcc_email;
                    $params['reply_to'] = $reply_to;
                    $params['subject'] = $email_subject;
                    $params['email_content'] = $email_content;
                    // prd($params);
                    $isSent = CustomHelper::sendCommonMail($params);
                    if($isSent) {
                        return redirect('thankyou')->with('alert-success', 'Thank You for connecting Us.');
                    } else {
                        return back()->withInput()->with('alert-danger', 'Error in sending email.');
                    }
                } else {
                    $message = $enquiry['msg'];
                    return back()->withInput()->with('alert-danger', $message);
                }                
            }
        }
        return view($this->theme.'.customer_review', $data);
    }


    public function customerReview(Request $request){

        $data = [];
        $limit = $this->limit;
        
        if($request->method() == 'POST' || $request->method() == 'post'){
            $rules['trip_name_duration'] = 'required';
            $rules['local_guide_name'] = 'required';
            $rules['driver_name'] = 'required';
            $rules['your_name'] = 'required';
            $rules['email'] = 'required|email';
            $rules['address'] = 'required|max:255';
            $message = [];
            $validator = Validator::make($request->all(), $rules,$message);

            if ($validator->fails()){
                return back()->withInput()->withErrors($validator);
            } else {
                $saveHotelArr = [];
                $saveHotelArr['hotel_name_1'] = $request->hotel_name_1 ?? '';
                $saveHotelArr['comfort_1'] = $request->comfort_1 ?? 0;
                $saveHotelArr['services_1'] = $request->services_1 ?? 0;
                $saveHotelArr['food_1'] = $request->food_1 ?? 0;
                $saveHotelArr['accommodation_comments_1'] = $request->accommodation_comments_1 ?? 0;
                
                $saveHotelArr['hotel_name_2'] = $request->hotel_name_2 ?? '';
                $saveHotelArr['comfort_2'] = $request->comfort_2 ?? 0;
                $saveHotelArr['services_2'] = $request->services_2 ?? 0;
                $saveHotelArr['food_2'] = $request->food_2 ?? 0;
                $saveHotelArr['accommodation_comments_2'] = $request->accommodation_comments_2 ?? 0;
                
                $saveHotelArr['hotel_name_3'] = $request->hotel_name_3 ?? '';
                $saveHotelArr['comfort_3'] = $request->comfort_3 ?? 0;
                $saveHotelArr['services_3'] = $request->services_3 ?? 0;
                $saveHotelArr['food_3'] = $request->food_3 ?? 0;
                $saveHotelArr['accommodation_comments_3'] = $request->accommodation_comments_3 ?? 0;
                
                $saveHotelArr['hotel_name_4'] = $request->hotel_name_4 ?? '';
                $saveHotelArr['comfort_4'] = $request->comfort_4 ?? 0;
                $saveHotelArr['services_4'] = $request->services_4 ?? 0;
                $saveHotelArr['food_4'] = $request->food_4 ?? 0;
                $saveHotelArr['accommodation_comments_4'] = $request->accommodation_comments_4 ?? 0;

                $req_data = [];
                $req_data = $request->except(["_token", "back_url", "id", 'hotel_name_1', 'comfort_1', 'services_1', 'food_1', 'accommodation_comments_1', 'hotel_name_2', 'comfort_2', 'services_2', 'food_2', 'accommodation_comments_2', 'hotel_name_3', 'comfort_3', 'services_3', 'food_3', 'accommodation_comments_3', 'hotel_name_4', 'comfort_4', 'services_4', 'food_4', 'accommodation_comments_4']);
                $req_data['hotel_data'] = json_encode($saveHotelArr,true);
                $isSaved = CustomerReview::create($req_data);

                $email_subject = "Customer Review Us Feedback | ".CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $emailData['{trip_name_duration}']= (isset($request->trip_name_duration))?$request->trip_name_duration:'';
                $emailData['{local_guide_name}']= (isset($request->local_guide_name))?$request->local_guide_name:'';
                $emailData['{driver_name}']= (isset($request->driver_name))?$request->driver_name:'';
                $emailData['{courteous}']= (isset($request->courteous))?$request->courteous:0;
                $emailData['{helpful}']= (isset($request->helpful))?$request->helpful:0;
                $emailData['{informative}']= (isset($request->informative))?$request->informative:0;
                $emailData['{guide_comments}']= (isset($request->guide_comments))?$request->guide_comments:'';
                $emailData['{sightseeing_tour}']= (isset($request->sightseeing_tour))?$request->sightseeing_tour:0;
                $emailData['{sight_tour_comments}']= (isset($request->sight_tour_comments))?$request->sight_tour_comments:'';
                $emailData['{driver}']= (isset($request->driver))?$request->driver:0;
                $emailData['{vehicle}']= (isset($request->vehicle))?$request->vehicle:0;
                $emailData['{transportation_comment}']= (isset($request->transportation_comment))?$request->transportation_comment:'';
                $emailData['{comfort}']= (isset($request->comfort))?$request->comfort:0;
                $emailData['{services}']= (isset($request->services))?$request->services:0;
                $emailData['{food}']= (isset($request->food))?$request->food:0;
                $emailData['{accommodation_comments}']= (isset($request->accommodation_comments))?$request->accommodation_comments:'';
                $emailData['{foods}']= (isset($request->foods))?$request->foods:0;
                $emailData['{camp_staff}']= (isset($request->camp_staff))?$request->camp_staff:0;
                $emailData['{pony_yak}']= (isset($request->pony_yak))?$request->pony_yak:0;
                $emailData['{trekking_comments}']= (isset($request->trekking_comments))?$request->trekking_comments:'';
                $emailData['{on_tour}']= (isset($request->on_tour))?$request->on_tour:0;
                $emailData['{on_trek}']= (isset($request->on_trek))?$request->on_trek:0;
                $emailData['{garbage_disposal_coomments}']= (isset($request->garbage_disposal_coomments))?$request->garbage_disposal_coomments:'';
                $emailData['{staff_on_the_trip}']= (isset($request->staff_on_the_trip))?$request->staff_on_the_trip:0;
                $emailData['{trip_expectations}']= (isset($request->trip_expectations))?$request->trip_expectations:0;
                $emailData['{name}']= (isset($request->name))?$request->name:'';
                $emailData['{if_so_why}']= (isset($request->if_so_why))?$request->if_so_why:'';
                $emailData['{highlight_of_trip}']= (isset($request->highlight_of_trip))?$request->highlight_of_trip:'';
                $emailData['{low_point}']= (isset($request->low_point))?$request->low_point:'';
                $emailData['{overall_comments}']= (isset($request->overall_comments))?$request->overall_comments:'';
                $emailData['{trip_name_duration}']= $request->trip_name_duration;
                $emailData['{local_guide_name}']= $request->local_guide_name;
                $emailData['{driver_name}']= $request->driver_name;
                $emailData['{your_name}']= $request->your_name;
                $emailData['{trip_date}']= $request->trip_date ?? '';
                $email = $request->email;
                $emailData['{email}'] = $email;
                $emailData['{address}'] = $request->address;
                $emailData['{hotel_data}'] = json_encode($saveHotelArr,true);
                $emailData['{recommendation}']= $request->recommendation ?? '';
                $emailData['{date}'] = date("l jS \of F Y h:i A");
                $emailData['{hotel_name_1}'] = $request->hotel_name_1 ?? '';
                $emailData['{comfort_1}'] = $request->comfort_1 ?? 0;
                $emailData['{services_1}'] = $request->services_1 ?? 0;
                $emailData['{food_1}'] = $request->food_1 ?? 0;
                $emailData['{accommodation_comments_1}'] = $request->accommodation_comments_1 ?? 0;
                $emailData['{hotel_name_2}'] = $request->hotel_name_2 ?? '';
                $emailData['{comfort_2}'] = $request->comfort_2 ?? 0;
                $emailData['{services_2}'] = $request->services_2 ?? 0;
                $emailData['{food_2}'] = $request->food_2 ?? 0;
                $emailData['{accommodation_comments_2}'] = $request->accommodation_comments_2 ?? 0;
                $emailData['{hotel_name_3}'] = $request->hotel_name_3 ?? '';
                $emailData['{comfort_3}'] = $request->comfort_3 ?? 0;
                $emailData['{services_3}'] = $request->services_3 ?? 0;
                $emailData['{food_3}'] = $request->food_3 ?? 0;
                $emailData['{accommodation_comments_3}'] = $request->accommodation_comments_3 ?? 0;
                $emailData['{hotel_name_4}'] = $request->hotel_name_4 ?? '';
                $emailData['{comfort_4}'] = $request->comfort_4 ?? 0;
                $emailData['{services_4}'] = $request->services_4 ?? 0;
                $emailData['{food_4}'] = $request->food_4 ?? 0;
                $emailData['{accommodation_comments_4}'] = $request->accommodation_comments_4 ?? 0;

                $websiteSettingsArr = CustomHelper::getSettings(['FRONTEND_LOGO']);
                $storage = Storage::disk('public');
                $path = "settings/";
                $logoSrc =asset(config('custom.assets').'/images/logo.png');
                if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
                    $logo = $websiteSettingsArr['FRONTEND_LOGO'];
                    if($storage->exists($path.$logo)){
                        $logoSrc = asset('/storage/'.$path.$logo);
                    }
                }

                $common_logo = '';
                $B2C_HEADER = CustomHelper::WebsiteSettings('B2C_HEADER');
                $b2c_logo_params = array(
                 '{company_logo}' => $logoSrc
             );
                $common_logo .= strtr($B2C_HEADER, $b2c_logo_params);

                $company_name = CustomHelper::WebsiteSettings('COMPANY_NAME');
                $system_title = CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $company_phone = CustomHelper::WebsiteSettings('SALE_PHONE');
                $company_mobile= CustomHelper::WebsiteSettings('BOOKING_QUERIES_NO');
                $company_email = CustomHelper::WebsiteSettings('SALES_EMAIL');

                $sales_phone = CustomHelper::getPhoneHref($company_phone);
                $sales_mobile = CustomHelper::getPhoneHref($company_mobile);
                $sales_email = CustomHelper::getEmailHref($company_email);

                $contact_details = '';
                $B2C_FOOTER = CustomHelper::WebsiteSettings('B2C_FOOTER');
                $b2c_email_params = array(
                    '{company_name}' => $company_name,
                    '{sales_mobile}' => $sales_mobile,
                    '{sales_phone}' => $sales_phone,
                    '{sales_email}' => $sales_email,
                    '{company_title}' => $system_title
                );
                $contact_details .= strtr($B2C_FOOTER, $b2c_email_params);

                $emailData['{header}'] = $common_logo??'';
                $emailData['{contact_details}'] = $contact_details??'';

                $template_slug = 'customer-review-email';
                $email_content_data = EmailTemplate::where('slug', $template_slug)->where('status', 1)->first();
                $email_content = isset($email_content_data->content) ? $email_content_data->content : '';
                $email_content = strtr($email_content, $emailData);
                $email_subject = isset($email_content_data->subject) ? $email_content_data->subject : '';

                if(isset($email_content_data) && !empty($email_content_data)){
                    $REPLYTO = $email;
                    $to_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL');
                    $cc_email = '';
                    $bcc_email = '';
                    if($email_content_data->email_type == 'customer' && $email_content_data->bcc_admin == 1){
						$bcc_email = CustomHelper::WebsiteSettings('ADMIN_EMAIL'); 
					}
                    $params = [];
                    $params['to'] = $to_email;
                    $params['cc'] = $cc_email;
                    $params['bcc'] = $bcc_email;
                    $params['reply_to'] = $REPLYTO;
                    $params['subject'] = $email_subject;
                    $params['email_content'] = $email_content;
                    $params['module_name'] = 'Customer Review';
                    $params['record_id'] = $isSaved->id ?? 0;
                    $isSent = CustomHelper::sendCommonMail($params);
                }

                // $from_email = CustomHelper::WebsiteSettings('FROM_EMAIL');
                // $query_email = CustomHelper::sendEmail('emails.customer_review_email', $emailData, $to=$to_email, $from_email, $REPLYTO = $from_email, $email_subject);

                //$isSaved = ContactEnquiry::create($emailData);
                // $isSaved = DB::table('customer_reviews')->insert($emailData);
                //prd($isSaved);
                 // query_email-->these variable used when email through data send on local used $email only here.

                // if($query_email){
                   return redirect('thankyou')->with('alert-success', 'Thank You for connecting Us.');
            //     }
            //    else{
            //        return back()->with('alert-danger', 'Error in submitting form.');
            //     }
            }
        }

        $data['meta_title'] = 'Customer Review - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Customer Review - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        return view($this->theme.'.customer_review', $data);
    }

    // public function travelInsurance(){

    //     $data = [];
    //     $limit = $this->limit;
    //     $data['meta_title'] = 'Travel Insurance - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    //     $data['meta_description'] = 'Travel Insurance Description';

    //     return view($this->theme.'.travel_insurance', $data);
    // }

    // public function carRentals(){

    //     $data = [];
    //     $limit = $this->limit;
    //     $data['meta_title'] = 'Car Rentals - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    //     $data['meta_description'] = 'Car Rentals Description';

    //     return view($this->theme.'.car_rentals', $data);
    // }
    // CustomHelper::WebsiteSettings('SYSTEM_TITLE') Partner Hotels Details
    // public function details(Request $request){

    //     $data = [];
    //     $limit = $this->limit;
    //     $slug = $request->slug;
    //     $data['meta_title'] = 'Details - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
    //     $data['meta_description'] = 'Details Description';
        
    //     $accomodations = Accommodation::where('status',1)->where('slug',$slug)->get();
    //     $data['featured_accomodations'] = $airline;

    //     return view($this->theme.'.details', $data);
    // }

    public function flights(){

        $data = [];
        $limit = $this->limit;
        $data['meta_title'] = 'Details - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Details Description';
        
        $airline = PackageAirline::where('status',1)->orderBy('sort_order','ASC')->get();
        $data['airlines'] = $airline;

        return view($this->theme.'.flights', $data);
    }

        //customize_this_trip static page
    public function customize_this_trip(Request $request){

        $data = [];
        $limit = $this->limit;
        $package_id = isset($request->package) ? $request->package:"";
        $type = isset($request->type) ? $request->type:"";
        $by = $request->by;
        if(empty($package_id)){
            abort(404);
        }
        $package= Package::where('slug',$package_id)->where('status',1)->first();

        if($package){
        $data['package'] = $package;
        $data['type'] = $type;
        $data['by'] = $by;
        
        $data['meta_title'] = 'Customize This Trip - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Customize This Trip Description';

        if($request->method() == 'POST' || $request->method() == 'post'){
            $rules['name'] = 'required';
            $rules['phone'] = 'required|digits:10|regex:/^([0-9\s\-\+\(\)]*)$/';
            $rules['email'] = 'required|email';
            $rules['content'] = 'required|max:255';
            $rules['country'] = 'required|max:255';
            $message = [];
            $validator = Validator::make($request->all(), $rules,$message);

            if ($validator->fails()){
                return back()->withInput()->withErrors($validator);

            }else{
                
                // $email_subject = "Customize Package Us Enquiry | ".CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $email_subject = "Customize Package Us Enquiry | ".CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $ADMIN_EMAIL = "";

                $emailData['package_id']= (isset($request->package_id))?$request->package_id:1;
                $emailData['zip_code']= (isset($request->zip_code))?$request->zip_code:'';
                $emailData['want_to_travel']= (isset($request->want_to_travel))?$request->want_to_travel:1;
                $emailData['no_of_packs']= (isset($request->no_of_packs))?$request->no_of_packs:'';
                $emailData['duration_of_stay']= (isset($request->duration_of_stay))?$request->duration_of_stay:'';
                $emailData['need_flight']= (isset($request->need_flight))?$request->need_flight:0;
                $emailData['travelled_with']= (isset($request->travelled_with))?$request->travelled_with:0;
                $emailData['name']= $request->name;
                $emailData['phone']= $request->phone;
                $email = $request->email;
                $emailData['email'] = $email;
                $emailData['content'] = $request->content;
                $emailData['country'] = (!empty($request->country)) ? explode(' ',$request->country)['0'] : "";

                $REPLYTO = $email;

                $adminEmail = CustomHelper::getSettings(['ADMIN_EMAIL']);
                $ADMIN_EMAIL = $adminEmail['ADMIN_EMAIL'];

                if(empty($ADMIN_EMAIL)){
                    $ADMIN_EMAIL = config('custom.admin_email');
                }

                $to_email = $ADMIN_EMAIL;
                $from_email = $ADMIN_EMAIL;
                
                //prd($emailData);
                //$viewArr = ['emails.contact', 'emails.contact_text'];
                //$query_email = CustomHelper::sendEmail($viewArr, $emailData, $ADMIN_EMAIL, $ADMIN_EMAIL, $REPLYTO, $email_subject);
                //$html = view('emails.contact', $emailData)->render();
                // $query_email = CustomHelper::sendEmail('emails.contact', $emailData, $ADMIN_EMAIL, $ADMIN_EMAIL, $REPLYTO, $email_subject);
                
                $query_email = CustomHelper::sendEmail('emails.customize_package_trip', $emailData, $to=$to_email, $from_email, $REPLYTO = $from_email, $email_subject);

                //$isSaved = ContactEnquiry::create($emailData);
                $isSaved = DB::table('customize_package_enquaries')->insert($emailData);
                //prd($isSaved);
                 // query_email-->these variable used when email through data send on local used $email only here.

                if($query_email){
                   return redirect('thankyou')->with('alert-success', 'Thank You for connecting Us.');
                }
               else{
                   return back()->with('alert-danger', 'Error in submitting form.');
                }
            }
        }
        
        return view($this->theme.'.customize_this_trip', $data);
        }else{
          abort(404);  
        }
    }

    //request_detail static page
    /*public function requestDetails(Request $request){
        
        $data = [];
        $limit = $this->limit;
        $package_id = isset($request->package) ? $request->package:"";
        $type = isset($request->type) ? $request->type:"";
        $by = $request->by;
        if(empty($package_id)){
          abort(404);
        }
        $package= Package::where('slug',$package_id)->where('status',1)->first();
        if($package){
          
        $data['package'] = $package;
        $data['by'] = $by;
        $data['type'] = $type;

        $data['meta_title'] = 'Request Detail - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Request Detail Description';


        if($request->method() == 'POST' || $request->method() == 'post'){
            //prd($request->toArray());

            $rules['name'] = 'required';
            $rules['phone'] = 'required|digits:10|regex:/^([0-9\s\-\+\(\)]*)$/';
            $rules['email'] = 'required|email';
            $rules['country'] = 'required|max:255';
            $message = [];
            $validator = Validator::make($request->all(), $rules,$message);

            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            } else {
                $email_subject = "Request Detail Us Enquiry | ".CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $ADMIN_EMAIL = "";

                $emailData['form_type']= (isset($request->form_type))?$request->form_type:"";
                $emailData['package_id']= (isset($request->package_id))?$request->package_id:1;
                $emailData['zip_code']= (isset($request->zip_code))?$request->zip_code:'';
                $emailData['plan_to_travel']= (isset($request->plan_to_travel))?$request->plan_to_travel:'';
                $emailData['travelled_with']= (isset($request->travelled_with))?$request->travelled_with:0;
                $emailData['name']= $request->name;
                $emailData['phone']= $request->phone;
                $email = $request->email;
                $emailData['email'] = $email;
                $emailData['country'] = (!empty($request->country)) ? explode(' ',$request->country)['0'] : "";

                $REPLYTO = $email;

                $adminEmail = CustomHelper::getSettings(['ADMIN_EMAIL']);
                $ADMIN_EMAIL = $adminEmail['ADMIN_EMAIL'];

                if(empty($ADMIN_EMAIL)){
                    $ADMIN_EMAIL = config('custom.admin_email');
                }

                $to_email = $ADMIN_EMAIL;
                $from_email = $ADMIN_EMAIL;
                $query_email = CustomHelper::sendEmail('emails.request_detail_email', $emailData, $to=$to_email, $from_email, $REPLYTO = $from_email, $email_subject);
                $isSaved = DB::table('request_details')->insert($emailData);
                if($query_email){
                   return redirect('thankyou')->with('alert-success', 'Thank You for connecting Us.');
                }
               else{
                   return back()->with('alert-danger', 'Error in submitting form.');
                }
            }
        }

        return view($this->theme.'.request_detail', $data);
        }else{
            abort(404);
            
        }
    }*/

 //download_itineray static page
    public function download_itineray_form(Request $request){
        
        $data = [];
        $limit = $this->limit;
        $package_id = isset($request->package) ? $request->package:"";
        $by = $request->by ?? '';
        if(empty($package_id)){
            abort(404);
        }
        $package= Package::where('slug',$package_id)->where('status',1)->first();
        $data['package'] = $package;
        $data['by'] = $by;

        $data['meta_title'] = 'Request Detail - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Request Detail Description';


        if($request->method() == 'POST' || $request->method() == 'post'){
            //prd($request->toArray());

            $rules['name'] = 'required';
            $rules['phone'] = 'required|digits:10|regex:/^([0-9\s\-\+\(\)]*)$/';
            $rules['email'] = 'required|email';
            $rules['country'] = 'required|max:255';
            $message = [];
            $validator = Validator::make($request->all(), $rules,$message);

            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            } else {
                $email_subject = "Request Detail Us Enquiry | ".CustomHelper::WebsiteSettings('SYSTEM_TITLE');
                $ADMIN_EMAIL = "";

                $emailData['form_type']= (isset($request->form_type))?$request->form_type:"";
                $emailData['package_id']= (isset($request->package_id))?$request->package_id:1;
                $emailData['zip_code']= (isset($request->zip_code))?$request->zip_code:'';
                $emailData['plan_to_travel']= (isset($request->plan_to_travel))?$request->plan_to_travel:'';
                $emailData['travelled_with']= (isset($request->travelled_with))?$request->travelled_with:0;
                $emailData['name']= $request->name;
                $emailData['phone']= $request->phone;
                $email = $request->email;
                $emailData['email'] = $email;
                $emailData['country'] = (!empty($request->country)) ? explode(' ',$request->country)['0'] : "";

                $REPLYTO = $email;

                $adminEmail = CustomHelper::getSettings(['ADMIN_EMAIL']);
                $ADMIN_EMAIL = $adminEmail['ADMIN_EMAIL'];

                if(empty($ADMIN_EMAIL)){
                    $ADMIN_EMAIL = config('custom.admin_email');
                }

                $to_email = $ADMIN_EMAIL;
                $from_email = $ADMIN_EMAIL;
                $query_email = CustomHelper::sendEmail('emails.request_detail_email', $emailData, $to=$to_email, $from_email, $REPLYTO = $from_email, $email_subject);
                $isSaved = DB::table('request_details')->insert($emailData);
                if($query_email){
                   return redirect('thankyou')->with('alert-success', 'Thank You for connecting Us.');
                }
               else{
                   return back()->with('alert-danger', 'Error in submitting form.');
                }
            }
        }

        return view($this->theme.'.download_itineray_form', $data);
    }

    public function contact(Request $request){
        $data = [];
        $limit = $this->limit;
        $isMobile = CustomHelper::isMobile();
        $bannerType = 'desktop';
        if($isMobile){
            $bannerType = 'mobile';
        }
        $bannerWhere = [];
        $bannerWhere['page'] = 'contact-us';
        $bannerWhere['status'] = 1;
        $bannerWhere['device_type'] = $bannerType;
        $banners = BannerImageGallery::where($bannerWhere)->orderBy('sort_order')->limit($limit)->get();
        $data['banners'] = $banners;
        $data['page_heading'] = "Contact us";

        // Add CMS Code
        /*$segments1 = $request->segment(1);
        if(!empty($segments1)){
           $page_name = $segments1;
           $select_cols = '*';
           $cms_data = CustomHelper::getCMSPage($page_name, $select_cols);
           //prd($cms_data);
           if(isset($cms_data['cms']) && !empty($cms_data)){
               $meta_title = (isset($cms_data['meta_title']))?$cms_data['meta_title']:'';
               $meta_description = (isset($cms_data['meta_description']))?$cms_data['meta_description']:'';
               $meta_keyword = (isset($cms_data['meta_keyword']))?$cms_data['meta_keyword']:'';
               if(empty($meta_title)){
                   $meta_title = (isset($cms_data['title']))?$cms_data['title']:'';
               }
                if(empty($meta_description)){
                   $meta_title = (isset($cms_data['meta_description']))?$cms_data['meta_description']:'';
               }
               if(empty($meta_keyword)){
                   $meta_title = (isset($cms_data['meta_keyword']))?$cms_data['meta_keyword']:'';
               }
               $data['meta_title'] = $meta_title;
               $data['meta_description'] = $meta_description;
               $data['meta_keyword'] = $meta_keyword;
               $data['cms'] = $cms_data;
           }
        }*/

        $meta_title = '';
        $meta_description = '';
        $meta_keyword = '';
        $identifier = '';

        $seo_tags = CustomHelper::getSeoModules('contact_us');
        if(!empty($seo_tags)) {
            $identifier = $seo_tags->identifier??'';
            $data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
            $data['page_url_label'] = $seo_tags->page_url_label??'';
            $data['page_brief'] = $seo_tags->page_brief??'';
            $data['page_description'] = $seo_tags->page_description??'';
            $data['page_url'] = $seo_tags->page_url??'';
            $data['page_detail_url'] = $seo_tags->page_detail_url??'';
            $data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
            $data['meta_description'] = $seo_tags->meta_description??'';
            $data['meta_keyword'] = $seo_tags->meta_keyword??'';
            $data['other_meta_tag'] = $seo_tags->other_meta_tag??'';           
        }
        // prd($data);
        return view($this->theme.'.contact', $data);
    }

// closed here contact page........

// Add Newesletter Code Here......
public function newsletterSubscribe(Request $request){

        $response = [];
        $response['success'] = false;
        $message = '';

        if($request->method() == 'POST' || $request->method() == 'post'){

           //prd($request->toArray());
            $email = $request->email;

            $existEmail =  DB::table('newsletter_subscribers')->select('id')->where(['email'=>$email])->first();
            //prd($existEmail);
            
            if(!empty($existEmail)){

                $existEmail=true;
            }
            if(!$existEmail) {
                $id = DB::table('newsletter_subscribers')->insert(['email'=>$email, 'status'=>1]);
                $response['success'] = true; 
                $response['message'] = 'Subscribed Successfully.';  
            }
            elseif($existEmail){
                $response['message'] = 'You are already subscribed our newsletter.'; 
            }
            
        } // close post
        echo json_encode($response); exit;
    
    }
    // Add Newesletter Code Closed......

    // Add Cms Code Here......
    public function cmsPage(Request $request) {
        $currentUrl = \Request::path();
        $url_redirection = UrlRedirection::where('source_url',$currentUrl)->where('show',1)->first();
        if(!empty($url_redirection) && !empty($url_redirection->destination_url)) {
            $redirectUrl = url($url_redirection->destination_url);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirectUrl);
            exit();
        }
        //$page_name = $request->segment(1);
        $page_name = $request->slug??'';
        $data = [];
        if(!empty($page_name)) {
            $select_cols = '*';
            $cms = CustomHelper::getCMSPage($page_name, $select_cols);
            if(isset($cms['id']) && $cms['cms_type'] == 'page') {
                $cms_id = $cms['id']??'';

                $meta_title = $cms['meta_title']??'';
                if(empty($meta_title)) {
                    $meta_title = $cms['title']??'';
                }
                $meta_description = $cms['meta_description']??'';
                $meta_keyword = $cms['meta_keyword']??'';

                $data['cms'] = $cms;

                $data['page_title'] =  $meta_title??'';
                $data['meta_description'] = $meta_description??'';
                $data['meta_keyword'] = $meta_keyword??'';
                $data['page_name'] = $page_name;
                $data['blogs'] = Blog::where(['is_home'=>1,'type'=>'blogs'])->where('status',1)->limit(6)->get();
                //$data['faqs'] = Faq::where('status',1)->orderBy('sort_order','desc')->get();
                $faq_cms = Faq::where('tbl_name','=','cms_pages')->where('status',1)->where('tbl_id',$cms_id)->orderBy("sort_order", "ASC");
                $faq_row = $faq_cms->take(50)->get();
                
                $data['faq_row'] = $faq_row;
                $data['airlines'] = PackageAirline::where('status',1)->get();
                if(!empty($cms['template'])) {
                    return view($this->theme.'.templates.'.$cms['template'], $data); 
                }
                return view($this->theme.'.cms_page', $data);
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

    public function download_itinerary(Request $request) {
        $download_package_id =  (session()->has('download_package_itinerary'))?session('download_package_itinerary'):0;
        if($download_package_id) {
            $package = Package::where('id',$download_package_id)->first();
            if($package) {
                $package_id = $package->id??0;
                $data = [
                    'title' => 'Welcome',
                    'date' => date('m/d/Y'),
                    'package' => $package
                ];

                $travellers = [];
                $total_amount = 0;
                $display_price = 0;
                $total_pax = 0;
                $action = $request->action??'';
                $is_activity = $package->is_activity??'';
                $module_type_id = 1;
                $module_name = 'package_listing';
                if($is_activity == 1) {
                    $module_type_id = 6;
                    $module_name = 'activity_listing';
                }
                $packagePriceCategory = $package->packagePriceCategory??[];
                $priceCategoryElements = $packagePriceCategory->priceCategoryElements??[];

                $data['package_data'] = Package::parsePackage($package);
                $data['package_id'] = $package_id;
                $data['package_type'] = '';
                $booking_data = (session()->has('booking_data'))?session('booking_data'):[];
                if(isset($booking_data['package_type'])) {
                    $packagePrice = PackagePriceInfo::find($booking_data['package_type']);
                    if($packagePrice) {
                        $data['package_type'] = $packagePrice->id??'';
                        $sumpPriceVal = $packagePrice->final_amount;
                        $original_price = $packagePrice->sub_total_amount;
                        $booking_price = $packagePrice->booking_price??0;
                        $category_choices_record = $packagePrice->category_choices_record??[];
                        $show_choices_record = $packagePrice->show_choices_record??[];

                        $category_choices_record_arr = [];
                        if($category_choices_record) {
                            $category_choices_record_arr = json_decode($category_choices_record,true);
                        }

                        $show_choices_record_arr = [];
                        if($show_choices_record) {
                            $show_choices_record_arr = json_decode($show_choices_record,true);
                        }
                        if(!empty($priceCategoryElements)) {
                            foreach($priceCategoryElements as $pce) {
                                if(array_key_exists('pce'.$pce->id, $show_choices_record_arr)) {
                                    $default = $show_choices_record_arr['pce'.$pce->id]['default']??'';
                                    if($default != 'pce'.$pce->id.'_null') {
                                        $pce_val = $booking_data['pce'.$pce->id]??0;
                                        if($pce_val > 0) {
                                            $pce_price = $category_choices_record_arr['pce'.$pce->id][$pce_val]??0;
                                            $sub_total = $pce_val*$pce_price;
                                            $total_amount += $sub_total;
                                            $total_pax += $pce_val;

                                            $travellers[] = [
                                                'pce_label' => $pce->input_label,
                                                'pce_val' => $pce_val,
                                                'pce_price' => $pce_price,
                                                'sub_total' => $sub_total
                                            ];
                                        }
                                    }
                                }
                            }
                        }

                        $package_price_id = $packagePrice->id??0;
                        $package_query = $packagePrice->Package??[];
                        if($package_price_id) {
                            $ppas = [];
                            $accommodations = PackageAccommodation::where('package_id',$package_id)->where('package_price_id',$package_price_id)->get();
                            if(!empty($accommodations)) {
                                foreach($accommodations as $acc) {
                                    if(!isset($ppas[$acc->accommodation_id])) {
                                        $row = $acc->Accommodation??[];
                                        if(!empty($row)) {
                                            $row = $row->toArray()??[];
                                            $row['accommodation_category'] = $acc->Accommodation->accommodationCategories->title??'';
                                            $ppas[$acc->accommodation_id] = [];
                                            $ppas[$acc->accommodation_id]['accommodation'] = $row;
                                            $ppas[$acc->accommodation_id]['accommodation']['itineraries'] = [];
                                            $ppas[$acc->accommodation_id]['accommodation']['itineraries'][] = $acc->Itenary->toArray()??[];
                                            $ppas[$acc->accommodation_id]['accommodation']['destination'] = $acc->Accommodation->accommodationDestination?$acc->Accommodation->accommodationDestination->name:'';
                                        }
                                    }else {
                                        $ppas[$acc->accommodation_id]['accommodation']['itineraries'][] = $acc->Itenary->toArray()??[];
                                    }
                                }
                            }
                            $data['accommodations_days'] = $accommodations;
                            $data['package_price_accommodations'] = $ppas;
                            $data['accommodation_features'] = AccommodationFeature::where('status' ,1)->get();
                        }

                        $discount_amount = 0;
                        /*Hide discount amount
                        $is_agent = Auth::user()->is_agent??0;
                        if($is_agent == 1) {
                            $group_id = '-1';
                            $discount_category_id = $package->discount_category_id??'';
                            $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$total_amount,$group_id);
                        }*/
                       
                        $sub_total_amount = $total_amount;
                        $total_amount = $total_amount - $discount_amount;
                        if($display_price) {
                            $discount_amount = $display_price - $total_amount;
                            $sub_total_amount = $display_price;
                        }

                        $data['booking_data'] = $booking_data;
                        $data['packagePrice'] = $packagePrice;
                        $data['booking_data']['travellers'] = $travellers;
                        $data['booking_data']['total_pax'] = $total_pax;
                        $data['booking_data']['sub_total_amount'] = $sub_total_amount;
                        $data['booking_data']['discount_amount'] = $discount_amount;
                        $data['booking_data']['total_amount'] = $total_amount;
                    }
                }
                $view = view(config('custom.theme').'/packages/download_itinerary', $data);
                $html = CustomHelper::minify_html_pdf($view->render());
                $pdf = PDF::loadHTML($html);
                $pdf->setPaper('A4', 'portrait');
                $package_name = $package->title ?? 'itinerary';
                return $pdf->download($package_name.'.pdf');
            }
        }
    }

    public function download_itinerary_view(Request $request)
    {
       // $download_package_id =  (session()->has('download_package_itinerary'))?session('download_package_itinerary'):0;

       $slug = (isset($request->slug))?$request->slug:'';
       $package_data = Package::where('slug',$slug)->groupBy('packages.id')->where('packages.status',1)->where('packages.is_deleted',0)->orderBy('created_at','desc')->first();

       // $package_data = Package::where('id',$download_package_id)->first();
       // prd($package_data);
        $data = [
            'title' => 'Welcome',
            'date' => date('m/d/Y'),
            'package' => $package_data
        ]; 

        $view = view(config('custom.theme').'/packages/download_itinerary', $data);
        //dd($view->render());
        $html = CustomHelper::minify_html_pdf($view->render());
        // $pdf = PDF::loadHTML($html);
        //dd($html);
        // $pdf = PDF::loadView(config('custom.theme').'/packages/download_itinerary', $data);
        // $pdf->setPaper('A4', 'portrait');
      
        $package_name = $package_data->title ?? 'package';
        //return $pdf->download($package_name.'.pdf');
        return view(config('custom.theme').'/packages/download_itinerary', $data);
         return $pdf->stream('itinerary.pdf');
    }
    // Add Cms Code Closed......
/* end of controller */
}
