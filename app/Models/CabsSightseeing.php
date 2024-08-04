<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Helpers\CustomHelper;
use App\Models\CabsCities;
use Storage;

class CabsSightseeing extends Model {

    protected $table = 'cabs_sightseeing';

    protected $guarded = ['id'];

    protected $fillable = [];

    /**
    * @return BelongsToMany
    */
    function CabRouteToGroup(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\CabRouteGroup', 'cab_route_to_group', 'cab_route_id', 'cab_route_group_id')->orderBy('sharing','asc');
    }
    /**
    * @return BelongsTo
    */
    function CabRouteGroup(): BelongsTo
    {
        return $this->belongsTo('App\Models\CabRouteGroup', 'cab_route_group_id')->where('status',1);
    }

    function ModuleDiscountCategory(){
        return $this->belongsTo('App\Models\ModuleDiscountCategory', 'discount_category_id');
    }
    /**
    * @return BelongsTo
    */
    function originCity(): BelongsTo
    {
        return $this->belongsTo('App\Models\CabsCities', 'origin');
    }

    function destinationCity(){
        return $this->belongsTo('App\Models\CabsCities', 'destination');
    }

    /* function CabRouteinventory()
    {
    return $this->belongsToMany('App\Models\CabRouteInventory', 'cab_route_group', 'id', 'id');
    }*/
    /**
    * @return HasMany
    */
    function CabRouteinventory(): HasMany
    {
        return $this->hasMany('App\Models\CabRouteInventory', 'cab_route_group_id', 'cab_route_group_id')->where('status',1);
    }

    public function sightseeingImages() {
        return $this->hasMany('App\Models\CommonImage', 'tbl_id')->where('tbl_name','cabs_sightseeing')->where('category','gallery')->orderBy('is_default','DESC')->orderBy('sort_order','ASC');
    }

