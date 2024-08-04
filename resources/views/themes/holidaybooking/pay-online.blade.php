@component(config('custom.theme').'.layouts.main')

@slot('title')
{{$meta_title}}
@endslot

@slot('meta_description')
{{ $meta_description ?? ''}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
@endslot


<?php 
 $websiteSettingsArr = CustomHelper::getSettings(['RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY','BOOKING_QUERIES_NO']);
  $country_id = 101;
  $country_id = old('country',$country_id);

  $country_code = old('country_code');
  $country_code = isset($country_code) && !empty($country_code) ? $country_code : 91;
?>
<section class="secpad online-payment inner-page faq faq-cms-block">
         <div class="breadcrumb_full">
            <div class="container">
               <ul class="breadcrumb">
                  <li><a href="{{url('/')}}">Home</a>
                  </li>
               <li><a href="javascript:void(0)">Online Payment</a>
                  </li>
                  
               </ul>
            </div>
         </div>

 <div class="container">
    <div class="onlinepaym">
    <div class="w-1/2">
        <div class="right_img">
        <!-- <div class="son"><h4><img src="{{asset(config('custom.assets').'/images/payment-lock.png') }}" alt=""> Secure Online Payment</h4></div> -->
        <?php if(isset($websiteSettingsArr['BOOKING_QUERIES_NO']) && $websiteSettingsArr['BOOKING_QUERIES_NO'] ) { ?>
           <div class="booking_problem mt-3 on_img">
            <p>Booking Queries? Call: <strong>
                {{$websiteSettingsArr['BOOKING_QUERIES_NO'] ?? ''}}
              </strong></p>
          </div>
        <?php } ?>
        <img src="{{asset(config('custom.assets').'/images/online-form.jpg') }}" alt="">
        </div>
        </div>
    <form class="w-1/2" method="post" action="" name="frmTransaction" id="frmTransaction" >
        <h1 class="title text-2xl mb-5">Online Payment</h1> 
            {{ csrf_field() }}
            @include('snippets.front.flash')
            <div class="form-group row">
                <div class="md:w-1/2">
                <label for="amount">Amount (INR)<span class="required">*</span></label>
                <input type="text" name="amount" id="amount"  class="form_control" value="{{old('amount')}}" placeholder="" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" />
                @include('snippets.front.errors_first', ['param' => 'amount'])
                <span id="amount_error" class="validation_error"></span>
                <!-- <input type="hidden" name="mode" value="TEST" />
                <input type="hidden" name="currency"  value="INR" />    -->
                </div>
                <div class="md:w-1/2">
                <label for="state">Description<span class="required">*</span></label>
                <input id="description" type="text" id="description" name="description"  class="form_control" value="{{old('description')}}" placeholder="" /> 
                @include('snippets.front.errors_first', ['param' => 'description'])
                <span id="description_error" class="validation_error"></span>
                <!--<input type="hidden" name="return_url" value="" /> -->
                </div>
            </div>
            <div class="form-group row">
                <div class="md:w-1/2">
                <label for="phone">Phone<span class="required">*</span></label>
                <select name="country_code" class="form-select country_code">
                  <?php /*{{$country->emoji}}*/ ?>
                  @if($countries)
                  @foreach($countries as $c)
                  <option value="{{$c->phonecode}}" {{($c->phonecode==$country_code)?'selected':''}} >+{{$c->phonecode}}</option>
                  @endforeach
                  @endif
              </select>
              <input type="text" name="phone" id="phone" value="{{old('phone')}}"  class="form_control" maxlength="12" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" required>
              @include('snippets.front.errors_first', ['param' => 'phone'])
              <span id="phone_error" class="validation_error"></span>
                </div>
                <div class="md:w-1/2">
                <label for="email">Email<span class="required">*</span></label>
                <input id="email" type="text" name="email"  class="form_control" value="{{old('email')}}" placeholder=""/>
                @include('snippets.front.errors_first', ['param' => 'email'])
                <span id="email_error" class="validation_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="md:w-1/2">
                <label for="contact_name">Name<span class="required">*</span></label>
                <input id="contact_name" type="text" name="name"  class="form_control" value="{{old('name')}}" placeholder="" />      
                @include('snippets.front.errors_first', ['param' => 'name'])
                <span id="contact_name_error" class="validation_error"></span>
                </div>
                <div class="md:w-1/2">
                <label for="address">Address<span class="required">*</span></label>
                <input id="address" type="text" name="address" id="address" class="form_control" value="{{old('address')}}" placeholder="" />
                @include('snippets.front.errors_first', ['param' => 'address'])
                <span id="address_error" class="validation_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="md:w-1/2">
                <label for="city">City</label>
                <input id="city" type="text" name="city"  class="form_control" value="{{old('city')}}" placeholder="" />
                @include('snippets.front.errors_first', ['param' => 'city'])
                <span class="help-block" style="color:red;"></span>
                </div>
                <div class="md:w-1/2">
                <label for="state">State</label>
                <input id="state" type="text" name="state"  class="form_control" value="{{old('state')}}" placeholder="" />
                @include('snippets.front.errors_first', ['param' => 'state'])
                <span class="help-block" style="color:red;"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="md:w-1/2">
                    <label for="country">Country</label>
                    <select name="country" class="form_control" data-validation="required" data-validation-error-msg="">
                        <option value="" selected="selected">Please Select</option>
                        @foreach($countries as $c)
                        <option value="{{$c->id}}" <?php echo ($c->id == $country_id) ? "selected" : ""; ?>>{{$c->name}}</option>
                        @endforeach
                    </select>
                    @include('snippets.front.errors_first', ['param' => 'country'])
                    <span class="help-block" style="color:red;"></span>
                </div>
                <div class="md:w-1/2">
                <label for="postal_code">Postal code</label>
                <input id="postal_code" type="text" name="zip_code"  class="form_control" value="{{old('zip_code')}}" placeholder="" />
                @include('snippets.front.errors_first', ['param' => 'zip_code'])
                <span id="contact_name_error" class="validation_error"></span>
                </div>
            </div>
            <div class="form-group row">
            <div class="col-sm-12">
            <div class="row">
                <!-- <div class="col-sm-3">
                <label class="radiobtn">
                <input type="radio" name="payment_method" value="BankDetails" checked> <span>Bank Details(NEFT and cheque)</span></label></div>  
                <div class="col-sm-3">
                <label class="radiobtn">
                <input type="radio" name="payment_method" value="TPS" checked> <span>Paynimo</span></label></div>
                -->
        </div>
        </div>
        </div>
            <div class="row">
                <div>

        <!--  <div class="form-group row">
                <div class="col-sm-3">
                <label for="phone">Security Code<span class="required">*</span></label>
                <input type="text" name="security_code" id="security_code" value="" class="form_control" placeholder="Security code"> 
                <span id="security_code_error" class="validation_error"></span>
                </div>
                <div class="col-sm-9 form-group"><label>&nbsp; </label><br><span class="img_captcha"><img  src="/assets/captcha/1675835824.6946.jpg" style="width: 140; height: 34; border: 0;" alt=" " /></span>  &nbsp; 
                    <span class="re_captcha"><i class="fa fa-refresh"></i></span>
                </div>
            
            </div> -->
            
                </div>
                <div class="lg:w-1/4">
                    <!-- <label>&nbsp;</label><br> -->
                <input type="hidden" name="captcha" id="hide_code" value="5UYf">

                <input type="submit" name="submit_payonline" value="Pay Now"  class="btn btn-primary" id="btn_save" />
                </div>
            </div>
        
        </form>
        
        
    </div>
</div>
</section>
<!-- Frequently Asked Questions Closed -->
@slot('bottomBlock')
<script>



jQuery.support.cors = true;
   if ($("#frmTransaction").length > 0) {
         $("#frmTransaction").validate({
            submitHandler: function(form) {
               $("#btn_save").val(
                     'Please wait...'
                     );
               $("#btn_save"). attr("disabled", true);
               form.submit();
            }
         })
   }


</script>     
@endslot

@endcomponent













