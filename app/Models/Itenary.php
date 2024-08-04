<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itenary extends Model{
    
    protected $table = 'itenaries';

    protected $guarded = ['id'];

    public function Location() {
        return $this->belongsTo('App\Models\Destination', 'location_id');
    }

    public function itenaryTags()
    {
        return $this->belongsToMany('App\Models\Tag', 'itenary_tags', 'itenary_id', 'tag_id');
    }

    public function Package() {
        return $this->belongsTo('App\Models\Package', 'package_id');
    }

    public static function packageItenariesDelete($id)

    {
        try {
            $data = Itenary::where('id', $id)->first();
            $name = $data->title;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Itinerary Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Itinerary Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Itinerary is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }
}