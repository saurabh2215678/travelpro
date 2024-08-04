@component(config('custom.theme').'.layouts.main')

  @slot('title')
    {{ $meta_title ?? 'Hotels'}}
  @endslot    

  @slot('meta_description')
    {{ $meta_description ?? 'Hotels'}}
  @endslot
  
  @slot('meta_keyword')
    {{ $meta_keyword ?? 'Hotels'}}
  @endslot

  @slot('headerBlock')
 
  @endslot

  @slot('bodyClass')
      hotel_listing_class
  @endslot
  <style>
   .sticky_header #search_hotels_form { position: fixed; z-index: 99; background:var(--secondary-color); width: 100%; max-width: 100%; left: 0; top: 4rem; padding: 10px 25px; box-shadow: -1px 3px 12px #0000001f;color: #fff; }
   .sticky_header #search_hotels_form h3 { display: none; }
   .sticky_header #search_hotels_form .header-top-search { max-width: 750px; margin: auto; }
   .sml-header {box-shadow: none;}
  </style>
  <?php
  $old_destination_id = (isset($accommodation->destination_id))?$accommodation->destination_id:'';
  $sub_destination_request =  (!empty(\Request::get('sub_destination'))) ? \Request::get('sub_destination') : '';
  $filter_tour_type =  (!empty(\Request::get('filter_tour_type'))) ? \Request::get('filter_tour_type') : [];
  $destination_request =  (!empty(\Request::get('destination'))) ? \Request::get('destination') : '';

  $b_image =asset(config('custom.assets').'/images/hotels.jpg');
  if($banner_image) {
    $b_image = $banner_image;
  }
  $star_rating = (request()->has('stars'))?request()->input('stars'):[];
  if(!is_array($star_rating)) {
    $star_rating = explode(',', $star_rating);
  }

  $class_arr = (request()->has('class'))?request()->input('class'):[];
  if(!is_array($class_arr)) {
    $class_arr = explode(',', $class_arr);
  }

  $categories_arr = (request()->has('categories'))?request()->input('categories'):[];
  if(!is_array($categories_arr)) {
    $categories_arr = explode(',', $categories_arr);
  }

  $features_arr = (request()->has('features'))?request()->input('features'):[];
  if(!is_array($features_arr)) {
    $features_arr = explode(',', $features_arr);
  }
  ?>

      <section class="inner_banner">
         <div class="inner_banner_main">
            <img src="{{$b_image}}" alt="{{$page_title}}" />
         </div>
         @include(config('custom.theme').'/includes/search')
      </section>

      
   <section class="hotel_bookinglist pt-11">
   <div class="container">
      <div class="row">
         <div class="pull-right lg:w-1/4 side_bar">
            <!-- <h1 class="title text-2xl">Filter</h1> -->
            <form action="{{ route('hotelListing') }}" method="GET" id="adv_hotel_search" name="adv_hotel_search">
            <?php if(!empty($categories)) { ?>
            <div class="acco_category checkbox_list">
              <div class="filter_box">
                <div class="title pb-2">Property types</div>
                <div class="acco_category checkbox_list">
                  <ul class="term-list">
                    <?php foreach($categories as $category){ ?>
                    <li class="term-item">
                      <input type="checkbox" id="category_{{$category->id}}" name="categories[]" value="{{$category->id??''}}" <?php if(!empty($categories_arr) && in_array($category->id, $categories_arr)){ echo 'checked'; }?> >
                      <label for="category_{{$category->id}}"> <span>{{$category->title??''}}</span></label>
                    </li>
                    <?php } ?>
                  </ul>
                </div>
              </div>
            </div>
            <?php } ?>

            <?php if(!empty($features)) { ?>
            <div class="acco_category checkbox_list">
              <div class="filter_box">
                <div class="title pb-2">Amenities</div>
                <div class="acco_category checkbox_list">
                  <ul class="term-list">
                    <?php foreach($features as $feature){ ?>
                    <li class="term-item">
                      <input type="checkbox" id="feature_{{$feature->id}}" name="features[]" value="{{$feature->id??''}}" <?php if(!empty($features_arr) && in_array($feature->id, $features_arr)){ echo 'checked'; }?> >
                      <label for="feature_{{$feature->id}}"> <span>{{$feature->title??''}}</span></label>
                    </li>
                    <?php } ?>
                  </ul>
                </div>
              </div>
            </div>
            <?php } ?>
            <div class="filter_box star-loc">
               <div class="title pb-2">Traveller rating</div>
               <?php for ($star=5; $star > 1; $star--) { ?>
               <div class=" checkbox_list">
                  <input type="checkbox" id="star_rating{{$star}}" value="{{$star}}" 
                  <?php if(!empty($star_rating) && in_array($star, $star_rating)){ echo 'checked'; }?> name="stars[]">
                  <label for="star_rating{{$star}}" class="flex items-center">
                    <ul class="rating_list py-1" rating="{{$star}}">
                     <?php for ($i=0; $i < $star ; $i++) { ?>
                        <li>
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                        </li>
                     <?php } ?>
                  </ul>
                  </label>
               </div>
               <?php } ?>
            </div>
            
            <div class="acco_category checkbox_list">
              <div class="filter_box">
                <div class="title pb-2">Hotel class</div>
                <div class="acco_category checkbox_list">
                  <ul class="term-list">
                    <?php for($i=5; $i>0; $i--) { ?>
                    <li class="term-item">
                      <input type="checkbox" id="class_{{$i}}" name="class[]" value="{{$i}}" <?php if(!empty($class_arr) && in_array($i, $class_arr)){ echo 'checked'; }?> >
                      <label for="class_{{$i}}"><span>{{$i.' Star'}}</span></label>
                    </li>
                    <?php } ?>
                  </ul>
                </div>
              </div>
            </div>
          </form>
         </div>
         <div class="pull-right lg:w-3/4">
            <div class="text_center mb-5">
               <div class="theme_title mb-5">
                  @if(isset($page_title) && !empty($page_title))
                  <h1 class="title text-2xl">{{$page_title}}</h1>
                  @endif
                  <div class="breadcrumb_full">
                     <div class="container">
                        <ul class="breadcrumb py-2 text-right">
                           <li><a href="{{url('/')}}">Home</a></li>
                           <li><a href="{{route('hotelListing')}}">{{$page_url_label}}</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
               <div id="disc-title" class="text-left">
                  @if(isset($page_brief) && !empty($page_brief))
                  <p>{!!$page_brief!!}</p>
                  @endif
                  @if(isset($page_description) && !empty($page_description))
                  <div>{!!$page_description!!}</div>
                  @endif
               </div>
            </div>
            <div class="hotel-list-view">
              @if(!$accommodations->isEmpty())
                @foreach ($accommodations as $accommodation)
                  @include(config('custom.theme').'/accommodations/_hotel_listing_li',['accommodation'=>$accommodation,'checkin'=>$checkin,'checkout'=>$checkout,'adult'=>$adult,'child'=>$child,'infant'=>$infant])
                @endforeach
              @else
              <div class="hotel-card mb-5">
              <div class="alert_not_found">No Hotels found matching your search criteria. Please look for an alternate package or call our travel experts at <a href="tel:
