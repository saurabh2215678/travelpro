@component('admin.layouts.main')

    @slot('title')
        Admin - Manage Package Itenaries - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php 
    $back_url=CustomHelper::BackUrl(); 
    $routeName = CustomHelper::getAdminRouteName();
    $old_day_title = (request()->has('day_title'))?request()->day_title:'';
    $old_status = app('request')->input('status');
    ?>

    <div class="top_title_admin">
    <div class="title">
    <h2>Manage Package Itenaries</h2>
    </div>
    <div class="add_button">
        <a href="{{ route($routeName.'.packages.itenary_add',['package_id'=>$package_id]).'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Itenary</a>
    </div>
    </div>
<!-- Start Search box section     -->
<div class="centersec">
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-2">
                        <label>Itenary:</label><br/>
                        <input type="text" name="day_title" class="form-control admin_input1" value="{{$old_day_title}}">
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
                        <a href="{{route($routeName.'.packages.itenaries',['package_id'=>$package_id])}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

<!-- End Search box Section -->

            @include('snippets.errors')
            @include('snippets.flash')

            <?php if(!empty($itenaries) && $itenaries->count() > 0){ ?>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Day</th>
                            <th>Day Title</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Sort Order</th>
                            <th>Action</th>
                        </tr>
                            <?php
                            foreach($itenaries as $itenary){?>
                        <tr>
                            <td>{{ $itenary->day }}</td>
                            <td>{{ $itenary->day_title }}</td>
                            <td>{{ $itenary->title }}</td>
                            <td>{{ CustomHelper::getStatusStr($itenary->status) }}</td>
                            <td>{{ $itenary->sort_order }}</td>
                            <td>
                                <a href="{{ route($routeName.'.packages.itenary_view', ['package_id'=>$package_id,'id'=>$itenary->id,'back_url'=>$back_url]) }}" title="View Itenary"><i class="fas fa-eye"></i></a>

                                <a href="{{ route($routeName.'.packages.itenary_edit', ['package_id'=>$package_id,'id'=>$itenary->id,'back_url'=>$back_url]) }}" title="Edit Itenary"><i class="fas fa-edit"></i></a>

                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$itenary->id}}" title="Delete Package"><i class="fas fa-trash-alt"></i></i></a>

                                 <form method="POST" action="{{ route($routeName.'.packages.itenary_delete', ['package_id'=>$package_id,'id'=>$itenary->id]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove?');" id="delete-form-{{$itenary->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="id" value="<?php echo $itenary->id; ?>">
                                </form>
                            </td>
                        </tr>

                        <?php } ?>
                    </table>
                </div>
              {{ $itenaries->appends(request()->query())->links('vendor.pagination.default') }}
            
            <?php }else{ ?>
            <div class="alert alert-warning">No Itenary found.</div>
            <?php } ?>

        </div>
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