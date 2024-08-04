<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Other extends Model{
    
    protected $table = 'others';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'brief',
        'url_link',
        'image',
        'status'
    ];

}