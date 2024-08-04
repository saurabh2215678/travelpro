@component('admin.layouts.main')
    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot
    @slot('headerBlock')
    <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ >
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endslot
    <?php
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();
    if(empty($back_url)){
        $back_url = $routeName.'/cab_route';
    }
    //pr($blog->toArray());
    $routeName = CustomHelper::getAdminRouteName();
    $id = (isset($cab_route->id))?$cab_route->id:'';
    $name = (isset($cab_route->name))?$cab_route->name:'';
    $route_type = (isset($cab_route->route_type))?$cab_route->route_type:'';
    $origin = (isset($cab_route->origin))?$cab_route->origin:'';
    $destination = (isset($cab_route->destination))?$cab_route->destination:'';
    $description = (isset($cab_route->description))?$cab_route->description:'';
    $places = (isset($cab_route->places))?$cab_route->places:'';
    $duration = (isset($cab_route->duration))?$cab_route->duration:'';
    $distance = (isset($cab_route->distance))?$cab_route->distance:'';
    $start_time = (isset($cab_route->start_time))?$cab_route->start_time:'';
    $status = (isset($cab_route->status))?$cab_route->status:1;
    $sharing = (isset($cab_route->sharing))?$cab_route->sharing:0;
    $featured = (isset($cab_route->featured))?$cab_route->featured:0;
    $cab_route_group_id = (isset($cab_route->cab_route_group_id))?$cab_route->cab_route_group_id:0;
    $CabRouteToGroup = (isset($cab_route->CabRouteToGroup))?$cab_route->CabRouteToGroup:0;
  
    // prd($CabRouteToGroup->toArray());
    $tripTimeArr = config('custom.tripTimeArr');
    $CabRouteGroupArr = "";
    if(!empty($CabRouteToGroup)){
        $CabRouteGroupArr = [];
        foreach ($CabRouteToGroup as  $routegroup) {    
            $CabRouteGroupArr[] = $routegroup->id;
        }
    }
    $cab_route_types = config('custom.cab_route_types');
    $active_menu = '';
    ?>
    <?php if($id){ ?>
    <div class="page_btns">
        <a class="active" href="{{route($routeName.'.cab_route.edit', ['id'=>$id])}}" title="Edit Cab Route"><i class="fas fa-edit"></i>Cab Route</a>
        <a <?php if($active_menu=="cab_route".$id.'/gallery'){echo 'class="active"' ;}?> href="{{route($routeName.'.images.upload_view',['tid'=>$id,'module'=>'cab_route','category'=>'gallery']) }}" title="Images"><i class="fas fa-image"></i>Gallery</a>
        <a  href="{{route($routeName.'.cab_route.agent_price', ['id'=>$id])}}" title="Edit Agent price"><i class="fas fa-edit"></i>Agent price</a>
    </div>
    <?php } ?>

    <div class="centersec">
        <div class="top_title_admin tab-title">
            <div class="title">
                <h2>{{ $page_heading }}</h2>
            </div>
            <div class="add_button">
                <?php if(request()->has('back_url') && request('back_url')){ $back_url= request('back_url');  ?>
                <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                   Back</a><?php } ?>
               </div>
           </div>
        <div class="bgcolor p10">
            @include('snippets.errors')
            @include('snippets.flash')
        <div class="ajax_msg"></div>
            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-4">
                       <div class="form-group{{ $errors->has('module_name') ? ' has-error' : '' }}">
                        <label class="required" >Cab Route:</label><br/>
                            <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $name) }}" />
                            @include('snippets.errors_first', ['param' => 'name'])                          
                        </div>
                    </div>
                    <div class="form-group  col-md-4{{ $errors->has('cab_route_group_id') ? ' has-error' : '' }}">
                        <label for="cab_route_group_id" class="control-label required">Cab Route Group :</label>
                        <select class="form-control myselect" name="cab_route_group_id[]" multiple>
                            <option value="">Select</option>
                            <?php
                            //prd($tKey);
                            //$cab_route_group_id = old('cab_route_group_id',$cab_route_group_id);
                            if(!empty($cab_route_groups) && count($cab_route_groups) > 0){
                                foreach ($cab_route_groups as $Key => $cab_route_group){
                                    $selected = '';

                                    if($CabRouteGroupArr && in_array($cab_route_group->id,$CabRouteGroupArr)){
                                        $selected = 'selected';
                                    }
                                   
                                    ?>
                                    <option value="{{$cab_route_group->id}}" {{$selected}}>{{$cab_route_group->name}}</option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        @include('snippets.errors_first', ['param' => 'cab_route_group_id'])
                    </div>
                     <div class="form-group col-md-4{{ $errors->has('route_type') ? ' has-error' : '' }}">
                        <label for="route_type" class="control-label required">Trip Type :</label>
                        <select class="form-control changeSelectBox" name="route_type">
                            <option value="">Select</option>
                            <?php foreach ($cab_route_types as $key => $cab_route_type) {
                                if($key != 1) { //do not show round trip
                              ?>
                            <option value="{{$key}}" <?php echo ($key == $route_type)?'selected':''; ?>>{{$cab_route_type}}</option>
                           <?php } } ?>

                        </select>
                        @include('snippets.errors_first', ['param' => 'route_type'])
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                       <div class="form-group{{ $errors->has('origin') ? ' has-error' : '' }}">
                        <label class="required" >Origin:</label><br/>
                            <select name="origin" class="form-control admin_input1 select2">
                            <?php
                            $origin = old('origin',$origin);

                            if(!empty($cab_cities)){ ?>
                                <option value="">Select</option>
                                
                                <?php
                                foreach ($cab_cities as $cabCity){

                                    $selected = '';
                                    if($cabCity->id == $origin){
                                        $selected = 'selected';
                                    }
                                ?>
                                <option value="{{$cabCity->id}}" {{$selected}}>{{$cabCity->name}}</option>
                            <?php }}?>
                            </select>
                        @include('snippets.errors_first', ['param' => 'origin'])                        
                        </div>
                    </div>
                     <div class="col-md-4 hideOnSelect">
                       <div class="form-group{{ $errors->has('destination') ? ' has-error' : '' }}">
                        <label class="" >Destination:</label><br/>
                            <select name="destination" class="form-control admin_input1 select2">
                            <?php
                            $destination = old('destination',$destination);

                            if(!empty($cab_cities)){ ?>
                                <option value="">Select</option>
                                
                                <?php
                                foreach ($cab_cities as $cabCity){

                                    $selected = '';
                                    if($cabCity->id == $destination){
                                        $selected = 'selected';
                                    }
                                ?>
                                <option value="{{$cabCity->id}}" {{$selected}}>{{$cabCity->name}}</option>
                            <?php }}?>
                            </select>
                        @include('snippets.errors_first', ['param' => 'destination'])                         
                        </div>
                    </div>
                    <div class="form-group col-md-1{{$errors->has('sharing')?' has-error':''}} ">
                        <label class="control-label ">Sharing:</label>
                        <input type="checkbox" id="checkbox" name="sharing" value="1" <?php echo ($sharing == '1')?'checked':''; ?> />
                        @include('snippets.errors_first', ['param' => 'sharing'])
                    </div>

                    <div class="col-md-3" id="start_time"  <?php echo ($sharing != '1')?'style="display:none;"':''; ?>  >
                       <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                        <label class="" >Start Time</label><br/>
                            <!-- <input type="text" id="start_time" class="form-control" name="start_time" value="{{ old('start_time', $start_time) }}" /> -->

                            <select name="start_time" class="form-control">
                                <option value="">Select</option>
                                <?php foreach ($tripTimeArr as $timekey => $tripTime) { ?>
                                    <option value="{{$timekey}}" <?php if($start_time == $timekey){ echo 'selected'; } ?>>{{$tripTime}}</option>
                                <?php } ?>
                            </select>
                            @include('snippets.errors_first', ['param' => 'duration'])                          
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                     <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                        <label class="" >Duration: (e.g. 2-3 hrs, 01N-02D, Same Day etc.)</label><br/>
                        <input type="text" id="duration" class="form-control" name="duration" value="{{ old('duration', $duration) }}" />
                        @include('snippets.errors_first', ['param' => 'duration'])                          
                    </div>
                </div>

                    <div class="col-md-4">
                       <div class="form-group{{ $errors->has('distance') ? ' has-error' : '' }}">
                        <label class="" >Distance in km:</label><br/>
                            <input type="number" id="distance" class="form-control" name="distance" value="{{ old('distance', $distance) }}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" />
                            @include('snippets.errors_first', ['param' => 'distance'])                          
                        </div>
                    </div>
                    <div class="col-md-8">
                    <div class="form-group{{ $errors->has('places') ? ' has-error' : '' }}">
                        <label class="" >Places:</label><br/>
                            <input type="text" id="places" class="form-control" name="places" value="{{ old('places', $places) }}" />
                            @include('snippets.errors_first', ['param' => 'places'])                          
                        </div>
                    </div>

                    <div class="clearfix"></div>
                <div class="form-group  col-md-12{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description" class="control-label">Description:</label>

                    <textarea name="description" id="description" class="form-control ckeditor" ><?php echo old('description', $description); ?></textarea>

                    @include('snippets.errors_first', ['param' => 'description'])
                </div>

               
                  <div class="form-group col-md-2{{$errors->has('featured')?' has-error':''}} ">
                    <label class="control-label ">Featured:</label>
                    <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />
                    @include('snippets.errors_first', ['param' => 'featured'])
                </div>
                
                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-12">
                        <label class="control-label">Status:</label>
                        &nbsp;&nbsp;
                        Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                        &nbsp;
                        Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >
                        @include('snippets.errors_first', ['param' => 'status'])
                    </div>
                </div>   
                <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                <button type="submit" class="btn btn-success" title="Create this new Group name"><i class="fa fa-save"></i> Submit</button>
                <a href="{{ url($back_url) }}" class="btn_admin2">Cancel</a>       
            </form>
        </div>
        </div>
        </div>
    <div class="clearfix"></div>
        
@slot('bottomBlock')
<script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    var description = document.getElementById('description');
        CKEDITOR.replace(description);
</script>
<script type="text/javascript">

$('.myselect').select2({closeOnSelect: true,  placeholder: "Please Select",}).on("change", function(e) {

var counter = $(this).next('.select2-container').find(".select2-selection__choice").length;
if(counter > 2){
  if($(this).next('.select2-container').find('.counter').length <= 0){
    $(this).next('.select2-container').find('.select2-selection__rendered').after('<div style="line-height: 28px; padding: 5px;" class="counter"> ('+counter+' selected)</div>');
  }else{
    $(this).next('.select2-container').find('.counter').text(`(${counter} selected)`);
  }
}else{
  $(this).next('.select2-container').find('.counter').remove();
}
});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script>
$(document).ready(function(){

function handleCabType(){
    if($('.changeSelectBox').val() == '2'){
        $(".hideOnSelect").hide();
    }else{
        $(".hideOnSelect").show();
    }
}
function show_timeslot(argument) {
    
     $(".hideOnSelect").hide();
}

 let checkbox = document.getElementById("checkbox");
      checkbox.addEventListener( "change", () => {
         if ( checkbox.checked ) {

            start_time
            $('#start_time').show();
            // text.innerHTML = " Check box is checked. ";
         } else {
            $('#start_time').hide();
            // text.innerHTML = "";
         }
      });

$('.changeSelectBox').change(handleCabType);
handleCabType();

});
</script>
@endslot

@endcomponent