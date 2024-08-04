@component(config('custom.theme').'.layouts.main')

    @slot('title')
      {{ $meta_title ?? 'Testimonial Detail'}}
    @endslot    

    @slot('headerBlock')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
    @endslot

    @slot('bodyClass')
        testimonials_class
    @endslot
<?php
$testimonialData = '';
$package_id = $testimonialData->id??0;
$websiteSettingsArr = CustomHelper::getSettings(['RECAPTCHA_SITE_KEY','RECAPTCHA_SECRET_KEY']); 
$name_title_arr = config('custom.name_title_arr')
?>


      <section class="inner_banner">
         <div class="inner_banner_main">
            <img src="{{asset(config('custom.assets').'/images/review-banner.jpg')}}" alt="Review Banner">
         </div>        
      </section>


      <section class="review_form">
         <div class="breadcrumb_full">
            <div class="xl:container xl:mx-auto">
               <div class="text_center">
                  <div class="theme_title">
                     <h1 class="title text-2xl">Write a Testimonial</h1>
                     <ul class="breadcrumb">
                        <li><a href="{{url('/')}}">Home</a>
                        </li>
                        <li><a href="{{route('testimonialListing')}}">Testimonial</a>
                        </li>
                     <li>Add</li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>



         <div class="xl:container xl:mx-auto">
            <div class="flex flex-wrap">
               <div class="lg:w-1/3 w-1/2 pt-2">
                  @include('snippets.front.flash')
                           {{ Form::open(array('route' => 'testimonialadd','method' => 'post','id'=>'testimonials-form','class' => '','autocomplete' => 'off')) }}
                           <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                           <input type="hidden" name="action" value="validate_captcha">

                  <div class="form-floating mb-3 w-full">
                     <label for="Name">Title*</label>
                     <!-- <input type="text" id="title" placeholder="Title*" class="form-control "name="title" value="{{old('title')}}"> -->
                     <select class="form_control" name="title">
                        <option value="">Select Title *</option>
                        @foreach($name_title_arr as $k => $v)
                           <option value="{{$k}}" @if(old('title') == $k) selected @endif >{{$v}}</option>
                        @endforeach
                     </select>

                     @include('snippets.front.errors_first', ['param' => 'title'])
                  </div>
                  
               </div>
               <div class="lg:w-1/3 w-1/2">
                  <div class="form-floating mb-3 w-full">
                     <label for="Name">Name*</label><input type="text" id="name" placeholder="Name*" class="form-control " datatypeinput="inputname" name="name" value="{{old('name')}}">
                     @include('snippets.front.errors_first', ['param' => 'name'])
                  </div>
               </div>
               <div class="lg:w-1/3 w-1/2">
                  <div class="form-floating mb-3 w-full">
                     <label for="Name">Email*</label><input type="text" id="email" placeholder="Email*"  class="form-control "  name="email" value="{{old('email')}}">
                     @include('snippets.front.errors_first', ['param' => 'email'])
                  </div>
               </div>
               <div class="lg:w-1/3 w-1/2">
                  <div class="form-floating mb-3 w-full">
                     <label for="Name">Company Name</label><input type="text" id="company_name" placeholder="Company Name" class="form-control " name="company_name" value="{{old('company_name')}}">
                  </div>
               </div>
               <div class="lg:w-1/3 w-1/2">
                  <div class="form-floating mb-3 w-full">
                     <label for="Name">Position in company</label><input type="text" id="position_in_company" placeholder="Position in company" class="form-control " name="position_in_company" value="{{old('position_in_company')}}">
                  </div>
               </div>
               <div class="lg:w-1/3 w-1/2">
                  <div class="form-floating mb-3 w-full">
                     <label for="Name">Website</label><input type="text" id="website" placeholder="Website" class="form-control " name="website" value="{{old('website')}}">
                  </div>
               </div>
               <div class="form-floating mb-3 w-full">
                    <label for="description" class="control-label">Content*</label>
                    <textarea name="description" id="description" class="form-control ckeditor" value="{{old('description')}}" >{{old('description')}}</textarea>   
                    @include('snippets.front.errors_first', ['param' => 'description']) 
                </div>
               <div class="submit_btn form-floating mb-3 mt-3">
                   <input type="hidden" name="package_id" id="package_id" value="{{$package_id}}">
                  <button type="submit" class="custom-btn btnSubmit" name="submit">Submit</button>
               </div>
               {{ Form::close() }}
            </div>
         </div>
      </section>

@slot('bottomBlock')
 <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script>
        var description = document.getElementById('description');
        CKEDITOR.replace(description);
    </script>

 <script src="https://www.google.com/recaptcha/api.js?render={{$websiteSettingsArr['RECAPTCHA_SITE_KEY']}}"></script>

<script type="text/javascript">  
grecaptcha.ready(function() {
   grecaptcha.execute("{{$websiteSettingsArr['RECAPTCHA_SITE_KEY']}}", {action:'validate_captcha'}).then(function(token) {
   // add token value to form
   const element = document.getElementById('g-recaptcha-response');
   if(element) {
    document.getElementById('g-recaptcha-response').value = token;
   }
   });
});
</script>
<script>
   jQuery.support.cors = true;
   if ($("#testimonials-form").length > 0) {
         $("#testimonials-form").validate({
            submitHandler: function(form) {
               $(".btnSubmit").html(
                     'Please wait...'
                     );
               $(".btnSubmit"). attr("disabled", true);
               form.submit();
            }
         })
   }
</script>

@endslot

@endcomponent
