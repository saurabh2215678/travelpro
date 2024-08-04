@component('admin.layouts.main')

@slot('title')
Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .bootstrap-tagsinput { display: block;}
    .slugEdit {
        position: absolute;
        right: 22px;
        top: 30px;
        font-size: 15px;
        opacity: .7;
    </style>
    @endslot
    <?php
// $BackUrl = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();

    $id = (isset($teamManagenent->id))?$teamManagenent->id:'';
    $gender = (isset($teamManagenent->gender))?$teamManagenent->gender:'';
    $title = (isset($teamManagenent->title))?$teamManagenent->title:'';
    $sub_title = (isset($teamManagenent->sub_title))?$teamManagenent->sub_title:'';
    $slug = (isset($teamManagenent->slug))?$teamManagenent->slug:'';
    $designation = (isset($teamManagenent->designation))?$teamManagenent->designation:'';
    $category = (isset($teamManagenent->category))?json_decode($teamManagenent->category,true):[];
    if(isset($teamManagenent->trip_theme) && !empty($teamManagenent->trip_theme)) {
        $trip_theme = json_decode($teamManagenent->trip_theme,true)??[];
    } else {
        $trip_theme = [];
    }
    $email = (isset($teamManagenent->email))?$teamManagenent->email:'';
    $alt_email = (isset($teamManagenent->alt_email))?$teamManagenent->alt_email:'';
    $mobile_no = (isset($teamManagenent->mobile_no))?$teamManagenent->mobile_no:'';
    $brief_profile = (isset($teamManagenent->brief_profile))?$teamManagenent->brief_profile:'';
    $detail_profile = (isset($teamManagenent->detail_profile))?$teamManagenent->detail_profile:'';
    $facebook_link = (isset($teamManagenent->facebook_link))?$teamManagenent->facebook_link:'';
    $twitter_link = (isset($teamManagenent->twitter_link))?$teamManagenent->twitter_link:'';
    $sort_order = (isset($teamManagenent->sort_order))?$teamManagenent->sort_order:0;
    $featured = (isset($teamManagenent->featured))?$teamManagenent->featured:'';
    $status = (isset($teamManagenent->status))?$teamManagenent->status:'';
    $image = (isset($teamManagenent->image))?$teamManagenent->image:'';

    $storage = Storage::disk('public');
    $path = 'teammanagements/';

    $old_image = $image;
    $image_req = '';
    $link_req = '';
    ?>
    <div class="top_title_admin">
        <div class="title">
            <h2>{{ $page_heading }}</h2>
        </div>
    </div>

    <div class="centersec">
       <div class="bgcolor">
        @include('snippets.errors')
        @include('snippets.flash')

        <div class="ajax_msg"></div>

        <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}
            <div class="form-group col-md-6{{ $errors->has('title') ? ' has-error' : '' }}">
                <label for="title" class="control-label required">Name :</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title',$title) }}" autocomplete="off">  
                @include('snippets.errors_first', ['param' => 'title'])
            </div>

            <div class="form-group col-md-6{{ $errors->has('sub_title') ? ' has-error' : '' }}">
                <label for="sub_title" class="control-label">Title:</label>
                <input type="text" name="sub_title" id="sub_title" class="form-control" value="{{ old('sub_title',$sub_title) }}" autocomplete="off">  
                @include('snippets.errors_first', ['param' => 'sub_title'])
            </div>

            <div class="form-group col-md-6{{ $errors->has('gender') ? ' has-error' : '' }}">
                <label for="gender" class="control-label">Gender:</label>
                <select class="form-control" name="gender">
                    <option value="">Select Gender</option>
                    <option value="1" <?php echo(!empty($gender) && $gender == '1')?"selected":'';?>>Male</option>
                    <option value="2" <?php echo(!empty($gender) && $gender == '2')?"selected":'';?>>Female</option>
                    <option value="3" <?php echo(!empty($gender) && $gender == '3')?"selected":'';?>>Other</option>
                </select>
                @include('snippets.errors_first', ['param' => 'gender'])
            </div>

            <?php
            if(!empty($teamManagenent->id)){
                ?>
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }} slug">
                        <label for="link_name" class="control-label required">Slug:</label>
                        <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $slug) }}" />

                        <a href="javascript:void(0);" class="slugEdit" id="EditTeamSlug" title="Edit"><i class="fas fa-edit"></i></a>
                        @include('snippets.errors_first', ['param' => 'slug'])
                    </div>
                </div>
            <?php }?>

            <div class="form-group col-md-6{{ $errors->has('designation') ? ' has-error' : '' }}">
                <label for="designation" class="control-label required">Designation:</label>
                <input type="text" name="designation" id="designation" class="form-control" value="{{ old('designation',$designation) }}" autocomplete="off">  
                @include('snippets.errors_first', ['param' => 'designation'])
            </div>

            <div style="display:none;" class="form-group col-md-6{{ $errors->has('category') ? ' has-error' : '' }}">
                <label for="category" class="control-label">Category:</label>
                <select class="form-control select2" name="category[]" multiple>
                    <?php
                    if(!empty($teamcategories)){ ?>
                        <?php
                        foreach ($teamcategories as $teamCategory){
                            $selected = '';
                            if(in_array($teamCategory->id,$category)){
                                $selected = 'selected';
                            }
                            ?>
                            <option value="{{$teamCategory->id}}" {{$selected}}>{{$teamCategory->title}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'category'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('trip_theme') ? ' has-error' : '' }}">
                    <label for="trip_theme" class="control-label">Tour/Trip Theme:</label>
                    <select class="form-control select2" name="trip_theme[]" multiple>
                       <?php
                       if(!empty($themes)){ ?>
                        <?php
                        foreach ($themes as $theme_query){
                            $selected = '';
                            if(in_array($theme_query->id,$trip_theme)){
                                $selected = 'selected';
                            }
                            ?>
                            <option value="{{$theme_query->id}}" {{$selected}}>{{$theme_query->title}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'trip_theme'])
                </div>

                <div class="form-group col-md-12{{ $errors->has('brief_profile') ? ' has-error' : '' }}">
                    <label for="brief_profile" class="control-label">Brief Profile:</label>

                    <textarea name="brief_profile" id="brief_profile" class="form-control" ><?php echo old('brief_profile', $brief_profile); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'brief_profile'])
                </div>

                <div class="clearfix"></div>
                <div class="form-group  col-md-12{{ $errors->has('detail_profile') ? ' has-error' : '' }}">
                	<label for="detail_profile" class="control-label">Detail Profile:</label>

                	<textarea name="detail_profile" id="detail_profile" class="form-control ckeditor" ><?php echo old('detail_profile', $detail_profile); ?></textarea>    

                	@include('snippets.errors_first', ['param' => 'detail_profile'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                    <label for="mobile_no" class="control-label">Contact Phone/ Mobile No:</label>
                    <input type="text" name="mobile_no" id="mobile_no" class="form-control" value="{{ old('mobile_no',$mobile_no) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'mobile_no'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="control-label">Email:</label>
                    <input type="text" name="email" id="email" class="form-control" value="{{ old('email',$email) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'email'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('alt_email') ? ' has-error' : '' }}">
                    <label for="alt_email" class="control-label">Alternative Email:</label>
                    <input type="text" name="alt_email" id="alt_email" class="form-control" value="{{ old('alt_email',$alt_email) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'alt_email'])
                </div>

                <div style="display:none;" class="form-group  col-md-6{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Sort Order:</label>

                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('facebook_link') ? ' has-error' : '' }}">
                    <label for="facebook_link" class="control-label ">Facebook Link :</label>
                    <input type="text" name="facebook_link" id="facebook_link" class="form-control" value="{{ old('facebook_link',$facebook_link) }}">  
                    @include('snippets.errors_first', ['param' => 'facebook_link'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('twitter_link') ? ' has-error' : '' }}">
                    <label for="twitter_link" class="control-label ">Twitter Link :</label>
                    <input type="text" name="twitter_link" id="twitter_link" class="form-control" value="{{ old('twitter_link',$twitter_link) }}">  
                    @include('snippets.errors_first', ['param' => 'twitter_link'])
                </div>

                <?php
                $image_required = $image_req;
                if($id > 0){
                    $image_required = '';
                }
                ?>
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                     <label for="sort_order" class="control-label {{ $image_required }}">Profile Photo:</label>
                     <input type="file" id="image" name="image"/>
                     @include('snippets.errors_first', ['param' => 'image'])
                 </div>
                 <?php
                if(!empty($image)){
                     if($storage->exists($path.$image)){ ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                           <a href="javascript:void(0)" data-id="{{ $id }}" data='image' class="del_image">Delete</a>
                       </div>
                       <?php        
                    }
                }
                ?>
               <input type="hidden" name="old_image" value="{{ $old_image }}">
           </div>
           <div class="clearfix"></div>              
           <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
            <label class="control-label">Status:</label>
            &nbsp;&nbsp;
            Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
            &nbsp;
            Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >
            @include('snippets.errors_first', ['param' => 'status'])
        </div>

        <div class="form-group col-md-2{{$errors->has('featured')?' has-error':''}} ">
            <label class="control-label ">Featured:</label>
            <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />
            @include('snippets.errors_first', ['param' => 'featured'])
        </div>

        <div class="clearfix"></div>
        <div class="form-group col-md-12">
            <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>
            <a href="{{ route('admin.teammanagements.index') }}" class="btn_admin2" title="Cancel">Cancel</a>
        </div>
        <br/><br/>
    </form>
</div>
</div>       

@slot('bottomBlock')
<script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">

    var description = document.getElementById('detail_profile');
    CKEDITOR.replace(description);

    $('.select2').select2({
        placeholder: "Please Select",
        allowClear: true
    });

    var slug = '{{$slug}}';
    if(slug != ''){
        $('#slug').attr('readonly',true);
    }

    $("#EditTeamSlug").click(function(){
        $('#slug').attr('readonly',false);
    });

    $(document).ready(function(){
    $(".del_image").click(function(){
        var current_sel = $(this);
        var image_id = $(this).attr('data-id');
        var type = $(this).attr('data');

        conf = confirm("Are you sure to Delete this Image?");
        if(conf){
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{route($routeName.'.teammanagements.ajax_delete_image')}}",
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