    public static function cabsSightseeingDelete($id) {
        try {
            $data = CabsSightseeing::find($id);
            $name = $data->name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . ' Cab Route Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Cab Route Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Cab Route is in use, you cannot delete it.'];
            } else {
                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }
        }
    }

    public static function parseCabsSightseeing($cabsSightseeing,$params=[]) {
        // prd($cabsSightseeing);
        $cabsSightseeingData = [];
        if($cabsSightseeing && $cabsSightseeing->id) {
            $storage = Storage::disk('public');
            $path = 'cabs_sightseeing/';
            $thumb_path = 'cabs_sightseeing/thumb/';
            $size = $params['size']??'box';
            $search_data = $params['search_data']??[];
            $dep = $params['dep']??'';
            $time = $params['time']??'';
            if(empty($dep)) {
                $dep = date('Y-m-d');
                $pre_date =  date('Y-m-d', strtotime($dep. ' -1 days'));
                $dep = CustomHelper::CabDateSelect($dep,$pre_date,'dep');
            }
            if(empty($time)) {
                $time = '08:00';
            }
            // $book_url = route('cab.sightseeing').'?tripType=5&from='.$cabsSightseeing->origin.'&to=&dep='.$dep.'&time='.$time.'&sightseeing='.$cabsSightseeing->id;
            $book_url = route('cab.sightseeing').'?sightseeing='.$cabsSightseeing->id;
            $sightseeingImages = $cabsSightseeing->sightseeingImages??[];
            $image = '';
            if($sightseeingImages) {
                $image = $sightseeingImages[0]->name??'';
            }
            $image_url = asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($image)){
                if($storage->exists($path.$image)){
                    $image_url =  url('storage/'.$path.'thumb/'.$image);
                }
            }
            $distance = $cabsSightseeing->distance??0;
            $publish_price = $cabsSightseeing->publish_price??0;
            $search_price = $cabsSightseeing->search_price??0;
            // if($distance) {
            //     $publish_price = $publish_price*$distance;
            //     $search_price = $search_price*$distance;
            // }
            $cabsSightseeingData = [
                'id' => $cabsSightseeing->id,
                'name' => $cabsSightseeing->name,
                'url' => $cabsSightseeing->id,
                'places' => $cabsSightseeing->places,
                'duration' => $cabsSightseeing->duration,
                'distance' => $cabsSightseeing->distance??0,
                'discount_category_id' => $cabsSightseeing->originCity->discount_category_id??'',
                'description' => $cabsSightseeing->description,
                'sharing' => $cabsSightseeing->sharing,
                'publish_price' => $publish_price,
                'search_price' => $search_price,
                'book_url' => $book_url,
                'image_url' => $image_url,
            ];
            if($size == 'detail') {
                $images_arr = [];
                if($sightseeingImages) {
                    $image_url = asset(config('custom.assets').'/images/noimage.jpg');
                    foreach($sightseeingImages as $image) {
                        $image_name = $image->name??'';
                        if ($image_name) {
                            $image_src = '';
                            $image_thumb_src = '';
                            if ($storage->exists($path . $image_name)) {
                                $image_src = asset('/storage/' . $path . $image_name);
                            }
                            if ($storage->exists($thumb_path . $image_name)) {
                                $image_thumb_src = asset('/storage/' . $thumb_path . $image_name);
                            }
                            if($image_src && $image_thumb_src) {
                                $images_arr[] = [
                                    'id' => $image->id,
                                    'image_src' => $image_src,
                                    'image_thumb_src' => $image_thumb_src,
                                    'title' => (!empty($image->title))?$image->title:$cabsSightseeing->name,
                                    'link' => $image->link,
                                ];
                            }
                        }
                    }
                }
                $cabsSightseeingData['images'] = $images_arr;
                $cabsSightseeingData['originCityName'] = $cabsSightseeing->originCity->name??'';
                $cabsSightseeingData['destinationCityName'] = $cabsSightseeing->destinationCity->name??'';
            }
        }
        return $cabsSightseeingData;
    }

    public static function updateCabsSightseeingPrice($id) {
        if($id) {
            $sightseeingData = CabsSightseeing::where('id',$id)->first();
            if($sightseeingData) {
                $search_data = [];
                $search_data['sightseeing'] = $id;
                $tripType = 5;
                $returnEnabled = 0;
                $from = $sightseeingData->origin;
                $distance = $sightseeingData->distance;
                $query = CabsCities::with('cabsCityPrices')->where('status',1);
                $query->where(function($q1) {
                    $q1->where('is_deleted',0);
                    $q1->orWhereNull('is_deleted');
                });
                if($from) {
                    $query->where('id',$from);
                }
                if($tripType == 3) {
                    $query->where('is_airport',1);
                } else if($tripType == 4) {
                    $query->where('is_railway',1);
                }
                $query->orderBy('sort_order', 'asc');
                $results = $query->limit(100)->get();
                $data_arr = [];
                if($results) {
                    $params = [];
                    $params['search_data'] = $search_data;
                    $params['distance'] = $distance;
                    $params['returnEnabled'] = $returnEnabled;
                    $params['tripType'] = $tripType;
                    $params['tripType'] = $tripType;
                    foreach ($results as $key => $row) {
                        $cabsCityPrices = $row->cabsCityPrices??[];
                        if($cabsCityPrices) {
                            foreach($cabsCityPrices as $cabsCityPrice) {
                                $data_arr[]  = CabsCityPrice::parseCabsCityPrice($cabsCityPrice,$params);
                            }
                        }
                    }
                }
                if($data_arr) {
                    $price = 0;
                    $publish_price = 0;
                    foreach($data_arr as $data) {
                        $data_price = $data['price']??0;
                        $data_publish_price = $data['publish_price']??0;
                        if(!empty($data_price)) {
                            if(empty($price)) {
                                $price = $data_price;
                                $publish_price = $data_publish_price;
                            } else if($data_price < $price) {
                                $price = $data_price;
                                $publish_price = $data_publish_price;
                            }
                        }
                    }
                    CabsSightseeing::where('id',$id)->update(['search_price'=>$price,'publish_price'=>$publish_price]);
                }
            }
        }        
    }
}