@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }}(Accommodation) - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
       
    @endslot
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<?php
//pr($page);
$routeName = CustomHelper::getAdminRouteName();

//$accommodation_id = (isset($accommodation_room->accommodation_id))?$accommodation_room->accommodation_id:0;
$brief = (isset($accommodation_room->brief))?$accommodation_room->brief:'';
$description = (isset($accommodation_room->description))?$accommodation_room->description:'';
$quantity = (isset($accommodation_room->quantity))?$accommodation_room->quantity:'';
$max_quantity = (isset($accommodation_room->max_quantity))?$accommodation_room->max_quantity:'';
$minimum_stay = (isset($accommodation_room->minimum_stay))?$accommodation_room->minimum_stay:'';
$no_of_exrta_beds = (isset($accommodation_room->no_of_exrta_beds))?$accommodation_room->no_of_exrta_beds:'';
$max_adults = (isset($accommodation_room->max_adults))?$accommodation_room->max_adults:'';
$max_children = (isset($accommodation_room->max_children))?$accommodation_room->max_children:'';
$room_features = (isset($accommodation_room->room_features))?json_decode($accommodation_room->room_features,true):[];
$sort_order = (isset($accommodation_room->sort_order))?$accommodation_room->sort_order:0;
$status = (isset($accommodation_room->status))?$accommodation_room->status:'';
$video_link = (isset($accommodation_room->video_link))?$accommodation_room->video_link:'';
//new fields
$room_type_name = (isset($accommodation_room->room_type_name))?$accommodation_room->room_type_name:'';
$room_type_id = (isset($accommodation_room->room_type_id))?$accommodation_room->room_type_id:'';
$total_room = (isset($accommodation_room->total_room))?$accommodation_room->total_room:'';

$room_view = (isset($accommodation_room->room_view))?$accommodation_room->room_view:'';
$bed_type = (isset($accommodation_room->bed_type))?$accommodation_room->bed_type:'';
$extra_bed_type = (isset($accommodation_room->extra_bed_type))?$accommodation_room->extra_bed_type:'';

$single_bed = (isset($accommodation_room->single_bed))?$accommodation_room->single_bed:'';
$double_bed = (isset($accommodation_room->double_bed))?$accommodation_room->double_bed:'';
$extra_bed = (isset($accommodation_room->extra_bed))?$accommodation_room->extra_bed:'';

$base_occupancy = (isset($accommodation_room->base_occupancy))?$accommodation_room->base_occupancy:'';

$base_price = (isset($accommodation_room->base_price))?$accommodation_room->base_price:'';
$publish_price = (isset($accommodation_room->publish_price))?$accommodation_room->publish_price:'';
$single_price = (isset($accommodation_room->single_price))?$accommodation_room->single_price:'';

$max_adult_bed = (isset($accommodation_room->max_adult_bed))?$accommodation_room->max_adult_bed:'';
$base_child_no = (isset($accommodation_room->base_child_no))?$accommodation_room->base_child_no:'';
$max_child_no = (isset($accommodation_room->max_child_no))?$accommodation_room->max_child_no:'';

$price_extra_adult = (isset($accommodation_room->price_extra_adult))?$accommodation_room->price_extra_adult:'';
$extra_child_bed = (isset($accommodation_room->extra_child_bed))?$accommodation_room->extra_child_bed:'';
$price_extra_child_1 = (isset($accommodation_room->price_extra_child_1))?$accommodation_room->price_extra_child_1:'';
$price_extra_child_2 = (isset($accommodation_room->price_extra_child_2))?$accommodation_room->price_extra_child_2:'';
$max_occupancy = (isset($accommodation_room->max_occupancy))?$accommodation_room->max_occupancy:'';

$is_default = (isset($accommodation_room) && !empty($accommodation_room))?$accommodation_room->is_default:0;

