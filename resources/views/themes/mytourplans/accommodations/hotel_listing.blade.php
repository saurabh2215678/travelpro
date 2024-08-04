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
    <?php
    $old_destination_id = (isset($accommodation->destination_id))?$accommodation->destination_id:'';
    $sub_destination_request =  (!empty(\Request::get('sub_destination'))) ? \Request::get('sub_destination') : '';
       $destination_request =  (!empty(\Request::get('destination'))) ? \Request::get('destination') : '';

       $b_image =asset(config('custom.assets').'/images/hotels.jpg');
       if($banner_image) {
        $b_image = $banner_image;
      }

       ?>

      <section class="inner_banner">
         <div class="inner_banner_main">
            <img src="{{$b_image}}" alt="{{$page_title}}" />
         </div>
         @include(config('custom.theme').'/includes/search')
      </section>

      

      <section class="recommendation_place hotellist">
        <div class="container">
          <?php /*
           <div class="text_center mb50">
              <div class="theme_title">
                 <div class="title">
                  Our Partner Hotels
                  <!-- Add Search-Box Start -->
                  <div class="bgcolor ">
                 <form class="form-inline" method="GET">
                 <ul class="search_inner">
                    <li>  <label for="destination">Filter By Destination:</label>  </li>

                    <li>

                        <select class="form_control admin_input1 destination" name="destination" id="destination">
                     <option value="">Destination</option>
                     <?php if(!($destinations->isEmpty())){
                        $parent_destinations = $destinations->where('parent_id', 0);
                        if(!($parent_destinations->isEmpty())){
                     ?>
                        @foreach($parent_destinations as $destination_it)
                        <option value="{{$destination_it->id}}" <?php echo ($destination_it->id == $destination_request) ? "selected":""; ?> >{{ $destination_it->name }}</option>
                        @endforeach
                     <?php }}?>
                  </select>
                    </li>


                  <li>
                  <select class="form_control" name="sub_destination" id="sub_destination">
                  <option value="">Sub Destination</option>
                  </select>
                  </li>

                  <li>
                           <ul class="btn_inline">
                      <li>     <button type=
                  "submit" class="btn btn-success">Search</button> </li>

                  <li>  <a class="btn2" href="{{route('hotelListing')}}" class="btn_admin2">Reset</a></li>
                           </ul>
                  </li>
                 </ul>
              </form>
                 </div>
                  <!-- Add Search-Box Closed -->
                  
                 </div>
                 <div class="icon mt15">
                    <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
                 </div>
              </div>
           </div>*/ ?>

           <div>
              <div>
                 
 <div class="xl:container xl:mx-auto">
 <div class="text_center mb-5">
      <div class="theme_title mb-5">
         @if(isset($page_title) && !empty($page_title))
            <div class="title text-2xl	">{{$page_title}}</div>
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
 

         <?php if(isset($accommodations) && !empty($accommodations)){ ?>
            <div class="flex flex-wrap">
               <ul class="featured_list hotel_list lg:w-3/4 pr-5">
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
                             <span class="hotel_name2 pl-5 text-lg">{{$accommodation->accommodationDestination ? $accommodation->accommodationDestination->name:''}}</span>
                           </div>
                           {!! $accommodation_brief !!}             
                        </div>
                     </a>
                     
                     <div class="hotel_bottom">
                        <div class="flex justify-between py-3 px-2.5">
                          <?php if(isset($accommodation_features) && !empty($accommodation_features)){ ?>
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
               <div class="pull-right lg:w-1/4">
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
            </div>
        </div>
      </div>
    </div>
    <div class="pagination-wrapper hotel-pagination mt-7 ml-5">
            {{ $accommodations->appends(request()->input())->links('vendor.pagination.bootstrap-4'); }}
            </div>
  </div>
            
         <?php }else{ ?>
            <div class="alert_not_found">No Hotels found.</div>
            <?php } ?>

             
        </div>
      </section>

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
</script>
@endslot

@endcomponent
