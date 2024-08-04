<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
    protected $guarded = array();

    public function userPermission(){
        return $this->hasOne('App\Models\Permission', 'user_id');
    }
    /**
     * @return BelongsTo
     */
    public function roles(): BelongsTo
    {
        return $this->belongsTo('App\Models\Role', 'role_id')->where('id','!=',1);
    }
    public static function adminuserPermissionDelete($id)

    {
        try {
            $data = Admin::where('id', $id)->first();
            $name = $data->name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'User Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'User Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This User is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }
}
