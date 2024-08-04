<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageType extends Model{
    
    protected $table = 'package_types';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'package_type',
        'status',
        'slug',
        'identifier'
    ];

}