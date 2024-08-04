<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamManagement extends Model
{
    protected $table = 'team_management';

    protected $guarded = ['id'];

    public $timestamps = true;

    protected $fillable = [
        'gender',
        'title',
        'sub_title',
        'slug',
        'designation',
        'category',
        'trip_theme',
        'brief_profile',
        'detail_profile',
        'email',
        'alt_email',
        'mobile_no',
        'facebook_link',
        'twitter_link',
        'image',
        'sort_order',
        'featured',
        'status'
    ];
 
    public function expertPackages()
    {
        return $this->belongsToMany('App\Models\Package', 'package_team_members', 'member_id', 'package_id');
    }

    public static function teamMembersDelete($id)

    {
        try {
            $data = TeamManagement::where('id', $id)->first();
            $name = $data->first_name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Team Management Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Team Management Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Team Management is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }

}