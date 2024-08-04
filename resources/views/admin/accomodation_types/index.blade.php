@component('admin.layouts.main')

    @slot('title')
        Admin - Manage Accommodation Types - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"  />
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    @endslot
<style>
    .toptab li a:hover {background-color: #162e44;}
    .nav-tabs>li>a {font-size:12px;}
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
   $active_menu = "accoommodation_type";
   ?>
   <div class="page_btns">
   @include('admin.includes.accommodationmenu')
</div>
   <div class="top_title_admin">
    <div class="title">
    <h2>Manage Accommodation Types ({{ $roomfeatures->total() }})</h2>
    </div>
    <div class="add_button">
        @if(CustomHelper::checkPermission('all_masters','add'))
    <a href="{{ route($routeName.'.accommodations.accoomodation_type_add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i>Add Accommodation Type </a>
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
                        <a href="{{url('admin/accommodations/accoommodation_type')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

<!-- End Search box Section -->
            @include('snippets.errors')
            @include('snippets.flash')

            <?php
            if(!empty($roomfeatures) && count($roomfeatures) > 0){
                ?>
                <div class="table-responsive mt0">
                    <table class="table table-bordered">
                        <tr>
                            <th>Title</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th>Action</th>
                        </tr>
                            <?php
                            foreach($roomfeatures as $roomFea){
                            ?>
                        <tr> 
                            <td><?php echo $roomFea->title; ?></td>
                            <td>{{$roomFea->sort_order}}</td>
                            <td>
                                <input data-id="{{ CustomHelper::getStatusStr($roomFea->status) }}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-type-id="{{$roomFea->id}}" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $roomFea->status ? 'checked' : '' }} >
                            </td>
                            <td>{{ CustomHelper::DateFormat($roomFea->created_at, 'd/m/Y') }}</td>
                            <td>
                                @if(CustomHelper::checkPermission('all_masters','view'))
                                <a href="{{route($routeName.'.accommodations.accoomodation_type_view',[$roomFea['id'], 'back_url'=>$back_url])}}" title="View Accommodation Type"><i class="fas fa-eye"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('all_masters','edit'))
                                <a href="{{ route($routeName.'.accommodations.accoomodation_type_edit', $roomFea->id.'?back_url='.$back_url) }}" title="Edit Accommodation Type"><i class="fas fa-edit"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('all_masters','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$roomFea->id}}" title="Delete Accommodation Type"><i class="fas fa-trash-alt"></i></i></a>

                                 <form method="POST" action="{{ route($routeName.'.accommodations.accoomodationType_delete', $roomFea->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove?');" id="delete-form-{{$roomFea->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="id" value="<?php echo $roomFea->id; ?>">
                                    </form>
                                    @endif
                            </td>
                        </tr>

                        <?php
                            }
                        ?>
                    </table>
                </div>
              {{ $roomfeatures->appends(request()->query())->links('vendor.pagination.default') }}
            
            <?php
            }
            else{
            ?>
            <div class="alert alert-warning">No Accommodation Type found.</div>
            <?php
        }
            ?>

        </div>
    </div>
@slot('bottomBlock')
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
  $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var id = $(this).attr('data-type-id'); 
         alert('Are you sure you want to change this situation?');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route($routeName.".accommodations.changeAccTypeStatus") }}',
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