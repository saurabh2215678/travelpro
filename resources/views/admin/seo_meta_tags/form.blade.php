@component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link href="{{url('/')}}/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
<style>
        .bootstrap-tagsinput { display: block;}
        .urlEdit {
            position: absolute;
            right: 22px;
            top: 30px;
            font-size: 15px;
            opacity: .7;
    </style>
    @endslot

<div class="row-full">

    <?php
    $routeName = CustomHelper::getAdminRouteName();
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    if(empty($back_url)){
        $back_url = 'admin/module_config';
    }
    
    $id = (isset($rec->id))?$rec->id:'';
    $module_config_id = $id;
    $description = (isset($rec->description))?$rec->description:'';
    $page_title = (isset($rec->page_title))?$rec->page_title:'';
    $page_brief = (isset($rec->page_brief))?$rec->page_brief:'';
    $page_description = (isset($rec->page_description))?$rec->page_description:'';
    $page_url_label = (isset($rec->page_url_label))?$rec->page_url_label:'';
    $page_url = (isset($rec->page_url))?$rec->page_url:'';
    $page_detail_url = (isset($rec->page_detail_url))?$rec->page_detail_url:'';
    $identifier = (isset($rec->identifier))?$rec->identifier:'';
    $meta_title = (isset($rec->meta_title))?$rec->meta_title:'';
    $meta_keyword = (isset($rec->meta_keyword))?$rec->meta_keyword:'';
    $meta_description = (isset($rec->meta_description))?$rec->meta_description:'';
    $other_meta_tag = (isset($rec->other_meta_tag))?$rec->other_meta_tag:'';
    $module_type = (isset($rec->module_type))?$rec->module_type:'';
    $admin_email = (isset($rec->admin_email))?$rec->admin_email:'';
    $admin_phone = (isset($rec->admin_phone))?$rec->admin_phone:'';
    $status = (isset($rec->status))?$rec->status:'1';
    $agent_discount = (isset($rec->agent_discount))?$rec->agent_discount:'1';
    $online_booking = (isset($rec->online_booking))?$rec->online_booking:'1';
    $image = (isset($rec->image))?$rec->image:'';

    $storage = Storage::disk('public');
    $path = 'seo_tags/';

    $old_image = $image;
    $image_req = '';
    $link_req = '';

    $active_menu = "module_config";
    ?>
    @if(!empty($module_config_id))
    @include('admin.includes.modulemenu')
    @endif
<div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
  
    </div>

        <div class="centersec">
        <div class="bgcolor p10">
            @include('snippets.errors')
            @include('snippets.flash')

            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}

                <div class="row">

                <?php $seo_module_config_arr = config('custom.seo_module_config_arr'); 
                
                      if(isset($seo_module_config_arr) && count($seo_module_config_arr) > 0){
                ?>

                <div class="col-sm-12 col-md-4">
                    <div class="form-group{{ $errors->has('identifier') ? ' has-error' : '' }}">
                    <label for="identifier" class="control-label ">Identifier</label>

                        <select name="identifier" class="form-control" disabled>
                            <option value="">--Select--</option>
                            
                            <?php foreach ($seo_module_config_arr as $key => $seo_meta_tag) { ?>
                                
                                <option @if(old('identifier',$identifier) == $key) selected @endif value="{{$key}}">{{$seo_meta_tag}}</option>

                            <?php } ?>

                        </select>

                        @include('snippets.errors_first', ['param' => 'identifier'])
                        </div>
                    </div>

                    <?php } ?>

                    <div class="col-md-8 col-sm-8">
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label class="control-label ">Identifier Description</label>

                            <input type="text" name="description" class="form-control" value="{{ old('description', $description) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'description'])
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('page_title') ? ' has-error' : '' }}">
                            <label class="control-label ">Page Title</label>

                            <input type="text" name="page_title" class="form-control" value="{{ old('page_title', $page_title) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'page_title'])
                        </div>
                    </div>

                    <div class="form-group col-md-12{{$errors->has('page_brief')?' has-error':''}}">
                    <label for="page_brief" class="control-label">Page Brief </label>

                    <textarea name="page_brief" id="page_brief" class="form-control" ><?php echo old('page_brief', $page_brief); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'page_brief'])
                </div>


                    <div class="form-group  col-md-12{{ $errors->has('page_description') ? ' has-error' : '' }}">
                        <label for="page_description" class="control-label ">Page Description </label>

                        <textarea name="page_description" id="page_description" class="form-control ckeditor" ><?php echo old('page_description', $page_description); ?></textarea>    

                        @include('snippets.errors_first', ['param' => 'page_description'])
                    </div>

                    

                    <?php
                if(!empty($rec->id)){
                    ?>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('page_url') ? ' has-error' : '' }}">
                            <label class="control-label ">Page Listing Url/Link : (Warning message: The existing urls will become inaccessible after changing the URL/Slug.)</label>

                            <input type="text" name="page_url" id="page_url" class="form-control" value="{{ old('page_url', $page_url) }}" maxlength="255" />

                            <a href="javascript:void(0);" class="urlEdit" id="EditPageUrl" title="Edit"><i class="fas fa-edit"></i></a>

                            @include('snippets.errors_first', ['param' => 'page_url'])
                        </div>
                    </div>
                     <?php }?>

                     <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('page_url_label') ? ' has-error' : '' }}">
                            <label class="control-label ">Page Listing Url/Label</label>

                            <input type="text" name="page_url_label" class="form-control" value="{{ old('page_url_label', $page_url_label) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'page_url_label'])
                        </div>
                    </div>


                     <?php
                if(!empty($rec->id)){
                    ?>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('page_detail_url') ? ' has-error' : '' }}">
                            <label class="control-label ">Page Detail Url/Link : (Warning message: The existing urls will become inaccessible after changing the URL/Slug.)</label>

                            <input type="text" id="page_detail_url" name="page_detail_url" class="form-control" value="{{ old('page_detail_url', $page_detail_url) }}" maxlength="255" />

                            <a href="javascript:void(0);" class="urlEdit" id="EditPageDetailUrl" title="Edit"><i class="fas fa-edit"></i></a>

                            @include('snippets.errors_first', ['param' => 'page_detail_url'])
                        </div>
                    </div>
                     <?php }?>


                     <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('meta_title') ? ' has-error' : '' }}">
                            <label class="control-label ">Meta Title </label>

                            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $meta_title) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'meta_title'])
                        </div>
                    </div>

                    <div class="form-group col-md-12{{$errors->has('meta_keyword')?' has-error':''}}">
                    <label for="meta_keyword" class="control-label">Meta Keyword </label>

                    <textarea name="meta_keyword" id="meta_keyword" class="form-control" ><?php echo old('meta_keyword', $meta_keyword); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'meta_keyword'])
                    </div>

                    <div class="form-group col-md-12{{$errors->has('meta_description')?' has-error':''}}">
                    <label for="meta_description" class="control-label">Meta Description </label>

                    <textarea name="meta_description" id="meta_description" class="form-control" ><?php echo old('meta_description', $meta_description); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'meta_description'])
                    </div>

                    <div class="form-group col-md-12{{$errors->has('other_meta_tag')?' has-error':''}}">
                    <label for="other_meta_tag" class="control-label">Other Meta Tag : For example < meta name="copyright" content="text" ></label>

                    <textarea name="other_meta_tag" id="other_meta_tag" class="form-control" ><?php echo old('other_meta_tag', $other_meta_tag); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'other_meta_tag'])
                    </div>

                    <?php
                $image_required = $image_req;
                if($id > 0){
                    $image_required = '';
                }
                ?>
        
                <div class="col-full">
                     <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label {{ $image_required }}">Image :</label>
                            <input type="file" id="image" name="image"/>
                            @include('snippets.errors_first', ['param' => 'image'])
                        </div>
                        <?php
                        if(!empty($image)){
                            if($storage->exists($path.$image))
                            {
                                ?>
                                <div class="col-md-2 image_box">
                                    <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                                    <a href="javascript:void(0)" data-id="{{ $id }}" data='image' class="del_image">Delete</a>
                                </div>
                                <?php        
                            }
                            ?>
                            <?php
                        }
                        ?>
                        <input type="hidden" name="old_image" value="{{ $old_image }}">
                    </div>
                </div>
                    <?php if($module_type == 'major') { ?>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('admin_email') ? ' has-error' : '' }}">
                            <label class="control-label ">Manager Email </label>

                            <input type="text" name="admin_email" class="form-control" value="{{ old('admin_email', $admin_email) }}" maxlength="255" />

                            @include('snippets.errors_first', ['param' => 'admin_email'])
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('admin_phone') ? ' has-error' : '' }}">
                            <label class="control-label ">Manager Phone </label>

                            <input type="text" name="admin_phone" class="form-control" value="{{ old('admin_phone', $admin_phone) }}" maxlength="12" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" />

                            @include('snippets.errors_first', ['param' => 'admin_phone'])
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label class="control-label">Agent Discount :</label>
                            <br>

                            <input class="" type="checkbox" <?php if($agent_discount==1) { echo 'checked';  } ?> name="agent_discount" id="agent_discount" value="1">



                            @include('snippets.errors_first', ['param' => 'agent_discount'])
                        </div>
                    </div>
                <?php } ?>



                       <div class="col-md-2">
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label class="control-label">Status :</label>
                            <br>

                            <input class="" type="checkbox" <?php if($status==1) { echo 'checked';  } ?> name="status" id="status" value="1">

                          

                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>
                       </div>

                    <div class="clearfix"></div>

                </div>


                    <div class="form-group">
                        <input type="hidden" name="back_url" value="{{ $back_url }}" >
                            <button type="submit" class="btn btn-success" title="Submit"><i class="fa fa-save"></i> Submit</button>

                            <a href="{{ url($back_url) }}" class="btn_admin2" title="Click here to cancel">Cancel</a>
                    </div>

            </form>
        </div>

    </div>
