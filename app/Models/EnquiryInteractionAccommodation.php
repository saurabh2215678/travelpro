<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EnquiryInteractionAccommodation extends Model {

    protected $table = 'enquiries_interactions_accommodations';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [];
}