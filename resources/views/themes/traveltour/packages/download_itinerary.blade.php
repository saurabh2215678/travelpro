<?php
$websiteSettingsArr=CustomHelper::getSettings(['FRONTEND_LOGO','SITE_EMAIL','WHATSAPP1','WHATSAPP2','GOOGLE_TRANSLATOR_MANAGEMENT','SALE_PHONE','SALES_EMAIL','SITE_ADDRESS','SITE_PHONE']);
$storage = Storage::disk('public');
$path = "settings/";
// prd($websiteSettingsArr);
$logoSrc =public_path(config('custom.assets').'/images/footer_logo.png');
if(!empty($websiteSettingsArr['FRONTEND_LOGO'])){
   $logo = $websiteSettingsArr['FRONTEND_LOGO'];
   if($storage->exists($path.$logo)){
      $logoSrc = public_path('/storage/'.$path.$logo);
   }
}
$agent_phone = $websiteSettingsArr['SALE_PHONE']??'';
$agent_email = $websiteSettingsArr['SALES_EMAIL']??'';
// AGENT LOGO
$agentLogo = '';
$user = auth()->user();
$is_agent = 0;
if($user) { 
  $userId = $user->id??0;
  $is_agent = $user->is_agent??0;
  if($is_agent == 1){
    $userDetails = App\Models\User::where('id',$userId)->first();
    $path = 'agent_logo/';
    $agentLogo = !empty($userDetails->agentInfo)?$userDetails->agentInfo->agent_logo:''; 

    $agent_phone = '';
    $agent_email = '';
    if($userDetails->phone) {
      $country_code = $userDetails->country_code ?? '91';
      $agent_phone = '+'.$country_code.'-'.$userDetails->phone;
    }
    $agent_email = !empty($userDetails->email)?$userDetails->email:'';
    if(!empty($agentLogo)){
      if($storage->exists($path.$agentLogo)){
        $logoSrc = public_path('/storage/'.$path.$agentLogo);
      }
    }
  }
}
$address = $websiteSettingsArr['SITE_ADDRESS']??'';
$email = $websiteSettingsArr['SITE_EMAIL']??''; 
$phone = $websiteSettingsArr['SITE_PHONE']??'';
$phone1 = $websiteSettingsArr['SITE_PHONE1']??'';
$phone2 = $websiteSettingsArr['SITE_PHONE2']??'';
$PackageFlights = $package->PackageFlights??[];
$PackageInfo = $package->PackageInfo??[];
?>
<!DOCTYPE  html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <title>Download Itinerary</title>
   <meta name="author" content="Author"/>
<style type="text/css">
@page {       
   margin-top: 50px!important; /* create space for header */
   margin-bottom: 0px!important;  create space for footer 
}

   .adrs_mt p{margin-top:0;}
   .footer_add a{color:#fff;text-decoration:none;font-size: 13px;}
   .brief_p p{margin-top: 0;}
   li {display: block; }
   #l1 {padding-left: 0pt; }
   #l1> li>*:first-child:before {content: " "; color: black; font-family:Symbol, serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 10pt; }
   .right-content-itinerary ul li{margin-bottom:5px;}
   .faq_data ul li{margin-bottom:5px;font-size: 14px;}
   .fn_bold{font-weight:bold;font-size:15px;}
   .inclusions>li .fa:before {
      content: '';
      display: inline-block;
      background-position: -150px 0;
      min-width: 35px;
      min-height: 35px;
   }
   .inclusions>li[data-text=ac-cab] .fa:before {
      content: '';
      background-position: -300px -155px;
      width: 35px;
      height: 35px;
   }
   table {page-break-inside: avoid;}
   ul li ul{padding-left:15px;}
   ul.inclusions li {
      text-align: center;
      width: 3.5rem;
      min-width: -moz-min-content;
      min-width: min-content;
      line-height: 12px;
      font-size: .7rem;
      padding: 0 5px;
      font-weight: 500;
      color: #6e6e70;
      display: inline-block;
      
   }
   ul.inclusions li img {
      height: 2rem;
      -o-object-fit: cover;
      object-fit: cover;
      margin: 0 auto;
      width: 2rem;
      max-width: 2rem;
   }
   .left-img-itinerary {
      float: left;
      width: 335px;
      padding-right: 25px;
      margin-bottom:20px;
   }
   .left-img-itinerary img {
      width: 100%;
   }
   .right-content-itinerary p, .toggle_inner p {
      font-size: 14px;
   }
   .sm_price {
      background: #fff;
      border-radius: 5px;
      box-shadow: 0 1px 2px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.05);
      padding: 20px;
   }
   .book_info_list {
      font-size:14px;
      margin-top: 0;
      display: flex;
      padding: 10px 0;
      width: 100%;
      align-content: center;
      align-items: center;
   }
   .book_info_list>span {
      color: #464646;
      display: flex;
      justify-content: space-between;
   }
   .book_info_list>span, .book_info_list>strong {
      display: block;
      float: left;
      font-size: 14px;
   }
   .book_info_list>span {
      width: 40%;
   }
