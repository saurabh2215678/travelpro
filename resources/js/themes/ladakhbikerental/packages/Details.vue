<template>
    <!-- <SearchFormWithBanner
    title="Search For Packages"
    /> -->
    <DetailGalleryWrapper>
        <FancyboxWrapper className="package_banner_box">
            <!-- <PackageImageSlider :data="package.images"/> -->
            <PackageImageSlider :data="package.images" />
            <div class="container">
                <h1 class="package_title font40 color_light fw600">{{package.title}}</h1>
                <div class="package_subtitle font16 color_theme">{{package.subtitle}}</div>
            </div>
        </FancyboxWrapper>
        <div class="after_banner_sec">
            <div class="container">
                <div class="flex flex-wrap justify-between items-center">
                    <ul class="flex flex-wrap justify-between md:w-8/12 logo_ul">
                        <li>
                            <!-- <div class="icon pr-5">
                                <i class="fa-regular fa-calendar-days font30"></i>
                            </div> -->
                            <div class="timing">
                                <img src="/assets/ladakhbikerental/images/white_logo.png" alt="logo" preload="">
                                <h6 class="font14">{{package.title}}</h6>
                                <h5 class="font16">{{package.package_duration}}</h5>
                                <div class="price mt-2" v-if="package.packagePrices && package.packagePrices.length > 0">
                                    <h6 class="font15"> Starting From : <span class="amount heading_sm3">{{showPrice(package.search_price,true)}} </span></h6>
                                </div>
                                <h6 class="font14 mt-2"> Share with friends</h6>
                                <ul class="share_btns">
                                    <li v-if="sharing_links.facebook"><a :href="sharing_links.facebook"><i class="fa-brands fa-square-facebook"></i></a></li>
                                    <li v-if="sharing_links.twitter"><a :href="sharing_links.twitter"><i class="fa-brands fa-twitter"></i></a></li>
                                    <li v-if="sharing_links.instagram"><a :href="sharing_links.instagram"><i class="fa-brands fa-square-instagram"></i></a></li>
                                    <li v-if="sharing_links.whatsapp"><a :href="sharing_links.whatsapp"><i class="fa-brands fa-square-whatsapp"></i></a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="flex flex-wrap items-center">
                            <ul class="w-full static_icns">
                                <li>
                                    <p>
                                        <img src="/assets/ladakhbikerental/images/icons1.png" />
                                        <span>Expert Guides & Highly Train Crew</span>
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <img src="/assets/ladakhbikerental/images/icons2.png" />
                                        <span>Customised Itinerary</span>
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <img src="/assets/ladakhbikerental/images/icons3.png" />
                                        <span>Well Maintain Bikes</span>
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <img src="/assets/ladakhbikerental/images/icons4.png" />
                                        <span>Stay On Some Best Locations</span>
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        <img src="/assets/ladakhbikerental/images/icons5.png" />
                                        <span>High Value Of Money</span>
                                    </p>
                                </li>
                                <!-- <li class="flex justify-between mb-4">
                                    <div class="flex items-center w_170">
                                        <div class="icn mr-3">
                                            <i class="fa fa-bed" aria-hidden="true"></i>
                                        </div>
                                        <h5 class="font16">Accommodation:</h5>
                                    </div>
                                    <div class="flex items-center">
                                        <h5 class="font16">Hotel + Lodge</h5>
                                    </div>
                                </li> 
                                <li class="flex justify-between mb-4" v-if="package.best_months && package.best_months.length > 0">
                                    <div class="flex items-center w_170">
                                        <div class="icn mr-3">
                                            <i class="fa-regular fa-thumbs-up"></i>
                                        </div>
                                        <h5 class="font16">Best Month:</h5>
                                    </div>
                                    <div class="flex items-center">
                                        <h5 class="font16">{{package.best_months}}</h5>
                                    </div>
                                </li>-->
                            </ul>
                        </li>
                    </ul>
                    <ul class="md:w-1/4 w-full call_ul">
                        <li> 
                            <h5 class="font19 whatsapp_img">
                                <img src="/assets/ladakhbikerental/images/whatsapp_scan.png" />
                            </h5>
                        </li>
                        <li>
                            <h5 class="font19">
                                <i class="fa-regular fa-envelope font22 mr-2"></i><a :href="`mailto:${store.websiteSettings?.SALES_EMAIL}`">{{store.websiteSettings?.SALES_EMAIL}}</a>
                            </h5>
                        </li>
                        <li>
                            <Link :href="`${package.enquiry_url}&type=${typename}`" class="enquiry_btn fw600 font18">Enquiry</Link>
                            <!-- <a href="/" class="enquiry_btn fw600 font18">Enquiry</a> -->
                        </li>
                        <li>
                            <input type="hidden" name="action" value="package_booking">
                            <input type="hidden" name="package" :value="package.id">
                            <input type="button" class="btn fw600 font18" name="submit" value="Book Now" @click="bookNow" v-if="this.isOnlineBooking('package_listing') && store?.myEvents?.length > 0">
                            <!-- <a href="/" class="btn fw600 font18">Book Now</a> -->
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </DetailGalleryWrapper>
     <!--- ======== Package Days  ======== -->

    <SinglePackageWrapper class="single_package_details py-0 mb-5">
            <!-- <div class="row">
                <div class="container">
                    <div class="right_side">
                        <div id="myForm">
                            <div class="theme_title mt-7">
                                <h1 class="title text-2xl">
                                    <p class="day_night_detail">
                                        <strong class="day_night text-base">{{package.package_duration}}</strong>
                                    </p> 
                                    {{package.title}}
                                </h1>
                            </div>
                            <div class="form_box">
                                <div class="city-list-block">
                                    <ul>
                                        <li class="full_field mb-3">
                                            <p class="text-sm">
                                                <span class="package_tour_type_text">{{package.package_tour_type_text}}</span> 
                                                <template v-for="dnc_value in package.days_nights_city" :key="dnc_value" >
                                                    <span v-html="dnc_value"></span>
                                                </template>
                                            </p>
                                        </li>
                                    </ul>
                                    <div id="element" class="btn btn-default show-modal mt-5" @click="showItenaryPopup">Download Itinerary</div>
                                </div>
                                <div class="flex items-center flex-wrap justify_btwn">
                                    <PackageRightPrice  
                                    :package="package" 
                                    :packageSelectedPrice="packageSelectedPrice" 
                                    :faq_row="faq_row" 
                                    :destinations="destinations" 
                                    :itenaries="itenaries" 
                                    :calenderPop="this.calenderPop"
                                    :showCalenderPopup="this.showCalenderPopup"
                                    :hideCalenderPopup="this.hideCalenderPopup"
                                    />

                                    <FancyboxWrapper className="left_price_box">
                                        <PackageImageSlider :data="package.images"/>
                                    </FancyboxWrapper>

                                </div>
                            </div>

                            <div class="inclusions_share flex flex-wrap">
                                <div class="left_side mb-5 pr-8 w-6/12 icon-wishlist relative">
                                    <ul class="inclusions inclusions_list pt-2">
                                        <li :data-text="package_inclusion.value" v-for="package_inclusion in package.package_inclusions">
                                            <img :src="package_inclusion.image" />
                                            {{package_inclusion.value}}
                                        </li>
                                    </ul>
                                    <a href="javascript:void(0)" class="more_btn"> more... </a>
                                </div>
                                <div class="share-section w-6/12">
                                    <div class="flex py-0 pt-3 pl-1.5 items-center">
                                        <div class="bg_share">
                                            <p class="mr-3">Share It On:</p> 
                                            <div class="footer_bottom_right">
                                                <ul class="social_icon">
                                                    <li class="facebook" v-if="sharing_links.facebook"><a
                                                        :href="sharing_links.facebook"
                                                        target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                                                    </li>
                                                    <li class="twitter" v-if="sharing_links.twitter"><a
                                                        :href="sharing_links.twitter"
                                                        target="_blank"><i class="fa-brands fa-twitter"></i></a>
                                                    </li>
                                                    <li class="whatsapp" v-if="sharing_links.whatsapp"><a
                                                        :href="sharing_links.whatsapp"
                                                        target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                                                    </li>
                                                    <li class="instagram" v-if="sharing_links.instagram"><a
                                                        :href="sharing_links.instagram"
                                                        target="_blank"><i class="fa-brands fa-instagram"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div> -->

            <div class="row">
                <div class="w-full">
                    <div class="listSec">
                        <div class="container">
                            <ul class="flex flex-wrap">
                                <li class="active"><a href="#"><i class="fa-regular fa-file-lines"></i> Overview</a></li>
                                <li><a href="#packageIt"><i class="fa-regular fa-file-lines"></i> Itinerary</a></li>
                                <template v-if="package.PackageInfo && package.PackageInfo.length > 0">
                                    <li v-for="info in package.PackageInfo"><a :href="`#pInfo${info.id}`"><i class="fa-regular fa-file-lines"></i> {{info.title}}</a></li>
                                </template>
                                 <li v-if="package.video_link"><a href="#videos">Videos</a></li>
                                 <li v-if="package.images"><a href="#photo_gallery"><i class="fa-regular fa-images"></i> Photos</a></li>
                                <!--<li><a href="#packageIt"><i class="fa-regular fa-file-lines"></i> Itinerary</a></li>
                                <li><a href="#"><i class="fa-regular fa-circle-check"></i> Cost Includes</a></li>
                                <li><a href="#"><i class="fa-solid fa-calendar-days"></i> Fixed Dates</a></li>
                                <li><a href="#"><i class="fa-regular fa-images"></i> Photos</a></li>
                                <li v-if="store.accommodations_days && store.accommodations_days.length > 0"><a href="#package_accommodations">Accommodation</a></li> -->
                                <li v-if="this.testimonials && this.testimonials.length > 0"><a href="#review">Review</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="container">
                        <div>
                            <div id="myForm" class="flex flex-wrap pt-16 flex_package right_side">
                                <div class="md:w-4/6 mo_full w-full">
                                    <div class="download_it flex justify-end">
                                        <div id="element" class="btn btn-default show-modal mt-5" @click="showItenaryPopup" v-if="store?.myEvents?.length > 0">Download Itinerary</div>
                                    </div>
                                    <!-- <div>
                                        <div class="theme_title py-3">
                                            <h1 class="title text-2xl">
                                                <p class="day_night_detail">
                                                    <strong class="day_night text-base">{{package.package_duration}}</strong>
                                                </p> 
                                            </h1>
                                        </div>
                                        <div class="city-list-block">
                                            <ul>
                                                <li class="full_field mb-3">
                                                    <p class="text-sm">
                                                        <span class="package_tour_type_text">{{package.package_tour_type_text}}</span> 
                                                        <template v-for="dnc_value in package.days_nights_city" :key="dnc_value" >
                                                            <span v-html="dnc_value"></span>
                                                        </template>
                                                    </p>
                                                </li>
                                            </ul>
                                            
                                        </div>
                                        <div class="left_side mb-5 pr-8 icon-wishlist relative">
                                            <ul class="inclusions inclusions_list pt-2">
                                                <li :data-text="package_inclusion.value" v-for="package_inclusion in package.package_inclusions">
                                                    <img :alt="package_inclusion.value" :src="package_inclusion.image" />
                                                    {{package_inclusion.value}}
                                                    
                                                </li>
                                            </ul>
                                            <a href="javascript:void(0)" class="more_btn"> more... </a>
                                        </div>
                                            <PackageRightPrice  
                                            :package="package"
                                            :defaultPriceId="default_price_id"
                                            :faq_row="faq_row"
                                            :destinations="destinations"
                                            :itenaries="itenaries"
                                            :calenderPop="this.calenderPop"
                                            :showCalenderPopup="this.showCalenderPopup"
                                            :hideCalenderPopup="this.hideCalenderPopup"
                                            />
                                    
                                            </div>
                                    -->
                                    <!--- ======== Description  ======== -->
                                    <div class="package_disc overview_box mt-5 mb-5" v-if="package.brief">
                                        <!-- <div class="package_disc flex items-center mt-5 mb-5">
                                            <h2 class="text-base font-semibold mr-3">Activities</h2>
                                            <div class="list_row_right">
                                                <ul class="activ_list">
                                                    <li v-for="pc in package.packageCategories" :key="pc.id" v-html="pc.title"></li>
                                                </ul>
                                            </div>
                                        </div> -->
                                        <div class="font26 color_theme fw700 pb-3">TRIP OVERVIEW</div>
                                        <p v-html="package.brief"></p>
                                        <p v-html="package.description" v-if="this.readMore"></p>
                                        <template v-if="package.description"><span id="s_more" @click="handleReadMore">{{(this.readMore)?'Read Less':'Read More'}}</span></template>
                                    </div>
                                    <!--- ======== Flight  ======== -->
                                    <div class="bg-slate-100 mt-3 p-4" v-if="package.PackageFlights && package.PackageFlights.length > 0">
                                        <div class="text-xl font-semibold pt-2">{{package.PackageFlights.length}} Flights in the package</div>
                                        <p class="para_lg2">
                                            <ul class="flight_list">
                                                <li class="mt-0 rowid-24" v-for="flight in package.PackageFlights">
                                                    <img src="../assets/images/flight.gif" alt="Air India"
                                                    class="logo">{{flight.airline_name}} | {{flight.flight_number}} | {{flight.flight_departure}} <i class="fa fa-long-arrow-right"></i>
                                                    {{flight.flight_arrival}}
                                                </li>
                                            </ul>
                                        </p>
                                    </div>
                                
                                    <div id="packageIt" class="package_day secpad" v-if="this.package_itenaries && this.package_itenaries.length > 0 && package.is_activity == 0">
                                        <div class="container-full">
                                            <h3 class="font26 color_theme fw700 pb-3">TOUR ITINERARY</h3>
                                            <div class="faqlist faqlist_in">
                                                <ul>
                                                    <li class="faq_main flex flex-wrap" v-for="itenary in this.package_itenaries"> 
                                                        <div class="itenary_day w-28">
                                                            <div class="day_curcle"><span>{{itenary.itenary_day_title}}</span></div>
                                                        </div> 
                                                        <div class="itnry_text">
                                                            <div class="faq_title font16 ">{{itenary.itenary_title}}</div>
                                                            <div class="theme_tags mb-3" v-if="itenary.itenaryTags && itenary.itenaryTags.length > 0">
                                                                <span class="tags mr-2" v-for="itag in itenary.itenaryTags">
                                                                    {{itag}}
                                                                </span>
                                                            </div>

                                                            <p class="pb-2" v-if="itenary.meal_option_arr && itenary.meal_option_arr.length > 0">
                                                                Meal :
                                                                <template v-for="meal_option_val,index in itenary.meal_option_arr">
                                                                    {{(index>0)?', ':''}}
                                                                    {{meal_option_val}}
                                                                </template>
                                                            </p>

                                                            <div class="faq_data">
                                                                <!-- <div class="faq_service package_disc overview_box pb-3 mb-3" v-if="itenary.itenary_inclusions_arr && itenary.itenary_inclusions_arr.length > 0">
                                                                    <div class="title2 mb-1 text-base font-semibold">Services Included</div>
                                                                    <ul class="include_list inclusions" style="clear: both;display: block;width: 100%;">
                                                                        <template v-for="inclusion in itenary.itenary_inclusions_arr">
                                                                            <li :data-text="inclusion.title" style="float: left;text-align: center;margin-right: 15px;" >
                                                                                <img style="" :src="inclusion.image"/>
                                                                                <span>{{inclusion.title}}</span>
                                                                            </li>
                                                                        </template>
                                                                    </ul>
                                                                </div>
                                                                 -->
                                                                <div class="text-lg font-semibold" v-if="itenary.itenary_location">{{itenary.itenary_location}}</div>
                                                                <div class="right-content-itinerary" v-html="itenary.itenary_description"></div>
                                                                <div class="left-img-itinerary pt-2" v-if="itenary.itenarySrc">
                                                                    <img :src="itenary.itenarySrc" :alt="itenary.itenary_title" />
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </li>                                           
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--- ======== Accommodation Hotel ======== -->
                                    <PackageAccommodation v-if="store.accommodations_days && store.accommodations_days.length > 0" />
                                    <!--- ======== Accommodation  Old ======== -->
                                    <div class="accommodation accordion mt-5 mb-5" v-if="this.packagePolicy()">
                                        <h3 class="font26 color_theme fw700 pb-3">MORE DETAILS</h3>
                                        <div class="container1">
                                            
                                            <div class="faqlist">
                                                <ul>
                                                    <li class="faq_main" v-if="package.inclusions">
                                                        <div class="faq_title heading6">Inclusions</div>
                                                        <div class="faq_data">
                                                            <span v-html="package.inclusions"></span>
                                                        </div>
                                                    </li>
                                                    <li class="faq_main" v-if="package.exclusions">
                                                        <div class="faq_title heading6">Exclusions</div>
                                                        <div class="faq_data">
                                                            <span v-html="package.exclusions"></span>
                                                        </div>
                                                    </li>

                                                    <li class="faq_main" v-if="package.payment_policy">
                                                        <div>
                                                            <div class="faq_title heading6">Payment Policy</div>
                                                            <div class="faq_data" style="">
                                                                <span  v-html="package.payment_policy"></span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="faq_main" v-if="package.cancellation_policy">
                                                        <div>
                                                            <div class="faq_title heading6">Cancellation Policy</div>
                                                            <div class="faq_data" style="">
                                                                <span  v-html="package.cancellation_policy"></span>

                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="faq_main" v-if="package.terms_conditions">
                                                        <div>
                                                            <div class="faq_title heading6">Terms and Conditions</div>
                                                            <div class="faq_data" style="">
                                                                <span  v-html="package.terms_conditions"></span>

                                                            </div>
                                                        </div>
                                                    </li>


                                                    <template v-if="package.PackageInfo && package.PackageInfo.length > 0" v-for="info in package.PackageInfo">
                                                        <li class="faq_main" :id="`pInfo${info.id}`" v-if="info.title && info.description">
                                                            <div>
                                                                <div class="faq_title heading6">{{info.title}}</div>
                                                                <div class="faq_data" style="">
                                                                    <span v-html="info.description"></span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </template>
                                                </ul>

                                                <!-- Frequently Asked Questions -->
                                                <template v-if="faq_row && faq_row.length > 0">
                                                    <br>
                                                    <div class="theme_title mb-3">
                                                        <h3 class="text-xl font-bold">FAQs About {{package.title}}</h3>
                                                    </div>
                                                    <ul>
                                                        <li class="faq_main"  v-for="faq in faq_row">
                                                            <div>
                                                                <div class="faq_title heading6"><strong>Q </strong>{{faq.question}}</div>
                                                                <div class="faq_data" style="">
                                                                    <p v-html="faq.answer"></p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </template>
                                                <!-- Frequently Asked Questions Closed -->
                                                <div  v-if="package.video_link" class="font26 color_theme fw700 pb-3 pt-5" id="videos">Video</div>
                                                <div v-if="package.video_link" v-html="package.video_link"></div>

                                            </div>
                                            
                                        </div>
                                    </div>
                                    <!-- <div class="btn_groups">
                                        <a href="#packageEnquire_form" class="btn btn_enquire">Enquire Now</a>
                                    </div>  -->
                                    <!-- <div class="flex flex-wrap mb-5">
                                        <div class="w-1/2">
                                            <div class="font26 color_theme fw700 pb-3">Cost Includes</div>
                                            <span v-html="package.inclusions" class="cost_list"></span>
                                        </div>
                                        <div class="w-1/2">
                                            <div class="font26 color_theme fw700 pb-3">Cost Excludes</div>
                                            <span v-html="package.exclusions" class="cost_list"></span>
                                        </div>
                                    </div> -->

                                    <div class="font26 color_theme fw700 pb-3 pt-5" id="photo_gallery">Photo</div>

                                    <DetailGalleryWrapper>
                                        <FancyboxWrapper className="package_banner_box">
                                            <!-- <PackageImageSlider :data="package.images"/> -->
                                            <PackageImageSlider :data="package.images" :showall="true"/>
                                        </FancyboxWrapper>
                                    </DetailGalleryWrapper>




                                    <testimonialSlider :testimonials="this.testimonials" v-if="this.testimonials && this.testimonials.length > 0"/>
                                        <!-- <div class="share-section mt-5 w-full bg-gray-100">
                                            <div class="flex py-0 pl-1.5 items-center">
                                                <div class="bg_share">
                                                    <p class="mr-3">Share It On:</p>
                                                    <div class="footer_bottom_right">
                                                        <ul class="social_icon">
                                                            <li class="facebook" v-if="sharing_links.facebook"><a
                                                                :href="sharing_links.facebook"
                                                                target="_blank"><i class="fa-brands fa-facebook-f"></i> Share</a>
                                                            </li>
                                                            <li class="twitter" v-if="sharing_links.twitter"><a
                                                                :href="sharing_links.twitter"
                                                                target="_blank"><i class="fa-brands fa-twitter"></i> Tweet</a>
                                                            </li>
                                                            <li class="whatsapp" v-if="sharing_links.whatsapp"><a
                                                                :href="sharing_links.whatsapp"
                                                                target="_blank"><i class="fa-brands fa-whatsapp"></i> Whatsapp</a>
                                                            </li>
                                                            <li class="instagram" v-if="sharing_links.instagram"><a
                                                                :href="sharing_links.instagram"
                                                                target="_blank"><i class="fa-brands fa-instagram"></i> Instagram</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                </div>
                                <div class="md:w-4/12 mt-5 mo_full w-full md:pl-9" v-if="this.package && this.package.id">
                                    <div class="duration_frm">
                                        <h1 class="font19"><strong>Duration:</strong> {{package.package_duration}}</h1>
                                        <div class="form_box-new" id="rider_pillion_form">
                                        <div class="forminner">
                                            <form method="get" :action="package.enquiry_url">
                                                <input type="hidden" name="package" :value="package.slug">
                                                <div class="form-floating mb-3 mt-3">
                                                    <label>Rider:</label>
                                                    <select name="rider" class="form-control">
                                                        <option value="">Select</option>
                                                        <template v-for="counter in 13">
                                                            <option :value="counter">{{counter}}</option>
                                                        </template>
                                                    </select>
                                                </div>
                                                <div class="form-floating">
                                                    <label>Pillion:</label>
                                                    <select name="pillion" class="form-control">
                                                        <option value="">Select</option>
                                                        <template v-for="counter in 13">
                                                            <option :value="counter">{{counter}}</option>
                                                        </template>
                                                    </select>
                                                </div>
                                                <div class="mt-3 text-center">                                  
                                                    <button type="submit" class="btn btn-outline btn-theme">Enquire Now</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="form_box-new single_package_form sticky_form" id="packageEnquire_form">
                                        <div class="form_box_inner">
                                            <div class="flex">
                                                <div class="bike_icon">
                                                    <i class="fa-solid fa-motorcycle"></i>
                                                </div>
                                                <div class="form_txt">
                                                    <h3 class="text-xl font-semibold">CUSTOMIZED TOUR</h3>
                                                </div>
                                            </div>
                                            <p class="mb-3">DO YOU WANT US TO SUGGEST A BEST TRIP FOR YOU ?</p>
                                            <formShortCode slug="[your_preference]" class="left_form" :hidden="{'package':this.package.id}" />
                                        </div>
                                    </div>
                                    <!-- <div class="wcu">
                                    <div class="title">Why Choose Us?</div>
                                        <ul>
                                            <li>
                                                <div class="wcu-img">
                                                    <img src="https://www.andamanisland.in/assets/site1/images/icon/Experience.png" class="img-responsive" alt="Experience"> 
                                                </div>
                                                <div class="wcu-para">
                                                    <span>Experience</span>
                                                    <p>Located in the Andaman Islands and having great love and knowledge for it, we have gained specialisation in the Andaman tourism industry. We have over 16 years of experience in the field and this makes us the best travel expert who can give you the best recommendations.</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="wcu-img">
                                                    <img src="https://www.andamanisland.in/assets/site1/images/icon/Tour-Packages.png" class="img-responsive" alt="Tour Packages"> 
                                                </div>
                                                <div class="wcu-para">
                                                    <span>Tour Packages</span>
                                                    <p>Whatever your budget is, our offered customized Andamans tour packages will help you explore this beautiful destination without burning a hole in your pocket. We offer first-class services to all kinds of travellers—luxury/budget/solo/family/romantic, etc.</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="wcu-img">
                                                    <img src="https://www.andamanisland.in/assets/site1/images/icon/Customer-Support-24X7.png" class="img-responsive" alt="Customer Support 24X7"> 
                                                </div>
                                                <div class="wcu-para">
                                                    <span>Customer Support 24X7</span>
                                                    <p>We aim at offering the most affordable tour packages so that everyone can have the best time at the pristine land of the Andaman Islands. Our staff comprising reservations, sales, and tour experts are locals of the Andamans. Thus, you are sure to get the best suggestions for your vacation. Moreover, our customer support service is available 24X7.</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="wcu-img">
                                                    <img src="https://www.andamanisland.in/assets/site1/images/icon/Offers-&amp;-Discounts.png" class="img-responsive" alt="Offers &amp; Discounts"> 
                                                </div>
                                                <div class="wcu-para">
                                                    <span>Offers &amp; Discounts</span>
                                                    <p>Holding vast expertise in promoting Andaman Island tourism, our committed representatives makes sure that you get the best deals on all your bookings. We also guarantee you the best discounts on your stay as we have tie-ups with the most amazing hotels and resorts across the Andaman Islands along with ourselves owning lavish properties as well.</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="wcu-img">
                                                    <img src="https://www.andamanisland.in/assets/site1/images/icon/Service-Guarantee.png" class="img-responsive" alt="Service Guarantee"> 
                                                </div>
                                                <div class="wcu-para">
                                                    <span>Service Guarantee</span>
                                                    <p>With the excellent services, our dedicated hospitality team members make you feel completely comfortable, thereby offering you holidays of a lifetime.</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="wcu-img">
                                                    <img src="https://www.andamanisland.in/assets/site1/images/icon/Our-Experts.png" class="img-responsive" alt="Our Experts"> 
                                                </div>
                                                <div class="wcu-para">
                                                    <span>Our Experts</span>
                                                    <p>The members of our operations team are local residents of the Andaman Islands. Therefore, they are proficient in handling all your queries before, during, and after the tour in the best way possible.</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="wcu-img">
                                                    <img src="https://www.andamanisland.in/assets/site1/images/icon/Special-Request-Services.png" class="img-responsive" alt="Special Request Services"> 
                                                </div>
                                                <div class="wcu-para">
                                                    <span>Special Request Services</span>
                                                    <p>When it comes to your special requests like midnight cake cutting with music and wine, candlelight dinner at the beach, champaign by the poolside, or anything else, we make sure that no stone is left unturned to give you what you have asked for.</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </SinglePackageWrapper>

    

   <!--  <SinglePackageWrapper>
        <SliderSection 
        v-if="this.similarPackages && this.similarPackages.length > 0"
        :sectionData="{data: this.similarPackages}"
        :withPrice="true"
        title="Similar Packages"
        />
    </SinglePackageWrapper> --> 

    <SinglePackageWrapper>
        <HomePackageSlider 
        v-if="this.similarPackages && this.similarPackages.length > 0"
        :sectionData="{data: this.similarPackages}"
        :withPrice="true"
        title="Similar Packages"
        />
    </SinglePackageWrapper>

    <!-- <DestinationWrapper>
        <SliderSection 
        v-if="this.destinationsSectionData && this.destinationsSectionData?.data.length > 0"
        :sectionData="this.destinationsSectionData"
        title="More Trips Departure"
        className="more_trips_departure bg-gray-100"
        :slidePerView="4"
        :spacebetween="30"
        />
    </DestinationWrapper> -->


    <template v-if="store.popupType != 'innerHtml' && this.itenaryPopup">
        <Popup className="download_itnery_pop_wrapper">
            <div class="modal-body download-itinerary">
                <div class="modal-header form-floating mb-3 w-full">
                    <h4 class="modal-title text-2xl">Enquiry Form</h4>
                    <p class="text-sm ">Download itinerary for :<span class="text-teal-500  italic">
                    {{package.title}}</span></p>
                </div>
                <div class="book-space">
                    <formShortCode slug="[download_itinerary]" class="form_list" :hidden="{'package':this.package.id}" name="download_itinerary" />
                </div>
            </div>
        </Popup>
    </template>

