@component('admin.layouts.main')

    @slot('title')
        Admin - Manage Package Type - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php 
    $back_url=CustomHelper::BackUrl(); 
    $routeName = CustomHelper::getAdminRouteName();
    $old_title = (request()->has('title'))?request()->title:'';
    $old_status = app('request')->input('status');
    $destination_id = (request()->has('destination'))?request()->destination:'';
    $sub_destination_id = (request()->has('sub_destination'))?request()->sub_destination:'';
    ?>
<!-- Start Search box section     -->
<div class="top_title_admin">
        <div class="title">
            <h2>Manage Package</h2>
        </div>
        <div class="add_button">
        <a href="{{ route($routeName.'.packages.add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Package</a>
</div>

</div>

<div class="centersec">
    <div class="bgcolor">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <div class="col-md-3">
                    <label>Search :</label><br/>
                    <input type="text" name="title" class="form-control admin_input1" value="{{$old_title}}">
                </div>
                <div class="col-md-3">
                <label for="destination"> Destination:</label><br/>
                <select class="form-control admin_input1" name="destination" id="destination">
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
                </div>
                <div class="col-md-3">
                <label for="sub_destination">Sub Destination:</label><br/>
                 <select class="form-control admin_input1" name="sub_destination" id="sub_destination">
                    <option value="">Select Sub Destination</option>
                </select>
                </div>
                <div class="col-md-3">
                    <label>Status:</label><br/>
                    <select name="status" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <option value="1" {{ ($old_status == '1')?'selected':'' }}>Active</option>
                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                    </select>
                </div>
               <!--  <div class="clearfix"></div> -->
                <div class="col-md-3">
                    <label></label><br>
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{route($routeName.'.packages.index')}}" class="btn_admin2">Reset</a>
            </form>
        </div>
    </div>
<!-- End Search box Section -->

<!-- End Search box Section -->
            @include('snippets.errors')
            @include('snippets.flash')

            <?php if(!empty($packages) && $packages->count() > 0){ ?>

                <div class="table-responsive mt50">
                    <table class="table table-bordered">
                        <tr>
                            <th>Package</th>
                            <th>Package Duration Days</th>
                            <th>Destination</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Action</th>
                        </tr>
                            <?php
                            foreach($packages as $package){?>
                        <tr>
                            <td>{{ $package->title }}</td>
                            <td>{{ $package->package_duration_days }}</td>
                            <td><?php echo (!empty($package->packageParentDestination)) ? $package->packageParentDestination->name.' >> ' : ''; ?><?php echo (!empty($package->packageDestination)) ? $package->packageDestination->name : ''; ?></td>
                            <td>{{ CustomHelper::getStatusStr($package->status) }}</td>
                            <td>{{ CustomHelper::getStatusStr($package->featured) }}</td>
                            <td>

                                <a href="{{route($routeName.'.packages.package_view',[$package['id'], 'back_url'=>$back_url])}}" title="View Package"><i class="fas fa-eye"></i></a>

                                <a href="{{ route($routeName.'.packages.edit', ['id'=>$package->id,'back_url'=>$back_url]) }}" title="Edit Package"><i class="fas fa-edit"></i></a>
                                
                                <a href="{{ route($routeName.'.packages.additional_info', $package->id) }}" class="" title="Additional Info"><i class="fas fa-info"></i> </a>

                                <a href="{{ route($routeName.'.packages.itenaries', ['package_id'=>$package->id,'back_url'=>$back_url]) }}" title="Itenaries"><i class="fas fa-cocktail"></i></a>

                                <a href="{{ route($routeName.'.packages.price_info',['package_id'=>$package->id,'back_url'=>$back_url]) }}" target="_blank"><i class="fas fa-dollar" title="Accommodations & price"></i></a>

                                <a href="{{ route($routeName.'.images.upload',['tid'=>$package->id,'tbl'=>'packages']) }}" target="_blank"><i class="fas fa-image" title="Add Images"></i></a>
                                
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$package->id}}" title="Delete Package"><i class="fas fa-trash-alt"></i></i></a>

                                 <form method="POST" action="{{ route($routeName.'.packages.delete', ['id'=>$package->id]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove?');" id="delete-form-{{$package->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="id" value="<?php echo $package->id; ?>">
                                </form>
                            </td>
                        </tr>

                        <?php } ?>
                    </table>
                </div>
              {{ $packages->appends(request()->query())->links('vendor.pagination.default') }}
            
            <?php }else{ ?>
            <div class="alert alert-warning">No Package Type found.</div>
            <?php } ?>

        </div>

@slot('bottomBlock')
<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
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