@component('admin.layouts.media_main')
@slot('headerBlock')
<style>
   html, body, .mainwrapper { height: 100%;     }
   .mainwrapper {overflow: hidden;}
   .container  { height: 100%;}
   .add_media { height: 100%;/*padding: 10px 25px;*/ margin: 10px 15px; background: #fff;}
   .full_width_center { /*height: calc(100% - 110px);*/ }
   .btn_lg { font-weight: 500; font-size: 16px; background-color: #952a25; border-radius: 5rem; padding: 12px 55px; color: #fff !important; transition: all ease 0.5s; text-transform: uppercase; border: solid 2px #952a25;}
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
    height: calc(100vh - 22rem);
    min-height: 100%;
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
            <h2>Manage Media</h2>
          </div>
          <!-- <div class="add_button">
            <div id="upload-container" class="text-center">
              <button id="browseFile" class="btn_admin">Browse File</button>
              </div>
          </div> -->
  </div>
  <div class="add_media">

   @include('snippets.errors')
   @include('snippets.flash')

   <!-- <div class="admin_title">Manage Media</div> -->
   <ul class="nav nav-tabs">
      <li><a data-toggle="tab" href="#upload_files">Upload files</a></li>
      <li class="active"><a data-toggle="tab" href="#media_library">Media Library</a></li>
      <li style="float: right;">
        <form method="GET" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
          {{ csrf_field() }}
          <input type="hidden" name="type" value="{{$type}}">
          <input type="hidden" name="action" value="{{$action}}">
          <input type="text" name="keyword" value="{{$keyword}}" placeholder="Search">
          <input type="submit" name="submit" value="Search">
        </form>
      </li>
   </ul>
   <div class="tab-content full_width_center">
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
      <div id="media_library" class="tab-pane fade in active">
         <div class="media_library_wrap">
            <div class="media_library_images">
               <div class="filter_images">
                  <!-- <div class="col-sm-3">
                     <select class="form-control">
                        <option>Images Filter</option>
                        <option>JPEG</option>
                        <option>PDF</option>
                     </select>
                  </div> -->
               </div>
               <div class="clearfix"></div>
               <?php if(!empty($medias) && ($medias->count() > 0)){ ?>
               <ul class="images_list">
                  <?php 
                  foreach($medias as $media){
                     $mediaCollection = $media->getFirstMedia('media');
                     if($mediaCollection) {
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

                  if(!empty($mediaCollection->getUrl()) && file_exists($mediaCollection->getPath())){ ?>
                  <li class="media_item" media-id="{{$media->id}}">
                     <div class="images_block">
                        <span class="active_mark"><i class="fa fa-check" aria-hidden="true"></i></span>
                        <img src="{{ $mainImg }}" data-id="{{$media->id}}" class="img-responsive" /> 
                     </div>
                     <span class="caption">{{isset($media->caption) ? substr($media->caption, 0, 16).'...' : ''}}</span>
                  </li>
                  <?php }else if(!empty($mediaCollection->getUrl()) && !file_exists($mediaCollection->getPath())){ ?>
                  <li class="media_item" media-id="{{$media->id}}">
                     <div class="images_block">
                        <span class="active_mark"><i class="fa fa-check" aria-hidden="true"></i></span>
                        Image path not exist
                     </div>
                  </li>
                  <?php } } } ?>
               </ul>
               {{ $medias->appends(request()->query())->links('vendor.pagination.default') }}
               <?php }else{ ?>
                  No Media Found.
               <?php } ?>
            </div>
            <div class="about_media">
            </div>
         </div>
         <?php if($action == 'dashboard'){ }else{ ?>
         <div class="media_footer">
            <input type="hidden" name="process_upload" id="process_upload" value="0">
            <input type="hidden" name="selected_media_id" id="selected_media_id">
            <input type="hidden" name="selected_media_path" id="selected_media_path">
            <button type="button" disabled class="btn frame_close_processing" style="display: none;">Processing...</button>
            <button type="button" disabled class="btn frame_close">Set Media</button>
         </div>
        <?php } ?>
      </div>
   </div>
</div>
@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script> -->
<script type="text/javaScript">
$(document).ready(function(){
  $(".images_list li").click(function(){
    if($(this).hasClass('active_media')){
      $(this).removeClass('active_media');
    } else {
      $(this).addClass('active_media');
    }
    // $(this).siblings().removeClass('active_media');
  });

  $('.media_item').click(function(){
    // Make About Media Blank 
    $('.about_media').html("");

    if($(this).closest('.images_list').find('.active_media').length > 0){
      $(".frame_close").prop('disabled',false);
    }else{
      $(".frame_close").prop('disabled',true);
    }
 
    if($(this).hasClass('active_media')) {
      var id = $(this).attr('media-id');

      //Set Values in hidden fields
      $("#selected_media_id").val(id);
      // $(".frame_close").prop('disabled',false);

      var imgPath = $(this).find('img').attr('src');
      $('#selected_media_path').val(imgPath);

      showAboutMedia(id);
    } else {
      showAboutMedia();
    }
  });
});

function showAboutMedia(id) {
  if($(".images_list li.active_media").length > 1) {
    $('.about_media').html($(".images_list li.active_media").length+' files selected');
    setTimeout(function(){
      var id = '';
      var media_ids = [];
      $.each($(".images_list li.active_media"),function(){
        id = $(this).attr('media-id');
        media_ids.push(id);
      });
      var media_ids_str = media_ids.join(',');
      $("#selected_media_id").val(media_ids_str);
      $('#selected_media_path').val('');
    },300);
  } else {
    if(!id) {
      id = $(".images_list li.active_media").attr('media-id');
    }
    $("#selected_media_id").val(id);
    var imgPath = $(".images_list li.active_media").find('img').attr('src');
    $('#selected_media_path').val(imgPath);

    if(id) {
      //get attachment media view
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
      });
      jQuery.ajax({
        url: "{{ route($ADMIN_ROUTE_NAME.'.media.mediadetailsView') }}",
        method: 'post',
        data: { media_id:id },
        dataType: 'json',
        success: function(result){
          if(result.success){
            $('.about_media').html(result.pageView);
          }
        }
      });
    }
  }
}

