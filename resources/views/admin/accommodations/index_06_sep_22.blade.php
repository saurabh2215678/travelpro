@component('admin.layouts.main')

    @slot('title')
        Admin - Destination - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
        // $destination_id = (request()->has('destination_id'))?request()->destination_id:'';
        $old_name = (request()->has('name'))?request()->name:'';
        $old_status = app('request')->input('status');
        $old_destination_id = (isset($accommodation->destination_id))?$accommodation->destination_id:'';

        $storage = Storage::disk('public');
        $path = 'accommodations/';
    ?>

    <!-- Start Search box section     -->

    <div class="top_title_admin">
    <div class="title">
    <h2>Accommodation</h2>
    </div>
    <div class="add_button">
    <a href="{{ route('admin.accommodations.add', ['back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Accommodation</a>    </div>
    </div>


    <div class="centersec">
        <div class="bgcolor ">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-3">
                        <label>Name:</label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                    </div>
                    <div class="col-md-3">
                    <label for="destination"> Destination:</label><br/>
                     <select class="form-control admin_input1" name="destination">
                    <?php
                        $destination_id = old('destination',$destination_id);

                        if(!empty($destinations)){ ?>
                            <option value="">Select Destination</option>
                            
                            <?php
                            foreach ($destinations as $destination_it){
                                $selected = '';
                                if($destination_it->id == $destination_id){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$destination_it->id}}" {{$selected}}>{{$destination_it->name}}</option>
                        <?php }}?>
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
                        <a href="{{route($routeName.'.accommodations.index')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    
<!-- End Search box Section -->
 

            @include('snippets.errors')
            @include('snippets.flash')

            <?php
            if(!empty($accommodations) && count($accommodations) > 0){
                ?>
                <div class="table-responsive mt50">
                    <table class="table table-bordered">

                        <tr>
                            <th width="15%" >Name</th>
                            <th width="5%" >Place</th>
                            <th width="10%">Image</th>
                            <th width="15%">Contact Phone</th>
                            <th width="15%">Contact Email</th>
                            <th width="5%">Status</th>
                            <th width="5%">Featured</th>
                            <th width="10%">Date Created</th>
                            <th width="20%">Action</th>
                        </tr>

                        <?php
                        foreach($accommodations as $accommodation){
                            //prd($accommodation->accommodationDestination);
                        ?>
                            <tr>
                            <td>{{$accommodation->name}}</td>
                            <td>{{ $accommodation->accommodationDestination->name }}</td>
                            <td>
                                <?php
                                    $image = $accommodation->image;
                                    if(!empty($image)){
                                    if($storage->exists($path.$image)){
                                ?>
                                <div class="col-md-2 image_box">
                                <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                                </div>
                                <?php }}else{
                                    echo "No Image";
                                } ?>
                                </td>
                                <td>{{$accommodation->contact_phone}}</td>
                                <td>{{$accommodation->contact_email}}</td>
                                <td>{{ CustomHelper::getStatusStr($accommodation->status) }}</td>
                                <td>{{ CustomHelper::getStatusStr($accommodation->featured) }}</td>
                                <td>{{ CustomHelper::DateFormat($accommodation->created_at, 'd/m/Y') }}</td>

                                <td>
                                    <a href="{{route($routeName.'.accommodations.view',[$accommodation['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>

                                    <a href="{{ route($routeName.'.accommodations.accommodation_room', $accommodation->id) }}" class="" title="Rooms"><i class="fas fa-bath"></i> </a>

                                    <a href="{{ route($routeName.'.images.upload',['tid'=>$accommodation->id,'tbl'=>'accommodations']) }}" target="_blank"><i class="fas fa-image" title="Add Images"></i></a>

                                    <a href="{{ route($routeName.'.accommodations.edit', $accommodation->id) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                                    <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                    <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.accommodations.delete', [$accommodation['id'], 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                        {{ csrf_field() }}
                                    </form>
                                </td>

                            </tr>
                        <?php } ?>
                    </table>
                </div>
                {{ $accommodations->appends(request()->query())->links('vendor.pagination.default') }}
           <?php
       }else{
        ?>
        <div class="alert alert-warning">There are no Accommodation at the present.</div>
        <?php } ?>
        </div>

   

    @slot('bottomBlock')

    <script type="text/javascript">
        $(document).on("click", ".sbmtDelForm", function(e){
            e.preventDefault();

            $(this).siblings("form.delForm").submit();                
        });
    </script>
    
    @endslot

@endcomponent