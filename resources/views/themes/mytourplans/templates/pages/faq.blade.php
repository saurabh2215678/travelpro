@component(config('custom.theme').'.layouts.main')

@slot('title')
{{$page_title}} 
@endslot

@slot('meta_description')
{{ $meta_description ?? 'FAQs'}}
@endslot

@slot('meta_keyword')
{{$meta_keyword ?? 'FAQs'}} 
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
@endslot

@slot('bodyClass')
cms-pages
@endslot
<section class="inner_banner">
<div class="inner_banner_main">
<img src="{{asset(config('custom.assets').'/images/about-banner.jpg')}}" alt="" >
</div>
</section>
<!-- Frequently Asked Questions -->
<section class="faq">
<div class="container">
<div class="text-center">
<div class="theme-title">
<div class="title text-3xl pb-5">Frequently Asked Questions</div>
<!-- <div class="icon mt15"><img alt="" src="/assets/front/images/featured-icon.png" /></div> -->
</div>
<?php echo $cms['content'];?>
</div>

<div class="faq_area">
<div class="faq_left">
<div class="faq_img"><img src="/assets/front/images/faq.jpg" /></div>
</div>

<div class="faq_list">
<?php if(isset($faqs) && !empty($faqs)){ ?>
<ul class="faq_listing">
   <?php
    
      foreach ($faqs as $faq) {
         $faq_question = $faq->question;
         $faq_answer = $faq->answer;
   ?>  
   <li class="faq_main">
   <div>
   <div class="faq_title heading6">{{ $faq->question }}</div>

   <div class="faq_data" style="">
   {!! $faq->answer !!}
   </div>
   </div>
<?php } ?>
   </li>
</ul>
<?php } ?>
</div>
</div>

<div class="text_center"><button class="btn" id="load">Load More Faq</button></div>
</div>
</section>
<!-- Frequently Asked Questions Closed -->
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

$(document).ready(function () {
   $(".faq_main").slice(0, 6).show();
   if ($(".faq_main:hidden").length != 0) {
      $("#load").show();
   }
   $("#load").on("click", function (e) {
      e.preventDefault();
      $(".faq_main:hidden").slice(0, 6).slideDown();
      if ($(".faq_main:hidden").length == 0) {
         $("#load").text("No More FAQ's").fadOut("slow");
      }
   });
})
   </script>     
@endslot

@endcomponent