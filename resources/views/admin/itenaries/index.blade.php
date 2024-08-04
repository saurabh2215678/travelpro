@component('admin.layouts.main')

    @slot('title')
        Admin - Manage Itinerary - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php 
    $back_url = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $old_day_title = (request()->has('day_title'))?request()->day_title:'';
    $old_status = app('request')->input('status') ?? 1;
    ?>

<?php
$active_menu = $segment.$package_id.'/itenaries';
?>
@if(!empty($package_id))
    @include('admin.includes.packageoptionmenu')
@endif
<div class="top_title_admin">
    <div class="title">
        <h2>{{ $heading }}</h2>
    </div>
    @if(CustomHelper::checkPermission('packages','add'))
    <div class="add_button">
        <a href="{{ route($routeName.'.'.$segment.'.itenary_add',['package_id'=>$package_id]).'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i> Add Itinerary</a>
    </div>
    @endif
</div>
<style>
    .autoCounter_table tbody tbody{counter-reset: section;}
    .autoCounter_table tbody tr{counter-increment: section;}
    .autoCounter_table tbody td:nth-child(3):before{content: counter(section);}
</style>
<!-- Start Search box section     -->
<div class="centersec">
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-2">
                        <label>Itinerary:</label><br/>
                        <input type="text" name="day_title" class="form-control admin_input1" value="{{$old_day_title}}">
                    </div>
                     <div class="col-md-2">
                        <label>Status:</label><br/>
                        <select name="status" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="1" {{ ($old_status == '1')?'selected':'' }}>Active</option>
                            <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                   <!--  <div class="clearfix"></div> -->
                   <div class="col-md-6">
                    <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{route($routeName.'.packages.itenaries',['package_id'=>$package_id])}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

<!-- End Search box Section -->

            @include('snippets.errors')
            @include('snippets.flash')

            <br><div class="ajax_msg"></div>
            

            <?php if(!empty($itenaries) && $itenaries->count() > 0){ ?>

                
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover autoCounter_table">
                        <tr>
                            <th>Sort</th>
                            <th width="100">Place</th>
                            <th>Day</th>
                            <th>Day Title</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <tbody class="row_position">
                            <?php
                            $i=0;
                            foreach($itenaries as $itenary){
                            $i++;
                            ?>
                        <tr id="{{$itenary->id}}">
                            <td><span class="rows handle"><span style="cursor:pointer"><i class="fa fa-arrows-v" style="font-size:21px;color: #00b2a7;"></span></i> </span></td>
                            <td>{{$itenary->Location->name??''}}</td>

                            <td></td>
                            {{--<td>{{ $itenary->day }}</td>--}}

                            <td>{{ $itenary->day_title }}</td>

                            <td>{{ $itenary->title }}</td>
                            <td><?php if($itenary->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                            </td>
                            <td>
                                <a href="{{ route($routeName.'.'.$segment.'.itenary_view', ['package_id'=>$package_id,'id'=>$itenary->id,'back_url'=>$back_url]) }}" title="View Itinerary"><i class="fas fa-eye"></i></a>
                                @if(CustomHelper::checkPermission('packages','edit'))
                                <a href="{{ route($routeName.'.'.$segment.'.itenary_edit', ['package_id'=>$package_id,'id'=>$itenary->id,'back_url'=>$back_url]) }}" title="Edit Itinerary"><i class="fas fa-edit"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('packages','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$itenary->id}}" title="Delete Itinerary"><i class="fas fa-trash-alt"></i></i></a>
                                <form method="POST" action="{{ route($routeName.'.'.$segment.'.itenary_delete', ['package_id'=>$package_id,'id'=>$itenary->id]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove?');" id="delete-form-{{$itenary->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="id" value="<?php echo $itenary->id; ?>">
                                </form>
                                @endif
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                
              {{ $itenaries->appends(request()->query())->links('vendor.pagination.default') }}
            
            <?php }else{ ?>
            <div class="alert alert-warning">No Itinerary found.</div>
            <?php } ?>
            </div>

    </div>

@slot('bottomBlock')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
var PackageId = '{{$package_id}}';

$( ".row_position" ).sortable({  
        delay: 150,
        /* start: function(event, ui) {
                ui.helper.css('display', 'table')
        }, */
        handle: ".handle",
        stop: function(event, ui) {
                var selectedData = new Array();  
                $('.row_position>tr').each(function() {  
                    selectedData.push($(this).attr("id"));  
                });  
                updateOrder(selectedData);  
            }  
    });
  
    function updateOrder(data) {  
        var _token = '{{ csrf_token() }}';
        $.ajax({  
            url : "{{ route('admin.packages.update_itenaries_order') }}",
            type : 'POST',  
            data:{data:data,package_id:PackageId},
            dataType:"JSON",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            beforeSend:function(){
                $(".ajax_msg").html("");
                $(".ajax_msg").show();
            },
            success: function(response) {
                $(".ajax_msg").html(response.msg).hide();
                $(".ajax_msg").html(response.msg).fadeIn();
                // setTimeout(function() { $(".ajax_msg").hide(); }, 3000);
                setTimeout(function() { $(".ajax_msg").fadeOut(); }, 3000);
                // location.reload();
            } 
        })  
    }

</script>

<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
</script>
@endslot

@endcomponent