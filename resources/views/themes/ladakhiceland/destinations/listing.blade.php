@component(config('custom.theme').'.layouts.main')

    @slot('title')
      {{ $meta_title ?? ''}}
    @endslot    

    @slot('meta_description')
      {{ $meta_description ?? '' }}
    @endslot

    @slot('meta_keyword')
      {{ $meta_keyword ?? '' }}
    @endslot

    @slot('headerBlock')
   
    @endslot

    @slot('bodyClass')
        theme_listing_class
    @endslot

    <?php 
    $storage = Storage::disk('public');
    $path = 'banners/';
    // $b_image =asset(config('custom.assets').'/images/noimage.jpg');
    $b_image =asset(config('custom.assets').'/img/default_banner.jpg');
    if($banner_image) {
      $b_image = $banner_image;
    }
    /*foreach($banners as $banner){
      $images = (isset($banner->Images))?$banner->Images:'';
      if(!empty($images) && count($images) > 0){
        foreach($images as $image){
          if(!empty($image->image_name) && $storage->exists($path.$image->image_name)) {
            $b_image = url('/storage/banners/'.$image->image_name);
          }
        }
      }
    }*/
    ?>
  <section class="inner_banner">
      <div class="inner_banner_main">
        <img src="{{$b_image}}" alt="{{$page_title}}" />
      </div>
  </section> 

  <section class="search_home py-0 md:px-0 hidden">
    <div>
        @include(config('custom.theme').'.includes.search')
    </div>
  </section>

    <div class="breadcrumb_full hidden">
      <div class="container">
         <ul class="breadcrumb">
            <li><a href="{{url('/')}}">Home</a>
            </li>
           <li><a href="{{route('destinationListing')}}">{{$page_url_label}}</a>
            </li>
           
         </ul>
      </div>
   </div>
      <section class="recommendation_place destination-cat pb_150">
        <div class="container">
        <div class="text_center mb50">
            <div class="theme-title">
                  @if(isset($page_title) && !empty($page_title))
                  <h1 class="title text-3xl pb-5">{{$page_title}}</h1>
                  @endif
                  @if(isset($page_brief) && !empty($page_brief))
                  <p>{!!$page_brief!!}</p>
                  @endif
                  @if(isset($page_description) && !empty($page_description))
                  <p>{!!$page_description!!}</p>
                  @endif
            </div>
        </div>

      
          <?php if(!$destinations->isEmpty()){?>
           <ul class="theme_listing">
            <?php
            //prd($destinations);
            foreach ($destinations as $destinationData) {
               $storage = Storage::disk('public');
               $destination_path = "destinations/";
               $destination_name = CustomHelper::wordsLimit($destinationData->name);
               $destination_brief = CustomHelper::wordsLimit($destinationData->brief);
               $destination_id = (isset($destinationData->id))?$destinationData->id:"";
               $destination_slug = $destinationData->slug;
               $destination_image = $destinationData->image;

               $destinationSrc =asset(config('custom.assets').'/images/noimagebig.jpg');

               if(!empty($destination_image) && $storage->exists($destination_path.$destination_image))
               {
               $destinationSrc = asset('/storage/'.$destination_path.$destination_image);
               }
            ?>
          <li>
        <a class="tour_category_box" href="{{ route('destinationDetail',['slug'=>$destination_slug]) }}">
              <img src="{{ $destinationSrc }}"  class="theme_radius"/>
              <div class="text text-center">
                <span> {{ $destination_name }}</span>
                <div class="tour_info">
                  <span> {{ $destination_name }}</span>
                  <p>{!! $destination_brief !!}</p>
                </div>
             
              </div>
            </a>
          </li>
          <?php } ?>
        </ul>
        <div class="pagination-wrapper">
          <?php echo $destinations->appends(request()->input())->links('vendor.pagination.bootstrap-4'); // frontpaginate?>
        </div>
      <?php }else{ ?>
            <div class="alert_not_found">No Destination Type found.</div>
            <?php } ?>
      </div>
      </section>

@slot('bottomBlock')

@endslot

@endcomponent