<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Coupon extends Model{

    protected $table = 'coupons';

    protected $guarded = ['id'];

    protected $fillable = [];

    public $timestamps = false;

    public function couponUsers(){
        return $this->belongsToMany('App\Models\User','coupon_users','coupon_id','user_id');
    }

    public function couponUsedCount() {
    	return Order::where('payment_status',1)->where('discount_coupon_details','like','%'.$this->code.'%')->count();
    }



}