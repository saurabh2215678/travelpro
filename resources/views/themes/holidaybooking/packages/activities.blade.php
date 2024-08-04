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
   <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" type="text/css" rel="stylesheet" />
   @endslot

   @slot('bodyClass')
     testimonials_class
   @endslot

<?php
   $storage = Storage::disk('public');
   $package_path = 'packages/';
   $itenaries_path = 'itenaries/';
   $current_url = url()->current();
   $sdest = (request()->has('sdest')) ? request()->sdest : '';
   $sno_of_days = (request()->has('sno_of_days')) ? request()->sno_of_days : '';
   $smonth = (request()->has('smonth')) ? request()->smonth : '';

   $destinations_arr =  (!empty(\Request::get('destinations'))) ? \Request::get('destinations') : [];
   $sub_destination_request =  (!empty(\Request::get('sub_destination'))) ? \Request::get('sub_destination') : '';
   $categories_arr =  (!empty(\Request::get('categories'))) ? \Request::get('categories') : [];
   $filter_package_budget =  (!empty(\Request::get('filter_package_budget'))) ? \Request::get('filter_package_budget') : [];
   $filter_month =  (!empty(\Request::get('filter_month'))) ? \Request::get('filter_month') : [];
   $filter_tour_type =  (!empty(\Request::get('filter_tour_type'))) ? \Request::get('filter_tour_type') : [];

   $month_request =  (!empty(\Request::get('month'))) ? \Request::get('month') : '';
   $duration_request = (!empty(\Request::get('duration'))) ? \Request::get('duration') : [];
   $activity_level_request = (!empty(\Request::get('activity_level'))) ? \Request::get('activity_level') : [];
   $service_level_request = (!empty(\Request::get('service_level'))) ? \Request::get('service_level') : '';
   $min_price_request = (!empty(\Request::get('min_price'))) ? \Request::get('min_price') : '';
   $max_price_request = (!empty(\Request::get('max_price'))) ? \Request::get('max_price') : '';
   $departure_month_request = (!empty(\Request::get('departure_month'))) ? \Request::get('departure_month') : '';

   $sort_by_price = (!empty(\Request::get('sort_by_price'))) ? \Request::get('sort_by_price') : '';
   $requestAll = \Request::all();
   //prd($requestArr);
   $requestQuery = "";
   unset($requestAll['sort_by_price']);
   if(!empty($requestAll)){
      $requestQuery = http_build_query($requestAll);
   }

$storage = Storage::disk('public');
$path = 'banners/';
$b_image =asset(config('custom.assets').'/images/noimage.jpg');
// dd($banners);
/*foreach($banners as $banner){
  $images = (isset($banner->Images))?$banner->Images:'';
  // prd($images->toArray());
  if(!empty($images) && count($images) > 0){
    foreach($images as $image){
      if(!empty($image->image_name) && $storage->exists($path.$image->image_name)){

        $b_image = url('/storage/banners/'.$image->image_name);
      }
    }
  }
}*/
if($banner_image) {
   $b_image = $banner_image;
}
?>
<section class="inner_banner">
<div class="inner_banner_main">
   <img src="{{$b_image}}" alt="{{$page_title}}" />
</div>
</section>

<section class="search_home py-0 md:px-0">
   <div>
      @include(config('custom.theme').'.includes.search')
   </div>
</section>


<?php /*
<form action="{{ route('packageListing') }}" method="GET" name="package_search">
<div class="enquiry_search">
   <div class="container">
   <div class="title para_lg2">
         Start your Trip here...
         </div>      
      <ul class="search_list">
        
            <li>
               <div class="form_group">
                  <div class="custom_select">
                  <select class="form_control" name="destination" id="destination">
                     <option value="">Destination</option>
                     <?php if(!($destinations->isEmpty())){
                        $parent_destinations = $destinations->where('parent_id', 0);
                        if(!($parent_destinations->isEmpty())){
                     ?>
                        @foreach($parent_destinations as $destination_it)
                        <option value="{{$destination_it->id}}" <?php echo ($destination_it->id == 21) ? "selected":""; ?>>{{ $destination_it->name }}</option>
                        @endforeach
                     <?php }}?>
                  </select>
               </div>
            </div>
            </li>
            <li>
               <div class="form_group">
                  <div class="custom_select">
                     <select class="form_control" name="sub_destination" id="sub_destination">
                        <option value="">All Locations</option>
                     </select>
                  </div>
               </div>
            </li>
            <li>
               <div class="form_group">
                  <div class="custom_select">
                  <select class="form_control" name="categories">
                     <option value="">Theme/Categories</option>
                     <?php if(!($themes->isEmpty())){?>
                        @foreach($themes as $them_cat)
                        <option value="{{$them_cat->id}}" <?php echo ($them_cat->id == $categories_request) ? "selected":""; ?> >{{ $them_cat->title}}</option>
                        @endforeach
                     <?php } ?>
                  </select>
               </div>
            </div>
            </li>
            <li>
               <div class="form_group">
                  <div class="custom_select">
                  <select class="form_control" name="month">
                     <option value="">Months</option>
                      <?php
                        $monthsArr = config('custom.months_arr');
                        if(!empty($monthsArr)){ ?>
                            <?php
                            foreach ($monthsArr as $month_id=>$month){
                            ?>
                            <option value="{{$month_id}}" <?php echo ($month_id == $month_request) ? "selected":""; ?> >{{$month}}</option>
                        <?php }}?> 
                  </select>
               </div>
               </div>
            </li>
            <li>
               <button type="submit" class="btn">Search</button>
            </li>
         <!-- <li><a href="packaging.php" class="advanced_btn">Advanced search</a> </li> -->
      </ul>
   </div>
</div>
</form>
*/ ?>

