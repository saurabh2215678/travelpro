@component('admin.layouts.main')

    @slot('title')
        Admin - Manage Cabs - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    @endslot

    <?php
    $back_url=CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $id = (request()->has('id'))?request()->id:'';
    $old_name = (request()->has('name'))?request()->name:'';
    $old_status = (request()->has('status'))?request()->status:1; 
    $old_featured = (request()->has('featured'))?request()->featured:'';
    $is_airport = (request()->has('is_airport'))?request()->is_airport:'';

    ?>

    <!-- Start Search box section     -->
    <div class="centersec">
        <div class="top_title_admin tab-title">
            <div class="title">
                <h2>Manage City ({{ $cabCities->total() }})</h2>
            </div>
            <div class="add_button">
              @if(CustomHelper::checkPermission('cabs','add'))
              <a href="{{ route($routeName.'.cab_cities.add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i>Add City</a>
              @endif
          </div>
      </div>
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-3">
                        <label>Name:</label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                    </div>
                     <div class="col-md-3">
                        <label>Status:</label><br/>
                        <select name="status" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                            <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-1">
                    <label class="control-label ">Featured </label><br/>

                    <input type="checkbox" name="featured" value="1" <?php echo ($old_featured == '1')?'checked':''; ?> />
                    </div>

                    <div class="col-md-1">
                    <label>Airport </label> <br/>
                    <input class="tourType" type="checkbox" name="is_airport" id="is_airport" value="1" <?php echo ($is_airport == '1')?'checked':''; ?> > Yes
                    </div>

                   <!--  <div class="clearfix"></div> -->
                   <div class="col-md-4">
                    <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{route($routeName.'.cab_cities.index')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

<!-- End Search box Section -->
   
            @include('snippets.errors')
            @include('snippets.flash')

            <?php
            if(!empty($cabCities) && count($cabCities) > 0){
                ?>

              <div class="table-responsive">           
    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th>Name</th>
                            <th>Sort Order</th>
                            <th>Date Created</th>
                            <th>Featured</th>
                            <th>Airport</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                            <?php
                            foreach($cabCities as $cabCity){?>
                        <tr>
                            <td><?php echo $cabCity->name; ?></td>
                            <td>{{ $cabCity->sort_order}}</td>
                            <td>{{ CustomHelper::DateFormat($cabCity->created_at, 'd/m/Y') }}</td>
                            <td><?php  if($cabCity->featured == 1){ ?>
                            <i class="fas fa-check" style="color:green"></i>
                            <?php   } ?>
                            </td>
                            <td><?php  if($cabCity->is_airport == 1){ ?>
                                <i class="fas fa-check" style="color:green"></i>
                            <?php   } ?>
                            </td>
                            <td><?php if($cabCity->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?></td>
                            <td>
                                @if(CustomHelper::checkPermission('cabs','view'))
                                <a href="{{route($routeName.'.cab_cities.view',[$cabCity['id'], 'back_url'=>$back_url])}}" title="View "><i class="fas fa-eye"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('cabs','edit'))
                                <a href="{{ route($routeName.'.cab_cities.edit', $cabCity->id.'?back_url='.$back_url) }}" title="Edit Cab City"><i class="fas fa-edit"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('cabs','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$cabCity->id}}" title="Delete Cab City"><i class="fas fa-trash-alt"></i></i></a>

                                 <form method="POST" action="{{ route($routeName.'.cab_cities.delete', $cabCity->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove this cab City?');" id="delete-form-{{$cabCity->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="id" value="<?php echo $cabCity->id; ?>">
                                    </form>
                                    @endif
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
              {{ $cabCities->appends(request()->query())->links('vendor.pagination.default') }}
            
            <?php
            }
            else{
            ?>
            <div class="alert alert-warning">No Cab Cities found.</div>
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