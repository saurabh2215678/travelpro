<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabRouteToGroup extends Model
{
    
    protected $table = 'cab_route_to_group';

    protected $guarded = ['id'];

    protected $fillable = [];
}
