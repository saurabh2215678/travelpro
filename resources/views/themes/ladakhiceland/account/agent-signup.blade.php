@component(config('custom.theme').'.layouts.main')
@slot('title')
    Signup - {{ config('app.name') }}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
</style>
@endslot
<?php 
$country_code = old('country_code');
$country_code = isset($country_code) && !empty($country_code) ? $country_code : 91;
?>

<section>
<div class="container">
    <div class="user_profile_login">
    <div class="text_center">
        <div class="theme-title">
            <div class="title text-3xl">Agent Signup </div>
        </div>
        <!-- <div class="icon mt15"><img alt="" src="/assets/front/images/featured-icon.png" /></div> -->
    </div>
   @include('snippets.front.flash')
    {{ Form::open(array('url' => 'account/agent-signup','method' => 'post','class' => 'form','id' => 'signup_form','files'=>true,'autocomplete' => 'off')) }}
    <div class="signup_form singup_form_wrap">
        <div class="singup_form_inner">
            <input type="hidden" name="is_agent" value="1">
            <div class="form_group">
                <label for="company_name" class="control-label required">Company Registered Name<em>*</em></label>
                <input type="text" name="company_name" id="company_name" value="{{old('company_name')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'company_name'])
            </div> 
           <div class="form_group">
                <label for="company_brand" class="control-label required">Company Brand/Trade Name<em>*</em></label>
                <input type="text" name="company_brand" id="company_brand" value="{{old('company_brand')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'company_brand'])
            </div> 
           <div class="form_group">
                <label for="company_owner_name" class="control-label required">Company Owner Name<em>*</em></label>
                <input type="text" name="company_owner_name" id="company_owner_name" value="{{old('company_owner_name')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'company_owner_name'])
            </div> 
            <div class="form_group">
                <label for="bookings_per_month" class="control-label required">Bookings Per Month</label>
                <!-- <input type="text" name="bookings_per_month" id="bookings_per_month" value="{{old('bookings_per_month')}}"  placeholder="" class="form_control"> -->

                <select class="form_control" name="bookings_per_month" data-validate="true">
                    <option value="">--Select--</option>
                    <option value="0 - 5"  @selected(old('bookings_per_month') == '0 - 5' )>0 - 5</option>
                    <option value="5 - 15"  @selected(old('bookings_per_month') == '5 - 15' )>5 - 15</option>
                    <option value="15 - 25"  @selected(old('bookings_per_month') == '15 - 25' )>15 - 25</option>
                    <option value="More than 25"  @selected(old('bookings_per_month') == 'More than 25' )>More than 25</option>
                </select>

                @include('snippets.front.errors_first', ['param' => 'bookings_per_month'])
            </div> 
              <div class="form_group">
                <label for="sales_team_size" class="control-label required">Sales Team Size</label>
                <!-- <input type="text" name="sales_team_size" id="sales_team_size" value="{{old('sales_team_size')}}"  placeholder="" class="form_control"> -->
                <select class="form_control" name="sales_team_size" data-validate="true">
                    <option value="0"  @selected(old('sales_team_size') == '0' )>Sales only</option>
                    <option value="Less than 3" @selected(old('sales_team_size') == 'Less than 3' )>Less than 3</option>
                    <option value="3 - 5" @selected(old('sales_team_size') == '3 - 5' )>3 - 5</option>
                    <option value="5 - 10" @selected(old('sales_team_size') == '5 - 10' )>5 - 10</option>
                    <option value="More than 10" @selected(old('sales_team_size') == 'More than 10' )>More than 10</option>
                </select>
                @include('snippets.front.errors_first', ['param' => 'sales_team_size'])
            </div> 
             <div class="form_group">
                <label for="source" class="control-label required">Where did you hear about us?</label>
                <!-- <input type="text" name="source" id="source" value="{{old('source')}}"  placeholder="" class="form_control"> -->

                <select class="form_control" name="source" data-validate="true">
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
             <div class="form_group">
                <label for="website" class="control-label required">Website</label>
                <br>
                <input type="text" name="website" id="website" value="{{old('website')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'website'])
            </div> 
           <div class="form_group">
                <label for="destinations_sell_most" class="control-label required">Destinations you sell the most<em>*</em></label>
                <input type="text" name="destinations_sell_most" id="destinations_sell_most" value="{{old('destinations_sell_most')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'destinations_sell_most'])
            </div> 
             <div class="form_group">
                <label for="name" class="control-label required">Your Name<em>*</em></label>
                <input type="text" name="name" id="name" value="{{old('name')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'name'])
            </div> 
            {{--<div class="form_group">
                <label for="name" class="control-label required">Your Designation<em>*</em></label>
                <input type="text" name="name" id="name" value="{{old('name')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'name'])
            </div>--}}
            <div class="form_group">
                <label for="phone" class="control-label required">Phone Number<em>*</em></label>
                <select name="country_code" class="form-select country_code">
                  <?php /*{{$country->emoji}}*/ ?>
                  @if($countries)
                  @foreach($countries as $c)
                  <option value="{{$c->phonecode}}" {{($c->phonecode==$country_code)?'selected':''}} >+{{$c->phonecode}}</option>
                  @endforeach
                  @endif
              </select>
                <input type="text" name="phone" id="phone" value="{{old('phone')}}"  placeholder="" class="form_control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="12">
                @include('snippets.front.errors_first', ['param' => 'phone'])
            </div>
           <div class="form_group">
                <label for="whatsapp_phone" class="control-label required">Whatsapp Number</label>
                <input type="text" name="whatsapp_phone" id="whatsapp_phone" value="{{old('whatsapp_phone')}}"  placeholder="" class="form_control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="10">
                @include('snippets.front.errors_first', ['param' => 'whatsapp_phone'])
            </div>
            <div class="form_group">
                <label for="email" class="control-label required">Email Id<em>*</em></label>
                <input type="text" name="email" id="email" value="{{old('email')}}"  placeholder="" class="form_control" autocomplete="off">
                @include('snippets.front.errors_first', ['param' => 'email'])
            </div>
            <div class="form_group">
                <label for="agency_age" class="control-label required">How old is you agency?<em>*</em></label>
                <!-- <input type="text" name="agency_age" id="agency_age" value="{{old('agency_age')}}"  placeholder="" class="form_control"> -->

                <select class="form_control" name="agency_age" data-validate="true">
                    <option value="">--Select--</option>
                    <option value="Less than 2 years old"   @selected(old('agency_age') == 'Less than 2 years old' )>Less than 2 years old</option>
                    <option value="2 - 5 years old"  @selected(old('agency_age') == '2 - 5 years old' )>2 - 5 years old</option>
                    <option value="5 - 10 years old"  @selected(old('agency_age') == '5 - 10 years old' )>5 - 10 years old</option>
                    <option value="More than 10 years old"  @selected(old('agency_age') == 'More than 10 years old' )>More than 10 years old</option>
                </select>
                @include('snippets.front.errors_first', ['param' => 'agency_age'])
            </div>
            <div class="form_group">
                <label for="no_of_employees" class="control-label required">Number of Employees?<em>*</em></label>
                <!-- <input type="text" name="no_of_employees" id="no_of_employees" value="{{old('no_of_employees')}}"  placeholder="" class="form_control"> -->

                <select class="form_control" name="no_of_employees" data-validate="true" class="">
                    <option value="">--Select--</option>
                    <option value="Less than 5 employees" @selected(old('no_of_employees') == 'Less than 5 employees' )>Less than 5 employees</option>
                    <option value="5 - 10 employees" @selected(old('no_of_employees') == '5 - 10 employees' )>5 - 10 employees</option>
                    <option value="10 - 15 employees" @selected(old('no_of_employees') == '10 - 15 employees' )>10 - 15 employees</option>
                    <option value="More than 15" @selected(old('no_of_employees') == 'More than 15' )>More than 15</option>
                </select>

                @include('snippets.front.errors_first', ['param' => 'no_of_employees'])
            </div>
            <div class="form_group">
                <label for="query" class="control-label required">How can we help?</label>
                {{--<input type="text" name="query" id="query" value="{{old('query')}}"  placeholder="" class="form_control">--}}
                <input type="text" name="queries" id="query" value="{{old('query')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'query'])
            </div>
            <div class="form_group">
                <label for="region" class="control-label required">Your current travelers are from which region? (for international user)<em>*</em></label>
                <br>
                <!-- <input type="text" name="region" id="region" value="{{old('region')}}"  placeholder="" class="form_control"> -->

                <select class="form_control" name="region" data-validate="true">
                    <option value="">--Select--</option>
                    <option value="US">US</option>
                    <option value="UK">UK</option>
                    <option value="Rest of Europe">Rest of Europe</option>
                    <option value="Middle East">Middle East</option>
                    <option value="India">India</option>
                    <option value="Asia">Asia</option>
                </select>
                @include('snippets.front.errors_first', ['param' => 'region'])
            </div>
            <div class="form_group">
                <label for="company_profile" class="control-label required">Company Profile (Specialization, year of registration, company address, annual revenue)<em>*</em></label>
                <input type="text" name="company_profile" id="company_profile" value="{{old('company_profile')}}"  placeholder="" class="form_control">
                @include('snippets.front.errors_first', ['param' => 'company_profile'])
            </div>
            <div class="form_group">
                <label for="password" class="control-label required">Password<em>*</em></label>
                <input type="password" name="password" id="password" value="{{old('password')}}" autocomplete="new-password" placeholder="" class="form_control" autocomplete="off">
                @include('snippets.front.errors_first', ['param' => 'password'])
            </div>
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
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
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