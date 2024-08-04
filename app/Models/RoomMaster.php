<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomMaster extends Model{
    
    protected $table = 'room_master';

    protected $guarded = ['id'];
    

    protected $fillable = [
        'name',
        'type',
        'sort_order',
        'status'
    ];
    public static function accommodationRoomMasterDelete($id)
    {
        try {
            $data = RoomMaster::where('id', $id)->first();
            $name = $data->name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Room Master Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Room Master Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Room Master is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }

}