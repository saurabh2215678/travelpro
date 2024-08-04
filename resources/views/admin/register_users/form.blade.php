@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')

    <link rel="stylesheet" type="text/css" href="{{ url('css/jquery-ui.css') }}"/ >

    <link rel="stylesheet" type="text/css" href="{{ url('datetimepicker/jquery-ui-timepicker-addon.css') }}"/ >

    <link href="{{url('')}}/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />

    @endslot


    <?php

    //pr($blog->toArray());
    // $routeName = CustomHelper::getAdminRouteName();

    $id = (isset($user->id))?$user->id:'';
    $name = (isset($user->name))?$user->name:'';
    $company_name = (isset($user->company_name))?$user->company_name:'';
    $email = (isset($user->email))?$user->email:'';

    // $passwordEnc = (isset($user->password))?$user->password:'';
    // $password = mb_substr($passwordEnc, 0, 7);

    $password = (isset($user->password))? "" : "";
    $phone = (isset($user->phone))?$user->phone:'';
    $country_code = isset($user->country_code) && !empty($user->country_code)?$user->country_code:91;
    $address1 = (isset($user->address1))?$user->address1:'';
    $status = (isset($user->status))?$user->status:1;
    $is_vendor = (isset($user->is_vendor))?$user->is_vendor:'';
    $is_agent = (isset($user->is_agent))?$user->is_agent:'';

   /* $userPermission = (isset($user->userPermission))?$user->userPermission:'';
    $permissionData = '';
    if(!empty($userPermission)){
        $permissionData = json_decode($userPermission->permissions,true);
    }*/
    ?>
<div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
    <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>

<a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a><?php } ?>
    </div>
    </div>

    <div class="centersec agent-admin-form">
        <div class="bgcolor p10"> 

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label required">Name:</label>

                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />

                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label required">Email:</label>

                            <input type="text" id="email" class="form-control" name="email" value="{{ old('email', $email) }}" />

                            @include('snippets.errors_first', ['param' => 'email'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <?php
                        $password_required = 'required';
                        if($id > 0){
                            $password_required = '';
                        }
                        ?>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label {{$password_required}}">Password:</label>

                            <input type="password" id="password" class="form-control" name="password" value="{{ old('password', $password) }}" />

                            @include('snippets.errors_first', ['param' => 'password'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="control-label required">Phone No:</label>
                            <select name="country_code" class="form-select country_code">
                              <?php /*{{$country->emoji}}*/ ?>
                              @if($countries)
                              @foreach($countries as $c)
                              <option value="{{$c->phonecode}}" {{($c->phonecode==$country_code)?'selected':''}} >+{{$c->phonecode}}</option>
                              @endforeach
                              @endif
                          </select>
                            <input type="text" id="phone" class="form-control" name="phone" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  maxlength="12" value="{{ old('phone', $phone) }}" />
                            @include('snippets.errors_first', ['param' => 'phone'])
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('address1') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label">Address:</label>
                            <textarea id="address" class="form-control" name="address1">{{ old('address1', $address1) }}</textarea>

                            @include('snippets.errors_first', ['param' => 'address1'])
                        </div>
                    </div>
                    

                    <div class="clearfix"></div>
                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-2">
                        <label class="control-label">Status:</label>
                        &nbsp;&nbsp;
                        Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                        &nbsp;
                        Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                        @include('snippets.errors_first', ['param' => 'status'])
                    </div>

                    @if(CustomHelper::isAllowedModule('agentuser'))
                    <div class="form-group col-md-1{{$errors->has('is_agent')?' has-error':''}} ">
                        <label class="control-label ">Is Agent:</label>
                        <input type="checkbox" name="is_agent" value="1" <?php echo ($is_agent == '1')?'checked':''; ?> />
                        @include('snippets.errors_first', ['param' => 'is_agent'])
                    </div>
                    @endif
                    <div class="form-group col-md-1{{$errors->has('is_vendor')?' has-error':''}} ">
                        <label class="control-label ">Is Vendor:</label>
                        <input type="checkbox" name="is_vendor" value="1" <?php echo ($is_vendor == '1')?'checked':''; ?> />
                        @include('snippets.errors_first', ['param' => 'is_vendor'])
                    </div>
                </div>
                <div class="form-group">
            <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
            <button type="submit" class="btn btn-success" title="Create this new category"><i class="fa fa-save"></i> Submit</button>
        </div>
            </div>
        
   
</form>       
</div>

@slot('bottomBlock')

<script type="text/javascript" src="{{ url('bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>

<script type="text/javascript">

    $('.sel_domains').multiselect({
        //enableFiltering: true,
        numberDisplayed: 4,
        //enableCaseInsensitiveFiltering: true,
        maxHeight: 200
    });


    $('.permission_tag').click(function(){
        
        // var checked = $(this).ischecked()
        //$(this).parents("li").siblings("li").find("input[value='view']").prop("checked",true);
        var checkedCount = $(this).parents("li").siblings("li").find('.permission_tag:checked').length;
        //alert(checkedCount);
        if($(this).is(":checked") || checkedCount > 0){
            $(this).parents("li").siblings("li").find("input[value='view']").prop("checked",true);
        }
        else{
            $(this).parents("li").siblings("li").find("input[value='view']").prop("checked",false);
        }
    });


</script>

<script type="text/javascript">
    
$(document).ready(function(){

$('.date').datepicker({
    dateFormat: "dd/mm/yy",
    changeMonth: true,
    changeYear: true,
    yearRange:"1950:+0"
});

});
 </script>


@endslot

@endcomponent