@component('admin.layouts.main')

@slot('title')
Admin - Downloads Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$id = (isset($download->id))?$download->id:'';
$title = (isset($download->title))?$download->title:'';
$brief = (isset($download->brief))?$download->brief:'';
$image = (isset($download->image))?$download->image:'';
$documents = (isset($download->documents))?$download->documents:'';
$status = (isset($download->status))?$download->status:1;

$storage = Storage::disk('public');
$path = 'downloads/';
$documents_path = 'downloads/documents/';

$old_image = $image;
$old_documents = $documents;
$image_req = '';
$link_req = '';

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/downloads';
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
                    <div>{{$download->title}}</div>
                </td>
                <td>
                    <div><b>Brief: </b></div>
                    <div>{{$download->brief}}</div>
                </td>
                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($download->status) }}</div>
                </td>
            </tr>

            <tr>
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
                    <div>{{ CustomHelper::DateFormat($download->created_at, 'd/m/Y') }}</div>
                </td>
            </tr>
</table>
</div>
</div>
@slot('bottomBlock')
@endslot

@endcomponent