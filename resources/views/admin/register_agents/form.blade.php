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
$routeName = CustomHelper::getAdminRouteName();
$id = (isset($user->id))?$user->id:'';
$name = (isset($user->name))?$user->name:'';
$company_name = (isset($user->agentInfo))?$user->agentInfo->company_name:'';
$company_brand = (isset($user->agentInfo))?$user->agentInfo->company_brand:'';
$company_owner_name = (isset($user->agentInfo))?$user->agentInfo->company_owner_name:'';
$bookings_per_month = (isset($user->agentInfo))?$user->agentInfo->bookings_per_month:'';
$bookings_per_month = old('bookings_per_month',$bookings_per_month);

$agency_age = (isset($user->agentInfo))?$user->agentInfo->agency_age:'';
$agency_age = old('agency_age',$agency_age);

$employees = (isset($user->agentInfo))?$user->agentInfo->no_of_employees:'';
$employees = old('no_of_employees',$employees);

$website = (isset($user->agentInfo))?$user->agentInfo->website:'';

$source = (isset($user->agentInfo))?$user->agentInfo->source:'';
$source = old('source',$source);

$company_profile = (isset($user->agentInfo))?$user->agentInfo->company_profile:'';
$destinations_sell_most = (isset($user->agentInfo))?$user->agentInfo->destinations_sell_most:'';
$region = (isset($user->agentInfo))?$user->agentInfo->region:'';
$region = old('region',$region);
$whatsapp_phone = (isset($user->agentInfo))?$user->agentInfo->whatsapp_phone:'';
$whatsapp_country_code = (isset($user->agentInfo))?$user->agentInfo->whatsapp_country_code:91;
$whatsapp_country_code = old('whatsapp_country_code',$whatsapp_country_code);
$pan_no = (isset($user->agentInfo))?$user->agentInfo->pan_no:'';
$gst_no = (isset($user->agentInfo))?$user->agentInfo->gst_no:'';
$pan_image = (isset($user->agentInfo))?$user->agentInfo->pan_image:'';
$gst_image = (isset($user->agentInfo))?$user->agentInfo->gst_image:'';
$agent_logo = (isset($user->agentInfo))?$user->agentInfo->agent_logo:'';

$storage = Storage::disk('public');

$path = 'agentuser/';
$old_image = $pan_image;
$gst_old_image = $gst_image;
$image_req = '';

$agent_logo_path = 'agent_logo/';
$old_agent_logo = $agent_logo;

$email = (isset($user->email))?$user->email:'';

    // $passwordEnc = (isset($user->password))?$user->password:'';
    // $password = mb_substr($passwordEnc, 0, 7);

