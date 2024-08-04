<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contact_enquiries';  

    protected $fillable = ['name', 'phone','contact_email','person_count','duration','month_of_travel','comment','country']; 
}
