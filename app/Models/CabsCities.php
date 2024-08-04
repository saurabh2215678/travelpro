<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CabsCities extends Model {
    
    protected $table = 'cabs_cities';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function cabsCityPrices() {
        return $this->hasMany('App\Models\CabsCityPrice', 'city_id', 'id');
    }

    public function cabsSightseeings() {
        return $this->belongsTo('App\Models\CabsSightseeing', 'id', 'origin');
    }

    public static function cabCityDelete($id) {
        try {
            $data = CabsCities::where('id', $id)->first();
            $name = $data->name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . ' Cab City Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Cab City Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Cab City is in use, you cannot delete it.'];
            } else {
                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }
    }

    public static function parseCabsCity($cabsCity,$params=[]) {
        $cityData = [];
        if($cabsCity && $cabsCity->id) {
            // $storage = Storage::disk('public');
            // $path = 'cabs_sightseeing/';
            $size = $params['size']??'box';
            $search_data = $params['search_data']??[];
            $cityData = [
                'id' => $cabsCity->id,
                'name' => $cabsCity->name,
                'discount_category_id' => $cabsCity->discount_category_id,
                'cab' => $cabsCity->cab,
                'per_day_km' => $cabsCity->per_day_km,
                'inclusions' => $cabsCity->inclusions,
                'exclusions' => $cabsCity->exclusions,
                'terms' => $cabsCity->terms,
                'is_sharing' => $cabsCity->is_sharing,
                'is_sightseeing' => $cabsCity->is_sightseeing,
                'featured' => $cabsCity->featured,
                'is_airport' => $cabsCity->is_airport,
                'airport_entry_charge' => $cabsCity->airport_entry_charge,
                'airport_max_distance' => $cabsCity->airport_max_distance,
                'terminals' => $cabsCity->terminals,
                'is_railway' => $cabsCity->is_railway,
                'station_entry_charge' => $cabsCity->station_entry_charge,
                'station_max_distance' => $cabsCity->station_max_distance,
                'stations' => $cabsCity->stations,
                'is_deleted' => $cabsCity->is_deleted,
                'sort_order' => $cabsCity->sort_order,
                'status' => $cabsCity->status,
            ];
        }
        return $cityData;
    }
}