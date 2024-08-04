@component('admin.layouts.main')

<?php $text = ''; if($type=='lead-source'){ $text = 'Manage Lead Source'; }elseif($type=='lead-status'){ $text = 'Manage Lead Status';}elseif($type=='rating'){ $text = 'Manage Ratings';}elseif($type=='order-status'){ $text = 'Manage Order Status';}
$add_btn_text = ''; if($type=='lead-source'){ $add_btn_text = 'Add Lead Source'; }elseif($type=='lead-status'){ $add_btn_text = 'Add Lead Status';}elseif($type=='rating'){ $add_btn_text = 'Add Rating';} elseif($type=='order-status'){ $add_btn_text = 'Add Order Status';} ?>

@slot('title')
Admin - {{$text}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$group_id = (request()->has('group_id'))?request()->group_id:'';
$old_status = (request()->has('status'))?request()->status:1;
$old_category = (request()->has('category'))?request()->category:'';
$lead_status_category_arr = config('custom.lead_status_category_arr');
$order_status_category_arr = config('custom.order_status_category_arr');
?>


<div class="centersec">
    <?php $active_menu = $type; ?>
    @if($type != 'order-status')
        @include('admin.includes.masterenquirymenu')
    @endif
    <div class="top_title_admin tab-title">
       <div class="title">
        <h5 class="title-text-left"> {{ $text }} </h5>
    </div>
    <div class="add_button">
        <?php
            /*if($parent_id) {
                if(request()->has('back_url')) {
                    $back_url= request('back_url');
                }
                else {
                    $back_url= $ADMIN_ROUTE_NAME.'/enquiries_master';
                }
            
            <a href="{{ url($back_url)}}" class="btn btn-success btn-sm pull-right">Back</a>
        <?php }*/ ?>

        @if(CustomHelper::checkPermission('all_masters','add'))
        <a href="{{ route($ADMIN_ROUTE_NAME.'.master_enquiries.add').'?type='.$type.'&back_url='.$BackUrl }}" class="btn_admin btn btn-sm btn-success pull-right" style="margin-right:5px;"><i class="fa fa-plus"></i> {{$add_btn_text}} </a>
        @endif
    </div>
</div>

@if($type == 'lead-status' || $type == 'order-status')
        <div class="bgcolor" style="padding-left: 0;">
            <div class="table-responsive">
                <form class="" method="GET">
                    <div class="col-sm-4">
                       <label>Category:</label><br/>
                       <select name="category" class="form-control">
                        <option value="">Select Category</option>
                        <?php if($type == 'lead-status'){
                            foreach ($lead_status_category_arr as $lead_status_category_key => $lead_status_category_value) { ?>
                                <option value="{{$lead_status_category_key}}" <?php if($old_category == $lead_status_category_key) { echo 'selected'; } ?>>{{$lead_status_category_value}}</option>

                        <?php } } elseif($type == 'order-status'){
                            foreach ($order_status_category_arr as $order_status_category_key => $order_status_category_value) { ?>
                                <option value="{{$order_status_category_key}}" <?php if($old_category == $order_status_category_key) { echo 'selected'; } ?>>{{$order_status_category_value}}</option>

                        <?php } } ?>
                    </select>
                </div>

                <div class="col-sm-3">
                    <input type="hidden" name="type" value="{{ old('type', $type) }}" />
                    <button type="submit" class="btn btn-success btn1search">Search</button>
                    <a href="{{ route($ADMIN_ROUTE_NAME.'.master_enquiries.index').'?type='.$type }}" class="btn_admin2 btn resetbtn btn-primary btn1search">Reset</a>
                </div>
            </form>
        </div>
    </div>
@endif


{{--<div class="row top_title_admin enquiries-top centersec" style="min-height: auto;padding-left: 0;">

    <div class="col-md-12">
        <div class="bgcolor" style="padding-left: 0;">

            <div class="table-responsive">

                <form class="" method="GET">
                    <div class="col-sm-4">
                       <label>Enquiries Master Group Name:</label><br/>
                       <select name="group_id" class="form-control">
                        <option value="">Select Group</option>
                        @foreach($groups as $group)
                        <option value="{{$group->id}}" {{($group_id == $group->id)?'selected':'' }}>{{$group->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-4">
                   <label>Show:</label><br/>
                   <select name="status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Show</option>
                    <option value="0" {{ ($old_status == '0')?'selected':'' }}>Hide</option>
                </select>
            </div> 


            <div class="col-sm-3">
                <button type="submit" class="btn btn-success btn1search">Search</button>
                <a href="{{url($ADMIN_ROUTE_NAME.'/enquiries_master')}}" class="btn_admin2 btn resetbtn btn-primary btn1search">Reset</a>
            </div>
        </form>
    </div>
</div>
</div>

</div>--}}

{{--<div class="row top_title_admin" style="margin-bottom:10px;">
    <div class="inner-breadcrumb">
        <ul>
            {!!CustomHelper::enquiryMasterBreadcrumb(request()->parent_id)!!}
        </ul>
    </div> 
</div>--}}

@include('snippets.errors')
@include('snippets.flash')

<?php
       // prd($records->count());
if(!empty($records) && $records->count() > 0){
    ?>
    <div class="bgcolor">
        <div class="table-responsive order_status_bg">

            <table class="table table-striped table-bordered">
                <tr>
                    @if($type=='lead-source' || $type=='rating')
                        <th>Name</th>
                    @endif

                    @if($type=='lead-status')
                        <th>Lead Status</th>
                        <th>Category</th>
                        <th>Description</th>
                    @endif
                    
                    @if($type=='order-status')
                        <th>Order Status</th>
                        <th>Category</th>
                        <th>Description</th>
                    @endif

                    @if($type=='rating')
                        <th width="70">Color</th>
                    @endif

                        <th>Sort Order</th>
                        <th>Show</th>
                    @if(CustomHelper::checkPermission('all_masters','edit') || CustomHelper::checkPermission('all_masters','delete'))
                        <th>Action</th>
                    @endif
                    
                </tr>
                <?php foreach($records as $record) { ?>
                    <tr>
                        @if($type=='lead-source' || $type=='rating')
                            <td>
                                <?php
                                echo $record->name;
                                ?>
                            </td>
                        @endif    

                        @if($type=='lead-status')
                            <td>
                                <?php
                                echo $record->name;
                                ?>
                            </td>
                            <td><?php echo $lead_status_category_arr[$record->category] ?? ''; ?></td>
                            <td>
                                <?php
                                echo $record->description;
                                ?>
                            </td>
                        @endif    
                        @if($type=='order-status')
                            <td>
                                <?php
                                echo $record->name;
                                ?>
                            </td>
                            <td class="bg"><?php 
                                //$order_status_category = $order_status_category_arr[$record->category] ?? '';

                                $show_color = CustomHelper::showOrderStatusColor($record->id)??''; ?>
                                {!! $show_color ??''!!}
                            </td>
                            <td>
                                <?php
                                echo $record->description;
                                ?>
                            </td>
                        @endif    

                        @if($type=='rating')
                            <td style="background-color: <?php echo $record->color_code; ?>;"></td>
                        @endif    

                            <td><?php echo $record->sort_order; ?></td>
                            <td><?php if($record->status == 1){ ?>
                                <i class="fas fa-check" style="color:green"></i>
                                <?php   }else{  ?><i class="fas fa-times" style="color:red"></i>
                            <?php } ?>
                            </td>
                    @if(CustomHelper::checkPermission('all_masters','edit') || CustomHelper::checkPermission('all_masters','delete'))
                    <td>
                        @if(CustomHelper::checkPermission('all_masters','edit'))
                        <a href="{{ route($ADMIN_ROUTE_NAME.'.master_enquiries.edit',[ $record->id, $record->parent_id]).'?type='.$type.'&back_url='.$BackUrl }}" title="Edit"><i class="fas fa-edit"></i></a>
                        @endif

                        @if(CustomHelper::checkPermission('all_masters','delete'))
                        <form method="POST" action="{{ route($ADMIN_ROUTE_NAME.'.enquiries_master.delete', $record->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Enquiries Master data ?');" id="delete-form-{{$record->id}}">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <input type="hidden" name="propertytype_id" value="<?php echo $record->id; ?>">
                        </form>
                        @endif
                    </td>
                    @endif
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php /*{{ $records->appends(request()->query())->links() }} */ ?>
</div>

<?php
}
else{
    ?>
    <div class="alert alert-warning top_title_admin">There is no dependent data present.</div>
    <?php
}
?>
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