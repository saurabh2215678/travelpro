@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<style>
    .slugEdit {position: absolute; right: 22px; top: 26px;font-size: 15px; opacity: .7;}
    </style>
@endslot

<?php
//pr($page);
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/themes';
}

// $routeName = CustomHelper::getAdminRouteName();


$parent_id = (isset($theme_query->parent_id))?$theme_query->parent_id:'';
$title = (isset($theme_query->title))?$theme_query->title:'';
$slug = (isset($theme_query->slug))?$theme_query->slug:'';
$brief = (isset($theme_query->brief))?$theme_query->brief:'';
$description = (isset($theme_query->description))?$theme_query->description:'';
//$identifier = (isset($theme_query->identifier))?$theme_query->identifier:'Package';
$status = (isset($theme_query->status))?$theme_query->status:'';
$sort_order = (isset($theme_query->sort_order))?$theme_query->sort_order:0;
$featured = (isset($theme_query->featured))?$theme_query->featured:0;

$page_title = (isset($theme_query->page_title))?$theme_query->page_title:'';
$page_description = (isset($theme_query->page_description))?$theme_query->page_description:'';
$page_keywords = (isset($theme_query->page_keywords))?$theme_query->page_keywords:'';
$image = (isset($theme_query->image))?$theme_query->image:'';
$icon = (isset($theme_query->icon))?$theme_query->icon:'';
$storage = Storage::disk('public');
//pr($storage);
$path = 'themes/';
$old_image = $image;
$banner_old_icon = $icon;
$image_req = '';
$link_req = '';
$theme_id = $id;
?>