@slot('bottomBlock')

<script type="text/javascript" src="{{ url('/js/ckeditor/ckeditor.js') }}"></script>

<script type="text/javascript">
        //var content = document.getElementById('content');
        //CKEditor.replace(content);

        var editor = CKEDITOR.replace('page_description',{
                filebrowserImageUploadUrl: "<?php echo url($routeName.'/ck_upload?type=blogs&csrf_token='.csrf_token());?>"
            });
        
        </script>

<script type="text/javascript">

        $(document).ready(function(){
            $(".del_image").click(function(){
                var current_sel = $(this);
                var image_id = $(this).attr('data-id');
                var type = $(this).attr('data');
                //alert(type); return false;

                conf = confirm("Are you sure to Delete this Image?");
                if(conf){
                    var _token = '{{ csrf_token() }}';
                    $.ajax({
                        url: "{{ route($routeName.'.module_config.ajax_delete_image') }}",
                        type: "POST",
                        data: {image_id , type},
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

var page_url = '{{$page_url}}';
if(page_url != ''){
  $('#page_url').attr('readonly',true);
}

$("#EditPageUrl").click(function(){
    $('#page_url').attr('readonly',false);
});

var page_detail_url = '{{$page_detail_url}}';
if(page_detail_url != ''){
  $('#page_detail_url').attr('readonly',true);
}

$("#EditPageDetailUrl").click(function(){
    $('#page_detail_url').attr('readonly',false);
});

    </script>


    
@endslot

@endcomponent