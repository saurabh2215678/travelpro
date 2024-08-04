<?php
$routeName = CustomHelper::getAdminRouteName();
if(isset($package) && !empty($package)) {
    // prd($record->toArray());
    $package_id = $package->id;
    $price_category_elements = $package->packagePriceCategory->priceCategoryElements??[];
    $status = (isset($record) && !empty($record))?$record->status:'1';
    $is_partial_amount = (isset($record) && !empty($record))?$record->is_partial_amount:0;
    $is_partial_amount_b2b = (isset($record) && !empty($record))?$record->is_partial_amount_b2b:0;
    $is_book_without_payment = (isset($record) && !empty($record))?$record->is_book_without_payment:0;
    $is_book_without_payment_b2b = (isset($record) && !empty($record))?$record->is_book_without_payment_b2b:'';
    $is_default = (isset($record) && !empty($record))?$record->is_default:'';

    $category_choices_record = (isset($record) && isset($record->category_choices_record))?json_decode($record->category_choices_record,true):"";
    $show_choices_record = (isset($record) && isset($record->show_choices_record))?json_decode($record->show_choices_record,true):"";
    $online_booking = CustomHelper::isOnlineBooking();
    ?>
    <style type="text/css">
        .bgcolor{ background:#F3F3F3;}
        .bgcolor1{ background:#fff; margin:15px 0;}
        .titlebgs{ background:#0A3F69; padding:10px 15px; color:#fff;}
        .fullrow {display:inline-block; background:#fff; width:100%;}
        .p15{padding: 15px;}
        .p15 p{margin-bottom: 10px; margin-top: 10px;}
        #packageprice_form:has(.listItem:nth-child(2)) .listItem:first-child .removeItem { display: block; }
        .listItem { display: flex; flex-wrap: wrap;}
        .multiselect+.select2 { width: 100%!important; }
        .addMore *, .removeItem *{pointer-events: none;}
        [isvisible=No] { display: none; }
        .listItem .addMore { display: none; }
        .listItem:first-child .removeItem {display: none;}
        .listItem button.removeItem{
            background: #af111c;
            color: #fff;
            transition: all ease 0.5s;
            padding: 5px 10px;
            border: 0;
            border-radius:3px;
        }
        .listItem .controls {display: flex;column-gap: 10px;align-items: center;}
        .listItem button.removeItem:hover{background:#af111cbf;}
        .listItem:last-child .addMore{
            display:block;
            background: #162e44;
            color: #fff;
            transition: all ease 0.5s;
            padding: 5px 10px;
            border: 0;
            border-radius:3px;
        }
        .listItem:last-child .addMore:hover{background:#00b2a7;}
        div#txtAmount input.form-control {width: auto;display: inline-block;}
        div#txtAmountB2B input.form-control {width: auto;display: inline-block;}
        .form-group.fix-height .bgcolor1 {min-height: 330px;padding-bottom: 15px;}
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <form id="packageprice_form" role="form" class="" onSubmit="return validate_packageprice_form();">
        <input type="hidden" name="message" id="message">
        <div class="sform col-sm-12">
            <div class="form-group">
                <input type="hidden" name="package_id" class="form-control" value="<?php echo $package_id;?>" />
            </div>
<?php /*$display_accommodation_type = $this->general_functions->getTableData("tbl_package","package_id",$package_id,"display_accommodation_type");
if(!empty($display_accommodation_type)){
?>
<div class="form-group">
<div class="col-sm-7">Accommodatin Category will not displayed on website.</div>
</div>
<?php
} */?>
<div class="form-group">
    <label for="hotel_category" class="col-sm-12 required">Price Title</label>
    <div class="col-sm-12">
        <input type="text" name="title"  class="form-control" value="{{$record->title??''}}" placeholder="Price Title" />
    </div>
</div>
<div class="form-group">
    <div class="col-sm-12">
    <label class="" style="padding-top:10px;">Internal Notes</label>
        <textarea name="description" class="form-control" placeholder="Note" rows="1">{{$record->description??''}}</textarea>
    </div>
</div>
<?php if($package->is_activity == 0) { ?>
<div class="form-group">
<label class="col-sm-12" style="padding-top:10px;">Select Days & Hotels</label>
<div class="clearfix"></div>
    <div class="listItemWrap">
        <?php $i=1;
            $total_itinerary =[];
            $accommodation_data =[];
            $selected_itinerary_data =[];
            $HotelList =[];
            // prd($accommodations_itenerary);
            if(isset($accommodations_itenerary) && !empty($accommodations_itenerary) && $accommodations_itenerary->count() > 0 ){  ?>
                <?php 
                // prd($accommodations_itenerary);
                foreach ($accommodations_itenerary as $key => $accommodations_itenerary_row) {
                    $selected_itinerary_data = $accommodations_itenerary_row->itenary_data ;
                    $selected_itinerary_data = json_decode($selected_itinerary_data);
                    $total_itinerary[] = $selected_itinerary_data;
                    $accommodation_data = $accommodations_itenerary_row->accommodation_data ;
                    $accommodation_data = json_decode($accommodation_data);
                    // prd($accommodation_data);
                    $HotelList   = CustomHelper::getSelectedAccommodation($selected_itinerary_data);
                 ?>
                    <div class="form-group listItem" dataitem="<?php echo $i; ?>">
                        
                        <div class="col-sm-4">
                            <select 
                            class="multiselect" name="accommodation_itenary[{{$i}}][]" 
                            dataName="day" 
                            id="day_<?php echo $i; ?>" 
                            multiple="multiple" 
                            placeHolder="Select Day"
                            defalut-selected='<?php echo json_encode($selected_itinerary_data); ?>'
                            >
                            <?php foreach($itinerary_data as $iti_data) { ?>

                            <?php } ?>
                            </select>
                            <span style="display: none;">
                                {{$iti_data->Location->name??''}}
                            </span>
                        </div>
                        <div class="col-sm-5">
                            <select class="multiselect hotel_list" name="package_accomodation[{{$i}}][]" dataName="hotel" multiple="multiple" placeHolder="Select Hotel">
                                <?php foreach ($HotelList as $key => $HotelRow) {
                                 ?>
                                  <option value="{{$HotelRow->id}}" <?php  if(!empty($accommodation_data) && is_array($accommodation_data) && in_array($HotelRow->id, $accommodation_data)){ echo "selected";} ?>>{{$HotelRow->name}}</option>
                                <?php } ?>

                            </select>
                        </div>
                        <div class="controls col-sm-2" style="margin-top:5px;">
                            <button class="addMore" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                            <button class="removeItem" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
                        </div>
                    </div>
                <?php $i++; } ?>
            <?php }else{ ?>
                <!-- ===For New Data== -->

                <div class="form-group listItem" dataitem="<?php echo $i; ?>">
                        <!-- <div class="col-sm-1"></div> -->
                        <div class="col-sm-4">
                            <select 
                            class="multiselect" name="accommodation_itenary[{{$i}}][]" 
                            dataName="day" 
                            id="day_<?php echo $i; ?>" 
                            multiple="multiple" 
                            placeHolder="Select Days"
                            defalut-selected='<?php echo json_encode($selected_itinerary_data); ?>'>
                                <?php foreach($itinerary_data as $iti_data) { ?>

                                <?php } ?>
                            </select>
                            <span style="display: none;">
                                {{$iti_data->Location->name??''}}
                            </span>
                        </div>
                        <div class="col-sm-5">
                            <select class="multiselect hotel_list" name="package_accomodation[{{$i}}][]" dataName="hotel" multiple="multiple" placeHolder="Select Hotel">
                                <?php foreach ($HotelList as $key => $HotelRow){ ?>
                                  <option value="{{$HotelRow->id}}" <?php  if(in_array($HotelRow->id, $accommodation_data)){ echo "selected";} ?>>{{$HotelRow->name}}</option>
                                <?php } ?>

                            </select>
                        </div>
                        <div class="controls col-sm-2">
                            <button class="addMore" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                            <button class="removeItem" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
                        </div>
                    </div>
                <!-- ===== -->
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <div class="clearfix"></div>
@if($online_booking)
<div class="form-group">
    <div class="form-group bgcolor" style="margin-bottom: 0;"> 
        <div class="row">
            <div class="col-sm-12">
                <div class="bgcolor p15">
                    <h2>For B2C</h2>
                    <div class="row2">
                        <div class="col-sm-3">
                            <input id="isAmountSelected" type="checkbox" name="is_partial_amount" value="1"  {{($is_partial_amount == 1)?'checked':''}} /> Allow Partial Booking 
                        </div>
                        <div class="col-sm-6" id="txtAmount" <?php if($is_partial_amount == 1){}else{?>style="display:none"<?php } ?> >
                            <div class="col-sm-4" style="text-align: right;padding-right: 0;">
                                <label class="b-price required" style="padding-top:10px;">Partial Booking Price: </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="booking_price" class="form-control" value="{{$record->booking_price??''}}" placeholder="Partial Booking Price" />
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <input type="checkbox" name="is_book_without_payment" value="1" {{($is_book_without_payment == 1)?'checked':''}} /> Allow booking without payment
                        </div>
                    </div>
                    @if(CustomHelper::isAllowedModule('agentuser'))
                    <div class="clearfix"></div>
                    <h2>For B2B</h2>
                    <div class="row2">
                        <div class="col-sm-3">
                            <input id="isAmountSelectedB2B" type="checkbox" name="is_partial_amount_b2b" value="1"  {{($is_partial_amount_b2b == 1)?'checked':''}} /> Allow Partial Booking 
                        </div>
                        <div class="col-sm-6" id="txtAmountB2B" <?php if($is_partial_amount_b2b == 1){}else{?>style="display:none"<?php } ?> >
                            <div class="col-sm-4" style="text-align: right;padding-right: 0;">
                                <label class="b-price required" style="padding-top:10px;">Partial Booking Price: </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="booking_price_b2b" class="form-control" value="{{$record->booking_price_b2b??''}}" placeholder="Partial Booking Price" />
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <input type="checkbox" name="is_book_without_payment_b2b" value="1" {{($is_book_without_payment_b2b == 1)?'checked':''}} /> Allow booking without payment
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-12">
                        <div class="alert alert-warning" role="alert" style="margin-top: 12px;padding: 5px;margin-bottom: 0;">
                            <b>Note :</b> <i class="fa fa-lightbulb-o fa-1x"></i> If booking price more than zero then booking price will display for customers.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @endif
    <div class="clearfix"></div>
      <div class="form-group fix-height"> 
        <div>
            <?php if(!empty($price_category_elements)){
             foreach($price_category_elements as $pce){
              ?>
                <div class="col-sm-6">
                    <div class="bgcolor1">
                        <div class="titlebgs form-group" style="display: flex;" for="pce<?php echo $pce->id; ?>">
                        <div class="col-sm-4" style="margin-top: 5px;"><?php echo $pce->input_label; ?></div>
                        <div class="col-sm-4"> <label><input type="checkbox" name="pce<?php echo $pce->id; ?>_required" value="1"> Required</label></div>
                        <div class="col-sm-4"><label class="pull-right">
                            <input type="radio" name="pce<?php echo $pce->id; ?>_default" value="pce<?php echo $pce->id; ?>_null" checked> Hide Option</label>
                        </div>
                            <input type="hidden" name="pce<?php echo $pce->id; ?>_null" value="" >
                        </div>
                        
                        <?php $input_choices = json_decode($pce->input_choices);
                        if($input_choices){ ?>
                            <div class="fullrow">                         
                              <div class="col-sm-5">
                                 <strong style="font-weight: 800;">Total person</strong>
                             </div>
                             <div class="col-sm-4">
                             <strong style="font-weight: 800;">Price per person</strong>
                              </div>
                              <div class="col-sm-3 text-center">
                              <strong style="font-weight: 800;">Select default </strong>
                              </div>
                          </div>
                         <?php foreach($input_choices as $ic){ ?>
                            <div class="fullrow">
                                <label class="col-sm-5 required"><?php echo $ic; //echo $ic.' '.$pce->input_label.' price'; ?></label>
                                <div class="col-sm-4">
                                    <input type="number" required="required" name="pce<?php echo $pce->id; ?>_<?php echo $ic; ?>"  class="form-control pce error_remove" value="{{ (isset($category_choices_record) && !empty($category_choices_record) && isset($category_choices_record['pce'. $pce->id])) ? $category_choices_record['pce'. $pce->id][$ic] : '' }}"  placeholder="<?php echo $ic; //echo $ic.' '.$pce->input_label.' price'; ?>">
                                </div>
                                <div class="col-sm-3 text-center">
                                    <input type="radio" name="pce<?php echo $pce->id; ?>_default" value="pce<?php echo $pce->id; ?>_<?php echo $ic; ?>" {{(isset($show_choices_record) && !empty($show_choices_record) && isset($show_choices_record['pce'. $pce->id]) && $show_choices_record['pce'. $pce->id]['default'] == 'pce'.$pce->id.'_'.$ic)?'checked':''}} >
                                </div>
                            </div>
                        <?php } }else{ ?>
                            <div class="col-sm-12">
                              <p><em><b>This is input box and do not require dropdown values</b></em></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } } ?>
            <div class="clearfix" > </div>

            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12"> 
                        <label for="is_default"><input name="is_default" type="checkbox" value="1" {{($is_default == 1)?'checked':''}} > Is Default Package Price</label>
                    </div>
                </div>

                <div class="form-group hide">
                    <div class="col-sm-4 validity"> 
                        <label for="price_validity">Price Validity</label>
                    </div>
                    <div class="col-sm-3"> 
                        <label><input name="price_validity" type="radio" value="default" checked > Default</label>
                    </div>    
                    <div class="col-sm-4"> 
                        <label><input name="price_validity" type="radio" value="specific" > Specific Period</label>
                    </div>
                </div>

                <div class="col-sm-12 classDateDiv hidediv hide">
                    <div id="dateDiv" class="row">
                        <div class="col-sm-3" id="divfromDate_1" >
                            <input type="text" name="fromDate_1" placeholder="From Date*" class="form-control datepicker" value="" />
                        </div>
                        <div class="col-sm-3" id="divtoDate_1" >
                            <input type="text" name="toDate_1" placeholder="To Date*"  class="form-control datepicker" value="" />
                        </div>
                        <div class='col-sm-6' id='divtoIdentifier_1' ><input type='text' class='form-control' name='iden_1' id='iden_1' placeholder='Identifier'> </div>
                    </div>
                </div>

                <div class="col-sm-12 classDateDiv hidediv hide">
                    <div class="form-group">
                        <input type="hidden" name="countVal" value="1" id="countVal">
                        <input type="button" name="add_more" class="btn" onclick="addMore();" value="+ Add More"> 
                    </div>
                </div>


                <div class="form-group">

                    <div class="col-sm-6{{ $errors->has('status') ? ' has-error' : '' }}">
                        <label class="control-label">Status:</label>
                        &nbsp;&nbsp;
                        Active: <input type="radio" name="status" value="1" {{($status == '1')?'checked':''}} >
                        &nbsp;
                        Inactive: <input type="radio" name="status" value="0" {{($status == '0')?'checked':''}} >
                    </div>
                    
                    <!-- <div class="col-sm-3"> 
                        <label for="sort_order">Display Order</label>        
                        <input type="text" name="sort_order" class="form-control sort_order" value="{{$record->sort_order??''}}"  />
                    </div> -->

                    <div class="col-sm-5">
                        <label>&nbsp;</label>
                        <input type="submit" name="submit" class="btn btn-success btnSubmit btn_admin">
                        <!-- <button id="price_form_save" ><i class="fa fa-save"></i> Save</button> -->
                        <a href="javascript:void(0);" class="btn_admin2 slide_close" title="Cancel">Cancel</a>
                        <input type="hidden" name="data_id" value="{{$record->id??''}}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</form>
    <script>

$('#isAmountSelected').click(function() {
    $("#txtAmount").toggle(this.checked);
});

$('#isAmountSelectedB2B').click(function() {
    $("#txtAmountB2B").toggle(this.checked);
});



        var data = [
        <?php foreach($itinerary_data as $iti_data) { 
           ?>
            {
                id: {{$iti_data->id}},
                text: '{{$iti_data->day_title}}',
            },
    <?php } ?>  
    ];
    </script>
<script type="text/javascript">

Array.prototype.diff = function(a) {
    return this.filter(function(i) {return a.indexOf(i) < 0;});
};

function addMore() {
    //alert('Hi');
    var oldCount=document.getElementById('countVal').value;
    var newCount=+oldCount+1;
    document.getElementById('countVal').value=newCount;
    $('#dateDiv').append("<div class='col-sm-3' id='divfromDate_"+newCount+"' ></label><input type='text' placeholder='From Date*' name='fromDate_"+newCount+"'  class='form-control datepicker' id='fromDate_"+newCount+"' value=''/></div><div class='col-sm-3' id='divtoDate_"+newCount+"' ><input type='text' placeholder='To Date*' name='toDate_"+newCount+"'  class='form-control datepicker' id='toDate_"+newCount+"' value=''/></div><div class='col-sm-6 removebtn' id='divtoIdentifier_"+newCount+"' ><input type='text' class='form-control' name='iden_"+newCount+"' id='iden_"+newCount+"' placeholder='Identifier'><input id='b_"+newCount+"' type='button' value='x' class='btn' onclick='removeDate();'></div>");
    $('#fromDate_'+newCount).datepicker({dateFormat: 'yy-mm-dd',onSelect: validateDate});
    $('#toDate_'+newCount).datepicker({dateFormat: 'yy-mm-dd',onSelect: validateDate});
}
function removeDate() {
    var count=document.getElementById('countVal').value;

    $('#toDate_'+count).remove();
    $('#divtoDate_'+count).remove();
    $('#divfromDate_'+count).remove();
    $('#fromDate_'+count).remove();
    $('#b_'+count).remove();
    $('#divtoIdentifier_'+count).remove();
    var newCount=+count-1;
    document.getElementById('countVal').value=newCount;
    $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
}

function getData(){
    var allSelectedDay = [];
    $('[dataName=day]').each(function(){
        var values = $(this).val() || [];
        allSelectedDay.push(...values);
    });

    newData = [];
    data.forEach(el=>{
        allSelectedDay.forEach(elm=>{
            if(el.id == elm){
                newData.push(el);
            }
        })
    })
    var dif1 = data.diff(newData);
    return dif1;
}

function initmultiselect(){
    if($('.listItemWrap').find('.multiselect').length > 0){
        $('.listItemWrap').find('.multiselect').each(function(){
            if(!$(this).hasClass('select2-hidden-accessible')){
                var placeHolder = $(this).attr('placeHolder') || 'Select';
                if($(this).attr('dataName') == 'day'){
                    $(this).select2({
                        data: getData(),
                        placeholder: placeHolder
                    });
                    $(this).on('select2:open', handleSelectOpen);
                    $(this).on('select2:opening', handleSelectOpen);
                    $(this).on('change', handledayChange);
                }else{
                    $(this).select2({
                        placeholder: placeHolder
                    });
                }                
                
            }
        });
    }
}
let isFirstLoad = true;
function selectdaymultiSelect(){
    $('.multiselect[defalut-selected]').each(function(){
        var selectedValues = JSON.parse($(this).attr('defalut-selected'));
        $(this).val(selectedValues);
        $(this).trigger('change');
    });
    isFirstLoad = false;
}

function handledayChange(e){
    // alert($(e.target).val());
    if(isFirstLoad){
        return true;
    }
   var days =  ($(e.target).val());
   var _token = '{{ csrf_token() }}';
                    $.ajax({
                        url: "{{ route($routeName.'.packages.getHotelList') }}",
                        type: "POST",
                        data: {days,days},
                        dataType:"JSON",
                        headers:{'X-CSRF-TOKEN': _token},
                        cache: false,
                        beforeSend:function(){
                         $(".ajax_msg").html("");
                     },
                     success: function(resp){
                        if(resp.success){
                            // $(".hotel_list").html(resp.options);
                            $(e.target).closest('.listItem').find(".hotel_list").html(resp.options);
                        }
                    }
                });
        }

function handleSelectOpen(){
    var selectedData = getData();
    setTimeout(() => {
        $(document).find('.select2-results__option--selectable').each(function(){
            if(selectedData.find(el=>el.text == $(this).text())){
                // console.log('yes', $(this));
                $(this).attr('isVisible', 'yes')
            }else{
                // console.log('No', $(this));
                $(this).attr('isVisible', 'No')

            }
        });
    }, 10);
};

setTimeout(()=>{
    initmultiselect();
},400);
setTimeout(()=>{
    selectdaymultiSelect()
},500);

$(document).click(function(e){
    handleAddMore(e);
    handleRemoveItem(e);
    initmultiselect();
});

function handleAddMore (e){
    var element = $(e.target);
    if(element.hasClass('addMore')){
        var clonedItem = element.closest('.listItem').clone();
        clonedItem.find('.select2').remove();
        clonedItem.find('.multiselect').removeAttr("data-select2-id");
        clonedItem.find('.multiselect').removeAttr("tabindex");
        clonedItem.find('.multiselect').removeAttr("aria-hidden");
        clonedItem.find('.multiselect').removeClass("select2-hidden-accessible");
        clonedItem.find('.multiselect').find('option').removeAttr("data-select2-id");
        clonedItem.find('.multiselect').val('');
        var itemdata = parseInt(clonedItem.attr('dataitem'));
        clonedItem.attr('dataitem', itemdata+1);
        clonedItem.find('[dataname=day]').attr('id', `day_${itemdata+1}`);
        clonedItem.find('[dataname=hotel]').attr('id', `hotel_${itemdata+1}`);
        clonedItem.find('[dataname=hotel]').attr('name', `package_accomodation[${itemdata+1}][]`);
        clonedItem.find('[dataname=day]').attr('name', `accommodation_itenary[${itemdata+1}][]`);
        clonedItem.appendTo('.listItemWrap');
    }
}
function handleRemoveItem (e){
    var element = $(e.target);
    if(element.hasClass('removeItem')){
        element.closest('.listItem').remove();
    }
}

</script>
</body>
</html>
<?php } ?>