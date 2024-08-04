@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
    <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ >
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
    @endslot

    <?php
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();

    if(empty($back_url)){
        $back_url = $routeName.'/packages/'.$package_id.'/itenaries';
    }

    $routeName = CustomHelper::getAdminRouteName();

    $id = (isset($itenary->id))?$itenary->id:'';
    $location_id = (isset($itenary->location_id))?$itenary->location_id:'';
    $location_name = '';
    if($location_id) {
        $location_name = $itenary->Location->name??'';
    }
    $day = (isset($itenary->day))?$itenary->day:'';
    $day_title = (isset($itenary->day_title))?$itenary->day_title:'';
    $title = (isset($itenary->title))?$itenary->title:'';
    $itenary_inclusions = (isset($itenary->itenary_inclusions))?json_decode($itenary->itenary_inclusions):[];
    $image = (isset($itenary->image))?$itenary->image:'';
    $description = (isset($itenary->description))?$itenary->description:'';
    $status = (isset($itenary->status))?$itenary->status:1;
    $sort_order = (isset($itenary->sort_order))?$itenary->sort_order:0;
    // $itenary_tags = isset($itenary->itenaryTags) ? $itenary->itenaryTags:null;
    $itenary_tags = isset($itenary->tags) ? $itenary->tags:null;
    $meal_option_key = isset($itenary->meal_option) ?json_decode($itenary->meal_option):[];

    // $meal_option_key = json_decode($meal_option_key);

    // prd($meal_option_key);

    // $itenaryTags = "";
    // if(!empty($itenary_tags)){
    //     $itenaryTags = [];
    //     foreach ($itenary_tags as  $tag) {
    //         $itenaryTags[] = $tag->name;
    //     }
    //     $itenaryTags = implode(',',$itenaryTags);
    // }

    $storage = Storage::disk('public');
    $path = 'itenaries/';

    $old_image = $image;
    $image_req = '';
    $link_req = '';
    $packageDurationDays = $package->package_duration_days;
    ?>
    <?php
    $active_menu = "packages".$package_id.'/itenaries';
    ?>
    @if(!empty($package_id))
        @include('admin.includes.packageoptionmenu')
    @endif

    <div class="top_title_admin">
    <div class="title">
    <h2>{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
    <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
            
            <a href="{{ url($back_url)}}" class="btn_admin"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
 Back</a><?php } ?>
    </div>
    </div>

     <div class="centersec">
        <div class="bgcolor">
            @include('snippets.errors')
            @include('snippets.flash')

            <div class="ajax_msg"></div>

            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group col-md-12 {{ $errors->has('location_id') ? ' has-error' : '' }}">
                    <label for="day" class="control-label required">Destination / Place :</label>
                    <select class="form-control select2" name="location_id" id="" >
                        <option value="">Select Destination / Place</option>
                        {!!CustomHelper::getDestinationOptions('', $location_id, ['show_locations'=>true])!!}
                    </select>
                    @include('snippets.errors_first', ['param' => 'location_id'])
                </div>

                <div class="form-group col-md-6 {{ $errors->has('day') ? ' has-error' : '' }}">
                    <label for="day" class="control-label required">Days :</label>
                    <select class="form-control" name="day">
                        <option value="">Select Day</option>
                        <?php for($i=1;$i<=$packageDurationDays;$i++) { ?>
                        <option value="{{$i}}" <?php echo ($i == old('day',$day)) ? "selected":"" ?> > {{$i}}</option>  
                        <?php } ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'day'])
                </div>


                <div class="form-group col-md-6 {{ $errors->has('day_title') ? ' has-error' : '' }}">
                    <label for="day_title" class="control-label required">Day Title (For Ex, Day1/Day2):</label>
                    <input type="text" id="day_title" class="form-control" name="day_title" value="{{ old('day_title',$day_title) }}" />
                    @include('snippets.errors_first', ['param' => 'day_title'])
                </div>
                
                <div class="form-group col-md-6 {{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title" class="control-label required">Title :</label>
                    <input type="text" id="title" class="form-control" name="title" value="{{ old('title',$title) }}" />
                    @include('snippets.errors_first', ['param' => 'title'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('itenary_inclusions') ? ' has-error' : '' }}">
                    <label for="itenary_inclusions" class="control-label">Itinerary Inclusions :</label>
                    <select class="form-control select2" name="itenary_inclusions[]" multiple>
                        <?php
                        $itenary_inclusions = old('itenary_inclusions[]',$itenary_inclusions);

                        if(!empty($packageInclusions)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($packageInclusions as $packageInclusion){
                                $selected = '';
                                if(in_array($packageInclusion->id,$itenary_inclusions)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$packageInclusion->id}}" {{$selected}}>{{$packageInclusion->title}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'itenary_inclusions'])
                </div>

                <!-- Meal choose option -->
               <div class="form-group col-md-6{{ $errors->has('meal_option') ? ' has-error' : '' }}">
                    <label for="meal_option" class="control-label">Meal option :</label>
                    <select class="form-control select2" name="meal_option[]" multiple>
                        <?php
                         $meal_options = config('custom.meal_options');

                        if(!empty($meal_options)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($meal_options as $key => $meal_option){
                                $selected = '';
                                if(in_array($key,$meal_option_key)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$key}}" {{$selected}}>{{$meal_option}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'meal_options'])
                </div>

                <?php
                $image_required = $image_req;
                if($id > 0){
                    $image_required = '';
                }
                ?>
                <div class="form-group col-md-6 {{ $errors->has('image') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label {{ $image_required }}">Image:</label>
                    <input type="file" id="image" name="image"/>
                    @include('snippets.errors_first', ['param' => 'image'])
                </div>
                <?php
                if(!empty($image)){
                    if($storage->exists($path.$image))
                    {
                        ?>
                        <div class="col-md-2 imgBox">
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

                <div class="form-group col-md-6{{$errors->has('tags')?' has-error':''}}">
                    <label for="tags" class="control-label">Tags:</label>
                    {{--<input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags',$itenaryTags) }}">--}}
                    <input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags',$itenary_tags) }}">
                    @include('snippets.errors_first', ['param' => 'tags'])
                </div>

                <div class="clearfix"></div>
                <div class="form-group  col-md-12{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description" class="control-label required">Description:</label>

                    <textarea name="description" id="description" class="form-control ckeditor" ><?php echo old('description', $description); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'description'])
                </div>

                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-6">
                    <label class="control-label">Status:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                    &nbsp;
                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                    @include('snippets.errors_first', ['param' => 'status'])
                </div>

                <div style="display:none;" class="form-group  col-md-6{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Sort Order:</label>

                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>

                <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
              <button type="submit" class="btn btn-success" title="Create this new itenary"><i class="fa fa-save"></i> Submit</button>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>

@slot('bottomBlock')
    <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script>
        var description = document.getElementById('description');
        CKEDITOR.replace(description);

        $('.select2').select2({
            placeholder: "Please Select",
            allowClear: true
        });

        $('#tags').tagsinput();

        $(".delImg").click(function(){
            var conf = confirm('Are you sure to delete this Image?');

            if(conf){

                var currSelector = $(this);

                var id = $(this).data("id");

                var _token = '{{ csrf_token() }}';

                $.ajax({
                    url: "{{ route($routeName.'.packages.ajax_delete_itenary_image',['package_id' => $package_id]) }}",
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
                            currSelector.parent(".imgBox").remove();
                        }

                    }
                });
            }
        });


        $('#location_id').select2({
            ajax: {
                url: "{{ route($routeName.'.destinations.ajax_locations') }}",
                type: "POST",
                data: function (params) {
                   return {
                      term: params.term, // search term,
                      destination_id: "{{$destination_id??''}}"
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