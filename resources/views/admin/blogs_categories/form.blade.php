 @component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')

    <link rel="stylesheet" type="text/css" href="{{ url('/css/jquery-ui.css') }}"/ >

    <link rel="stylesheet" type="text/css" href="{{ url('/datetimepicker/jquery-ui-timepicker-addon.css') }}"/ >
    <!-- add link for trip tags -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css" />
    <style>
        .bootstrap-tagsinput { display: block;}

        .bootstrap-tagsinput { display: block;}
        .slugEdit {
            position: absolute;
            right: 22px;
            top: 26px;
            font-size: 15px;
            opacity: .7;
        }
    </style>
     
    @endslot

<div class="row">
<div class="col-md-12">

    <?php

    $routeName = CustomHelper::getAdminRouteName();

    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    if(empty($back_url)){
        $back_url = 'admin/blog_categories';
    }

    $id = (isset($category->id))?$category->id:0;
    $parent_id = (isset($category->parent_id))?$category->parent_id:0;
    $name = (isset($category->name))?$category->name:'';
    $slug = (isset($category->slug))?$category->slug:'';
    $content = (isset($category->content))?$category->content:'';
    $meta_title = (isset($category->meta_title))?$category->meta_title:'';
    $meta_keyword = (isset($category->meta_keyword))?$category->meta_keyword:'';
    $meta_description = (isset($category->meta_description))?$category->meta_description:'';
    $status = (isset($category->status))?$category->status:1;
    $hot_categories = (isset($category->hot_categories))?$category->hot_categories:0;
    $sort_order = (isset($category->sort_order))?$category->sort_order:0;

    $path = 'blog_categories/';
    $thumb_path = 'blog_categories/thumb/';

    $storage = Storage::disk('public');

    ?>
    <div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
  
    </div>


    

    <div class="centersec">
        <div class="bgcolor">
        <div class="alert_msg"></div>
            @include('snippets.errors')
            @include('snippets.flash')

            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}

                <input type="hidden" name="id" value="{{$id}}">
                <input type="hidden" name="parent_id" value="{{$parent_id}}">
                <div class="data-space">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label required">Name:</label>

                            <input type="text" name="name" class="form-control" value="{{ old('name', $name) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>  

                     <div class="col-md-6 col-sm-6">
                        <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label ">Sort Order:</label>

                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $sort_order) }}" maxlength="255" />
                            @include('snippets.errors_first', ['param' => 'sort_order'])
                        </div>
                    </div>   

                    <?php
                if(!empty($category->id)){
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
                        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label">Description:</label>

                            <textarea id="content" class="form-control " name="content">{{ old('content', $content) }}</textarea>

                            @include('snippets.errors_first', ['param' => 'content'])
                        </div>
                    </div>             

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('meta_title') ? ' has-error' : '' }}">
                            <label class="control-label ">Meta Title:</label>

                            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $meta_title) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'meta_title'])
                        </div>
                    </div>


                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('meta_keyword') ? ' has-error' : '' }}">
                            <label class="control-label ">Meta Keyword:</label>

                            <input type="text" name="meta_keyword" class="form-control" value="{{ old('meta_keyword', $meta_keyword) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'meta_keyword'])
                        </div>
                    </div> 


                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('meta_keyword') ? ' has-error' : '' }}">
                            <label class="control-label ">Meta Description:</label>

                            <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $meta_description) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'meta_description'])
                        </div>
                    </div>  

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">

                            <?php
                            $sel_status = old('status', $status);
                            ?>
                            <label class="control-label required">Status:</label>
                            <input type="radio" name="status" value="1" {{($sel_status == 1)?'checked':''}}>Active
                            &nbsp;
                            <input type="radio" name="status" value="0" {{($sel_status == 0)?'checked':''}}>Inactive

                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('hot_categories') ? ' has-error' : '' }} col-md-12">
                            <div class="form-group{{ $errors->has('hot_categories') ? ' has-error' : '' }}">
                                <label class="control-label ">Is Hot Category </label>

                                <input type="checkbox" name="hot_categories" value="1" <?php echo ($hot_categories == '1')?'checked':''; ?> />

                                @include('snippets.errors_first', ['param' => 'hot_categories'])
                            </div>
                            </div>
                   
                    <div class="clearfix"></div>

                </div>
                <div class="form-group">
                        <input type="hidden" name="back_url" value="{{ $back_url }}" >
                            <button type="submit" class="btn btn-success" title="Create this new blog category"><i class="fa fa-save"></i> Submit</button>

                            <a href="{{ url($back_url) }}" class="btn_admin2" title="Click here to cancel">Cancel</a>
                        </div>
                </div>
                    

            </form>
        </div>

    </div>
    </div>
</div>
    @slot('bottomBlock')

<script type="text/javascript" src="{{ url('/js/ckeditor/ckeditor.js') }}"></script>

<script type="text/javascript">
var editor = CKEDITOR.replace('content', {
    filebrowserImageUploadUrl: "<?php echo route($routeName.'.ck_upload',['_token' => csrf_token()]);?>",
    filebrowserUploadMethod: 'form'
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

