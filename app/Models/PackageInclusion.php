<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageInclusion extends Model{
    
    protected $table = 'package_inclusion_lists';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'slug',
        'identifier',
        'sort_order',
        'status',
        'image'
    ];

}