</template>

<script>
import SearchForm from '../components/SearchForm.vue';
import Layout from '../components/layout.vue';
import { store } from '../store';
import FancyboxWrapper from '../components/FancyboxWrapper.vue';
import formShortCode from '../components/formShortCode.vue';
import Popup from '../components/popup.vue';
import PackageRightPrice from '../components/package/PackageRightPrice.vue';
import PackageAccommodation from '../components/package/PackageAccommodation.vue';
import {showPopup, hidePopup, isOnlineBooking} from '../utils/commonFuntions';
import testimonialSlider from '../components/testimonial/testimonialSlider.vue';
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import {SinglePackageWrapper} from './detailStyles';
import SliderSection from '../components/SliderSection.vue';
import HomePackageSlider from "../components/home/HomePackageSlider.vue";
import styled from 'vue3-styled-components';
import PackageImageSlider from '../components/package/PackageImageGallery.vue';
import axios from "axios";
import { Link } from '@inertiajs/inertia-vue3';
import { showPrice } from '../utils/commonFuntions';

const DestinationWrapper = styled.section`
    padding-top: 1rem;

    
    & .package_image_box{height: 13.125rem;}

    @media (max-width: 767px){
        padding-bottom: 0;
    }
`

const DetailGalleryWrapper = styled.section`
position: relative;
/* margin-top: -5.3rem; */
& .package_banner_box{position:relative;}
& .package_title{position:absolute;bottom:2rem;background: var(--black400); padding:0 1rem;}
& .after_banner_sec{background:#4d4d4f;padding: 2.063rem 0;}
& .after_banner_sec {color:var(--white);}
& .after_banner_sec p{color:#7e7e80;}
& .btn, .enquiry_btn{text-align: center;}
& .duration_frm {background: #0087be;color: #fff;padding: 2rem;margin-bottom: 2rem;}
& .faq_title{color:#4b4b4d;}
& .logo_ul>li:nth-child(2){width:43%;}
& .logo_ul>li:first-child{width:50%;}
& .call_ul li{border-bottom: 1px solid #e5e5e5;width: 100%;margin-bottom: 1rem;padding-bottom: 1rem;}
& .call_ul li:nth-last-child(2){border:none;padding-bottom:0;margin-bottom:0;}
& .call_ul li:last-child{border:none;padding-bottom:0;margin-bottom:0;}
& .call_ul li .enquiry_btn {background:var(--theme-color);color:#fff;}
& .timing{text-align:center;}
& .timing img{margin:0 auto;margin-bottom:1rem;}
& .icn {background: #fff;width: 40px;height: 40px;color: #0087be;border-radius: 50%;display: flex;align-items: center;justify-content: center;}
& .share_btns{display:flex;justify-content: center;margin-top: .2rem;}
& .share_btns li{margin-right: 8px;}
& .share_btns li i{font-size:1.5rem;}
& .w_170{width:170px;}
& .static_icns li p{display: flex;align-items: center;margin-bottom: 0.5rem;    color: #c9c9cb;}
& .static_icns li p img{margin-right:1rem;width:40px;height:auto;}
& .whatsapp_img img{width:35%;}
& .price{
    background: var(--theme-color);
    width: fit-content;
    margin: 0 auto;
    padding: 0.2rem 1rem;
    margin-top: .5rem;
}



@media (max-width: 767px){
    & .timing img{margin:0;}
    & .timing{text-align:left;}
    & .share_btns{justify-content:flex-start;}
    & .static_icns li p{margin-bottom:0;}
    & .logo_ul>li:nth-child(2){width:100%;}
    & .logo_ul>li{width:50%;}
    & .logo_ul>li:first-child{width:100%;}
    & .package_title{font-size: 2rem;line-height: 1.3;}
    & .after_banner_sec ul li{
        padding-bottom:1rem;
        & .icon{
            padding-right:.5rem;
            & i{font-size:1.2rem;}
        }
    }
    & .after_banner_sec ul:last-child{margin-top:1rem;}
    
}

`

