@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
    <style>
        .bootstrap-tagsinput { display: block;}
        .slugEdit {
            position: absolute;
            right: 22px;
            top: 30px;
            font-size: 15px;
            opacity: .7;
    </style>
@endslot
    
<?php
    $banner_id = (isset($banner->id))?$banner->id:'';
    $title = (isset($banner->title))?$banner->title:'';
    $slug = (isset($banner->slug))?$banner->slug:'';
    $status = (isset($banner->status))?$banner->status:1;
    $type = (isset($banner->type))?$banner->type:1;
    $video_type = (isset($banner->video_type))?$banner->video_type:1;
    $video_embed = (isset($banner->video_embed))?$banner->video_embed:"";
    $video = (isset($banner->video))?$banner->video:"";

    $storage = Storage::disk('public');
    $video_path = 'banners/video/';
    $path = 'banners/';
    $old_video = $video;
    $image_req = '';
    $routeName = CustomHelper::getAdminRouteName();
?>

<div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
    <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
        <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>Back</a>
    <?php } ?>
    </div>
</div>

<div class="centersec">
<div class="bgcolor p10">

    @include('snippets.errors')
    @include('snippets.flash')

    <div class="ajax_msg"></div>
    <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                <label for="link_name" class="control-label required">Title:</label>
                <input type="text" id="title" class="form-control" name="title" value="{{ old('title', $title) }}" />
                @include('snippets.errors_first', ['param' => 'title'])
            </div>
        </div>
            <?php
                if(!empty($banner->id)){
                    ?>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }} slug">
                            <label for="link_name" class="control-label required">Slug:</label>

                            <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $slug) }}" />

                            <a href="javascript:void(0);" class="slugEdit" id="EditBSlug" title="Edit"><i class="fas fa-edit"></i></a>

                            @include('snippets.errors_first', ['param' => 'slug'])
                        </div>
                    </div>
                <?php }?>

        <div class="col-md-6">
            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                <label for="type" class="control-label">Type:</label>
                <?php
                    $type = old('type',$type);
                ?>
                <select name="type" id="type" class="form-control">
                    <option value="1" <?php echo ($type == 1) ? "selected":""; ?>>Images</option>
                    <option value="2" <?php echo ($type == 2) ? "selected":""; ?>>Video</option>
                </select>

                @include('snippets.errors_first', ['param' => 'type'])
            </div>
        </div>

        <div class="col-md-6 video_div" style="display: none;">
            <div class="form-group{{ $errors->has('video_type') ? ' has-error' : '' }}">
                <label for="video_type" class="control-label">Video Type:</label>
                <?php
                    $video_type = old('video_type',$video_type);
                ?>
                <select name="video_type" id="video_type" class="form-control">
                    <option value="1" <?php echo ($video_type == 1) ? "selected":""; ?>>Uploaded</option>
                    <option value="2" <?php echo ($video_type == 2) ? "selected":""; ?>>Youtube/Vimeo</option>
                </select>

                @include('snippets.errors_first', ['param' => 'type'])
            </div>
        </div>
    
        <div class="col-md-6 video_div" style="display: none;">
            <div class="form-group video_embed {{ $errors->has('video_embed') ? ' has-error' : '' }}" style="display:none;">
                <label for="video_embed" class="control-label">Video Embed Code:</label>
                <?php
                    $video_embed = old('video_embed',$video_embed);
                ?>
                <textarea name="video_embed" id="video_embed" class="form-control">{{ old('video_embed',$video_embed) }}</textarea>

                @include('snippets.errors_first', ['param' => 'video_embed'])
            </div>

            <?php
                $image_required = $image_req;
                if($banner_id > 0){
                    $image_required = '';
                }
            ?>
            <div class="form-group video_browse {{ $errors->has('video') ? ' has-error' : '' }}">
                <label for="video" class="control-label {{ $image_required }}">Video:</label>
                <?php if(empty($video)){ ?>
                <div class="browse_video_section" >
                    <div id="resumable-error" style="display: none">
                        Resumable not supported
                    </div>
                    <div id="resumable-drop" style="display: none">
                        <p><span class="btn btn-primary" id="resumable-browse" data-url="{{ route('admin.banners.uploadVideo') }}" >Upload</span>
                        </p>
                        <p></p>
                    </div>
                    <ul id="file-upload-list" class="list-unstyled"  style="display: none"></ul>
                    <br/>
                </div>
                <?php } ?>
                <input type="hidden" name="video_name" id="video_name" value="{{ $video }}" >
                @include('snippets.errors_first', ['param' => 'video_name'])

                @include('snippets.errors_first', ['param' => 'video'])
            </div>
            <?php
            if(!empty($video)){
                if($storage->exists($video_path.$video))
                {
                    ?>
                    <div class="col-md-2 image_box">
                        <a href="{{ url('storage/'.$video_path.$video) }}" target="_blank">{{ $video }}</a><br>
                        <a href="javascript:void(0)" class="btn btn-danger delVideo" data-id="{{ $banner_id }}" data='image'>Delete</a>
                    </div>
                    <?php        
                }
                ?>
                <?php
            }
            ?>
            <input type="hidden" name="old_video" value="{{ $old_video }}">
        </div>

        <div class="col-md-12">
            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                <label class="control-label">Status:</label>
                &nbsp;&nbsp;
                Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                &nbsp;
                Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                @include('snippets.errors_first', ['param' => 'status'])
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <p></p>
                <input type="hidden" id="banner_id" class="form-control" name="banner_id" value="{{ old('banner_id', $banner_id) }}"  />
                <button type="submit" class="btn btn-success" title="Create this new banner"><i class="fa fa-save"></i> Submit</button>
            </div>
        </div>
    </div>
