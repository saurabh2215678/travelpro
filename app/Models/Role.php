<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Role extends Model {
	protected $table = 'roles';

	protected $guarded = ['id'];

	public $timestamps = false;

	protected $fillable = [
		'name',
		'display_name',
		'description',
	];

	public static function roleDelete($id){
        try {
            $data = Role::where('id', $id)->first();
            $name = $data->name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Role has been deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Role Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Role is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }
}
