<?php
namespace App\Models;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Helpers\CustomHelper;
use Storage;
use File;

class Package extends Model{

    protected $table = 'packages';
    protected $guarded = ['id'];
    /**
     * @return BelongsTo
     */
    public function packageDestination(): BelongsTo
    {
        return $this->belongsTo('App\Models\Destination', 'destination_id');
    }
    /**
     * @return BelongsTo
     */
    public function packageParentDestination(): BelongsTo
    {
        return $this->belongsTo('App\Models\Destination', 'destination_id');
    }
    /**
     * @return BelongsToMany
     */
    public function packageCategories(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\ThemeCategories', 'package_themes', 'package_id', 'theme_id');
    }
    /**
     * @return BelongsToMany
     */
    public function packageFlags(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Flag', 'package_flags', 'package_id', 'flag_id');
    }
    /**
     * @return BelongsToMany
     */
    public function packageTags(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Tag', 'package_tags', 'package_id', 'tag_id');
    }
    public function packageItenaries(){
        return $this->hasMany('App\Models\Itenary', 'package_id')->where('status',1)->orderBy('day','asc');

    }
    public function packageExperts(){
        return $this->belongsToMany('App\Models\TeamManagement', 'package_team_members', 'package_id', 'member_id');
    }
    public function PackageInfo(){
        return $this->hasMany('App\Models\PackageInfo', 'package_id')->orderBy('sort_order','ASC');
    }

    public function packageImages() {
        return $this->hasMany('App\Models\CommonImage', 'tbl_id')->where('tbl_name','packages')->where('category','gallery')->orderBy('is_default','DESC')->orderBy('sort_order','ASC');
    }

    public function packageBanners() {
        return $this->hasMany('App\Models\CommonImage', 'tbl_id')->where('tbl_name','packages')->where('category','banner')->orderBy('is_default','DESC')->orderBy('sort_order','ASC');
    }

    public function packagePriceCategory(){
        return $this->belongsTo('App\Models\PriceCategory', 'price_category');
    }

    public function packageDefaultPrice() {
        return $this->hasOne('App\Models\PackagePriceInfo', 'package_id')->where('status',1)->orderBy('is_default','DESC')->orderBy('sort_order','ASC');
    }
    /**
     * @return HasMany
     */
    public function packagePrices(): HasMany
    {
        return $this->hasMany('App\Models\PackagePriceInfo', 'package_id')->where('status',1)->orderBy('sort_order','ASC');
    } 
    public function Prices() {
        return $this->hasMany('App\Models\PackagePriceInfo', 'package_id')->where('status',1)->orderBy('sort_order','ASC');
    }
    /**
     * @return HasMany
     */
    public function PackageDeparture(): HasMany
    {
        return $this->hasMany('App\Models\PackageDeparture','package_id');
    }

    public function PackageFlights() {
        return $this->hasMany('App\Models\PackageFlights','package_id');
    }

    public function PackageType() {
        return $this->belongsTo('App\Models\PackageType', 'package_type');
    }

    public function packageVendorData() {
        return $this->belongsTo('App\Models\Admin', 'vendor_id');
    }

    public function getExperName($id) {
        $data = TeamManagement::where('id', $id)->first();
           return $data->title??'';
    }

    public static function getSubdestinationId($text='') {
        $data = Destination::where('name','like','%'.$text.'%')->first();
        // return $data->id??0;
        return [$data->id??0, $data->name??''];
    }

    public function Admin() {
        return $this->belongsTo('App\Models\Admin', 'vendor_id');
    }

