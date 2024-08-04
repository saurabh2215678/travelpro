@component('admin.layouts.main')

    @slot('title')
        Admin - Additional Info(Accommodation) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endslot

<?php
// prd($accommodation_info);
$routeName = CustomHelper::getAdminRouteName();

$info_title = (isset($accommodation_info->title))?$accommodation_info->title:'';
$brief = (isset($accommodation_info->brief))?$accommodation_info->brief:'';
$image = (isset($accommodation_info->image))?$accommodation_info->image:'';
$description = (isset($accommodation_info->description))?$accommodation_info->description:'';
$sort_order = (isset($accommodation_info->sort_order))?$accommodation_info->sort_order:0;
$status = (isset($accommodation_info->status))?$accommodation_info->status:1;
$storage = Storage::disk('public');
$path = 'accommodations/';

$old_image = $image;
$image_req = '';
$link_req = '';
?>
<?php
$active_menu = "accommodations".$accommodation_id.'/additional-info';
?>
@if(!empty($accommodation_id))
    @include('admin.includes.accommodationoptionmenu')
@endif
<div class="top_title_admin">
<div class="title">
<h2>{{ $page_heading }}</h2>
</div>
</div>

   <div class="centersec">
    <div class="bgcolor">

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>
			<div class="bgcolor">
            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                
                <div class="form-group col-md-6{{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title" class="control-label required">Title:</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title',$info_title) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'title'])
                </div>

                <div class="form-group col-md-12{{ $errors->has('brief') ? ' has-error' : '' }}">
                    <label for="brief" class="control-label">Brief:</label>

                    <textarea name="brief" id="brief" class="form-control" ><?php echo old('brief', $brief); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'brief'])
                </div>

				<div class="clearfix"></div>
                <div class="form-group  col-md-12{{ $errors->has('description') ? ' has-error' : '' }}">
                	<label for="description" class="control-label required">Description:</label>

                	<textarea name="description" id="description" class="form-control ckeditor" ><?php echo old('description', $description); ?></textarea>    

                	@include('snippets.errors_first', ['param' => 'description'])
                </div>

                <div class="clearfix"></div>
            
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

                <div class="form-group  col-md-6{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Sort Order:</label>

                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>

                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                    <label class="control-label">Status:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                    &nbsp;
                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                    @include('snippets.errors_first', ['param' => 'status'])
                </div>

                <div class="clearfix"></div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                     <a href="<?php echo url($routeName.'/accommodations/'.$accommodation->id).'/additional-info' ?>" class="btn_admin2" title="Cancel">Cancel</a>
                </div>
                <br/><br/>
            </form>
			</div>
        </div>       
 
    </div>

@slot('bottomBlock')

    <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        var editor = CKEDITOR.replace('description', {
            filebrowserImageUploadUrl: "<?php echo route($routeName.'.ck_upload',['_token' => csrf_token()]);?>",
            filebrowserUploadMethod: 'form'
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
                url: "{{ route($routeName.'.accommodations.ajax_delete_images',['accommodation_id' => $accommodation_id]) }}",
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