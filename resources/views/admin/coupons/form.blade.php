@component('admin.layouts.main')

@slot('title')
Admin - {{$title}} - {{ config('app.name') }}
@endslot

@slot('headerBlock')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<style>
   /*  .bootstrap-tagsinput { display: block;}
    .slugEdit {
        position: absolute;
        right: 22px;
        top: 26px;
        font-size: 15px;
        opacity: .7;

        .centersec {
    min-height: auto;
}
.counter{
    position: absolute;
    top: -33px;
    right: -3px;
    font-size: 11px;
 }
 .select2-search--inline{
    background-color:Gainsboro;
    display:none;
 }
 .select2-selection__rendered {
    display: flex !important;
    overflow: hidden;
    margin-right: 20px;
    margin-bottom: 0;
  }
  .select2-selection__rendered li { display: none !important;}
  .select2-selection__rendered li:nth-child(-n + 2) { display: block !important;  
   } */

   .editCode {
    position: absolute;
    right: 22px;
    top: 26px;
    font-size: 15px;
    opacity: .7;
  }

</style>
@endslot

<div class="row">
    <div class="col-md-12">
        <h2>{{$heading}}</h2>
        <div class="centersec">
        <div class="bgcolor">
            <!-- @include('snippets.errors') -->
            @include('snippets.flash')

            @if (session('errMsg'))
                <div class="alert alert-danger alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ session('errMsg') }}
                </div>
            @endif

            <?php
            $coupon_id = (isset($coupons->id))?$coupons->id:0;
            $name = (isset($coupons->name))?$coupons->name:'';
            $code = (isset($coupons->code))?$coupons->code:'';
            $type = (isset($coupons->type))?$coupons->type:'';
            $discount = (isset($coupons->discount))?$coupons->discount:'';
            $description = (isset($coupons->description))?$coupons->description:'';
            $use_limit = (isset($coupons->use_limit))?$coupons->use_limit:'';
            $min_amount = (isset($coupons->min_amount))?$coupons->min_amount:'';
            $max_amount = (isset($coupons->max_amount))?$coupons->max_amount:'';
            $max_discount_amt = (isset($coupons->max_discount_amt))?$coupons->max_discount_amt:'';
            $start_date = (isset($coupons->start_date))?$coupons->start_date:'';
            $expiry_date = (isset($coupons->expiry_date))?$coupons->expiry_date:'';
            $status = (isset($coupons->status))?$coupons->status:1;
            $modules = (isset($coupons->modules))?json_decode($coupons->modules,true):[];
            $modules = old('modules',$modules);

            $order_type = config('custom.order_type');

            if(is_numeric($coupon_id) && $coupon_id > 0){
                $action_url = url('admin/coupons/edit', $coupon_id);
            } else {
                $action_url = url('admin/coupons/add');
            }            
            ?>

            <form method="POST" action="{{ $action_url }}" accept-charset="UTF-8" enctype="multipart/form-data"
                role="form">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} col-md-6">
                    <label for="name" class="control-label required">Name:</label>
                    <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" maxlength="255" autocomplete="off" />
                    @include('snippets.errors_first', ['param' => 'name'])
                </div>

                <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }} col-md-6">
                    <label for="code" class="control-label required">Code:</label>
                    <input type="text" id="code" class="form-control" name="code" value="{{ old('code', $code) }}" @if(old('code',$code)) readOnly @endif/>
                    <a @if($coupon_id == 0 && empty(old('code'))) style="display:none" @endif href="javascript:void(0);" class="editCode" id="editCode" title="Edit"><i class="fas fa-edit"></i></a>
                    @include('snippets.errors_first', ['param' => 'code'])
                </div>

                <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }} col-md-6">
                    <label for="type" class="control-label required">Discount Type:</label>
                    <select name="type" class="form-control">
                        <option value="value" <?php echo (old("type")=="value" || $type=="value") ? 'selected':''; ?>>By Value</option>
                        <option value="percentage" <?php echo (old("type")=="percentage" || $type=="percentage") ? 'selected':''; ?>>By Percentage
                        </option>
                    </select>
                    @include('snippets.errors_first', ['param' => 'type'])
                </div>

                <div class="form-group{{ $errors->has('discount') ? ' has-error' : '' }} col-md-6">
                    <label for="discount" class="control-label required"> <span class="percentSign">Discount Amount :</span></label>
                    <input type="text" id="discount" class="form-control" name="discount"
                        value="{{ old('discount', $discount) }}" maxlength="255" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>
                    @include('snippets.errors_first', ['param' => 'discount'])
                </div>

                <div class="form-group{{ $errors->has('max_discount_amt') ? ' has-error' : '' }} col-md-6">
                    <label for="max_discount_amt" class="control-label required">Maximum Discount Amount : </label>
                    <input type="text" name="max_discount_amt" value="{{ old('max_discount_amt', $max_discount_amt) }}" class="form-control" maxlength="255" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>
                    @include('snippets.errors_first', ['param' => 'max_discount_amt'])
                </div>

                <div class="form-group{{ $errors->has('min_amount') ? ' has-error' : '' }} col-md-6">
                    <label for="min_amount" class="control-label required">Minimum Order Amount : </label>
                    <input type="text" name="min_amount" value="{{ old('min_amount', $min_amount) }}" class="form-control" maxlength="255" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"/>
                    @include('snippets.errors_first', ['param' => 'min_amount'])
                </div>

                <?php
                /*
                 <div class="form-group{{ $errors->has('max_amount') ? ' has-error' : '' }} col-md-6">
                    <label for="min_amount" class="control-label ">Max. Amount : </label>

                    <input type="text" name="max_amount" value="{{ old('max_amount', $max_amount) }}" class="form-control"  maxlength="255" />

                    @include('snippets.errors_first', ['param' => 'max_amount'])
                </div>
                 */
                ?>

                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }} col-sm-12">
                    <label for="description" class="control-label">Description : </label>
                    <textarea name="description" class="form-control"
                        maxlength="255">{{ old('description', $description) }}</textarea>
                    @include('snippets.errors_first', ['param' => 'description'])
                </div>

                <div class="form-group{{ $errors->has('use_limit') ? ' has-error' : '' }} col-md-6">
                    <label for="use_limit" class="control-label required">Use Limit : </label>
                    <input type="text" id="use_limit" class="form-control" name="use_limit"
                        value="{{ old('use_limit', $use_limit) }}" maxlength="255" />
                    @include('snippets.errors_first', ['param' => 'use_limit'])
                </div>

                <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }} col-md-6">
                    <label for="start_date" class="control-label required">Start Date : </label>
                    <input type="text" id="start_date" class="form-control strDate" name="start_date"
                        value="{{ old('start_date', ($start_date) ? date('d/m/Y',strtotime($start_date)): date('d/m/Y'))  }}"
                        maxlength="255" />
                    @include('snippets.errors_first', ['param' => 'start_date'])
                </div>

                <div class="form-group{{ $errors->has('expiry_date') ? ' has-error' : '' }} col-md-6">
                    <label for="expiry_date" class="control-label required">Expire Date : </label>
                    <input type="text" id="expiry_date" class="form-control expDate" name="expiry_date"
                        value="{{ old('expiry_date', ($expiry_date) ? date('d/m/Y',strtotime($expiry_date)): '')  }}"
                        maxlength="255" />
                    @include('snippets.errors_first', ['param' => 'expiry_date'])
                </div>


                <?php if(!empty($order_type)){ ?>
                <!-- <input id="chkall" type="checkbox" style="margin-top: 23px;" >Select All -->
                <div  class="form-group col-md-6{{ $errors->has('modules') ? ' has-error' : '' }}">
                <label for="modules" class="control-label required">Modules:</label>
                <select name="modules[]" id="modules" class="form-control admin_input1 select2 myselect" multiple="multiple">
                <?php foreach ($order_type as $k => $v){ 
                    $selected = '';
                    if($modules && in_array($k,$modules)){
                        $selected = 'selected'; } ?>
                        <option value="{{$k}}" {{$selected}} >{{$v}}</option>
                <?php } ?>
                </select>
                @include('snippets.errors_first', ['param' => 'modules'])
                </div>
                <?php } ?>

                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                    <?php $sel_status = old('status', $status); ?>
                    <label for="type" class="control-label required">Status:</label>
                    <br />
                    <input type="radio" name="status" value="1" {{($sel_status == 1)?'checked':''}}>Active
                    &nbsp;&nbsp;
                    <input type="radio" name="status" value="0" {{($sel_status == 0)?'checked':''}}>Inactive
                    </select>
                    @include('snippets.errors_first', ['param' => 'status'])
                </div>

                <div class="col-md-12">
                <div class="form-group">
                        <input type="hidden" name="coupon_id" value="{{$coupon_id}}">
                        <button type="submit" class="btn btn-success" title="Save"><i class="fa fa-save"></i> Submit</button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn_admin2" title="Click here to cancel">Cancel</a>
                    </div>
                </div>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
    </div>
