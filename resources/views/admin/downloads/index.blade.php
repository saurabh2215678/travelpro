@component('admin.layouts.main')

@slot('title')
Admin -Manage Downloads - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$id = (request()->has('id'))?request()->id:'';
$old_title = (request()->has('title'))?request()->title:'';
$old_status = (request()->has('status'))?request()->status:1;

$storage = Storage::disk('public');
$path = 'downloads/';
?>

<!-- Start Search box section     -->
<div class="top_title_admin">
    <div class="title">
        <h2>Manage Downloads ({{ $downloads->total()}})</h2>
    </div>
    <div class="add_button">
        @if(CustomHelper::checkPermission('downloads','add'))
        <a href="{{ route('admin.downloads.add', ['back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Download</a>
        @endif
    </div>
</div>


<div class="centersec">
    <div class="bgcolor">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <div class="col-md-2">
                    <label>Title:</label><br/>
                    <input type="text" name="title" class="form-control admin_input1" value="{{$old_title}}">
                </div>
                <div class="col-md-2">
                    <label>Status:</label><br/>
                    <select name="status" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Deactive</option>
                    </select>
                </div>
                <!--  <div class="clearfix"></div> -->
                <div class="col-md-6"><br>
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{route('admin.downloads.index')}}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>
    

    <!-- End Search box Section -->
    @include('snippets.errors')
    @include('snippets.flash')

    <?php
    if(!empty($downloads) && count($downloads) > 0){
        ?>
        <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">

                <tr>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
                <?php
                foreach($downloads as $download){
                    ?>
                    <tr>
                        <td>{{$download->title}}</td>
                        <td>
                            <?php
                            $image = $download->image;
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
                            <td><?php if($download->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                            </td>
                            <td>{{ CustomHelper::DateFormat($download->created_at, 'd/m/Y') }}</td>

                            <td>
                                @if(CustomHelper::checkPermission('downloads','view'))
                                <a href="{{route($routeName.'.downloads.view',[$download['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('downloads','edit'))
                                <a href="{{ route($routeName.'.downloads.edit', $download->id) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                                @endif 

                                @if(CustomHelper::checkPermission('downloads','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.downloads.delete', [$download['id'], 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                    {{ csrf_field() }}
                                </form>
                                @endif
                            </td>

                        </tr>
                    <?php } ?>
                </table>
            </div>
            {{ $downloads->appends(request()->query())->links('vendor.pagination.default') }}
            <?php
        }else{
            ?>
            <div class="alert alert-warning">There are no Download at the present.</div>
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