@component('admin.layouts.main')

@slot('title')
    Admin - Manage Annual Report - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php 
$back_url=CustomHelper::BackUrl(); 
$routeName = CustomHelper::getAdminRouteName();

$old_name = app('request')->input('name');
$old_status = app('request')->input('status');
$old_from = app('request')->input('from');
$old_to = app('request')->input('to');
$old_company_id = app('request')->input('company_id');
?>
<div class="row">

    <div class="col-md-12">

        <h2>Manage Teams
           
           @if(CustomHelper::checkPermission('teammanagements','add'))
            <a href="{{ route($routeName.'.teams.add').'?back_url='.$back_url }}" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i> Add Teams</a>
           @endif
        </h2>

        @include('snippets.errors')
        @include('snippets.flash')


        <div class="row">


            <div class="col-md-12">
                <div class="bgcolor">

                    <div class="table-responsive">

                        <?php /*
                        <form class="form-inline" method="GET">
                            <div class="col-md-3">
                                <label>Name:</label><br/>
                                <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
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
                            <div class="col-md-3">
                                <label>From Date:</label><br/>
                                <input type="text" name="from" class="form-control admin_input1 to_date" value="{{$old_from}}" autocomplete="off" >
                            </div>

                            <div class="col-md-3">
                                <label>To Date:</label><br/>
                                <input type="text" name="to" class="form-control admin_input1 from_date" value="{{$old_to}}" autocomplete="off" >
                            </div>

                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn1search">Search</button>

                                <a href="{{url($routeName.'/teams')}}" class="btn resetbtn btn-primary btn1search" >Reset</a>
                            </div>
                        </form>
                        */ ?>

                    </div>
                </div>
            </div>

        </div>

        <?php
        if(!empty($teams) && count($teams) > 0){
            ?>

            <div class="table-responsive">

                <table class="table table-striped">
                    <tr>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    foreach($teams as $team){

                        $brief = CustomHelper::wordsLimit($team->brief,35);
                        ?>

                        <tr>
                            <td><?php echo $team->name; ?></td>
                            <td>{{ $team->designation }}</td>
                            <td>{{ CustomHelper::getStatusStr($team->status) }}</td>

                            <td>
                                @if(CustomHelper::checkPermission('teammanagements','edit'))
                                <a href="{{ route($routeName.'.teams.edit', $team->id.'?back_url='.$back_url) }}" title="Edit Team"><i class="fas fa-edit"></i></a>
                                @endif
                               
                               @if(CustomHelper::checkPermission('teammanagements','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$team->id}}" title="Delete Team"><i class="fas fa-trash-alt"></i></i></a>

                                <form method="POST" action="{{ route($routeName.'.teams.delete', $team->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Report?');" id="delete-form-{{$team->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="banner_id" value="<?php echo $team->id; ?>">

                                </form>
                                @endif
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            {{ $teams->appends(request()->query())->links('vendor.pagination.default') }}


            <?php
        }
        else{
            ?>
            <div class="alert alert-warning">No Team found.</div>
            <?php
        }
        ?>

    </div>

</div>

@slot('bottomBlock')

<link rel="stylesheet" href="{{ url('/css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('/js/jquery-ui.js') }}"></script>

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
</script>

@endslot

@endcomponent