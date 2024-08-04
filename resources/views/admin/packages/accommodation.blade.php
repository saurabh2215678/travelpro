@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
    ?>

<?php
$active_menu = "packages".$package_id.'/accommodation';
?>
@if(!empty($package))
    @include('admin.includes.packageoptionmenu')
@endif

    <div class="top_title_admin">
    <div class="title">
    <h2>{{ $heading }}</h2>
    </div>
    <div class="add_button">
    <a href="{{ route('admin.packages.accommodation_add', $package_id) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Accommodation</a>
    </div>
    </div>

    <div class="centersec">
        <div class="bgcolor">

    @include('snippets.errors')
    @include('snippets.flash')

    <div class="ajax_msg"></div>
            

    <?php if(!($package_prices->isEmpty())){ ?>
        <div class="data-space">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">

                <tr>
                    <th width="45%" class="text-center">Name</th>
       
                    <th width="20%" class="text-center">Date Created</th>
                    <th width="15%" class="text-center">Action</th>
                </tr>

                <?php 
                $xID=0; 
                foreach($package_prices as $info){ #prd($info); 
                    if($xID!=$info->service_level){
                        $xID=$info->service_level;
                        ?>
                    <tr>
                        <td>{{$info->name }}</td>
                        <td>{{ CustomHelper::DateFormat($info->created_at, 'd/m/Y') }}</td>
                        <td>
                            <a href="{{ route($routeName.'.packages.accommodation_edit', ['package_id'=>$package_id,'accommodation_id'=>$info->accommodation_id,'service_level'=>$info->service_level, 'back_url'=>$BackUrl]) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>

                            <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>

                               <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.packages.accommodation_delete', ['package_id'=>$package_id,'accommodation_id'=>$info->accommodation_id, 'service_level'=>$info->service_level,'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                            {{ csrf_field() }}
                            </form>




                        </td>

                    </tr>
                <?php } } ?>
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