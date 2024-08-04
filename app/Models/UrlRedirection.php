<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlRedirection extends Model{

    protected $table = 'seo_url_redirection';

    protected $guarded = ['id'];

    protected $fillable = [
        'url_type',
        'source_url',
        'destination_url',
        'status_code',
        'show',
    ];
}