@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }}(Package) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
        <style>
            .form-control { padding: 6px 12px !important; }
        </style>
    @endslot

<?php
//pr($page);
$routeName = CustomHelper::getAdminRouteName();

$info_title = (isset($package_price_info->title))?$package_price_info->title:'';
//$package_id = (isset($package_price_info->package_id))?$package_price_info->package_id:null;
$discount_type = (isset($package_price_info->discount_type))?$package_price_info->discount_type:"";
$discount = (isset($package_price_info->discount))?$package_price_info->discount:0;
$booking_price = (isset($package_price_info->booking_price))?$package_price_info->booking_price:0;

$is_partial_amount = (isset($package_price_info->is_partial_amount))?$package_price_info->is_partial_amount:0;
$is_book_without_payment = (isset($package_price_info->is_book_without_payment))?$package_price_info->is_book_without_payment:'';
$price_validity = (isset($package_price_info->price_validity))?$package_price_info->price_validity:'';
$description = (isset($package_price_info->description))?$package_price_info->description:'';
$sort_order = (isset($package_price_info->sort_order))?$package_price_info->sort_order:0;
$is_default = (isset($package_price_info->is_default))?$package_price_info->is_default:0;
$category_choices_record = (isset($package_price_info->category_choices_record))?json_decode($package_price_info->category_choices_record,true):"";
$show_choices_record = (isset($package_price_info->show_choices_record))?json_decode($package_price_info->show_choices_record,true):"";
$status = (isset($package_price_info->status))?$package_price_info->status:'';


$package_price_category = (!empty($package->packagePriceCategory)) ? $package->packagePriceCategory : "";
$defPkgAcc = array();
$defPkgAccType = array();
if(!empty($packageAccommodations)){
    foreach($packageAccommodations as $pkgAcc){
        $defPkgAcc[$pkgAcc->itenary_id] = $pkgAcc->accommodation_id;
        $defPkgAccType[$pkgAcc->itenary_id] = $pkgAcc->accommodation_type_id;
    }
}

//$defPkgAccType = json_encode($defPkgAccType);
//prd($category_choices_record);

?>
<?php
$active_menu = "packages".$package_id.'/accommodation';
?>
@if(!empty($package))
    @include('admin.includes.packageoptionmenu')
@endif
    <div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
    </div>
    
<div class="centersec">
    <div class="bgcolor">
        
        
        @include('snippets.errors')
        @include('snippets.flash')

        <div class="ajax_msg"></div>
        <div class="bgcolor">
        <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            <div class="bg_admin col-md-12">
                <div class="bg_admin_inner">
                <h2>Accomodations</h2>

           <div class="form-group col-md-6{{ $errors->has('service_level') ? ' has-error' : '' }}">
                <label for="service_level" class="control-label required">Service Level:</label>
                <select class="form-control select2" name="service_level" >

                    <?php
                    //$service_level = old('service_level',$service_level);
                    //$serviceArr = config('custom.service_level_arr');
                    $serviceArr = CustomHelper::getServiceLevels();
                    if(!empty($serviceArr)){ ?>
                        <option value="">Select</option>
                        <?php
                        foreach ($serviceArr as $serId=>$service){
                            $selected = '';
                            if($serId == $service_level){
                                $selected = 'selected="selected"';
                            }
                        ?>
                        <option value="{{$serId}}" {{$selected}}>{{$service}}</option>
                    <?php }}?>
                </select>
                @include('snippets.errors_first', ['param' => 'service_level'])
            </div>
                     <div class="form-group{{ $errors->has('is_default_without_price') ? ' has-error' : '' }}">
                            <label for="is_default_without_price" class="control-label mt34 ">Is Default Package Price:</label>

                            <input type="checkbox" name="is_default_without_price" id="is_default_without_price" class="form-control" value="1" <?php echo ($is_default_without_price == 1) ? "checked":""; ?>> 
                            @include('snippets.errors_first', ['param' => 'is_default_without_price'])
                        </div>


                <div class="clearfix"></div>
                <?php 
                //prd($package_itenaries);
                if(!($package_itenaries->isEmpty())){
                    foreach ($package_itenaries as $itenary_key => $package_itenary) {
                ?>

                <div class="form-group col-md-6{{ $errors->has('package_accom') ? ' has-error' : '' }}">
                    <label for="package_accom_{{$package_itenary->id}}" class="control-label">{{$package_itenary->day_title}}:</label>
                    <div class="form-group">
                        <select name="package_accomodation[]" id="package_accom_{{$package_itenary->id}}" class="form-control package_accommodations">
                            <option value="">Select Accommodation</option>
                            <?php if(!($accommodations->isEmpty())){
                                foreach($accommodations as $accommodation){
                            ?>
                            <option value="{{ $accommodation->id }}" <?php echo (isset($defPkgAcc[$package_itenary->id]) && ($defPkgAcc[$package_itenary->id]) == $accommodation->id) ? "selected":""; ?>>{{($accommodation->name.' ('.$accommodation->name.') ')}}</option>
                            <?php }} ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="package_accomodation_type[]" id="package_accom_type_{{$package_itenary->id}}" class="form-control accommodation_type">
                            <option value="">Select Accommodation Type</option>
                        </select>
                    </div>
                    <input type="hidden" name="accommodation_itenary[]" value="{{$package_itenary->id}}">
                </div>

                <?php }}else{ ?>
                    No Itenaries Found.
                <?php } ?>

            </div>
        </div>
             
               <!-- CLONE INPUT WORK END -->

