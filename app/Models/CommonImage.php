<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonImage extends Model{
    
    protected $table = 'common_images';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function ImageCategory() {
        return $this->belongsTo('App\Models\ImageCategory', 'category_id');
    }
}