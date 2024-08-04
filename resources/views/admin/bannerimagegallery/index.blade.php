@component('admin.layouts.main')

@slot('title')
Admin - Manage Banners - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$old_title = (request()->has('title'))?request()->title:'';
$old_status = (request()->has('status'))?request()->status:1; 
?>

<div class="top_title_admin">
<div class="title">
<h2>Manage Banners ({{$total_banner}})</h2>
</div>
<div class="add_button">
@if(CustomHelper::checkPermission('banner_images','add'))
<a href="{{ route($ADMIN_ROUTE_NAME.'.managebanners.add').'?back_url='.$BackUrl }}" class="btn_admin"><i class="fa fa-plus"></i>Add New Banner</a>
@endif
</div>
</div>

 <!-- Start Search box section     -->
    <div class="centersec">
        <div class="bgcolor ">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-4">
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
                        <a href="{{ route($routeName.'.managebanners.index') }}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    
<!-- End Search box Section -->
        @include('snippets.errors')
        @include('snippets.flash')

        <?php
        if(!empty($banners) && count($banners) > 0){
            ?>

             <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>Title</th>
                        <th>Sub-title</th>  
                        <th>SortBy</th>
                        <th>Page</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    foreach($banners as $banner){
                        ?>
                        
                        <tr>
                            <td><?php echo $banner->title; ?></td>
                            <td>{{ $banner->subtitle }}</td>
                            <td>{{ $banner->sort_order }}</td>
                            <td>{{ (isset($page_arr[$banner->page]))?$page_arr[$banner->page]:ucwords($banner->page) }}</td>
                            <td> <?php if($banner->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                            </td>

                            <td>

                                @if(CustomHelper::checkPermission('banner_images','edit'))
                                <a href="{{ route($ADMIN_ROUTE_NAME.'.managebanners.edit', $banner->id.'?back_url='.$BackUrl) }}"><i class="fas fa-edit"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('banner_images','delete'))
                                
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$banner->id}}"><i class="fas fa-trash-alt"></i></i></a>
                                
                                <form style="display: inline-block;" method="POST" action="{{ route($ADMIN_ROUTE_NAME.'.managebanners.delete', $banner->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Banner?');" id="delete-form-{{$banner->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="banner_id" value="<?php echo $banner->id; ?>">

                                </form>

                                
                                <a href="{{ route($ADMIN_ROUTE_NAME.'.images.upload',['tid'=>$banner->id,'tbl'=>'banners']) }}" target="_blank"><i class="fas fa-image"></i></a>
                                @endif

                                <!-- admin/images/upload?id=&tbl= -->
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            {{ $banners->appends(request()->query())->links('vendor.pagination.default')  }}
            <?php
        }
        else{
            ?>
            <div class="alert alert-warning">No banners found.</div>
            <?php
        }
        ?>

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