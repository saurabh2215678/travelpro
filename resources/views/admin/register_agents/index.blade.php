@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<style>
    .toggle-off.btn {display: flex;align-items: center; }
    .select {
        position: relative;
        margin-bottom: 15px;
    }
    .select .selectBtn.toggle {
        border-radius: 3px 3px 0 0;
    }
    .select .selectBtn.toggle:after {
        -webkit-transform: translateY(-50%) rotate(-135deg);
        transform: translateY(-50%) rotate(-135deg);
    }
    .option_type span {
        line-height: normal;
    }
    .dropdown_option select {
        margin-bottom: 0;
    }
    .select .selectDropdown {
        position: absolute;
        top: 100%;
        width: 100%;
        border-radius: 0 0 3px 3px;
        overflow: hidden;
        background: var(--bg1);
        border-top: 1px solid #eee;
        z-index: 1;
        background: #fff;
        -webkit-transform: scale(1, 0);
        transform: scale(1, 0);
        -webkit-transform-origin: top center;
        transform-origin: top center;
        visibility: hidden;
        transition: 0.2s ease;
        box-shadow: 0 3px 3px rgba(0, 0, 0, 0.2);
    }
    .select .selectDropdown .option {
        padding: 8px;
        box-sizing: border-box;
        cursor: pointer;
        font-size: 14px;
    }
    .select .selectDropdown .option:hover {
        background: #ededed;
    }
    .select .selectDropdown.toggle {
        visibility: visible;
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
    }
    .option_type {
        padding: 6px;
        background: #ddd;
        display: flex;
        justify-content: space-between;
    }
    .option_type span i {
        cursor: pointer;
    }
    .option_type span:hover i {
        filter: grayscale(1);
    }
    #smallModal .modal-dialog.modal-sm {
        position: fixed;
        top: 0;
        left: 0;
        background-color: #000000a1;
        width: 100%;
        height: 100%;
        z-index: 99999;
        display: grid;
        place-items: center;
        transition: 0.5s;
        margin: 0;
        pointer-events: none;
    }
    #smallModal .modal-dialog .modal-content {
        pointer-events: all;
    }
    #smallModal .modal-dialog .modal-content .modal-header {
        border-bottom: 0;
        padding: 10px 10px 0;
    }
