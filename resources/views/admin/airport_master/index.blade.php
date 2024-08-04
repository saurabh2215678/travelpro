@component('admin.layouts.main')

@slot('title')
Admin - Manage Airtport Master Data - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$name = (request()->has('name'))?request()->name:'';
$status = (request()->has('status'))?request()->status:1;
$featured = (request()->has('featured'))?request()->featured:'';

$lead_status_category_arr = config('custom.lead_status_category_arr');
?>


<div class="top_title_admin">
    <div class="title">
        <h2> Manage Airtport Master Data ({{$records->total()}})</h2>
    </div>
    <div class="add_button">
        @if(CustomHelper::checkPermission('flight','add'))
        <a href="{{ route($ADMIN_ROUTE_NAME.'.airport_master.add').'?back_url='.$BackUrl }}" class="btn_admin btn btn-sm btn-success pull-right" style="margin-right:5px;"><i class="fa fa-plus"></i> Add Airport Master Data</a>
        @endif
    </div>
</div>

<div class="centersec">
    <div class="bgcolor">
        <div class="table-responsive">
            <form class="" method="GET">
                <div class="col-sm-3">
                    <label>Search:</label><br/>
                    <input type="text" name="name" class="form-control" placeholder="Name / City" value="{{$name}}" />
                </div>
                <div class="col-sm-3">
                    <label>Show:</label><br/>
                    <select name="status" class="form-control">
                        <option value="">Select Status</option>
                        <option value="1" {{($status == '1')?'selected':'1' }}>Show</option>
                        <option value="0" {{($status == '0')?'selected':'' }}>Hide</option>
                    </select>
                </div>
                <div class="col-sm-1">
                    <label>Featured:</label><br/>
                    <input type="checkbox" name="featured" class="form-control" value="1" {{($featured==1)?'checked':''}} />
                </div>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-success btn1search">Search</button>
                    <a href="{{url($ADMIN_ROUTE_NAME.'/airport_master')}}" class="btn_admin2 btn resetbtn btn-primary btn1search">Reset</a>
                </div>
            </form>
        </div>
    </div>

@include('snippets.errors')
@include('snippets.flash')

<?php
if(!empty($records) && $records->count() > 0){
    ?>
    <div class="table-responsive ">

        <table class="table table-striped table-bordered">
            <tr>
                <th>Name</th>
                <th>City</th>
                <th>Sort Order</th>
                <th>Featured</th>
                <th>Show</th>
                @if(CustomHelper::checkPermission('flight','edit') || CustomHelper::checkPermission('flight','delete'))
                <th>Action</th>
                @endif
                @if(CustomHelper::checkPermission('activitylogs','view'))
                <th>View History</th>
                @endif
            </tr>
            <?php foreach($records as $record) { ?>
                <tr>
                    <td>
                        {{$record->name}}
                    </td>
                    <td>
                        {{$record->city}} ({{$record->code}})
                    </td>
                    <td>
                        {{$record->sort_order}}
                    </td>
                    <td>
                        {{($record->featured==1)?'Yes':'No'}}
                    </td>
                    <td>
                        <?php if($record->status == 1){ ?>
                            <i class="fas fa-check" style="color:green"></i>
                        <?php }else{ ?>
                            <i class="fas fa-times" style="color:red"></i>
                        <?php } ?>
                    </td>
                    @if(CustomHelper::checkPermission('flight','edit') || CustomHelper::checkPermission('flight','delete'))
                    <td>
                        @if(CustomHelper::checkPermission('flight','edit'))
                        <a href="{{ route($ADMIN_ROUTE_NAME.'.airport_master.edit',[ $record->id, $record->parent_id]) }}" title="Edit"><i class="fas fa-edit"></i></a>
                        @endif

                        @if(CustomHelper::checkPermission('flight','delete'))
                        <form method="POST" action="{{ route($ADMIN_ROUTE_NAME.'.airport_master.delete', $record->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Enquiries Master data ?');" id="delete-form-{{$record->id}}">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <input type="hidden" name="propertytype_id" value="<?php echo $record->id; ?>">
                        </form>
                        @endif
                    </td>
                    @endif
                    @if(CustomHelper::checkPermission('activitylogs','view'))
                    <td>
                        <a href="{{ route($ADMIN_ROUTE_NAME.'.activitylogs.index','moduleid='.$record->id.'&module=AirtportMaster') }}" title="View History" target="_blank"><i class="fas fa-history"></i></a>
                    </td>
                    @endif
                </tr>
            <?php } ?>
        </table>
        {{ $records->appends(request()->query())->links('vendor.pagination.default') }}
        <?php
    }
    else{
        ?>
        <div class="alert alert-warning top_title_admin">There is no dependent data present.</div>
        <?php
    }
    ?>
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