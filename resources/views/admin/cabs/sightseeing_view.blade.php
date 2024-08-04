@component('admin.layouts.main')

@slot('title')
Admin -{{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endslot
<style>
.centersec.fancybox-content {
    width: 900px;
    height: 600px;
}
</style>
<?php
$id = (isset($cab_route->id))?$cab_route->id:'';
$name = (isset($cab_route->name))?$cab_route->name:'';
$route_type = (isset($cab_route->route_type))?$cab_route->route_type:'';
$origin = (isset($cab_route->origin))?$cab_route->origin:'';
$destination = (isset($cab_route->destination))?$cab_route->destination:'';
$description = (isset($cab_route->description))?$cab_route->description:'';
$places = (isset($cab_route->places))?$cab_route->places:'';
$duration = (isset($cab_route->duration))?$cab_route->duration:'';
$distance = (isset($cab_route->distance))?$cab_route->distance:'';
$status = (isset($cab_route->status))?$cab_route->status:1;
$routeName = CustomHelper::getAdminRouteName();
$back_url = request()->has('back_url') ? request()->input('back_url') : '';
$cab_route_types = config('custom.cab_route_types');
$active_menu = '';
?>
<?php if($id){ ?>
<div class="page_btns">
    <a class="active"href="{{route($routeName.'.cabs.sightseeing_view', ['id'=>$id])}}" title="View Cab Route"><i class="fas fa-view"></i>Cab Sightseeing</a>
    <a <?php if($active_menu=="cabs_sightseeing".$id.'/gallery'){echo 'class="active"' ;}?> href="{{route($routeName.'.images.upload_view',['tid'=>$id,'module'=>'cabs_sightseeing','category'=>'gallery']) }}" title="Images"><i class="fas fa-image"></i>Gallery</a>
</div>
<?php } ?>
<div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
        <a href="{{ route($routeName.'.cabs.sightseeing_edit', ['id'=>$cab_route->id,'back_url'=> $back_url]) }}" class="btn_admin"><i class="fas fa-edit"  title="Edit Cab Route"></i>Edit Cab Sightseeing</a>
    </div>
</div>
<div class="centersec">
    <div class="bgcolor">
        <div class="ajax_msg"></div>
        <table border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">
            <tr>
                <td width="806" valign="top" class="innersec">
                    <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td>
                                <div><b>Sightseeing Name: </b></div>
                                <div>
                                    {{$cab_route->name}}
                                </div>
                            </td>
                            <td>
                                <div><b>Origin: </b></div>
                                <div>{{(isset($cab_route->originCity))?$cab_route->originCity->name:""}}</div>
                            </td>
                            <td>
                                <div><b>Destination: </b></div>
                                <div>{{(isset($cab_route->destinationCity))?$cab_route->destinationCity->name:""}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div><b>Sharing: </b></div>
                                <div>
                                    {{CustomHelper::getStatusStr($cab_route->sharing)}}
                                    @if($cab_route->sharing)
                                    <br />
                                    <b>Start Time:</b>
                                    {{CustomHelper::getPackageSlotTitle($cab_route->start_time)}}
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div><b>Duration: </b></div>
                                <div>
                                    {{$cab_route->duration}}
                                </div>
                            </td>
                            <td>
                                <div><b>Distance in km: </b></div>
                                <div>
                                    {{$cab_route->distance}} km
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <div><b>Places covered in sightseeing: </b></div>
                                <div>{{$cab_route->places}}</div>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <div><b>Description: </b></div>
                                <div>{!!$cab_route->description!!}</div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div><b>Date Created: </b></div>
                                <div>{{ CustomHelper::DateFormat($cab_route->created_at, 'd/m/Y') }}</div>
                            </td>
                            <td>
                                <div><b>Featured: </b></div>
                                <div>{{ CustomHelper::getStatusStr($cab_route->featured)}}</div>
                            </td>
                            <td>
                                <div><b>Status: </b></div>
                                <div>{{ CustomHelper::getStatusStr($cab_route->status) }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
@slot('bottomBlock')

@endslot
@endcomponent