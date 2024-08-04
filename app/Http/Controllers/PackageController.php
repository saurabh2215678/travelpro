<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Testimonial;
use App\Models\Package;
use App\Models\CommonImage;
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
use App\Helpers\CustomHelper;
use Inertia\Inertia;
use DB;
use Validator;

class PackageController extends Controller {

	private $limit;

    public function __construct() {
    	$this->limit = CustomHelper::WebsiteSettings('FRONT_PAGE_LIMIT');
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
        $data['page_title'] = '';
        $data['page_brief'] = '';
        $data['page_description'] = '';
        $data['page_url'] = '';
        $data['page_detail_url'] = '';
        $data['meta_title'] = '';
        $data['meta_description'] = '';
        $data['meta_keyword'] = '';
        $data['other_meta_tag'] = '';

        $limit = $this->limit;
        $DestinationId = '';
        $getDestination = isset($request->package) ? $request->package : '';
        if(!empty($getDestination) && isset($getDestination)) {
            $DestinationData = Destination::where('is_city',0)->where('slug',$getDestination)->first();
            $DestinationId = isset($DestinationData->id) ? $DestinationData->id:"";
        }

        $isMobile = CustomHelper::isMobile();
        $bannerType = 'desktop';
        if($isMobile) {
            $bannerType = 'mobile';
        }
        $bannerWhere = [];
        $bannerWhere['page'] = 'packages';
        $bannerWhere['status'] = 1;
        $bannerWhere['device_type'] = $bannerType;
        $banners = BannerImageGallery::where($bannerWhere)->orderBy('sort_order')->limit($limit)->get();
        $data['banners'] = $banners;

        $testimonial = '';
        // $limit = $this->limit;
        $limit = 50;
        $identifier = 'package_listing';
        $segments1 = $request->segment(1);
        $segments2 = $request->segment(2);
        //$seo_tags = SeoMetaTag::where(['page_url'=>$segments1,'status'=>1])->first();
        $query = SeoMetaTag::where('status',1);
        $query->where(function($q1) use($segments1) {
            $q1->where('page_url',$segments1);
            $q1->orWhere(function($q2) use($segments1) {
                $q2->where('identifier','tour_category');
                $q2->where('page_detail_url',$segments1);
            });
        });
        $seo_tags = $query->first();

        if($segments1 == 'search-packages'){
            $seo_meta_tags_id = $seo_tags->id??0;
            $faq_modules = Faq::where('tbl_name','=','seo_meta_tags')->where('status',1)->where('tbl_id',$seo_meta_tags_id)->orderBy("sort_order", "ASC");
            $faqs = $faq_modules->take(40)->get();
            $data['faqs'] = $faqs;
        }
        $banner_image = '';
        if(!empty($seo_tags)) {
            $identifier = $seo_tags->identifier;
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

        $query = Package::with('packagePrices','packageCategories','packagePriceCategory','packageDestination')->selectRaw('packages.*')->where('packages.status',1);
        $query->where(function($query1){
            $query1->where('packages.is_deleted',0);
            $query1->orWhereNull('packages.is_deleted');
        });
        $is_activity = 0;
        switch ($identifier) {
            case 'activity_listing':
                    $is_activity = 1;
                break;
            case 'tour_category':
                    $tour_category_slug = $request->tour_category;
                    $query->whereHas('packageCategories',function($q1) use($tour_category_slug) {
                        $q1->where('slug',$tour_category_slug);
                    });
                    $tour_category = ThemeCategories::where('status',1)->where('slug',$tour_category_slug)->first();
                    if($tour_category) {
                        if($tour_category->identifier == 'activity'){
                            $is_activity = 1;
                        }
                        $theme_id = $tour_category->id??0;
                        $faq_theme = Faq::where('tbl_name','=','themes')->where('status',1)->where('tbl_id',$theme_id)->orderBy("sort_order", "ASC");
                        $faqs = $faq_theme->take(40)->get();
                        $data['faqs'] = $faqs;
                        // prd($tour_category->toArray());
                        $data['tour_category_id'] = $tour_category->id??0;
                        $data['page_title'] = $tour_category->title??'';
                        if(config('custom.theme_name') == 'holidaybooking'){
                        $data['page_title'] = $tour_category->title.' '."Tour Packages" ??'';
                        }
                        $data['page_brief'] = $tour_category->brief??'';
                        $data['page_description'] = $tour_category->description??'';
                        $data['meta_title'] = $tour_category->page_title??'';
                        $data['meta_description'] = $tour_category->page_description??'';
                        $data['meta_keyword'] = $tour_category->page_keywords??'';
                        $data['search_text'] = $request->search_text??'';
                        if($tour_category->image) {
                            // $banner_image = url('/storage/themes/'.$tour_category->image);
                        }
                    }else{
                        abort(404);
                    }
                break;
            default:
                break;
        }

        if($segments1 == 'tour-packages') {
            $destination_slug = $segments2;
            /*$getId = Package::getSubdestinationId($request->search_text);
            }
            $query->whereHas('packageDestination',function($q1) use($destination_slug) {
                $q1->where('slug',$destination_slug);
            });
            $query->orWhere(function($q1) use ($getId) {
                $q1->whereRaw('case when related_destinations is not null and related_destinations!="[]" and related_destinations!="" then json_contains(related_destinations, \'["'.$getId[0]. '"]\') end');
            });*/
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

                    $destination_id = $destination->id??0;
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
                    $data['page_title'] = $destination->name??'';
                    if(config('custom.theme_name') == 'holidaybooking'){
                        $data['page_title'] = $destination->name.' '."Tour Packages" ??'';
                    }
                    $data['dest_id'] = $destination_id??0;
                    $data['page_brief'] = $destination->brief??'';
                    $data['page_description'] = $destination->description??'';
                    $data['meta_title'] = $destination->package_meta_title??'';
                    $data['meta_description'] = $destination->package_meta_description??'';
                    $data['meta_keyword'] = $destination->package_meta_keywords??'';
                    $data['search_text'] = $request->search_text??'';
                    if(isset($destination->destinationBanners) && !empty($destination->destinationBanners)){
                        $banner_image_name = $destination->destinationBanners[0]->name ?? '';
                        if(isset($banner_image_name) && !empty($banner_image_name)){
                            $banner_image = asset('/storage/destinations/'.$banner_image_name);
                        }
                    }

                    $faq_tour_package = Faq::where('tbl_name','=','destination_packages')->where('status',1)->where('tbl_id',$destination_id)->orderBy("sort_order", "ASC");
                    $faqs = $faq_tour_package->take(40)->get()->toArray();

                    $data['faqs'] = $faqs;
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
            $query->whereHas('packageDestination',function($q1) use($destination_slug) {
                $q1->where('slug',$destination_slug);
            });
            if($destination_slug) {
                $q2 = Destination::where('status',1);                
                $q2->where(function($q3){
                    $q3->where('is_deleted',0);
                    $q3->orWhereNull('is_deleted');
                });
                $q2->where('slug',$destination_slug);
                $destination = $q2->first();
                if($destination) {
                    $destination_id = $destination->id??0;
                    $data['page_title'] = $destination->name??'';
                    $data['page_brief'] = $destination->brief??'';
                    $data['page_description'] = '';//$destination->description??'';
                    $data['meta_title'] = $destination->activity_meta_title??'';
                    $data['meta_description'] = $destination->activity_meta_description??'';
                    $data['meta_keyword'] = $destination->activity_meta_keywords??'';

                    if(isset($destination->destinationBanners) && !empty($destination->destinationBanners)){
                        $banner_image_name = $destination->destinationBanners[0]->name ?? '';
                        if(isset($banner_image_name) && !empty($banner_image_name)){
                            $banner_image = asset('/storage/destinations/'.$banner_image_name);
                        }
                    }

                    $faq_tour_activity = Faq::where('tbl_name','=','destination_activity')->where('status',1)->where('tbl_id',$destination_id)->orderBy("sort_order", "ASC");
                    $faqs = $faq_tour_activity->take(40)->get()->toArray();
                    $data['faqs'] = $faqs;
                    // if($destination->image) {
                    //     $banner_image = url('/storage/destinations/'.$destination->image);
                    // }
                }else{
                    abort(404);
                }
            }            
        }

        if($request->international && strlen($request->international) > 0) {
            $international = $request->international ?? 0;
            $query->whereHas('packageDestination',function($q1) use($international){
                $q1->where('is_international',$international);
            });
        }

        if($request->sdest) {
            $sdest = $request->sdest;
            $query->where(function($q1) use($sdest){
                $q1->where('packages.destination_id',$sdest);
                $q1->orWhere('packages.sub_destination_id',$sdest);
            });
        }
        
        if($request->sno_of_days) {
            $sno_of_days = $request->sno_of_days;
            // prd($sno_of_days);
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

                /*$query->whereHas('packagePrices',function($q1) use($filter_package_budget_arr) {
                    $q1->where(function($q2) use($filter_package_budget_arr){
                        foreach($filter_package_budget_arr as $budget) {
                            $budget_arr = explode('-', $budget);
                            $budget_min = str_replace('_', '', $budget_arr[0])??0;
                            $budget_max = str_replace('_', '', $budget_arr[1])??0;
                            $q2->orWhereBetween('final_amount',[$budget_min,$budget_max]);
                        }
                    });
                });*/
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

        /*if($request->text) {
            $text = $request->text??'';
            if($text) {
                $getId = Package::getSubdestinationId($text);
                $query->where(function($q1) use($text, $getId) {
                    $q1->where('title','like','%'.$text.'%');
                    $q1->orWhereHas('packageDestination',function($q2) use ($text) {
                        $q2->where('name','like','%'.$text.'%');
                    });
                    $q1->orWhereHas('packageCategories',function($q2) use ($text) {
                        $q2->where('title','like','%'.$text.'%');
                    });  
                    $q1->orWhere(function($q2) use ($getId) {
                            $q2->whereRaw('case when related_destinations is not null and related_destinations!="[]" and related_destinations!="" then json_contains(related_destinations, \'["'.$getId[0]. '"]\') end');
                    });
                });
            }
        }*/

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

        if(isset($request->sort_by_price) && !empty($request->sort_by_price)){
            $sort_by_price = $request->sort_by_price;
            if($sort_by_price == 'lth') {
                $query->orderBy('packages.search_price','ASC');
            } else {
                $query->orderBy('packages.search_price','DESC');
            }
            
        } else {
            $query->orderBy('packages.featured','DESC');
            $query->orderBy('packages.sort_order','ASC');
        }

        if($request->featured != '') {
            $query->where('featured',$request->featured);
        }

        if($request->flag) {
            $flag =$request->flag;
            $query->whereHas('packageFlags',function($q1) use($flag){
                $q1->where('flag_id',$flag);
            });
        }

        if($is_activity) {
            $query->where('packages.is_activity',1);
        } else {
            $query->where('packages.is_activity',0);
        }
        
        $packages = $query->paginate($limit);
        $data['packages'] = $packages;

        if(!empty(Auth::check())) {
            $userId = Auth::user()->id;
            $package_fab =DB::table('users_favourite_packages')->where('user_id',$userId)->get()->keyBy('package_id')->toArray();
            $data['package_fab'] = $package_fab;
        } else {
            $data['package_fab'] =array();
        }

        $identifier = "package";
        if($is_activity == 1){
           $identifier = "activity";
        } 

        $themes = ThemeCategories::where('status',1)->where('identifier',$identifier)->get();
        $data['themes'] = $themes;
        //prd($data['themes']);

        $destinations = Destination::where('is_city',0)->where('status',1)->where('parent_id',0)->get();
        $data['destinations'] = $destinations;

        $data['is_activity'] = $is_activity;
        $data['banner_image'] = $banner_image;
        if($is_activity) {
           $data['route_name'] = 'activityListing';
           return view(config('custom.theme').'.activities.listing', $data);
        } else {
            $data['route_name'] = 'packageListing';
            return view(config('custom.theme').'.packages.listing', $data);
        }
    }

    public function ajaxSearchPackages(Request $request) {
        $response = [];
        $icon = '';
        $response['success'] = false;
        $response['message'] = '';
        $is_activity = $request->is_activity??'';
        $text = $request->text??'';
        $query = Package::with('packagePrices','packageCategories','packagePriceCategory')->selectRaw('packages.*')->where('packages.status',1);
        $query->orderBy('packages.featured','DESC');
        $query->orderBy('packages.sort_order','ASC');
        if($is_activity == 1) {
            $query->where('packages.is_activity',1);
        } else {
            $query->where('packages.is_activity',0);
        }
        $query->where(function($query1){
            $query1->where('packages.is_deleted',0);
            $query1->orWhereNull('packages.is_deleted');
        });
        if($request->text) {
            $getId = Package::getSubdestinationId($text);
            $query->where(function($q1) use($text, $getId) {
                $q1->where('title','like','%'.$text.'%');
                $q1->orWhereHas('packageDestination',function($q2) use ($text) {
                    $q2->where('name','like','%'.$text.'%');
                });
                $q1->orWhereHas('packageCategories',function($q2) use ($text) {
                    $q2->where('title','like','%'.$text.'%');
                });
                $q1->orWhere(function($q2) use ($getId) {
                    $q2->whereRaw('case when related_destinations is not null and related_destinations!="[]" and related_destinations!="" then json_contains(related_destinations, \'["'.$getId[0]. '"]\') end');
                });
            });
            $packages = $query->limit(30)->get();
        } else {
            if($is_activity == 1) {
                $query->where('packages.featured',1);
            } else {
                $query->whereHas('packageDestination',function($q2){
                    $q2->where('featured',1);
                });
            }
            $packages = $query->limit(10)->get();
        }
            // prd($packages->toArray());
            $destination_arr = [];
            $category_arr = [];
            $package_arr = [];
            $title_arr = [];
            $default_asset = asset(config("custom.assets"));
            if($packages) {
                foreach($packages as $package) {
                    $destination = $package->packageDestination->name??'';
                    $categories = $package->packageCategories??[];
                    $category_id = '';
                    $category_title = '';
                    if($categories) {
                        foreach($categories as $category) {
                            if(stripos($category->title, $text) !== false) {
                                $category_id = $category->id;
                                $category_title = $category->title;
                                break;
                            }
                        }
                    }
                        if($destination && (stripos($destination, $text) !== false) && !in_array($destination, $title_arr)) {
                            if($is_activity == '' || !empty($text)){
                                $destination_arr[] = [
                                    'slug' => 'd'.$package->destination_id,
                                    'id' => $package->destination_id,
                                    'title' => '<i class="fa fa-map-marker"></i>'.$destination,
                                ];
                                $title_arr[] = $destination;
                            }
                            if($is_activity == 1 || !empty($text)){
                                $icon = '<i class="fa fa-suitcase"></i>';
                                if($is_activity == 1){ 
                                    $icon = '<img class="icon_img" src="'.$default_asset.'/images/activities-icon.png">';
                                }else if(!empty($text) && $is_activity == ''){
                                    $icon = '<i class="fa fa-suitcase"></i>';
                                }
                                $title = $package->title.', '.$destination;
                                $package_arr[] = [
                                    'slug' => 'p'.$package->id,
                                    'id' => $package->id,
                                    'title' => $icon.$title,
                                ];
                                $title_arr[] = $title;
                            }
                        }else if(!empty($text) && $destination && (stripos($getId[1], $text) !== false) && !in_array($getId[1], $title_arr)){
                                $destination_arr[] = [
                                    'slug' => 'd'.$getId[0],
                                    'id' => $getId[0],
                                    'title' => '<i class="fa fa-map-marker"></i>'.$getId[1],
                                ];
                                $title_arr[] = $getId[1];
                        }
                        else if($category_id && $category_title && !in_array($category_title, $title_arr) && !empty($text)) {
                        $category_arr[] = [
                            'slug' => 'c'.$category_id,
                            'id' => $category_id,
                            'title' => '<i class="fa fa-map-marker"></i>'.$category_title,
                        ];
                        $title_arr[] = $category_title;
                        if($is_activity == 1){ 
                            $icon = '<img class="icon_img" src="'.$default_asset.'/images/activities-icon.png">';
                        }else if($is_activity == ''){
                            $icon = '<i class="fa fa-suitcase"></i>';
                        }
                        $package_arr[] = [
                            'slug' => 'p'.$package->id,
                            'id' => $package->id,
                            'title' => $icon.$package->title,
                        ];
                        $title_arr[] = $package->title;
                    } else if(!in_array($package->title, $title_arr) && ($is_activity == 1 || !empty($text))){
                        $title = $package->title;
                        if($destination) {
                            $title = $title.', '.$destination;
                        }
                        if($is_activity == 1){ 
                            $icon = '<img class="icon_img" src="'.$default_asset.'/images/activities-icon.png">';
                        }else if($is_activity == ''){
                            $icon = '<i class="fa fa-suitcase"></i>';
                        }
                        $package_arr[] = [
                            'slug' => 'p'.$package->id,
                            'id' => $package->id,
                            'title' => $icon.$title,
                        ];
                        $title_arr[] = $title;
                    }
                }
            }
            $result = array_merge($destination_arr,$category_arr,$package_arr);
            $response['success'] = true;
            $response['result'] = $result;
        return response()->json($response);
    }

    public function details(Request $request) {
        $data = [];
        $slug = (isset($request->slug))?$request->slug:'';
        if(!empty($slug)) {
            if(!empty(Auth::check())) {
                $userId = Auth::user()->id;
                $package_fab =DB::table('users_favourite_packages')->where('user_id',$userId)->get()->keyBy('package_id')->toArray();
                $data['package_fab'] = $package_fab;
            } else {
                $data['package_fab'] =array();
            }
            $query = Package::with('packageDestination')->where('slug',$slug)->where('status',1);
            $query->where(function($q1){
                $q1->where('packages.is_deleted',0);
                $q1->orWhereNull('packages.is_deleted');
            });
            $package = $query->groupBy('id')->orderBy('packages.created_at','desc')->first();
            if(!empty($package)) {
                $package_id = $package->id;
                $is_activity = $package->is_activity;
                $segments1 = $request->segment(1);
                if($is_activity) {
                    $activity_listing = CustomHelper::getSeoModules('activity_listing');
                    $activityListing = $activity_listing->page_url??'activities';
                    $activityDetail = $activity_listing->page_detail_url??'activity';
                    if($segments1 != $activityDetail) {
                        abort(404);
                    }
                } else {
                    $package_listing = CustomHelper::getSeoModules('package_listing');
                    $packageListing = $package_listing->page_url??'packages';
                    $packageDetail = $package_listing->page_detail_url??'package';
                    if($segments1 != $packageDetail) {
                        abort(404);
                    }
                }

                $packageDefaultPrice = $package->packageDefaultPrice??[];
                $package_price_id = '';
                if($packageDefaultPrice) {
                    $package_price_id = $packageDefaultPrice->id;
                    $package_price_title = $packageDefaultPrice->title;
                }
                $data['package_price_id'] = $package_price_id;
                $data['package_price_title'] = !empty($package_price_title)?$package_price_title:'';
                $data['packageSelectedPrice'] = $packageDefaultPrice;
                // prd($data['package_price_id']);
                // prd($isDefaultPackagePrice->toArray());
                $data['meta_title'] = (!empty($package->meta_title))?$package->meta_title:$package->title . ', '.$package->packageDestination->name??'';
                $data['meta_description'] = $package->meta_description??'';
                $data['meta_keywords'] = $package->meta_keywords??'';

                $data['package'] = $package;
                $packageID = $package->id;
                /*$accommodationData = Accommodation::select('accommodations.id', 'package_accommodations.package_id')->selectRaw('accommodations.*')
                ->leftJoin('package_accommodations','accommodations.id','=','package_accommodations.accommodation_id')->where('package_id',$packageID)
                ->get();
                $data['accommodations'] = $accommodationData;*/

                $related_packages = (!empty($package->related_packages)) ? json_decode($package->related_packages,true) : [];
                $destination_id = (!empty($package->destination_id)) ? $package->destination_id :0;

                $query = Package::with('packagePrices','packageDestination')->where('status',1);
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
                $query->orderBy('featured','DESC');
                $query->orderBy('sort_order','ASC');
                $data['relatedPackagestObj'] = $query->limit(10)->get();

                $destinations = Destination::where('is_city',0)->where('featured',1)->where('parent_id',0)->where('status',1)->limit(6)->get();
                $data['destinations'] = $destinations;
                $idArr = (string)$package->id;
                /*$testimonials = Testimonial::whereJsonContains('package_id',[$idArr])->where('status',1)->where('is_deleted',0)->limit(6)->get();

                if(empty($testimonials)){
                    $testimonials = Testimonial::where('status',1)->where('is_deleted',0)->limit(6)->get(); //Show 10 Any Testimonial
                }*/
                $testimonialQuery = Testimonial::whereJsonContains('package_id',[$idArr])->where('status',1);
                $testimonialQuery->where(function($q1){
                    $q1->where('is_deleted',0);
                    $q1->orWhereNull('is_deleted');
                });
                $testimonials = $testimonialQuery->limit(6)->get();
                //prd($testimonials->toArray());
                if($testimonials->count() == 0) {
                    $testimonialQuery = Testimonial::where('status',1);
                    $testimonialQuery->where(function($q1){
                        $q1->where('is_deleted',0);
                        $q1->orWhereNull('is_deleted');
                    });
                    $testimonials = $testimonialQuery->limit(6)->get();
                }

                $faq_package = Faq::where('tbl_name','=','packages')->where('status',1)->where('tbl_id',$package_id)->orderBy("sort_order", "ASC");
                $faq_row = $faq_package->take(10)->get();

                $data['faq_row'] = $faq_row;
                $data['testimonials'] = $testimonials;

                /*$accommodation_without_price = $accommodation_without_pricNew=array();
                $accommodation_without_price = DB::table('package_accommodations as P')->leftjoin('service_levels as S','S.id','=','P.service_level')->where(['package_price_id'=>$idArr,'package_price_id'=>0])->get()->toArray();
                if(!empty($accommodation_without_price)) {
                    foreach($accommodation_without_price as $rec){
                        $accommodation_without_pricNew[$rec->service_level]=$rec;
                    }
                }
                $data['accommodation_without_price'] = $accommodation_without_pricNew;*/

                $data['is_activity'] = $is_activity;

                $activity_listing = CustomHelper::getSeoModules('activity_listing');
                $activityDetail = $activity_listing->page_detail_url??'activity';
        
            if($segments1 == $activityDetail) {

              $data['route_name'] = 'activityListing';
              return view(config('custom.theme').'.activities.details', $data);

             }else{

                return view(config('custom.theme').'.packages.details', $data);
            }

            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

public function hotelPackagepopupDetails(Request $request){

        $data = [];
        $limit = $this->limit;
        $data['meta_title'] = 'HotelPackagepopupDetails - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'HotelPackagepopupDetails';
        $slug = (isset($request->slug))?$request->slug:'';
        if(!empty($slug)){

        $accoID = isset($request->id)?$request->id:"";

        $accommodationDetails = Accommodation::where('slug',$slug)->groupBy('accommodations.id')->first();
        $accommodationName = isset($accommodationDetails->name) ? $accommodationDetails->name:'';

        $data['meta_title'] = 'Hotel ('.$accommodationName.') - '.CustomHelper::WebsiteSettings('SYSTEM_TITLE');
        $data['meta_description'] = 'Hotel Detail Description';
        
        $data['accommodation'] = $accommodationDetails;
        $data['accommodations'] = $accommodationDetails;

        //prd($accommodationDetails);

        $data['accommodation_features'] = AccommodationFeature::where('status' ,1)->get();
        
        return view(config('custom.theme').'.packages.packagepopup_hotel_details', $data);
    }else{
    abort(404);
    }
}

    public function ajaxPriceDetails(Request $request) {
        // prd($request->toArray());
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        if($request->price_id) {
            $price = PackagePriceInfo::find($request->price_id);
            if($price) {
                $package = $price->Package??[];
                if($package) {
                    $is_activity = $package->is_activity??'';
                    $data = [];
                    $data['package'] = $package;
                    $data['package_price_id'] = $price->id;
                    $data['packageSelectedPrice'] = $price;
                    $data['package_price_title'] = $price->title ??'';
                    if($is_activity == 1) {
                        $htmlData = view(config('custom.theme').'.activities._right_side_details', $data)->render();
                    } else {
                        $htmlData = view(config('custom.theme').'.packages._right_side_details', $data)->render();
                    }
                    $response['success'] = true;
                    $response['htmlData'] = $htmlData;
                }
            }
        }
        return response()->json($response);
    }

    public function ajaxPriceSlots(Request $request) {
        // prd($request->toArray());
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        if($request->price_id && $request->trip_date && $request->total_pax) {
            $price = PackagePriceInfo::find($request->price_id);
            if($price) {
                $price_id = $price->id;
                $package = $price->Package??[];
                $trip_date = CustomHelper::DateFormat($request->trip_date,'Y-m-d','d/m/Y');
                $total_pax = $request->total_pax??'';
                if($package && $trip_date && $total_pax) {
                    $package_id = $package->id;
                    $query = PackageInventory::where(function($q1){
                        $q1->whereHas('packageSlot',function($q2){
                            $q2->where('status',1);
                        });
                        $q1->orWhereNull('slot_id');
                    });
                    $query->where("package_id",$package_id);
                    $query->where("price_id",$price_id);
                    $query->where('inventory','>=',$total_pax);
                    $query->whereDate('trip_date',$trip_date);
                    $PackageInventory = $query->get();
                    // prd($PackageInventory->toArray());
                    $results = [];
                    if($PackageInventory) {
                        $current_time = date('H:i');
                        $current_date = date("Y-m-d");
                        foreach($PackageInventory as $pi) {
                            if(($pi->inventory > $pi->booked) && (($pi->inventory - $pi->booked) >= $total_pax )  ) {
                                if($pi->slot_id) {
                                    $slot_key = CustomHelper::getPackageSlot($pi->package_id,$pi->slot_id);
                                    $curr_trip_date = CustomHelper::DateFormat($request->trip_date,'Y-m-d','d/m/Y');
                                    if($current_date && $current_date == $curr_trip_date){
                                        $slot_key = ($slot_key > $current_time)?$slot_key:''; 
                                    }
                                    if($slot_key) {
                                        $slot_title = CustomHelper::getPackageSlotTitle($slot_key);
                                        if($slot_title) {
                                            $results[] = [
                                                'key' => $slot_key,
                                                'title' => $slot_title,
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $response['success'] = true;
                    $response['results'] = $results;
                }
            }
        } else {
            if($request->total_pax == 0) {
                $response['message'] = 'Travellers is required !';
            }
        }
        return response()->json($response);
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
                    $accommodations = PackageAccommodation::where('package_id',$package_id)->where('package_price_id',$package_price_id)->get();
                    $data['accommodations_days'] = $accommodations;
                    $htmlData = view(config('custom.theme').'.packages._package_price_accommodations', $data)->render();
                    $response['success'] = true;
                    $response['htmlData'] = $htmlData;
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
        return view(config('custom.theme').'.themes.theme_listing', $data);
    }


    public function getPackageTypePrice(Request $request) {
        $response = [];
        $response['success'] = false;
        $packageId = $request->pkgId??'';
        $typeId = $request->typeId??'';
        if(!empty($packageId) && !empty($typeId)) {
            $priceData = PackagePriceInfo::find($typeId);
            /*$original_price = CustomHelper::getPrice($priceData->sub_total_amount);
            $package_price = CustomHelper::getPrice($priceData->final_amount);*/

            $search_price = CustomHelper::getPackagePriceFrom($packageId,$typeId);
            $sumpPriceVal = $search_price;
            $original_price = $search_price;

            $price = '';
            if($original_price == $sumpPriceVal) {

            } else {
                $price .= '<small class="old_price">'.CustomHelper::getPrice($original_price).'</small>';
            }
            $price .= CustomHelper::getPrice($sumpPriceVal);
            $response['success'] = true;
            $response['price'] = $price;
            $response['package_price_title'] = $priceData->title ??'';
        }
        return response()->json($response);    
    }



/* end of controller */
}
