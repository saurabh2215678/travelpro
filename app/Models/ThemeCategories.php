<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ThemeCategories extends Model {

    protected $table = 'themes';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function children(){
        return $this->hasMany('App\Models\ThemeCategories', 'parent_id');
    }

    public function Packages() {
        return $this->hasMany('App\Models\PackageToTheme', 'theme_id');
    }

    public function themeBanners() {
        return $this->hasMany('App\Models\CommonImage', 'tbl_id')->where('tbl_name','themes')->where('category','banner')->orderBy('sort_order','ASC');
    }

    public static function packageThemesDelete($id) {
        try {
            $data = ThemeCategories::where('id', $id)->first();
            $name = $data->title;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Theme Categories Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Theme Categories Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Theme Categories is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }
    }
}