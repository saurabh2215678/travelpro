<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model{
    
    protected $table = 'faq_categories';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function children(){
        return $this->hasMany('App\Models\FaqCategory', 'parent_id');
    }

    // public function faqCategories(){
    //     return $this->belongsTo('App\Models\CourseCategory', 'category_id');
    // }
}