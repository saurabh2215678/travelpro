<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use App\Models\Blog;
use App\Models\Testimonial;
use App\Models\Enquiry;
use App\Models\Accommodation;
use App\Models\AccommodationPlan;
use App\Models\CmsPages;
use App\Models\PackageAccommodation;
use App\Models\Order;
use App\Models\Cron;
use App\Models\CommonImage;
use App\Models\Package;
use App\Models\PackagePriceInfo;
use App\Models\PackageToTheme;
use App\Models\ThemeCategories;
use App\Models\PackageInclusion;
use App\Models\Flag;
use App\Models\PackageFlags;
use Image;
use DB;
use Storage;
use PDF;
use File;

class ToolsController extends Controller {

    //private $limit;
    //private $ADMIN_ROUTE_NAME;

    public function __construct() {
       ini_set('memory_limit', '-1');
       ini_set('max_execution_time', 1000);
       // $this->limit = 100;
    }

    public function phpinfo(Request $request) {
        // $resp = Order::processPaymentSuccess(1356);
        // prd($resp);
        // $order = Order::find(1471);
        // $resp = Order::parseIIAIRBookingDetails($order);
        // $order->booking_details = json_encode($resp);
        // $order->save();
        // prd($resp);

        pr(gd_info());
        phpinfo();
    }

    public function crons(Request $request) {
        $data = [];
        $records = Cron::where('status',1)->get();
        $data['records'] = $records;
        return view("admin.tools.crons", $data);
    }

    public function import_destinations(Request $request) {
        // DELETE FROM destinations;
        // ALTER TABLE destinations AUTO_INCREMENT = 1;

        $destinations = DB::connection('mysql_old')->table('tbl_destinations')->get();
        if($destinations) {
            $destination_types = DB::connection('mysql')->table('destination_type')->get()->pluck('id','slug')->toArray();
            foreach($destinations as $destination) {
                $record = [
                    'id' => $destination->destination_id, 
                    'name' => $destination->destination_name, 
                    'parent_id' => $destination->parent_id, 
                    'is_city' => $destination->is_city??0, 
                    'destination_type' => $destination_types[$destination->dest_type]??0, 
                    'slug' => $destination->slug, 
                    'brief' => $destination->brief, 
                    'description' => $destination->description, 
                    'image' => $destination->images, 
                    'feature_image' => '', 
                    'banner_image' => '', 
                    'video_link' => $destination->video, 
                    'latitude' => $destination->latitude, 
                    'longtitude' => $destination->longitude, 
                    'sort_order' => $destination->sort_order, 
                    'best_months' => '', 
                    'meta_title' => $destination->pages_title, 
                    'meta_keyword' => $destination->pages_keywords, 
                    'meta_description' => $destination->pages_description, 
                    'status' => $destination->status, 
                    'featured' => $destination->top_destination, 
                    'is_deleted' => $destination->deleted??0,
                    'created_at' => ($destination->date_added=='0000-00-00 00:00:00')?NULL:$destination->date_added, 
                    'updated_at' => ($destination->date_modify=='0000-00-00 00:00:00')?NULL:$destination->date_modify,
                ];
                // prd($destinations_arr);
                $destination_id = DB::connection('mysql')->table('destinations')->insertGetId($record);

                if($destination->images) {
                    $common_images = [
                        'tbl_id' => $destination->destination_id,
                        'category' => 'gallery', 
                        'tbl_name' => 'destinations', 
                        'name' => $destination->images, 
                        'title' => $destination->destination_name, 
                        'link' => '', 
                        'sort_order' => 0, 
                        'is_default' => 1, 
                        'status' => 1, 
                        'created_at' => ($destination->date_added=='0000-00-00 00:00:00')?NULL:$destination->date_added, 
                        'updated_at' => ($destination->date_modify=='0000-00-00 00:00:00')?NULL:$destination->date_modify,
                    ];
                    // prd($destinations_arr);
                    DB::connection('mysql')->table('common_images')->insert($common_images);
                }
            }
        }

        $highlights = DB::connection('mysql_old')->table('tbl_highlight')->get();
        if($highlights) {
            $destination_info = [];
            foreach($highlights as $highlight) {
                
                switch ($highlight->type) {
                    case 'activity':
                            $type = 1;
                        break;
                    case 'highlight':
                            $type = 2;
                        break;
                    case 'info':
                            $type = 3;
                        break;
                    case 'festival':
                            $type = 4;
                        break;
                    case 'festival_date':
                            $type = 5;
                        break;
                    case "FAQ":
                            $type = 6;
                        break;
                    case "Do's & Don'ts":
                            $type = 7;
                        break;
                    default:
                            $type = 0;
                        break;
                }
                $destination_info[] = [
                    'id' => $highlight->id, 
                    'destination_id' => $highlight->destination_id, 
                    'title' => $highlight->title, 
                    'type' => $type, 
                    'brief' => $highlight->brief, 
                    'description' => $highlight->description,
                    'image' => $highlight->images,  
                    'status' => $highlight->status, 
                    'sort_order' => $highlight->sort_order, 
                    'created_at' => ($highlight->date_added=='0000-00-00 00:00:00')?NULL:$highlight->date_added, 
                    'updated_at' => ($highlight->date_modify=='0000-00-00 00:00:00')?NULL:$highlight->date_modify,
                ];
            }
            if(!empty($destination_info)) {
                DB::connection('mysql')->table('destination_info')->insert($destination_info);
            }
        }        
        echo 'import Done';
    }

