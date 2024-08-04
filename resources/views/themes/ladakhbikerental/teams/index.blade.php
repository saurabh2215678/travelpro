@component(config('custom.theme').'.layouts.main')

@slot('title')
  {{ $meta_title ?? 'Teams'}}
@endslot    

@slot('meta_description')
  {{ $meta_description ?? 'Teams'}}
@endslot

@slot('headerBlock')
   
@endslot

@slot('bodyClass')
    teams_class
@endslot

@slot('bottomBlock')

 

<?php /*
<section>
   <div class="container">
      <div class="text_center">
         <div class="theme_title">
            <div class="title text-2xl mb-3">
               Our Team
            </div>
            
         </div>
         <p class="text-base text-left">Our personnel are some of the most highly qualified and experienced in the industry. Each and every one of them represents the spirit of the company. We try & reward better than our competitors and go to great lengths to identify self-starters with diverse experience. The members have had a broad-based training, which allows the performance of multiple roles with well rounded, talented and a complimentary team. They have a genuine love for our country and it is always a pleasure rediscovering it through the experience of every new guest. Click through the individual photos to learn more about our employees...</p>
         
               
      </div>
   </div>
</section>

<?php if(!($teams)->isEmpty()){ ?>
<section class="management_team">
   <div class="container">
      <div class="text_center">
         <div class="theme_title">
            <div class="title text-2xl mb-3">
              {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}} Team
            </div>
            
         </div>
      </div>
      <ul class="team_management">
         <?php foreach ($teams as $teamLists) {
            $manager_title = (!empty($teamLists->title)) ?($teamLists->title): '';
            $manager_sub_title = (!empty($teamLists->sub_title)) ?($teamLists->sub_title): '';
            $manager_designation = (!empty($teamLists->designation)) ?($teamLists->designation): '';
            $manager_slug = $teamLists->slug;

            $storage = Storage::disk('public');
            $manager_path = "teammanagements/";
            $manager_thumb_path = "teammanagements/thumb/";

            $managerSrc =asset(config('custom.assets').'/images/noimage.jpg');

            $image = $teamLists->image;

            if(!empty($image) && $storage->exists($manager_path.$image))
            {
               $managerSrc = asset('/storage/'.$manager_path.$image);
            }
         ?>
         <li>
            <a href="{{ route('teamDetail',['slug'=>$manager_slug]) }}" class="team_management_box">
               <div class="images">
                  <img src="{{ $managerSrc }}" alt="{{ $manager_title }}" />
               </div>
               <div class="team_info">
                  <div class="name para_lg1">{{ $manager_title }}</div>
                  <div class="designation para_md1">{{ $manager_designation }}</div>
                  <!-- <div class="designation para_md1">{{ $manager_sub_title }}</div> -->
               </div>
            </a>
         </li>
         <?php } ?>
      </ul>
   </div>
</section>
<?php }*/ ?>

<section>
<div class="xl:container xl:mx-auto">
      <div class="team-cat">
      <div class="mb-5">
         <div>
            @if(isset($page_title) && !empty($page_title))
            <h1 class="title text-2xl mb-5">{{$page_title}}</h1>
            @endif
            <div class="">
               @if(isset($page_brief) && !empty($page_brief))
               <p>{!!$page_brief!!}</p>
               @endif
               @if(isset($page_description) && !empty($page_description))
               <div>{!!$page_description!!}</div>
               @endif
            </div>
      </div>
      <div class="breadcrumb_full"> 
         <div class="container">
            <ul class="breadcrumb">
               <li><a href="{{url('/')}}">Home</a>
               </li>
               <li><a href="{{route('teamListing')}}">{{$page_url_label}}</a>
               </li>
            </ul>
         </div>
      </div>
      </div>
      
      <div class="theme-title">
            
           
            
         </div>
         <div class="team-grid_outer">
            <ul class="team-grid grid lg:grid-cols-4 grid-cols-2 gap-4">

