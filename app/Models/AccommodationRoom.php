<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccommodationRoom extends Model {

    protected $table = 'accommodation_room';

    protected $guarded = ['id'];
    /**
     * @return HasMany
     */
    public function planData(): HasMany
    {
        return $this->hasMany('App\Models\AccommodationPlan', 'room_id')->where('status',1);
    }     

    public function RoomType() {
        return $this->belongsTo('App\Models\RoomType', 'room_type_id');
    }
    /**
     * @return BelongsTo
     */
    public function roomAccommodation(): BelongsTo
    {
        return $this->belongsTo('App\Models\Accommodation', 'accommodation_id');
    }
    /**
     * @return BelongsTo
     */
    public function roomAccommodationType(): BelongsTo
    {
        return $this->belongsTo('App\Models\RoomType', 'room_type_id');
    }

    public function inventoryData() {
        return $this->hasMany('App\Models\AccommodationInventory', 'room_id')->where('status',1);
    }

    public function accommodationRoomImages() {
        return $this->hasMany('App\Models\CommonImage', 'tbl_id')->where('tbl_name','accommodation_rooms')->where('category','gallery')->orderBy('is_default','DESC')->orderBy('sort_order','ASC')->orderBy('created_at','DESC');
    }
}