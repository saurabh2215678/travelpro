@component('admin.layouts.main')

@slot('title')
    Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php 
$back_url=CustomHelper::BackUrl(); 
$routeName = CustomHelper::getAdminRouteName();

$old_name = app('request')->input('name');
$old_email = app('request')->input('email');
$old_status = (request()->has('status'))?request()->status:1; 
$old_from = app('request')->input('from');
$old_to = app('request')->input('to');
$seo_module_config_arr = config('custom.seo_module_config_arr');
?>

<div class="top_title_admin">
    <div class="title">
    <h2>Manage Discount Category ({{$groups->count()}})</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('agents','add'))    
    <a href="{{ route($routeName.'.discount.addcategory').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Discount Category </a>
    @endif
   <?php /* <button type="button" onclick="exportList('export_xls')" class="btn_admin" ><i class="fa fa-table"></i> Export XLS</button> */ ?>
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
                                <label>Status:</label><br/>
                                <select name="status" class="form-control admin_input1">
                                    <option value="">--Select--</option>
                                    <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                                    <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                                </select>
                            </div>

                    <div class="col-md-6 d_flex mt15">
                        <button style="margin-right:15px" type="submit" class="btn btn-success">Search</button>
                        <a href="{{url($routeName.'/discount/category')}}" class="btn_admin2" >Reset</a>
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
                        <th>Module name</th>
                        <th>Discount Category Name</th>
                        <th>Status</th>
                        @if(CustomHelper::checkPermission('agents','edit'))
                        <th>Action</th>
                        @endif
                    </tr>
                    <?php
                    foreach($groups as $group){
                        ?>
                        <tr>
                            <td><?php echo $module_name = isset($seo_module_config_arr[$group->module_name])?$seo_module_config_arr[$group->module_name]:''; ?></td>
                            <td><?php echo $group->name; ?></td>
                            <td><?php if($group->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php }else{ ?><span style="color:red">Inactive</span>
                            <?php } ?>
                            </td>

                            @if(CustomHelper::checkPermission('agents','edit'))          <td>
                            @if(CustomHelper::checkPermission('agents','edit'))
                            <a href="{{ route($routeName.'.discount.editcategory', $group->id.'?back_url='.$back_url) }}" title="Edit Blog"><i class="fas fa-edit"></i></a>
                            @endif
                            
                                <?php /*
                                @if(CustomHelper::checkPermission('groups','delete'))
                                    <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$group->id}}" title="Delete group"><i class="fas fa-trash-alt"></i></i></a>
                                    <form method="POST" action="{{ route($routeName.'.register-groups.delete', $group->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove to this customer?');" id="delete-form-{{$group->id}}">
                                        {{ csrf_field() }}
                                        {{ method_field('POST') }}
                                        <input type="hidden" name="banner_id" value="<?php echo $group->id; ">
                                </form>
                                @endif
                                 */ ?>
                            </td>
                            @endif
                        </tr>
                        <?php } ?>
                </table>
            </div>
          
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