$password = (isset($user->password))? "" : "";
$phone = (isset($user->phone))?$user->phone:'';
$country_code = isset($user->country_code) && !empty($user->country_code)?$user->country_code:91;
$country_code = old('country_code',$country_code);
$address1 = (isset($user->address1))?$user->address1:'';
$status = (isset($user->status))?$user->status:1;
$group_id = (isset($user->group_id))?$user->group_id:'';
// $is_vendor = (isset($user->is_vendor))?$user->is_vendor:'';
// $is_vendor = old('is_vendor',$is_vendor);
$is_agent = (isset($user->is_agent))?$user->is_agent:1;
$is_agent = old('is_agent',$is_agent);
$source_types = config('custom.source_types');
$bookings_every_months = config('custom.bookings_per_month');
$total_no_of_employees = config('custom.no_of_employees');
$agency_durations = config('custom.agency_durations');
$traveler_regions = config('custom.traveler_regions');
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
                        <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label">Company Registered Name:</label>
                            <input type="text" id="company_name" class="form-control" name="company_name" value="{{ old('company_name', $company_name) }}" />
                            @include('snippets.errors_first', ['param' => 'company_name'])
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('company_brand') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label">Company Brand/Trade Name:</label>
                            <input type="text" id="company_brand" class="form-control" name="company_brand" value="{{ old('company_brand', $company_brand) }}" />
                            @include('snippets.errors_first', ['param' => 'company_brand'])
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('pan_no') ? ' has-error' : '' }}">
                            <label for="pan_no" class="control-label">PAN Number:</label>
                            <input type="text" id="pan_no" class="form-control" name="pan_no" value="{{ old('pan_no', $pan_no) }}" />
                            @include('snippets.errors_first', ['param' => 'pan_no'])
                        </div>
                    </div> 
                    <?php
                    $image_required = $image_req;
                    if($id > 0){
                        $image_required = '';
                    }
                    ?>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('pan_image') ? ' has-error' : '' }}">
                            <label for="pan_image" class="control-label">Upload PAN Card:</label>
                            <input type="file" id="pan_image" name="pan_image"/>
                            @include('snippets.errors_first', ['param' => 'pan_image'])
                        </div>
                        <?php
                        if(!empty($pan_image)){
                             //prd($path.$pan_image);
                            if($storage->exists($path.$pan_image))
                            {
                                ?>
                                <div class="image_box">
                                   <?php /* <img src="{{ url('storage/'.$path.$pan_image) }}" style="width: 100px;"><br>
                                   <a href="javascript:void(0)" data-id="{{ $id }}" data='pan_image' class="delImg">Delete</a> */ ?>
                                   <a href="{{ url('/storage/'.$path.$pan_image) }}" target="_blank" class="btn btn-primary">View</a>
                                   <a href="javascript:void(0)" data-id="{{ $id }}" data='pan_image' class="delImg">Delete</a>

                               </div>
                               <?php        
                           }
                           ?>
                           <?php
                       }
                       ?>
                       <input type="hidden" name="old_image" value="{{ $old_image }}">
                   </div>
                   <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('gst_no') ? ' has-error' : '' }}">
                            <label for="gst_no" class="control-label">GST Number:</label>
                            <input type="text" id="gst_no" class="form-control" name="gst_no" value="{{ old('gst_no', $gst_no) }}" />
                            @include('snippets.errors_first', ['param' => 'gst_no'])
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="form-group{{ $errors->has('gst_image') ? ' has-error' : '' }}">
                            <label for="gst_image" class="control-label">Upload GST:</label>
                            <input type="file" id="gst_image" name="gst_image"/>
                            @include('snippets.errors_first', ['param' => 'gst_image'])
                        </div>
                        <?php
                        if(!empty($gst_image)){
                            if($storage->exists($path.$gst_image))
                            {
                                ?>
                                <div class="image_box">
                                   <?php /* <img src="{{ url('storage/'.$path.$gst_image) }}" style="width: 100px;"><br>
                                   <a href="javascript:void(0)" data-id="{{ $id }}" data='gst_image' class="delImg">Delete</a> */ ?>
                                   <a href="{{ url('/storage/'.$path.$gst_image) }}" target="_blank" class="btn btn-primary">View</a>
                                   <a href="javascript:void(0)" data-id="{{ $id }}" data='gst_image' class="delImg">Delete</a>

                               </div>
                               <?php        
                           }
                           ?>
                           <?php
                       }
                       ?>
                       <input type="hidden" name="gst_old_image" value="{{ $gst_old_image }}">
                   </div>
                   <div class="col-md-3">
                    <div class="form-group{{ $errors->has('agent_logo') ? ' has-error' : '' }}">
                        <label for="agent_logo" class="control-label">Agent Logo:</label>
                        <input type="file" id="agent_logo" name="agent_logo"/>
                        @include('snippets.errors_first', ['param' => 'agent_logo'])
                    </div>
                    <?php
                    if(!empty($agent_logo)){
                        if($storage->exists($agent_logo_path.$agent_logo))
                        {
                            ?>
                            <div class="imgBox">
                                    <!-- <img src="{{ url('storage/'.$agent_logo_path.$agent_logo) }}" style="width: 100px;"><br> -->
                                    <a href="{{ url('/storage/'.$agent_logo_path.$agent_logo) }}" target="_blank" class="btn btn-primary">View</a>
                                   <a href="javascript:void(0)" data-id="{{ $id }}" data='agent_logo' class="delLogo">Delete</a>
                               </div>
                               <?php        
                           }
                           ?>
                           <?php
                       }
                       ?>
                       <input type="hidden" name="old_agent_logo" value="{{ $old_agent_logo }}">
                   </div>
                   <div class="clearfix"></div>
                   <div class="col-md-3">
                    <div class="form-group{{ $errors->has('company_owner_name') ? ' has-error' : '' }}">
                        <label for="link_name" class="control-label">Company Owner Name:</label>
                        <input type="text" id="company_owner_name" class="form-control" name="company_owner_name" value="{{ old('company_owner_name', $company_owner_name) }}" />
                        @include('snippets.errors_first', ['param' => 'company_owner_name'])
                    </div>
                </div>
                    <div class="form-group col-md-3{{ $errors->has('bookings_per_month') ? ' has-error' : '' }}">
                        <label for="bookings_per_month" class="control-label">Bookings Per Month</label>
                        <select class="form-control" name="bookings_per_month">
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
                        @include('snippets.errors_first', ['param' => 'bookings_per_month'])
                    </div>
                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('no_of_employees') ? ' has-error' : '' }}">
                        <label for="no_of_employees" class="control-label">Number of Employees?</label>
                        <select class="form-control" name="no_of_employees">
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
                        @include('snippets.errors_first', ['param' => 'no_of_employees'])
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('agency_age') ? ' has-error' : '' }}">
                        <label for="link_name" class="control-label">How old is you agency?</label>
                        <select class="form-control" name="agency_age">
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
                        @include('snippets.errors_first', ['param' => 'agency_age'])
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
                        <label for="link_name" class="control-label">Website:</label>
                        <input type="text" id="website" class="form-control" name="website" value="{{ old('website', $website) }}" />
                        @include('snippets.errors_first', ['param' => 'website'])
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('destinations_sell_most') ? ' has-error' : '' }}">
                        <label for="link_name" class="control-label">Destinations you sell the most:</label>
                        <input type="text" id="destinations_sell_most" class="form-control" name="destinations_sell_most" value="{{ old('destinations_sell_most', $destinations_sell_most) }}" />
                        @include('snippets.errors_first', ['param' => 'destinations_sell_most'])
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('whatsapp_phone') ? ' has-error' : '' }}">
                        <label for="whatsapp_phone" class="control-label">Whatsapp Number</label>
                        <select name="whatsapp_country_code" class="form-select country_code">
                          <?php /*{{$country->emoji}}*/ ?>
                          @if($countries)
                          @foreach($countries as $country)
                          <option value="{{$country->phonecode}}" {{($country->phonecode==$whatsapp_country_code)?'selected':''}} >+{{$country->phonecode}}</option>
                          @endforeach
                          @endif
                      </select>
                      <input type="text" id="phone" class="form-control" name="whatsapp_phone" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  maxlength="12" value="{{ old('whatsapp_phone', $whatsapp_phone) }}" />
                      @include('snippets.errors_first', ['param' => 'whatsapp_phone'])
                  </div>
              </div>
              <div class="col-md-3">
                <div class="form-group{{ $errors->has('region') ? ' has-error' : '' }}">
                    <label for="region" class="control-label">Your current travelers are from which region? (for international user)</label>
                    <select class="form-control" name="region">
                        <option value="">--Select--</option>
                          <?php
                        if(!empty($traveler_regions)) {
                            foreach($traveler_regions as $key => $val) {
                                $selected = '';
                                if($key == $region) {
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="{{$key}}" {{$selected}}>{{$val}} </option>
                                <?php
                            }
                        }
                        ?> 
                    </select>
                    @include('snippets.errors_first', ['param' => 'region'])
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
                <div class="form-group{{ $errors->has('source') ? ' has-error' : '' }}">
                    <label for="source" class="control-label">Where did you hear about us?</label>
                    <select class="form-control" name="source">
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
                    @include('snippets.errors_first', ['param' => 'source'])
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="link_name" class="control-label required">Agent Name:</label>
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
                  @include('snippets.errors_first',['param' => 'phone'])
              </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-6">
            <?php
            $password_required = 'required';
            if($id > 0){
                $password_required = '';
            }
            ?>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="link_name" class="control-label {{$password_required}}">Password:</label>
                <input type="password" id="password" class="form-control" name="password" autocomplete="new-password" value="{{ old('password', $password) }}" />
                @include('snippets.errors_first', ['param' => 'password'])
            </div>
        </div>
        <div class="form-group col-md-6 {{$errors->has('group_id')?' has-error':''}}">
            <label for="group_id" class="control-label">Agent Group:</label>
            <select class="form-control select2" name="group_id" id="group_id">
                <?php
                $group_id = old('group_id',$group_id);
                ?>
                <option value="">Select</option>
                <?php
                foreach ($groups as $group)
                {
                    $selected = '';
                    if($group->id == $group_id){
                        $selected = 'selected';
                    }
                    ?>
                    <option value="{{$group->id}}" {{$selected}}>{{$group->name}}</option>
                <?php } ?>
            </select>
            @include('snippets.errors_first', ['param' => 'group_id'])
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('company_profile') ? ' has-error' : '' }}">
                <label for="link_name" class="control-label">Company Profile (Specialization, year of registration, company address, annual revenue):</label>
                <input type="text" id="company_profile" class="form-control" name="company_profile" value="{{ old('company_profile', $company_profile) }}" />
                @include('snippets.errors_first', ['param' => 'company_profile'])
            </div>
        </div>
       <?php /* <div class="col-md-6">
                <label for="link_name" class="control-label">Address:</label>
                <textarea id="address" class="form-control" name="address1">{{ old('address1', $address1) }}</textarea>
                @include('snippets.errors_first', ['param' => 'address1'])
        </div> */ ?>
        <div class="clearfix"></div>
        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-3">
            <label class="control-label">Status:</label>
            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
            &nbsp;
            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >
            @include('snippets.errors_first', ['param' => 'status'])
        </div>
        <div class="form-group col-md-1{{$errors->has('is_agent')?' has-error':''}} " style="display:none;">
            <label class="control-label">Is Agent:</label>
            <input type="checkbox" name="is_agent" value="1" <?php echo ($is_agent == '1')?'checked':''; ?> />
            @include('snippets.errors_first', ['param' => 'is_agent'])
        </div>
       
    </div>
    <div class="form-group">
        <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
        <button type="submit" class="btn btn-success" title="Create this new category"><i class="fa fa-save"></i> Submit</button>
    </div>
</div>
</div>

</form>       
</div>

@slot('bottomBlock')

<script type="text/javascript" src="{{ url('bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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


    $(".delImg").click(function(){
        var conf = confirm('Are you sure to delete this Image?');
        if(conf){
            var currSelector = $(this);
            //var id = $(this).data("id");
            var image_id = $(this).attr('data-id');
            var type = $(this).attr('data');
            //alert(id);
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($routeName.'.register-agents.ajax_delete_image') }}",
                type: "POST",
                data: {image_id,type},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                beforeSend:function(){
                    $(".ajax_msg").html("");
                },
                success: function(resp){
                    if(resp.success){
                        currSelector.parent(".image_box").remove();
                    }
                }
            });
        }
    });
    $(".delLogo").click(function(){
        var conf = confirm('Are you sure to delete this Agent logo?');
        if(conf){
            var currSelector = $(this);
            var id = $(this).data("id");
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($routeName.'.register-agents.agent_logo_delete') }}",
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