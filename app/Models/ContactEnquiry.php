<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ContactEnquiry extends Model {
	protected $table = 'contact_enquiries';
	protected $guarded = ['id'];
	protected $fillable = [];

	public function form() {
		return $this->belongsTo('App\Models\Form', 'form_id');
	}
}