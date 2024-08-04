<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model{
    
    protected $table = 'downloads';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'brief',
        'image',
        'documents',
        'status'
    ];

}