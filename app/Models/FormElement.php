<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormElement extends Model{
    
    protected $table = 'form_elements';

    protected $guarded = ['id'];

    protected $fillable = [];
}