<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInventory extends Model
{   
     protected $table = 'order_inventory';

     protected $guarded = ['id'];

     protected $fillable = [];
}
