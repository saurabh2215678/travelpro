@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<link href="{{asset('')}}css/palette-color-picker.css" rel="stylesheet" type="text/css" />
<?php
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$id = (isset($record->id))?$record->id:'';
$name = (isset($record->name))?$record->name:'';
$code = (isset($record->code))?$record->code:'';
// $image = (isset($record->image))?$record->image:'';
$sort_order = (isset($record->sort_order))?$record->sort_order:'';
$featured = (isset($record->featured))?$record->featured:'';
$status = (isset($record->status))?$record->status:1;
$image_req = '';
$storage = Storage::disk('public');
$path = 'airlines/';
$thumb_path = 'airlines/thumb/';
?>
<div class="row top_title_admin enquiries-top" style="padding-left:0; padding-right:0;">

    <div class="col-md-6">
        <h2 class="top_title_admin enquiries-top">{{ $page_heading }} <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
    </div>
    <div class="col-md-6"><a href="{{ route($ADMIN_ROUTE_NAME.'.airline.index')}}" class="btn_admin btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?></h2></div>
</div>
<div class="row top_title_admin enquiries-top centersec" style="min-height: auto; padding-left:0; padding-right:0;">

    <div class="col-md-12">
        <div class="bgcolor">

            <?php /*@include('snippets.errors')*/ ?>
            @include('snippets.flash')

            <div class="ajax_msg"></div>

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label required">Name:</label>
                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />
                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <label for="code" class="control-label required">Code:</label>
                            <input type="text" id="code" class="form-control" name="code" value="{{ old('code', $code) }}" maxlength="2" />
                            @include('snippets.errors_first', ['param' => 'code'])
                        </div>
                    </div>                    

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label">Sort Order:</label>

                            <input type="text" id="sort_order" class="form-control" name="sort_order" value="{{ old('sort_order', $sort_order) }}" />

                            @include('snippets.errors_first', ['param' => 'sort_order'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label">Featured:</label> <br />
                            <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> >
                            @include('snippets.errors_first', ['param' => 'featured'])
                        </div>
                    </div>

                    <?php /*
                    <?php
                    $image_required = $image_req;
                    if($id > 0){
                        $image_required = '';
                    }
                    ?>

                    <div class="image-choose">
                        <div class="col-md-6">
                            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                <label for="image" class="control-label {{ $image_required }}">Image:</label>
                                <input type="file" id="image" name="image"/>
                                @include('snippets.errors_first', ['param' => 'image'])
                            </div>
                            <?php
                            if(!empty($image)) {
                                if($storage->exists($thumb_path.$image))
                                {
                            ?>
                            <div class="col-md-2 image_box">
                                <img src="{{ asset('storage/'.$thumb_path.$image) }}" style="width: 100px;"><br>
                                <a href="javascript:void(0)" data-id="{{ $id }}" data='image' class="del_image">Delete</a>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>*/ ?>

                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            &nbsp;&nbsp;
                            Show: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                            &nbsp;
                            Hide: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>
                    </div>                    
                </div>
                <div class="col-full">
                    <div class="form-group">
                        <input type="hidden" name="back_url" value="{{ $back_url }}" >
                        <button type="submit" class="btn btn-success" title="Submit"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div> 
</div>
<div class="clearfix"></div>
</div>
@slot('bottomBlock')
<script type="text/javascript">
$(document).ready(function() {
    $(".del_image").click(function(){
        var current_sel = $(this);
        var image_id = $(this).attr('data-id');
        var type = $(this).attr('data');
        conf = confirm("Are you sure to Delete this Image?");
        if(conf){
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($ADMIN_ROUTE_NAME.'.airline.ajax_delete_image') }}",
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
})
</script>

@endslot


@endcomponent