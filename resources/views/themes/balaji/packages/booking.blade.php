<!-- <link rel="stylesheet" href="{{asset(config('custom.assets').'/css/style.css') }}"> -->
<link rel="stylesheet" href="{{asset(config('custom.assets').'/css/media.css') }}">

<style type="text/css">
.ui-widget.ui-widget-content {z-index: 999 !important;}
.fancybox-overlay-fixed {position: fixed;bottom: 0;right: 0;background: #ffffffeb;}
.fancybox-wrap.fancybox-type-html.fancybox-opened section.theme_form .container {padding-right: 0;}

</style>
<?php
$booking_price = 0;
$total_amount = 0;
if(isset($action) && !empty($action)) {
  if($action == 'hotel_booking') {
    $storage = Storage::disk('public');
    $path = 'accommodations/';
    $image = $accommodation->image;
    $thumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
    if(!empty($image)) {
       if($storage->exists($path.$image)) {
          $thumbSrc = asset('/storage/'.$path.'thumb/'.$image);
       }
    }

    /*if($inventory->plan_id) {
      $total_amount = $inventory->price??0;
    } else {
      $total_amount = $inventory->AccommodationRoom->base_price??0;
    }*/

    $params = [];
    $params['room_id'] = $inventory_data->room_id??'';
    $params['plan_id'] = $inventory_data->plan_id??'';
    $params['checkin'] = $checkin??'';
    $params['checkout'] = $checkout??'';
    $params['inventory'] = $inventory??'';
    // prd($params);
    $inventory_price_data = CustomHelper::getAccommodationInventory($params);
    if(isset($inventory_price_data['success']) && !empty($inventory_price_data['success'])) {
      // $inventory_id = $inventory_price_data['inventory_id'];
      $total_amount = $inventory_price_data['price'];
    }
  } else if(isset($package) && !empty($package)) {
    $multiple_dates = "";
    if($package->tour_type == "group") {
      $multiple_dates = (!empty($package->multiple_dates)) ? json_decode($package->multiple_dates) : "";
    }
    $packagePriceCategory = $package->packagePriceCategory??[];
    $priceCategoryElements = $packagePriceCategory->priceCategoryElements??[];
    // pr($priceCategoryElements->toArray());
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

    $storage = Storage::disk('public');
    $path = 'packages/';
    $image = $package->image;
    $thumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
    if(!empty($image)) {
       if($storage->exists($path.$image)) {
          $thumbSrc = asset('/storage/'.$path.'thumb/'.$image);
       }
    }
    $total_pax = 0;
  }
}
$authCheck = auth()->user()?auth()->user():'';
$user_id =  auth()->user()?auth()->user()->id:0;
$is_agent = auth()->user()?auth()->user()->is_agent:0 ;
$country = $userObject->country??'101';
$country = old('country',$country);
$country_code = old('country_code');
$country_code = isset($country_code) && !empty($country_code) ? $country_code : 91;
?>
<section class="theme_form allbooking_form">
   <div class="container pr-0">
      <div class="theme_form_wrap popup-booking">
         <form id="book_now_form" method="POST" autocomplete="off">
           @include('snippets.front.flash')
            <div class="theme_form_inner">
               <div class="form_price">
                  <div class="left_sec w-1/2 pr-7">
                  <h3 class="text-xl pt-3">Book Now</h3>
                     <div class="form_list">
                           <div class="w-1/2 p-2 pb-0 pt-0">
                              <div class="form_group">
                                 <label>Your Name<em>*</em></label>
                                 <input type="text" name="name" id="name" value="{{old('name',$userObject->name??'')}}" class="form_control">
                                 @include('snippets.front.errors_first', ['param' => 'name'])
                                 <span id="name_error" class="validation_error"></span>
                              </div>
                           </div>
                           <div class="w-1/2 p-2 pb-0 pt-0">
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
                                 <input type="text" name="phone" id="phone" value="{{old('phone',$userObject->phone??'')}}" class="form_control" maxlength="12" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                 @include('snippets.front.errors_first', ['param' => 'phone'])
                                 <span id="phone_error" class="validation_error"></span>
                              </div>
                           </div>
                           <div class="w-1/2 p-2 pb-0 pt-0">
                              <div class="form_group">
                                 <label>Email<em>*</em></label>
                                 <input type="text" name="email" id="email" value="{{old('email',$userObject->email??'')}}"  class="form_control">
                                 @include('snippets.front.errors_first', ['param' => 'email'])
                                 <span id="email_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="w-1/2 p-2 pb-0 pt-0">
                              <div class="form_group">
                                 <label> Address 1</label>
                                 <input type="text" name="address1" id="address1" value="{{old('address1',$userObject->address1??'')}}"  class="form_control">
                                 <span id="address1_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="w-1/2 p-2 pb-0 pt-0">
                              <div class="form_group">
                                 <label> Address 2</label>
                                 <input type="text" name="address2" id="address2" value="{{old('address2',$userObject->address2??'')}}"  class="form_control">
                                 <span id="address2_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="w-1/2 p-2 pb-0 pt-0">
                              <div class="form_group">
                                 <label> City</label>
                                 <input type="text" name="city" id="city" value="{{old('city',$userObject->city??'')}}"  class="form_control">
                                 <span id="city_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="w-1/2 p-2 pb-0 pt-0">
                              <div class="form_group">
                                 <label> State</label>
                                 <input type="text" name="state" id="state" value="{{old('state',$userObject->state??'')}}"  class="form_control">
                                 <span id="state_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="w-1/2 p-2 pb-0 pt-0">
                              <div class="form_group">
                                 <label>Zip Code</label>
                                 <input type="text" name="zip_code" id="zip_code" value="{{old('zip_code',$userObject->zipcode??'')}}" class="form_control" maxlength="10" <?php /*onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"*/ ?>>
                                 @include('snippets.front.errors_first', ['param' => 'zip_code'])
                                 <span id="zip_code_error" class="validation_error"></span>
                              </div>
                           </div>

                           <div class="w-1/2 p-2 pb-0 pt-0">
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

                           <div class="mb-0 w-1/2 p-2 pb-0 pt-0">
                              <div class="form_group">
                                 <label>Comments</label>
                                 <textarea name="comment" id="comment" class="form_control" rows="1">{{old('comment')}}</textarea>
                                 @include('snippets.front.errors_first', ['param' => 'comment'])
                                 <span id="comment_error" class="validation_error"></span>
                              </div>
                           </div>
                        </div>
                        <?php if($action == 'hotel_booking') { ?>
                        <input type="hidden" name="hotel" value="{{$accommodation->id}}">
                        <input type="hidden" name="inventory" value="{{$inventory_data->id}}">
                        <input type="hidden" name="action" value="hotel_booking">
                        <?php }else if(isset($package) && !empty($package)){ ?>
                        <input type="hidden" name="package" value="{{$package_id}}">
                        <input type="hidden" name="package_type" value="{{$package_type}}">
                        <input type="hidden" name="action" value="package_booking">
                        <?php } ?>
                        <button type="submit" class="btn btnSubmit" name="submit">Submit</button>

                  </div>
                  <div class="right_sec w-1/2 bg-gray-100 pl-5 pt-0">
                    <?php if($action == 'hotel_booking') { ?>
                      <div class="col_details">
                        <h3 class="text-xl">Hotel Details</h3>
                     <div class="img-pack pt-1 pb-3 inline-block w-full">
                        <img src="{{$thumbSrc}}" alt="{{$inventory_data->Accommodation->name??''}}">
                        <div class="text-lg">{{$inventory_data->Accommodation->name??''}}<br>
                        <p class="date-bg text-xs inline-block" style="color:#01b3a7;">{{$inventory_data->AccommodationRoom->room_type_name??''}}</p>
                        </div>
                     <?php if(isset($inventory_data->plan_id)){?>
                     <p class="type-bg text-xs bg-orange-700 p-1 inline-block">{{$inventory_data->AccommodationPlan->plan_name??''}}</p>
                      <?php } else { ?>
                        <p class="type-bg text-sm p-1 inline-block">&nbsp;</p>
                      <?php } ?>
                     </div>
                     <div class="sm_price">
                     <hr>
                        <h4 class="text-sm font-semibold pt-2">Booking Details</h4>
                        <span class="w-full">
                          <?php if(isset($booking_data['checkin']) && !empty($booking_data['checkin'])){ ?>
                          <div class="book_info_list">
                            <strong>Checkin Date:</strong>
                            <span>
                              {{CustomHelper::DateFormat($booking_data['checkin'],'D, M dS Y','d/m/Y')??''}}
                            </span>
                          </div>
                          <?php } ?>

                          <?php if(isset($booking_data['checkout']) && !empty($booking_data['checkout'])){ ?>
                          <div class="book_info_list">
                            <strong>Checkout Date:</strong>
                            <span>
                              {{CustomHelper::DateFormat($booking_data['checkout'],'D, M dS Y','d/m/Y')??''}}
                            </span>
                          </div>
                          <?php } ?>

                          <?php if(isset($booking_data['inventory']) && !empty($booking_data['inventory'])){ ?>
                          <div class="book_info_list">
                            <strong>Room(s):</strong>
                            <span>
                              {{$booking_data['inventory']??''}}
                            </span>
                          </div>
                          <?php } ?>

                          <?php if(isset($booking_data['adult']) && !empty($booking_data['adult'])){ ?>
                          <div class="book_info_list">
                            <strong>Adult:</strong>
                            <span>
                              {{$booking_data['adult']??''}}
                            </span>
                          </div>
                          <?php } ?>

                          <?php if(isset($booking_data['child']) && !empty($booking_data['child'])){ ?>
                          <div class="book_info_list">
                            <strong>Child:</strong>
                            <span>
                              {{$booking_data['child']??''}}
                            </span>
                          </div>
                          <?php } ?>

                          <?php if(isset($booking_data['infant']) && !empty($booking_data['infant'])){ ?>
                          <div class="book_info_list">
                            <strong>Infant:</strong>
                            <span>
                              {{$booking_data['infant']??''}}
                            </span>
                          </div>
                          <?php } ?>
                        </span>
                        <hr>
                        @if($is_agent == 1)
                        <div class="preview_pricebox book_info_list"><strong style="padding-top:10px;">Sub Amount :</strong><span class="price"> {{CustomHelper::getPrice($total_amount)}}</span></div>
                        <?php
                        $discount_amount = 0;
                        if($is_agent == 1) {
                          $group_id = (auth()->user()) ? auth()->user()->group_id : '';
                          $discount_category_id = $accommodation->discount_category_id??'';
                          $discount_amount = CustomHelper::getDiscountPrice('hotel_listing',$discount_category_id,$total_amount,$group_id);
                        }
                        $total_amount = $total_amount - $discount_amount;
                        ?>
                        <div class="preview_pricebox book_info_list"><strong style="padding-top:10px;">Discount :</strong><span class="price"> {{CustomHelper::getPrice($discount_amount)}}</span></div>
                        @else
                           @if($booking_price && $booking_price > 0)
                              <div class="preview_pricebox book_info_list"><strong style="padding-top:10px;">Booking Amount :</strong><span class="price"> {{CustomHelper::getPrice($booking_price*$total_pax)}}</span></div>
                           @endif
                        @endif
                        <div class="preview_pricebox book_info_list"><strong style="padding-top:10px;">Total Amount :</strong><span class="price"> {{CustomHelper::getPrice($total_amount)}}</span></div>
                     </div>


                      </div>

                    <?php }else if(isset($package) && !empty($package)){ ?>
                      <div>
                  <h3 class="text-xl">Package Details</h3>
                     <div class="img-pack pt-1 pb-3 inline-block w-full">
                        <img src="{{$thumbSrc}}" alt="{{$package->title??''}}">
                        <div class="text-lg leading-tight">{{$package->title??''}}<br>
                        <p class="date-bg text-xs inline-block" style="color:#01b3a7;">{{$package->package_duration??''}}</p>
                        </div>
                     
                     <p class="type-bg text-xs bg-orange-700 p-1 inline-block">{{$packagePrice->title??''}}</p>
                     </div>
                     <div class="sm_price">
                     <hr>
                        <h4 class="text-sm font-semibold pt-2">Booking Details</h4>
                        <!-- <span class="showPrice"><?php //echo ($original_price == $sumpPriceVal) ? "":"<small class='old_price'>".CustomHelper::getPrice($original_price)."</small>"; ?>{{CustomHelper::getPrice($sumpPriceVal)}}</span> <br>
                        <small>{{ $packagePriceCategory->name }}</small> -->
                        <span class="w-full">
                          <div class="book_info_list">
                            <strong>Trip Date:</strong>
                            <span>
                              {{CustomHelper::DateFormat($booking_data['trip_date'],'D, M dS Y','d/m/Y')??''}}
                              <?php if(isset($booking_data['trip_slot'])){ //F d, Y ?>
                                {{CustomHelper::getPackageSlotTitle($booking_data['trip_slot'])}}
                              <?php } ?>
                            </span>
                          </div>

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
                            $module_name = 'package_listing';
                            $is_activity = $package->is_activity??'';
                            if($is_activity == 1) {
                              $module_name = 'activity_listing';
                            }
                            $discount_amount = CustomHelper::getDiscountPrice($module_name,$discount_category_id,$total_amount,$group_id);
                           }
                           $total_amount = $total_amount - $discount_amount;
                            
                          ?>
                        <div class="preview_pricebox book_info_list"><strong>Discount :</strong><span class="price"> {{CustomHelper::getPrice($discount_amount)}}</span></div>
                        @else
                           @if($booking_price && $booking_price > 0)
                              <div class="preview_pricebox book_info_list"><strong>Booking Amount :</strong><span class="price"> {{CustomHelper::getPrice($booking_price*$total_pax)}}</span></div>
                           @endif
                        @endif
                        <div class="preview_pricebox book_info_list"><strong>Total Amount :</strong><span class="price"> {{CustomHelper::getPrice($total_amount)}}</span></div>
                     </div>
                     <hr>
            
                     </div>
                    <?php } ?>

                    <?php if($authCheck && $is_agent != 1 && !empty($coupons)){ ?>
                      <div class="offers_code">
                        <?php
                        $ii = 0; 
                        foreach($coupons as $key => $coupon) {
                          $coupon_count = $coupon->couponUsedCount();
                          $couponDiscount = $coupon->discount?? 0;
                          if($coupon->type == 'percentage') {
                            $discount_coupon = ($total_amount * ($couponDiscount/100));
                          } else {
                            $discount_coupon = $coupon->discount??0;
                          }
                          if($discount_coupon > $coupon->max_discount_amt) {
                            $discount_coupon = $coupon->max_discount_amt;
                          }
                          if($coupon_count < $coupon->use_limit && $total_amount >= $coupon->min_amount && $discount_coupon < $total_amount) {
                            ?>
                          <?php if($ii == 0){ ?>
                            <h4 class="text-sm font-semibold pt-2" style=" color: #4CAF50;">{{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}} Offers</h4>
                          <?php } ?>
                          <div class="offerlist">
                            <label class="flex">
                              <input type="radio" id="coupon_id" name="fav_offer" value="{{$coupon->id}}"<?php if($coupon->id){ echo "selected";} ?> style="width: auto;">
                              <div class="w-full pl-2">
                                <div class="flex flex-row justify-between">
                                  <span class="text-sm">{{$coupon->code??''}}</span>
                                  <span class="text-sm">- {{CustomHelper::getPrice($discount_coupon)}}</span>
                                </div>
                                <div class="text-xs">Congratulations! You have saved {{CustomHelper::getPrice($discount_coupon)}} with {{$coupon->code??''}}.</div>
                              </div>
                            </label>
                          </div>
                          <?php $ii++; } } ?>
                        </div>
                      <?php } ?>
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
                        console.log(response);
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

               if(response.message){
                  alert(response.message);
               }
            }
         </script>