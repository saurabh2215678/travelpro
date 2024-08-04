<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomFeature extends Model{
    
    protected $table = 'room_feature';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'sort_order',
        'status'
    ];

}