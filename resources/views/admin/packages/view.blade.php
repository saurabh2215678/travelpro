
@component('admin.layouts.main')

@slot('title')
Admin - Manage Package View - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
@endslot

<?php
    $tour_type = (isset($package->tour_type))?$package->tour_type:'';
    $title = (isset($package->title))?$package->title:'';
    $price_category = (isset($package->price_category))?$package->price_category:'';
    $subtitle = (isset($package->subtitle))?$package->subtitle:'';
    $destination_id = (isset($package->destination_id))?$package->destination_id:'';
    $related_destinations = (isset($package->related_destinations))?json_decode($package->related_destinations):[];
    $related_packages = (isset($package->related_packages))?json_decode($package->related_packages):[];
    $best_months = (isset($package->best_months))?json_decode($package->best_months):[];
    $package_duration_days = (isset($package->package_duration_days))?$package->package_duration_days:'';
    $activity_level = (isset($package->activity_level))?$package->activity_level:'';
    $service_level = (isset($package->service_level))?$package->service_level:'';
    $package_type = (isset($package->package_type))?$package->package_type:'';
    $package_inclusions = (isset($package->package_inclusions))?json_decode($package->package_inclusions):[];
    $package_experts = (!empty($package) && (!$package->packageExperts->isEmpty())) ? $package->packageExperts : "";
    $image = (isset($package->image))?$package->image:'';
    $banner_image = (isset($package->banner_image))?$package->banner_image:'';
    $brief = (isset($package->brief))?$package->brief:'';
    $description = (isset($package->description))?$package->description:'';
    $inclusions = (isset($package->inclusions))?$package->inclusions:'';
    $exclusions = (isset($package->exclusions))?$package->exclusions:'';
    $payment_policy = (isset($package->payment_policy))?$package->payment_policy:'';
    $cancellation_policy = (isset($package->cancellation_policy))?$package->cancellation_policy:'';
    $terms_conditions = (isset($package->terms_conditions))?$package->terms_conditions:'';
    $meta_title = (isset($package->meta_title))?$package->meta_title:'';
    $meta_description = (isset($package->meta_description))?$package->meta_description:'';
    $star_ratings = (isset($package->star_ratings))?$package->star_ratings:0;
    $status = (isset($package->status))?$package->status:'';
    $inladakh = (isset($package->inladakh))?$package->inladakh:1;
    $featured = (isset($package->featured))?$package->featured:0;
    $popular = (isset($package->popular))?$package->popular:0;
    $sort_order = (isset($package->sort_order))?$package->sort_order:'';
    $video_link = (isset($package->video_link))?$package->video_link:'';
    $package_categories = (!empty($package) && (!$package->packageCategories->isEmpty())) ? $package->packageCategories : "";
    $package_tags = (!empty($package) && !($package->packageTags->isEmpty())) ? $package->packageTags : "";

    $packageCategories = [];
    if(!empty($package_categories)){
        foreach ($package_categories as  $category) {
            $packageCategories[] = $category->title;
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

    // $storage = Storage::disk('public');
    $path = 'packages/';

    $old_image = $image;
    $old_banner_image = $banner_image;
    $image_req = '';
    $link_req = '';
    
    $relaHotelstObj = "";
    if(!empty($related_destinations)){
        $relaHotelstObj = App\Models\Destination::whereIn('id',$related_destinations)->get();
    }

    $pkgInclusionObj = "";
    if(!empty($package_inclusions)){
        $pkgInclusionObj = App\Models\PackageInclusion::whereIn('id',$package_inclusions)->get();
    }

    $experts = [];
    if(!empty($package_experts)){
        foreach ($package_experts as  $expert) {
            $experts[] = $expert->first_name.' '.$expert->last_name;
        }
        $experts = implode(',',$experts);
    }
    //prd($experts);
    $routeName = CustomHelper::getAdminRouteName();
    //$back_url=CustomHelper::BackUrl(); 
    $back_url = request()->has('back_url') ? request()->input('back_url') : '';
?>




<?php

$active_menu = "packages";

$package_id = isset($package->id)?$package->id:0;
?>
@if(!empty($package))
@include('admin.includes.packageoptionmenu')
@endif
<div class="top_title_admin">
    <div class="title">
    <h2>{{($segment == 'activity')?'Activity':'Packages'}}{{ $page_heading }}</h2>
    </div>
    <div class="add_button">
    @if(CustomHelper::checkPermission('packages','edit'))

    <?php if($package->is_activity == 1){ ?>
        <a href="{{ route($routeName.'.activity.edit', ['id'=>$package->id,'back_url'=>$back_url]) }}" class="btn_admin"><i class="fas fa-edit"  title="Edit Package"></i>Edit Activity</a>
    <?php }else{ ?>
        <a href="{{ route($routeName.'.packages.edit', ['id'=>$package->id,'back_url'=>$back_url]) }}" class="btn_admin"><i class="fas fa-edit"  title="Edit Package"></i>Edit Package</a>
    <?php } ?>
    @endif
    </div>
    </div>

<div class="centersec">
<div class="centersec">
<div class="bgcolor viewsec packageview">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="ajax_msg"></div>
<table border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
        
                
                <tr>
                    <td width="33%">
                        <div><b>Tour Type: </b></div>
                        <div>
                            @if($package->tour_type == 'general') {{'Customized Tour'}} @elseif($package->tour_type == 'open') {{'Open Tour'}} @elseif($package->tour_type == 'fixed') {{'Fixed Tour'}} @elseif($package->tour_type == 'group') {{'Group Tour'}}  @endif
                            @if($package->is_activity)
                            (<strong>Activity</strong>)
                            @endif
                        </div>
                    </td>
                    <td width="33%">
                        <div><b>Price Category: </b></div>
                        <div>{{$package->packagePriceCategory->name??''}}</div>
                    </td>
                    <td width="33%">
                        <div><b>Title: </b></div>
                        <div>{{$package->title}}</div>
                    </td>
                </tr>
                <tr>
                    <td width="33%">
                        <div><b>Sub Title: </b></div>
                        <div>{!! $package->subtitle !!}</div>
                    </td width="33%">
                    <td>
                        <div><b>Destination: </b></div>
                        <div>{{ (isset($package->packageDestination)) ? $package->packageDestination->name:"" }}</div>
                    </td width="33%">
                    <td>
                        <div><b>{{($segment == 'activity')?'Activity':'Packages'}} Duration Days: </b></div>
                        <div>{{$package->package_duration_days}}</div>
                    </td>
                </tr>
                
                <tr>
                    <td width="33%">
                        <div><b>Related Destinations :  </b></div>
                        <div>
                            <?php if(!empty($relaHotelstObj) && !($relaHotelstObj->isEmpty())){
                                foreach ($relaHotelstObj as $hotel) {
                                    $hotelsArr[] = $hotel->name;
                                    }
                                    echo implode(', ', $hotelsArr);
                                }
                            ?>
                        </div>
                    </td width="33%">
                    <td>
                        <div><b>Best months to visit: </b></div>
                        <div>
                            <?php
                                if(!empty($best_months)){
                                    $monthArr = config('custom.months_arr');
                                    foreach ($best_months as $month) {
                                        $monthName[] = $monthArr[$month];
                                    }
                                    echo implode(', ', $monthName);
                                }?>
                        </div>
                    </td>
                    <td width="33%">
                        <div><b>Activity Level: </b></div>
                        <div>
                            <?php
                                $activityArr = config('custom.activity_level_arr');
                                if(!empty($activity_level)){
                                echo $activityArr[$activity_level];
                                }
                            ?>
                        </div>
                    </td>
                </tr>
                               

                <tr>
                    
                    <td>
                        <div><b>{{($segment == 'activity')?'Activity':'Packages'}} Type: </b></div>
                        <div>{{$package->PackageType->package_type??''}}</div>
                    </td>
                    <td>
                        <div><b>{{($segment == 'activity')?'Activity':'Packages'}} Inclusions : </b></div>
                        <div>
                            <?php if(!empty($pkgInclusionObj) && !($pkgInclusionObj->isEmpty())){
                                foreach ($pkgInclusionObj as $inclusion) {
                                    $inclusionArr[] = $inclusion->title;
                                }
                                echo implode(', ', $inclusionArr);
                            }
                            ?>
                        </div>
                    </td>
                    <td>
                        <div><b>Experts : </b></div>
                        <div>
                            <?php
                            $expert_name=[];   
                            $checkPackageExperts = json_decode($package->experts)??[];
                            if($checkPackageExperts){
                                    foreach($checkPackageExperts as $data){
                                       $expert_name[] = $package->getExperName($data);
                                    }
                                }
                                if(isset($expert_name) && !empty($expert_name)){

                                echo implode(",",$expert_name);
                                }
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                
                <?php /*<td >
                    <div><b>Service Level: </b></div>
                    <div>
                        <?php
                        $serviceArr = config('custom.service_level_arr');
                        if(!empty($service_level)){
                        echo $serviceArr[$service_level];
                        }
                       
                    </div>
                </td> */ ?>
                <td colspan="3">
                        <div><b>Payment Policy: </b></div>
                        <div>{!! $package->payment_policy !!}</div>
                    </td>
            </tr>
                
                <tr>
                    
                    <td colspan="3">
                        <div><b>Brief: </b></div>
                        <div>{!! $package->brief !!}</div>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="3">
                        <div><b>Description: </b></div>
                        <div>{!! $package->description !!}</div>
                    </td>
                </tr>

                <tr>
                    <td colspan="3">
                        <div><b>Inclusions: </b></div>
                        <div>{!! $package->inclusions !!}</div> 
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div><b>Exclusions: </b></div>
                        <div>{!! $package->exclusions !!}</div>
                    </td>
                </tr>

                <tr>
                <td colspan="3">
                        <div><b>Cancellation Policy: </b></div>
                        <div>{!! $package->cancellation_policy !!}</div>
                    </td>
                </tr>            

                <tr>
                    
                    <td colspan="3">
                        <div><b>Terms Conditions: </b></div>
                        <div>{!! $package->terms_conditions !!}</div>
                    </td>
                    
                </tr>
                <tr>
                <?php /*<td>
                <div><b>Star Ratings: </b></div>
                <div>{{$package->star_ratings.' Star'}}</div> 
                </td> */ ?>
                    <td>
                        <div><b>Status: </b></div>
                        <div>{{ CustomHelper::getStatusStr($package->status) }}</div>
                    </td>

                    <td>
                        <div><b>Date Created: </b></div>
                        <div>{{ CustomHelper::DateFormat($package->created_at, 'd/m/Y') }}</div>
                    </td>
                  
                </tr>            
                
                <tr>
                    <td>
                        <div><b>Featured: </b></div>
                        <div>{{ CustomHelper::getStatusStr($package->featured) }}</div>
                    </td>

                    <?php 
                    $package_flags = (!empty($package) && (!$package->packageFlags->isEmpty())) ? $package->packageFlags : "";
                    $package_flag_arr = [];
                    if(!empty($package_flags)){
                        foreach ($package_flags as  $pflag) {
                            $package_flag_arr[] = $pflag->id;
                        }
                    }
                    ?>
                    <td>
                        <div><b>Flags: </b></div>
                        <div>
                            <?php $packageflag = '';
                            $package_flags = (!empty($package) && (!$package->packageFlags->isEmpty())) ? $package->packageFlags : "";
                            $package_flag_arr = [];
                            if(!empty($package_flags)){
                                foreach ($package_flags as  $pflag) {
                                    $package_flag_arr[] = $pflag->name;
                                }
                            }
                        echo implode(', ',$package_flag_arr); ?>

                        </div>
                    </td>
                    <td>
                        <div><b>Sort Order: </b></div>
                        <div>{{$package->sort_order}}</div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div><b>Video Link: </b></div>
                        <div>{{$package->video_link}}</div>
                    </td>
                    <td>
                        <div><b>Tags: </b></div>
                        <div><?php 
                        $package_tags = (!empty($package) && !($package->packageTags->isEmpty())) ? $package->packageTags : "";
                        $packageTags = "";
                        if(!empty($package_tags)){
                            $packageTags = [];
                            foreach ($package_tags as  $tag) {
                                $packageTags[] = $tag->name;
                            }
                            $packageTags = implode(',',$packageTags);
                        }
                        echo $packageTags;
                        //prd($packageTags);
                        ?>
                        </div>
                    </td>
                    <td>
                        <div><b>{{($segment == 'activity')?'Activity':'Packages'}} Themes/Categories: </b></div>
                        <div>
                            <?php
                            $package_categories = (!empty($package) && !($package->packageCategories->isEmpty())) ? $package->packageCategories : "";
                        $packageCategories = "";
                        if(!empty($package_categories)){
                            $packageCategories = [];
                            foreach ($package_categories as  $category) {
                                $packageCategories[] = $category->title;
                            }
                            $packageCategories = implode(',',$packageCategories);
                        }
                        echo $packageCategories;
                        //prd($packageTags);
                        ?>
                        </div>
                    </td>
                </tr>
</table>
    </td>
</tr>

</table>
</div>
</div>
@slot('bottomBlock')
@endslot

@endcomponent




