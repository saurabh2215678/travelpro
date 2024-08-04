@component('admin.layouts.main')

@slot('title')
Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
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
//pr($page);
$templates = CustomHelper::getTemplates('pages');
$routeName = CustomHelper::getAdminRouteName();
$parent_id = (isset($page->parent_id))?$page->parent_id:$parent_id;
$cms_type = (isset($page->cms_type))?$page->cms_type:'page';
$name = (isset($page->name))?$page->name:'';
$slug = (isset($page->slug))?$page->slug:'';
$title = (isset($page->title))?$page->title:'';
$heading = (isset($page->heading))?$page->heading:'';
$brief = (isset($page->brief))?$page->brief:'';
$template = (isset($page->template))?$page->template:'';
$content = (isset($page->content))?$page->content:'';
$status = (isset($page->status))?$page->status:1;
$featured = (isset($page->featured))?$page->featured:0;
$sort_order = (isset($page->sort_order))?$page->sort_order:0;

$meta_title = (isset($page->meta_title))?$page->meta_title:'';
$meta_keyword = (isset($page->meta_keyword))?$page->meta_keyword:'';
$meta_description = (isset($page->meta_description))?$page->meta_description:'';
$image = (isset($page->image))?$page->image:'';
$banner_image = (isset($page->banner_image))?$page->banner_image:'';
$document = (isset($page->document))?$page->document:'';
// $document = (isset($page->document))?$page->document:'';
// $doc_link = (isset($page->doc_link))?$page->doc_link:'';

$storage = Storage::disk('public');
//pr($storage);
$path = 'cms_pages/';
$thumb_path = 'cms_pages/thumb/';


$old_image = $image;
$old_document = $document;


$banner_old_image = $banner_image;
$image_req = '';
$link_req = '';

