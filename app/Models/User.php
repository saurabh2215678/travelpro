<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserWallet;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $guarded = ['id'];
    
    protected $fillable = [
        'is_agent',
        'is_vendor',
        'group_id',
        'approved_agent',
        'name',
        'email',
        'phone',
        'country_code',
        'gender',
        'dob',
        'profile_image',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'zipcode',
        'password',
        'referrer',
        'remember_token',
        'verify_token',
        'is_verified',
        'otp',
        'status',
        'forgot_token',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
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
    /**
     * @return BelongsTo
     */
    public function agentGroup(): BelongsTo
    {
        return $this->belongsTo('App\Models\AgentGroup', 'group_id');
    }
    public function agentInfo(){
        return $this->hasOne('App\Models\UserAgentInfo', 'user_id');
    }
    /**
     * @return HasMany
     */
    public function agentInfoSearch(): HasMany
    {
        return $this->hasMany('App\Models\UserAgentInfo', 'user_id');
    }
    public function usersBookings(){
        return $this->hasMany('App\Models\Order', 'user_id');
    }

    public function walletBalance() {
        return UserWallet::where('user_id',$this->id)->sum('amount');
    }

    public static function CreateUser($data, $params=[]) {
        $response = [];
        $response['success'] = false;
        $response['msg'] = '';
        try {
            $id = $params['id']??'';
            $action = $params['action']??'';
            $record = [];
            if(isset($data['is_agent'])){
                $record['is_agent'] = $data['is_agent'];
            }
            if(isset($data['is_vendor'])){
                $record['is_vendor'] = $data['is_vendor'];
            }
            if(isset($data['group_id'])){
                $record['group_id'] = $data['group_id'];
            }
            if(isset($data['approved_agent'])){
                $record['approved_agent'] = $data['approved_agent'];
            }
            if(isset($data['name'])){
                $record['name'] = $data['name'];
            }
            if(isset($data['email'])){
                $record['email'] = $data['email'];
            }
            if(isset($data['phone'])) {
                $record['phone'] = $data['phone'];
                if(isset($data['country_code'])) {
                    $record['country_code'] = $data['country_code'];
                }
            }
            if(isset($data['country_code'])){
                $record['country_code'] = $data['country_code'];
            }
            if(isset($data['gender'])){
                $record['gender'] = $data['gender'];
            }
            if(isset($data['dob'])){
                $record['dob'] = $data['dob'];
            }
            if(isset($data['profile_image'])){
                $record['profile_image'] = $data['profile_image'];
            }
            if(isset($data['address1'])){
                $record['address1'] = $data['address1'];
            }
            if(isset($data['address2'])){
                $record['address2'] = $data['address2'];
            }
            if(isset($data['city'])){
                $record['city'] = $data['city'];
            }
            if(isset($data['state'])){
                $record['state'] = $data['state'];
            }
            if(isset($data['country'])){
                $record['country'] = $data['country'];
            }
            if(isset($data['zipcode'])){
                $record['zipcode'] = $data['zipcode'];
            }
            if(isset($data['password'])){
                $record['password'] = $data['password'];
            }
            if(isset($data['referrer'])){
                $record['referrer'] = $data['referrer'];
            }
            if(isset($data['remember_token'])){
                $record['remember_token'] = $data['remember_token'];
            }
            if(isset($data['verify_token'])){
                $record['verify_token'] = $data['verify_token'];
            }
            if(isset($data['is_verified'])){
                $record['is_verified'] = $data['is_verified'];
            }
            if(isset($data['otp'])){
                $record['otp'] = $data['otp'];
            }
            if(isset($data['status'])){
                $record['status'] = $data['status'];
            }
            if(isset($data['forgot_token'])){
                $record['forgot_token'] = $data['forgot_token'];
            }
            if(isset($data['email_verified_at'])){
                $record['email_verified_at'] = $data['email_verified_at'];
            }
            // prd($record);
            if(!empty($record)) {
                if($id) {
                    if($action == 'booking') {
                        $response['id'] = $id;
                        $response['success'] = true;
                    } else {
                        $isSaved = User::where('id',$id)->update($record);
                        if($isSaved) {
                            $response['id'] = $id;
                            $response['success'] = true;
                        }
                    }
                } else {
                    $country_code = $record['country_code']??'';
                    $phone = $record['phone']??'';
                    $email = $record['email']??'';
                    //Use only email as primary, discussed on //19-may-2023
                    // if($phone || $email) {
                    if(!empty($email)) {
                        $query = User::orderBy('id','desc');
                        /*if($phone && $email) {
                            $query->where(function($q1) use($country_code, $phone, $email){
                                $q1->where('phone',$phone);
                                $q1->orWhere('email',$email);
                            });
                        } else if($phone) {
                            $query->where('phone',$phone);
                        } else if($email) {
                            $query->where('email',$email);
                        }*/
                        $query->where('email',$email);
                        $exists = $query->get()->first();
                        if($exists) {
                            $id = $exists->id;
                            if($action == 'booking') {
                                $response['id'] = $id;
                                $response['email'] = $exists->email;
                                $response['success'] = true;
                            } else {
                                $isSaved = User::where('id',$id)->update($record);
                                if($isSaved) {
                                    $response['exists'] = true;
                                }
                                $response['id'] = $id;
                                $response['email'] = $exists->email;
                                $response['success'] = true;
                            }
                        } else {
                            $record['status'] = 1;
                            $isSaved = User::create($record);
                            if($isSaved) {
                                $email = $record['email']??'';
                                $id = $isSaved->id;
                                $response['id'] = $id;
                                $response['email'] = $email;
                                $response['success'] = true;
                            }
                        }
                    } else {
                        // $response['msg'] = 'Phone or Email is required.';
                        $response['msg'] = 'Email is required.';
                    }
                }
            }
        } catch (\Exception $e) {
            $response['msg'] = $e->getMessage();
        }
        return $response;
    }

}
