<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EnquiryInteractionForType extends Model {

    protected $table = 'enquiries_interactions_for_types';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [];
}