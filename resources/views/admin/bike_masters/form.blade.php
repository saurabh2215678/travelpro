@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endslot

<?php
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();
    if(empty($back_url)){ $back_url = $routeName.'/bike_master';}
    $routeName = CustomHelper::getAdminRouteName();
    $id = (isset($bikeQuery->id))?$bikeQuery->id:'';
    $name = (isset($bikeQuery->name))?$bikeQuery->name:'';
    $equivalent = (isset($bikeQuery->equivalent))?$bikeQuery->equivalent:'';
    $type = (isset($bikeQuery->type))?$bikeQuery->type:'';
    $type = old('type',$type);
    $modal = (isset($bikeQuery->modal))?$bikeQuery->modal:'';
    $image = (isset($bikeQuery->image))?$bikeQuery->image:'';
    $status = (isset($bikeQuery->status))?$bikeQuery->status:1;
    $status = old('status',$status);
    $storage = Storage::disk('public');
    $path = 'bikes/';
    $old_image = $image;
    $image_req = '';
    $link_req = '';
    $rental_type_arr = config('custom.rental_type_arr');
?>

<div class="centersec">
    <div class="top_title_admin tab-title">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
        <div class="add_button">
            <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
            <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                Back</a><?php } ?>
        </div>
    </div>
    <div class="bgcolor">

        <?php /*@include('snippets.errors')*/ ?>
        @include('snippets.flash')

        <div class="ajax_msg"></div>
        <div class="bgcolor1">
            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}

                <div class="form-group  col-md-6{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label required">Bike Name:</label>
                    <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control" />
                    @include('snippets.errors_first', ['param' => 'name'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('equivalent') ? ' has-error' : '' }}">
                    <label for="equivalent" class="control-label">Equivalent: <!-- (e.g. Ertiga, Xylo, Njoy, Evalia or Tavera) --> </label>
                    <input type="text" name="equivalent" value="{{ old('equivalent', $equivalent) }}" id="equivalent" class="form-control" />
                    @include('snippets.errors_first', ['param' => 'equivalent'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('type') ? ' has-error' : '' }}">
                    <label class="control-label required">Bike Type:</label>
                    <select name="type" class="form-control">
                        <option value="">Select Bike Type</option>
                            @if($rental_type_arr)
                                @foreach($rental_type_arr as $key => $val)
                                    <option value="{{$key}}" {{($key==$type)? 'selected' : ''}}>{{$val}}</option>
                                @endforeach
                            @endif
                    </select>
                    @include('snippets.errors_first', ['param' => 'type'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('modal') ? ' has-error' : '' }}">
                    <label for="modal" class="control-label required">Bike Model:</label>
                    <input type="text" name="modal" value="{{ old('modal', $modal) }}" id="modal" class="form-control" />
                    @include('snippets.errors_first', ['param' => 'modal'])
                </div>
        </div>
        <?php
                $image_required = $image_req;
                if($id > 0){
                    $image_required = '';
                } 
        ?>
        <div class="image-choose">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label {{ $image_required }}">Image:</label>
                    <input type="file" id="image" name="image" />
                    @include('snippets.errors_first', ['param' => 'image'])
                </div>
                <?php
                    if(!empty($image)) {
                        if($storage->exists($path.$image)) { ?>
                            <div class="col-md-2 image_box">
                                <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                                <a href="javascript:void(0)" data-id="{{ $id }}" data='image' class="del_image">Delete</a>
                            </div>
                <?php } } ?>
                <input type="hidden" name="old_image" value="{{ $old_image }}">
            </div>
        </div>

        <div class="form-group  col-md-6{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
            <label class="control-label">Status:</label>
            &nbsp;&nbsp;
            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
            &nbsp;
            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?>>
            @include('snippets.errors_first', ['param' => 'status'])
        </div>
        <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}" />
        <div class="clearfix"></div>
        <button type="submit" class="btn btn-success" title="Create this new Bike"><i class="fa fa-save"></i>
            Submit</button>
        </form>
    </div>
</div>

@slot('bottomBlock')
<script type="text/javascript">
$(document).ready(function() {
    $(".del_image").click(function() {
        var current_sel = $(this);
        var image_id = $(this).attr('data-id');
        var type = $(this).attr('data');
        conf = confirm("Are you sure to Delete this Image?");
        if (conf) {
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($routeName.'.bike_master.ajax_delete_image') }}",
                type: "POST",
                data: {image_id,type},
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                cache: false,
                beforeSend: function() {
                    $(".ajax_msg").html("");
                },
                success: function(resp) {
                    if (resp.success) {
                        $(".ajax_msg").html(resp.msg);
                        current_sel.parent('.image_box').remove();
                    } else {
                        $(".ajax_msg").html(resp.msg);
                    }
                }
            });
        }
    });
});
</script>

@endslot

@endcomponent