<!-- 
            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                <label class="control-label">Status:</label>
                &nbsp;&nbsp;
                Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                &nbsp;
                Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                @include('snippets.errors_first', ['param' => 'status'])
            </div> -->

            <div class="clearfix"></div>
            <div class="form-group col-md-12">
                <input type="hidden" name="sub_total_amount" id="sub_total_amount">
                <input type="hidden" name="final_amount" id="final_amount">
                <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>

                <a href="<?php echo url($routeName.'/packages/'.$package->id).'/accommodation' ?>" class="btn_admin2" title="Cancel">Cancel</a> 
            </form>
        </div>
    </div>
</div>

@slot('bottomBlock')

    <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

    <script type="text/javascript">

        function priceCalculation() {
            var total = 0;
            var discount_type = $('#discount_type').val();
            var discount = $('#discount').val();

            $( ".selPriceRadio:checked" ).each(function( index ) {

                var amount = $(this).parent().siblings().find('.selPriceInput').val();
                var nameAttr = $(this).parent().siblings().find('.selPriceInput').attr('name');
                var nameFieldArr = "";
                if(nameAttr != undefined){
                    var nameFieldArr = nameAttr.split('_');
                }

                if(amount == undefined || amount == ''){
                    amount = 0;
                }
                if(nameFieldArr == ""){
                    var no_of_trippers = 0;
                }else{
                    var no_of_trippers = nameFieldArr[1];
                }
                total = parseInt(total) + (parseInt(amount)*parseInt(no_of_trippers));

            });

            var Ftotal = 0;
            if(discount_type == 1){
                Ftotal = parseInt(total) - parseInt(discount);
            }else{
                Ftotal = parseInt(total) - ((parseInt(total) * parseInt(discount))/100);
            }

            $('#final_amount').val(Ftotal);

            return parseInt(total);
        }

        function populateAccomodationType(){
            var defPkgAccType = JSON.parse('<?php echo json_encode($defPkgAccType); ?>');
            if(defPkgAccType != ""){
                $.each(defPkgAccType, function(index,value) {
                    if(value == 0){
                        value = "";
                    }
                    $('#package_accom_type_'+index).val(value);
                });
            }
        }

        function myfunction(thisElement, accommodation_id){
            //console.log(thisElement)
                var _token = '{{ csrf_token() }}';
                var option = "";
                $.ajax({
                    url: "{{ route($routeName.'.accommodations.ajax_get_accommodation_types') }}",
                    type: "POST",
                    data: {accommodation_id:accommodation_id},
                    dataType:"JSON",
                    headers:{'X-CSRF-TOKEN': _token},
                    cache: false,
                    beforeSend:function(){
                       thisElement.parent().siblings().find('.accommodation_type').html(option);
                   },
                   success: function(resp){
                    if(resp.success){
                        option += '<option value="">Select Accommodation Type</option>';
                        $.each(resp.accommodation_room,function(key,val) {
                            option += "<option value='" + val.id + "'>" + val.accommodation_type + "</option>";
                        });
                        thisElement.parent().siblings().find('.accommodation_type').html(option);
                    }
                    else{
                        option += '<option value="">Select Accommodation Type</option>';
                        thisElement.parent().siblings().find('.accommodation_type').html(option);
                    }
                },
                complete: function(){
                    populateAccomodationType();
                }
                });                
            }

        $('.select2').select2({
            placeholder: "Please Select",
            allowClear: true
        });

        $(document).ready(function() {
            $(".package_accommodations").each(function(index) {
                myfunction($(this), $(this).val());
            });

            $('.package_accommodations').change(function(){
                var thisElement = $(this);
                var accommodation_id = $(this).val();

                myfunction(thisElement, accommodation_id);
            });
        });

        var Preiod_box = `<div class="row show_specific_period_form">
                            <div class="col-sm-3">
                                <input type="text" class="form-control mycalender" placeholder="From Date*" >
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control mycalender" placeholder="To Date*" >
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control"  placeholder="Identifier">
                            </div>
                            <div class="col-sm-2">
                                <div class="cross"><i class="fa fa-times" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>`

           $('.add_more').click(function(){
            $(Preiod_box).appendTo('.hide_specific_period_form')
           })   
            // $( function() {
            //     $( "#datepicker" ).datepicker();
            // } );

           $(document).click(function(e){
               var Element = $(e.target)
               if(Element.hasClass('cross')){
                   Element.closest('.show_specific_period_form').remove();
               }

           })
            $('body').on('focus',".mycalender", function(){
                $(this).datepicker();
            });

    // Auto price Calculation
    var modulePrice = priceCalculation();
    $('#sub_total_amount').val(modulePrice);

    $('.selPriceRadio').click(function(){
        var modulePrice = priceCalculation();
        $('#sub_total_amount').val(modulePrice);
    });

    $('.selPriceInput').blur(function(){
        var modulePrice = priceCalculation();
        $('#sub_total_amount').val(modulePrice);
    });

    $('#discount_type').change(function(){
        var modulePrice = priceCalculation();
        $('#sub_total_amount').val(modulePrice);
    });

    $('#discount').blur(function(){
        var modulePrice = priceCalculation();
        $('#sub_total_amount').val(modulePrice);
    });

    </script>

@endslot

@endcomponent