</div>

@slot('bottomBlock')

<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$('.myselect').select2({closeOnSelect: true,  placeholder: "Please Select",}).on("change", function(e) {
    $(this).next('.select2-container').find('.counter').remove();
});
$("#chkall").click(function(){
    if($("#chkall").is(':checked')){
        $("#modules > option").prop("selected", "selected");
        $("#modules").trigger("change");
    } else {
        $("#modules > option").removeAttr("selected");
        $("#modules").trigger("change");
    }
});
        
$(function() {
    $(".strDate").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        minDate: 0,
        yearRange: "1950:0+"
    });

    $(".expDate").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        minDate: 0,
        yearRange: "1950:0+"
    });

});

$(document).on("change", "select[name=type]", function() {
    checkDiscountType();
});

$(document).on("change keyup", "input[name=discount]", function() {
    checkDiscountType();
});

$(document).on("keyup", "input[name=name]", function() {
    <?php if(empty($coupon_id)) { ?>
    writeCode(event);
    <?php } ?>
});

$(document).on("keyup", "input[name=code]", function() {
    var code = $("input[name=code]");
    var codeVal = $("input[name=code]").val();
    code.val(codeVal.replace(/\s+/g, '-').toUpperCase());

});

function checkDiscountType() {
    var type = $("select[name=type]").val();
    var max_discount_amt = $("input[name=max_discount_amt]");
    var max_discount_value = "{{old('max_discount_amt',$max_discount_amt)}}";
    var sign = 'Discount Amount';
    if (type == 'percentage') {
        sign = 'Discount(%)';
        max_discount_amt.val(max_discount_value);
        max_discount_amt.attr("readonly", false);
    } else {
        var discount = $("input[name=discount]").val();
        max_discount_amt.val(discount);
        max_discount_amt.attr("readonly", true);
    }
    $(".percentSign").text(sign);
}

function writeCode(event) {
    var code = $("input[name=code]");
    var codeVal = $("input[name=code]").val();
    if(event.keyCode != 8 && event.keyCode != 38 && event.keyCode != 37 && event.keyCode != 39 && event.keyCode != 40 && event.keyCode != 17 && event.keyCode != 16){
        $("#editCode").show();
        if(codeVal.length <= 5){
            // let randNum = Math.floor((Math.random() * 10) + 1);
            let randNum = (Math.random() * 10).toString(30).substring(4,5);
            code.val(codeVal+randNum.toUpperCase());
            code.attr("readonly", true);
        }
    }else{
        code.val(codeVal);
    }
}
checkDiscountType();

$("#editCode").click(function(){
    if ($('input[name=code]').is('[readonly]') ) { 
        $("input[name=code]").attr('readonly',false);
    } else {
        $("input[name=code]").attr('readonly',true);
    }

});
</script>
@endslot
@endcomponent