<section class="home_featured pb-0 pt-11">
<div class="xl:container xl:mx-auto">
  <div class="text-center mb-6 padding_x">
      <h1 class="heading2">Top India Destinations</h1>
      <p class="sub_heading">India is incredible! It has mountains, beaches, waterfalls, rivers, lakes, seas, and innumerable other natural treasures. Take a look at the most sought-after holiday destinations!</p>
   </div>
 <div>
    <div>
       <div class="indian_destination_box">
          <?php $storage = Storage::disk('public');
          foreach ($destinations as $key => $national_destinations) {
            if($key==0){

              $theme_path = "destinations/";

           }else{
            $theme_path = "destinations/";

           }
             $theme_title = CustomHelper::wordsLimit($national_destinations->name);
             $theme_brief = CustomHelper::wordsLimit($national_destinations->brief);
             $theme_slug = $national_destinations->slug;
             $theme_image = $national_destinations->image;

             $themeSrc = asset(config('custom.assets').'/images/noimagebig.jpg');
             if(!empty($theme_image) && $storage->exists($theme_path.$theme_image)) {
              $themeSrc = asset('/storage/'.$theme_path.$theme_image);
             }
             ?>            
             <div class="indian_destination" >
               <a class="destination_category_box" href="{{ url('destination/'.$national_destinations->slug) }}" >
                  <div class="images">
                     <img src="{{ $themeSrc }}" alt="{{ $theme_title }} Tour Packages">
                  </div>
                  <div class="featured_content py-4 px-2">
                     <div class="title text-lg"> {{ $theme_title }}</div>
               </div>
               </a>
            </div>
         <?php } ?>
      </div>
      <div class="view_all_btn pt-7 text-center"><a href="/destinations">View All</a></div>
   </div>
  
</div>
</div>
</section>