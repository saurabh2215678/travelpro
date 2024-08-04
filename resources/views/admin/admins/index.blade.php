@component('admin.layouts.main')

@slot('title')
Admin - Manage {{ucfirst($segment)}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php 
$back_url=CustomHelper::BackUrl(); 
$routeName = CustomHelper::getAdminRouteName();
$old_name = app('request')->input('name');
$old_email = app('request')->input('email');
$role_id = (request()->has('role_id'))?request()->role_id:'';
$old_status = (request()->has('status'))?request()->status:1; 
$old_from = app('request')->input('from');
$old_to = app('request')->input('to');
?>

<div class="top_title_admin">
    <div class="title">
        <h2>Manage  <?php if($segment == 'vendors'){ echo 'Vendor';}else{ echo 'Users';} ?>  ({{ $users->total() }})</h2>
    </div>
    <div class="add_button">
        <?php if($segment == 'vendors'){ ?>
        @if(CustomHelper::checkPermission('vendor','add'))
            <a href="{{ route($routeName.'.vendors.add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Vendor</a>
            @endif
        <?php }else{ ?>
            @if(CustomHelper::checkPermission('staff','add'))
            <a href="{{ route($routeName.'.users.add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add User</a>
            @endif
        <?php } ?>
    </div>
</div>

    <div class="centersec">
        <div class="bgcolor ">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <div class="col-md-2">
                    <label>Name:</label><br/>
                    <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                </div>
                <div class="col-md-2">
                    <label>Email:</label><br/>
                    <input type="text" name="email" class="form-control admin_input1" value="{{$old_email}}">
                </div>
                <?php if($segment != 'vendors'){ ?>
                    <div class="col-md-2">
                        <label>Role:</label><br/>
                        <select name="role_id" id="single" class="form-control">
                           <option value="">Select Role</option>
                           @foreach($roles as $role)
                           <option value="{{$role->id}}" {{($role->id==$role_id)?'selected':''}}>{{$role->name}}</option>
                           @endforeach
                       </select>
                   </div>
               <?php } ?>

             <div class="col-md-2">
                <label>Status:</label><br/>
                <select name="status" class="form-control admin_input1">
                    <option value="">--Select--</option>
                    <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                    <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                </select>
            </div>
            <!-- <div class="clearfix"></div> -->
            <div class="col-md-2">
                <label>From Date:</label><br/>
                <input type="date" name="from" class="form-control" value="{{$old_from}}" autocomplete="off" >
            </div>

            <div class="col-md-2">
                <label>To Date:</label><br/>
                <input type="date" name="to" class="form-control" value="{{$old_to}}" autocomplete="off" >
            </div>

            <div class="col-md-12 d_flex" style="padding-bottom:8px;">
                <button style="margin-right:15px" type="submit" class="btn btn-success">Search</button>

                <a href="{{url($routeName.'/'.$segment)}}" class="btn_admin2" >Reset</a>
            </div>
        </form>
    </div>
</div>

    @include('snippets.errors')
    @include('snippets.flash')

<?php
if(!empty($users) && count($users) > 0){
    ?>
    <div class="table-responsive">           
<table class="table table-striped table-bordered table-hover">
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Phone</th>
                <th>Status</th>
                @if(CustomHelper::checkPermission('staff','edit') || CustomHelper::checkPermission('staff','delete') || CustomHelper::checkPermission('vendor','edit')|| CustomHelper::checkPermission('vendor','delete'))
                <th>Action</th>
                @endif
            </tr>
            <?php
            foreach($users as $user){
                $role_name = !empty($user->roles)?$user->roles->name:''; 
                ?>
                @if($user->role_id != 1)
                <tr>
                    <td><?php echo $user->name; ?></td>
                    <td><?php echo $user->email; ?></td>
                    <td><?php echo $role_name; ?></td>
                    <td><?php echo $user->phone; ?></td>
                    <td>
                    <?php if($user->status == 1){ ?>
                    <span style="color:green">Active</span>
                    <?php   }else{  ?><span style="color:red">Inactive</span>
                    <?php } ?>
                    </td>
                    @if(CustomHelper::checkPermission('staff','edit') ||CustomHelper::checkPermission('staff','delete') || CustomHelper::checkPermission('vendor','edit') || CustomHelper::checkPermission('vendor','delete'))
                    <td>
                        <?php if($segment == 'vendors'){ ?>
                            @if(CustomHelper::checkPermission('vendor','edit'))
                            <a href="{{ route($routeName.'.vendors.edit', $user->id.'?back_url='.$back_url) }}" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif

                        <?php }else{ ?>
                            @if(CustomHelper::checkPermission('staff','edit'))
                            <a href="{{ route($routeName.'.users.edit', $user->id.'?back_url='.$back_url) }}" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif
                        <?php } ?>

                        <?php 
                        $show_msg = 'User';
                        if($segment == 'vendors'){ $show_msg = 'Vendor';} 

                        $userUrl = route($routeName.'.users.delete', $user->id);
                        if($user->is_vendor == 1){
                            $userUrl = route($routeName.'.vendors.delete', $user->id);
                        }?> 

                        @if($user->is_vendor == 0 && CustomHelper::checkPermission('staff','delete') || $user->is_vendor == 1 && CustomHelper::checkPermission('vendor','delete'))
                        <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$user->id}}" title="Delete"><i class="fas fa-trash-alt"></i></i></a>
                       
                        <form method="POST" action="{{$userUrl}}" accept-charset="UTF-8" role="form" onsubmit="return confirm('{{'Are you sure you want to remove this '.$show_msg}}?');" id="delete-form-{{$user->id}}">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <input type="hidden" name="banner_id" value="<?php echo $user->id; ?>">
                        </form>
                        @endif
                    </td>
                    @endif
                </tr>
                @endif
                <?php
            }
            ?>
        </table>
    </div>
    {{ $users->appends(request()->query())->links('vendor.pagination.default') }}
    <?php
}
else{
    ?>
    <div class="alert alert-warning">No Users found.</div>
    <?php
}
?>
</div>
</div>
@slot('bottomBlock')
<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" /> 
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script type="text/javaScript">
    $( function() {
        $( ".to_date, .from_date" ).datepicker({
            'dateFormat':'dd/mm/yy'
        });
    });

      $("#single").select2({
          placeholder: "Select Roles",
          allowClear: true
      });

    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
</script>
@endslot
@endcomponent