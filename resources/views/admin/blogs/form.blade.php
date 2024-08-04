@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')

    <link rel="stylesheet" type="text/css" href="{{ url('/css/jquery-ui.css') }}"/ >
     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ url('/datetimepicker/jquery-ui-timepicker-addon.css') }}"/ >
    <!-- add link for trip tags -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />
    <style>
        .bootstrap-tagsinput { display: block;}
        .slugEdit {
            position: absolute;
            right: 22px;
            top: 26px;
            font-size: 15px;
            opacity: .7;
    </style>

    @endslot

    <?php
    $routeName = CustomHelper::getAdminRouteName();
    $type = (request('type'))?request('type'):'';

    $id = (isset($blog->id))?$blog->id:'';
    // $category_id  =  isset($blog->category_id) ? explode(',', $blog->category_id) : 0;
    $blogToCategory  =  isset($blog->blogToCategory) ?  $blog->blogToCategory : 0;

    $title = (isset($blog->title))?$blog->title:'';
    $post_by = (isset($blog->post_by))?$blog->post_by:'';
    $brief = (isset($blog->brief))?$blog->brief:'';
    $source_title = (isset($blog->source_title))?$blog->source_title:'';
    $source_url = (isset($blog->source_url))?$blog->source_url:'';
    $add_media = (isset($blog->add_media))?$blog->add_media:'';
    $post_title_url = (isset($blog->post_title_url))?$blog->post_title_url:'';
    $content = (isset($blog->content))?$blog->content:'';
    $slug = (isset($blog->slug))?$blog->slug:'';
    $sort_order = (isset($blog->sort_order))?$blog->sort_order:0;
    $meta_title = (isset($blog->meta_title))?$blog->meta_title:'';
    $meta_keyword = (isset($blog->meta_keyword))?$blog->meta_keyword:'';
    $meta_description = (isset($blog->meta_description))?$blog->meta_description:'';
    $featured = (isset($blog->featured))?$blog->featured:0;
    $show_comments = (isset($blog->show_comments))?$blog->show_comments:0;
    $allow_comments = (isset($blog->allow_comments))?$blog->allow_comments:0;
    $popular = (isset($blog->popular))?$blog->popular:0;
    $is_home = (isset($blog->is_home))?$blog->is_home:0;



    $status = (isset($blog->status))?$blog->status:1;
    $image = (isset($blog->image))?$blog->image:1;
    $blog_tags = (!empty($blog) && !($blog->blogTags->isEmpty())) ? $blog->blogTags : "";
    
    $blog_date = (isset($blog->blog_date))?$blog->blog_date:'';
    $date = CustomHelper::DateFormat($blog_date, 'd/m/Y');
    $storage = Storage::disk('public');

        $path = 'blogs/';
        $thumb_path = 'blogs/thumb/';
        $old_image = 0;

        $image_req = 'required';
        $link_req = '';

    $blogTags = "";
    if(!empty($blog_tags)){
        $blogTags = [];
        foreach ($blog_tags as  $tag) {
            $blogTags[] = $tag->name;
        }
        $blogTags = implode(',',$blogTags);
    }  
    
    $blogCatArr = [];
    if(!empty($blogToCategory)){
        foreach ($blogToCategory as  $cat) {
            
            $blogCatArr[] = $cat->id;
        }
    }else{
        $blogCatArr  = old('category_id');
    }


    $active_menu = "blogs";
    $back_url = CustomHelper::BackUrl();
    ?>

@if(!empty($id))
    @include('admin.includes.blogmenu')
@endif

<div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
    <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
        <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a><?php } ?>
    </div>
    </div>

    <div class="centersec">
        <div class="bgcolor">

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="data-space">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label required">Title:</label>

                            <input type="text" id="title" class="form-control" name="title" value="{{ old('title', $title) }}" />

                            @include('snippets.errors_first', ['param' => 'title'])
                        </div>
                    </div>

                <div  class="form-group col-md-6{{ $errors->has('category_id') ? ' has-error' : '' }}">
                <label for="category_id" class="control-label required">Category:</label>
                <select class="form-control select2" name="category_id[]" multiple>
                 <?php

                //  $category_id = old("category_id[]",$category_id);

                 if(!empty($categories)){
                 ?>
                  <option value="">Select</option>
                  
                  <?php
                  foreach ($categories as $catID){
                    $selected = '';
                    if($blogCatArr && in_array($catID->id,$blogCatArr)){
                        $selected = 'selected';
                    }

                    ?>
                    <option value="{{$catID->id}}" {{$selected}}>{{$catID->name}}</option>
                  <?php } } ?>
                </select>
                @include('snippets.errors_first', ['param' => 'category_id'])
                </div>

                    
                    @if($type == 'blogs')
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('post_by') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label required">Author:</label>

                            <input type="text" id="post_by" class="form-control" name="post_by" value="{{ old('post_by', $post_by) }}"  />

                            @include('snippets.errors_first', ['param' => 'post_by'])
                        </div>
                    </div>
                    @endif

                    

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('blog_date') ? ' has-error' : '' }}">
                        <label for="blog_date" class="control-label">Blog Date:</label>
                            <input type="text" id="blog_date" class="form-control" name="blog_date" value="{{ old('date', $date) }}" autocomplete="off"  />

                            @include('snippets.errors_first', ['param' => 'blog_date'])
                        </div>
                    </div>

                     <?php
                if(!empty($blog->id)){
                    ?>
                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }} slug">
                            <label for="link_name" class="control-label required">Slug:</label>

                            <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $slug) }}" />

                            <a href="javascript:void(0);" class="slugEdit" id="EditSlug" title="Edit"><i class="fas fa-edit"></i></a>

                            @include('snippets.errors_first', ['param' => 'slug'])
                        </div>
                    </div>
                <?php }?>

                     <div class="col-md-12">
                        <div class="form-group{{ $errors->has('brief') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label">Brief:</label>

                            <textarea id="brief" class="form-control " name="brief">{{ old('brief', $brief) }}</textarea>

                            @include('snippets.errors_first', ['param' => 'brief'])
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('source_title') ? ' has-error' : '' }}">
                            <label for="source_title" class="control-label">Source Title:</label>

                            <input type="text" id="meta_description" class="form-control" name="source_title" value="{{ old('source_title', $source_title) }}"  />

                            @include('snippets.errors_first', ['param' => 'meta_description'])
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('source_url') ? ' has-error' : '' }}">
                            <label for="source_url" class="control-label">Source Url:</label>

                            <input type="text" id="source_url" class="form-control" name="source_url" value="{{ old('source_url', $source_url) }}"  />

                            @include('snippets.errors_first', ['param' => 'meta_description'])
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('add_media') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label">Add Media:</label>

                            <textarea id="add_media" class="form-control " name="add_media">{{ old('add_media', $add_media) }}</textarea>

                            @include('snippets.errors_first', ['param' => 'add_media'])
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('post_title_url') ? ' has-error' : '' }}">
                            <label for="post_title_url" class="control-label">Post Title Url:</label>

                            <input type="text" id="post_title_url" class="form-control" name="post_title_url" value="{{ old('post_title_url', $post_title_url) }}"  />

                            @include('snippets.errors_first', ['param' => 'post_title_url'])
                        </div>
                    </div>
                
                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label">Description:</label>

                            <textarea id="content" class="form-control " name="content">{{ old('content', $content) }}</textarea>

                            @include('snippets.errors_first', ['param' => 'content'])
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                            <label>Image</label><br/>
                            <input type="file" name="image">
                            <?php
                            $imgSrc = '';
                            $imgUrl = 'javascript:void(0)';
                            if(!empty($image)){
                                if($storage->exists($thumb_path.$image)){
                                   $imgSrc = asset('/storage/'.$thumb_path.$image);
                                }
                                if($storage->exists($path.$image)){
                                    $imgUrl = asset('/storage/'.$path.$image);

                                    if(empty($imgSrc)){
                                        $imgSrc =  $imgUrl;
                                    }
                                }
                                else{
                                    $imgUrl =  $imgSrc;
                                }
                            }

                        if(!empty($imgSrc)){
                            ?>
                            <div class="imgBox">
                                <a href="{{$imgUrl}}" target="_blank">
                                    <img src="{{$imgSrc}}" style="width:100px;" >
                                </a>

                                <a href="javascript:void(0)" data-id="{{$blog->id}}" class="delImg">Delete</a>

                                <br><br>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                    <!-- Add trip tags code -->
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('tags') ? ' has-error' : '' }}">
                            <label for="author">Tags</label>
                            <input type="text" class="form-control" name="tags" id="tags" value="{{ old('tags',$blogTags) }}" data-role="tagsinput" placeholder="Please Separate your tags with a comma">
                            @include('snippets.errors_first', ['param' => 'tags'])
                        </div>
                    </div>    
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label">Display Order:</label>

                            <input type="number" id="sort_order" class="form-control" name="sort_order" value="{{ old('sort_order', $sort_order) }}"  />

                            @include('snippets.errors_first', ['param' => 'sort_order'])
                        </div>
                    </div>
                
                    {{-- <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('meta_title') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label">Meta Title:</label>

                            <input type="text" id="meta_title" class="form-control" name="meta_title" value="{{ old('meta_title', $meta_title) }}"  />

                            @include('snippets.errors_first', ['param' => 'meta_title'])
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('meta_keyword') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label">Meta Keyword:</label>

                            <input type="text" id="meta_keyword" class="form-control" name="meta_keyword" value="{{ old('meta_keyword', $meta_keyword) }}"  />

                            @include('snippets.errors_first', ['param' => 'meta_keyword'])
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('meta_description') ? ' has-error' : '' }}">
                            <label for="meta_description" class="control-label">Meta Description:</label>

                            <input type="text" id="meta_description" class="form-control" name="meta_description" value="{{ old('meta_description', $meta_description) }}"  />

                            @include('snippets.errors_first', ['param' => 'meta_description'])
                        </div>
                    </div> --}}
                
                
                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                            <label class="control-label">Status:</label>
                            &nbsp;&nbsp;
                            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                            &nbsp;
                            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>

                        <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }} col-md-12">
                            <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }}">
                                <label class="control-label ">Featured:</label>

                                <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />

                                @include('snippets.errors_first', ['param' => 'featured'])
                            </div>
                            </div>

                        <div class="form-group{{ $errors->has('show_comments') ? ' has-error' : '' }} col-md-12">
                            <div class="form-group{{ $errors->has('show_comments') ? ' has-error' : '' }}">
                                <label class="control-label ">Show Comments</label>

                                <input type="checkbox" name="show_comments" value="1" <?php echo ($show_comments == '1')?'checked':''; ?> />

                                @include('snippets.errors_first', ['param' => 'show_comments'])
                            </div>
                            </div>

                        <div class="form-group{{ $errors->has('allow_comments') ? ' has-error' : '' }} col-md-12">
                            <div class="form-group{{ $errors->has('allow_comments') ? ' has-error' : '' }}">
                                <label class="control-label ">Allow Comments</label>

                                <input type="checkbox" name="allow_comments" value="1" <?php echo ($allow_comments == '1')?'checked':''; ?> />

                                @include('snippets.errors_first', ['param' => 'allow_comments'])
                            </div>
                            </div>

                       
                        <div style="display: none;" class="popularDiv form-group{{ $errors->has('popular') ? ' has-error' : '' }} col-md-12">
                            <div class="form-group{{ $errors->has('popular') ? ' has-error' : '' }}">
                                <label class="control-label ">Popular:</label>

                                <input type="checkbox" name="popular" value="1" <?php echo ($popular == '1')?'checked':''; ?> />

                                @include('snippets.errors_first', ['param' => 'popular'])
                            </div>
                            </div>


                            <div  class="is_homeDiv form-group{{ $errors->has('is_home') ? ' has-error' : '' }} col-md-12">
                            <div style="display: none;"class="form-group{{ $errors->has('is_home') ? ' has-error' : '' }}">
                                <label class="control-label ">Display on Home:</label>

                                <input type="checkbox" name="is_home" value="1" <?php echo ($is_home == '1')?'checked':''; ?> />

                                @include('snippets.errors_first', ['param' => 'is_home'])
                            </div>
                            </div>

                            <div class="row form-group">      
                            <div class="col-sm-6">

                            <label for="send_newsletter">Send Newsletter</label>
                            <input  name="send_newsletter" id="send_newsletter" type="checkbox" value="1">

                            </div>
                            </div>




                            <div class="col-md-12">
                                <div class="form-group">
                                    <p></p>
                                    <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                                    <button type="submit" class="btn btn-success" title="Create this new blog"><i class="fa fa-save"></i> Submit</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    </form>
                    </div><div class="clearfix"></div>
