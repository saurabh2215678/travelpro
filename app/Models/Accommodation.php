<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CustomHelper;
use Storage;

class Accommodation extends Model {

    protected $table = 'accommodations';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function accommodationFeature() {
        return $this->belongsTo('App\Models\AccommodationFeature', 'id');
    }
    /**
     * @return BelongsTo
     */
    public function accommodationDestination(): BelongsTo
    {
        return $this->belongsTo('App\Models\Destination', 'destination_id');
    }

    public function accommodationFacility() {
        return $this->belongsTo('App\Models\AccommodationFacility', 'id');
    }
    /**
     * @return BelongsTo
     */
    public function accommodationCategories(): BelongsTo
    {
        return $this->belongsTo('App\Models\AccommodationCategory', 'category_id');
    }

    public function accommodationInfo() {
        return $this->hasMany('App\Models\accommodationInfo', 'accommodation_id')->orderBy('sort_order','ASC');
    }

    public function accommodationImages() {
        return $this->hasMany('App\Models\CommonImage', 'tbl_id')->where('tbl_name','accommodations')->where('category','gallery')->orderBy('is_default','DESC')->orderBy('sort_order','ASC');
    }

    public function accommodationBanners() {
        return $this->hasMany('App\Models\CommonImage', 'tbl_id')->where('tbl_name','accommodations')->where('category','banner')->orderBy('is_default','DESC')->orderBy('sort_order','ASC');
    }
    /**
     * @return HasMany
     */
    public function AccommodationLocation(): HasMany
    {
        return $this->hasMany('App\Models\AccommodationLocation', 'accommodation_id')->where('is_default',0);
    }
    /**
     * @return HasOne
     */
    public function AccommodationDefaultLocation(): HasOne
    {
        return $this->hasOne('App\Models\AccommodationLocation', 'accommodation_id')->where('is_default',1);
    }
    /**
     * @return HasMany
     */
    public function AccommodationRooms(): HasMany
    {
        return $this->hasMany('App\Models\AccommodationRoom', 'accommodation_id')->where('status',1)->orderBy('sort_order','asc');
    }

    public function vendorData() {
        return $this->belongsTo('App\Models\Admin', 'vendor_id');
    }

