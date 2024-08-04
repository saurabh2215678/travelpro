<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMetaTag extends Model
{
    protected $table = 'seo_meta_tags';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [];

}