</form>
</div>
</div>

@slot('bottomBlock')

<script>
    $(document).ready(function(){

        var typeIdVal = "{{ old('type',$type) }}";
        if(typeIdVal == 2){
            $('.video_div').show();
            var videoTypeIdVal = "{{ old('video_type',$video_type) }}";

            if(videoTypeIdVal == 2){
                $('.video_embed').show();
                $('.video_browse').hide();
            }else{
                $('.video_embed').hide();
                $('.video_browse').show();                
            }
        }else{
            $('.video_div').hide();
        }

        $('#type').change(function() {
            var typeId = $(this).val();
            if(typeId == 2){
                $('.video_div').show();
            }else{
                $('.video_div').hide();
            }
        });

        $('#video_type').change(function() {
            var videoTypeId = $(this).val();
            if(videoTypeId == 2){
                $('.video_embed').show();
                $('.video_browse').hide();
            }else{
                $('.video_embed').hide();
                $('.video_browse').show();                
            }
        });

        $(".delVideo").click(function(){

            var current_sel = $(this);

            var id = $(this).attr('data-id');

            conf = confirm("Are you sure to Delete this Banner Video?");

            if(conf){

                var _token = '{{ csrf_token() }}';

                $.ajax({
                    url: "{{ route($routeName.'.banners.ajax_delete_video') }}",
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
                        $(".ajax_msg").html(resp.msg);
                        current_sel.parent('.image_box').remove();
                        location.reload();
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.1.0/resumable.min.js"></script>
<script type="text/javascript">
var slug = '{{$slug}}';
if(slug != ''){
  $('#slug').attr('readonly',true);
}

$("#EditBSlug").click(function(){
    $('#slug').attr('readonly',false);
});
</script>

<script>
    var $ = window.$; // use the global jQuery instance

    var _token = '{{ csrf_token() }}';

    var $fileUpload = $('#resumable-browse');
    var $fileUploadDrop = $('#resumable-drop');
    var $uploadList = $("#file-upload-list");

    if ($fileUpload.length > 0 && $fileUploadDrop.length > 0) {
        var resumable = new Resumable({
            // Use chunk size that is smaller than your maximum limit due a resumable issue
            // https://github.com/23/resumable.js/issues/51
            chunkSize: 1 * 1024 * 1024, // 1MB
            simultaneousUploads: 3,
            maxFiles:1,
            fileType:['mp4'],
            testChunks: false,
            headers: {
                'Accept' : 'application/json'
            },
            throttleProgressCallbacks: 1,
            // Get the url from data-url tag
            target: $fileUpload.data('url'),
            // Append token to the request - required for web routes
            query:{_token : _token}
        });

    // Resumable.js isn't supported, fall back on a different method
        if (!resumable.support) {
            $('#resumable-error').show();
        } else {
            // Show a place for dropping/selecting files
            $fileUploadDrop.show();
            resumable.assignDrop($fileUpload[0]);
            resumable.assignBrowse($fileUploadDrop[0]);

            // Handle file add event
            resumable.on('fileAdded', function (file) {
                // Show progress pabr
                $uploadList.show();
                // Show pause, hide resume
                $('.resumable-progress .progress-resume-link').hide();
                $('.resumable-progress .progress-pause-link').show();
                // Add the file to the list
                $uploadList.append('<li class="resumable-file-' + file.uniqueIdentifier + '">Uploading <span class="resumable-file-name"></span> <span class="resumable-file-progress"></span>');
                $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-name').html(file.fileName);
                // Actually start the upload
                resumable.upload();
            });
            resumable.on('fileSuccess', function (file, message) {
                // Reflect that the file upload has completed
                response = JSON.parse(message);
                $('#video_name').val(response.name);
                //$('.browse_video_section').hide();
                //console.log(response);
                $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html('(completed)');
            });
            /*resumable.on('complete', function (file, message) { // trigger when file upload complete
                //console.log(file);
                //$('#video_name').val(response);
            });*/
            resumable.on('fileError', function (file, message) {
                // Reflect that the file upload has resulted in error
                $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html('(file could not be uploaded: ' + message + ')');
            });
            resumable.on('fileProgress', function (file) {
                // Handle progress for both the file and the overall upload
                $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html(Math.floor(file.progress() * 100) + '%');
                $('.progress-bar').css({width: Math.floor(resumable.progress() * 100) + '%'});
            });
        }

    }
</script>
@endslot


@endcomponent