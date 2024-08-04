@component('admin.layouts.main')

    @slot('title')
        Admin - Manage {{ucfirst($segment)}} Settings - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
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
    $active_menu = "packages.settings";
    ?>
    @include('admin.includes.packagemenu')
    <div class="top_title_admin tab-title">
        <div class="title">
        <h2>Manage {{ucfirst($segment)}} Settings ({{ $results->total() }})</h2>
        </div>
        <?php /*<div class="add_button">
          @if(CustomHelper::checkPermission('all_masters','add'))
          <a href="{{ route($routeName.'.packages.settings_add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Settings</a>
          @endif
      </div>*/ ?>
  </div>
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-4">
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
                   <!--  <div class="clearfix"></div> -->

                    <div class="col-md-6">
                    <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{route($routeName.'.'.$segment.'.settings')}}" class="btn_admin2">Reset</a>

                    </div>
                </form>
            </div>
        </div>

<!-- End Search box Section -->

            @include('snippets.errors')
            @include('snippets.flash')

            <?php
            if(!empty($results) && count($results) > 0){
                ?>
                <div class="table-responsive">           
                <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th>Name</th>
                            <th>Default (Hide)</th>
                            <th>Status</th>
                            @if(CustomHelper::checkPermission('all_masters','edit'))
                            <th>Action</th>
                            @endif
                        </tr>
                            <?php
                            foreach($results as $row){?>
                        <tr>
                            <td><?php echo $row->name; ?></td>
                            <td>
                                @if($row->hide==1)
                                <span style="color:green">Yes</span>
                                @else
                                <span style="color:red">No</span>
                                @endif
                            </td>
                            <td>
                                @if($row->status==1)
                                <span style="color:green">Active</span>
                                @else
                                <span style="color:red">Inactive</span>
                                @endif
                            </td>
                            @if(CustomHelper::checkPermission('all_masters','edit'))
                            <td>
                                <a href="{{ route($routeName.'.'.$segment.'.settings_edit', ['id'=>$row->id,'back_url'=>$back_url]) }}" title="Edit Settings"><i class="fas fa-edit"></i></a>
                               

                                <?php /*
                                @if(CustomHelper::checkPermission('all_masters','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$row->id}}" title="Delete Settings"><i class="fas fa-trash-alt"></i></i></a>
                                @endif
                                 <form method="POST" action="{{ route($routeName.'.packages.settings_delete', ['id'=>$row->id]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove?');" id="delete-form-{{$row->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="id" value="<?php echo $row->id; ">
                                </form>*/ ?>
                            </td>
                            @endif
                        </tr>
                        <?php } ?>
                    </table>
                </div>
              {{ $results->appends(request()->query())->links('vendor.pagination.default') }}
            
            <?php }else{ ?>
            <div class="alert alert-warning">No settings found.</div>
            <?php } ?>

        </div>

@slot('bottomBlock')
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
</script>
@endslot


@endcomponent

