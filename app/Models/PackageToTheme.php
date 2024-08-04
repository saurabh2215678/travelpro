<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PackageToTheme extends Model {

    protected $table = 'package_themes';

    protected $fillable = [];

    public $timestamps = false;
    
    public function Package() {
        return $this->belongsTo('App\Models\Package', 'package_id');
    }

    public function ThemeCategory() {
        return $this->belongsTo('App\Models\ThemeCategories', 'theme_id');
    }
}