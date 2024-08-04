@component('admin.layouts.main')
@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot
@slot('headerBlock')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<style>
    .fancybox-slide--iframe .fancybox-content { height:100% !important;  }

    .bootstrap-tagsinput { display: block;}
        .slugEdit {
            position: absolute;
            right: 22px;
            top: 26px;
            font-size: 15px;
            opacity: .7;
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endslot
<?php
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();
if(empty($back_url)){
    $back_url = $routeName.'/testimonials';
}
$name = (isset($testimonial->name))?$testimonial->name:'';
$title = (isset($testimonial->title))?$testimonial->title:'';
$slug = (isset($testimonial->slug))?$testimonial->slug:'';
$destination_id = (isset($testimonial->destination_id))?json_decode($testimonial->destination_id,true):[];
$destination_id = old('destination_id',$destination_id);
$package_id = (isset($testimonial->package_id))?json_decode($testimonial->package_id,true):[];
$package_id = old('package_id',$package_id);
$description = (isset($testimonial->description))?$testimonial->description:'';
$position_in_company = (isset($testimonial->position_in_company))?$testimonial->position_in_company:'';
$company_name = (isset($testimonial->company_name))?$testimonial->company_name:'';
$client_link = (isset($testimonial->client_link))?$testimonial->client_link:'';
$website = (isset($testimonial->website))?$testimonial->website:'';
$meta_title = (isset($testimonial->meta_title))?$testimonial->meta_title:'';
$meta_description = (isset($testimonial->meta_description))?$testimonial->meta_description:'';
$meta_keywords = (isset($testimonial->meta_keywords))?$testimonial->meta_keywords:'';
$email = (isset($testimonial->email))?$testimonial->email:'';
$image = (isset($testimonial->image))?$testimonial->image:'';
$date_on = (isset($testimonial->date_on))?$testimonial->date_on:'';
$date_on = CustomHelper::DateFormat($date_on, 'd/m/Y');
$featured = (isset($testimonial->featured))?$testimonial->featured:'';
$status = (isset($testimonial->status))?$testimonial->status:1;
$sort_order = (isset($testimonial->sort_order))?$testimonial->sort_order:0;
$storage = Storage::disk('public');
//pr($storage);
$path = 'testimonials/';
$old_image = $image;
$image_req = '';
$active_menu = "testimonials";
// $back_url = CustomHelper::BackUrl();
?>
@if(!empty($id))
    @include('admin.includes.testimonialmenu')
@endif
    <div class="top_title_admin">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
        <div class="add_button">
            <a href="{{ url($back_url) }}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>Back</a>
        </div>
    </div>
    <div class="centersec">
        @include('snippets.errors')
        @include('snippets.flash')
        <div class="bgcolor">
            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label class="control-label">Title:</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $title) }}" />
                            @include('snippets.errors_first', ['param' => 'title'])
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label required">Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $name) }}" />
                            @include('snippets.errors_first', ['param' => 'name'])
                        </div>
                    </div>
                    <?php
                if(!empty($testimonial->id)){
                    ?>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }} slug">
                            <label for="link_name" class="control-label required">Slug:</label>

                            <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $slug) }}" />

                            <a href="javascript:void(0);" class="slugEdit" id="EditTSlug" title="Edit"><i class="fas fa-edit"></i></a>

                            @include('snippets.errors_first', ['param' => 'slug'])
                        </div>
                    </div>
                <?php }?>

                <div class="form-group col-md-6{{ $errors->has('destination_id') ? ' has-error' : '' }}">
                  <label for="destination_id" class="control-label">Destination:</label>
                  <select class="form-control select2" name="destination_id[]" multiple>
                       <?php
                       $destination_id = old("destination_id[]",$destination_id);
                      if(!empty($destinations)){ ?>
                          <option value="">Select</option>   
                          <?php
                         // prd($categories);
                          foreach ($destinations as $destination){
                              ?>
                          <option value="{{$destination->id}}" <?php echo is_array($destination_id) && in_array($destination->id,$destination_id) ? 'selected' : ''  ?> >{{$destination->name}}</option>
                      <?php } } ?>
                  </select>
                  @include('snippets.errors_first', ['param' => 'destination_id'])
                </div>
                <div class="form-group col-md-6{{ $errors->has('package_id') ? ' has-error' : '' }}">
                  <label for="category" class="control-label">Package:</label>
                  <select class="form-control select2" name="package_id[]" multiple>
                       <?php
                       $package_id = old("package_id[]",$package_id);
                      if(!empty($packages)){ ?>
                          <option value="">Select</option>
                          
                          <?php
                         // prd($categories);
                          foreach ($packages as $package){
                              ?>
                          <option value="{{$package->id}}" <?php echo is_array($package_id) && in_array($package->id,$package_id) ? 'selected' : ''  ?> >{{$package->title}}</option>
                      <?php } } ?>
                  </select>
                  @include('snippets.errors_first', ['param' => 'package_id'])
                </div>
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label class="control-label required">Description:</label>
                        <textarea name="description" id="description" class="form-control ckeditor" >{{ old('description', $description) }}</textarea>
                        @include('snippets.errors_first', ['param' => 'description'])
                    </div>
                </div>
                <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('position_in_company') ? ' has-error' : '' }}">
                            <label class="control-label ">Position in Company</label>
                            <input type="text" name="position_in_company" class="form-control" value="{{ old('position_in_company', $position_in_company) }}" maxlength="255" />
                            @include('snippets.errors_first', ['param' => 'position_in_company'])
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                            <label class="control-label ">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $company_name) }}" maxlength="255" />
                            @include('snippets.errors_first', ['param' => 'company_name'])
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('client_link') ? ' has-error' : '' }}">
                            <label class="control-label ">Client Link</label>
                            <input type="text" name="client_link" class="form-control" value="{{ old('client_link', $client_link) }}" maxlength="255" />
                            @include('snippets.errors_first', ['param' => 'client_link'])
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
                            <label class="control-label ">Website</label>
                            <input type="text" name="website" class="form-control" value="{{ old('website', $website) }}" maxlength="255" />
                            @include('snippets.errors_first', ['param' => 'website'])
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label ">Email</label>
                            <input type="text" name="email" class="form-control" value="{{ old('email', $email) }}" maxlength="255" />
                            @include('snippets.errors_first', ['param' => 'email'])
                        </div>
                    </div>
                    <hr>
               {{-- <div  class="col-md-12">
                <h3>Add SEO Meta:</h3>
                </div>
                <div  class="form-group col-md-12 {{$errors->has('meta_title')?' has-error':''}}">
                    <label for="meta_title" class="control-label">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $meta_title)}}" id="meta_title" class="form-control"  />    
                    @include('snippets.errors_first', ['param' => 'meta_title'])
                </div>
                <div class="form-group col-md-12{{$errors->has('meta_description')?' has-error':''}}">
                    <label for="meta_description" class="control-label">Meta Description </label>
                    <textarea name="meta_description" id="meta_description" class="form-control" ><?php echo old('meta_description', $meta_description); ?></textarea>    
                    @include('snippets.errors_first', ['param' => 'meta_description'])
                </div>
                <div class="form-group col-md-12{{$errors->has('meta_keywords')?' has-error':''}}">
                    <label for="meta_keywords" class="control-label">Meta Keywords</label>
                    <textarea name="meta_keywords" id="brief" class="form-control" ><?php echo old('meta_keywords', $meta_keywords); ?></textarea>    
                    @include('snippets.errors_first', ['param' => 'meta_keywords'])
                </div> --}}
                 <?php
                $image_required = $image_req;
                if($id > 0){
                    $image_required = '';
                }
                ?>
                <div class="col-md-12">
                        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label {{ $image_required }}">Profile image:</label>
                            <input type="file" id="image" name="image"/>
                            @include('snippets.errors_first', ['param' => 'image'])
                        </div>
                        <?php
                        if(!empty($image)){
                            if($storage->exists($path.$image))
                            {
                                ?>
                                <div class="col-md-2 image_box">
                                    <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                                    <a href="javascript:void(0)" data-id="{{ $id }}" data='image' class="delImg">Delete</a>
                                </div>
                                <?php        
                            }
                            ?>
                            <?php
                        }
                        ?>
                        <input type="hidden" name="old_image" value="{{ $old_image }}">
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('date_on') ? ' has-error' : '' }}">
                        <label class="control-label ">Date on:</label>

                        <input type="text" name="date_on" class="form-control date_on" value="{{ old('date_on', $date_on) }}" autocomplete="off" />

                        @include('snippets.errors_first', ['param' => 'date_on'])
                    </div>
                </div>
                <div class="form-group  col-md-6{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Display Order:</label>

                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                        <label class="control-label">Status:</label>
                        &nbsp;&nbsp;
                        Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> >
                        &nbsp;
                        Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >
                        @include('snippets.errors_first', ['param' => 'status'])
                    </div>
                </div>
                <div class="form-group col-md-2{{$errors->has('featured')?' has-error':''}} ">
                    <label class="control-label ">Featured:</label>
                    <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />
                    @include('snippets.errors_first', ['param' => 'featured'])
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" title="Create this new testimonial"><i class="fa fa-save"></i> Save</button>
                        <a href="{{ url($back_url) }}" class="btn_admin2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@slot('bottomBlock')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(".date_on").on("keypress",function(event){return false;});
    var description = document.getElementById('description');
    CKEDITOR.replace(description);
    $( function() {
        $( ".date_on" ).datepicker({
            'dateFormat':'dd/mm/yy',
            changeMonth:true,
            changeYear:true,
            yearRange:"1950:0+"
        });
    });
    $('.select2').select2({
            placeholder: "Please Select",
            allowClear: true
        });

    $(".delImg").click(function(){
        var conf = confirm('Are you sure to delete this Image?');
        if(conf){
            var currSelector = $(this);
            var id = $(this).data("id");
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($routeName.'.testimonials.ajax_delete_image') }}",
                type: "POST",
                data: {image_id:id},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                beforeSend:function(){
                    $(".ajax_msg").html("");
                },
                success: function(resp){
                    if(resp.success){
                        currSelector.parent(".image_box").remove();
                    }
                }
            });
        }
    });
var slug = '{{$slug}}';
if(slug != ''){
  $('#slug').attr('readonly',true);
}

$("#EditTSlug").click(function(){
    $('#slug').attr('readonly',false);
});
</script>
@endslot
@endcomponent