@component('admin.layouts.main')

@slot('title')
Admin - Manage Cab City Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<?php
    $id = (isset($bikeCityQuery->id))?$bikeCityQuery->id:'';
    $name = (isset($bikeCityQuery->name))?$bikeCityQuery->name:'';
    $status = (isset($bikeCityQuery->status))?$bikeCityQuery->status:1;
    $sort_order = (isset($bikeCityQuery->sort_order))?$bikeCityQuery->sort_order:0;
    $bike = (isset($bikeCityQuery->bike))? json_decode($bikeCityQuery->bike):[];
    $inclusions = (isset($bikeCityQuery->inclusions))?$bikeCityQuery->inclusions:'';
    $exclusions = (isset($bikeCityQuery->exclusions))?$bikeCityQuery->exclusions:'';
    $km_included = (isset($bikeCityQuery->km_included))?$bikeCityQuery->km_included:'';

    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();
    if(empty($back_url)){ $back_url = $routeName.'/bike_cities'; }
?>

<div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
        <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
        <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>Back</a><?php } ?>
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
                                <div><b>Name: </b></div>
                                <div>{{$bikeCityQuery->name??''}}</div>
                            </td>
                            <td>
                                <div><b>Bike: </b></div>
                                <div>
                                    <?php if(is_array($bike)){
                                        foreach ($bike as $key => $val) {
                                            if(count($bike) > $key+1){
                                                $comma = ", ";
                                            } else {
                                                $comma = "";
                                            }
                                            echo $bikeCityQuery->bikeName($val).$comma??'';
                                        } } ?>
                                </div>
                            </td>

                            <td>
                                <div><b>Km Included: </b></div>
                                <div>{{ $bikeCityQuery->km_included??'' }}</div>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <div><b>Inclusions: </b></div>
                                <div>{!!$bikeCityQuery->inclusions??''!!}</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div><b>Exclusions: </b></div>
                                <div>{!!$bikeCityQuery->exclusions??''!!}</div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div><b>Status: </b></div>
                                <div>{{ CustomHelper::getStatusStr($bikeCityQuery->status) }}</div>
                            </td>
                            <td>
                                <div><b>Sort Order: </b></div>
                                <div>{{$bikeCityQuery->sort_order}}</div>
                            </td>

                            <td>
                                <div><b>Date Created: </b></div>
                                <div>{{ CustomHelper::DateFormat($bikeCityQuery->created_at, 'd/m/Y') }}</div>
                            </td>

                        </tr>
                    </table>
    </div>
</div>
@slot('bottomBlock')
@endslot

@endcomponent