function ajaxUpdate(id,field) {
  if(id) {
    $('#media_library').find('.validation_msg').html('');
    var value = '';
    if(field == 'alt_text') {
      value = $('#media_library').find('input[name=media_alt_text]').val();
    } else if(field == 'caption') {
      value = $('#media_library').find('input[name=media_caption]').val();
    }
      //get attachment media view
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
      });
      jQuery.ajax({
        url: "{{ route($ADMIN_ROUTE_NAME.'.media.ajaxUpdate') }}",
        method: 'post',
        data: { media_id:id, field:field, value:value},
        dataType: 'json',
        success: function(result){
          if(result.success){
            $('#media_library').find('.validation_msg').html(result.message);
            if(field == 'caption') {
              if($(".images_list li.active_media").length == 1) {
                $(".images_list li.active_media").find('.caption').html(value);
              }
            }
          } else if(result.message) {
            $('#media_library').find('.validation_msg').html(result.message);
          }
        }
      });
    }  
}

function copyToClipboard() {
   /* Get the text field */
   var copyText = document.getElementById("mainMediaUrl");

   /* Select the text field */
   copyText.select();
   copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
   navigator.clipboard.writeText(copyText.value);

}

$('body').on('click','.delete_media',function(){
   var mediaid = $(this).attr('data-media');

   var confirmation = confirm("Are you sure you want to remove the media?");
   if(confirmation){
      //delete selected media
      $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
         }
      });
      jQuery.ajax({
         url: "{{ route($ADMIN_ROUTE_NAME.'.media.ajaxMediaDelete') }}",
         method: 'post',
         data: { media_id:mediaid },
         dataType: 'json',
         success: function(result){
            if(result.success){
               location.reload();
            }
         }
      });
   }
});

$('.frame_close').on('click', function(){
  $('.frame_close_processing').show();
  $('.frame_close').hide();
  $('#process_upload').val(1);
  setTimeout(function(){
    parent.jQuery.fancybox.close();
  },300);
});
</script>
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