export default {
    created() {
        document.body.classList.add('package-detail-page');
        document.body.classList.add('holiday_package');
        store.searchData = this.search_data;
        store.seoData = this.seo_data;                    
        this.package_itenaries = this.itenaries;
        //this.package_total_price = this.packageSelectedPrice?.final_amount;
        //this.package_booking_price = this.packageSelectedPrice?.booking_price;
        console.log('similarPackages==', this.similarPackages);
         console.log('package_inclusions123==',this.package);

        const newSectionData = this.destinations;
        newSectionData.forEach(item => item.thumbSrc = item.image);
        this.destinationsSectionData = {
            data : newSectionData
        }
    },
    data() {
        return {
            store,
            package_itenaries: [],
            package_booking_price: 0,
            package_total_price: 0,
            itenaryPopup:false,
            calenderPop :false,
            readMore:false,
            destinationsSectionData: {}
        }
    },
    methods: {
        showPrice,
        isOnlineBooking,
         bookNow() {
            var currentObj = this;
            currentObj.processing = true;
            currentObj.errors = {};
            axios.post('/book_now', new FormData($('#bookNowForm')[0]))
            .then(function (response) {  
                currentObj.processing = false;
                if(response.data.success) {
                    // window.location.href = response.data.redirect_url;
                    currentObj.$inertia.get(response.data.redirect_url);
                } else {
                    alert('Something went wrong, please try again.');
                }
            }).catch(function (e) {
                var response = e.response.data;
                if(response) {
                    currentObj.parseBookingErrorMessages(response, 'myForm', 'Book Online');
                }
            });
        },

        packagePolicy(){
            let package_policy = false;
            let packageData = this.package;
            if(packageData.inclusions || packageData.exclusion || packageData.payment_policy || packageData.cancellation_policy || packageData.terms_conditions || (packageData.PackageInfo && packageData.PackageInfo.length > 0) || (this.faq_row && this.faq_row.length > 0) || packageData.video_link){
              package_policy = true; 
            }
            return package_policy;
        },
        handleReadMore() {
            this.readMore = !this.readMore;
        },
        showItenaryPopup(){
            var currentObj = this;
            currentObj.processing = true;
            currentObj.errors = {};
            let formData = new FormData($('#bookNowForm')[0]);
            if(this.package.package_tour_type == 'open') {
                formData.append('is_download_itinerary',1);
            }
            axios.post('/book_now', formData)
            .then(function (response) {
                currentObj.processing = false;
                if(response.data.success) {
                    // window.location.href = response.data.redirect_url;
                    // currentObj.$inertia.get(response.data.redirect_url);
                    store.popupType = 'simple';
                    currentObj.itenaryPopup = true;
                    currentObj.calenderPop = false;
                    showPopup();
                } else {
                    alert('Something went wrong, please try again.');
                }
            }).catch(function (e) {
                var response = e.response.data;
                if(response) {
                    currentObj.parseBookingErrorMessages(response, 'myForm', 'Book Online');
                }
            });
        },
        parseBookingErrorMessages(response, formID, boxText) {
            if(response.errors) {
                var errors = response.errors;
                var message='';
                $.each(errors, function (key, val) {
                    $('#'+formID).find("#" + key + "_error").text(val[0]);
                });
            }
        },
        showCalenderPopup(){
            this.calenderPop = true;
            this.itenaryPopup = false;
            store.popupType = 'simple';
            showPopup();
        },
        hideCalenderPopup(){
            this.calenderPop = false;
            this.itenaryPopup = false;
            hidePopup();
        }
    },
    mounted () {
        // ====== REMOVE SPACE IN P TAG =======
        var faqData = document.querySelectorAll('.faq_data p');
        faqData.forEach(function(element) {
            var innerText = element.textContent.trim();
            if (innerText === '') {
                element.style.display = 'none';
            }
        });
    },
    beforeUnmount() {
        document.body.classList.remove('package-detail-page');
        document.body.classList.remove('holiday_package');
    },
    beforeDestroy () {
    },

    watch:{
    },
    components: {
        SearchForm,
        FancyboxWrapper,
        Popup,
        PackageRightPrice,
        PackageAccommodation,
        formShortCode,
        testimonialSlider,
        SearchFormWithBanner,
        SinglePackageWrapper,
        SliderSection,
        DestinationWrapper,
        PackageImageSlider,
        DetailGalleryWrapper,
        HomePackageSlider,
        Link,
    },
    layout: Layout,
    props: ["search_data", "seo_data", "package", "default_price_id","faq_row","destinations","itenaries","sharing_links", "testimonials", "similarPackages","best_months","packageData"],
};
    </script>
<style>
html {
  scroll-behavior: smooth;
  scroll-padding-top: 80px;
}
</style>