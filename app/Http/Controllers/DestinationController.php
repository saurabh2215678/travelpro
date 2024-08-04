<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\Accommodation;
use App\Models\Testimonial;
use App\Models\ThemeCategories;
use App\Models\Package;
use App\Models\BannerImageGallery;
use App\Models\DestinationInfo;
use App\Models\SeoMetaTag;
use App\Helpers\CustomHelper;
use DB;
use Validator;

class DestinationController extends Controller {

	private $limit;
    public function __construct(){
    	$this->limit = 40;
    }

    public function index(Request $request) {
        $data = [];
        $limit = $this->limit;
        $seo_tags = CustomHelper::getSeoModules('destination_listing');
        $data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
        $data['page_url_label'] = $seo_tags->page_url_label??'Destinations';
        $data['page_brief'] = $seo_tags->page_brief??'';
        $data['page_description'] = $seo_tags->page_description??'';
        $data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
        $data['meta_description'] = $seo_tags->meta_description??'';
        $data['meta_keyword'] = $seo_tags->meta_keyword??'';
        $data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
        $banner_image = '';
        if($seo_tags->image) {
            $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
        }
        $query = Destination::where('is_city',0)->where('status',1)->where('show',1);

        if($request->international == 1){
            $query->where('is_international',1);
        }

        $destinations = $query->where(function($query1){
                    $query1->where('is_deleted',0);
                    $query1->orWhereNull('is_deleted');
                })->paginate($limit);
        $data['destinations'] = $destinations;
        $isMobile = CustomHelper::isMobile();
        $bannerType = 'desktop';
        if($isMobile) {
            $bannerType = 'mobile';
        }
        $bannerWhere = [];
        $bannerWhere['page'] = 'destinations';
        $bannerWhere['status'] = 1;
        $bannerWhere['device_type'] = $bannerType;
        $banners = BannerImageGallery::where($bannerWhere)->orderBy('sort_order')->limit($limit)->get();
        $data['banners'] = $banners;
        $data['banner_image'] = $banner_image;
        return view(config('custom.theme').'.destinations.listing', $data);
    }

    public function detail(Request $request) {
        $destinationinfo = '';
        $data = [];
        $limit = $this->limit;
        $destinationSlug = isset($request->slug) ? $request->slug:"";

        $query = Destination::where('slug',$destinationSlug)->where('is_city',0)->where('status',1)->where('show',1);
        $query->where(function($query1){
            $query1->where('is_deleted',0);
            $query1->orWhereNull('is_deleted');
        });
        $destinationDetails = $query->first();
        if(isset($destinationDetails) && !empty($destinationDetails)){
            $destinationID = $destinationDetails->id;
            $seo_tags = CustomHelper::getSeoModules('destination_listing');
            $banner_image = '';
            if($seo_tags->image) {
                $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
            }
            $data['breadcrumb_title'] = $seo_tags->page_url_label??'Destinations';
            $data['meta_title'] = (!empty($destinationDetails->meta_title))?$destinationDetails->meta_title:$destinationDetails->name;
            $data['meta_description'] = (!empty($destinationDetails->meta_description))?$destinationDetails->meta_description:'';
            $data['meta_keyword'] = (!empty($destinationDetails->meta_keyword))?$destinationDetails->meta_keyword:'';
            $data['destinationDetails'] = $destinationDetails;

            $isMobile = CustomHelper::isMobile();
            $bannerType = 'desktop';
            if($isMobile){
                $bannerType = 'mobile';
            }
            $bannerWhere = [];
            $bannerWhere['page'] = 'destinations-details/india';
            //$bannerWhere['page'] = 'tour-packages/india';
            $bannerWhere['status'] = 1;
            $bannerWhere['device_type'] = $bannerType;
            $banners = BannerImageGallery::where($bannerWhere)->orderBy('sort_order')->limit($limit)->get();
            $data['banners'] = $banners;

            if(!empty(Auth::check())){
                $userId = Auth::user()->id;
                $package_fab =DB::table('users_favourite_packages')->where('user_id',$userId)->get()->keyBy('package_id')->toArray();
                $data['package_fab'] = $package_fab;
            }else{
                $data['package_fab'] = array();
            }

            //Tour Packages
            $query = Package::where('status',1); //where('featured',1)->
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
            /*$query->orWhere(function($query) use ($destinationID) {
                $query->whereRaw('case when related_destinations is not null and related_destinations!="[]" and related_destinations!="" then json_contains(related_destinations, \'["'.$destinationID. '"]\') end');
             });*/
            $query->orderBy('featured','DESC');
            $query->orderBy('sort_order','ASC');
            $popular_packages = $query->get();

            //Tour Activity
            $query = Package::where('status',1); //where('featured',1)->
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
            $popular_activities = $query->get();

            // $faq_destinations = Faq::where('sub_destination_id',$destinationID)->orwhere('destination_id',$destinationID)->where('status',1)->orderBy("sort_order", "ASC");
            // $faq_row = $faq_destinations->take(10)->get();
            $faq_destinations = Faq::where('tbl_name','=','destinations')->where('status',1)->where('tbl_id',$destinationID)->orderBy("sort_order", "ASC");
            $faq_row = $faq_destinations->take(10)->get();
            
            $destinations = Destination::where('is_city',0)->where('status',1)->where('show',1)->get();

            // $destinationinfo = DestinationInfo::where(["destination_id"=>$destinationID,"status"=>1])->orderBy("sort_order", "ASC")->get();
            $query = DestinationInfo::selectRaw('destination_info.type,destination_type.name,destination_type.slug')->where('destination_info.destination_id',$destinationID)->where('destination_info.status',1);
            $query = $query->leftJoin('destination_type',function ($join) {
                $join->on('destination_type.id', '=' , 'destination_info.type');
            });
            $destination_type = $query->groupBy('destination_info.type')->orderBy('destination_type.sort_order','ASC')->get();
            // prd($destinationinfo->toArray());

            $idArr[] = (string)$destinationID;
            $testimonials = Testimonial::whereJsonContains('destination_id',$idArr)->where('status',1)->limit(5)->get();
            // prd($testimonials->toArray());
            $data['testimonials'] = $testimonials;


            $data['destination_id'] = $destinationID;
            $data['destination_type'] = $destination_type;
            $data['popular_packages'] = $popular_packages;
            $data['popular_activities'] = $popular_activities;
            $data['destinations'] = $destinations;
            $data['faq_row'] = $faq_row;
            $data['banner_image'] = $banner_image;
            return view(config('custom.theme').'.destinations.details', $data);
        } else {
            abort(404);
        }
        
           
    }

/* end of controller */
}
