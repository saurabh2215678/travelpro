@component('admin.layouts.main')

@slot('title')
Admin - Manage Media - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
@endslot

<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
?>
<div class="top_title_admin">
        <div class="title">
            <h2>Manage Media</h2>
        </div>
        <div class="add_button">
        <div id="upload-container" class="text-center">
                                <button id="browseFile" class="btn_admin">Browse File</button>
                            </div>
        </div>
    </div>

<div class="row">
    <div class="col-md-12">
        <h2>Manage Media

        <div class="container pt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5>Upload File</h5>
                        </div>

                        <div class="card-body">
                            <div id="upload-container" class="text-center">
                                <button id="browseFile" class="btn btn-primary">Browse File</button>
                            </div>
                            <div  style="display: none" class="progress mt-3" style="height: 25px">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%</div>
                            </div>
                        </div>

                        <div class="card-footer p-4" style="display: none">
                            <img id="imgPreview" src="" style="width: 100px; height: auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </h2>

        @include('snippets.errors')
        @include('snippets.flash')

        <?php if(!empty($medias) && ($medias->count() > 0)){ ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <th>Name</th>
                        <th>Image</th>  
                        <th>Mime</th>
                        <th>Size</th>
                        <th>Action</th>
                    </tr>
                        <?php 
                        foreach($medias as $media){
                            $mediaCollection = $media->getFirstMedia('media');
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
                                $mainImg = $mediaCollection->getUrl('thumb');
                            }
                        ?>
                        <tr>
                            <td><?php echo $media->caption; ?></td>
                            <td>
                                <?php if(!empty($mediaCollection->getUrl()) && file_exists($mediaCollection->getPath())){ ?>
                                <div class="imgBox">
                                    <a href="{{$mediaCollection->getUrl()}}" target="_blank">
                                        <img src="{{ $mainImg }}" style="width:100px;" >
                                    </a>
                                </div>
                                <?php } ?>
                            </td>
                            <td>{{ $mediaCollection->mime_type }}</td>
                            <td>{{ $mediaCollection->human_readable_size }}</td>
                            <td>                                
                                <a href="javascript:void(0)" class="sbmtDelForm"  id="{{$media->id}}"><i class="fas fa-trash-alt"></i></i></a>
                                
                                <form style="display: inline-block;" method="POST" action="{{ route($ADMIN_ROUTE_NAME.'.media.delete', $media->id) }}" accept-charset="UTF-8" role="form" onsubmit="return confirm('Do you really want to remove this Media?');" id="delete-form-{{$media->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <input type="hidden" name="banner_id" value="<?php echo $media->id; ?>">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            {{ $medias->appends(request()->query())->links('vendor.pagination.default') }}
        <?php } else{ ?>
            <div class="alert alert-warning">No Media found.</div>
        <?php } ?>
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