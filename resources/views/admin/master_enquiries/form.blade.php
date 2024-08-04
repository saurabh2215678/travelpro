@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    <link href="{{asset('')}}css/palette-color-picker.css" rel="stylesheet" type="text/css" />
    <?php
    // $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $id = (isset($record->id))?$record->id:'';
    $group_id = (isset($record->group_id))?$record->group_id:0;
    $parent_id = (isset($parent_id))?$parent_id:'';
    $group_id = old('group_id', $group_id);   
    $name = (isset($record->name))?$record->name:'';  
    $color_code = (isset($record->color_code))?$record->color_code:'';  
    $color_name = (isset($record->color_name))?$record->color_name:'';  
    $sort_order = (isset($record->sort_order))?$record->sort_order:'';
    $status = (isset($record->status))?$record->status:1;
    $category = (isset($record->category))?$record->category:'';
    $description = (isset($record->description))?$record->description:'';
    $lead_status_category_arr = config('custom.lead_status_category_arr');
    $order_status_category_arr = config('custom.order_status_category_arr');
    ?>
<div class="centersec">
        <?php $active_menu = $type; ?>
        @if($type != 'order-status')
            @include('admin.includes.masterenquirymenu')
        @endif

<div class="top_title_admin tab-title">
        <div class="col-md-6">
        <h2 class="top_title_admin enquiries-top">{{ $page_heading }} <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
        </div>
        <div class="col-md-6"><a href="{{ url($back_url) }}" class="btn_admin btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?></h2></div>
</div>
    
 
 
        
{{--<div class="row top_title_admin enquiries-top centersec" style="min-height: auto; padding-left:0; padding-right:0;">
<div class="col-md-12">
        <div class="bgcolor">
            <div class="inner-breadcrumb">
                <ul>
                    <?php if(!empty($id)){?>
                    {!!CustomHelper::enquiryMasterBreadcrumb($id)!!}
                <?php }else{?>
                    {!!CustomHelper::enquiryMasterBreadcrumb($parent_id)!!}
                <?php } ?>
                </ul>
            </div> 
        </div>
    </div> 
</div>--}}

        <div class="row top_title_admin enquiries-top centersec" style="min-height: auto; padding-left:0; padding-right:0;">

<div class="col-md-12">
        <div class="bgcolor">

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="row">
                    @if($type=='lead-source' || $type=='rating')
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label required">Name:</label>
                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />
                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>
                    @endif                             

                    @if($type=='lead-status')
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label required">Lead Status:</label>
                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />
                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                            <label class="control-label">Category:</label>
                            <select class="form-control" name="category">
                                <option value="">Select Category</option> 
                                <?php 
                                    foreach ($lead_status_category_arr as $lead_status_category_key => $lead_status_category_value) 
                                    {
                                        ?>
                                        <option value="{{$lead_status_category_key}}" <?php if($category == $lead_status_category_key) { echo 'selected'; } ?>>{{$lead_status_category_value}}</option>

                                        <?php
                                    }
                                ?>
                            </select>
                            @include('snippets.errors_first', ['param' => 'category'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="control-label">Description:</label>
                            
                            <input type="text" id="description" class="form-control" name="description" value="{{ old('description', $description) }}" />

                            @include('snippets.errors_first', ['param' => 'description'])
                        </div>
                    </div>
                    @endif

                    @if($type=='order-status')
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label required">Order Status:</label>
                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />
                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                            <label class="control-label">Category:</label>
                            <select class="form-control" name="category">
                                <option value="">Select Category</option> 
                                <?php foreach ($order_status_category_arr as $order_status_category_key => $order_status_category_value) { ?>
                                        <option value="{{$order_status_category_key}}" <?php if($category == $order_status_category_key) { echo 'selected'; } ?>>{{$order_status_category_value}}</option>
                                <?php } ?>
                            </select>
                            @include('snippets.errors_first', ['param' => 'category'])
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="control-label">Description:</label>
                            
                            <input type="text" id="description" class="form-control" name="description" value="{{ old('description', $description) }}" />

                            @include('snippets.errors_first', ['param' => 'description'])
                        </div>
                    </div>
                    @endif

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label">Sort Order:</label>
                            <input type="text" id="sort_order" class="form-control" name="sort_order" value="{{ old('sort_order', $sort_order) }}" />
                            @include('snippets.errors_first', ['param' => 'sort_order'])
                        </div>
                    </div>

                    @if($type=='rating')
                    <div class="col-md-1">
                        <div class="form-group{{ $errors->has('color_code') ? ' has-error' : '' }}">
                            <label for="color_code" class="control-label">Color Code:</label>
                            <input type="color" id="color_code" class="form-control" name="color_code" value="{{ old('color_code', $color_code) }}" />
                            @include('snippets.errors_first', ['param' => 'color_code'])
                        </div>
                    </div>
                    @endif

                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-full">
                            &nbsp;&nbsp;
                            Show: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                            &nbsp;
                            Hide: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-full">
                            <div class="form-group">
                            <!-- <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  /> -->
                                <input type="hidden" name="back_url" value="{{ $back_url }}" >
                                <button type="submit" class="btn btn-success" title="Submit"><i class="fa fa-save"></i> Submit</button>
                            </div>
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
<script type="text/javascript" src="{{url('')}}/js/palette-color-picker.min.js"></script>
        <script>
            $(document).ready(function(){
                $('#color_code').paletteColorPicker({
                    colors: [{"Silver": "#C0C0C0"},{"Gray": "#808080"},{"Black": "#000000"},{"Red": "#FF0000"},{"Maroon": "#800000"},{"Yellow": "#FFFF00"},{"Olive": "#808000"},{"Lime": "#00FF00"},{"Green": "#008000"},{"Aqua": "#00FFFF"},{"Teal": "#008080"},{"Blue": "#0000FF"},{"Navy Blue": "#000080"},{"Pink": "#FF00FF"},{"Purple": "#800080"}],
                    position:'downside',
                    insert: 'after',
                    timeout: 5000,
                    onchange_callback: function( clicked_color ) {
                        //console.log(clicked_color);
                        $('#color_code').attr('value',clicked_color);
                    }
                });
                setTimeout(function(){
                    $('.palette-color-picker-button').appendTo('#coPic');
                },500);
                


  /*  $("select[name='page']").on("change", function(){
        var page_name = $(this).val();

        if(page_name == 'home_link'){
            $("#image").siblings("label").removeClass("required");
            $("#link").siblings("label").addClass("required");
        }
        else{
            if(!($("#image").siblings("label").hasClass("required"))){
                $("#image").siblings("label").addClass("required");
            }
            $("#link").siblings("label").removeClass("required");
        }
    });*/

 

});
</script>

@endslot
 

@endcomponent