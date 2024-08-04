@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ >
@endslot


<?php
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

if(empty($back_url)){
    $back_url = $routeName.'/accommodations_features';
}

    //pr($blog->toArray());
$routeName = CustomHelper::getAdminRouteName();

$image = (isset($feature->icon))?$feature->icon:'';
$old_image = $image;

$id = (isset($feature->id))?$feature->id:'';
$title = (isset($feature->title))?$feature->title:'';
$status = (isset($feature->status))?$feature->status:1; 
$is_featured = (isset($feature->is_featured))?$feature->is_featured:1;
$sort_order = (isset($feature->sort_order))?$feature->sort_order:0;
$image_req = '';
$link_req = '';
$path = 'accommodation_feature/';
?>

    <?php
    $active_menu = "features";
    ?>
    @include('admin.includes.accommodationmenu')
    <div class="top_title_admin">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
        <div class="add_button">
            <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
            <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
               Back</a><?php } ?>
           </div>
       </div>
       <div class="centersec">
       <div class="bgcolor p10">
        @include('snippets.errors')
        @include('snippets.flash')
        <div class="ajax_msg"></div>
        <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="title" class="control-label required">Title:</label>
                        <input type="text" id="title" class="form-control" name="title" value="{{ old('title', $title) }}" />
                        @include('snippets.errors_first', ['param' => 'title'])
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                        <label for="sort_order" class="control-label">Sort Order:
                        </label>
                        <input type="number" id="sort_order" class="form-control" name="sort_order" value="{{ old('sort_order', $sort_order) }}" />
                        @include('snippets.errors_first', ['param' => 'sort_order'])
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
                            <input type="file" id="image" name="image"/>
                            @include('snippets.errors_first', ['param' => 'image'])
                        </div>
                        <?php if(!empty($image)){ ?>
                        <div class="col-md-2 image_box">
                            <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                        </div>
                        <?php } ?>
                        <input type="hidden" name="old_image" value="{{ $old_image }}">
                    </div>
                </div>

                 <div class="form-group col-md-2{{$errors->has('featured')?' has-error':''}} ">
                    <label class="control-label ">Featured:</label>
                    <input type="checkbox" name="is_featured" value="1" <?php echo ($is_featured == '1')?'checked':''; ?> />
                    @include('snippets.errors_first', ['param' => 'featured'])
                </div>
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                    <label class="control-label">Status:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                    &nbsp;
                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                    @include('snippets.errors_first', ['param' => 'status'])
                </div>
                <div class="form-group col-md-12">
                <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                <button type="submit" class="btn btn-success" title="Create this new accommodation feature"><i class="fa fa-save"></i> Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="clearfix"></div>



@slot('bottomBlock')

@endslot

@endcomponent