<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model{
    
    protected $table = 'menu_items';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function children(){
    	return $this->hasMany('App\Models\MenuItem', 'parent_id')->orderBy('sort_order');
    }

}