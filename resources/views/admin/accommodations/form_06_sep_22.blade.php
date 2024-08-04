@component('admin.layouts.main')

    @slot('title')
        Admin - Accommodation - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endslot

<?php
//prd($accommodation);
$BackUrl = (request()->has('back_url'))?request()->input('back_url'):'';
$routeName = CustomHelper::getAdminRouteName();

$id = (isset($accommodation->id))?$accommodation->id:'';
$name = (isset($accommodation->name))?$accommodation->name:'';
$destination_id = (isset($accommodation->destination_id))?$accommodation->destination_id:'';
$slug = (isset($accommodation->slug))?$accommodation->slug:'';
$accommodation_feature = (isset($accommodation->accommodation_feature))?json_decode($accommodation->accommodation_feature,true):[];
$accommodation_facility = (isset($accommodation->accommodation_facility))?json_decode($accommodation->accommodation_facility,true):[];
$category_id = (isset($accommodation->category_id))?$accommodation->category_id:'';
$star_classification = (isset($accommodation->star_classification))?$accommodation->star_classification:0;
$related_hotels = (isset($accommodation->related_hotels))?json_decode($accommodation->related_hotels,true):[];
$address = (isset($accommodation->address))?$accommodation->address:'';
$contact_phone = (isset($accommodation->contact_phone))?$accommodation->contact_phone:'';
$contact_email = (isset($accommodation->contact_email))?$accommodation->contact_email:'';
$rating = (isset($accommodation->rating))?$accommodation->rating:0;
$brief = (isset($accommodation->brief))?$accommodation->brief:'';
$description = (isset($accommodation->description))?$accommodation->description:'';
$map = (isset($accommodation->map))?$accommodation->map:'';
$latitude = (isset($accommodation->latitude))?$accommodation->latitude:'';
$longtitude = (isset($accommodation->longtitude))?$accommodation->longtitude:'';
$sort_order = (isset($accommodation->sort_order))?$accommodation->sort_order:'';
$status = (isset($accommodation->status))?$accommodation->status:'';
$featured = (isset($accommodation->featured))?$accommodation->featured:'';
$meta_title = (isset($accommodation->meta_title))?$accommodation->meta_title:'';
$meta_description = (isset($accommodation->meta_description))?$accommodation->meta_description:'';
$image = (isset($accommodation->image))?$accommodation->image:'';

