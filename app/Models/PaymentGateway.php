<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model{

    protected $table = 'payment_gateways';

    protected $guarded = ['id'];

    protected $fillable = [];
}