<?php
namespace App\Http\Controllers\js;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogToCategories;
use App\Models\BannerImageGallery;
use App\Models\SeoMetaTag;
use App\Models\Package;
use App\Models\UrlRedirection;
//use App\Models\BlogTag;
use App\Helpers\CustomHelper;
use DB;
use Storage;
use Inertia\Inertia;
use Response;


class BlogController extends Controller
{
    private $limit;
    private $theme;
    public function __construct(){
        $this->limit = 40;
        //$this->limit = CustomHelper::WebsiteSettings('FRONT_PAGE_LIMIT');
        $this->theme = config('custom.themejs');
    }

    public function index(Request $request) {
        $currentUrl = \Request::path();
        $url_redirection = UrlRedirection::where('source_url',$currentUrl)->where('show',1)->first();
        if(!empty($url_redirection) && !empty($url_redirection->destination_url)) {
            $redirectUrl = url($url_redirection->destination_url);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirectUrl);
            exit();
        }
        $data = [];
        $seo_data = [];
        $limit = $this->limit;

        $meta_title = '';
        $meta_description = '';
        $meta_keyword = '';
        $breadcrumb_title = '';

        //$seo_tags = CustomHelper::getSeoModules('blog_listing');

        $segments1 = $request->segment(1);
        $query = SeoMetaTag::where('status',1);
        $query->where(function($q1) use($segments1) {
            $q1->where('page_url',$segments1);
        });
        $seo_tags = $query->first();
        $banner_image = '';
        $identifier = '';
        if(!empty($seo_tags)) {
            $identifier = $seo_tags->identifier;
            $seo_data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
            $seo_data['page_url_label'] = $seo_tags->page_url_label??'';
            $seo_data['page_brief'] = $seo_tags->page_brief??'';
            $seo_data['page_description'] = $seo_tags->page_description??'';
            $seo_data['page_url'] = $seo_tags->page_url??'';
            $seo_data['page_detail_url'] = $seo_tags->page_detail_url??'';
            $seo_data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
            $seo_data['meta_description'] = $seo_tags->meta_description??'';
            $seo_data['meta_keyword'] = $seo_tags->meta_keyword??'';
            $seo_data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
            
            if($seo_tags->image) {
                $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
            } else {
                $banner_image = asset(config('custom.assets').'/images/default_common_banner.jpg');
            }
            $breadcrumb_title = $seo_tags->page_url_label??'Blogs';
            $meta_title = $seo_data['meta_title'];
            $meta_description = $seo_data['meta_description'];
            $meta_keyword = $seo_data['meta_keyword'];            
        }
        $seo_data['banner_image'] = $banner_image;
        // prd($banner_image);

        $search = (isset($request->search))?$request->search:'';
        $category = (isset($request->category))?$request->category:'';

        $dataType = 'blogs';
        if($identifier == 'news_listing') {
            $dataType = 'news';
        }

        $blog_query = Blog::select('blogs.*')->where('blogs.status',1)->where('blogs.type',$dataType)->orderBy('blogs.blog_date','DESC')->orderBy('blogs.featured','DESC');
        $blog_query->where(function($query1){
            $query1->where('is_deleted',0);
            $query1->orWhereNull('is_deleted');
        });

        $blog_category_data = BlogCategory::where('status',1)->where('type',$dataType)->get();
        $data['blog_category_data'] = $blog_category_data;
        
        if(isset($category) && !empty($category)){
            $category_data = BlogCategory::where('slug',$category)->first();
            if($category_data && $category_data->id){
                $category_id = $category_data->id ?? '';
                $blogArr = BlogToCategories::where('category_id',$category_id)->pluck('blog_id')->toArray();
                $blog_query->whereIn('id',$blogArr);
                $data['category_id'] = $category_id;

                $meta_title = (!empty($category_data->meta_title))?$category_data->meta_title:$category_data->name??'';
                $meta_description = $category_data->meta_description??'';
                $meta_keyword = $category_data->meta_keyword??'';
            }else{
                abort(404);
            }
        }

