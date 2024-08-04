<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerImage extends Model{
    
    protected $table = 'banner_images';

    protected $guarded = ['id'];

    protected $fillable = [
        'banner_id',
        'image_name',
    ];
}