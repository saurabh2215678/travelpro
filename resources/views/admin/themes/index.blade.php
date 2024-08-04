@component('admin.layouts.main')

@slot('title')
Admin -Manage Theme - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$back_url=CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$id = (request()->has('id'))?request()->id:'';
$old_title = (request()->has('title'))?request()->title:'';
$old_status = (request()->has('status'))?request()->status:1; 
?>

<!-- Start Search box section     -->
<div class="centersec">
    <?php
    $active_menu = "packages.theme";
    ?>
    @include('admin.includes.packagemenu')
    <div class="top_title_admin tab-title">
        <div class="title">
            <h2>Manage Theme ({{ $themes->total() }})</h2>
        </div>
        <div class="add_button">
            @if(CustomHelper::checkPermission('all_masters','add'))
            <a href="{{ route($routeName.'.'.$segment.'.theme_add').'?back_url='.$back_url }}" class="btn_admin"><i class="fa fa-plus"></i>Add Theme </a>
            @endif
        </div>
    </div>
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
                        <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                    </select>
                </div>
                <!--  <div class="clearfix"></div> -->
                <div class="col-md-6"><br>
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{route($routeName.'.'.$segment.'.theme_index')}}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>
    

    <!-- End Search box Section -->

    <div class="alert alert-warning" role="alert" style="margin-top: 12px;padding: 5px;margin-bottom: 5px;"><b>Note :</b> <i class="fa fa-lightbulb-o fa-1x"></i> (e.g. Trekking & Hiking, Mountaineering)</div>

    @include('snippets.errors')
    @include('snippets.flash')

    <?php
    if(!empty($themes) && count($themes) > 0){
        ?>

        <div class="table-responsive">           
        <table class="table table-striped table-bordered table-hover">
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>No. of {{ucfirst($segment)}}</th>
                    <?php //<th>Theme Categories Identifier</th> ?>
                    <th>SortBy</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
                <?php
                foreach($themes as $theme_query){?>
                    <tr>
                        <td><?php echo $theme_query->title; ?></td>
                        <td><?php echo $theme_query->slug;?></td>
                        <?php //<td><?php echo ucfirst($theme_query->identifier);</td> ?>

                        <td><a href="{{route($routeName.'.'.$segment.'.index',['package_themes'=> $theme_query->id])}}">({{$theme_query->packages_count}})</a>
                        </td>
                        <td>{{$theme_query->sort_order}}</td>
                        <td><?php if($theme_query->status == 1){ ?>
                                <span style="color:green">Active</span>
                                <?php   }else{  ?><span style="color:red">Inactive</span>
                            <?php } ?>
                        </td>
                        <td>{{ CustomHelper::DateFormat($theme_query->created_at, 'd/m/Y') }}</td>
                        <td>
                           @if(CustomHelper::checkPermission('all_masters','view'))
                           <a href="{{route($routeName.'.'.$segment.'.theme_view',[$theme_query['id'], 'back_url'=>$back_url])}}" title="View"><i class="fas fa-eye"></i></a>
                           @endif
                           
                           @if(CustomHelper::checkPermission('all_masters','edit'))
                           <a href="{{ route($routeName.'.'.$segment.'.theme_edit', $theme_query->id.'?back_url='.$back_url) }}" title="Edit Theme"><i class="fas fa-edit"></i></a>
                           @endif

                           @if(CustomHelper::checkPermission('all_masters','delete'))
                           <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$theme_query->id}}" title="Delete Theme"><i class="fas fa-trash-alt"></i></i></a>

                           <form method="POST" action="{{ route($routeName.'.'.$segment.'.theme_delete', $theme_query->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Are you sure you want to remove this {{ucfirst($segment)}} Theme?');" id="delete-form-{{$theme_query->id}}">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <input type="hidden" name="id" value="<?php echo $theme_query->id; ?>">
                        </form>
                        @endif
                        <a  href="{{ route($routeName.'.images.upload_view',['tid'=>$theme_query->id,'module'=>'themes','category'=>'banner']) }}" title="Add Banner" target="_blank"><i class="fas fa-image"></i></a>
                    </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        {{ $themes->appends(request()->query())->links('vendor.pagination.default') }}

        <?php
    }
    else{
        ?>
        <div class="alert alert-warning">No Theme found.</div>
    <?php } ?>
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