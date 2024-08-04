@component('admin.layouts.main')

@slot('title')
Admin - Manage Cab City Details - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<?php
$id = (isset($cityData->id))?$cityData->id:'';
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();
if(empty($back_url)) {
    $back_url = $routeName.'/cabs.cities';
}
?>
<?php if($id){ ?>
<div class="page_btns">
    <a class="active" href="{{route($routeName.'.cabs.cities_view', ['id'=>$id])}}" title="Edit Cab City"><i class="fas fa-view"></i>Cab City</a>
    <a href="{{route($routeName.'.cabs.cities_agent_price', ['id'=>$id])}}" title="Edit Agent Price"><i class="fas fa-edit"></i>Agent price</a>
</div>
<?php } ?>

<div class="top_title_admin">
<div class="title">
<h2>{{ $page_heading }}</h2>
</div>
<div class="add_button">

<a href="{{ route($routeName.'.cabs.cities_edit', ['id'=>$id,'back_url'=>$back_url]) }}" class="btn_admin"><i class="fas fa-edit"  title="Edit City"></i>Edit Cab City</a>

<?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
<a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
Back</a>
<?php } ?>
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
                    <div>{{$cityData->name}}</div>
                </td>
                <td>
                    <div><b>Cabs: </b></div>
                    <div>
                        <?php
                        $cabs = $cityData->cabsCityPrices->pluck('cabData.name')->toArray();
                        if($cabs) {
                            echo implode(', ', $cabs);
                        }
                        ?>
                    </div>
                </td>
                <td>
                    <div><b>Minimum KM Per Day: </b></div>
                    <div>{{$cityData->per_day_km}}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><b>Sort Order: </b></div>
                    <div>{{$cityData->sort_order}}</div>
                </td>

                <td>
                    <div><b>Featured: </b></div>
                    <div>{{ CustomHelper::getStatusStr($cityData->featured) }}</div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <div><b>Airport: </b></div>
                    <div>{{ CustomHelper::getStatusStr($cityData->is_airport) }}</div>
                </td>
                <td>
                    <div><b>Airport Entry Charge & Toll: </b></div>
                    <div>{{$cityData->airport_entry_charge}}</div>
                </td>
                <td>
                    <div><b>Maximum allowed distance (KM): </b></div>
                    <div>{{$cityData->airport_max_distance}}</div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div><b>Terminals: </b></div>
                    <?php
                    $terminals = (isset($cityData->terminals))?json_decode($cityData->terminals):[];
                    if(!empty($terminals)) {
                        echo implode(', ',$terminals);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <div><b>Railway: </b></div>
                    <div>{{ CustomHelper::getStatusStr($cityData->is_railway) }}</div>
                </td>
                <td>
                    <div><b>Station entry Charge: </b></div>
                    <div>{{$cityData->station_entry_charge}}</div>
                </td>
                <td>
                    <div><b>Maximum allowed distance (KM): </b></div>
                    <div>{{$cityData->station_max_distance}}</div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div><b>Stations: </b></div>
                    <?php
                    $stations = (isset($cityData->stations))?json_decode($cityData->stations):[];
                    if(!empty($stations)) {
                        echo implode(', ',$stations);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div><b>Inclusions: </b></div>
                    <div>{!!$cityData->inclusions!!}</div>                    
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div><b>Exclusions: </b></div>
                    <div>{!!$cityData->exclusions!!}</div>                    
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div><b>Terms: </b></div>
                    <div>{!!$cityData->terms!!}</div>                    
                </td>
            </tr>
            <tr>
                <td>
                    <div><b>Date Created: </b></div>
                    <div>{{ CustomHelper::DateFormat($cityData->created_at, 'd/m/Y') }}</div>
                </td>

                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($cityData->status) }}</div>
                </td>
                <td></td>

            </tr>
</table>
</div>
</div>
@slot('bottomBlock')
@endslot

@endcomponent