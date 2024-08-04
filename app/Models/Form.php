<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model{
    
    protected $table = 'forms';

    protected $guarded = ['id'];

    protected $fillable = [];

    public function formElements() {
    	return $this->hasMany('App\Models\FormElement', 'form_id')->orderBy('position','asc');
    }

    public static function formsDelete($id) {
        try {
            $data = Form::where('id', $id)->first();
            $name = $data->name ?? '';
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Form Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Form Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Form is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }
}