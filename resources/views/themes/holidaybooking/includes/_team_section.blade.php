<div class="xl:container xl:mx-auto">
     <div class="text_center ">
         <div class="theme_title mb-6">
            <div class="title text-2xl">{{$section_title}}</div>
            <div class="view_all_btn"><a href="{{route('teamListing')}}">View All</a></div>
            
         </div>
         <div class="p_relative">
     <div class="slider_box">
        <div class="swiper client_slider">
           <div class="swiper-wrapper">
            <?php $storage = Storage::disk('public');
            foreach($teams as $teamManagenent){
            $team_title = (!empty($teamManagenent->title)) ?($teamManagenent->title): '';
            $team_sub_title = (!empty($teamManagenent->sub_title)) ?($teamManagenent->sub_title): '';
            $team_designation = (!empty($teamManagenent->designation)) ?($teamManagenent->designation): '';
            $team_slug = $teamManagenent->slug;
            $team_brief_profile = isset($teamManagenent->brief_profile) ? $teamManagenent->brief_profile:'';

            $storage = Storage::disk('public');
            $team_path = "teammanagements/";
            $team_thumb_path = "teammanagements/thumb/";

            $teamSrc = asset(config('custom.assets').'/images/noimage.jpg');

            $image = $teamManagenent->image;

            if(!empty($image) && $storage->exists($team_path.$image))
            {
               $teamSrc = asset('/storage/'.$team_path.$image);
            }

            ?>
              <div class="swiper-slide">
                 <!-- <a href="{{ route('teamDetail',['slug'=>$team_slug]) }}" class="team_management_box"></a> -->
               <div class="images">
                  <img src="{{ $teamSrc }}" alt="{{ $team_title }}" />
               </div>
               <div class="team_info">
               <div class="text py-4 px-2"><span class="title leading-5"> {{ $team_title }}</span>
                  <div class="designation para_md1 text-xs">{{ $team_designation }}</div>
                  <div class="designation para_md1 text-sm font-bold">{{ $team_sub_title }}</div>
               </div>
               </div>
              </div>
             
         <?php }?>
         </div>
      </div>
      
  </div>

  <div class="slider_btns">
        <div class="client-prev theme-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
        <div class="client-next theme-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
     </div>
     </div>
</div>
</div>