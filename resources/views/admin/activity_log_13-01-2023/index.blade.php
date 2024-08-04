@component('admin.layouts.main')

    @slot('title')
        Admin - Activity Log - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
    $back_url=CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $id = (request()->has('id'))?request()->id:'';
    $old_action_type = (request()->has('action_type'))?request()->action_type:'';
    ?>




    <!-- Start Search box section     -->
    <div class="centersec">
        <div class="bgcolor">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-4">
                        <label>Action Type:</label><br/>
                        <input type="text" name="action_type" class="form-control admin_input1" value="{{$old_action_type}}">
                    </div>
                   <!--  <div class="clearfix"></div> -->
                    <div class="col-md-6">
                        <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{url('admin/activities')}}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

<!-- End Search box Section -->

            @include('snippets.errors')
            @include('snippets.flash')

            <?php
            if(!empty($activities) && count($activities) > 0){
                ?>

                <div class="table-responsive mt50">
                    <table class="table table-bordered">
                        <tr>
                            <th>Action perform On Table</th>
                            <th>Action Type</th>
                            <th>Ip Address</th>
                            <th>Action Date</th>
                            <th>Action</th>
                        </tr>
                            <?php
                            foreach($activities as $activity){?>
                        <tr>
                            <td><?php echo $activity->action_table; ?></td>
                            <td><?php echo $activity->action_type; ?></td>
                            <td><?php echo $activity->ip_address; ?></td>
                            <td>{{ CustomHelper::DateFormat($activity->created_at, 'd/m/Y') }}</td>
                            <td>
                                <a href="{{route($routeName.'.activities.view',[$activity['id'], 'back_url'=>$back_url])}}" title="Activity Log View List"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
              {{ $activities->appends(request()->query())->links('vendor.pagination.default') }}
            
            <?php
            }
            else{
            ?>
            <div class="alert alert-warning">No Activity Log found.</div>
            <?php } ?>
        </div>


    @slot('bottomBlock')

    <!-- <script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
</script> -->
    
@endslot
@endcomponent