    public function import_accommodations(Request $request) {
        $accommodation_categories = DB::connection('mysql_old')->table('tbl_hotel_categories')->get();
        if(!empty($accommodation_categories)) {
            $accommodation_categories_arr = [];
            foreach($accommodation_categories as $accommodation_category) {
                $accommodation_categories_arr[] = [
                    'id' => $accommodation_category->categories_id, 
                    'title' => $accommodation_category->title, 
                    'sort_order' => $accommodation_category->sort_order, 
                    'status' => $accommodation_category->status, 
                    'created_at' => ($accommodation_category->date_added=='0000-00-00 00:00:00')?NULL:$accommodation_category->date_added, 
                    'updated_at' => ($accommodation_category->date_modify=='0000-00-00 00:00:00')?NULL:$accommodation_category->date_modify,
                ];
            }
            if(!empty($accommodation_categories_arr)) {
                DB::connection('mysql')->table('accommodation_categories')->insert($accommodation_categories_arr);
            }
        }

        $accommodation_facilities = DB::connection('mysql_old')->table('tbl_hotel_facility')->get();
        if(!empty($accommodation_facilities)) {
            $accommodation_facilities_arr = [];
            foreach($accommodation_facilities as $accommodation_facility) {
                $accommodation_facilities_arr[] = [
                    'id' => $accommodation_facility->facility_id, 
                    'title' => $accommodation_facility->title,
                    'sort_order' => $accommodation_facility->sort_order, 
                    'status' => $accommodation_facility->status, 
                    'created_at' => ($accommodation_facility->date_added=='0000-00-00 00:00:00')?NULL:$accommodation_facility->date_added, 
                    'updated_at' => ($accommodation_facility->date_modify=='0000-00-00 00:00:00')?NULL:$accommodation_facility->date_modify,
                ];
            }
            if(!empty($accommodation_facilities_arr)) {
                DB::connection('mysql')->table('accommodation_facilities')->insert($accommodation_facilities_arr);
            }
        }

        $accommodation_features = DB::connection('mysql_old')->table('tbl_hotel_feature')->get();
        if(!empty($accommodation_features)) {
            $accommodation_features_arr = [];
            $room_feature_arr = [];
            foreach($accommodation_features as $accommodation_feature) {
                $accommodation_features_arr[] = [
                    'id' => $accommodation_feature->feature_id, 
                    'title' => $accommodation_feature->title, 
                    'feature_type' => $accommodation_feature->feature_type, 
                    'sort_order' => $accommodation_feature->sort_order, 
                    'is_deleted' => $accommodation_feature->deleted, 
                    'status' => $accommodation_feature->status, 
                    'created_at' => ($accommodation_feature->date_added=='0000-00-00 00:00:00')?NULL:$accommodation_feature->date_added, 
                    'updated_at' => ($accommodation_feature->date_modify=='0000-00-00 00:00:00')?NULL:$accommodation_feature->date_modify,
                ];

                $room_feature_arr[] = [
                    'id' => $accommodation_feature->feature_id, 
                    'title' => $accommodation_feature->title, 
                    'sort_order' => $accommodation_feature->sort_order, 
                    'status' => $accommodation_feature->status, 
                    'created_at' => ($accommodation_feature->date_added=='0000-00-00 00:00:00')?NULL:$accommodation_feature->date_added, 
                    'updated_at' => ($accommodation_feature->date_modify=='0000-00-00 00:00:00')?NULL:$accommodation_feature->date_modify,
                ];
            }
            if(!empty($accommodation_features_arr)) {
                DB::connection('mysql')->table('accommodation_features')->insert($accommodation_features_arr);
            }
            if(!empty($room_feature_arr)) {
                DB::connection('mysql')->table('room_feature')->insert($room_feature_arr);
            }
        }

        $hotels = DB::connection('mysql_old')->table('tbl_hotel')->get();
        foreach($hotels as $hotel) {
            $old_id = $hotel->hotel_id ;  
            $hotel_to_features = DB::connection('mysql_old')->table('tbl_hotel_to_feature')->where('hotel_id',$old_id)->pluck('feature_id')->toArray();
            $accommodation_feature = json_encode(array_map('strval', $hotel_to_features));

            $facilities_ids =  $hotel->fac_ids;
            $facilities_arr = explode(",",$facilities_ids);
            $facilities_json = json_encode($facilities_arr);

            $related_hotels_ids =  $hotel->related_hotels;
            $related_hotels_arr = explode(",",$related_hotels_ids);
            $related_hotels_json = json_encode($related_hotels_arr);

            $req_data = array(
                'id' => $old_id,
                'slug' => $hotel->slug,
                'accommodation_feature' => $accommodation_feature,
                'accommodation_facility' => $facilities_json,
                'destination_id' => $hotel->destination_id,
                'name' => $hotel->hotel_name,
                'category_id' => $hotel->categories_id,
                'image' => $hotel->images,
                'address' => ($hotel->address) ? substr($hotel->address, 0,255) : '',
                'contact_phone' => $hotel->hotel_phone,
                'contact_email' => $hotel->hotel_email,
                'map' => $hotel->map_iframe_src,
                'rating' => $hotel->rating,
                'brief' => $hotel->brief,
                'description' => $hotel->description,
                'latitude' => $hotel->latitude,
                'longtitude' => $hotel->longitude,
                'star_classification' => $hotel->hotel_star,
                'related_hotels' => $related_hotels_json,
                'is_deleted' => $hotel->deleted,
                // '' => $hotel->location,
                // '' => $hotel->hotel_tag,
                // '' => $hotel->hotel_website,
                // '' => $hotel->checkin_time,
                // '' => $hotel->checkout_time,
                // '' => $hotel->payment_options,
                // '' => $hotel->policy_terms,
                'featured' => $hotel->featured,
                'status' => $hotel->status,
                'sort_order' => $hotel->sort_order,
                'meta_title' => $hotel->pages_title,
                'meta_description' => $hotel->pages_description,
                'meta_keywords' => $hotel->pages_keywords,
                'created_at' =>  ($hotel->date_added=='0000-00-00 00:00:00')?NULL:$hotel->date_added,
                'updated_at' => ($hotel->date_modify=='0000-00-00 00:00:00')?NULL:$hotel->date_modify,    
            );
            $accommodation_id = Accommodation::insertGetId($req_data);

            if($hotel->images) {
                $common_images = [
                    'tbl_id' => $hotel->hotel_id,
                    'category' => 'gallery', 
                    'tbl_name' => 'accommodations', 
                    'name' => $hotel->images, 
                    'title' => $hotel->hotel_name, 
                    'link' => '', 
                    'sort_order' => 0, 
                    'is_default' => 1, 
                    'status' => 1, 
                    'created_at' => ($hotel->date_added=='0000-00-00 00:00:00')?NULL:$hotel->date_added, 
                    'updated_at' => ($hotel->date_modify=='0000-00-00 00:00:00')?NULL:$hotel->date_modify,
                ];
                DB::connection('mysql')->table('common_images')->insert($common_images);
            }
        }


        $hotel_to_destinations = DB::connection('mysql_old')->table('tbl_hotel_to_destinations')->get();
        if($hotel_to_destinations) {
            $accommodation_locations_arr = [];
            foreach($hotel_to_destinations as $key => $hotel_to_destination) {
                $accommodation_locations_arr[] = [
                    'accommodation_id' => $hotel_to_destination->hotel_id,
                    'destination_location_id' => $hotel_to_destination->destination_id,
                ];
            }
            if(!empty($accommodation_locations_arr)) {
                DB::table('accommodation_locations')->insert($accommodation_locations_arr);
            }
        }

        $hotel_addinfos = DB::connection('mysql_old')->table('tbl_hotel_addinfo')->get();
        if($hotel_addinfos) {
            $accommodation_info_arr = [];
            foreach($hotel_addinfos as $key => $hotel_addinfo) {
                $accommodation_info_arr[] = [
                    'accommodation_id' => $hotel_addinfo->hotel_id,
                    'title' => $hotel_addinfo->title,
                    'description' => $hotel_addinfo->description,
                    'brief' => $hotel_addinfo->brief,
                    'image' => $hotel_addinfo->images,
                    'status' => $hotel_addinfo->status,
                    'sort_order' => $hotel_addinfo->sort_order,
                    'created_at' => ($hotel_addinfo->date_added=='0000-00-00 00:00:00') ? NULL:$hotel_addinfo->date_added,
                    'updated_at' => ($hotel_addinfo->date_modify=='0000-00-00 00:00:00') ? NULL:$hotel_addinfo->date_modify,
                ];
            }
            if(!empty($accommodation_info_arr)) {
                DB::table('accommodation_info')->insert($accommodation_info_arr);
            }
        }

        $hotel_roomtypes = DB::connection('mysql_old')->table('tbl_hotel_roomtype')->get();
        $room_type_arr = [];
        if($hotel_roomtypes) {
            foreach($hotel_roomtypes as $key => $room_type) {
                $room_type_arr[$room_type->id] = [
                    'id' => $room_type->id,
                    'title' => $room_type->title,
                    'sort_order' => $room_type->sort_order,
                    'status' => $room_type->status,
                    'created_at' => ($room_type->date_added=='0000-00-00 00:00:00') ? NULL:$room_type->date_added,
                    'updated_at' => ($room_type->date_modify=='0000-00-00 00:00:00') ? NULL:$room_type->date_modify,
                ];
            }
            if(!empty($room_type_arr)) {
                DB::table('room_type')->insert($room_type_arr);
            }
        }

        $hotel_rooms = DB::connection('mysql_old')->table('tbl_hotel_rooms')->get();
        if($hotel_rooms) {
            $hotel_room_arr = [];
            $hotel_room_plan_arr = [];
            $common_images_arr = [];
            foreach($hotel_rooms as $key => $hotel_room) {
                $room_type_id = NULL;
                $room_type_name = '';
                if(!empty($hotel_room->room_type)) {
                    $room_type_id = $hotel_room->room_type;
                    if(isset($room_type_arr[$hotel_room->room_type])) {
                        $room_type_name = $room_type_arr[$hotel_room->room_type]['title']??'';
                    }
                }

                $price = 0;
                $bed_price = 0;
                $room_price = DB::connection('mysql_old')->table('tbl_hotel_room_price')->where('room_id',$hotel_room->room_id)->first();
                if($room_price) {
                    $price = $room_price->price??0;
                    $bed_price = $room_price->bed_price??0;
                }

                $hotel_room_arr[] = [
                    'id' => $hotel_room->room_id,
                    'accommodation_id' => $hotel_room->hotel_id,
                    'room_type_id' => $room_type_id,
                    'room_type_name' => $room_type_name,
                    'brief' => $hotel_room->brief,
                    'description' => $hotel_room->description,
                    // 'images' => $hotel_room->images,
                    'base_price' => $price,
                    'publish_price' => $price,
                    'quantity' => $hotel_room->quantity,
                    'max_adult_bed' => $hotel_room->max_quantity,
                    'max_quantity' => $hotel_room->max_quantity,
                    'minimum_stay' => $hotel_room->minimum_stay,
                    'max_adults' => $hotel_room->max_adults,
                    'max_children' => $hotel_room->max_children,
                    'max_child_no' => $hotel_room->max_children,
                    'no_of_exrta_beds' => $hotel_room->no_of_exrta_beds,
                    'price_extra_adult' => $hotel_room->exrta_bed_charges,
                    'room_features' => $hotel_room->features,
                    // 'added_by' => $hotel_room->added_by,
                    // 'modified_by' => $hotel_room->modified_by,
                    'status' => $hotel_room->status,
                    // 'deleted' => $hotel_room->deleted,
                    'sort_order' => $hotel_room->sort_order,
                    'created_at' => ($hotel_room->date_added=='0000-00-00 00:00:00') ? NULL:$hotel_room->date_added,
                    'updated_at' => ($hotel_room->date_modify=='0000-00-00 00:00:00') ? NULL:$hotel_room->date_modify,
                ];

                $hotel_room_plan_arr[] = [
                    'accommodation_id' => $hotel_room->hotel_id,
                    'room_id' => $hotel_room->room_id,
                    'plan_name' => 'EP',
                    'meal_type' => 'accommodation_only',
                    'plan_json_data' => NULL,
                    'price' => $price,
                    // 'single_price' => 0,
                    'extra_adult' => $bed_price,
                    // 'extra_child_1' => 0,
                    // 'extra_child_2' => 0,
                    'is_default' => 1,
                    'status' => 1,
                    'created_at' => ($hotel_room->date_added=='0000-00-00 00:00:00') ? NULL:$hotel_room->date_added,
                    'updated_at' => ($hotel_room->date_modify=='0000-00-00 00:00:00') ? NULL:$hotel_room->date_modify,
                ];

                if($hotel_room->images) {
                    $common_images_arr[] = [
                        'tbl_id' => $hotel_room->room_id,
                        'category' => 'gallery', 
                        'tbl_name' => 'accommodation_rooms', 
                        'name' => $hotel_room->images, 
                        'title' => CustomHelper::wordsLimit($hotel_room->brief,false,true),
                        'link' => '', 
                        'sort_order' => 0, 
                        'is_default' => 1, 
                        'status' => 1, 
                        'created_at' => ($hotel_room->date_added=='0000-00-00 00:00:00')?NULL:$hotel_room->date_added, 
                        'updated_at' => ($hotel_room->date_modify=='0000-00-00 00:00:00')?NULL:$hotel_room->date_modify,
                    ];
                }
            }
            if(!empty($hotel_room_arr)) {
                DB::table('accommodation_room')->insert($hotel_room_arr);
            }
            if(!empty($hotel_room_plan_arr)) {
                DB::table('accommodation_plans')->insert($hotel_room_plan_arr);
            }
            if(!empty($common_images_arr)) {
                DB::table('common_images')->insert($common_images_arr);
            }
        }
        echo 'import Done';
    }