<?php foreach ($teams as $teamLists) {
            $manager_title = (!empty($teamLists->title)) ?($teamLists->title): '';
            $manager_sub_title = (!empty($teamLists->sub_title)) ?($teamLists->sub_title): '';
            $manager_designation = (!empty($teamLists->designation)) ?($teamLists->designation): '';
            $manager_slug = $teamLists->slug;

            $storage = Storage::disk('public');
            $manager_path = "teammanagements/";
            $manager_thumb_path = "teammanagements/thumb/";

            $managerSrc =asset(config('custom.assets').'/images/noimage.jpg');

            $image = $teamLists->image;

            if(!empty($image) && $storage->exists($manager_path.$image))
            {
               $managerSrc = asset('/storage/'.$manager_path.$image);
            }
         ?>
               <li>
               <div id="flip3">
               <div class="flip-container">
                  <div class="card">
                     <div class="front">
                     
                     <div class="tcont">
                     <div class="images">
                  <img src="{{ $managerSrc }}" alt="{{ $manager_title }}">
               </div>
               <div class="content-box">
                     <h4 class="teamtitle text-lgtext-xl"><strong>{{ $manager_title }}</strong></h4>
                     <h5 class="pb-3">{{ $manager_designation }}</h5>
               </div>
                     </div>   
                     </div>
                     <div class="back">
                     <h4 class="teamtitle text-lg"><strong>{{ $manager_title }}</strong></h4>
                     <h5 class="pb-3">{{ $manager_designation }}</h5>
                     <p>{!!$teamLists->detail_profile!!}</p> 
                     </div>
                  </div>
               </div>
               <!-- <button id="flipButton">Click me</button> -->
        </div>
               </li>
               <?php } ?>
         
               <div class="clearfix"></div>
            </ul>
         </div>
      </div>
   </div>
</section>
 
<?php if(!($teamManagers)->isEmpty()){ ?>
<section class="management_team">
   <div class="container">
      <div class="text_center">
         <div class="theme_title">
            <div class="title text-2xl mb-3">
               Management Team 
            </div>
            
         </div>
      </div>
      <ul class="team_management">
         <?php foreach ($teamManagers as $manager) {
            $manager_title = (!empty($manager->title)) ?($manager->title): '';
            $manager_sub_title = (!empty($manager->sub_title)) ?($manager->sub_title): '';
            $manager_designation = (!empty($manager->designation)) ?($manager->designation): '';
            $manager_slug = $manager->slug;

            $storage = Storage::disk('public');
            $manager_path = "teammanagements/";
            $manager_thumb_path = "teammanagements/thumb/";

            $managerSrc =asset(config('custom.assets').'/images/noimage.jpg');

            $image = $manager->image;

            if(!empty($image) && $storage->exists($manager_path.$image))
            {
               $managerSrc = asset('/storage/'.$manager_path.$image);
            }
         ?>
         <li>
            <a href="{{ route('teamDetail',['slug'=>$manager_slug]) }}" class="team_management_box">
               <div class="images">
                  <img src="{{ $managerSrc }}" alt="{{ $manager_title }}" />
               </div>
               <div class="team_info">
                  <div class="name para_lg1">{{ $manager_title }}</div>
                  <div class="designation para_md1">{{ $manager_designation }}</div>
                  <!-- <div class="designation para_md1">{{ $manager_sub_title }}</div> -->
               </div>
            </a>
         </li>
         <?php } ?>
      </ul>
   </div>
</section>
<?php } ?>
<?php if(!($teamGuides)->isEmpty()){ ?>
<section class="our_guides">
   <div class="container">
      <div class="text_center">
         <div class="theme-title">
            <div class="title text-2xl mb-3">
               Our Guides 
            </div>
            
         </div>
      </div>
      <ul class="our_guides_list">
         <?php foreach ($teamGuides as $guide) {
            $guide_first_name = (!empty($guide->first_name)) ?($guide->first_name): '';
            $guide_last_name = (!empty($guide->last_name)) ?($guide->last_name): '';
            $guide_designation = (!empty($guide->designation)) ?($guide->designation): '';
            $guide_slug = $guide->slug;

            $storage = Storage::disk('public');
            $guide_path = "teammanagements/";
            $guide_thumb_path = "teammanagements/thumb/";

            $guideSrc =asset(config('custom.assets').'/images/noimage.jpg');

            $image = $guide->image;

            if(!empty($image) && $storage->exists($guide_path.$image))
            {
               $guideSrc = asset('/storage/'.$guide_path.$image);
            }
         ?>
         <li>
            <a href="{{ route('teamDetail',['slug'=>$guide_slug]) }}" class="team_management_box">
               <div class="images">
                  <img src="{{ $guideSrc }}" alt="{{ $guide_first_name.' '.$guide_last_name }}" />
               </div>
               <div class="team_info">
                  <div class="name para_lg1">{{ $guide_first_name.' '.$guide_last_name }}</div>
                  <div class="designation para_md1">{{ $guide_designation }}</div>
               </div>
            </a>
         </li>
         <?php } ?>
      </ul>
   </div>
</section>
<?php } ?>
<?php /* <section class="field_staff_sec">
   <div class="container">
      <div class="text_center">
         <div class="theme_title">
            <div class="title text-2xl mb-3">
               Office & Field Staff
            </div>
            
         </div>
      </div>
      <ul class="field_staff_list">
         <li>
            <a class="hotel_box" href="{{asset(config('custom.assets').'/images/field-staff1-big.jpg') }}" data-fancybox="gallery">
               <div class="images">
                  <img src="{{asset(config('custom.assets').'/images/field-staff1.jpg') }}" alt="">
               </div>
               <div class="hotel_content">
                  <div class="title para_lg2">Team</div>
              
               </div>
            </a>
         </li>
         <li>
            <a class="hotel_box" href="{{asset(config('custom.assets').'/images/field-staff2-big.jpg') }}" data-fancybox="gallery">
               <div class="images">
                  <img src="{{asset(config('custom.assets').'/images/field-staff2.jpg') }}" alt="">
               </div>
               <div class="hotel_content">
                  <div class="title para_lg2">Office Team</div>
              
               </div>
            </a>
         </li>
         <li>
            <a class="hotel_box" href="{{asset(config('custom.assets').'/images/field-staff3-big.jpeg') }}" data-fancybox="gallery">
               <div class="images">
                  <img src="{{asset(config('custom.assets').'/images/field-staff3.jpg') }}" alt="">
               </div>
               <div class="hotel_content">
                  <div class="title para_lg2">The Leaders</div>
              
               </div>
            </a>
         </li>
         <li>
            <a class="hotel_box" href="{{asset(config('custom.assets').'/images/field-staff4-big.jpeg') }}" data-fancybox="gallery">
               <div class="images">
                  <img src="{{asset(config('custom.assets').'/images/field-staff4.jpg') }}" alt="">
               </div>
               <div class="hotel_content">
                  <div class="title para_lg2">Trekking Crew</div>
              
               </div>
            </a>
         </li>
      </ul>
     
 </div>
 </section>
 */ ?>

