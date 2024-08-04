<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EnquiryDestination extends Model {

    protected $table = 'enquiries_destinations';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [];
}