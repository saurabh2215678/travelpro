@component('admin.layouts.main')

@slot('title')
Admin - Manage Coupons - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endslot
<?php
$BackUrl = CustomHelper::BackUrl();
$modules = (request()->has('modules'))?request()->modules:[];
$modules = old('modules', $modules);
$old_name = app('request')->input('name');
$old_code = app('request')->input('code');
$old_start = app('request')->input('start');
$old_expiry = app('request')->input('expiry');
$old_status = app('request')->input('status');
$compare_scope = config('custom.compare_scope');
$back_url = (request()->has('back_url'))?request('back_url'):'';

$module_config_arr = config('custom.order_type');

?>
<div class="top_title_admin">
    <div class="title">
        <h2>Coupons ({{ $coupons->total() }})</h2>
    </div>
    <div class="add_button">
        @if (CustomHelper::checkPermission('coupons', 'add'))
        <a href="{{ route('admin.coupons.add') }}" class="btn_admin"><i class="fa fa-plus"></i> Add Coupon</a>
        @endif
    </div>
</div>

<!-- Start Search box section     -->
<div class="centersec">
    <div class="bgcolor ">
        <div class="table-responsive">
            <form class="form-inline" method="GET">

                <div class="col-md-3{{$errors->has('modules')?' has-error':''}}">
                <label for="FormControlSelect1">Modules</label><br/>
                <select name="modules[]" class="form-control admin_input1 select2" multiple="multiple">
                <option value="">--Select Module Name--</option>
                @foreach($module_config_arr as $k => $v)
                <option value="{{$k}}" {{(!empty($modules) && in_array($k,$modules))?'selected':''}} >{{$v}}</option>
                @endforeach
                </select>
                </div>

                <div class="col-md-2">
                    <label class="control-label">Name:</label><br />
                    <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                </div>

                <div class="col-md-2">
                    <label>Code:</label><br />
                    <input type="text" name="code" class="form-control admin_input1" value="{{$old_code}}">
                </div>

                <div class="col-md-2">
                    <label>Start Date:</label><br />
                    <input type="text" name="start" class="form-control admin_input1 pickDate" value="{{$old_start}}"
                        autocomplete="off">
                </div>

                <div class="col-md-2">
                    <label>Expiry Date:</label><br />
                            <input type="text" name="expiry" class="form-control admin_input1 pickDate"
                                value="{{$old_expiry}}" autocomplete="off">
                </div>

                <div class="col-md-2">
                    <label>Status:</label><br />
                    <select name="status" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <option value="1" {{ ($old_status == '1')?'selected':'' }}>Active</option>
                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label></label><br>
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{url('admin/coupons')}}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <!-- End Search box Section -->

    <div class="blog-gap">
        @include('snippets.errors')
        @include('snippets.flash')
        <?php
            if(!empty($coupons) && count($coupons) > 0){
                ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <th>Modules</th>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Max Discount Amt</th>
                    <th>Min Order Amount</th>
                    <th>Used / Limit</th>
                    <th>Start Date</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    @if (CustomHelper::checkPermission('coupons', 'edit') ||
                    CustomHelper::checkPermission('coupons','delete'))
                    <th>Action</th>
                    @endif
                </tr>
                <?php
                        foreach($coupons as $coupon){
                            $start_from = ($coupon->start_date) ? CustomHelper::DateFormat($coupon->start_date, 'd M Y'): '';
                            $expiry_at = CustomHelper::DateFormat($coupon->expiry_date, 'd M Y');
                            ?>

                <tr>
                    <td>
                        {{ $coupon['name']??'' }}
                    </td>
                    <td>
                        <?php
                        $modules = (isset($coupon->modules))?json_decode($coupon->modules,true):[];
                        if(!empty($modules)){
                            $moduleArr = config('custom.order_type');
                        foreach ($modules as $key => $module) {
                            // $moduleName[] = $moduleArr[$module];
                            if(count($modules) > $key+1){
                                $comma = ", ";
                            }else{
                                $comma = "";
                            }
                            echo $moduleArr[$module].$comma;
                        }

                        //echo implode(', ', $moduleName);
                        } ?>
                    </td>
                    <td>
                        {{ $coupon['code']??'' }}
                    </td>
                    <td>
                        {{ $coupon['discount']??'' }} {{ ($coupon['type']=="value") ? '':'%' }}
                    </td>
                    <td>
                        {{ $coupon['max_discount_amt']??'' }}
                    </td>
                    <td>
                        <?php echo $coupon['min_amount']??'';?>
                    </td>
                    <td>
                        <?php echo $coupon->couponUsedCount()??0;?> / <?php echo $coupon['use_limit']??'';?>
                    </td>
                    <td>
                        {{ $start_from }}
                    </td>
                    <td>
                        {{ $expiry_at }}
                    </td>
                    <td>
                        <?php if ($coupon['status'] == 1) { ?>
                        <span style="color:green">Active</span>
                        <?php } else { ?>
                        <span style="color:red">Inactive</span>
                        <?php } ?>
                    </td>

                    @if (CustomHelper::checkPermission('coupons', 'edit') ||
                    CustomHelper::checkPermission('coupons','delete'))
                    <td>
                        @if (CustomHelper::checkPermission('coupons', 'edit'))
                        <a href="{{ route('admin.coupons.edit', [$coupon['id'],  'back_url'=>$BackUrl]) }}"
                            title="Edit"><i class="fas fa-edit"></i></a>
                        @endif
                        &nbsp;
                        @if (CustomHelper::checkPermission('coupons', 'delete'))
                        <a href="javascript:void(0)" class="sbmtDelForm" title="Delete"><i
                                class="fas fa-trash-alt"></i></a>

                        <form method="POST" action="{{ route('admin.coupons.delete', $coupon['id']) }}"
                            accept-charset="UTF-8" role="form"
                            onsubmit="return confirm('Do you really want to delete this coupon?');" class="delForm">
                            {{ csrf_field() }}
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
        {{ $coupons->appends(request()->query())->links('vendor.pagination.default') }}
        <?php
        }
        else{ ?>
        <div class="alert alert-warning">There are no Records at the present.</div>
        <?php
        }
        ?>
    </div>
</div>

@slot('bottomBlock')
<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
$(function() {
    $(".pickDate").datepicker({
        dateFormat: 'dd M yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "1950:0+"
    });
});

$(document).on("click", ".sbmtDelForm", function(e) {
    e.preventDefault();
    $(this).siblings("form.delForm").submit();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $('.select2').select2({
        placeholder: "Select Module Name",
        allowClear: true
      });
</script>
@endslot
@endcomponent