@component('admin.layouts.main')

    @slot('title')
        Admin - Manage Destination Flag - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    @endslot

    <?php 
    $back_url=CustomHelper::BackUrl(); 
    $routeName = CustomHelper::getAdminRouteName();
    $old_name = (request()->has('name'))?request()->name:'';
    $old_status = (request()->has('status'))?request()->status:1;
    
    ?>
   

<!-- Start Search box section     -->
<div class="centersec">
     <?php
   $active_menu = "destinations.flags";
   ?>
   @include('admin.includes.destinationmenu')
    <div class="top_title_admin tab-title">
    <div class="title">
    <h2>Manage Destination Flag ({{$destinations->total()}})</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('all_masters','add'))
    <a href="{{ route($routeName.'.destinations.flags_add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Destination Flag</a>
    @endif
    </div>
    </div>
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-4">
                        <label>Destination Flag:</label><br/>
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
                   <!--  <div class="clearfix"></div> -->
                    <div class="col-md-6">
                    <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{route($routeName.'.destinations.flags')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

<!-- End Search box Section -->

            @include('snippets.errors')
            @include('snippets.flash')
            <?php
            if(!empty($destinations) && count($destinations) > 0){
                ?>
                <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th>Destination Flag</th>
                            <th>Slug</th>
                            <th>Page Title</th>
                            <th>Status</th>
                            @if(CustomHelper::checkPermission('all_masters','edit') || CustomHelper::checkPermission('all_masters','delete'))
                            <th>Action</th>
                            @endif
                        </tr>
                            <?php
                            foreach($destinations as $destination){?>
                        <tr>
                            <td><?php echo $destination->name; ?></td>
                            <td><?php echo $destination->slug; ?></td>
                            <td><?php echo $destination->page_title; ?></td>
                            <td>
                            @if(CustomHelper::checkPermission('all_masters','edit'))
                                <input data-id="{{ CustomHelper::getStatusStr($destination->status) }}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-type-id="{{$destination->id}}" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $destination->status ? 'checked' : '' }} >
                                @else
                                <?php if($destination->status == 1){ ?>
                                <i class="fas fa-check" style="color:green"></i>
                                <?php   }else{  ?><i class="fas fa-times" style="color:red"></i>
                            <?php } ?>
                            @endif
                            </td>
                            @if(CustomHelper::checkPermission('all_masters','edit') || CustomHelper::checkPermission('all_masters','delete'))
                            <td>
                                @if(CustomHelper::checkPermission('all_masters','edit'))
                                <a href="{{ route($routeName.'.destinations.flags_edit', ['id'=>$destination->id,'back_url'=>$back_url]) }}" title="Edit Destination Flags"><i class="fas fa-edit"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('all_masters','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$destination->id}}" title="Delete Destination Flags"><i class="fas fa-trash-alt"></i></i></a>

                                 <form method="POST" action="{{ route($routeName.'.destinations.flags_delete', ['id'=>$destination->id]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove this destination flag?');" id="delete-form-{{$destination->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="id" value="<?php echo $destination->id; ?>">
                                    </form>
                                    @endif
                            </td>
                            @endif
                        </tr>

                        <?php } ?>
                    </table>
                </div>
              {{ $destinations->appends(request()->query())->links('vendor.pagination.default') }}
            
            <?php }else{ ?>
            <div class="alert alert-warning">No Destination Type found.</div>
            <?php } ?>

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
            url: '{{ route($routeName.".destinations.changeFlagStatus") }}',
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

