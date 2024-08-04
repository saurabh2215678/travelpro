<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model{
    
    protected $table = 'banners';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'slug',
        'type',
        'video_type',
        'status'
    ];

    public function Images() {
        return $this->hasMany('App\Models\BannerImage')->where('status',1)->orderBy('sort_order','ASC');
    }
    public static function bannerImageDelete($banner_id)

    {
        try {
            $data = Banner::where('id', $banner_id)->first();
            $name = $data->title;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Banner Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Banner Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Banner is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }
}