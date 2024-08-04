@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ >
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endslot
<?php
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();
if(empty($back_url)){
    $back_url = $routeName.'/cabs';
}
$routeName = CustomHelper::getAdminRouteName();

$id = (isset($cabData->id))?$cabData->id:'';
$name = (isset($cabData->name))?$cabData->name:'';
$equivalent = (isset($cabData->equivalent))?$cabData->equivalent:'';
$seats = (isset($cabData->seats))?$cabData->seats:'';
$base_price = (isset($cabData->base_price))?$cabData->base_price:'';
$chauffeur_charge = (isset($cabData->chauffeur_charge))?$cabData->chauffeur_charge:'';
$night_stay_allowance = (isset($cabData->night_stay_allowance))?$cabData->night_stay_allowance:'';
$status = (isset($cabData->status))?$cabData->status:1;
$image = (isset($cabData->image))?$cabData->image:'';
$storage = Storage::disk('public');
$path = 'cabs/';
$old_image = $image;
$image_req = '';
$link_req = '';
?>
<div class="centersec">
    <div class="top_title_admin tab-title">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
        <div class="add_button">
            <?php if(request()->has('back_url')){ $back_url= request('back_url'); ?>
            <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
            <?php } ?>
        </div>
    </div>
    <div class="bgcolor">
    @include('snippets.flash')
    <div class="ajax_msg"></div>
    <div class="bgcolor1">
        <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            <div class="form-group  col-md-6{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="control-label required">Name:</label>
                <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"/>
                @include('snippets.errors_first', ['param' => 'name'])
            </div>

            <div class="form-group  col-md-6{{ $errors->has('equivalent') ? ' has-error' : '' }}">
                <label for="equivalent" class="control-label">Equivalent: (e.g. Includes AC Cabs like Ertiga, Xylo, Njoy, Evalia or Tavera)</label>

                <input type="text" name="equivalent" value="{{ old('equivalent', $equivalent) }}" id="equivalent" class="form-control"/>

                @include('snippets.errors_first', ['param' => 'equivalent'])
            </div>
<div class="clearfix"></div>
            <div class="form-group  col-md-3{{ $errors->has('seats') ? ' has-error' : '' }}">
                <label for="seats" class="control-label required">Passenger Seats :</label>
                <input type="number" name="seats" value="{{ old('seats', $seats) }}" id="seats" class="form-control"/>

                @include('snippets.errors_first', ['param' => 'seats'])
            </div>

            <div class="form-group  col-md-3{{ $errors->has('base_price') ? ' has-error' : '' }}">
                <label for="base_price" class="control-label">Base Price:</label>
                <input type="number" name="base_price" id="base_price" class="form-control" value="{{ old('base_price',$base_price) }}"> 
                @include('snippets.errors_first', ['param' => 'base_price'])
            </div>

            <div class="form-group  col-md-3{{ $errors->has('chauffeur_charge') ? ' has-error' : '' }}">
                <label for="chauffeur_charge" class="control-label">Driver charge Per Day :</label>
                <input type="number" name="chauffeur_charge" value="{{ old('chauffeur_charge', $chauffeur_charge) }}" id="chauffeur_charge" class="form-control"/>

                @include('snippets.errors_first', ['param' => 'chauffeur_charge'])
            </div>

            <div class="form-group  col-md-3{{ $errors->has('night_stay_allowance') ? ' has-error' : '' }}">
                <label for="night_stay_allowance" class="control-label">Night Stay Allowance Per Night :</label>
                <input type="number" name="night_stay_allowance" value="{{ old('night_stay_allowance', $night_stay_allowance) }}" id="night_stay_allowance" class="form-control"/>

                @include('snippets.errors_first', ['param' => 'night_stay_allowance'])
            </div>


        </div>
        <?php
        $image_required = $image_req;
        if($id > 0) {
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
                <?php if(!empty($image) && $storage->exists($path.$image)) { ?>
                <div class="col-md-2 image_box">
                    <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                    <a href="javascript:void(0)" data-id="{{ $id }}" data='image' class="del_image">Delete</a>
                </div>
                <?php } ?>
                <input type="hidden" name="old_image" value="{{ $old_image }}">
            </div>
        </div>

        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
            <label class="control-label">Status:</label>
            &nbsp;&nbsp;
            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
            &nbsp;
            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >
            @include('snippets.errors_first', ['param' => 'status'])
        </div>
        <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
        <div class="form-group  col-md-12">
            <button type="submit" class="btn btn-success" title="Create this new Cab"><i class="fa fa-save"></i> Submit</button>
        </div>

    </form>
</div>
</div>


@slot('bottomBlock')

<!-- <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">

var description = document.getElementById('description');
CKEDITOR.replace(description);
</script> -->

<script type="text/javascript">

    $(document).ready(function(){

        $(".del_image").click(function(){

            var current_sel = $(this);

            var image_id = $(this).attr('data-id');

            var type = $(this).attr('data');

// alert(type); return false;

conf = confirm("Are you sure to Delete this Image?");

if(conf){

    var _token = '{{ csrf_token() }}';

    $.ajax({
        url: "{{ route($routeName.'.cabs.ajax_delete_image') }}",
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

    });
</script>

@endslot

@endcomponent