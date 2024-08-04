<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CabRoutePrice extends Model{
    
    protected $table = 'cab_route_price';

    protected $guarded = ['id'];

    protected $fillable = [];
    
    /**
     * @return BelongsTo
     */
    function CabData(): BelongsTo
    {
        return $this->belongsTo('App\Models\CabMaster', 'cab_id')->where('status',1);
    }

}