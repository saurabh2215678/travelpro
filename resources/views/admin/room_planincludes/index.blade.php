@component('admin.layouts.main')

@slot('title')
Admin - Room Plan Inclusion - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endslot
<style>
    .page_btns:before {height: 0;}
    .page_btns {margin-bottom: 0;margin-top: 10px;}
</style>
<?php 
$back_url=CustomHelper::BackUrl(); 
$routeName = CustomHelper::getAdminRouteName();
$old_title = (request()->has('title'))?request()->title:'';
$old_status = (request()->has('status'))?request()->status:1;
?>

<!-- Start Search box section-->
 <?php
 $active_menu = "plan_includes";
 ?>
 <div class="page_btns">
 @include('admin.includes.accommodationmenu')
 </div>
 <div class="top_title_admin">
    <div class="title">
        <h2>Manage Accommodation Plan Inclusion ({{ $features->total() }})</h2>
    </div>
    <div class="add_button">
        @if(CustomHelper::checkPermission('all_masters','add'))
        <a href="{{ route($routeName.'.accommodations.plan_include_add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Room Plan Inclusion</a>
        @endif
    </div>
</div>

<div class="centersec">
<div class="bgcolor">
    <div class="table-responsive">
        <form class="form-inline" method="GET">
            <div class="col-md-2">
                <label>Title:</label><br/>
                <input type="text" name="title" class="form-control admin_input1" value="{{$old_title}}">
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
                <a href="{{route($routeName.'.accommodations.room_plan_includes')}}" class="btn_admin2">Reset</a>

            </div>
        </form>
    </div>
</div>
<!-- End Search box Section -->

@include('snippets.errors')
@include('snippets.flash')

<?php
if(!empty($features) && count($features) > 0){
    ?>

   <div class="table-responsive">           
    <table class="table table-striped table-bordered table-hover">
            <tr>
                <th>Title</th>
                <th>SortBy</th>
                <!-- <th>Available</th> -->
                <th>Featured</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Action</th>
            </tr>


            <?php
            foreach($features as $feature){

                ?>

                <tr> 
                    <td><?php  if($feature->available == 1){ ?>
                           <i class="fa fa-check-circle-o" style="color:green"></i>
                        <?php }else{ ?>
                           <i class="fa fa-times-circle-o" style="color:red"></i>
                        <?php } ?><?php echo $feature->name; ?></td>
                    <td>{{$feature->sort_order}}</td>
                    
                   <td><?php  if($feature->is_featured == 1){ ?>
                           <i class="fas fa-check" style="color:green"></i>
                        <?php } ?>
                    </td>
                    <td><?php if($feature->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                    </td>
                    <td>{{ CustomHelper::DateFormat($feature->created_at, 'd/m/Y') }}</td>
                    <td>
                        @if(CustomHelper::checkPermission('all_masters','view'))
                        <a href="{{route($routeName.'.accommodations.plan_include_view',[$feature['id'], 'back_url'=>$back_url])}}" title="View"><i class="fas fa-eye"></i></a>
                        @endif

                        @if(CustomHelper::checkPermission('all_masters','edit'))
                        <a href="{{ route($routeName.'.accommodations.plan_include_edit', $feature->id.'?back_url='.$back_url) }}" title="Edit Rate Plan Inclusion"><i class="fas fa-edit"></i></a>
                        @endif
                        @if(CustomHelper::checkPermission('all_masters','delete'))
                            <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$feature->id}}" title="Delete Rate Plan Inclusion"><i class="fas fa-trash-alt"></i></i></a>
                        <form method="POST" action="{{ route($routeName.'.accommodations.plan_include_delete', $feature->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove?');" id="delete-form-{{$feature->id}}">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}
                        <input type="hidden" name="id" value="<?php echo $feature->id; ?>">
                        </form>
                        @endif
                    </td>
                </tr>

                <?php
            }
            ?>
        </table>
    </div>
    {{ $features->appends(request()->query())->links('vendor.pagination.default') }}

    <?php
}
else{
    ?>
    <div class="alert alert-warning">No Accommodation Feature found.</div>
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

