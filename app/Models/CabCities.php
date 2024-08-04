<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CabCities extends Model{
    
    protected $table = 'cab_cities';

    protected $guarded = ['id'];

    protected $fillable = [];

    function cabsCityPrices(): HasMany
    {
        return $this->hasMany('App\Models\CabsCityPrice', 'city_id');
    }

    public static function cabCityDelete($id) {
        try {
            $data = CabCities::where('id', $id)->first();
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

    

}