<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserGstInfo extends Model {

    protected $table = 'user_gst_info';

    protected $guarded = ['id'];

    public function User() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}