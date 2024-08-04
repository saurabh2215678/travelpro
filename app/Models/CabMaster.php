<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CabMaster extends Model{
    
    protected $table = 'cab_master';

    protected $guarded = ['id'];

    protected $fillable = [];

    public static function cabMasterDelete($id)

    {
        try {
            $data = CabMaster::where('id', $id)->first();
            $name = $data->name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . ' Cab Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Cab Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Cab is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }

    

}