    public function import_blogs(Request $request) {

        $blog_categories = DB::connection('mysql_old')->table('tbl_blog_categories')->get();
        if(!empty($blog_categories)) {
            $blog_categories_arr = [];
            foreach($blog_categories as $blog_category) {
                $blog_categories_arr[] = [
                    'id' => $blog_category->categories_id,
                    'type' => 'blogs',
                    'parent_id' => $blog_category->parent_id,
                    'name' => $blog_category->categories_name,
                    'slug' => $blog_category->slug,
                    'sort_order' => $blog_category->sort_order,
                    'status' => $blog_category->status,
                    'hot_categories' => $blog_category->hot_categories,
                    'content' => $blog_category->description,
                    'meta_title' => $blog_category->page_title,
                    'meta_keyword' => $blog_category->page_keywords,
                    'meta_description' => $blog_category->page_description,
                    'is_deleted' => $blog_category->deleted,
                    'created_at' => ($blog_category->date_added=='0000-00-00 00:00:00')?NULL:$blog_category->date_added,
                    'updated_at' => ($blog_category->modify_date=='0000-00-00 00:00:00')?NULL:$blog_category->modify_date,
                ];
            }
            if(!empty($blog_categories_arr)) {
                DB::connection('mysql')->table('blog_categories')->insert($blog_categories_arr);
            }
        }


        $blogs = DB::connection('mysql_old')->table('tbl_blog')->get();
        // prd($blogs->toArray());
        foreach($blogs as $blog) {
            $old_blog_id =$blog->blog_id ;
            $req_data = array(

                        'id' =>$old_blog_id,
                        'type' =>'blogs',
                        'title' =>$blog->title ,
                        'slug' =>$blog->slug ,
                        'content' =>$blog->description ,
                        'brief' =>$blog->brief ,
                        'post_by' =>$blog->author ,
                        'source_title' =>$blog->source_title ,
                        'source_url' =>$blog->source_url ,
                        'add_media' =>$blog->add_media ,
                        'post_title_url' =>$blog->post_title_url ,
                        'show_comments' =>$blog->show_comments ,
                        'allow_comments' =>$blog->allow_comments ,
                        'featured' =>$blog->feature ,
                        'status' =>$blog->status ,
                        'is_deleted' =>$blog->deleted ,
                        'blog_date' =>$blog->blog_date ,
                        'image' =>$blog->images ,
                        'pdf' =>$blog->doc , // ''
                        'meta_title' =>$blog->pages_title , // ''
                        'meta_description' =>$blog->pages_description , // ''
                        'meta_keyword' =>$blog->pages_keywords , // ''
                        'posted_by' =>$blog->add_by , // ''
                        'sort_order' =>$blog->sort_order ,
                        'created_at' =>($blog->date_added=='0000-00-00 00:00:00')?NULL:$blog->date_added,
                        'updated_at' =>($blog->date_modify=='0000-00-00 00:00:00')?NULL:$blog->date_modify,
                    
                        // '' =>$blog->comments ,
                        // '' =>$blog->modify_by , // ''
                        // '' =>$blog->tags ,

                     );

            $blog_id = Blog::insertGetId($req_data);
            
            $blog_to_cats = DB::connection('mysql_old')->table('tbl_blog_to_categories')->where('blog_id',$old_blog_id)->get();

            foreach ($blog_to_cats as $key => $blog_cat) {

                $category_id = $blog_cat->categories_id;
                $cat_arr = array(
                        'blog_id' => $blog_id,
                        'category_id' => $category_id,
                     );
                DB::table('blog_to_categories')->insert($cat_arr);           
            }
        }
        echo 'import Done';
    }