</style>
@endslot
<?php 
$back_url=CustomHelper::BackUrl(); 
$routeName = CustomHelper::getAdminRouteName();
$old_company_name = app('request')->input('company_name');
$old_company_brand = app('request')->input('company_brand');
$old_company_owner_name = app('request')->input('company_owner_name');
$old_name = app('request')->input('name');
$old_email = app('request')->input('email');
$old_status = (request()->has('status'))?request()->status:1;
$old_status = old('status',$old_status);
$old_approved_agent = (request()->has('approved_agent'))?request()->approved_agent:'';
$old_approved_agent = old('approved_agent',$old_approved_agent);
$old_from = app('request')->input('from');
$old_to = app('request')->input('to');
$group_id = app('request')->input('group_id');
$user_type = app('request')->input('user_type');
?>
<div class="top_title_admin">
    <div class="title">
        <h2>Manage Agents ({{$users->total()}})</h2>
    </div>
    <div class="add_button">
        @if(CustomHelper::checkPermission('agents','add'))    
        <a href="{{ route($routeName.'.register-agents.add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Agent</a>
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
                    <label>Company Name:</label><br/>
                    <input type="text" name="company_name" class="form-control admin_input1" value="{{$old_company_name}}">
                </div>
                <div class="col-md-3">
                    <label>Brand:</label><br/>
                    <input type="text" name="company_brand" class="form-control admin_input1" value="{{$old_company_brand}}">
                </div>
                <div class="col-md-3">
                    <label>Owner:</label><br/>
                    <input type="text" name="company_owner_name" class="form-control admin_input1" value="{{$old_company_owner_name}}">
                </div>
                <div class="col-md-3">
                    <label>Email:</label><br/>
                    <input type="text" name="email" class="form-control admin_input1" value="{{$old_email}}">
                </div>

                <div class="col-md-3">
                    <label>Group:</label><br/>
                    <select name="group_id" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <?php foreach ($agent_groups as $key => $agent_group) { ?>
                            <option value="{{$agent_group->id}}" @selected($group_id == $agent_group->id )>{{$agent_group->name}}</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Status:</label><br/>
                    <select name="status" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Approved Status:</label><br/>
                    <select name="approved_agent" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <option value="0" {{ ($old_approved_agent == '0')?'selected':'' }}>Pending</option>
                        <option value="1" {{ ($old_approved_agent == '1')?'selected':'1' }}>Approved</option>
                        <option value="2" {{ ($old_approved_agent == '2')?'selected':'1' }}>Rejected</option>                            
                    </select>
                </div>
                <div class="col-md-12 d_flex mt15">
                    <button style="margin-right:15px" type="submit" class="btn btn-success">Search</button>
                    <a href="{{route($routeName.'.register-agents.index')}}" class="btn btn_admin2" >Reset</a>
                </div>
            </form>
        </div>
    </div>
    <?php if(!empty($users) && count($users) > 0){ ?>
        <div class="table-responsive">           
            <table class="table table-striped table-bordered table-hover">
                <tr>                       
                    <th>Brand <br />[Company]</th>
                    <th>Owner Name <br />[Agent Name]</th>
                    <th>Email</th>
                    <th>Phone</th>
                    @if(CustomHelper::isOnlineBooking('flight'))
                    <th>Offline Flight</th>
                    @endif
                    <th>Group</th>
                    <th>Wallet Balance</th>
                    <th>Approved</th>
                    <th>Status</th>
                    <th>Action</th>
                    @if(CustomHelper::checkPermission('agents','edit'))
                    <th>Login As <br>Agent</th>
                    @endif
                </tr>
                <?php
                foreach($users as $user) {
                    $is_allowed_offline_flight_inventory = $user->agentInfo->is_allowed_offline_flight_inventory??0;
                    ?>
                    <tr>
                        <td>
                            {{$user->agentInfo->company_brand??'--'}} 
                            <br /><span style="font-size:x-small;">[{{$user->agentInfo->company_name??'--'}}]</span>
                        </td>
                        <td>
                            {{$user->agentInfo->company_owner_name??'--'}} <br /><span style="font-size:x-small;">[{{$user->name??'--'}}]</span>
                        </td>
                        <td>
                            {{$user->email??''}}
                        </td>
                        <td>
                            @if($user->phone)
                            +{{$user->country_code??'91'}}-{{$user->phone??''}}
                            @endif
                        </td>
                        @if(CustomHelper::isOnlineBooking('flight'))
                        <td>
                            <input type="checkbox" class="allow_offline_flight_inventory" data-id="{{$user->id}}" {{($is_allowed_offline_flight_inventory==1)?'checked':''}} value="1" >
                        </td>
                        @endif
                        <td>
                            {{$user->agentGroup->name??''}}
                        </td>
                        <td align="right">
                            {{CustomHelper::getPrice($user->walletBalance()??0)}}
                        </td>
                        <td style="text-align: center;">
                            @if(CustomHelper::checkPermission('agents','edit'))
                            <?php /*<input data-id="" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-type-id="{{$user->id}}" data-toggle="toggle" data-on="Approved" data-off="Disapproved" {{ $user->approved_agent ? 'checked' : '' }} >*/ ?>
                            <div class="row">
                                <div class="col text-center">
                                    <a href="javascript:void(0)" class="approved_agent" data-id="{{$user->id}}" data-approved_agent="{{$user->approved_agent??0}}">
                                        <?php if($user->approved_agent == 2) { ?>
                                            <i class="fas fa-times" style="color:red"></i><br />
                                            <u>Rejected</u>
                                        <?php } else if($user->approved_agent == 1) { ?>
                                            <i class="fas fa-check" style="color:green"></i><br />
                                            <u>Approved</u>
                                        <?php } else { ?>
                                            <i class="fas fa-clock-o" style="color:orange"></i><br />
                                            <u>Pending</u>
                                        <?php } ?>
                                    </a>
                                </div>
                            </div>
                            @else
                            <?php if($user->approved_agent == '2') { ?>
                                <i class="fas fa-times" style="color:red"></i><br />
                                <u>Rejected</u>
                            <?php } else if($user->approved_agent == '1') { ?>
                                <i class="fas fa-check" style="color:green"></i><br />
                                <u>Approved</u>
                            <?php } else { ?>
                                <i class="fas fa-watch" style="color:orange"></i><br />
                                <u>Pending</u>
                            <?php } ?>
                            @endif
                        </td>
                        <td><?php if($user->status == 1){ ?>
                            <span style="color:green">Active</span>
                            <?php   }else{  ?><span style="color:red">Inactive</span>
                        <?php } ?>
                    </td>
                    <td>
                        @if(CustomHelper::checkPermission('agents','view'))
                        <a href="javascript:void(0);" data-src="{{route($routeName.'.register-agents.view',[$user['id'], 'back_url'=>$back_url])}}" data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a> 
                        @endif

                        @if(CustomHelper::checkPermission('agents','edit'))
                        <a href="{{ route($routeName.'.register-agents.edit', $user->id.'?back_url='.$back_url) }}" title="Edit"><i class="fas fa-edit"></i></a>
                        @endif

                        @if(CustomHelper::isOnlineBooking() && CustomHelper::checkPermission('agents','edit'))
                        <a href="{{ route($routeName.'.register-agents.wallet', $user->id.'?back_url='.$back_url) }}" title="Wallet"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 101.33" style="width: 16px; position: relative; top: 0.2rem;fill: #009688;enable-background:new 0 0 122.88 101.33" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;}</style><g><path class="st0" d="M90.62,33.32h18.4v-2.79c-2.88-10.73-10.2-10.66-19.25-10.57c-1.49,0.02-2.84,0.03-2.92,0.03H18.07 c-1.58,0-2.86-1.28-2.86-2.86c0-1.58,1.28-2.86,2.86-2.86h68.78c2.03,0,2.46,0,2.87-0.01c7.74-0.08,14.5-0.15,19.3,4.38v-1.31 c0-3.2-1.31-6.1-3.42-8.21c-2.11-2.11-5.02-3.42-8.21-3.42H17.34c-3.2,0-6.1,1.31-8.21,3.42c-2.11,2.11-3.42,5.02-3.42,8.21v66.64 c0,3.2,1.31,6.1,3.42,8.21c2.11,2.11,5.02,3.42,8.21,3.42h80.04c3.2,0,6.1-1.31,8.21-3.42c2.11-2.11,3.42-5.02,3.42-8.21v-9.46 h-18.4c-5.55,0-10.6-2.27-14.25-5.92c-3.65-3.65-5.92-8.7-5.92-14.25v-0.87c0-5.55,2.27-10.6,5.92-14.25 C80.02,35.59,85.06,33.32,90.62,33.32L90.62,33.32z M114.73,33.43c2.07,0.31,3.92,1.29,5.33,2.71c1.74,1.74,2.81,4.14,2.81,6.78 v21.6c0,2.76-1.12,5.26-2.93,7.07c-1.39,1.39-3.2,2.38-5.21,2.76v9.63c0,4.77-1.95,9.11-5.09,12.25 c-3.14,3.14-7.48,5.09-12.25,5.09H17.34c-4.77,0-9.11-1.95-12.25-5.09C1.95,93.1,0,88.76,0,83.99V17.34 c0-4.77,1.95-9.11,5.09-12.25C8.23,1.95,12.57,0,17.34,0h80.04c4.77,0,9.11,1.95,12.25,5.09c3.14,3.14,5.09,7.48,5.09,12.25V33.43 L114.73,33.43z M88.14,46.11c4.05,0,7.33,3.28,7.33,7.33c0,4.05-3.28,7.33-7.33,7.33c-4.05,0-7.33-3.28-7.33-7.33 C80.81,49.39,84.09,46.11,88.14,46.11L88.14,46.11z"></path></g></svg></a>
                        @endif

                        @if(CustomHelper::checkPermission('agents','delete'))
                        <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$user->id}}" title="Delete User"><i class="fas fa-trash-alt"></i></a>

                        <form method="POST" action="{{ route($routeName.'.register-agents.delete', $user->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove to this Agent?');" id="delete-form-{{$user->id}}">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <input type="hidden" name="banner_id" value="<?php echo $user->id; ?>">

                        </form>
                        @endif
                    </td>
                    <td>
                        @if(CustomHelper::checkPermission('agents','edit'))
                        <a href="{{route($routeName.'.register-users.login',['user_id'=>$user->id])}}" target="_blank" title="Login as Agent"  onclick="if (confirm('Are you sure you want to Login as Agent?')){return true;}else{event.stopPropagation(); event.preventDefault();};"><i class="fa fa-sign-in"></i></a>
                        @endif
                    </td>
                </tr>
            <?php } ?>
        </table>

        <form method="POST" action="" id="approvedAgentForm" onsubmit="return validateApprovedStatus()" >
            <div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Approved Status</h4>
                        </div>
                        <div class="modal-body">
                            <div class="dropdown_option">
                                <select name="approved_agent" class="form-control admin_input1">
                                    <option value="">--Select--</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Rejected</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="user_id" value="">
                                <button type="button" class="btn btn_admin" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn_admin2" id="save_button" >Save changes</button>
                                <button type="button" class="btn btn_admin2" id="processing_button" disabled style="display: none;" >Processing...</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{ $users->appends(request()->query())->links('vendor.pagination.default') }}

