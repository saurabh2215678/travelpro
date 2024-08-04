@component('admin.layouts.main')

@slot('title')
Admin - CMS Pages - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$parent_id = (request()->has('parent_id'))?request()->parent_id:'';
$title = (request()->has('title'))?request()->title:'';
$cms_type = (request()->has('cms_type'))?request()->cms_type:'page';
$status = (request()->has('status'))?request()->status:1;
?>
<style type="text/css">
.inner-breadcrumb ul li {
    font-size: inherit;
}
</style>
<div class="top_title_admin">
    <div class="title">
        <h2>
            <div class="inner-breadcrumb">
                <ul>
                {!!CustomHelper::cmsBreadcrumb(request()->parent_id)!!}({{$pages->total()}})
                </ul>
            </div>
        </h2>
    </div>
    <div class="add_button">
        @if(CustomHelper::checkPermission('cms','add'))
        <a href="{{ route('admin.cms.add', ['parent_id'=>$parent_id, 'back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> Add {{(!empty($parent_id))?'Child CMS':'CMS'}}</a>
        @endif
    </div>
</div>

<!-- Start Search box section     -->
<div class="centersec">
    <div class="bgcolor ">
        <div class="table-responsive">
            <form class="form-inline" method="GET">
                <input type="hidden" name="parent_id" value="{{$parent_id}}">
                <div class="col-md-4">
                    <label>Title:</label><br/>
                    <input type="text" name="title" class="form-control admin_input1" value="{{$title}}">
                </div>
                <div class="col-md-2">
                    <label>CMS Type:</label><br/>
                    <select name="cms_type" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <option value="page" {{($cms_type=='page')?'selected':'1'}}>Page</option>
                        <option value="section" {{($cms_type=='section')?'selected':''}}>Section</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Status:</label><br/>
                    <select name="status" class="form-control admin_input1">
                        <option value="">--Select--</option>
                        <option value="1" {{($status == '1')?'selected':'1'}}>Active</option>
                        <option value="0" {{($status == '0')?'selected':''}}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label></label><br>
                    <button type="submit" class="btn btn-success">Search</button>
                    <a href="{{ route($routeName.'.cms.index',['parent_id'=>$parent_id]) }}" class="btn_admin2">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- End Search box Section -->

    @include('snippets.errors')
    @include('snippets.flash')

    <?php if(!empty($pages) && count($pages) > 0){ ?>
        <div class="table-responsive">           
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <th width="35%">Title</th>
                    <th width="20%">Slug</th>
                    <th width="10%">CMS Type</th>
                    <th width="5%">SortBy</th>
                    <th width="5%">Status</th>
                    <th width="10%">Date Created</th>
                    <th width="20%">Action</th>
                </tr>
                <?php
                foreach($pages as $page) {
                    $child_url = 'javascript:void(0)';
                    $title = $page->title;
                    if(!empty($page->children) && $page->children->count() > 0 ){
                        $child_url = 'cms?parent_id='.$page->id.'&back_url='.rawurlencode($BackUrl);
                        $title = '<i class="fa fa-list"></i> <strong>'.$page->title.'</strong>';
                    }
                    ?>
                    <tr>
                        <td>
                            <a href="{{$child_url}}">{!!$title!!}</a>
                        </td>
                        <td>{{$page->slug}}</td>
                        <td>{{$page->cms_type}}</td>
                        <td>{{$page->sort_order}}</td>
                        <td>
                            <?php if($page->status == 1){ ?>
                                <span style="color:green">Active</span>
                            <?php } else { ?>
                                <span style="color:red">Inactive</span>
                            <?php } ?>
                        </td>

                        <td>{{CustomHelper::DateFormat($page->created_at, 'd/m/Y')}}</td>

                        <td>
                            @if(empty($parent_id))
                            @if(CustomHelper::checkPermission('cms','add'))
                            <a href="{{ route($routeName.'.cms.add', ['parent_id'=>$page->id, 'back_url'=>$BackUrl]) }}" title="Add Child Page" ><i class="fas fa-plus"></i></a>
                            &nbsp;
                            @endif
                            @endif
                            @if(CustomHelper::checkPermission('cms','view'))
                            <a href="{{route($routeName.'.cms.view',[$page['id'], 'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>
                            @endif

                            @if(CustomHelper::checkPermission('cms','edit'))
                            <a href="{{ route($routeName.'.cms.edit', $page->id) }}" class=""><i class="fas fa-edit"></i></a>
                            @endif

                            @if(CustomHelper::checkPermission('cms','delete'))
                            <?php if($page->children->count() == 0){ ?>
                                <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" ><i class="fas fa-trash-alt"></i></a>
                                <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.cms.delete', [$page['id'], 'back_url'=>$BackUrl]) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this?');" class="delForm">
                                    {{ csrf_field() }}
                                </form>
                            <?php } else { ?>
                                <a href="javascript:void(0)" title="Delete" onclick="alert('please remove Child associated with this Parent first!')" ><i class="fas fa-trash-alt"></i></a>
                            <?php } ?>
                            @endif
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        {{ $pages->appends(request()->query())->links('vendor.pagination.default') }}
    <?php } else { ?>
        <div class="alert alert-warning">There are no CMS Pages at the present.</div>
    <?php } ?>
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