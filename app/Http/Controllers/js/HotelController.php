<?php
namespace App\Http\Controllers\js;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Accommodation;
use App\Models\AccommodationCategory;
use App\Models\AccommodationFeature;
use App\Models\AccommodationInfo;
use App\Models\Destination;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\SeoMetaTag;
use App\Models\UrlRedirection;
use App\Helpers\CustomHelper;
use Inertia\Inertia;
use DB;
use Validator;
use Storage;

class HotelController extends Controller {

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
                            $params = $request->toArray();
                            if(isset($params['slug'])) {
                                unset($params['slug']);
                            }
                            $actual_link = route('hotelListing',$params);
                            return redirect($actual_link);
                        }
                    }
                } else if($slug_start == 'c') {
                    $theme_id = str_replace($slug_start, '', $slug);
                    if(!empty($theme_id) && is_numeric($theme_id)) {
                        $query = ThemeCategories::where('status',1)->where('id',$theme_id);
                        $query->where(function($query1){
                            $query1->where('is_deleted',0);
                            $query1->orWhereNull('is_deleted');
                        });
                        $theme = $query->first();
                        if($theme) {
                            $actual_link = route('tourCategoryDetail',['tour_category'=>$theme->slug]);
                            return redirect($actual_link);
                        }
                    }
                } else if($slug_start == 'h') {
                    $hotel_id = str_replace($slug_start, '', $slug);
                    if(!empty($hotel_id) && is_numeric($hotel_id)) {
                        $query = Accommodation::with('accommodationCategories','accommodationDestination')->select('accommodations.*')->leftjoin('destinations as D','D.id','=','accommodations.destination_id')->where('accommodations.status',1);
                        $query->where(function($query1){
                            $query1->where('accommodations.is_deleted',0);
                            $query1->orWhereNull('accommodations.is_deleted');
                        });
                        $query->where('accommodations.id',$hotel_id);
                        $hotel = $query->first();
                        if($hotel) {
                            $params = $request->toArray();
                            if(isset($params['text'])) {
                                unset($params['text']);
                            }
                            $params['slug'] = $hotel->slug;
                            $actual_link = route('hotelDetail',$params);
                            return redirect($actual_link);
                        }
                    }
                }
            }
        }

        $checkin = '';
        $checkout = '';
        $adult = 2;
        $child = 0;
        $infant = 0;
        $inventory = 1;

        if($request->checkin) {
            $checkin = $request->checkin;
            if($request->checkout) {
                $checkout = $request->checkout;
            }
            if($request->adult) {
                $inventory = $request->inventory??1;
                $adult = $request->adult??1;
                $child = $request->child??0;
                $infant = $request->infant??0;
            }
        }

        $checkin = $checkin??'';
        $checkout = $checkout??'';
        $adult = $adult??'';
        $child = $child??'';
        $infant = $infant??'';
        $params = [];
        if($checkin) {
            $params['checkin'] = $checkin;
        }
        if($checkout) {
            $params['checkout'] = $checkout;
        }
        if($adult) {
            $params['adult'] = $adult;
        }
        if($child) {
            $params['child'] = $child;
        }
        if($infant) {
            $params['infant'] = $infant;
        } 
        $data['destination_id'] = $destination_id = (isset($request->destination) && !empty($request->destination)) ? $request->destination : 0;

        $segments1 = $request->segment(1);
        $seo_tags = SeoMetaTag::where(['page_url'=>$segments1,'status'=>1])->first();
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
        }

        if($request->destination) {
            if(is_numeric($request->destination)) {
            } else {
                $query = Destination::where('is_city',0)->where('status',1)->where('show',1)->orderBy('sort_order','asc');
                $query->where('slug',$request->destination);
                $query->where('parent_id',0);
                $query->where(function($query1){
                    $query1->where('is_deleted',0);
                    $query1->orWhereNull('is_deleted');
                });                
                $destination = $query->first();

                if($destination) {
                    $destination_name = $destination->name??'';

                    $seo_data['page_title'] = 'Best Hotels/Resorts in '.$destination_name;
                    $seo_data['meta_title'] = 'Best Hotels in '.$destination_name.', Book Budget Beach Resorts in '.$destination_name.', Andaman & Nicobar Islands';
                    $seo_data['meta_description'] = 'Searching for the best hotels in '.$destination_name.'? Book budget to luxury beach resorts & beach hotels in '.$destination_name.', Andaman & Nicobar Islands on Andamanisland.in';
                    if($destination->hotels_pages_title) {
                        $seo_data['page_title'] = $destination->hotels_pages_title;
                    }if($destination->hotels_pages_description) {
                        $seo_data['page_description'] = $destination->hotels_pages_description;
                    }if($destination->hotels_meta_title) {
                        $seo_data['meta_title'] = $destination->hotels_meta_title;
                    }if($destination->hotels_meta_keyword) {
                        $seo_data['meta_keyword'] = $destination->hotels_meta_keyword;
                    }if($destination->hotels_meta_description) {
                        $seo_data['meta_description'] = $destination->hotels_meta_description;
                    }
                }
            }
            $params['destination'] = $request->destination;
        }

        $data['data'] = 'hotel';
        $seo_data['banner_image'] = $banner_image;
        $data['checkin'] = $checkin;
        $data['checkout'] = $checkout;                
        $data['adult'] = $adult;
        $data['child'] = $child;
        $data['infant'] = $infant;

        $data["categories"] = AccommodationCategory::where("status", 1)->get();
        $data["features"] = AccommodationFeature::where("status", 1)->get();
        $search_data = CustomHelper::getSearchData('hotel',$params);
        $search_data['module'] = 'Hotel';
        $search_data['details'] = '';
        $data['search_data'] = $search_data;
        $data['seo_data'] = $seo_data;
