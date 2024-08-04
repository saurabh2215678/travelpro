 <?php
    // $routeName = CustomHelper::getAdminRouteName();
?>
 <style>
form#frm_slot {
    min-width: 700px;
}

.fancybox-content {
    padding: 25px;
    min-width: 600px;
}

#frm_slot .p_slot:first-child a.col-md-3 {
    padding-top: 28px !important;
}

.slot_section p {
    padding: 0 5px;
    font-size: 16px;
    color: #009688;
}

.slot_section h2 {
    margin-bottom: 0;
    padding-top: 0;
    padding-left: 0;
    padding-bottom: 5px;
}

.slot_section .table>tbody>tr td,
.popup_slot_section tbody tr td {
    font-size: 13px;
}

.counter {
    position: absolute;
    top: -33px;
    right: -3px;
    font-size: 11px;
}

.select2-search--inline {
    background-color: Gainsboro;
    display: none;
}

.select2-selection__rendered {
    display: flex !important;
    overflow: hidden;
    margin-right: 20px;
    margin-bottom: 0;
}

.select2-selection__rendered li {
    display: none !important;
}

.select2-selection__rendered li:nth-child(-n + 2) {
    display: block !important;
}
body {
    background: #ffffff !important;
}
 </style>

 <link href="{{asset('/')}}css/bootstrap.min.css" rel="stylesheet" type="text/css" />
 <link href="{{url('')}}/css/site.css" rel="stylesheet" type="text/css" />
 <link href="{{url('')}}/css/jquery-ui.css" rel="stylesheet" type="text/css" />
 
 <?php 
$getSettingData = CustomHelper::WebsiteSettings("WALLET_PAYMENT_METHODS");
$getInArray = $getSettingData ? json_decode($getSettingData) :[];

// prd($getInArray);

?>

 <div class="slot_section">
     <div class="title22 d_flex" style="align-items: center;"><h2>{{$page_heading}}</h2> <p style="margin-bottom: 0;">(  <?php echo $getUser->agentInfo?$getUser->agentInfo->company_brand:''; ?> )</p></div>
     <!-- @include('snippets.errors') -->
     @include('snippets.flash')
     <form method="post" id="wallet_form" role="form">
         {{csrf_field()}}
         <div class="ajax_msg"></div>
         <table class="table table-striped table-bordered table-hover">
             <tr>
                 <td>
                 <label class="control-label required">Type</label>
                 <div class="{{ $errors->has('type') ? ' has-error' : '' }}">
                     <select name="type" class="form-control admin_input1" id="type">
                         <option value="credit" @if(old('type') == 'credit' ||old('type') == '') selected @endif>Credit</option>
                         <option value="debit" @if(old('type') == 'debit') selected @endif>Debit</option>
                     </select>
                </div>
                 </td>
                 <td style="width: 15rem;">
                     <div class="{{ $errors->has('amount') ? ' has-error' : '' }}">
                         <label class="control-label required">Amount</label>
                         <input type="text" name="amount" value="{{old('amount')}}" class="form-control admin_input1" >
                     </div>
                 </td>

                 <td style="width: 16rem;">
                 <!--    <div style="display:none;" class="credit_method">
                        <label class="control-label">Payment Method</label>
                         <div class="{{ $errors->has('payment_method') ? ' has-error' : '' }}">
                             <select name="payment_method" class="form-control admin_input1" id="payment_method">
                                 <option value="Bank Transfer" @if(old('payment_method') == 'Bank Transfer') selected @endif>Bank Transfer</option>
                                 <option value="Cash" @if(old('payment_method') == 'Cash' || old('payment_method') == '') selected @endif>Cash</option>
                                 <option value="Cheque" @if(old('payment_method') == 'Cheque') selected @endif>Cheque</option>
                             </select>
                        </div>
                    </div> -->


                    @if($getInArray)
                    <div class="credit_method">
                        <label class="control-label required">Payment Method</label>
                         <div class="{{ $errors->has('payment_method') ? ' has-error' : '' }}">
                             <select name="payment_method" class="form-control admin_input1" id="payment_method">
                                 <option value="">--Select--</option>
                                <?php foreach ($getInArray as $key => $value){ 
                                     
                                ?>
                                 <option value="{{$key}}" @if(old('payment_method') == $key) selected @endif>{{$value}}</option>
                                <?php } ?>
                             </select>
                        </div>
                    </div>
                    @endif



                    <div class="debit_method" style="display: none;">
                        <label class="control-label">Order No</label>
                        <div class="{{ $errors->has('payment_method') ? ' has-error' : '' }}">
                            <input type="text" name="order_no" value="{{old('order_no')}}" class="form-control admin_input1">
                        </div>
                    </div>
                 </td>

                 <td style="width: 16rem;">
                     <div class="{{ $errors->has('for_date') ? ' has-error' : '' }}">
                         <label class="control-label">Date</label>
                         <input type="text" id="datepicker" name="for_date" value="<?php if(old('for_date') == ''){ echo date('d/m/Y');} else { echo old('for_date'); } ?>" class="form-control admin_input1">
                     </div>
                 </td>  
                 
                 <td>
                     <div class="{{ $errors->has('comment') ? ' has-error' : '' }}">
                         <label class="control-label">Comment</label>
                         <input type="text" name="comment" value="{{old('comment')}}" class="form-control admin_input1">
                        </div>
                    </td>
                    
                    <input type="hidden" name="user_id" value="{{$id}}" class="form-control admin_input1">
             </tr>
         </table>

         <button id="submitBtn" type="submit" class="btn_admin2 location_form_submit save_submit">Submit</button>
     </form>
 </div>

 <script type="text/javascript" src="{{asset('/js/jquery.min.js')}}"></script>
 <script type="text/javascript" src="{{asset('/js/jquery-ui.js')}}"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script>
    jQuery.support.cors = true;
    if ($("#wallet_form").length > 0) {
        $("#wallet_form").validate({
            submitHandler: function(form) {
                $("#submitBtn").html(
                    'Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>'
                );
                $("#submitBtn").attr("disabled", true);
                form.submit();
            }
        })
    }

    $('#type').change(setTypeInfo);

    function setTypeInfo(){
        var selectedValue = $('#type').val();
        if(selectedValue == 'debit'){
            $('#type').closest('form').find('.credit_method').hide();
            $('#type').closest('form').find('.debit_method').show();
        }else{
            $('#type').closest('form').find('.debit_method').hide();
            $('#type').closest('form').find('.credit_method').show();
        }
    }

    setTypeInfo();

    $(function() {
    $( "#datepicker" ).datepicker({  maxDate: new Date(), dateFormat: "dd/mm/yy", });
  });
    
    </script>