8491947053">8491947053</a>. You may also drop us an email at <a href="mailto:info@grandindiatour.com">info@grandindiatour.com</a> </div>
              </div>
              @endif
            </div>
         </div>
      </div>
   </div>
</section>

















      <?php /*
      <section class="recommendation_place hotellist">
        <div class="container">
 <div class="xl:container xl:mx-auto">
 <div class="text_center mb-5">
      <div class="theme_title mb-5">
         @if(isset($page_title) && !empty($page_title))
            <h1 class="title text-2xl">{{$page_title}}</h1>
         @endif
         <div class="breadcrumb_full">
         <div class="container">
            <ul class="breadcrumb py-2 text-right">
               <li><a href="{{url('/')}}">Home</a>
               </li>
               <li><a href="{{route('hotelListing')}}">{{$page_url_label}}</a>
               </li>
            </ul>
         </div>
      </div>
        
      </div>
      <div id="disc-title" class="text-left">
         @if(isset($page_brief) && !empty($page_brief))
            <p>{!!$page_brief!!}</p>
            @endif
            @if(isset($page_description) && !empty($page_description))
            <div>{!!$page_description!!}</div>
            @endif
         </div>
      </div>
 

         <?php if(!$accommodations->isEmpty()){ ?>
            <div class="flex flex-wrap">
               <ul class="featured_list hotel_list w-full pr-5">
               <?php foreach ($accommodations as $accommodation) {
                  //prd($accommodation->toArray());
                           $storage = Storage::disk('public');
                           $accommodation_path = "accommodations/";
                           $accommodation_name = $accommodation->name;
                           $accommodation_slug = $accommodation->slug;
                           $accommodation_brief = $accommodation->brief ?? '';
                           $accommodation_star = $accommodation->star_classification;
                           $accommodation_image = $accommodation->image;
                           $accommodationCategories = $accommodation->accommodationCategories;
                           $category_name = $accommodationCategories->title ?? '';

                           $accommodationThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
                           $accommodation_features_arr = $accommodation->accommodation_feature ? json_decode($accommodation->accommodation_feature): [];
                           // $accommodation_features_arr = $accommodation_features_arr;

                           if(!empty($accommodation_image)){
                              if($storage->exists($accommodation_path.$accommodation_image)){
                                 $accommodationThumbSrc = asset('/storage/'.$accommodation_path.'thumb/'.$accommodation_image);
                              }
                           }
                  ?>
                  <li data-aos="fade-up" data-aos-duration="2000">
                     <a class="featured_box" href="{{route('hotelDetail',['slug' => $accommodation_slug])}}">
                        <div class="images">
                        <img src="{{ $accommodationThumbSrc }}" alt="{{ $accommodation_name }}" class="img_responsive">
                        </div>
                        <div class="featured_content px-2.5 py-3.5" >
                           <div class="title text-xl">{{ $accommodation_name }}</div>
                           <ul class="rating_list py-3" rating="{{ $accommodation_star }}">
                              @if(isset($accommodation_star) && $accommodation_star > 0)
                              <!-- <li>
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                              </li>
                              <li>
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                              </li> -->
                              <?php for ($i=0; $i < $accommodation_star ; $i++) { ?>
                                 <li>
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>
                                 </li>
                              <?php } ?>
                              @endif
                           </ul>
                           <div class="title hotel_location">
                             <span class="hotel_name2 pl-5 text-lg"> {{$accommodation->AccommodationDefaultLocation ? $accommodation->AccommodationDefaultLocation->DestinationLocation->name.', ':''}} {{$accommodation->accommodationDestination ? $accommodation->accommodationDestination->name:''}}</span>
                           </div>
                           <p>{!! $accommodation_brief !!}</p>
                        </div>
                     </a>
                     <div class="hotel_bottom">
                        <div class="flex justify-between py-3 px-2.5">
                          <?php if($accommodation_features_arr){ ?>
                           <h4> Facilities : <br>
                              <?php  $i = 0;
                              foreach ($accommodation_features as $key => $accommodation_feature) {
                                 $accommodation_feature_id =$accommodation_feature->id ?? '';
                                 if(in_array($accommodation_feature_id, $accommodation_features_arr)){
                                    // prd($accommodation_feature);
                                    if($i != 0){
                                        echo ", ";
                                    }
                                    echo $accommodation_feature->title;
                                    $i++;
                                 }
                              } ?>
                           </h4>
                           <?php }else{ ?>
                             <h4>&nbsp;</h4>
                           <?php } ?>
                           <a class="btn text-sm mt-1" href="{{route('hotelDetail',['slug' => $accommodation_slug])}}">View Details</a>
                        </div>
                     </div>                  
                  </li>
                  
                  <?php } ?>
               </ul>
               <div class="pull-right lg:w-1/4" style="display:none;">
                  <div class="side_hotel_bar">
                     <div class="search_hotel pull-left">
                        <div class="left_form">
                           <form action="" class="" id="" method="GET" accept-charset="utf-8">
                              <div class="form-inline">
                                 <div class="title"> Search Hotel</div>
                                 <?php 
                                 $sdest = (request()->has('destination'))?request()->input('destination'):'';
                                 $stars = (request()->has('stars'))?request()->input('stars'):'';
                                 ?>
                                 <select name="destination" id="" class="form-control" data-placeholder="Select Destination">
                                    <option value="">Select Destination</option>
                                    {!!CustomHelper::getDestinationOptions('', $sdest, ['show_active'=>true])!!}
                                 </select>                              
                                 <select name="stars" id="" class="form-control" data-placeholder="Select star">
                                    <option value="">Select Stars</option>
                                       <?php
                                       for($i = 1; $i<=5; $i++){
                                       $selected = '';
                                          if($stars == $i){
                                          $selected = 'selected';
                                          }
                                       ?>
                                       <option value="{{$i}}" {{$selected}}>{{$i.' Star'}}</option>
                                       <?php
                                    }
                                    ?>
                                 </select>
                                 <div class="pull-right">
                                 <input type="submit" value="Search" name="search" class="btn btn-success">
                              </div>
                           </div>
                           </form>
                        </div>
                     </div>
                     </div>      
               </div>
               <div class="pagination-wrapper hotel-pagination mt-7 ml-0">
               {{ $accommodations->appends(request()->input())->links('vendor.pagination.bootstrap-4'); }}
            </div>
            </div>
        </div>
      </div>
    </div>
    
  </div>
      <?php }else{ ?>
         <div class="alert_not_found">No Hotels found.</div>
         <?php } ?>          
     </div>
   </section>*/ ?>