    public static function packagesDelete($id) {
        try {
            $data = Package::where('id', $id)->first();
            $name = $data->title;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Package Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Package Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Package is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }
        }
    }

    public static function parsePackage($package,$params=[]) {
        if($package && $package->id) {
            $size = $params['size']??'box';

            $storage = Storage::disk('public');
            $path = 'packages/';

            $packageData = [];
            $packageData['id'] = $package->id;
            $packageData['title'] = CustomHelper::wordsLimit($package->title);
            $packageData['inclusions'] = $package->inclusions;
            $packageData['is_activity'] = ($package->is_activity)?$package->is_activity:0;
            $packageData['featured'] = $package->featured??0;
            $packageData['search_price'] = $package->search_price??0;
            $packageData['publish_price'] = $package->publish_price??0;
            $packageData['nights'] = $package->nights;
            $packageData['days'] = $package->days;

            $package_tour_type = $package->tour_type??'';
            $package_tour_type_text = '';
            if($package->is_activity==0){
                if(isset($package_tour_type) && $package_tour_type == 'group'){
                    $package_tour_type_text = "Group Package";
                }elseif (isset($package_tour_type) && ($package_tour_type == 'fixed' || $package_tour_type == 'open')) {
                    $package_tour_type_text = "Flexi Package";
                }
            } else {
                $package_tour_type_text = "Activity Package";
            }
            $packageData['package_tour_type'] = $package_tour_type;
            $packageData['package_tour_type_text'] = $package_tour_type_text;

            $package_image = $package->image;
            $packageSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
            $thumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($package_image)) {
                if($storage->exists($path.$package_image)) {
                    $packageSrc = asset('/storage/'.$path.$package_image);
                    $thumbSrc = asset('/storage/'.$path.'thumb/'.$package_image);
                }
            }
            $packageData['thumbSrc'] = $thumbSrc;
            $packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';
            $packageData['url'] = route($packageDetailName,['slug'=>$package->slug]);

            if($size == 'listing' || $size == 'detail') {
                $packageData['brief'] = CustomHelper::wordsLimit($package->brief);

                $days_nights_city = unserialize($package->days_nights_city);
                $days_nights_city_arr = [];
                if(!empty($days_nights_city)) {
                    $ii = 0;
                    foreach($days_nights_city as $dnc_key => $dnc_value) {
                        $ii++;
                        $days_nights_city_str =  $dnc_key.' ('.$dnc_value.'D)';
                        if($ii < count($days_nights_city) ){
                            $days_nights_city_str.= ' <i class="fa fa-long-arrow-right"></i> '; 
                        }
                        $days_nights_city_arr[] =$days_nights_city_str; 
                    }
                }
                $packageData['days_nights_city'] = $days_nights_city_arr;

                if($size == 'listing') {
                    $package_inclusions_arr = [];
                    if($package->package_inclusions) {
                        $package_inclusion_ids = json_decode($package->package_inclusions,true);
                        $package_inclusions = CustomHelper::showPackageInclusionsOther($package_inclusion_ids);
                        
                        if($package_inclusions) {
                            $i_path = 'inclusion/';
                            foreach($package_inclusions as $inclusion_key => $inclusion_val){
                                $i_image = asset(config('custom.assets').'/images/ico3.png');
                                if(!empty($inclusion_key) && File::exists(public_path('storage/inclusion/'.$inclusion_key))){
                                    $i_image = url('storage/'.$i_path.'thumb/'.$inclusion_key);
                                    $package_inclusions_arr[]  = array(
                                        'value' =>$inclusion_val ,
                                        'image' => $i_image,
                                    ); 
                                }
                            }
                        }
                    }
                    $packageData['package_inclusions'] = $package_inclusions_arr;
                }

                $packageData['enquiry_url'] = route('enquire-this-trip',['package' =>$package->slug]);
                $packageData['packageDestination_name'] = !empty($package->packageDestination)? $package->packageDestination->name :'';
                $packageData['packagePrices'] = $package->packagePrices;
                $packageData['package_duration'] = $package->package_duration;
                $packageData['tour_type'] = $package->tour_type;
                $packageData['packageCategories'] = $package->packageCategories;
                $packageData['packagePriceCategory'] = $package->packagePriceCategory;
                $packageData['packageDefaultPrice'] = $package->packageDefaultPrice;
            }

            if($size == 'detail') {
                $packageData['slug'] = $package->slug;
                $packagePriceCategory = $package->packagePriceCategory??[];
                $packageData['packageSrc'] = $packageSrc;

                $package_inclusions_arr = [];
                if($package->package_inclusions) {
                    $package_inclusion_ids = json_decode($package->package_inclusions,true);
                    $package_inclusions = CustomHelper::showPackageInclusionsOther($package_inclusion_ids);
                    
                    if($package_inclusions) {
                        $i_path = 'inclusion/';
                        foreach($package_inclusions as $inclusion_key => $inclusion_val){
                            $i_image = asset(config('custom.assets').'/images/ico3.png');
                            if(!empty($inclusion_key) && File::exists(public_path('storage/inclusion/'.$inclusion_key))){
                                $i_image = url('storage/'.$i_path.'thumb/'.$inclusion_key);
                            }
                            $package_inclusions_arr[]  = array(
                                'value' =>$inclusion_val ,
                                'image' => $i_image,
                            ); 
                        }
                    }
                }
                $packageData['package_inclusions'] = $package_inclusions_arr;

                $best_months = (isset($package->best_months))?json_decode($package->best_months):[];
                $monthName = '';
                if(!empty($best_months)){
                    $monthArr = config('custom.months_arr');
                    $monthNames = [];
                    foreach($best_months as $month) {
                        if(isset($monthArr[$month])) {
                            $monthNames[] = $monthArr[$month];
                        }
                    }
                    $monthName = implode(', ', $monthNames);
                }
                $packageData['best_months'] = $monthName;


                $package_images = (!($package->packageImages->isEmpty())) ? $package->packageImages : [];
                $package_images_arr = [];
                foreach($package_images as $package_image){
                    if($storage->exists($path.$package_image->name)){
                        $image = asset('/storage/'.$path.$package_image->name);
                    } else {
                        $image =asset(config('custom.assets').'/images/noimage.jpg');
                    }
                    $package_images_arr[] = array(
                        'name' => $package_image->name,
                        'title' => $package_image->title,
                        //'is_default' => $package_image->is_default,
                        'sort_order' => $package_image->sort_order??0,
                        'srcimage' => $image,
                    );
                }
                $packageData['images'] = $package_images_arr;
                $packageData['brief'] = $package->brief;
                $packageData['description'] = $package->description;
                $packageData['inclusions'] = $package->inclusions;
                $packageData['exclusions'] = $package->exclusions;
                $packageData['payment_policy'] = $package->payment_policy;
                $packageData['cancellation_policy'] = $package->cancellation_policy;
                $packageData['terms_conditions'] = $package->terms_conditions;
                $packageData['video_link'] = $package->video_link;
                $packageData['PackageInfo'] = $package->PackageInfo->toArray();
                // prd($packageData['PackageInfo']->toArray());
                // prd($packageData['PackageInfo']);
                $packageData['packageDestination_name'] = $package->packageDestination? $package->packageDestination->name :'';
                $packageData['packagePriceCategory'] = $package->packagePriceCategory;
                $packageData['priceCategoryElements'] = $packagePriceCategory->priceCategoryElements??[];
            }
        }

        return $packageData;

    }

    public static function parsePackagePrice($price,$params=[]) {
        $priceData = [];
        if($price) {
            $booking_price = $price->booking_price??0;
            $is_partial_amount = $price->is_partial_amount??0;
            $is_book_without_payment = $price->is_book_without_payment??0;
            $is_agent = Auth::user()->is_agent??0;
            if($is_agent) {
                $booking_price = $price->booking_price_b2b??0;
                $is_partial_amount = $price->is_partial_amount_b2b??0;
                $is_book_without_payment = $price->is_book_without_payment_b2b??0;
            }
            $priceData = [
                'id' => $price->id??'',
                'package_id' => $price->package_id??'',
                'title' => $price->title??'',
                'discount_type' => $price->discount_type??'',
                'discount' => $price->discount??'',
                'description' => $price->description??'',
                'booking_price' => $booking_price,
                'sub_total_amount' => $price->sub_total_amount??'',
                'final_amount' => $price->final_amount??'',
                'category_choices_record' => $price->category_choices_record??'',
                'show_choices_record' => $price->show_choices_record??'',
                'is_partial_amount' => $is_partial_amount,
                'is_book_without_payment' => $is_book_without_payment,
                'price_validity' => $price->price_validity??'',
                'status' => $price->status??'',
                'sort_order' => $price->sort_order??'',
                'is_default' => $price->is_default??'',
            ];
        }
        return $priceData;
    }

}