   public function import_testimonials(Request $request) {
        $testimonials = DB::connection('mysql_old')->table('tbl_testimonial')->get();
        // prd($testimonials->toArray());
        foreach($testimonials as $testimonial) {

            $old_id =$testimonial->id ;
            $pkg_ids =$testimonial->pkg_id;
            $pkg_arr = explode(",",$pkg_ids);
            $pkg_json = json_encode($pkg_arr);

            $destination_ids =$testimonial->destination_id;
            $destination_arr = explode(",",$destination_ids);
            $destination_json = json_encode($destination_arr);

            $req_data = array(
                'id' =>$old_id,
                'package_id' =>$pkg_json,
                'destination_id' =>$destination_json,
                'description' =>$testimonial->contents,
                'name' =>$testimonial->name,
                'title' =>$testimonial->title,
                'slug' =>$testimonial->slug,
                // 'contents' =>$testimonial->contents,
                'position_in_company' =>$testimonial->position_in_company,
                'company_name' =>$testimonial->company_name,
                'client_link' =>$testimonial->client_link,
                'website' =>$testimonial->website,
                'email' =>$testimonial->email,
                'featured' =>$testimonial->featured,
                'image' =>$testimonial->image,
                'sort_order' =>$testimonial->sort_order,
                'status' =>$testimonial->status,
                'is_deleted' =>$testimonial->deleted,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            // prd($req_data);
            $id = Testimonial::insertGetId($req_data);

            if($testimonial->image) {
                $common_images = [
                    'tbl_id' => $old_id,
                    'category' => 'gallery', 
                    'tbl_name' => 'testimonials', 
                    'name' => $testimonial->image, 
                    'title' => $testimonial->title, 
                    'link' => '', 
                    'sort_order' => 0, 
                    'is_default' => 1, 
                    'status' => 1, 
                    'created_at' => date('Y-m-d H:i:s'), 
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                // prd($destinations_arr);
                DB::connection('mysql')->table('common_images')->insert($common_images);
            }          
        }
        echo 'import Done';
    }

    public function import_cms(Request $request) {
        $resources = DB::connection('mysql_old')->table('tbl_resource')->where('deleted',0)->get();
        if($resources) {
            $cms_pages_arr = [];
            foreach($resources as $resource) {
                $cms_pages_arr[] = [
                    'id' =>$resource->id,
                    'type' =>$resource->type,
                    'parent_id' =>$resource->parent_id,
                    'title' =>$resource->name,
                    'name' =>$resource->name,
                    'slug' =>$resource->slug,
                    'image' =>$resource->images,
                    'brief' =>$resource->brief,
                    'content' =>$resource->contents,
                    'sort_order' =>$resource->sort_order,
                    'status' =>$resource->status,
                    'template' => '', //$resource->template,                
                    'meta_title' =>$resource->meta_title,
                    'meta_description' =>$resource->meta_description,
                    'meta_keyword' =>$resource->meta_keywords,
                    'is_deleted' => $resource->deleted,
                    'created_at' =>  ($resource->date_added=='0000-00-00 00:00:00')? NULL:$resource->date_added ,
                    'updated_at' => ($resource->date_modified=='0000-00-00 00:00:00')? NULL:$resource->date_modified ,
                ];
                
            }
            if(!empty($cms_pages_arr)) {
                DB::connection('mysql')->table('cms_pages')->insert($cms_pages_arr);
            }
        }
        echo 'import CMS Done';
    }

    public function import_packages(Request $request) {
        
        $themes = DB::connection('mysql_old')->table('tbl_theme')->get();
        if(!empty($themes)) {
            $themes_arr = [];
            foreach($themes as $theme) {
                $themes_arr[] = [
                    'id' => $theme->theme_id, 
                    'parent_id' => 0, 
                    'title' => $theme->title, 
                    'slug' => $theme->slug, 
                    'brief' => $theme->brief, 
                    'description' => $theme->description, 
                    'image' => $theme->images, 
                    'icon' => $theme->icon, 
                    'page_title' => $theme->pages_title, 
                    'page_description' => $theme->pages_description, 
                    'page_keywords' => $theme->pages_keywords, 
                    'sort_order' => $theme->sort_order, 
                    'status' => $theme->status, 
                    'is_deleted' => $theme->deleted, 
                    'featured' => $theme->featured, 
                    'created_at' => ($theme->date_added=='0000-00-00 00:00:00')?NULL:$theme->date_added, 
                    'updated_at' => ($theme->date_modify=='0000-00-00 00:00:00')?NULL:$theme->date_modify, 
                ];
            }
            if(!empty($themes_arr)) {
                DB::connection('mysql')->table('themes')->insert($themes_arr);
            }
        }

        $package_inclusions = DB::connection('mysql_old')->table('tbl_inclusions')->get();
        if(!empty($package_inclusions)) {
            $package_inclusions_arr = [];
            foreach($package_inclusions as $package_inclusion) {
                $package_inclusions_arr[] = [
                    'id' => $package_inclusion->inclusions_id, 
                    'title' => $package_inclusion->inclusions, 
                    'sort_order' => 0,
                    'status' => $package_inclusion->status,
                ];
            }
            if(!empty($package_inclusions_arr)) {
                DB::connection('mysql')->table('package_inclusion_lists')->insert($package_inclusions_arr);
            }
        }

        $price_categories = DB::connection('mysql_old')->table('tbl_price_category')->get();
        if(!empty($price_categories)) {
            $price_categories_arr = [];
            foreach($price_categories as $price_category) {
                $price_categories_arr[] = [
                    'id' => $price_category->id, 
                    'name' => $price_category->name, 
                    'display_title' => $price_category->name, 
                    'status' => $price_category->status,
                    'created_at' => ($price_category->date_added=='0000-00-00 00:00:00')?NULL:$price_category->date_added,
                    'updated_at' => ($price_category->date_modified=='0000-00-00 00:00:00')?NULL:$price_category->date_modified,
                ];
            }
            if(!empty($price_categories_arr)) {
                DB::connection('mysql')->table('price_categories')->insert($price_categories_arr);
            }
        }

        $price_category_elements = DB::connection('mysql_old')->table('tbl_price_category_elements')->get();
        if(!empty($price_category_elements)) {
            $price_category_elements_arr = [];
            foreach($price_category_elements as $price_category_element) {
                $price_category_elements_arr[] = [
                    'id' => $price_category_element->id, 
                    'pc_id' => $price_category_element->pc_id, 
                    'price_label' => $price_category_element->price_label, 
                    'input_label' => $price_category_element->input_label,
                    'input_type' => $price_category_element->input_type,
                    'input_choices' => $price_category_element->input_choices,
                    'status' => $price_category_element->status,
                    'created_at' => ($price_category_element->date_added=='0000-00-00 00:00:00')?NULL:$price_category_element->date_added,
                ];
            }
            if(!empty($price_category_elements_arr)) {
                DB::connection('mysql')->table('price_category_elements')->insert($price_category_elements_arr);
            }
        }

        $package_types = DB::connection('mysql_old')->table('tbl_package_type')->get();
        if(!empty($package_types)) {
            $package_types_arr = [];
            foreach($package_types as $package_type) {
                $package_types_arr[] = [
                    'id' => $package_type->id, 
                    'package_type' => $package_type->package_type, 
                    'status' => 1, 
                    'created_at' => date('Y-m-d H:i:s'), 
                    'updated_at' => date('Y-m-d H:i:s'), 
                ];
            }
            if(!empty($package_types_arr)) {
                DB::connection('mysql')->table('package_types')->insert($package_types_arr);
            }
        }

        $package_airlines = DB::connection('mysql_old')->table('tbl_airlines')->get();
        if(!empty($package_airlines)) {
            $package_airlines_arr = [];
            foreach($package_airlines as $package_airline) {
                $package_airlines_arr[] = [
                    'id' => $package_airline->id, 
                    'airline_name' => $package_airline->airline_name, 
                    'airline_code' => $package_airline->airline_code, 
                    'image' => $package_airline->airline_img, 
                    'status' => $package_airline->status, 
                    'sort_order' => 0, 
                    'created_at' => ($package_airline->date_added=='0000-00-00 00:00:00')?NULL:$package_airline->date_added, 
                    'updated_at' => ($package_airline->date_modified=='0000-00-00 00:00:00')?NULL:$package_airline->date_modified,
                ];
            }
            if(!empty($package_airlines_arr)) {
                DB::connection('mysql')->table('package_airlines')->insert($package_airlines_arr);
            }
        }       


        $packages = DB::connection('mysql_old')->table('tbl_package')->get();
        // prd($packages->toArray());

        $DISPLAY_DURATION = '[NIGHTS] NIGHTS/ [DAYS] DAYS';
        $DISPLAY_DAY_DURATION = '[DAYS] Day';

        foreach($packages as $package) {
            // prd($package);
            $best_months = DB::connection('mysql_old')->table('tbl_best_time_to_packages')->where('package_id',$package->package_id)->get()->pluck('best_time_id')->toArray();
            $best_months = json_encode(array_map('strval', $best_months));

            $package_inclusions = DB::connection('mysql_old')->table('tbl_package_to_inclusions')->where('package_id',$package->package_id)->get()->pluck('inclusions_id')->toArray();
            $package_inclusions = json_encode(array_map('strval', $package_inclusions));

            $main_destination = DB::connection('mysql_old')->table('tbl_package_to_destination')->where('package_id',$package->package_id)->first();
            $destination_id = 0;
            if($main_destination) {
                $destination_id = ($main_destination->destination_id)?$main_destination->destination_id:NULL;
            }

            $package_duration = $package->sub_title;
            if($package->is_activity == 1) {
                $activity_duration = ($package->duration)?(int)$package->duration:0;
                $package_duration = $activity_duration.' '.(($package->duration_type==1)?'Hours':'Days');
            } else {
                $days_nights_city = unserialize($package->days_nights_city);
                if(!empty($days_nights_city)) {
                    $days = $package->days;
                    $nights = $package->nights;
                    if(!empty($nights)){
                        $package_days = $DISPLAY_DURATION;
                    }else {
                        $package_days = $DISPLAY_DAY_DURATION;
                    }
                    $package_days = str_replace("[DAYS]",$days,$package_days);
                    $package_days = str_replace("[NIGHTS]",$nights,$package_days);
                    // echo $package_days;
                    $package_duration = $package_days;
                }
            }

            $record = [
                'id' => $package->package_id, 
                'title' => $package->package_name, 
                'tour_type' => ($package->tour_type=='general')?'open':$package->tour_type, 
                'is_activity' => $package->is_activity, 
                'multiple_dates' => $package->multiple_date, 
                'price_category' => $package->price_category, 
                'subtitle' => $package->sub_title, 
                'package_duration' => $package_duration,//$package->sub_title, 
                'package_duration_days' => $package->days, 
                'activity_duration' => ($package->duration)?(int)$package->duration:0, 
                'activity_duration_type' => $package->duration_type, 
                'slug' => $package->slug, 
                'destination_id' => $destination_id, 
                'sub_destination_id' => $destination_id, 
                'related_destinations' => '', 
                'related_packages' => '', 
                'best_months' => $best_months,
                'activity_level' => 0, 
                'package_type' => $package->package_type, 
                'package_inclusions' => $package_inclusions, 
                'experts' => '', 
                'image' => $package->images, 
                'banner_image' => '', 
                'brief' => $package->brief, 
                'description' => $package->description, 
                'inclusions' => $package->inclusion, 
                'exclusions' => $package->exclusion, 
                'payment_policy' => '',//$package->payment_policy, 
                'cancellation_policy' => $package->cancellation_policy, 
                'terms_conditions' => '',//$package->terms_and_conditions, 
                'inclusions_chk' => 1,//$package->inclusions_chk, 
                'exclusions_chk' => 1,//$package->exclusions_chk, 
                'payment_policy_chk' => 1,//$package->payment_policy_chk, 
                'cancellation_policy_chk' => 1,//$package->cancellation_policy_chk, 
                'terms_conditions_chk' => 1,//$package->terms_conditions_chk, 
                'meta_title' => $package->pages_title, 
                'meta_description' => $package->pages_description, 
                'star_ratings' => '', 
                'status' => $package->status, 
                'is_deleted' => $package->deleted, 
                'featured' => $package->top_package, 
                'popular' => 0, 
                'sort_order' => $package->sort_order, 
                'video_link' => $package->video, 
                'days' => $package->days, 
                'nights' => $package->nights, 
                'days_nights_city' => $package->days_nights_city, 
                'inladakh' => $package->inladakh, 
                'search_price' => $package->search_price, 
                'tags' => $package->package_tag, 
                'created_at' => ($package->date_added=='0000-00-00 00:00:00')?NULL:$package->date_added, 
                'updated_at' => ($package->date_modify=='0000-00-00 00:00:00')?NULL:$package->date_modify,

            ];
            // prd($record);

            $package_id = DB::connection('mysql')->table('packages')->insertGetId($record);
            // prd($package_id);
            if($package_id) {
                $package_created_at = (empty($package->date_added) || $package->date_added=='0000-00-00 00:00:00')?NULL:$package->date_added;
                if($package->images) {
                    $common_images = [
                        'tbl_id' => $package->package_id,
                        'category' => 'gallery', 
                        'tbl_name' => 'packages', 
                        'name' => $package->images, 
                        'title' => $package->package_name, 
                        'link' => '', 
                        'sort_order' => 0, 
                        'is_default' => 1, 
                        'status' => 1, 
                        'created_at' => ($package->date_added=='0000-00-00 00:00:00')?NULL:$package->date_added, 
                        'updated_at' => ($package->date_modify=='0000-00-00 00:00:00')?NULL:$package->date_modify,
                    ];
                    DB::connection('mysql')->table('common_images')->insert($common_images);
                }

                $package_info = [];
                $package_info[] = [
                    'package_id' => $package_id,
                    'title' => 'Payment Terms Policy',
                    'description' => $package->child_policy??'',
                    'status' => 1,
                    'sort_order' => 1,
                    'created_at' => $package_created_at,
                    'updated_at' => $package_created_at,
                ];
                $package_info[] = [
                    'package_id' => $package_id,
                    'title' => 'Refund Policy',
                    'description' => $package->payment_policy??'',
                    'status' => 1,
                    'sort_order' => 1,
                    'created_at' => $package_created_at,
                    'updated_at' => $package_created_at,
                ];
                $package_info[] = [
                    'package_id' => $package_id,
                    'title' => 'Confirmation Policy',
                    'description' => $package->terms_and_conditions??'',
                    'status' => 1,
                    'sort_order' => 1,
                    'created_at' => $package_created_at,
                    'updated_at' => $package_created_at,
                ];

                $package_info[] = [
                    'package_id' => $package_id,
                    'title' => 'Inner Line Permit',
                    'description' => $package->inner_line_permit??'',
                    'status' => 1,
                    'sort_order' => 2,
                    'created_at' => $package_created_at,
                    'updated_at' => $package_created_at,
                ];

                $package_info[] = [
                    'package_id' => $package_id,
                    'title' => 'Supplementary Other Charges',
                    'description' => $package->supplementary_charges??'',
                    'status' => 1,
                    'sort_order' => 3,
                    'created_at' => $package_created_at,
                    'updated_at' => $package_created_at,
                ];
                // prd($package_info);
                DB::connection('mysql')->table('package_info')->insert($package_info);
                

                if(!empty($package->multiple_date)) {
                    $multiple_dates = json_decode($package->multiple_date,true);
                    if(!empty($multiple_dates)) {
                        foreach($multiple_dates as $md) {
                            // pr($md);
                            if(isset($md['from_date']) && !empty($md['from_date'])) {
                                $from_date = CustomHelper::DateFormat($md['from_date'],'Y-m-d','d/m/Y');
                            } else {
                                $from_date = NULL;
                            }
                            if(isset($md['to_date']) && !empty($md['to_date'])) {
                                $to_date = CustomHelper::DateFormat($md['to_date'],'Y-m-d','d/m/Y');
                            } else {
                                $to_date = NULL;
                            }
                            $record = [
                                'package_id' => $package_id,
                                'from_date' => $from_date,
                                'to_date' => $to_date,
                            ];
                            // prd($record);
                            if(isset($md['capacity']) && !empty($md['capacity']) && is_numeric($md['capacity'])) {
                                $capacity = $md['capacity'];
                            } else {
                                $capacity = 0;
                            }
                            
                            $package_departure_id = DB::connection('mysql')->table('package_departures')->insertGetId($record);
                            if($package_departure_id) {
                                $package_departure_capacities = [];
                                if(!empty($package_prices)) {
                                    $package_departure_capacities = [];
                                    foreach($package_prices as $price) {
                                        $package_departure_capacities[] = [
                                            'package_id' => $package_id,
                                            'package_departure_id' => $package_departure_id,
                                            'package_price_id' => $price->id,
                                            'capacity' => $capacity,
                                        ];
                                    }
                                    if(!empty($package_departure_capacities)) {
                                        DB::connection('mysql')->table('package_departure_capacities')->insert($package_departure_capacities);
                                    }
                                }
                            }

                        }
                    }
                }

                if(!empty($package->package_settings)) {
                    $package_settings_arr = json_decode($package->package_settings,true);
                    $package_settings = DB::connection('mysql')->table('package_settings')->get()->pluck('id','slug')->toArray();
                    $package_to_settings = [];
                    foreach($package_settings_arr as $pc) {
                        if(isset($package_settings[$pc['key_text']]) && $pc['val'] == 1) {
                            $package_to_settings[] = [
                                'package_id' => $package_id,
                                'setting_id' => $package_settings[$pc['key_text']],
                            ];
                        }
                    }
                    if(!empty($package_to_settings)) {
                        DB::connection('mysql')->table('package_to_settings')->insert($package_to_settings);
                    }
                }
            }            
        }

        $package_to_themes = DB::connection('mysql_old')->table('tbl_package_to_theme')->get();
        if(!empty($package_to_themes)) {
            $package_themes_arr = [];
            foreach($package_to_themes as $package_to_theme) {
                $package_themes_arr[] = [
                    'package_id' => $package_to_theme->package_id, 
                    'theme_id' => $package_to_theme->theme_id, 
                    'created_at' => NULL,
                ];
            }
            if(!empty($package_themes_arr)) {
                DB::connection('mysql')->table('package_themes')->insert($package_themes_arr);
            }
        }

        $itenaries = DB::connection('mysql_old')->table('tbl_itinerary')->get();
        if(!empty($itenaries)) {
            $itenaries_arr = [];
            foreach($itenaries as $itenary) {
                if($itenary->inclusions_str) {
                    $itenary_inclusions = explode(',', $itenary->inclusions_str);
                } else {
                    $itenary_inclusions = [];
                }
                $itenary_inclusions = json_encode(array_map('strval', $itenary_inclusions));

                $itenaries_arr[] = [
                    'id' => $itenary->itinerary_id, 
                    'package_id' => $itenary->package_id, 
                    'location_id' => $itenary->destination_id, 
                    'day' => $itenary->day_id, 
                    'day_title' => $itenary->day_title, 
                    'title' => $itenary->itinerary_title, 
                    'image' => $itenary->images, 
                    'itenary_inclusions' => $itenary_inclusions, 
                    'brief' => $itenary->brief, 
                    'description' => $itenary->description, 
                    'tags' => $itenary->include, 
                    'sort_order' => $itenary->sort_order, 
                    'status' => $itenary->status, 
                    'created_at' => ($itenary->date_added=='0000-00-00 00:00:00')?NULL:$itenary->date_added, 
                    'updated_at' => ($itenary->date_modify=='0000-00-00 00:00:00')?NULL:$itenary->date_modify,                            
                ];
            }
            if(!empty($itenaries_arr)) {
                DB::connection('mysql')->table('itenaries')->insert($itenaries_arr);
            }
        }

        $flights = DB::connection('mysql_old')->table('tbl_flight')->get();
        if(!empty($flights)) {
            $flights_arr = [];
            foreach($flights as $flight) {
                $flights_arr[] = [
                    'id' => $flight->flight_id, 
                    'package_id' => $flight->package_id, 
                    'flight_number' => $flight->flight_number, 
                    'flight_time' => $flight->flight_time, 
                    'airline_name' => $flight->airline_name, 
                    'flight_departure' => $flight->flight_departure, 
                    'flight_arrival' => $flight->flight_arrival, 
                    'flight_comment' => $flight->flight_comment,  
                    'status' => 1, 
                    'created_at' => ($flight->date_added=='0000-00-00 00:00:00')?NULL:$flight->date_added, 
                    'updated_at' => ($flight->date_modify=='0000-00-00 00:00:00')?NULL:$flight->date_modify,                            
                ];
            }
            if(!empty($flights_arr)) {
                DB::connection('mysql')->table('package_flights')->insert($flights_arr);
            }
        }

        $package_prices = DB::connection('mysql_old')->table('tbl_package_price')->get();
        if(!empty($package_prices)) {
            $package_price_info = [];
            foreach($package_prices as $price) {
                $show_price = [];
                if($price->show_price) {
                    $show_price_arr = json_decode($price->show_price,true);
                    if(!empty($show_price_arr)) {
                        foreach($show_price_arr as $spa_key => $spa_val) {
                            $key = str_replace('_default', '', $spa_key);
                            $show_price[$key] = [
                                'default' => $spa_val['price_id'],
                            ];
                        }
                    }
                }

                $title = 'Standard';
                if($price->hotel_category) {
                    $title = $price->hotel_category??'Standard';
                }
                $package_price_info[] = [
                    'id' => $price->id, 
                    'package_id' => $price->package_id, 
                    'title' => $title, 
                    'service_level' => 1, 
                    'discount_type' => 1, //(1 => Flat, 2 => Percent) 
                    'discount' => 0, 
                    'description' => $price->hotel_note, 
                    'booking_price' => $price->booking_price, 
                    'sub_total_amount' => $price->booking_price, 
                    'final_amount' => $price->booking_price, 
                    'category_choices_record' => $price->price_category_data, 
                    'show_choices_record' => json_encode($show_price),//$price->show_price, 
                    'is_partial_amount' => $price->is_partial_show, 
                    'is_book_without_payment' => $price->is_book_without_payment, 
                    'price_validity' => $price->price_validity, 
                    'status' => $price->status, 
                    'sort_order' => $price->sort_order, 
                    'is_default' => $price->is_default, 
                    'created_at' => ($price->date_added=='0000-00-00 00:00:00')?NULL:$price->date_added, 
                    'updated_at' => ($price->date_modified=='0000-00-00 00:00:00')?NULL:$price->date_modified,
                ];
            }
            // prd($package_price_info);
            if(!empty($package_price_info)) {
                DB::connection('mysql')->table('package_price_info')->insert($package_price_info);
            }
        }

        $package_price_destination_hotels = DB::connection('mysql_old')->table('tbl_package_price_destination_hotels')->get();
        if(!empty($package_price_destination_hotels)) {
            $package_accommodations = [];
            foreach($package_price_destination_hotels as $ppds) {
                $package_accommodations[] = [
                    'package_id' => $ppds->package_id, 
                    'package_price_id' => $ppds->package_price_id, 
                    'itenary_id' => $ppds->itinerary_id, 
                    'accommodation_id' => $ppds->hotel_id, 
                    'accommodation_type_id' => NULL, 
                    'service_level' => NULL, 
                    'created_at' => NULL, 
                    'is_default_without_price' => NULL,
                ];
            }
            // prd($package_accommodations);
            if(!empty($package_accommodations)) {
                DB::connection('mysql')->table('package_accommodations')->insert($package_accommodations);
            }
        }

        $packages = Package::where('status',1)->get();
        if($packages) {
            foreach($packages as $package) {
                $packagePrices = $package->packagePrices??[];
                if($packagePrices) {
                    $is_default = 0;
                    foreach($packagePrices as $price) {
                        if($price->is_default == 1) {
                            $is_default = $price->id;
                        }
                    }
                    if($is_default == 0) {
                        $price_id = $packagePrices[0]->id??0;
                        if($price_id) {
                            PackagePriceInfo::where('id',$price_id)->update(['is_default'=>1]);
                        }
                    }
                }
                CustomHelper::updatePackagePublishPrice($package->id);
            }
        }

        echo 'import Packages Done';
    }
    

    public function import_teams(Request $request) {
        $teams = DB::connection('mysql_old')->table('tbl_team')->where('deleted',0)->get();
        if($teams) {
            $team_arr = [];
            foreach($teams as $team) {
                $team_arr[] = [
                    'id' =>$team->id,
                    // 'gender' =>$team->gender,
                    'title' =>$team->title,
                    'sub_title' =>$team->sub_title,
                    'slug' =>$team->slug,
                    'designation' =>$team->designation,
                    // 'category' =>$team->category,
                    // 'trip_theme' =>$team->trip_theme,
                    // 'brief_profile' =>$team->brief_profile,
                    'detail_profile' =>$team->contents,
                    // 'email' =>$team->email,
                    // 'alt_email' => $team->alt_email,                
                    // 'mobile_no' =>$team->mobile_no,
                    // 'facebook_link' =>$team->facebook_link,
                    // 'twitter_link' =>$team->twitter_link,
                    'image' =>$team->image,
                    'sort_order' =>$team->sort_order,
                    'status' =>$team->status,
                    'featured' => $team->featured,
                    'meta_title' => $team->pages_title,
                    'meta_description' => $team->pages_description,
                    'meta_keywords' => $team->pages_keywords,
                    'is_deleted' => $team->deleted,
                    'created_at' =>  ($team->date_added=='0000-00-00 00:00:00')? NULL:$team->date_added ,
                    'updated_at' => ($team->date_modify=='0000-00-00 00:00:00')? NULL:$team->date_modify ,
                ];                
            }
            if(!empty($team_arr)) {
                DB::connection('mysql')->table('team_management')->insert($team_arr);
            }
        }
        echo 'import Teams Done';
    }

    public function import_faqs(Request $request) {
        $faqs = DB::connection('mysql_old')->table('tbl_faq')->get();
        if($faqs) {
            $faq_arr = [];
            foreach($faqs as $faq) {
                $tbl_id = '';
                $tbl_name = '';
                if($faq->show_in_home == 1) {
                    $tbl_id = '2';
                    $tbl_name = 'seo_meta_tags';
                }
                if($faq->show_in_packages == 1) {
                    $tbl_id = '3';
                    $tbl_name = 'seo_meta_tags';
                }
                if($faq->show_in_package_detail) {
                    $tbl_id = $faq->show_in_package_detail;
                    $tbl_name = 'packages';
                }
                if($faq->show_in_destination_detail) {
                    $tbl_id = $faq->show_in_destination_detail;
                    $tbl_name = 'destinations';
                }
                $faq_arr[] = [
                    'id' => $faq->faq_id,
                    'tbl_id' => $tbl_id,
                    'tbl_category' => 'faqs',
                    'tbl_name' => $tbl_name,
                    // 'destination_id' =>$faq->destination_id,
                    // 'sub_destination_id' =>$faq->sub_destination_id,
                    'category' => $faq->show_in_category,
                    'question' => $faq->title,
                    'answer' => $faq->description,
                    'sort_order' => $faq->sort_order,
                    'status' => $faq->status,
                    'created_at' => ($faq->created=='0000-00-00 00:00:00')? NULL:$faq->created ,
                    'updated_at' => ($faq->modified=='0000-00-00 00:00:00')? NULL:$faq->modified ,
                ];                
            }
            if(!empty($faq_arr)) {
                DB::connection('mysql')->table('faqs')->insert($faq_arr);
            }
        }
        echo 'import Faqs Done';
    }

    public function import_gallery(Request $request) {
        $galleries = DB::connection('mysql_old')->table('tbl_gallery')->get();
        if($galleries) {
            $common_images = [];
            foreach($galleries as $gallery) {
                $common_images[] = [
                    'tbl_id' => $gallery->table_id,
                    'category' => 'gallery', 
                    'tbl_name' => $gallery->table_name, 
                    'name' => $gallery->gallery_image, 
                    'title' => $gallery->title, 
                    'link' => '', 
                    'sort_order' => $gallery->sort_order, 
                    'is_default' => 0, 
                    'status' => $gallery->status, 
                    'created_at' => ($gallery->date_added=='0000-00-00 00:00:00')?NULL:$gallery->date_added, 
                    'updated_at' => ($gallery->date_modify=='0000-00-00 00:00:00')?NULL:$gallery->date_modify,
                ];
                // prd($destinations_arr);
            }
            if(!empty($common_images)) {
                DB::connection('mysql')->table('common_images')->insert($common_images);
            }
        }
        echo 'import Gallery Done';
    }

    public function import_banner(Request $request) {
        $banner_images = DB::connection('mysql_old')->table('tbl_banner_images')->where('controller_name','')->where('table_id',0)->get();
        if($banner_images) {
            $banner_images_arr = [];
            foreach($banner_images as $banner_image) {
                $banner_images_arr[] = [
                    'banner_id' => 1, 
                    'image_name' => $banner_image->image, 
                    'title' => $banner_image->title, 
                    'sub_title' => $banner_image->sub_title, 
                    'link_text_1' => $banner_image->title, 
                    'link_1' => $banner_image->link, 
                    'link_text_2' => '', 
                    'link_2' => '', 
                    'sort_order' => $banner_image->sort_order, 
                    'status' => $banner_image->status, 
                    'created_at' => ($banner_image->date_added=='0000-00-00 00:00:00')?NULL:$banner_image->date_added, 
                    'updated_at' => ($banner_image->date_modify=='0000-00-00 00:00:00')?NULL:$banner_image->date_modify,
                ];
                // prd($destinations_arr);
            }
            if(!empty($common_images)) {
                DB::connection('mysql')->table('banner_images')->insert($banner_images_arr);
            }
        }

        $banners = DB::connection('mysql_old')->table('tbl_banner_images')->where('controller_name','!=','')->where('table_id','!=',0)->get();
        if($banners) {
            $common_images = [];
            foreach($banners as $banner) {
                $common_images[] = [
                    'tbl_id' => $banner->table_id,
                    'category' => 'banner', 
                    'tbl_name' => $banner->table_name, 
                    'name' => $banner->image, 
                    'title' => $banner->title, 
                    'link' => $banner->link, 
                    'sort_order' => $banner->sort_order, 
                    'is_default' => 0, 
                    'status' => $banner->status, 
                    'created_at' => ($banner->date_added=='0000-00-00 00:00:00')?NULL:$banner->date_added, 
                    'updated_at' => ($banner->date_modify=='0000-00-00 00:00:00')?NULL:$banner->date_modify,
                ];
                // prd($destinations_arr);
            }
            if(!empty($common_images)) {
                DB::connection('mysql')->table('common_images')->insert($common_images);
            }
        }
        echo 'import Banners Done';
    }

    public function copy_gallery(Request $request) {
        $storage = Storage::disk('public');

        $common_images = CommonImage::where('is_sync',0)->limit(100)->get();
        $total_records = $common_images->count();
        $counter = 0;
        // prd($common_images->toArray());
        if($common_images) {
            foreach($common_images as $image) {
                if($image->name && $image->tbl_name) {
                    $image_name = $image->name;
                    $table_name = $image->tbl_name;
                    $old_table_name = '';
                    switch ($table_name) {
                        case 'packages':
                                $old_table_name = 'package';
                            break;
                        case 'destinations':
                                $old_table_name = 'destinations';
                            break;
                        case 'accommodations':
                                $old_table_name = 'hotels';
                            break;
                        case 'accommodation_rooms':
                                $old_table_name = 'hotels';
                            break;
                        case 'testimonials':
                                $old_table_name = 'testimonial';
                            break;
                        case 'cms_pages':
                                $old_table_name = 'gallery';
                            break;
                        default:
                            # code...
                            break;
                    }

                    if($old_table_name) {
                        $isSaved = 0;
                        if($storage->exists($table_name.'/'.$image_name)) {
                            //Do nothing
                        } else {
                            $path = "https://www.andamanisland.in/uploads/andamanislands/".$old_table_name."/main/".$image_name;
                            try {
                                $isSaved = Storage::disk('public_uploads')->put('storage/'.$table_name.'/'.$image_name, file_get_contents($path));
                            } catch (\Exception $e) {
                                $path = "https://www.andamanisland.in/uploads/andamanislands/".$old_table_name."/thumb/".$image_name;
                                try {
                                    $isSaved = Storage::disk('public_uploads')->put('storage/'.$table_name.'/'.$image_name, file_get_contents($path));
                                } catch (\Exception $e) {
                                    $path = "https://www.andamanisland.in/uploads/andamanislands/".$old_table_name."/".$image_name;
                                    try {
                                        $isSaved = Storage::disk('public_uploads')->put('storage/'.$table_name.'/'.$image_name, file_get_contents($path));
                                    } catch (\Exception $e) {
                                        $path = "https://www.andamanisland.in/uploads/andamanislands/gallery/main/".$image_name;
                                        try {
                                            $isSaved = Storage::disk('public_uploads')->put('storage/'.$table_name.'/'.$image_name, file_get_contents($path));
                                        } catch (\Exception $e) {

                                        }
                                    }                                    
                                }
                            }
                        }

                        if($storage->exists($table_name.'/thumb/'.$image_name)) {
                            // Do nothing
                        } else {
                            if($storage->exists($table_name.'/'.$image_name)) {
                                $storage->copy($table_name.'/'.$image_name,$table_name.'/thumb/'.$image_name);
                            }
                        }
                        if($isSaved) {
                            $counter++;
                        }
                    }
                }
                $image->is_sync = 1;
                $image->save();
            }
        }
        // prd($total_records);

        if($total_records == 0) {
            $blogs = Blog::where('is_sync',0)->limit(100)->get();
            $total_records = $blogs->count();
            $counter = 0;
            if($blogs) {
                foreach($blogs as $blog) {
                    if($blog->image) {
                        $image_name = $blog->image;
                        $table_name = 'blogs';
                        $old_table_name = 'blog';
                        if($old_table_name) {
                            $isSaved = 0;
                            if($storage->exists($table_name.'/'.$image_name)) {
                                //Do nothing
                            } else {
                                $path = "https://www.andamanisland.in/uploads/andamanislands/".$old_table_name."/main/".$image_name;
                                try {
                                    $isSaved = Storage::disk('public_uploads')->put('storage/'.$table_name.'/'.$image_name, file_get_contents($path));
                                } catch (\Exception $e) {
                                    $path = "https://www.andamanisland.in/uploads/andamanislands/".$old_table_name."/".$image_name;
                                    try {
                                        $isSaved = Storage::disk('public_uploads')->put('storage/'.$table_name.'/'.$image_name, file_get_contents($path));
                                    } catch (\Exception $e) {
                                        $path = "https://www.andamanisland.in/uploads/andamanislands/gallery/main/".$image_name;
                                        try {
                                            $isSaved = Storage::disk('public_uploads')->put('storage/'.$table_name.'/'.$image_name, file_get_contents($path));
                                        } catch (\Exception $e) {

                                        }
                                    }
                                }
                            }

                            if($storage->exists($table_name.'/thumb/'.$image_name)) {
                                // Do nothing
                            } else {
                                if($storage->exists($table_name.'/'.$image_name)) {
                                    $storage->copy($table_name.'/'.$image_name,$table_name.'/thumb/'.$image_name);
                                }
                            }
                            if($isSaved) {
                                $counter++;
                            }
                        }
                    }
                    $blog->is_sync = 1;
                    $blog->save();
                }
            }
        }

        if($total_records == 0) {
            $cms_pages = CmsPages::where('is_sync',0)->limit(100)->get();
            $total_records = $cms_pages->count();
            $counter = 0;
            if($cms_pages) {
                foreach($cms_pages as $cms) {
                    if($cms->image) {
                        $image_name = $cms->image;
                        $table_name = 'cms_pages';
                        $old_table_name = 'gallery';
                        if($old_table_name) {
                            $isSaved = 0;
                            if($storage->exists($table_name.'/'.$image_name)) {
                                //Do nothing
                            } else {
                                $path = "https://www.andamanisland.in/uploads/andamanislands/".$old_table_name."/main/".$image_name;
                                try {
                                    $isSaved = Storage::disk('public_uploads')->put('storage/'.$table_name.'/'.$image_name, file_get_contents($path));
                                } catch (\Exception $e) {
                                    $path = "https://www.andamanisland.in/uploads/andamanislands/".$old_table_name."/".$image_name;
                                    try {
                                        $isSaved = Storage::disk('public_uploads')->put('storage/'.$table_name.'/'.$image_name, file_get_contents($path));
                                    } catch (\Exception $e) {
                                        $path = "https://www.andamanisland.in/uploads/andamanislands/gallery/main/".$image_name;
                                        try {
                                            $isSaved = Storage::disk('public_uploads')->put('storage/'.$table_name.'/'.$image_name, file_get_contents($path));
                                        } catch (\Exception $e) {

                                        }
                                    }
                                }
                            }

                            if($storage->exists($table_name.'/thumb/'.$image_name)) {
                                // Do nothing
                            } else {
                                if($storage->exists($table_name.'/'.$image_name)) {
                                    $storage->copy($table_name.'/'.$image_name,$table_name.'/thumb/'.$image_name);
                                }
                            }
                            if($isSaved) {
                                $counter++;
                            }
                        }
                    }
                    $cms->is_sync = 1;
                    $cms->save();
                }
            }
        }

        /*$common_images = DB::connection('mysql')->table('common_images')->get();
        if($common_images) {
            $path = 'gallery';
            foreach($common_images as $image) {
                if($image->name && $image->tbl_name) {
                    $image_name = $image->name;
                    $table_name = $image->tbl_name;                    
                    if($storage->exists($path.'/main/'.$image_name)) {
                        $storage->copy($path.'/main/'.$image_name,$table_name.'/'.$image_name);
                        $storage->copy($path.'/main/'.$image_name,$table_name.'/thumb/'.$image_name);
                    }                    
                }
            }
        }

        $resources = DB::connection('mysql_old')->table('tbl_resource')->get();
        if(!empty($resources)) {
            $path = 'gallery';
            $table_name = 'cms_pages';
            foreach($resources as $resource) {
                $image_name = $resource->images??'';
                if($image_name) {
                    if(!empty($image_name) && $storage->exists($path.'/main/'.$image_name)) {
                        $storage->copy($path.'/main/'.$image_name,$table_name.'/'.$image_name);
                        $storage->copy($path.'/main/'.$image_name,$table_name.'/thumb/'.$image_name);
                    }
                }
            }
        }

        $highlights = DB::connection('mysql_old')->table('tbl_highlight')->get();
        if(!empty($highlights)) {
            $path = 'highlight';
            $table_name = 'destinations';
            foreach($highlights as $highlight) {
                $image_name = $highlight->images??'';
                if($image_name) {
                    if(!empty($image_name) && $storage->exists($path.'/main/'.$image_name)) {
                        $storage->copy($path.'/main/'.$image_name,$table_name.'/'.$image_name);
                        $storage->copy($path.'/main/'.$image_name,$table_name.'/thumb/'.$image_name);
                    }
                }
            }
        }*/
        echo 'import Done of '.$counter.' from '.$total_records;
    }



    public function copy_custom_gallery(Request $request) {
        $old_table = $request->old_table;
        $old_path = $request->old_path;
        $main = $request->main??'';
        if($main){
            $old_path = $old_path.'/'.$main;
        }
        $new_path = $request->new_path;
        // prd($old_table.'=='.$old_path.'=='.$new_path);
        if($old_table && $old_path && $new_path) {
            $storage = Storage::disk('public');
            $results = DB::connection('mysql_old')->table($old_table)->get();
            if(!empty($results)) {
                foreach($results as $row) {
                    $image_name = $row->images??'';
                    if(!empty($image_name)) {
                        pr($old_path.'/'.$image_name);
                        pr($new_path.'/'.$image_name);
                        pr($new_path.'/thumb/'.$image_name);
                        $storage->copy($old_path.'/'.$image_name,$new_path.'/'.$image_name);
                        $storage->copy($old_path.'/'.$image_name,$new_path.'/thumb/'.$image_name);
                    }
                }
            }
        }
        echo 'copy_custom_gallery Done';
    }


    public function import_booking_enquiry(Request $request) {

        $query = DB::connection('mysql_old')->table('tbl_booking_enquiry')->orderby('id','desc');
        // prd($booking_enquiries->toArray());

        $query->chunk(1000, function($booking_enquiries) {


        foreach($booking_enquiries as $booking_enquiry) {

            $old_id =$booking_enquiry->id ;
            $enquiry_no_id = CustomHelper::genrateEnquiryNoId();
        
            $req_data = array(

                        'id' =>$old_id,
                        'enquiry_no_id' =>$enquiry_no_id,
                        'form_id' => null,//$booking_enquiry->forms_id,
                        'form_data' =>$booking_enquiry->data,
                        'accommodation' =>$booking_enquiry->hotel_id,
                        'package_id' =>$booking_enquiry->package_id,
                        'package_name' =>$booking_enquiry->package_name,
                        'name' =>$booking_enquiry->contact_name,
                        'email' =>$booking_enquiry->email,
                        'phone' =>$booking_enquiry->phone,
                        'is_deleted' => $booking_enquiry->deleted,
                        'created_at' =>  ($booking_enquiry->date_added == '0000-00-00 00:00:00')? NULL:$booking_enquiry->date_added ,
                        'updated_at' => ($booking_enquiry->date_modify == '0000-00-00 00:00:00')? NULL:$booking_enquiry->date_modify ,

                        // '' =>$booking_enquiry->hotel_name,
                        // '' =>$booking_enquiry->room_id,
                        // '' =>$booking_enquiry->rooms,

                        // '' =>$booking_enquiry->type_id,
                        // '' =>$booking_enquiry->type_name,

                        // '' =>$booking_enquiry->date_from,
                        // '' =>$booking_enquiry->date_to,
                        // '' =>$booking_enquiry->adult,
                        // '' =>$booking_enquiry->children,
                        // '' =>$booking_enquiry->infant,
                        // '' =>$booking_enquiry->extra_bed,
                        // '' =>$booking_enquiry->exclude_child_with_extra_bed,
                        // '' =>$booking_enquiry->price_category_data,
                        // '' =>$booking_enquiry->address,
                        // '' =>$booking_enquiry->city,
                        // '' =>$booking_enquiry->state,
                        // '' =>$booking_enquiry->zip,
                        // '' =>$booking_enquiry->country,
                        // '' =>$booking_enquiry->currency,
                        // '' =>$booking_enquiry->cost,
                        // '' =>$booking_enquiry->bookingcost,
                        // '' =>$booking_enquiry->partial_amount,
                        // '' =>$booking_enquiry->pay_type,
                        // '' =>$booking_enquiry->payment_mode,
                        // '' =>$booking_enquiry->comment,
                        // '' =>$booking_enquiry->stat_pay,
                        // '' =>$booking_enquiry->booking_enquiry,
                        // '' =>$booking_enquiry->amount,
                        // '' =>$booking_enquiry->transaction_id,
                        // '' =>$booking_enquiry->tpsl_response,
                    );

          $id = Enquiry::insertGetId($req_data);

        }
     });
    echo 'import Done';

    }

    public function flight_booking(Request $request) {
        $data = [];
        $order_id = $request->order_id;
        $order = Order::find($order_id);
        if($order) {
            // prd($order);
            $resp = Order::bookFlight($order_id,'confirm-book');
            // prd($resp);
            $message = json_encode($resp);
            // prd($message);
            CustomHelper::captureSentryMessage($message);
        }        
    }


    public function flight_booking_email(Request $request) {
        $data = [];
        $order_id = $request->order_id;
        $order = Order::find($order_id);
        if($order) {
            $data['order'] = $order;
            if($order->booking_details) {
                $data['booking_details'] = json_decode($order->booking_details, true);
                // prd($data['booking_details']);
            }
            return view("emails._flight_booking_email", $data);
        }
    }

    public function flight_booking_pdf(Request $request) {
        $data = [];
        $order_id = $request->order_id;
        $order = Order::find($order_id);
        if($order) {
            $storage = Storage::disk('public');
            $data['order'] = $order;
            if($order->booking_details) {
                $data['booking_details'] = json_decode($order->booking_details, true);
                // prd($data['booking_details']);
            }
            // $pdf = view("emails._flight_booking_pdf", $data)->render();
            // prd($pdf);

            $pdf = PDF::loadView("emails._flight_booking_pdf", $data);
            // return $pdf->download($order->order_no.'.pdf');

            $path = 'temp/';
            $pdf_name = $order->order_no.'.pdf';
            if (!File::exists(public_path("storage/" . $path))) {
                File::makeDirectory(public_path("storage/" . $path), $mode = 0777, true, true);
            }
            $pdf->save(public_path("storage/" . $path).$pdf_name);
            $attachments = public_path("storage/".$path).$pdf_name;


            $params = [];
            $params['to'] = 'braham@indiainternets.com';
            // $params['reply_to'] = $email;
            // $params['cc'] = $cc_email;
            // $params['bcc'] = $bcc_email;
            $params['subject'] = 'test attachments';
            $params['email_content'] = 'test attachments';
            if(isset($attachments)) {
                $params['attachments'] = $attachments;
            }
            $isSent = CustomHelper::sendCommonMail($params);
            prd($isSent);
        }
    }

    public function package_accommodation(Request $request) {
   
        //==========================
            $package_ids = PackageAccommodation::select('package_id')->groupBy('package_id')->get();
            foreach ($package_ids as $key => $package_row) {
                
                $package_id = $package_row['package_id'] ?? '';
                $packageAccommodations = PackageAccommodation::where('package_id',$package_id)->groupBy('package_price_id')->get();
                foreach ($packageAccommodations as $key => $packageAccommodation) {

                  $package_price_id = $packageAccommodation->package_price_id;
                  $packageAccommodation_itinerary=PackageAccommodation::where('package_id',$package_id)->where('package_price_id',$package_price_id)->groupBy('accommodation_id')->get();

                  //=======
                  
                  foreach ($packageAccommodation_itinerary as $key => $package_itinerary) {




                  $itenary = [];
                  $accommodation = [];
                  $package_accommodation_data = [];
                    $accommodation_id =  $package_itinerary->accommodation_id ;
                    if($accommodation_id > 0 ){

                        // pr($accommodation_id);
                        $packageAccommodation_iti =PackageAccommodation::where('package_id',$package_id)->where('package_price_id',$package_price_id)->where('accommodation_id',$accommodation_id)->get();

                        foreach ($packageAccommodation_iti as $key => $package_iti) {

                            $itenary[] = $package_iti->itenary_id;
                            if($accommodation_id && !in_array($accommodation_id,$accommodation)){
                                $accommodation[] = $package_iti->accommodation_id;
                            }
                        }

                            $package_accommodation_data = array(
                                'package_id'=> $package_id,
                                'package_price_id'=> $package_price_id,
                                'itenary_data'=> json_encode(array_map('strval', $itenary)),
                                'accommodation_data'=> json_encode(array_map('strval', $accommodation)),
                            );

                            // pr($package_accommodation_data);
                    }

                $delete_package_accommodation = PackageAccommodation::where('package_id',$package_id)->where('package_price_id',$package_price_id)->where('accommodation_id',$accommodation_id)->delete();

                $package_accommodation = PackageAccommodation::insert($package_accommodation_data);
               
                }
                
            }


            }

        //===============

            echo 'Update';
            exit;

    }


    public function syncAccommodationPublishPrice(Request $request) {
        $accommodations = Accommodation::with('AccommodationRooms','AccommodationRooms.planData')->where('status',1)->get();
        $update = $request->update??0;
        if($accommodations && $update == 1) {
            // prd($accommodations);
            foreach($accommodations as $accommodation) {
                // CustomHelper::updateAccommodationPublishPrice($accommodation->id);
                // prd($accommodation->toArray());
                $accommodation_rooms = $accommodation->AccommodationRooms??[];//->first();
                $acc_search_price = 0;
                $acc_publish_price = 0;
                $acc0_search_price = 0;
                $acc0_publish_price = 0;
                if($accommodation_rooms && $accommodation_rooms->count() > 0) {
                    foreach($accommodation_rooms as $room_key => $accommodation_room) {
                        $search_price = 0;
                        $publish_price = 0;
                        $planData = $accommodation_room->planData??[];
                        if($planData && $planData->count() > 0) {
                            $publish_price_arr = [];
                            foreach($planData as $plan) {
                                $plan_update = [
                                    'price' => $accommodation_room->base_price,
                                    'single_price' => $accommodation_room->base_price,
                                    'extra_adult' => $accommodation_room->price_extra_adult,
                                    'extra_child_1' => $accommodation_room->price_extra_child_1,
                                    'extra_child_2' => $accommodation_room->price_extra_child_2,
                                ];
                                AccommodationPlan::where('id',$plan->id)->update($plan_update);
                                if($accommodation_room->base_price) {
                                    $publish_price_arr[] = $accommodation_room->base_price;
                                }
                            }
                            if(!empty($publish_price_arr)) {
                                $publish_price = min($publish_price_arr);
                                if($publish_price) {
                                    $module_name = 'hotel_listing';
                                    $discount_category_id = '-1';
                                    if($accommodation->discount_category_id) {
                                        $discount_category_id = $accommodation->discount_category_id;
                                    }
                                    $agent_group_id = '-1';
                                    $module_record_id = $accommodation->id;
                                    $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$publish_price,$agent_group_id,$module_record_id);
                                    if($publish_price > $discount_amount) {
                                        $search_price = $publish_price - $discount_amount;
                                    }
                                }
                            }
                        }
                        if($room_key == 0) {
                            $acc0_search_price = $search_price;
                            $acc0_publish_price = $publish_price;
                        } else if($accommodation_room->is_default == 1) {
                            $acc_search_price = $search_price;
                            $acc_publish_price = $publish_price;
                        }
                    }
                }
                // prd($acc_search_price.'=='.$acc_publish_price);
                $accommodation->search_price = (!empty($acc_search_price))?$acc_search_price:$acc0_search_price;
                $accommodation->publish_price = (!empty($acc_publish_price))?$acc_publish_price:$acc0_publish_price;
                $accommodation->save();
            }
        }
        prd('Done');
    }

    public function syncPackageActivityTheme(Request $request) {
        $themeCategories = ThemeCategories::whereNull('slug')->orWhere('slug','')->get();
        if($themeCategories) {
            foreach($themeCategories as $themeCategory) {
                $themeCategory->slug = $themeCategory->title;
                $themeCategory->save();
            }
        }
        pr('Slug Update Done');

        $themeCategories = ThemeCategories::whereNull('identifier')->orWhere('identifier','')->get();
        if($themeCategories) {
            foreach($themeCategories as $themeCategory) {
                $themeCategory->identifier = 'package';
                $themeCategory->save();
            }
        }
        pr('Identifier Update Done');

        $themeCategories = ThemeCategories::where('identifier','package')->get();
        if($themeCategories) {
            foreach($themeCategories as $themeCategory) {
                $activityCategory = ThemeCategories::where('slug',$themeCategory->slug)->where('identifier','activity')->first();
                if($activityCategory) {
                    //Nothing to do
                } else {
                    $record = $themeCategory->toArray();
                    $record['id'] = NULL;
                    $record['identifier'] = 'activity';
                    $isSaved = ThemeCategories::create($record);
                    if($isSaved) {
                        pr('ThemeCategories '.$record['title'].' Created');
                    } else {
                        pr('ThemeCategories '.$record['title'].' Not Created');
                    }
                }
            }
        }
        pr('Activity Category Update Done');


        $package_to_themes = PackageToTheme::with(['ThemeCategory','Package'])->whereHas('Package',function($q1) {
            $q1->where('is_activity',1);
        })->get();
        // prd($package_to_themes->toArray());
        if($package_to_themes) {
            foreach($package_to_themes as $package_to_theme) {
                $slug = $package_to_theme->ThemeCategory->slug??'';
                // prd($slug);
                if($slug) {
                    $newTheme = ThemeCategories::where('slug',$slug)->where('identifier','activity')->first();
                    if($newTheme) {                    
                        $isSaved = PackageToTheme::where('package_id',$package_to_theme->package_id)->where('theme_id',$package_to_theme->theme_id)->update(['theme_id'=>$newTheme->id]);
                        if($isSaved) {
                            pr('Activity Theme for activity '.$package_to_theme->package_id.' Updated');
                        } else {
                            pr('Activity Theme for activity '.$package_to_theme->package_id.' Already Updated');
                        }
                    }                    
                }
            }
        }
    }


    public function syncPackageActivityInclusion(Request $request) {
        $packageInclusions = PackageInclusion::whereNull('slug')->orWhere('slug','')->get();
        if($packageInclusions) {
            foreach($packageInclusions as $packageInclusion) {
                $packageInclusion->slug = $packageInclusion->title;
                $packageInclusion->save();
            }
        }
        pr('Slug Update Done');

        $packageInclusions = PackageInclusion::whereNull('identifier')->orWhere('identifier','')->get();
        if($packageInclusions) {
            foreach($packageInclusions as $packageInclusion) {
                $packageInclusion->identifier = 'package';
                $packageInclusion->save();
            }
        }
        pr('Identifier Update Done');

        $packageInclusions = PackageInclusion::where('identifier','package')->get();
        if($packageInclusions) {
            foreach($packageInclusions as $packageInclusion) {
                $activityInclusion = PackageInclusion::where('slug',$packageInclusion->slug)->where('identifier','activity')->first();
                if($activityInclusion) {
                    //Nothing to do
                } else {
                    $record = $packageInclusion->toArray();
                    $record['id'] = NULL;
                    $record['identifier'] = 'activity';
                    $isSaved = PackageInclusion::create($record);
                    if($isSaved) {
                        pr('Activity Inclusion '.$record['title'].' Created');
                    } else {
                        pr('Activity Inclusion '.$record['title'].' Not Created');
                    }
                }
            }
        }
        pr('Activity Inclusion Update Done');

        $activities = Package::where('is_activity',1)->get();
        // prd($activities->toArray());
        if($activities) {
            foreach($activities as $activity) {
                $activity_inclusions_ids = [];
                if($activity->package_inclusions) {
                    $package_inclusions_ids = json_decode($activity->package_inclusions);
                    // prd($package_inclusions);
                    if(!empty($package_inclusions_ids)) {
                        $package_inclusions = PackageInclusion::whereIn('id',$package_inclusions_ids)->get();
                        if($package_inclusions) {
                            foreach($package_inclusions as $package_inclusion) {
                                $activity_inclusion = PackageInclusion::where('slug',$package_inclusion->slug)->where('identifier','activity')->first();
                                if($activity_inclusion) {
                                    $activity_inclusions_ids[] = $activity_inclusion->id;
                                }
                            }
                        }
                    }
                }
                if(!empty($activity_inclusions_ids)) {
                    $activity->package_inclusions = json_encode($activity_inclusions_ids);
                    $isSaved = $activity->save();
                    if($isSaved) {
                        pr('Activity Inclusion for activity '.$activity->id.' Updated');
                    }
                }
            }
        }
    }

    public function syncPackageActivityFlag(Request $request) {
        $flags = Flag::whereNull('slug')->orWhere('slug','')->get();
        if($flags) {
            foreach($flags as $flag) {
                $flag->slug = $flag->name;
                $flag->save();
            }
        }
        pr('Slug Update Done');

        $flags = Flag::whereNull('identifier')->orWhere('identifier','')->get();
        if($flags) {
            foreach($flags as $flag) {
                $flag->identifier = 'package';
                $flag->save();
            }
        }
        pr('Identifier Update Done');

        $flags = Flag::where('identifier','package')->get();
        if($flags) {
            foreach($flags as $flag) {
                $activityFlag = Flag::where('slug',$flag->slug)->where('identifier','activity')->first();
                if($activityFlag) {
                    //Nothing to do
                } else {
                    $record = $flag->toArray();
                    $record['id'] = NULL;
                    $record['identifier'] = 'activity';
                    $isSaved = Flag::create($record);
                    if($isSaved) {
                        pr('Activity Flag '.$record['title'].' Created');
                    } else {
                        pr('Activity Flag '.$record['title'].' Not Created');
                    }
                }
            }
        }
        pr('Activity Flag Update Done');


        $package_flags = PackageFlags::with(['Flag','Package'])->whereHas('Package',function($q1) {
            $q1->where('is_activity',1);
        })->get();
        // prd($package_flags->toArray());
        if($package_flags) {
            foreach($package_flags as $package_flag) {
                $slug = $package_flag->Flag->slug??'';
                // prd($slug);
                if($slug) {
                    $newFlag = Flag::where('slug',$slug)->where('identifier','activity')->first();
                    if($newFlag) {                    
                        $isSaved = PackageFlags::where('package_id',$package_flag->package_id)->where('flag_id',$package_flag->flag_id)->update(['flag_id'=>$newFlag->id]);
                        if($isSaved) {
                            pr('Activity Flag for activity '.$package_flag->package_id.' Updated');
                        } else {
                            pr('Activity Flag for activity '.$package_flag->package_id.' Already Updated');
                        }
                    }                    
                }
            }
        }
    }


    /* end of controller */
}