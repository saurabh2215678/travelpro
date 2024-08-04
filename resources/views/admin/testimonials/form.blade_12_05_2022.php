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

$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/testimonials';
}

$name = (isset($testimonial->name))?$testimonial->name:'';
$location = (isset($testimonial->location))?$testimonial->location:'';
$description = (isset($testimonial->description))?$testimonial->description:'';
$image = (isset($testimonial->image))?$testimonial->image:'';
$date_on = (isset($testimonial->date_on))?$testimonial->date_on:'';
$featured = (isset($testimonial->featured))?$testimonial->featured:1;
$status = (isset($testimonial->status))?$testimonial->status:1;

$date_on = CustomHelper::DateFormat($date_on, 'd/m/Y');

$media = "";
$testimonial_img = "";
$testimonial_thumb = "";

if(!empty($testimonial)){
    $testimonialMedia = $testimonial->Media;
    if(!empty($testimonialMedia)){
        $media = $testimonialMedia->getFirstMedia('media');
    }
}
if(!empty($media)){
    $testimonial_img = $media->getUrl();
    $testimonial_thumb = $media->getUrl('thumb');
}
?>
    <div class="top_title_admin">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
        <div class="add_button">
            <a href="{{ url($back_url) }}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>Back</a>
        </div>
    </div>
    <div class="centersec">

        @include('snippets.errors')
        @include('snippets.flash')

        <div class="bgcolor">

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label required">Name:</label>

                            <input type="text" name="name" class="form-control" value="{{ old('name', $name) }}" />

                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>

                    <div class="form-group col-md-12{{ $errors->has('package_id') ? ' has-error' : '' }}">
                    <label for="package_id" class="control-label">Package:</label>
                    <select class="form-control select2" name="package_id[]" multiple>
                         <?php
                        if(!empty($packages)){ ?>
                            <?php
                            foreach ($packages as $package){
                                $selected = '';
                                if(in_array($package->id,$package_id)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$package->id}}" {{$selected}}>{{$package->title}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'package_id'])
                </div>

                    <div class="col-md-6">

                        <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label class="control-label">Location:</label>

                            <input type="text" name="location" class="form-control" value="{{ old('location', $location) }}" />

                            @include('snippets.errors_first', ['param' => 'location'])
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label class="control-label required">Description:</label>
                            <textarea name="description" id="description" class="form-control ckeditor" >{{ old('description', $description) }}</textarea>
                            @include('snippets.errors_first', ['param' => 'description'])
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <span class="btn btn-success media_box" data-fancybox data-type="iframe" data-fancybox-type="iframe" href="javascript:;" data-src="{{ route($routeName.'.media.pop',['type'=>'image']) }}" data-preload="false" role="dialog"> Add Image </span>
                            <!-- <span class="btn btn-primary" name="image">Add Image</span> -->
                            @include('snippets.errors_first', ['param' => 'image'])
                        </div>
                        <input type="hidden" name="image" id="image" value="{{$image}}">
                        <div class="mediaBox"></div>
                        <?php
                        if(!empty($testimonial_img)){ ?>
                            <div class="imgBox">
                                <a href="{{$testimonial_img}}" target="_blank">
                                    <img src="{{$testimonial_thumb}}" >
                                </a>
                                <a href="javascript:void(0)" data-id="{{$testimonial->id}}" class="delImg">Delete</a>
                                <br><br>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('date_on') ? ' has-error' : '' }}">
                            <label class="control-label ">Date on:</label>

                            <input type="text" name="date_on" class="form-control date_on" value="{{ old('date_on', $date_on) }}" readonly />

                            @include('snippets.errors_first', ['param' => 'date_on'])
                        </div>
                    </div>
                </div>


                 <div class="row">
                    <div class="col-md-6">
                        <br>
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label class="control-label">Status:</label>
                            &nbsp;&nbsp;
                            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                            &nbsp;
                            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-12">

                        <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }}">
                            <label class="control-label ">Featured:</label>

                            <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />

                            @include('snippets.errors_first', ['param' => 'featured'])
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" title="Create this new category"><i class="fa fa-save"></i> Save</button>
                            <a href="{{ url($back_url) }}" class="btn_admin2">Cancel</a>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>

@slot('bottomBlock')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
    var description = document.getElementById('description');
    CKEDITOR.replace(description);

    $( function() {
        $( ".date_on" ).datepicker({
            'dateFormat':'dd/mm/yy',
            changeMonth:true,
            changeYear:true,
            yearRange:"1950:0+"
        });
    });

    $(".delImg").click(function(){
        var conf = confirm('Are you sure to delete this Image?');

        if(conf){

            var currSelector = $(this);

            var id = $(this).data("id");

            var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.testimonials.ajax_delete_image') }}",
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
                        currSelector.parent(".imgBox").remove();
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
            var media_id = $('.media_footer input:hidden[name=selected_media_id]', $iframe.contents()).val();
            var media_path = $('.media_footer input:hidden[name=selected_media_path]', $iframe.contents()).val();

            if(media_id != ""){
                //Set value of media id
                $('#image').val(media_id);

                if(media_path!=""){
                    var mediaBox = '<div class="imgBox"><img src="'+media_path+'" ><br/><br/></div>';
                    $('.imgBox').html('');
                    $('.mediaBox').html(mediaBox);
                }
            }
        }
    });
});    
</script>

@endslot

@endcomponent