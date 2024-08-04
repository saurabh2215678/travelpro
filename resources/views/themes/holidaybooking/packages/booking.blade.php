<link rel="stylesheet" href="{{asset(config('custom.assets').'/css/style.css') }}">
<link rel="stylesheet" href="{{asset(config('custom.assets').'/css/media.css') }}">
<style type="text/css">
   .ui-widget.ui-widget-content {z-index: 999 !important;}
   .fancybox-overlay-fixed {
    position: fixed;
    bottom: 0;
    right: 0;
    background: #ffffffeb;
}
.fancybox-wrap.fancybox-type-html.fancybox-opened section.theme_form .container {
    padding-right: 0;
}
.type-bg { font-size: 0.7rem; padding: 0 0.6rem!important; font-family: 'SF-Pro-Display'; letter-spacing: 0.8px; line-height: 2; }
.img-pack .text-xl { font-size: 1rem; line-height: 1.3; font-family: 'SF-Pro-Display'; font-weight: 500; }
.img-pack img { width: 6.813rem; padding-right: 0; margin-right: 1.1rem; border-radius: 6px; }
.right_sec>h3.text-2xl, .left_sec>h3.text-2xl { font-size: 1.4rem; line-height: 1.3; margin: 0; }
.left_sec>h3.text-2xl { padding-top: 1.5rem!important; }
.theme_form_wrap.popup-booking .form_list { margin: 0 -0.5rem; }
.theme_form_inner .form_list .form_group > label:nth-child(1) { font-family: 'SF-Pro-Display'; margin: 0; }
.sm_price>hr { margin: 0.5rem 0; }
.sm_price>.text-2xl { font-size: 1.4rem; line-height: 1.3; margin: 0; padding-top: 0!important; padding-bottom: 0.4rem; }
.popup-booking .book_info_list > * { font-family: 'SF-Pro-Display'; font-size: 0.86rem!important; }
</style>
<?php
$multiple_dates = "";
if($package->tour_type == "group") {
   $multiple_dates = (!empty($package->multiple_dates)) ? json_decode($package->multiple_dates) : "";
}
$packagePriceCategory = $package->packagePriceCategory??[];
$priceCategoryElements = $packagePriceCategory->priceCategoryElements??[];
// pr($priceCategoryElements->toArray());
$country = $userObject->country??'101';
$country = old('country',$country);
$sumpPriceVal = $packagePrice->final_amount;
$original_price = $packagePrice->sub_total_amount;
$booking_price = $packagePrice->booking_price??0;
$category_choices_record = $packagePrice->category_choices_record??[];
$show_choices_record = $packagePrice->show_choices_record??[];
// prd($show_choices_record);
$category_choices_record_arr = [];
if($category_choices_record) {
   $category_choices_record_arr = json_decode($category_choices_record,true);
}
// prd($category_choices_record_arr);

$show_choices_record_arr = [];
if($show_choices_record) {
   $show_choices_record_arr = json_decode($show_choices_record,true);
}
// prd($show_choices_record_arr);
$total_amount = 0;

$storage = Storage::disk('public');
$package_path = 'packages/';
$package_image = $package->image;
$packageThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
if(!empty($package_image)) {
   if($storage->exists($package_path.$package_image)) {
      $packageThumbSrc = asset('/storage/'.$package_path.'thumb/'.$package_image);
   }
}
$is_agent = auth()->user()?auth()->user()->is_agent:0 ;
$total_pax = 0;