.swiper-wrapper {
   position: relative;
   width: 100%;
   z-index: 1;
   display: flex;
   flex-wrap:wrap;
   transition-property: transform;
   box-sizing: content-box;
}
.hotel_package_detail .slider_box .hotel_info_box.itenery_info .itn_img {
    width: 100%;
}
.hotel_package_detail .slider_box .hotel_info_box.itenery_info .itn_img img {
    height: 14rem;
    -o-object-fit: cover;
    object-fit: cover;
    width: 100%;
}
.hodel_detail_list .hotel_info .box-content {
    display: inline-block;
    width: 100%;
}
.hotel_info .dest_title {
    font-size: 15px;
    font-weight: 600;
    margin: 0 0 10px;
}
.vue-star-rating{
    display: flex;
    align-items: center;
}
.vue-star-rating-star {
    display: inline-block;
    -webkit-tap-highlight-color: rgba(0,0,0,0);
    -webkit-tap-highlight-color: transparent;
}
.hodel_detail_list .hotel_info .box-content .hotel_destination {
    margin-bottom: 5px;
}
.hotel_info .hotel_destination img.map-i {
    min-width: auto;
    width: 14px;
    padding-right: 5px;
    margin-left: 0;
    display: inline-block;
    position: relative;
    top:5px;
}
.flight_list {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -18px;
}



