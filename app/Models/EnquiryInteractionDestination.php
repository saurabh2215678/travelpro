<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EnquiryInteractionDestination extends Model {

    protected $table = 'enquiries_interactions_destinations';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [];
}