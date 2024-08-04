@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $page_title }}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css" />
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
    .crop_change { padding-bottom:35px; }
    .user_profile_inner .right_info .btn2{font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
    .profile_info_box .profile_title {
    width: 200px;
}
</style>
@endslot
<?php
$bookings_per_month = !empty($agentDetails->bookings_per_month)?$agentDetails->bookings_per_month:'';
$bookings_per_month = old('bookings_per_month',$bookings_per_month);

$source = !empty($agentDetails->source)?$agentDetails->source:'';
$source = old('source',$source);

$agency_age = !empty($agentDetails->agency_age)?$agentDetails->agency_age:'';
$agency_age = old('agency_age',$agency_age);

$no_of_employees = !empty($agentDetails->no_of_employees)?$agentDetails->no_of_employees:'';
$employees = old('no_of_employees',$no_of_employees);

$regions = !empty($agentDetails->region)?$agentDetails->region:'';
$regions = old('region',$regions);

$whatsapp_country_code = (isset($agentDetails->whatsapp_country_code))?$agentDetails->whatsapp_country_code:91;

$source_types = config('custom.source_types');
$bookings_every_months = config('custom.bookings_per_month');
$total_no_of_employees = config('custom.no_of_employees');
$agency_durations = config('custom.agency_durations');
$traveler_regions = config('custom.traveler_regions');

$country = (isset($c->country))?$c->country:101;
$storage = Storage::disk('public');
$path = 'agent_logo/';
$agent_path = 'agentuser/';
?>
<section>
    <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">Agent Info </h1>
                        </div>
                        <p class="para-lg2">Basic info, for a faster booking experience</p>
                    </div>
                    <div class="right">
                        <a class="btn2 edit_pofile_btn" href="javascript:void(0);"><span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M421.7 220.3l-11.3 11.3-22.6 22.6-205 205c-6.6 6.6-14.8 11.5-23.8 14.1L30.8 511c-8.4 2.5-17.5 .2-23.7-6.1S-1.5 489.7 1 481.2L38.7 353.1c2.6-9 7.5-17.2 14.1-23.8l205-205 22.6-22.6 11.3-11.3 33.9 33.9 62.1 62.1 33.9 33.9zM96 353.9l-9.3 9.3c-.9 .9-1.6 2.1-2 3.4l-25.3 86 86-25.3c1.3-.4 2.5-1.1 3.4-2l9.3-9.3H112c-8.8 0-16-7.2-16-16V353.9zM453.3 19.3l39.4 39.4c25 25 25 65.5 0 90.5l-14.5 14.5-22.6 22.6-11.3 11.3-33.9-33.9-62.1-62.1L314.3 67.7l11.3-11.3 22.6-22.6 14.5-14.5c25-25 65.5-25 90.5 0z"/></svg> Edit </span></a>
                    </div>
                 
                </div>
                <div class="profile_info">
                    <ul class="profile_info_list">
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Company Name</div>
                                <div class="profile_value">{{ $agentDetails->company_name ?? '' }}</div>
                            </div>
                        </li>
                         <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Company Brand Name</div>
                                <div class="profile_value">{{ $agentDetails->company_brand ?? '' }}</div>
                            </div>
                        </li>
                         <li>
                            <div class="profile_info_box">
                                <div class="profile_title">PAN Number</div>
                                <div class="profile_value">{{ $agentDetails->pan_no ?? '' }}</div>
                            </div>
                        </li> 
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">GST Number</div>
                                <div class="profile_value">{{ $agentDetails->gst_no ?? '' }}</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Company Owner Name</div>
                                <div class="profile_value">{{ $agentDetails->company_owner_name ?? '' }}</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Agent Logo</div>
                                <?php $imgSrc = ''; $imgUrl = ''; $agent_logo = $agentDetails->agent_logo ?? '';
                                if(!empty($agent_logo)){
                                        if($storage->exists($path.$agent_logo)){
                                            $imgUrl = asset('/storage/'.$path.$agent_logo);
                                                if(empty($imgSrc)){
                                                    $imgSrc =  $imgUrl;
                                                }
                                        } else {
                                            $imgUrl =  $imgSrc;
                                        }
                                ?>
                                <div class="imgBox">
                                    <!-- <div class="profile_value"><a href="{{$imgUrl}}" target="_blank"><img src="{{$imgSrc}}" style="width:40px;"></a></div> -->
                                    <a class="view_cancel" href="{{$imgUrl}}" target="_blank" title="View agent logo">View <i class="fa fa-external-link"></i></a>
                                    &nbsp;&nbsp;
                                    <a href="javascript:void(0)" data-id="{{$agentDetails->id}}" class="delImg view_cancel" title="Delete agent logo"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </div>
                                <?php } ?>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">PAN Image</div>
                                <?php $imgSrc = ''; $imgUrl = ''; 
                                $pan_image = $agentDetails->pan_image ?? '';
                                if(!empty($pan_image)){
                                        if($storage->exists($agent_path.$pan_image)){
                                            $imgUrl = asset('/storage/'.$agent_path.$pan_image);
                                                if(empty($imgSrc)){
                                                    $imgSrc =  $imgUrl;
                                                }
                                        } else {
                                            $imgUrl =  $imgSrc;
                                        }
                                ?>
                                <div class="imgBox">
                                   <?php /* <div class="profile_value"><a href="{{$imgUrl}}" target="_blank"><img src="{{$imgSrc}}" style="width:40px;"></a></div>
                                    <a href="javascript:void(0)" data-id="{{$agentDetails->id}}" class="delImg">Delete</a> */ ?> 
                                    <a class="view_cancel" href="{{ url('/storage/'.$agent_path.$pan_image) }}" title="View PAN image" target="_blank">View <i class="fa fa-external-link"></i></a>

                                    <?php /*<a href="{{route('user.viewfile',['id' =>$agentDetails->id])}}" target="_blank" class="btn btn-primary" style="width:40px;">View</a> */ ?>
                                    
                                </div>
                                <?php } ?>
                            </div>
                        </li>
                         <li>
                            <div class="profile_info_box">
                                <div class="profile_title">GST Image</div>
                                <?php $imgSrc = ''; $imgUrl = ''; 
                                $gst_image = $agentDetails->gst_image ?? '';
                                if(!empty($gst_image)){
                                        if($storage->exists($agent_path.$gst_image)){
                                            $imgUrl = asset('/storage/'.$agent_path.$gst_image);
                                                if(empty($imgSrc)){
                                                    $imgSrc =  $imgUrl;
                                                }
                                        } else {
                                            $imgUrl =  $imgSrc;
                                        }
                                ?>
                                <div class="imgBox">
                                   <?php /* <div class="profile_value"><a href="{{$imgUrl}}" target="_blank"><img src="{{$imgSrc}}" style="width:40px;"></a></div>
                                    <a href="javascript:void(0)" data-id="{{$agentDetails->id}}" class="delImg">Delete</a>
                                   */ ?>
                                    <a class="view_cancel" href="{{ url('/storage/'.$agent_path.$gst_image) }}" title="View GST image" target="_blank">View <i class="fa fa-external-link"></i></a>
                                </div>
                                <?php } ?>
                            </div>
                        </li>
                        <li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Bookings Per Month</div>
                                <div class="profile_value">
                                <?php
                                if(!empty($bookings_every_months)) {
                                    foreach($bookings_every_months as $key => $val) {
                                        if($key == $bookings_per_month) {
                                            echo $val;
                                        }
                                    }
                                } ?>
                                </div>
                            </div>
                        </li>
                        <?php /*
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Sales Team Size</div>
                                <div class="profile_value">{{ $agentDetails->sales_team_size ?? '' }}</div>
                            </div>
                        </li> */ ?>
                         <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Source</div>
                                <div class="profile_value">
                                    <?php
                                    if(!empty($source_types)) {
                                        foreach($source_types as $key => $val) {
                                            if($key == $source) {
                                                echo $val;
                                            }
                                        }
                                    } ?> 
                                </div>
                            </div>
                        </li>
                          <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Website</div>
                                <div class="profile_value">{{ $agentDetails->website ?? '' }}</div>
                            </div>
                        </li>
                         <li>
                            <?php if(!empty($agentDetails->whatsapp_phone)) { ?>
                            <div class="profile_info_box">
                                <div class="profile_title">Whatsapp Phone</div>
                                <div class="profile_value">+{{$whatsapp_country_code.'-'.$agentDetails->whatsapp_phone??''}}</div>
                            </div>
                        <?php } ?>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Destinations Sell Most</div>
                                <div class="profile_value">{{ $agentDetails->destinations_sell_most ?? '' }}</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Agency Age</div>
                                <div class="profile_value">
                                    <?php
                                    if(!empty($agency_durations)) {
                                        foreach($agency_durations as $key => $val) {
                                            if($key == $agency_age) {
                                                echo $val;
                                            }
                                        }
                                    } 
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">No Of Employees</div>
                                <div class="profile_value">
                                    <?php
                                    if(!empty($total_no_of_employees)) {
                                        foreach($total_no_of_employees as $key => $val) {
                                            if($key == $no_of_employees) {
                                                echo $val;
                                            }
                                        }
                                    } 
                                    ?>
                                </div>
                            </div>
                        </li>
                          <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Region</div>
                                <div class="profile_value">
                                <?php
                                if(!empty($traveler_regions)) {
                                    foreach($traveler_regions as $key => $val) {
                                        if($key == $regions) {
                                            echo $val;
                                        }
                                    }
                                } 
                                ?>
                                </div>
                            </div>
                        </li>
                          <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Company Profile</div>
                                <div class="profile_value">{{ $agentDetails->company_profile ?? '' }}</div>
                            </div>
                        </li>
                          <!-- <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Comment</div>
                                <div class="profile_value">{{ $agentDetails->query ?? '' }}</div>
                            </div>
                        </li> -->
                      
                    </ul>
                    </div>
                </div>               
        </div>
    </div>
</section>
<div class="edit_profile_field_wrap" style="display: none">
<div class="edit_profile_field" >
    {{ Form::open(array('route' => 'user.updateUserDetails','method' => 'post','id'=>'update_profile','enctype'=>'multipart/form-data','class' => '','autocomplete' => 'off')) }}
<div class="edit_profile_inner_box">
    <div class="cross">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>
    </div>

    <div class="edit_profile_inner">
        <div class="form_group">
            <label>Company name<em>*</em></label>
            <input type="text" name="company_name" id="company_name" value="{{ $agentDetails->company_name?? '' }}"  placeholder="" class="form_control">
        </div> 
        <div class="form_group">
            <label>Company brand<em>*</em></label>
            <input type="text" name="company_brand" id="company_brand" value="{{ $agentDetails->company_brand?? '' }}"  placeholder="" class="form_control">
        </div> 
        <div class="form_group">
            <label>Company owner name<em>*</em></label>
            <input type="text" name="company_owner_name" id="company_owner_name" value="{{ $agentDetails->company_owner_name?? '' }}"  placeholder="" class="form_control">
        </div>
        <div class="form_group">
            <label>PAN Number<em>*</em></label>
            <input type="text" name="pan_no" id="pan_no" value="{{ $agentDetails->pan_no?? '' }}"  placeholder="" class="form_control">
        </div> 
        <div class="form_group">
            <label>Upload PAN Card</label>
            <input type="file" name="pan_image" id="pan_image" value="" class="form_control">
        </div> 
         <div class="form_group">
            <label>GST Number</label>
            <input type="text" name="gst_no" id="gst_no" value="{{ $agentDetails->gst_no?? '' }}"  placeholder="" class="form_control">
        </div>
        <div class="form_group">
            <label>Upload GST</label>
            <input type="file" name="gst_image" id="gst_image" value="" class="form_control">
        </div> 
       <?php /* <div class="form_group">
            <label>Bookings per month</label>
            <input type="text" name="bookings_per_month" id="bookings_per_month" value="{{ $agentDetails->bookings_per_month?? '' }}"  placeholder="" class="form_control">
        </div> */ ?> 
        <div class="form_group">
            <label>Bookings per month</label>
            <select class="form_control" name="bookings_per_month">
                <option value="">--Select--</option>
                <?php
                if(!empty($bookings_every_months)) {
                    foreach($bookings_every_months as $key => $val) {
                        $selected = '';
                        if($key == $bookings_per_month) {
                            $selected = 'selected';
                        }
                        ?>
                        <option value="{{$key}}" {{$selected}}>{{$val}} </option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
        <div class="form_group">
            <label>Agent logo</label>
            <input type="file" name="agent_logo" id="agent_logo" value="" class="form_control">
        </div> 
        <?php /* 
        <div class="form_group">
            <label>Sales team size</label>
            <input type="text" name="sales_team_size" id="sales_team_size" value="{{ $agentDetails->sales_team_size?? '' }}"  placeholder="" class="form_control">
        </div> */ ?>
       <?php /* <div class="form_group">
            <label>Source</label>
            <input type="text" name="source" id="source" value="{{ $agentDetails->source?? '' }}"  placeholder="" class="form_control">
        </div> */ ?>
        <div class="form_group">
            <label>Source</label>
            <select class="form_control" name="source">
                <option value="">--Select--</option>
                <?php
                if(!empty($source_types)) {
                    foreach($source_types as $key => $val) {
                        $selected = '';
                        if($key == $source) {
                            $selected = 'selected';
                        }
                        ?>
                        <option value="{{$key}}" {{$selected}}>{{$val}} </option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
        <div class="form_group">
            <label>Website</label>
            <input type="text" name="website" id="website" value="{{ $agentDetails->website?? '' }}"  placeholder="" class="form_control">
        </div> 

        <div class="form_group">
            <label>Whatsapp phone</label>
            <select name="whatsapp_country_code" class="form-select country_code">
              @if($countries)
              @foreach($countries as $c)
              <option value="{{$c->phonecode}}" {{($c->phonecode==$whatsapp_country_code)?'selected':''}} >+{{$c->phonecode}}</option>
              @endforeach
              @endif
          </select>
            <input type="text" name="whatsapp_phone" id="whatsapp_phone" value="{{ $agentDetails->whatsapp_phone?? '' }}"  placeholder="" class="form_control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="12">
        </div> 

        <div class="form_group">
            <label>destinations sell most</label>
            <input type="text" name="destinations_sell_most" id="destinations_sell_most" value="{{ $agentDetails->destinations_sell_most?? '' }}"  placeholder="" class="form_control">
        </div> 
        
       <?php /* <div class="form_group">
            <label>Agency age</label>
            <input type="text" name="agency_age" id="agency_age" value="{{ $agentDetails->agency_age?? '' }}"  placeholder="" class="form_control">
        </div> */ ?>
        <div class="form_group">
            <label>Agency age</label>
            <select class="form_control" name="agency_age">
                <option value="">--Select--</option>
                <?php
                if(!empty($agency_durations)) {
                    foreach($agency_durations as $key => $val) {
                        $selected = '';
                        if($key == $agency_age) {
                            $selected = 'selected';
                        }
                        ?>
                        <option value="{{$key}}" {{$selected}}>{{$val}} </option>
                        <?php
                    }
                }
                ?>  
            </select>
        </div> 
        <?php /* <div class="form_group">
            <label>No of employees</label>
            <input type="text" name="no_of_employees" id="no_of_employees" value="{{ $agentDetails->no_of_employees?? '' }}"  placeholder="" class="form_control">
        </div>  */ ?>
        <div class="form_group">
        <label for="no_of_employees" class="control-label">Number of Employees?</label>
            <select class="form_control" name="no_of_employees">
                <option value="">--Select--</option>
                <?php
                if(!empty($total_no_of_employees)) {
                    foreach($total_no_of_employees as $key => $val) {
                        $selected = '';
                        if($key == $employees) {
                            $selected = 'selected';
                        }
                        ?>
                        <option value="{{$key}}" {{$selected}}>{{$val}} </option>
                        <?php
                    }
                }
                ?>
            </select> 
        </div>
        <?php /*<div class="form_group">
            <label>Region</label>
            <input type="text" name="region" id="region" value="{{ $agentDetails->region?? '' }}"  placeholder="" class="form_control">
        </div> */ ?>
        <div class="form_group">
            <label>Region</label>
            <select class="form_control" name="region">
                <option value="">--Select--</option>
                <?php
                if(!empty($traveler_regions)) {
                    foreach($traveler_regions as $key => $val) {
                        $selected = '';
                        if($key == $regions) {
                            $selected = 'selected';
                        }
                        ?>
                        <option value="{{$key}}" {{$selected}}>{{$val}} </option>
                        <?php
                    }
                }
                ?> 
            </select>
        </div>
        
        <div class="form_group" style="width: 100%;">
            <label>Company profile</label>
            <input type="text" name="company_profile" id="company_profile" value="{{ $agentDetails->company_profile?? '' }}"  placeholder="" class="form_control">
        </div> 
        <!-- <div class="form_group col-md-12">
            <label>Comment</label>
            {{--<input type="text" name="query" id="query" value="{{ $agentDetails->query?? '' }}"  placeholder="" class="form_control">--}}
            <input type="text" name="queries" id="query" value="{{ $agentDetails->query?? '' }}"  placeholder="" class="form_control">
        </div>  -->
        <div class="crop_change">
        <div id="demo-basic" style="height:180px; display:none">
        </div>
          
        </div>
        
    </div>
    <button type="submit" class="btn">Submit</button>
</div>
{{ Form::close() }}
</div>

</div>
@slot('bottomBlock')
<script>

    var basic = $('#demo-basic').croppie({
        viewport: {
            width: 150,
            height: 200
        }
    });

    $('#profile_image').change(function(evt){
        var tgt = evt.target || window.event.srcElement,
        files = tgt.files;
        var fr = new FileReader();
        fr.onload = function () {
            // $('#demo-basic img').attr('src', fr.result);
            $('#demo-basic').show();
            basic.croppie('bind', {
        url: fr.result,
        // points: [77,469,280,739]
    });

            console.log(fr.result)
            //document.getElementById(outImage).src = fr.result;
        }
        fr.readAsDataURL(files[0]);
        // console.log($(this).val())
    })


    
    //on button click
    basic.croppie('result', 'html').then(function(html) {
        // html is div (overflow hidden)
        // with img positioned inside.
    });



    $(document).ready(function(){
       $(".edit_pofile_btn").click(function(){
         $(".edit_profile_field_wrap").fadeIn();
      });
      $(".cross").click(function(){
        $(".edit_profile_field_wrap").fadeOut();
      })
    });

    var maxBirthdayDate = new Date();
    //maxBirthdayDate.setFullYear( maxBirthdayDate.getFullYear() - 18,11,31);

    $( function() {
        $( "#date_of_birth" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            maxDate: maxBirthdayDate,
            yearRange: '1900:2005'
        });
    } );

    $(document).on("submit", "#update_profile", function(e){
        e.preventDefault();

        // var updateProfile = $("form[id=update_profile]");
        // alert(updateProfile);

        var _token = '{{ csrf_token() }}';
        var form_data = $("#update_profile").serialize();
        $.ajax({
            url: "{{ route('user.updateAgentDetails') }}",
            type: 'post',
            dataType: 'json',
            data: new FormData(this),
            contentType: false,
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            processData: false,
            beforeSend: function() {
                $("span").remove();
            },
            complete: function() {

            },
            success: function(json) {
                //console.log(json);
                $('.alert-success, .alert-danger').remove();

                if (json['success']==false) {

                    $.each(json['errors'], function(i, item) {
                        $('#update_profile #'+i).after('<span class="validation_error">'+item+'</span>');
                        $('#update_profile #'+i).css('border','1px solid #ff0000');
                    });

                    $('html, body').animate({
                            scrollTop:$('.modal-content').position().top
                        }, 'slow'
                    );

                    $('#update_profile').before('<div class="alert alert-danger"> ' + json['message'] + '</div>');
                    //$('#update_form')[0].reset();
                    $('.alert-danger').delay(3000).fadeOut();
                }
                if (json['success']==true) {
                    //$('#update_form').before('<div class="alert alert-success"> ' + json['message'] + '</div>');

                    //$('#update_form')[0].reset();
                    $('.alert-success').delay(3000).fadeOut();

                    location.reload();
                }
            }
        });
    });
</script>
<script type="text/javascript">
$("#profile_image").blur(function(){

        photo_check();
    });

    function photo_check(){

    $('#profile_image').bind('change', function() {
    var picsize = (this.files[0].size);

    if (picsize > 2000000){
     
      $('#profile_image').val('');
      alert("Maximize size is 2MB");
      return false;
   
   }
 });
}

$(document).ready(function(){
    $(".delImg").click(function(){
        var conf = confirm('Are you sure to delete this Agent logo?');
        if(conf){
            var currSelector = $(this);
            var id = $(this).data("id");
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route('user.agent_logo_delete') }}",
                type: "POST",
                data: {id:id},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                beforeSend:function(){
                    $(".ajax_msg").html("");
                },
                success: function(resp){
                    if(resp.success){
                        currSelector.parent(".imgBox").remove();
                    }
                    else{
                        $(".ajax_msg").html(resp.msg);
                    }
                }
            });
        }
    });
});
</script>
@endslot

@endcomponent


