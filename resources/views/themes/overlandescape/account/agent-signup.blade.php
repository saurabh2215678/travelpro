@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
</style>
@endslot
<?php 
$country_code = old('country_code');
$country_code = isset($country_code) && !empty($country_code) ? $country_code : 91;

$whatsapp_country_code = old('whatsapp_country_code');
$whatsapp_country_code = isset($whatsapp_country_code) && !empty($whatsapp_country_code) ? $whatsapp_country_code : 91;
?>

<section>
<div class="container">
    <div class="user_profile_login">
    <div class="text_center">
        <div class="theme-title">
            <div class="text-2xl">Travel Agent Signup 2</div>
        </div>
        <div class="form-group google_with_login">
          <a href="{{route('social_login',['provider' => 'google','type'=>'customer'])}}" class="loginbtn"><img src="{{url('/images/google.png')}}" alt="Google Icon" class="glogo" /> Continue with Google</a>
          </div>
          <p class="border">Or continue with username / email</p>
    </div>
   @include('snippets.front.flash')
    {{ Form::open(array('url' => 'user/agent-signup-2','method' => 'post','class' => 'form','id' => 'signup_form','files'=>true,'autocomplete' => 'off','enctype'=>'multipart/form-data')) }}
    <div class="signup_form singup_form_wrap">
        <div class="singup_form_inner">

            <input type="hidden" name="is_agent" value="1">
            <input type="hidden" name="old_user_id" value="{{$old_user_id}}">

            <div class="form_group">
                <label for="company_name" class="control-label required text-xs">Company Registered Name<em>*</em></label>
                <input type="text" name="company_name" id="company_name" value="{{old('company_name')}}"  placeholder="" class="form_control bg-transparent">
                @include('snippets.front.errors_first', ['param' => 'company_name'])
            </div> 
           <div class="form_group">
                <label for="company_brand" class="control-label required text-xs">Company Brand/Trade Name<em>*</em></label>
                <input type="text" name="company_brand" id="company_brand" value="{{old('company_brand')}}"  placeholder="" class="form_control bg-transparent">
                @include('snippets.front.errors_first', ['param' => 'company_brand'])
            </div> 
            <div class="form_group">
                <label for="pan_no" class="control-label required text-xs">PAN Number<em>*</em></label>
                <input type="text" name="pan_no" id="pan_no" value="{{old('pan_no')}}"  placeholder="" class="form_control bg-transparent">
                @include('snippets.front.errors_first', ['param' => 'pan_no'])
            </div>
            <div class="form_group">
                <label class="control-label text-xs">Upload PAN Card</label>
                <input type="file" name="pan_image" id="pan_image" value="{{old('pan_image')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'pan_image'])
            </div> 
            <div class="form_group">
                <label for="gst_no" class="control-label required text-xs">GST Number<em>*</em></label>
                <input type="text" name="gst_no" id="gst_no" value="{{old('gst_no')}}"  placeholder="" class="form_control bg-transparent">
                @include('snippets.front.errors_first', ['param' => 'gst_no'])
            </div>
            <div class="form_group">
                <label class="control-label required text-xs">Upload GST<em>*</em></label>
                <input type="file" name="gst_image" id="gst_image" value="{{old('gst_image')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'gst_image'])
            </div>
            <div class="form_group">
                <label for="company_owner_name" class="control-label required text-xs">Company Owner Name<em>*</em></label>
                <input type="text" name="company_owner_name" id="company_owner_name" value="{{old('company_owner_name')}}"  placeholder="" class="form_control bg-transparent">
                @include('snippets.front.errors_first', ['param' => 'company_owner_name'])
            </div> 
            <div class="form_group">
                <label for="bookings_per_month" class="control-label required text-xs">Bookings Per Month</label>
                <!-- <input type="text" name="bookings_per_month" id="bookings_per_month" value="{{old('bookings_per_month')}}"  placeholder="" class="form_control"> -->

                <select class="form_control bg-transparent" name="bookings_per_month" data-validate="true">
                    <option value="">--Select--</option>
                    <option value="0 - 5"  @selected(old('bookings_per_month') == '0 - 5' )>0 - 5</option>
                    <option value="5 - 15"  @selected(old('bookings_per_month') == '5 - 15' )>5 - 15</option>
                    <option value="15 - 25"  @selected(old('bookings_per_month') == '15 - 25' )>15 - 25</option>
                    <option value="More than 25"  @selected(old('bookings_per_month') == 'More than 25' )>More than 25</option>
                </select>

                @include('snippets.front.errors_first', ['param' => 'bookings_per_month'])
            </div>
            <div class="form_group">
                <label for="no_of_employees" class="control-label text-xs">Number of Employees?</label>
                <!-- <input type="text" name="no_of_employees" id="no_of_employees" value="{{old('no_of_employees')}}"  placeholder="" class="form_control"> -->

                <select class="form_control bg-transparent" name="no_of_employees" data-validate="true" class="">
                    <option value="">--Select--</option>
                    <option value="Less than 5 employees" @selected(old('no_of_employees') == 'Less than 5 employees' )>Less than 5 employees</option>
                    <option value="5 - 10 employees" @selected(old('no_of_employees') == '5 - 10 employees' )>5 - 10 employees</option>
                    <option value="10 - 15 employees" @selected(old('no_of_employees') == '10 - 15 employees' )>10 - 15 employees</option>
                    <option value="More than 15" @selected(old('no_of_employees') == 'More than 15' )>More than 15</option>
                </select>

                @include('snippets.front.errors_first', ['param' => 'no_of_employees'])
            </div>

            <div class="form_group">
                <label for="agency_age" class="control-label text-xs">How old is you agency?</label>
                <!-- <input type="text" name="agency_age" id="agency_age" value="{{old('agency_age')}}"  placeholder="" class="form_control"> -->

                <select class="form_control bg-transparent" name="agency_age" data-validate="true">
                    <option value="">--Select--</option>
                    <option value="Less than 2 years old"   @selected(old('agency_age') == 'Less than 2 years old' )>Less than 2 years old</option>
                    <option value="2 - 5 years old"  @selected(old('agency_age') == '2 - 5 years old' )>2 - 5 years old</option>
                    <option value="5 - 10 years old"  @selected(old('agency_age') == '5 - 10 years old' )>5 - 10 years old</option>
                    <option value="More than 10 years old"  @selected(old('agency_age') == 'More than 10 years old' )>More than 10 years old</option>
                </select>
                @include('snippets.front.errors_first', ['param' => 'agency_age'])
            </div>

             <div class="form_group">
                <label for="website" class="control-label required text-xs">Website</label>
                <input type="text" name="website" id="website" value="{{old('website')}}"  placeholder="" class="form_control bg-transparent">
                @include('snippets.front.errors_first', ['param' => 'website'])
            </div> 
           <div class="form_group">
                <label for="destinations_sell_most" class="control-label text-xs">Destinations you sell the most</label>
                <input type="text" name="destinations_sell_most" id="destinations_sell_most" value="{{old('destinations_sell_most')}}"  placeholder="" class="form_control bg-transparent">
                @include('snippets.front.errors_first', ['param' => 'destinations_sell_most'])
            </div>

           <div class="form_group">
            <label for="whatsapp_phone" class="control-label required text-xs">Whatsapp Number</label>
              <select name="whatsapp_country_code" class="form-select country_code">
                  <?php /*{{$country->emoji}}*/ ?>
                  @if($countries)
                  @foreach($countries as $country)
                  <option value="{{$country->phonecode}}" {{($country->phonecode==$whatsapp_country_code)?'selected':''}} >+{{$country->phonecode}}</option>
                  @endforeach
                  @endif
              </select>
                <input type="text" name="whatsapp_phone" id="phone" value="{{old('whatsapp_phone')}}"  placeholder="" class="form_control bg-transparent" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="12">
                @include('snippets.front.errors_first', ['param' => 'whatsapp_phone'])
            </div>
            <div class="form_group">
                <label for="region" class="control-label text-xs">Your current travelers are from which region? (for international user)</label>
                
                <!-- <input type="text" name="region" id="region" value="{{old('region')}}"  placeholder="" class="form_control"> -->

                <select class="form_control bg-transparent" name="region" data-validate="true">
                    <option value="">--Select--</option>
                    <option value="US" @selected(old('region') == 'US' )>US</option>
                    <option value="UK" @selected(old('region') == 'UK' )>UK</option>
                    <option value="Rest of Europe" @selected(old('region') == 'Rest of Europe' )>Rest of Europe</option>
                    <option value="Middle East" @selected(old('region') == 'Middle East' )>Middle East</option>
                    <option value="India" @selected(old('region') == 'India' )>India</option>
                    <option value="Asia" @selected(old('region') == 'Asia' )>Asia</option>
                </select>
                @include('snippets.front.errors_first', ['param' => 'region'])
            </div>

            <div class="form_group"  style="width:100%;">
                <label for="source" class="control-label required text-xs">Where did you hear about us?</label>
                <!-- <input type="text" name="source" id="source" value="{{old('source')}}"  placeholder="" class="form_control"> -->

                <select class="form_control bg-transparent" name="source" data-validate="true">
                    <option value="">--Select--</option>
                    <option value="Emails from us"  @selected(old('source') == 'Emails from us' ) >Emails from us</option>
                    <option value="We called you" @selected(old('source') == 'We called you' ) >We called you</option>
                    <option value="Google / Bing search" @selected(old('source') == 'Google / Bing search' ) >Google / Bing search</option>
                    <option value="News/ Magazines" @selected(old('source') == 'News/ Magazines' ) >News/ Magazines</option>
                    <option value="LinkedIn / Facebook" @selected(old('source') == 'LinkedIn / Facebook' ) >LinkedIn / Facebook </option>
                    <option value="Other Travel Agents" @selected(old('source') == 'Other Travel Agents' ) >Other Travel Agents</option>
                    <option value="Your team member" @selected(old('source') == 'Your team member' ) >Your team member</option>
                    <option value="Other / Misc" @selected(old('source') == 'Other / Misc' ) >Other / Misc</option>
                </select>

                @include('snippets.front.errors_first', ['param' => 'source'])
            </div>
             <div class="form_group w-full" style="width:100%;">
                <label for="company_profile" class="control-label required text-xs">Company Profile (Specialization, year of registration, company address, annual revenue)<em>*</em></label>
                <input type="text" name="company_profile" id="company_profile" value="{{old('company_profile')}}"  placeholder="" class="form_control bg-transparent">
                @include('snippets.front.errors_first', ['param' => 'company_profile'])
            </div>
 
            <?php /*<div class="form_group">
                <label for="sales_team_size" class="control-label required text-xs">Sales Team Size</label>
                <!-- <input type="text" name="sales_team_size" id="sales_team_size" value="{{old('sales_team_size')}}"  placeholder="" class="form_control"> -->
                <select class="form_control bg-transparent" name="sales_team_size" data-validate="true">
                    <option value="0"  @selected(old('sales_team_size') == '0' )>Sales only</option>
                    <option value="Less than 3" @selected(old('sales_team_size') == 'Less than 3' )>Less than 3</option>
                    <option value="3 - 5" @selected(old('sales_team_size') == '3 - 5' )>3 - 5</option>
                    <option value="5 - 10" @selected(old('sales_team_size') == '5 - 10' )>5 - 10</option>
                    <option value="More than 10" @selected(old('sales_team_size') == 'More than 10' )>More than 10</option>
                </select>
                @include('snippets.front.errors_first', ['param' => 'sales_team_size'])
            </div> 
              
             
            <div class="form_group">
                <label for="query" class="control-label required text-xs">How can we help?</label>
                {{--<input type="text" name="query" id="query" value="{{old('query')}}"  placeholder="" class="form_control">--}}
                <input type="text" name="queries" id="query" value="{{old('query')}}"  placeholder="" class="form_control bg-transparent">
                @include('snippets.front.errors_first', ['param' => 'query'])
            </div> */ ?>
        </div>
        <div class="form_group submit_btn">
            <button type="submit" class="btn" id="sbmtBtn" name="submit">Submit</button>
        </div>
          <div class="create_account">Already have an account <a href="{{route('account.login')}}">Login Now</a>
          </div>
    </div>
    {{ Form::close() }}
    </div>
</div>
</section>
@slot('bottomBlock')
<script>
    var maxBirthdayDate = new Date();
    maxBirthdayDate.setFullYear( maxBirthdayDate.getFullYear() - 18,11,31);
    $( function() {
        $( "#dob" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            maxDate: maxBirthdayDate,
            yearRange: '1900:'+maxBirthdayDate.getFullYear(),
        });
    } );
   jQuery.support.cors = true;
   if ($("#signup_form").length > 0) {
         $("#signup_form").validate({
            submitHandler: function(form) {
               $("#sbmtBtn").html(
                     'Please wait...'
                     );
               $("#sbmtBtn"). attr("disabled", true);
               $("#signup_form").submit();
            }
         })
   }
</script>
@endslot
@endcomponent