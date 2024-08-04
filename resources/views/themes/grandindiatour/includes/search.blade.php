<?php
$search_text = $search_text??'';
$search_slug = $search_slug??'';
$text = (request()->has('text')) ? request()->text : $search_text;
$dep = (request()->has('dep')) ? request()->dep : '';
$stars = (request()->has('stars')) ? request()->stars : '';
$sdest = (request()->has('sdest')) ? request()->sdest : '';
$sno_of_days = (request()->has('sno_of_days')) ? request()->sno_of_days : '';
$smonth = (request()->has('smonth')) ? request()->smonth : '';
$checkin = (request()->has('checkin')) ? request()->checkin : date('Y-m-d',strtotime('+6 days'));
$checkout = (request()->has('checkout')) ? request()->checkout : date('Y-m-d',strtotime('+7 days'));
$checkin_picker = CustomHelper::DateFormat($checkin,'d/m/Y');
$checkout_picker = CustomHelper::DateFormat($checkout,'d/m/Y');

$inventory = (request()->has('inventory')) ? request()->inventory : 1;
$adult = (request()->has('adult')) ? request()->adult : 2;
$child = (request()->has('child')) ? request()->child : 0;
$guest_title = $adult.' Adults';
if($child) {
  $guest_title = $guest_title.' + '.$child.' Child';
}
if($inventory) {
  $guest_title = $guest_title.' | '.$inventory.' Room(s)';
}

