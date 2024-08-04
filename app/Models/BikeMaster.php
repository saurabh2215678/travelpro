<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\BikeCities;
use App\Models\BikeCityInventory;
use Storage;

class BikeMaster extends Model{
    
    protected $table = 'bike_master';

    protected $guarded = ['id'];

    protected $fillable = [];

    public static function checkBikeCities($id) {
        $data = BikeCities::whereJsonContains('bike', $id)->first();
        return $data->name??'';
    }
    public static function bikeCities($id) {

        $data = BikeCities::whereJsonContains('bike', [(string)$id])->get();
        return $data;
    }

      public function inventoryData() {
        return $this->hasMany('App\Models\BikeCityInventory', 'bike_id')->where('status',1);
    }

    public static function bikeMasterDelete($id)
    {
        try {
            $data = BikeMaster::where('id', $id)->first();
            $name = $data->name;
            if (!empty($data)) {
                $bikeCities = self::checkBikeCities($id);
                if($bikeCities){
                    return ['status' => 'error', 'message' => 'This Bike is in use, you cannot delete it.'];
                }else{
                    $is_deleted = $data->delete();
                }
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . ' Bike Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Bike Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Bike is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }
        }
    }

    public static function parseBike($bike,$total_days=1,$cityId=0)
    {
        $storage = Storage::disk('public');
        $path = 'bikes/';    

        $image = $bike->image;
        $src = '';
        if(!empty($image)){
            if($storage->exists($path.$image)){ 
                $src = url('storage/'.$path.'thumb/'.$image);
                } 
            }
                $bike_id = $bike->id;
                // $total_days = $bike->inventoryData->count();
                $BikeCityInventories = $bike->inventoryData;

                if($BikeCityInventories){
                $bikeCity_arr=[];
                $city_ids=[];
                $price = 0;
                foreach ($BikeCityInventories as $key => $BikeCityInventory) {

                    // prd($BikeCityInventory);
                    $price+= $BikeCityInventory->price;
                    $city_id = $BikeCityInventory->city_id;
                    $bikeCity = BikeCities::where('id',$city_id)->whereNotIn('id',$city_ids)->first();

                    $city_ids [] = $city_id ;
                    if($bikeCity){
                        
                        $locationData = $bikeCity->locations ;

                        foreach ($locationData as $locationRow) {
                        $bikeCity_arr[] = array(

                            'id' => $locationRow->id,
                            'name' => $locationRow->name,
                        );   

                        }   

                    }

                }

                $priceData = BikecityPrice::where('city_id',$city_id)->where('bike_id',$bike_id)->first();

                $price = $priceData->price?? 0;
                $totalprice =$price*$total_days;

                $bike_arr = array(
                    'id' =>$bike->id,
                    'name' =>$bike->name,
                    'equivalent' =>$bike->equivalent,
                    'modal' =>$bike->modal,
                    'city_id' =>$cityId,
                    'cities' =>$bikeCity_arr,
                    'price' =>$totalprice,
                    'src' =>$src,
                );

            }


            

        return $bike_arr;  
    }
    

}