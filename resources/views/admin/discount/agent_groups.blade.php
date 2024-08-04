@component('admin.layouts.main')

@slot('title')
    Admin - {{ $page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php 
$back_url=CustomHelper::BackUrl(); 
$routeName = CustomHelper::getAdminRouteName();
$old_name = app('request')->input('name');
$old_email = app('request')->input('email');
$old_status = (request()->has('status'))?request()->status:1; 
$old_from = app('request')->input('from');
$old_to = app('request')->input('to');
$group_id = app('request')->input('group_id');
$seo_module_config_arr = config('custom.seo_module_config_arr');
?>
<div class="top_title_admin">
    <div class="title">
    <h2>Manage Discount for Agent Groups ({{$modules->count()}})</h2>
    </div>
    <div class="add_button">
    <?php /*<a href="{{ route($routeName.'.discount.add_agent_groups').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Discount Agent Groups </a> 
    <button type="button" onclick="exportList('export_xls')" class="btn_admin" ><i class="fa fa-table"></i> Export XLS</button>
    */ ?>
    </div>
    </div>
    <div class="centersec">
        @if(CustomHelper::checkPermission('agents','add'))
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
                <div class="ajax_success_msg alert alert-success" style="display:none;">Discount Saved data</div>       
                <div class="ajax_error_msg alert alert-danger" style="display:none;">Discount Type is required!</div>       
                <div class="ajax_error_msg_discount alert alert-danger" style="display:none;">Invalid Discount!</div>
                <div class="table-responsive">
                    <div class="col-md-3">
                        <label>Module Name:</label><br/>
                        <select class="form-control select2" name="group_id" id="module">
                            <option value="">Select</option>
                            <?php foreach ($modules as $key => $module) { ?>                   
                                <option value="{{$module->identifier}}">{{$seo_module_config_arr[$module->identifier]}}</option>
                            <?php } ?>
                        </select>                           
                    </div>                
                    <div class="col-md-3 d_flex mt15">
                        <button style="margin-right:15px" id="search" type="submit" class="btn btn-success" onclick="submit()" >Add/Update</button>
                        <a href="{{url($routeName.'/discount/agent_groups')}}" class="btn_admin2" >Reset</a>
                    </div> 
                </div>
                <div class="clearfix"></div>
                <div class="table-responsive">
                    <div class="discount_agent_groups">
                    </div>
                </div>
            </div>
            @endif
            <div class="table-responsive">           
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>Module Name</th>
                        <th>Discount Category</th>
                        <th>Default</th>
                        <th>Discount Type</th>
                        <th>B2C</th>
                        <?php foreach($agent_groups as $agent_group){ ?>
                        <th>B2B ({{$agent_group->name??''}})</th>
                        <?php } ?>
                    </tr>
                    <?php
                    foreach($modules as $module){
                        $tr = false;
                        foreach($discount_groups as $discount_group)
                        {
                            $module_to_group = CustomHelper::module_to_group_data($module->identifier,$discount_group->id); 
                            if($module_to_group && $module_to_group->count() > 0){
                                $tr = true;
                        ?>
                        <tr>
                            <td><?php echo $seo_module_config_arr[$module->identifier]; ?></td>
                            <td><?php echo $discount_group? $discount_group->name:''; ?></td>
                            <?php
                            foreach($module_to_group as $key => $group) {
                                if($key == 0) {
                            ?>
                            <td>{{($group->is_default == 1)?'Default':'---'}}</td>                            
                            <td>{{ucfirst($group->discount_type)}}</td>
                            <td>
                                <?php $agent_group_id = '-1'; if($group->agent_group_id == $agent_group_id) { ?>
                                    {{$group->discount_value}}
                                <?php } ?>
                            </td>
                            <?php
                                }
                                foreach($agent_groups as $agent_group) {
                                    if($group->agent_group_id == $agent_group->id) {
                            ?>
                            <td>{{$group->discount_value}}</td>
                            <?php } } } ?>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                        <?php if($tr){ ?>
                        <tr>
                            <th colspan="5"></th>
                            <?php foreach($agent_groups as $agent_group){ ?>
                            <th></th>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
            </div>
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

/*
$(document).on("click", $('#search'), function(){   
    var module_name = $('#module').val();
    getagentDiscount(module_name);

});

*/

function submit(){
    var module_name = $('#module').val();
    if(module_name != ''){
        getagentDiscount(module_name);
    }else{
        $(".discount_agent_groups").html('');
        $(".ajax_error_msg").html("Please Select module name!");
        $(".ajax_error_msg").show();
        setTimeout( function() {
            $(".ajax_error_msg").hide();
        },3500); 
    }
}

function getagentDiscount(module_name){
    var _token = '{{ csrf_token() }}';
    $.ajax({
        url: "{{ route('admin.discount.add_agent_groups') }}",
        type: "POST",
        data: {module_name:module_name },
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        async: false,
        beforeSend:function(){
            $(".ajax_msg").html("");
            $(".discount_agent_groups").html('');
        },
        success: function(resp){
            if(resp.success){
                if(resp.discount_agent_groups)
                    $(".discount_agent_groups").html(resp.discount_agent_groups);
            }
        }
    });  
}

function save_discoount(group_id) {
    var module_name = $('#module').val();
    var is_default = 0;
    if(document.getElementById('default_'+group_id).checked){
        var is_default = 1;
    }
    var discount_type = $('#discount_type_'+group_id).val();
    if(discount_type == ''){
        $(".ajax_error_msg").html("Discount Type is required!");
        $(".ajax_error_msg").show();
        setTimeout( function() {
            $(".ajax_error_msg").hide();
        },3500); 
        return false;
    }
    let saveButton = $('#discount_type_'+group_id).parent().parent().find('.btn-success');
    if(saveButton.length) {
        saveButton.html('Processing...');
        saveButton.attr('disabled',true);
    }

    var discount_value = $("input[name='discount_value_"+group_id+"[]']").map(function(){return $(this).val();}).get();
    var agent_group = $("input[name='agent_group_"+group_id+"[]']").map(function(){return $(this).val();}).get();
    var _token = '{{ csrf_token() }}';
    $.ajax({
        url: "{{ route('admin.discount.add_agent_groups_discount') }}",
        type: "POST",
        data: {module_name:module_name,group_id:group_id,discount_type:discount_type,agent_group:agent_group,discount_value:discount_value,is_default:is_default },
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        async: true,
        beforeSend:function(){
            $(".ajax_success_msg").hide();
        },
        success: function(resp){
            if(saveButton.length) {
                saveButton.html('Save');
                saveButton.attr('disabled',false);
            }
            if(resp.success && !resp.discount_value_error){
                $(".ajax_success_msg").show();
                setTimeout( function() {
                    $(".ajax_success_msg").hide();
                }, 3500 );                
            }
            if(resp.discount_value_error){
                $(".ajax_error_msg_discount").show();
                setTimeout( function() {
                    $(".ajax_error_msg_discount").hide();
                }, 3500 );                
            }
        }
    });
}
</script>
@endslot
@endcomponent