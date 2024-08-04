@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
<!-- add for removing error not define $categories -->
<?php
$categories = (isset($categories->categories)) ? $categories->categories : '';
$old_category_id = app('request')->input('category_id');
$routeName = CustomHelper::getAdminRouteName();
$back_url = (request()->has('back_url'))?request('back_url'):'';
?>
 <!-- add for removing error not define $categories -->
<?php
$category = app('request')->input('category') ?? '';
?>
@if(!empty($tbl_name) && $tbl_name=='packages')
<?php
$category = app('request')->input('category') ?? 'gallery';
$package_id = $tbl_id;
$active_menu = "packages".$package_id.'/'.$category;
?>
@include('admin.includes.packageoptionmenu')
@endif

@if(!empty($tbl_name) && $tbl_name=='accommodations')
<?php
$accommodation_id = $tbl_id;
if($category == 'gallery'){
    $active_menu = "accommodations".$accommodation_id.'/gallery';
}else if($category == 'banner'){
    $active_menu = "accommodations".$accommodation_id.'/banner';
}
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


<link rel="stylesheet" href="<?php echo url('/uploadifive'); ?>/uploadifive.css">

<div class="top_title_admin">
    <div class="title">
        <h2>{{ $page_heading }} ({{$images->count()}})</h2>
        <?php if ($back_url){ ?>
            <a href="{{ url($back_url)}}" class="btn btn-sm btn-success btn_admin2" style='float: right;'>Back</a>
        <?php } ?>
    </div>
    @if(CustomHelper::checkPermission('images','add'))
    <div class="add_button">
        <a href="{{ route($routeName.'.images.upload',['tid'=>$tbl_id,'module'=>$tbl_name,'category'=>$category])}}" class="btn_admin"><i class="fa fa-plus"></i> Edit Images</a>
    </div>
    @endif
</div>



<div class="data-space">
<div class="row">
    <div class="col-md-12">
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
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Default</th>
                    <th>Title</th>
                    <th>Image</th>
                    <!-- <th>Category</th> -->
                    <th>Link</th>
                    <th>Sort Order</th>
                </tr>
            </thead>

            <tbody>
                <?php
$noRecordsDisp = '';
$saveBtnDisp = 'display:none;';

$isSaveBtn = false;
if (!empty($images) && count($images) > 0) {
    $storage = Storage::disk('public');
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
            @include('admin.images._row_view', $rowData)
            <?php } } }

if ($isSaveBtn) {
    $noRecordsDisp = 'display:none;';
    $saveBtnDisp = '';
}
?>

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
                'buttonText'  : 'SELECT IMAGES',
                'width'        : 110,
                'formData'         : {
                    'tid' : "{{$tbl_id}}",
                    'tbl' : "{{$tbl_name}}",
                    '_token' : '<?php echo csrf_token(); ?>',

                    'timestamp' : '<?php echo $timestamp; ?>',
                    'token'     : '<?php echo md5('unique_salt' . $timestamp); ?>',
                },
                'itemTemplate' : '<div class="uploadifive-queue-item"><span class="filename"></span> | <span class="fileinfo"></span><div class="close"></div></div>',
                'queueID'          : 'queue',
                //'checkScript'  : '<?php //echo url($routeName."/images/"); ?>/ajax_check_exist',
                'uploadScript'     : '<?php echo url($routeName . "/images/"); ?>/ajax_upload',
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

                            $(resp.imgRow).insertBefore($("form[name=updateForm]").find("table tbody .save_btn_box"));

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
                        url: "{{ url($routeName.'/images/ajax_check_exist') }}",
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
                    url: "{{ url($routeName.'/images/ajax_update') }}",
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
                        url: "{{ url($routeName.'/images/ajax_delete') }}",
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


</script>


@endslot

@endcomponent