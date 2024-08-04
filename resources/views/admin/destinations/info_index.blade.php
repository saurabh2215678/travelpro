@component('admin.layouts.main')

    @slot('title')
        Admin - Additional Info(Destination) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
        $BackUrl = CustomHelper::BackUrl();
        $routeName = CustomHelper::getAdminRouteName();
    ?>
<?php
$active_menu = "destinations".$destination_id.'/additional-info';

$storage = Storage::disk('public');
$path = 'destinations/';
?>
@if(!empty($destination_id))
    @include('admin.includes.destinationoptionmenu')
@endif
<div class="top_title_admin">
    <div class="title">
        <h2>{{ $heading }}</h2>
    </div>
    @if(CustomHelper::checkPermission('destinations','add'))
    <div class="add_button">
        <a href="{{ route('admin.destinations.info_add', $destination_id) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Additional Info</a>
    </div>
    @endif
</div>

<div class="centersec">
    <div class="bgcolor">
    

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>
            

            <?php if(!($destination_infos->isEmpty())){?>
                <div class="data-space">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">

                        <tr>
                            <th width="45%" class="text-center">Image</th>
                            <th width="45%" class="text-center">Title</th>
                            <th width="20%" class="text-center">Status</th>
                            <th width="20%" class="text-center">Date Created</th>
                            <th width="15%" class="text-center">Action</th>
                        </tr>

                        <?php
                        foreach($destination_infos as $info){
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
                                    @if(CustomHelper::checkPermission('destinations','edit'))
                                    <a href="{{ route($routeName.'.destinations.info_edit', ['id'=>$destination_id,'info_id'=>$info->id, 'back_url'=>$BackUrl]) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                                    @endif
                                    @if(CustomHelper::checkPermission('destinations','delete'))
                                    <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                    <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.destinations.info_delete', ['id'=>$destination_id,'info_id'=>$info->id, 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                    {{ csrf_field() }}
                                    </form>
                                    @endif
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