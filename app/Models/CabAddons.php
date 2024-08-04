<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CabAddons extends Model {
    
    protected $table = 'cab_addons';

    protected $guarded = ['id'];

    protected $fillable = [];

    public static function cabAddonsDelete($id) {
        try {
            $data = CabAddons::where('id', $id)->first();
            $name = $data->name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . ' Cab Addons Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Cab Addons Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Cab Addons is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }
        }
    }

    public static function getRecord($record, $params=[]) {
        $recordData = [];
        if($record && $record->id) {
            $size = $params['size']??'box';
            $search_data = $params['search_data']??[];
            $recordData = [
                'id' => $record->id,
                'name' => $record->name,
                'price' => $record->price,
                'sort_order' => $record->sort_order,
                'featured' => $record->featured,
                'daily_rental' => $record->daily_rental,
                'status' => $record->status,
                'is_deleted' => $record->is_deleted,
            ];
        }
        return $recordData;
    }

    

}