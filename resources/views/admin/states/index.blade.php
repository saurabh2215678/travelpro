	@component('admin.layouts.main')

    @slot('title')
        Admin - Manage States - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
        $old_name = (request()->has('name'))?request()->name:'';
        // $storage = Storage::disk('public');
    ?>

<div class="top_title_admin">
    <div class="title">
    <h2>State List ({{ $states->total() }})</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('states','add'))
            <a href="{{ url($routeName.'/states/save') }}" class="btn_admin"><i class="fa fa-plus"></i> Add State</a>
            @endif       
    </div>
    </div>


  
    <div class="centersec">
    <div class="bgcolor">

            <div class="table-responsive">

                <form class="form-inline" method="GET">
                    <div class="col-md-4">
                        <label>State Name:</label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
                    </div>                   

                   <!--  <div class="clearfix"></div> -->                    

                    <div class="col-md-6">
                        <label</label><br/>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{url('admin/states')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
 




    @include('snippets.errors')
    @include('snippets.flash')

    <?php if(!empty($states) && $states->count() > 0){ ?>
        <div class="table-responsive">

        {{ $states->appends(request()->query())->links('vendor.pagination.default') }}

            <table class="table table-bordered">
                <tr>
                    <th class="">Name</th>
                    <th class="">Country</th>
                    <th class="">Status</th>
                    <th class="">Action</th>
                </tr>
                <?php foreach ($states as $state){ ?>
                    <tr>
                        <td>{{$state->name}}</td>
                        <td><?php echo $state->country->name; ?></td>
                        <td><?php  echo ($state->status==1)?'Active':'Inactive';  ?></td>

                        <td>
                            @if(CustomHelper::checkPermission('states','edit'))
                            <a href="{{route($routeName.'.states.save', ['id'=>$state->id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        {{ $states->appends(request()->query())->links('vendor.pagination.default') }}
        <?php }else{ ?>
        <div class="alert alert-warning">There are no Records at the present.</div>
        <?php } ?>
    </div>
</div>
@endcomponent

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
  $(".tooltip_title").tooltip();

    $( function() {
        $( ".to_date, .from_date" ).datepicker({
            dateFormat:'dd/mm/yy',
            changeMonth:true,
            changeYear: true,
            yearRange:"1950:0+"
        });
    });

    $(document).on("click", ".product_status", function(){

        var curr_sel = $(this);

        var product_id = $(this).attr('data-id');
        var curr_status = $(this).attr('data-status');
        
        var conf = confirm("Are you sure to change status of this Product?");
        
        if(conf){

            _token = '{{csrf_token()}}';
            
            $.ajax({
                url: "{{ url($routeName.'/products/ajax_change_status') }}",
                method: 'POST',
                data:{product_id, curr_status},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                beforeSend:function(){},
                success: function(resp) {
                    if(resp.success == true){
                        curr_sel.parent().html(resp.status_html);
                        //curr_sel.remove();
                    } else {

                    }
                },
                error: function(resp) {

                }
            });
        }
    });
    
</script>