@slot('bottomBlock')
<script>
   var epSlider = new Swiper(".latest_blog_slider", {
   // slidesPerView: 5.3,
   slidesPerView: 1,
   loop: true,
   parallax:true,
   speed:2000,
   navigation: {
   nextEl: ".cat-prev",
   prevEl: ".cat-next",
   },
   });



   // this is function
// function flipIt() {
//     $('.card').toggleClass('flipped');
// }
// $('.card').click(flipIt);
// 

// This also works
// $('.card').on('click', function() {
//   $(this).toggleClass('flipped');
// });

$(document).ready(function(){

$('.team-grid li .card ').each(function(){
  $(this).click(function(){
   $(this).toggleClass('flipped');
  });

});

// For toggle
document.querySelector("#myCard").classList.toggle("flip");

});

// $('.card').on('click', function() {
//     $('.card').toggleClass('flipped');
// });





// For verical flip
// $('.cardV').on('click', function() {
//     $('.cardV').toggleClass('flipped');
// });



// Works but it is not a good solution
// $(window).scroll(function(){
//     $(".fader").css("opacity", 1 - $(window).scrollTop() / 1500);
// });


// Good solution uses math > (height - scrollTop) / height > gives value set which is linear form 1 to 0.
// $(window).scroll(function () {
//     var scrollTop = $(window).scrollTop();
//     var height = $(window).height();

//     $('.test, .slogan').css({
//         'opacity': ((height - scrollTop) / height)
//     }); 
// });

// Similiar to above solution also uses math change the number 1100 to adjust to your need
// $(window).scroll(function() {    
//     var scroll = $(window).scrollTop();
//     $('.fader, .slogan').stop().animate(
//         {opacity: (( 1140-scroll )/100)+0.1},
//         "slow"
//     );
// });



</script>
@endslot

@endcomponent