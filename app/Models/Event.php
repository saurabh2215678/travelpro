<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model{

    protected $table = 'events';

    protected $guarded = ['id'];

    protected $fillable = [];

    //public $timestamps = false;

}