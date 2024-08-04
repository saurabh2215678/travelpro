@component(config('custom.theme').'.layouts.main')
@slot('title')
{{ $meta_title ?? 'Request Detail'}}
@endslot    
@slot('meta_description')
{{ $meta_description ?? 'Request Detail'}}
@endslot
@slot('headerBlock')
@endslot
@slot('bodyClass')
request_detail_class
@endslot
<?php
$emailData = '';
$package_slug = request()->get('package');
$packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';

?>

<section class="theme_form" style="background-image: url({{asset(config('custom.assets').'/images/vision-bg.jpg')}});">
   <div class="container">
      <div class="theme_form_wrap rounded-lg">
         <div class="theme_form_inner">
         <div class="text-3xl font-semibold pb-4"><?php echo ($by == 'enquire') ? "Enquire Now":"Request Detailed Itinerary";?> <a class="go_back float-right" href="{{route($packageDetailName,['slug'=>$package_slug])}}">
         <span class="arrow">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M9.375 233.4l128-128c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L109.3 224H480c17.69 0 32 14.31 32 32s-14.31 32-32 32H109.3l73.38 73.38c12.5 12.5 12.5 32.75 0 45.25c-12.49 12.49-32.74 12.51-45.25 0l-128-128C-3.125 266.1-3.125 245.9 9.375 233.4z"/></svg>
         </span> Back
      </a></div>
            <?php
            $package_id = $package->id;
            $package_title = $package->title;
            $package_duration = $package->package_duration;
            ?>
            <h1 class="text-xl">{{ $package_title }}</h1>
            <p class="para_md font700">{{ $package_duration }}</p>
           @include('snippets.front.flash')
            {!!formShortCode(['slug' =>'[enquire_now]','class'=>'form_list','hidden'=>['package'=>$package_id]])!!}

            <?php /*
           @include('snippets.front.flash')
               {{ Form::open(array('route' => array('request-itinerary','package'=>$package->slug),'method' => 'post','id'=>'request-detail-frm','class' => '','autocomplete' => 'off')) }}
            <?php
                $package_id = $package->id;
                $package_title = $package->title;
                $package_duration = $package->package_duration;
            ?>
            <div class="heading3 font700">{{ $package_title }}</div>
            <p class="para_md font700">{{ $package_duration }}</p>
            <ul class="form_list">
               <li>
               <div class="form_group">
               <label>Your Name<em>*</em></label>
               <input type="text" name="name" id="name" value="{{old('name')}}" class="form_control" required>
               @include('snippets.front.errors_first', ['param' => 'name'])
               </div>
               </li>
               <li>
                  <div class="form_group">
                  <label>Phone<em>*</em></label>
                  <input type="number" name="phone" id="phone" value="{{old('phone')}}" class="form_control" required>
                  @include('snippets.front.errors_first', ['param' => 'phone'])
               </div>
               </li>
               <li>
                  <div class="form_group">
                  <label>Email<em>*</em></label>
                  <input type="text" name="email" id="email" value="{{old('email')}}"  class="form_control" required>
                 @include('snippets.front.errors_first', ['param' => 'email'])
               </div>
               </li>
               <li>
                  <div class="form_group">
                  <label>Country<em>*</em></label>
                  <input type="text" name="country" id="country" value="{{old('country')}}" class="form_control" required>
                  @include('snippets.front.errors_first', ['param' => 'country'])
               </div>
               </li>
               <li>
                  <div class="form_group">
                  <label>Zip Code</label>
                  <input type="text" name="zip_code" id="zip_code" value="{{old('zip_code')}}" class="form_control">
               </div>
               </li>
               <li>
                  <div class="form_group">
                  <label>When Do You Plan To Travel?</label>
                  <div class="custom_select">
                  <select class="form_control" name="plan_to_travel">
                     <?php
                     $i = 1;
                     $cr_date = date('Y-m-d');
                     $month = strtotime(old('plan_to_travel',$cr_date));
                     while($i <= 12) {
                        $month_name = date('F Y', $month);
                        echo '<option value="'. $month_name. '">'.$month_name.' </option>';
                        $month = strtotime('+1 month', $month);    $i++; 
                     }
                     ?>
                  </select>
                  </div>
               </div>
               </li>
               <li>
               <div class="form_group">
                  <label>Have You Travelled With Us Before? </label>
                  Yes: <input type="radio" name="travelled_with" value="1" {{ old("travelled_with") =='1' ? 'checked' : '' }} <?php echo ($emailData == '1')?'checked':''; ?> >
                  &nbsp;
                  No: <input type="radio" name="travelled_with" value="0" {{ old("travelled_with") =='0' ? 'checked' : '' }} <?php echo ( strlen($emailData) > 0 && $emailData == '0')?'checked':''; ?> >
               </div>
               </li>
            </ul>
            <input type="hidden" name="package_id" value="{{ $package_id }}">
            <input type="hidden" name="form_type" value="<?php echo ($by == 'enquire') ? "enquire":"request_itinerary";?>">
            <button type="submit" class="btn" name="submit">Submit</button>
            {{ Form::close() }}*/ ?>
         </div>
      </div>
   </div>

</section>
@slot('bottomBlock')

@endslot
@endcomponent