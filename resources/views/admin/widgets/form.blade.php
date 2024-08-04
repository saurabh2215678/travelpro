@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
// $templates = CustomHelper::getTemplates('widgets');
// $BackUrl = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

$id = (isset($widget_query->id))?$widget_query->id:'';
$widget_name = (isset($widget_query->widget_name))?$widget_query->widget_name:'';
$widget_identifier = (isset($widget_query->slug))?$widget_query->slug:'';
$section_heading = (isset($widget_query->section_heading))?$widget_query->section_heading:'';
$section_sub_heading = (isset($widget_query->section_sub_heading))?$widget_query->section_sub_heading:'';
$about_widget_desc = (isset($widget_query->about_widget_desc))?$widget_query->about_widget_desc:'';
$description = (isset($widget_query->description))?$widget_query->description:'';
$status = (isset($widget_query->status))?$widget_query->status:1;
$image1 = (isset($widget_query->image1))?$widget_query->image1:'';
$image2 = (isset($widget_query->image2))?$widget_query->image2:'';

$storage = Storage::disk('public');
$path = 'widgets/';

$old_image1 = $image1;
$old_image2 = $image2;
$image_req = '';
$link_req = '';
?>

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

                <div class="form-group col-md-6{{ $errors->has('widget_name') ? ' has-error' : '' }}">
                    <label for="widget_name" class="control-label required">Widget Name:</label>
                    <input type="text" name="widget_name" id="widget_name" class="form-control" value="{{ old('widget_name',$widget_name) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'widget_name'])
                </div>

                 <?php
                if(!empty($widget_query->id)){
                    ?>
                 <div class="form-group col-md-6{{ $errors->has('widget_identifier') ? ' has-error' : '' }}">
                    <label for="widget_identifier" class="control-label required">Widget Identifier:</label>
                    <input type="text" name="widget_identifier" id="widget_identifier" class="form-control" value="{{ old('widget_identifier',$widget_identifier) }}" autocomplete="off">

                    <a href="javascript:void(0);" class="slugEdit" id="EditDSlug" title="Edit"><i class="fas fa-edit"></i></a>  
                    @include('snippets.errors_first', ['param' => 'widget_identifier'])
                </div>
                <?php }?>

                <div class="form-group col-md-6{{ $errors->has('section_heading') ? ' has-error' : '' }}">
                    <label for="section_heading" class="control-label required">Section Heading:</label>
                    <input type="text" name="section_heading" id="section_heading" class="form-control" value="{{ old('section_heading',$section_heading) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'section_heading'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('section_sub_heading') ? ' has-error' : '' }}">
                    <label for="section_sub_heading" class="control-label ">Section Sub Heading:</label>
                    <input type="text" name="section_sub_heading" id="section_sub_heading" class="form-control" value="{{ old('section_sub_heading',$section_sub_heading) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'section_sub_heading'])
                </div>

                <div class="form-group col-md-12{{ $errors->has('about_widget_desc') ? ' has-error' : '' }}">
                    <label for="about_widget_desc" class="control-label">About Widget Description:</label>

                    <textarea name="about_widget_desc" id="about_widget_desc" class="form-control" ><?php echo old('about_widget_desc', $about_widget_desc); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'about_widget_desc'])
                </div>

				<div class="clearfix"></div>
                <div class="form-group  col-md-12{{ $errors->has('description') ? ' has-error' : '' }}">
                	<label for="description" class="control-label required">Description:</label>

                	<textarea name="description" id="description" class="form-control ckeditor" ><?php echo old('description', $description); ?></textarea>    

                	@include('snippets.errors_first', ['param' => 'description'])
                </div>

                <?php
                $image_required = $image_req;
                if($id > 0){
                    $image_required = '';
                }
                ?>
                <div class="col-full">
                    <div class="col-md-6">

                        <div class="form-group{{ $errors->has('image1') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label {{ $image_required }}">Image1:</label>

                            <input type="file" id="image1" name="image1"/>

                            @include('snippets.errors_first', ['param' => 'image1'])
                        </div>
                        <?php
                        if(!empty($image1)){
                            if($storage->exists($path.$image1)){
                        ?>
                            <div class="col-md-2 image_box">
                               <img src="{{ url('/storage/'.$path.'thumb/'.$image1) }}" style="width: 100px;"><br>
                               <a href="javascript:void(0)" data-id="{{ $id }}" data='image1' class="del_image">Delete</a>
                           </div>
                        <?php } ?>
                        <?php } ?>
                       <input type="hidden" name="old_image1" value="{{ $old_image1 }}">
                   </div>

                   <div class="col-md-6">

                    <div class="form-group{{ $errors->has('image2') ? ' has-error' : '' }}">
                        <label for="sort_order" class="control-label {{ $image_required }}">Image2 :</label>

                        <input type="file" id="image2" name="image2"/>

                        @include('snippets.errors_first', ['param' => 'image2'])
                    </div>
                    <?php
                    if(!empty($image2)){
                        if($storage->exists($path.$image2))
                        {
                            ?>
                            <div class="col-md-2 image_box">
                                <img src="{{ url('/storage/'.$path.'thumb/'.$image2) }}" style="width: 100px;"><br>
                                <a href="javascript:void(0)" data-id="{{ $id }}" data='image2' class="del_image">Delete</a>
                            </div>
                            <?php        
                        }

                        ?>
                        <?php
                    }
                    ?>
                    <input type="hidden" name="old_image2" value="{{ $old_image2 }}">
                </div>
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

                    <a href="{{ route('admin.widget.index') }}" class="btn_admin2" title="Cancel">Cancel</a>
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
    var widget_identifier = '{{$widget_identifier}}';
    if(widget_identifier != ''){
      $('#widget_identifier').attr('readonly',true);
    }

    $("#EditDSlug").click(function(){
        $('#widget_identifier').attr('readonly',false);
    });
    </script>

    <script type="text/javascript">

        var description = document.getElementById('description');
        CKEDITOR.replace(description);

        $('.select2').select2({
            placeholder: "Please Select",
            allowClear: true
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
                        url: "{{ route($routeName.'.widget.ajax_delete_image') }}",
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