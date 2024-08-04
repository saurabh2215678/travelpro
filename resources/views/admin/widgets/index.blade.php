    @component('admin.layouts.main')

        @slot('title')
            Admin -Manage Widget - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
        @endslot

        <?php
            $BackUrl = CustomHelper::BackUrl();
            $routeName = CustomHelper::getAdminRouteName();
            $id = (request()->has('id'))?request()->id:'';
            $old_widget_name = (request()->has('widget_name'))?request()->widget_name:'';
            $old_status = (request()->has('status'))?request()->status:1;

            $storage = Storage::disk('public');
            $path = 'widgets/';
        ?>

        <!-- Start Search box section     -->
        <div class="top_title_admin">
        <div class="title">
        <h2>Manage Widgets ({{ $widgets->total() }})</h2>
        </div>
        <div class="add_button">
        @if(CustomHelper::checkPermission('widget','add'))
        <a href="{{ route('admin.widget.add', ['back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Widget</a>
        @endif
        </div>
        </div>

        <div class="centersec">
            <div class="bgcolor">
                <div class="table-responsive">
                    <form class="form-inline" method="GET">
                        <div class="col-md-4">
                            <label>Widget Name:</label><br/>
                            <input type="text" name="widget_name" class="form-control admin_input1" value="{{$old_widget_name}}">
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
                            <a href="{{url('admin/widget')}}" class="btn_admin2">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

    <!-- End Search box Section -->
                @include('snippets.errors')
                @include('snippets.flash')
                <?php
                if(!empty($widgets) && count($widgets) > 0){
                    ?>
                    <div class="table-responsive">           

                    <table class="table table-striped table-bordered table-hover">

                            <tr>
                                <th>Widget Name</th>
                                <th>Widget Identifier</th>
                                <th>Section Heading</th>
                                <th>About Widget Description</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>

                            <?php
                            foreach($widgets as $widget_query){
                            ?>
                                <tr>
                                <td>{{$widget_query->widget_name}}</td>
                                    <td>{{$widget_query->slug}}</td>
                                    <td>{!! $widget_query->section_heading !!}</td>
                                    <td>{!! $widget_query->about_widget_desc !!}</td>
                                    <td><?php if($widget_query->status == 1){ ?>
                                    <span style="color:green">Active</span>
                                    <?php   }else{  ?><span style="color:red">Inactive</span>
                                    <?php } ?>
                                    </td>
                                    <td>{{ CustomHelper::DateFormat($widget_query->created_at, 'd/m/Y') }}</td>

                                    <td>
                                        @if(CustomHelper::checkPermission('widget','view'))
                                        {{--<a href="{{route($routeName.'.widget.view',[$widget_query['id'], 'back_url'=>$BackUrl])}}" title="View Widget"><i class="fas fa-eye"></i></a>--}}
                                        <a href="javascript:void(0);" data-src="<?php echo route($routeName.'.widget.view',[$widget_query['id']]) ?>" data-fancybox data-type="ajax" title="View Widget"><i class="fas fa-eye"></i></a>
                                        @endif

                                        @if(CustomHelper::checkPermission('widget','edit'))
                                        <a href="{{ route($routeName.'.widget.edit', $widget_query->id) }}" class="" title="Edit Widget"><i class="fas fa-edit"></i> </a>
                                        @endif

                                        @if(CustomHelper::checkPermission('widget','delete'))
                                        <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete Widget"><i class="fas fa-trash-alt"></i></a>
                                        <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.widget.delete', [$widget_query['id'], 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                            {{ csrf_field() }}
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                    {{ $widgets->appends(request()->query())->links('vendor.pagination.default') }}
               <?php
           }else{
            ?>
            <div class="alert alert-warning">There are no Widget at the present.</div>
            <?php } ?>
            </div>
        @slot('bottomBlock')
        <script type="text/javascript">
            $(document).on("click", ".sbmtDelForm", function(e){
                e.preventDefault();
                $(this).siblings("form.delForm").submit();                
            });
        </script>
        @endslot
    @endcomponent