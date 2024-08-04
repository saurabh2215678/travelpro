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
        <div class="title">Downloads</div>
        <div class="icon mt15"><img alt="" src="/assets/front/images/featured-icon.png" /></div>
    </div>
</div>
<!-- <?php //if(!$downloads->isEmpty()){ ?> -->
<ul class="hotel_list mt40">
   <?php 
   // foreach ($downloads as $download) {
   //    $storage = Storage::disk('public');
   //    $download_path = 'downloads/';
   //    $download_title = CustomHelper::wordsLimit($download->title);
   //    $download_brief = CustomHelper::wordsLimit($download->brief);
   //    $download_image = $download->image;

   //    $downloadThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

   //    if(!empty($download_image)){
   //       if($storage->exists($download_path.$download_image)){
   //          $downloadThumbSrc = asset('/storage/'.$download_path.'thumb/'.$download_image);
   //       }
   //    }

?>

   <!-- <li>
       <a class="hotel_box" href="#">
          <div class="images">
             <img src="">
          </div>
          <div class="hotel_content">
             <div class="title heading_sm3"></div>
             <p class="para_md"></p>
             <div class="rating_btn">
             <div class="btn">Download</div>
          
          </div>
       </div>
       </a>
    </li> -->
            
    <li>
       <a class="hotel_box" target="_balnk" href="/assets/front/pdf/_2116988830_Tentative_Festival_Dates_2022.pdf">
          <div class="images">
             <img src="/assets/front/images/blog5.jpg">
          </div>
          <div class="hotel_content">
             <div class="title heading_sm3">Festival Dates for 2022</div>
             <p class="para_md">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the</p>
             <div class="rating_btn">
             <div class="btn">Download</div>
          
          </div>
       </div>
       </a>
    </li>
       
    <li>
       <a class="hotel_box" target="_balnk" href="/assets/front/pdf/_1509196318_2021_Tentative_Festival_Dates_17Jan2020.pdf">
          <div class="images">
             <img src="/assets/front/images/blog-list2.jpg">
          </div>
          <div class="hotel_content">
             <div class="title heading_sm3">Festival Dates(2021)</div>
             <p class="para_md">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the</p>
             <div class="rating_btn">
              <div class="btn">Download</div>
          
          </div>
       </div>
       </a>
    </li>
       
    <li>
       <a class="hotel_box" target="_balnk" href="/assets/front/pdf/_167528921_biking.pdf">
          <div class="images">
            <img src="/assets/front/images/blog-single2.jpg">
          </div>
          <div class="hotel_content">
             <div class="title heading_sm3">Mountain Biking</div>
             <p class="para_md">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the</p>
             <div class="rating_btn">
                <div class="btn">Download</div>
            
          </div>
       </div>
       </a>
    </li>
 <?php //} ?>
    </ul>
 <?php //} ?>
    </div>
</section>



@slot('footerBlock')
downloads
@endslot

@endcomponent
