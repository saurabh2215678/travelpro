@component('admin.layouts.main')

    @slot('title')
        Admin - Additional Info(Accommodation) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
    ?>
<?php
$active_menu = "accommodations".$accommodation_id.'/additional-info';

$storage = Storage::disk('public');
$path = 'accommodations/';
?>
@if(!empty($accommodation_id))
    @include('admin.includes.accommodationoptionmenu')
@endif
<div class="top_title_admin">
    <div class="title">
        <h2>{{ $heading }}</h2>
    </div>
    @if(CustomHelper::checkPermission('accommodations','add'))
    <div class="add_button">
        <a href="{{ route('admin.accommodations.info_add', $accommodation_id) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Additional Info</a>
    </div>
    @endif
</div>

<div class="centersec">
    <div class="bgcolor">
    

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>
            

            <?php if(!($accommodation_infos->isEmpty())){?>
                <div class="data-space">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">

                        <tr>
                            <th width="25%" class="text-left">Image</th>
                            <th width="50%" class="text-left">Title</th>
                            <th width="10%" class="text-left">Status</th>
                            <th width="10%" class="text-left">Date Created</th>
                            <th width="20%" class="text-left">Action</th>
                        </tr>

                        <?php
                        foreach($accommodation_infos as $info){
                        ?>
                            <tr>
                                <td><?php
                                $image = $info->image;
                                if(!empty($image)){
                                    if($storage->exists($path.$image)){
                                        ?>
                                        <div class="col-md-2 image_box">
                                            <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                                        </div>
                                    <?php }}else{
                                        echo "No Image";
                                    } ?>
                                </td>
                                <td>{{$info->title}}</td>
                                <td><?php if($info->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                                <?php } ?>
                                </td>
                                <td>{{ CustomHelper::DateFormat($info->created_at, 'd/m/Y') }}</td>

                                <td>
                                    @if(CustomHelper::checkPermission('accommodations','edit'))
                                    <a href="{{ route($routeName.'.accommodations.info_edit', ['id'=>$accommodation_id,'info_id'=>$info->id, 'back_url'=>$BackUrl]) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                                    @endif
                                    @if(CustomHelper::checkPermission('accommodations','delete'))
                                    <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                    <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.accommodations.info_delete', ['id'=>$accommodation_id,'info_id'=>$info->id, 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                    {{ csrf_field() }}
                                    </form>
                                    @endif
                                </td>

                            </tr>
                        <?php } ?>
                    </table>
                </div>
                {{ $accommodation_infos->appends(request()->query())->links('vendor.pagination.default') }}
           <?php
       }else{
        ?>
        <div class="alert alert-warning">There are no Additional Info Block(Accommodation) at the present.</div>
        <?php } ?>
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