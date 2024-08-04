<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccommodationFacility extends Model{
    
    protected $table = 'accommodation_facilities';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'sort_order',
        'status'
    ];

}