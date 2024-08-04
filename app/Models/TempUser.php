<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TempUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $table = 'temp_users';

    protected $guarded = ['id'];
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'confirm_password',
        'phone',
        'verify_token',
        'is_verified',
        'forgot_token',
        'address1',
        'address2',
        'city',
        'state',
        'country_code',
        'country',
        'status',
        'otp',
        'zipcode',
        'is_agent',
        'group_id',
        'is_vendor',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        // 'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function signupCountry(){
        return $this->belongsTo('App\Models\Country', 'country');
    }
    public function agentGroup(){
        return $this->belongsTo('App\Models\AgentGroup', 'group_id');
    }
    public function agentInfo(){
        return $this->hasOne('App\Models\UserAgentInfo', 'user_id');
    }
    public function agentInfoSearch(){
        return $this->hasMany('App\Models\UserAgentInfo', 'user_id');
    }
    public function usersBookings(){
        return $this->hasMany('App\Models\Order', 'user_id');
    }
}
