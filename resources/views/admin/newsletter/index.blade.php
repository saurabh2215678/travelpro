	@component('admin.layouts.main')

    @slot('title')
    Admin - Newsletters - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
    // $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $old_name = (request()->has('name'))?request()->name:'';
    $old_email = (request()->has('email'))?request()->email:'';
    $old_phone = (request()->has('phone'))?request()->phone:'';
    $old_from = app('request')->input('from');
    $old_to = app('request')->input('to');
    ?>
    <div class="top_title_admin">
        <div class="title">
            <h2>Newsletter Subscriber ({{ $newsletters->count() }})</h2>
        </div>
        <div class="add_button">
            @if(CustomHelper::checkPermission('newsletter','export'))
            <button type="button" onclick="exportList('export_xls')" class="btn_admin" ><i class="fa fa-table"></i> Export XLS</button>
            @endif

        </div>
    </div>

    <!-- Start Search box section     -->
    <div class="centersec">
        <div class="bgcolor ">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-3">
                        <label>Name:</label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}" autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        <label>Email:</label><br/>
                        <input type="text" name="email" class="form-control admin_input1" value="{{$old_email}}" autocomplete="off">
                    </div>

                    <div class="col-md-3">
                        <label>Whatsapp No.:</label><br/>
                        <input type="text" name="phone" class="form-control admin_input1" value="{{$old_phone}}" autocomplete="off">
                    </div>

                    <div class="col-md-3">
                        <label>From Date:</label><br/>
                        <input type="text" name="from" class="form-control admin_input1 to_date" value="{{$old_from}}" autocomplete="off" >
                    </div>

                    <div class="col-md-3">
                        <label>To Date:</label><br/>
                        <input type="text" name="to" class="form-control admin_input1 from_date" value="{{$old_to}}" autocomplete="off" >
                    </div>

                    <!--  <div class="clearfix"></div> -->
                    <div class="col-md-3">
                        <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{ route($routeName.'.newsletter.index') }}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- End Search box Section -->
        <form name="exportForm" method="" action="" >
            {{ csrf_field() }}
            <input type="hidden" name="export_xls" value="">
            <?php
            if(count(request()->input())){
                foreach(request()->input() as $input_name=>$input_val){
                    ?>
                    <input type="hidden" name="{{$input_name}}" value="{{$input_val}}">
                    <?php
                }
            }
            ?>
        </form>

        @include('snippets.errors')
        @include('snippets.flash')

        <?php
        if(!empty($newsletters) && $newsletters->count() > 0){
            ?>
            <div class="table-responsive">           
                <table class="table table-striped table-bordered table-hover">
                    <!-- {{ $newsletters->appends(request()->query())->links() }} -->

                    <tr>
                        <th class="">Name</th>
                        <th class="">Email</th>
                        <th class="">Whatsapp No.</th>
                        <th>Date Added</th>
                        @if(CustomHelper::checkPermission('newsletter','delete'))
                        <th class="">Action</th>
                        @endif
                    </tr>
                    <?php
                    foreach ($newsletters as $newsletter){
                       ?>
                       <tr>
                        <td>{{$newsletter->name}}</td>
                        <td>{{$newsletter->email}}</td>
                        <td>{{$newsletter->phone}}</td>
                        <td>{{ CustomHelper::DateFormat($newsletter->created_at, 'd/m/Y') }}</td>
                        @if(CustomHelper::checkPermission('newsletter','delete'))
                        <td>   
                            <a href="javascript:void(0)" title="Delete" class="delBtn"><i class="fas fa-trash"></i></a>

                            <form method="post" class="delForm" action="{{url($routeName.'/newsletter/delete/'.$newsletter->id)}}">
                                {{csrf_field()}}
                            </form>
                        </td>
                        @endif
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        {{ $newsletters->appends(request()->query())->links('vendor.pagination.default') }}
        <?php
    }
    else{
        ?>
        <div class="alert alert-warning">There are no Records at the present.</div>
        <?php
    }
    ?>
</div>

@slot('bottomBlock')
<link rel="stylesheet" href="{{ url('css/jquery-ui.css') }}">
<script type="text/javascript" src="{{ url('js/jquery-ui.js') }}"></script>
<script type="text/javaScript">
    $( function() {
        $( ".to_date, .from_date" ).datepicker({
            changeMonth: true,
            changeYear: true,
            'dateFormat':'dd/mm/yy'
        });
    });
</script>
<script type="text/javascript">
    $(".delBtn").click(function(){
        var conf = confirm("Are you sure you want to delete this record?");

        if(conf){
            $(this).siblings(".delForm").submit();
        }
    });

    function exportList(exportName){

        if(exportName ){
            if( exportName == 'export_xls'){
                var exportForm = $("form[name='exportForm']");
                exportForm.find("input[name='export_xls']").val('1');
                exportForm.find("input[name='export_inventory']").val('');
                document.exportForm.submit();
            }
        }
    }
</script>
@endslot
@endcomponent

