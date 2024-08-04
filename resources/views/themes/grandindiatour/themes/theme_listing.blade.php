@component(config('custom.theme').'.layouts.main')

    @slot('title')
      {{ $meta_title ?? ''}}
    @endslot    

    @slot('meta_description')
      {{ $meta_description ?? ''}}
    @endslot

    @slot('meta_keyword')
      {{ $meta_keyword ?? ''}}
    @endslot

    @slot('headerBlock')
   
    @endslot

    @slot('bodyClass')
        theme_listing_class
    @endslot

      <section class="inner_banner">
         <div class="inner_banner_main">
            <img src="{{asset(config('custom.assets').'/images/Banner (2).jpg')}}" alt="Banner">
         </div>
         @include(config('custom.theme').'/includes/search')
      </section>

      

      <section class="recommendation_place pack-category">
        <div class="container">
          <?php /*
           <div class="text_center mb50">
            <?php
            $widgetDetail = CustomHelper::getWidget('after-home-page-widget-2');
           //prd($widgetDetail);
            if(!empty($widgetDetail)){
               $storage = Storage::disk('public');
               $path = 'widgets/';
               $section_heading = $widgetDetail->section_heading;
               $description = $widgetDetail->description;
              ?>
              <div class="theme_title">
                 <div class="title">
                  {{ $widgetDetail->section_heading }}
                 </div>
                 {!! $widgetDetail->description !!}  
              </div>
            <?php } ?>
           </div>*/ ?>

           <div class="mb50">
            <div class="theme-title">
              <div class="flex flex-wrap theme_title mb-6">
                @if(isset($page_title) && !empty($page_title))
                <h1 class="title text-2xl">{{$page_title}}</h1>
                @endif
                <div class="breadcrumb_full">
                  <div class="container">
                      <ul class="breadcrumb">
                        <li><a href="{{url('/')}}">Home</a>
                        </li>
                        <li><a href="{{route('tourCategoryListing')}}">{{$page_url_label}}</a>
                        </li>
                      </ul>
                  </div>
                </div>
              </div>
              @if(isset($page_brief) && !empty($page_brief))
              <p>{!!$page_brief!!}</p>
              @endif
              @if(isset($page_description) && !empty($page_description))
              <p>{!!$page_description!!}</p>
              @endif
            </div>
          </div>            
            

           <ul class="theme_listing">
          <?php if(isset($themes) && !empty($themes)){ //if(!$themes->isEmpty()){
            foreach ($themes as $theme) {
               $storage = Storage::disk('public');
               $theme_path = "themes/";
               $theme_title = CustomHelper::wordsLimit($theme->title);
               $theme_brief = CustomHelper::wordsLimit($theme->brief);
               $theme_slug = $theme->slug;
               $theme_image = $theme->image;
               $themeSrc =asset(config('custom.assets').'/images/noimagebig.jpg');
               if(!empty($theme_image) && $storage->exists($theme_path.$theme_image)) {
                $themeSrc = asset('/storage/'.$theme_path.$theme_image);
               }
            ?>
          <li>
        <a class="tour_category_box m-0" href="{{ route('tourCategoryDetail',[$theme->slug]) }}">
              <div class="images"><img src="{{ $themeSrc }}" class="theme_radius" alt="" /></div>
            </a>
            <div class="text text-center mb-3 pb-7 border">
                <span class="text-base pt-3 pb-3"> <a class="tour_category_box m-0 hover:text-teal-400" href="{{ route('tourCategoryDetail',[$theme->slug]) }}">{!! $theme_title !!} </a></span>
                <div class="tour_info">
                  <!-- <span> {{ $theme_title }}</span>
                  <p>{{ $theme_brief }}</p> -->
                </div>
                <div class="fintout-more text-center">
            <a class="bg-transparent border-blue-900 border	rounded-full p-1 pl-3 pr-3 text-sm" href="{{ route('tourCategoryDetail',[$theme->slug]) }}"> Find out more</a>
            </div>
              </div>
          </li>
          <?php }} ?>
        </ul>
        </div>
      </section>

@slot('bottomBlock')

@endslot

@endcomponent