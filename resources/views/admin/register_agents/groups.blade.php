@component('admin.layouts.main')

@slot('title')
    Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
 @slot('headerBlock')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <style>
     .toggle-off.btn {display: flex;align-items: center; }
     .agent_group .toggle.btn.btn-danger {min-height: auto;border-radius: 5rem;}
     .agent_group .toggle.btn.btn-danger .btn.btn-danger:hover {background: #8b8b8b;}
    </style>
    @endslot

<?php 
$back_url=CustomHelper::BackUrl(); 
$routeName = CustomHelper::getAdminRouteName();

$old_name = app('request')->input('name');
$old_email = app('request')->input('email');
$old_status = (request()->has('status'))?request()->status:1; 
$old_from = app('request')->input('from');
$old_to = app('request')->input('to');
?>

<div class="top_title_admin">
    <div class="title">
    <h2>Manage Agent Groups ({{$groups->total()}})</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('agents','add'))  
    <a href="{{ route($routeName.'.register-agents.group_add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Agent Group </a>
    @endif
    <!-- <button type="button" onclick="exportList('export_xls')" class="btn_admin" ><i class="fa fa-table"></i> Export XLS</button> -->
    </div>
    </div>


    <div class="centersec agent_group">
 <div class="bgcolor p10">
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
                                <label>Status:</label><br/>
                                <select name="status" class="form-control admin_input1">
                                    <option value="">--Select--</option>
                                    <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                                    <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-5 d_flex mt15">
                                <button style="margin-right:15px" type="submit" class="btn btn-success">Search</button>
                                <a href="{{url($routeName.'/register-agents/groups')}}" class="btn_admin2" >Reset</a>
                            </div>
                        </form>
                    </div>
                </div>
        <?php
        if(!empty($groups) && count($groups) > 0){
            ?>
            <div class="table-responsive">           
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        @if(CustomHelper::checkPermission('agents','edit'))
                        <th>Default <span class="ihover">(i) <span class="hover_text">(When a new agent signup, he will be added to default group and it can be changed by modifying agent)</span></span></th>
                       @endif

                       @if(CustomHelper::checkPermission('agents','edit'))
                        <th>Action</th>
                        @endif
                    </tr>
                    <?php
                    foreach($groups as $group){
                        ?>
                        <tr>
                            <td><?php echo $group->name; ?></td> 
                            <td><?php echo $group->description; ?></td> 
                            <td><?php if($group->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                            </td>

                              @if(CustomHelper::checkPermission('agents','edit'))
                              <td style="text-align: center;">
                                <input data-id="" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-type-id="{{$group->id}}" data-toggle="toggle" data-on="Default" data-off="Make default" {{ $group->is_default ? 'checked' : '' }} >
                            </td>
                            @endif

                            @if(CustomHelper::checkPermission('agents','edit') || CustomHelper::checkPermission('agents','delete')) 
                            <td>
                            @if(CustomHelper::checkPermission('agents','edit'))
                            <a href="{{ route($routeName.'.register-agents.group_edit', $group->id.'?back_url='.$back_url) }}" title="Edit Blog"><i class="fas fa-edit"></i></a>@endif                           
                            
                            @if(CustomHelper::checkPermission('agents','delete'))
                            <?php /*
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$group->id}}" title="Delete group"><i class="fas fa-trash-alt"></i></i></a>

                                <form method="POST" action="{{ route($routeName.'.register-groups.delete', $group->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove to this customer?');" id="delete-form-{{$group->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="banner_id" value="<?php echo $group->id; ?>">

                                </form> */ ?>
                                @endif
                            </td>
                            @endif
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            {{ $groups->appends(request()->query())->links('vendor.pagination.default') }}


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
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
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
<script>
  $(function() {
    $('.toggle-class').change(function() {
        var conf = confirm('Are you sure you want to make default group?');
        if(conf){
        var status = $(this).prop('checked') == true ? 1 : 1; 
        var id = $(this).attr('data-type-id'); 
        //alert('Are you sure you want to make default group?');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route($routeName.".register-agents.changeGroupDefault") }}',
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