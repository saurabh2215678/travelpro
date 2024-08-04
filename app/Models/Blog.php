<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Helpers\CustomHelper;
use Storage;

class Blog extends Model {
    protected $table = 'blogs';

    protected $guarded = ['id'];

    protected $fillable = [
        'category_id',
        'title',
        'type',
        'post_by',
        'slug',
        'brief',
        'source_title',
        'source_url',
        'add_media',
        'post_title_url',
        'content',
        'image',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'featured',
        'blog_date',
        'posted_by',
        'show_comments',
        'allow_comments',
        'status',
        'created_at',
        'updated_at'
    ];

    public $timestamps = false;

    public function Images() {
        return $this->hasMany('App\Models\BlogImage', 'blog_id');
    }

    function Category() {
        return $this->belongsTo('App\Models\BlogCategory', 'category_id');
    }

    /**
     * @return BelongsToMany
     */
    function blogToCategory(): BelongsToMany {
      return $this->belongsToMany('App\Models\BlogCategory', 'blog_to_categories', 'blog_id', 'category_id');
    }

    public function blogTags() {
        return $this->belongsToMany('App\Models\Tag', 'blog_tags', 'blog_id', 'tag_id');
    }

    public static function blogTagDelete($id) {
        try {
            $data = Blog::where('id', $id)->first();
            $cat_data = BlogToCategories::where('blog_id', $id)->delete();
            $name = $data->title;
            if (!empty($data)) {
                $is_deleted = $data->delete();
                if ($is_deleted) {
                    return ['status' => 'ok', 'message' => $name . 'Blog Has Been Deleted..!', 'name' => $name];
                }
            } else {
                return ['status' => 'error', 'message' => 'Blog Not Found..!'];
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'This Blog is in use, you cannot delete it.'];
            } else {

                return ['status' => 'error', 'message' => 'Something went wrong. try after some time or contact to administrator..!'];
            }

        }

    }

    /**
     * @return HasMany
     */
    function BlogsCat() : HasMany {
        return $this->hasMany('App\Models\BlogToCategories', 'blog_id', 'id');
    }

    public static function parseBlog($blog,$params=[]) {
        if($blog && $blog->id) {
            $size = $params['size']??'box';
            $search_data = $params['search_data']??[];
            $storage = Storage::disk('public');

            $tImage = $blog->image;
            $path = 'blogs/';
            $thumbPath = 'blogs/thumb/';
            $blogSrc = asset(config('custom.assets').'/images/noimage.jpg');
            $blogThumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
            if(!empty($tImage)){
                if($storage->exists($path.$tImage)){
                    $blogSrc = asset('/storage/'.$path.$tImage);
                }
                if($storage->exists($thumbPath.$tImage)){
                    $blogThumbSrc = asset('/storage/'.$thumbPath.$tImage);
                }
            }
            $BLOG_VUE_JS = ['viaggindia'];
            if(in_array(config('custom.theme_name'), $BLOG_VUE_JS)) {
            $blog_date = !empty($blog->blog_date) ? CustomHelper::DateFormat($blog->blog_date,'d/m/Y') : '';
            } else {
            $blog_date = !empty($blog->blog_date) ? CustomHelper::DateFormat($blog->blog_date,'d M Y') : '';
            }


            if($blog->type == 'news') {
                $blogsDetailUrl = route('newsDetail',['slug'=>$blog->slug]);
            } else{
                $blogsDetailUrl = route('blogsDetail',['slug'=>$blog->slug]);
            }

            $blog = [
                'title' => CustomHelper::wordsLimit($blog->title),
                'brief' => CustomHelper::wordsLimit($blog->brief),
                'blogSrc' => $blogSrc,
                'blogThumbSrc' => $blogThumbSrc,
                'url' => $blogsDetailUrl??'',
                'author' => $blog->post_by??'',
                'blog_date' => $blog_date??'',
            ];

            if($size == 'listing' || $size == 'detail') {

            }
            if($size == 'detail') {

            }
            return $blog;
        }
    }
}