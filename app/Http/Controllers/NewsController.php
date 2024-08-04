<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Helpers\CustomHelper;
//use App\Models\BlogTag;

class NewsController extends Controller
{

    private $limit;
    public function __construct(){
        $this->limit = 10;
    }

    public function index(Request $request){

        $data = [];

        $data['page_title'] = "Blogs";
        $data['page_heading'] = "Blogs";

        $limit = $this->limit;
        $category = (isset($request->category))?$request->category:'';
        $search = (isset($request->search))?$request->search:'';
        //$tag = (isset($request->tag))?$request->tag:'';

        $blog_query = Blog::orderBy('blogs.created_at','desc');
        if($search !='')
        {
            $blog_query->where('blogs.title', 'like', '%'.$search.'%')->orWhere('blogs.content', 'like', '%'.$search.'%')->orWhere('blogs.brief', 'like', '%'.$search.'%');
        }

        if(!empty($category)){
            $blogCat = BlogCategory::where('slug',$category)->first();
            if(!empty($blogCat)){
                $sCategoryId = $blogCat->id;
                $blog_query->leftJoin('blog_to_categories as bc', function($join) {
                    $join->on('bc.blog_id', '=', 'blogs.id');
                })->where('bc.category_id',$sCategoryId);
            }
        }

        // if(!empty($tag)){
        //     $blogTag = BlogTag::where('slug',$tag)->first();
        //     if(!empty($blogTag)){
        //         $sTagId = $blogTag->id;
        //         $blog_query->leftJoin('tags_to_blog as tb', function($join) {
        //             $join->on('tb.blog_id', '=', 'blogs.id');
        //         })->where('tb.tag_id',$sTagId);
        //     }
        // }

        $blog_query = Blog::where(['status'=>1,'type'=>'news']);
        $blog_query->orderBy('created_at', 'desc');
        $blogs = $blog_query->paginate($limit);

        $data['meta_title'] = 'Whats & News-Grand India Tour';
        $data['meta_description'] = 'Whats & News-Grand India Tour';
        $data['meta_keyword'] = 'Whats & News-Grand India Tour';
        $data['blogs'] = $blogs;

        // Add CMS Code
        $segments1 = $request->segment(1);
        if(!empty($segments1)){
           $page_name = $segments1;
           $select_cols = '*';
           $cms_data = CustomHelper::getCMSPage($page_name, $select_cols);
           //prd($cms_data);
           if(isset($cms_data['cms']) && !empty($cms_data)){
               $meta_title = (isset($cms_data['meta_title']))?$cms_data['meta_title']:$cms_data['name'];
               $meta_description = (isset($cms_data['meta_description']))?$cms_data['meta_description']:'';
               $meta_keyword = (isset($cms_data['meta_keyword']))?$cms_data['meta_keyword']:'';
               if(empty($meta_title)){
                   $meta_title = (isset($cms_data['title']))?$cms_data['title']:'';
               }
               if(empty($meta_description)){
                   $meta_title = (isset($cms_data['meta_description']))?$cms_data['meta_description']:'';
               }
               if(empty($meta_keyword)){
                   $meta_title = (isset($cms_data['meta_keyword']))?$cms_data['meta_keyword']:'';
               }
               $data['meta_title'] = $meta_title;
               $data['meta_description'] = $meta_description;
               $data['meta_keyword'] = $meta_keyword;
               $data['cms'] = $cms_data;
           }
           else{
                abort(404);
           }
        }

        return view(config('custom.theme').'.news.index', $data);
    }

    public function details(Request $request){

        $data = [];

        $slug = (isset($request->slug))?$request->slug:'';
        if(!empty($slug)){
            $blogDetails = Blog::where('slug',$slug)->where('status',1)->first();
            if(!empty($blogDetails)){
                $data['meta_title'] = isset($blogDetails->meta_title)?$blogDetails->meta_title:$blogDetails->title;
                $data['meta_description'] = isset($blogDetails->meta_description)?$blogDetails->meta_description:'';
                $data['meta_keyword'] = isset($blogDetails->meta_keyword) ? $blogDetails->meta_keyword:'';
                $data['page_heading'] = $blogDetails->title;

                $data['blogDetails'] = $blogDetails;
                $data['latestBlogs'] = Blog::where('status',1)->orderBy('blogs.created_at','desc')->limit(5)->get();

                return view(config('custom.theme').'.news.details', $data);
            }else{
                abort(404);
            }
        }else{
            abort(404);
        }
    }

}
