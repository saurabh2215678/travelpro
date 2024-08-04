<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CustomHelper;
use App\Models\Accommodation;
use App\Models\AccommodationInfo;
use App\Models\AccommodationCategory;
use App\Models\AccommodationFeature;
use App\Models\ThemeCategories;
use App\Models\Destination;
use App\Models\SeoMetaTag;
use App\Models\Faq;

use DB;
use Validator;

class AccommodationController extends Controller {

	private $limit;

    public function __construct() {
    	$this->limit = CustomHelper::WebsiteSettings('FRONT_PAGE_LIMIT');
    }

    public function index(Request $request) {
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


        $data = [];
        // $limit = $this->limit;
        $limit = 10;
        $destination_id = (isset($request->destination) && !empty($request->destination)) ? $request->destination : 0;
        
        $sub_destination = (isset($request->sub_destination) && !empty($request->sub_destination)) ? $request->sub_destination : 0;
        $query = Accommodation::with('accommodationCategories','accommodationDestination')->select('accommodations.*')->leftjoin('destinations as D','D.id','=','accommodations.destination_id')->where('accommodations.status',1)->orderBy("accommodations.sort_order", "ASC");

        $query->where(function($query1){
            $query1->where('accommodations.is_deleted',0);
            $query1->orWhereNull('accommodations.is_deleted');
        });

        if($sub_destination) {
            $query->where('accommodations.destination_id',$sub_destination);
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
        // prd($inventory);

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

        $accommodation = $query->paginate($limit);
        $data['accommodations'] = $accommodation;
        
        $data['destination_id'] = $destination_id = (isset($request->destination) && !empty($request->destination)) ? $request->destination : 0;
        //$data["destinations"] = Destination::where('is_city',0)->where(['status'=>1,'parent_id'=>0])->get();

        $seo_tags = CustomHelper::getSeoModules('hotel_listing');
        $data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
        $data['page_url_label'] = $seo_tags->page_url_label??'Hotels';
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
        $data['banner_image'] = $banner_image;
        $data['checkin'] = $checkin;
        $data['checkout'] = $checkout;                
        $data['adult'] = $adult;
        $data['child'] = $child;
        $data['infant'] = $infant;

        $data["categories"] = AccommodationCategory::where("status", 1)->get();
        $data["features"] = AccommodationFeature::where("status", 1)->get();

        return view(config('custom.theme').'.accommodations.hotel_listing', $data);
    }


    public function ajaxSearchHotels(Request $request) {
        $response = [];
        $response['success'] = false;
        $response['message'] = '';
        $text = $request->text??'';
        
        $query = Accommodation::with('accommodationCategories','accommodationDestination')->select('accommodations.*')->leftjoin('destinations as D','D.id','=','accommodations.destination_id')->where('accommodations.status',1);
        $query->where(function($query1){
            $query1->where('accommodations.is_deleted',0);
            $query1->orWhereNull('accommodations.is_deleted');
        });
        $query->orderBy('accommodations.featured','DESC');
        $query->orderBy('accommodations.sort_order','ASC');
        if($request->text) {
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
            $hotels = $query->limit(30)->get();
        }else{
            $query->whereHas('accommodationDestination',function($q2) {
                $q2->where('featured',1);
            });
            $hotels = $query->limit(10)->get();
        }
            // prd($hotels->toArray());
            $destination_arr = [];
            $category_arr = [];
            $activity_arr = [];
            $title_arr = [];
            if($hotels) {
                foreach($hotels as $hotel) {
                    $destination = $hotel->accommodationDestination->name??'';
                    $category_id = '';
                    $category_title = '';
                    if($hotel->category_id) {
                        $category_id = $hotel->category_id;
                        $category_title = $hotel->accommodationCategories->name??'';
                    }
                    if($destination && (stripos($destination, $text) !== false) && !in_array($destination, $title_arr)) {
                        $destination_arr[] = [
                            'slug' => 'd'.$hotel->destination_id,
                            'id' => $hotel->destination_id,
                            'title' => '<i class="fa-solid fa-location-dot"></i>'.$destination,
                        ];
                        $title_arr[] = $destination;
                        if(!empty($text)){
                            $title = $hotel->name.', '.$destination;
                            $activity_arr[] = [
                                'slug' => 'h'.$hotel->id,
                                'id' => $hotel->id,
                                'title' => '<i class="fa-regular fa-building"></i>'.$title,
                            ];
                            $title_arr[] = $title;
                        }
                    } else if($category_id && $category_title && !in_array($category_title, $title_arr) && !empty($text)) {
                        $category_arr[] = [
                            'slug' => 'c'.$category_id,
                            'id' => $category_id,
                            'title' => '<i class="fa-solid fa-location-dot"></i>'.$category_title,
                        ];
                        $title_arr[] = $category_title;

                        $activity_arr[] = [
                            'slug' => 'h'.$hotel->id,
                            'id' => $hotel->id,
                            'title' => '<i class="fa-regular fa-building"></i>'.$hotel->name,
                        ];
                        $title_arr[] = $hotel->name;
                    } else if(!in_array($hotel->name, $title_arr) && !empty($text)){
                        $title = $hotel->name;
                        if($destination) {
                            $title = $title.', '.$destination;
                        }
                        $activity_arr[] = [
                            'slug' => 'h'.$hotel->id,
                            'id' => $hotel->id,
                            'title' => '<i class="fa-regular fa-building"></i>'.$title,
                        ];
                        $title_arr[] = $title;
                    }
                }
            }
            $result = array_merge($destination_arr,$category_arr,$activity_arr);
            $response['success'] = true;
            $response['result'] = $result;
        return response()->json($response);
    }


    //hotel detail  page
    public function detail(Request $request) {
        $data = [];
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
                $accomodation_info = AccommodationInfo::where(['status'=>1, 'accommodation_id'=>$accommodation->id])->orderBy('sort_order','ASC')->get();
                $seo_tags = CustomHelper::getSeoModules('hotel_listing');
                $banner_image = '';
                if($seo_tags->image) {
                    $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
                }

                $faq_accomodation = Faq::where('tbl_name','=','accommodations')->where('status',1)->where('tbl_id',$accommodation->id)->orderBy("sort_order", "ASC");
                $faq_row = $faq_accomodation->take(10)->get();

                $data['accomodation_info'] = $accomodation_info??'';
                $data['breadcrumb_title'] = $seo_tags->page_url_label??'Hotels';

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
                }

                if($accommodation->meta_description) {
                    $meta_description = $accommodation->meta_description;
                } else {
                    $meta_description = $meta_title;
                }

                $data['meta_title'] = $meta_title ?? '';
                $data['meta_description'] = (!empty($accommodation->meta_description))?$accommodation->meta_description:'';
                $data['meta_keyword'] = (!empty($accommodation->meta_keyword))?$accommodation->meta_keyword:'';

                $data['accommodation'] = $accommodation;
                $data['banner_image'] = $banner_image;
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
                $data['checkin'] = $checkin;
                $data['checkout'] = $checkout;
                $data['inventory'] = $inventory;
                $data['adult'] = $adult;
                $data['child'] = $child;
                $data['infant'] = $infant;
                $destination = $accommodation->accommodationDestination->name??'';
                $data['search_text'] = $accommodation->name.', '.$destination;
                $data['search_slug'] = 'h'.$accommodation->id;
                return view(config('custom.theme').'.accommodations.hotel_detail', $data);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }

    }


    /* end of controller */
}
