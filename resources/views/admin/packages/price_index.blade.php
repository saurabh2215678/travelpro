@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
    ?>


<?php
$active_menu = "packages".$package_id.'/price-info';
?>
@if(!empty($package_id))
    @include('admin.includes.packageoptionmenu')
@endif

    <div class="top_title_admin">
    <div class="title">
    <h2>{{ $heading }}</h2>
    </div>
    <div class="add_button">
    <a href="{{ route('admin.packages.price_add', $package_id) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Price Info</a>
    </div>
    </div>

    <div class="centersec">
        <div class="bgcolor">

    @include('snippets.errors')
    @include('snippets.flash')

    <div class="ajax_msg"></div>
        

    <?php if(!($package_prices->isEmpty())){ ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">

                <tr>
                    <th width="25%" class="text-center">Title</th>
                    <th width="10%" class="text-center">Service Level</th>
                    <th width="15%" class="text-center">Default Price</th>
                    <th width="10%" class="text-center">Status</th>
                    <th width="10%" class="text-center">Date Created</th>
                    <th width="10%" class="text-center">Action</th>
                </tr>

                <?php foreach($package_prices as $info){ ?>
                    <tr>
                        <td>{{$info->title }}</td>
                        <td>
                             <?php
                        $serviceArr = config('custom.service_level_arr');
                        if(!empty($info)){
                        echo $serviceArr[$info->service_level];
                        }
                        ?>
                        </td>
                        <td><?php  if($info->is_default == 1){ ?>
                                <i class="fas fa-check" style="color:green"></i>
                                <?php   } ?> 
                        </td>
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