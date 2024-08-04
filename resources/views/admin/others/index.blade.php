@component('admin.layouts.main')

@slot('title')
Admin - Others - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$id = (request()->has('id'))?request()->id:'';
$old_title = (request()->has('title'))?request()->title:'';
$old_status = (request()->has('status'))?request()->status:1;

$storage = Storage::disk('public');
$path = 'others/';
?>
<!-- Start Search box section     -->
<div class="top_title_admin">
    <div class="title">
        <h2>Manage Others ({{ $others->total() }})</h2>
    </div>
    <div class="add_button">
        @if(CustomHelper::checkPermission('downloads','add'))
        <a href="{{ route('admin.downloads.other_add', ['back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add Other</a>
        @endif
    </div>
</div>

<div class="centersec">
    <div class="bgcolor">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <div class="col-md-2">
                    <label>Name:</label><br/>
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
                    <a href="{{route('admin.downloads.others')}}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <!-- End Search box Section -->
    @include('snippets.errors')
    @include('snippets.flash')

    <?php
    if(!empty($others) && count($others) > 0){
        ?>
       <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">

                <tr>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Brief</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>

                <?php
                foreach($others as $other){
                    ?>
                    <tr>
                        <td>{{$other->title}}</td>
                        <td>
                            <?php
                            $image = $other->image;
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
                            <td>{{$other->brief}}</td>
                            <td>
                                <?php if($other->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                            </td>
                            <td>{{ CustomHelper::DateFormat($other->created_at, 'd/m/Y') }}</td>

                            <td>
                                @if(CustomHelper::checkPermission('downloads','view'))
                                <a href="{{route($routeName.'.downloads.other_view',[$other['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>
                                @endif

                                @if(CustomHelper::checkPermission('downloads','edit'))
                                <a href="{{ route($routeName.'.downloads.other_edit', $other->id) }}" class="" title="Edit"><i class="fas fa-edit"></i> </a>
                                @endif

                                @if(CustomHelper::checkPermission('downloads','delete'))
                                <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.downloads.others_delete', [$other['id'], 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="id" value="<?php echo $other->id; ?>">
                                </form>
                                @endif
                            </td>

                        </tr>
                    <?php } ?>
                </table>
            </div>
            {{ $others->appends(request()->query())->links('vendor.pagination.default') }}
            <?php
        }else{
            ?>
            <div class="alert alert-warning">There are no Other at the present.</div>
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