@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<!-- <link rel="stylesheet" type="text/css" href="{{ url('css/jquery-ui.css') }}"/ >
<link rel="stylesheet" type="text/css" href="{{ url('datetimepicker/jquery-ui-timepicker-addon.css') }}"/ >
<link href="{{url('')}}/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet" type="text/css" /> -->

@endslot

<?php
    //pr($blog->toArray());
$routeName = CustomHelper::getAdminRouteName();
$id = (isset($user->id))?$user->id:'';
$name = (isset($user->name))?$user->name:'';
$vendor_company_name = (isset($user->vendor_company_name))?$user->vendor_company_name:'';
$email = (isset($user->email))?$user->email:'';
$role_id = (isset($user->role_id))?$user->role_id:'';
$role_id = old('role_id',$role_id);
$password = (isset($user->password))?$user->password:'';
$phone = (isset($user->phone))?$user->phone:'';
$address = (isset($user->address))?$user->address:'';
$status = (isset($user->status))?$user->status:1;
$vendor_logo = (isset($user->vendor_logo))?$user->vendor_logo:'';

$logo_path = 'vendor_logo/';
$old_vendor_logo = $logo_path;
$storage = Storage::disk('public');

/*$userPermission = (isset($user->userPermission))?$user->userPermission:'';
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

<div class="centersec">
    <div class="bgcolor p10"> 

        @include('snippets.errors')
        @include('snippets.flash')

        <div class="ajax_msg"></div>
        <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="row">
                @if($segment == 'vendors')
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('vendor_company_name') ? ' has-error' : '' }}">
                        <label for="link_name" class="control-label">Company Name:</label>

                        <input type="text" id="vendor_company_name" class="form-control" name="vendor_company_name" value="{{ old('vendor_company_name', $vendor_company_name) }}" />

                        @include('snippets.errors_first', ['param' => 'vendor_company_name'])
                    </div>
                </div>
                @endif
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="link_name" class="control-label required">Name:</label>

                        <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />

                        @include('snippets.errors_first', ['param' => 'name'])
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="link_name" class="control-label required">Email:</label>

                        <input type="text" id="email" class="form-control" name="email" value="{{ old('email', $email) }}" />

                        @include('snippets.errors_first', ['param' => 'email'])
                    </div>
                </div>

                <div class="col-md-6">
                    <?php
                    $password_required = 'required';
                    if($id > 0){
                        $password_required = '';
                    }
                    ?>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="link_name" class="control-label {{$password_required}}">Password:</label>

                        <input type="password" id="password" class="form-control" name="password" value="" />

                        @include('snippets.errors_first', ['param' => 'password'])
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                        <label for="phone" class="control-label required">Phone No:</label>
                        <input type="text" id="phone" class="form-control" name="phone" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  maxlength="10" value="{{ old('phone', $phone) }}" />
                        @include('snippets.errors_first', ['param' => 'phone'])
                    </div>
                </div>

                <?php if($segment != 'vendors'){ ?>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('role_id') ? ' has-error' : '' }}">
                            <label for="role_id" class="control-label required">Role:</label>
                            <select class="form-control" name="role_id">
                                <option value="">Select</option>   
                                <?php foreach($roles as $key => $value) { ?>
                                    <option value="{{$value->id}}" <?php if($role_id == $value->id){ echo "selected"; } ?>>{{$value->name}}</option>
                                <?php } ?>
                            </select>
                            @include('snippets.errors_first', ['param' => 'role_id'])
                        </div>
                    </div>
                <?php } ?>

                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                        <label for="link_name" class="control-label">Address:</label>
                        <textarea id="address" class="form-control" name="address">{{ old('address', $address) }}</textarea>


                        @include('snippets.errors_first', ['param' => 'address'])
                    </div>
                </div>

                @if($segment == 'vendors')
                 <div class="col-md-3">
                    <div class="form-group{{ $errors->has('vendor_logo') ? ' has-error' : '' }}">
                        <label for="vendor_logo" class="control-label">Vendor Logo:</label>
                        <input type="file" id="vendor_logo" name="vendor_logo"/>
                        @include('snippets.errors_first', ['param' => 'vendor_logo'])
                    </div>
                    <?php
                    if(!empty($vendor_logo)){
                        if($storage->exists($logo_path.$vendor_logo))
                        {
                            ?>
                            <div class="imgBox">
                                     <img src="{{ url('storage/'.$logo_path.$vendor_logo) }}" style="width: 100px;"><br> 
                                    <!-- <a href="{{ url('/storage/'.$logo_path.$vendor_logo) }}" target="_blank" class="btn btn-primary">View</a> -->
                                   <a href="javascript:void(0)" data-id="{{ $id }}" data='vendor_logo' class="delLogo">Delete</a>
                               </div>
                               <?php        
                           }
                           ?>
                           <?php
                       }
                       ?>
                     <!--  <input type="hidden" name="old_vendor_logo" value="{{ $old_vendor_logo }}"> -->
                   </div>
                   @endif

                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                    <label class="control-label">Status:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                    &nbsp;
                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                    @include('snippets.errors_first', ['param' => 'status'])
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                        <button type="submit" class="btn btn-success" title="Create this new category"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="bgcolor mt50">
            <!-- <h2 style="margin-bottom: 0">User Permission</h2> -->
       <?php /*  <div class="row">
           
            //pr($permissions);
            $permission_params = [];
            $permission_params['modules'] = $modules;
            $permission_params['permissionData'] = $permissionData;
            @include('admin.admins._permission', $permission_params)

            </div> */ ?>
        </div>
    </form>       
</div>

@slot('bottomBlock')
<!-- <script type="text/javascript" src="{{ url('bootstrap-multiselect/bootstrap-multiselect.js') }}"></script> -->
<script type="text/javascript">

    /*$('.sel_domains').multiselect({
        //enableFiltering: true,
        numberDisplayed: 4,
        //enableCaseInsensitiveFiltering: true,
        maxHeight: 200
    });*/
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

    $(".delLogo").click(function(){
        var conf = confirm('Are you sure to delete this Vendor logo?');
        if(conf){
            var currSelector = $(this);
            var id = $(this).data("id");
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($routeName.'.vendors.vendor_logo_delete') }}",
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
</script>
@endslot
@endcomponent