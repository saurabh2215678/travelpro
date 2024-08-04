@component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')

<link href="{{url('')}}/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />

@endslot

<div class="row">

    <?php
     //prd($category->toArray());

    $parent_id = (request()->has('parent_id'))?request()->parent_id:'';
    $routeName = CustomHelper::getAdminRouteName();
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    if(empty($back_url)){
        $back_url = 'admin/blog_categories';
    }

    $id = (isset($category->id))?$category->id:0;

    $module = (isset($category->module))?$category->module:'';
    $module = old('module',$module);

    $language = (isset($category->language))?$category->language:'';
    $parent_id = (isset($page->parent_id))?$page->parent_id:$parent_id;
    $name = (isset($category->name))?$category->name:'';
    $hindi_name = (isset($category->hindi_name))?$category->hindi_name:'';
    $slug = (isset($category->slug))?$category->slug:'';
    $image = (isset($category->image))?$category->image:'';
    $meta_title = (isset($category->meta_title))?$category->meta_title:'';
    $meta_keyword = (isset($category->meta_keyword))?$category->meta_keyword:'';
    $meta_description = (isset($category->meta_description))?$category->meta_description:'';
    $status = (isset($category->status))?$category->status:1;
    $featured = (isset($category->featured))?$category->featured:0;
    $sort_order = (isset($category->sort_order))?$category->sort_order:0;

    $storage = Storage::disk('public');
    //pr($storage);

    $path = 'gallery/';
    $old_image = $image;
    $image_req = '';
    $link_req = '';

    $language_arr = config('custom.language_arr');
    $module_types = config('custom.module_level_arr');

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

                <div class="row">

                    <div class="col-md-6 col-sm-6">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label required">Image Category Name:</label>

                            <input type="text" name="name" class="form-control" value="{{ old('name', $name) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>

                    <div class="col-md-6{{$errors->has('module')?' has-error':''}}">
                      <label for="FormControlSelect1">Module Name</label><br/>
                      <select name="module" class="form-control admin_input1 select2">
                        <option value="">--Select Category Name--</option>
                        @foreach($module_types as $k => $v)
                        <option value="{{$k}}" {{(!empty($module) && $k == $module)?'selected':''}} >{{$v}}</option>
                        @endforeach
                      </select>
                      @include('snippets.errors_first', ['param' => 'module'])
                    </div>

                    <?php /*
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('language') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label required">Language:</label>
                            <?php //echo $language; ?>

                            <select name="language" class="form-control">

                                <option value="">--Please Select--</option>
                                <?php
                                if(!empty($language_arr) && count($language_arr) > 0){
                                    foreach($language_arr as $Key=>$value){
                                        $selected = '';

                                        if($Key==$language){
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="{{ $Key }}" <?php echo $selected; ?> ><?php echo $value; ?></option>

                                        <?php
                                    }
                                }
                                ?>
                            </select>

                            @include('snippets.errors_first', ['param' => 'language'])
                        </div>
                    </div> */ ?>

                    <?php
                    $image_required = $image_req;
                    if($id > 0){
                        $image_required = '';
                    }
                    ?>
                    <div class="col-md-6">

                        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label {{ $image_required }}">Image:</label>

                            <input type="file" id="image" name="image"/>

                            @include('snippets.errors_first', ['param' => 'image'])
                        </div>
                        <?php
                        if(!empty($image)){
                            if($storage->exists($path.$image))
                            {
                                ?>
                                <div class="col-md-2 image_box">
                                 <img src="{{ url('/storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                                 <a href="javascript:void(0)" data-id="{{ $id }}" class="del_image">Delete</a>
                             </div>
                             <?php        
                         }

                         ?>
                         <?php
                     }
                     ?>

                     <input type="hidden" name="old_image" value="{{ $old_image }}">

                 </div>


                     <div class="col-md-6 col-sm-6">
                        <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                            <label class="control-label ">Sort Order:</label>

                            <input type="text" name="sort_order" class="form-control" value="{{ old('sort_order', $sort_order) }}" maxlength="255" />
                            @include('snippets.errors_first', ['param' => 'sort_order'])
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

                    <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }} col-md-12">
                            <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }}">
                                <label class="control-label ">Featured:</label>

                                <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />

                                @include('snippets.errors_first', ['param' => 'featured'])
                            </div>
                        </div>
                   
                    <div class="clearfix"></div>

                </div>

                <br>
                <br>

                <div class="form-group">
                        <input type="hidden" name="back_url" value="{{ $back_url }}" >
                            <button type="submit" class="btn btn-success" title="Create this new album"><i class="fa fa-save"></i> Submit</button>

                            <a href="{{ url($back_url) }}" class="btn_admin2" title="Click here to cancel">Cancel</a>
                        </div>
            </form>
        </div>

    </div>
</div>

@slot('bottomBlock')

<script type="text/javascript" src="{{ url('/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

<script type="text/javascript">

    $('.select2').select2({
            placeholder: "Select Module Name",
            allowClear: true
        });
    
</script>

<script type="text/javascript">

$(document).ready(function(){

    $(".del_image").click(function(){

        var current_sel = $(this);

        var image_id = $(this).attr('data-id');

        conf = confirm("Are you sure to Delete this Image?");

        if(conf){

            var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.image_categories.ajax_delete_image') }}",
                type: "POST",
                data: {image_id},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                beforeSend:function(){
                   $(".ajax_msg").html("");
                },
                success: function(resp){
                    if(resp.success){
                        $(".ajax_msg").html(resp.msg);
                        current_sel.parent('.image_box').remove();
                    }
                    else{
                        $(".ajax_msg").html(resp.msg);
                    }
                    
                }
            });
        }

    });


    });

</script>
@endslot

@endcomponent

