<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageAirline extends Model{
    
    protected $table = 'package_airlines';

    protected $guarded = ['id'];

    protected $fillable = [
        'airline_name',
        'airline_code',
        'slug',
        'identifier',
        // 'airline_from',
        // 'airline_to',
        // 'price',
        'image',
        'status',
        'sort_order'
    ];
    
}