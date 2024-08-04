@component('admin.layouts.main')

@slot('title')
Admin - Manage Bikes - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<!-- <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet"> -->
<link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style> .select2-container {z-index: 99;}</style>

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<style>
.toggle-off.btn {display: flex;align-items: center; }
</style>
@endslot

<?php
    $back_url=CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $id = (request()->has('id'))?request()->id:'';
    $old_name = (request()->has('name'))?request()->name:'';
    $old_status = (request()->has('status'))?request()->status:1;
    $is_airport = (request()->has('is_airport'))?request()->is_airport:'';
    $selected_bike = (request()->has('bike'))?request()->bike:[];
?>

<!-- Start Search box section     -->
<div class="centersec">
    <div class="top_title_admin tab-title">
        <div class="title">
            <h2>Manage City ({{ $bikeCities->total() }})</h2>
        </div>
        <div class="add_button">
            @if(CustomHelper::checkPermission('rental','add'))
                <a href="{{ route($routeName.'.bike_cities.add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i>Add City</a>
            @endif
        </div>
    </div>
    <div class="bgcolor">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <div class="col-md-3">
                    <label>Name:</label><br />
                    <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                </div>
                @if(!empty($bikes))
                <div class="col-md-3">
                        <label>Bike:</label><br/>
                        <select name="bike[]" class="form-control admin_input1 select2" multiple="multiple">
                            <?php foreach ($bikes as $bike_data) {
                                $selected = "";
                                if(in_array($bike_data->id,$selected_bike)){ $selected = "selected"; }
                            ?>
                            <option value="{{$bike_data->id}}" {{$selected}}>{{$bike_data->name}}</option>
                            <?php } ?>
                        </select>                  
                </div>
                @endif
                <div class="col-md-3">
                    <label>Status:</label><br />
                    <select name="status" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label></label><br>
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{route($routeName.'.bike_cities.index')}}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <!-- End Search box Section -->

    @include('snippets.errors')
    @include('snippets.flash')
    <?php
    if(!empty($bikeCities) && count($bikeCities) > 0){ ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tr>
                <th>Name</th>
                <th>Bike</th>
                <th>Pickup Location</th>
                <th>Status</th>
                @if(CustomHelper::checkPermission('rental','edit'))
                <th>Default <span class="ihover">(i) <span class="hover_text">(When a new rental signup, he will be added to default city and it can be changed by modifying rental)</span></span></th>
               @endif
                <th>Date Created</th>
                <th>Action</th>
            </tr>
            <?php foreach($bikeCities as $bikeCity) { 
                  $bike = (isset($bikeCity->bike))? json_decode($bikeCity->bike):[];?>
            <tr>
                <td><?php echo $bikeCity->name; ?></td>
                <td>
                    <?php if(is_array($bike)){
                        foreach ($bike as $key => $val) {
                            if(count($bike) > $key+1){
                                $comma = ", ";
                            } else {
                                $comma = "";
                            }
                            echo $bikeCity->bikeName($val).$comma??'';
                        } } ?>
                </td>
                <td>
                <?php   $locations = $bikeCity->locations;
                        $location_names = [];
                        foreach ($locations as  $rowLocation) {
                            
                            $location_names[] = $rowLocation->name ;

                        }
                        echo implode(', ',$location_names);
                 ?>
                 <br>
                 <a href="javascript:void(0);" class="location_fancy" data-src="{{route($routeName.'.bike_cities.locations',[$bikeCity->id])}}" title="Locations" data-fancybox data-type="ajax"><i class="fas fa-plus"></i> 
                    {{$bikeCity->locations->count()??'0'}} 
                </a> 
                <td>
                    <?php if($bikeCity->status == 1){ ?>
                    <span style="color:green">Active</span>
                    <?php   }else{  ?><span style="color:red">Inactive</span>
                    <?php } ?>
                </td>
                @if(CustomHelper::checkPermission('rental','edit'))
                  <td style="text-align: center;">
                    <input data-id="" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-type-id="{{$bikeCity->id}}" data-toggle="toggle" data-on="Default" data-off="Make default" {{ $bikeCity->is_default ? 'checked' : '' }} >
                </td>
                @endif
                <td>{{ CustomHelper::DateFormat($bikeCity->created_at, 'd/m/Y') }}</td>
                <td>
                    @if(CustomHelper::checkPermission('rental','view'))
                        <a href="{{route($routeName.'.bike_cities.view',[$bikeCity['id'], 'back_url'=>$back_url])}}" title="View "><i class="fas fa-eye"></i></a>
                    @endif
                    @if(CustomHelper::checkPermission('rental','edit'))
                        <a href="{{ route($routeName.'.bike_cities.edit', $bikeCity->id.'?back_url='.$back_url) }}" title="Edit Bike City"><i class="fas fa-edit"></i></a>
                    @endif
                    @if(CustomHelper::checkPermission('rental','delete'))
                        <a href="javascript:void(0)" class="sbmtDelForm" id="{{$bikeCity->id}}" title="Delete Bike City"><i class="fas fa-trash-alt"></i></i></a>
                        <form method="POST" action="{{ route($routeName.'.bike_cities.delete', $bikeCity->id) }}"
                            accept-charset="UTF-8" role="form"
                            onsubmit="return confirm('Are you sure you want to remove this bike City?');"
                            id="delete-form-{{$bikeCity->id}}">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <input type="hidden" name="id" value="<?php echo $bikeCity->id; ?>">
                        </form>
                    @endif
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
    {{ $bikeCities->appends(request()->query())->links('vendor.pagination.default') }}

    <?php } else { ?>
    <div class="alert alert-warning">No Bike Cities found.</div>
    <?php } ?>
</div>
@slot('bottomBlock')
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javaScript">
$('.select2').select2({ placeholder: "Please Select", allowClear: true }); 
    $('.location_fancy').fancybox({
		afterClose: function () {
			parent.location.reload(true);
		}
	});

    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
</script>

<script>
  $(function() {
    $('.toggle-class').change(function() {
        var conf = confirm('Are you sure you want to make default rental?');
        if(conf){
        var status = $(this).prop('checked') == true ? 1 : 1; 
        var id = $(this).attr('data-type-id'); 
        //alert('Are you sure you want to make default group?');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route($routeName.".bike_cities.changeRentalDefault") }}',
            data: {'is_default': status, 'id': id},
            success: function(data){
              console.log(data.success);
            window.location.reload();
            }
        });
    }else{
        window.location.reload();
    }
    })
  })
</script>

@endslot
@endcomponent