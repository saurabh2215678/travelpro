
@component('admin.layouts.main')

@slot('title')
Admin -  - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php

$name = (isset($page->name))?$page->name:'';
$slug = (isset($page->slug))?$page->slug:'';
$title = (isset($page->title))?$page->title:'';
$heading = (isset($page->heading))?$page->heading:'';
$brief = (isset($page->brief))?$page->brief:'';
$template = (isset($page->template))?$page->template:'';
$content = (isset($page->content))?$page->content:'';
$status = (isset($page->status))?$page->status:'';
$cms_type = (isset($page->cms_type))?$page->cms_type:'';

$meta_title = (isset($page->meta_title))?$page->meta_title:'';
$meta_keyword = (isset($page->meta_keyword))?$page->meta_keyword:'';
$meta_description = (isset($page->meta_description))?$page->meta_description:'';
$image = (isset($page->image))?$page->image:'';
$banner_image = (isset($page->banner_image))?$page->banner_image:'';
$document = (isset($page->document))?$page->document:'';
$doc_link = (isset($page->doc_link))?$page->doc_link:'';

$storage = Storage::disk('public');
$path = 'cms_pages/';

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/cms';
}

$active_menu = "cms";
$back_url = CustomHelper::BackUrl();
?>
@if(!empty($id))
@include('admin.includes.cmsmenu')
@endif
<div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
        @if(CustomHelper::checkPermission('cms','edit'))  
        <a  <?php if($active_menu=='cms/view/'.$id){echo 'class="active"' ;}?>  href="{{ route($routeName.'.cms.edit', ['id'=>$id, 'back_url'=>$back_url]) }}" class="btn_admin"><i class="fas fa-edit"  title="Edit SEO Meta"></i>Edit Cms</a>
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
                                <div> {{$page->title}}</div>
                            </td>
                            <td>
                                <div><b>Heading: </b></div>
                                <div>{{$page->heading}}</div>
                            </td>
                            <td>
                                <div><b>Template: </b></div>
                                <div>{!!$page->template!!}</div>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <div><b>Slug: </b></div>
                                <div>{{$page->slug}}</div>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <div><b>Brief: </b></div>
                                <div>{!! $page->brief !!}</div>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <div><b>Content: </b></div>
                                <div>{!! $page->content !!}</div>
                            </td>
                        </tr>

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
                                <?php } ?>
                            </div>
                        </td>
                        <td>
                            <div><b>Banner Image: </b></div>
                            <div><?php
                            if(!empty($banner_image)){
                                if($storage->exists($path.$banner_image)){
                                    ?>
                                    <div class="col-md-2 image_box">
                                        <img src="{{ url('/storage/'.$path.'thumb/'.$banner_image) }}" style="width: 100px;">
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </td>
                    <td>
                        <div><b>Document: </b></div>
                        <div><?php
                        if(!empty($document)){
                            if($storage->exists($path.$document)){
                                ?>
                                <div class="col-md-2 image_box">
                                    <img src="{{ url('/storage/'.$path.'thumb/'.$document) }}" style="width: 100px;">
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Status: </b></div>
                    <div>{{CustomHelper::getStatusStr($page->status)}}</div>
                </td>
                <td>
                    <div><b>CMS Type: </b></div>
                    <div>{{$page->cms_type}}</div>
                </td>
                <td>
                    <div><b>Sort Order: </b></div>
                    <div>{{$page->sort_order}}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Date Created: </b></div>
                    <div>{{ CustomHelper::DateFormat($page->created_at, 'd/m/Y') }}</div>
                </td>
            </tr>
        </table>
    </div>
</div>
@slot('bottomBlock')
@endslot

@endcomponent