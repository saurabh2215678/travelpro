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
    if(empty($back_url)){ $back_url = $routeName.'/cab_cities'; }
    $routeName = CustomHelper::getAdminRouteName();
    $id = (isset($bikeCityQuery->id))?$bikeCityQuery->id:'';
    $name = (isset($bikeCityQuery->name))?$bikeCityQuery->name:'';
    $inclusions = (isset($bikeCityQuery->inclusions))?$bikeCityQuery->inclusions:'';
    $exclusions = (isset($bikeCityQuery->exclusions))?$bikeCityQuery->exclusions:'';
    $status = (isset($bikeCityQuery->status))?$bikeCityQuery->status:1;
    $sort_order = (isset($bikeCityQuery->sort_order))?$bikeCityQuery->sort_order:'';
    $selected_bike = (isset($bikeCityQuery->bike))?json_decode($bikeCityQuery->bike):[];
    $km_included = (isset($bikeCityQuery->km_included))?$bikeCityQuery->km_included:'';
?>
<?php if($id){ ?>
<div class="page_btns">
    <a class="active" href="{{route($routeName.'.bike_cities.view', ['id'=>$id])}}" title="Edit Cab Route"><i class="fas fa-view"></i>Edit City</a>
    <a href="{{route($routeName.'.bike_cities.agent_price', ['id'=>$id])}}" title="Edit Activity"><i class="fas fa-edit"></i>Agent price</a>
</div>
<?php } ?>

<div class="centersec">
    <div class="top_title_admin tab-title">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
        <div class="add_button">
            <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
            <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a><?php } ?>
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
                    <label for="name" class="control-label required">Name:</label>
                    <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control" />
                    @include('snippets.errors_first', ['param' => 'name'])
                </div>

                @if(!empty($bikes))
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('bike') ? ' has-error' : '' }}">
                        <label class="required">Bike:</label><br/>
                        <select name="bike[]" class="form-control admin_input1 select2" multiple="multiple">
                            <?php foreach ($bikes as $bike_data) {
                                $selected = "";
                                if(in_array($bike_data->id,$selected_bike)){ $selected = "selected"; }
                            ?>
                            <option value="{{$bike_data->id}}" {{$selected}}>{{$bike_data->name}}</option>
                        <?php } ?>
                        </select>                  
                        @include('snippets.errors_first', ['param' => 'bike'])                          
                    </div>
                </div>
                @endif

                <div class="form-group  col-md-12{{ $errors->has('inclusions') ? ' has-error' : '' }}">
                    <label for="inclusions" class="control-label">Inclusions:</label>
                    <textarea name="inclusions" id="inclusions" class="form-control ckeditor" ><?php echo old('inclusions', $inclusions); ?></textarea>    
                    @include('snippets.errors_first', ['param' => 'inclusions'])
                </div>
                <div class="form-group  col-md-12{{ $errors->has('exclusions') ? ' has-error' : '' }}">
                        <label for="exclusions" class="control-label">Exclusions:</label>
                        <textarea name="exclusions" id="exclusions" class="form-control ckeditor" ><?php echo old('exclusions', $exclusions); ?></textarea>    
                        @include('snippets.errors_first', ['param' => 'exclusions'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('km_included') ? ' has-error' : '' }}">
                    <label for="km_included" class="control-label required">Km Included:</label>
                    <input type="text" name="km_included" value="{{ old('km_included', $km_included) }}" id="km_included" class="form-control" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" />
                    @include('snippets.errors_first', ['param' => 'km_included'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Sort Order:</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}">
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>
        </div>

        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
            <label class="control-label">Status:</label>
            &nbsp;&nbsp;
            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
            &nbsp;
            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?>>
            @include('snippets.errors_first', ['param' => 'status'])
        </div>

        <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}" />
        <div class="clearfix"></div>
        <button type="submit" class="btn btn-success" title="Create this new Cab"><i class="fa fa-save"></i>Submit</button>
        </form>
    </div>
</div>
@slot('bottomBlock')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ url('/js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
$('.select2').select2({ placeholder: "Please Select", allowClear: true }); 
var editor = CKEDITOR.replace('inclusion',{
    filebrowserImageUploadUrl: "<?php echo route($routeName.'.ck_upload',['_token' => csrf_token()]);?>",
    filebrowserUploadMethod: 'form'
});
var editor = CKEDITOR.replace('exclusion',{
    filebrowserImageUploadUrl: "<?php echo route($routeName.'.ck_upload',['_token' => csrf_token()]);?>",
    filebrowserUploadMethod: 'form'
});
</script>

@endslot

@endcomponent