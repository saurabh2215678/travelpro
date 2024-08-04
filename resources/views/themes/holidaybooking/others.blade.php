@component(config('custom.theme').'.layouts.main')

@slot('title')
    {{$meta_title ?? ''}}
@endslot

@slot('meta_description')
    {{$meta_description ?? ''}}
@endslot

@slot('headerBlock')


@endslot

@slot('bodyClass')
ohters
@endslot
<section class="inner_banner">
    <div class="inner_banner_main">
    <img src="{{asset(config('custom.assets').'/images/about-banner.jpg')}}" alt="" >
    </div>
    </section>
<section>
    <div class="container">
<div class="text_center mb40">
    <div class="theme_title">
        <div class="title">Other Links</div>
        <div class="icon mt15"><img alt="" src="/assets/front/images/featured-icon.png" /></div>
    </div>
</div>
<!-- <?php //if(!$others->isEmpty()){ ?> -->
<ul class="hotel_list others_site_list mt40">
   <!-- <?php 
   // foreach ($others as $other) {
   //    $storage = //Storage::disk('public');
   //    $other_path = //'others/';
   //    $other_title = //CustomHelper::wordsLimit($other->title);
   //    $other_brief =// CustomHelper::wordsLimit($other->brief);
   //    $other_image = $other->image;

   //    $otherThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

   //    if(!empty($other_image)){
   //       if($storage->exists($other_path.$other_image)){
   //          $otherThumbSrc = asset('/storage/'.$other_path.'thumb/'.$other_image);
   //       }
   //    }

?>
 -->
   <!-- <li>
       <a class="hotel_box" href="https://www.zhiwaling.com/" target="_blank">
          <div class="images">
             <img src="">
          </div>
          <div class="hotel_content">
             <div class="title heading_sm3"></div>
             <p class="para_md"></p>
             <div class="rating_btn">
             <div class="btn">Visit Site</div>
          
          </div>
       </div>
       </a>
    </li> -->
            
    <li>
       <a class="hotel_box" href="https://www.zhiwaling.com/" target="_blank">
          <div class="images">
             <img src="/assets/front/images/others1.jpg">
          </div>
          <div class="hotel_content">
             <div class="title heading_sm3 mb16">Zhiwa Ling Heritage Hotel</div>
             <div class="rating_btn">
             <div class="btn">Visit Site</div>
          
          </div>
       </div>
       </a>
    </li>
       
    
       
   
    <li>
       <a class="hotel_box" href="https://zhiwalingascent.com/" target="_blank">
          <div class="images">
            <img src="/assets/front/images/others3.jpg">
          </div>
          <div class="hotel_content">
             <div class="title heading_sm3 mb16">Zhiwa Ling ASCENT hotel</div>
             <div class="rating_btn">
                <div class="btn">Visit Site</div>
            
          </div>
       </div>
       </a>
    </li>
    <li>
       <a class="hotel_box" href="https://bhutaninsurance.com.bt/" target="_blank">
          <div class="images">
            <img src="/assets/front/images/others4.jpg">
          </div>
          <div class="hotel_content">
             <div class="title heading_sm3 mb16">Bhutan Insurance Limited</div>
             <div class="rating_btn">
                <div class="btn">Visit Site</div>
            
          </div>
       </div>
       </a>
    </li>

    <li>
       <a class="hotel_box" href="https://www.facebook.com/bigcolabhutan" target="_blank">
          <div class="images">
            <img src="/assets/front/images/others5.jpg">
          </div>
          <div class="hotel_content">
             <div class="title heading_sm3 mb16">ICE Beverages (BIG cola)</div>
             <div class="rating_btn">
                <div class="btn">Visit Site</div>
            
          </div>
       </div>
       </a>
    </li>

  <?php /* <li>
       <a class="hotel_box" href="https://www.facebook.com/aboutbhutan" target="_blank">
          <div class="images">
            <img src="/assets/front/images/others6.jpg">
          </div>
          <div class="hotel_content">
             <div class="title heading_sm3">Yangphel Adventure Travel</div>
             <p class="para_md">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the</p>
             <div class="rating_btn">
                <div class="btn">Visit Site</div>
            
          </div>
       </div>
       </a>
    </li>
    <li>
       <a class="hotel_box" href="#">
          <div class="images">
            <img src="/assets/front/images/departure3.jpg">
          </div>
          <div class="hotel_content">
             <div class="title heading_sm3">The Bhutan Education & Technology Academy (www.betapark.bt)</div>
             <p class="para_md">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the</p>
             <div class="rating_btn">
                <div class="btn">Visit Site</div>
            
          </div>
       </div>
       </a>
    </li> */ ?>
 <?php //} ?>
    </ul>
 <?php //} ?>
    </div>
</section>



@slot('footerBlock')
Visit Sites
@endslot

@endcomponent
