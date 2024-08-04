<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PriceCategory extends Model{
    
    protected $table = 'price_categories';

    protected $guarded = ['id'];

    public function priceCategoryElements() {
        return $this->hasMany('App\Models\PriceCategoryElement', 'pc_id')->orderBy('sort_order','asc');
    }

    public function pricePackages() {
        return $this->hasMany('App\Models\Package', 'price_category');
    }

    public function Packages() {
        return $this->hasMany('App\Models\Package', 'price_category');
    }

    public static function priceCategoryDelete($id) {
        try {
            $data = PriceCategory::where('id', $id)->first();
            $name = $data->name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Price Category Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Price Category Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Price Category is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }
    }
}