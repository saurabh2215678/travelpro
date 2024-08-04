<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationInfo extends Model{
    
    protected $table = 'destination_info';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'destination_id',
        'type',
        'brief',
        'image',
        'featured',
        'description',
        'sort_order',
        'status'
    ];

}