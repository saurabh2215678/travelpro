<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model{
    
    protected $table = 'room_type';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'title',
        'sort_order',
        'status'
    ];
    public static function accommodationRoomTypeDelete($id)

    {
        try {
            $data = RoomType::where('id', $id)->first();
            $name = $data->title;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Room Type Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Room Type Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Room Type is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }

}