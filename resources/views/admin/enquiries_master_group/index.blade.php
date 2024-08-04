@component('admin.layouts.main')

@slot('title')
Admin - Manage Enquiries Master Group Data - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
?>
<div class="row top_title_admin enquiries-top" style="padding-left:0; padding-right:0;">
<div class="col-md-12">
 <?php 
          $active_menu = "enquiries_master_group";
        ?>
        @include('admin.includes.enquirymastermenu')
        </div>
</div>

<div class="row top_title_admin enquiries-top" style="padding-left:0; padding-right:0;">

        <div class="col-md-6">
        <h5 class="title-text-left">Manage Enquiries Master Group Data </h5>

        </div>

        <div class="col-md-6 enquiries_btn">
            @if(CustomHelper::checkPermission('enquiries_master','add'))
             <a href="{{ route($ADMIN_ROUTE_NAME.'.enquiries_master_group.add').'?back_url='.$BackUrl }}" class="btn_admin btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i> Add Enquiries Master Group Data</a>
             @endif
            </div>

       <!--  <div class="bgcolor" style="margin-bottom:10px;">
            <div class="inner-breadcrumb">
                <ul>
                    {!!CustomHelper::enquiryMasterBreadcrumb(request()->parent_id)!!}
                </ul>
            </div> 
        </div> -->
</div>
        @include('snippets.errors')
        @include('snippets.flash')

        <?php
       // prd($records->count());
        if(!empty($records) && $records->count() > 0){
            ?>
            <div class="row top_title_admin enquiries-top centersec" style="min-height: auto;">

<div class="col-md-12">
    <div class="bgcolor">
            <div class="table-responsive">

                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Name of the Enquiries Master Group data</th>
                        <th>Status</th>
                        <th>Action</th>
                        @if(CustomHelper::checkPermission('activitylogs','view'))
                        <th>View History</th>
                        @endif
                    </tr>
                    <?php
                    foreach($records as $record) {
                        //echo $record->id;
                        // prd($record->Children);
                        ?>
                        
                        <tr>
                            <td><a href="{{ url($ADMIN_ROUTE_NAME.'/enquiries_master?group_id=') }}{{$record->id}}" title="List Enquiries Master Group">{{$record->name}}</a></td>


                            <td><?php if($record->status == 1){ ?>
                                <i class="fas fa-check" style="color:green"></i>
                                <?php   }else{  ?><i class="fas fa-times" style="color:red"></i>
                                <?php } ?>
                            </td>

                            <td>
                                 @if(CustomHelper::checkPermission('enquiries_master','edit'))
                                  <a href="{{ route($ADMIN_ROUTE_NAME.'.enquiries_master_group.edit', $record->id.'?back_url='.$BackUrl) }}" title="Edit"><i class="fas fa-edit"></i></a>
                                  @endif

                                   @if(CustomHelper::checkPermission('enquiries_master','delete'))
                                    <form method="POST" action="{{ route($ADMIN_ROUTE_NAME.'.enquiries_master_group.delete', $record->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Enquiries Master  Group data ?');" id="delete-form-{{$record->id}}">
                                        {{ csrf_field() }}
                                        {{ method_field('POST') }}
                                        <input type="hidden" name="propertytype_id" value="<?php echo $record->id; ?>">

                                    </form>
                                    @endif
                              
                            </td>
                            @if(CustomHelper::checkPermission('activitylogs','view'))
                             <td>
                                <?php /*{{ route($ADMIN_ROUTE_NAME.'.activitylogs.index','moduleid='.$record->id.'&module=EnquiriesMasterGroup') }}*/ ?>
                                <a href="" title="View History" target="_blank"><i class="fas fa-history"></i></a>
                            </td>
                                @endif
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
                <!-- {{ $records->appends(request()->query())->links() }} -->
            </div>
            </div>
                <!-- {{ $records->appends(request()->query())->links() }} -->
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