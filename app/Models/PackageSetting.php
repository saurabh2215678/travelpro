<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PackageSetting extends Model {
	protected $table = 'package_settings';
	protected $guarded = ['id'];
	protected $fillable = [];
}