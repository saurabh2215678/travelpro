@component('admin.layouts.main')

@slot('title')
Admin - Manage Itinerary View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
    $day = (isset($itenary->day))?$itenary->day:'';
    $day_title = (isset($itenary->day_title))?$itenary->day_title:'';
    $title = (isset($itenary->title))?$itenary->title:'';
    $itenary_inclusions = (isset($itenary->itenary_inclusions))?json_decode($itenary->itenary_inclusions):[];
    $image = (isset($itenary->image))?$itenary->image:'';
    $description = (isset($itenary->description))?$itenary->description:'';
    $status = (isset($itenary->status))?$itenary->status:1;
    $sort_order = (isset($itenary->sort_order))?$itenary->sort_order:0;
    $itenary_tags = isset($itenary->itenaryTags) ? $itenary->itenaryTags:null;

    $itenaryTags = "";
    if(!empty($itenary_tags)){
        $itenaryTags = [];
        foreach ($itenary_tags as  $tag) {
            $itenaryTags[] = $tag->name;
        }
        $itenaryTags = implode(',',$itenaryTags);
    }

    $storage = Storage::disk('public');
    $path = 'itenaries/';

    $old_image = $image;
    $image_req = '';
    $link_req = '';

    $pkgInclusionObj = "";
    if(!empty($itenary_inclusions)){
        $pkgInclusionObj = App\Models\PackageInclusion::whereIn('id',$itenary_inclusions)->get();
    }
    $routeName = CustomHelper::getAdminRouteName();
    //$back_url=CustomHelper::BackUrl(); 
    $back_url = request()->has('back_url') ? request()->input('back_url') : '';

?>
<?php

$active_menu = "packages".$package_id.'/itenaries';

$package_id = isset($package->id)?$package->id:0;
?>
@if(!empty($package))
@include('admin.includes.packageoptionmenu')
@endif
<div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
    @if(CustomHelper::checkPermission('packages','edit'))
    <div class="add_button">
        <a href="{{ route($routeName.'.packages.itenary_edit', ['package_id'=>$package_id,'id'=>$itenary->id,'back_url'=>$back_url]) }}" class="btn_admin"><i class="fas fa-edit"  title="Edit Itinerary"></i>Edit Itinerary</a>
    </div>
    @endif
</div>

<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="alert_msg"></div>

<table  border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td  valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
            
                <tr>
                    <td>
                        <div><b>Day: </b></div>
                        <div>{{$itenary->day}}</div>
                    </td>
                    <td>
                        <div><b>Day Title: </b></div>
                        <div>{{$itenary->day_title}}</div>
                    </td>
                    <td>
                        <div><b>Title: </b></div>
                        <div>{{$itenary->title}}</div>
                    </td>
                </tr>

                <tr>
                    <td colspan="3">
                        <div><b>Tags: </b></div>
                        <div><?php 
                        $itenary_tags = (!empty($itenary) && !($itenary->itenaryTags->isEmpty())) ? $itenary->itenaryTags : "";
                        $itenaryTags = "";
                        if(!empty($itenary_tags)){
                            $itenaryTags = [];
                            foreach ($itenary_tags as  $tag) {
                                $itenaryTags[] = $tag->name;
                            }
                            $itenaryTags = implode(',',$itenaryTags);
                        }
                        echo $itenaryTags;
                        //prd($itenaryTags);
                        ?></div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div><b>Itinerary Inclusions: </b></div>
                        <div><?php if(!empty($pkgInclusionObj) && !($pkgInclusionObj->isEmpty())){
                        foreach ($pkgInclusionObj as $inclusion) {
                            $inclusionArr[] = $inclusion->title;
                        }
                        echo implode(', ', $inclusionArr);
                    }
                    ?></div>
                    </td>
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
                        <div><b>Status: </b></div>
                        <div>{{ CustomHelper::getStatusStr($itenary->status) }}</div>
                    </td>
                </tr>

                 <tr>
                    <td colspan="3">
                        <div><b>Description: </b></div>
                        <div>{!! $itenary->description !!}</div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div><b>Sort Order: </b></div>
                        <div>{{$itenary->sort_order}}</div>
                    </td>
                    <td>
                        <div><b>Date Created: </b></div>
                        <div>{{ CustomHelper::DateFormat($itenary->created_at, 'd/m/Y') }}</div>
                    </td>
                </tr>     
</table>
</div>
</div>
@slot('bottomBlock')
@endslot

@endcomponent