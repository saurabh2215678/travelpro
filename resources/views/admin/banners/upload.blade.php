@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="<?php echo url('/uploadifive');?>/uploadifive.css">
@endslot
<span id="alert_msg"></span>
<div class="row1">
    <div class="col-md-12">
        <h2>{{ $page_heading }} <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
        <a href="{{ url($back_url)}}" class="btn btn-sm btn-success" style='float: right;'>Back</a><?php } ?></h2>

        <?php
            $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
        ?>

        <div class="bgcolor">
        @include('snippets.errors')
        @include('snippets.flash')

        <form method="POST" action="" enctype="multipart/form-data" accept-charset="UTF-8" role="form">
            {{ csrf_field() }}
            <div class="row1">
                <div class="col-md-12">
                    <p>
                        <span style="font-family: arial, sans-serif;"><i><strong>Instructions on use:</strong></i> <br>
                        Please make sure you are trying to upload Image(s) file only with the extension <strong>.jpg</strong>, <strong>.jpeg</strong>, or <strong>.png</strong> at the end of the file name.<br><br></span>
                    </p>
                </div>
                <div class="row1">
                <div class="col-md-12">
                @if(CustomHelper::checkPermission('banners','add'))
                    <div class="form-group video_browse {{ $errors->has('images') ? ' has-error' : '' }}">
                        <label for="images" class="control-label">Images:</label>
                        <div class="browse_video_section" >
                            <div id="resumable-error" style="display: none">
                                Resumable not supported
                            </div>
                            <div id="resumable-drop" style="display: none">
                                <p><span class="btn btn-primary btn_admin" id="resumable-browse" data-url="{{ route('admin.banners.uploadImages',['banner_id'=>$banner_id]) }}" >Upload</span>
                                </p>
                                <p></p>
                            </div>
                            <ul id="file-upload-list" class="list-unstyled"  style="display: none"></ul>
                            <br/>
                        </div>
                        @include('snippets.errors_first', ['param' => 'images'])
                    </div>
                 </div>
              </div>
                @endif
        </div>
        </form>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<br>
<div class="row1">
    <div class="col-md-12">
<div class="bgcolor">
    <form name="updateForm" method="POST">
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Title/Sub-Title</th>
                    <th>Image</th>
                    <th>Links</th>
                    <th>Sort Order</th>
                    @if(CustomHelper::checkPermission('banners','delete'))
                    <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <?php
                $noRecordsDisp = '';
                $saveBtnDisp = 'display:none;';
                $isSaveBtn = false;
                if(!empty($bannerImages)){

                    $storage = Storage::disk('public');
                    $path = 'banners/';
                    $thumb_path = 'banners/thumb/';

                    foreach($bannerImages as $img){

                        $imgName = $img->image_name;
                        // && $storage->exists($path.$imgName)
                        if(!empty($imgName) ){

                            $isSaveBtn = true;
                            $rowData = [];
                            $rowData['image'] = $img;
                            $rowData['path'] = $path;
                            $rowData['thumb_path'] = $thumb_path;
                            ?>

                            @include('admin.banners._row', $rowData)
                            <?php
                        }
                    }
                }
                if($isSaveBtn){
                    $noRecordsDisp = 'display:none;';
                    $saveBtnDisp = '';
                }
            ?>
            @if(CustomHelper::checkPermission('banners','edit'))
            <tr class="save_btn_box" style="{{$saveBtnDisp}}">
                <td colspan="5">
                    <input type="submit" name="submit" value="Save" class="saveBtn btn-success btn_admin">
                </td>
            </tr>
            @endif
        </tbody>
    </table>
    <div class="no_records" style="{{$noRecordsDisp}}">
        <p>Currently there is no uploaded Image(s).</p>
    </div>
</form>
</div>
</div>
</div>
@slot('bottomBlock')

<style type="text/css">
    .col-md-6{ padding: 10px; }
    table td,th{border: 1px solid #000;}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.1.0/resumable.min.js"></script>
<script>
    $(document).on("click", ".saveBtn", function(e){
        e.preventDefault();
        updateFormData = $("form[name=updateForm]").serialize();

        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ route($ADMIN_ROUTE_NAME.'.banners.ajax_banner_update',['banner_id'=>$banner_id]) }}",
            type: "POST",
            data: updateFormData,
            dataType:"JSON",
            headers:{'X-CSRF-TOKEN': _token},
            async: false,
            cache: false,
            beforeSend:function(){
            },
            success: function(resp){

                if(resp.success){
                    window.location.reload();
                }else if(resp.err_msg){
                    alert(resp.err_msg);
                }
            }
        });
    });

    $(document).on("click", ".delImg", function(e){
        e.preventDefault();
        var conf = confirm("Are you sure you want to remove this image?");

        if(conf){

            var currSelector = $(this);

            var id = $(this).data("id");

            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($ADMIN_ROUTE_NAME.'.banners.ajax_image_delete') }}",
                type: "POST",
                data: {id:id},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                async: false,
                cache: false,
                beforeSend:function(){
                },
                success: function(resp){

                    if(resp.success){

                        $("#alert_msg").html(resp.msg);
                        //currSelector.parents(".row_"+id).remove();
                        window.location.reload();
                    }
                }
            });
        }
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
            maxFiles:3,
            fileType:['jpg','jpeg','png'],
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
                //$('#video_name').val(response.name);
                //$('.browse_video_section').hide();
                //console.log(response);
                $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html('(completed)');
            });
            resumable.on('complete', function (file, message) { // trigger when file upload complete
                location.reload();
            });
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