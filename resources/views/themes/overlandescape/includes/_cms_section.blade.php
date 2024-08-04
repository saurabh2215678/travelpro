<section class="inner-cms-page">
  <div class="xl:container xl:mx-auto">
    <div class="text_center">
      <div class="theme_title">
        <h1 class="title text-2xl">{{$cms['title']??''}}</h1>
      </div>
    </div>

    <div class="about_inner">
      @if(!empty($cms['imageSrc']))
      <div class="singleimgs mb-6">
        <div class="single_imgs"><img class="w-full" src="{{$cms['imageSrc']}}" alt="{{$cms['title']??''}}" ></div>
      </div>
      @endif

      <?php if(!empty($cms['brief']) || !empty($cms['content'])){ ?>
      <div class="about_text">
        <?php if(!empty($cms['brief'])){ ?>
        <p>{!!$cms['brief']!!}</p>
        <?php } ?>

        <?php if(!empty($cms['content'])){ ?>
        {!!$cms['content']!!}
        <?php } ?>
      </div>
      <?php } ?>
    </div>

    <?php if( isset($cms['child_data']) && !empty($cms['child_data']) && count($cms['child_data']) > 0){ ?>
      <div class="container_no">
        <div class="slidecourses owl-carousel">
          <?php foreach ($cms['child_data'] as $child) { ?>
            <div class="listbox">
              <div class="border_box">
                <?php if(!empty($child['thumbSrc'])){ ?>
                <div class="courseimg">
                  <img src="{{$child['thumbSrc']}}" alt="{{ $child['title']??''}}">
                </div>
                <?php } ?>

                <?php if(!empty($child['heading']) || !empty($child['brief'])){ ?>
                <div class="titles">
                  <?php if(!empty($child['heading'])){ ?>
                  <h3>{{$child['heading']??''}}</h3>
                  <?php } ?>

                  <?php if(!empty($child['brief'])){ ?>
                  <p>{!!$child['brief']??''!!}</p>
                  <?php } ?>
                </div>
                <?php } ?>

                <?php if(!empty($child['content'])){ ?>
                <div>{!!$child['content']??''!!}</div>
                <?php } ?>

                <?php if(isset($child['images']) && !empty($child['images'])){ ?>
                <div class="flex flex-wrap gall_cms">
                  <?php foreach($child['images'] as $image){ ?>
                  <a href="{{$image['imageSrc']}}" data-fancybox="destination-gallery"><img src="{{$image['thumbSrc']}}" alt="{{$image['title']}}"></a>
                  <?php } ?>
                </div>
                <?php } ?>

              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

    <?php if(isset($cms['images']) && !empty($cms['images'])){ ?>
    <div>
      <div class="flex flex-wrap gall_cms">
        <?php foreach($cms['images'] as $image){ ?>
        <a href="{{$image['imageSrc']}}" data-fancybox="destination-gallery"><img src="{{$image['thumbSrc']}}" alt="{{$image['title']}}"></a>
        <?php } ?>
      </div>
    </div>
    <?php } ?>
  </div>
</section>