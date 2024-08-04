<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model{

    protected $table = 'email_templates';

    protected $guarded = ['id'];

    protected $fillable = [
        'title',
        'slug',
        'subject',
        'content',
        'bcc_admin',
        'manager_email',
        'contact_email',
        'status',
        'created_at',
        'updated_at',
    ];
}