</style>
   </head>
   <body>
      <main>
         <div class="topsec">
            <table align="center" border="0" cellpadding="0" cellspacing="0" style="background: #f7f6f9;width: 100%; font-family: Arial, sans-serif; padding: 0px 15px 0 15px; box-sizing: border-box; max-width: 1000px; margin: 0px auto 0;" width="800" class=" cke_show_border">
               <tbody>
                  <tr>
                     <td colspan="2">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="cke_show_border" style="margin-top:10px;">
                           <tbody>
                              <tr style="background-color: #18afa6;">
                                 <td style="border-bottom:1px solid #000;padding: 15px;margin-top:10px;">
                                    <a href="{{ url('/') }}" class="header-logo"><img src="{{ $logoSrc }}" alt="logo" style="max-width:10rem;max-height: 7rem; object-fit: contain;"></a>
                                 </td>
                                 <td style="border-bottom:1px solid #000;padding:15px;margin-top:10px;">
                                    <div style="text-align:right;color: #fff;">
                                       <span style="color: #fff; font-family:Arial, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 12px;">Phone: {{$agent_phone}}</span><br>
                                       <span style="color: #fff; font-family:Arial, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 12px;">E-mail: <a href="mailto:{{$agent_email}}" style="color: #fff; font-family:Arial, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 12px;text-decoration:none;"> {{$agent_email}} </a></span>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td valign="top" style="margin-top:15px;">
                                    <h1 style="font-size:22px; font-family: Arial, sans-serif;">{{$package->title??''}}</h1>
                                    <?php // @if($package->is_activity==0) ?>
                                    <div style="margin-top:15px;">
                                       <img width="15" height="15" src="data:image/jpg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wAARCAALAAsDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD7ms4NQ8byyXk91qYmOovbeRaXLQR2MSttJYKwy2Pm+bP0qtp/xM8Q2Nu1qlkdXS3lkgW+2Z84I7KG447V1niXwXo97qn2l7Z45p2xM0FxJCJeMfOEYBvxzW/ZafbadbJa2sCQW8WUSNFwFANe7Ux2HhTUpU+ZPZPS3o0/0Xd6nzKwOIrTap1OR9ZLVv1X/BfZWR//2QAA"/>
                                       <span style="color: #666;font-family: Arial, sans-serif;font-style: normal;font-weight: normal;text-decoration: none;font-size: 10pt;margin: 0pt;">
                                          {{$package->package_duration??''}}
                                       </span>
                                    </div>
                                    <?php //@endif ?>
                                    <?php //@if($package->is_activity==0) ?>
                                    <div style="margin-top:15px;">
                                       <img width="15" height="15" src="data:image/jpg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wAARCAALAAsDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD7O+KniWbSPGGo219favb3ckMZ0KPTpSkTOeDvA4b5uuc8V7po4uTpNn9twbzyU87b034Gf1rH8QeHtO1XVtGvLu0jnurKZnt5GzmNsdR/9eugLEHFeticTTr0KNOEbNLX8tPLS782zyMJh6lPE1qjlo7WXrrr+S7I/9kA"/>
                                       <div style="color: #666;font-family: Arial, sans-serif;font-style: normal;font-weight: normal;text-decoration: none;font-size: 10pt;margin: 0pt;">

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
                                    </div>
                                   <?php //@endif ?>
                                 </td>
                                 <?php $storage = Storage::disk('public');
                                 $package_path = 'packages/';
                                 $package_image = $package->image;
                                 $packageSrc =public_path(config('custom.assets').'/images/noimagebig.jpg');
                                 if(!empty($package_image)) {
                                    if($storage->exists($package_path.$package_image)) {
                                       $packageSrc = public_path('/storage/'.$package_path.$package_image);
                                    }
                                 } ?>
                                 <td style="text-align: right;width:50%;">
                                    <img src="{{$packageSrc}}" style="width:100%;margin-top:15px;margin-left:auto;border-radius: 5px;" alt="{{$package->title??''}}"/>
                                 </td>
                              </tr>
                              <?php
                              $package_itenaries = (!($package->packageItenaries->isEmpty())) ? $package->packageItenaries : "";
                              if(isset($package_itenaries) && !empty($package_itenaries) && $package->is_activity==0){ ?>
                                 <tr>
                                    <td colspan="2">
                                    </td>
                                 </tr>
                              <?php }
                              $logoSrc = public_path(config('custom.assets').'/images/dot.png');
                              ?>
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
            <?php if(!empty($booking_data)){ ?>
            <table align="center" border="0" cellpadding="0" cellspacing="0" style="background: #f7f6f9;width: 100%; font-family: Arial, sans-serif; padding: 25px 15px 25px 15px; box-sizing: border-box; max-width: 1000px; margin: 0px auto 0;" width="800" class=" cke_show_border">
               <tbody>
                  <tr>
                     <td colspan="2">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="cke_show_border">
                           <tbody>
                              <tr>
                                 <td style="padding: 2px 5px 2px 5px;">
                                    <p style="color: #3a3a3c; font-size: 18px; font-family: Arial, sans-serif; margin: 4px 0px;"><strong>Booking Details : </strong></p>
                                 </td>
                                 <td style="padding: 2px 5px 2px 5px;">
                                 </td>
                              </tr>
                              <tr>
                                 <td style="padding: 2px 5px 2px 5px;">
                                    <p style="color: #000; font-size: 13px; font-family: Arial, sans-serif; margin: 4px 0px;"><strong>Package Type : </strong> {{$packagePrice->title??''}}</p>
                                 </td>
                              </tr>

                              <?php if(isset($booking_data['trip_date'])){ ?>
                              <tr>
                                 <td style="padding: 2px 5px 2px 5px;">
                                    <p style="color: #000; font-size: 13px; font-family: Arial, sans-serif; margin: 4px 0px;"><strong>Trip Date : </strong> {{CustomHelper::DateFormat($booking_data['trip_date'],'D, M dS Y','d/m/Y')??''}}
                                       <?php if(isset($booking_data['trip_slot'])){ //F d, Y ?>
                                         {{CustomHelper::getPackageSlotTitle($booking_data['trip_slot'])}}
                                         <?php } ?></p>
                                      </td>
                                   </tr>
                                 <?php } ?>

                                   <?php if($is_agent != 1 && $booking_data['booking_price'] > 0 && $booking_data['total_pax'] > 0){ ?>
                                   <tr>
                                    <td style="padding: 2px 5px 2px 5px;">
                                       <p style="color: #000; font-size: 13px; font-family: Arial, sans-serif; margin: 4px 0px;"><strong>Booking Amount : </strong>Rs. {{$booking_data['booking_price']*$booking_data['total_pax']}}</p>
                                    </td>
                              </tr>
                              <?php } ?>
                                 <tr>
                                    <td style="padding: 2px 5px 2px 5px;">
                                       <p style="color: #000; font-size: 13px; font-family: Arial, sans-serif; margin: 4px 0px;"><strong>Total Amount : </strong>Rs. {{$booking_data['total_amount']??0}}</p>
                                    </td>
                                 </tr>
                                 
                              </tbody>
                           </table>
                        </td>

                        <?php if(!empty($booking_data['travellers'])){ ?>
                        <td colspan="2">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="cke_show_border">
                           <tbody>
                                 <tr>
                                
                                    <td style="padding: 2px 5px 2px 5px;">
                                    <?php foreach ($booking_data['travellers'] as $traveller) { ?>
                                       <p style="color: #000; font-size: 13px; font-family: Arial, sans-serif; margin: 4px 0px;"><strong>{{$traveller['pce_label']}}:</strong>
                                          {{$traveller['pce_val']}} X Rs. {{$traveller['pce_price']}} = Rs. {{$traveller['sub_total']}}</p>
                                          <?php } ?>
                                    </td>
                                   
                                 </tr>
                                 
                              </tbody>
                           </table>
                        </td>
                      <?php } ?>
                     </tr>
                  </tbody>
               </table>
             <?php } ?>
            </div>

            <div class="clearfix"></div>

            
            
            <div style="width: 100%;font-family: Arial, sans-serif;box-sizing: border-box;max-width: 1000px;margin: 0px auto;background: #fff;padding:20px 15px;">
               <h3>Overview</h3>
               <h2 style="text-decoration:none;line-height:1;margin-bottom: 15px;">About {{$package->title}}</h2>
               @if($package->brief)
               <div class="brief_p" style="margin-top:0px;line-height: 1.2;margin-bottom:20px;">{!!$package->brief!!}</div>
               @endif 
               @if($package->description)
               <div class="brief_p" style="margin-top:0px;line-height: 1.2;margin-bottom:20px;">{!!$package->description!!}</div>
               @endif

               <!--- ======== Flight  ======== -->
               @if(!empty($PackageFlights) && count($PackageFlights) > 0)
               <div class="package_disc shadow-md mt-3 p-4" style="background:#F1F5F9;padding: 1rem;margin-bottom: 25px;">
               <h2 class="heading2 pt-3" style="font-size: 1.25rem;line-height: 1.75rem;">{{count($PackageFlights)}} Flights in the package</h2>
               <p class="para_lg2">
                  <ul class="flight_list">
                  @foreach($PackageFlights as $flight)
                  <li class="mt-0 rowid-{{$flight->id}}">
                     <img src="{{public_path('images')}}/flight.gif" alt="{{$flight->airline_name}}" class="logo">{{$flight->airline_name}} | {{$flight->flight_number}} | {{$flight->flight_departure}} <i class="fa fa-long-arrow-right"></i> {{$flight->flight_arrival}}
                  </li>
                  @endforeach
                  </ul>
               </p>
            </div>
            @endif
               <?php if(!empty($package_itenaries) && count($package_itenaries) > 0 && $package->is_activity==0){ ?>
                  <h3 class="text-xl font-bold" style="margin:0;">Package Itinerary</h3>
                  <ul style="padding:0;">
                     <?php foreach ($package_itenaries as $itenary) {
                        $itenaries_path = 'itenaries/';
                        $itenary_day_title = $itenary->day_title;
                        $itenary_title = $itenary->title;
                        $itenary_description = $itenary->description;
                        $itenary_image = $itenary->image; 
                        $itenary_location = $itenary->Location->name??'';
                        // $itenaryTags = $itenary->itenaryTags??[];
                        // prd($itenaryTags->toArray());
                        $itenaryTags = explode(',', $itenary->tags);
                        $itenary_inclusions = $itenary->itenary_inclusions??[];
                        $itenary_inclusions_arr = [];
                        if(!empty($itenary_inclusions)) {
                           $itenary_inclusions = json_decode($itenary_inclusions);
                        // $itenary_inclusions_arr = CustomHelper::showPackageInclusions($itenary_inclusions);
                           $itenary_inclusions_arr = CustomHelper::showPackageInclusionsOther($itenary_inclusions);
                        }
                        // prd($itenary_inclusions_arr);
                        $itenarySrc = '';
                        if(!empty($itenary_image)){
                           if($storage->exists($itenaries_path.$itenary_image)){
                              $itenarySrc = public_path('/storage/'.$itenaries_path.$itenary_image);
                           }
                        }
                        $itenaryThumbSrc =public_path(config('custom.assets').'/images/noimage.jpg');
                        if(!empty($itenary_image)){
                           if($storage->exists($itenaries_path.$itenary_image)){
                              $itenaryThumbSrc = public_path('/storage/'.$itenaries_path.'thumb/'.$itenary_image);
                           }
                        }
                        ?>
                        <li class="faq_main" style="margin-bottom:20px; margin-top:20px;clear: both;">
                           <div class="ml-14" style="margin-bottom:20px;">
                              <div class="day_curcle" style="margin-bottom:20px;">
                                 <span style="color: #fff; background: #18afa6; border-radius: 25px; padding: 8px 15px;">{{ $itenary_day_title }}</span> : <span class="">{!! $itenary_title !!}</span>                 
                                 @if(!empty($itenaryTags))
                                 <div class="theme_tags mb-5" style="margin-top:15px;margin-bottom:15px;">
                                    @foreach($itenaryTags as $itag)
                                    @if(!empty(trim($itag)))
                                    <span class="tags" style="margin:10px 10px 10px 0;padding: 5px 15px 5px;text-align: center; display: inline-block; font-size: 12px; border: 1px solid #c8c8c8; border-radius: 20px; text-transform: capitalize;">{{$itag??''}}</span>
                                    @endif
                                    @endforeach
                                 </div>
                                 @endif
                                 @if(!empty($itenary_inclusions_arr))
                                 <div class="faq_service" style="display: block;border: 1px solid #ddd; background: #f7f7f7;border-radius: 0.3rem;margin-bottom:15px;">
                                    <div class="title2 text-base" style="padding:10px 15px;">Services Included</div>
                                    <p style="font-size:12px;display: flex;">@include(config('custom.theme').'/packages/_itinerary_inclusions',['inclusions'=>$itenary_inclusions_arr,'itenary_pdf'=>true])</p>
                                 </div>
                                 @endif
                                 
                                 <div class="" style="display: block;clear: both;">
                                    @if(!empty($itenarySrc))
                                    <div class="left-img-itinerary" style="padding-right: 25px;clear: both;">
                                    <img src="{{ $itenarySrc }}" alt="{{$itenary_title}}" style="width:300px;height:250px;"/>
                                  </div>
                                    @endif
                                    
                                    <div class="right-content-itinerary">
                                     <div class="text-lg font-semibold">@if($itenary_location) <strong>{{$itenary_location}}</strong>@endif</div>
                                       <p class="">{!! $itenary_description !!}</p>
                                    </div>
                                 </div>
                                 <div class="clearfix"></div>
                              </div>
                           </div>
                        </li>
                     <?php } ?>
                  </ul>

               <?php } ?>

               <?php if(!empty($accommodations_days) && count($accommodations_days) > 0){ ?>
   <div id="hotel_accom" class="hotel_accordion mt-5">
      <h1 class="no_line text-2xl pb-1 font-bold">Accommodation</h1>
      <p class="pb-1"> <strong>Note:-</strong> We Will provide you these or similar accomodation depending on availability </p>
      <?php foreach ($accommodations_days as $key => $accommodation_days) {
         $accommodation_days->itenary_data;
         $itenary_arr = $accommodation_days->itenary_data?json_decode($accommodation_days->itenary_data):[];
         $accommodation_arr = $accommodation_days->accommodation_data?json_decode($accommodation_days->accommodation_data):[];
         ?>
         
    <div class="hotel_package_detail" style="margin-top: 15px;">
      <div>
          <?php $itineraries_title = [];
          foreach($itenary_arr as $key => $itenary_id) {
            $itenary_data = CustomHelper::getitenarydata($itenary_id);
            if($itenary_data) {
              $itineraries_title[]= $itenary_data->day_title ?? '' ;
            }
          } ?>
          <?php if(!empty($itineraries_title)){ ?>
            <div class="pull-right day_font">
              <h2 style="padding: 5px 10px; color:#fff; font-size:20px; background:#282658;">{!!implode(' > ',$itineraries_title)!!}</h2>
            </div>
          <?php } ?>
        </div>
      <div class="slider_box">
         <div class="swiper hotel_inner_slider">
            <div class="swiper-wrapper" style="width: 700px; height: 20rem; z-index: 1; display: flex;flex-wrap:wrap;">     
               <?php $counter=0;foreach($accommodation_arr as $key => $accommodation){$counter++;
                  $accommodation = CustomHelper::getAccommodationdata($accommodation);
                  $disabled ='';
                  if($accommodation){
                     if($accommodation->status == 0){
                        $disabled = 'disabled';
                     }
                     $storage = Storage::disk('public');
                     $accommodation_path = 'accommodations/';
                     $accommodation_image = isset($accommodation->image) ? $accommodation->image:'';

                     //$accommodationThumbSrc =asset(config('custom.assets').'/images/noimage.jpg');
                     $accommodationThumbSrc = public_path(config('custom.assets').'/images/noimage.jpg');
                     if(!empty($accommodation_image)){
                        if($storage->exists($accommodation_path.$accommodation_image)){
                           //$accommodationThumbSrc = asset('/storage/'.$accommodation_path.'thumb/'.$accommodation_image);
                           $accommodationThumbSrc = public_path('/storage/'.$accommodation_path.'thumb/'.$accommodation_image);
                        }
                     }
                     $accommodation_slug = $accommodation->slug;
                     $accommodation_star = $accommodation->star_classification;
                     $accommodation_brief = $accommodation->brief ?? '';
                     ?>
                     <div class="swiper-slide {{$disabled}}" style="width: 230px; float:left;margin-right:5px;">
                        <div class="hodel_detail_list">
                           <div class="hotel_info hotel_info_typeid385" style="padding-buttom: 20px;">
                              <div class="hotel_info_box itenery_info">
                                 <div class="itn_img">
                                    <img src="{{ $accommodationThumbSrc }}" alt="{{$accommodation->name ?? '' }}" >
                                 </div>
                                 <div class="box-content" @if(count($accommodation_arr) == '1') style="" @endif>
                                    <h6 class="dest_title" style="font-size:20px;padding:10px 0 0;font-family: Arial, sans-serif;"> {{$accommodation->name ?? '' }}</h6>
                                    <div class="star-loc">
                                    <ul class="rating_list" style="margin:0;padding:0;padding-buttom: 10px;" rating="{{ $accommodation_star }}">
                                    <?php for ($i=0; $i < $accommodation_star; $i++) { ?>
                                    <li style="display:inline-block;">
                                      <img style="height: 1rem; width: 1rem;" src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1NzYgNTEyIj48cGF0aCBkPSJNMzgxLjIgMTUwLjNMNTI0LjkgMTcxLjVDNTM2LjggMTczLjIgNTQ2LjggMTgxLjYgNTUwLjYgMTkzLjFDNTU0LjQgMjA0LjcgNTUxLjMgMjE3LjMgNTQyLjcgMjI1LjlMNDM4LjUgMzI4LjFMNDYzLjEgNDc0LjdDNDY1LjEgNDg2LjcgNDYwLjIgNDk4LjkgNDUwLjIgNTA2QzQ0MC4zIDUxMy4xIDQyNy4yIDUxNCA0MTYuNSA1MDguM0wyODguMSA0MzkuOEwxNTkuOCA1MDguM0MxNDkgNTE0IDEzNS45IDUxMy4xIDEyNiA1MDZDMTE2LjEgNDk4LjkgMTExLjEgNDg2LjcgMTEzLjIgNDc0LjdMMTM3LjggMzI4LjFMMzMuNTggMjI1LjlDMjQuOTcgMjE3LjMgMjEuOTEgMjA0LjcgMjUuNjkgMTkzLjFDMjkuNDYgMTgxLjYgMzkuNDMgMTczLjIgNTEuNDIgMTcxLjVMMTk1IDE1MC4zTDI1OS40IDE3Ljk3QzI2NC43IDYuOTU0IDI3NS45LS4wMzkxIDI4OC4xLS4wMzkxQzMwMC40LS4wMzkxIDMxMS42IDYuOTU0IDMxNi45IDE3Ljk3TDM4MS4yIDE1MC4zeiIvPjwvc3ZnPg=='/>
                                    </li>
                                    <?php } ?>
                                 </ul>
                                 <div class="hotel_destination text-sm" style="padding-top:10px;"><img class="map-i" src="{{public_path(config('custom.assets').'/images/map.png')}}">
                                    <?php
                                    $name_arr = [];
                                    $place_name = $accommodation->AccommodationDefaultLocation->DestinationLocation->name??'';
                                    if($place_name) {
                                       $name_arr[] = $place_name;
                                    }
                                    $distination_name = $accommodation->accommodationDestination->name??'';
                                    if($distination_name) {
                                       $name_arr[] = $distination_name;
                                    }
                                    if(!empty($name_arr)) {
                                       echo implode(', ',$name_arr);
                                    }
                                    ?>
                                 </div>
                              </div>
                              
                            </div>
                        </div>
                        
                     </div>
                  </div>
               </div>
             <?php if(($counter%3)==0){ ?>
                  </div>
                </div>
              </div>
           
        <div class="slider_box">
         <div class="swiper hotel_inner_slider">
            <div class="swiper-wrapper" style="width: 700px; height: 20rem; z-index: 1; display: flex;flex-wrap:wrap;"> 
                  <?php } ?>
            <?php } } ?>
         </div>
      </div>
   </div>
 <?php } ?>
</div>
<?php } ?>
               @if(isset($package->inclusions) && !empty($package->inclusions))
               <h3 class="text-2xl pb-3" style="background: #ededed;padding: 10px 14px;">Policy</h3>
               <ul class="toggle_inner" style="padding-left:0">
                  <li class="faq_main">
                     <div class="fn_bold" style=" font-size: 1rem; ">Inclusions</div>
                     {!!$package->inclusions!!}
                  </li>
               </ul>
               @endif
               @if(isset($package->exclusions) && !empty($package->exclusions))
               <ul class="toggle_inner" style="padding-left:0">
                  <li class="faq_main">
                   <div class=" fn_bold" style="margin-bottom:15px;margin-top:15px;" style=" font-size: 1rem; ">Exclusions</div>
                   {!!$package->exclusions!!}
                </li>
             </ul>
             @endif
             @if(isset($package->payment_policy) && !empty($package->payment_policy))
             <ul class="toggle_inner" style="padding-left:0">
               <li class="faq_main">
                <div class=" fn_bold" style="margin-bottom:15px;margin-top:15px;" style=" font-size: 1rem; ">Payment Policy</div>
                {!!$package->payment_policy!!}
             </li>
          </ul>
          @endif
          @if(isset($package->cancellation_policy) && !empty($package->cancellation_policy))
          <ul class="toggle_inner" style="padding-left:0">
            <li class="faq_main">
             <div class=" fn_bold" style="margin-bottom:15px;margin-top:15px;" style=" font-size: 1rem; ">Cancellation Policy</div>
             {!!$package->cancellation_policy!!}
          </li>
       </ul>
       @endif
       @if(isset($package->terms_conditions) && !empty($package->terms_conditions))
       <ul class="toggle_inner" style="padding-left:0">
         <li class="faq_main">
          <div class="fn_bold" style="margin-bottom:5px;margin-top:15px;font-size: 1rem;">Terms and Conditions</div>
          {!!$package->terms_conditions!!}
       </li>
    </ul>
    @endif
    @if(!empty($PackageInfo))
    @foreach($PackageInfo as $info)
    @if(!empty($info->title) && !empty($info->description))
    <ul class="toggle_inner" style="padding-left:0">
      <li class="faq_main">
       <div class="fn_bold">{{$info->title}}</div>
       {!!$info->description!!}
    </li>
 </ul>
 @endif
 @endforeach
 {{ $video_link ?? "" }}
 @endif
