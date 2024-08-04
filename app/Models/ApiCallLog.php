<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ApiCallLog extends Model{

    protected $table = 'api_call_log';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function User() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}