<?php } else { ?>
    <div class="alert alert-warning">No Agents found.</div>
<?php } ?>
</div>

</div>

@slot('bottomBlock')
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<link rel="stylesheet" href="{{asset('assets/css/jquery.fancybox.min.css')}}">
<script src="{{asset('/assets/js/jquery.fancybox.min.js')}}"></script>
<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>

<script type="text/javaScript">
    $( function() {
        $( ".to_date, .from_date" ).datepicker({
            'dateFormat':'dd/mm/yy'
        });

        $(document).click(function(){
            $('.selectDropdown').removeClass('toggle');
        });

        $('.selectBtn').click(function(e){
            e.stopPropagation();
            var nextElement = $(this).next('.selectDropdown');
            $('.selectDropdown').not(nextElement).removeClass('toggle');
            nextElement.toggleClass('toggle');
        });

        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0; 
            var id = $(this).attr('data-type-id');
            if(status == 1) {
                var conf = confirm('Are you sure you want to approved Agent?');
            } else {
                var conf = confirm('Are you sure you want to disapproved Agent?');
            }
            if(conf){
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{ route($routeName.".register-agents.updateApproved") }}',
                    data: {'approved_agent': status, 'id': id},
                    success: function(data){
                        console.log(data.success);
                        window.location.reload();
                    }
                });
            } else {
                window.location.reload();
            }
        })

        $(document).on('click','.allow_offline_flight_inventory',function() {
            var user_id = $(this).data("id");
            var checked = false;
            var confirm_message = '';
            if($(this).is(":checked")) {
                checked = true;
                confirm_message = 'Do you want to allow this agent to manage offline flight inventory?';
            } else {
                checked = false;
                confirm_message = 'Revoking permission will disable the agent\'s ability to manage offline flight inventory. Are you sure you want to proceed with this action?';
            }
            var conf = confirm(confirm_message);
            if(conf) {
                var _token = '{{ csrf_token() }}';
                $.ajax({
                    url: "{{route($routeName.'.register-agents.ajax_offline_flight_inventory') }}",
                    type: "POST",
                    data: {user_id,checked},
                    dataType:"JSON",
                    headers:{'X-CSRF-TOKEN': _token},
                    cache: false,
                    beforeSend:function() {

                    },
                    success: function(resp){
                        if(resp.success) {
                        } else {
                            alert(resp.message);
                            $(this).prop("checked", !checked);
                        }
                    }
                });                
            } else {
                $(this).prop("checked", !checked);
            }
        });

        $('.sbmtDelForm').click(function(){
            var id = $(this).attr('id');
            $("#delete-form-"+id).submit();
        });

        $(document).on('click','.approved_agent',function() {
            var user_id = $(this).attr('data-id');
            var  approved_agent = $(this).attr('data-approved_agent');
            $('#approvedAgentForm').find('input[name=user_id]').val(user_id);
            $('#approvedAgentForm').find('select[name=approved_agent]').val(approved_agent);
            $('#smallModal').modal("show");
        });
    });

    function validateApprovedStatus() {
        var id = $('#approvedAgentForm').find('input[name=user_id]').val();
        var status = $('#approvedAgentForm').find('select[name=approved_agent]').val();

        $('#approvedAgentForm').find('#save_button').hide();
        $('#approvedAgentForm').find('#processing_button').show();

        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route($routeName.".register-agents.updateApproved") }}',
            data: {'approved_agent': status, 'id': id},
            success: function(data){
                $('#smallModal').modal("hide");
                window.location.reload();
            }
        });
        return false;
    }

    function exportList(exportName) {
        if(exportName ) {
            if( exportName == 'export_xls') {
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