<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Helpers\CustomHelper;
use App\Models\DestinationInfo;
use Storage;

class Destination extends Model{

    protected $table = 'destinations';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function destinationType(){
        return $this->belongsTo('App\Models\DestinationType', 'destination_type')->where('status',1);
    }

    // this relationship will only return one level of child items
    public function destinations() {
        return $this->hasMany(Destination::class, 'parent_id')->where('status',1)->where('show',1);
    }

    // This is method where we implement recursive relationship
    public function childDestinations() {
        return $this->hasMany(Destination::class, 'parent_id')->with('destinations')->where('is_deleted',0)->where('is_city',0)->orderby('sort_order', 'asc')->orderby('name', 'asc');
    }

    /**
     * @return BelongsToMany
     */
    public function destinationFlags(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\DestinationFlag', 'destination_flags', 'destination_id', 'flag_id');
    }
    /**
     * Sub-Categories of this Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(){
        return $this->hasMany('App\Models\Destination', 'parent_id')->where('is_deleted',0)->where('is_city',0)->orderby('sort_order', 'asc')->orderby('name', 'asc');
    }

    /**
     * Parent Category of this Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(){
        return $this->belongsTo('App\Models\Destination', 'parent_id');
    }

    public function destinationPackages(){
        return $this->hasMany('App\Models\Package', 'destination_id');
    }
    /**
     * @return HasMany
     */
    public function Packages(): HasMany
    {
        return $this->hasMany('App\Models\Package', 'destination_id');
    }
    /**
     * @return HasMany
     */
    public function destinationLocations(): HasMany
    {
        return $this->hasMany('App\Models\Destination', 'parent_id')->where('is_deleted',0)->where('is_city','1')->orderBy('sort_order','asc')->orderby('name', 'asc');
    }

    public function destinationLocationsCount(){
        return $this->hasMany('App\Models\Destination', 'parent_id')->where('is_deleted',0)->where('is_city','1')->count();
    }

    public function destinationPackagesCount() {
        return Package::where(function($q){
            $q->where('destination_id',$this->id)->orWhere('sub_destination_id',$this->id);
        })->where('is_activity',0)->count();
    }

    public function destinationActivityCount() {
        return Package::where(function($q){
            $q->where('destination_id',$this->id)->orWhere('sub_destination_id',$this->id);
        })->where('is_activity',1)->count();
    }

    public function destinationImages() {
        return $this->hasMany('App\Models\CommonImage', 'tbl_id')->where('tbl_name','destinations')->where('category','gallery')->orderBy('sort_order','ASC');
    }

    public function destinationBanners() {
        return $this->hasMany('App\Models\CommonImage', 'tbl_id')->where('tbl_name','destinations')->where('category','banner')->orderBy('sort_order','ASC');
    }

    public static function destinationDelete($id)

    {
        try {
            $data = Destination::where('id', $id)->first();
            $name = $data->name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . ' Destination Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Destination Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Destination is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }

    public static function parseDestination($destination,$params=[]) {
        $destinationData = [];
        if($destination && $destination->id) {
            $storage = Storage::disk('public');
            $path = 'destinations/';

            $size = $params['size']??'box';
            $destinationData['name'] = CustomHelper::wordsLimit($destination->name);
            $destinationData['description'] = $destination->description;

            $imageSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
            $thumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($destination->image)) {
                if($storage->exists($path.$destination->image)) {
                    $imageSrc = asset('/storage/'.$path.$destination->image);
                    $thumbSrc = asset('/storage/'.$path.'thumb/'.$destination->image);
                }
            }
            $destinationData['thumbSrc'] = $thumbSrc;

            if($size == 'detail') {
                $destinationData['imageSrc'] = $imageSrc;
                $images = (!($destination->destinationImages->isEmpty())) ? $destination->destinationImages : [];
                $images_arr = [];
                foreach($images as $image) {
                    $imageSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
                    $thumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
                    if($storage->exists($path.$image->name)) {
                        $imageSrc = asset('/storage/'.$path.$image->name);
                        $thumbSrc = asset('/storage/'.$path.'thumb/'.$image->name);
                    }
                    $images_arr[] = array(
                        'name' => $image->name,
                        'imageSrc' => $imageSrc,
                        'thumbSrc' => $thumbSrc,
                    );
                }
                $destinationData['images'] = $images_arr;


                $query = DestinationInfo::selectRaw('destination_info.type,destination_type.name,destination_type.slug')->where('destination_info.destination_id',$destination->id)->where('destination_info.status',1);
                $query = $query->leftJoin('destination_type',function ($join) {
                    $join->on('destination_type.id', '=' , 'destination_info.type');
                });
                $destinationTypes = $query->groupBy('destination_info.type')->orderBy('destination_type.sort_order','ASC')->get();
                // prd($destinationTypes->toArray());

                $destinationTypes_arr = [];
                if(isset($destinationTypes) && !empty($destinationTypes) > 0) {
                    foreach($destinationTypes as $destinationType) {
                        $row = [];
                        $row['name'] = $destinationType->name;
                        $row['slug'] = (!empty($destinationType->slug))?$destinationType->slug:CustomHelper::slugify($destinationType->name);
                        $row['type'] = $destinationType->type;
                        $destination_info = CustomHelper::getDestinationTypeInfo($destination->id,$destinationType->type);
                        $destination_info_arr = [];
                        if($destination_info) {
                            foreach($destination_info as $info) {
                                $info_row = [];
                                $info_row['id'] = $info->id;
                                $info_row['title'] = $info->title;
                                $info_row['brief'] = $info->brief;
                                $info_row['description'] = $info->description;
                                if(!empty($info->image)) {
                                    if($storage->exists($path.$info->image)) {
                                        $info_row['imageSrc'] = asset('/storage/'.$path.'thumb/'.$info->image);
                                    }
                                }
                                $destination_info_arr[] = $info_row;
                            }
                        }
                        $row['destination_info'] = $destination_info_arr;
                        $destinationTypes_arr[] = $row;
                    }
                }
                $destinationData['destinationTypes'] = $destinationTypes_arr;
            }
        }
        return $destinationData;
    }

}