<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserAgentInfo extends Model {

    protected $table = 'user_agent_info';

    protected $guarded = ['id'];

    public function User() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}