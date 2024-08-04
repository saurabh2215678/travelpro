@component('admin.layouts.main')

@slot('title')
Admin - Destination - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
    <!-- <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ > -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
<style>
       /* .select2-container {height: 32px; }*/
    .bootstrap-tagsinput { display: block; }
    .admin_input1 {margin-bottom: 5px;}
    .form-inline .form-control {font-size: 13px;}
    .activity_search .select2-container {z-index: 99;}
   .select2-container {z-index: 9999;}
</style>
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$parent_id = (request()->has('parent_id'))?request()->parent_id:'';
$place = (request()->has('place'))?request()->place:'';
$old_destination_name = (request()->has('destination_name'))?request()->destination_name:'';
$old_featured = (request()->has('featured'))?request()->featured:'';
$old_status = app('request')->input('status') ?? 1;

$checkbox = (request()->has('checkbox'))?request()->checkbox:[];
if(!empty($checkbox)) {
    if(!is_array($checkbox)) {
        $checkbox = explode(',', $checkbox);
    }
}
$checkbox = old('checkbox',$checkbox);

$storage = Storage::disk('public');
$path = 'destinations/';
?>

<div class="top_title_admin">
    <div class="title">
    <h2>Destinations ({{ $destinations->total() }})</h2>
    </div>
    <div class="add_button">
        @if(CustomHelper::checkPermission('destinations','add'))
        <a href="{{ route('admin.destinations.add', ['parent_id'=>$parent_id,'back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Destination</a>    
        @endif
    </div>
</div>