<section class="packaging_wrap p_list_view ">
   <div class="container">
      <div class="packaging_wrap_inner">
            <div class="side_bar">
      <form action="{{ route($route_name) }}" method="GET" id="adv_package_search" name="adv_package_search">
      
            <div class="cross">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"/></svg>
            </div>
            <div class="sidebar_title para_lg2">Filter</div>            
            <div class="filter_box">
               <div class="title para_md">Categories </div>
                  @if(!$themes->isEmpty())
                  @foreach($themes as $them_cat)
                  <div class="checkbox_list">
                     <input type="checkbox" id="categories{{$them_cat->id}}" name="categories[]" value="{{$them_cat->id}}" <?php echo in_array($them_cat->id, $categories_arr) ? "checked":""; ?> >
                     <label for="categories{{$them_cat->id}}">{{$them_cat->title}}</label><br>
                  </div>
                  @endforeach
                  @endif
            </div>

            <div class="filter_box">
               <div class="title para_md">Location Wise </div>
                  @if(!$destinations->isEmpty())
                  @foreach($destinations as $destination)
                  <div class="checkbox_list">
                     <input type="checkbox" id="destination{{$destination->id}}" name="destinations[]" value="{{$destination->id}}" <?php echo in_array($destination->id, $destinations_arr) ? "checked":""; ?> >
                     <label for="destination{{$destination->id}}">{{$destination->name}}</label>
                  </div>
                  @endforeach
                  @endif
            </div>

            <div class="filter_box">
               <div class="title para_md">Budget per person</div>
                  <?php $options_package_budget = Customhelper::budgetDataArr(); ?>
                  @if(!empty($options_package_budget))
                  @foreach($options_package_budget as $fpb_k => $fpb_v)
                  <div class="checkbox_list">
                     <input type="checkbox" id="filter_package_budget{{$fpb_k}}" name="filter_package_budget[]" value="{{$fpb_k}}" <?php echo in_array($fpb_k, $filter_package_budget) ? "checked":""; ?> >
                     <label for="filter_package_budget{{$fpb_k}}">{{$fpb_v}}</label>
                  </div>
                  @endforeach
                  @endif
            </div>

            <div class="filter_box">
               <div class="title para_md">Month Wise</div>
               <?php 
               for ($i=0; $i < 12; $i++) {
                  $month = date('Y-m',strtotime('+'.$i.'month'));
                  ?>
                  <div class="checkbox_list">
                     <input type="checkbox" id="filter_month{{$month}}" name="filter_month[]" value="{{$month}}" <?php echo in_array($month, $filter_month) ? "checked":""; ?> >
                     <label for="filter_month{{$month}}">{{CustomHelper::DateFormat($month,'M Y')}}</label>
                  </div>
               <?php } ?>
               <!-- <div class="checkbox_list">
                  <input type="checkbox" id="filter_monthnot_decision" name="filter_month[]" value="not_decision" <?php echo in_array('not_decision', $filter_month) ? "checked":""; ?> >
                  <label for="filter_monthnot_decision">Not decided</label>
               </div> -->
            </div>            

            <div class="filter_box">
               <div class="title para_md">Package Type </div>
               <div class="checkbox_list">
                  <input type="checkbox" id="filter_tour_type_group" value="group" <?php echo in_array('group', $filter_tour_type) ? "checked":""; ?> name="filter_tour_type[]">
                  <label for="filter_tour_type_group">Group Tour</label><br>
               </div>

               {{--<div class="checkbox_list">
                  <input type="checkbox" id="filter_tour_type_general" value="general" <?php echo in_array('general', $filter_tour_type) ? "checked":""; ?> name="filter_tour_type[]">
                  <label for="filter_tour_type_general">Customised Tour</label><br>
               </div>--}}
               
               <div class="checkbox_list">
                  <input type="checkbox" id="filter_tour_type_fixed" value="fixed" <?php echo in_array('fixed', $filter_tour_type) ? "checked":""; ?> name="filter_tour_type[]">
                  <label for="filter_tour_type_fixed">Fixed Tour</label><br>
               </div>

               <div class="checkbox_list">
                  <input type="checkbox" id="filter_tour_type_open" value="open" <?php echo in_array('open', $filter_tour_type) ? "checked":""; ?> name="filter_tour_type[]">
                  <label for="filter_tour_type_open">Open Tour</label><br>
               </div>
            </div>

            <?php /*
            <div class="filter_box">
               <div class="title para_md">Duration </div>
               <div class="checkbox_list">
                  <input type="checkbox" id="duration1" value="0-7" <?php echo in_array('0-7', $duration_request) ? "checked":""; ?> name="duration[]">
                  <label for="duration1"> 0 to 7 Days</label><br>
               </div>
               <div class="checkbox_list">
                  <input type="checkbox" id="duration2" value="8-14" <?php echo in_array('8-14', $duration_request) ? "checked":""; ?> name="duration[]">
                  <label for="duration2"> 8 to 14 Days</label><br>
               </div>
               <div class="checkbox_list">
                  <input type="checkbox" id="duration3" value="15-21" <?php echo in_array('15-21', $duration_request) ? "checked":""; ?> name="duration[]">
                  <label for="duration3"> 15 to 21 Days</label><br>
               </div>
            </div>

            <div class="filter_box">
               <div class="title para_md">Activity level </div>
               <div class="checkbox_list">
                  <input type="checkbox" id="light" value="1" <?php echo in_array(1, $activity_level_request) ? "checked":""; ?> name="activity_level[]">
                  <label for="light"> Light</label><br>
               </div>
               <div class="checkbox_list">
                  <input type="checkbox" id="Moderate" value="2" <?php echo in_array(2, $activity_level_request) ? "checked":""; ?> name="activity_level[]">
                  <label for="Moderate"> Moderate</label><br>
               </div>
               <div class="checkbox_list">
                  <input type="checkbox" id="Strenuous" value="3" <?php echo in_array(3, $activity_level_request) ? "checked":""; ?> name="activity_level[]">
                  <label for="Strenuous"> Strenuous</label><br>
               </div>
               <div class="checkbox_list">
                  <input type="checkbox" id="Extreme" value="4" <?php echo in_array(4, $activity_level_request) ? "checked":""; ?> name="activity_level[]">
                  <label for="Extreme"> Extreme/ Difficult</label><br>
               </div>
            </div>

            <div class="filter_box">
               <div class="title para_md">Departure Month</div>
                  <div class="claender_box current_year">
                     <div class="title">{{date('Y')}}</div>
                     <ul class="month_name">
                        <li><input type="radio" name="departure_month" value="{{ '1-'.date('Y') }}" <?php echo ($departure_month_request == '1-'.date('Y')) ? "checked":"";?> id="Jan{{date('Y')}}">
                        <label for="Jan{{date('Y')}}">Jan</label></li>
                        <li> <input type="radio" name="departure_month" value="{{ '2-'.date('Y') }}"  <?php echo ($departure_month_request == '2-'.date('Y')) ? "checked":"";?> id="Feb{{date('Y')}}">
                        <label for="Feb{{date('Y')}}">Feb</label></li>
                        <li> <input type="radio" name="departure_month" value="{{ '3-'.date('Y') }}" <?php echo ($departure_month_request == '3-'.date('Y')) ? "checked":"";?> id="Mar{{date('Y')}}">
                        <label for="Mar{{date('Y')}}">Mar</label></li>
                        <li> <input type="radio" name="departure_month" value="{{ '4-'.date('Y') }}" <?php echo ($departure_month_request == '4-'.date('Y')) ? "checked":"";?> id="Apr{{date('Y')}}">
                        <label for="Apr{{date('Y')}}">Apr</label></li>
                        <li> <input type="radio" name="departure_month" value="{{ '5-'.date('Y') }}" <?php echo ($departure_month_request == '5-'.date('Y')) ? "checked":"";?> id="May{{date('Y')}}">
                        <label for="May{{date('Y')}}">May</label></li>
                        <li> <input type="radio" name="departure_month" value="{{ '6-'.date('Y') }}" <?php echo ($departure_month_request == '6-'.date('Y')) ? "checked":"";?> id="Jun{{date('Y')}}">
                        <label for="Jun{{date('Y')}}">Jun</label></li>
                        <li> <input type="radio" name="departure_month" value="{{ '7-'.date('Y') }}" <?php echo ($departure_month_request == '7-'.date('Y')) ? "checked":"";?> id="Jul{{date('Y')}}">
                        <label for="Jul{{date('Y')}}">Jul</label></li>
                        <li> <input type="radio" name="departure_month" value="{{ '8-'.date('Y') }}" <?php echo ($departure_month_request == '8-'.date('Y')) ? "checked":"";?> id="Aug{{date('Y')}}">
                        <label for="Aug{{date('Y')}}">Aug</label></li>
                        <li> <input type="radio" name="departure_month" value="{{ '9-'.date('Y') }}" <?php echo ($departure_month_request == '9-'.date('Y')) ? "checked":"";?> id="Sep{{date('Y')}}">
                        <label for="Sep{{date('Y')}}">Sep</label></li>
                        <li> <input type="radio" name="departure_month" value="{{ '10-'.date('Y') }}" <?php echo ($departure_month_request == '10-'.date('Y')) ? "checked":"";?> id="Oct{{date('Y')}}">
                        <label for="Oct{{date('Y')}}">Oct</label></li>
                        <li> <input type="radio" name="departure_month" value="{{ '11-'.date('Y') }}" <?php echo ($departure_month_request == '11-'.date('Y')) ? "checked":"";?> id="Nov{{date('Y')}}">
                        <label for="Nov{{date('Y')}}">Nov</label></li>
                        <li> <input type="radio" name="departure_month" value="{{ '12-'.date('Y') }}" <?php echo ($departure_month_request == '12-'.date('Y')) ? "checked":"";?> id="Dec{{date('Y')}}">
                        <label for="Dec{{date('Y')}}">Dec</label></li>
                     </ul>
                  </div>

                  <div class="claender_box next_year">
                     <div class="title">{{date('Y',strtotime('+1 year'))}}</div>
                     <ul class="month_name">
                        <li>
                           <input type="radio" name="departure_month" value="{{ '1-'.date('Y',strtotime('+1 year')) }}" <?php echo ($departure_month_request == '1-'.date('Y',strtotime('+1 year'))) ? "checked":"";?> id="Jan{{date('Y',strtotime('+1 year'))}}">
                           <label for="Jan{{date('Y',strtotime('+1 year'))}}">Jan</label>
                        </li>
                        <li>
                           <input type="radio" name="departure_month" value="{{ '2-'.date('Y',strtotime('+1 year')) }}" <?php echo ($departure_month_request == '2-'.date('Y',strtotime('+1 year'))) ? "checked":"";?> id="Feb{{date('Y',strtotime('+1 year'))}}">
                           <label for="Feb{{date('Y',strtotime('+1 year'))}}">Feb</label>
                        </li>
                        <li>
                           <input type="radio" name="departure_month" value="{{ '3-'.date('Y',strtotime('+1 year')) }}" <?php echo ($departure_month_request == '3-'.date('Y',strtotime('+1 year'))) ? "checked":"";?> id="Mar{{date('Y',strtotime('+1 year'))}}">
                           <label for="Mar{{date('Y',strtotime('+1 year'))}}">Mar</label>
                        </li>
                        <li>
                           <input type="radio" name="departure_month" value="{{ '4-'.date('Y',strtotime('+1 year')) }}" <?php echo ($departure_month_request == '4-'.date('Y',strtotime('+1 year'))) ? "checked":"";?> id="Apr{{date('Y',strtotime('+1 year'))}}">
                           <label for="Apr{{date('Y',strtotime('+1 year'))}}">Apr</label>
                        </li>
                        <li>
                           <input type="radio" name="departure_month" value="{{ '5-'.date('Y',strtotime('+1 year')) }}" <?php echo ($departure_month_request == '6-'.date('Y',strtotime('+1 year'))) ? "checked":"";?> id="May{{date('Y',strtotime('+1 year'))}}">
                           <label for="May{{date('Y',strtotime('+1 year'))}}">May</label>
                        </li>
                        <li>
                           <input type="radio" name="departure_month" value="{{ '6-'.date('Y',strtotime('+1 year')) }}" <?php echo ($departure_month_request == '6-'.date('Y',strtotime('+1 year'))) ? "checked":"";?> id="Jun{{date('Y',strtotime('+1 year'))}}">
                           <label for="Jun{{date('Y',strtotime('+1 year'))}}">Jun</label>
                        </li>
                        <li>
                           <input type="radio" name="departure_month" value="{{ '7-'.date('Y',strtotime('+1 year')) }}" <?php echo ($departure_month_request == '7-'.date('Y',strtotime('+1 year'))) ? "checked":"";?> id="Jul{{date('Y',strtotime('+1 year'))}}">
                           <label for="Jul{{date('Y',strtotime('+1 year'))}}">Jul</label>
                        </li>
                        <li>
                           <input type="radio" name="departure_month" value="{{ '8-'.date('Y',strtotime('+1 year')) }}" <?php echo ($departure_month_request == '8-'.date('Y',strtotime('+1 year'))) ? "checked":"";?> id="Aug{{date('Y',strtotime('+1 year'))}}">
                           <label for="Aug{{date('Y',strtotime('+1 year'))}}">Aug</label>
                        </li>
                        <li>
                           <input type="radio" name="departure_month" value="{{ '9-'.date('Y',strtotime('+1 year')) }}" <?php echo ($departure_month_request == '9-'.date('Y',strtotime('+1 year'))) ? "checked":"";?> id="Sep{{date('Y',strtotime('+1 year'))}}">
                           <label for="Sep{{date('Y',strtotime('+1 year'))}}">Sep</label>
                        </li>
                        <li>
                           <input type="radio" name="departure_month" value="{{ '10-'.date('Y',strtotime('+1 year')) }}" <?php echo ($departure_month_request == '10-'.date('Y',strtotime('+1 year'))) ? "checked":"";?> id="Oct{{date('Y',strtotime('+1 year'))}}">
                           <label for="Oct{{date('Y',strtotime('+1 year'))}}">Oct</label>
                        </li>
                        <li>
                           <input type="radio" name="departure_month" value="{{ '11-'.date('Y',strtotime('+1 year')) }}" <?php echo ($departure_month_request == '11-'.date('Y',strtotime('+1 year'))) ? "checked":"";?> id="Nov{{date('Y',strtotime('+1 year'))}}">
                           <label for="Nov{{date('Y',strtotime('+1 year'))}}">Nov</label>
                        </li>
                        <li>
                           <input type="radio" name="departure_month" value="{{ '12-'.date('Y',strtotime('+1 year')) }}" <?php echo ($departure_month_request == '12-'.date('Y',strtotime('+1 year'))) ? "checked":"";?> id="Dec{{date('Y',strtotime('+1 year'))}}">
                           <label for="Dec{{date('Y',strtotime('+1 year'))}}">Dec</label>
                        </li>
                     </ul>
                  </div>
            </div>
            <div class="filter_box price_range">
               <div class="title para_md">Price </div>
             
               <div class="price-range-slider">
                  <div class="price_value">
                     <span class="currency_type">$</span><input type="number" name="min_price" class="price1 form_control" />
                     <span class="currency_type">-$</span><input type="number" name="max_price" class="price2 form_control" />
                     <!-- <span class="price1"></span>
                     <span class="price2"></span> -->
                  </div>
                  <!-- <p class="range-value">
                    <input type="text" id="amount" readonly>
                  </p> -->
                  <div id="slider-range" class="range-bar"></div>
                  
                </div>
            </div>

            <div class="filter_box">
               
               <div class="flex_box">
               <div class="title para_md">Service Level </div>
               <div class="clear_services">Clear</div>
                  </div>
               <div class="checkbox_list">
                  <input type="radio" id="standard" value="1" <?php echo ($service_level_request==1) ? "checked":""; ?> name="service_level">
                  <label for="standard"> Standard</label><br>
               </div>
               <div class="checkbox_list">
                  <input type="radio" id="deluxe" value="2" <?php echo ($service_level_request==2) ? "checked":""; ?> name="service_level">
                  <label for="deluxe"> Deluxe</label><br>
               </div>
               <div class="checkbox_list">
                  <input id="premium" type="radio" value="3" <?php echo ($service_level_request==3) ? "checked":""; ?> name="service_level">
                  <label for="premium"> Premium</label><br>
               </div>
               <div class="checkbox_list">
                  <input type="radio" id="superior" value="4" <?php echo ($service_level_request==4) ? "checked":""; ?> name="service_level">
                  <label for="superior"> Superior</label><br>
               </div>
              
            </div>
            */ ?>

            <ul class="filter_button">
               <li><button  type="submit" class="btn">Apply</button></li>
               <li><a href="{{ route('packageListing') }}" class="btn2">Clear</a></li>
            </ul>

            <input type="hidden" name="sdest" value="{{$sdest}}">
            <input type="hidden" name="sno_of_days" value="{{$sno_of_days}}">
            <input type="hidden" name="smonth" value="{{$smonth}}">         
      </form> 
      </div>        
         <div class="packaging_view">
                  @if(isset($page_title) && !empty($page_title))
                  <h1 class="title text-2xl mb-3">{{$page_title}}</h1>
                  @endif
                  @if(isset($page_brief) && !empty($page_brief))
                  <div class="package_list_brief">{!!$page_brief!!}</div>
                  <a href="javascript:void(0);" class="read_more_btn_package read_full">read more</a>
                  @endif
            <div class="packaging_top">
               <div class="title font-bold">Showing {{ $packages->total() }} {{$is_activity==1?'Activities':'Packages'}} For You</div>
               <div class="sort_by">
                  <div class="custom_select">
                  <select class="form_control" name="sort_by_price" id="sort_by_price">
                     <option value="">Sort By Price :</option>
                     <option value="lth" {{($sort_by_price=='lth')?'selected':''}}>Low To High</option>
                     <option value="htl" {{($sort_by_price=='htl')?'selected':''}}>High To Low</option>
                  </select>
               </div>
            </div>
            </div>
                  <?php if(!$packages->isEmpty()){
                     foreach ($packages as $package) {
                        /*$package_inclusions = (!empty($package->package_inclusions)) ? json_decode($package->package_inclusions,true) : [];

                        $pkgInclusionObj = "";
                        if(!empty($package_inclusions)){
                          $pkgInclusionObj = App\Models\PackageInclusion::whereIn('id',$package_inclusions)->orderBy('sort_order','asc')->get();
                        }
                        $pkgInclusions = [];
                        if(!empty($pkgInclusionObj)){  
                           foreach($pkgInclusionObj as $pkginc){  
                              $pkgInclusions[] = strtolower($pkginc->title);
                           }
                        }*/
                        $package_id = $package->id;
                        $package_title = CustomHelper::wordsLimit($package->title);
                        $package_brief = CustomHelper::wordsLimit($package->brief);
                        // $original_price = CustomHelper::getPrice($package->sub_total_amount);
                        // $package_price = CustomHelper::getPrice($package->final_amount);
                        $package_slug = $package->slug;
                        $package_price_title = !empty($package->packageDefaultPrice)?$package->packageDefaultPrice->title:'';
                        // $package_service_level = $package->service_level;
                        $package_duration = $package->package_duration;
                        $package_image = $package->image;
                        $package_path = 'packages/';

                        $priceCategoryDetails = $package->packagePriceCategory;

                        $packageThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

                        if(!empty($package_image)){
                           if($storage->exists($package_path.$package_image)){
                              $packageThumbSrc = asset('/storage/'.$package_path.'thumb/'.$package_image);
                           }
                        }

                        $packageCategories = $package->packageCategories??[];


                        $isDefaultPackagePrice = $package->packagePrices->first()??[];
                        $final_amount = (int)$package->search_price??0;
                        $sub_total_amount = $final_amount;
                        /*if($isDefaultPackagePrice) {
                           $sub_total_amount = $isDefaultPackagePrice->sub_total_amount??0;
                           $final_amount = $isDefaultPackagePrice->final_amount??0;
                        }*/

                        $packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';

                        $package_inclusions = $package->package_inclusions??[];
                        if($package_inclusions) {
                           $package_inclusions = json_decode($package_inclusions);
                           // $package_inclusions = CustomHelper::showPackageInclusions($package_inclusions);
                           $package_inclusions = CustomHelper::showPackageInclusionsOther($package_inclusions);
                        }
                  ?>
                  <div class="packaging_view_box">
                     <div class="packaging_view_box_top">
                  @if(auth()->check())
                  <div class="wishlist_btn">
                  <span id="favid-{{ $package_id }}" class="pkg_fav pkg_fav_{{ $package_id }} {{ (isset($package_fab[$package_id])) ? 'pkg_fav_clicked liked_btn' : 'pkg_fav' }} " >
                  <img class="wishlist" src="{{asset(config('custom.assets').'/images/wishlist.png') }}" alt="">
                  <img class="wishlist_active" src="{{asset(config('custom.assets').'/images/wishlist-active.png') }}" alt="">
                  </span>
                  </div>
                  @endif
                        
                  <div class="images">
                  <a href="{{ route($packageDetailName,['slug'=>$package_slug]) }}"><img src="{{ $packageThumbSrc }}" class="theme_radius img_responsive" alt="{{ $package_title }}"></a>
                        </div>
                        <div class="packaging_info">
                           <div class="package_top">
                           @if(!empty($package))
                              <div class="duration mb-3 pr-5"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $package_duration }}</div>
                           @endif
                           <div class="title para_lg2"><a href="{{ route($packageDetailName,['slug'=>$package_slug]) }}">{{ $package_title }}</a></div>

                           <div class="flex flex-wrap flex-start">
                              @if($package->is_activity==1)
                              <div class="location mb-3"><i class="fa fa-map-marker" aria-hidden="true"></i> {{$package->packageDestination->name??''}}</div>
                              @endif
                           </div>

                           <!-- <div class="pac_disc py-2">
                              @if(!empty($packageCategories))
                                 <div class="package_disc flex items-center mt-5 mb-5">
                                    <div class="list_row_right">
                                       <ul class="activ_list">
                                          @foreach($packageCategories as $pc)
                                          <li >{!!$pc->title??""!!}</li>
                                          @endforeach
                                       </ul>
                                    </div>
                                 </div>
                              @endif
                           </div> -->

                           
                                                
                        </div>
                        @if($package->is_activity==0)
                        <div class="cities ">
					             <strong>Places : </strong>
                           @if(!empty($package->days_nights_city))
                           <span>
                           <?php 
                           $days_nights_city = unserialize($package->days_nights_city);
                           if(!empty($days_nights_city)) {
                              $ii = 0;
                              foreach($days_nights_city as $dnc_key => $dnc_value) {
                                 $ii++;
                                 echo $dnc_key.' ('.$dnc_value.'D)';
                                 if($ii < count($days_nights_city) ){ echo ' <i class="fa fa-long-arrow-right"></i> '; }
                              }
                           }
                           ?>
                           </span>
                           @endif
                        </div>                        

                           <?php /*<!-- <p class="para_md1">{{ $package_brief }}</p> -->
                           <!-- <div class="package_type">
                           <ul class="package_type_list">
                           <li> <input type="radio" id="Standar" name="package" value="Standar">
                           <label for="Standar">Standar</label></li>

                           <li><input type="radio" id="Deluxe" name="package" value="Deluxe">
                           <label for="Deluxe">Deluxe</label></li>
                           <li><input type="radio" id="Premium" name="package" value="Premium">
                           <label for="Premium">Premium</label></li>
                           <li><input type="radio" id="Superior" name="package" value="Superior">
                           <label for="Superior">Superior</label></li>
                           </ul>
                           
                           </div> -->*/ ?>

                           <?php
                           /*$isDefaultPackagePrice = $package->packagePrices->first()??[];
                           $package_price_id = '';
                           if($isDefaultPackagePrice) {
                              $package_price_id = $isDefaultPackagePrice->id;
                           }*/
                           $packagePrices = $package->packagePrices;
                           if($packagePrices && count($packagePrices) > 0){ ?>
                              <div class="package_type mt-3">  
                                    <?php foreach($packagePrices as $key => $price){ ?>
                                       <label class="customradio ">
                                       
                                          <input type="radio" name="package_type_{{$package->id}}" value="{{$price->id}}"  data-package_id="{{$package_id}}" {{($price->is_default==1 || $key == 0)?'checked':''}} >
                                          <i></i>
                                          <span>{{$price->title}}</span>
                                          
                                    </label>
                                    <?php } ?>                                 
                              </div>
                           <?php } ?>
                           @endif
                        </div>
                        

                        <div class="price">
                           @if(!empty($final_amount))
                           <div class="price_inner mb-2">
                              <!-- <p class="price_package" style="color:#000;font-size: 1.125rem;font-weight: 600;">Starting From : </p> -->
                              <p class="amount heading_sm3"><?php echo ($sub_total_amount == $final_amount) ? "" : "<small class='old_price'>".CustomHelper::getPrice($sub_total_amount)."</small>"; ?>{{CustomHelper::getPrice($final_amount)}}</p>
                              <div class="peerson"><?php //<p> {{$priceCategoryDetails->display_title??''}} </p> ?></div>
                           </div>
                           @endif

                           <?php /*<div class="price_inner mb-2">
                              <h1 style="color: red;"><a href="{{route('request-itinerary',['package'=>$package_slug,'by'=>'enquire'])}}" class="btn text-sm">Enquire For Price</a><h1>
                              </div>*/ ?>


                        <div class="right_side text-center">
                           <a href="{{route('enquire-this-trip',['package'=>$package_slug,'type'=> $package_price_title ??'','by'=>'enquire'])}}" class="btn theme_clr enquir_now text-sm rounded-full">Enquire Now</a>
                           <a href="{{ route($packageDetailName,['slug'=>$package_slug]) }}" target="_blank" class="mt-3 rounded-full more_btn">More Info <i class="fa-solid fa-chevron-right"></i></a> <!-- 'service_level'=>$package_service_level -->
                        </div>
                        </div>

                        
                        
                       
                        <div class="clearfix"></div>
                       
                        


                        
                        <?php /*<!-- <div class="price">
                           @if($package->is_default == NULL)
                           <div class="price_inner">
                              <h1 style="color: red;"><a href="{{route('request-itinerary',['package'=>$package_slug,'by'=>'enquire'])}}">Enquire For<br> Price</a><h1>
                           </div>
                           @else
                           <div class="price_inner">
                              Starting Price <span class="amount heading_sm3"><?php echo ($package->sub_total_amount == $package->final_amount) ? "" : "<small class='old_price'>".$original_price."</small>"; ?>{{ $package_price }}</span>
                              <small>{{ $priceCategoryDetails->name }}</small>
                           </div>
                           @endif
                        </div> -->*/ ?>
                     </div>

                     <?php if(!empty($package_inclusions)){ 
                           $i_path = 'inclusion/';?>
                     <div class="packaging_view_footer">
                        <div class="packaging_view_footer_inner">
                           <div class="left_side">
                              <div class="inclusions_box">
                                 <ul class="inclusions">
                                    <?php foreach($package_inclusions as $inclusion_key => $inclusion_val){ ?>
                                       <!-- <li data-text="<?php //echo $inclusion_key;?>"><i class="fa"></i><?php //echo ucwords($inclusion_val);?></li> -->

                                       <li data-text="<?php echo $inclusion_val;?>">
                                          <?php
                                            $i_image = asset(config('custom.assets').'/images/ico3.png');
                                            if(!empty($inclusion_key) && File::exists(public_path('storage/inclusion/'.$inclusion_key))){
                                            $i_image = url('storage/'.$i_path.'thumb/'.$inclusion_key);
                                          } ?>
                                          <img src="{{$i_image}}"/>
                                          <?php echo ucwords($inclusion_val);?>
                                       </li>
                                    <?php } ?>
                                 </ul>
                              </div>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
                  <?php }?>
                  <div class="pagination-wrapper">
                  <?php echo $packages->appends(request()->input())->links('vendor.pagination.bootstrap-4'); // frontpaginate?>
               </div>
            <?php }else{ ?>
                     <div class="alert_not_found">No {{$is_activity==1?'Activities':'Packages'}} Found.</div>
                  <?php } ?>




                  
                  @if(isset($page_description) && !empty($page_description))
                  <p class="text-sm">{!!$page_description!!}</p>
                  @endif



         </div>
      </div>

   </div>