</div>

<?php if($is_agent != 1){ ?>
   <table style="width:100%;background:#12968e;padding:25px 15px;margin-top:35px;max-width:1000px;margin: 0 auto;color:#fff;">
      <tr>
      <td valign="top" colspan="2" style="width:200px;">
        <!-- <p class="text-lg">Ticket Booking or Enquiry:</p> -->
         <div class="footer_add" style="display: inline-block;font-family: Arial, sans-serif;width:200px;">
            <img src="{{ public_path(config('custom.assets').'/img/telephone.png') }}" alt="Phone" style="width:14px;margin-right:5px;float:left;"> {!! $phone !!}
         </div>
         <div class="footer_add" style="display: inline-block;font-family: Arial, sans-serif;width:200px;">
           <img src="{{ public_path(config('custom.assets').'/img/mail.png') }}" alt="Email" style="width:14px;margin-right:5px;"> {!! $email !!}
         </div>
         </td>
         <td colspan="2" style="width:200px;">
            <!-- <p style="margin-bottom:10px;font-family: Arial, sans-serif;font-size:20px;">Contact Us:</p> -->
            <div class="adrs_mt" style="font-family: Arial, sans-serif;"><img src="{{ public_path(config('custom.assets').'/img/location.png') }}" alt="Phone" style="width:12px;margin-right:5px;"> {!! $address !!}</div>
         </td>
      </tr>
   </table>
<?php } ?>
</main>
</body>
</html>