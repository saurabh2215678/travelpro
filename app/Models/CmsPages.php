<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPages extends Model{
    
    protected $table = 'cms_pages';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function children(){
        return $this->hasMany('App\Models\CmsPages', 'parent_id')->orderBy('sort_order','ASC');
    }

    public function cmsImages() {
        return $this->hasMany('App\Models\CommonImage', 'tbl_id')->where('tbl_name','cms_pages')->where('category','gallery')->orderBy('is_default','DESC')->orderBy('sort_order','ASC');
    }
}