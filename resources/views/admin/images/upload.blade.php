@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<style>
    .fancybox-slide--iframe .fancybox-content { height:100% !important;  }
    
</style>
@endslot
<?php
$routeName = CustomHelper::getAdminRouteName();
$back_url = (request()->has('back_url'))?request('back_url'):'';
$category = app('request')->input('category') ?? '';
?>
@if(!empty($tbl_name) && $tbl_name=='packages')
<?php

$category = app('request')->input('category');
$package_id = $tbl_id;
$active_menu = "packages".$package_id.'/'.$category;
?>
@include('admin.includes.packageoptionmenu')
@endif

@if(!empty($tbl_name) && ($tbl_name=='accommodations' || $tbl_name=='accommodation_rooms'))
<?php
$accommodation_id = $tbl_id;
$active_menu = "accommodations".$accommodation_id.'/'.$category;
?>
@include('admin.includes.accommodationoptionmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='destinations')
<?php
$destination_id = $tbl_id;
$active_menu = "destinations".$destination_id.'/'.$category;
?>
@include('admin.includes.destinationoptionmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='testimonials')
<?php
$id = $tbl_id;
$active_menu = "testimonials/".$id.'/'.$category.'gallery';
?>
@include('admin.includes.testimonialmenu')
@endif


@if(!empty($tbl_name) && $tbl_name=='cms_pages')
<?php
$id = $tbl_id;
$active_menu = "cms".$id.'/'.$category;
?>
@include('admin.includes.cmsmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='cab_route')
<?php
$id = $tbl_id;
$active_menu = "cab_route".$id.'/gallery';
?>
<div class="page_btns">
    <a  href="{{route($routeName.'.cab_route.edit', ['id'=>$id])}}" title="Edit Cab Route"><i class="fas fa-edit"></i>Cab Route</a>
    <a <?php if($active_menu=="cab_route".$id.'/gallery'){echo 'class="active"' ;}?> href="{{route($routeName.'.images.upload_view',['tid'=>$id,'module'=>'cab_route','category'=>'gallery']) }}" title="Images"><i class="fas fa-image"></i>Gallery</a>
    <a  href="{{route($routeName.'.cab_route.agent_price', ['id'=>$id])}}" title="Edit Agent price"><i class="fas fa-edit"></i>Agent price</a>
</div>
@endif

@if(!empty($tbl_name) && $tbl_name=='cabs_sightseeing')
<?php
$id = $tbl_id;
$active_menu = "cabs_sightseeing".$id.'/gallery';
?>
<div class="page_btns">
    <a  href="{{route($routeName.'.cabs.sightseeing_edit', ['id'=>$id])}}" title="Edit Cab Sightseeing"><i class="fas fa-edit"></i>Cab Sightseeing</a>
    <a <?php if($active_menu=="cabs_sightseeing".$id.'/gallery'){echo 'class="active"' ;}?> href="{{route($routeName.'.images.upload_view',['tid'=>$id,'module'=>'cabs_sightseeing','category'=>'gallery']) }}" title="Images"><i class="fas fa-image"></i>Gallery</a>
</div>
@endif

<!-- add for removing error not define $categories -->
<?php
$categories = (isset($categories->categories)) ? $categories->categories : '';
?>

<!-- add for removing error not define $categories -->

<link rel="stylesheet" href="<?php echo url('/uploadifive'); ?>/uploadifive.css">
<div class="data-space">
    <div class="row">
        <div class="col-md-12">
            <h2>{{ $page_heading }} ({{$images->count()}}) <?php if (request()->has('back_url')) {$back_url = request('back_url');?>
            <a href="{{ url($back_url)}}" class="btn btn-sm btn-success btn_admin2" style='float: right;'>Back</a><?php }?></h2>

            <?php
            $old_category_id = app('request')->input('category_id');
            $category = app('request')->input('category') ?? '';
            $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
/*
<p class="alert alert-warning">
<strong><span style="color:#FF0000;">*</span> Mandatory fields</strong>
</p>
 */
$id = (request('id')) ? request('id') : 0;
// $tbl = (request('tbl')) ? request('tbl') : '';
//prd($tbl);
?>

<div class="bgcolor">

 @include('snippets.errors')
 @include('snippets.flash')


 <?php

 if (session()->has('scc_msg')) {
    echo session('scc_msg');
}
?>

<?php
if (session()->has('err_msg')) {
    echo session('err_msg');
}
?>


<form method="POST" action="" enctype="multipart/form-data" accept-charset="UTF-8" role="form">
    {{ csrf_field() }}
    <div class="row1">
        <div class="col-md-12">
            <p>
                <span style="font-family: arial, sans-serif;"><i><strong>Instructions on use:</strong></i> <br>
                    Please make sure you are trying to upload Image(s) file only with the extension <strong>.jpg</strong>, <strong>.jpeg</strong>, or <strong>.png</strong> at the end of the file name.<br><br></span>
                </p>
            </div>

            @if(CustomHelper::checkPermission('images','add'))
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="images">Image <span class="required">*</span></label>
                    <div class="row">
                        <div class="col-lg-2">
                            <input type="file" name="images" id="images" multiple>
                        </div>
                        <div class="col-lg-1">
                            OR
                        </div>
                        <div class="col-lg-2">
                            <span class="btn btn-success media_box" data-fancybox data-type="iframe" data-fancybox-type="iframe" href="javascript:;" data-src="{{ route($ADMIN_ROUTE_NAME.'.media.pop',['type'=>'image']) }}" data-preload="false" role="dialog"> Select From Gallery </span>
                            <?php /*<input type="hidden" name="image" id="image" value="">*/ ?>
                        </div>
                    </div>
                </div>
                <div id="queue"></div>
                <?php /*<div class="mediaBox"></div>*/ ?>
            </div>                
            @endif

            <?php
/*
<div class="col-md-12">

<div class="form-group">
<input type="submit" id="submit" class="btn-success btn" name="submit" value="Upload" />
</div>
</div>
 */
?>
</div>
</form>
</div>
</div>
<div class="clearfix"></div>
</div>
</div>
<br>
<div class="data-space">
    <div class="bgcolor">
        <form name="updateForm" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="category" value="{{$category}}" class="form-control">
            <table class="table table-bordered" id="gallery_table">
                <thead>
                  <tr>
                    <th>Default</th>
                    <th>Title</th>
                    <th>Image</th>
                    <!-- <th>Category</th> -->
                    <th>Link / Sort Order</th>
                    @if(CustomHelper::checkPermission('images','delete'))
                    <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <?php
                $noRecordsDisp = '';
                $saveBtnDisp = 'display:none;';

                $isSaveBtn = false;
                if (!empty($images) && count($images) > 0) {

                    $storage = Storage::disk('public');

                /*$path = 'common/';
                $thumb_path = 'common/thumb/';*/
                foreach ($images as $img) {
                    $imgName = $img->name;
                    if (!empty($imgName)) { //$storage->exists($path . $imgName)

                        $isSaveBtn = true;
                        $rowData = [];
                        $rowData['image'] = $img;
                        $rowData['path'] = $path;
                        $rowData['thumb_path'] = $thumb_path;
                        $rowData['categories'] = $categories;
                        $rowData['module'] = $tbl_name;
                        ?>
                        @include('admin.images._row', $rowData)
                    <?php }
                }
            }
            if ($isSaveBtn) {
                $noRecordsDisp = 'display:none;';
                $saveBtnDisp = '';
            }
            ?>

            @if(CustomHelper::checkPermission('images','edit'))
            <tr class="save_btn_box" style="{{$saveBtnDisp}}">
                <td colspan="6">
                    <input type="button" name="save" value="Save" class="btn saveBtn btn-success btn_admin">
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
@slot('bottomBlock')
<style type="text/css">
    .col-md-6{ padding: 10px; }
    table td,th{border: 1px solid #000;}
</style>

<script src="<?php echo url('/uploadifive'); ?>/jquery.uploadifive.min.js"></script>
<script>
    <?php $timestamp = time();?>
    $('#images').uploadifive({

        'auto'             : true,
        'removeCompleted'  : true,
        'buttonText'  : 'Upload Images',
        'width'        : 110,
        'formData'         : {
            'cat' : "{{$category}}",
            'tid' : "{{$tbl_id}}",
            'tbl' : "{{$tbl_name}}",
            'table_id' : "{{$table_id}}",
            '_token' : '<?php echo csrf_token(); ?>',

            'timestamp' : '<?php echo $timestamp; ?>',
            'token'     : '<?php echo md5('unique_salt' . $timestamp); ?>',
        },
        'itemTemplate' : '<div class="uploadifive-queue-item"><span class="filename"></span> | <span class="fileinfo"></span><div class="close"></div></div>',
        'queueID'          : 'queue',
                //'checkScript'  : '<?php //echo url($ADMIN_ROUTE_NAME."/images/"); ?>/ajax_check_exist',
        'uploadScript'     : '<?php echo url($ADMIN_ROUTE_NAME . "/images/"); ?>/ajax_upload',
                //'uploadLimit'  : 4,
                //'fileSizeLimit' : <?php //echo ALBUM_MAX_UPLOAD_SIZE; ?>,

        'onAddQueueItem' : function(file) {
                    //console.log(file.name);
                    //console.log(file.queueItem[0]);
                    //checkExistFile(file);
                    //$(file.queueItem[0]).find(".fileinfo").text("Already exist");
        },

        'onCheck'      : function(file, exists) {
            if (exists) {
                alert('The file ' + file.name + ' exists on the server.');
            }
        },

        'onUploadComplete' : function(file, data,response) {

            console.log(JSON.parse(data));

            var resp = JSON.parse(data);
           
            if(resp.success){
                if(resp.imgRow){
                            //$("form[name=updateForm]").find("table tbody").append(resp.imgRow);

                            // $(resp.imgRow).insertBefore($("form[name=updateForm]").find("table tbody .save_btn_box"));
                    $('#gallery_table tbody').prepend(resp.imgRow);

                    $(".no_records").hide();
                    $(".save_btn_box").show();
                }
            }

                    /*console.log(JSON.parse(data));

                    if(data.error && data.error != ""){
                        $("#queue .uploadifive-queue-item error div").html("");
                    }*/

                    /*var resp = JSON.parse(data);

                    if(resp.success){
                        if(resp.photoRow){
                            $(".photoBox").append(resp.photoRow);
                            $(".sbmtFormBtnTag").show();
                        }
                    }*/

        },
        'onError'      : function(errorType) {
                    //console.log('The error was: ' + errorType);
        }
    });


            /*function checkExistFile(file){
                if(file && file.name){
                    var filename = file.name;
                    var _token = '{{ csrf_token() }}';
                    $.ajax({
                        url: "{{ url($ADMIN_ROUTE_NAME.'/images/ajax_check_exist') }}",
                        type: "POST",
                        data: {filename:filename},
                        dataType:"JSON",
                        headers:{'X-CSRF-TOKEN': _token},
                        async: false,
                        cache: false,
                        beforeSend:function(){
                        },
                        success: function(resp){
                            var isExist = parseInt(resp);

                            if(isExist == 1){
                                $(file.queueItem[0]).find(".fileinfo").text("Already exist");
                            }
                        }
                    });
                }
            }


            $(document).on("click", ".UploadBtn", function(){
                var selectedImages = $(".uploadifive-queue-item");
                //alert(selectedImages.length);
                if(selectedImages.length > 0){
                    $('#images').uploadifive('upload');
                }
                else{
                    alert("Please selecte files...");
                }
            });*/


    $(document).on("click", ".saveBtn", function(e){
        e.preventDefault();

        updateFormData = $("form[name=updateForm]").serialize();

        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url($ADMIN_ROUTE_NAME.'/images/ajax_update') }}",
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
                }
            }
        });
    });


    $(document).on("click", ".delImg", function(e){
        e.preventDefault();

        var conf = confirm("Are you sure you want to delete this?");

        if(conf){

            var currSelector = $(this);

            var id = $(this).data("id");

            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url($ADMIN_ROUTE_NAME.'/images/ajax_delete') }}",
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
                                //currSelector.parents(".row_"+id).remove();
                        window.location.reload();
                    }
                }
            });
        }
    });