@slot('bottomBlock')
<script>
   var _token = '{{ csrf_token() }}';
   $(document).ready(function () {
      var destinationId = $('#destination').val();
      var subDestinationId = '{{ $sub_destination_request }}';

      populateSubDestination(destinationId,subDestinationId);

      $('#destination').on('change',function(e) {
         var destination_id = e.target.value;
         populateSubDestination(destination_id);
      });
  });

   function populateSubDestination(destination_id,setDestination=""){
      $.ajax({
         url:"{{ route('common.ajax_load_sub_destinations') }}",
         type:"POST",
         headers:{'X-CSRF-TOKEN': _token},
         data: {
            destination_id: destination_id
         },
         success:function (data) {
            let text = "";
            $('#sub_destination').empty();
            text +=  `<option value="">Sub Destination</option>`
            text += data.options;
            /*data.states.forEach(function(item, index){
              text +=  `<option value="${item.id}">${item.name}</option>`
            })*/
            $("#sub_destination").html(text)
         },complete:function(){
            $('#sub_destination').val(setDestination);
         }
      });
   }


   $('.rating_list').each(function(){
      var ratingValue = parseInt($(this).attr('rating'))
      $(this).children('li').each(function(i){
         if(i < ratingValue){
            $(this).addClass('active');
         }
      })
   });

