<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class BikeCities extends Model{
    
    protected $table = 'bike_cities';
    protected $guarded = ['id'];
    protected $fillable = [];

    public function bikeName($id) {
        $data = BikeMaster::where('id', $id)->first();
           return $data->name??'';
    }

    public function locations(){
        return $this->hasMany('App\Models\BikeCityLocation', 'city_id');
    }

    public static function bikeCityDelete($id)
    {
        try {
            $data = BikeCities::where('id', $id)->first();
            $name = $data->name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . ' Bike City Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Bike City Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Bike City is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }

    

}