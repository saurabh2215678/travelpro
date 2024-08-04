@component(config('custom.theme').'.layouts.main')
@slot('title')
{{ $meta_title ?? 'Group Partner'}}
@endslot    
@slot('meta_description')
{{ $meta_description ?? 'Group Partner'}}
@endslot
@slot('headerBlock')
@endslot
@slot('bodyClass')
group_partner_class
@endslot
<section class="inner_banner">
   <div class="inner_banner_main">
      <img src="{{asset(config('custom.assets').'/images/review-banner.jpg')}}" alt="" >
   </div>
</section>
<section style="background-image: url({{asset(config('custom.assets').'/images/vision-bg.jpg')}});">
   <div class="container">
      <div class="text_center group_title">
         <div class="theme_title">
            <div class="title">
               Luxury Group Travel
            </div>
            <p class="para_lg2">For intrepid travelers looking for the best in luxury small group tours</p>
            <div class="icon mt15">
            </div>
            <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
         </div>
         <a href="#" class="btn">START PLANNING</a>
      </div>
      <div class="gateways_grid group_travel_top">
         <div class="left_side">
            <div class="left_side_inner">
               <p class="para_lg2">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
               <p class="para_lg2">It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>
         </div>
         <div class="right_side">
            <img src="{{asset(config('custom.assets').'/images/group-img.jpg')}}" class="theme_radius img_responsive"  alt="" >
         </div>
      </div>
   </div>
</section>
<div class="container">
<div class="mid_cta" style="background-image: url(./images/featured-bg.jpg);">
   <div class="heading_sm1">Let our experts guide you to your perfect trip</div>
   <div class="bottom_call para_lg1">Call : <a class="callto" href="tel:+97517111563 ">+97517111563 </a> - OR - <a href="#" class="btn">INQUIRE ONLINE</a></div>
  </div>
</div>
<section class="group_trip_sec">
   <div class="container">
      <div class="text_center">
         <div class="theme_title">
            <div class="title">
               Other Partner Hotels
            </div>
            <div class="icon mt15">
               <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
            </div>
         </div>
      </div>
      <ul class="featured_list">
         <li data-aos="fade-up" data-aos-duration="2000">
            <a class="featured_box" href="#">
               <div class="images">
                  <img src="{{asset(config('custom.assets').'/images/featured-img.jpg')}}" alt="" class="img_responsive">
               </div>
               <div class="featured_content" style="background-image: url(./images/featured-bg.jpg);">
                  <div class="title heading3">The Bhutanese Essence</div>
                  <div class="duration">9 Nights & 10 days</div>
                  <p class="para_md">An essential overview of the Himalayan Buddhist Kingdom.</p>
                  <div class="view_all">View Detail</div>
               </div>
            </a>
         </li>
         <li data-aos="fade-up" data-aos-duration="2000">
            <a class="featured_box" href="#">
               <div class="images">
                  <img src="{{asset(config('custom.assets').'/images/featured-img2.jpg')}}" alt="" class="img_responsive">
               </div>
               <div class="featured_content" style="background-image: url(./images/featured-bg.jpg);">
                  <div class="title heading3">Western Bhutan in a week</div>
                  <div class="duration">9 Nights & 10 days</div>
                  <p class="para_md">An essential overview of the Himalayan Buddhist Kingdom.</p>
                  <div class="view_all">View Detail</div>
               </div>
            </a>
         </li>
         <li data-aos="fade-up" data-aos-duration="2000">
            <a class="featured_box" href="#">
               <div class="images">
                  <img src="{{asset(config('custom.assets').'/images/featured-img3.jpg')}}" alt="" class="img_responsive">
               </div>
               <div class="featured_content" style="background-image: url(./images/featured-bg.jpg);">
                  <div class="title heading3">The most Sacred
                     Mountain in Bhutan
                  </div>
                  <div class="duration">9 Nights & 10 days</div>
                  <p class="para_md">An essential overview of the Himalayan Buddhist Kingdom.</p>
                  <div class="view_all">View Detail</div>
               </div>
            </a>
         </li>
      </ul>
      <div class="view_all_cta">
         <a href="#" class="btn">View More</a>
      </div>
   </div>
</section>
<section class="pb_150">
   <div class="container">
      <div class="text_center">
         <div class="theme_title">
            <div class="title">
               Frequently Asked Questions
            </div>
            <div class="icon mt15">
               <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
            </div>
         </div>
      </div>
      <div class="faq_list_group">
         <ul class="faq_listing">
            <li class="">
               <div class="faq_main">
                  <div class="faq_title heading6">How about accommodation? What are the hotels like?</div>
                  <div class="faq_data" style="">
                     <p class="para-md2">Bhutan now has a host of variety of hotels. You can get standard hotels with all the basic necessities, clean and hygienic. There also a number of luxury hotels offering the best of facilities matching any international 5 star property like Zhiwa Ling Heritage www.zhiwaling.com   You can also choose to camp or try out the home stays in villages that are now becoming very popular with guests. Yangphel offers glamping (Deluxe camping services).</p>
                  </div>
               </div>
            </li>
            <li class="">
               <div class="faq_main">
                  <div class="faq_title heading6">What is the weather like ?</div>
                  <div class="faq_data" style="">
                     <p class="para-md2">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                  </div>
               </div>
            </li>
            <li class="">
               <div class="faq_main">
                  <div class="faq_title heading6">What flights are available and which airports can I depart for Bhutan ?</div>
                  <div class="faq_data" style="">
                     <p class="para-md2">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                  </div>
               </div>
            </li>
            <li class="">
               <div class="faq_main">
                  <div class="faq_title heading6">Is it true that we have to pay USD 200/250 per person per night stay to visit Bhutan ?</div>
                  <div class="faq_data" style="">
                     <p class="para-md2">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                  </div>
               </div>
            </li>
         </ul>
      </div>
   </div>
</section>
@slot('bottomBlock')
<script>
   $(".faq_title").on("click", function(e){
    e.preventDefault();
    if($(this).hasClass("active")){
      $(this).removeClass("active");
      $(".faq_data").slideUp(300);
    } else {
      $(".faq_title").removeClass("active");
      $(".faq_listing > li").removeClass("active");
      $(".faq_data").slideUp(300);
      $(this).addClass("active");
      $(this).closest("li").toggleClass("active");
      $(this).parent().find(".faq_data").slideDown(300);
    } 
   });
   $(".faq_title").first().trigger("click");
</script>
@endslot
@endcomponent