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
            top: 26px;
            font-size: 15px;
            opacity: .7;
    </style>
    @endslot

<?php
$ACCOMMODATION_POLICIES =  CustomHelper::WebsiteSettings('ACCOMMODATION_POLICIES');
$BackUrl = (request()->has('back_url'))?request()->input('back_url'):'';
$category = (request()->has('category'))?request()->input('category'):'';
$routeName = CustomHelper::getAdminRouteName();

$user = auth()->user();
$is_vendor = $user?$user->is_vendor:'';

$id = (isset($accommodation->id))?$accommodation->id:'';
$name = (isset($accommodation->name))?$accommodation->name:'';
$vendor_id = (isset($accommodation->vendor_id))?$accommodation->vendor_id:'';
$destination_id = (isset($accommodation->destination_id))?$accommodation->destination_id:'';
$destination_id = old('destination',$destination_id);

$checkin_time = (isset($accommodation->checkin_time))?$accommodation->checkin_time:'';
$checkin_time = old('checkin_time',$checkin_time);

$checkout_time = (isset($accommodation->checkout_time))?$accommodation->checkout_time:'';
$checkout_time = old('checkout_time',$checkout_time);

$slug = (isset($accommodation->slug))?$accommodation->slug:'';
$accommodation_feature = (isset($accommodation->accommodation_feature))?json_decode($accommodation->accommodation_feature,true):[];
$accommodation_facility = (isset($accommodation->accommodation_facility))?json_decode($accommodation->accommodation_facility,true):[];
$category_id = (isset($accommodation->category_id))?$accommodation->category_id:0;
$star_classification = (isset($accommodation->star_classification))?$accommodation->star_classification:'';
$related_hotels = (isset($accommodation->related_hotels))?json_decode($accommodation->related_hotels,true):[];
$address = (isset($accommodation->address))?$accommodation->address:'';
$contact_phone = (isset($accommodation->contact_phone))?$accommodation->contact_phone:'';
$contact_email = (isset($accommodation->contact_email))?$accommodation->contact_email:'';
$rating = (isset($accommodation->rating))?$accommodation->rating:'';
$total_reviews = (isset($accommodation->total_reviews))?$accommodation->total_reviews:'';
$brief = (isset($accommodation->brief))?$accommodation->brief:'';
$description = (isset($accommodation->description))?$accommodation->description:'';
$policies_chk = (isset($accommodation->policies_chk))?$accommodation->policies_chk:'';
$policies = (isset($accommodation->policies))?$accommodation->policies:'';
$map = (isset($accommodation->map))?$accommodation->map:'';
$latitude = (isset($accommodation->latitude))?$accommodation->latitude:'';
$longtitude = (isset($accommodation->longtitude))?$accommodation->longtitude:'';
$sort_order = (isset($accommodation->sort_order))?$accommodation->sort_order:'';
$status = (isset($accommodation->status))?$accommodation->status:1;
$featured = (isset($accommodation->featured))?$accommodation->featured:'';
$meta_title = (isset($accommodation->meta_title))?$accommodation->meta_title:'';
$meta_description = (isset($accommodation->meta_description))?$accommodation->meta_description:'';
$contact_person = (isset($accommodation->contact_person))?$accommodation->contact_person:'';
$meta_keywords = (isset($accommodation->meta_keywords))?$accommodation->meta_keywords:'';
$total_room = (isset($accommodation->total_room))?$accommodation->total_room:'';
$AccommodationDefaultLocation = (isset($accommodation->AccommodationDefaultLocation))?$accommodation->AccommodationDefaultLocation:'';
// prd($AccommodationDefaultLocation);

$default_location_id = $AccommodationDefaultLocation ? $AccommodationDefaultLocation->destination_location_id : '';
$default_location_id = old('default_location_id',$default_location_id); 
$AccommodationLocation =  $accommodation->AccommodationLocation ?? '' ;
 
 $location_id = (request()->has('location_id'))?request()->input('location_id'):[];
 $location_id = old('location_id',$location_id);
 // pr($location_id);

 $default_location_name = $acc_location->DestinationLocation->name??'';
 $add_location = [];
 
    foreach ($selectDestinations as $key => $Destination) {
        if($Destination->id == $default_location_id){
            $default_location_name = $Destination->name ;
            $default_location_id = $Destination->id ;
        }

        if(in_array($Destination->id ,$location_id)){
            $add_location[]  = array(
                'id' =>$Destination->id ,
                'name' =>$Destination->name ,
             );
        }

    }