// =======Images Thumb Banner Gallery =====
$('.img-list-view').each(function(){
   var thumbSlider = $(this).find('.hotelViewgallery')[0];
   var mainSlider = $(this).find('.hotelViewgallery2')[0];

   var nextButton = $(this).find('.swiper-button-next')[0];
   var prevButton = $(this).find('.swiper-button-prev')[0];

   var thumbSwiper = new Swiper(thumbSlider, {
      loop: false,
      protect: true,
      spaceBetween: 3,
      slidesPerView: 4,
      freeMode: true,
      watchSlidesProgress: true,
    });
    var mainSwiper = new Swiper(mainSlider, {
      loop: false,
      protect: true,
      spaceBetween: 5,
      navigation: {
        nextEl: nextButton,
        prevEl: prevButton,
      },
      thumbs: {
        swiper: thumbSwiper,
      },
    });

})


// var swiper = new Swiper(".hotelViewgallery", {
//       loop: true,
//       spaceBetween: 3,
//       slidesPerView: 4,
//       freeMode: true,
//       watchSlidesProgress: true,
//     });
//     var swiper2 = new Swiper(".hotelViewgallery2", {
//       loop: true,
//       spaceBetween: 5,
//       navigation: {
//         nextEl: ".swiper-button-next",
//         prevEl: ".swiper-button-prev",
//       },
//       thumbs: {
//         swiper: swiper,
//       },
//     });

$(document).ready(function(){
  $(document).on('change','.side_bar .filter_box input[type=checkbox]', function(){
    $('#adv_hotel_search').submit();
  });
});

/////  Show more, less list with + - buttons ///
$('ul.term-list').each(function(){
  var LiN = $(this).find('li').length;
  if( LiN > 4){    
    $('li', this).eq(2).nextAll().hide().addClass('toggleable');
    $(this).append('<li class="more cursor-pointer text-sm font-bold">Show more <i class="fa fa-angle-down"></i></li>');    
  }  
});
$('ul.term-list').on('click','.more', function(){
  if( $(this).hasClass('less') ){    
    $(this).text('Show more').removeClass('less');    
  }else{
    $(this).text('Show less').addClass('less'); 
  }
  $(this).siblings('li.toggleable').slideToggle(); 
});

</script>

@endslot

@endcomponent
