@component('admin.layouts.main')

    @slot('title')
        Admin - Manage {{ucfirst($segment)}} Airlines - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    @endslot

    <?php
    $back_url=CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $id = (request()->has('id'))?request()->id:'';
    $old_airline_name = (request()->has('airline_name'))?request()->airline_name:'';
    $old_status = (request()->has('status'))?request()->status:1; 

    $storage = Storage::disk('public');
    $path = 'airlines/';
    ?>

    <!-- Start Search box section     -->
    <div class="centersec">
        <?php
        $active_menu = "packages.airlines";
        ?>
        @include('admin.includes.packagemenu')
        <div class="top_title_admin tab-title">
            <div class="title">
                <h2>Manage {{ucfirst($segment)}} Airlines ({{ $airlines->total() }})</h2>
            </div>
            <div class="add_button">
              @if(CustomHelper::checkPermission('all_masters','add'))
              <a href="{{ route($routeName.'.'.$segment.'.airline_add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i>Add {{ucfirst($segment)}} Airline</a>
              @endif
          </div>
      </div>
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-2">
                        <label>Airline Name:</label><br/>
                        <input type="text" name="airline_name" class="form-control admin_input1" value="{{$old_airline_name}}">
                    </div>
                     <div class="col-md-2">
                        <label>Status:</label><br/>
                        <select name="status" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                            <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                   <!--  <div class="clearfix"></div> -->
                   <div class="col-md-6">
                    <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{route($routeName.'.'.$segment.'.package_airlines')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

<!-- End Search box Section -->
   
            @include('snippets.errors')
            @include('snippets.flash')

            <?php
            if(!empty($airlines) && count($airlines) > 0){
                ?>

              <div class="table-responsive">           
    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th>Airline Name</th>
                            <th>Image</th>
                            <th>Airline Code</th>
                            <th>Status</th>
                            <th>Sort Order</th>
                            <th>Date Created</th>
                            <th>Action</th>
                        </tr>
                            <?php
                            foreach($airlines as $airline_query){?>
                        <tr>
                            <td><?php echo $airline_query->airline_name; ?></td>
                            <td> <?php
                                    $image = $airline_query->image;
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
                            <td>{{ $airline_query->airline_code}}</td>
                            <td><?php if($airline_query->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?></td>
                            <td>{{ $airline_query->sort_order}}</td>
                            <td>{{ CustomHelper::DateFormat($airline_query->created_at, 'd/m/Y') }}</td>
                            <td>
                                @if(CustomHelper::checkPermission('all_masters','view'))
                                <a href="{{route($routeName.'.'.$segment.'.airline_view',[$airline_query['id'], 'back_url'=>$back_url])}}" title="View {{ucfirst($segment)}} Airline"><i class="fas fa-eye"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('all_masters','edit'))
                                <a href="{{ route($routeName.'.'.$segment.'.air_edit', $airline_query->id.'?back_url='.$back_url) }}" title="Edit {{ucfirst($segment)}} Airline"><i class="fas fa-edit"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('all_masters','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$airline_query->id}}" title="Delete {{ucfirst($segment)}} Airline"><i class="fas fa-trash-alt"></i></i></a>

                                 <form method="POST" action="{{ route($routeName.'.'.$segment.'.delete_airline', $airline_query->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove this package Airlines?');" id="delete-form-{{$airline_query->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="id" value="<?php echo $airline_query->id; ?>">
                                    </form>
                                    @endif
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
              {{ $airlines->appends(request()->query())->links('vendor.pagination.default') }}
            
            <?php
            }
            else{
            ?>
            <div class="alert alert-warning">No {{ucfirst($segment)}} Airline found.</div>
            <?php } ?>
        </div>
    @slot('bottomBlock')
    <script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
</script>
    
@endslot
@endcomponent