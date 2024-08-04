
@component('admin.layouts.main')

@slot('title')
Admin -  - {{CustomHelper::WebsiteSettings('SYSTEM_TITLE')}}
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
    $package_detail_banners = (isset($package->package_detail_banners))?$package->package_detail_banners:'';
    $brief = (isset($package->brief))?$package->brief:'';
    $description = (isset($package->description))?$package->description:'';
    $meta_title = (isset($package->meta_title))?$package->meta_title:'';
    $meta_description = (isset($package->meta_description))?$package->meta_description:'';
    $star_ratings = (isset($package->star_ratings))?$package->star_ratings:0;
    $status = (isset($package->status))?$package->status:'';
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

    $storage = Storage::disk('public');
    $path = 'packages/';

    $old_image = $image;
    $old_package_detail_banners = $package_detail_banners;
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
?>
<div class="centersec">
<div class="bgcolor viewsec">

    @include('snippets.errors')
    @include('snippets.flash')

<div class="alert_msg"></div>

<table width="1100" border="0" align="center" cellpadding="0" cellspacing="0" class="mainsec" class="table-responsive">

<tr>
    <td width="806" valign="top" class="innersec">
        <table cellspacing="1" class="table table-bordered" cellpadding="0" border="0" width="100%">
        <h2>Package Detail</h2> 
                
                <tr>
                    <td><b>Tour Type: </b></td>
                    <td>{{ ($package->tour_type == 'general') ? 'Customized Tour':'Group Tour' }}</td>
                </tr>

                <tr>
                    <td><b>Title: </b></td>
                    <td>{{$package->title}}</td>
                </tr>

                <tr>
                    <td><b>Price Category: </b></td>
                    <td>{{$package->price_category}}</td>
                </tr>

                <tr>
                    <td><b>Sub Title: </b></td>
                    <td>{{$package->subtitle}}</td>
                </tr>

                <tr>
                    <td><b>Destination: </b></td>
                    <td>{{ $package->packageDestination->name }}</td>
                </tr>
                
                <tr>
                    <td><b>Package Duration Days: </b></td>
                    <td>{{$package->package_duration_days}}</td>
                </tr>

                <tr>
                    <td><b>Related Destinations :  </b></td>
                    <td>
                        <?php if(!empty($relaHotelstObj) && !($relaHotelstObj->isEmpty())){
                        foreach ($relaHotelstObj as $hotel) {
                            $hotelsArr[] = $hotel->name;
                            }
                            echo implode(', ', $hotelsArr);
                        }
                    ?>
                    </td>
                </tr>

                <tr>
                    <td><b>Best months to visit: </b></td>
                    <td>
                        <?php 
                        //prd($best_months);
                        if(!empty($best_months)){
                            $monthArr = config('custom.months_arr');
                            foreach ($best_months as $month) {
                                $monthName[] = $monthArr[$month];
                            }
                            echo implode(', ', $monthName);
                    }
                    ?>
                    </td>
                </tr>

                <tr>
                    <td><b>Activity Level: </b></td>
                    <td>
                        <?php
                        $activityArr = config('custom.activity_level_arr');
                        if(!empty($activity_level)){
                        echo $activityArr[$activity_level];
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><b>Service Level: </b></td>
                    <td>
                        <?php
                        $serviceArr = config('custom.service_level_arr');
                        if(!empty($service_level)){
                        echo $serviceArr[$service_level];
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><b>Package Type: </b></td>
                    <td>{{$package->package_type}}</td>
                </tr>

                <tr>
                    <td><b>Package Inclusions : </b></td>
                    <td>
                        <?php if(!empty($pkgInclusionObj) && !($pkgInclusionObj->isEmpty())){
                            foreach ($pkgInclusionObj as $inclusion) {
                                $inclusionArr[] = $inclusion->title;
                            }
                            echo implode(', ', $inclusionArr);
                        }
                        ?> 
                        </td>
                </tr>

                <tr>
                    <td><b>Experts : </b></td>
                    <td><?php
                        if(!empty($package_experts)){
                            foreach ($package_experts as $expert) {
                                $expertArr[] = $expert->first_name.' '.$expert->last_name;
                            }
                            echo implode(', ', $expertArr);
                        }
                        ?>  
                        </td>
                </tr>

                <tr>
                    <td><b>Brief: </b></td>
                    <td>{!! $package->brief !!}</td>
                </tr>

                <tr>
                    <td><b>Description: </b></td>
                    <td>{!! $package->description !!}</td>
                </tr>

                <tr>
                    <td><b>Meta Title: </b></td>
                    <td>{{$package->meta_title}}</td>
                </tr>

                <tr>
                    <td><b>Meta Description: </b></td>
                    <td>{{$package->meta_description}}</td>
                </tr>

                <tr>
                    <td><b>Star Ratings: </b></td>
                    <td>{{$package->star_ratings.' Star'}}</td>
                </tr>
                
                <tr>
                    <td><b>Status: </b></td>
                    <td>{{ CustomHelper::getStatusStr($package->status) }}</td>
                </tr>

                <tr>
                    <td><b>Featured: </b></td>
                    <td>{{ CustomHelper::getStatusStr($package->featured) }}</td>
                </tr>

                <tr>
                    <td><b>Popular: </b></td>
                    <td>{{ CustomHelper::getStatusStr($package->popular) }}</td>
                </tr>

                <tr>
                    <td><b>Sort Order: </b></td>
                    <td>{{$package->sort_order}}</td>
                </tr>

                <tr>
                    <td><b>Video Link: </b></td>
                    <td>{{$package->video_link}}</td>
                </tr>

                <tr>
                    <td><b>Tags: </b></td>
                    <td><?php 
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
                </td>
                </tr>

                <tr>
                    <td><b>Package Themes/Categories: </b></td>
                    <td>
                        <?php
                        $package_categories = (!empty($package) && (!$package->packageCategories->isEmpty())) ? $package->packageCategories : "";
                        $packageCategories = [];
                        if(!empty($package_categories)){
                            foreach ($package_categories as  $category) {
                                $packageCategories[] = $category->title;
                            }
                        $packageCategories = implode(', ', $packageCategories);
                        }
                        echo  $packageCategories;
                        ?>
                    </td>
                </tr>

                <tr>
                    <td><b>Image: </b></td>
                    <td>
                    <?php
                    if(!empty($image)){
                        if($storage->exists($path.$image)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$image) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?>
                </td>
                </tr>

                <tr>
                    <td><b>Package Detail Banners: </b></td>
                    <td>
                    <?php
                    if(!empty($package_detail_banners)){
                        if($storage->exists($path.$package_detail_banners)){
                    ?>
                        <div class="col-md-2 image_box">
                           <img src="{{ url('/storage/'.$path.'thumb/'.$package_detail_banners) }}" style="width: 100px;">
                       </div>
                    <?php } ?>
                    <?php } ?>
                </td>
                </tr>

                 <tr>
                <td><b>Date Created: </b></td>
                <td>{{ CustomHelper::DateFormat($package->created_at, 'd/m/Y') }}</td>
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




