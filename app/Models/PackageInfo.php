<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageInfo extends Model{
    
    protected $table = 'package_info';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'package_id',
        'description',
        'sort_order',
        'status'
    ];

}