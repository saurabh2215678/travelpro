<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model{
    
    protected $table = 'blog_categories';

    protected $guarded = ['id'];

    protected $fillable = [
        'parent_id',
        'name',
        'type',
        'slug',
        'sort_order',
        'status',
        'hot_categories',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'created_at',
        'updated_at'
    ];

    //public $timestamps = false;

    public function Blogs() {
        return $this->hasMany('App\Models\BlogToCategories','category_id','id');
    }

    /**
     * Sub-Categories of this Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(){
        return $this->hasMany('App\Models\BlogCategory', 'parent_id');
    }

    /**
     * Parent Category of this Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(){
        return $this->belongsTo('App\Models\BlogCategory', 'parent_id');
    }
    public static function blogCategoryDelete($id)

    {
        try {
            $data = BlogCategory::where('id', $id)->first();
            $name = $data->name;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Blog Category Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Blog Category Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Blog Category is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }

    function BlogsCat()
    {
        return $this->hasMany('App\Models\BlogToCategories', 'category_id', 'id');
    }
}