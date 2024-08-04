@component('admin.layouts.main')

@slot('title')
Admin - Manage Enquiries Master Data - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$group_id = (request()->has('group_id'))?request()->group_id:'';
$old_status = (request()->has('status'))?request()->status:1;

$lead_status_category_arr = config('custom.lead_status_category_arr');
?>


<div class="row top_title_admin enquiries-top" style="padding-left:0; padding-right:0;">
<div class="col-md-6">
<?php 
          $active_menu = "enquiries_master";
        ?>
        @include('admin.includes.enquirymastermenu')
        </div>
        <div class="col-md-4">
 
        <h5 class="title-text-left"> Manage Enquiries Master Data </h5>
            </div>
            <div class="col-md-2 enquiries_btn">
            <?php
            /*if($parent_id) {
                if(request()->has('back_url')) {
                    $back_url= request('back_url');
                }
                else {
                    $back_url= $ADMIN_ROUTE_NAME.'/enquiries_master';
                }
            ?>
            <a href="{{ url($back_url)}}" class="btn btn-success btn-sm pull-right">Back</a>
            <?php }*/ ?>
            @if(CustomHelper::checkPermission('enquiries_master','add'))
            <a href="{{ route($ADMIN_ROUTE_NAME.'.enquiries_master.add').'?parent_id='.$parent_id.'&back_url='.$BackUrl }}" class="btn_admin btn btn-sm btn-success pull-right" style="margin-right:5px;"><i class="fa fa-plus"></i> Add Enquiries Master Data</a>
            @endif

            </div>
</div>

        <div class="row top_title_admin enquiries-top centersec" style="min-height: auto;padding-left: 0;">

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

    </div>

        <div class="row top_title_admin" style="margin-bottom:10px;">
            <div class="inner-breadcrumb">
                <ul>
                    {!!CustomHelper::enquiryMasterBreadcrumb(request()->parent_id)!!}
                </ul>
            </div> 
        </div>

        @include('snippets.errors')
        @include('snippets.flash')

        <?php
       // prd($records->count());
        if(!empty($records) && $records->count() > 0){
            ?>
            <div class="bgcolor">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Name of the Enquiries Master data</th>
                        <th>Category</th>
                        <th>Total Data</th>
                        <th>Group Name</th>
                        <th>Sort Order</th>
                        <th>Show</th>
                        <th>Action</th>
                        @if(CustomHelper::checkPermission('activitylogs','view'))
                        <th>View History</th>
                        @endif
                    </tr>
                    <?php
                    foreach($records as $record) {
                        //prd($record->Children->toArray());
                        ?>
                        
                        <tr>
                            <td>
                                <?php
                                $link = url($ADMIN_ROUTE_NAME.'/enquiries_master?parent_id='.$record->id.'&back_url='.$BackUrl);
                                echo '<a class="tdlink" href="'.$link.'">'.$record->name.'</a>';
                                ?>
                            </td>
                            <td><?php echo $lead_status_category_arr[$record->category] ?? ''; ?></td>
                            <td><?php echo $record->child_count; ?></td>

                            <td><a href="{{ url($ADMIN_ROUTE_NAME.'/enquiries_master?group_id=') }}{{$record->group_id}}" title="List Enquiries Master Group">{{isset($record->GroupData) ? $record->GroupData->name : '' }}</a></td>

                            <td><?php echo $record->sort_order; ?></td>
                            <td><?php if($record->status == 1){ ?>
                                <i class="fas fa-check" style="color:green"></i>
                                <?php   }else{  ?><i class="fas fa-times" style="color:red"></i>
                                <?php } ?>
                            </td>

                            <td>
                                @if(CustomHelper::checkPermission('enquiries_master','edit'))
                                  <a href="{{ route($ADMIN_ROUTE_NAME.'.enquiries_master.edit',[ $record->id, $record->parent_id]) }}" title="Edit"><i class="fas fa-edit"></i></a>
                                  @endif

                                    @if(CustomHelper::checkPermission('enquiries_master','delete'))
                                    <form method="POST" action="{{ route($ADMIN_ROUTE_NAME.'.enquiries_master.delete', $record->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Enquiries Master data ?');" id="delete-form-{{$record->id}}">
                                        {{ csrf_field() }}
                                        {{ method_field('POST') }}
                                        <input type="hidden" name="propertytype_id" value="<?php echo $record->id; ?>">

                                    </form>
                                    @endif
                              

                              
                            </td>
                                @if(CustomHelper::checkPermission('activitylogs','view'))
                             <td>
                                <?php /*{{ route($ADMIN_ROUTE_NAME.'.activitylogs.index','moduleid='.$record->id.'&module=EnquiriesMaster') }}*/ ?>
                                <a href="" title="View History" target="_blank"><i class="fas fa-history"></i></a>
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