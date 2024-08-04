<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OrderServiceVoucher extends Model {

    protected $table = 'order_service_voucher';

    protected $guarded = ['id'];

    /*public function Order(){
        return $this->belongsTo('App\Models\Order', 'order_id');
    }*/

    public function Package(){
        return $this->belongsTo('App\Models\Package', 'package_id');
    }
    
}