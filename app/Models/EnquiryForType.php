<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EnquiryForType extends Model {

    protected $table = 'enquiries_for_types';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [];
}