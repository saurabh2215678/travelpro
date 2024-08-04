<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OrderTraveller extends Model {

    protected $table = 'orders_traveller';

    protected $guarded = ['id'];

    public function Order(){
        return $this->belongsTo('App\Models\Order', 'order_id');
    }
}