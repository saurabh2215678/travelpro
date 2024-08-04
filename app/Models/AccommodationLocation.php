<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AccommodationLocation extends Model {

    protected $table = 'accommodation_locations';

    protected $guarded = ['id'];

    public function DestinationLocation(){
        return $this->belongsTo('App\Models\Destination', 'destination_location_id');
    }
}