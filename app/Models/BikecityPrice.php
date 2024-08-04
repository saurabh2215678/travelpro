<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BikecityPrice extends Model{
    
    protected $table = 'bike_city_price';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function city() {
        return $this->belongsTo('App\Models\BikeCities', 'city_id');
    }

    public function bikeData() {
        return $this->belongsTo('App\Models\BikeMaster', 'bike_id');
    }

    public function bikeCityLocations() {
        return $this->hasMany('App\Models\BikeCityLocation', 'city_id', 'city_id');
    }
}