<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{

    protected $table = 'user_wallet';

    protected $guarded = ['id'];

    public function UserDetails() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function orderData() {
        return $this->belongsTo('App\Models\Order', 'txn_id', 'order_no');
    }
}
