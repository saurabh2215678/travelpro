<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PackageDeparture extends Model {

	protected $table = 'package_departures';

	protected $guarded = ['id'];

	protected $fillable = [];
	/**
     * @return BelongsTo
     */
	public function PackageDepartureCapacity(): BelongsTo
	{
        return $this->hasMany('App\Models\PackageDepartureCapacity', 'package_departure_id');
    }
}