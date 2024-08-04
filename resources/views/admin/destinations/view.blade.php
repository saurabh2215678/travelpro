
@component('admin.layouts.main')

@slot('title')
Admin -  - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$destination_name = (isset($destination->name))?$destination->name:'';
$parent_id = (isset($destination->parent_id))?$destination->parent_id:0;
$destination_type = (isset($destination->destination_type))?$destination->destination_type:0;
$best_months = (isset($destination->best_months))?json_decode($destination->best_months):[];
$brief = (isset($destination->brief))?$destination->brief:'';
$description = (isset($destination->description))?$destination->description:'';
$video_link = (isset($destination->video_link))?$destination->video_link:'';
$sort_order = (isset($destination->sort_order))?$destination->sort_order:'';
$latitude = (isset($destination->latitude))?$destination->latitude:'';
$longtitude = (isset($destination->longtitude))?$destination->longtitude:'';
$status = (isset($destination->status))?$destination->status:'';
$show = (isset($destination->show))?$destination->show:'';
$featured = (isset($destination->featured))?$destination->featured:'';

$meta_title = (isset($destination->meta_title))?$destination->meta_title:'';
$meta_keyword = (isset($destination->meta_keyword))?$destination->meta_keyword:'';
$meta_description = (isset($destination->meta_description))?$destination->meta_description:'';
$image = (isset($destination->image))?$destination->image:'';
$feature_image = (isset($destination->feature_image))?$destination->feature_image:'';
$banner_image = (isset($destination->banner_image))?$destination->banner_image:'';

$storage = Storage::disk('public');
$path = 'destinations/';

$old_image = $image;
$old_feature_image = $feature_image;
$old_banner_image = $banner_image;
$image_req = '';
$link_req = '';
$routeName = CustomHelper::getAdminRouteName();
    //$back_url=CustomHelper::BackUrl(); 
$back_url = request()->has('back_url') ? request()->input('back_url') : '';


?>
<?php
$active_menu = "destinations";
$destination_id = isset($destination->id) ? $destination->id:'';
?>
@if(!empty($destination))
    @include('admin.includes.destinationoptionmenu')
@endif

<div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('destinations','edit'))
    <a href="{{ route($routeName.'.destinations.edit', ['id'=>$destination->id,'back_url'=>$back_url]) }}" class="btn_admin" title="Edit destinations"><i class="fas fa-edit"></i>Edit Destinations</a>
    @endif
    </div>
    </div>

<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="ajax_msg"></div>
<table  border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td  valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
             
                     <tr>
                    <td>
                        <div><b>Destination Name: </b></div>
                        <div>{{$destination->name}}</div>
                    </td>
                    <td>
                        <div><b>Subdestinations: </b></div>
                        <div>{{$destination->parent_id}}</div>
                    </td>
                    <td>
                        <div><b>Package: </b></div>
                        <div>{{$destination->packages_count}}</div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div><b>Status: </b></div>
                        <div>{{ CustomHelper::getStatusStr($destination->status) }}</div>
                    </td>
                    <td>
                        <div><b>Featured: </b></div>
                        <div>{{ CustomHelper::getStatusStr($destination->featured) }}</div>
                    </td>
                    <td>
                        <div><b>Date Created: </b></div>
                        <div>{{ CustomHelper::DateFormat($destination->created_at, 'd/m/Y') }}</div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div><b>Sort Order : </b></div>
                        <div>
                            {{$destination->sort_order}}
                        </div>
                    </td>
                    <td>
                        <div><b>Best months to visit: </b></div>
                        <div> <?php 
                        //prd($best_months);
                        if(!empty($best_months)){
                            $monthArr = config('custom.months_arr');
                            foreach ($best_months as $month) {
                                $monthName[] = $monthArr[$month];
                            }
                            echo implode(', ', $monthName);
                    }
                    ?></div>
                    </td>
                    <td>
                        <div><b>Brief: </b></div>
                        <div>{!! $destination->brief !!}</div>
                    </td>
                </tr>

                <tr>
                    <td colspan="3">
                        <div><b>Description: </b></div>
                        <div>{!! $destination->description !!}</div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div><b>Image: </b></div>
                        <div>
                        <?php
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
                        <div><b>Banners Image: </b></div>
                        <div>
                         <?php
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
                        <div><b>Latitude: </b></div>
                        <div>{{$destination->latitude}}</div>
                    </td>
                </tr>

                <tr>
                    <td colspan="3">
                        <div><b>Video Link (Youtube,Vimeo): </b></div>
                        <div>{{ $destination->video_link }}</div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div><b>Longtitude: </b></div>
                        <div>{{$destination->longtitude}}</div>
                    </td>

                    <td>
                    <div><b>Show: </b></div>
                    <div>{{ CustomHelper::getStatusStr($destination->show) }}</div>
                    </td>

                    <?php 
                    $destination_flags = (!empty($destination) && (!$destination->destinationFlags->isEmpty())) ? $destination->destinationFlags : "";
                    $destination_flag_arr = [];
                    if(!empty($destination_flags)){
                        foreach ($destination_flags as  $dflag) {
                            $destination_flag_arr[] = $dflag->id;
                        }
                    }
                    ?>
                    <td>
                        <div><b>Flags: </b></div>
                        <div>
                            <?php $destinationflag = '';
                            $destination_flags = (!empty($destination) && (!$destination->destinationFlags->isEmpty())) ? $destination->destinationFlags : "";
                            $destination_flag_arr = [];
                            if(!empty($destination_flags)){
                                foreach ($destination_flags as  $dflag) {
                                    $destination_flag_arr[] = $dflag->name;
                                }
                            }
                        echo implode(', ',$destination_flag_arr); ?>

                        </div>
                    </td>
                </tr>
 
</table>
    </td>
</tr>

</table>
</div>
</div>
@slot('bottomBlock')
<!-- 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
  $(".tooltip_title").tooltip();

  $( function() {
    $( ".book_start_date, .book_end_date, .record_date, .agm_date" ).datepicker({
        'dateFormat':'dd/mm/yy'
    });
});
</script> -->
@endslot

@endcomponent




