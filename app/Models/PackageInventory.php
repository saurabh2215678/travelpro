<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PackageInventory extends Model {

	protected $table = 'package_inventory';

	protected $guarded = ['id'];

    protected $fillable = [];
    /**
     * @return BelongsTo
     */
    public function packageSlot(): BelongsTo
    {
        return $this->belongsTo('App\Models\PackageSlot', 'slot_id');
    }

}