</section>

<div class="filter_icon">
   <svg data-aos="zoom-in"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M3.853 54.87C10.47 40.9 24.54 32 40 32H472C487.5 32 501.5 40.9 508.1 54.87C514.8 68.84 512.7 85.37 502.1 97.33L320 320.9V448C320 460.1 313.2 471.2 302.3 476.6C291.5 482 278.5 480.9 268.8 473.6L204.8 425.6C196.7 419.6 192 410.1 192 400V320.9L9.042 97.33C-.745 85.37-2.765 68.84 3.854 54.87L3.853 54.87z"/></svg>
</div>

@slot('bottomBlock')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

<script>
      $('.read_full').click(function(e){
         $(this).text() == 'read more' ? $(this).text('read less') : $(this).text('read more');
         $('.package_list_brief').toggleClass('active');
      });
      // $(".read_less").text('read less');
      

       $('.pkg_fav').click(function(e){
         e.preventDefault();
      });
   <?php if(isset($departure_month_request) && !empty($departure_month_request)){ ?>
      $('.current_year li').each(function(i){
         var currentMonth =  new Date().getMonth();
         if(i<currentMonth){
            $(this).addClass('past_month')
            $(this).find("input").prop('disabled', true);
         }
      })      
   <?php }else{ ?>
      $('.current_year li').each(function(i){
         var currentMonth =  new Date().getMonth();
         if(i<currentMonth){
            $(this).addClass('past_month')
            $(this).find("input").prop('disabled', true);
         }
         if(i==currentMonth){
         //   $(this).find("input").prop('checked', true);
         }
      })      
   <?php } ?>
   $(".clear_services").click(function(){
      $('.checkbox_list input').prop('checked', false);
   })