<!-- Start Search box section     -->
<div class="centersec">
    <div class="bgcolor ">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <div class="col-md-3">
                    <label>Destination / Subdestination </label><br/>
                    <input type="text" name="destination_name" class="form-control admin_input1" value="{{$old_destination_name}}">
                    <input type="hidden" name="parent_id" value="{{$parent_id}}">
                </div>

                <div class="col-md-3">
                    <label>Place Name </label><br/>
                    <input type="text" name="place" class="form-control admin_input1" value="{{$place}}">
                </div>

                <div class="col-md-3">
                    <label>Flags</label><br/>
                    <select name="checkbox[]" class="form-control admin_input1 select2" multiple="multiple">
                        <option value="">--Select--</option>
                         <?php
                            if(!empty($flags)){ ?>
                            <option value="">Select</option>
                            <?php
                            foreach ($flags as $flag){
                                $selected = '';
                                if(in_array($flag->id,$checkbox)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$flag->id}}" {{$selected}}>{{$flag->name}}</option>
                        <?php } } ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Status </label><br/>
                    <select name="status" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <option value="1" {{ ($old_status == '1')?'selected':'' }}>Active</option>
                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <label class="control-label ">Featured </label><br/>

                    <input type="checkbox" name="featured" value="1" <?php echo ($old_featured == '1')?'checked':''; ?> />
                </div>

                <!--  <div class="clearfix"></div> -->
                <div class="col-md-3">
                    <label></label><br>
                    <button type="submit" name="submit" value="submit" class="btn btn-success">Search</button>
                    <a href="{{ route($routeName.'.destinations.index') }}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- End Search box Section -->

        @include('snippets.errors')
        @include('snippets.flash')
        <div class="ajax_msg"></div>

        @if(request()->has('parent_id'))
        <div class="bgcolor" style="margin-bottom:10px;">
            <div class="inner-breadcrumb">
                <ul>
                    {!!CustomHelper::destinationsBreadcrumb(request()->parent_id)!!}
                </ul>
            </div> 
        </div>
        @endif
        <div class="alert alert-warning" role="alert" style="margin-top: 12px;padding: 5px;margin-bottom: 5px;"><b>Note :</b> <i class="fa fa-lightbulb-o fa-1x"></i> Smaller places/sub-destination that don't need to be displayed on the website should be added under respective destination.</div>
        <?php if(!($destinations->isEmpty())){?>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">

                    <tr>
                        <th width="10%" class="text-center">Destination Name</th>
                        <th width="10%" class="text-center">Destination Type</th>
                        <th width="10%" class="text-center">Image</th>
                        <!-- <th width="10%" class="text-center">Feature Image</th> -->
                        <th width="10%" class="text-center">Sub Destinations</th>
                        <th width="10%" class="text-center">Places</th>
                        <th width="5%" class="text-center">Sort Order</th>
                        <th width="5%" class="text-center">Status</th>
                        <th width="15%" class="text-center">Flags</th>
                        <th width="5%" class="text-center">Show</th>
                        <th width="10%" class="text-center">Packages</th>
                        <th width="15%" class="text-center">Action</th>
                    </tr>

                    <?php
                    foreach($destinations as $destination){
                        $destinationType = $destination->destinationType??'';
                        $destinationType_name = $destinationType->name??'';

                        ?>
                        <tr>
                            <?php //if(!($destination->children->isEmpty())){ ?>
                                <td><a href="{{route($routeName.'.destinations.index',['parent_id'=>$destination->id])}}">{{$destination->name}}</a></td>
                            <?php /*}else{ ?>
                                <td>{{$destination->name}}</td>
                            <?php }*/ ?>
                            <td>{{$destinationType_name}}</td>
                            <td>
                                <?php
                                $image = $destination->image;
                                if(!empty($image)){
                                    if($storage->exists($path.$image)){
                                        ?>
                                        <div class="col-md-2 image_box">
                                            <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                                        </div>
                                    <?php }}else{
                                        echo "No Image";
                                    } ?>
                                </td>

                                <?php /*<td><?php echo (!empty($destination->parent)) ? $destination->parent->name.' -> ' : ''; ?><?php echo (!empty($destination->children)) ? $Destination->name : ''; ?></td>*/ ?>

                                <td>
                                    <?php if(!($destination->children->isEmpty())){ ?>
                                    <a href="{{route($routeName.'.destinations.index',['parent_id'=>$destination->id])}}">{{$destination->children->count()}}</a>
                                    <?php }else{ ?>
                                    0
                                    <?php } ?>
                                </td>
                                <td><a href="javascript:void(0);" data-src="{{route($routeName.'.destinations.locations',[$destination->id])}}" title="Locations" data-fancybox data-type="ajax"><i class="fas fa-plus"></i> {{$destination->destinationLocationsCount()??'0'}}</a> 
                                <label>
                                <?php
                                if(!empty($place) && isset($place)) {
                                $places_arr = [];
                                foreach($destination->destinationLocations as $dplace) { 

                                    if(strpos($dplace, $place) ){
                                        $places_arr[] = $dplace->name;
                                    }

                               /* if($place->name  $place ) {
                                $places_arr[] = $place->name;
                                }*/

                                }
                                if(!empty($places_arr)) {
                                echo '('.implode(',',$places_arr).")";
                                }
                                }
                                ?>
                                </label> 
                            </td>
                                <td>{{$destination->sort_order}}</td>
                                <td>
                                    <?php if($destination->status == 1){ ?>
                                    <span style="color:green">Active</span>
                                    <?php }else{ ?>
                                    <span style="color:red">Inactive</span>
                                    <?php } ?>
                                </td>

                                <td><?php
                            $destination_flags = (!empty($destination) && (!$destination->destinationFlags->isEmpty())) ? $destination->destinationFlags : "";

                            $destination_flag_arr = [];
                            if(!empty($destination_flags)){
                                foreach ($destination_flags as  $dflag) {
                                    $destination_flag_arr[] = $dflag->name;
                                }
                            }

                            echo implode(', ',$destination_flag_arr);

                            ?>
                        </td>
                        </td>

                        <td><?php if($destination->show == 1){ ?>
                            <span style="color:green"><i class="fas fa-check" style="color:green"></i></span>
                            <?php }else{ ?>
                            <span style="color:#f00;"><i style="color:#f00;" class="fa fa-times"></i></span>
                            <?php } ?>
                        </td>
                        <td>{{ $destination->packages_count}}</td>
                        <td>

                            <a href="{{route($routeName.'.destinations.view',[$destination['id'], 'back_url'=>$BackUrl])}}" title="View Destination"><i class="fas fa-eye"></i></a>
                            @if(CustomHelper::checkPermission('destinations','edit'))

                            <a href="{{ route($routeName.'.destinations.edit', ['id'=>$destination->id,'parent_id'=>$parent_id]) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                            @endif
                            @if(CustomHelper::checkPermission('destinations','delete'))

                            <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                            <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.destinations.delete', [$destination['id'], 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove this destination?');" class="delForm">
                                {{ csrf_field() }}
                            </form>
                            @endif
                        </td>

                    </tr>
                <?php } ?>
            </table>
        </div>
        {{ $destinations->appends(request()->query())->links('vendor.pagination.default') }}
        <?php
    }else{
        ?>
        <div class="alert alert-warning">There are no Destination at the present.</div>
    <?php } ?>
</div>

</div>

@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
    $(document).on("click", ".sbmtDelForm", function(e){
        e.preventDefault();

        $(this).siblings("form.delForm").submit();                
    });

    $('.select2').select2({
            placeholder: "Please Select",
            allowClear: true
        });
</script>

@endslot

@endcomponent