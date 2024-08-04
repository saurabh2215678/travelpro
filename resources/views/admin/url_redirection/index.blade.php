@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{ config('app.name') }}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$active_menu = "url_redirection";
/*@include('admin.includes.property_types')*/

$source_url = (request()->has('source_url'))?request()->source_url:'';
$source_url = old('source_url',$source_url);

$destination_url = (request()->has('destination_url'))?request()->destination_url:'';
$destination_url = old('destination_url',$destination_url);

$show = (request()->has('show'))?request()->show:'';
$show = old('show',$show);

$status_code = (request()->has('status_code'))?request()->status_code:'';
$status_code = old('status_code',$status_code);

?>
<div class="centersec">
<div class="row">

    <div class="col-md-12">
        <h2>
            Manage URL Redirection ({{$results->total()}})
            @if(CustomHelper::checkPermission($page_type,'add'))
            <a href="{{ route($ADMIN_ROUTE_NAME.'.'.$page_type.'.add').'?back_url='.$BackUrl }}" class="btn_admin pull-right"><i class="fa fa-plus"></i> Add URL Redirection</a>
            @endif
        </h2>
        <div class="row">
            <div class="col-md-12">
                <div class="bgcolor">
                    <form class="" method="GET">
                        <div class="col-sm-2">
                            <label>Source URL:</label><br/>
                            <input type="text" name="source_url" class="form-control admin_input1" value="{{$source_url}}">
                            @include('snippets.errors_first', ['param'=>'source_url'])
                        </div>

                        <div class="col-sm-2">
                            <label>Destination URL:</label><br/>
                            <input type="text" name="destination_url" class="form-control admin_input1" value="{{$destination_url}}">
                            @include('snippets.errors_first', ['param'=>'destination_url'])
                        </div>
                        <div class="col-sm-2">
                            <label>Status Code:</label><br/>
                            <select name="status_code" class="form-control">
                                <option value="">Select</option>
                                <option value="301" {{ ($status_code == '301')?'selected':'' }}>301</option>
                                <option value="410" {{ ($status_code == '410')?'selected':'' }}>410</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Status:</label><br/>
                            <select name="show" class="form-control">
                                <option value="">Select</option>
                                <option value="1" {{ ($show == '1')?'selected':'' }}>Active</option>
                                <option value="0" {{ ($show == '0')?'selected':'' }}>Inactive</option>
                            </select>
                            @include('snippets.errors_first', ['param'=>'show'])
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn_admin btn1search">Search</button>
                            <a href="{{url($ADMIN_ROUTE_NAME.'/'.$page_type)}}" class="btn_admin2">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('snippets.flash')      
        <?php if(!empty($results) && $results->count() > 0){ ?>
        <div class="table-responsive bgcolor">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Source URL</th>
                    <th>Destination URL</th>
                    <th>Status Code</th>
                    <th>Status</th>
                    <th>Action</th>
                    @if(CustomHelper::checkPermission('activitylogs','view'))
                    <th>View History</th>
                    @endif
                </tr>
                <?php
                foreach($results as $row){
                    ?>
                    <tr>
                        <td>
                            <a href="{{url('')}}/<?php echo $row->source_url; ?>" target="_blank">{{url('')}}/<?php echo $row->source_url; ?> <i class="fa fa-external-link"></i></a></td>
                        <td style="word-break: break-all;"><a href="{{url('')}}/<?php echo $row->destination_url; ?>" target="_blank">
                            {{url('')}}/<?php echo $row->destination_url; ?> <i class="fa fa-external-link"></i></a></td>
                        <td><?php echo $row->status_code; ?></td>

                        <td><?php if($row->show == 1){ ?>
                            <i class="fas fa-check" style="color:green"></i>
                            <?php   }else{  ?><i class="fas fa-times" style="color:red"></i>
                            <?php } ?>
                        </td>

                        <td>
                            @if(CustomHelper::checkPermission($page_type,'edit'))
                            <a href="{{ route($ADMIN_ROUTE_NAME.'.'.$page_type.'.edit', $row->id.'?back_url='.$BackUrl) }}" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif

                            @if(CustomHelper::checkPermission($page_type,'delete'))
                            <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$row->id}}"><i class="fas fa-trash-alt"></i></a>

                            <form method="POST" action="{{ route($ADMIN_ROUTE_NAME.'.'.$page_type.'.delete', $row->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this data?');" id="delete-form-{{$row->id}}">
                                {{ csrf_field() }}
                                {{ method_field('POST') }}
                                <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                            </form>
                            @endif
                        </td>
                            @if(CustomHelper::checkPermission('activitylogs','view'))
                         <td>
                            <a href="{{ route($ADMIN_ROUTE_NAME.'.activitylogs.index','moduleid='.$row->id.'&module=Url Redirection') }}" title="View History" target="_blank"><i class="fas fa-history"></i></a>
                        </td>
                            @endif
                    </tr>
                    <?php } ?>
            </table>
        </div>
        {{ $results->appends(request()->query())->links('vendor.pagination.default') }}
        <?php } else{ ?>
        <div class="alert alert-warning">No Url Redirection found.</div>
        <?php } ?>
    </div>
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