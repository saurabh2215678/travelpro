@component('admin.layouts.main')

    @slot('title')
        Admin - Destination - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
        $parent_id = (request()->has('parent_id'))?request()->parent_id:'';
        $old_destination_name = (request()->has('destination_name'))?request()->destination_name:'';
        $old_status = app('request')->input('status');

        $storage = Storage::disk('public');
        $path = 'destinations/';
    ?>

<div class="top_title_admin">
    <div class="title">
    <h2>Destinations</h2>
    </div>
    <div class="add_button">
    <a href="{{ route('admin.destinations.add', ['back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Destination</a>      
    </div>
    </div>

    <!-- Start Search box section     -->
    <div class="centersec">
        <div class="bgcolor ">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-2">
                        <label>Name:</label><br/>
                        <input type="text" name="destination_name" class="form-control admin_input1" value="{{$old_destination_name}}">
                    </div>
                     <div class="col-md-2">
                        <label>Status:</label><br/>
                        <select name="status" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="1" {{ ($old_status == '1')?'selected':'' }}>Active</option>
                            <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                   <!--  <div class="clearfix"></div> -->
                    <div class="col-md-6">
                        <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{ route($routeName.'.destinations.index') }}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    
<!-- End Search box Section -->




    <div class="centersec">


            @include('snippets.errors')
            @include('snippets.flash')

            <?php if(!($holidaydes->isEmpty())){?>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">

                        <tr>
                            <th width="25%" class="text-center">Name</th>
                            <th width="10%" class="text-center">Image</th>
                            <!-- <th width="10%" class="text-center">Feature Image</th> -->
                            <!-- <th width="10%" class="text-center">Banner Image</th> -->
                            <th width="20%" class="text-center">Subdestinations</th>
                            <th width="5%" class="text-center">Status</th>
                            <th width="5%" class="text-center">Featured</th>
                            <th width="10%" class="text-center">Packages</th>
                            <th width="10%" class="text-center">Date Created</th>
                            <th width="15%" class="text-center">Action</th>
                        </tr>

                        <?php
                        foreach($holidaydes as $destination){
                            
                        ?>
                            <tr>
                                <?php if(!($destination->children->isEmpty())){ ?>
                                    <td><a href="{{route($routeName.'.destinations.index',['parent_id'=>$destination->id])}}">{{$Destination->name}}</a></td>
                                <?php }else{ ?>
                                    <td>{{$Destination->name}}</td>
                                <?php } ?>
                                <td>
                                <?php
                                    $image = $destination->image;
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

                                <?php if(!($destination->children->isEmpty())){ ?>
                                    <td><a href="{{route($routeName.'.destinations.index',['parent_id'=>$destination->id])}}">{{$destination->children->count()}}</a></td>
                                <?php }else{ ?>
                                    <td>0</td>
                                <?php } ?>
                                <td>{{ CustomHelper::getStatusStr($destination->status) }}</td>
                                <td>{{ CustomHelper::getStatusStr($destination->featured) }}</td>
                                <td>{{ $destination->destinationPackages->count()}}</td>
                                <td>{{ CustomHelper::DateFormat($destination->created_at, 'd/m/Y') }}</td>

                                <td>

                                    <a href="{{route($routeName.'.destinations.view',[$destination['id'], 'back_url'=>$BackUrl])}}" title="View Destination"><i class="fas fa-eye"></i></a>

                                    <a href="{{ route($routeName.'.destinations.additional_info', $destination->id) }}" class="" title="Additional Info"><i class="fas fa-info"></i> </a>

                                    <a href="{{ route($routeName.'.destinations.edit', $destination->id) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>

                                    <a href="{{ route($routeName.'.images.upload',['tid'=>$destination->id,'tbl'=>'destinations']) }}" target="_blank"><i class="fas fa-image" title="Add Images"></i></a>
                                    
                                    <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                    <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.destinations.delete', [$destination['id'], 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                        {{ csrf_field() }}
                                    </form>
                                </td>

                            </tr>
                        <?php } ?>
                    </table>
                </div>
                {{ $holidaydes->appends(request()->query())->links('vendor.pagination.default') }}
           <?php
       }else{
        ?>
        <div class="alert alert-warning">There are no Destination at the present.</div>
        <?php } ?>
        </div>

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