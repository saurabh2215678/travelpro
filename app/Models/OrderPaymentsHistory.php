<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class OrderPaymentsHistory extends Model {

    protected $table = 'order_payments_history';

    protected $guarded = ['id'];

    protected $fillable = [];

     /**
     * @return BelongsTo
     */
     public function orderData(): BelongsTo
     {
         return $this->belongsTo('App\Models\Order', 'order_id')->with('userData');
      }
}