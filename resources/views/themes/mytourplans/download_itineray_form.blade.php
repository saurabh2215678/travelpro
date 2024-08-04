<?php
$emailData = '';
$package_slug = request()->get('package');
$packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="{{asset(config('custom.assets').'/js/monthpicker.js') }}"></script>
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
            <div class="text-xl">{{ $package_title }}</div>
            <p class="para_md font700">{{ $package_duration }}</p>
           @include('snippets.front.flash')
            {!!formShortCode(['slug' =>'[download_itinerary]','class'=>'form_list','hidden'=>['package'=>$package_id]])!!}

           
         </div>
      </div>
   </div>

</section>
@include(config('custom.theme').'.layouts.footerScript')

