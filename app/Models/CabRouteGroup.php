<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CabRouteGroup extends Model{
    
    protected $table = 'cab_route_group';

    protected $guarded = ['id'];

    protected $fillable = [];

    // public function Cabs() {
    //     return $this->hasMany('App\Models\CabRoute','cab_route_group_id','id')->where('status',1);
    // }

    function CabRoutes(){
      return $this->belongsToMany('App\Models\CabRoute', 'cab_route_to_group', 'cab_route_group_id', 'cab_route_id');
    }

    public function RouteInventory() {
        return $this->hasMany('App\Models\CabRouteInventory','cab_route_group_id','id')->where('status',1);
    }


}