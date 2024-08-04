<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizePackage extends Model{
    
    protected $table = 'customize_package_enquaries';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'package_id',
        'name',
        'phone',
        'email',
        'country',
        'zip_code',
        'want_to_travel',
        'no_of_packs',
        'duration_of_stay',
        'need_flight',
        'travelled_with',
        'content'

    ];

    public function customizePackage(){
        return $this->belongsTo('App\Models\Package', 'package_id');
    }
    
}