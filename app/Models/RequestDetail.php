<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDetail extends Model{
    
    protected $table = 'request_details';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'package_id',
        'name',
        'form_type',
        'phone',
        'email',
        'country',
        'zip_code',
        'plan_to_travel',
        'travelled_with'
    ];

    public function requestitineraryPackage(){
        return $this->belongsTo('App\Models\Package', 'package_id');
    }
    
}