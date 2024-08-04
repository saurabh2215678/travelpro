@component(config('custom.theme').'.layouts.main')

    @slot('title')
      {{ $page_title ?? 'Flights'}}
    @endslot    

    @slot('meta_description')
      {{ $meta_description ?? 'Flights'}}
    @endslot
   
    @slot('meta_keyword')
    {{$meta_keyword ?? 'Flights'}} 
    @endslot

    @slot('headerBlock')
   @endslot

    @slot('bodyClass')
        flights_class
    @endslot
<?php
//prd($cms);
$storage = Storage::disk('public');
   $cms_path = 'cms_pages/';
   $cms_heading = $cms['heading'];
   $cms_content = $cms['content'];
   $cms_image = $cms['image'];
   $cms_banner_image = $cms['banner_image'];

   $cmsSrc =asset(config('custom.assets').'/images/noimage.jpg');
   $cmsThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

   if(!empty($cms_image)){
      if($storage->exists($cms_path.$cms_image)){
         $cmsSrc = asset('/storage/'.$cms_path.$cms_image);
      }
   }

   if(!empty($cms_banner_image)){
      if($storage->exists($cms_path.$cms_banner_image)){
         $cmsThumbSrc = asset('/storage/'.$cms_path.$cms_banner_image);
      }
   } 
?>

<section class="inner_banner">
  <div class="inner_banner_main">
    <img src="{{ $cmsThumbSrc }}" alt="" >
  </div>
</section>
<section class="flight_page pb_150">
<div class="container">
<div class="text-center">
<div class="theme-title">
<div class="title text-3xl pb-5">Book Roundtrip Flights Today!</div>

<!-- <div class="icon mt15">&nbsp;</div> -->
<!-- <img alt="" src="{{asset(config('custom.assets').'/images/featured-icon.png')}}" /></div> -->
</div>
<div class="gateways_grid group_travel_top">
  <div class="visa_assistance">
     <!-- <div class="images">
        <img alt="" class="theme_radius img_responsive" src="{{ $cmsSrc }}" />
     </div> -->
     <div class="visa_assistance_content mt0">
     <div class="title heading3">{{ $cms_heading }}</div>
     {!! $cms_content !!}
     </div>

  </div>
</div>
<div style="clear: both"></div>
<div class="flight-list">
  <ul>
    <li><img alt="" src="{{asset(config('custom.assets').'/images/Vistara_Logo.png')}}" /> <span>Vistara</span></li>
    <li><img alt="" src="{{asset(config('custom.assets').'/images/Indigo-logo.png')}}" /> <span>Indigo</span></li>
    <li><img alt="" src="{{asset(config('custom.assets').'/images/Air-India-logo.png')}}" /> <span>Air India</span></li>
    <li><img alt="" src="{{asset(config('custom.assets').'/images/SpiceJet-Logo.png')}}" /> <span>SpiceJet</span></li>
    <li><img alt="" src="{{asset(config('custom.assets').'/images/AirAsia-Logo.png')}}" /> <span>AirAsia</span></li>
    <li><img alt="" src="{{asset(config('custom.assets').'/images/GoAir-Logo.png')}}" /> <span>GoAir</span></li>
    <li><img alt="" src="{{asset(config('custom.assets').'/images/Air-India-Express-Logo.png')}}" /> <span>Air India Express</span></li>
  </ul>
</div>
      <!--<div class="text_center">
        <div class="theme_title">
           <div class="title">
            Most Popular Routes
           </div>
           <div class="icon mt15">
           </div>
           <img src="{{asset(config('custom.assets').'/images/featured-icon.png')}}"   alt="">
        </div>
      
     </div>-->
     <?php if(!$airlines->isEmpty()){?>
     <!--<ul class="flight_list">
      <?php
            foreach ($airlines as $airline) {
               $storage = Storage::disk('public');
               $flight_path = "airlines/";
               $flight_name = $airline->airline_name;
               $flight_from = $airline->airline_from;
               $flight_to = $airline->airline_to;
               $flight_price = $airline->price;
               $flight_image = $airline->image;

               $flightThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');

               if(!empty($flight_image)){
                  if($storage->exists($flight_path.$flight_image)){
                     $flightThumbSrc = asset('/storage/'.$flight_path.'thumb/'.$flight_image);
                  }
               }
         ?>   
       <li>
         <div class="flight_list_box">
         <div class="flight_name">{{ $flight_name }}</div>
            <div class="flight_price para_md">
              <small>Starting at</small> <span>{{ CustomHelper::getPrice($flight_price) }}</span> 
            </div>
            <div class="flight_left">
           <div class="flight_logo">
              <img src="{{ $flightThumbSrc }}" class="img_responsive"   alt="">
           </div>
         </div>

           <div class="flight_right">
           <div class="airport_name para_md">
            <strong>From</strong>
            {{ $flight_from }}
            
           </div>
           <div class="airport_name para_md">
           <strong>To</strong>
            {{ $flight_to }}
           </div>
          
         </div>
         </div>
       </li>
    <?php } ?>
     </ul>-->
  <?php } ?>
 

  <!--<div class="flight_table_data">
    <div class="para_md font600 title">Drukair operates scheduled flights to the following destinations</div>
   <table class="table theme_table flight_table">
      <thead>
        <tr>
          <th scope="col">Country</th>
          <th scope="col">City</th>
          <th scope="col">Airport</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>Bangladesh</th>
          <td>Dhaka</td>
          <td>Hazrat Shahjalal International Airport</td>
        </tr>
        <tr>
          <th rowspan="5">India</th>
          <td>Bagdogra</td>
          <td>Bagdogra Airport</td>
        
        </tr>
        <tr>
         <td>Delhi</td>
         <td>Indira Gandhi International Airport</td>
       </tr>
       <tr>
         <td>Gaya</td>
         <td>Gaya Airport</td>
       </tr>
       <tr>
         <td>Kolkata</td>
         <td>Netaji Subash Chandra Bose International Airport</td>
       </tr>
       <tr>
         <td>Guwahati</td>
         <td>Lokpriya Gopinath Bordoloi International Airport</td>
       </tr>
       <tr>
         <th>Nepal</th>
         <td>Kathmandu</td>
         <td>Tribhuvan International Airport</td>
       </tr>
       <tr>
         <th>Thailand</th>
         <td>Bangkok</td>
         <td>Suvarnabhumi International Airport</td>
       </tr>
       <tr>
         <th>Singapore</th>
         <td>Singapore</td>
         <td>Changi Airport</td>
       </tr>
       <tr> 
         <th rowspan="5">Bhutan</th>
         <td>Paro</td>
         <td>Paro International Airport (International & Domestic)</td>
       </tr>
       <tr>
         <td>Bumthang</td>
         <td>Bumthang Airport (Domestic)</td>
       </tr>
       <tr>
         <td>Gelephu</td>
         <td>Gelephu Airport (Domestic)</td>
       </tr>
       <tr>
         <td>Yonphula</td>
         <td>Yonphula Airport (Domestic)</td>
       </tr>
        <tr>
          <th scope="row">3</th>
          <td colspan="2">Larry the Bird</td>
          <td>@twitter</td>
        </tr>
      </tbody>
    </table>
  </div>-->
  <!--<div class="para_md font600 ">Email : <a href="#"></a> for more details & reservation</div>-->
   </div>
</section>
@slot('bottomBlock')


@endslot

@endcomponent
