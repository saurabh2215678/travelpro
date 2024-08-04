<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model{

    protected $table = 'sms_templates';

    protected $guarded = ['id'];

    protected $fillable = [
        'title',
        'slug',
        'template_id',
        'content',
        'status',
        'created_at',
        'updated_at',
    ];
}