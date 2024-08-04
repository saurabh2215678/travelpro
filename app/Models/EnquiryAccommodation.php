<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EnquiryAccommodation extends Model {

    protected $table = 'enquiries_accommodations';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [];
}