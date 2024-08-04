@component('admin.layouts.main')

@slot('title')
Admin - Manage Bikes - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endslot

<?php
    $back_url=CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $id = (request()->has('id'))?request()->id:'';
    $old_name = (request()->has('name'))?request()->name:'';
    $old_status = (request()->has('status'))?request()->status:1; 
    $old_bike_type = (request()->has('bike_type'))?request()->bike_type:''; 
    $storage = Storage::disk('public');
    $path = 'bikes/';
    $rental_type_arr = config('custom.rental_type_arr');
?>

<!-- Start Search box section     -->
<div class="centersec">
    <div class="top_title_admin tab-title">
        <div class="title">
            <h2>Manage Bikes ({{ $bikes->total() }})</h2>
        </div>
        <div class="add_button">
            @if(CustomHelper::checkPermission('rental','add'))
            <a href="{{ route($routeName.'.bike_master.add').'?back_url='.$back_url }}" class="btn_admin"><i
                    class="fa fa-plus"></i>Add Bike</a>
            @endif
        </div>
    </div>
    <style>
    .autoCounter_table tbody tbody {
        counter-reset: section;
    }

    .autoCounter_table tbody tr {
        counter-increment: section;
    }

    .autoCounter_table tbody td:nth-child(7):before {
        content: counter(section);
    }
    </style>
    <div class="bgcolor">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <div class="col-md-2">
                    <label>Name:</label><br />
                    <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                </div>
                @if($rental_type_arr)
                <div class="col-md-2">
                    <label>Bike Type:</label><br />
                    <select name="bike_type" class="form-control admin_input1">
                        <option value="">--Select--</option>
                            @foreach($rental_type_arr as $key => $val)
                                <option value="{{$key}}" {{($key==$old_bike_type)? 'selected' : ''}}>{{$val}}</option>
                            @endforeach
                    </select>
                </div>
                @endif                
                <div class="col-md-2">
                    <label>Status:</label><br />
                    <select name="status" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label></label><br>
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{route($routeName.'.bike_master.index')}}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <!-- End Search box Section -->
    @include('snippets.errors')
    @include('snippets.flash')
    <br>
    <div class="ajax_msg"></div>
    <?php if(!empty($bikes) && count($bikes) > 0){ ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tr>
                <th width="10">Sort</th>
                <th>Name</th>
                <th>Model</th>
                <th>Equivalent</th>
                <th width="160">Image</th>
                <th>Status</th>
                @if(CustomHelper::checkPermission('rental','edit') || CustomHelper::checkPermission('rental','delete'))
                <th>Action</th>
                @endif
            </tr>
            <tbody class="row_position">
                <?php $i=0;
                      foreach($bikes as $bike){
                      $i++; ?>
                <tr class="ui-sortable-handle" id="{{$bike->id}}">
                    <td><span class="rows handle"><span style="cursor:pointer"><i class="fa fa-arrows-v" style="font-size:21px;color: #00b2a7;"></i></span> </span></td>
                    <td><?php echo $bike->name; ?></td>
                    <td><?php echo $bike->modal; ?></td>
                    <td><?php echo $bike->equivalent; ?></td>
                    <td> <?php $image = $bike->image;
                        if(!empty($image)){
                            if($storage->exists($path.$image)){ ?>
                                <div class="col-md-2 image_box">
                                    <a href="{{ url('storage/'.$path.$image) }}" target="_blank">
                                        <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                                    </a>    
                                </div>
                        <?php } }else{
                                    echo "No Image";
                                } ?>
                    </td>
                    <td><?php if($bike->status == 1){ ?>
                        <span style="color:green">Active</span>
                        <?php  }else{ ?><span style="color:red">Inactive</span>
                        <?php } ?>
                    </td>

                    @if(CustomHelper::checkPermission('rental','edit') || CustomHelper::checkPermission('rental','delete'))
                    <td>
                        @if(CustomHelper::checkPermission('rental','edit'))
                        <a href="{{ route($routeName.'.bike_master.edit', $bike->id.'?back_url='.$back_url) }}"
                            title="Edit Bike"><i class="fas fa-edit"></i></a>
                        @endif
                        @if(CustomHelper::checkPermission('rental','delete'))
                        <a href="javascript:void(0)" class="sbmtDelForm" id="{{$bike->id}}" title="Delete Bike"><i class="fas fa-trash-alt"></i></i></a>

                        <form method="POST" action="{{ route($routeName.'.bike_master.delete', $bike->id) }}"
                            accept-charset="UTF-8" role="form"
                            onsubmit="return confirm('Are you sure you want to remove this bike?');"
                            id="delete-form-{{$bike->id}}">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <input type="hidden" name="id" value="<?php echo $bike->id; ?>">
                        </form>
                        @endif
                    </td>
                    @endif
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    {{ $bikes->appends(request()->query())->links('vendor.pagination.default') }}

    <?php } else { ?>
    <div class="alert alert-warning">No Bikes found.</div>
    <?php } ?>
</div>
@slot('bottomBlock')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });

    $( ".row_position" ).sortable({  
        delay: 150,
        handle: ".handle",
        stop: function(event, ui) {
                var selectedData = new Array();  
                $('.row_position>tr').each(function() {  
                    selectedData.push($(this).attr("id"));  
                });  
                updateBike(selectedData);  
            }  
    });
</script>

<script>
function updateBike(data) {
    var _token = '{{ csrf_token() }}';
    $.ajax({
        url: "{{ route('admin.bike_master.update_bike') }}",
        type: 'POST',
        data: {
            data: data
        },
        dataType: "JSON",
        headers: {
            'X-CSRF-TOKEN': _token
        },
        cache: false,
        beforeSend: function() {
            $(".ajax_msg").html("");
            $(".ajax_msg").show();
        },
        success: function(response) {
            // location.reload();
            $(".ajax_msg").html(response.msg).hide();
            $(".ajax_msg").html(response.msg).fadeIn();
            // setTimeout(function() { $(".ajax_msg").hide(); }, 3000);
            setTimeout(function() {
                $(".ajax_msg").fadeOut();
            }, 3000);
        }
    })
}
</script>

@endslot
@endcomponent