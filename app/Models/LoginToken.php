<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginToken extends Model{
    protected $table = 'login_token';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'token',
        'create_date',
        'expire_date',
    ];
}