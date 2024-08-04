@component('admin.layouts.main')

@slot('title')
Admin - Manage Menus - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$backUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$old_title = (request()->has('title'))?request()->title:'';
$old_status = (request()->has('status'))?request()->status:1;
?>
<div class="top_title_admin">
    <div class="title">
    <h2>Manage Menus ({{ $menus->total()}})</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('menus','add'))
    <a href="{{ route($routeName.'.menus.add').'?back_url='.$backUrl }}" class="btn_admin"><i class="fa fa-plus"></i> New Menu</a>
    @endif
    </div>
    </div>

    <!-- Start Search box section     -->
    <div class="centersec">
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-2">
                        <label>Title:</label><br/>
                        <input type="text" name="title" class="form-control admin_input1" value="{{$old_title}}">
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
                        <a href="{{ route($routeName.'.menus.index') }}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    
<!-- End Search box Section -->


        @include('snippets.errors')
        @include('snippets.flash')

        <?php
        if(!empty($menus) && count($menus) > 0){
            ?>
            <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>Title</th>
                        <th>Position</th>
                        <th>Status</th>
                        @if(CustomHelper::checkPermission('menus','edit') || CustomHelper::checkPermission('menus','delete'))
                        <th>Action</th>
                        @endif
                    </tr>
                    <?php
                    foreach($menus as $menu){
                    ?>
                        <tr>
                            <td><?php echo $menu->title; ?></td>
                            <td><?php echo $menu->position; ?></td>
                            <td><?php if($menu->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?></td>
                            @if(CustomHelper::checkPermission('menus','edit') || CustomHelper::checkPermission('menus','delete'))
                            <td>
                                @if(CustomHelper::checkPermission('menus','edit'))
                                <a href="{{route($routeName.'.menus.edit', $menu->id.'?back_url='.$backUrl)}}" title="Edit Menu Items"><i class="fas fa-edit"></i></a>
                                @endif
                                
                                @if(CustomHelper::checkPermission('menus','edit'))
                                <a href="{{route($routeName.'.menus.items', $menu->id.'?back_url='.$backUrl)}}" title="Menu Items"><i class="fas fa-list"></i></i></a>
                                @endif
                                
                                @if(CustomHelper::checkPermission('menus','delete'))
                                <a href="javascript:void(0)" id="{{$menu->id }}" class="sbmtDelForm" title="Delete Menu Items" ><i class="fas fa-trash-alt"></i></i></a>
                                
                                <form method="POST" action="{{ route($routeName.'.menus.delete', $menu->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Menu?');" id="delete-form-{{$menu->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="menu_id" value="<?php echo $menu->id; ?>">
                                </form>
                                @endif
                            </td>
                            @endif
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            {{ $menus->appends(request()->query())->links() }}
            <?php
        }
        else{
            ?>
            <div class="alert alert-warning">No record(s) found.</div>
            <?php
        }
        ?>

@slot('bottomBlock')
<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
</script>
@endslot

@endcomponent