// pr($add_location);
// $image = (isset($accommodation->image))?$accommodation->image:'';
$tripTimeArr = config('custom.tripTimeArr');
$storage = Storage::disk('public');
$path = 'accommodations/';
// $old_image = $image;
// $image_req = '';
$link_req = '';
?>
<?php
$active_menu = "accommodations";
?>
@if(!empty($accommodation))
@include('admin.includes.accommodationoptionmenu')
@endif
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

                <div class="form-group col-md-3{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label required">Accommodation Name:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name',$name) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'name'])
                </div>

                <div class="form-group col-md-3{{ $errors->has('destination') ? ' has-error' : '' }}">
                    <label for="destination" class="control-label required">Destination:</label>
                    <select class="form-control" name="destination" id="destination_id">
                        <option value="">Select Destination</option>
                        
                        {!!CustomHelper::getDestinationOptions('', $destination_id)!!}
                        
                    </select>
                    @include('snippets.errors_first', ['param' => 'destination'])
                </div>

                <div class="form-group col-md-3{{ $errors->has('default_location_id') ? ' has-error' : '' }}">
                    <label for="location_id" class="control-label required">Place:</label>
                    <select class="form-control" name="default_location_id" id="location_id">


                <?php if($default_location_id && $default_location_name){ ?>
                    <option value="{{$default_location_id}}" selected>{{$default_location_name}}</option>
                <?php } ?>
                     
                    </select>
                    @include('snippets.errors_first', ['param' => 'default_location_id'])
                </div>

                <div class="form-group col-md-3{{ $errors->has('location_id') ? ' has-error' : '' }}">
                    <label for="location_id" class="control-label">Additional Places:</label>
                    <select class="form-control select2" name="location_id[]" id="add_location_id" multiple>
                       <?php if($add_location){ ?>
                           @foreach($add_location as $acc_location)
                           <option value="{{$acc_location['id']}}" selected>{{$acc_location['name']??''}}</option>
                           @endforeach

                     <?php }else{ ?>
                        @if($accommodation && $accommodation->AccommodationLocation)
                        @foreach($accommodation->AccommodationLocation as $acc_location)
                        <option value="{{$acc_location->destination_location_id}}" selected>{{$acc_location->DestinationLocation->name??''}}</option>
                        @endforeach
                        @endif
                    <?php } ?>  
                    </select>
                    @include('snippets.errors_first', ['param' => 'location_id'])
                </div>                
                <div class="clearfix"></div>

                <?php
                if(!empty($accommodation->id)){
                    ?>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }} slug">
                            <label for="link_name" class="control-label required">Slug:</label>

                            <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $slug) }}" />

                            <a href="javascript:void(0);" class="slugEdit" id="EditASlug" title="Edit"><i class="fas fa-edit"></i></a>

                            @include('snippets.errors_first', ['param' => 'slug'])
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group col-md-3{{ $errors->has('category_id') ? ' has-error' : '' }}">
                    <label for="category_id" class="control-label required">Accommodation Category:</label>
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

                <div class="form-group col-md-2{{ $errors->has('star_classification') ? ' has-error' : '' }}" style="padding-right: 5px;">
                    <label for="star_classification" class="control-label required">Hotel Star Classification:</label>
                    <select class="form-control" name="star_classification">
                        <option value="">Select</option>
                        <?php for($i=1;$i<=5;$i++) { ?>
                        <option value="{{$i}}" <?php echo ($i == old('star_classification',$star_classification)) ? "selected":"" ?> >{{$i.' Star'}}</option>  
                        <?php } ?>
                    </select>
                   
                      @include('snippets.errors_first', ['param' => 'star_classification'])
                </div>

                <div class="col-md-4">
                    <div class="row">
                        <div class="form-group col-md-6{{ $errors->has('rating') ? ' has-error' : '' }}" style="padding-right: 5px;">
                            <label for="rating" class="control-label ">Traveller Rating:</label>
                            <input type="text" id="rating" class="form-control" name="rating" value="{{ old('rating', $rating) }}" />
                            <?php /*<select class="form-control" name="rating" value="{{$rating}}">
                                    <option value="">Select</option>
                                    <?php for($i=1;$i<=5;$i++) { ?>
                                <option value="{{$i}}" <?php echo ($i == old('rating',$rating)) ? "selected":"" ?> > {{$i}}</option>  
                                <?php } ?>
                            </select>*/ ?>
                            @include('snippets.errors_first', ['param' => 'rating'])
                        </div>

                        <div class="form-group col-md-6{{ $errors->has('total_reviews') ? ' has-error' : '' }}">
                            <label for="total_reviews" class="control-label ">Total Traveller Reviews:</label>
                            <input type="text" id="total_reviews" class="form-control" name="total_reviews" value="{{ old('total_reviews', $total_reviews) }}" />
                            @include('snippets.errors_first', ['param' => 'total_reviews'])
                        </div>                        
                    </div>
                </div>

                <div class="form-group col-md-6{{ $errors->has('accommodation_facility') ? ' has-error' : '' }}">
                    <label for="accommodation_facility" class="control-label">Accommodation Facilities:</label>
                    <select class="form-control select2" name="accommodation_facility[]" multiple>
                         <?php
                         $accommodation_facility = old('accommodation_facility',$accommodation_facility);
                        if(!empty($facilities)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($facilities as $facility_query){

                                $selected = '';
                                if(!empty($accommodation_facility) && (count($accommodation_facility) > 0)){
                                if(in_array($facility_query->id,$accommodation_facility)){
                                    $selected = 'selected';
                                }}
                            ?>
                            <option value="{{$facility_query->id}}" {{$selected}}>{{$facility_query->title}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'accommodation_facility'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('accommodation_feature') ? ' has-error' : '' }}">
                    <label for="accommodation_feature" class="control-label">Accommodation Features & Amenities:</label>
                    <select class="form-control select2" name="accommodation_feature[]" multiple>
                         <?php
                         $accommodation_feature = old('accommodation_feature',$accommodation_feature);
                        if(!empty($features)){ ?>
                            <?php
                            foreach ($features as $feature){
                                $selected = '';
                                if(!empty($accommodation_feature) && (count($accommodation_feature) > 0)){
                                if(in_array($feature->id,$accommodation_feature)){
                                    $selected = 'selected';
                                }}
                            ?>
                            <option value="{{$feature->id}}" {{$selected}}>{{$feature->title}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'accommodation_feature'])
                </div>
                
                <div class="form-group col-md-6{{ $errors->has('total_room') ? ' has-error' : '' }}">
                    <label for="total_room" class="control-label ">Total Rooms:</label>
                    <input type="number" name="total_room" id="total_room" class="form-control" value="{{ old('total_room',$total_room) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'total_room'])
                </div>
                <?php /* if($is_vendor != 1){ ?>
                    <div class="form-group col-md-3{{ $errors->has('vendor_id') ? ' has-error' : '' }}">
                        <label for="vendor_id" class="control-label">Vendor:</label>
                        <select class="form-control select2" name="vendor_id">
                            <option value="">Select</option>
                            <?php foreach ($vendors as $vendor) { ?>
                                <option value="{{$vendor->id}}" {{($vendor->id==$vendor_id)?'selected':''}}>{{$vendor->name}}</option>
                            <?php } ?>
                        </select>
                        @include('snippets.errors_first', ['param' => 'vendor_id'])
                    </div>
                <?php } */?>
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
                                if(!empty($related_hotels) && (count($related_hotels) > 0)){
                                if(in_array($accommodation->id,$related_hotels)){
                                    $selected = 'selected';
                                }}
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
				
                <div class="clearfix"></div>
                <div class="form-group  col-md-12{{ $errors->has('policies') ? ' has-error' : '' }}">
                	<label for="policies" class="control-label">Policies:</label>

                    <input type="checkbox" id="policies_chk" name="policies_chk"  value="1" <?php echo ($policies_chk == 1)?'checked':''; ?> />
                    <strong> Copy default policies from settings</strong>

                	<textarea name="policies" id="policies" class="form-control ckeditor" ><?php echo old('policies', $policies); ?></textarea>    
                	@include('snippets.errors_first', ['param' => 'policies'])
                </div>

                <div class="form-group col-md-12{{ $errors->has('map') ? ' has-error' : '' }}">
                    <label for="map" class="control-label ">Map (Embeded Code): <span style="font-weight: 300; color: #a3650b;"><strong>Note:</strong> In google map search for a location. Click on share and then click on "Embed a map". Copy the HTML code and paste here.</span></label> 

                    <textarea name="map" id="map" class="form-control" ><?php echo old('map', $map); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'map'])
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

                <div class="form-group col-md-12{{ $errors->has('address') ? ' has-error' : '' }}">
                    <label for="address" class="control-label ">Address:</label>

                    <textarea name="address" id="address" class="form-control" ><?php echo old('address', $address); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'address'])
                </div>

                <div class="form-group col-md-3{{ $errors->has('contact_person') ? ' has-error' : '' }}">
                    <label for="contact_person" class="control-label ">Contact Person:</label>
                    <input type="text" name="contact_person" id="contact_person" class="form-control" value="{{ old('contact_person',$contact_person) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'contact_person'])
                </div>

                <div class="form-group col-md-3{{ $errors->has('contact_phone') ? ' has-error' : '' }}">
                    <label for="contact_phone" class="control-label ">Contact Phone:</label>
                    <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone',$contact_phone) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'contact_phone'])
                </div>

                <div class="form-group col-md-3{{ $errors->has('contact_email') ? ' has-error' : '' }}">
                    <label for="contact_email" class="control-label ">Contact Email:</label>
                    <input type="text" name="contact_email" id="contact_email" class="form-control" value="{{ old('contact_email',$contact_email) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'contact_email'])
                </div>


                <div class="form-group col-md-3{{ $errors->has('checkin_time') ? ' has-error' : '' }}">
                    <label for="checkin_time" class="control-label">Check IN Time: </label>
                    <select class="form-control" name="checkin_time">
                    <option value="">Select</option>
                    <?php foreach ($tripTimeArr as $timekey => $tripTime) { ?>
                        <option value="{{$timekey}}" <?php if(date('H:i',strtotime($checkin_time)) == $timekey){ echo 'selected'; } ?>>{{$tripTime}}</option>
                    <?php } ?>
                </select>
                 @include('snippets.errors_first', ['param' => 'checkin_time'])
                </div> 
                <div class="form-group col-md-3{{ $errors->has('checkout_time') ? ' has-error' : '' }}">
                    <label for="checkout_time" class="control-label">Check OUT Time:</label>
                    <select class="form-control" name="checkout_time">
                     <option value="">Select</option>
                     <?php foreach ($tripTimeArr as $timekey => $tripTime) { ?>
                        <option value="{{$timekey}}" <?php if(date('H:i',strtotime($checkout_time)) == $timekey){ echo 'selected'; } ?>>{{$tripTime}}</option>
                    <?php } ?>
                </select>
                @include('snippets.errors_first', ['param' => 'checkout_time'])
                </div>

                <div class="form-group  col-md-6{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Sort Order:</label>

                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>

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
                </div>

                <hr>
				<div style="display:none;" class="col-md-12">
                <h3>SEO:</h3>
				</div>
				
                <div style="display:none;" class="form-group col-md-4{{ $errors->has('meta_title') ? ' has-error' : '' }}">
                	<label for="meta_title" class="control-label">Meta Title:</label>

                	<input type="text" name="meta_title" value="{{ old('meta_title', $meta_title)}}" id="meta_title" class="form-control"  />    

                	@include('snippets.errors_first', ['param' => 'meta_title'])
                </div>

                <div style="display:none;" class="form-group col-md-4{{ $errors->has('meta_description') ? ' has-error' : '' }}">
                	<label for="meta_description" class="control-label">Meta Description:</label>

                	<textarea name="meta_description" id="meta_description"  class="form-control" >{{ old('meta_description', $meta_description) }}</textarea>    

                	@include('snippets.errors_first', ['param' => 'meta_description'])
                </div> */ ?>


                <?php 
                /* <hr><div  class="col-md-12">
                <h3>Add SEO Meta:</h3>
                </div>
                
                <div  class="form-group col-md-12 {{$errors->has('meta_title')?' has-error':''}}">
                    <label for="meta_title" class="control-label">Page Title</label>

                    <input type="text" name="meta_title" value="{{ old('meta_title', $meta_title)}}" id="meta_title" class="form-control"  />    

                    @include('snippets.errors_first', ['param' => 'meta_title'])
                </div>

                <div class="form-group col-md-12{{$errors->has('meta_description')?' has-error':''}}">
                    <label for="meta_description" class="control-label">Page Description :</label>

                    <textarea name="meta_description" id="meta_description" class="form-control" ><?php echo old('meta_description', $meta_description); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'meta_description'])
                </div>

                <div class="form-group col-md-12{{$errors->has('meta_keywords')?' has-error':''}}">
                    <label for="meta_keywords" class="control-label">Page Keywords</label>

                    <textarea name="meta_keywords" id="brief" class="form-control" ><?php echo old('meta_keywords', $meta_keywords); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'meta_keywords'])
                </div> */
                ?>


                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-3">
                    <label class="control-label required">Status:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?>>
                    &nbsp;
                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                    @include('snippets.errors_first', ['param' => 'status'])
                </div>

                <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }} col-md-3">
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


    <div id="addTask" class="modal fade" role="dialog" modal-ref="">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" >
                    <div class="modal_alert_msg"></div>
                    <div class="form-group mb-3" style="text-align: center;">
                    <input type="hidden" name="product_id" value="">
                    <button type="button" class="btn btn-success save_task">Overwrite</button>
                    <button type="button" class="btn btn-primary close_task" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


