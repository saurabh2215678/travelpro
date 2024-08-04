@component('admin.layouts.main')

@slot('title')
    Admin - Manage Blogs - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
    <style>
   .centersec {
   min-height: auto;
   }
   
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endslot

    <?php 
    $back_url = CustomHelper::BackUrl();
    $routeName = CustomHelper::getAdminRouteName();
    $id = (request()->has('id'))?request()->id:'';
    $old_title = (request()->has('title'))?request()->title:'';
    $category_id = (request()->has('category_id'))?request()->category_id:'';
    $category_id = old('category_id', $category_id);
    $old_status = (request()->has('status'))?request()->status:1;

    $addBtn = 'Add '.ucwords($type);
    $title = 'Manage '.ucwords($type);

    $storage = Storage::disk('public');
    $path = 'blogs/';
    ?>
    <div class="top_title_admin">
    <div class="title">
    <h2>{{$title}} ({{ $blogs->total() }})</h2>
    </div>
    <div class="add_button">
     @if($type == 'blogs' && CustomHelper::checkPermission('blogs','add') || $type == 'news' && CustomHelper::checkPermission('news','add'))
    <a href="{{route($routeName.'.blogs.add',['type'=>$type,'back_url'=>$back_url]) }}" class="btn_admin"><i class="fa fa-plus"></i> {{$addBtn}}</a>
    @endif
    </div>
    </div>

    <!-- Start Search box section     -->
    <div class="centersec">
        <div class="bgcolor ">
            <div class="table-responsive">
                <form class="form-inline" method="GET">
                    <input type="hidden" name="type" value="{{$type}}">
                    <div class="col-md-3">
                        <label>Name:</label><br/>
                        <input type="text" name="title" class="form-control admin_input1" value="{{$old_title}}">
                    </div>

                    <div class="col-md-3{{$errors->has('category_id')?' has-error':''}}">
                    <label for="FormControlSelect1">Category</label><br/>
                    <select name="category_id" class="form-control admin_input1 select2">
                    <option value="">--Select Category--</option>
                    @if($categories)
                    @foreach($categories as $row)
                    <option value="{{$row->id}}" {{($row->id==$category_id)?'selected':''}}>{{$row->name}}</option>
                    @endforeach
                    @endif
                    </select>
                    </div>

                     <div class="col-md-3">
                        <label>Status:</label><br/>
                        <select name="status" class="form-control admin_input1">
                            <option value="">--Select--</option>
                            <option value="1" {{ ($old_status == '1')?'selected':'1' }}>Active</option>
                            <option value="0" {{ ($old_status == '0')?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                   <!--  <div class="clearfix"></div> -->
                    <div class="col-md-3">
                        <label></label><br>
                        <button type="submit" class="btn btn-success">Search</button>
                        <a href="{{ route($routeName.'.blogs.index',['type'=>$type]) }}" class="btn_admin2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
<!-- End Search box Section -->

    <div class="blog-gap">
            @include('snippets.errors')
            @include('snippets.flash')
            <?php
            if(!empty($blogs) && count($blogs) > 0){
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Category</th>
                            @if($type == 'blogs')
                            <th>Author</th>
                            @endif  
                            <!-- <th>Description</th> -->
                            <th>Display Order</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Date Added</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        foreach($blogs as $blog){
                            $content = CustomHelper::wordsLimit($blog->content,35);

                            // $blog_category = $blog->Category;
                            $blog_category = $blog->blogToCategory->pluck('name')->toArray()??[];
                            //prd( $blog_category);

                            // $category_name = (isset($blog_category->name))?$blog_category->name:'';
                            ?>
                        
                            <tr>
                                <td> <?php
                                $image = $blog->image;
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
                                <td><?php echo $blog->title; ?></td>
                                <td>{{ $blog->slug }}</td>
                                <td>

                                    <?php if ($blog_category) {
                                        echo implode(', ',$blog_category );
                                        
                                    } ?>
                                </td>
                                @if($type == 'blogs')
                                <td>{{ $blog->post_by }}</td>
                                @endif
                                <?php /* <td>{{ strip_tags($content) }}</td> */ ?>
                               <?php /* <td>{{ CustomHelper::getStatusStr($blog->featured) }}</td> */ ?>
                                <td>{{$blog->sort_order}}</td>
                                <td>
                                    <?php if($blog->status == 1){ ?>
                                <span style="color:green">Active</span>
                                    <?php   }else{  ?><span style="color:red">Inactive</span>
                                 <?php } ?>
                                </td>

                                <td><?php  if($blog->featured == 1){ ?>
                                <i class="fas fa-check" style="color:green"></i>
                                <?php   } ?>
                                </td>
                                <td>{{ CustomHelper::DateFormat($blog->created_at, 'd/m/Y') }}</td>

                                <td>
                                    @if($type == 'blogs' && CustomHelper::checkPermission('blogs','view') || $type == 'news' && CustomHelper::checkPermission('news','view'))
                                    <a href="{{ route($routeName.'.blogs.blog_view', [$blog->id,'back_url'=>$back_url,'type'=>$type]) }}" title="View"><i class="fas fa-eye"></i></a>
                                    @endif
                                    
                                    @if($type == 'blogs' && CustomHelper::checkPermission('blogs','edit')|| $type == 'news' &&  CustomHelper::checkPermission('news','edit'))
                                    <a href="{{ route($routeName.'.blogs.edit', [$blog->id,'back_url'=>$back_url,'type'=>$type]) }}" title="Edit"><i class="fas fa-edit"></i></a>
                                    @endif

                                    @if($type == 'blogs' && CustomHelper::checkPermission('blogs','delete')|| $type == 'news' &&  CustomHelper::checkPermission('news','delete'))
                                    <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$blog->id}}" title="Delete"><i class="fas fa-trash-alt"></i></i></a>

                                    <?php if($type == 'blogs'){
                                        $title = 'Blog List';
                                    }
                                    elseif($type == 'news'){
                                        $title = 'News List';
                                    } ?>
                                    <form style="display: inline-block;" method="POST" action="{{ route($routeName.'.blogs.delete', $blog->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('{{'Are you sure you want to remove this '.$title}} ?');" id="delete-form-{{$blog->id}}">
                                        {{ csrf_field() }}
                                        {{ method_field('POST') }}
                                        <input type="hidden" name="id" value="<?php echo $blog->id; ?>">
                                        <input type="hidden" name="type" value="{{$type}}">

                                    </form>

                                   <?php /* <a href="{{ route($routeName.'.images.upload',['tid'=>$blog->id,'tbl'=>'blogs']) }}" target="_blank"><i class="fas fa-image" title="Add Images"></i></a> 

                                    <a  href="{{ route($routeName.'.blogs.add',['vid'=>$blog->id,'tbl'=>'blogs']) }}" target="_blank"><i class="fa fa-video" title="Add Videos"></i></a>
                                                    
                                    <a  href="{{ route($routeName.'.blogs.add',['vid'=>$blog->id,'tbl'=>'blogs']) }}" target="_blank"><i class="fa fa-video" title="Add Videos"></i></a> */ ?>

                                     @endif

                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                {{ $blogs->appends(request()->query())->links('vendor.pagination.default') }}
            <?php
        }
        else{ ?>
        <div class="alert alert-warning">There are no Records at the present.</div>
        <?php
        }
        ?>
        </div>
    </div>

@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
    $('.select2').select2({
        placeholder: "Please Select",
        allowClear: true
        });
</script>

<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();

        $('.select2').select2({
        placeholder: "Please Select",
        allowClear: true
        });

    });
</script>
@endslot
@endcomponent