	@component('admin.layouts.main')

    @slot('title')
    Admin - Manage Blog Categories - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <?php
    $BackUrl = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $id = (request()->has('id'))?request()->id:'';
    $old_name = (request()->has('name'))?request()->name:'';
    $old_status = (request()->has('status'))?request()->status:1;   
    $path = 'blog_categories/';
    $thumb_path = 'blog_categories/thumb/';

    $storage = Storage::disk('public');

    $title = '';

    if($type == 'blogs'){
        $title = 'Blog Category List';
        $addBtn = 'Add Blog Category';
    }
    elseif($type == 'news'){
        $title = 'News Category List';
        $addBtn = 'Add News Category';
    }
    ?>

    <div class="top_title_admin">
        <div class="title">
            <h2>{{$title}} ({{ $categories->total() }})</h2>
        </div>
        <div class="add_button">
            @if($type == 'blogs' && CustomHelper::checkPermission('blogs','add') || $type == 'news' && CustomHelper::checkPermission('news','add'))
            <a href="{{ route($routeName.'.blogs_categories.add', ['type'=>$type,'back_url'=>$BackUrl]) }}" class="btn_admin"><i class="fa fa-plus"></i> <?php echo $addBtn;?></a>
            @endif
        </div>
    </div>

    <!-- Start Search box section     -->
    <div class="centersec">
        <div class="bgcolor ">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <input type="hidden" name="type" value="{{$type}}">
                    <div class="col-md-2">
                        <label>Name:</label><br/>
                        <input type="text" name="name" class="form-control admin_input1" value="{{$old_name}}">
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
                    <div class="col-md-6">
                        <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{ route($routeName.'.blogs_categories.index',['type'=>$type]) }}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- End Search box Section -->


        @include('snippets.errors')
        @include('snippets.flash')

        <?php

        if(!empty($categories) && $categories->count() > 0){
            ?>
            <div class="table-responsive">           

                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>

                        <?php if($type == 'blogs'){ ?>
                            <th>No. of Blog</th>
                        <?php } elseif($type == 'news'){ ?>
                            <th>No. of News</th>
                        <?php } ?>

                        <th>Display Order</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $storage = Storage::disk('public');
                    foreach ($categories as $category){

                        $created_at = CustomHelper::DateFormat($category->created_at, 'd M Y');
                        ?>
                        <tr class="row_{{$category->id}}">
                            <td>{{$category->name}}</td>
                            <td>{{$category->slug}}</td>
                            <?php /* <td>{{$category->blogs()->count()}}</td> */ ?>
                            <?php if($type == 'blogs'){ ?>
                             <td><a href="{{route($routeName.'.blogs.index',['category_id'=> $category->id,'type'=>'blogs'])}}"><i class="fas fa-list"></i>Blog List ({{$category->blogs_count}})</a>
                             <?php } elseif($type == 'news'){ ?>
                                <td><a href="{{route($routeName.'.blogs.index',['category_id'=> $category->id,'type'=>'news'])}}"><i class="fas fa-list"></i>News List ({{$category->blogs_count}})</a>
                                <?php } ?>
                                <?php if($type == 'blogs'){
                                    $title = 'Blog List';
                                }
                                elseif($type == 'news'){
                                    $title = 'News List';
                                } ?>
                            </td>
                            <td>{{$category->sort_order}}</td>
                            <td>
                                <?php if($category->status == 1){ ?>
                                    <span style="color:green">Active</span>
                                    <?php   }else{  ?><span style="color:red">Inactive</span>
                                <?php } ?>
                            </td>
                            <td>{{$created_at}}</td>

                            <td>
                                {{--<a href="{{route($routeName.'.blogs_categories.categories_view',[$category['id'],'type'=> $type,'back_url'=>$BackUrl])}}" title="View"><i class="fas fa-eye"></i></a>--}}
                                @if($type == 'blogs' && CustomHelper::checkPermission('blogs','view') || $type == 'news' && CustomHelper::checkPermission('news','view'))
                                <a href="javascript:void(0);" data-src="<?php echo route($routeName.'.blogs_categories.categories_view',[$category['id'],'type'=> $type]) ?>" data-fancybox data-type="ajax" title="View"><i class="fas fa-eye"></i></a>
                                @endif

                                @if($type == 'blogs' && CustomHelper::checkPermission('blogs','edit')|| $type == 'news' &&  CustomHelper::checkPermission('news','edit'))
                                <a href="{{route($routeName.'.blogs_categories.edit', ['type'=>$type,'id'=>$category->id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>
                                @endif
                                &nbsp;

                               @if($type == 'blogs' && CustomHelper::checkPermission('blogs','delete')|| $type == 'news' &&  CustomHelper::checkPermission('news','delete'))

                                <?php 
                                if ($category->blogs() && $category->blogs_count == 0) { ?>
                                    <a href="javascript:void(0)" class="sbmtDelForm" title="Delete" ><i class="fas fa-trash-alt"></i></a>

                                    <form method="POST" action="{{ route($routeName.'.blogs_categories.delete', $category->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('{{'Are you sure you want to remove this '.$title}} ?');" class="delForm">
                                        {{ csrf_field() }}
                                        {{ method_field('POST') }}
                                        <input type="hidden" name="id" value="<?php echo $category->id; ?>">
                                        <input type="hidden" name="type" value="{{$type}}">
                                    </form>
                                <?php } ?>

                                @endif
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            {{ $categories->appends(request()->query())->links('vendor.pagination.default') }}
        <?php } else{ ?>
            <div class="alert alert-warning">There are no Records at the present.</div>
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

