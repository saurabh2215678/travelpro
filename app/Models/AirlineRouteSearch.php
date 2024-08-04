<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AirlineRouteSearch extends Model {

    protected $table = 'airline_route_search';

    protected $guarded = ['id'];

    protected $fillable = [];
}