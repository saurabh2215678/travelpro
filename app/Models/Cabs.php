<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CabsCityPrice;
use App\Models\CabsInventory;
use App\Models\CabRouteInventory;
class Cabs extends Model{
    
    protected $table = 'cabs';

    protected $guarded = ['id'];

    protected $fillable = [];

    public static function cabsDelete($id) {
        try {
            $data = Cabs::where('id', $id)->first();
            if($data) {
                $name = $data->name;
                if (!empty($data)) {
                    $idArr = [];
                    $idArr[] = (string) $id;
                    $cabCitiesCount = CabsCities::whereJsonContains('cab',$idArr)->count();
                    if($cabCitiesCount > 0) {
                        return ['status' => 'error', 'message' => 'This Cab is in use, you cannot delete it.'];
                    }
                    CabsCityPrice::where('cab_id',$id)->delete();
                    CabsInventory::where('cab_id',$id)->delete();
                    CabRouteInventory::where('cab_id',$id)->delete();
                    $is_deleted = $data->delete();
                    if ($is_deleted) {
                        return ['status' => 'ok', 'message' => $name . ' Cab Has Been Deleted..!', 'name' => $name];
                    }
                } else {
                    return ['status' => 'error', 'message' => 'Cab Not Found..!'];
                }
            } else {
                return ['status' => 'error', 'message' => 'Cab details not found.'];
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