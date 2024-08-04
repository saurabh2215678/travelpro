<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccomodationType extends Model{
    
    protected $table = 'accommodation_type';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'sort_order',
        'status'
    ];

    public function roomAccommodation(){
        return $this->belongsTo('App\Models\Accommodation', 'accommodation_id');
    }
    
}