$(document).ready(function(){
   $(function() {
      $( "#slider-range" ).slider({
         range: true,
         min: 0,
         max: 25000,
         values: [ {{!empty($min_price_request) ? $min_price_request : 0 }}, {{!empty($max_price_request) ? $max_price_request : 25000 }} ],
         slide: function( event, ui ) {
         $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
         }
      });

      $('.price2').on('keydown keyup',function(){
         var firstInputValue = $('.price1').val();
         $(this).val();
         $( "#slider-range" ).slider({
            values: [ firstInputValue,  $(this).val() ],
         });
      })
      $('.price1').on('keydown keyup',function(){
         var sencondInputValue = $('.price2').val();
         $(this).val();
         $( "#slider-range" ).slider({
            values: [ $(this).val(),  sencondInputValue ],
         });
      })

      $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
      " $" + $( "#slider-range" ).slider( "values", 1 ) );
      $(document).mousemove(function(){
         $('.price1').val( $( "#slider-range" ).slider( "values", 0 ))
         $('.price2').val( + $( "#slider-range" ).slider( "values", 1 ))
         // $('.price2').val("$" + $( "#slider-range" ).slider( "values", 1 ))
      })
   });
})
$(document).ready(function(){
   $(".filter_icon").click(function(){
      $(".packaging_wrap_inner .side_bar").fadeIn();
   });

   $(".cross").click(function(){
      $(".packaging_wrap_inner .side_bar").fadeOut();
   });

   $(document).on('change','.side_bar .filter_box input[type=checkbox]', function(){
      $('#adv_package_search').submit();
   });
})
</script>
<script type="text/javascript">
   var _token = '{{ csrf_token() }}';

   $(document).ready(function () {

      // var destinationId = $('#destination').val();
      // var subDestinationId = '{{ $sub_destination_request }}';
      // populateSubDestination(destinationId,subDestinationId);
      // $('#destination').on('change',function(e) {
      //    var destination_id = e.target.value;
      //    populateSubDestination(destination_id);
      // });

      $('#sort_by_price').on('change',function(e) {
         var sort_by = e.target.value;
         var requestQuery = '{{ $requestQuery }}';

         requestQuery = requestQuery.replaceAll('&amp;','&');

         if(requestQuery != ""){
            urlQuery = '?'+requestQuery+'&sort_by_price='+sort_by;
         }else{
            urlQuery = '?sort_by_price='+sort_by;
         }
         url = "{{$current_url}}";

         window.location = url+urlQuery;
         //alert(urlQuery);
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
            text +=  `<option value="">All Locations</option>`
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
</script>
<!-- Favourite add/remove script -->
<script type="text/javascript">

   $("body").on('click','.package_type input[type=radio]',function() {
      var _this1 = $(this);
      var pkgId = $(this).attr('data-package_id');
      var typeId = $(this).val();

      // alert(typeId);
      // alert(pkgId);
      
      var btnUrl = new URL($(this).closest('.packaging_view_box_top').find('.enquir_now').attr('href'));
      let params = new URLSearchParams(btnUrl.search);
      $.ajax({
         url:"{{ route('getPackageTypePrice') }}",
         type:"POST",
         headers:{'X-CSRF-TOKEN': _token},
         data: {
            pkgId: pkgId,
            typeId: typeId,
         },
         success:function (response) {
            if(response.success) {               
               _this1.parent().parent().parent().find('.amount').html(response.price);
               params.set("type", response['package_price_title']);
               var finalurl = btnUrl.origin + btnUrl.pathname + '?' + params.toString();
               _this1.closest('.packaging_view_box_top').find('.enquir_now').attr('href', finalurl);

            } else if(response.message) {
               alert(response.message);
            }
         }
      });
   });

</script>
@endslot

@endcomponent