        if($request->categories) {
            if(is_array($request->categories)) {
                $categories_arr = $request->categories;
            } else {
                $categories_arr = explode(',', $request->categories);
            }
            $category_data = BlogCategory::where('id',$categories_arr[0])->first();
            if(!empty($categories_arr)) {
                $blog_query->whereHas('blogToCategory',function($q1) use($categories_arr) {
                    $q1->whereIn('category_id',$categories_arr);
                });
            }
            $meta_title = (!empty($category_data->meta_title))?$category_data->meta_title:$category_data->name;
            $meta_description = $category_data->meta_description??'';
            $meta_keyword = $category_data->meta_keyword??'';
        }

        if($search !='') {
            $blog_query->where('blogs.title', 'like', '%'.$search.'%')->orWhere('blogs.content', 'like', '%'.$search.'%')->orWhere('blogs.brief', 'like', '%'.$search.'%');
        }
        $blogs = $blog_query->paginate($limit);
        $category_slug = $category ?? '';

        $allBlogsCat = [];
        if($blog_category_data){
                foreach($blog_category_data as $blog_category) {
                    $blogsListing = route('blogsListing',[$blog_category->slug]);
                    if($dataType == 'news'){
                        $blogsListing = route('newsListing',[$blog_category->slug]);
                    }
                 $allBlogsCat[] = [
                    'category_name' => $blog_category->name??'',
                    'slug' => $blog_category->slug??'',
                    'cat_url' => route('blogsListing',[$blog_category->slug]) ,
                ];
            }
        }

        $links = '';
        $allBlogs = [];
        if($blogs) {
            foreach($blogs as $blog) {
                $allBlogs[] = Blog::parseBlog($blog);
            }
        }
        $seo_data['meta_title'] = $meta_title ?? '';
        $seo_data['meta_description'] = $meta_description ?? '';
        $seo_data['meta_keyword'] = $meta_keyword ?? '';
        $data['categories'] = $allBlogsCat;

        $params = [];
        $search_data = CustomHelper::getSearchData('blog_listing',$params);
        $blog_listing = CustomHelper::getSeoModules('blog_listing');
        $blogsListing = $blog_listing->page_url??'blog';
        if($category_slug){
            $blogs->withPath($blogsListing.'/'.$category_slug);
        }else{
            
        $blogs->withPath($blogsListing);
        }
        $results = [];
        $results['data'] = $allBlogs;
        $results['links'] = $blogs->appends($search_data)->links('vendor.pagination.bootstrap-4')->render();
        $data['results'] = $results;

