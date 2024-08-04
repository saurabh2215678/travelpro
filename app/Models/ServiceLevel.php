<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceLevel extends Model{
    
    protected $table = 'service_levels';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'name',
        'status'
    ];

}