// Media Library Box
    $(document).ready(function() {
        $('span.media_box').fancybox({
            type: "iframe",
            beforeClose: function(){
                var $iframe = $( '.fancybox-iframe' );
                var process_upload = $('.media_footer input:hidden[name=process_upload]', $iframe.contents()).val();
                if(process_upload == 1) {
                    var media_ids = $('.media_footer input:hidden[name=selected_media_id]', $iframe.contents()).val();
                    var media_path = $('.media_footer input:hidden[name=selected_media_path]', $iframe.contents()).val();
                    if(media_ids != ""){
                    //Set value of media id
                    /*$('#image').val(media_id);

                    if(media_path!=""){
                        var mediaBox = '<div class="imgBox"><img src="'+media_path+'" ><br/><br/></div>';
                        $('.imgBox').html('');
                        $('.mediaBox').html(mediaBox);
                    }*/
                        var media_ids_arr = media_ids.split(',');
                        if(media_ids_arr && media_ids_arr.length > 0) {
                            $.each(media_ids_arr,function(index,media_id){
                                ajaxUploadFromGallery(media_id);
                            });
                        }
                    }
                }
            }
        });
    });
    function ajaxUploadFromGallery(media_id) {
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url($ADMIN_ROUTE_NAME.'/images/ajax_upload_from_gallery') }}",
            type: "POST",
            data: {
                'cat' : "{{$category}}",
                'tid' : "{{$tbl_id}}",
                'tbl' : "{{$tbl_name}}",
                'table_id' : "{{$table_id}}",
                'media_id': media_id
            },
            dataType:"JSON",
            headers:{'X-CSRF-TOKEN': _token},
            async: false,
            cache: false,
            beforeSend:function(){
            },
            success: function(resp){
                if(resp.success){
                    if(resp.imgRow) {
                    // $(resp.imgRow).insertBefore($("form[name=updateForm]").find("table tbody .save_btn_box"));
                        $('#gallery_table tbody').prepend(resp.imgRow);
                        $(".no_records").hide();
                        $(".save_btn_box").show();
                    }
                } else {
                    if(resp.message) {
                        alert(resp.message);
                    } else {
                        alert('Something went wrong, please try again.');
                    }
                }
            }
        });
    }
</script>


@endslot

@endcomponent