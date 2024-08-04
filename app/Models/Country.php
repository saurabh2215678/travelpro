<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [
        'name',
        'iso3',
        'numeric_code',
        'iso',
        'phonecode',
        'capital',
        'currency',
        'currency_name',
        'region',
        'subregion',
        'emoji',
        'status'
        
    ];

}