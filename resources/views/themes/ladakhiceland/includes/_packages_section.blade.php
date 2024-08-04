<section class="domestic_package">
      <div class="container">
       <div class="text-center mb-6 padding_x">
         <div class="text-sky-500">Take a look at Our</div>
            <h3 class="section_heading">{{$section_title}}</h3>
         </div>
         <div class="slider_box">
               <div class="swiper domestic_package_slider">
                  <div class="swiper-wrapper">                
                     @include(config('custom.theme').'/includes/package-li-box',['packages'=>$packages])

               </div>
            </div>

            <div class="slider_btns">
               <div class="package-prev theme-prev"><img src="{{ asset(config('custom.assets').'/images/next.png') }}" alt=""></div>
               <div class="package-next theme-next"><img src="{{ asset(config('custom.assets').'/images/prev.png') }}" alt=""></div>
            </div>
         </div>
         <div class="text_center view_all_btn mt45">
      <a href="{{url('search-packages')}}" class="btn" hreflang="en">View All <i class="fa fa-long-arrow-right"></i></a>
   </div>
      </div>
 
   
   </section>