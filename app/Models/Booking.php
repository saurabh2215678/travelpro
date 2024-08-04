<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model{
    
    protected $table = 'book_now_enquiries';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'trip_date',
        'service_level',
        'original_price',
        'final_pay_price',
        'name',
        'phone',
        'email',
        'country',
        'zip_code'
    ];
    public function bookingPackage(){
        return $this->belongsTo('App\Models\Package', 'package_id');
    }

    public function bookingCountry(){
        return $this->belongsTo('App\Models\Country', 'country');
    }

    public function usersBookings(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}