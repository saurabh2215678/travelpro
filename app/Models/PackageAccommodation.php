<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PackageAccommodation extends Model {

	protected $table = 'package_accommodations';

	// protected $guarded = ['id'];

	public $timestamps = false;

    public function Itenary() {
        return $this->belongsTo('App\Models\Itenary', 'itenary_id');
    }

    public function Accommodation() {
        return $this->belongsTo('App\Models\Accommodation', 'accommodation_id');
    }
}