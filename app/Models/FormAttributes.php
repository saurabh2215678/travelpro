<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FormAttributes extends Model {
    
    protected $table = 'form_attributes';

    protected $guarded = ['id'];

    protected $fillable = [];
}