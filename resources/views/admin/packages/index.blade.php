@component('admin.layouts.main')

    @slot('title')
        Admin - Manage {{ucfirst($segment)}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
    <!-- <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ > -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
    <style>
       /* .select2-container {height: 32px; }*/
    .bootstrap-tagsinput { display: block; }
    .admin_input1 {margin-bottom: 5px;}
    .form-inline .form-control {font-size: 13px;}
    .activity_search .select2-container {
    z-index: 99;
}
    </style>
    @endslot

    <?php 
    $back_url=CustomHelper::BackUrl(); 
    $routeName = CustomHelper::getAdminRouteName();
    $is_activity = (request()->has('is_activity'))?request()->is_activity:'';
    $old_title = (request()->has('title'))?request()->title:'';
    $from_package_duration_days = (request()->has('from_package_duration_days'))?request()->from_package_duration_days:'';
    $to_package_duration_days = (request()->has('to_package_duration_days'))?request()->to_package_duration_days:'';

    $tour_type = (request()->has('tour_type'))?request()->tour_type:'';
    $tour_type = old('tour_type', $tour_type);

    $vendor_id = (request()->has('vendor_id'))?request()->vendor_id:'';
    $vendor_id = old('vendor_id', $vendor_id);

    $package_experts = (!empty($query) && !($query->packageExperts->isEmpty())) ? $query->packageExperts : "";
    //$package_experts = (request()->has('package_experts'))?request()->package_experts:'';
    $package_experts = old('package_experts', $package_experts);

    $experts = [];
    if(!empty($package_experts)){
        $experts = [];
        foreach ($package_experts as  $expert) {
            $experts[] = $expert->id;
        }
    }

    $experts = (request()->has('experts'))?request()->experts:'';
    $experts = old('experts', $experts);

    $package_themes = (request()->has('package_themes'))?request()->package_themes:'';
    $package_themes = old('package_themes', $package_themes);

    $package_type = (request()->has('package_type'))?request()->package_type:'';
    $package_type = old('package_type', $package_type);

    $price_category = (request()->has('price_category'))?request()->price_category:'';
    $price_category = old('price_category', $price_category);

    $package_inclusions = (request()->has('package_inclusions'))?request()->package_inclusions:[];
    $package_inclusions = old('package_inclusions', $package_inclusions);

    $old_status = (request()->has('status'))?request()->status:1; 
    $featured = (request()->has('featured'))?request()->featured:''; 
    $popular = (request()->has('popular'))?request()->popular:''; 
    $destination_id = (request()->has('destination'))?request()->destination:'';
    $sub_destination_id = (request()->has('sub_destination'))?request()->sub_destination:'';
    
    $checkbox = (request()->has('checkbox'))?request()->checkbox:[];
    if(!empty($checkbox)) {
        if(!is_array($checkbox)) {
            $checkbox = explode(',', $checkbox);
        }
    }
    $checkbox = old('checkbox',$checkbox);

    $user = auth()->user();
    $is_vendor = $user?$user->is_vendor:'';

    $storage = Storage::disk('public');
    $path = 'packages/';
    
    ?>
<!-- Start Search box section     -->
<div class="top_title_admin">
        <div class="title">
        <h2>Manage {{ucfirst($segment)}} ({{$packages->total()}})</h2>
    </div>
        <div class="add_button">
      <?php if($segment == 'activity'){ ?>
        @if(CustomHelper::checkPermission('activity','add'))
            <a href="{{ route($routeName.'.activity.add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Activity</a>
            @endif
        <?php }else{ ?>
            @if(CustomHelper::checkPermission('packages','add'))
            <a href="{{ route($routeName.'.packages.add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Package</a>
            @endif
        <?php } ?>
</div>

</div>

<div class="centersec">
    <div class="bgcolor">
        <div class="table-responsive">
            <form class="form-inline activity_search" method="GET">
                <div class="col-md-3">
                    <label>{{($segment == 'activity')?'Activity':'Packages'}} Title </label><br/>
                    <input type="text" name="title" class="form-control admin_input1" value="{{$old_title}}">
                </div>
                <?php if($segment == 'packages'){ ?>
                <div class="col-md-3">
                    <label>From Days </label><br/>
                    <select class="form-control" name="from_package_duration_days" style="width:100%">
                        <option value="">Select From Days</option>
                        <?php for($i=1;$i<=60;$i++) { ?>
                        <option value="{{$i}}" <?php echo ($i == old('from_package_duration_days',$from_package_duration_days)) ? "selected":"" ?> > {{$i}}</option>  
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>To Days </label><br/>
                    <select class="form-control" name="to_package_duration_days" style="width:100%">
                        <option value="">Select To Days</option>
                        <?php for($i=1;$i<=60;$i++) { ?>
                        <option value="{{$i}}" <?php echo ($i == old('to_package_duration_days',$to_package_duration_days)) ? "selected":"" ?> > {{$i}}</option>  
                        <?php } ?>
                    </select>
                </div>
                <?php } ?>

                <div class="col-md-3">
                    <label for="destination"> Destination ({{($segment == 'activity')?'Activity':'Packages'}} Count):</label><br/>
                    <?php /*<select class="form-control admin_input1" name="destination" id="destination">
                    <?php
                        $destination_id = old('destination',$destination_id);
                        if(!empty($destinations)){
                        $parent_destinations = $destinations->where('parent_id', 0);
                        ?>
                            <option value="">Select Destination</option>
                            <?php
                            if(!($parent_destinations->isEmpty())){
                            foreach ($parent_destinations as $destination_it){
                                $selected = '';
                                if($destination_it->id == $destination_id){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$destination_it->id}}" {{$selected}}>{{$destination_it->name}}</option>
                        <?php }}}?>
                    </select>

                    <ul>
                        @if(count($destinations) > 0)
                        @foreach ($destinations as $destination)
                        <li>{{ $destination->name }}</li>
                        <ul>
                            @if(count($destination->childDestinations))
                            @foreach ($destination->childDestinations as $subDestinations)
                            @include('admin.packages.sub_destinations', ['sub_destinations' => $subDestinations])
                            @endforeach
                            @endif
                        </ul>
                        @endforeach
                        @endif
                    </ul>*/ ?>

                    <select class="form-control admin_input1" name="destination" id="destination">
                        <option value="">Select Destination</option>
                        {!!CustomHelper::getDestinationOptions('', $destination_id,['show_packages_count'=>true,'segment'=>$segment])!!}
                    </select>
                </div>

                <?php /*
                <div class="col-md-3">
                <label for="sub_destination">Sub Destination:</label><br/>
                 <select class="form-control admin_input1 select2" name="sub_destination" id="sub_destination">
                    <option value="">Select Sub Destination</option>
                </select>
                </div>*/ ?>
                <?php if($segment == 'packages'){ ?>
                <div class="col-md-3">
                    <label>Tour Type </label><br/>
                    <select name="tour_type" class="form-control admin_input1">
                        <option value="">Select Tour Type</option>
                        <option value="group" {{ ($tour_type == 'group')?'selected':'' }}>Group</option>
                        <option value="fixed" {{ ($tour_type == 'fixed')?'selected':'' }}>Fixed</option>
                        <option value="open" {{ ($tour_type == 'open')?'selected':'' }}>Open</option>
                    </select>
                </div>
            <?php } ?>

                <div class="col-md-3{{$errors->has('package_themes')?' has-error':''}}">
                <label for="FormControlSelect1">{{($segment == 'activity')?'Activity':'Packages'}} Themes</label><br/>
                <select name="package_themes" class="form-control admin_input1 select2">
                <option value="">--Select Package Themes--</option>
                @if($themes)
                @foreach($themes as $row)
                <option value="{{$row->id}}" {{($row->id==$package_themes)?'selected':''}}>{{$row->title}}</option>
                @endforeach
                @endif
                </select>
                </div>

                <?php if($segment == 'packages'){ ?>
                    
                <div class="col-md-3{{$errors->has('package_type')?' has-error':''}}">
                <label for="FormControlSelect1">{{($segment == 'activity')?'Activity':'Packages'}} Type</label><br/>
                <select name="package_type" class="form-control admin_input1 select2">
                <option value="">--Select Package Type--</option>
                @if($types)
                @foreach($types as $row)
                <option value="{{$row->id}}" {{($row->id==$package_type)?'selected':''}}>{{$row->package_type}}</option>
                @endforeach
                @endif
                </select>
                </div>
                <?php } ?>

                <?php if($segment == 'packages'){ ?>
                    
                <div class="col-md-3{{$errors->has('price_category')?' has-error':''}}">
                <label for="FormControlSelect1">Price Category</label><br/>
                <select name="price_category" class="form-control admin_input1 select2">
                <option value="">--Select Price Category--</option>
                @if($price_categories)
                @foreach($price_categories as $row)
                <option value="{{$row->id}}" {{($row->id==$price_category)?'selected':''}}>{{$row->name}}</option>
                @endforeach
                @endif
                </select>
                </div>
                <?php } ?>

                <div class="clearfix"></div>
                <div class="col-md-3{{$errors->has('package_inclusions')?' has-error':''}}">
                <label for="FormControlSelect1">{{($segment == 'activity')?'Activity':'Packages'}} Inclusions</label><br/>
                <select name="package_inclusions[]" class="form-control admin_input1 select2"multiple="multiple">
                <option value="">--Select Package Inclusions--</option>
                @if($inclusions)
                @foreach($inclusions as $row)
                <option value="{{$row->id}}" {{(!empty($package_inclusions) && in_array($row->id,$package_inclusions))?'selected':''}}>{{$row->title}}</option>
                @endforeach
                @endif
                </select>
                </div>

                <div class="col-md-3">
                    <label>Status</label><br/>
                    <select name="status" class="form-control admin_input1">
                        <option value="-1" {{ ($old_status == '-1')?'selected':'1' }}>--Select--</option>
                        <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Flags</label><br/>
                    <select name="checkbox[]" class="form-control admin_input1 select2" multiple="multiple">
                        <option value="">--Select--</option>
                         <?php
                            if(!empty($flags)){ ?>
                            <option value="">Select</option>
                            <?php
                            foreach ($flags as $flag){
                                $selected = '';
                                if(in_array($flag->id,$checkbox)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$flag->id}}" {{$selected}}>{{$flag->name}}</option>
                        <?php } } ?>
                    </select>
                </div>

                <?php if(CustomHelper::isAllowedModule('vendor')){ ?>
                <div class="col-md-3">
                    <label>Vendor :</label><br/>
                    <select class="form-control admin_input1 select2"  name="vendor_id" id="vendor_id">  
                        <option value="">Select</option>
                            <?php
                            foreach ($vendors as $vendor)
                            {
                                $selected = '';
                                if($vendor->id == $vendor_id) {
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$vendor->id}}" {{$selected}}>{{$vendor->name}}</option>
                        <?php } ?>
                    </select>
                </div>
                <?php } ?>

                <div class="clearfix"></div>
                 <div class="col-md-3">
                    <label class="control-label ">Featured </label><br/>
                    <input type="checkbox" name="featured" value="1" <?php echo($featured == '1')?'checked':''; ?> />
                </div>
                
                <?php /*
                <div class="col-md-3">
                    <label>Is Activity </label> <br/>
                    <input class="tourType" type="checkbox" name="is_activity" id="is_activity" value="1" <?php echo ($is_activity == '1')?'checked':''; ?> > Yes
                </div> */ ?>

                <div class="clearfix"></div>
                <div class="col-md-6">
                    <label></label><br>
                    <button type="submit" class="btn btn-success">Search</button>
                     <a href="{{route($routeName.'.'.$segment.'.index')}}" class="btn_admin2">Reset</a>
                </form>
        </div>
    </div>
