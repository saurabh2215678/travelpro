@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
    <!-- <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ > -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
    <style>
       /* .select2-container {height: 32px; }*/
        .bootstrap-tagsinput { display: block; }
    </style>
    @endslot

    <?php
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();

    if(empty($back_url)){
        $back_url = $routeName.'/packages';
    }

    $routeName = CustomHelper::getAdminRouteName();

    $id = (isset($package->id))?$package->id:'';
    $tour_type = (isset($package->tour_type))?$package->tour_type:'';
    $title = (isset($package->title))?$package->title:'';
    $price_category = (isset($package->price_category))?$package->price_category:'';
    $slug = (isset($package->slug))?$package->slug:'';
    $subtitle = (isset($package->subtitle))?$package->subtitle:'';
    $package_duration = (isset($package->package_duration))?$package->package_duration:'';
    $package_duration_days = (isset($package->package_duration_days))?$package->package_duration_days:'';
    $destination_id = (isset($package->destination_id))?$package->destination_id:'';
    $sub_destination_id = (isset($package->sub_destination_id))?$package->sub_destination_id:'';
    $related_destinations = (isset($package->related_destinations))?json_decode($package->related_destinations):[];
    $related_packages = (isset($package->related_packages))?json_decode($package->related_packages):[];
    $best_months = (isset($package->best_months))?json_decode($package->best_months):[];
    $activity_level = (isset($package->activity_level))?$package->activity_level:'';
    $package_type = (isset($package->package_type))?$package->package_type:'';
    $package_inclusions = (isset($package->package_inclusions))?json_decode($package->package_inclusions):[];
    $package_experts = (!empty($package) && !($package->packageExperts->isEmpty())) ? $package->packageExperts : "";
    $image = (isset($package->image))?$package->image:'';
    $package_detail_banners = (isset($package->package_detail_banners))?$package->package_detail_banners:'';
    $brief = (isset($package->brief))?$package->brief:'';
    $description = (isset($package->description))?$package->description:'';
    $meta_title = (isset($package->meta_title))?$package->meta_title:'';
    $meta_description = (isset($package->meta_description))?$package->meta_description:'';
    $star_ratings = (isset($package->star_ratings))?$package->star_ratings:0;
    $status = (isset($package->status))?$package->status:0;
    $featured = (isset($package->featured))?$package->featured:'';
    $popular = (isset($package->popular))?$package->popular:'';
    $sort_order = (isset($package->sort_order))?$package->sort_order:'';
    $video_link = (isset($package->video_link))?$package->video_link:'';
    $package_categories = (!empty($package) && (!$package->packageCategories->isEmpty())) ? $package->packageCategories : "";
    $package_tags = (!empty($package) && !($package->packageTags->isEmpty())) ? $package->packageTags : "";
    $multiple_dates = (!empty($package) && !empty($package->multiple_dates)) ? json_decode($package->multiple_dates) : "";

    $package_themes = [];
    if(!empty($package_categories)){
        foreach ($package_categories as  $category) {
            $package_themes[] = $category->id;
        }
    }

    $packageTags = "";
    if(!empty($package_tags)){
        $packageTags = [];
        foreach ($package_tags as  $tag) {
            $packageTags[] = $tag->name;
        }
        $packageTags = implode(',',$packageTags);
    }

    $experts = [];
    if(!empty($package_experts)){
        $experts = [];
        foreach ($package_experts as  $expert) {
            $experts[] = $expert->id;
        }
    }

    $storage = Storage::disk('public');
    $path = 'packages/';

    $old_image = $image;
    $old_package_detail_banners = $package_detail_banners;
    $image_req = '';
    $link_req = '';
    ?>


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

                <div class="form-group{{ $errors->has('tour_type') ? ' has-error' : '' }} col-md-6">
                    <label class="control-label ">Tour Type:</label>
                    &nbsp;&nbsp;
                    Group Tour: <input class="tourType" type="radio" name="tour_type" value="group" <?php echo ($tour_type == 'group')?'checked':''; ?> checked>
                    &nbsp;
                    Customised Tour: <input class="tourType" type="radio" name="tour_type" value="general" <?php echo ($tour_type == 'general')?'checked':''; ?> >

                    @include('snippets.errors_first', ['param' => 'tour_type'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('price_category') ? ' has-error' : '' }}">
                    <label for="price_category" class="control-label required">Price Category:</label>
                    <select class="form-control select2" name="price_category" id="price_category">
                        <?php
                        $price_category = old('price_category',$price_category);
                        
                        if(!empty($price_categories)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($price_categories as $pc){
                                $selected = '';
                                if($pc->id == $price_category){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$pc->id}}" {{$selected}}>{{$pc->name}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'price_category'])
                </div>

                <div class="row">
                    <div class="col-sm-10">
                        <div class="specific_period_form">
                            <div class="hide_specific_period_form">
                                <?php if(!empty($multiple_dates)){
                                    foreach ($multiple_dates as $dates) {
                                ?>
                                <div class="row show_specific_period_form">
                                    <div class="col-sm-4">
                                        <input type="text" name="from_date[]" class="form-control mycalender" placeholder="From Date*" value="{{ CustomHelper::DateFormat($dates->from_date,'d-m-Y','Y-m-d') }}" required >
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="to_date[]" class="form-control mycalender" placeholder="To Date*" value="{{ CustomHelper::DateFormat($dates->to_date,'d-m-Y','Y-m-d') }}" required>
                                    </div>
                                    <!--<div class="col-sm-4">
                                        <input type="text" class="form-control"  placeholder="Identifier">
                                    </div>-->
                                    <div class="col-sm-4">
                                        <div class="cross"><i class="fa fa-times" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <?php }}else{ ?>
                                <div class="row show_specific_period_form">
                                    <div class="col-sm-4">
                                        <input type="text" name="from_date[]" class="form-control mycalender" placeholder="From Date*" required >
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="to_date[]" class="form-control mycalender" placeholder="To Date*" required>
                                    </div>
                                    <!--<div class="col-sm-4">
                                        <input type="text" class="form-control"  placeholder="Identifier">
                                    </div>-->
                                    <div class="col-sm-4">
                                        <div class="cross"><i class="fa fa-times" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>                                
                            </div>
                            <div class="add_more">+ Add More</div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-6 {{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title" class="control-label required">Title :</label>
                    <input type="text" id="title" class="form-control" name="title" value="{{ old('title',$title) }}" />
                    @include('snippets.errors_first', ['param' => 'title'])
                </div>

                <div style="display:none;" class="form-group col-md-6 {{ $errors->has('slug') ? ' has-error' : '' }}">
                    <label for="title" class="control-label required">Slug :</label>
                    <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug',$slug) }}" />
                    @include('snippets.errors_first', ['param' => 'slug'])
                </div>

                <div class="form-group col-md-6 {{ $errors->has('subtitle') ? ' has-error' : '' }}">
                    <label for="subtitle" class="control-label">Sub-Title :</label>
                    <input type="text" id="subtitle" class="form-control" name="subtitle" value="{{ old('subtitle',$subtitle) }}" />
                    @include('snippets.errors_first', ['param' => 'subtitle'])
                </div>

                <div class="form-group col-md-6 {{ $errors->has('package_duration') ? ' has-error' : '' }}">
                    <label for="package_duration" class="control-label required">Package Duration Text:</label>
                    <input type="text" id="package_duration" class="form-control" name="package_duration" value="{{ old('package_duration',$package_duration) }}" />
                    @include('snippets.errors_first', ['param' => 'package_duration'])
                </div>

                <div class="form-group col-md-6 {{ $errors->has('package_duration_days') ? ' has-error' : '' }}">
                    <label for="package_duration_days" class="control-label required">Package Duration Days :</label>
                    <select class="form-control" name="package_duration_days">
                        <option value="">Select Duration Days</option>
                        <?php for($i=1;$i<=60;$i++) { ?>
                        <option value="{{$i}}" <?php echo ($i == old('package_duration_days',$package_duration_days)) ? "selected":"" ?> > {{$i}}</option>  
                        <?php } ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'package_duration_days'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('destination') ? ' has-error' : '' }}">
                    <label for="destination" class="control-label required">Destination:</label>
                    <select class="form-control select2" name="destination" id="destination">
                        <?php
                        $destination_id = old('destination',$destination_id);
                        if(!empty($destinations)){
                        $parent_destinations = $destinations->where('parent_id', 0);
                        ?>
                            <option value="">Select</option>
                            <?php
                            if(!($parent_destinations->isEmpty())){
                            foreach ($parent_destinations as $destination_it){
                                $selected = '';
                                if($destination_it->id == $destination_id){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$destination_it->id}}" {{$selected}}>{{$destination_it->name}}</option>
                        <?php }}}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'destination'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('sub_destination') ? ' has-error' : '' }}">
                    <label for="sub_destination" class="control-label required">Sub Destination:</label>
                    <select class="form-control select2" name="sub_destination" id="sub_destination">
                        <?php $sub_destination_id = old('sub_destination',$sub_destination_id); ?>
                        <option value="">Select</option>
                    </select>
                    @include('snippets.errors_first', ['param' => 'sub_destination'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('related_destinations') ? ' has-error' : '' }}">
                    <label for="related_destination" class="control-label">Related Destinations :</label>
                    <select class="form-control select2" name="related_destination[]" multiple>
                        <?php
                        $related_destinations = old('related_destination[]',$related_destinations);

                        if(!empty($destinations)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($destinations as $destination_it){
                                $selected = '';
                                if(in_array($destination_it->id,$related_destinations)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$destination_it->id}}" {{$selected}}>{{$destination_it->name}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'related_destinations'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('related_packages') ? ' has-error' : '' }}">
                    <label for="related_packages" class="control-label">Related Packages :</label>
                    <select class="form-control select2" name="related_packages[]" multiple>
                        <?php
                        $related_packages = old('related_packages[]',$related_packages);

                        if(!empty($packages)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($packages as $package_it){
                                $selected = '';
                                if($package_it->id != $id){
                                    if(in_array($package_it->id,$related_packages)){
                                        $selected = 'selected';
                                    }
                            ?>
                            <option value="{{$package_it->id}}" {{$selected}}>{{$package_it->title}}</option>
                        <?php }}}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'related_packages'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('best_months') ? ' has-error' : '' }}">
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


                 <div class="form-group col-md-6{{ $errors->has('activity_level') ? ' has-error' : '' }}">
                    <label for="activity_level" class="control-label">Activity Level:</label>
                    <select class="form-control select2" name="activity_level" >

                        <?php
                        $activityArr = config('custom.activity_level_arr');
                        if(!empty($activityArr)){ ?>
                            <option value="">Select</option>
                            <?php
                            foreach ($activityArr as $actId=>$activity){
                                $selected = '';
                                if($actId == $activity_level){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$actId}}" {{$selected}}>{{$activity}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'activity_level'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('package_type') ? ' has-error' : '' }}">
                    <label for="package_type" class="control-label">Package Type:</label>
                    <select class="form-control select2" name="package_type">
                        <?php
                        $package_type = old('package_type',$package_type);

                        if(!empty($package_types)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($package_types as $package_typ){
                                $selected = '';
                                if($package_typ->id == $package_type){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$package_typ->id}}" {{$selected}}>{{$package_typ->package_type}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'package_type'])
                </div>

                <div class="form-group col-md-6 {{ $errors->has('package_themes') ? ' has-error' : '' }}">
                    <label for="package_themes" class="control-label">Package Themes/Categories :</label>
                    <select class="form-control select2" name="package_themes[]" multiple>
                        <?php
                        $package_themes = old('package_themes[]',$package_themes);

                        if(!empty($themes)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($themes as $theme){
                                $selected = '';
                                if(in_array($theme->id,$package_themes)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$theme->id}}" {{$selected}}>{{$theme->title}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'package_themes'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('package_inclusions') ? ' has-error' : '' }}">
                    <label for="package_inclusions" class="control-label">Package Inclusions :</label>
                    <select class="form-control select2" name="package_inclusions[]" multiple>
                        <?php
                        $package_inclusions = old('package_inclusions[]',$package_inclusions);

                        if(!empty($packageInclusions)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($packageInclusions as $packageInclusion){
                                $selected = '';
                                if(in_array($packageInclusion->id,$package_inclusions)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$packageInclusion->id}}" {{$selected}}>{{$packageInclusion->title}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'package_inclusions'])
                </div>

                <div class="form-group col-md-6{{ $errors->has('experts') ? ' has-error' : '' }}">
                    <label for="experts" class="control-label">Experts :</label>
                    <select class="form-control select2" name="experts[]" multiple>

                        <?php
                        //prd($packageExperts);
                        $experts = old('experts[]',$experts);

                        if(!empty($packageExperts)){ ?>
                            <option value="">Select</option>
                            
                            <?php
                            foreach ($packageExperts as $expert){
                                $selected = '';
                                if(in_array($expert->id,$experts)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$expert->id}}" {{$selected}}>{{$expert->first_name.' '.$expert->last_name}}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'experts'])
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

                            <input onchange="validateSize(this)" type="file" id="image" name="image"/>

                            @include('snippets.errors_first', ['param' => 'image'])
                        </div>
                        <?php
                        if(!empty($image)){
                            if($storage->exists($path.$image)){
                        ?>
                            <div class="col-md-2 image_box">
                               <img src="{{ url('/storage/'.$path.'thumb/'.$image) }}" style="width: 100px;"><br>
                               <a href="javascript:void(0)" data-id="{{ $id }}" data='image' class="del_image">Delete</a>
                           </div>
                        <?php } ?>
                        <?php } ?>
                       <input type="hidden" name="old_image" value="{{ $old_image }}">
                   </div>

                   <div class="col-md-6">

                    <div class="form-group{{ $errors->has('package_detail_banners') ? ' has-error' : '' }}">
                        <label for="sort_order" class="control-label {{ $image_required }}">Package Detail Banners :</label>

                        <input onchange="validateSize(this)" type="file" id="package_detail_banners" name="package_detail_banners"/>

                        @include('snippets.errors_first', ['param' => 'package_detail_banners'])
                    </div>
                    <?php
                    if(!empty($package_detail_banners)){
                        if($storage->exists($path.$package_detail_banners))
                        {
                            ?>
                            <div class="col-md-2 image_box">
                                <img src="{{ url('/storage/'.$path.'thumb/'.$package_detail_banners) }}" style="width: 100px;"><br>
                                <a href="javascript:void(0)" data-id="{{ $id }}" data='package_detail_banners' class="del_image">Delete</a>
                            </div>
                            <?php        
                        }

                        ?>
                        <?php
                    }
                    ?>
                    <input type="hidden" name="old_package_detail_banners" value="{{ $package_detail_banners }}">
                </div>
            </div>

                <div class="form-group  col-md-6{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Sort Order:</label>

                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>

                <div class="form-group  col-md-6 {{ $errors->has('video_link') ? ' has-error' : '' }}">
                    <label for="sort_order" class="control-label">Video Link (Youtube,Vimeo):</label>

                    <textarea name="video_link" id="video_link" class="form-control" >{{ old('video_link',$video_link) }}</textarea>
                    Note: Put "?rel=0" for play single video only. e.g. src="https://www.youtube.com/embed/qEEFfGUenLQ?rel=0"
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>

                <div class="form-group  col-md-6 {{ $errors->has('star_ratings') ? ' has-error' : '' }}">
                    <label for="star_ratings" class="control-label">Star Ratings:</label>
                        <select class="form-control" name="star_ratings">
                        <option value="">Please Select</option>
                        <?php
                        $selected = '';
                        for($i = 1; $i<=5; $i++){
                        if($star_ratings == $i){
                            $selected = 'selected';

                        }
                        ?>
                        <option value="{{$i}}" {{$selected}}>{{$i.' Star'}}</option>
                        <?php
                        }
                        ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'star_ratings'])
                </div>

                <div class="form-group  col-md-6 {{ $errors->has('tags') ? ' has-error' : '' }}">
                    <label for="tags" class="control-label">Tags:</label>

                    <input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags',$packageTags) }}">
                    @include('snippets.errors_first', ['param' => 'tags'])
                </div>

                <hr>
                <div class="col-md-12">
                <h3>SEO:</h3>
                </div>
                
                <div class="form-group col-md-6 {{ $errors->has('meta_title') ? ' has-error' : '' }}">
                    <label for="meta_title" class="control-label">Meta Title:</label>

                    <input type="text" name="meta_title" value="{{ old('meta_title', $meta_title)}}" id="meta_title" class="form-control"  />    

                    @include('snippets.errors_first', ['param' => 'meta_title'])
                </div>

                <div class="form-group col-md-6 {{ $errors->has('meta_description') ? ' has-error' : '' }}">
                    <label for="meta_description" class="control-label">Meta Description:</label>

                    <textarea name="meta_description" id="meta_description"  class="form-control" >{{ old('meta_description', $meta_description) }}</textarea>    

                    @include('snippets.errors_first', ['param' => 'meta_description'])
                </div>

                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-md-6">
                    <label class="control-label">Status:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                    &nbsp;
                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                    @include('snippets.errors_first', ['param' => 'status'])
                </div>

                <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }} col-md-6">
                    <label class="control-label ">Featured:</label>
                    <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />
                    @include('snippets.errors_first', ['param' => 'featured'])
                </div>

                <div class="form-group{{ $errors->has('popular') ? ' has-error' : '' }} col-md-6">
                    <label class="control-label ">Popular:</label>
                    <input type="checkbox" name="popular" value="1" <?php echo ($popular == '1')?'checked':''; ?> />
                    @include('snippets.errors_first', ['param' => 'popular'])
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <p></p>
                        <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                        <button type="submit" class="btn btn-success" title="Create this new package"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>

@slot('bottomBlock')
    <script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script>
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

        $('#tags').tagsinput();

        $(".del_image").click(function(){

                    var current_sel = $(this);

                    var image_id = $(this).attr('data-id');

                    var type = $(this).attr('data');

                // alert(type); return false;

                conf = confirm("Are you sure to Delete this Image?");

                if(conf){

                    var _token = '{{ csrf_token() }}';

                    $.ajax({
                        url: "{{ route($routeName.'.packages.ajax_delete_image') }}",
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

        var Preiod_box = `<div class="row show_specific_period_form">
                            <div class="col-sm-4">
                                <input type="text" name="from_date[]" class="form-control mycalender" placeholder="From Date*" >
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="to_date[]" class="form-control mycalender" placeholder="To Date*" >
                            </div>
                            <!--<div class="col-sm-4">
                                <input type="text" class="form-control"  placeholder="Identifier">
                            </div>-->
                            <div class="col-sm-4">
                                <div class="cross"><i class="fa fa-times" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>`

            $('.add_more').click(function(){
                $(Preiod_box).appendTo('.hide_specific_period_form')
            })

            $(document).click(function(e){
               var Element = $(e.target)
               if(Element.hasClass('cross')){
                   Element.closest('.show_specific_period_form').remove();
               }
            })

            $('body').on('focus',".mycalender", function(){
                $(this).datepicker({
                    minDate: 0,
                    dateFormat: 'dd-mm-yy',
                    changeMonth: true,
                    changeYear: true
                });
            });


            var tour_type = $('input[name="tour_type"]:checked').val();

            if(tour_type == "group"){
                $('.specific_period_form').show();
            }else{
                $('.specific_period_form').hide();
                $(".mycalender").removeAttr("required");
            }

            $('.tourType').click(function() {
                var tour_type = $('input[name="tour_type"]:checked').val();
                if(tour_type == "group"){
                    $('.specific_period_form').show();
                }else{
                    $('.specific_period_form').hide();
                    $(".mycalender").removeAttr("required");
                }
            })

    function validateSize(input) {
      const fileSize = input.files[0].size / 1024 / 1024; // in MiB
      if (fileSize > 5) {
        alert('File size exceeds 5 MiB');
        $(input).val(''); //for clearing with Jquery
      } else {
        // Proceed further
      }
    }

    </script>
<script type="text/javascript">
    var _token = '{{ csrf_token() }}';

    $(document).ready(function () {

        var destinationId = $('#destination').val();
        var subDestinationId = '{{ $sub_destination_id }}';

        populateSubDestination(destinationId,subDestinationId);

        $('#destination').on('change',function(e) {
            var destination_id = e.target.value;
            populateSubDestination(destination_id);
        });

        // Alert For Price Category Change
        var existingPriceCategory = '{{ $price_category }}';

        $('#price_category').on('change',function(e) {
            var price_category_id = e.target.value;
            if(existingPriceCategory != ""){
                if(existingPriceCategory != price_category_id){
                    alert("Warning : If you update the price category then all price information related to this package will be delete.");
                }
            }
        });      

    });

    function populateSubDestination(destination_id,setDestination=""){
        $.ajax({
            url:"{{ route('common.ajax_load_sub_destinations') }}",
            type:"POST",
            headers:{'X-CSRF-TOKEN': _token},
            data: {
                destination_id: destination_id
            },
            success:function (data) {
                let text = "";
                $('#sub_destination').empty();
                text +=  `<option value="">---Select Sub Destination---</option>`
                text += data.options;
                /*data.states.forEach(function(item, index){
                    text +=  `<option value="${item.id}">${item.name}</option>`
                })*/
                $("#sub_destination").html(text)
            },complete:function(){
                $('#sub_destination').val(setDestination);
            }
        });
    }
</script>
@endslot

@endcomponent