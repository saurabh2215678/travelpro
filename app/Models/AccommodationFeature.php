<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccommodationFeature extends Model{
    
    protected $table = 'accommodation_features';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'is_featured',
        'sort_order',
        'status'
    ];

}