<?php
namespace App\Http\Controllers\js;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\DestinationFlag;
use App\Models\Package;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\UrlRedirection;
use App\Helpers\CustomHelper;
use Inertia\Inertia;
use Storage;
use File;
use Response;
use DB;

class DestinationController extends Controller {

	private $limit;
    private $theme;

    public function __construct() {
    	$this->limit = 40;
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
        $seo_tags = CustomHelper::getSeoModules('destination_listing');
        $seo_data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
        $seo_data['page_url_label'] = $seo_tags->page_url_label??'Destinations';
        $seo_data['page_brief'] = $seo_tags->page_brief??'';
        $seo_data['page_description'] = $seo_tags->page_description??'';
        $seo_data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
        $seo_data['meta_description'] = $seo_tags->meta_description??'';
        $seo_data['meta_keyword'] = $seo_tags->meta_keyword??'';
        $seo_data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
        $banner_image = "";
        if($seo_tags->image) {
            $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
        } else {
            $banner_image = asset(config('custom.assets').'/images/default_common_banner.jpg');
        }
        $seo_data['banner_image'] = $banner_image;

        $flag = $request->flag??'';
        if(!empty($flag)) {
            $flagData = DestinationFlag::where('slug',$flag)->where('status',1)->first();
            if($flagData) {
                $seo_data['page_title'] = $flagData->page_title??'';
                $seo_data['page_brief'] = $flagData->page_brief??'';
                $seo_data['page_description'] = $flagData->page_description??'';
            }else{
                abort(404);
            }
        }
        $data['seo_data'] = $seo_data;
        View::share('seo_data', $seo_data);
        $params = [];
        $search_data = CustomHelper::getSearchData('destination',$params);
        $search_data['module'] = 'Destination';
        $data['search_data'] = $search_data;
        // prd($seo_data);
        return Inertia::render('destinations/Listing', $data);  
    }

    public function ajaxDestinationList(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        
        if($request->limit) {
            $limit = $request->limit??$this->limit;
        } else {
            $limit = $this->limit;
        }

        $flag = $request->flag??'';
        /*$FlagTitle = '';
        $FlagBrief = '';
       if(!empty($flag)) {
            $flagData = DestinationFlag::where('slug',$flag)->where('status',1)->first();
            if($flagData) {
                //$flag = $flagData->id??0;
                $flagData = [
                    'flag_slug' => $flagData->slug,
                    'page_title' => $flagData->page_title??'',
                    'page_brief' => $flagData->page_brief??'',
                ];
                $data['flagData'] = $flagData;
            } else {
                $flag = '-1';
            }
        }*/
        $query = Destination::where('is_city',0)->where('status',1)->where('show',1)->orderBy('sort_order','asc');
        $query->where('parent_id',0);
        $query->where(function($query1){
            $query1->where('is_deleted',0);
            $query1->orWhereNull('is_deleted');
        });
        if($request->featured) {
            $query->where('featured',$request->featured);
        }
        /*if($request->international == 1) {
            $query->where('is_international',1);
        }else{
            $query->where('is_international',0);
        }*/
        if($request->destination_type) {
            $destination_type = $request->destination_type;
            $query->whereHas('destinationType',function($q1) use($destination_type) {
                $q1->where('slug',$destination_type);
            });
        }
        if(!empty($flag)) {
            $query->whereHas('destinationFlags',function($q1) use($flag){
                $q1->where('slug',$flag);
            });
        }
        $records = $query->paginate($limit);
        $records_arr = [];
        $total_count = 0;
        $links = '';
        if($records) {
            $total_count = $records->total();


            $params = [];
            $search_data = CustomHelper::getSearchData('destination',$params);
            $records->withPath(route('destinationListing'));
            $links = $records->appends($search_data)->links('vendor.pagination.bootstrap-4')->render();

            $storage = Storage::disk('public');
            $path = "destinations/";
            foreach($records as $row) {
                // $record = $row->toArray();
                $record = [];
                $imageSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
                if(!empty($row->image) && $storage->exists($path.$row->image)) {
                    $imageSrc = asset('/storage/'.$path.$row->image);
                }
                $package_data = $row->Packages??'';
                $package_data_count = $package_data->where('status',1);
                $package_count = '';
                if(!empty($package_data_count) && count($package_data_count) > 0){
                    $package_count = $package_data_count->count();
                }
                $record['name'] = CustomHelper::wordsLimit($row->name);
                $record['brief'] = $row->brief; //CustomHelper::wordsLimit($row->brief);
                $record['imageSrc'] = $imageSrc;
                $record['package_count'] = $package_count;
                $record['url'] = route('destinationDetail',['slug'=>$row->slug]);
                $records_arr[] = $record;
            }
        }
        $results = [];
        $results['data'] = $records_arr;
        $results['total_count'] = $total_count;
        $results['links'] = $links;
        $response['results'] = $results;
        $response['success'] = true;
        return Response::json($response, 200);    
    }

