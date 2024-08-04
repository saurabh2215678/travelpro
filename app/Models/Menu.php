<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model{
    
    protected $table = 'menus';

    protected $guarded = ['id'];

    protected $fillable = [];


    public function menuItems(){
    	return $this->hasMany('App\Models\MenuItem', 'menu_id')->orderBy('sort_order')->with('children');
    }


    public function menuParentItems(){
    	return $this->hasMany('App\Models\MenuItem', 'menu_id')->with('children')->where(function ($query) {
            $query->where('parent_id',0);
        })->orderBy('sort_order');
    }


    public static function menuItemsDelete($id)

    {
        try {
            $data = Menu::where('id', $id)->first();
            $name = $data->title;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Menu Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Menu Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Menu is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }
    

}