$active_menu = "cms";
$back_url = CustomHelper::BackUrl();
?>
@if(!empty($id))
    @include('admin.includes.cmsmenu')
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
        @include('snippets.errors')
        @include('snippets.flash')

        <div class="ajax_msg"></div>
        <div class="bgcolor">
            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}

                <input type="hidden" name="parent_id" value="{{$parent_id}}">

                <div class="form-group  col-md-4{{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title" class="control-label required">Title:</label>

                    <input type="text" name="title" value="{{ old('title', $title) }}" id="title" class="form-control"  maxlength="255"  />

                    @include('snippets.errors_first', ['param' => 'title'])
                </div>

                <div class="form-group  col-md-4{{ $errors->has('heading') ? ' has-error' : '' }}">
                    <label for="heading" class="control-label">Heading:</label>

                    <input type="text" name="heading" value="{{ old('heading', $heading)}}" id="heading" class="form-control"  maxlength="255" />

                    @include('snippets.errors_first', ['param' => 'heading'])
                </div>


                <div class="form-group  col-md-4{{ $errors->has('template') ? ' has-error' : '' }}">
                    <label for="template" class="control-label">Tempalate:</label>

                    <select class="form-control" name="template">
                        <option value="">Select</option>
                        <?php
                        
                       /* if(!empty($templates) && count($templates) > 0){
                            foreach ($templates as $tKey => $tVal){
                                $selected = '';
                                if($template == $tKey){
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="{{$tKey}}" {{$selected}}>{{$tVal}}</option>
                                <?php
                            }
                        }*/
                        ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'template'])
                </div>

                <?php if(isset($page) && !empty($page) && !empty($page->id)){ ?>
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }} slug">
                        <label for="link_name" class="control-label required">Slug:</label>
                        <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $slug) }}" />
                        <a href="javascript:void(0);" class="slugEdit" id="EditCmsSlug" title="Edit"><i class="fas fa-edit"></i></a>
                        @include('snippets.errors_first', ['param' => 'slug'])
                    </div>
                </div>
                <?php } ?>

                <div class="form-group  col-md-12{{ $errors->has('brief') ? ' has-error' : '' }}">
                    <label class="control-label">Brief:</label>

                    <textarea name="brief" class="form-control" ><?php echo old('brief', $brief); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'brief'])
                </div>

                <div class="clearfix"></div>
                <div class="form-group  col-md-12{{ $errors->has('content') ? ' has-error' : '' }}">
                    <label for="content" class="control-label">Content:</label>

                    <textarea name="content" id="content" class="form-control ckeditor" ><?php echo old('content', $content); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'content'])
                </div>

                <?php
                $image_required = $image_req;
                if($id > 0) {
                    $image_required = '';
                }
                ?>
                <div class="col-md-12">
                    <div class="row">
                    <div class="col-md-6">

                        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label {{ $image_required }}">Image:</label>

                            <input type="file" id="image" name="image"/>

                            @include('snippets.errors_first', ['param' => 'image'])
                        </div>
                        <?php
                        if(!empty($image)){
                            
                             if(File::exists("storage/".$path.$image))
                            {
                                ?>
                                <div class="col-md-2 image_box">
                                   <img src="{{ url('/storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
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


                   <div class="col-md-6">

                    <div class="form-group{{ $errors->has('banner_image') ? ' has-error' : '' }}">
                        <label for="sort_order" class="control-label {{ $image_required }}">Banner Image:</label>

                        <input type="file" id="banner_image" name="banner_image"/>

                        @include('snippets.errors_first', ['param' => 'banner_image'])
                    </div>
                    <?php
                    if(!empty($banner_image)){

                     
                        if(File::exists("storage/".$path.$banner_image))
                        {
                            ?>
                            <div class="col-md-2 image_box">
                                <img src="{{ url('/storage/'.$path.'thumb/'.$banner_image) }}" style="width: 100px;"><br>
                                <a href="javascript:void(0)" data-id="{{ $id }}" data='banner_image' class="del_image">Delete</a>
                            </div>
                            <?php        
                        }

                        ?>
                        <?php
                    }
                    ?>
                    <input type="hidden" name="banner_old_image" value="{{ $banner_old_image }}">
                </div>
                </div>
            </div>

            <div class="col-md-6">

                    <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                        <label for="sort_order" class="control-label {{ $image_required }}">Document:</label>

                        <input type="file" id="document" name="document"/>

                        @include('snippets.errors_first', ['param' => 'image'])
                    </div>
                    <?php
                    if(!empty($document)){
                        if($storage->exists($path.$document))
                        {
                            ?>
                            <div class="col-md-2 image_box">
                             
                                <a href="{{ url('/storage/'.$path.$document) }}" target="_blank" class="btn btn-primary">View</a><br>
                                <a href="javascript:void(0)" data-id="{{ $id }}" data='document' class="del_image">Delete</a>
                            </div>
                            <?php        
                        }

                        ?>
                        <?php
                    }
                    ?>
                    
                    <input type="hidden" name="old_image" value="{{ $old_image }}">
                </div>


            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Sort Order:</label>

                    <input type="number" id="sort_order" class="form-control" name="sort_order" value="{{ old('sort_order', $sort_order) }}"  />

                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>
            </div>

            <hr>
            

            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-6">
                <label class="control-label">Status:</label>
                &nbsp;&nbsp;
                Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                &nbsp;
                Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                @include('snippets.errors_first', ['param' => 'status'])
            </div>

            <?php if(empty($parent_id)){ ?>
            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-6">
                <div class="form-group{{ $errors->has('cms_type') ? ' has-error' : '' }}">
                    <label for="cms_type" class="control-label">CMS Type:</label>
                    <input type="radio" name="cms_type" value="page" <?php if($cms_type=='page'){ echo 'checked'; }?> > Page
                    <input type="radio" name="cms_type" value="section" <?php if($cms_type=='section'){ echo 'checked'; }?> > Section
                    @include('snippets.errors_first', ['param' => 'cms_type'])
                </div>
            </div>
            <?php } ?>

           <?php /* <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }} col-md-12">
                <label class="control-label ">Featured:</label>

                <input type="checkbox" name="featured" value="1" <?php //echo ($featured == '1')?'checked':'';  />

                @include('snippets.errors_first', ['param' => 'featured'])
            </div> */ ?>
            
            <div class="clearfix"></div>
            <div class="form-group col-md-12">
                <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>

                <a href="{{ route('admin.cms.index') }}" class="btn_admin2" title="Cancel">Cancel</a>
            </div>
            <br/><br/>

        </form>
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

$("#EditCmsSlug").click(function(){
    $('#slug').attr('readonly',false);
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    $(".del_image").click(function(){
        var current_sel = $(this);
        var image_id = $(this).attr('data-id');
        var type = $(this).attr('data');
    conf = confirm("Are you sure to Delete this Image?");
    if(conf){
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ route($routeName.'.cms.ajax_delete_image') }}",
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
</script>

@endslot

@endcomponent