<div class="centersec">


    @if(!empty($theme_id))
    <?php $active_menu = "themes"; ?>
    @include('admin.includes.themefaqmenu')
    @else
    <?php $active_menu = "packages.theme"; ?>
    @include('admin.includes.packagemenu')
    @endif
    <div class="top_title_admin tab-title">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>

    </div>
    <div class="bgcolor">
        @include('snippets.errors')
        @include('snippets.flash')

        <div class="ajax_msg"></div>

        <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            <input type="hidden" name="parent_id" value="{{$parent_id}}">

            <?php
            if(!empty($theme_query->id)){
                ?>
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }} slug">
                        <label for="slug" class="control-label required">Slug:</label>

                        <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $slug) }}" />

                        <a href="javascript:void(0);" class="slugEdit" id="EditPSlug" title="Edit"><i class="fas fa-edit"></i></a>
                        @include('snippets.errors_first', ['param' => 'slug'])
                    </div>
                </div>
            <?php }?>

            <div class="form-group  col-md-4{{ $errors->has('title') ? ' has-error' : '' }}">
                <label for="title" class="control-label required">Title:</label>

                <input type="text" name="title" value="{{ old('title', $title) }}" id="title" class="form-control"/>

                @include('snippets.errors_first', ['param' => 'title'])
            </div>

            <div class="form-group  col-md-4{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                <label for="sort_order" class="control-label">Display Order:</label>

                <input type="number" name="sort_order" value="{{ old('sort_order', $sort_order) }}" id="sort_order" class="form-control"/>

                @include('snippets.errors_first', ['param' => 'sort_order'])
            </div>


            <div class="form-group  col-md-12{{ $errors->has('brief') ? ' has-error' : '' }}">
                <label class="control-label">Brief:</label>

                <textarea name="brief" class="form-control" ><?php echo old('brief', $brief); ?></textarea>    

                @include('snippets.errors_first', ['param' => 'brief'])
            </div>

            <div class="clearfix"></div>
            <div class="form-group  col-md-12{{ $errors->has('description') ? ' has-error' : '' }}">
                <label for="description" class="control-label">Description:</label>

                <textarea name="description" id="description" class="form-control ckeditor" ><?php echo old('description', $description); ?></textarea>    

                @include('snippets.errors_first', ['param' => 'description'])
            </div>


            <?php
            $image_required = $image_req;
            if($id > 0){
                $image_required = '';
            }
            ?>
            <div class="col-md-12">
                <div class="col-md-6">

                    <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                        <label for="sort_order" class="control-label {{ $image_required }}">Image:</label>

                        <input type="file" id="image" name="image"/>

                        @include('snippets.errors_first', ['param' => 'image'])
                    </div>
                    <?php
                    if(!empty($image)){
                        if($storage->exists($path.$image)){
                            ?>
                            <div class="col-md-2 image_box">
                             <img src="{{ url('/storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                             <a href="javascript:void(0)" data-id="{{ $id }}" data='image' class="del_image">Delete</a>
                         </div>
                     <?php } ?>
                 <?php } ?>
                 <input type="hidden" name="old_image" value="{{ $old_image }}">
             </div>

             <div class="col-md-6">

                <div class="form-group{{ $errors->has('icon') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label {{ $image_required }}">Icon :</label>

                    <input type="file" id="icon" name="icon"/>

                    @include('snippets.errors_first', ['param' => 'icon'])
                </div>
                <?php
                if(!empty($icon)){
                    if($storage->exists($path.$icon))
                    {
                        ?>
                        <div class="col-md-2 image_box">
                            <img src="{{ url('/storage/'.$path.'thumb/'.$icon) }}" style="width: 100px;"><br>
                            <a href="javascript:void(0)" data-id="{{ $id }}" data='icon' class="del_image">Delete</a>
                        </div>
                        <?php        
                    }

                    ?>
                    <?php
                }
                ?>
                <input type="hidden" name="banner_old_icon" value="{{ $banner_old_icon }}">
            </div>
        </div>

        <div style="display:none;" class="col-md-4">
            <div class="form-group{{ $errors->has('identifier') ? ' has-error' : '' }}">
                <label for="identifier" class="control-label">Theme Categories Identifier :</label>
                <select class="form-control" name="identifier" id="identifier">
                 <?php //<option value="">--Select--</option> ?>
                 <option value="package" {{($identifier=='package')?'selected':''}}>Package</option>
                 <option value="activity" {{($identifier=='activity')?'selected':''}}>Activity</option>
             </select>
         </div>
     </div>

     <hr>
     <div class="col-md-12">
        <h3>SEO:</h3>
    </div>

    <div class="col-md-12 col-sm-12{{ $errors->has('page_title') ? ' has-error' : '' }}">
        <label for="page_title" class="control-label">Page Title:</label>

        <input type="text" name="page_title" value="{{ old('page_title', $page_title)}}" id="page_title" class="form-control"  />    

        @include('snippets.errors_first', ['param' => 'page_title'])
    </div>

    <div class="col-md-12 col-sm-12{{ $errors->has('page_description') ? ' has-error' : '' }}">
        <label for="page_description" class="control-label" >Page Description:</label>

        <textarea name="page_description" id="page_description"  class="form-control" >{{ old('page_description', $page_description) }}</textarea>    

        @include('snippets.errors_first', ['param' => 'page_description'])
    </div>

    <div class="col-md-12 col-sm-12{{ $errors->has('page_keywords') ? ' has-error' : '' }}">
        <label for="page_keywords" class="control-label" maxlength="688">Page Keywords:</label>

        <textarea name="page_keywords" id="page_keywords"  class="form-control" >{{ old('page_keywords', $page_keywords) }}</textarea>    

        @include('snippets.errors_first', ['param' => 'page_keywords'])
    </div>
            <?php //prd($packages);
            //if(!empty($packages) && count($packages) > 0){
            ?>

            <?php //}else{?>
             <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                <label class="control-label">Status:</label>
                &nbsp;&nbsp;
                Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                &nbsp;
                Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                @include('snippets.errors_first', ['param' => 'status'])
            </div>

            <?php //}?>

            <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }} col-md-12">
                <label class="control-label ">Featured:</label>

                <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />

                @include('snippets.errors_first', ['param' => 'featured'])
            </div>
            
            <div class="clearfix"></div>
            <div class="form-group col-md-12">
                <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>

                <a href="{{ route('admin.'.$segment.'.theme_index') }}" class="btn_admin2" title="Cancel">Cancel</a>
            </div>
            <br/><br/>
        </form>
    </div>
</div>

@slot('bottomBlock')
<script type="text/javascript" src="{{ url('/js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    //var content = document.getElementById('content');
    //CKEditor.replace(content);

    var editor = CKEDITOR.replace('brief','description' {
        filebrowserImageUploadUrl: "<?php echo url($routeName.'/ck_upload?type=blogs&csrf_token='.csrf_token());?>"
    });
</script>
<script type="text/javascript">
    var slug = '{{$slug}}';
    if(slug != ''){
      $('#slug').attr('readonly',true);
    }

    $("#EditPSlug").click(function(){
        $('#slug').attr('readonly',false);
    });
</script>
<script type="text/javascript">

    $(document).ready(function(){

        $(".del_image").click(function(){

            var current_sel = $(this);

            var image_id = $(this).attr('data-id');

            var type = $(this).attr('data');

                // alert(type); return false;

            conf = confirm("Are you sure to Delete this Image?");

            if(conf){

                var _token = '{{ csrf_token() }}';

                $.ajax({
                    url: "{{ route($routeName.'.'.$segment.'.ajax_delete_image') }}",
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