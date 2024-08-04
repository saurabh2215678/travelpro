<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model {

    protected $table = 'orders_status_history';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function GetCategory() {
		return $this->belongsTo('App\Models\EnquiriesMaster', 'orders_status');
	}
}