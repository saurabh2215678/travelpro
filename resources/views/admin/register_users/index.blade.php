@component('admin.layouts.main')

@slot('title')
    Admin - Manage Registered Users - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php 
$back_url=CustomHelper::BackUrl(); 
$routeName = CustomHelper::getAdminRouteName();

$old_name = app('request')->input('name');
$old_email = app('request')->input('email');
$old_phone = app('request')->input('phone');
$old_status = (request()->has('status'))?request()->status:1; 
/*if(empty($old_phone) && empty($old_email)){
    $old_status = (request()->has('status'))?request()->status:1; 
}*/
$old_from = app('request')->input('from');
$old_to = app('request')->input('to');
?>
<div class="top_title_admin">
    <div class="title">
    <h2>Manage Customers ({{$users->total()}})</h2>
    </div>
    <div class="add_button">
    <!-- <a href="{{ route($routeName.'.register-users.add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Register User</a> -->
    @if(CustomHelper::checkPermission('users','export'))
    <button type="button" onclick="exportList('export_xls')" class="btn_admin" ><i class="fa fa-table"></i> Export XLS</button>
    @endif
    </div>
    </div>
    <div class="centersec">
 <div class="bgcolor">
         <form name="exportForm" method="" action="" >
                {{ csrf_field() }}
                <input type="hidden" name="export_xls" value="">
                <?php
                if(count(request()->input())){
                    foreach(request()->input() as $input_name=>$input_val){
                        ?>
                        <input type="hidden" name="{{$input_name}}" value="{{$input_val}}">
                        <?php
                    }
                }
                ?>
            </form>
        @include('snippets.errors')
        @include('snippets.flash')
       
                    <div class="table-responsive">
                        <form class="form-inline" method="GET">
                            <div class="col-md-3">
                                <label>Name:</label><br/>
                                <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                            </div>
                            <div class="col-md-3">
                                <label>Email:</label><br/>
                                <input type="text" name="email" class="form-control admin_input1" value="{{$old_email}}">
                            </div>
                            <div class="col-md-3">
                                <label>Phone:</label><br/>
                                <input type="text" name="phone" class="form-control admin_input1" value="{{$old_phone}}">
                            </div>
                            <div class="col-md-2">
                                <label>Status:</label><br/>
                                <select name="status" class="form-control admin_input1">
                                    <option value="">--Select--</option>
                                    <option value="1" {{ ($old_status == '1')?'selected':'' }}>Active</option>
                                    <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                                </select>
                            </div>
                            <!-- <div class="clearfix"></div> -->
                            <!-- <div class="col-md-3">
                                <label>From Date:</label><br/>
                                <input type="text" name="from" class="form-control admin_input1 to_date" value="{{$old_from}}" autocomplete="off" >
                            </div>
                            <div class="col-md-3">
                                <label>To Date:</label><br/>
                                <input type="text" name="to" class="form-control admin_input1 from_date" value="{{$old_to}}" autocomplete="off" >
                            </div> -->

                            <div class="col-md-12 d_flex">
                                <button style="margin-right:15px" type="submit" class="btn btn-success">Search</button>

                                <a href="{{url($routeName.'/register-users')}}" class="btn_admin2" >Reset</a>
                            </div>
                        </form>
                    </div>
                </div>
        <?php
        if(!empty($users) && count($users) > 0){
            ?>

            <div class="table-responsive">           
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Date Created</th>
                        <th>Action</th>
                        @if(CustomHelper::checkPermission('users','edit'))
                        <th>Login As <br> Customer</th>
                        @endif
                    </tr>
                    <?php
                    foreach($users as $user){
                        ?>
                        <tr>
                            <td><?php echo $user->name; ?></td>
                            <td><?php echo $user->email; ?></td>
                            <td>
                                <?php
                                if($user->phone) {
                                    $phone = '+'.$user->country_code.'-'.$user->phone;
                                    echo $phone; 
                                }
                                ?>
                            </td>
                            <td>
                                <?php if($user->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                                <?php } ?>
                            </td>
                            <td>
                               <?php echo $created_at = CustomHelper::DateFormat($user->created_at, 'd F Y'); ?>
                            </td>
                            <td>
                                {{-- <a href="{{route($routeName.'.register-users.view',[$user['id'], 'back_url'=>$back_url])}}" title="View"><i class="fas fa-eye"></i></a> --}}
                                 @if(CustomHelper::checkPermission('users','view'))
                                <a href="javascript:void(0);" data-src="<?php echo route($routeName.'.register-users.view',[$user['id']]) ?>" data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                @endif
                                @if(CustomHelper::checkPermission('users','edit'))
                                <a href="{{ route($routeName.'.register-users.edit', $user->id.'?back_url='.$back_url) }}" title="Edit Blog"><i class="fas fa-edit"></i></a>
                                <a href="{{ route($routeName.'.register-users.wallet', $user->id.'?back_url='.$back_url) }}" title="User Wallet"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 101.33" style="width: 16px; position: relative; top: 0.2rem;fill: #009688;enable-background:new 0 0 122.88 101.33" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;}</style><g><path class="st0" d="M90.62,33.32h18.4v-2.79c-2.88-10.73-10.2-10.66-19.25-10.57c-1.49,0.02-2.84,0.03-2.92,0.03H18.07 c-1.58,0-2.86-1.28-2.86-2.86c0-1.58,1.28-2.86,2.86-2.86h68.78c2.03,0,2.46,0,2.87-0.01c7.74-0.08,14.5-0.15,19.3,4.38v-1.31 c0-3.2-1.31-6.1-3.42-8.21c-2.11-2.11-5.02-3.42-8.21-3.42H17.34c-3.2,0-6.1,1.31-8.21,3.42c-2.11,2.11-3.42,5.02-3.42,8.21v66.64 c0,3.2,1.31,6.1,3.42,8.21c2.11,2.11,5.02,3.42,8.21,3.42h80.04c3.2,0,6.1-1.31,8.21-3.42c2.11-2.11,3.42-5.02,3.42-8.21v-9.46 h-18.4c-5.55,0-10.6-2.27-14.25-5.92c-3.65-3.65-5.92-8.7-5.92-14.25v-0.87c0-5.55,2.27-10.6,5.92-14.25 C80.02,35.59,85.06,33.32,90.62,33.32L90.62,33.32z M114.73,33.43c2.07,0.31,3.92,1.29,5.33,2.71c1.74,1.74,2.81,4.14,2.81,6.78 v21.6c0,2.76-1.12,5.26-2.93,7.07c-1.39,1.39-3.2,2.38-5.21,2.76v9.63c0,4.77-1.95,9.11-5.09,12.25 c-3.14,3.14-7.48,5.09-12.25,5.09H17.34c-4.77,0-9.11-1.95-12.25-5.09C1.95,93.1,0,88.76,0,83.99V17.34 c0-4.77,1.95-9.11,5.09-12.25C8.23,1.95,12.57,0,17.34,0h80.04c4.77,0,9.11,1.95,12.25,5.09c3.14,3.14,5.09,7.48,5.09,12.25V33.43 L114.73,33.43z M88.14,46.11c4.05,0,7.33,3.28,7.33,7.33c0,4.05-3.28,7.33-7.33,7.33c-4.05,0-7.33-3.28-7.33-7.33 C80.81,49.39,84.09,46.11,88.14,46.11L88.14,46.11z"></path></g></svg></a>
                                @endif
                                @if(CustomHelper::checkPermission('users','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$user->id}}" title="Delete User"><i class="fas fa-trash-alt"></i></i></a>
                                <form method="POST" action="{{ route($routeName.'.register-users.delete', $user->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove to this customer?');" id="delete-form-{{$user->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="banner_id" value="<?php echo $user->id; ?>">
                                </form>
                                @endif
                            </td>
                            <td>
                                @if(CustomHelper::checkPermission('users','edit'))
                                <a href="{{route($routeName.'.register-users.login',['user_id'=>$user->id])}}" target="_blank" title="Login as Customer"  onclick="if (confirm('Are you sure you want to Login as Customer?')){return true;}else{event.stopPropagation(); event.preventDefault();};"><i class="fa fa-sign-in"></i></a> 
                                @endif
                            </td>
                        </tr>
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
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
<script type="text/javaScript">
    $( function() {
        $( ".to_date, .from_date" ).datepicker({
            'dateFormat':'dd/mm/yy'
        });
    });
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });

   $('.sbmtLoginUser').click(function(){
        var id = $(this).attr('id');
        $("#login-form-"+id).submit();
    });

function exportList(exportName){
    if(exportName ){
        if( exportName == 'export_xls'){
            var exportForm = $("form[name='exportForm']");

            exportForm.find("input[name='export_xls']").val('1');
            exportForm.find("input[name='export_inventory']").val('');
            document.exportForm.submit();
        }
    }
}
</script>

@endslot
@endcomponent