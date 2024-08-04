<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePriceInfo extends Model{
    
    protected $table = 'package_price_info';

    protected $guarded = ['id'];

    public function Package() {
        return $this->belongsTo('App\Models\Package', 'package_id');
    }
    
    public function PackageAccommodation() {
        return $this->hasMany('App\Models\PackageAccommodation', 'package_price_id');
    }


    public static function packagepriceInfoDelete($id) {
        try {
            $data = PackagePriceInfo::where('id', $id)->first();
            $name = $data->title;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Package Price Info Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Package Price Info Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Package Price Info is in use, you cannot delete it.'];
            } else {
                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }
        }
    }

}