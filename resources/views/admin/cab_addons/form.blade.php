@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
    <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ >
     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endslot


<?php
//pr($page);
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();

    if(empty($back_url)){
        $back_url = $routeName.'/cab_addons';
    }

$routeName = CustomHelper::getAdminRouteName();


$id = (isset($record->id))?$record->id:'';
$name = (isset($record->name))?$record->name:'';
$price = (isset($record->price))?$record->price:'';
$sort_order = (isset($record->sort_order))?$record->sort_order:'';
$featured = (isset($record->featured))?$record->featured:'';
$daily_rental = (isset($record->daily_rental))?$record->daily_rental:'';
$status = (isset($record->status))?$record->status:1;
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
              
                <div class="form-group  col-md-4{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label required">Name:</label>
                    <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"/>
                    @include('snippets.errors_first', ['param' => 'name'])
                </div>

                <div class="form-group  col-md-4{{ $errors->has('price') ? ' has-error' : '' }}">
                    <label for="price" class="control-label">Price:</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{ old('price',$price) }}" onkeyup="if(/[^\d.]/g.test(this.value)) this.value = this.value.replace(/[^\d.]/g, '')" > 
                    @include('snippets.errors_first', ['param' => 'price'])
                </div>

                <div class="form-group  col-md-4{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Sort Order:</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>
                
            
                
            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-4">
                <label class="control-label">Status:</label>
                &nbsp;&nbsp;
                Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                &nbsp;
                Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                @include('snippets.errors_first', ['param' => 'status'])
            </div>

            <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }} col-md-4">
                <label class="control-label ">Featured:</label>
                <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />
                @include('snippets.errors_first', ['param' => 'featured'])
            </div>

            <div class="form-group{{ $errors->has('daily_rental') ? ' has-error' : '' }} col-md-4">
                <label class="control-label ">Daily Rental:</label>
                <input type="checkbox" name="daily_rental" value="1" <?php echo ($daily_rental == '1')?'checked':''; ?> />
                @include('snippets.errors_first', ['param' => 'daily_rental'])
            </div>

            <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
            <div class="form-group col-md-12"><button type="submit" class="btn btn-success" title="Create this new Cab"><i class="fa fa-save"></i> Submit</button></div>
            
        </form>
        </div>
    </div>
</div>
 

@slot('bottomBlock')


@endslot

@endcomponent