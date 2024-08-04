@component('admin.layouts.main')
    @slot('title')
        Admin - Additional Info({{ucfirst($segment)}}) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
    ?>
<?php
$active_menu = "packages".$package_id.'/additional-info';
?>
@if(!empty($package_id))
    @include('admin.includes.packageoptionmenu')
@endif

<div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
    @if(CustomHelper::checkPermission('packages','add'))
    <div class="add_button">
        <a href="{{ route('admin.'.$segment.'.info_add', $package_id) }}" class="btn_admin"><i class="fa fa-plus"></i> Add {{ucfirst($segment)}} Additional Info</a>
    </div>
    @endif
</div>
    <div class="centersec">
        <div class="bgcolor">

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>
            <?php if(!($package_infos->isEmpty())){?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <th width="45%" class="text-center">Title</th>
                                    <th width="20%" class="text-center">Status</th>
                                    <th width="20%" class="text-center">Date Created</th>
                                    <th width="15%" class="text-center">Action</th>
                                </tr>

                                <?php foreach($package_infos as $info){ ?>
                                    <tr>
                                        <td>{{$info->title}}</td>
                                        <td><?php if($info->status == 1){ ?>
                                            <span style="color:green">Active</span>
                                            <?php   }else{  ?><span style="color:red">Inactive</span>
                                        <?php } ?>
                                    </td>
                                    <td>{{ CustomHelper::DateFormat($info->created_at, 'd/m/Y') }}</td>

                                    <td>
                                        @if(CustomHelper::checkPermission('packages','edit'))
                                        <a href="{{ route($routeName.'.'.$segment.'.info_edit', ['id'=>$package_id,'info_id'=>$info->id, 'back_url'=>$BackUrl]) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                                        @endif

                                        @if(CustomHelper::checkPermission('packages','delete'))
                                        <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                        <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.'.$segment.'.info_delete', ['id'=>$package_id,'info_id'=>$info->id, 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove to this info?');" class="delForm">
                                            {{ csrf_field() }}
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            <?php } ?>
                            </table>
                        </div>
                    
            {{ $package_infos->appends(request()->query())->links('vendor.pagination.default') }}
           <?php
       }else{
        ?>
        <div class="alert alert-warning">There are no Additional Info Block({{ucfirst($segment)}}) at the present.</div>
        <?php } ?>
        </div>
    </div>
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