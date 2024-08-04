<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleDiscountCategory extends Model{

    protected $table = 'module_discount_category';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function DiscountModuleToGroup(){
        return $this->hasMany('App\Models\DiscountModuleToGroup', 'module_discount_type_id')->orderBy('module_name','ASC');
    }
}