<style>
    .top_title_admin.fancybox-content {
    width: 800px;
    height: 500px;
}
</style>
<?php
$name = (isset($category->name))?$category->name:'';
$slug = (isset($category->slug))?$category->slug:'';
$content = (isset($category->content))?$category->content:'';
$meta_title = (isset($category->meta_title))?$category->meta_title:'';
$meta_keyword = (isset($category->meta_keyword))?$category->meta_keyword:'';
$meta_description = (isset($category->meta_description))?$category->meta_description:'';
$status = (isset($category->status))?$category->status:1;
$hot_categories = (isset($category->hot_categories))?$category->hot_categories:0;
$sort_order = (isset($category->sort_order))?$category->sort_order:0;
$created_at = (isset($category->created_at))?$category->created_at:0;

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/blogs_categories';
}

?>

<div class="top_title_admin">
<div class="title">
<h2>{{ $page_heading }}</h2>
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
                    <div><b>Name: </b></div>
                    <div>{{$category->name}}</div>
                </td>
                <td>

                <?php if($type == 'blogs'){ ?>
                    <div><b>No. of Blog: </b></div>
                <?php } elseif($type == 'news'){ ?>
                    <div><b>No. of News: </b></div>
                <?php } ?>
                    <div>{{count($category->BlogsCat)}}</div>
                </td>
                <td>
                    <div><b>Meta Title: </b></div>
                    <div>{{$category->meta_title}}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Meta Keyword: </b></div>
                    <div>{{$category->meta_keyword}}</div>
                </td>
                <td>
                    <div><b>Meta Description: </b></div>
                    <div>{{$category->meta_description}}</div>
                </td>
                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($category->status) }}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Sort Order: </b></div>
                    <div>{{$category->sort_order}}</div>
                </td>

                <td>
                    <div><b>Slug: </b></div>
                    <div>{{$category->slug}}</div>
                </td>

                <td>
                    <div><b>Date Created: </b></div>
                    <div>{{CustomHelper::DateFormat($category->created_at, 'd/m/Y')}}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Is Hot Category: </b></div>
                    <div>{{CustomHelper::getStatusStr($category->hot_categories)}}</div>
                </td>

            </tr>

            <tr>
                <td colspan="3">
                    <div><b>Description: </b></div>
                    <div>{!! $category->content !!}</div>
                </td>
             </tr>
</table>
</div>
</div>
