<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DestinationFlag extends Model{
    
    protected $table = 'destination_flag';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $fillable = [];
   
}