    public function detail(Request $request) {
        $currentUrl = \Request::path();
        $url_redirection = UrlRedirection::where('source_url',$currentUrl)->where('show',1)->first();
        if(!empty($url_redirection) && !empty($url_redirection->destination_url)) {
            $redirectUrl = url($url_redirection->destination_url);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirectUrl);
            exit();
        }
        $destinationinfo = '';
        $data = [];
        $seo_data = [];
        $limit = $this->limit;
        $slug = $request->slug??'';
        if($slug) {
            $query = Destination::where('slug',$slug)->where('status',1);
            $query->where(function($query1){
                $query1->where('is_deleted',0);
                $query1->orWhereNull('is_deleted');
            });
            $destination = $query->first();

            if($destination) {
                $storage = Storage::disk('public');
                $path = "destinations/";
                $destinationID = $destination->id;
                $seo_tags = CustomHelper::getSeoModules('destination_listing');
                $banner_image = asset(config('custom.assets').'/images/default_common_banner.jpg');
                if($seo_tags->image) {
                    $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
                }
                $data['breadcrumb_title'] = $seo_tags->page_url_label??'Destinations';
                $seo_data['meta_title'] = (!empty($destination->meta_title))?$destination->meta_title:$destination->name;
                $seo_data['meta_description'] = (!empty($destination->meta_description))?$destination->meta_description:'';
                $seo_data['meta_keyword'] = (!empty($destination->meta_keyword))?$destination->meta_keyword:'';
                $data['destination'] = Destination::parseDestination($destination,['size'=>'detail']);

                if(!empty(Auth::check())) {
                    $userId = Auth::user()->id;
                    $package_fab = DB::table('users_favourite_packages')->where('user_id',$userId)->get()->keyBy('package_id')->toArray();
                    $data['package_fab'] = $package_fab;
                } else {
                    $data['package_fab'] = array();
                }

                $query = Package::where('status',1);
                $query->where(function($query1){
                    $query1->where('is_deleted',0);
                    $query1->orWhereNull('is_deleted');
                });
                $query->where('is_activity',0);
                $query->where(function($q1) use($destinationID){
                    $q1->where('destination_id',$destinationID);
                    $q1->orWhere('sub_destination_id',$destinationID);
                    $q1->orWhere(function($q2) use($destinationID){
                        $q2->where('related_destinations','!=','');
                        $q2->where('related_destinations','!=','[]');
                        $q2->whereJsonContains('related_destinations',[(string)$destinationID]);
                    });
                });
                $query->orderBy('featured','DESC');
                $query->orderBy('sort_order','ASC');
                $popular_packages = $query->paginate($limit);
                if($popular_packages) {
                    $popular_packages_arr = [];
                    foreach($popular_packages as $package) {
                        $popular_packages_arr[] = Package::parsePackage($package);
                    }
                    $data['popular_packages'] = $popular_packages_arr;
                    $data['tourPackagesUrl'] = route('tourPackages',$destination->slug);
                }

                $query = Package::where('status',1);
                $query->where(function($query1){
                    $query1->where('is_deleted',0);
                    $query1->orWhereNull('is_deleted');
                });
                $query->where('is_activity',1);
                $query->where(function($query) use($destinationID){
                    $query->where('destination_id',$destinationID);
                    $query->orWhere('sub_destination_id',$destinationID);
                });
                $query->orderBy('featured','DESC');
                $query->orderBy('sort_order','ASC');
                $popular_activities = $query->paginate($limit);
                if($popular_activities) {
                    $popular_activities_arr = [];
                    foreach($popular_activities as $package) {
                        $popular_activities_arr[] = Package::parsePackage($package);
                    }
                    $data['popular_activities'] = $popular_activities_arr;
                    $data['tourActivitiesUrl'] = route('tourActivities',$destination->slug);
                }

                $faq_destinations = Faq::where('tbl_name','=','destinations')->where('status',1)->where('tbl_id',$destinationID)->orderBy("sort_order", "ASC");
                $faqs = $faq_destinations->take(40)->get();
                if($faqs) {
                    $faqs_arr = [];
                    foreach($faqs as $faq) {
                        $faqs_arr[] = $faq->toArray();
                    }
                    $data['faqs'] = $faqs_arr;
                }

                $banners = $destination->destinationBanners??'';
                $banners_arr = [];
                $image_arr = [];
                if($banners) {
                    foreach($banners as $banner) {
                        $bannerThumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
                        $bannerSrc = asset(config('custom.assets').'/images/default_common_banner');
                        if($banner->name && !in_array($banner->name, $image_arr)) {
                            $image_arr[] = $banner->name;
                            if($storage->exists($path.$banner->name)){
                                $bannerSrc = asset('/storage/'.$path.$banner->name);
                                $bannerThumbSrc = asset('/storage/'.$path.'thumb/'.$banner->name);
                            } else {
                                $bannerSrc = $bannerSrc;
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

                $idArr[] = (string) $destinationID;
                $testimonials = Testimonial::whereJsonContains('destination_id',$idArr)->where('status',1)->limit(5)->get();
                if($testimonials) {
                    $testimonials_arr = [];
                    $storage = Storage::disk('public');
                    $path = "testimonials/";
                    $defaultThumbSrc = asset(config('custom.assets').'/images/default_testimonial_icon.jpg');
                    foreach($testimonials as $testimonial) {
                        $row = $testimonial->toArray();
                        $row['url'] = route('testimonialDetails',['slug'=>$testimonial->slug]);
                        $thumbSrc = $defaultThumbSrc;
                        if(!empty($testimonial->image) && $storage->exists($path.$testimonial->image)) {
                            $thumbSrc = asset('/storage/'.$path.'thumb/'.$testimonial->image);
                        }
                        $row['thumbSrc'] = $thumbSrc;
                        $testimonials_arr[] = $row;
                    }
                    $data['testimonials'] = $testimonials_arr;
                    $data['testimonialsUrl'] = route('testimonialListing');
                }
                $seo_data['banner_image'] = $banner_image;
                $seo_data['banners'] = $banners_arr;
                $data['seo_data'] = $seo_data;
View::share('seo_data', $seo_data);
View::share('faqs', $faqs);
                return Inertia::render('destinations/Details', $data);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    

/* end of controller */
}
