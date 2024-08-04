<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderAmendments extends Model {

    protected $table = 'order_amendments';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function Order() {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }
}