$country_code = old('country_code');
$country_code = isset($country_code) && !empty($country_code) ? $country_code : 91;
?>
<section class="theme_form" style="background-image: url({{asset(config('custom.assets').'/images/vision-bg.jpg')}});">
   <div class="container pr-0">
      <div class="theme_form_wrap popup-booking">
         <form id="book_now_form" method="POST" autocomplete="off">
           @include('snippets.front.flash')
            <div class="theme_form_inner">
               <div class="form_price">
                  <div class="left_sec w-2/3 pr-7">
                     
                  <h3 class="text-2xl pt-3">Book Now</h3>
                     <div class="form_list">
                           <div class="w-1/3 p-2 pb-0">
                              <div class="form_group">
                                 <label>Your Name<em>*</em></label>
                                 <input type="text" name="name" id="name" value="{{old('name',$userObject->name??'')}}"  class="form_control">
                                 @include('snippets.front.errors_first', ['param' => 'name'])
                                 <span id="name_error" class="validation_error"></span>
                              </div>
                           </div>
                           <div class="w-1/3 p-2 pb-0">
                              <div class="form_group phone_code">
                                 <label>Phone<em>*</em></label>
                                 <select name="country_code" class="form-select country_code">
                                  <?php /*{{$country->emoji}}*/ ?>
                                  @if($countries)
                                  @foreach($countries as $c)
                                  <option value="{{$c->phonecode}}" {{($c->phonecode==$country_code)?'selected':''}} >+{{$c->phonecode}}</option>
                                  @endforeach
                                  @endif
                                </select>
                                 <input type="text" name="phone" id="phone" value="{{old('phone',$userObject->phone??'')}}"  class="form_control" maxlength="12" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                 @include('snippets.front.errors_first', ['param' => 'phone'])
                                 <span id="phone_error" class="validation_error"></span>
                              </div>
                           </div>
                           <div class="w-1/3 p-2 pb-0">
                              <div class="form_group">
                                 <label>Email<em>*</em></label>
                                 <input type="text" name="email" id="email" value="{{old('email',$userObject->email??'')}}"  class="form_control">
                                 @include('snippets.front.errors_first', ['param' => 'email'])
                                 <span id="email_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="w-2/3 p-2 pb-0">
                              <div class="form_group">
                                 <label> Address 1</label>
                                 <input type="text" name="address1" id="address1" value="{{old('address1',$userObject->address1??'')}}"  class="form_control">
                                 <span id="address1_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="w-1/3 p-2 pb-0">
                              <div class="form_group">
                                 <label> Address 2</label>
                                 <input type="text" name="address2" id="address2" value="{{old('address2',$userObject->address2??'')}}"  class="form_control">
                                 <span id="address2_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="w-1/3 p-2 pb-0">
                              <div class="form_group">
                                 <label> City</label>
                                 <input type="text" name="city" id="city" value="{{old('city',$userObject->city??'')}}"  class="form_control">
                                 <span id="city_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="w-1/3 p-2 pb-0">
                              <div class="form_group">
                                 <label> State</label>
                                 <input type="text" name="state" id="state" value="{{old('state',$userObject->state??'')}}"  class="form_control">
                                 <span id="state_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="w-1/3 p-2 pb-0">
                              <div class="form_group">
                                 <label>Zip Code</label>
                                 <input type="text" name="zip_code" id="zip_code" value="{{old('zip_code',$userObject->zipcode??'')}}" class="form_control" maxlength="10" <?php /*onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"*/ ?>>
                                 @include('snippets.front.errors_first', ['param' => 'zip_code'])
                                 <span id="zip_code_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="w-1/3 p-2 pb-0">
                              <div class="form_group">
                                 <label>Country</label>
                                 <select class="form_control" name="country" id="country">
                                    <option value="">Please Select</option>
                                    @foreach($countries as $c)
                                    <option value="{{$c->id}}" <?php echo ($c->id == $country) ? "selected" : ""; ?>>{{$c->name}}</option>
                                    @endforeach
                                 </select>
                                 @include('snippets.front.errors_first', ['param' => 'country'])
                                 <span id="country_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="mb-0 w-2/3 p-2 pb-0">
                              <div class="form_group">
                                 <label>Comments</label>
                                 <textarea name="comment" id="comment" class="form_control" rows="1">{{old('comment')}}</textarea>
                                 @include('snippets.front.errors_first', ['param' => 'comment'])
                                 <span id="comment_error" class="validation_error"></span>
                              </div>
                           </div>
                        </div>
                        <input type="hidden" name="package" value="{{$package_id}}">
                        <input type="hidden" name="package_type" value="{{$package_type}}">
                        <input type="hidden" name="action" value="package_booking">
                        <button type="submit" class="btn btnSubmit" name="submit">Submit</button>

                  </div>
                  <div class="right_sec w-1/3 bg-gray-100 p-4">
                  <h3 class="text-2xl">Package Details</h3>
                     <div class="img-pack py-3">
                        <img src="{{$packageThumbSrc}}" alt="{{$package->title??''}}">
                        <div class="text-xl">{{$package->title??''}}</div>
                     <p class="date-bg text-sm">{{$package->package_duration??''}}</p>
                     <p class="type-bg text-sm bg-orange-700 p-2 inline-block">{{$packagePrice->title??''}}</p>
                     </div>
                     <div class="sm_price">
                     <hr>
                        <h4 class="text-2xl pt-2">Booking Details</h4>
                        <!-- <span class="showPrice"><?php //echo ($original_price == $sumpPriceVal) ? "":"<small class='old_price'>".CustomHelper::getPrice($original_price)."</small>"; ?>{{CustomHelper::getPrice($sumpPriceVal)}}</span> <br>
                        <small>{{ $packagePriceCategory->name }}</small> -->
                        <span class="w-full">
                          <div class="book_info_list"> <strong>Departure:</strong> <span>{{CustomHelper::DateFormat($booking_data['trip_date'],'F d, Y','d/m/Y')??''}}</span></div>
                           <?php
                           if(!empty($priceCategoryElements))
                           {
                              foreach($priceCategoryElements as $pce)
                              {
                                 if(array_key_exists('pce'.$pce->id, $show_choices_record_arr))
                                 {
                                    $default = $show_choices_record_arr['pce'.$pce->id]['default']??'';
                                    if($default != 'pce'.$pce->id.'_null')
                                    {
                                       $pce_val = $booking_data['pce'.$pce->id]??0;
                                       if($pce_val > 0)
                                       {
                                          $pce_price = $category_choices_record_arr['pce'.$pce->id][$pce_val]??0;
                                          $sub_total = $pce_val*$pce_price;
                                          $total_amount += $sub_total;
                                          $total_pax += $pce_val;
                           ?>
                           <div class="book_info_list"> <strong>{{$pce->input_label}}:</strong><span>{{$pce_val}} X {{CustomHelper::getPrice($pce_price)}} = {{CustomHelper::getPrice($sub_total)}}</span></div>
                           <?php
                                       }
                                    }
                                 }
                              }
                           }
                           ?>
                        </span>
                        <hr>
                        @if($is_agent == 1)
                         <div class="preview_pricebox book_info_list"><strong style="padding-top:10px;">Sub Amount :</strong><span class="price"> {{CustomHelper::getPrice($total_amount)}}</span></div>
                   
                           <?php
                           $discount_amount = 0;
                           if($is_agent == 1){
                            $group_id = (auth()->user()) ? auth()->user()->group_id : '';
                            $discount_category_id = $package->discount_category_id??'';  
                            $discount_amount = CustomHelper::getDiscountPrice('package_listing',$discount_category_id,$total_amount,$group_id);
                           } 
                            $total_amount = $total_amount - $discount_amount;

                          ?>
                        <div class="preview_pricebox book_info_list"><strong >Discount :</strong><span class="price"> {{CustomHelper::getPrice($discount_amount)}}</span></div>
                        @else
                           @if($booking_price && $booking_price > 0)
                              <div class="preview_pricebox book_info_list"><strong >Booking Amount :</strong><span class="price"> {{CustomHelper::getPrice($booking_price*$total_pax)}}</span></div>
                           @endif
                        @endif
                     
                        <div class="preview_pricebox book_info_list"><strong >Total Amount :</strong><span class="price"> {{CustomHelper::getPrice($total_amount)}}</span></div>
                     </div>
                   </div>
                 </div>       
               </div>
            </form>
         </div>
      </div>
   </section>
         <script type="text/javascript">
            $("#book_now_form").bind("submit", function() {
               var _token = '{{ csrf_token() }}';
               var formID = 'book_now_form';
               $.ajax({
                  type        : "POST",
                  url         : "/booking",
                  cache       : false,
                  data        : $(this).serializeArray(),
                  headers:{'X-CSRF-TOKEN': _token},
                  beforeSend:function() {
                    $('#'+formID).find('.ajax_msg').html('');
                    $('#'+formID).find('.validation_error').html('');
                    $('#'+formID).find('.error').html('');
                    $('#'+formID).find('.btnSubmit').html('Please wait...');
                    $('#'+formID).find('.btnSubmit').attr("disabled", true);
                  },
                  success: function(response) {
                     if(response.success) {
                        window.location.href = response.redirect_url;
                     } else if(response.message) {
                        alert(response.message);
                     } else {
                        alert('Something went wront, please try again.');
                     }
                     $('#'+formID).find('.btnSubmit').html('Submit');
                     $('#'+formID).find('.btnSubmit').attr("disabled", true);
                  },
                  error: function(e) {
                    var response = e.responseJSON;
                    if(response) {
                      parseBookingNowErrorMessages(response, formID, 'Submit');
                    }
                  }
               });
               return false;
            });

            function parseBookingNowErrorMessages(response, formID, boxText) {
               $('#'+formID).find('.btnSubmit').html('Submit');
               $('#'+formID).find('.btnSubmit').attr("disabled", false);
              if(response.errors) {
                  var errors = response.errors;//$.parseJSON(resp.errors_fields);
                  var message='';
                  $.each(errors, function (key, val) {
                   $('#'+formID).find("#" + key + "_error").text(val[0]);
                });
               }
            }
         </script>