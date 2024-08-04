@component('admin.layouts.main')

@slot('title')
Admin -Manage Team Management - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$id = (request()->has('id'))?request()->id:'';
$old_title = (request()->has('title'))?request()->title:'';
$old_status = (request()->has('status'))?request()->status:1; 
$old_featured = (request()->has('featured'))?request()->featured:''; 

$team_listing = CustomHelper::getSeoModules('team_listing');
$teamListing = $team_listing->page_url??'team';

$storage = Storage::disk('public');
$path = 'teammanagements/';
?>
<!-- Start Search box section     -->
<div class="top_title_admin">
    <div class="title">
        <h2>Manage Teams ({{ count($teammanagements) }})</h2>
    </div>
    <div class="add_button">
     @if(CustomHelper::checkPermission('teammanagements','add'))
     <a href="{{ route('admin.teammanagements.add', ['back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Teams</a>
     @endif
 </div>
</div>

<style>
    /* .autoCounter_table tbody tbody{counter-reset: section;}
    .autoCounter_table tbody tr{counter-increment: section;}
    .autoCounter_table tbody td:nth-child(7):before{content: counter(section);} */
    </style>
    <div class="centersec">
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-3">
                        <label>Name:</label><br/>
                        <input type="text" name="title" class="form-control admin_input1" value="{{$old_title}}">
                    </div>
                    <div class="col-md-3">
                        <label>Status:</label><br/>
                        <select name="status" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                            <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Featured:</label><br/>
                        <select name="featured" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="1" {{ ($old_featured == '1')?'selected':'' }}>Active</option>
                        </select>
                    </div>

                    <!--  <div class="clearfix"></div> -->
                    <div class="col-md-3"><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{url('admin/teammanagements')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Search box Section -->
        @include('snippets.errors')
        @include('snippets.flash')

        <br><div class="ajax_msg"></div>
        <?php
        if(!empty($teammanagements) && count($teammanagements) > 0){
            ?>
            <div class="table-responsive">           
                <table class="table table-striped table-bordered table-hover main-table sort-table autoCounter_table">
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Profile Photo</th>
                        <th>Contact Phone</th>
                        <th>Contact Email</th>
                        <!-- <th>Display Order</th> -->
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Date Created</th>
                        <th>Action</th>
                    </tr>

                    <tbody class="row_position">
                        <?php
                        $i=0;
                        foreach($teammanagements as $teamManagenent){
                            $i++;    
                            ?>
                            <tr class="ui-sortable-handle" id="{{$teamManagenent->id}}">
                                <td><span class="rows handle"><span style="cursor:pointer"><i class="fa fa-arrows-v" style="font-size:21px;color: #00b2a7;"></i></span> </span></td>
                                <td>{{$teamManagenent->title}}</td>
                                <td>{{$teamManagenent->sub_title}}</td>
                                <td>
                                    <?php
                                    $image = $teamManagenent->image;
                                    if(!empty($image)){
                                        if($storage->exists($path.$image)){
                                            ?>
                                            <div class="col-md-2 image_box">
                                                <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                                            </div>
                                        <?php }}else{
                                            echo "No Image";
                                        } ?>
                                    </td>
                                    <td>{{$teamManagenent->mobile_no}}</td>
                                    <td>{{$teamManagenent->email}}</td>
                                    <!-- <td></td> -->
                                    <?php /* <td>{{ CustomHelper::getStatusStr($teamManagenent->status) }}</td> */ ?>
                                    <td>
                                       <?php if($teamManagenent->status == 1){ ?>
                                        <span style="color:green">Active</span>
                                        <?php   }else{  ?><span style="color:red">Inactive</span>
                                    <?php } ?>
                                </td>
                                <td><?php  if($teamManagenent->featured == 1){ ?>
                                    <i class="fas fa-check" style="color:green"></i>
                                <?php   } ?>  
                            </td>

                            <td>{{ CustomHelper::DateFormat($teamManagenent->created_at, 'd/m/Y') }}</td>

                            <td>
                                {{--<a href="{{route($routeName.'.teammanagements.view',[$teamManagenent['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>--}}
                                <a href="javascript:void(0);" data-src="<?php echo route($routeName.'.teammanagements.view', [$teamManagenent['id']]) ?>" data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                @if(CustomHelper::checkPermission('teammanagements','edit'))
                                <a href="{{ route($routeName.'.teammanagements.edit', $teamManagenent->id) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                                @endif
                                @if(CustomHelper::checkPermission('teammanagements','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.teammanagements.delete', [$teamManagenent['id'], 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                    {{ csrf_field() }}
                                </form>
                                @endif
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        {{--{{ $teammanagements->appends(request()->query())->links('vendor.pagination.default') }}--}}
        <?php
    }else{ ?>
        <div class="alert alert-warning">There are no Team Management at the present.</div>
    <?php } ?>
</div>
</div>

@slot('bottomBlock')

<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- ===========  Drag & Drop Table =======    -->
<script>
  /*   $("tbody").sortable({
        cursor: 'row-resize',
        placeholder: 'ui-state-highlight',
        opacity: '0.8',
        items: '.ui-sortable-handle',
        stop: function(event, ui) {
                var selectedData = new Array();  
                $('.row_position .ui-sortable-handle').each(function() {  
                    selectedData.push($(this).attr("id"));  
                });  
                updateOrder(selectedData);  
            }
        }).disableSelection(); */

    $( ".row_position" ).sortable({  
        delay: 150,
        handle: ".handle",
        stop: function(event, ui) {
            var selectedData = new Array();  
            $('.row_position>tr').each(function() {  
                selectedData.push($(this).attr("id"));  
            });  
            updateOrder(selectedData);  
        }  
    });

</script>
<script>  
    function updateOrder(data) {  
        var _token = '{{ csrf_token() }}';
        $.ajax({  
            url : "{{ route('admin.teammanagements.update_order') }}",
            type : 'POST',  
            data:{data:data},
            dataType:"JSON",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            beforeSend:function(){
                $(".ajax_msg").html("");
                $(".ajax_msg").show();
            },
            success: function(response) {
                // location.reload();
                $(".ajax_msg").html(response.msg).hide();
                $(".ajax_msg").html(response.msg).fadeIn();
                // setTimeout(function() { $(".ajax_msg").hide(); }, 3000);
                setTimeout(function() { $(".ajax_msg").fadeOut(); }, 3000);
            } 
        })  
    }
</script>
<script type="text/javascript">
    $(document).on("click", ".sbmtDelForm", function(e){
        e.preventDefault();

        $(this).siblings("form.delForm").submit();                
    });
</script>
@endslot
@endcomponent