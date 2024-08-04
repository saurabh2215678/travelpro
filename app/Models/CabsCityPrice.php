<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CabsSightseeing;
use App\Helpers\CustomHelper;
use Storage;

class CabsCityPrice extends Model {
    
    protected $table = 'cabs_city_price';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function cityData() {
        return $this->belongsTo('App\Models\CabsCities', 'city_id');
    }

    public function cabData() {
        return $this->belongsTo('App\Models\Cabs', 'cab_id');
    }

    public function cabCityLocations() {
        return $this->hasMany('App\Models\CabCityLocation', 'city_id', 'city_id');
    }

    public static function parseCabsCityPrice($cabsCityPrice,$params=[]) {
        $cabsData = [];
        if($cabsCityPrice && $cabsCityPrice->id) {
            $storage = Storage::disk('public');
            $path = 'cabs/';
            $size = $params['size']??'box';
            $distance = $params['distance']??0;
            $returnEnabled = $params['returnEnabled']??0;
            $tripType = $params['tripType']??1;

            $inclusions = $params['inclusions']??'';
            $exclusions = $params['exclusions']??'';
            $terms = $params['terms']??'';

            $search_data = $params['search_data']??[];

            $cityData = $cabsCityPrice->cityData??[];
            $cabData = $cabsCityPrice->cabData??[];

            $sold = false;
            $base_fare = $cabData->base_price??0;

            $no_of_days = 0;
            $chauffeur_charge = 0;
            $total_chauffeur_charge = 0;
            $night_stay_allowance = 0;
            $no_of_nights = 0;
            $total_night_stay_allowance = 0;

            $image = $cabData->image??'';
            $image_url = asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($image)){
                if($storage->exists($path.$image)){
                    $image_url = url('storage/'.$path.'thumb/'.$image);
                }
            }

            if($tripType == 5) {
                if(isset($search_data['sightseeing'])) {
                    $sightseeingData = CabsSightseeing::find($search_data['sightseeing']);
                    if($sightseeingData) {
                        $routeId = $sightseeingData->id;
                        $routeName = $sightseeingData->name;
                        $from = $sightseeingData->origin;
                        $distance = $sightseeingData->distance;
                        $distance_text = $distance.' km';
                        $duration_text = $sightseeingData->duration;
                        $duration_type = $sightseeingData->duration_type;
                        $duration_value = $sightseeingData->duration_value;
                    }
                }
            }

            $price = $cabsCityPrice->price??0;
            $price_per_km = $price;
            $per_day_km = 0;
            $total_days_km = 0;
            $total_distance_price = 0;
            $billable_distance = 0;
           
            $distance = ceil((float) ($distance));
            $total_distance = $distance;

            if($base_fare) {
                $price = $base_fare;
            }

            if($tripType == 1) {
                $total_distance_price = $price_per_km * $total_distance;
                $price = $price + $total_distance_price;
            } else if($tripType == 2) {
                if($returnEnabled) {
                    $no_of_days = CustomHelper::DateDiff($search_data['return'],$search_data['dep']);
                    $no_of_days = $no_of_days + 1;
                    if($no_of_days) {
                        $no_of_nights = $no_of_days - 1;
                    }
                    $total_distance = $distance * 2;
                }
                if(empty($no_of_days)) {
                    $no_of_days = 1;
                }

                $per_day_km = $cityData->per_day_km??0;

                if(!empty($per_day_km) && !empty($distance) && !empty($no_of_days)) {
                    if($distance > $per_day_km) {
                        $distance = $per_day_km;
                        $per_day_total_distance = $per_day_km * $no_of_days;
                        if($per_day_total_distance > $total_distance) {
                            $total_distance = $per_day_total_distance;
                            $billable_distance = $total_distance;
                        } else {
                            $billable_distance = $total_distance;
                        }
                    } else {
                        $distance = $per_day_km;
                        $per_day_total_distance = $per_day_km * $no_of_days;
                        if($per_day_total_distance > $total_distance) {
                            $total_distance = $per_day_total_distance;
                            $billable_distance = $total_distance;
                        } else {
                            $billable_distance = $total_distance;
                        }
                    }

                    $total_distance_price = $price_per_km * $total_distance;
                    $price = $price + $total_distance_price;
                } else {
                    $total_distance_price = $price_per_km * $total_distance;
                    $price = $price + $total_distance_price;
                }

                if(!empty($per_day_km) && !empty($no_of_days)) {
                    $total_days_km = $per_day_km * $no_of_days;
                }

                if(!empty($no_of_days)) {
                    $chauffeur_charge = $cabData->chauffeur_charge??0;
                    if(!empty($chauffeur_charge)) {
                        $total_chauffeur_charge = $chauffeur_charge * $no_of_days;
                        $price = $price + $total_chauffeur_charge;
                    }
                }

                if(!empty($no_of_nights)) {
                    $night_stay_allowance = $cabData->night_stay_allowance??0;
                    if(!empty($night_stay_allowance)) {
                        $total_night_stay_allowance = $night_stay_allowance * $no_of_nights;
                        $price = $price + $total_night_stay_allowance;
                    }
                }
            } else if($tripType == 3) {
                $total_distance_price = $price_per_km * $total_distance;
                $price = $price + $total_distance_price;

                $is_airport = $cityData->is_airport??0;
                $airport_max_distance = $cityData->airport_max_distance??0;
                $airport_entry_charge = $cityData->airport_entry_charge??0;
                if($is_airport == 1 && !empty($airport_entry_charge)) {
                    $price = $price + $airport_entry_charge;
                }
                if(!empty($airport_max_distance) && $total_distance > $airport_max_distance) {
                    $sold = true;
                }
            } else if($tripType == 4) {
                $total_distance_price = $price_per_km * $total_distance;
                $price = $price + $total_distance_price;
                
                $is_railway = $cityData->is_railway??0;
                $station_max_distance = $cityData->station_max_distance??0;
                $station_entry_charge = $cityData->station_entry_charge??0;
                if($is_railway == 1 && !empty($station_entry_charge)) {
                    $price = $price + $station_entry_charge;
                }
                if(!empty($station_max_distance) && $total_distance > $station_max_distance) {
                    $sold = true;
                }
            } else if($tripType == 5) {
                if(isset($duration_type) && $duration_type == 'days' && isset($duration_value) && !empty($duration_value)) {
                    $no_of_days = $duration_value;
                    if($no_of_days > 0) {
                        $no_of_nights = $no_of_days - 1;
                    }
                }

                /*$per_day_km = $cityData->per_day_km??0;

                if(!empty($per_day_km) && !empty($distance) && !empty($no_of_days) && $distance < $per_day_km) {
                    $distance = $per_day_km;
                    $total_distance = $per_day_km * $no_of_days;
                    $billable_distance = $total_distance;
                    $total_distance_price = $price_per_km * $total_distance;
                    $price = $price + $total_distance_price;
                }

                if(!empty($per_day_km) && !empty($no_of_days)) {
                    $total_days_km = $per_day_km * $no_of_days;
                }*/
                
                $total_distance_price = $price_per_km * $total_distance;
                $price = $price + $total_distance_price;

                if(!empty($no_of_days)) {
                    $chauffeur_charge = $cabData->chauffeur_charge??0;
                    if(!empty($chauffeur_charge)) {
                        $total_chauffeur_charge = $chauffeur_charge * $no_of_days;
                        $price = $price + $total_chauffeur_charge;
                    }
                }

                if(!empty($no_of_nights)) {
                    $night_stay_allowance = $cabData->night_stay_allowance??0;
                    if(!empty($night_stay_allowance)) {
                        $total_night_stay_allowance = $night_stay_allowance * $no_of_nights;
                        $price = $price + $total_night_stay_allowance;
                    }
                }
            }

            $publish_price = $price;
            $price = $price;
            if(empty($price)) {
                $sold = true;
            }
            
            $cabsData = [
                'id' => $cabsCityPrice->id??'',
                'city_id' => $cabsCityPrice->city_id??'',
                'city_name' => $cityData->name??'',
                'cab_id' => $cabsCityPrice->cab_id??'',
                'name' => $cabData->name??'',
                'seats' => $cabData->seats??0,
                'image' => $image_url,
                'sort_order' => $cabData->sort_order??0,
                'publish_price' => $publish_price,
                'price' => $price,
                'equivalent' => $cabData->equivalent??'',
                'sharing' => '',
                'route_sharing' => '',
                'sold' => $sold,
                'start_time' => ''
            ];

            $fareDetails = [
                'base_fare' => $base_fare,
                'price' => $price,
                'publish_price' => $publish_price,
                'per_day_km' => $per_day_km,
                'total_days_km' => $total_days_km,
                'price_per_km' => $price_per_km,
                'no_of_days' => $no_of_days,
                'chauffeur_charge' => $chauffeur_charge,
                'total_chauffeur_charge' => $total_chauffeur_charge,
                'night_stay_allowance' => $night_stay_allowance,
                'no_of_nights' => $no_of_nights,
                'total_night_stay_allowance' => $total_night_stay_allowance,
                'distance' => $distance,
                'total_distance' => $total_distance,
                'billable_distance' => $billable_distance,
                'total_distance_price' => $total_distance_price,
                'inclusions' => $inclusions,
                'exclusions' => $exclusions,
                'terms' => $terms,
            ];
            if($tripType == 3) {
                $fareDetails['airport_entry_charge'] = $airport_entry_charge;
            } else if($tripType == 4) {
                $fareDetails['station_entry_charge'] = $station_entry_charge;
            }
            $cabsData['fareDetails'] = $fareDetails;

        }
        return $cabsData;
    }


}