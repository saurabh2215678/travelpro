<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AccommodationInventory extends Model {
    protected $table = 'accommodation_inventory';
    protected $guarded = ['id'];
    protected $fillable = [];

    public function Accommodation() {
        return $this->belongsTo('App\Models\Accommodation', 'accommodation_id');
    }

    public function AccommodationRoom() {
        return $this->belongsTo('App\Models\AccommodationRoom', 'room_id');
    }

    public function AccommodationPlan() {
        return $this->belongsTo('App\Models\AccommodationPlan', 'plan_id');
    }

    public static function parseData($record) {
        $inventory_data = $record->toArray();
        $inventory_data['AccommodationRoom'] = $record->AccommodationRoom->toArray();
        $inventory_data['AccommodationPlan'] = $record->AccommodationPlan->toArray();
        return $inventory_data;
    }
}