@slot('bottomBlock')

    <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">

    $("#policies_chk").change( function()
        {
            if($('#policies_chk').is(':checked')) 
            {
                $("#addTask .modal-title").text("Are you sure, you want to overwrite existing policies with default policies from settings?");
                $("#addTask").modal("show");
                $("#addTask").attr('modal-ref', 'policies_chk');
            } else {
                CKEDITOR.instances['policies'].setData('');
            }
            $(document).on("click", "[modal-ref=policies_chk] .save_task", function () {
                    CKEDITOR.instances['policies'].setData(` {!! $ACCOMMODATION_POLICIES!!}`);
                    $("#addTask").modal("hide");
                    $("#addTask").attr('modal-ref', '');
            });

            $(document).on("click", "[modal-ref=policies_chk] .close_task", function () {
                CKEDITOR.instances['policies'].setData('');
                $("#addTask").modal("hide");
                $("#addTask").attr('modal-ref', '');
                $("#policies_chk").prop('checked', false);
            });
       });

    var slug = '{{$slug}}';
    if(slug != ''){
      $('#slug').attr('readonly',true);
    }

    $("#EditASlug").click(function(){
        $('#slug').attr('readonly',false);
    });
    </script>

    <script type="text/javascript">

        var description = document.getElementById('description');
        CKEDITOR.replace(description);
        
        var policies = document.getElementById('policies');
        CKEDITOR.replace(policies);

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

        $('#destination_id').on('change',function(){
            $('#location_id').empty();
            $('#add_location_id').empty();
        });

        $('#location_id').select2({
            ajax: {
                url: "{{ route($routeName.'.destinations.ajax_locations') }}",
                type: "POST",
                data: function (params) {
                   return {
                      term: params.term, // search term,
                      destination_id: $('#destination_id').val()
                   };
                },
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                processResults: function (resp) {
                    return {
                        results:  resp.items
                    };
                }
            },
        });

  $('#add_location_id').select2({
            ajax: {
                url: "{{ route($routeName.'.destinations.ajax_locations') }}",
                type: "POST",
                data: function (params) {
                   return {
                      term: params.term, // search term,
                      destination_id: $('#destination_id').val()
                   };
                },
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                processResults: function (resp) {
                    return {
                        results:  resp.items
                    };
                }
            },
        });
    </script>
@endslot
@endcomponent