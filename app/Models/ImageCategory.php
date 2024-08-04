<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageCategory extends Model{
    
    protected $table = 'image_categories';

    protected $guarded = ['id'];

    protected $fillable = [];

    //public $timestamps = false;

    public function images() {
        return $this->hasMany('App\Models\CommonImage','category_id', 'id');
    }

    /**
     * Sub-Categories of this Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(){
        return $this->hasMany('App\Models\ImageCategory', 'parent_id');
    }

    /**
     * Parent Category of this Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(){
        return $this->belongsTo('App\Models\ImageCategory', 'parent_id');
    }
    
}