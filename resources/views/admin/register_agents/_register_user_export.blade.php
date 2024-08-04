<table>
    <thead>
      
         <tr>
            <!-- <td><b>User ID : </b></td> -->
            <td><b>Name : </b></td>
            <td><b>Email : </b></td>
            <td><b>Phone : </b></td>
            <td><b>Date Of Birth : </b></td>
            <td><b>Gender : </b></td>
            <!-- <td><b>Profile Image: </b></td> -->
            <td><b> Address 1: </b></td>
            <td><b> Address 2: </b></td>
            <td><b> City : </b></td>
            <td><b> State : </b></td>
            <td><b>Country : </b></td>
            <td><b>Zip Code: </b></td>
            <!-- <td><b>Password: </b></td>
            <td><b>Referrer Code: </b></td>
            <td><b>Remember Token: </b></td>
            <td><b>Verify Token: </b></td>
            <td><b>Is Verified: </b></td>
            <td><b>One Time Password: </b></td>
            <td><b>Status: </b></td>
            <td><b>Forgot Token: </b></td> -->
            <!-- <td><b>Email Verified At: </b></td> -->
            <td><b>Registered Date: </b></td>

        </tr>
    </thead>
     <?php   if(!empty($users)){ ?>
    <tbody>
        
        <?php

        //$storage = Storage::disk('public');

        //$img_path = 'products/';
        //$thumb_path = $img_path.'thumb/';


        foreach($users as $user){
            // $id = (isset($user->id))?$user->id:'';
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
            // $password = (isset($user->password))?$user->password:'';
            // $referrer = (isset($user->referrer))?$user->referrer:'';
            // $remember_token = (isset($user->remember_token))?$user->remember_token:'';
            // $verify_token = (isset($user->verify_token))?$user->verify_token:'';
            // $is_verified = (isset($user->is_verified))?$user->is_verified:'';
            // $otp = (isset($user->otp))?$user->otp:'';
            // $status = (isset($user->status))?$user->status:'';
            // $forgot_token = (isset($user->forgot_token))?$user->forgot_token:'';
            // $email_verified_at = (isset($user->email_verified_at))?$user->email_verified_at:'';
            $created_at = (isset($user->created_at))?$user->created_at:'';
            // $created_at = CustomHelper::DateFormat($created_at, 'd/m/Y');

            // $storage = Storage::disk('public');
            // $path = 'users/';
                //$productInventory = $product->productInventory;

                //prd($product->toArray());

                //if(!empty($productInventory)){

                    //foreach($productInventory as $pi){

            ?>


    <tr>
        <!-- <td>{{$user->id}}</td> -->
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
        <td>{{$user->phone}}</td>
        <td>{{$user->dob}}</td>
        <td>{{$user->gender}}</td>
        <!-- <td>{{$profile_image}}</td> -->
        <td>{{$user->address1}}</td>
        <td>{{$user->address2}}</td>
        <td>{{$user->city}}</td>
        <td>{{$user->state}}</td>
        <td>{{(!empty($user->signupCountry))? $user->signupCountry->name:""}}</td>
        <td>{{$user->zip_code}}</td>
        <!-- <td>
        <?php //echo $user->password ; ?>
        </td>
        <td>{{$user->referrer}}</td>
        <td> 
        {{ $user->remember_token }}
        </td>
        <td>{{$user->verify_token}}</td>
        <td>{{$user->is_verified}}</td>
        <td>{{$user->otp}}</td>
        <td>{{ CustomHelper::getStatusStr($user->status) }}</td>
        <td>{{$user->forgot_token}}</td> -->
        <!-- <td>{{$user->email_verified_at}}</td> -->
        <td>{{ CustomHelper::DateFormat($user->created_at, 'd/m/Y') }}</td>
    </tr>

    <?php
                
    }
}
?>
</tbody>
</table>