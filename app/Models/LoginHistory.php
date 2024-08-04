<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model{

    protected $table = 'login_history';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'user_name',
        'ip_address',
        'os_details',
        'browser_details',
        'action',
    ];

    public function adminUser() {
        return $this->belongsTo('App\Models\Admin', 'user_id');
    }
}