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
// $routeName = CustomHelper::getAdminRouteName();

$id = (isset($feature->id))?$feature->id:'';
$name = (isset($feature->name))?$feature->name:'';
$available = (isset($feature->available))?$feature->available:0;
$is_featured = (isset($feature->is_featured))?$feature->is_featured:1; 
$status = (isset($feature->status))?$feature->status:1; 
$sort_order = (isset($feature->sort_order))?$feature->sort_order:0;

?>

    <?php
    $active_menu = "plan_includes";
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
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="control-label required">Name:</label>

                        <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />

                        @include('snippets.errors_first', ['param' => 'name'])
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
                
                <div class="form-group col-md-2{{$errors->has('available')?' has-error':''}} ">
                    <label class="control-label ">Available:</label>
                    <input type="checkbox" name="available" value="1" <?php echo ($available == '1')?'checked':''; ?> />
                    @include('snippets.errors_first', ['param' => 'available'])
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
</div>
</div>


@slot('bottomBlock')

@endslot

@endcomponent