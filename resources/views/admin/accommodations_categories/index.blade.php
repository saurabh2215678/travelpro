@component('admin.layouts.main')

@slot('title')
Admin - Accommodation Category - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
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
<!-- Start Search box section     -->

   <?php
   $active_menu = "category";
   ?>
   <div class="page_btns">
   @include('admin.includes.accommodationmenu')
   </div>
    <div class="top_title_admin">
    <div class="title">
    <h2>Manage Accommodation Category ({{ $categories->total() }})</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('all_masters','add'))
    <a href="{{ route($routeName.'.accommodations.category_add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Accommodation Category </a>
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
            <div class="col-md-6"><br>
                <button type="submit" class="btn btn-success">Search</button>
                <a href="{{url('admin/accommodations/category')}}" class="btn_admin2">Reset</a>
            </div>
        </form>
    </div>
</div>
<!-- End Search box Section -->

@include('snippets.errors')
@include('snippets.flash')

<?php
if(!empty($categories) && count($categories) > 0){
    ?>
    <div class="table-responsive">           
<table class="table table-striped table-bordered table-hover">
            <tr>
                <th>Title</th>
                <th>SortBy</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Action</th>
            </tr>
            <?php
            foreach($categories as $category_query){
                ?>
                <tr> 
                    <td><?php echo $category_query->title; ?></td>
                    <td>{{$category_query->sort_order}}</td>
                    <td> <?php if($category_query->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                    </td>
                    <td>{{ CustomHelper::DateFormat($category_query->created_at, 'd/m/Y') }}</td>
                    <td>
                        @if(CustomHelper::checkPermission('all_masters','view'))
                        <a href="{{route($routeName.'.accommodations.category_view',[$category_query['id'], 'back_url'=>$back_url])}}" title="View"><i class="fas fa-eye"></i></a>
                        @endif

                        @if(CustomHelper::checkPermission('all_masters','edit'))
                        <a href="{{ route($routeName.'.accommodations.category_edit', $category_query->id.'?back_url='.$back_url) }}" title="Edit Accommodation Category"><i class="fas fa-edit"></i></a>
                        @endif

                        @if(CustomHelper::checkPermission('all_masters','delete'))
                        <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$category_query->id}}" title="Delete Accommodation Category"><i class="fas fa-trash-alt"></i></i></a>

                        <form method="POST" action="{{ route($routeName.'.accommodations.category_delete', $category_query->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove?');" id="delete-form-{{$category_query->id}}">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <input type="hidden" name="id" value="<?php echo $category_query->id; ?>">
                        </form>
                        @endif
                    </td>
                </tr>

                <?php
            }
            ?>
        </table>
    </div>
    {{ $categories->appends(request()->query())->links('vendor.pagination.default')  }}
    
    <?php
}
else{
    ?>
    <div class="alert alert-warning">No Accommodation Category found.</div>
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

