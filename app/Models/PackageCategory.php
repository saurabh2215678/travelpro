<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageCategory extends Model{
    
    protected $table = 'package_categories';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'status',
        'slug',
        'identifier'
    ];

}