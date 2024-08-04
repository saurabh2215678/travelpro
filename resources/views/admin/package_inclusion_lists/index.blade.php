@component('admin.layouts.main')

    @slot('title')
        Admin - Manage {{ucfirst($segment)}} Inclusion List - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot
    
    @slot('headerBlock')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    @endslot
    
    <?php 
    $back_url=CustomHelper::BackUrl(); 
    $routeName = CustomHelper::getAdminRouteName();
    $old_title = (request()->has('title'))?request()->title:'';
    $old_status = (request()->has('status'))?request()->status:1;
    ?>
<!-- Start Search box section     -->
<div class="centersec">
    <?php
      $active_menu = "packages.lists";
      ?>
      @include('admin.includes.packagemenu')
     <div class="top_title_admin tab-title">
    <div class="title">
    <h2>Manage {{ucfirst($segment)}} Inclusion List ({{$package_lists->total()}})</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('all_masters','add'))
    <a href="{{ route($routeName.'.'.$segment.'.list_add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add {{ucfirst($segment)}} Inclusion List</a>
    @endif
    </div>
    </div>
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
                        <a href="{{route($routeName.'.'.$segment.'.package_inclusion_lists')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

<!-- End Search box Section -->

            @include('snippets.errors')
            @include('snippets.flash')

            <?php
            if(!empty($package_lists) && count($package_lists) > 0){
                ?>

                <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th>Title</th>
                            <th>Icon</th>
                            <th>SortBy</th>
                            <th style="text-align:center;">Status</th>
                            @if(CustomHelper::checkPermission('all_masters','edit') || CustomHelper::checkPermission('all_masters','delete'))
                            <th>Action</th>
                            @endif
                        </tr>
                            <?php
                            foreach($package_lists as $package_list){

                                $image = (isset($package_list->image))?$package_list->image:'';
                                // $storage = Storage::disk('public');
                                $path = 'inclusion/';
                            ?>
                        <tr> 
                            <td><?php echo $package_list->title; ?></td>
                            <td>
                                <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 25px;"><br>
                            </td>
                            <td>{{$package_list->sort_order}}</td>
                            <td style="text-align: center;">
                                @if(CustomHelper::checkPermission('all_masters','edit')) 
                                <input data-id="{{ CustomHelper::getStatusStr($package_list->status) }}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-type-id="{{$package_list->id}}" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $package_list->status ? 'checked' : '' }} >
                                @else
                                <?php if($package_list->status == 1){ ?>
                                <i class="fas fa-check" style="color:green"></i>
                                <?php   }else{  ?><i class="fas fa-times" style="color:red"></i>
                            <?php } ?>
                            @endif
                            </td>
                             @if(CustomHelper::checkPermission('all_masters','edit') || CustomHelper::checkPermission('all_masters','delete'))
                            <td>
                                <?php /* <a href="{{route($routeName.'.packages.list_view',[$package_list['id'], 'back_url'=>$back_url])}}" title="View"><i class="fas fa-eye"></i></a> */ ?>
                                @if(CustomHelper::checkPermission('all_masters','edit'))
                                <a href="{{ route($routeName.'.'.$segment.'.list_edit', $package_list->id.'?back_url='.$back_url) }}" title="Edit Package Inclusion List"><i class="fas fa-edit"></i></a>
                                @endif
                                  @if(CustomHelper::checkPermission('all_masters','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$package_list->id}}" title="Delete {{ucfirst($segment)}} Inclusion List"><i class="fas fa-trash-alt"></i></i></a>
                                
                                 <form method="POST" action="{{ route($routeName.'.'.$segment.'.list_delete', $package_list->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove this {{ucfirst($segment)}} Inclusion List?');" id="delete-form-{{$package_list->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="id" value="<?php echo $package_list->id; ?>">
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
              {{ $package_lists->appends(request()->query())->links('vendor.pagination.default') }}
            
            <?php
            }
            else{
            ?>
            <div class="alert alert-warning">No {{ucfirst($segment)}} Inclusion List found.</div>
            <?php
        }
            ?>
      </div>
  

@slot('bottomBlock')
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
  $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var id = $(this).attr('data-type-id'); 
         alert('Are you sure you want to change this status?');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route($routeName.".packages.ChangeUserStatus") }}',
            data: {'status': status, 'id': id},
            success: function(data){
              console.log(data.success)
              setTimeout(function() {
                location.reload();
            }, 300);
            }
        });
    })
  })
</script>
<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
</script>
@endslot

@endcomponent