$noOfDays = array('1-4'=>'1-4 Days','5-8'=>'5-8 Days','9-15'=>'9-15 Days','16-30'=>'16-30 Days');
$current_url = url()->current();
$segments1 = request()->segment(1);
$flight_active = ($current_url==route('flight.index')) ? True : False;
$cab_active = ($current_url==route('cab.index')) ? True : False;
$hotel_active = ($current_url==route('hotelListing') || (!empty($segments1) && stripos(route('hotelDetail',['slug'=>'slug']), $segments1) !== false)) ? True : False;
$package_active = ($current_url==route('packageListing') || (stripos($current_url, 'tour-packages') !== false)) ? True : False;
$activity_active = ($current_url==route('activityListing') || (stripos($current_url, 'tour-activities') !== false) ) ? True : False;
?>
<div class="main-header" id="my_form">
  <div class="flight_page">
    <div class="flight-banner">
      <div class="xl:container xl:mx-auto container mx-auto">
        <div class="head-search">
          <ul class="menu_list">
            @if(CustomHelper::isAllowedModule('flight'))
            <li>
              <a href="{{route('flight.index')}}" <?php if($flight_active){ echo 'class="active"';}?> ><i class="fa fa-plane"></i> Flight</a>
            </li>
            @endif
            @if(CustomHelper::isAllowedModule('cab'))
            <li>
              <a href="{{route('cab.index')}}" <?php if($cab_active){ echo 'class="active"';}?> ><i class="fa fa-taxi"></i> Cab</a>
            </li>
            @endif
            <li>
              <a href="{{route('packageListing')}}" <?php if($package_active){ echo 'class="active"';}?> > <i class="fa fa-suitcase"></i> Holiday Packages</a>
            </li>
            <li>
              <a href="{{route('activityListing')}}" <?php if($activity_active){ echo 'class="active"';}?> ><img class="icon_img" src="{{asset(config('custom.assets').'/images/activities-icon.png')}}" alt="Short Activities"> Short Activities</a>
            </li>
            <li>
              <a href="{{route('hotelListing')}}" <?php if($hotel_active){ echo 'class="active"';}?> ><i class="fa fa-building-o"></i> Hotels</a>
            </li>
          </ul>
          <?php if($hotel_active){ ?>
          <form action="{{ route('hotelListing') }}" method="GET" name="searchForm" class="" id="search_hotels_form" onSubmit="return validateSearchHotelForm()" > 
            <h3 class="text-lg font-bold pb-1 leading-normal">Search Hotels</h3>
            <div class="header-top-search flex gap-2">
              <div class="selectoption pr-0.5 md:w-1/2 max-md:w-full">
                <label>Destination or Property Name</label>
                <i class="mapicon"></i>
                <input type="text" name="text" class="form-control" value="{{$text}}" id="search_hotels" autocomplete="off" placeholder="Place or accommodation">
                <div class="search_hotels">
                  <ul id="search_hotels_ul"></ul>
                </div>
              </div>

              <div class="selectoption date_box pr-0.5 md:w-1/2 max-md:w-full">
                <!-- <label class="flex mx-1"><span>(Check In - Check Out)</span></label> -->
                <label><span>(Check In - Check Out)</span></label>
                <i class="calendericon"></i>
                <input type="text" id="hoteldaterange" class="form-control daterange" value="{{$checkin_picker}} - {{$checkout_picker}}" autocomplete="off">
                <input type="hidden" name="checkin" value="{{$checkin}}">
                <input type="hidden" name="checkout" value="{{$checkout}}">
              </div>
              
              <div class="selectoption pr-0.5 md:w-1/2 max-md:w-full">
                <label>Guest & Rooms</label>
                <i class="guest-icons"></i>
                <input type="text" class="form-control guest_room" value="{{$guest_title}}" id="guest_rooms" autocomplete="off">
                <div class="passenger_wrap">
                <div class="guest_room_box flex">
                  <div class="guest_room_select w-1/2" data-id="guest_room_select">
                     <label>Rooms <span> (Max 6)</span></label>
                     <div class="selc_btn">
                      <span><i class="fa fa-minus"></i></span>
                      <span class="pax_counter">{{$inventory}}</span>
                      <span><i class="fa fa-plus"></i></span>
                     </div>
                  </div>
                  <div class="adults_select w-1/2" data-id="adults_select">
                    <label>Adults <span> (12+ yr)</span></label>
                    <div class="selc_btn">
                      <span><i class="fa fa-minus"></i></span>
                      <span class="pax_counter">{{$adult}}</span>
                      <span><i class="fa fa-plus"></i></span>
                    </div>
                  </div>
                  <div class="child_select w-1/2" data-id="child_select">
                    <label>Children <span> (0-12 yr)</span></label>
                    <div class="selc_btn">
                      <span><i class="fa fa-minus"></i></span>
                      <span class="pax_counter">{{$child}}</span>
                      <span><i class="fa fa-plus"></i></span>
                    </div>
                  </div>
                </div>
                <div class="mt-2 text-left"><button type="button" class="btn2 text-sm capitalize cursor-pointer mt-1 px-3 py-1 pax_done">Done</button></div>
                </div>
                <input type="hidden" name="inventory" value="{{$inventory}}">
                <input type="hidden" name="adult" value="{{$adult}}">
                <input type="hidden" name="child" value="{{$child}}">
              </div>

              <div class="selectoption pr-0.5 md:w-1/2 max-md:w-full" style="display:none;">
                <label>Select Stars</label>
                <select name="stars" class=" pl-9">
                  <option value="">Select Stars</option>
                  <?php for($i = 1; $i<=5; $i++) {
                    $selected = '';
                    if($stars == $i) { $selected = 'selected'; }
                    ?>
                    <option value="{{$i}}" {{$selected}}>{{$i.' Star'}}</option>
                  <?php } ?>
                </select>
              </div>
              <div class="searchbtn mt-6">
                <input type="hidden" name="slug" value="{{$search_slug}}">
                <input type="submit" class="btn btn-primary p-2 pl-4 pr-3 h-auto" value="Search"> 
              </div>
            </div>
          </form>
          <?php } else if($activity_active) { ?>
          <form action="{{ route('activityListing') }}" method="GET" name="searchForm" class="" id="search_activities_form" onSubmit="return validateSearchActivityForm()" > 
            <h3 class="text-lg font-bold pb-1 leading-normal">Search Activities</h3>
            <div class="flex gap-2">
              <div class="selectoption pr-0.5 md:w-1/2 max-md:w-full">
                <label>Where To?</label>
                <i class="mapicon"></i>
                <input type="text" name="text" class="form-control" value="{{$text}}" id="search_activities" autocomplete="off" placeholder="Search for a place or activity">
                <div class="search_activities">
                  <ul id="search_activities_ul"></ul>
                </div>
              </div>

              <div class="selectoption date_box pr-0.5 md:w-1/2 max-md:w-full">
                <label>When?</label>
                <input type="text" name="dep" class="form-control datepicker" value="{{$dep}}" autocomplete="off" placeholder="DD/MM/YYYY">
                <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
        </span>
              </div>

              <div class="searchbtn mt-6">
                <input type="hidden" name="slug" value="">
                <input type="submit" class="btn btn-primary p-2 pl-4 pr-3 h-auto" value="Search"> 
              </div>
            </div>
          </form>
          <?php }else{ ?>
          <form action="{{route('packageListing')}}" method="GET" name="searchForm" class="" id="search_packages_form" onSubmit="return validateSearchPackageForm()">
            <h3 class="text-lg font-bold pb-1 leading-normal">Search Holiday Packages</h3>
            <div class="flex gap-2">
              <?php /*
              <div class="selectoption pr-0.5 md:w-1/2 max-md:w-full">
                <i class="mapicon"></i>
                <select name="sdest" class=" pl-9">
                  <option value="">Type a Destination</option>
                  {!!CustomHelper::getDestinationOptions('', $sdest, ['show_active'=>true])!!}
                </select>
              </div>*/ ?>

              <div class="selectoption pr-0.5 md:w-1/2 max-md:w-full">
                <i class="mapicon"></i>
                <input type="text" name="text" class="form-control" value="{{$text}}" id="search_packages" autocomplete="off" placeholder="Search for a place or package">
                <div class="search_packages">
                  <ul id="search_activities_ul"></ul>
                </div>
              </div>

              <div class="selectoption pr-0.5 md:w-1/3 max-md:w-full">
                <i class="themepackicon"></i>
                <select name="sno_of_days" class=" pl-6">
                  <option value="">Number of days</option>
                  <?php
                  if(!empty($noOfDays)) {
                    foreach($noOfDays as $nod_k=>$nod_v) {
                      $smw_selected = '';
                      if($sno_of_days==$nod_k) {
                        $smw_selected = 'selected';
                      }
                  ?>
                  <option value="<?php echo $nod_k; ?>" <?php echo $smw_selected; ?> ><?php echo $nod_v; ?></option>
                  <?php } } ?>
                </select>
              </div>

              <div class="selectoption pr-0.5 md:w-1/3 max-md:w-full">
                <i class="calendericon"></i>
                <select name="smonth" class="pl-8">
                  <option value="">Select Month</option>
                  <?php 
                  for ($i=0; $i < 12; $i++) {
                    $month = date('Y-m',strtotime('+'.$i.'month'));
                    $smw_selected = '';
                    if($smonth==$month) {
                      $smw_selected = 'selected';
                    }
                  ?>
                  <option value="{{$month}}" {{$smw_selected}} >{{CustomHelper::DateFormat($month,'M Y')}}</option>
                  <?php } ?>
                  <option value="not_decision" <?php if($smonth=='not_decision'){echo 'selected';} ?> >Not decided</option>
                </select>
              </div>
              <div class="searchbtn">
                <input type="hidden" name="slug" value="">
                <input type="submit" class="btn btn-primary" value="Explore"> 
              </div>
            </div>
          </form>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
   //alert($('#my_form').length); 
   if($('#my_form').length!=0){
   $('body').addClass('headeradd')
   }
</script>
<script type="text/javascript">
  <?php if($hotel_active){ ?>
  $(function() {
    $(document).on('click','#search_hotels',function(event){
      load_hotels();
    });  
    $(document).on('keyup','#search_hotels',function(event){
      var search = true;
      var li_count = $('#search_hotels_ul li').length;
      // if(li_count > 0) {
        var curent_active = -1;
        var each_counter = 0;
        $('#search_hotels_ul li').each(function(){
          if( $(this).hasClass('active')){
            curent_active = each_counter;
          }
          each_counter++;
        });

        switch (event.keyCode) {
          case 37:
              // alert('Left key');
              curent_active-=1;
              search = false;
            break;
          case 38:
              // alert('Up key');
              curent_active-=1;
              search = false;
            break;
          case 39:
              // alert('Right key');
              curent_active+=1;
              search = false;
            break;
          case 40:
              // alert('Down key');
              curent_active+=1;
              search = false;
            break;
          case 13:
              // alert('Enter key');
              search = false;
            break;
        }

        if(curent_active==li_count){
          curent_active = 0;
        }

        $('#search_hotels_ul li').removeClass('active');
        $('#search_hotels_ul li').eq(curent_active).addClass('active');
      // }

      if(search) {
        $('#search_hotels_ul').hide();
        $('#search_hotels_ul').empty();
        $('#search_hotels_form input[name=slug]').val('');
        $('#search_hotels_form input[name=slug]').attr('disabled',true);

        var text = $(this).val();
        if(text.length > 2) {
          load_hotels(text);
        }
      }
    });

    function load_hotels(text=''){
      var _token = '{{ csrf_token() }}';
          $.ajax({
            url:"{{ route('ajaxSearchHotels') }}",
            type:"POST",
            headers:{'X-CSRF-TOKEN': _token},
            data: {
              text: text
            },
            success:function (response) {
              $('#search_hotels_ul').empty();
              if(response.success) {
                if(response.result) {
                  $.each(response.result, function(index,row){
                    var row_li = '<li data-slug="'+row.slug+'">'+row.title+'</li>'
                    $('#search_hotels_ul').append(row_li);
                  });
                  $('#search_hotels_ul').show();
                }
              } else if(response.message) {
                console.log('false');
              } else {
                console.log('error');
              }
            },complete:function(){

            }
          });
    }

    $(document).on('click','#search_hotels_ul li',function(){
      var slug = $(this).attr('data-slug');
      var title = $(this).text();
      $('#search_hotels_ul').hide();
      $('#search_hotels_ul').empty();
      $('#search_hotels').val(title);
      $('#search_hotels_form input[name=slug]').val(slug);
      $('#search_hotels_form input[name=slug]').attr('disabled',false);
    });

    $('input[id="hoteldaterange"]').daterangepicker({
      locale: {
        format: 'DD/MM/YYYY'
      },
      minDate: new Date()
    },function(start, end, label) {
      $(document).find('input[name=checkin]').val(start.format('YYYY-MM-DD'));
      $(document).find('input[name=checkout]').val(end.format('YYYY-MM-DD'));
    });

    $(document).click(function(e){
      var clickedElement = $(e.target);
      if(!(clickedElement.closest('.passenger_wrap').length > 0 || clickedElement.hasClass('passenger_wrap'))){
        $('.passenger_wrap').hide();
      }
    });

  

    $('.guest_room').on('click', function(e) {
      e.stopPropagation();
      $('.passenger_wrap').toggle();
    });

    $(document).on('click','.passenger_wrap .fa-minus',function(){
      var counter = $(this).parent().parent().find('.pax_counter').html();
      var pax_type = $(this).parent().parent().parent().attr('data-id');
      counter = parseInt(counter);
      if(counter > 1) {
        counter = counter - 1;
      } else if(pax_type == 'child_select') {
        counter = 0;
      }
      $(this).parent().parent().find('.pax_counter').html(counter);
    });
    $(document).on('click','.passenger_wrap .fa-plus',function(){
      var counter = $(this).parent().parent().find('.pax_counter').html();
      counter = parseInt(counter);
      if(counter >= 6) {
        alert('Max allowed upto 6')
      } else {
        counter = counter + 1;
      }
      $(this).parent().parent().find('.pax_counter').html(counter);        
    });

    $(document).on('click','.pax_done',function(){
      var inventory = $(this).parent().parent().find('.guest_room_select .pax_counter').html();
      var adult = $(this).parent().parent().find('.adults_select .pax_counter').html();
      var child = $(this).parent().parent().find('.child_select .pax_counter').html();
      $(document).find('input[name=inventory]').val(parseInt(inventory));
      $(document).find('input[name=adult]').val(parseInt(adult));
      $(document).find('input[name=child]').val(parseInt(child));
      var guest_title = adult+' Adults';
      if(parseInt(child) > 0) {
        guest_title = guest_title + ' + '+ child+' Child';
      }
      if(parseInt(inventory) > 0) {
        guest_title = guest_title + ' | '+ inventory+' Room(s)';
      }
      $(document).find('#guest_rooms').val(guest_title);
      $('.passenger_wrap').fadeToggle("slow");
    });

  });
  function validateSearchHotelForm() {
    var search = true;
    $('#search_hotels_ul li').each(function(){
      if( $(this).hasClass('active')) {
        search = false;
        $(this).trigger('click');
      }
    });
    return search;
  }

  $(document).click(function(e){
    var clickedElement = $(e.target);
    if(!(clickedElement.closest('.search_hotels ul').length > 0 || clickedElement.hasClass('.search_hotels ul'))){
      $('.search_hotels ul').hide();
    }
  });
  <?php } else if($activity_active){ ?>
    $(document).on('click','#search_activities',function(event){
      load_activities();
    });
    $(document).on('keyup','#search_activities',function(event){
      var search = true;
      var li_count = $('#search_activities_ul li').length;
      // if(li_count > 0) {
        var curent_active = -1;
        var each_counter = 0;
        $('#search_activities_ul li').each(function(){
          if( $(this).hasClass('active')){
            curent_active = each_counter;
          }
          each_counter++;
        });

        switch (event.keyCode) {
          case 37:
              // alert('Left key');
              curent_active-=1;
              search = false;
            break;
          case 38:
              // alert('Up key');
              curent_active-=1;
              search = false;
            break;
          case 39:
              // alert('Right key');
              curent_active+=1;
              search = false;
            break;
          case 40:
              // alert('Down key');
              curent_active+=1;
              search = false;
            break;
          case 13:
              // alert('Enter key');
              search = false;
            break;
        }

        if(curent_active==li_count){
          curent_active = 0;
        }

        $('#search_activities_ul li').removeClass('active');
        $('#search_activities_ul li').eq(curent_active).addClass('active');
      // }

      if(search) {
        $('#search_activities_ul').hide();
        $('#search_activities_ul').empty();
        $('#search_activities_form input[name=slug]').val('');
        $('#search_activities_form input[name=slug]').attr('disabled',true);

        var text = $(this).val();
        if(text.length > 2) {
          load_activities(text);
        }
      }
    });

    function load_activities(text=''){
      var _token = '{{ csrf_token() }}';
          $.ajax({
            url:"{{ route('ajaxSearchPackages') }}",
            type:"POST",
            headers:{'X-CSRF-TOKEN': _token},
            data: {
              text: text, is_activity: 1
            },
            success:function (response) {
              $('#search_activities_ul').empty();
              if(response.success) {
                if(response.result) {
                  $.each(response.result, function(index,row){
                    var row_li = '<li data-slug="'+row.slug+'">'+row.title+'</li>'
                    $('#search_activities_ul').append(row_li);
                  });
                  $('#search_activities_ul').show();
                }
              } else if(response.message) {
                console.log('false');
              } else {
                console.log('error');
              }
            },complete:function(){

            }
          });
    }

    $(document).on('click','#search_activities_ul li',function(){
      var slug = $(this).attr('data-slug');
      var title = $(this).text();
      $('#search_activities_ul').hide();
      $('#search_activities_ul').empty();
      $('#search_activities').val(title);
      $('#search_activities_form input[name=slug]').val(slug);
      $('#search_activities_form input[name=slug]').attr('disabled',false);
    });

    function validateSearchActivityForm() {
      var search = true;
      $('#search_activities_ul li').each(function(){
        if( $(this).hasClass('active')) {
          search = false;
          $(this).trigger('click');
        }
      });
      return search;
    }

    $(document).click(function(e){
      var clickedElement = $(e.target);
      if(!(clickedElement.closest('.search_activities ul').length > 0 || clickedElement.hasClass('.search_activities ul'))){
        $('.search_activities ul').hide();
      }
    });

  <?php } // if($package_active) { ?>
    $(document).on('click','#search_packages',function(event){
      load_packages();
    });

    $(document).on('keyup','#search_packages',function(event){
      var search = true;
      var li_count = $('#search_activities_ul li').length;
        var curent_active = -1;
        var each_counter = 0;
        $('#search_activities_ul li').each(function(){
          if( $(this).hasClass('active')){
            curent_active = each_counter;
          }
          each_counter++;
        });

        switch (event.keyCode) {
          case 37:
              // alert('Left key');
              curent_active-=1;
              search = false;
            break;
          case 38:
              // alert('Up key');
              curent_active-=1;
              search = false;
            break;
          case 39:
              // alert('Right key');
              curent_active+=1;
              search = false;
            break;
          case 40:
              // alert('Down key');
              curent_active+=1;
              search = false;
            break;
          case 13:
              // alert('Enter key');
              search = false;
            break;
        }

        if(curent_active==li_count){
          curent_active = 0;
        }

        $('#search_activities_ul li').removeClass('active');
        $('#search_activities_ul li').eq(curent_active).addClass('active');

        if(search) {
          $('#search_activities_ul').hide();
          $('#search_activities_ul').empty();
          $('#search_packages_form input[name=slug]').val('');
          $('#search_packages_form input[name=slug]').attr('disabled',true);

          var text = $(this).val();
          if(text.length > 2) {
            load_packages(text);
          }
        }
    });

    function load_packages(text=''){
      var _token = '{{ csrf_token() }}';
          $.ajax({
            url:"{{ route('ajaxSearchPackages') }}",
            type:"POST",
            headers:{'X-CSRF-TOKEN': _token},
            data: {
              text: text
            },
            success:function (response) {
              $('#search_activities_ul').empty();
              if(response.success) {
                if(response.result) {
                  $.each(response.result, function(index,row){
                    var row_li = '<li data-slug="'+row.slug+'">'+row.title+'</li>'
                    $('#search_activities_ul').append(row_li);
                  });
                  $('#search_activities_ul').show();
                }
              } else if(response.message) {
                console.log('false');
              } else {
                console.log('error');
              }
            },complete:function(){

            }
          });
    }

    $(document).on('click','#search_activities_ul li',function(){
      var slug = $(this).attr('data-slug');
      var title = $(this).text();
      $('#search_activities_ul').hide();
      $('#search_activities_ul').empty();
      $('#search_packages').val(title);
      $('#search_packages_form input[name=slug]').val(slug);
      $('#search_packages_form input[name=slug]').attr('disabled',false);
    });

    function validateSearchPackageForm() {
      var search = true;
      $('#search_activities_ul li').each(function(){
        if( $(this).hasClass('active')) {
          search = false;
          $(this).trigger('click');
        }
      });
      return search;
    }

    $(document).click(function(e){
      var clickedElement = $(e.target);
      if(!(clickedElement.closest('.search_packages ul').length > 0 || clickedElement.hasClass('.search_packages ul'))){
        $('.search_packages ul').hide();
      }
    });
  <?php //} ?>
</script>