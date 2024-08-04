<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AirlineMarkup extends Model{

    protected $table = 'flight_commission';

    protected $guarded = ['id'];

    protected $fillable = [];
}