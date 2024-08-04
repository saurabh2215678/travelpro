<?php if(!empty($mediaDetails)){
   $media_id = $media->id;
   $media_caption = $media->caption;
   $media_alt_text = $media->alt_text;
   $media_date = CustomHelper::DateFormat($media->created_at,'d/m/Y');
   $media_path = $mediaDetails->getPath();
   $media_name = $mediaDetails->file_name;
   $media_mime_type = $mediaDetails->mime_type;
   $media_size = $mediaDetails->human_readable_size;
   $media_main_url = $mediaDetails->getUrl();
   $media_medium_url = "";
   $media_thumb_url = "";

   if(!empty($media_main_url) && file_exists($media_path) && $media_mime_type == "video/mp4"){
     $media_medium_url = asset('assets/img/mp4.png');
   }else if(!empty($media_main_url) && file_exists($media_path) && $media_mime_type == "application/msword"){
     $media_medium_url = asset('assets/img/doc.png');
   }else if(!empty($media_main_url) && file_exists($media_path) && $media_mime_type == "audio/mpeg"){
     $media_medium_url = asset('assets/img/mp3.png');
   }else if(!empty($media_main_url) && file_exists($media_path) && $media_mime_type == "application/pdf"){
     $media_medium_url = asset('assets/img/pdf.png');
   }else if(!empty($media_main_url) && file_exists($media_path) && $media_mime_type == "application/vnd.ms-excel"){
     $media_medium_url = asset('assets/img/xlsx.png');
   }else{
      // $media_medium_url = $mediaDetails->getUrl();
      $media_medium_url = $mediaDetails->getUrl('medium');
      // $media_thumb_url = $mediaDetails->getUrl('thumb');
      if(!file_exists($mediaDetails->getPath('medium'))) {
       $media_medium_url = $mediaDetails->getUrl();
    }
   }
?>
   <div class="title_sm">ATTACHMENT DETAILS</div>
   <div class="media_info">
      <div class="media_view">
         <img src="{{$media_medium_url}}" class="img-responsive" /> 
      </div>
      <div class="media_name">{{$media_name}}</div>
      <div class="media_uploadDate">{{$media_date}}</div>
      <div class="media_size">{{$media_size}}</div>
   </div>
   <div class="media_action delete_media" data-media="{{$media_id}}">
      <span class="delete_btn">Delete media</span>
   </div>
   <div class="media_edit">
      <span class="validation_msg"></span>
      <div class="media_title">
         <label>Alt Text</label>
         <div class="form-group">
            <input type="text" name="media_alt_text" class="form-control" value="{{$media_alt_text}}" />
         </div>

         <input type="button" onClick="return ajaxUpdate({{$media_id}},'alt_text')" value="Save">
      </div>
      <div class="media_title">
         <label>Caption</label>
         <div class="form-group">
            <input type="text" name="media_caption" class="form-control" value="{{$media_caption}}" />
         </div>
         
         <input type="button" onClick="return ajaxUpdate({{$media_id}},'caption')" value="Save">
      </div>
      <div class="media_url">
         <label>Media URL</label>
         <input type="text" id="mainMediaUrl" value="{{$media_main_url}}" class="form-control" readonly />
         <div class="btn_copy" onclick="copyToClipboard()">Copy To Clipboard</div>
      </div>
   </div>
<?php } ?>