$storage = Storage::disk('public');
$path = 'accommodations/';

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

                <div class="form-group col-md-6{{ $errors->has('destination') ? ' has-error' : '' }}">
                    <label for="destination" class="control-label required">Place:</label>
                    <select class="form-control" name="destination">
                        <?php
                        $destination_id = old('destination',$destination_id);

                        if(!empty($destinations)){ ?>
                            <option value="">Select Destination</option>
                            
                            <?php
                            foreach ($destinations as $destination_it){
                                $selected = '';
                                if($destination_it->id == $destination_id){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$destination_it->id}}" {{$selected}}>{{$destination_it->name}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'destination'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label required">Accommodation Name:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name',$name) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'name'])
                </div>

                <div style="display:none;" class="form-group col-md-6 {{ $errors->has('slug') ? ' has-error' : '' }}">
                    <label for="title" class="control-label required">Slug :</label>
                    <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug',$slug) }}" />
                    @include('snippets.errors_first', ['param' => 'slug'])
                </div>
                <?php 
                //prd($errors);
                ?>

                <div class="form-group col-md-6{{ $errors->has('accommodation_feature') ? ' has-error' : '' }}">
                    <label for="accommodation_feature" class="control-label">Accommodation Features:</label>
                    <select class="form-control select2" name="accommodation_feature[]" multiple>
                         <?php
                         $accommodation_feature = old('accommodation_feature',$accommodation_feature);
                        if(!empty($features)){ ?>
                            <?php
                            foreach ($features as $feature){
                                $selected = '';
                                if(in_array($feature->id,$accommodation_feature)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$feature->id}}" {{$selected}}>{{$feature->title}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'accommodation_feature'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('accommodation_facility') ? ' has-error' : '' }}">
                    <label for="accommodation_facility" class="control-label">Accommodation Facility:</label>
                    <select class="form-control select2" name="accommodation_facility[]" multiple>
                         <?php
                         $accommodation_facility = old('accommodation_facility',$accommodation_facility);
                        if(!empty($facilities)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($facilities as $facility_query){

                                $selected = '';
                                if(in_array($facility_query->id,$accommodation_facility)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$facility_query->id}}" {{$selected}}>{{$facility_query->title}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'accommodation_facility'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('category_id') ? ' has-error' : '' }}">
                    <label for="category_id" class="control-label">Accommodation Category:</label>
                    <select class="form-control" name="category_id">
                         <?php
                        $category_id = old('category_id',$category_id);

                        if(!empty($categories)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($categories as $category){

                                $selected = '';
                                if($category->id == $category_id){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$category->id}}" {{$selected}}>{{$category->title}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'category_id'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('star_classification') ? ' has-error' : '' }}">
                    <label for="star_classification" class="control-label required">Star Classification:</label>
                    <select class="form-control" name="star_classification">
                        <option value="">Select</option>
                        <?php
                        $selected = '';
                        for($i = 1; $i<=7; $i++){
                        if($star_classification == $i){
                            $selected = 'selected';

                        }
                        ?>
                        <option value="{{$i}}" {{$selected}}>{{$i.' Star'}}</option>
                        <?php
                        }
                        ?>
                    </select>
                   
                      @include('snippets.errors_first', ['param' => 'star_classification'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('related_hotels') ? ' has-error' : '' }}">
                    <label for="related_hotels" class="control-label">Related Hotels:</label>
                    <select class="form-control select2" name="related_hotels[]" multiple>
                         <?php
                         $related_hotels = old("related_hotels",$related_hotels);
                        if(!empty($accommodations)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                           // prd($accommodations);
                            foreach ($accommodations as $accommodation){
                                if($accommodation->id != $id){

                                $selected = '';
                                if(in_array($accommodation->id,$related_hotels)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$accommodation->id}}" {{$selected}}>{{$accommodation->name}}</option>
                        <?php }}}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'related_hotels'])
                </div>

                <div class="form-group col-md-12{{ $errors->has('brief') ? ' has-error' : '' }}">
                    <label for="brief" class="control-label">Brief:</label>

                    <textarea name="brief" id="brief" class="form-control" ><?php echo old('brief', $brief); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'brief'])
                </div>

				<div class="clearfix"></div>
                <div class="form-group  col-md-12{{ $errors->has('description') ? ' has-error' : '' }}">
                	<label for="description" class="control-label">Description:</label>

                	<textarea name="description" id="description" class="form-control ckeditor" ><?php echo old('description', $description); ?></textarea>    

                	@include('snippets.errors_first', ['param' => 'description'])
                </div>

                <div class="form-group col-md-12{{ $errors->has('address') ? ' has-error' : '' }}">
                    <label for="address" class="control-label ">Address:</label>

                    <textarea name="address" id="address" class="form-control" ><?php echo old('address', $address); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'address'])
                </div>

                <div class="form-group col-md-12{{ $errors->has('map') ? ' has-error' : '' }}">
                    <label for="map" class="control-label ">Map(Embeded Code):</label>

                    <textarea name="map" id="map" class="form-control" ><?php echo old('map', $map); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'map'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('contact_phone') ? ' has-error' : '' }}">
                    <label for="contact_phone" class="control-label ">Contact Phone:</label>
                    <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone',$contact_phone) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'contact_phone'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('contact_email') ? ' has-error' : '' }}">
                    <label for="contact_email" class="control-label ">Contact Email:</label>
                    <input type="text" name="contact_email" id="contact_email" class="form-control" value="{{ old('contact_email',$contact_email) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'contact_email'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('rating') ? ' has-error' : '' }}">
                    <label for="rating" class="control-label ">Rating:</label>
                    <select class="form-control" name="rating">
                            <option value="">Select</option>
                            <?php
                            
                                $selected = '';
                                for($i = 1; $i<=5; $i++){
                                    if($rating == $i){
                                    $selected = 'selected';
                                }
                                
                                ?>
                                <option value="{{$i}}" {{$selected}}>{{$i}}</option>
                                <?php 
                            }
                        
                        ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'rating'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Sort Order:</label>

                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('latitude') ? ' has-error' : '' }}">
                    <label for="latitude" class="control-label ">Latitude :</label>

                    <input type="text" name="latitude" id="latitude" class="form-control" value="{{ old('latitude',$latitude) }}">  
                    @include('snippets.errors_first', ['param' => 'latitude'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('longtitude') ? ' has-error' : '' }}">
                    <label for="longtitude" class="control-label ">Longtitude :</label>

                    <input type="text" name="longtitude" id="longtitude" class="form-control" value="{{ old('longtitude',$longtitude) }}">  
                    @include('snippets.errors_first', ['param' => 'longtitude'])
                </div>

                <?php
                $image_required = $image_req;
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
                </div>

                <hr>
				<div class="col-md-12">
                <h3>SEO:</h3>
				</div>
				
                <div class="form-group col-md-4{{ $errors->has('meta_title') ? ' has-error' : '' }}">
                	<label for="meta_title" class="control-label">Meta Title:</label>

                	<input type="text" name="meta_title" value="{{ old('meta_title', $meta_title)}}" id="meta_title" class="form-control"  />    

                	@include('snippets.errors_first', ['param' => 'meta_title'])
                </div>

                <div class="form-group col-md-4{{ $errors->has('meta_description') ? ' has-error' : '' }}">
                	<label for="meta_description" class="control-label">Meta Description:</label>

                	<textarea name="meta_description" id="meta_description"  class="form-control" >{{ old('meta_description', $meta_description) }}</textarea>    

                	@include('snippets.errors_first', ['param' => 'meta_description'])
                </div>

                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                    <label class="control-label">Status:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                    &nbsp;
                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                    @include('snippets.errors_first', ['param' => 'status'])
                </div>

                <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }} col-md-12">
                    <label class="control-label ">Featured:</label>

                    <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />

                    @include('snippets.errors_first', ['param' => 'featured'])
                </div>
				
				 <div class="clearfix"></div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>

                    <a href="{{ route('admin.accommodations.index') }}" class="btn_admin2" title="Cancel">Cancel</a>
                </div>
				<br/><br/>

            </form>
			</div>
        </div>       
 
    </div>

@slot('bottomBlock')

    <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">

        var description = document.getElementById('description');
        CKEDITOR.replace(description);

        $('.select2').select2({
            placeholder: "Please Select",
            allowClear: true
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
                        url: "{{ route($routeName.'.accommodations.ajax_delete_image') }}",
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