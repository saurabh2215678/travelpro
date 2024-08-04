@component('admin.layouts.main')

@slot('title')
    Admin - {{$page_heading}} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

@slot('headerBlock')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .bootstrap-tagsinput { display: block;}
        .slugEdit {
            position: absolute;
            right: 22px;
            top: 26px;
            font-size: 15px;
            opacity: .7;
    </style>
@endslot

<?php
$routeName = CustomHelper::getAdminRouteName();
$name = (isset($destination->name))?$destination->name:'';
$slug = (isset($destination->slug))?$destination->slug:'';
$parent_id = (isset($destination->parent_id))?$destination->parent_id:0;
if(empty($parent_id)) {
    $parent_id = (request()->has('parent_id'))?request()->parent_id:0;
}
$destination_type = (isset($destination->destination_type))?$destination->destination_type:0;
$brief = (isset($destination->brief))?$destination->brief:'';
$description = (isset($destination->description))?$destination->description:'';
$sort_order = (isset($destination->sort_order))?$destination->sort_order:'';
if(isset($destination->best_months) && !empty($destination->best_months)) {
    $best_months = json_decode($destination->best_months)??[];
} else {
    $best_months = [];
}
$latitude = (isset($destination->latitude))?$destination->latitude:'';
$longtitude = (isset($destination->longtitude))?$destination->longtitude:'';
$status = (isset($destination->status))?$destination->status:1;
$show = (isset($destination->show))?$destination->show:1;
$featured = (isset($destination->featured))?$destination->featured:'';
$is_international = (isset($destination->is_international))?$destination->is_international:'';
$video_link = (isset($destination->video_link))?$destination->video_link:'';
$meta_title = (isset($destination->meta_title))?$destination->meta_title:'';
$meta_keyword = (isset($destination->meta_keyword))?$destination->meta_keyword:'';
$meta_description = (isset($destination->meta_description))?$destination->meta_description:'';
$image = (isset($destination->image))?$destination->image:'';
$feature_image = (isset($destination->feature_image))?$destination->feature_image:'';
$banner_image = (isset($destination->banner_image))?$destination->banner_image:'';
$destination_flags = (!empty($destination) && (!$destination->destinationFlags->isEmpty())) ? $destination->destinationFlags : "";

$destination_flag_arr = [];
    if(!empty($destination_flags)){
        foreach ($destination_flags as  $dflag) {
            $destination_flag_arr[] = $dflag->id;
        }
    }

$storage = Storage::disk('public');
$path = 'destinations/';

$old_image = $image;
$old_feature_image = $feature_image;
$old_banner_image = $banner_image;
$image_req = '';
$link_req = '';
?>
<?php
$active_menu = "destinations";
$category = (request()->has('category'))?request()->category:0;
?>
@if(!empty($destination_id))
@include('admin.includes.destinationoptionmenu')
@endif
<div class="top_title_admin">
<div class="title">
<h2>{{ $page_heading }}</h2>
</div>
</div>



    <div class="centersec">

            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>
			<div class="bgcolor">
            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}

                <div class="form-group col-md-6{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label required">Destination Name:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name',$name) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'name'])
                </div>
                
                @if($parent != 0)
                <!-- <div class="clearfix"></div> -->
                <div class="form-group col-md-6{{ $errors->has('parent_id') ? ' has-error' : '' }}">
                    <label for="parent_id" class="control-label">Parent Destination:</label>
                    <select class="form-control select2" name="parent_id">
                        <?php
                        $parent_id = old('parent_id',$parent_id);

                        if(!empty($destinations)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($destinations as $destination_it){
                                if($destination_it->id != $id){

                                $selected = '';
                                if($destination_it->id == $parent_id){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$destination_it->id}}" {{$selected}}>{{$destination_it->name}}</option>
                        <?php }}}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'parent_id'])
                </div>             
                @endif
                <div class="form-group col-md-6{{ $errors->has('destination_type') ? ' has-error' : '' }}">
                    <label for="destination_type" class="control-label">Destination Type:</label>
                    <select class="form-control" name="destination_type">
                        <?php
                        $destination_type = old('destination_type',$destination_type);
                        if(!empty($destination_types)){
                            ?>
                            <option value="">Select</option>
                            <?php
                            foreach ($destination_types as $destination_typ){
                                $selected = '';
                                if($destination_typ->id == $destination_type){
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="{{$destination_typ->id}}" {{$selected}}>{{$destination_typ->name}}</option>
                                <?php 
                            }
                        }
                        ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'destination_type'])
                </div>

                <?php
                if(!empty($destination->id)){
                    ?>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }} slug">
                            <label for="link_name" class="control-label required">Slug:</label>

                            <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $slug) }}" />

                            <a href="javascript:void(0);" class="slugEdit" id="EditDSlug" title="Edit"><i class="fas fa-edit"></i></a>

                            @include('snippets.errors_first', ['param' => 'slug'])
                        </div>
                    </div>
                <?php }?>

                <div class="form-group col-md-6{{ $errors->has('related_destinations') ? ' has-error' : '' }}">
                    <label for="related_destinations" class="control-label">Related Destinations:</label>
                    <select class="form-control select2" name="related_destinations[]" multiple>
                        <?php
                        if(!empty($destinations)){ ?>
                            <?php
                            foreach ($destinations as $destination_it){
                                if($destination_it->id != $id){
                                $selected = '';
                            ?>
                            <option value="{{$destination_it->id}}" {{$selected}}>{{$destination_it->name}}</option>
                        <?php }}}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'related_destinations'])
                </div>

                <div class="form-group col-md-12{{ $errors->has('brief') ? ' has-error' : '' }}">
                    <label for="brief" class="control-label">Brief:</label>

                    <textarea name="brief" id="brief" class="form-control" ><?php echo old('brief', $brief); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'brief'])
                </div>

				<div class="clearfix"></div>
                <div class="form-group col-md-12{{ $errors->has('description') ? ' has-error' : '' }}">
                	<label for="description" class="control-label">Description:</label>

                	<textarea name="description" id="description" class="form-control ckeditor" ><?php echo old('description', $description); ?></textarea>    

                	@include('snippets.errors_first', ['param' => 'description'])
                </div>

                <div class="form-group col-md-12{{$errors->has('video_link')?' has-error':''}}">
                    <label for="sort_order" class="control-label">Video Link (Youtube,Vimeo):</label>
                    <textarea name="video_link" id="video_link" class="form-control" rows="1" >{{ old('video_link',$video_link) }}</textarea>
                    <small><i>Note: Put "?rel=0" for play single video only. e.g. src="https://www.youtube.com/embed/qEEFfGUenLQ?rel=0"</i></small>
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Sort Order:</label>

                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>

                <div class="form-group col-md-6{{$errors->has('best_months')?' has-error':''}}">
                    <label for="best_months" class="control-label">Best months to visit:</label>
                    <input id="chkall" type="checkbox" > Select All
                    <select class="form-control select2" name="best_months[]" id="best_months" multiple>
                        <?php
                        $monthsArr = config('custom.months_arr');
                        if(!empty($monthsArr)){ ?>
                            <?php
                            foreach ($monthsArr as $month_id=>$month){
                                $selected = '';
                                if(in_array($month_id,$best_months)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$month_id}}" {{$selected}}>{{$month}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'best_months'])
                </div>
                <div style="clear: both;"></div>

                <div class="clearfix"></div>
                <div class="form-group col-md-6{{ $errors->has('latitude') ? ' has-error' : '' }}">
                    <label for="latitude" class="control-label">Latitude :</label>

                    <input type="text" name="latitude" id="latitude" class="form-control" value="{{ old('latitude',$latitude) }}">  
                    @include('snippets.errors_first', ['param' => 'latitude'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('longtitude') ? ' has-error' : '' }}">
                    <label for="longtitude" class="control-label">Longtitude :</label>

                    <input type="text" name="longtitude" id="longtitude" class="form-control" value="{{ old('longtitude',$longtitude) }}">  
                    @include('snippets.errors_first', ['param' => 'longtitude'])
                </div>
                <div class="clearfix"></div>
                <?php
                /*$image_required = $image_req;
                if($id > 0){
                    $image_required = '';
                }
                ?>
        
                <div class="col-md-12">
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

                    <!-- <div class="col-md-6">
                        <div class="form-group{{ $errors->has('feature_image') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label {{ $image_required }}">Feature Image:</label>
                            <input type="file" id="feature_image" name="feature_image"/>
                            @include('snippets.errors_first', ['param' => 'feature_image'])
                        </div>
                        <?php
                        //if(!empty($feature_image)){
                            //if($storage->exists($path.$feature_image))
                            //{
                                //?>
                                <div class="col-md-2 image_box">
                                    <img src="{{ url('storage/'.$path.'thumb/'.$feature_image) }}" style="width: 100px;"><br>
                                    <a href="javascript:void(0)" data-id="{{ $id }}" data='feature_image' class="del_image">Delete</a>
                                </div>
                                <?php        
                            //}
                        ?>
                        <?php
                        //}
                        ?>
                        <input type="hidden" name="old_feature_image" value="{{ $old_feature_image }}">
                    </div> -->

                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('banner_image') ? ' has-error' : '' }}">
                            <label for="sort_order" class="control-label {{ $image_required }}">Banner Image:</label>
                            <input type="file" id="banner_image" name="banner_image"/>
                            @include('snippets.errors_first', ['param' => 'banner_image'])
                        </div>
                        <?php
                        if(!empty($banner_image)){
                            if($storage->exists($path.$banner_image))
                            {
                                ?>
                                <div class="col-md-2 image_box">
                                    <img src="{{ url('storage/'.$path.'thumb/'.$banner_image) }}" style="width: 100px;"><br>
                                    <a href="javascript:void(0)" data-id="{{ $id }}" data='banner_image' class="del_image">Delete</a>
                                </div>
                                <?php        
                            }
                            ?>
                            <?php
                        }
                        ?>
                        <input type="hidden" name="old_banner_image" value="{{ $old_banner_image }}">
                    </div>
                </div>

                <hr>
				<div style="display: none;" class="col-md-12">
                <h3>SEO:</h3>
				</div>
				
                <div style="display:none;" class="form-group col-md-12{{ $errors->has('meta_title') ? ' has-error' : '' }}">
                	<label for="meta_title" class="control-label">Meta Title:</label>

                	<input type="text" name="meta_title" value="{{ old('meta_title', $meta_title)}}" id="meta_title" class="form-control"  />    

                	@include('snippets.errors_first', ['param' => 'meta_title'])
                </div>

                <div style="display:none;" class="form-group col-md-12{{ $errors->has('meta_keyword') ? ' has-error' : '' }}">
                	<label for="meta_keyword" class="control-label" maxlength="688" >Meta Keyword:</label>

                	<input type="text" name="meta_keyword" value="{{ old('meta_keyword', $meta_keyword)}}" id="meta_keyword" class="form-control"  />    

                	@include('snippets.errors_first', ['param' => 'meta_keyword'])
                </div>

                <div style="display:none;" class="form-group col-md-12{{ $errors->has('meta_description') ? ' has-error' : '' }}">
                	<label for="meta_description" class="control-label">Meta Description:</label>

                	<textarea name="meta_description" id="meta_description"  class="form-control" >{{ old('meta_description', $meta_description) }}</textarea>    

                	@include('snippets.errors_first', ['param' => 'meta_description'])
                </div>*/ ?>

                <div class="form-group col-md-6{{$errors->has('related_destinations')?' has-error':''}}">
                    <label for="related_destination" class="control-label">Destination Flag :</label>
                    <select class="form-control select2" name="destination_flags[]" multiple>
                        <?php
                            if(!empty($flags)){ ?>
                            <option value="">Select</option>
                            <?php
                            foreach ($flags as $flag){
                                $selected = '';
                                if(in_array($flag->id,$destination_flag_arr)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$flag->id}}" {{$selected}}>{{$flag->name}}</option>
                        <?php } } ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'destination_flags'])
                </div>

               
                <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }} col-md-2">
                    <label class="control-label ">Featured:</label>

                    <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />

                    @include('snippets.errors_first', ['param' => 'featured'])
                </div>
			   
               <?php /* <div class="form-group{{ $errors->has('is_international') ? ' has-error' : '' }} col-md-2">
                    <label class="control-label ">International:</label>

                    <input type="checkbox" name="is_international" value="1" <?php echo ($is_international == '1')?'checked':''; ?> />

                    @include('snippets.errors_first', ['param' => 'is_international'])
                </div> */ ?>
                <div class="clearfix"></div>

                <div class="form-group{{ $errors->has('show') ? ' has-error' : '' }} col-md-12">
                    <label class="control-label required">Show:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="show" value="1" <?php echo ($show == '1')?'checked':''; ?> checked>
                    &nbsp;
                    Inactive: <input type="radio" name="show" value="0" <?php echo ( strlen($show) > 0 && $show == '0')?'checked':''; ?> >
                    
                    <div class="alert alert-warning" role="alert" style="margin-top: 12px;padding: 5px;margin-bottom: 0;"><b>Note :</b> <i class="fa fa-lightbulb-o fa-1x"></i> If "Show" is enabled, the destination will appear in frontend.</div>

                    @include('snippets.errors_first', ['param' => 'show'])
                </div>

                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                    <label class="control-label required">Status:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                    &nbsp;
                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                    @include('snippets.errors_first', ['param' => 'status'])
                </div>
                
            	
				 <div class="clearfix"></div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>

                    <a href="{{ route('admin.destinations.index') }}" class="btn_admin2" title="Cancel">Cancel</a>
                </div>
				<br/><br/>

            </form>
			</div>
        </div>       
 


@slot('bottomBlock')

    <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">
    var slug = '{{$slug}}';
    if(slug != ''){
      $('#slug').attr('readonly',true);
    }

    $("#EditDSlug").click(function(){
        $('#slug').attr('readonly',false);
    });
    </script>

    <script type="text/javascript">

        var description = document.getElementById('description');
        CKEDITOR.replace(description);

        $('.select2').select2({
            placeholder: "Please Select",
            allowClear: true
        });

        $("#chkall").click(function(){
            if($("#chkall").is(':checked')){
                $("#best_months > option").prop("selected", "selected");
                $("#best_months").trigger("change");
            } else {
                $("#best_months > option").removeAttr("selected");
                $("#best_months").trigger("change");
            }
        });

        $(document).ready(function(){
            $(".del_image").click(function(){
                var current_sel = $(this);
                var image_id = $(this).attr('data-id');
                var type = $(this).attr('data');
                //alert(type); return false;

                conf = confirm("Are you sure to Delete this Image?");
                if(conf){
                    var _token = '{{ csrf_token() }}';
                    $.ajax({
                        url: "{{ route($routeName.'.destinations.ajax_delete_image') }}",
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