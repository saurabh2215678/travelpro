<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiriesMasterGroup extends Model{

    protected $table = 'enquiries_master_group';

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'status',
        'slug'
    ];

    // public function Children(){
    //     return $this->hasMany('App\Models\EnquiriesMaster', 'parent_id');
    // }

}