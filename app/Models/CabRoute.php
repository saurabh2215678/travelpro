<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class CabRoute extends Model{
    
    protected $table = 'cab_route';

    protected $guarded = ['id'];

    protected $fillable = [];

    /**
     * @return BelongsToMany
     */
    function CabRouteToGroup(): BelongsToMany
    {
      return $this->belongsToMany('App\Models\CabRouteGroup', 'cab_route_to_group', 'cab_route_id', 'cab_route_group_id')->orderBy('sharing','asc');
    }
    /**
     * @return BelongsTo
     */
   function CabRouteGroup(): BelongsTo
   {
        return $this->belongsTo('App\Models\CabRouteGroup', 'cab_route_group_id')->where('status',1);
    }
   
   function ModuleDiscountCategory(){
        return $this->belongsTo('App\Models\ModuleDiscountCategory', 'discount_category_id');
    }
    /**
     * @return BelongsTo
     */
    function originCity(): BelongsTo
    {
        return $this->belongsTo('App\Models\CabCities', 'origin');
    }

    function destinationCity(){
        return $this->belongsTo('App\Models\CabCities', 'destination');
    }

    /* function CabRouteinventory()
    {
          return $this->belongsToMany('App\Models\CabRouteInventory', 'cab_route_group', 'id', 'id');
    }*/
    /**
     * @return HasMany
     */
     function CabRouteinventory(): HasMany
    {
        return $this->hasMany('App\Models\CabRouteInventory', 'cab_route_group_id', 'cab_route_group_id')->where('status',1);
    }

    public static function cabRouteDelete($id)

    {
        try {
            $data = CabRoute::where('id', $id)->first();
            $name = $data->name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . ' Cab Route Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Cab Route Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Cab Route is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }

}