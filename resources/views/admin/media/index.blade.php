@component('admin.layouts.main')

@slot('title')
Admin - Manage Media Gallery - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<style>
html, body, .mainwrapper { height: 100%;     }
.mainwrapper {overflow: hidden;}
.container  { height: 100%;}
.add_media { height: 100%;/*padding: 10px 25px;*/ margin: 10px 15px; background: #fff;}
.full_width_center { /*height: calc(100% - 110px);*/ }
.btn_lg {font-weight: 500; font-size: 14px; background-color: #952a25; border-radius: 5rem; padding: 8px 30px; color: #fff !important; transition: all ease 0.5s; text-transform: uppercase; border: solid 2px #952a25;}
.btn_lg:hover {opacity: 0.6;}
.upload_container {text-align: center; }
.upload_container .file_limit { font-size: 14px; display: block;margin-top: 10px; }
.admin_title { font-size: 20px; font-weight: 500;padding: 5px 0 10px;}
#upload_files {/*height: 50%;*/position: relative;}
.upload_container {display: flex;flex-direction: column; align-items: center; justify-content: center; height: 100%;}
.media_library_images .images_list { list-style: none;display: flex; flex-wrap: wrap; height: calc(100% - 74px); overflow:auto;  margin: -5px;  padding: 0;  }
.filter_images {    padding: 20px 0; }
.images_block { border: solid 1px #ddd; }
.images_list li { width: 16.66666666666667%; padding:8px; }
.filter_images .col-sm-3 { float: none; padding: 0; }
.media_library_wrap { display:flex ;height: calc(100vh - 22rem); min-height: 100%;}
.media_library_images {width:75%; padding-right:25px; }
.about_media {width:25%; background:#f1f1f1; padding: 15px; overflow: auto;}
.title_sm {font-size: 16px; font-weight: 500; margin-bottom:10px; }
.media_view { margin-bottom:5px; }
.delete_btn { color:#952a25; text-decoration:underline; cursor: pointer;}
.media_edit {border-top: 1px solid #ddd;  padding: 10px 0; margin: 25px 0 0 0;}
.btn_copy {cursor: pointer;     margin: 5px 0;}
.images_list{cursor: pointer;}
.images_list li.active_media .images_block {     border-color: #c58e4e;
box-shadow: 0px 1px 5px 1px #c58e4e;}
.images_block{ position: relative; }
.images_block .active_mark {height: 20px; width: 20px; background-color: #c58e4e; position: absolute; text-align: center; color: #fff; display:none; }
.images_list li.active_media .images_block .active_mark {display:block; }
.media_footer {padding: 15px 0;
    border-top: 1px solid #ddd;
    text-align: right;
    margin-top: 0px;}
    .btn {
        background-color: #00b2a7;
        color: #fff;
        padding: 8px 18px;
        font-size: 14px;
        border-radius: 0;
    }
    .btn:hover {color:#fff;}
    .fancybox-slide--iframe .fancybox-content { height:100% !important;  }
    .tab-content>.active {height:100%; }
    #upload_files .progress {
        position: absolute;
        bottom: 10em;
        left: 3rem;
        right: 3rem;
        width: 50rem;
        margin: 0 auto;
    }
    div#upload-container {
        height: calc(100vh - 100rem);
        min-height: 100%;
    }
    #upload_files.active #upload-container {
    height: calc(100vh - 22rem);
}
    .media_title {
        position: relative;
    }
    .media_title input.form-control {
        padding: 6px 55px 6px 12px;
    }
    .media_title input[type="button"] {
        position: absolute;
        top: 2.5rem;
        right: 0;
        background: #00b2a7;
        color: #fff;
        transition: all ease 0.5s;
        padding: 8px 12px;
        font-size: 12px;
        display: inline-block;
        border: none;
    }
    .add_media ul.nav.nav-tabs input[type="text"]{
        padding: 5px 8px;
    }
    .add_media ul.nav.nav-tabs input[type="submit"]{
        background: #00b2a7;
        color: #fff;
        transition: all ease 0.5s;
        padding: 8px 12px;
        font-size: 12px;
        display: inline-block;
        border: none;
    }
    .media_title input[type="button"]:hover, .add_media ul.nav.nav-tabs input[type="submit"]:hover {background:#162e44;}
    .media_edit .alert.alert-success {
        padding-top: 8px;
        padding-bottom: 5px;
    }
    .images_list li .images_block img {
        width: 100%;
        height: 12rem;
        object-fit: cover;
    }
    li.media_item .images_block {
        background: #f0f0f1;
        padding: 8px;
        color: #3c434a;
        cursor: pointer;
        border: 1px solid #b9b9b9;
    }
</style>
@endslot

<?php
// $BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
?>
    <div class="top_title_admin">
        <div class="title">
            <h2>Manage Media Gallery</h2>
        </div>
        <!-- <div class="add_button">
           <div id="upload-container" class="text-center">
            <button id="browseFile" class="btn_admin">Browse File</button>
            </div>
        </div> -->
</div>

    <div class="add_media">

        <!-- <div class="admin_title">Manage Media Gallery</div> -->
        <ul class="nav nav-tabs">
            @if(CustomHelper::checkPermission('media','add'))
            <li><a data-toggle="tab" href="#upload_files">Upload files</a></li>
            @endif
            <li class="active"><a data-toggle="tab" href="#media_library">Media Library</a></li>
            <li style="float: right;">
                <form method="GET" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                    <input type="text" name="keyword" value="{{$keyword}}" placeholder="Search">
                    <input type="submit" name="submit" value="Search">
                    <a href="{{route($ADMIN_ROUTE_NAME.'.media.index')}}" class="btn_admin2">Reset</a>
                </form>
            </li>
        </ul>

        <div class="centersec">

            @include('snippets.errors')
            @include('snippets.flash')

            <div id="upload_files" class="tab-pane fade">
                <div id="upload-container" class="upload_container" >
                    <button id="browseFile" class="btn_lg">Browse File</button>
                    <span class="file_limit">Maximum upload file size: 100 MB.</span>
                </div>
                <div  style="display: none" class="progress mt-3" style="height: 25px">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%</div>
                </div>
                <div class="card-footer p-4" style="display: none">
                    <img id="imgPreview" src="" style="width: 100px; height: auto">
                </div>
            </div>

            <div id="media_library" class="table-responsive tab-pane fade in active">
                <div class="validation_msg"></div>
                <?php if(!empty($medias) && ($medias->count() > 0)){ ?>

                    <table class="table table-bordered">
                        <tr>
                            <th>Image</th>
                            <th>Media Caption</th>
                            <th>Media Alt Text</th>
                            <th>Mime</th>
                            <th>Size</th>
                            @if(CustomHelper::checkPermission('media','delete'))
                            <th>Action</th>
                            @endif
                        </tr>
                        <?php 
                        foreach($medias as $media){
                            $mediaCollection = $media->getFirstMedia('media');
                            $mainImg = '';
                            if($mediaCollection){
                                if(!empty($mediaCollection) && $mediaCollection->mime_type == "video/mp4"){
                                    $mainImg = asset('assets/img/mp4.png');
                                }else if(!empty($mediaCollection) && $mediaCollection->mime_type == "application/msword"){
                                    $mainImg = asset('assets/img/doc.png');
                                }else if(!empty($mediaCollection) && $mediaCollection->mime_type == "audio/mpeg"){
                                    $mainImg = asset('assets/img/mp3.png');
                                }else if(!empty($mediaCollection) && $mediaCollection->mime_type == "application/pdf"){
                                    $mainImg = asset('assets/img/pdf.png');
                                }else if(!empty($mediaCollection) && $mediaCollection->mime_type == "application/vnd.ms-excel"){
                                    $mainImg = asset('assets/img/xlsx.png');
                                }else{
                                    $mainImg = $mediaCollection->getUrl('medium');
    // $mainImg = $mediaCollection->getUrl();
                                    if(!file_exists($mediaCollection->getPath('medium'))) {
                                        $mainImg = $mediaCollection->getUrl();
                                    }
                                }
                                ?>
                                <tr>
                                    <td>
                                        <?php if(!empty($mediaCollection->getUrl()) && file_exists($mediaCollection->getPath())){ ?>
                                            <div class="imgBox">
                                                <a href="{{$mediaCollection->getUrl()}}" target="_blank">
                                                    <img src="{{ $mainImg }}" style="width:100px;" >
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td style="word-break: break-all;">
                                        <div class="caption_label data{{$media->id}}" data-id="{{$media->id}}">
                                            {{$media->caption??''}}
                                            @if(CustomHelper::checkPermission('media','edit'))
                                            <a href="javascript:void(0);" title="Edit"><i class="fas fa-edit"></i></a>
                                            @endif
                                        </div>
                                        <div class="caption_edit data{{$media->id}}" data-id="{{$media->id}}" style="display:none;">
                                            <input type="text" class="form-control" value="{{$media->caption}}" />
                                            <a href="javascript:void(0);" title="Save"><i class="fas fa-check"></i></a>
                                            <a href="javascript:void(0);" title="Cancel"><i class="fas fa-times"></i></a>
                                        </div>
                                    </td>
                                    <td style="word-break: break-all;">
                                        <div class="alt_text_label data{{$media->id}}" data-id="{{$media->id}}">
                                            {{$media->alt_text??''}}
                                            @if(CustomHelper::checkPermission('media','edit'))
                                            <a href="javascript:void(0);" title="Edit"><i class="fas fa-edit"></i></a>
                                            @endif
                                        </div>
                                        <div class="alt_text_edit data{{$media->id}}" data-id="{{$media->id}}"  style="display:none;">
                                            <input type="text" class="form-control" value="{{$media->alt_text}}" />
                                            <a href="javascript:void(0);" title="Save"><i class="fas fa-check"></i></a>
                                            <a href="javascript:void(0);" title="Cancel"><i class="fas fa-times"></i></a>
                                        </div>
                                    </td>
                                    <td>{{ $mediaCollection->mime_type??'' }}</td>
                                    <td>{{ $mediaCollection->human_readable_size??'' }}</td>
                                    @if(CustomHelper::checkPermission('media','delete'))
                                    <td>                                
                                        <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$media->id}}"><i class="fas fa-trash-alt"></i></a>

                                        <form style="display: inline-block;" method="POST" action="{{ route($ADMIN_ROUTE_NAME.'.media.delete', $media->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Media?');" id="delete-form-{{$media->id}}">
                                            {{ csrf_field() }}
                                            {{ method_field('POST') }}
                                            <input type="hidden" name="banner_id" value="<?php echo $media->id; ?>">
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                            <?php } } ?>
                        </table>
                        {{ $medias->appends(request()->query())->links('vendor.pagination.default') }}
                    <?php } else{ ?>
                        <div class="alert alert-warning">No Media found.</div>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
</div>

@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script> -->
<script type="text/javaScript">
    $('.sbmtDelForm').click(function(){
        var id = $(this).attr('id');
        $("#delete-form-"+id).submit();
    });
    $(document).ready(function(){
        $(document).on('click','.caption_label .fa-edit',function(){
            var _this = $(this);
            var data_id = _this.parent().parent().attr('data-id');
            $('.caption_label.data'+data_id).hide();
            $('.caption_edit.data'+data_id).show();
        });
        $(document).on('click','.caption_edit .fa-times',function(){
            var _this = $(this);
            var data_id = _this.parent().parent().attr('data-id');
            $('.caption_edit.data'+data_id).hide();
            $('.caption_label.data'+data_id).show();
        });
        $(document).on('click','.caption_edit .fa-check',function(){
            var _this = $(this);
            var data_id = _this.parent().parent().attr('data-id');

            var field = 'caption';
            var value = _this.parent().parent().find('.form-control').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: "{{ route($ADMIN_ROUTE_NAME.'.media.ajaxUpdate') }}",
                method: 'post',
                data: { media_id:data_id, field:field, value:value},
                dataType: 'json',
                success: function(result){
                    if(result.success){
                        $('#media_library').find('.validation_msg').html(result.message);
                        $('.caption_edit.data'+data_id).hide();
                        $('.caption_label.data'+data_id).show();
                        $('.caption_label.data'+data_id).html(value+'<a href="javascript:void(0);" title="Edit"><i class="fas fa-edit"></i></a>');
                    } else if(result.message) {
                        $('#media_library').find('.validation_msg').html(result.message);
                    }
                }
            });
        });


        $(document).on('click','.alt_text_label .fa-edit',function(){
            var _this = $(this);
            var data_id = _this.parent().parent().attr('data-id');
            $('.alt_text_label.data'+data_id).hide();
            $('.alt_text_edit.data'+data_id).show();
        });
        $(document).on('click','.alt_text_edit .fa-times',function(){
            var _this = $(this);
            var data_id = _this.parent().parent().attr('data-id');
            $('.alt_text_edit.data'+data_id).hide();
            $('.alt_text_label.data'+data_id).show();
        });
        $(document).on('click','.alt_text_edit .fa-check',function(){
            var _this = $(this);
            var data_id = _this.parent().parent().attr('data-id');

            var field = 'alt_text';
            var value = _this.parent().parent().find('.form-control').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: "{{ route($ADMIN_ROUTE_NAME.'.media.ajaxUpdate') }}",
                method: 'post',
                data: { media_id:data_id, field:field, value:value},
                dataType: 'json',
                success: function(result){
                    if(result.success){
                        $('#media_library').find('.validation_msg').html(result.message);
                        $('.alt_text_edit.data'+data_id).hide();
                        $('.alt_text_label.data'+data_id).show();
                        $('.alt_text_label.data'+data_id).html(value+'<a href="javascript:void(0);" title="Edit"><i class="fas fa-edit"></i></a>');
                    } else if(result.message) {
                        $('#media_library').find('.validation_msg').html(result.message);
                    }
                }
            });

        });

    });
</script>

    <!-- <script>
    var uploadedDocumentMap = {}
    Dropzone.options.documentDropzone = {
    url: '{{ route($ADMIN_ROUTE_NAME.".media.store") }}',
    maxFilesize: 100, // MB
    addRemoveLinks: true,
    headers: {
    'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    success: function (file, response) {
    $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
    uploadedDocumentMap[file.name] = response.name
    },
    removedfile: function (file) {
    file.previewElement.remove()
    var name = ''
    if (typeof file.file_name !== 'undefined') {
    name = file.file_name
    } else {
    name = uploadedDocumentMap[file.name]
    }
    $('form').find('input[name="document[]"][value="' + name + '"]').remove()
    },
    init: function () {
    @if(isset($project) && $project->document)
    var files =
    {!! json_encode($project->document) !!}
    for (var i in files) {
    var file = files[i]
    this.options.addedfile.call(this, file)
    file.previewElement.classList.add('dz-complete')
    $('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
    }
    @endif
    }
    }
</script> -->

<script type="text/javascript">
    let browseFile = $('#browseFile');
    let resumable = new Resumable({
        target: '{{ route($ADMIN_ROUTE_NAME.".media.store") }}',
    query:{_token:'{{ csrf_token() }}'} ,// CSRF token
    fileType: ['mp3','mp4','jpeg','jpg','png','pdf','doc','docx','gif','xls','xlsx'],
    chunkSize: 1*1024*1024, // default is 1*1024*1024, this should be less than your maximum limit in php.ini
    headers: {
        'Accept' : 'application/json'
    },
    testChunks: false,
    throttleProgressCallbacks: 1,
});

    resumable.assignBrowse(browseFile[0]);

    resumable.on('fileAdded', function (file) { // trigger when file picked
        showProgress();
    resumable.upload() // to actually start uploading.
});

    resumable.on('fileProgress', function (file) { // trigger when file progress update
        updateProgress(Math.floor(file.progress() * 100));
    });

    resumable.on('fileSuccess', function (file, response) { // trigger when file upload complete
        response = JSON.parse(response);
        console.log(response);
    //$('#imgPreview').attr('src', response.path);
    //$('.card-footer').show();
});

    resumable.on('complete', function (file, response) { // trigger when file upload complete
        location.reload();
    });

    resumable.on('fileError', function (file, response) { // trigger when there is any error
        alert('file uploading error.')
    });

    let progress = $('.progress');
    function showProgress() {
        progress.find('.progress-bar').css('width', '0%');
        progress.find('.progress-bar').html('0%');
        progress.find('.progress-bar').removeClass('bg-success');
        progress.show();
    }

    function updateProgress(value) {
        progress.find('.progress-bar').css('width', `${value}%`)
        progress.find('.progress-bar').html(`${value}%`)
    }

    function hideProgress() {
        progress.hide();
    }
</script>

@endslot

@endcomponent