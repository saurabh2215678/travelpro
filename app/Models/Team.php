<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model{

    protected $table = 'teams';

    protected $guarded = ['id'];

    protected $fillable = [];

    //public $timestamps = false;
 
}