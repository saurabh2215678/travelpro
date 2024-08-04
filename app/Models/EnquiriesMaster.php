<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class EnquiriesMaster extends Model{

    protected $table = 'enquiries_master';

    protected $guarded = ['id'];

    protected $fillable = [
        'parent_id',
        'type',
        'category',
        'description',
        'group_id',
        'name',
        'slug',
        'color_code',
        'color_name',
        'sort_order',
        'status'
    ];

    public function Children(){
        return $this->hasMany('App\Models\EnquiriesMaster', 'parent_id')->where('status',1)->orderBy('name','asc');
    }

    function child(){
        return $this->hasMany('App\Models\EnquiriesMaster', 'parent_id');
    }
    /**
     * @return BelongsTo
     */
    public function GroupData(): BelongsTo
    {
        return $this->belongsTo('App\Models\EnquiriesMasterGroup','group_id');
     }

}