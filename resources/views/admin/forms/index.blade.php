@component('admin.layouts.main')
    @slot('title')
        Admin - Manage Forms - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
    $back_url = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    
    $addBtn = 'Add Form';
    $title = 'Manage Forms';
    $eventID = app('request')->input('eventID');
    $old_name = (request()->has('name'))?request()->name:'';
    $old_status = (request()->has('status'))?request()->status:1;
    ?>
    <div class="centersec">

    <div class="top_title_admin">
    <div class="title">
    <h2>{{ $title }}</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('forms', 'add'))
    <a href="{{ route($routeName . '.forms.add', ['eventID' => $eventID, 'id' => 0]) . '&back_url=' . $back_url }}"
    class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i> {{ $addBtn }}</a>
    @endif
     </div>
    </div>

             <div class="centersec">
        <div class="bgcolor ">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <div class="col-md-4">
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
                        <a href="{{ route($routeName.'.forms.index') }}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
            @include('snippets.errors')
            @include('snippets.flash')

            <?php
            if(!empty($forms) && count($forms) > 0){
                ?>
           <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>Name</th>
                        <th>Short Code</th>
                        <th>Status</th>
                        @if(CustomHelper::checkPermission('forms', 'edit') || CustomHelper::checkPermission('forms', 'delete'))
                        <th>Action</th>
                        @endif
                    </tr>
                    <?php
                    foreach($forms as $form){
                    ?>
                    <tr>
                        <td>{{ $form->name }}</td>
                        <td><?php echo '['.$form->slug .']'; ?></td>
                        <td><?php if($form->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                        </td>
                        @if(CustomHelper::checkPermission('forms', 'edit') || CustomHelper::checkPermission('forms', 'delete'))
                        <td>
                            @if(CustomHelper::checkPermission('forms', 'edit'))
                            <a href="{{ route($routeName . '.forms_data.index', ['eventID' => $eventID, 'formID' => $form->id]) . '?back_url=' . $back_url }}"
                            title="Form Data"><i class="fas fa-clipboard"></i></a>
                            @endif

                            @if(CustomHelper::checkPermission('forms', 'edit'))
                            <a href="{{ route($routeName . '.forms.edit', ['eventID' => $eventID, 'id' => $form->id]) . '?back_url=' . $back_url }}"
                            title="Edit Form"><i class="fas fa-edit"></i></a>
                            @endif

                            @if(CustomHelper::checkPermission('forms', 'delete'))
                            <a style="display:none" href="javascript:void(0)" class="sbmtDelForm" id="{{ $form->id }}"
                            title="Delete Form"><i class="fas fa-trash-alt"></i></i></a>
                            <form method="POST" action="{{ route($routeName . '.forms.delete', $form->id) }}"
                                    accept-charset="UTF-8" role="form"
                                    onsubmit="return confirm('Do you really want to remove this Form?');"
                                    id="delete-form-{{ $form->id }}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="banner_id" value="<?php echo $form->id; ?>">
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
            {{ $forms->appends(request()->query())->links() }}

            <?php
        }
        else{
            ?>
            <div class="alert alert-warning">There are no Records at the present.</div>
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
