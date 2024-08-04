@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')

    <link rel="stylesheet" type="text/css" href="{{ url('/css/jquery-ui.css') }}"/ >

    <link rel="stylesheet" type="text/css" href="{{ url('/datetimepicker/jquery-ui-timepicker-addon.css') }}"/ >

    <link href="{{url('')}}/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />

    @endslot


    <?php

    //pr($blog->toArray());
    $routeName = CustomHelper::getAdminRouteName();

    $id = (isset($team->id))?$team->id:'';
    $name = (isset($team->name))?$team->name:'';
    $designation = (isset($team->designation))?$team->designation:'';
    $type = (isset($team->type))?$team->type:'';
    $brief = (isset($team->brief))?$team->brief:'';
    $description = (isset($team->description))?$team->description:'';
    $image = (isset($team->image))?$team->image:'';
    $pdf = (isset($team->pdf))?$team->pdf:'';
    $linkedin_url = (isset($team->linkedin_url))?$team->linkedin_url:'';
    $facebook_url = (isset($team->facebook_url))?$team->facebook_url:'';
    $twitter_url = (isset($team->twitter_url))?$team->twitter_url:'';
    $sort_order = (isset($team->sort_order))?$team->sort_order:0;
    $status = (isset($team->status))?$team->status:1;


    $storage = Storage::disk('public');
    //pr($storage);

    $path = 'teams/';

    $old_image = 0;

    $image_req = 'required';
    $link_req = '';

    $pdf_path = 'teams/pdf/';
    $old_pdf = $pdf;

    $type_arr = config('custom.team_type_arr');
    //pr($type_arr);

    ?>

    <div class="row">

        <div class="col-md-12 bgcolor">

            <h2>{{ $page_heading }} <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
            
        <a href="{{ url($back_url)}}" class="btn btn-success btn-sm" style='float: right;'>Back</a><?php } ?>
    </h2>

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label required">Name:</label>

                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />

                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>

                    
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('designation') ? ' has-error' : '' }}">
                            <label for="designation" class="control-label">Designation:</label>

                            <input type="text" id="designation" class="form-control" name="designation" value="{{ old('designation', $designation) }}" />

                            @include('snippets.errors_first', ['param' => 'designation'])
                        </div>
                    </div>

                    


                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('brief') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label">Brief:</label>

                            <textarea id="brief" class="form-control " name="brief">{{ old('brief', $brief) }}</textarea>

                            @include('snippets.errors_first', ['param' => 'brief'])
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="link_name" class="control-label">Description:</label>

                            <textarea id="description" class="form-control " name="description">{{ old('description', $description) }}</textarea>

                            @include('snippets.errors_first', ['param' => 'description'])
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label">Sort Order:</label>

                            <input type="text" id="sort_order" class="form-control" name="sort_order" value="{{ old('sort_order', $sort_order) }}" />

                            @include('snippets.errors_first', ['param' => 'sort_order'])
                        </div>
                    </div>



                <?php
                $image_required = $image_req;
                if($id > 0){
                    $image_required = 'required';
                }
                ?>
                    <div class="col-md-6">

                                <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                    <label for="sort_order" class="control-label {{ $image_required }}">Image:</label>

                                    <input type="file" id="image" name="image"/>

                                    @include('snippets.errors_first', ['param' => 'image'])
                                </div>
                                <?php
                                if(!empty($image)){
                                    if($storage->exists($path.$image))
                                    {
                                        ?>
                                        <div class="col-md-2 image_box">
                                           <img src="{{ url('/storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                                           <a href="javascript:void(0)" data-id="{{ $id }}" class="del_image">Delete</a>
                                       </div>
                                       <?php        
                                   }

                                   ?>
                                   <?php
                               }
                               ?>

                           <input type="hidden" name="old_image" value="{{ $old_image }}">
                        
                 </div>
                
                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                            <label class="control-label">Status:</label>
                            &nbsp;&nbsp;
                            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                            &nbsp;
                            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <p></p>
                                    <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                                    <button type="submit" class="btn btn-success" title="Create this new team"><i class="fa fa-save"></i> Submit</button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </form>
            <div class="clearfix"></div>
        </div>

    </div>




@slot('bottomBlock')

<script type="text/javascript" src="{{ url('/js/jquery-ui.js') }}"></script>

<script type="text/javascript" src="{{ url('/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>

<script type="text/javascript" src="{{ url('/js/ckeditor/ckeditor.js') }}"></script>

<script type="text/javascript">

    $(document).ready(function(){

        var editor = CKEDITOR.replace('description');
        var editor = CKEDITOR.replace('brief');
    });

    $('.sel_domains').multiselect({
        //enableFiltering: true,
        numberDisplayed: 4,
        //enableCaseInsensitiveFiltering: true,
        maxHeight: 200
    });
</script>

<script type="text/javascript">

$(document).ready(function(){

    $(".del_image").click(function(){

        var current_sel = $(this);

        var image_id = $(this).attr('data-id');

        conf = confirm("Are you sure to Delete this Image?");

        if(conf){

            var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.teams.ajax_delete_image') }}",
                type: "POST",
                data: {image_id},
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


    $(".del_pdf").click(function(){

        var current_sel = $(this);

        var id = $(this).attr('data-id');
        
        conf = confirm("Are you sure to Delete this Pdf?");

        if(conf){

            var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.teams.ajax_delete_pdf') }}",
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
                    $(".ajax_msg").html(resp.msg);
                    current_sel.parent('.pdf_box').remove();
                }
                else{
                    $(".ajax_msg").html(resp.msg);
                }

            }
        });
        }

    });

});
    
$(document).ready(function(){

$('.date').datepicker({
    dateFormat: "dd/mm/yy",
    changeMonth: true,
    changeYear: true,
    yearRange:"1950:+0"
});

});
 </script>


@endslot

@endcomponent