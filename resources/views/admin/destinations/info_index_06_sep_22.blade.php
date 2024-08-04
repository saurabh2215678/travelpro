@component('admin.layouts.main')

    @slot('title')
        Admin - Additional Info(Destination) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
    ?>
    <div class="top_title_admin">
    <div class="title">
    <h2>Additional Info</h2>
    </div>
    <div class="add_button">
    <a href="{{ route('admin.destinations.info_add', $destination_id) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Additional Info</a>
    </div>
    </div>
    

            @include('snippets.errors')
            @include('snippets.flash')

            <?php if(!($destination_infos->isEmpty())){?>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">

                        <tr>
                            <th width="45%" class="text-center">Title</th>
                            <th width="20%" class="text-center">Status</th>
                            <th width="20%" class="text-center">Date Created</th>
                            <th width="15%" class="text-center">Action</th>
                        </tr>

                        <?php
                        foreach($destination_infos as $info){
                        ?>
                            <tr>
                                <td>{{$info->title}}</td>
                                <td>{{ CustomHelper::getStatusStr($info->status) }}</td>
                                <td>{{ CustomHelper::DateFormat($info->created_at, 'd/m/Y') }}</td>

                                <td>
                                    <a href="{{ route($routeName.'.destinations.info_edit', ['id'=>$destination_id,'info_id'=>$info->id, 'back_url'=>$BackUrl]) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                                    <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                    <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.destinations.info_delete', ['id'=>$destination_id,'info_id'=>$info->id, 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                    {{ csrf_field() }}
                                    </form>
                                </td>

                            </tr>
                        <?php } ?>
                    </table>
                </div>
                {{ $destination_infos->appends(request()->query())->links('vendor.pagination.default') }}
           <?php
       }else{
        ?>
        <div class="alert alert-warning">There are no Additional Info Block(Destination) at the present.</div>
        <?php } ?>
        </div>

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