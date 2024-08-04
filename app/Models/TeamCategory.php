<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamCategory extends Model{
    
    protected $table = 'team_categories';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'status'
    ];

}