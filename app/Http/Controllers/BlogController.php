<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogToCategories;
use App\Models\BannerImageGallery;
use App\Models\SeoMetaTag;
use App\Models\Package;
//use App\Models\BlogTag;
use App\Helpers\CustomHelper;
use DB;

class BlogController extends Controller
{
    private $limit;
    public function __construct(){
        $this->limit = 20;
    }

    public function index(Request $request) {
        $data = [];
        $limit = $this->limit;

        //$seo_tags = CustomHelper::getSeoModules('blog_listing');

        $segments1 = $request->segment(1);
        $query = SeoMetaTag::where('status',1);
        $query->where(function($q1) use($segments1) {
            $q1->where('page_url',$segments1);
        });
        $seo_tags = $query->first();
        $identifier = '';
        if(!empty($seo_tags)) {
            $identifier = $seo_tags->identifier??'';
            $data['page_title'] = (!empty($seo_tags->page_title))?$seo_tags->page_title:'';
            $data['page_url_label'] = $seo_tags->page_url_label??'Blogs';
            $data['page_heading'] = $seo_tags->page_title??'';
            $data['page_url'] = $seo_tags->page_url??'';
            $data['page_detail_url'] = $seo_tags->page_detail_url??'';
            $data['page_brief'] = $seo_tags->page_brief??'';
            $data['page_description'] = $seo_tags->page_description??'';
            $meta_title = (!empty($seo_tags->meta_title))?$seo_tags->meta_title:!empty($seo_tags->page_title);
            $meta_description = $seo_tags->meta_description??'';
            $meta_keyword = $seo_tags->meta_keyword??'';
            $data['other_meta_tag'] = $seo_tags->other_meta_tag??'';
        }
        $banner_image = '';
        if(!empty($seo_tags->image)) {
            $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
        }
        $data['banner_image'] = $banner_image;

        $search = (isset($request->search))?$request->search:'';
        // $tag = (isset($request->tag))?$request->tag:'';
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
                //==============
                $data['page_heading'] = BlogCategory::where('slug',$category)->first()->name??'';
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
        // if(!empty($tag)) {
        //     $blog_query->whereHas('tag',function($t1) use($tag){
        //         $t1->where('slug',$tag);
        //     });
        //     $data['page_heading'] = BlogTag::where('slug',$tag)->first()->name??'';
        // }
        $blogs = $blog_query->paginate($limit);
    
        $data['meta_title'] = $meta_title ?? '';
        $data['meta_description'] = $meta_description ?? '';
        $data['meta_keyword'] = $meta_keyword ?? '';
        
        $data['category_slug'] = $category ?? '';
        $data['category_name'] = $category_data->name ?? '';

        $data['blogs'] = $blogs;
        $isMobile = CustomHelper::isMobile();
        $bannerType = 'desktop';
        if($isMobile){
            $bannerType = 'mobile';
        }
        $bannerWhere = [];
        $bannerWhere['page'] = 'blog-news';
        $bannerWhere['status'] = 1;
        $bannerWhere['device_type'] = $bannerType;
        $banners = BannerImageGallery::where($bannerWhere)->orderBy('sort_order')->limit($limit)->get();
        $data['banners'] = $banners;
        $data['segment'] = $segments1;
        return view(config('custom.theme').'.blogs.index', $data);
    }

    public function details(Request $request){

        $data = [];
        $segments1 = $request->segment(1);
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

            // prd($blogDetails);

            if(isset($blogDetails) && !empty($blogDetails)) {
                $blog_id = $blogDetails->id??'';
                $data['blogDetails'] = $blogDetails;

                $query = SeoMetaTag::where('status',1);
                $query->where(function($q1) use($segments1) {
                    $q1->where('page_url',$segments1);
                });
                $seo_tags = $query->first();

                //$seo_tags = CustomHelper::getSeoModules('blog_listing');
                $data['breadcrumb_title'] = $seo_tags->page_url_label??'Blogs';

                $banner_image = '';
                if(!empty($seo_tags->image)) {
                    $banner_image = url('/storage/seo_tags/'.$seo_tags->image);
                }
                $data['page_title'] = $seo_tags->page_title??'';
                $data['page_url'] = $seo_tags->page_url??'';
                $data['page_detail_url'] = $seo_tags->page_detail_url??'';
                $data['banner_image'] = $banner_image;                

                $meta_title = $blogDetails->meta_title??'';
                $meta_description = $blogDetails->meta_description??'';
                $meta_keyword = $blogDetails->meta_keyword??'';
                if(empty($meta_title)) {
                    $meta_title = $blogDetails->title;
                }
                $data['meta_title'] = $meta_title;
                $data['meta_description'] = $meta_description;
                $data['meta_keyword'] = $meta_keyword;

                $data['page_heading'] = $blogDetails->title;

                $category_id = $blogDetails->category_id ?? '';
                $blogToCategory  =  isset($blogDetails->blogToCategory) ?  $blogDetails->blogToCategory : [];
                $blogCatArr = $blogToCategory->pluck('id')->toArray();
                $blogArr = BlogToCategories::whereIn('category_id',$blogCatArr)->where('blog_id','!=',$blog_id)->pluck('blog_id')->toArray();
                $data['category_slug'] = $category_slug;
                $data['category_name'] =  BlogCategory::where('slug',$category_slug)->first()->name??'';
                //prd($blogDetails->Category);
                $relatedBlogsObj = Blog::whereIn('id',$blogArr)->where('status',1)->where('id','!=',$blog_id)->where(function($query1){
                    $query1->where('is_deleted',0);
                    $query1->orWhereNull('is_deleted');
                })->orderBy('blogs.blog_date','asc')->orderBy('blogs.featured','desc')->limit($limit)->get();
                $data['relatedBlogsObj'] = $relatedBlogsObj;
                $data['segment'] = $segments1;

                $data['explore_package'] = Package::where('status',1)->where('featured',1)->where(function($query1){
                    $query1->where('is_deleted',0);
                    $query1->orWhereNull('is_deleted');
                })->orderBy('sort_order','asc')->orderBy('featured','desc')->limit($limit)->get();
                return view(config('custom.theme').'.blogs.details', $data);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    // public function blogCategoryListing(Request $request){

    //     $data = [];
    //     $limit = $this->limit;
    //     $category_slug = isset($request->category_id) ? $request->category_id:'';
    //     if(!empty($category_slug)){

    //         $blogCategory = BlogCategory::where('slug',$category_slug)->where('status',1)->first();
    //         $blogCategoryDetails = Blog::where('category_id',$blogCategory->id)->where('status',1)->get();
    //         if(!empty($blogCategoryDetails)){


    //             // $data['meta_title'] = $blogDetails->meta_title;
    //             // $data['meta_description'] = $blogDetails->meta_description;
    //             // $data['meta_keyword'] = $blogDetails->meta_keyword;
    //             // $data['page_heading'] = $blogDetails->title;

    //             $data['blogCategoryDetails'] = $blogCategoryDetails;
    //             //prd($blogCategoryDetails);
                
    //             return view(config('custom.theme').'.blogs.blog_category', $data);
    //         }else{
    //             abort(404);
    //         }
    //     }else{
    //         abort(404);
    //     }
    // }

}