?>
<?php
$active_menu = "accommodations".$accommodation_id.'/accommodation-room';
?>
@if(!empty($accommodation_id))
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
			<div class="bgcolor">
            <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}

                <div class="form-group col-md-6{{ $errors->has('room_type_name') ? ' has-error' : '' }}">
                    <label for="room_type_name" class="control-label required">Display name:</label>
                    <input type="text" name="room_type_name" id="room_type_name" class="form-control" value="{{ old('room_type_name',$room_type_name) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'room_type_name'])
                </div>

                 <div class="form-group col-md-3{{ $errors->has('room_type_id') ? ' has-error' : '' }}">
                    <label for="room_type_id" class="control-label required">Room Type:</label>
                    <select class="form-control" name="room_type_id">
                        <?php
                        $room_type_id = old('room_type_id',$room_type_id);
                        if(!empty($room_types)){
                            ?>
                            <option value="">Select</option>
                            <?php
                            foreach ($room_types as $room_typ){
                                $selected = '';
                                if($room_typ->id == $room_type_id){
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="{{$room_typ->id}}" {{$selected}}>{{$room_typ->title}}</option>
                                <?php 
                            }
                        }
                        ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'room_type_id'])
                </div>

                <div class="form-group col-md-3{{ $errors->has('total_room') ? ' has-error' : '' }}">
                    <label for="single_bed" class="control-label">Total Rooms:</label>
                    <input type="text" name="total_room" id="total_room" class="form-control" value="{{ old('total_room',$total_room) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'total_room'])
                </div>


                <div class="form-group col-md-3{{ $errors->has('room_features') ? ' has-error' : '' }}">
                    <label for="room_features" class="control-label">Room Features & Amenities:</label>
                    <select class="form-control select2" name="room_features[]" multiple>
                         <?php
                        if(!empty($features)){ ?>
                            <?php
                            foreach ($features as $feature){
                                $selected = '';
                                if(is_array($room_features) && in_array($feature->id,$room_features)) {
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$feature->id}}" {{$selected}}>{{$feature->title}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'room_features'])
                </div>

                  <div class="form-group col-md-3{{ $errors->has('room_view') ? ' has-error' : '' }}">
                    <label for="room_view" class="control-label ">Room View:</label>
                    <select class="form-control" name="room_view">
                        <?php
                        $room_view = old('room_view',$room_view);


                        if(!empty($room_master)){
                            ?>
                            <option value="">Select</option>
                            <?php
                            foreach ($room_master as $view_key => $room_view_value){
                                if($room_view_value->type == 'room_view'){
                                    $selected = '';
                                    if($room_view_value->id == $room_view){
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="{{$room_view_value->id}}" {{$selected}}>{{$room_view_value->name}}</option>
                                    <?php 
                                }
                            }
                        }
                        ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'room_view'])
                </div>
                <div class="form-group col-md-3{{ $errors->has('room_view') ? ' has-error' : '' }}">
                    <label for="room_view" class="control-label ">Bed Type:</label>
                    <select class="form-control" name="bed_type">
                        <?php
                        $bed_type = old('bed_type',$bed_type);
                        if(!empty($room_master)){
                            ?>
                            <option value="">Select</option>
                            <?php
                            foreach ($room_master as $bed_key => $bed_type_value){
                                 if($bed_type_value->type == 'bed_type'){
                                $selected = '';
                                if($bed_type_value->id == $bed_type){
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="{{$bed_type_value->id}}" {{$selected}}>{{$bed_type_value->name}}</option>
                                <?php }
                            }
                        }
                        ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'room_view'])
                </div>
                <div class="form-group col-md-3{{ $errors->has('extra_bed_type') ? ' has-error' : '' }}">
                    <label for="extra_bed_type" class="control-label ">Extra Bed Type:</label>
                    <select class="form-control" name="extra_bed_type">
                        <?php
                        $extra_bed_type = old('extra_bed_type',$extra_bed_type);
                        if(!empty($room_master)){
                            ?>
                            <option value="">Select</option>
                            <?php
                            foreach ($room_master as $extra_bed_key => $extra_bed_type_value){
                                if($extra_bed_type_value->type == 'extra_bed_type'){
                                $selected = '';
                                if( $extra_bed_type_value->id == $extra_bed_type){
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="{{$extra_bed_type_value->id}}" {{$selected}}>{{$extra_bed_type_value->name}}</option>
                                <?php }
                            }
                        }
                        ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'extra_bed_type'])
                </div>
                <div class="form-group col-md-12{{ $errors->has('brief') ? ' has-error' : '' }}">
                    <label for="brief" class="control-label">Brief:</label>
                    <textarea name="brief" id="brief" class="form-control" ><?php echo old('brief', $brief); ?></textarea>    
                    @include('snippets.errors_first', ['param' => 'brief'])
                </div>

                <div class="form-group col-md-12{{$errors->has('video_link')?' has-error':''}}">
                    <label for="sort_order" class="control-label">Video Link (Youtube,Vimeo):</label>
                    <textarea name="video_link" id="video_link" class="form-control" rows="1" >{{ old('video_link',$video_link) }}</textarea>
                    <small><i>Note: Put "?rel=0" for play single video only. e.g. src="https://www.youtube.com/embed/qEEFfGUenLQ?rel=0"</i></small>
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>
				<div class="clearfix"></div>
                <?php /*
                <div class="form-group  col-md-12{{ $errors->has('description') ? ' has-error' : '' }}">
                	<label for="description" class="control-label required">Description:</label>
                	<textarea name="description" id="description" class="form-control ckeditor" ><?php echo old('description', $description); ?></textarea>    
                	@include('snippets.errors_first', ['param' => 'description'])
                </div>
             
                <div class="clearfix"></div> 
                */?>

                <div class="form-group col-md-12"><h4>Room Occupancy:</h4></div>                
                
                <div class="form-group col-md-3{{ $errors->has('base_occupancy') ? ' has-error' : '' }}">
                    <label for="base_occupancy" class="control-label required">Base Occupancy (Adult):</label>
                    <input type="text" name="base_occupancy" id="base_occupancy" class="form-control" value="{{ old('base_occupancy',$base_occupancy) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'base_occupancy'])
                </div>

                <?php /*
                <div class="form-group col-md-3{{ $errors->has('publish_price') ? ' has-error' : '' }}">
                    <label for="publish_price" class="control-label required">Publish Rate:</label>
                    <input type="text" name="publish_price" id="publish_price" class="form-control" value="{{ old('publish_price',$publish_price) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'publish_price'])
                </div>
                <div class="form-group col-md-3{{ $errors->has('base_price') ? ' has-error' : '' }}">
                    <label for="base_price" class="control-label required">Base Occupancy Price (Adult):</label>
                    <input type="text" name="base_price" id="base_price" class="form-control" value="{{ old('base_price',$base_price) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'base_price'])
                </div>
                
                <div class="form-group col-md-3{{ $errors->has('single_price') ? ' has-error' : '' }}">
                    <label for="single_price" class="control-label required">Single Price (Adult):</label>
                    <input type="text" name="single_price" id="single_price" class="form-control" value="{{ old('single_price',$single_price) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'single_price'])
                </div>
                <div class="form-group col-md-4{{ $errors->has('price_extra_adult') ? ' has-error' : '' }}">
                    <label for="price_extra_adult" class="control-label required">Price per Extra Adult (12 Years & above):</label>
                    <input type="text" name="price_extra_adult" id="price_extra_adult" class="form-control" value="{{ old('price_extra_adult',$price_extra_adult) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'price_extra_adult'])
                </div>
                <div class="form-group col-md-3{{ $errors->has('price_extra_child_1') ? ' has-error' : '' }}">
                    <label for="price_extra_child_1" class="control-label required">Price per Extra Child range (0-5 Years):</label>
                    <input type="text" name="price_extra_child_1" id="price_extra_child_1" class="form-control" value="{{ old('price_extra_child_1',$price_extra_child_1) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'price_extra_child_1'])
                </div>

                 <div class="form-group col-md-3{{ $errors->has('price_extra_child_2') ? ' has-error' : '' }}">
                    <label for="price_extra_child_2" class="control-label required">Price per Extra Child (6-12 Years):</label>
                    <input type="text" name="price_extra_child_2" id="price_extra_child_2" class="form-control" value="{{ old('price_extra_child_2',$price_extra_child_2) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'price_extra_child_2'])
                </div>
                <div class="clearfix"></div>
                <div class="clearfix"></div>
                <div class="clearfix"></div>
                */ ?>
                
                <div class="form-group col-md-3{{ $errors->has('max_adult_bed') ? ' has-error' : '' }}">
                    <label for="max_adult_bed" class="control-label">Adult Occupancy (Max):</label>
                    <input type="text" name="max_adult_bed" id="max_adult_bed" class="form-control" value="{{ old('max_adult_bed',$max_adult_bed) }}" autocomplete="off">
                    @include('snippets.errors_first', ['param' => 'max_adult_bed'])
                </div>
                
                <div class="form-group col-md-3{{ $errors->has('base_child_no') ? ' has-error' : '' }}">
                    <label for="base_child_no" class="control-label"> Base Occupancy (Child):</label>
                    <input type="text" name="base_child_no" id="base_child_no" class="form-control" value="{{ old('base_child_no',$base_child_no) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'base_child_no'])
                </div>

                <div class="form-group col-md-3{{ $errors->has('max_child_no') ? ' has-error' : '' }}">
                    <label for="max_child_no" class="control-label">Child Occupancy (Max):</label>
                    <input type="text" name="max_child_no" id="max_child_no" class="form-control" value="{{ old('max_child_no',$max_child_no) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'max_child_no'])
                </div>
                
                <div class="form-group col-md-3{{ $errors->has('max_occupancy') ? ' has-error' : '' }}">
                    <label for="max_occupancy" class="control-label required">Max Occupancy:</label>
                    <input type="text" name="max_occupancy" id="max_occupancy" class="form-control" value="{{ old('max_occupancy',$max_occupancy) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'max_occupancy'])
                </div>
                <?php /*
                <div class="form-group col-md-3{{ $errors->has('quantity') ? ' has-error' : '' }}">
                    <label for="quantity" class="control-label required">No. of Rooms to Show:</label>
                    <input type="text" name="quantity" id="quantity" class="form-control" value="{{ old('quantity',$quantity) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'quantity'])
                </div>
                <div class="form-group col-md-3{{ $errors->has('max_quantity') ? ' has-error' : '' }}">
                    <label for="max_quantity" class="control-label required">No. of Rooms:</label>
                    <input type="text" name="max_quantity" id="max_quantity" class="form-control" value="{{ old('max_quantity',$max_quantity) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'max_quantity'])
                </div>
                <div class="form-group col-md-3{{ $errors->has('minimum_stay') ? ' has-error' : '' }}">
                    <label for="minimum_stay" class="control-label required">Minimum Stay:</label>
                    <input type="text" name="minimum_stay" id="minimum_stay" class="form-control" value="{{ old('minimum_stay',$minimum_stay) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'minimum_stay'])
                </div>
                <div class="form-group col-md-3{{ $errors->has('no_of_exrta_beds') ? ' has-error' : '' }}">
                    <label for="no_of_exrta_beds" class="control-label required">Number of Extra bed:</label>
                    <input type="text" name="no_of_exrta_beds" id="no_of_exrta_beds" class="form-control" value="{{ old('no_of_exrta_beds',$no_of_exrta_beds) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'no_of_exrta_beds'])
                </div>
                <div class="form-group col-md-3{{ $errors->has('max_adults') ? ' has-error' : '' }}">
                    <label for="max_adults" class="control-label required">Max Adults:</label>
                    <input type="text" name="max_adults" id="max_adults" class="form-control" value="{{ old('max_adults',$max_adults) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'max_adults'])
                </div>
                <div class="form-group col-md-3{{ $errors->has('max_children') ? ' has-error' : '' }}">
                    <label for="max_children" class="control-label required">Max Children:</label>
                    <input type="text" name="max_children" id="max_children" class="form-control" value="{{ old('max_children',$max_children) }}" autocomplete="off">  
                    @include('snippets.errors_first', ['param' => 'max_children'])
                </div>
                */ ?>

                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-3">
                    <br />
                    <label class="control-label">Status:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                    &nbsp;
                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                    @include('snippets.errors_first', ['param' => 'status'])
                </div>

                <div class="col-sm-3">
                    <br />
                    <label for="is_default"><input name="is_default" type="checkbox" value="1" {{($is_default == 1)?'checked':''}} > Is Default Room Price</label>
                </div>

                <div class="clearfix"></div>
                <div class="form-group col-md-12">
                    <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                    <a href="<?php echo url($routeName.'/accommodations/'.$accommodation->id).'/accommodation-room' ?>" class="btn_admin2" title="Cancel">Cancel</a>  
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


       $('.select2').select2({
            placeholder: "Please Select",
            allowClear: true
        });


        // var description = document.getElementById('description');
        // CKEDITOR.replace(description);

    </script>

@endslot

@endcomponent