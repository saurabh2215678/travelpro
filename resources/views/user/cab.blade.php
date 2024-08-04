@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"  />
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
    .btn.s-btn { display: initial; background: #2c2d6c; color: #ffffff !important;}
    .btn2.edit_pofile_btn { font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
</style>

@endslot
<?php 
$name = (request()->has('name'))?request()->input('name'):'';
?>

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$old_name = (request()->has('name'))?request()->name:'';
$route_type = (request()->has('route_type'))?request()->route_type:'';
$old_route_type = old('route_type',$route_type);
$old_status = (request()->has('status'))?request()->status:1;

$origin = (request()->has('origin'))?request()->origin:'';
$origin = old('origin', $origin);

$destination = (request()->has('destination'))?request()->destination:'';
$destination = old('destination', $destination);

$cab_route_group_id = (request()->has('cab_route_group_id'))?request()->cab_route_group_id:'';
$cab_route_group_id = old('cab_route_group_id', $cab_route_group_id);

$storage = Storage::disk('public');
$cab_route_types = config('custom.cab_route_types');
$group_id =  (auth()->user()) ? auth()->user()->group_id : '';
?>

<section>
        <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">Cab-Route</h1>
                        </div> 
                    </div>

                </div> 
<div class="row">
                <form action="" method="GET" class="mt-1">
                  <form class="form-inline" method="GET">
                    <div class="col-md-3">
                        <label>Cab Route:</label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                    </div>
                    <div class="col-md-3{{$errors->has('cab_route_group_id')?' has-error':''}}">
                    <label for="FormControlSelect1">Route Group</label><br/>
                    <select name="cab_route_group_id" class="form-control admin_input1 select2">
                    <option value="">--Select Route Group--</option>
                    @if($cab_route_groups)
                    @foreach($cab_route_groups as $row)
                    <option value="{{$row->id}}" {{($row->id==$cab_route_group_id)?'selected':''}}>{{$row->name}}</option>
                    @endforeach
                    @endif
                    </select>
                    </div>
                    <div class="col-md-3{{$errors->has('route_type')?' has-error':''}}">
                    <label for="FormControlSelect1">Trip Type</label><br />
                    <select name="route_type" class="form-control admin_input1 select2">
                        <option value="">--Select Trip Type--</option>
                        <?php foreach ($cab_route_types as $key => $cab_route_type) { 

                          if($key != 1) { //do not show round trip
                          ?>
                        <option value="{{$key}}" <?php  if(strlen($old_route_type) > 0 && $key == $old_route_type){ echo'selected'; } ?>>{{$cab_route_type}}</option>
                           <?php } } ?>
                    </select>
                </div>
                <div class="col-md-3{{$errors->has('origin')?' has-error':''}}">
                <label for="FormControlSelect1">Origin</label><br/>
                <select name="origin" class="form-control admin_input1 select2">
                <option value="">--Select Origin--</option>
                @if($cab_cities)
                @foreach($cab_cities as $row)
                <option value="{{$row->id}}" {{($row->id==$origin)?'selected':''}}>{{$row->name}}</option>
                @endforeach
                @endif
                </select>
                </div>
             
               <div class="clearfix"></div>
                  <div class="col-md-3">
                        <label>&nbsp</label>
                        <div class="mb-3">
                     <button type="submit" class="btn s-btn rounded-full">Search</button>
                     <a class="btn2 edit_pofile_btn" href="{{route('user.cab')}}">Reset</a>
                </div>
                </div>
                </form>
            </form>
            </div>
                <?php if(!empty($res) && $res->count() > 0){ ?>  
                    <table class="table table_order mt-1">
                    <tr>
                   <th class="">Cab Route</th>
                   <th class="">Origin</th>
                   <th class="">Destination</th>
                   <th class="">Trip Type</th>
                   <th class="">Cab Route Group</th>
                   <th class="">Places</th>
                   <th class="">Duration/<br />Distance</th>
                   <th class="">Desc</th>
                   <th class="">Discount</th>
                                   
               </tr>
                    <tbody>
                         <?php foreach ($res as $rec) {

                        $CabRouteToGroup = $rec->CabRouteToGroup??''; ?>
                    <tr>
                       <td>{{$rec->name}}</td>
                       <td>{{$rec->originCity->name??''}}</td>
                       <td>{{$rec->destinationCity->name??''}}</td>

                       <td>{{$cab_route_types[$rec->route_type] ?? ''}}</td>
                       <td>
                       <?php //<td>{{$rec->CabRouteGroup->name??''}}</td> 
                        if($CabRouteToGroup){
                            foreach ($CabRouteToGroup as $key => $RouteGroup) {
                                // pr($RouteGroup->toArray());
                                if($key != 0){
                                    echo " ,";
                                }
                                echo $RouteGroup->name ?? '';
                            }
                        } ?>
                        </td>
                       <td>{{$rec->places}}</td>
                       <td>
                          {{$rec->duration}} 
                          <?php if(!empty($rec->distance)) { ?>
                          <br />({{$rec->distance}}km)
                        <?php } ?>
                        </td>
                        <td><?php  if($rec->description){ ?>
                                <i class="fas fa-check" style="color:green"></i>
                        <?php } ?>
                        </td>
                        <td>
                          <?php
                          $price_discount = '';
                          if($rec->discount_category_id !== 0) {
                            $price_discount = CustomHelper::getDiscountType('cab',$rec->discount_category_id,0,$group_id,$rec->id);
                          }
                          echo $price_discount;
                          ?>
                            <a href="javascript:void(0);" data-src="<?php echo route('user.cab_price', $rec['id']) ?> " data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a> 
                        </td>
                   </tr>
               <?php } ?>
                    </tbody>
                    </table>
                    <div class="pagination-wrapper hotel-pagination mt-3 ml-0">
                        {{ $res->appends(request()->input())->links('vendor.pagination.bootstrap-4'); }}
                    </div>
                </div>
            <?php }else{ ?>
            <div class="alert_not_found">No Booking data found.</div>
            <?php } ?>
        </div>
        </div>
    </section>
@slot('bottomBlock')

@endslot

@endcomponent


