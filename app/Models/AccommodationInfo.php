<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccommodationInfo extends Model{
    
    protected $table = 'accommodation_info';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'accommodation_id',
        'brief',
        'image',
        'description',
        'sort_order',
        'status'
    ];

}