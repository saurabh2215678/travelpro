@component('admin.layouts.main')

    @slot('title')
        Admin - {{ $page_heading }} - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
    @endslot

    @slot('headerBlock')
    <!-- <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.css') }}"/ > -->
    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
    <style>
       /* .select2-container {height: 32px; }*/
    .bootstrap-tagsinput { display: block; }
    .tooltip {position: relative;display: inline-block;border-bottom: 1px dotted black;opacity: 1;font-style: normal;}
    .tooltip .tooltiptext {visibility: hidden;width: 330px; background-color: #ffeaea; color: #000;text-align: center;border-radius: 6px;padding: 10px;position: absolute;z-index: 1;bottom: 0;}
    .tooltip:hover .tooltiptext {visibility: visible;}
    .z-zero{position: relative; z-index: 0;}
    .bootstrap-tagsinput { display: block;}
    .slugEdit {position: absolute; right: 22px; top: 26px;font-size: 15px; opacity: .7;}
    </style>

    @endslot

    <?php
    $back_url = (request()->has('back_url'))?request()->input('back_url'):'';
    $routeName = CustomHelper::getAdminRouteName();

    if(empty($back_url)){
        $back_url = $routeName.'/'.$segment;
    }

    $id = (isset($package->id))?$package->id:0;

    $tour_type = (!empty($package->tour_type))?$package->tour_type:'fixed';
    $tour_type = old('tour_type',$tour_type);
    

    $is_activity = (isset($package->is_activity))?$package->is_activity:'';
    $is_activity = old('is_activity',$is_activity);

    $title = (isset($package->title))?$package->title:'';
    $price_category = (isset($package->price_category))?$package->price_category:'';
    $slug = (isset($package->slug))?$package->slug:'';
    $subtitle = (isset($package->subtitle))?$package->subtitle:'';
    $package_duration = (isset($package->package_duration))?$package->package_duration:'';
    $package_duration_days = (isset($package->package_duration_days))?$package->package_duration_days:'';

    $activity_duration = (isset($package->activity_duration))?$package->activity_duration:'';
    $activity_duration_type = (isset($package->activity_duration_type))?$package->activity_duration_type:'';
    $activity_duration_type = old('activity_duration_type',$activity_duration_type);
    $destination_id = (isset($package->destination_id))?$package->destination_id:'';
    $sub_destination_id = (isset($package->sub_destination_id))?$package->sub_destination_id:'';
    if(isset($package->related_destinations) && !empty($package->related_destinations)) {
        $related_destinations = (isset($package->related_destinations))?json_decode($package->related_destinations):[];
    } else {
        $related_destinations = [];
    }
    if(isset($package->related_packages) && !empty($package->related_packages)) {

        $related_packages = (isset($package->related_packages))?json_decode($package->related_packages):[];
    } else {
        $related_packages = [];
    }
    $best_months = (isset($package->best_months))?json_decode($package->best_months):[];
    $activity_level = (isset($package->activity_level))?$package->activity_level:'';
    $package_type = (isset($package->package_type))?$package->package_type:'';
    $package_inclusions = (isset($package->package_inclusions))?json_decode($package->package_inclusions):[];
    $package_experts = (!empty($package) && !($package->packageExperts->isEmpty())) ? $package->packageExperts : "";
    $image = (isset($package->image))?$package->image:'';
    $banner_image = (isset($package->banner_image))?$package->banner_image:'';
    $brief = (isset($package->brief))?$package->brief:'';
    $description = (isset($package->description))?$package->description:'';
    $inclusions = (isset($package->inclusions))?$package->inclusions:'';
    $exclusions = (isset($package->exclusions))?$package->exclusions:'';
    $payment_policy = (isset($package->payment_policy))?$package->payment_policy:'';
    $cancellation_policy = (isset($package->cancellation_policy))?$package->cancellation_policy:'';
    $terms_conditions = (isset($package->terms_conditions))?$package->terms_conditions:'';
    $vendor_id = (isset($package->vendor_id))?$package->vendor_id:'';

    $inclusions_chk = (isset($package->inclusions_chk))?$package->inclusions_chk:0;
    $exclusions_chk = (isset($package->exclusions_chk))?$package->exclusions_chk:0;
    $payment_policy_chk = (isset($package->payment_policy_chk))?$package->payment_policy_chk:0;
    $cancellation_policy_chk = (isset($package->cancellation_policy_chk))?$package->cancellation_policy_chk:0;
    $terms_conditions_chk = (isset($package->terms_conditions_chk))?$package->terms_conditions_chk:0;

    $meta_title = (isset($package->meta_title))?$package->meta_title:'';
    $meta_description = (isset($package->meta_description))?$package->meta_description:'';
    $star_ratings = (isset($package->star_ratings))?$package->star_ratings:0;
    $package_status = (isset($package->status))?$package->status:1;

    $inladakh = (isset($package->inladakh))?$package->inladakh:1;
    $featured = (isset($package->featured))?$package->featured:'';
    $popular = (isset($package->popular))?$package->popular:'';
    $sort_order = (isset($package->sort_order))?$package->sort_order:'';
    $video_link = (isset($package->video_link))?$package->video_link:'';
    $package_categories = (!empty($package) && (!$package->packageCategories->isEmpty())) ? $package->packageCategories : "";
    $package_flags = (!empty($package) && (!$package->packageFlags->isEmpty())) ? $package->packageFlags : "";
    
    $package_tags = (!empty($package) && !($package->packageTags->isEmpty())) ? $package->packageTags : "";
    // $multiple_dates = (!empty($package) && !empty($package->multiple_dates)) ? json_decode($package->multiple_dates) : "";

    $place = (isset($package->place))?$package->place:'';
    $additional_places = (isset($package->additional_places))?$package->additional_places:'';
    $address = (isset($package->address))?$package->address:'';
    $contact_person = (isset($package->contact_person))?$package->contact_person:'';
    $contact_phone = (isset($package->contact_phone))?$package->contact_phone:'';
    $contact_email = (isset($package->contact_email))?$package->contact_email:'';

    $package_flag_arr = [];
    if(!empty($package_flags)){
        foreach ($package_flags as  $pflag) {
            $package_flag_arr[] = $pflag->id;
        }
    }

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

    // $storage = Storage::disk('public');
    $path = 'packages/';

    $old_image = $image;
    $old_banner_image = $banner_image;
    $image_req = '';
    $link_req = '';


    $websiteSettingsArr = [];
    $websiteSettingsArr = CustomHelper::getSettings(['PACKAGE_INCLUSIONS','PACKAGE_EXCLUSIONS','PACKAGE_PAYMENT_POLICY','PACKAGE_CANCELLATION_POLICY','PACKAGE_TERMS_CONDITIONS','ACTIVITY_INCLUSIONS','ACTIVITY_EXCLUSIONS','ACTIVITY_PAYMENT_POLICY','ACTIVITY_CANCELLATION_POLICY','ACTIVITY_TERMS_CONDITIONS']);
    $pack_inclusions = isset($websiteSettingsArr['PACKAGE_INCLUSIONS'])?html_entity_decode($websiteSettingsArr['PACKAGE_INCLUSIONS'], ENT_NOQUOTES):'';
    if($segment == 'activity'){
        $pack_inclusions = isset($websiteSettingsArr['ACTIVITY_INCLUSIONS'])?html_entity_decode($websiteSettingsArr['ACTIVITY_INCLUSIONS'], ENT_NOQUOTES):'';
    }
    $pack_exclusions = isset($websiteSettingsArr['PACKAGE_EXCLUSIONS'])?html_entity_decode($websiteSettingsArr['PACKAGE_EXCLUSIONS'], ENT_NOQUOTES):'';
    if($segment == 'activity'){
        $pack_exclusions = isset($websiteSettingsArr['ACTIVITY_EXCLUSIONS'])?html_entity_decode($websiteSettingsArr['ACTIVITY_EXCLUSIONS'], ENT_NOQUOTES):'';
    }
    $pack_payment_policy = isset($websiteSettingsArr['PACKAGE_PAYMENT_POLICY'])?html_entity_decode($websiteSettingsArr['PACKAGE_PAYMENT_POLICY'], ENT_NOQUOTES):'';
    if($segment == 'activity'){
        $pack_payment_policy = isset($websiteSettingsArr['ACTIVITY_PAYMENT_POLICY'])?html_entity_decode($websiteSettingsArr['ACTIVITY_PAYMENT_POLICY'], ENT_NOQUOTES):'';
    }
    $pack_cancellation_policy = isset($websiteSettingsArr['PACKAGE_CANCELLATION_POLICY'])?html_entity_decode($websiteSettingsArr['PACKAGE_CANCELLATION_POLICY'], ENT_NOQUOTES):'';
    if($segment == 'activity'){
        $pack_cancellation_policy = isset($websiteSettingsArr['ACTIVITY_CANCELLATION_POLICY'])?html_entity_decode($websiteSettingsArr['ACTIVITY_CANCELLATION_POLICY'], ENT_NOQUOTES):'';
    }
    $pack_terms_conditions = isset($websiteSettingsArr['PACKAGE_TERMS_CONDITIONS'])?html_entity_decode($websiteSettingsArr['PACKAGE_TERMS_CONDITIONS'], ENT_NOQUOTES):'';
    if($segment == 'activity'){
        $pack_terms_conditions = isset($websiteSettingsArr['ACTIVITY_TERMS_CONDITIONS'])?html_entity_decode($websiteSettingsArr['ACTIVITY_TERMS_CONDITIONS'], ENT_NOQUOTES):'';
    }
    //prd($pack_exclusions);
    ?>

<?php
$active_menu = "packages";
?>
@if(!empty($package))
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
            <form method="POST" action="" accept-charset="UTF-8" role="form" enctype="multipart/form-data" class="package-add-form">
                {{ csrf_field() }}
                <?php if($segment == 'packages'){ ?>
                <div class="form-group col-md-6{{$errors->has('tour_type')?' has-error':''}}">
                    <label class="control-label required">Tour Type:</label> <br />
                    Group Tour: <input id="group" class="tourType" type="radio" name="tour_type" value="group" <?php echo ($tour_type == 'group')?'checked':''; ?>>
                    &nbsp;
                    Fixed Tour: <input id="customised_tour" class="tourType CommonTourType" type="radio" name="tour_type" value="fixed" <?php echo ($tour_type == 'fixed')?'checked':''; ?> >

                    &nbsp;
                    Open Tour: <input id="open" class="tourType CommonTourType" type="radio" name="tour_type" value="open" <?php echo ($tour_type == 'open')?'checked':''; ?> ><br>
                    @include('snippets.errors_first', ['param' => 'tour_type'])
                </div> 
                <?php } ?>
                
               <?php /* <div class="form-group col-md-2{{$errors->has('is_activity')?' has-error':''}}">
                    <label class="control-label ">Is Activity:</label> <br />
                    <input class="tourType" type="checkbox" name="is_activity" id="is_activity" value="1" <?php echo ($is_activity == '1')?'checked':''; ?> > Yes
                    @include('snippets.errors_first', ['param' => 'is_activity'])
                </div> */ ?>

                <div class="form-group col-md-6{{$errors->has('price_category')?' has-error':''}}">
                    <label for="price_category" class="control-label required">Price Category:</label>
                    <select class="form-control select2" name="price_category" id="price_category">
                        <?php
                        $price_category = old('price_category',$price_category);
                        if(!empty($price_categories)){ ?>
                        <option value="">Select</option>
                            <?php
                            foreach ($price_categories as $pc)
                            {
                                $selected = '';
                                if($pc->id == $price_category) {
                                    $selected = 'selected';
                                }
                            ?>
                        <option value="{{$pc->id}}" {{$selected}}>{{$pc->name}}</option>
                        <?php } } ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'price_category'])
                </div>

                <?php /*
                <div class="row">
                    <div class="col-sm-10">
                        <div class="specific_period_form">
                            <div class="hide_specific_period_form">
                                <?php if(!empty($multiple_dates)){
                                    foreach ($multiple_dates as $dates) {
                                ?>
                                <div class="row show_specific_period_form">
                                    <div class="col-sm-4">
                                        <input type="text" name="from_date[]" class="form-control mycalender" placeholder="From Date*" value="{{ CustomHelper::DateFormat($dates->from_date,'d-m-Y','Y-m-d') }}" required autocomplete="off">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="to_date[]" class="form-control mycalender" placeholder="To Date*" value="{{ CustomHelper::DateFormat($dates->to_date,'d-m-Y','Y-m-d') }}" required autocomplete="off">
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
                                        <input type="text" name="from_date[]" class="form-control mycalender" placeholder="From Date*" required autocomplete="off">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="to_date[]" class="form-control mycalender" placeholder="To Date*" required autocomplete="off">
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
                </div>*/ ?>
                <div class="clearfix"></div>
                <div class="form-group col-md-6{{$errors->has('title')?' has-error':''}}">
                    <label for="title" class="control-label required">{{($segment == 'activity')?'Activity':'Packages'}} Title :</label>
                    <input type="text" id="title" class="form-control" name="title" value="{{ old('title',$title) }}" />
                    @include('snippets.errors_first', ['param' => 'title'])
                </div>

                <div class="form-group col-md-6{{$errors->has('subtitle')?' has-error':''}}">
                    <label for="subtitle" class="control-label">Sub-Title :</label>
                    <input type="text" id="subtitle" class="form-control" name="subtitle" value="{{ old('subtitle',$subtitle) }}" />
                    @include('snippets.errors_first', ['param' => 'subtitle'])
                </div>
                
                <?php
                if(!empty($package->id)){
                    ?>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }} slug">
                            <label for="link_name" class="control-label required">Slug:</label>

                            <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug', $slug) }}" />

                            <a href="javascript:void(0);" class="slugEdit" id="EditPSlug" title="Edit"><i class="fas fa-edit"></i></a>

                            @include('snippets.errors_first', ['param' => 'slug'])
                        </div>
                    </div>
                <?php }?>

                <div style="display: none;" class="form-group col-md-6{{$errors->has('vendor_id')?' has-error':''}}">
                    <label for="vendor_id" class="control-label ">Vendor :</label>
                    <select class="form-control select2" name="vendor_id" id="vendor_id">  
                        <option value="">Select</option>
                            <?php
                            foreach ($vendors as $vendor)
                            {
                                $selected = '';
                                if($vendor->id == $vendor_id) {
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$vendor->id}}" {{$selected}}>{{$vendor->name}}</option>
                        <?php } ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'vendor_id'])
                </div>
                <div class="clearfix"></div>
                
                <div id="duration_div" {{($segment == 'activity')?'':'style=display:none;'}} >
                    <div class="clearfix"></div>
                    <div class="form-group col-md-4{{$errors->has('activity_duration_type')?' has-error':''}}">
                        <label for="activity_duration_type" class="control-label">Activity Duration Type :</label>
                        <select class="form-control" name="activity_duration_type" id="activity_duration_type">
                            <option value="">Please Select</option>
                            <?php
                            $activity_duration_type_arr = config('custom.activity_duration_type_arr');
                            if(!empty($activity_duration_type_arr)) {
                                foreach($activity_duration_type_arr as $key => $value) {
                            ?>
                            <option value="{{$key}}" {{($activity_duration_type==$key)?'selected':''}}>{{$value}}</option>
                            <?php
                                }
                            }
                            ?>
                            <?php /*<option value="2" {{($activity_duration_type==2)?'selected':''}}>Days</option>
                            <option value="3" {{($activity_duration_type==3)?'selected':''}}>Hours</option>
                            <option value="1" {{($activity_duration_type==1)?'selected':''}}>Minutes</option>
                            <option value="4" {{($activity_duration_type==4)?'selected':''}}>Kilometer</option>*/ ?>
                        </select>
                        @include('snippets.errors_first', ['param' => 'activity_duration_type'])
                    </div>

                    <div id="activity_duration_div" class="form-group col-md-4{{$errors->has('activity_duration')?' has-error':''}}">
                        <label for="activity_duration" class="control-label">Activity Duration:</label>
                        <input type="text" id="activity_duration" class="form-control" name="activity_duration" value="{{ old('activity_duration',$activity_duration) }}" />
                        @include('snippets.errors_first', ['param' => 'activity_duration'])
                    </div>
                </div>

                <div @if($segment == 'activity') id="duration_days_div" @endif class="form-group col-md-4{{$errors->has('package_duration_days')?' has-error':''}}">
                    <label for="package_duration_days" class="control-label required">{{($segment == 'activity')?'Activity':'Packages'}} Duration Days :</label>
                    <select class="form-control" name="package_duration_days">
                        <option value="">Select Duration Days</option>
                        <?php for($i=1;$i<=60;$i++) { ?>
                        <option value="{{$i}}" <?php echo ($i == old('package_duration_days',$package_duration_days)) ? "selected":"" ?> > {{$i}}</option>  
                        <?php } ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'package_duration_days'])
                </div>
                @if($segment == 'activity')
                <div @if($segment == 'activity') id="duration_lap_div" @endif class="form-group col-md-4{{$errors->has('activity_duration_lap')?' has-error':''}}">
                    <label for="activity_duration_lap" class="control-label required">Activity Duration LAP :</label>
                    <select class="form-control" name="activity_duration_lap">
                        <option value="">Select Duration LAP</option>
                        <?php for($i=1;$i<=10;$i++) { ?>
                        <option value="{{$i}}" <?php echo ($i == old('activity_duration',$activity_duration)) ? "selected":"" ?> > {{$i}}</option>  
                        <?php } ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'activity_duration_lap'])
                </div>
                @endif

                <div class="form-group col-md-4{{$errors->has('package_duration')?' has-error':''}}">
                    <label for="package_duration" class="control-label required">{{($segment == 'activity')?'Activity':'Packages'}} Duration Text:</label>
                    <input type="text" id="package_duration" class="form-control" name="package_duration" value="{{ old('package_duration',$package_duration) }}" />
                    @include('snippets.errors_first', ['param' => 'package_duration'])
                </div>

                <div class="form-group col-md-4{{$errors->has('destination')?' has-error':''}}">
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
                                $status = '';
                                if($destination_it->status != 1 ){
                                    $status = ' (Inactive)';
                                }
                            ?>
                            <option value="{{$destination_it->id}}" {{$selected}}>{{$destination_it->name}}{{$status}}</option>
                        <?php }}}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'destination'])
                </div>

                @if($segment == 'activity')                
                <div>
                    <div class="form-group col-md-4{{$errors->has('place')?' has-error':''}}">
                        <label for="place" class="control-label">Place:</label>
                        <input type="text" id="place" class="form-control" name="place" value="{{ old('place',$place) }}" />
                        @include('snippets.errors_first', ['param' => 'place'])
                </div>

                <div>
                    <div class="form-group col-md-4{{$errors->has('additional_places')?' has-error':''}}">
                        <label for="additional_places" class="control-label">Additional Places:</label>
                        <input type="text" id="additional_places" class="form-control" name="additional_places" value="{{ old('additional_places',$additional_places) }}" />
                        @include('snippets.errors_first', ['param' => 'additional_places'])
                </div>

                <div>
                    <div class="form-group col-md-12{{$errors->has('address')?' has-error':''}}">
                        <label for="address" class="control-label">Address:</label>
                        <textarea rows="2" cols="2" name="address" id="address" class="form-control" ><?php echo old('address', $address); ?></textarea>    
                        @include('snippets.errors_first', ['param' => 'address'])
                </div>
                @endif

                <div class="clearfix"></div>

                <div class="form-group col-md-4{{$errors->has('contact_person')?' has-error':''}}">
                        <label for="contact_person" class="control-label">Contact Person:</label>
                        <input type="text" id="contact_person" class="form-control" name="contact_person" value="{{ old('contact_person',$contact_person) }}" />
                        @include('snippets.errors_first', ['param' => 'contact_person'])
                </div>
                
                <div class="form-group col-md-4{{$errors->has('contact_phone')?' has-error':''}}">
                        <label for="contact_phone" class="control-label">Contact Phone:</label>
                        <input type="text" id="contact_phone" class="form-control" name="contact_phone" value="{{ old('contact_phone',$contact_phone) }}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="12" />
                        @include('snippets.errors_first', ['param' => 'contact_phone'])
                </div>
                
                <div class="form-group col-md-4{{$errors->has('contact_email')?' has-error':''}}">
                        <label for="contact_email" class="control-label">Contact Email:</label>
                        <input type="text" id="contact_email" class="form-control" name="contact_email" value="{{ old('contact_email',$contact_email) }}" />
                        @include('snippets.errors_first', ['param' => 'contact_email'])
                </div>


                <?php /*<div class="form-group col-md-6{{$errors->has('sub_destination')?' has-error':''}} z-zero">
                    <label for="sub_destination" class="control-label required">Sub Destination:</label>
                    <select class="form-control select2" name="sub_destination" id="sub_destination">
                        <?php $sub_destination_id = old('sub_destination',$sub_destination_id); 
                        <option value="">Select</option>
                    </select>
                    @include('snippets.errors_first', ['param' => 'sub_destination'])
                </div>*/ ?>

                <div @if($segment == 'activity') style="display:none;" @endif class="form-group col-md-6{{$errors->has('related_destinations')?' has-error':''}}">
                    <label for="related_destination" class="control-label">Additional Destinations :</label>
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

                <div class="form-group col-md-6 tooltips {{$errors->has('related_packages')?' has-error':''}}">
                    <label for="related_packages" class="control-label related_tooltip">Related {{($segment == 'activity')?'Activity':'Packages'}} :<em class="tooltip">(i)<span class="tooltiptext">if no {{($segment == 'activity')?'Activity':'Packages'}} is selected in "Related {{($segment == 'activity')?'Activity':'Packages'}}" then {{($segment == 'activity')?'Activity':'Packages'}} based on "Sub Destinations", "Related Destinations" will be shown in {{($segment == 'activity')?'Activity':'Packages'}} detail page</span>
                    </em>
                </label>
                    <select class="form-control select2" name="related_packages[]" multiple>
                        <?php
                        $related_packages = old('related_packages[]',$related_packages);
                        if(!empty($packages)){ ?>
                        <option value="">Select</option>                            
                            <?php
                            foreach ($packages as $package_it)
                            {
                                $selected = '';
                                if($package_it->id != $id)
                                {
                                    if(in_array($package_it->id,$related_packages)) {
                                        $selected = 'selected';
                                    }
                            ?>
                        <option value="{{$package_it->id}}" {{$selected}}>{{$package_it->title}}</option>
                        <?php } } } ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'related_packages'])
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


                 <div class="form-group col-md-6{{$errors->has('activity_level')?' has-error':''}}">
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

                @if($segment == 'packages')
                <div class="form-group col-md-6{{$errors->has('package_type')?' has-error':''}}">
                    <label for="package_type" class="control-label">{{($segment == 'activity')?'Activity':'Packages'}} Type <span style="color:#b55603;">(e.g. Adventure, Beach,Wildlife Tours)</span> </label>
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
                @endif

                <div class="form-group col-md-6{{$errors->has('package_themes')?' has-error':''}}">
                    <label for="package_themes" class="control-label">{{($segment == 'activity')?'Activity':'Packages'}} Themes/Categories <span style="color:#b55603;">(e.g. Trekking & Hiking, Mountaineering)</span></label>
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
                            <option value="{{$theme->id}}" {{$selected}}>{!! $theme->title !!}</option>
                        <?php }}?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'package_themes'])
                </div>

                <div class="form-group col-md-6{{$errors->has('package_inclusions')?' has-error':''}}">
                    <label for="package_inclusions" class="control-label">{{($segment == 'activity')?'Activity':'Packages'}} Inclusions :</label>
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

                <div style="display: none;" class="form-group col-md-6{{$errors->has('experts')?' has-error':''}}">
                    <label for="experts" class="control-label">Experts :</label>
                    <select class="form-control select2" name="experts[]" multiple>
                        <?php
                        $experts = old('experts[]',$experts);
                        if(!empty($packageExperts)){ ?>
                        <option value="">Select</option>                            
                            <?php foreach ($packageExperts as $expert) {
                                $selected = '';
                                if(in_array($expert->id,$experts)) {
                                    $selected = 'selected';
                                }
                            ?>
                        {{-- <option value="{{$expert->id}}" {{$selected}}>{{$expert->first_name.' '.$expert->last_name}}</option>--}}
                        <option value="{{$expert->id}}" {{$selected}}>{{$expert->title}}</option>
                        <?php } } ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'experts'])
                </div>
                <div class="form-group col-md-12{{$errors->has('brief')?' has-error':''}}">
                    <label for="brief" class="control-label">Brief:</label>

                    <textarea name="brief" id="brief" class="form-control" ><?php echo old('brief', $brief); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'brief'])
                </div>

                <div class="clearfix"></div>
                <div class="form-group col-md-12{{$errors->has('description')?' has-error':''}}">
                    <label for="description" class="control-label">Description:</label>

                    <textarea name="description" id="description" class="form-control ckeditor" ><?php echo old('description', $description); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'description'])
                </div>

                <div class="clearfix"></div>
                <div class="form-group col-md-12{{$errors->has('inclusions')?' has-error':''}}">
                    <label for="inclusions" class="control-label">Inclusions:</label>
                    <input type="checkbox" id="inclusions_chk" name="inclusions_chk"  value="1" <?php echo ($inclusions == 1)?'checked':''; ?> />
                    <strong> Copy default inclusions from settings</strong>
                    <textarea name="inclusions" id="inclusions" class="form-control ckeditor" ><?php echo old('inclusions', $inclusions); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'inclusions'])
                </div>

                <div class="clearfix"></div>
                <div class="form-group col-md-12{{$errors->has('exclusions')?' has-error':''}}">
                    <label for="exclusions" class="control-label">Exclusions:</label>
                    <input type="checkbox" id="exclusions_chk" name="exclusions_chk" value="1" <?php echo ($exclusions == 1)?'checked':''; ?> />
                    <strong> Copy default exclusions from settings</strong>
                    <textarea name="exclusions" id="exclusions" class="form-control ckeditor" ><?php echo old('exclusions', $exclusions); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'exclusions'])
                </div>

                <div class="clearfix"></div>
                <div class="form-group col-md-12{{$errors->has('payment_policy')?' has-error':''}}">
                    <label for="payment_policy" class="control-label">Payment Policy:</label>
                    <input type="checkbox" id="payment_policy_chk" name="payment_policy_chk" value="1" <?php echo ($payment_policy == 1)?'checked':''; ?> />
                    <strong> Copy default payment policy from settings</strong>
                    <textarea name="payment_policy" id="payment_policy" class="form-control ckeditor" ><?php echo old('payment_policy', $payment_policy); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'payment_policy'])
                </div>

                <div class="clearfix"></div>
                <div class="form-group col-md-12{{$errors->has('cancellation_policy')?' has-error':''}}">
                    <label for="cancellation_policy" class="control-label">Cancellation Policy:</label>
                    <input type="checkbox" id="cancellation_policy_chk" name="cancellation_policy_chk" value="1" <?php echo ($cancellation_policy == 1)?'checked':''; ?> />
                    <strong> Copy default cancellation policy from settings</strong>
                    <textarea name="cancellation_policy" id="cancellation_policy" class="form-control ckeditor" ><?php echo old('cancellation_policy', $cancellation_policy); ?></textarea>    

                    @include('snippets.errors_first', ['param' => 'cancellation_policy'])
                </div>

                <div class="clearfix"></div>
                <div class="form-group col-md-12{{$errors->has('terms_conditions')?' has-error':''}}">
                    <label for="terms_conditions" class="control-label">Terms Conditions:</label>
                    <input type="checkbox" id="terms_conditions_chk" name="terms_conditions_chk" value="1" <?php echo ($terms_conditions == 1)?'checked':''; ?> />
                    <strong> Copy default terms conditions from settings</strong>
                    <textarea name="terms_conditions" id="terms_conditions" class="form-control ckeditor" ><?php echo old('terms_conditions', $terms_conditions); ?></textarea>    
                    @include('snippets.errors_first', ['param' => 'terms_conditions'])
                </div>

                <?php
                /*$image_required = $image_req;
                if($id > 0){
                    $image_required = '';
                }
                ?>
                <div class="col-md-12">
                    <div class="col-md-6">

                        <div class="form-group{{$errors->has('image')?' has-error':''}}">
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

                    <div class="form-group{{$errors->has('banner_image')?' has-error':''}}">
                        <label for="sort_order" class="control-label {{ $image_required }}">Package Detail Banners :</label>

                        <input onchange="validateSize(this)" type="file" id="banner_image" name="banner_image"/>

                        @include('snippets.errors_first', ['param' => 'banner_image'])
                    </div>
                    <?php
                    if(!empty($banner_image)){
                        if($storage->exists($path.$banner_image))
                        {
                            ?>
                            <div class="col-md-2 image_box">
                                <img src="{{ url('/storage/'.$path.'thumb/'.$banner_image) }}" style="width: 100px;"><br>
                                <a href="javascript:void(0)" data-id="{{ $id }}" data='banner_image' class="del_image">Delete</a>
                            </div>
                            <?php        
                        }

                        ?>
                        <?php
                    }
                    ?>
                    <input type="hidden" name="old_banner_image" value="{{ $banner_image }}">
                </div>
            </div>

            <div style="display:none;" class="form-group col-md-6 {{$errors->has('star_ratings')?' has-error':''}}">
                    <label for="star_ratings" class="control-label">Star Ratings:</label>
                    <select class="form-control" name="star_ratings">
                        <option value="">Please Select</option>
                        <?php
                        $selected = '';
                        for($i = 1; $i<=5; $i++) {
                            if($star_ratings == $i) {
                                $selected = 'selected';
                            }
                        ?>
                        <option value="{{$i}}" {{$selected}}>{{$i.' Star'}}</option>
                        <?php } ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'star_ratings'])
                </div>

                <hr><div style="display:none;" class="col-md-12">
                <h3>Add SEO Meta:</h3>
                </div>
                
                <div style="display:none;" class="form-group col-md-12 {{$errors->has('meta_title')?' has-error':''}}">
                    <label for="meta_title" class="control-label">Meta Title:</label> 

                    <input type="text" name="meta_title" value="{{ old('meta_title', $meta_title)}}" id="meta_title" class="form-control"  />    

                    @include('snippets.errors_first', ['param' => 'meta_title'])
                </div>

                <div style="display:none;" class="form-group col-md-12 {{$errors->has('meta_description')?' has-error':''}}">
                    <label for="meta_description" class="control-label">Meta Description:</label>

                    <textarea name="meta_description" id="meta_description"  class="form-control" >{{ old('meta_description', $meta_description) }}</textarea>    

                    @include('snippets.errors_first', ['param' => 'meta_description'])
                </div>*/?>

                <div class="form-group col-md-12{{$errors->has('video_link')?' has-error':''}}">
                    <label for="sort_order" class="control-label">Video Link (Youtube,Vimeo):</label>
                    <textarea name="video_link" id="video_link" class="form-control" rows="1" >{{ old('video_link',$video_link) }}</textarea>
                    <small><i>Note: Put "?rel=0" for play single video only. e.g. src="https://www.youtube.com/embed/qEEFfGUenLQ?rel=0"</i></small>
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>

                <div class="form-group col-md-10{{$errors->has('tags')?' has-error':''}}">
                    <label for="tags" class="control-label">Tags:</label>
                    <input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags',$packageTags) }}">
                    @include('snippets.errors_first', ['param' => 'tags'])
                </div>

                <div class="form-group col-md-2{{$errors->has('sort_order')?' has-error':''}}">
                    <label for="sort_order" class="control-label">Sort Order:</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order',$sort_order) }}"> 
                    @include('snippets.errors_first', ['param' => 'sort_order'])
                </div>

                <div class="form-group col-md-6{{$errors->has('related_destinations')?' has-error':''}}">
                    <label for="related_destination" class="control-label">{{($segment == 'activity')?'Activity':'Packages'}} Flag :</label>
                    <select class="form-control select2" name="package_flags[]" multiple>
                        <?php
                            if(!empty($flags)){ ?>
                            <option value="">Select</option>
                            <?php
                            foreach ($flags as $flag){
                                $selected = '';
                                if(in_array($flag->id,$package_flag_arr)){
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$flag->id}}" {{$selected}}>{{$flag->name}}</option>
                        <?php } } ?>
                    </select>
                    @include('snippets.errors_first', ['param' => 'package_flags'])
                </div>

                <div class="form-group col-md-2{{$errors->has('featured')?' has-error':''}} ">
                    <label class="control-label ">Featured:</label>
                    <input type="checkbox" name="featured" value="1" <?php echo ($featured == '1')?'checked':''; ?> />
                    @include('snippets.errors_first', ['param' => 'featured'])
                </div>

                <div class="clearfix"></div>
                <div class="form-group col-md-6{{$errors->has('status')?' has-error':''}} ">
                    <label class="control-label">Status:</label>
                    &nbsp;&nbsp;
                    Active: <input type="radio" name="status" value="1" <?php if($package_status == 1){ echo 'checked'; } ?>>
                    &nbsp;
                    Inactive: <input type="radio" name="status" value="0" <?php if($package_status == 0){ echo 'checked'; } ?> >

                    @include('snippets.errors_first', ['param' => 'status'])
                </div>

              <?php /* <div class="form-group col-md-2{{$errors->has('popular')?' has-error':''}} ">
                    <label class="control-label ">Popular:</label>
                    <input type="checkbox" name="popular" value="1" <?php echo ($popular == '1')?'checked':''; ?> />
                    @include('snippets.errors_first', ['param' => 'popular'])
                </div> 
                
                <div class="col-sm-12">
                <label for="inladakh">Best-selling Packages <span class="required">*</span></label>
                Best Selling Packages<!-- in Ladakh -->:
                <input type="radio" name="inladakh" value="1" <?php if($inladakh=='1') echo 'checked'; ?> >
                Explore Great Deals<!-- outside Ladakh -->:
                <input type="radio" name="inladakh" value="2" <?php if($inladakh=='2') echo 'checked'; ?> >
              </div>
*/ ?>

                

                <div class="col-md-12">
                    <div class="form-group">
                        <p></p>
                        <input type="hidden" id="id" class="form-control" name="id" value="{{ old('id', $id) }}"  />
                        <button type="submit" class="btn btn-success" title="Create this new package"><i class="fa fa-save"></i> Submit</button>
                        <a href="{{ route('admin.'.$segment.'.index') }}" class="btn_admin2" title="Cancel">Cancel</a>
                    </div>
                </div>
            </form>
            <div class="clearfix"></div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <!-- <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script> -->
    <script type="text/javascript">
    var slug = '{{$slug}}';
    if(slug != ''){
      $('#slug').attr('readonly',true);
    }

    $("#EditPSlug").click(function(){
        $('#slug').attr('readonly',false);
    });
    </script>
    <script type="text/javascript">
    $(document).ready(function(){   
        var activity_duration_type = '{{$activity_duration_type}}';
        durationType(activity_duration_type);
        $('#activity_duration_type').change(function() {
            var activity_duration_type = $(this).val();
            durationType(activity_duration_type);
        });
      
        function durationType(type = '') {
            if(type == 5) {
                $('#activity_duration_div').hide();
                $('#duration_days_div').hide();
                $('#duration_lap_div').show();
            } else if(type == 1 || type == 3 || type == 4){
                $('#activity_duration_div').show();
                $('#duration_days_div').hide();
                $('#duration_lap_div').hide();
            } else if(type == 2 || type == '') {
                $('#activity_duration_div').hide();
                $('#duration_lap_div').hide();
                $('#duration_days_div').show();
                $('#activity_duration').val('');
            }else{
                $('#activity_duration_div').hide();
                $('#duration_days_div').hide();
                $('#duration_lap_div').hide();
                $('#activity_duration').val('');
            }
        }

        $("#inclusions_chk").change( function()
        {
            if($('#inclusions_chk').is(':checked')) 
            {

                $("#addTask .modal-title").text("Are you sure, you want to overwrite existing inclusions with default inclusions from settings?");
                $("#addTask").modal("show");
                $("#addTask").attr('modal-ref', 'inclusions_chk');

          }else{
            CKEDITOR.instances['inclusions'].setData('');
          }
            $(document).on("click", "[modal-ref=inclusions_chk] .save_task", function () {
                    CKEDITOR.instances['inclusions'].setData(` {!! $pack_inclusions!!}`);
                    $("#addTask").modal("hide");
                    $("#addTask").attr('modal-ref', '');
            });

            $(document).on("click", "[modal-ref=inclusions_chk] .close_task", function () {
                CKEDITOR.instances['inclusions'].setData('');
                $("#addTask").modal("hide");
                $("#addTask").attr('modal-ref', '');
                $("#inclusions_chk").prop('checked', false);
            });
       });


        $("#exclusions_chk").change( function()
        {
            if($('#exclusions_chk').is(':checked')) 
            {

                $("#addTask .modal-title").text("Are you sure, you want to overwrite existing exclusions with default exclusions from settings?");
                $("#addTask").modal("show");
                $("#addTask").attr('modal-ref', 'exclusions_chk');
          
            }else{
                CKEDITOR.instances['exclusions'].setData('');
              }
            $(document).on("click", "[modal-ref=exclusions_chk] .save_task", function () {  
                CKEDITOR.instances['exclusions'].setData(` {!! $pack_exclusions!!}`);
                $("#addTask").modal("hide");
                $("#addTask").attr('modal-ref', '');
            });

            $(document).on("click", "[modal-ref=exclusions_chk] .close_task", function () {
                CKEDITOR.instances['exclusions'].setData('');
                $("#addTask").modal("hide");
                $("#addTask").attr('modal-ref', '');
                $("#exclusions_chk").prop('checked', false);
            });
       });

        $("#payment_policy_chk").change( function()
        {
            if($('#payment_policy_chk').is(':checked')) 
            {

                $("#addTask .modal-title").text("Are you sure, you want to overwrite existing payment policy with default payment policy from settings?");
                $("#addTask").modal("show");
                $("#addTask").attr('modal-ref', 'payment_policy_chk');
          
            }else{
                CKEDITOR.instances['payment_policy'].setData('');
              }
            $(document).on("click", "[modal-ref=payment_policy_chk] .save_task", function () {  
                CKEDITOR.instances['payment_policy'].setData(` {!! $pack_payment_policy!!}`);
                $("#addTask").modal("hide");
                $("#addTask").attr('modal-ref', '');
            });

            $(document).on("click", "[modal-ref=payment_policy_chk] .close_task", function () {
                CKEDITOR.instances['payment_policy'].setData('');
                console.log($('#payment_policy'));
                $("#addTask").modal("hide");
                $("#addTask").attr('modal-ref', '');
                $("#payment_policy_chk").prop('checked', false);
            });
       });

        $("#cancellation_policy_chk").change( function()
        {
            if($('#cancellation_policy_chk').is(':checked')) 
            {

                $("#addTask .modal-title").text("Are you sure, you want to overwrite existing cancellation policy with default cancellation policy from settings?");
                $("#addTask").modal("show");
                $("#addTask").attr('modal-ref', 'cancellation_policy_chk');
          
            }else{
                CKEDITOR.instances['cancellation_policy'].setData('');
              }
            $(document).on("click", "[modal-ref=cancellation_policy_chk] .save_task", function () {  
                CKEDITOR.instances['cancellation_policy'].setData(` {!! $pack_cancellation_policy!!}`);
                $("#addTask").modal("hide");
                $("#addTask").attr('modal-ref', '');
            });

            $(document).on("click", "[modal-ref=cancellation_policy_chk] .close_task", function () {
                CKEDITOR.instances['cancellation_policy'].setData('');
                $("#addTask").modal("hide");
                $("#addTask").attr('modal-ref', '');
                $("#cancellation_policy_chk").prop('checked', false);
            });
       });

        $("#terms_conditions_chk").change( function()
        {
            if($('#terms_conditions_chk').is(':checked')) 
            {

                $("#addTask .modal-title").text("Are you sure, you want to overwrite existing terms conditions with default terms conditions from settings?");
                $("#addTask").modal("show");
                $("#addTask").attr('modal-ref', 'terms_conditions_chk');
          
            }else{
                CKEDITOR.instances['terms_conditions'].setData('');
              }
            $(document).on("click", "[modal-ref=terms_conditions_chk] .save_task", function () {  
                CKEDITOR.instances['terms_conditions'].setData(` {!! $pack_terms_conditions!!}`);
                $("#addTask").modal("hide");
                 $("#addTask").attr('modal-ref', '');
            });

            $(document).on("click", "[modal-ref=terms_conditions_chk] .close_task", function () {
                CKEDITOR.instances['terms_conditions'].setData('');
                $("#addTask").modal("hide");
                $("#addTask").attr('modal-ref', '');
                $("#terms_conditions_chk").prop('checked', false);
            });
       });


        $('#is_activity').on('click',function () {
            var is_activity = $(this);
            if (is_activity.is(':checked')) {
                $("#group").prop("checked", true);
                $('#duration_div').show();
            } else {
                $('#duration_div').hide();
            }
        });        

        $('.CommonTourType').on('click',function (e) {
            var is_activity = $('#is_activity');
            if (is_activity.is(':checked')) {
                e.preventDefault();
                return false;
            } else {
                return true;
            }
        });

    });

    </script>
    <script>
        var description = document.getElementById('description');
        CKEDITOR.replace(description);

        var inclusions = document.getElementById('inclusions');
        CKEDITOR.replace(inclusions);

        var exclusions = document.getElementById('exclusions');
        CKEDITOR.replace(exclusions);

        var payment_policy = document.getElementById('payment_policy');
        CKEDITOR.replace(payment_policy);

        var cancellation_policy = document.getElementById('cancellation_policy');
        CKEDITOR.replace(cancellation_policy);

        var terms_conditions = document.getElementById('terms_conditions');
        CKEDITOR.replace(terms_conditions);

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
                        url: "{{ route($routeName.'.'.$segment.'.ajax_delete_image') }}",
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

        /*var Preiod_box = `<div class="row show_specific_period_form">
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
            })*/

            $(document).click(function(e){
               var Element = $(e.target)
               if(Element.hasClass('cross')){
                   Element.closest('.show_specific_period_form').remove();
               }
            })

            /*$('body').on('focus',".mycalender", function(){
                $(this).datepicker({
                    minDate: 0,
                    dateFormat: 'dd-mm-yy',
                    changeMonth: true,
                    changeYear: true
                });
            });*/


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