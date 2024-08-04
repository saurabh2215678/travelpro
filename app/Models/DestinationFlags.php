<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DestinationFlags extends Model{
    
    protected $table = 'destination_flags';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $fillable = [];
   
}