<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountModuleToGroup extends Model{

    protected $table = 'agent_discount_module_to_group';

    protected $guarded = ['id'];

    public $timestamps = false;

   

   public function agent_group() {
        return $this->belongsTo('App\Models\AgentGroup', 'agent_group_id');
    }
public function discount_category() {
        return $this->belongsTo('App\Models\ModuleDiscountCategory', 'module_discount_type_id');
    }

}