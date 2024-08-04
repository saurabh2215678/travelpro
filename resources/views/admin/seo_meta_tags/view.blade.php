@component('admin.layouts.main')

@slot('title')
Admin - Other Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
// $BackUrl = (request()->has('back_url'))?request()->input('back_url'):'';
// $routeName = CustomHelper::getAdminRouteName();

$id = (isset($seoTag->id))?$seoTag->id:'';
$module_config_id = $id;
$page_title = (isset($seoTag->page_title))?$seoTag->page_title:'';
$page_brief = (isset($seoTag->page_brief))?$seoTag->page_brief:'';
$page_description = (isset($seoTag->page_description))?$seoTag->page_description:'';
$page_url_label = (isset($seoTag->page_url_label))?$seoTag->page_url_label:'';
$page_url = (isset($seoTag->page_url))?$seoTag->page_url:'';
$page_detail_url = (isset($seoTag->page_detail_url))?$seoTag->page_detail_url:'';
$identifier = (isset($seoTag->identifier))?$seoTag->identifier:'';
$meta_title = (isset($seoTag->meta_title))?$seoTag->meta_title:'';
$meta_keyword = (isset($seoTag->meta_keyword))?$seoTag->meta_keyword:'';
$meta_description = (isset($seoTag->meta_description))?$seoTag->meta_description:'';
$other_meta_tag = (isset($seoTag->other_meta_tag))?$seoTag->other_meta_tag:'';
$image = (isset($seoTag->image))?$seoTag->image:'';
$status = (isset($seoTag->status))?$seoTag->status:'';

$storage = Storage::disk('public');
$path = 'seo_tags/';

$old_image = $image;
$image_req = '';
$link_req = '';

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/module_config';
}
$active_menu = "module_config";
?>
@if(!empty($module_config_id))
@include('admin.includes.modulemenu')
@endif

<div class="top_title_admin">
<div class="title">
<h2>{{ $page_heading }}</h2>
</div>
<?php /*<div class="add_button">
<?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>

<a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
Back</a><?php } ?>
</div> */ ?>

<div class="add_button">
    @if(CustomHelper::checkPermission('module_config','edit'))
    <a href="{{ route($routeName.'.module_config.save', ['id'=>$seoTag->id,'back_url'=>$back_url]) }}" class="btn_admin" title="Edit Modules"><i class="fas fa-edit"></i>Edit Modules</a>
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
                    <div><b>Identifier: </b></div>
                    <div>{{ $seoTag->identifier }}</div>
                </td>

                <td>
                    <div><b>Page Url: </b></div>
                    <div>{!! $seoTag->page_url !!}</div>
                </td>

                <td>
                    <div><b>Page Detail Url: </b></div>
                    <div>{!! $seoTag->page_detail_url !!}</div>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                   <div><b>Page Url/Label: </b></div>
                    <div>{{ $seoTag->page_url_label }}</div>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <div><b>Page Title: </b></div>
                    <div>{!! $seoTag->page_title !!}</div>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <div><b>Page Brief: </b></div>
                    <div>{!! $seoTag->page_brief !!}</div>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <div><b>Page Description: </b></div>
                    <div>{!! $seoTag->page_description !!}</div>
                </td>
            </tr>

           <tr>
                <td colspan="3">
                    <div><b>Meta Title: </b></div>
                    <div>{{ $seoTag->meta_title }}</div>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <div><b>Meta Keyword: </b></div>
                    <div>{{ $seoTag->meta_keyword }}</div>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <div><b>Meta Description: </b></div>
                    <div>{!! $seoTag->meta_description !!}</div>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                   <div><b>Other Meta Tag: </b></div>
                    <div>{{ $seoTag->other_meta_tag }}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($seoTag->status) }}</div>
                </td>
                <td>
                    <div><b>Image: </b></div>
                    <div><?php
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
                    <div><b>Date Created: </b></div>
                    <div>{{ CustomHelper::DateFormat($seoTag->created_at, 'd/m/Y') }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><b>Date Updated: </b></div>
                    <div>{{ CustomHelper::DateFormat($seoTag->updated_at, 'd/m/Y') }}</div>
                </td>
            </tr>
</table>
</div>
</div>
@slot('bottomBlock')
@endslot
@endcomponent