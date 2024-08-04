<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PackageFlags extends Model {
    
    protected $table = 'package_flags';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [];

    public function Package() {
        return $this->belongsTo('App\Models\Package', 'package_id');
    }

    public function Flag() {
        return $this->belongsTo('App\Models\Flag', 'flag_id');
    }   
}