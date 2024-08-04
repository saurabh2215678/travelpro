<style>
    .top_title_admin.fancybox-content {
    width: 800px;
    height: 500px;
}
</style>
<?php
//prd($user);
$id = (isset($user->id))?$user->id:'';
$name = (isset($user->name))?$user->name:'';
$phone = (isset($user->phone))?$user->phone:'';
$email = (isset($user->email))?$user->email:'';
$phone = (isset($user->phone))?$user->phone:'';
$gender = (isset($user->gender))?$user->gender:'';
$dob = (isset($user->dob))?$user->dob:'';
$profile_image = (isset($user->profile_image))?$user->profile_image:'';
$address1 = (isset($user->address1))?$user->address1:'';
$address2 = (isset($user->address2))?$user->address2:'';
$city = (isset($user->city))?$user->city:'';
$state = (isset($user->state))?$user->state:'';
$country = (isset($user->country))?$user->country:'';
$zip_code = (isset($user->zip_code))?$user->zip_code:'';
$password = (isset($user->password))?$user->password:'';
$referrer = (isset($user->referrer))?$user->referrer:'';
$remember_token = (isset($user->remember_token))?$user->remember_token:'';
$verify_token = (isset($user->verify_token))?$user->verify_token:'';
$is_verified = (isset($user->is_verified))?$user->is_verified:'';
$otp = (isset($user->otp))?$user->otp:'';
$status = (isset($user->status))?$user->status:'';
$forgot_token = (isset($user->forgot_token))?$user->forgot_token:'';
$email_verified_at = (isset($user->email_verified_at))?$user->email_verified_at:'';
$created_at = (isset($user->created_at))?$user->created_at:'';
// $created_at = CustomHelper::DateFormat($created_at, 'd F Y');

$storage = Storage::disk('public');
$path = 'users/';

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/register-users';
}
//prd($user);
?>

<div class="top_title_admin">
<div class="title">
<h2>{{ $page_heading }}</h2>
</div>

<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="ajax_msg"></div>
<table border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td width="806" valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">

              <tr>
                <td>
                    <div><b>User ID: </b></div>
                    <div>{{$user->id}}</div>
                </td>
                <td>
                    <div><b>Name: </b></div>
                    <div>{{$user->name}}</div>
                </td>
                <td>
                    <div><b>Email: </b></div>
                    <div>{{$user->email}}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <?php
                    if($user->phone) {
                        $country_code = $user->country_code ?? '91';
                        $phone = '+'.$country_code.'-'.$user->phone;
                    }?>
                    <div><b>Phone: </b></div>
                    <div>{{$phone}}</div>
                </td>
                <td>
                    <div><b>Gender: </b></div>
                    <div>
                        <?php if(is_numeric($user->gender)){
                         if ($user->gender == 1){
                        echo "Male";
                    }elseif($user->gender == 2){
                        echo "Female";
                    }else{
                        echo "others";
                    }
                }else{
                    echo $user->gender;
                }?>
                    </div>
                </td>
                <td>
                    <div><b>Date Of Birth: </b></div>
                    <div>{{$user->dob}}</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div><b>Profile Image: </b></div>
                    <div>
                        <?php
                    if(!empty($profile_image)){
                        if($storage->exists($path.$profile_image)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$profile_image) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?>
                </div>
                </td>
                <td>
                    <div><b>Address 1: </b></div>
                    <div>{{$user->address1}}</div>
                </td>

                <td>
                    <div><b>Address 2: </b></div>
                    <div>
                        {{$user->address2}}
                </div>
                </td>
            </tr>

            <tr>
                
                <td>
                    <div><b>City: </b></div>
                    <div>{{$user->city}}</div>
                </td>

                <td>
                    <div><b>State: </b></div>
                    <div>{{$user->state}}</div>
                </td>

                <td>
                    <div><b>Country: </b></div>
                    <div>
                       {{(!empty($user->signupCountry))? $user->signupCountry->name:""}}
                    </div>
                </td>
         </tr>

         <tr>
                
                <td>
                    <div><b>Zip Code: </b></div>
                    <div>{{$user->zipcode}}</div>
                </td>

                <td style="display:none;">
                    <div><b>Password: </b></div>
                    <div><?php echo $user->password ; ?></div>
                </td>

                <td>
                    <div><b>Referrer Code: </b></div>
                    <div>
                       {{$user->referrer}}
                </div>
                </td>

                <td>
                    <div><b>Remember Token: </b></div>
                    <div>{{ $user->remember_token }}</div>
                </td>
         </tr>

         <tr>
                
               

                <td style="display:none;">
                    <div><b>Verify Token: </b></div>
                    <div>{{$user->verify_token}}</div>
                </td>

                <td>
                    <div><b>Is Verified: </b></div>
                    <div>
                      {{$user->is_verified}}
                </div>
                </td>

                <td>
                    <div><b>One Time Password: </b></div>
                    <div>{{$user->otp}}</div>
                </td>

                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($user->status) }}</div>
                </td>
         </tr>

          <tr>
                <td style="display:none;">
                    <div><b>Forgot Token: </b></div>
                    <div>
                      {{$forgot_token}}
                </div>
                </td>
         </tr>

         <tr>
                <td>
                    <div><b>Email Verified At: </b></div>
                    <div>{{$email_verified_at}}</div>
                </td>

                <td>
                    <div><b>Date Created: </b></div>
                    <div>{{ CustomHelper::DateFormat($user->created_at, 'd/m/Y') }}</div>
                </td>
         </tr>
</table>
</div>
</div>