@slot('bottomBlock')

<script type="text/javascript" src="{{ url('/js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ url('/datetimepicker/jquery-ui-timepicker-addon.js') }}"></script>

<script type="text/javascript" src="{{ url('/js/ckeditor/ckeditor.js') }}"></script>

<!-- add script for trip tags -->
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(".delImg").click(function(){
        var conf = confirm('Are you sure to delete this Image?');
        if(conf){
            var currSelector = $(this);
            var id = $(this).data("id");
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route('admin.blogs.ajax_delete_image',['type'=>$type]) }}",
                type: "POST",
                data: {id:id},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                beforeSend:function(){
                    $(".ajax_msg").html("");
                },
                success: function(resp){
                    if(resp.success){
                        currSelector.parent(".imgBox").remove();
                    }
                    else{
                        $(".ajax_msg").html(resp.msg);
                    }
                }
            });
        }
    });
});

$(document).ready(function(){
    var editor = CKEDITOR.replace('content',{
        filebrowserImageUploadUrl: "<?php echo route($routeName.'.ck_upload',['_token' => csrf_token()]);?>",
        filebrowserUploadMethod: 'form'
    });

    $('#blog_date').datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        yearRange:"1950:+0"
    });
});

// Add for trip tags
$(document).ready(function() {
    $('#tags').tagsinput();

    var type='{{$type}}';

    if(type=='news'){
        $('.popularDiv').show();

    $('.is_homeDiv').hide();
    }else{
  $('.popularDiv').hide();

    $('.is_homeDiv').show();
    }

});

$('.select2').select2({
            placeholder: "Please Select",
            allowClear: true
        });


var slug = '{{$slug}}';
if(slug != ''){
  $('#slug').attr('readonly',true);
}

$("#EditSlug").click(function(){
    $('#slug').attr('readonly',false);
});

</script>

@endslot

@endcomponent