<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PackageFlights extends Model {

    protected $table = 'package_flights';

    protected $guarded = ['id'];

    public function Package() {
        return $this->belongsTo('App\Models\Package', 'package_id');
    }
}