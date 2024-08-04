<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerImageGallery extends Model{
    
    protected $table = 'banner_image_gallery';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'subtitle',
        'brief',
        'page',
        'device_type',
        'image',
        'link',
        'sort_order',
        'status'
    ];

    public function Images() {
        return $this->hasMany('App\Models\BannerImage','banner_id');
    }
}