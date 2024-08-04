<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccommodationPlan extends Model{
    
    protected $table = 'accommodation_plans';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function inventoryData() {
    	return $this->hasMany('App\Models\AccommodationInventory', 'plan_id')->where('status',1);
    }
}