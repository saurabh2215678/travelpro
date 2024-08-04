@component('admin.layouts.main')

@slot('title')
Admin - Blog View Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php

$title = (isset($blog->title))?$blog->title:'';
$categoryArr  =  isset($blog->category_id) ? explode(',', $blog->category_id) : 0;
$post_by = (isset($blog->post_by))?$blog->post_by:'';
$brief = (isset($blog->brief))?$blog->brief:'';
$source_title = (isset($blog->source_title))?$blog->source_title:'';
$source_url = (isset($blog->source_url))?$blog->source_url:'';
$add_media = (isset($blog->add_media))?$blog->add_media:'';
$post_title_url = (isset($blog->post_title_url))?$blog->post_title_url:'';
$content = (isset($blog->content))?$blog->content:'';
$slug = (isset($blog->slug))?$blog->slug:0;
$sort_order = (isset($blog->sort_order))?$blog->sort_order:0;
$meta_title = (isset($blog->meta_title))?$blog->meta_title:'';
$meta_keyword = (isset($blog->meta_keyword))?$blog->meta_keyword:'';
$meta_description = (isset($blog->meta_description))?$blog->meta_description:'';
$featured = (isset($blog->featured))?$blog->featured:0;
$show_comments = (isset($blog->show_comments))?$blog->show_comments:'';
$allow_comments = (isset($blog->allow_comments))?$blog->allow_comments:'';
$status = (isset($blog->status))?$blog->status:1;
$image = (isset($blog->image))?$blog->image:1;
$blog_date = (isset($blog->blog_date))?$blog->blog_date:'';
$date = CustomHelper::DateFormat($blog_date, 'd/m/Y');
$created_at = (isset($category->created_at))?$category->created_at:0;

$storage = Storage::disk('public');
$path = 'blogs/';

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/blogs';
}

$BlogCategoryObj = "";
if(!empty($categoryArr)){
    #\DB::enableQueryLog();
    $BlogCategoryObj = App\Models\BlogCategory::whereIn('id',$categoryArr)->get();
    #dd(\DB::getQueryLog());
}

$blog_category = $blog->blogToCategory->pluck('name')->toArray()??[];

$active_menu = "blogs";
$back_url = CustomHelper::BackUrl();
?>

@if(!empty($id))
    @include('admin.includes.blogmenu')
@endif

<div class="top_title_admin">
<div class="title">
<h2>{{ $page_heading }}</h2>
</div>
<div class="add_button">

@if(CustomHelper::checkPermission('blogs','edit'))
<a  <?php if($active_menu=='blogs/blog_view/'.$id){echo 'class="active"' ;}?>  href="{{ route($routeName.'.blogs.edit', ['id'=>$id, 'type'=>$type, 'back_url'=>$back_url]) }}" class="btn_admin"><i class="fas fa-edit"  title="Edit SEO Meta"></i>Edit Blog</a>
@endif

</div>
</div>

<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="ajax_msg"></div>
<table border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td width="806" valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">

              <tr>
                <td>
                    <div><b>Title: </b></div>
                    <div>{{$blog->title}}</div>
                </td>
                <td>
                    <div><b>Post By: </b></div>
                    <div>{{$blog->post_by}}</div>
                </td>
                <td>
                    <div><b>Brief: </b></div>
                    <div>{!! $blog->brief !!}</div>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <div><b>Content: </b></div>
                    <div>{!! $blog->content !!}</div>
                </td>
             </tr>

            <tr>
                <td>
                    <div><b>Display Order: </b></div>
                    <div>{{$blog->sort_order}}</div>
                </td>
                <td>
                    <div><b>Featured: </b></div>
                    <div>{{CustomHelper::getStatusStr($blog->featured)}}</div>
                </td>
                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($blog->status) }}</div>
                </td>
            </tr>

            {{--<tr>
                <td>
                    <div><b>Meta Title: </b></div>
                    <div>{{$blog->meta_title}}</div>
                </td>
                <td>
                    <div><b>Meta Keyword: </b></div>
                    <div>{{$blog->meta_keyword}}</div>
                </td>

                <td>
                    <div><b>Meta Description: </b></div>
                    <div>{{$blog->meta_description}}</div>
                </td>
            </tr>--}}

             <tr>
                <td>
                    <div><b>Image: </b></div>
                    <div> <?php
                    if(!empty($image)){
                        if($storage->exists($path.$image)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?></div>
                </td>
                <td>
                    <div><b>Blog Date: </b></div>
                    <div>{{CustomHelper::DateFormat($blog->blog_date, 'd/m/Y')}}</div>
                </td>

                <td>
                    <div><b>Date Created: </b></div>
                    <div>{{CustomHelper::DateFormat($blog->created_at, 'd/m/Y')}}</div>
                </td>
                
            </tr>

             <tr>
                <td>
                    <div><b>Slug: </b></div>
                    <div>{{$blog->slug}}</div>
                </td>

                 <td>
                    <div><b>Show Comments: </b></div>
                    <div>{{ CustomHelper::getStatusStr($blog->show_comments) }}</div>
                </td>

                 <td>
                    <div><b>Allow Comments: </b></div>
                    <div>{{ CustomHelper::getStatusStr($blog->allow_comments) }}</div>
                </td>

            </tr>

            <tr>
                <td colspan="3">
                    <div><b>Source Title: </b></div>
                    <div>{!! $blog->source_title !!}</div>
                </td>
             </tr>

             <tr>
                <td colspan="3">
                    <div><b>Source Url: </b></div>
                    <div>{!! $blog->source_url !!}</div>
                </td>
             </tr>

             <tr>
                <td colspan="3">
                    <div><b>Add Media: </b></div>
                    <div>{!! $blog->add_media !!}</div>
                </td>
             </tr>

             <tr>
                <td colspan="3">
                    <div><b>Post Title Url: </b></div>
                    <div>{!! $blog->post_title_url !!}</div>
                </td>
             </tr>

             <tr>
                <td colspan="3">
                    <div><b>Category: </b></div>
                    <div> <?php if ($blog_category) {
                        echo implode(', ',$blog_category );
                        
                    } ?>
                    </div>
                </td>
             </tr>
            
</table>
</div>
</div>
@slot('bottomBlock')
@endslot

@endcomponent