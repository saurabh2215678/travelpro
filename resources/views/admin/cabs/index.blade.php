@component('admin.layouts.main')

@slot('title')
Admin - Manage Cabs - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endslot

<?php
$back_url = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$id = (request()->has('id'))?request()->id:'';
$old_name = (request()->has('name'))?request()->name:'';
$old_status = (request()->has('status'))?request()->status:1;
$storage = Storage::disk('public');
$path = 'cabs/';
?>

<!-- Start Search box section     -->
<div class="centersec">
    <div class="top_title_admin tab-title">
        <div class="title">
            <h2>Manage Cabs ({{ $cabs->total() }})</h2>
        </div>
        <div class="add_button">
            @if(CustomHelper::checkPermission('cabs','add'))
            <a href="{{ route($routeName.'.cabs.add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i>Add Cab</a>
            @endif
        </div>
    </div>
    <style>
        .autoCounter_table tbody tbody{counter-reset: section;}
        .autoCounter_table tbody tr{counter-increment: section;}
        .autoCounter_table tbody td:nth-child(7):before{content: counter(section);}
    </style>
    <div class="bgcolor">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <div class="col-md-2">
                    <label>Name:</label><br/>
                    <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
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
                    <a href="{{route($routeName.'.cabs.index')}}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- End Search box Section -->

    @include('snippets.errors')
    @include('snippets.flash')

    <br><div class="ajax_msg"></div>

    <?php
    if(!empty($cabs) && count($cabs) > 0){
        ?>

        <div class="table-responsive">           
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <th>Sort</th>
                    <th>Name</th>
                    <th>Equivalent</th>
                    <th>Base Fare</th>
                    <th>Driver charge<br />(Per Day)</th>
                    <th>Night Stay Allowance<br />(Per Night)</th>
                    <th>Image</th>
                    <th>Status</th>
                    <!-- <th>Date Created</th> -->
                    @if(CustomHelper::checkPermission('cabs','edit') || CustomHelper::checkPermission('cabs','delete'))
                    <th>Action</th>
                    @endif
                </tr>
                <tbody class="row_position">
                    <?php
                    $i=0;
                    foreach($cabs as $cab){
                        $i++; ?>
                        <tr class="ui-sortable-handle" id="{{$cab->id}}">
                            <td><span class="rows handle"><span style="cursor:pointer"><i class="fa fa-arrows-v" style="font-size:21px;color: #00b2a7;"></i></span> </span></td>
                            <td><?php echo $cab->name; ?></td>
                            <td><?php echo $cab->equivalent; ?></td>
                            
                            <td>{{ $cab->base_price??''}}</td>
                            <td>{{ $cab->chauffeur_charge??''}}</td>
                            <td>{{ $cab->night_stay_allowance??''}}</td>
                            <td> <?php
                            $image = $cab->image;
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
                            <td><?php if($cab->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                                <?php } ?></td>

                                @if(CustomHelper::checkPermission('cabs','edit') || CustomHelper::checkPermission('cabs','delete'))
                                <td>
                                    @if(CustomHelper::checkPermission('cabs','edit'))
                                    <a href="{{ route($routeName.'.cabs.edit', $cab->id.'?back_url='.$back_url) }}" title="Edit Cab"><i class="fas fa-edit"></i></a>
                                    @endif
                                    @if(CustomHelper::checkPermission('cabs','delete'))
                                    <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$cab->id}}" title="Delete Cab"><i class="fas fa-trash-alt"></i></i></a>

                                    <form method="POST" action="{{ route($routeName.'.cabs.delete', $cab->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove this cab?');" id="delete-form-{{$cab->id}}">
                                        {{ csrf_field() }}
                                        {{ method_field('POST') }}
                                        <input type="hidden" name="id" value="<?php echo $cab->id; ?>">
                                    </form>
                                    @endif
                                </td>
                                @endif
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            {{ $cabs->appends(request()->query())->links('vendor.pagination.default') }}

            <?php
        }
        else{
            ?>
            <div class="alert alert-warning">No Cabs found.</div>
        <?php } ?>
    </div>
    @slot('bottomBlock')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javaScript">
        $('.sbmtDelForm').click(function(){
            var id = $(this).attr('id');
            $("#delete-form-"+id).submit();
        });

        $( ".row_position" ).sortable({  
            delay: 150,
            handle: ".handle",
            stop: function(event, ui) {
                var selectedData = new Array();  
                $('.row_position>tr').each(function() {  
                    selectedData.push($(this).attr("id"));  
                });  
                updateCab(selectedData);  
            }  
        });
    </script>

    <script>  
        function updateCab(data) {  
            var _token = '{{ csrf_token() }}';
            $.ajax({  
                url : "{{ route('admin.cabs.update_cab') }}",
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
                    $(".ajax_msg").html(response.msg).hide();
                    $(".ajax_msg").html(response.msg).fadeIn();
                    setTimeout(function() { $(".ajax_msg").fadeOut(); }, 3000);
                } 
            })  
        }

    </script>

    @endslot
    @endcomponent