@component('admin.layouts.main')

@slot('title')
Admin - Accommodations - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('title')
Admin - Destination - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
        // $destination_id = (request()->has('destination_id'))?request()->destination_id:'';
$old_name = (request()->has('name'))?request()->name:'';
$old_status = app('request')->input('status') ?? 1;
// $old_destination_id = (isset($accommodation->destination_id))?$accommodation->destination_id:'';
$destination_id = (request()->has('destination'))?request()->destination:'';

$vendor_id = (request()->has('vendor_id'))?request()->vendor_id:'';
$vendor_id = old('vendor_id', $vendor_id);

$old_featured = (request()->has('featured'))?request()->featured:'';

$user = auth()->user();
$is_vendor = $user?$user->is_vendor:'';

$storage = Storage::disk('public');
$path = 'accommodations/';
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Start Search box section     -->

<div class="top_title_admin">
    <div class="title">
        <h2>Accommodation ( {{$accommodations->total()}})</h2>
    </div>
    <div class="add_button">
        @if(CustomHelper::checkPermission('accommodations','add'))
        <a href="{{ route('admin.accommodations.add', ['back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Accommodation</a>
    @endif    </div>
</div>

<div class="centersec accommodation-index">
    <div class="bgcolor ">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <div class="col-md-2">
                    <label>Name:</label><br/>
                    <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                </div>
                <div class="col-md-2">
                    <label for="destination"> Destination:</label><br/>
                    <select class="form-control admin_input1" name="destination">
                        <?php
                        /*$destination_id = old('destination',$destination_id);
                        if(!empty($destinations)){ 
                            <option value="">Select Destination</option>                            
                            <?php
                            foreach ($destinations as $destination_it){
                                $selected = '';
                                if($destination_it->id == $destination_id){
                                    $selected = 'selected';
                                }
                                
                                <option value="{{$destination_it->id}}" {{$selected}}>{{$destination_it->name}}</option>
                            <?php }}*/?>
                            <option value="">Select Destination</option>
                            {!!CustomHelper::getDestinationOptions('', $destination_id)!!}
                        </select>
                    </div>
                    <?php if(CustomHelper::isAllowedModule('vendor')){ 
                        if($is_vendor != 1){ ?>
                            <div class="col-md-2">
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
                        <?php } } ?>

                        <div class="col-md-2">
                            <label>Status:</label><br/>
                            <select name="status" class="form-control admin_input1">
                                <option value="">--Select--</option>
                                <option value="1" {{ ($old_status == '1')?'selected':'' }}>Active</option>
                                <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-1">
                        <label class="control-label ">Featured </label><br/>

                        <input type="checkbox" name="featured" value="1" <?php echo ($old_featured == '1')?'checked':''; ?> />
                        </div>
                        <!--  <div class="clearfix"></div> -->
                        <div class="col-md-3">
                            <label></label><br>
                            <button type="submit" class="btn btn-success">Search</button>
                            <a href="{{route($routeName.'.accommodations.index')}}" class="btn_admin2">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- End Search box Section -->
            @include('snippets.errors')
            @include('snippets.flash')
            <div class="ajax_msg"></div>

            <?php if(!empty($accommodations) && count($accommodations) > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th width="25%">Name</th>
                            <th width="15%">Destination</th>
                            <th width="10%">Image</th>
                            <th width="5%">Featured</th>
                            <th width="8%">Sort Order</th>
                            <?php if(CustomHelper::isAllowedModule('vendor')){ ?>
                                <th width="10%">Vendor</th>
                            <?php } ?>
                            <th width="8%">Status</th>
                            <th width="9%">Date Created</th>
                            <th width="10%">Action</th>
                        </tr>
                        <?php foreach($accommodations as $accommodation) { ?>
                            <tr>
                                <td>{{$accommodation->name}}</td>
                                <td>
                                    {{$accommodation->accommodationDestination->name??''}}
                                </td>
                                <td>
                                    <?php
                                    $image = $accommodation->image??'';
                                    if(!empty($image)) {
                                        if($storage->exists($path.$image)) {
                                            /*<div class="col-md-2 image_box"></div>*/
                                            ?>
                                            <img src="{{url('storage/'.$path.'thumb/'.$image)}}" style="width: 75px;">
                                        <?php } }else{ ?>
                                            No Image
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if($accommodation->featured == 1){ ?>
                                            <i class="fas fa-check" style="color:green"></i>
                                        <?php } ?>
                                    </td>

                                        <td>{{(!empty($accommodation->sort_order))?$accommodation->sort_order:''}}</td>

                                    <?php if(CustomHelper::isAllowedModule('vendor')){ ?>
                                        <td> <?php $vendor_name = $accommodation->vendorData->name??''; 
                                        $icon = 'plus';
                                        if($vendor_name){
                                            echo $vendor_name;
                                            $icon = 'edit';
                                        }
                                        if($is_vendor != 1){?>
                                            @if(CustomHelper::checkPermission('accommodations','edit'))
                                            <a href="javascript:void(0);" data-src="{{route($routeName.'.accommodations.update_vendor',[$accommodation->id])}}" title="vendor update" data-fancybox data-type="ajax"><i class="fas fa-{{$icon}}"></i></a>
                                            @else
                                            <?php $vendor_name; ?>
                                            @endif 
                                        <?php } ?>
                                        </td>
                                    <?php } ?>
                                <td>
                                    <?php if($accommodation->status == 1){ ?>
                                        <span style="color:green">Active</span>
                                    <?php } else { ?>
                                        <span style="color:red">Inactive</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    {{CustomHelper::DateFormat($accommodation->created_at, 'd/m/Y') }}
                                </td>
                                <td>
                                    @if(CustomHelper::checkPermission('accommodations','view'))
                                    <a href="{{route($routeName.'.accommodations.view',[$accommodation['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>
                                    @endif

                                    @if(CustomHelper::checkPermission('accommodations','edit'))
                                    <a href="{{ route($routeName.'.accommodations.edit', $accommodation->id) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                                    @endif

                                    @if(CustomHelper::checkPermission('accommodations','delete'))
                                    <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>

                                    <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.accommodations.delete', [$accommodation['id'], 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove this accommodation?');" class="delForm">
                                        {{ csrf_field() }}
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                {{$accommodations->appends(request()->query())->links('vendor.pagination.default')}}
            <?php } else { ?>
                <div class="alert alert-warning">There are no Accommodation at the present.</div>
            <?php } ?>
        </div>
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
          const $select = document.querySelector('#sel_vendor_id');
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
  <script type="text/javascript">
      $('.select2').select2({
        placeholder: "Please Select",
        allowClear: true
    });

      $(document).on("click", ".sbmtDelForm", function(e){
        e.preventDefault();

        $(this).siblings("form.delForm").submit();                
    });

</script>
@endslot
@endcomponent