<!-- End Search box Section -->

<!-- End Search box Section -->
            @include('snippets.errors')
            @include('snippets.flash')

            <?php if(!empty($packages) && $packages->count() > 0){ ?>

                <div class="table-responsive mt20">
                    <table class="table table-bordered">
                        <tr>
                            <th>Image</th>
                            <?php if($segment == 'packages'){ ?>
                            <th>Tour Type</th>
                            <?php } ?>
                            <th>Title</th>
                            @if($segment == 'packages')
                            <th>Days</th>
                            @endif

                            @if($segment == 'activity')
                            <th>Activity Duration</th>
                            @endif
                            <th>Destination</th>
                            <?php if(CustomHelper::isAllowedModule('vendor')){ ?>
                             <th>Vendor</th> 
                            <?php } ?>
                            <th>Experts</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Flags</th>
                            <th>Action</th>
                            <?php if(CustomHelper::checkPermission('activitylogs','view')){ ?>
                            <th>View History</th>
                        <?php } ?>
                        </tr>
                            <?php
                            foreach($packages as $package){?>
                        <tr>
                            <td><?php
                                $image = $package->image;
                                if(!empty($image)){
                                    if($storage->exists($path.$image)){
                                        ?>
                                        <div class="col-md-2 image_box">
                                            <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                                        </div>
                                    <?php }}else{
                                        echo "No Image";
                                    } ?></td>
                            <?php if($segment == 'packages'){ ?>
                            <td>{{$package->tour_type ?? ''}} </td>
                            <?php } ?>
                            <td>{{ $package->title }}</td>
                            @if($segment == 'packages')
                            <td>{{ $package->package_duration_days }}</td>
                            @endif

                            @if($segment == 'activity')
                            <td>{{ $package->package_duration }}</td>
                            @endif
                            <td><?php echo (!empty($package->packageParentDestination)) ? $package->packageParentDestination->name: ''; ?></td>
 
                            <?php if(CustomHelper::isAllowedModule('vendor')){ ?>
                            <td> <?php $vendor_name = $package->packageVendorData->name??''; 
                                    $icon = 'plus';
                                if($vendor_name){
                                    echo $vendor_name;
                                    $icon = 'edit';
                                }
                                if($is_vendor != 1){?>
                                @if(CustomHelper::checkPermission('packages','edit') || CustomHelper::checkPermission('activity','edit'))
                                <a href="javascript:void(0);" data-src="{{route($routeName.'.packages.update_vendor',[$package->id])}}" title="vendor update" data-fancybox data-type="ajax"><i class="fas fa-{{$icon}}"></i></a> 
                                @else
                                <?php $vendor_name; ?>
                                @endif
                            <?php } ?>
                            </td>
                            <?php } ?>
                            <td> <?php //$expert_name = $package->packageExpertData->title??''; 
                                     $expert_name=[];   
                                    $checkPackageExperts = json_decode($package->experts)??[];
                                    $icon = 'plus';
                                if(is_array($checkPackageExperts)){
                                    foreach($checkPackageExperts as $data){
                                       $expert_name[] = $package->getExperName($data);
                                    $icon = 'edit';
                                    }
                                }
                                if(isset($expert_name) && !empty(is_array($expert_name))){

                                echo implode(",",$expert_name);
                                }
                                //if($is_vendor != 1){?>
                                <a href="javascript:void(0);" data-src="{{route($routeName.'.packages.update_expert',[$package->id])}}" title="Experts update" data-fancybox data-type="ajax"><i class="fas fa-{{$icon}}"></i></a> 
                            <?php //} ?>
                            </td>
                            <td>{{ $package->sort_order }}</td>
                            <td><?php if($package->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                        </td>
                        <td><?php if($package->featured == 1){ ?>
                            <span style="color:green"><i class="fas fa-check" style="color:green"></i></span>
                            <?php }else{ ?>
                            <span style="color:#f00;"><i style="color:#f00;" class="fa fa-times"></i></span>
                            <?php } ?>
                        </td>
                            <td><?php
                            $package_flags = (!empty($package) && (!$package->packageFlags->isEmpty())) ? $package->packageFlags : "";

                            $package_flag_arr = [];
                            if(!empty($package_flags)){
                                foreach ($package_flags as  $pflag) {
                                    $package_flag_arr[] = $pflag->name;
                                }
                            }

                            echo implode(', ',$package_flag_arr);

                             /* if($package->featured == 1){ 
                                <!-- <i class="fas fa-check" style="color:green"></i> -->
                                #F
                                <?php   } 

                                <?php  if($package->popular == 1){ 
                                <!-- <i class="fas fa-check" style="color:green"></i> -->
                                #P
                                <?php   } */?>
                        </td>
                            <td>
                                <?php if($segment == 'activity'){ ?>
                                @if(CustomHelper::checkPermission('activity','view'))
                                <a href="{{route($routeName.'.activity.activity_view',[$package['id'], 'back_url'=>$back_url])}}" title="View Package"><i class="fas fa-eye"></i></a>
                                @endif

                               <?php }else{ ?>
                                @if(CustomHelper::checkPermission('packages','view'))
                                <a href="{{route($routeName.'.packages.package_view',[$package['id'], 'back_url'=>$back_url])}}" title="View Package"><i class="fas fa-eye"></i></a>
                                @endif
                                
                               <?php } ?>

                                @if(CustomHelper::checkPermission('packages','edit') || CustomHelper::checkPermission('activity','edit'))
                                <a href="{{ route($routeName.'.'.$segment.'.edit', ['id'=>$package->id,'back_url'=>$back_url]) }}" title="Edit Package"><i class="fas fa-edit"></i></a>
                                @endif
                                @if(CustomHelper::checkPermission('packages','delete') ||CustomHelper::checkPermission('activity','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$package->id}}" title="Delete"><i class="fas fa-trash-alt"></i></i></a>
                                <?php  $package_slug = $package->slug;
                                  $packageDetailName = ($package->is_activity==1)?'activityDetail':'packageDetail';

                                $title = "Package";
                                if($package->is_activity == 1){
                                    $title = 'Activity';
                                }
                                ?>
                                <a href="{{ route($packageDetailName,['slug'=>$package_slug]) }}" title="Package page" target="_blank"><i class="fa fa-globe"></i></a>

                                 <form method="POST" action="{{ route($routeName.'.'.$segment.'.delete', ['id'=>$package->id]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('{{'Are you sure you want to remove this '.$title}}?');" id="delete-form-{{$package->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="id" value="<?php echo $package->id; ?>">
                                </form>
                                @endif
                            </td>
                            <?php if(CustomHelper::checkPermission('activitylogs','view')){ ?>
                            <td>
                                    <a href="{{ route($routeName.'.activitylogs.index','moduleid='.$package->id.'&module='.$title) }}"
                                        title="View History" target="_blank"><i class="fas fa-history"></i></a>
                                </td>
                                    <?php } ?>
                        </tr>

                        <?php } ?>
                    </table>
                </div>
              {{ $packages->appends(request()->query())->links('vendor.pagination.default') }}
            
            <?php }else{ ?>
            <div class="alert alert-warning">No {{ucfirst($segment)}} found.</div>
            <?php } ?>

        </div>

@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
      $("#model_click").click(function() {
           $("#sendMail").html(
                'Please Wait <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>'
            );
            $("#sendMail").attr("disabled", true);
      });


const changeSelected = (e) => {
  const $select = document.querySelector('#sel_vendor_id','#sel_experts');
  $select.value = 14
};


      $(document).on("click","#cust_btn",function(){
        var order_id = $(this).attr('data-order_id');
        // alert(order_id);
        $('#refund_order_id').val(order_id);
        $("#myModal").modal("toggle");

        // document.getElementById('sel_vendor_id').value = 14;


      })
document.querySelector('#cust_btn').addEventListener('click', changeSelected);
</script>

<script type="text/javaScript">

    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });

    $('.select2').select2({
            placeholder: "Please Select",
            allowClear: true
        });
</script>
<script type="text/javascript">
    var _token = '{{ csrf_token() }}';

    $(document).ready(function () {

        var destinationId = $('#destination').val();
        var subDestinationId = '{{ $sub_destination_id }}';

        populateSubDestination(destinationId,subDestinationId);

        $('#destination').on('change',function(e) {
            var destination_id = e.target.value;
            populateSubDestination(destination_id);
        });
    });

    function populateSubDestination(destination_id,setDestination=""){
        $.ajax({
            url:"{{ route('common.ajax_load_sub_destinations') }}",
            type:"POST",
            headers:{'X-CSRF-TOKEN': _token},
            data: {
                destination_id: destination_id
            },
            success:function (data) {
                let text = "";
                $('#sub_destination').empty();
                text +=  `<option value="">---Select Sub Destination---</option>`
                text += data.options;
                /*data.states.forEach(function(item, index){
                    text +=  `<option value="${item.id}">${item.name}</option>`
                })*/
                $("#sub_destination").html(text)
            },complete:function(){
                $('#sub_destination').val(setDestination);
            }
        });
    }
</script>
@endslot

@endcomponent