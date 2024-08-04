<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PackageToSetting extends Model {
	protected $table = 'package_to_settings';
	protected $guarded = ['id'];
	public $timestamps = false;
	protected $fillable = [];
}