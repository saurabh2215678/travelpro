
@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $meta_title ?? ''}}
@endslot
@slot('headerBlock')
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
</style>
@endslot
<?php 
$storage = Storage::disk('public');
   $package_path = 'packages/';
      
?>
    <section>
        <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title"> My Favourite </h1>
                        </div>
                        <!-- <p class="para-lg3">Basic info, for a faster booking experience</p> -->
                    </div>
                    <!-- <div class="right">
                        <a class="btn2 edit_pofile_btn" href="#"><span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M421.7 220.3l-11.3 11.3-22.6 22.6-205 205c-6.6 6.6-14.8 11.5-23.8 14.1L30.8 511c-8.4 2.5-17.5 .2-23.7-6.1S-1.5 489.7 1 481.2L38.7 353.1c2.6-9 7.5-17.2 14.1-23.8l205-205 22.6-22.6 11.3-11.3 33.9 33.9 62.1 62.1 33.9 33.9zM96 353.9l-9.3 9.3c-.9 .9-1.6 2.1-2 3.4l-25.3 86 86-25.3c1.3-.4 2.5-1.1 3.4-2l9.3-9.3H112c-8.8 0-16-7.2-16-16V353.9zM453.3 19.3l39.4 39.4c25 25 25 65.5 0 90.5l-14.5 14.5-22.6 22.6-11.3 11.3-33.9-33.9-62.1-62.1L314.3 67.7l11.3-11.3 22.6-22.6 14.5-14.5c25-25 65.5-25 90.5 0z"/></svg> Edit </span></a>
                    </div> -->
                 
                </div>
                <?php if(!($popularPackageWishlists->isEmpty())){ ?>
                <ul class="featured_list my_favorite mt-3">
                  <?php
                     foreach($popularPackageWishlists as $wishlistData){
                     $package_id = $wishlistData->id;
                     $package_title = CustomHelper::wordsLimit($wishlistData->title);
                     $package_brief = CustomHelper::wordsLimit($wishlistData->brief);
                     $package_slug = $wishlistData->slug;
                     $package_duration = $wishlistData->package_duration;
                     $package_image = $wishlistData->image;

                     $packageThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

                     if(!empty($package_image)){
                     if($storage->exists($package_path.$package_image)){
                        $packageThumbSrc = asset('/storage/'.$package_path.'thumb/'.$package_image);
                     }
                     }
                     $packageDetailName = ($wishlistData->is_activity==1)?'activityDetail':'packageDetail';
                     ?>
                    <li class="aos-init aos-animate w-1/2">
                       <div class="featured_box" >
                          <div class="images">
                             <img src="{{$packageThumbSrc}}" alt="{{$package_title}}">
                          </div>
                          <a class="featured_content" href="{{route($packageDetailName,['slug'=>$package_slug])}}">
                         
                             <div class="title heading3">{{ $package_title }} </div>
                             <div class="duration"><strong>{{$package_duration}}</strong></div>
                            <div class="package_brief">{!!$package_brief!!}</div>
                             <div class="view_all">View Detail</div>
                             </a>
                          </div>
                    </li>
                 <?php }?>
                 </ul>
                 <div class="pagination-wrapper">
                 {{ $popularPackageWishlists->appends(request()->input())->links('vendor.pagination.bootstrap-4'); }}
                 </div>
              <?php }else{ ?>
            <div class="alert_not_found">No Favourite data found.</div>
            <?php } ?>
      </div>
        </div>
        </div>
    </section>

@slot('bottomBlock')

@endslot

@endcomponent


