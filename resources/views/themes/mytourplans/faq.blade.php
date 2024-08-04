@component(config('custom.theme').'.layouts.main')

@slot('title')
{{$meta_title}}
@endslot

@slot('meta_description')
{{ $meta_description ?? ''}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
@endslot

@slot('bodyClass')
bodybg cms-pages
@endslot
<?php
?>
<section class="innerbanner" style="background: url({{asset(config('custom.assets').'/images/course-detail.jpg') }}) center center no-repeat;">  
 <div class="container">
   <div class="bannerhead">
     <h1 class="heading1">{{$faq_categories->title ?? 'Frequently Asked Questions'}}</h1>
     <div class="inner-breadcrumb">
         <ul>
          <li><a href="/" title="Home"><i class="la la-home"></i> Home &nbsp; ></a></li><?php if(isset($faq_categories->title) && !empty($faq_categories->title)){?><li><a href="{{url('/faqs')}}" title="Faqs"><i class="la la-home"></i> Frequently Asked Questions &nbsp; ></a></li><?php } ?><li>{{$faq_categories->title ?? 'Frequently Asked Questions'}}</li>
         </ul>
      </div>
  </div>
  
</div>
</section>
<!-- Frequently Asked Questions -->
<section class="secpad inner-page faq faq-cms-block">
   <div class="animation-elements">
         <span class="discover-fishing" data-aos="fade-left" data-aos-duration="100"></span>
         <span class="discover-boble" data-aos="fade-left" data-aos-duration="500"></span> 
         <span class="fisher-men" data-aos="fade-left" data-aos-duration="1500"></span>
         <span class="panda" data-aos="fade-up" data-aos-duration="3000"></span>
         <span class="octapus" data-aos="fade-up" data-aos-duration="3500"></span>        
   </div>
   <div class="container">
      <?php if(!empty($faq_categories)){ ?>
       <h2 class="heading2">{{(isset($faq_categories->heading) && !empty($faq_categories->heading) ? $faq_categories->heading : $faq_categories->brief)}}</h2>
       <p>{!!$faq_categories->content!!}</p>
    <?php } ?>
    <div class="faqlist">
      <?php if(isset($faqs) && !empty($faqs)){ ?>
         <ul>
            <?php
            $q = 0;
            foreach ($faqs as $faq) {
              $q++;    
              $faq_question = $faq->question;
              $faq_answer = $faq->answer;
              ?>  
              <li class="faq_main">
               <div>
                  <div class="faq_title heading6"><!-- {{'Q.'.$q.' '}} -->{{ $faq->question }}</div>

                  <div class="faq_data" style="">
                     {!! $faq->answer !!}
                  </div>
               </div>
            </li>
         <?php } ?> 
      </ul>

   <?php } ?>
</div> 

<!-- <div class="text_center"><button class="btn" id="load">Load More Faq</button></div> -->
</div>
</section>
<!-- Frequently Asked Questions Closed -->
@slot('bottomBlock')
<script>
   $('.ftitle').click( function(){
      $(this).parent().toggleClass('active'); 
      
   });
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