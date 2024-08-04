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
    $back_url = $routeName.'/package_airlines';
}

// $routeName = CustomHelper::getAdminRouteName();


$id = (isset($airline_query->id))?$airline_query->id:'';
$airline_name = (isset($airline_query->airline_name))?$airline_query->airline_name:'';
$airline_code = (isset($airline_query->airline_code))?$airline_query->airline_code:'';
// $airline_from = (isset($airline_query->airline_from))?$airline_query->airline_from:'';
// $airline_to = (isset($airline_query->airline_to))?$airline_query->airline_to:'';
// $price = (isset($airline_query->price))?$airline_query->price:'';
$status = (isset($airline_query->status))?$airline_query->status:1;
$sort_order = (isset($airline_query->sort_order))?$airline_query->sort_order:0;
$image = (isset($airline_query->image))?$airline_query->image:'';
$slug = (isset($airline_query->slug))?$airline_query->slug:'';
$storage = Storage::disk('public');
//pr($storage);
$path = 'airlines/';
$old_image = $image;
$image_req = '';
$link_req = '';
?>



<div class="centersec">
    <?php
    $active_menu = "packages.airlines";
    ?>
    @include('admin.includes.packagemenu')
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

                <?php
                    if(!empty($airline_query->id)){
                        ?>
                        <div style="display: none;" class="form-group  col-md-4{{ $errors->has('slug') ? ' has-error' : '' }}">
                            <label for="slug" class="control-label">Slug:</label>

                            <input type="text" name="slug" value="{{ old('slug', $slug) }}" id="slug" class="form-control"  maxlength="255" readonly />
                            @include('snippets.errors_first', ['param' => 'slug'])
                        </div>
                    <?php } ?>
              
                <div class="form-group  col-md-6{{ $errors->has('airline_name') ? ' has-error' : '' }}">
                    <label for="airline_name" class="control-label required">Airline Name:</label>

                    <input type="text" name="airline_name" value="{{ old('airline_name', $airline_name) }}" id="airline_name" class="form-control"/>

                    @include('snippets.errors_first', ['param' => 'airline_name'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('airline_code') ? ' has-error' : '' }}">
                    <label for="airline_code" class="control-label required">Airline Code:</label>

                    <input type="text" name="airline_code" value="{{ old('airline_code', $airline_code) }}" id="airline_code" class="form-control"/>

                    @include('snippets.errors_first', ['param' => 'airline_code'])
                </div>

                <?php /*
                <div class="form-group col-md-6{{ $errors->has('airline_from') ? ' has-error' : '' }}">
                    <label for="airline_from" class="control-label">Airline From:</label>
                    <input type="text" name="airline_from" id="airline_from" class="form-control" value="{{ old('airline_from',$airline_from) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'airline_from'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('airline_to') ? ' has-error' : '' }}">
                    <label for="airline_to" class="control-label">Airline To:</label>
                    <input type="text" name="airline_to" id="airline_to" class="form-control" value="{{ old('airline_to',$airline_to) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'airline_to'])
                </div>

                <div class="form-group col-md-3 {{ $errors->has('price') ? ' has-error' : '' }}">
                <label for="price" class="control-label ">Price :</label>
                <input type="text" id="price" class="form-control" name="price" value="{{ old('price',$price) }}" />
                @include('snippets.errors_first', ['param' => 'price'])
                </div> */ ?>

                <div style="display:none;" class="col-md-4">
                    <div class="form-group{{ $errors->has('identifier') ? ' has-error' : '' }}">
                        <label for="identifier" class="control-label">{{($segment == 'activity')?'Activity':'Package'}} Type Identifier :</label>
                        <select class="form-control" name="identifier" id="identifier">
                        <?php //<option value="">--Select--</option> ?>
                        <option value="package" {{($identifier=='package')?'selected':''}}>Package</option>
                        <option value="activity" {{($identifier=='activity')?'selected':''}}>Activity</option>
                        </select>
                    </div>
                 </div>

                <div class="form-group  col-md-3{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Sort Order:</label>

                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
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
                        <?php
                        if(!empty($image)){
                            // prd($path.$image);
                            if($storage->exists($path.$image))
                            {
                                ?>
                                <div class="col-md-2 image_box">
                                    <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                                    <a href="javascript:void(0)" data-id="{{ $id }}" data='image' class="del_image">Delete</a>
                                </div>
                                <?php        
                            }
                            ?>
                            <?php
                        }
                        ?>
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
            <div class="clearfix"></div>
            <button type="submit" class="btn btn-success" title="Create this new {{($segment == 'activity')?'Activity':'Packages'}} airline"><i class="fa fa-save"></i> Submit</button>
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
                        url: "{{ route($routeName.'.'.$segment.'.ajax_delete_image_airline') }}",
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