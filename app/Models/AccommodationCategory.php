<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccommodationCategory extends Model{

    protected $table = 'accommodation_categories';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'sort_order',
        'status'
    ];

    public static function accommodationCategoryDelete($id)

    {
        try {
            $data = AccommodationCategory::where('id', $id)->first();
            $name = $data->title;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . ' Accommodation Category Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Accommodation Category Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Accommodation Category is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }

}