        $data['category_slug'] = $category_slug;
        $data['category_name'] =  BlogCategory::where('slug',$category_slug)->first()->name??'';
        $breadcrumbData = [];
        $BLOG_VUE_JS = ['viaggindia'];
        if(in_array(config('custom.theme_name'), $BLOG_VUE_JS)) {
            $breadcrumbData[] = ['url'=>'/','label'=>'Casa'];
        } else {
            $breadcrumbData[] = ['url'=>'/','label'=>'Home'];
        }
        if($data['category_slug'] && $data['category_name']){
            $breadcrumbData[] = ['url'=>route('blogsListing'),'label'=>$breadcrumb_title];
            $breadcrumbData[] = ['url'=>'','label'=>$data['category_name']];
        } else {
            $breadcrumbData[] = ['url'=>'','label'=>$breadcrumb_title];
        }
        $data['breadcrumbData'] = $breadcrumbData;
        $data['seo_data'] = $seo_data;
View::share('seo_data', $seo_data);
        return Inertia::render('blogs/index', $data);
    }

    public function ajaxSearchBlog(Request $request) {
        $response = [];
        $seo_data = [];
        $response['success'] = false;
        $response['message'] = '';
        $type = $request->type??'blogs';
        $featured = $request->featured??0;
        $limit = $request->limit??10;
        $category_id = $request->category_id??0;
        if($request->category_slug) {
            $category_id = BlogCategory::where('slug',$request->category_slug)->first()->id??0;
        }
        $query = Blog::where('status',1)->orderBy('sort_order','desc')->orderBy('featured','desc');
        $query->where(function($q1) {
            $q1->where('is_deleted', 0);
            $q1->orWhereNull('is_deleted');
        });
        if($type && $type != 'all') {
            $query->where('type',$type);
        }
        if($request->featured) {
            $query->where('featured',$request->featured);
        }

        if($category_id) {
            if(is_array($category_id)) {
                $categories_arr = $category_id;
            } else {
                $categories_arr = [];
                $categories_arr[] = $category_id;
            }
            $query->whereHas('blogToCategory',function($q1) use($categories_arr) {
                $q1->whereIn('category_id',$categories_arr);
            });
        }
        $blogs = $query->paginate($limit);
        $blogs_arr = [];
        foreach ($blogs as $blog) {
            $blogs_arr[] = Blog::parseBlog($blog);
        }
        $search_data = [];
        $results = [];
        $results['data'] = $blogs_arr;
        $results['links'] = $blogs->appends($search_data)->links('vendor.pagination.bootstrap-4')->render();
        $data['results'] = $results;

        $data['success'] = true;
        return Response::json($data, 200);
    }

    public function details(Request $request) {
        $currentUrl = \Request::path();
        $url_redirection = UrlRedirection::where('source_url',$currentUrl)->where('show',1)->first();
        if(!empty($url_redirection) && !empty($url_redirection->destination_url)) {
            $redirectUrl = url($url_redirection->destination_url);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirectUrl);
            exit();
        }
        $data = [];
        $seo_data = [];
        $slug = (isset($request->slug))?$request->slug:'';
        $category_slug = (isset($request->category_slug))?$request->category_slug:'';
        $limit = $this->limit;
        if(isset($slug) && !empty($slug)) {
            $query = Blog::where('slug',$slug)->where('status',1);
            $query->where(function($query1) {
                $query1->where('is_deleted',0);
                $query1->orWhereNull('is_deleted');
            });
            $blogDetails = $query->first();
            if(isset($blogDetails) && !empty($blogDetails)) {
                $blog_id = $blogDetails->id??'';
                $data['blogDetails'] = $blogDetails;

                $breadcrumb_title = '';

                $seo_tags = CustomHelper::getSeoModules('blog_listing');
                $banner_image = '';
                $identifier = '';
                if(!empty($seo_tags)) {
                    $identifier = $seo_tags->identifier;
                    $seo_data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
                    $seo_data['page_url_label'] = $seo_tags->page_url_label??'';
                    $seo_data['page_brief'] = $seo_tags->page_brief??'';
                    $seo_data['page_description'] = $seo_tags->page_description??'';
                    $seo_data['page_url'] = $seo_tags->page_url??'';
                    $seo_data['page_detail_url'] = $seo_tags->page_detail_url??'';
                    $seo_data['meta_title'] = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:$seo_tags->page_title;
                    $seo_data['meta_description'] = $seo_tags->meta_description??'';
                    $seo_data['meta_keyword'] = $seo_tags->meta_keyword??'';
                    $seo_data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
                    if($seo_tags->image) {
                        $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
                    }
                    $breadcrumb_title = $seo_tags->page_url_label??'Blogs';
                    $meta_title = $seo_data['meta_title'];
                    $meta_description = $seo_data['meta_description'];
                    $meta_keyword = $seo_data['meta_keyword'];            
                }
                $seo_data['banner_image'] = $banner_image;

                $data['page_title'] = $seo_tags->page_title??'';

                $meta_title = $blogDetails->meta_title??'';
                $meta_description = $blogDetails->meta_description??'';
                $meta_keyword = $blogDetails->meta_keyword??'';
                if(empty($meta_title)) {
                    $meta_title = $blogDetails->title;
                }
                $seo_data['meta_title'] = $meta_title;
                $seo_data['meta_description'] = $meta_description;
                $seo_data['meta_keyword'] = $meta_keyword;

                $data['page_heading'] = $blogDetails->title;

                $category_id = $blogDetails->category_id ?? '';
                $blogToCategory  =  isset($blogDetails->blogToCategory) ?  $blogDetails->blogToCategory : [];
                $blogCatArr = $blogToCategory->pluck('id')->toArray();
                $blogArr = BlogToCategories::whereIn('category_id',$blogCatArr)->where('blog_id','!=',$blog_id)->pluck('blog_id')->toArray();
                $data['category_slug'] = $category_slug;
                $data['category_name'] =  BlogCategory::where('slug',$category_slug)->first()->name??'';
                $relatedBlogsObj = Blog::whereIn('id',$blogArr)->where('status',1)->where('id','!=',$blog_id)->where(function($query1){
                    $query1->where('is_deleted',0);
                    $query1->orWhereNull('is_deleted');
                })->orderBy('blogs.blog_date','asc')->orderBy('blogs.featured','desc')->limit($limit)->get();
                $similar_blog = [];
                if($relatedBlogsObj){
                    foreach ($relatedBlogsObj as $rblogs) {
                        $similar_blog[] = Blog::parseBlog($rblogs);
                    }
                }
                $data['relatedBlogsObj'] = $similar_blog;

                $package_data = Package::where('status',1)->where('featured',1)->where('is_activity',0)->where(function($query1){
                    $query1->where('is_deleted',0);
                    $query1->orWhereNull('is_deleted');
                })->orderBy('sort_order','asc')->orderBy('featured','desc')->limit($limit)->get();
                $explore_package = [];
                if($package_data){
                    foreach ($package_data as $package) {
                        $explore_package[] = Package::parsePackage($package);
                    }
                }
                $data['explore_package'] = $explore_package;

                $allBlogsCat = [];
                if($blogToCategory){
                    foreach($blogToCategory as $blog_category) {
                        $allBlogsCat[] = [
                            'category_name' => $blog_category->name??'',
                            'slug' => $blog_category->slug??'',
                            'cat_url' => route('blogsListing',[$blog_category->slug]) ,
                        ];
                    }
                }
                $blog_arr = [];
                $storage = Storage::disk('public');
                $blog_path = "blogs/";
                $blog_thumb_path = "blogs/thumb/";

                $blogthumbSrc = asset(config('custom.assets').'/images/noimage.jpg');
                $blogSrc = asset(config('custom.assets').'/images/noimage.jpg');

                $image = $blogDetails->image??'';
                if(!empty($image) && $storage->exists($blog_thumb_path.$image)){
                    $blogthumbSrc = asset('/storage/'.$blog_thumb_path.$image);
                }
                if(!empty($image) && $storage->exists($blog_path.$image)){
                    $blogSrc = asset('/storage/'.$blog_path.$image);
                }
                $BLOG_VUE_JS = ['viaggindia'];
                if(in_array(config('custom.theme_name'), $BLOG_VUE_JS)) {
               $blog_date = !empty($blogDetails->blog_date) ? CustomHelper::DateFormat($blogDetails->blog_date,'d/m/Y') : '';
                } else {
                     $blog_date = !empty($blogDetails->blog_date) ? CustomHelper::DateFormat($blogDetails->blog_date,'d M Y') : '';
                }

                $blogDetailsData = [
                    'title' => $blogDetails->title??'',
                    'author' => $blogDetails->post_by??'',
                    'blog_date' => $blog_date??'',
                    'brief' => $blogDetails->brief??'',
                    'description' => CustomHelper::parseOutput($blogDetails->content??''),
                    'blogthumbSrc' => $blogthumbSrc,
                    'blogSrc' => $blogSrc,
                    'url' => route('blogsDetail',['slug'=>$blogDetails->slug]),
                ];
                $data['blogDetails'] = $blogDetailsData;
                $data['categories'] = $allBlogsCat;
                
                $breadcrumbData = [];
                if(in_array(config('custom.theme_name'), $BLOG_VUE_JS)) {
                $breadcrumbData[] = ['url'=>'/','label'=>'Casa'];
                } else {
                $breadcrumbData[] = ['url'=>'/','label'=>'Home'];
                }

                $breadcrumbData[] = ['url'=>route('blogsListing'),'label'=>$breadcrumb_title];
                // if($data['category_slug'] && $data['category_name']){
                //     $breadcrumbData[] = ['url'=>route('blogsListing',[$data['category_slug']]),'label'=>$data['category_name']];
                // }

                if(isset($data['categories']) && !empty($data['categories'])){
                    $breadcrumbData[] = ['url'=>route('blogsListing',[$data['categories'][0]['slug']]),'label'=>$data['categories'][0]['category_name']];
                }

                $breadcrumbData[] = ['label'=>$blogDetails->title];
                $data['breadcrumbData'] = $breadcrumbData;
                $data['seo_data'] = $seo_data;
                View::share('seo_data', $seo_data);
                // prd($data['blogDetails']);
                return Inertia::render('blogs/details', $data);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

}
