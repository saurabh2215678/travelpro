@component(config('custom.theme').'.layouts.main')
@slot('title')
    {{ $page_title }}
@endslot
@slot('headerBlock')
<style type="text/css">
    .theme_footer:before { z-index: -1;  }
    .crop_change { padding-bottom:35px; }
    .user_profile_inner .right_info .btn2{font-size: 13px; padding: 8px 25px 8px;text-transform: none;}
</style>
@endslot
<?php
//prd($userDetails);
$country = (isset($c->country))?$c->country:101;
$country_code = (isset($userDetails->country_code))?$userDetails->country_code:91;
?>
<section>
    <div class="container-fluid">
        <div class="user_profile_inner">
            @include('user.left_sidebar')
            <div class="right_info">
                <div class="top_info">
                    <div class="left">
                        <div class="theme_title">
                            <h1 class="title">Profile </h1>
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
                                <div class="profile_title">Name</div>
                                <div class="profile_value">{{ $userDetails->name ?? ''}}</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Email</div>
                                <div class="profile_value">{{ $userDetails->email ?? '' }}</div>
                            </div>
                        </li>
                        <li>
                            @if($userDetails->phone)
                            <div class="profile_info_box">
                                <div class="profile_title">Phone</div>
                                <div class="profile_value">+{{$country_code.'-'.$userDetails->phone??''}}</div>
                            </div>
                            @endif
                        </li> 
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Address1</div>
                                <div class="profile_value">{{$userDetails->address1??'-'}}</div>
                            </div>
                        </li> 
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Address2</div>
                                <div class="profile_value">{{$userDetails->address2??'-'}}</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">City</div>
                                <div class="profile_value">{{ (!empty($userDetails->city)) ? $userDetails->city : '-'}}</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">State</div>
                                <div class="profile_value">{{ (!empty($userDetails->state)) ? $userDetails->state : '-'}}</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Country</div>
                                <div class="profile_value">{{ (!empty($userDetails->country)) ? CustomHelper::GetCountry($userDetails->country,'name') : '-'}}</div>
                            </div>
                        </li>
                        <li>
                            <div class="profile_info_box">
                                <div class="profile_title">Zipcode</div>
                                <div class="profile_value">{{ (!empty($userDetails->zipcode)) ? $userDetails->zipcode : '-'}}</div>
                            </div>
                        </li>
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
            <label>Name</label>
            <input type="text" name="name" id="name" value="{{ $userDetails->name ?? '' }}"  placeholder="" class="form_control">
        </div> 
        <div class="form_group">
            <label>Email</label>
            <input type="text" name="email" id="email"  placeholder="" value="{{ $userDetails->email ?? ''}}" readonly disabled class="form_control">
        </div>
        <div class="form_group">
            <label>Phone</label>
            <select name="country_code" class="form-select country_code">
              @if($countries)
              @foreach($countries as $c)
              <option value="{{$c->phonecode}}" {{($c->phonecode==$country_code)?'selected':''}} >+{{$c->phonecode}}</option>
              @endforeach
              @endif
          </select>
            <input type="text" name="phone" id="phone" placeholder="" value="{{ $userDetails->phone ?? ''}}"  class="form_control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="12">
        </div>

        <div class="crop_change">
        <div id="demo-basic" style="height:180px; display:none">
        </div>
          
        </div>
        <?php $is_agent =  (auth()->user()) ? auth()->user()->is_agent : '';

        if($is_agent != 1){ 
        ?>
        <?php /* <div class="form_group">
            <label>Date of Birth</label>
            <input type="text" name="dob" id="date_of_birth" placeholder="" value="{{($userDetails->dob)? \Carbon\Carbon::createFromFormat('Y-m-d',$userDetails->dob)->format('d/m/Y') : '' }}"  class="form_control">
        </div>
        <div class="form_group">
            <label>Gender</label>
            <input type="radio" name="gender" value="male" {{ $userDetails->gender == "male" ? 'checked':'' }} class="form_control"> Male
            <input type="radio" name="gender" value="female" {{ $userDetails->gender == "female" ? 'checked':'' }} class="form_control"> Female
            <input type="radio" name="gender" value="other" {{ $userDetails->gender == "other" ? 'checked':'' }} class="form_control"> Other
        </div>
        
           <div class="form_group">
                <label>Profile Photo<em>*</em></label>
                <input type="file" name="profile_image" id="profile_image" class="validation-error"  placeholder="" class="form_control">
                <input type="hidden" name="profile_image_old" id="profile_image_old" value="{{ $userDetails->profile_image }}">
            </div> */ ?>    
        <?php } ?>   
        <div class="form_group">
            <label>Address 1<em>*</em></label>
            <input type="text" name="address1" id="address1" placeholder="" value="{{ $userDetails->address1 }}"  class="form_control">
        </div>
        <div class="form_group">
            <label>Address 2</label>
            <input type="text" name="address2" id="address2" placeholder="" value="{{ $userDetails->address2 }}"  class="form_control">
        </div>        
        <div class="form_group">
            <label>City<em>*</em></label>
            <input type="text" name="city" id="city" placeholder="" value="{{ $userDetails->city }}"  class="form_control">
        </div>
        <div class="form_group">
            <label>State<em>*</em></label>
            <input type="text" name="state" id="state" placeholder="" value="{{ $userDetails->state }}" class="form_control">
        </div>
        <div class="form_group">
            <label>Country<em>*</em></label>
            <select class="form_control" name="country" id="country">
             <option value="">Please Select</option>
             @foreach($countries as $c)
             <option value="{{$c->id}}" <?php echo ($c->id == $userDetails->country) ? "selected" : ""; ?>>{{$c->name}}</option>
             @endforeach
             </select>
        </div>
        <div class="form_group">
            <label>Zipcode<em>*</em></label>
            <input type="text" name="zipcode" id="zipcode" placeholder="" value="{{ $userDetails->zipcode }}"  class="form_control">
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

 /*   $('#profile_image').change(function(evt){
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
*/

    
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
            url: "{{ route('user.updateUserDetails') }}",
            type: 'post',
            dataType: 'json',
            data: new FormData(this),
            contentType: false,
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            processData: false,
            beforeSend: function() {
                $(".edit_profile_field span").remove();
            },
            complete: function() {

            },
            success: function(json) {
                //console.log(json);
                $('.alert-success, .alert-danger').remove();

                if (json['success']==false) {

                    $.each(json['errors'], function(i, item) {
                        $('#update_profile #'+i).after('<span class="validation_error">'+item+'</span>');
                        // $('#update_profile #'+i).css('border','1px solid #ff0000');
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
</script>
@endslot

@endcomponent


