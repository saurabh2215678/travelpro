<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $table = 'widgets';

    protected $guarded = ['id'];

    // public $timestamps = false;

    protected $fillable = [
        'widget_name',
        'slug',
        'section_heading',
        'section_sub_heading',
        'about_widget_desc',
        'description',
        'image1',
        'image2',
        'status'
    ];
        
}