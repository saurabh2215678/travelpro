<?php $source_types = config('custom.source_types');
$bookings_every_months = config('custom.bookings_per_month');
$total_no_of_employees = config('custom.no_of_employees');
$agency_durations = config('custom.agency_durations');
$traveler_regions = config('custom.traveler_regions');
$bookings_per_month = !empty($user->agentInfo)?$user->agentInfo->bookings_per_month:'';
$source = !empty($user->agentInfo)?$user->agentInfo->source:'';
$agency_age = !empty($user->agentInfo)?$user->agentInfo->agency_age:'';
$no_of_employees = !empty($user->agentInfo)?$user->agentInfo->no_of_employees:'';
$regions = !empty($user->agentInfo)?$user->agentInfo->region:'';
$storage = Storage::disk('public');
$path = 'agent_logo/';
?>
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
                    <div><b>Agent Name: </b></div>
                    <div>{{$user->name}}</div>
                </td>
                <td>
                    <div><b>Email: </b></div>
                    <div>{{$user->email}}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><b>Company Brand </b></div>
                    <div>{{$user->agentInfo?$user->agentInfo->company_brand:''}}</div>
                </td>
                <td>
                    <div><b>Company Name </b></div>
                    <div>{{$user->agentInfo?$user->agentInfo->company_name:''}}</div>
                </td>
                <td>
                    <div><b>Company Owner Name </b></div>
                    <div>{{$user->agentInfo?$user->agentInfo->company_owner_name:''}}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><b>PAN Number</b></div>
                    <div>{{$user->agentInfo?$user->agentInfo->pan_no:''}}</div>
                </td> 
                <td>
                    <div><b>GST Number</b></div>
                    <div>{{$user->agentInfo?$user->agentInfo->gst_no:''}}</div>
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
                    <?php
                 $whatsapp_phone = !empty($user->agentInfo)?$user->agentInfo->whatsapp_phone:'';
                    if($whatsapp_phone) {
                        $whatsapp_country_code = !empty($user->agentInfo)?$user->agentInfo->whatsapp_country_code:'91';
                        $whatsapp_phone = '+'.$whatsapp_country_code.'-'.$whatsapp_phone;
                    }?>
                    <div><b>Whatsapp Number: </b></div>
                    <div>{{$whatsapp_phone}}</div>
                </td>

                <td>
                    <div><b>Bookings Per Month: </b></div>
                    <div>
                         <?php
                        if(!empty($bookings_every_months)) {
                            foreach($bookings_every_months as $key => $val) {
                                if($key == $bookings_per_month) {
                                    echo $val;
                                }
                            }
                        } ?>
                    </div>
                </td>
            </tr>
            <tr>
              <?php /*  <td>
                    <div><b>Sales Team Size </b></div>
                    <div>{{$user->agentInfo?$user->agentInfo->sales_team_size:''}}</div>
                </td>*/ ?>

                 <td>
                    <div><b>Agent Logo </b></div>
                    <div>
                        <?php 
                        $imgSrc = ''; 
                        $imgUrl = ''; 
                        $agent_logo = !empty($user->agentInfo)?$user->agentInfo->agent_logo:'';
                        //prd($agent_logo);
                        if(!empty($agent_logo)){
                            if($storage->exists($path.$agent_logo)){
                                $imgUrl = asset('/storage/'.$path.$agent_logo);
                                if(empty($imgSrc)){
                                    $imgSrc =  $imgUrl;
                                }
                            }
                        } 
                        //<a href="{{$imgUrl}}" target="_blank"><img src="{{$imgSrc}}" style="width:40px;"></a>?>
                        <img src="{{$imgSrc}}" style="width:40px;">
                    </div>
                </td>
                <td>
                    <div><b>Source </b></div>
                    <div> <?php
                        if(!empty($source_types)) {
                            foreach($source_types as $key => $val) {
                                if($key == $source) {
                                    echo $val;
                                }
                            }
                        } ?>        
                    </div>
                </td>
                <td>
                    <div><b>Website</b></div>
                    <div>{{$user->agentInfo?$user->agentInfo->website:''}}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><b> Destinations Sell Most</b></div>
                    <div>{{$user->agentInfo?$user->agentInfo->destinations_sell_most:''}}</div>
                </td>
                <td>
                    <div><b>Agency Age </b></div>
                    <div>
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
                </td>
                <td>
                    <div><b>No of Employees</b></div>
                    <div>
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
                </td>
            </tr>
            <tr>
                <td>
                    <div><b> Region</b></div>
                    <div>
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
                </td>
                <td>
                    <div><b>Company Profile </b></div>
                    <div>{{$user->agentInfo?$user->agentInfo->company_profile:''}}</div>
                </td>
                <td>
                    <div><b>Comment</b></div>
                    <div>{{$user->agentInfo?$user->agentInfo->query:''}}</div>
                </td>
            </tr>
            
            <tr>
                <td>
                    <div><b>Address 1: </b></div>
                    <div>{{$user->address1}}</div>
                </td>
                <td>
                    <div><b>Address 2: </b></div>
                    <div>{{$user->address2}}</div>
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
                <?php /*
                <td>
                    <div><b>Remember Token: </b></div>
                    <div>{{ $user->remember_token }}</div>
                </td>

               <td>
                    <div><b>Verify Token: </b></div>
                    <div>{{$user->verify_token}}</div>
                </td> 
                <td>
                <div><b>Is Verified: </b></div>
                <div>
                    {{$user->is_verified}}
                </div>
                </td>*/ ?>
         </tr>

          <tr>
            <?php /*
                <td>
                    <div><b>One Time Password: </b></div>
                    <div>{{$user->otp}}</div>
                </td> 
                
                <td>
                    <div><b>Email Verified At: </b></div>
                    <div>{{$user->email_verified_at}}</div>
                </td> */ ?>
                <td>
                    <div><b>Zip Code: </b></div>
                    <div>{{$user->zip_code}}</div>
                </td>
                <td>
                    <div><b>Status: </b></div>
                    <div>{{ CustomHelper::getStatusStr($user->status) }}</div>
                </td>
                <td>
                    <div><b>Date Created: </b></div>
                    <div>{{ CustomHelper::DateFormat($user->created_at,'d F Y') }}</div>
                </td>
         </tr>

        <?php /*
        <tr>
                <td>
                    <div><b>Email Verified At: </b></div>
                    <div>{{$user->email_verified_at}}</div>
                </td>

                <td>
                    <div><b>Date Created: </b></div>
                    <div>{{ CustomHelper::DateFormat($user->created_at, 'd/m/Y') }}</div>
                </td>
        </tr> */ ?>
</table>