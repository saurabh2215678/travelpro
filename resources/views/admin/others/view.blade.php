@component('admin.layouts.main')

@slot('title')
Admin - Other Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
// $BackUrl = (request()->has('back_url'))?request()->input('back_url'):'';
// $routeName = CustomHelper::getAdminRouteName();

$id = (isset($other->id))?$other->id:'';
$title = (isset($other->title))?$other->title:'';
$brief = (isset($other->brief))?$other->brief:'';
$url_link = (isset($other->url_link))?$other->url_link:'';
$image = (isset($other->image))?$other->image:'';
$status = (isset($other->status))?$other->status:'';

$storage = Storage::disk('public');
$path = 'others/';

$old_image = $image;
$image_req = '';
$link_req = '';

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/others';
}
?>

<div class="top_title_admin">
<div class="title">
<h2>{{ $page_heading }}</h2>
</div>
<div class="add_button">
<?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>

<a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
Back</a><?php } ?>
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
                    <div>{{$other->title}}</div>
                </td>
                <td>
                    <div><b>Brief: </b></div>
                    <div>{{$other->brief}}</div>
                </td>
                <td>
                    <div><b>Url/Link: </b></div>
                    <div>{{$other->url_link}}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($other->status) }}</div>
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
                    <div>{{ CustomHelper::DateFormat($other->created_at, 'd/m/Y') }}</div>
                </td>
            </tr>
</table>
</div>
</div>
@slot('bottomBlock')
@endslot

@endcomponent