View::share('seo_data', $seo_data);
        return Inertia::render('hotels/Listing', $data);
    }

    public function ajaxAccommodationList(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';

        $limit = $this->limit;
        if($request->limit) {
            $limit = $request->limit??$limit;
        }
        $destination_id = (isset($request->destination) && !empty($request->destination)) ? $request->destination : 0;
        $sub_destination = (isset($request->sub_destination) && !empty($request->sub_destination)) ? $request->sub_destination : 0;
        $destination_slug = '';
        if(!empty($destination_id) && !is_numeric($destination_id)) {
            $destination_slug = $destination_id;
        }
        if ($request->destination_slug) {
            $destination_slug = $request->destination_slug;
        }
        if($destination_slug) {
            $q2 = Destination::where('status',1);                
            $q2->where(function($q3){
                $q3->where('is_deleted',0);
                $q3->orWhereNull('is_deleted');
            });
            $q2->where('slug',$destination_slug);
            $destination = $q2->first();
            if($destination) {
                $destination_id = $destination->id;
            }
        }

        $query = Accommodation::with('accommodationCategories','accommodationDestination','AccommodationDefaultLocation','AccommodationDefaultLocation.DestinationLocation','accommodationImages')->select('accommodations.*')->leftjoin('destinations as D','D.id','=','accommodations.destination_id')->where('accommodations.status',1);

        $query->where(function($query1) {
            $query1->where('accommodations.is_deleted',0);
            $query1->orWhereNull('accommodations.is_deleted');
        });

        if($request->featured) {
            $query->where('accommodations.featured',$request->featured);
        }
        if($request->stars){
            $stars = $request->stars??[];
            if(!is_array($stars)) {
                $stars = explode(',', $stars);
            }
            if(!empty($stars)) {
                $query->whereIn('accommodations.rating',$stars);
            }
        }
        if($request->class){
            $class_arr = $request->class??[];
            if(!is_array($class_arr)) {
                $class_arr = explode(',', $class_arr);
            }
            if(!empty($class_arr)) {
                $query->whereIn('accommodations.star_classification',$class_arr);
            }
        }

        if($request->categories){
            $categories_arr = $request->categories??[];
            if(!is_array($categories_arr)) {
                $categories_arr = explode(',', $categories_arr);
            }
            if(!empty($categories_arr)) {
                $query->whereIn('accommodations.category_id',$categories_arr);
            }
        }

        if($request->features){
            $features_arr = $request->features??[];
            if(!is_array($features_arr)) {
                $features_arr = explode(',', $features_arr);
            }
            if(!empty($features_arr)) {
                $query->where(function($q1) use($features_arr) {
                    foreach($features_arr as $feature) {
                        $q1->orWhereJsonContains('accommodations.accommodation_feature',$feature);
                    }
                });
            }
        }

        if($destination_id) {
            $query->where(function ($query1) use ($destination_id) {
               $query1->where('accommodations.destination_id', '=', $destination_id)->orWhere('D.parent_id', '=', $destination_id);
           });
        }

        if($sub_destination) {
            $query->where('accommodations.destination_id',$sub_destination);
        }

        if($request->text) {
            $text = $request->text??'';
            if($text) {
                $query->where(function($q1) use($text) {
                    $q1->where('accommodations.name','like','%'.$text.'%');
                    $q1->orWhereHas('accommodationDestination',function($q2) use ($text) {
                        $q2->where('name','like','%'.$text.'%');
                    });
                    $q1->orWhereHas('accommodationCategories',function($q2) use ($text) {
                        $q2->where('title','like','%'.$text.'%');
                    });
                    $q1->orWhereHas('AccommodationDefaultLocation',function($q2) use ($text) {
                        $q2->whereHas('DestinationLocation',function($q3) use ($text){
                            $q3->where('name','like','%'.$text.'%');
                        });
                    });
                    $q1->orWhereHas('AccommodationLocation',function($q2) use ($text) {
                        $q2->whereHas('DestinationLocation',function($q3) use ($text){
                            $q3->where('name','like','%'.$text.'%');
                        });
                    });
                });
            }
        }

        $checkin = '';
        $checkout = '';
        $adult = 2;
        $child = 0;
        $infant = 0;
        $inventory = 1;

        if($request->checkin) {
            $checkin = $request->checkin;
            if($request->checkout) {
                $checkout = $request->checkout;
            }
            if($request->adult) {
                $inventory = $request->inventory??1;
                $adult = $request->adult??1;
                $child = $request->child??0;
                $infant = $request->infant??0;
                $total_pax = (int)$adult + (int)$child + (int)$infant;
                // if($total_pax > 0) {
                //     $inventory = ceil($total_pax/3);
                // }
            }
            $query->where(function($q1) use($checkin,$checkout,$inventory) {
                $q1->whereHas('AccommodationRooms',function($q2) use ($checkin,$checkout,$inventory) {
                    $q2->whereHas('inventoryData',function($q3) use ($checkin,$checkout,$inventory) {
                        $q3->where('status',1);
                        $q3->where('inventory','>=',$inventory);
                        if($checkin && $checkout) {
                            $checkout_date = date('Y-m-d', strtotime($checkout. ' - 1 days'));
                            $period = CustomHelper::DateRange($checkin,$checkout_date);
                            $date_arr = [];
                            foreach ($period as $key => $value) {
                                $date_arr[] = $value->format('Y-m-d');
                            }
                            $q3->whereIn('date',$date_arr);

                        } else {
                            $q3->whereDate('date',$checkin);
                        }
                    });
                });
            });
        }

        $query->orderBy('accommodations.featured','DESC');
        $query->orderBy('accommodations.sort_order','ASC');

        $accommodation_result = $query->paginate($limit);
        $storage = Storage::disk('public');
        $accommodation_path = "accommodations/";

        $checkin = $checkin??'';
        $checkout = $checkout??'';
        $adult = $adult??'';
        $child = $child??'';
        $infant = $infant??'';
        $params = [];
        if($checkin) {
            $params['checkin'] = $checkin;
        }
        if($checkout) {
            $params['checkout'] = $checkout;
        }
        if($adult) {
            $params['adult'] = $adult;
        }
        if($child) {
            $params['child'] = $child;
        }
        if($infant) {
            $params['infant'] = $infant;
        }
        $search_data = CustomHelper::getSearchData('hotel',$params);
        unset($search_data['default_filters']);
        $accommodations =[];
        foreach ($accommodation_result as $key => $accommodation_row) {
            $accommodations[] = Accommodation::parseHotel($accommodation_row,['size'=>'listing','search_data'=>$search_data]);
        }
        $data['hotelList']['data'] = $accommodations;
        $hotel_listing = CustomHelper::getSeoModules('hotel_listing');
        $hotelListing = $hotel_listing->page_url??'hotels';
        $accommodation_result->withPath($hotelListing);
        $data['hotelList']['links'] = $accommodation_result->appends($search_data)->links('vendor.pagination.bootstrap-4')->render();
        $data['success'] = true;
        return response()->json($data);
    }

    //hotel detail  page
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
        $limit = $this->limit;
        $slug = isset($request->slug) ?$request->slug:"";
        if(isset($slug) && !empty($slug)) {
            $query = Accommodation::where('status',1)->where('slug',$slug);
            $query->where(function($query1){
                $query1->where('is_deleted',0);
                $query1->orWhereNull('is_deleted');
            });
            $accommodation = $query->first();
            if(isset($accommodation) && !empty($accommodation)) {
                $storage = Storage::disk('public');
                $path = "accommodations/";
                $accommodation_id = $accommodation->id;
                $accomodation_info = AccommodationInfo::where(['status'=>1, 'accommodation_id'=>$accommodation->id])->orderBy('sort_order','ASC')->get();
                $accomodation_info_arr = [];
                if($accomodation_info) {
                    $accommodation_info_path = "accommodations/";
                    foreach ($accomodation_info as $acc_info) {
                        $accommodation_info_image = $acc_info->image;
                        $accommodationInfoThumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
                        if(!empty($accommodation_info_image) && $storage->exists($accommodation_info_path.$accommodation_info_image)) {
                            $accommodationInfoThumbSrc = asset('/storage/'.$accommodation_info_path.'thumb/'.$accommodation_info_image);
                        }
                        $accomodation_info_arr[] = [
                                'title'=> $acc_info->title ,
                                'brief'=> $acc_info->brief ,
                                'image'=> $accommodationInfoThumbSrc ,
                                'url'=> $accommodationInfoThumbSrc ,
                        ];
                    }
                }
                $data['accomodation_info'] = $accomodation_info_arr;

                $seo_tags = CustomHelper::getSeoModules('hotel_listing');
                $banner_image = '';
                if($seo_tags->image) {
                    $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
                }

                $faq_accomodation = Faq::where('tbl_name','=','accommodations')->where('status',1)->where('tbl_id',$accommodation->id)->orderBy("sort_order", "ASC");
                $faq_row = $faq_accomodation->take(10)->get();

                $banners = $accommodation->accommodationBanners;
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

                $breadcrumb_title = $seo_tags->page_url_label??'Hotels';

                $meta_title = '';
                $meta_description = '';
                if($accommodation->meta_title) {
                    $meta_title = $accommodation->meta_title;
                } else {
                    $meta_title = $accommodation->name;
                    $location_name = $accommodation->AccommodationDefaultLocation->DestinationLocation->name??'';
                    $destination_name = $accommodation->accommodationDestination->name??'';
                    if($location_name) {
                        $meta_title .= ', '.$location_name;
                    }
                    if($destination_name) {
                        $meta_title .= ', '.$destination_name;
                    }
                    if(config('custom.theme_name') == 'overlandescape'){
                        $meta_title = $meta_title."– Prices, Reviews, Photos & Offers" ??$meta_title;
                    }
                }

                if($accommodation->meta_description) {
                    $meta_description = $accommodation->meta_description;
                } else {

                    $meta_description = $accommodation->name;
                    $location_name = $accommodation->AccommodationDefaultLocation->DestinationLocation->name??'';
                     if(config('custom.theme_name') == 'overlandescape'){
                            $location_name = 'located in '.$location_name??'';
                        }
                    $destination_name = $accommodation->accommodationDestination->name??'';
                    if($location_name) {
                        $meta_description .= ', '.$location_name;
                    }
                    if($destination_name) {
                        $meta_description .= ', '.$destination_name;
                    }
                    //$meta_description = $meta_title;

                    if(config('custom.theme_name') == 'overlandescape'){
                        $meta_description = "Explore & book ".$meta_description.' '."at overlandescape.com.Check prices, read reviews, view photos, and discover exclusive offers for a perfect stay in Ladakh" ??$meta_description;
                    }

                }

                $seo_data['meta_title'] = $meta_title ?? '';
                //$seo_data['meta_description'] = (!empty($accommodation->meta_description))?$accommodation->meta_description:'';
                $seo_data['meta_description'] = $meta_description??'';
                $seo_data['meta_keyword'] = (!empty($accommodation->meta_keywords))?$accommodation->meta_keywords:'';
                $seo_data['banner_image'] = $banner_image;
                $seo_data['banners'] = $banners_arr;

                $data['accommodation'] = Accommodation::parseHotel($accommodation,['size'=>'detail']);
                $data['faq_row'] = $faq_row;
                $checkin = date('Y-m-d',strtotime('+6 days'));
                $checkout = date('Y-m-d',strtotime('+7 days'));
                $inventory = 1;
                $adult = 2;
                $child = 0;
                $infant = 0;
                if($request->checkin) {
                    $checkin = $request->checkin;
                    if($request->checkout) {
                        $checkout = $request->checkout;
                    }
                    if($request->adult) {
                        $inventory = $request->inventory??1;
                        $adult = $request->adult??1;
                        $child = $request->child??0;
                        $infant = $request->infant??0;
                        $total_pax = (int)$adult + (int)$child + (int)$infant;
                        // if($total_pax > 0) {
                        //     $inventory = ceil($total_pax/3);
                        // }
                    }
                }

                //===============

            $related_hotels = (!empty($accommodation->related_hotels)) ? json_decode($accommodation->related_hotels,true) : "";
            $destination_id = $accommodation->destination_id ?? 0;
            $relatedAccommodationObj = "";

            $relatedAccommodation_arr = [];
            if(!empty($related_hotels)){
                $relatedAccommodationObj = Accommodation::whereIn('id',$related_hotels)->where('id','!=',$accommodation_id)->where('status',1)->where(function($query1){
                $query1->where('is_deleted',0);
                $query1->orWhereNull('is_deleted');
            })->limit(8)->get();
            }
            if(empty($relatedAccommodationObj)){
                $relatedAccommodationObj = Accommodation::where('destination_id',$destination_id)->where('id','!=',$accommodation_id)->where('status',1)->where(function($query1){
                $query1->where('is_deleted',0);
                $query1->orWhereNull('is_deleted');
            })->limit(8)->get();
            }
            if(!empty($relatedAccommodationObj) && count($relatedAccommodationObj) > 0){

               foreach($relatedAccommodationObj as $relatedHotel){

                $relatedAccommodation_arr[] = Accommodation::parseHotel($relatedHotel);
            }
        }

                $data['relatedAccommodations'] = $relatedAccommodation_arr;
                //===============
                $data['checkin'] = $checkin;
                $data['checkout'] = $checkout;
                $data['inventory'] = $inventory;
                $data['adult'] = $adult;
                $data['child'] = $child;
                $data['infant'] = $infant;
                $destination = $accommodation->accommodationDestination->name??'';
                $data['destination'] = $destination;

                $breadcrumbData = [];
                $breadcrumbData[] = ['url'=>'/','label'=>'Home'];
                $breadcrumbData[] = ['url'=>route('hotelListing'),'label'=>$breadcrumb_title];
                $breadcrumbData[] = ['label'=>$accommodation->name];
                $data['breadcrumbData'] = $breadcrumbData;

                $params = [];
                $search_data = CustomHelper::getSearchData('hotel',$params);
                if(isset($search_data['checkin'])) {
                    $search_data['text'] = $accommodation->name.', '.$destination;
                    $search_data['search_slug'] = 'h'.$accommodation->id;
                } else if(isset($search_data['default_filters'])){
                    $search_data['default_filters']['text'] = $accommodation->name.', '.$destination;
                    $search_data['default_filters']['search_slug'] = 'h'.$accommodation->id;                    
                }
                $data['search_data'] = $search_data;
                $data['seo_data'] = $seo_data;
View::share('seo_data', $seo_data);
                return Inertia::render('hotels/Details', $data);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }


}