    public static function parseHotel($hotel,$params=[]) {
        if($hotel && $hotel->id) {
            $size = $params['size']??'box';
            $search_data = $params['search_data']??[];
            unset($search_data['default_filters']);
            unset($search_data['checkin_picker']);
            unset($search_data['checkout_picker']);
            unset($search_data['guest_title']);

            $inventory = $search_data['inventory']??1;

            $search_data['slug'] = $hotel->slug;

            $storage = Storage::disk('public');
            $url = route('hotelDetail',$search_data);
            $path = "accommodations/";
            $hotel_image = $hotel->image;
            $hotelSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
            $thumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($hotel_image)) {
                if($storage->exists($path.$hotel_image)) {
                    $hotelSrc = asset('/storage/'.$path.$hotel_image);
                    $thumbSrc = asset('/storage/'.$path.'thumb/'.$hotel_image);
                }
            }

            $accommodationCategories = $hotel->accommodationCategories;
            $category_name = $accommodationCategories->title ?? '';

            $name_arr = [];
            $place_name = $hotel->AccommodationDefaultLocation->DestinationLocation->name??'';
            if($place_name) {
                $name_arr[] = $place_name;
            }
            $distination_name = $hotel->accommodationDestination->name??'';
            if($distination_name) {
                $name_arr[] = $distination_name;
            }
            if(!empty($name_arr)) {
                $place_name =  implode(', ',$name_arr);
            }
            
            $publish_price = $hotel->publish_price*$inventory??0;
            $search_price = $hotel->search_price*$inventory??0;

            /*$is_agent = Auth::user()->is_agent??0;
            $agent_group_id = '-1';
            if($is_agent==1) {
                $discount_category_id = $hotel->discount_category_id;
                if($publish_price > 0 && $discount_category_id !== 0) {
                    $module_name = 'hotel_listing';
                    $agent_group_id = Auth::user()->group_id??0;
                    $discount_amount = CustomHelper::getDiscountPrice($module_name, $discount_category_id, $publish_price, $agent_group_id, $hotel->id, $inventory);
                    if($publish_price > $discount_amount) {
                        $search_price = $publish_price - $discount_amount;
                    }
                }
            }*/

            $accommodation = [
                'id'=> $hotel->id,
                'name'=> $hotel->name,
                'brief'=> CustomHelper::wordsLimit($hotel->brief),
                'star_classification'=> $hotel->star_classification,
                'hotelSrc'=> $hotelSrc,
                'thumbSrc'=> $thumbSrc,
                'category_name'=> $category_name,                
                'place_name'=> $place_name,
                'rating'=> $hotel->rating,
                'total_reviews'=> $hotel->total_reviews,
                'search_price'=> $search_price,
                'publish_price'=> $publish_price,
                'url'=> $url,
            ];
            if($size == 'listing' || $size == 'detail') {
                $accommodationImages = $hotel->accommodationImages??[];
                $accommodationImages_arr = [];
                if(!empty($accommodationImages) && count($accommodationImages) > 0) {
                    $image_arr = [];
                    if($size == 'detail' && $hotel_image) {
                        $image_arr[] = $hotel_image;
                    }
                    foreach($accommodationImages as $image) {
                        $accommodationThumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
                        if($image->name && !in_array($image->name, $image_arr)) {
                            $image_arr[] = $image->name;
                            if($storage->exists($path.$image->name)){
                                $accommodationSrc = asset('/storage/'.$path.$image->name);
                                $thumbSrc = asset('/storage/'.$path.'thumb/'.$image->name);
                            } else {
                                $accommodationSrc = $accommodationThumbSrc;
                                $thumbSrc = $accommodationThumbSrc;
                            }
                            $accommodationImages_arr[] = [
                                'title' => $image->title,
                                'src' => $accommodationSrc,
                                'thumbSrc' => $thumbSrc,
                            ];
                        }
                    }
                } else {
                    $accommodationImages_arr[] = [
                        'title' => $hotel->name,
                        'src' => $hotelSrc,
                        'thumbSrc' => $thumbSrc,
                    ];
                }
                $accommodation['accommodationImages'] = $accommodationImages_arr;


                $accommodation_features = isset($hotel->accommodation_feature) ? json_decode($hotel->accommodation_feature): [];
                $accommodation_features_arr = [];
                if($accommodation_features){
                    foreach ($accommodation_features as $key => $feature_id) {
                        $is_featured = CustomHelper::showAccommodationFeature($feature_id,'is_featured');
                        if($is_featured == 1){
                            $accommodation_features_arr[] = [
                                'src' => CustomHelper::showAccommodationFeature($feature_id,'icon'),
                                'name' => CustomHelper::showAccommodationFeature($feature_id),
                            ];
                        }
                    }
                }
                $accommodation['accommodation_features_arr'] = $accommodation_features_arr;

                $accommodation_facilities = $hotel->accommodation_facility?json_decode($hotel->accommodation_facility):[];
                $accommodation_facilities_arr = [];
                if($accommodation_facilities){
                    foreach ($accommodation_facilities as $key => $facility_id) {
                        $name = CustomHelper::showAccommodationFacility($facility_id);
                        if($name) {
                            $accommodation_facilities_arr[] = [
                                'name' => $name,
                            ];
                        }
                    }
                }
                $accommodation['accommodation_facilities_arr'] = $accommodation_facilities_arr;
            }
            if($size == 'detail') {
                $AccommodationRooms = $hotel->AccommodationRooms??[];
                $rooms_path = 'accommodation_rooms/';
                $roomDefaultThumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
                $roomData = [];
                $roomPlans = [];
                foreach ($AccommodationRooms as $key => $room) {
                    $room_images_arr = $room->accommodationRoomImages??[];
                    $room_images = [];
                    if(!empty($room_images_arr) && count($room_images_arr) > 0) {
                        foreach($room_images_arr as $image) {
                            $roomThumbSrc = $roomDefaultThumbSrc;
                            $roomSrc = $roomDefaultThumbSrc;
                            if($image->name) {
                                $roomSrc = asset('/storage/'.$rooms_path.$image->name);
                                $roomThumbSrc = asset('/storage/'.$rooms_path.'thumb/'.$image->name);
                                $room_images[] = [
                                    'thumbSrc' => $roomThumbSrc,
                                    'src' => $roomSrc,
                                    'title' => $image->name,
                                ];
                            }
                        }
                    }
                    $room_features = $room->room_features ?json_decode($room->room_features):[];
                    $room_features_arr = [];
                    if($room_features){
                        foreach ($room_features as $room_feature_id) {
                            $room_features_arr[] = [
                                'name' => CustomHelper::showRoomFeature($room_feature_id),
                            ];
                        }
                    }

                    // prd($room_features_arr);
                    $planData = $room->planData??[];
                    $roomPlans = [];
                    foreach ($planData as $key => $room_plan) {
                        $plan_json_data = json_decode($room_plan->plan_json_data)??[];
                        $includes_arr = $plan_json_data->includes??[];
                        $options = $plan_json_data->options??[];
                        $includes =[];
                        foreach($includes as $include){
                            $includes[] = [
                                'is_available' => CustomHelper::showRoomPlanInclude($include,'available'),
                            ];
                        }
                        $search_data = CustomHelper::getSearchData('hotel',$params=[]);
                        if(isset($search_data['default_filters'])) {
                            $checkin = $search_data['default_filters']['checkin']??'';
                            $checkout = $search_data['default_filters']['checkout']??'';
                            $inventory = $search_data['default_filters']['inventory']??1;
                        } else {
                            $checkin = $search_data['checkin']??'';
                            $checkout = $search_data['checkout']??'';
                            $inventory = $search_data['inventory']??1;
                        }

                        $sold_out = true;
                        $params = [];
                        $params['room_id'] = $room_plan->room_id;
                        $params['plan_id'] = $room_plan->id;
                        $params['checkin'] = $checkin;
                        $params['checkout'] = $checkout;
                        $params['inventory'] = $inventory;
                        $inventory_data = CustomHelper::getAccommodationInventory($params);

                        $roomPlans[] = [
                            'plan_name' => $room_plan->plan_name,
                            'includes' => $includes,
                            'options' => $options,
                            'price' => $room_plan->price??0.00,
                            'single_price' => $room_plan->single_price??0.00,
                            'extra_adult' => $room_plan->extra_adult??0.00,
                            'extra_child_1' => $room_plan->extra_child_1??0.00,
                            'extra_child_2' => $room_plan->extra_child_2??0.00,
                            'inventory_data' => $inventory_data,
                        ];
                    }
                    $max_occupancy = $room->max_occupancy?? '';
                    $max_adult_bed = $room->max_adult_bed? (int)$room->max_adult_bed:(int)$room->base_occupancy;
                    $max_child_no = 0;
                    $child_no =$room->max_child_no?$room->max_child_no : $room->base_child_no;
                    if($child_no && $child_no > 0){
                        if($max_adult_bed < $max_occupancy){
                            $diff_child_no =  $max_occupancy -$max_adult_bed;
                            if($diff_child_no > $child_no){
                                $max_child_no= $child_no;
                            }else{
                                $max_child_no = $diff_child_no;
                            }
                        }
                    }
                    $roomData[]= [
                        'plan_data' => $roomPlans??[],
                        'brief' => $room->brief??'',
                        'description' => $room->description??'',
                        'room_type_name' => $room->room_type_name??'',
                        'video_link' => $room->video_link??'',
                        'room_images' => $room_images,
                        'room_features' => $room_features_arr,
                        'roomThumbSrc' => $roomDefaultThumbSrc,
                        'max_adult_bed' => $max_adult_bed,
                        'max_child_no' => $max_child_no,
                        'publish_price' => $room->publish_price,
                    ];
                }
                
                $accommodation_features = isset($hotel->accommodation_feature) ? json_decode($hotel->accommodation_feature): [];
                $accommodation_features_arr = [];
                if($accommodation_features){
                    foreach ($accommodation_features as $key => $feature_id) {
                        $accommodation_features_arr[] = [
                            'src' => CustomHelper::showAccommodationFeature($feature_id,'icon'),
                            'name' => CustomHelper::showAccommodationFeature($feature_id),
                        ];
                    }
                }
                $accommodation['accommodation_features_arr'] = $accommodation_features_arr;

                $map_src = CustomHelper::fetchIframeSrc($hotel->map);

                $accommodation['brief'] = $hotel->brief;
                $accommodation['description'] = $hotel->description;
                $accommodation['accommodationRooms'] = $roomData;
                $accommodation['map_src'] = $map_src;
                $accommodation['policies'] = $hotel->policies;
            }
            return $accommodation;
        }
    }

}