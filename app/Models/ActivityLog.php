<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model{

    protected $table = 'admin_user_activity_logs';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'user_name',
        'module',
        'module_desc',
        'module_id',
        'sub_module',
        'sub_module_desc',
        'sub_module_id',
        'sub_sub_module',
        'sub_sub_module_desc',
        'sub_sub_module_id',
        'data_after_action',
        'action',
        'user_agent',
        'ip_address',
    ];

    public function adminUser() {
        return $this->belongsTo('App\Models\Admin', 'user_id');
    }
}