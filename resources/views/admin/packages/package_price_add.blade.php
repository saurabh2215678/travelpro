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
//$package_id = (isset($package_price_info->package_id))?$package_price_info->package_id:0;
$discount_type = (isset($package_price_info->discount_type))?$package_price_info->discount_type:"";
$discount = (isset($package_price_info->discount))?$package_price_info->discount:0;
$booking_price = (isset($package_price_info->booking_price))?$package_price_info->booking_price:0;
$service_level = (isset($package_price_info->service_level))?$package_price_info->service_level:0;
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
$active_menu = "packages".$package_id.'/price-info';
?>
@if(!empty($package_id))
    @include('admin.includes.packageoptionmenu')
@endif
<div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
    </div>

<style>
    .pricebox{display: none;}
    .pricebox.show{display: block;}
    .pricebox .flex_item {width: calc(100% / 4);}
    .admin_flex{flex-wrap: wrap;}
</style>

<div class="centersec">
    <div class="bgcolor">

        @include('snippets.errors')
        @include('snippets.flash')

        <div class="ajax_msg"></div>
        <div class="bgcolor">
        <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            <div class="form-group col-md-4{{ $errors->has('title') ? ' has-error' : '' }}">
                <label for="title" class="control-label required">Price Title:</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title',$info_title) }}" autocomplete="off">  
                @include('snippets.errors_first', ['param' => 'title'])
            </div>

            <div class="form-group col-md-4{{ $errors->has('discount_type') ? ' has-error' : '' }}">
                <label for="discount_type" class="control-label">Discount Type:</label>
                <select name="discount_type" id="pay_mode2" class="form-control">
                    <option value="">Select Discount Type</option>
                    <option value="1" <?php echo ($discount_type == 1) ? 'selected':''; ?>>Flat</option>
                    <option value="2" <?php echo ($discount_type == 2) ? 'selected':''; ?>>Percent</option>
                </select>
                @include('snippets.errors_first', ['param' => 'discount_type'])
            </div>

            <div style="display:none;" id="Discount" class="form-group col-md-4{{ $errors->has('discount') ? ' has-error' : '' }}">
                <label for="discount" class="control-label">Discount:</label>
                <input type="text" name="discount" id="discount" class="form-control" value="{{ old('discount',$discount) }}" autocomplete="off">  
                @include('snippets.errors_first', ['param' => 'discount'])
            </div>

            <div class="clearfix"></div>

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
                                $selected = 'selected';
                            }
                        ?>
                        <option value="{{$serId}}" {{$selected}}>{{$service}}</option>
                    <?php }}?>
                </select>
                @include('snippets.errors_first', ['param' => 'service_level'])
            </div>
            
            <div class="form-group  col-md-12{{ $errors->has('description') ? ' has-error' : '' }}">
                <label for="description" class="control-label">Description / Note:</label>

                <textarea name="description" id="description" class="form-control" ><?php echo old('description', $description); ?></textarea>    

                @include('snippets.errors_first', ['param' => 'description'])
            </div>
            <div style="clear:both"></div>
            <div class="bg_admin col-md-12">
                <div class="bg_admin_inner">
                <h2>Accomodations</h2>

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
                            <option value="{{ $accommodation->id }}" <?php echo (isset($defPkgAcc[$package_itenary->id]) && ($defPkgAcc[$package_itenary->id]) == $accommodation->id) ? "selected":""; ?>>{{ $accommodation->name }}</option>
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

        <div class="bg_admin col-md-12">
            <div class="bg_admin_inner">
               <div class="admin_flex">
                   <div class="flex_item">
                       <strong>Booking Price</strong>
                   </div>

                   <div class="flex_item">
                    <input type="checkbox" id="is_book_without_payment" name="is_book_without_payment" value="1" <?php echo ($is_book_without_payment == 1) ? "checked":""; ?>>
                    <label for="is_book_without_payment">Allow booking without payment</label>
                   </div>

                   <div class="flex_item">
                    <input type="checkbox" id="is_partial_amount" class="amtshow" name="is_partial_amount" value="1" <?php echo ($is_partial_amount == 1) ? "checked":""; ?>>
                    <label for="is_partial_amount">Book with Token amount</label>
                   </div>
                   <div class="flex_item">
                       <input type="text" class="form-control pricebox" name="booking_price" value="{{ old('booking_price', $booking_price) }}" placeholder="0.00" >
                   </div>

               </div>
               <div class="title_bg mt30">If booking price more than zero than booking price will display for customers.2222
            </div>
            </div>
            </div>

            <?php if(!empty($package_price_category)){
                $catElements = $package_price_category->priceCategoryElements;
            ?>
            <div class="bg_admin col-md-12">
                <div class="bg_admin_inner">
                    <div class="row">
                            <?php if(!empty($catElements)){
                                foreach ($catElements as $element) {
                            ?>
                        <!-- LOOP THIS COL-SM-6 DIV -->
                        <div class="col-sm-6">
                            <div class="cost_box">
                                <div class="title_bg mt30">
                                    <div class="admin_flex">
                                        <div class="flex_item">
                                            <!-- <input type="checkbox" name="cost-Per-couple" value="cost-Per-couple"> -->
                                            <label for="cost-Per-couple"> {{ $element->price_label }} <!-- (Required ?) --></label>
                                        </div>
                                        <div class="flex_item">
                                            <input type="radio" class="selPriceRadio" name="pce{{ $element->id }}_default" value="{{ 'pce'.$element->id.'_null'}}" {{ (isset($show_choices_record) && !empty($show_choices_record) && isset($show_choices_record['pce'. $element->id]) && $show_choices_record['pce'. $element->id]['default'] == 'pce'.$element->id.'_null') ? 'checked="checked"' : '' }} >
                                            <label for="hide-price">Hide Price</label><br>
                                        </div>
                                    </div>
                                </div>
                                <?php if(!empty($element->input_choices) && $element->input_type == 'select'){
                                    $choices = json_decode($element->input_choices);
                                    foreach($choices as $stKey => $choice){
                                ?>
                                <div>
                                    <div class="admin_flex bg_whitePD10">
                                        <div class="flex_item">
                                            <strong>{{ $choice.' '.$element->input_label }} price</strong>
                                        </div>
                                        <div class="flex_item">
                                            <input type="number" class="selPriceInput" required="required" name="pce{{ $element->id.'_'.$choice }}" value="{{ (isset($category_choices_record) && !empty($category_choices_record) && isset($category_choices_record['pce'. $element->id])) ? $category_choices_record['pce'. $element->id][$choice] : '' }}" class="form-control" placeholder="0.00" >
                                        </div>
                                       
                                        <div class="flex_item">
                                            <input type="radio" class="selPriceRadio" name="pce{{ $element->id }}_default" value="{{ 'pce'.$element->id.'_'.$choice}}" {{ (isset($show_choices_record) && !empty($show_choices_record) && isset($show_choices_record['pce'. $element->id]) && $show_choices_record['pce'. $element->id]['default'] == 'pce'.$element->id.'_'.$choice) ? 'checked="checked"' : '' }} <?php echo ($stKey == 0 && empty($show_choices_record)) ? "checked":""; ?>>
                                        </div>
                                    </div>
                                </div>
                                <?php }} ?>
                            </div>
                        </div>
                    <!-- LOOP END HERE -->
                        <?php }} ?>
                    </div>
                </div>
            </div>
            <?php } ?>
                <!-- CLONE INPUT WORK START -->
                <div class="bg_admin col-md-12">
                    <div class="bg_admin_inner">
                        <div class="form-group{{ $errors->has('is_default') ? ' has-error' : '' }}">
                            <label for="is_default" class="control-label mt34 ">Is Default Package Price:</label>

                            <input type="checkbox" name="is_default" id="is_default" class="form-control" value="1" <?php echo ($is_default == 1) ? "checked":""; ?>> 
                            @include('snippets.errors_first', ['param' => 'is_default'])
                        </div>
                        <!-- <div class="row">
                        <div class="col-sm-10">

                            <input type="radio" value="default" name="period" checked>
                            <label for="Default"> Default</label>
                            <input type="radio" name="period" value="specific">
                            <label for="specific_period">Specific Period</label>

                            <div class="specific_period_form">
                                <div class="hide_specific_period_form"></div>
                                
                                <div class="add_more">+ Add More</div>
                            </div>
                            </div>
                        </div> -->
                    </div>
                </div>
                      
               <!-- CLONE INPUT WORK END -->

            <div class="form-group  col-md-6{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                <label for="sort_order" class="control-label">Sort Order:</label>

                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                @include('snippets.errors_first', ['param' => 'sort_order'])
            </div>

            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                <label class="control-label">Status:</label>
                &nbsp;&nbsp;
                Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                &nbsp;
                Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                @include('snippets.errors_first', ['param' => 'status'])
            </div>

            <div class="clearfix"></div>
            <div class="form-group col-md-12">
                <input type="hidden" name="sub_total_amount" id="sub_total_amount">
                <input type="hidden" name="final_amount" id="final_amount">
                <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>

                <a href="<?php echo url($routeName.'/packages/'.$package->id).'/price-info' ?>" class="btn_admin2" title="Cancel">Cancel</a>
            </div>
            <br/><br/>
        </form>
        </div>
    </div>
</div>

@slot('bottomBlock')

    <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script>
        $('#pay_mode2').change(function(){
            var myID = $(this).val();
            if(myID == '1' || myID == '2')
            {
                $("#Discount").show();
            }else{
                $("#Discount").hide();
                $("#discount").val(0);
            }
        });

</script>

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


    <script>
        $(".amtshow").click(function(){
            $(".pricebox").toggleClass("show");
        });
    </script>

@endslot

@endcomponent