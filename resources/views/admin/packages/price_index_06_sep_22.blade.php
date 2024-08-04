@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
    ?>
    <div class="top_title_admin">
    <div class="title">
    <h2>{{ $heading }}</h2>
    </div>
    <div class="add_button">
    <a href="{{ route('admin.packages.price_add', $package_id) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Price Info</a>
    </div>
    </div>

    @include('snippets.errors')
    @include('snippets.flash')

    <?php if(!($package_prices->isEmpty())){ ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">

                <tr>
                    <th width="45%" class="text-center">Title</th>
                    <th width="20%" class="text-center">Status</th>
                    <th width="20%" class="text-center">Date Created</th>
                    <th width="15%" class="text-center">Action</th>
                </tr>

                <?php foreach($package_prices as $info){ ?>
                    <tr>
                        <td>{{$info->title }} {{ ($info->is_default==1) ? "(Default)":"" }}</td>
                        <td>{{ CustomHelper::getStatusStr($info->status) }}</td>
                        <td>{{ CustomHelper::DateFormat($info->created_at, 'd/m/Y') }}</td>
                        <td>
                            <a href="{{ route($routeName.'.packages.price_edit', ['package_id'=>$package_id,'info_id'=>$info->id, 'back_url'=>$BackUrl]) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                            <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                            <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.packages.price_delete', ['package_id'=>$package_id,'info_id'=>$info->id, 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                            {{ csrf_field() }}
                            </form>
                        </td>

                    </tr>
                <?php } ?>
            </table>
        </div>
        {{ $package_prices->appends(request()->query())->links('vendor.pagination.default') }}
    <?php }else{ ?>
    <div class="alert alert-warning">There are no Price Info (Package) at the present.</div>
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