<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EnquiryAccommodationType extends Model {

    protected $table = 'enquiries_accommodation_types';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [];
}