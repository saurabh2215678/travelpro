@component('admin.layouts.main')

    @slot('title')
        Admin - Manage Destination Type - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <style>
            .toggle-off.btn {display: flex;align-items: center; }
        </style>
    @endslot

    <?php 
    $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $old_name = (request()->has('name'))?request()->name:'';
    $old_status = (request()->has('status'))?request()->status:1;
    ?>
<!-- Start Search box section  -->
<div class="centersec">
<?php
   $active_menu = "destinations.types";
   ?>
   @include('admin.includes.destinationmenu')
<div class="top_title_admin">
    <div class="title">
    <h2>Manage Destination Type ({{ $destination_types->total()}})</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('all_masters','add'))
    <a href="{{ route('admin.destinations.type_add', ['back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Destination Type</a>
    @endif
    </div>
    </div>
    <div class="centersec">
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-2">
                        <label>Name:</label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                    </div>
                     <div class="col-md-2">
                        <label>Status:</label><br/>
                        <select name="status" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                            <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                       <?php //prd($branches->toArray());?>
                    <!-- class section here -->
                     
               <!-- closed here -->

                   <!--  <div class="clearfix"></div> -->
                    <div class="col-md-6">
                        <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{url('admin/destinations/types')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
</div>
<!-- End Search box Section -->

            @include('snippets.errors')
            @include('snippets.flash')
            <?php
            if(!empty($destination_types) && count($destination_types) > 0){
                ?>
                <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th>Name</th>
                            <th style="
                                    text-align: center;
                                    width: 45%;
                                ">Status
                            </th>
                            @if(CustomHelper::checkPermission('all_masters','edit') || CustomHelper::checkPermission('all_masters','delete'))
                            <th>Action</th>
                            @endif
                        </tr>        
             <?php
                 if(!empty($destination_types)){  
                  foreach ($destination_types as $destination_type) {
                            ?>
                        <tr>      
                            <td><?php 
                            echo $destination_type->name;
                            $id = $destination_type->id;
                             ?></td>
                            <td style="text-align: center;">
                                <input data-id="{{ CustomHelper::getStatusStr($destination_type->status) }}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-type-id="{{$destination_type->id}}" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $destination_type->status ? 'checked' : '' }} >
                            </td>
                        @if(CustomHelper::checkPermission('all_masters','edit') || CustomHelper::checkPermission('all_masters','delete'))
                            <td>
                               @if(CustomHelper::checkPermission('all_masters','edit'))
                                <a href="{{route($routeName.'.destinations.type_edit',[$destination_type['id'], 'back_url'=>$BackUrl])}}" title="Edit Destination Type"><i class="fas fa-edit"></i></a>
                                @endif
                                @if(CustomHelper::checkPermission('all_masters','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$destination_type->id}}" title="Delete Destination Type"><i class="fas fa-trash-alt"></i></i></a>

                                 <form method="POST" action="{{route($routeName.'.destinations.destination_delete',[$destination_type['id'], 'back_url'=>$BackUrl])}}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove this destination type?');" id="delete-form-{{$destination_type->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="id" value="<?php echo $destination_type->id; ?>">
                                    </form>
                                    @endif
                            </td>
                            @endif
                        </tr>

                        <?php
                            } }
                        ?>
                    </table>
              {{$destination_types->appends(request()->query())->links('vendor.pagination.default')}}
            <?php }else{ ?>
            <div class="alert alert-warning">No destination type found.</div>
            <?php
        }
            ?>

        </div>
    </div>

@slot('bottomBlock')
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
  $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var id = $(this).attr('data-type-id'); 
         alert('Are you sure you want to change this status?');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route($routeName.".destinations.ChangeUserStatus") }}',
            data: {'status': status, 'id': id},
            success: function(data){
              console.log(data.success)
              setTimeout(function() {
                location.reload();
            }, 300);
            }
        });
    })
  })
</script>
<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
</script>
@endslot

@endcomponent
