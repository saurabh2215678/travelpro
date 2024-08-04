<template>
    <Layout :metaTitle="metaTitle" :metaDescription="metaDescription">
        <SearchForm />
        <section class="single_package_details activity_right_side_details py-0">
            <div class="w-full overflow-hidden">
                <div class="row">
                    <div class="xl:container xl:mx-auto">
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
                                                    <span class="package_tour_type_text">{{package.tour_type? package.tour_type :'Fixed'}} Package</span>
                                                    <template v-for="dnc_value in package.days_nights_city" :key="dnc_value" >
                                                        <span v-html="dnc_value"></span>
                                                 </template>
                                                </p>
                                            </li>
                                        </ul>
                                        <div id="element" class="btn btn-default show-modal mt-5" @click="showItenaryPopup">Download Itinerary</div>
                                    </div>
                                    <div class="flex items-center flex-wrap justify_btwn">
                                        <div class="right_price_box">
                                            <div class="par_cost pb-2 text-sm">From <span>₹60</span> per person</div>
                                            <div class="scac flex items-center">
                                                <ul class="form_list w-full float-left">
                                                    <li class="w-2/4">
                                                        <div class="package_type">
                                                            <label class="text-sm">Package Type</label>
                                                            <div class="custom_select">
                                                                <select class="form_control" name="package_type">
                                                                    <option v-for="price in package.packagePrices" :key="price.id" value="{{price.id}}">{{price.title}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                            <div class="single_package_day">
                                                <ul class="form_list w-full float-left">
                                                    <li class="w-2/4">
                                                        <div class="form_group icon_calender inline-block">
                                                            <label class="text-sm">Trip Date</label>
                                                            <div class="departure_form">
                                                                <input @click="showClaenderPopup" type="text"
                                                                    name="trip_date" id="trip_date" class="form_control"
                                                                    autocomplete="off" data-popup-btn="departure-date" />
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="w-2/4">
                                                        <label class="text-sm font-bold">Number of Persons</label>
                                                        <fieldset class="scheduler-border booking_fields">
                                                            <legend class="select_trav font-normal" @click="showSelectPersonsPopup">1 Travellers</legend>
                                                            <span id="travellers_error" class="validation_error"></span>
                                                        </fieldset>
                                                    </li>
                                                </ul>
                                                
                                                
                                            </div>
                                            <div class="slot_time">
                                                <a class="apply_slot" href="#" id="applyTripTravellers">Check Availability</a>
                                                <div class="time_list mb-2 active">
                                                    <label class="text-sm pb-0">Please select the slot</label>
                                                    <ul id="cont"><li data-id="04:00" class="active">04:00 AM</li></ul>
                                                </div>
                                                </div>
                                            <div class="booknow_btn ">
                                                <div class="price_box">
                                                    <p class="text-lg font-semibold">Total Price:</p>
                                                    <span class="heading_sm3" id="final_pay_price">₹40</span>
                                                </div>
                                                <div class="price_box">
                                                    <input type="hidden" name="booking_price" id="booking_price"
                                                        value="500">
                                                    <p class="text-lg font-semibold">Booking Price:</p>
                                                    <span class="heading_sm3">₹1,000</span>
                                                </div>
                                                <ul class="btn_group listing_btn">
                                                    <li>
                                                        <input type="hidden" name="action" value="booking">
                                                        <input type="hidden" name="package" value="303">
                                                        <input type="button" class="btn w-full" name="submit"
                                                            value="Book Online" @click="showBookingPopup">
                                                    </li>
                                                    <li class="text-center">
                                                        <a href="https://overlandescape.tekzini.com/enquire-this-trip?package=gwalior-tour&amp;type=super%20deluxe"
                                                            class="btn2">Enquire Now</a>
                                                    </li>

                                                    <li class="text-center">
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="note_text text-sm mb-3 pt-2"><strong>Note* : </strong> More than 6
                                                    persons please contact.</div>
                                        </div>

                                        <FancyboxWrapper className="left_price_box">
                                            <div class="swiper package_detail_img">
                                                <div class="swiper-wrapper">
                                                    <div class="swiper-slide" v-for="imageData in package.images">
                                                        <img  data-fancybox="gallery"
                                                        :src="imageData.srcimage"
                                                        alt="{{imageData.title}}" />
                                                    </div>
                                                </div>
                                                <div class="swiper-button-next"></div>
                                                <div class="swiper-button-prev"></div>
                                            </div>
                                        </FancyboxWrapper>
                                    </div>
                                </div>

                                <div class="inclusions_share flex flex-wrap">
                                    <div class="left_side mb-5 pr-8 w-6/12 icon-wishlist relative">
                                        <ul class="inclusions inclusions_list">
                                            <li data-text="Flight" v-for="package_inclusion in package.package_inclusions">
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
                                                        <li class="facebook"><a
                                                                href="http://www.facebook.com/sharer.php?u=https://overlandescape.tekzini.com/package/gwalior-tour"
                                                                target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                                                        </li>
                                                        <li class="twitter"><a
                                                                href="http://twitter.com/share?url=https://overlandescape.tekzini.com/package/gwalior-tour"
                                                                target="_blank"><i class="fa-brands fa-twitter"></i></a>
                                                        </li>

                                                        <li class="whatsapp"><a
                                                                href="https://web.whatsapp.com/send?text=https://overlandescape.tekzini.com/package/gwalior-tour"
                                                                target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
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
                </div>

                <div class="row">
                    <div class="xl:container xl:mx-auto">
                        <div class="flex flex-wrap">
                            <div class="row-cols-2 w-70 pr-9">
                                <div class="package_disc flex items-center mt-5 mb-5">
                                    <h2 class="text-base font-semibold">Activities</h2>
                                    <div class="list_row_right">
                                        <ul class="activ_list">
                                            <li class="ml-5 mb-2">Mountaineering</li>
                                        </ul>
                                    </div>
                                </div>

                                <!--- ======== Description  ======== -->
                                <div class="package_disc overview_box py-3.5 px-5">
                                    <div class="text-2xl font-semibold pb-3">Overview</div>

                                    <p>Identifying and capturing the essence of the place is vital for the tourism business
                                        to do well. If an attractive place is turned into a destination, the place generates
                                        high revenue from tourism. Today, Destination Management is an important subject in
                                        tourism studies.
                                    </p>



                                </div>

                                <div id="package_accommodations" class="mt-9">
                                    <div id="hotel_accom" class="hotel_accordion mt-5">
                                        <h3 class="no_line text-2xl pb-1 font-bold">Accommodation</h3>
                                        <p class="pb-1"> <strong>Note:-</strong> We Will provide you these or similar accomodation depending on availability </p>
                                        <button class="accordion active">
                                            <div class="pull-left"><b></b></div>
                                            <div class="pull-right day_font">
                                            <b>Day 6</b>
                                            </div>
                                        </button>
                                        <div class="hotel_package_detail">
                                            <div class="slider_box">
                                            <div class="swiper hotel_inner_slider swiper-initialized swiper-horizontal swiper-backface-hidden">
                                                <div class="swiper-wrapper" id="swiper-wrapper-2f3d3bab6d9c119f" aria-live="polite">
                                                    <div class="swiper-slide disabled swiper-slide-active" role="group" aria-label="1 / 1" style="width: 240.333px; margin-right: 20px;">
                                                        <div class="hodel_detail_list">
                                                        <div class="hotel_info hotel_info_typeid385 " style="">
                                                            <div class="hotel_info_box itenery_info">
                                                                <div class="itn_img">
                                                                    <img src="https://overlandescape.tekzini.com/storage/accommodations/thumb/5ab4aecce918b196_lake-view-hotel-tsomoriri_4.jpg" alt="Lake View Hotel Tsomoriri">
                                                                </div>
                                                                <div class="box-content" style="position: absolute;">
                                                                    <h6 class="dest_title"><a class="text-sm fancy_popup fancybox.iframe" data-fancybox="" data-type="iframe" href="https://overlandescape.tekzini.com/packagespopup/hotel-details/lake-view-hotel-tsomoriri" rel="nofollow">Lake View Hotel Tsomoriri</a></h6>
                                                                    <div class="star-loc">
                                                                    <ul class="rating_list" rating="2">
                                                                        <li class="active">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                                                <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"></path>
                                                                            </svg>
                                                                        </li>
                                                                        <li class="active">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                                                <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"></path>
                                                                            </svg>
                                                                        </li>
                                                                        <li>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                                                <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"></path>
                                                                            </svg>
                                                                        </li>
                                                                        <li>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                                                <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"></path>
                                                                            </svg>
                                                                        </li>
                                                                        <li>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                                                <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"></path>
                                                                            </svg>
                                                                        </li>
                                                                    </ul>
                                                                    <div class="hotel_destination text-sm"><img class="map-i" src="https://overlandescape.tekzini.com/assets/overlandescape/images/map.png">
                                                                        Tsomoriri                                    
                                                                    </div>
                                                                    </div>
                                                                    <div class="brief-text text-sm mt-3"></div>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                                            </div>
                                            <div class="slider_btns">
                                                <div class="hotel-inner-prev swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-2f3d3bab6d9c119f" aria-disabled="true"><img src="https://overlandescape.tekzini.com/assets/overlandescape/images/next.png" alt="Next Icon"></div>
                                                <div class="hotel-inner-next swiper-button-disabled swiper-button-lock" tabindex="-1" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-2f3d3bab6d9c119f" aria-disabled="true"><img src="https://overlandescape.tekzini.com/assets/overlandescape/images/prev.png" alt="Prev Icon"></div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>

                               

                                <!--- ======== Package Days  ======== -->
                                <div class="package_day secpad mt-5">
                                    <div class="container-full">
                                        <h3 class="text-xl font-bold">Package Itinerary</h3>
                                        <div class="faqlist faqlist_in">
                                            <ul>
                                                <li class="faq_main" v-for="itenary in itenaries"> 
                                                    <div class="ml-14">
                                                        <div class="faq_title heading6">{{itenary.itenary_title}}</div>
                                                        <div class="theme_tags mb-5">
                                                            <span v-for="itag in itenary.itenaryTags">
                                                                {{itag}}
                                                            </span>
                                                        </div>
                                                        <div class="day_curcle"><span>{{itenary.itenary_day_title}}</span></div>
                                                        <div class="faq_data">
                                                            <div class="heading6 font-semibold" v-if="itenary.itenary_location">{{itenary.itenary_location}}</div>
                                                            <div class="right-content-itinerary">
                                                                <p v-for="meal_option_val in itenary.meal_option_arr">{{meal_option_val}}&nbsp;</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>                                           
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!--- ======== Accommodation Hotel ======== -->
                                <div id="package_accommodations" class="mt-9">

                                </div>
                                <!--- ======== Accommodation  Old ======== -->
                               <div class="accommodation accordion pt-5">
                                <div class="container1">
                                    <h3 class="text-2xl pb-3">Policy</h3>
                                    <div class="faqlist">
                                        <ul>
                                            <li class="faq_main" v-if="package.inclusions">
                                                <div class="faq_title heading6">Inclusions</div>
                                                <div class="faq_data">
                                                    <span  v-html="package.inclusions"></span>
                                                </div>
                                            </li>
                                            <li class="faq_main" v-if="package.exclusions">
                                                <div class="faq_title heading6">Exclusions</div>
                                                <div class="faq_data">
                                                    <span  v-html="package.exclusions"></span>
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
                                                        <span  v-html="package.payment_policy"></span>

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


                                            <li class="faq_main" >
                                                <div>
                                                    <div class="faq_title heading6">Info</div>
                                                    <div class="faq_data" v-for="info in package.PackageInfo">
                                                        <p>{{info.title}}</p>
                                                        <p v-htnl="info.description"></p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>


                                        <!-- Frequently Asked Questions -->
                                        <br><br>
                                        <div class="theme_title mb-6" v-if="faq_row">
                                            <h3 class="text-2xl pb-3">FAQs About {{package.title}}</h3>
                                        </div>
                                        <ul>

                                            <li class="faq_main"  v-for="faq in faq_row">
                                                <div>
                                                    <div class="faq_title heading6"><strong>Q </strong>{{faq.question}}</div>
                                                    <div class="faq_data" style="">
                                                        <p>{{faq.answer}}</p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <!-- Frequently Asked Questions Closed -->



                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="row-cols-2 w-30 mt-5">
                                <div class="form_box-new single_package_form">
                                    <div class="form_box_inner">
                                        <h2 class="heading2">Your Preference</h2>
                                        <div class="flash-message">
                                        </div>
                                        <div class="book-space">
                                            <div class="bookspace-inner">
                                                <form method="POST" action="" id="form_your_preference"
                                                    class="common_main_form needs-validation left_form" novalidate
                                                    accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                                                    <div class="ajax_msg"></div><input type="hidden"
                                                        id="g-recaptcha-response" name="g-recaptcha-response"
                                                        class="g-recaptcha-response"><input type="hidden" name="action"
                                                        value="validate_captcha">
                                                    <div class="formMessage"></div><input type="hidden" name="_token"
                                                        value="VmKdgQXu5418EoPQiowd9QV5WTyFwSbLKNdrCYIf">
                                                    <div class="form-floating mb-3 "><label for="Name">Name</label><input
                                                            type="text" placeholder="Name*" required class="form-control "
                                                            dataTypeInput="inputname" name="input72" value="" /><span
                                                            class="input72_error error"></span></div>
                                                    <div class="form-floating mb-3 phone_code"><label
                                                            for="Phone">Phone</label>
                                                        <select name="input73_country_code"
                                                            class="form-select country_code">
                                                            <option value="+1-284">++1-284</option>
                                                            <option value="+1-340">++1-340</option>
                                                            <option value="+1-649">++1-649</option>
                                                            <option value="+1-868">++1-868</option>
                                                            <option value="0055">+0055</option>
                                                            <option value="1">+1</option>
                                                            <option value="1">+1</option>
                                                            <option value="1">+1</option>
                                                            <option value="1242">+1242</option>
                                                            <option value="1246">+1246</option>
                                                            <option value="1264">+1264</option>
                                                            <option value="1268">+1268</option>
                                                            <option value="1345">+1345</option>
                                                            <option value="1441">+1441</option>
                                                            <option value="1473">+1473</option>
                                                            <option value="1664">+1664</option>
                                                            <option value="1670">+1670</option>
                                                            <option value="1671">+1671</option>
                                                            <option value="1684">+1684</option>
                                                            <option value="1721">+1721</option>
                                                            <option value="1758">+1758</option>
                                                            <option value="1767">+1767</option>
                                                            <option value="1784">+1784</option>
                                                            <option value="1787">+1787</option>
                                                            <option value="1809">+1809</option>
                                                            <option value="1869">+1869</option>
                                                            <option value="1876">+1876</option>
                                                            <option value="20">+20</option>
                                                            <option value="211">+211</option>
                                                            <option value="212">+212</option>
                                                            <option value="212">+212</option>
                                                            <option value="213">+213</option>
                                                            <option value="216">+216</option>
                                                            <option value="218">+218</option>
                                                            <option value="220">+220</option>
                                                            <option value="221">+221</option>
                                                            <option value="222">+222</option>
                                                            <option value="223">+223</option>
                                                            <option value="224">+224</option>
                                                            <option value="225">+225</option>
                                                            <option value="226">+226</option>
                                                            <option value="227">+227</option>
                                                            <option value="228">+228</option>
                                                            <option value="229">+229</option>
                                                            <option value="230">+230</option>
                                                            <option value="231">+231</option>
                                                            <option value="232">+232</option>
                                                            <option value="233">+233</option>
                                                            <option value="234">+234</option>
                                                            <option value="235">+235</option>
                                                            <option value="236">+236</option>
                                                            <option value="237">+237</option>
                                                            <option value="238">+238</option>
                                                            <option value="239">+239</option>
                                                            <option value="240">+240</option>
                                                            <option value="241">+241</option>
                                                            <option value="242">+242</option>
                                                            <option value="243">+243</option>
                                                            <option value="244">+244</option>
                                                            <option value="245">+245</option>
                                                            <option value="246">+246</option>
                                                            <option value="248">+248</option>
                                                            <option value="249">+249</option>
                                                            <option value="250">+250</option>
                                                            <option value="251">+251</option>
                                                            <option value="252">+252</option>
                                                            <option value="253">+253</option>
                                                            <option value="254">+254</option>
                                                            <option value="255">+255</option>
                                                            <option value="256">+256</option>
                                                            <option value="257">+257</option>
                                                            <option value="258">+258</option>
                                                            <option value="260">+260</option>
                                                            <option value="261">+261</option>
                                                            <option value="262">+262</option>
                                                            <option value="262">+262</option>
                                                            <option value="262">+262</option>
                                                            <option value="263">+263</option>
                                                            <option value="264">+264</option>
                                                            <option value="265">+265</option>
                                                            <option value="266">+266</option>
                                                            <option value="267">+267</option>
                                                            <option value="268">+268</option>
                                                            <option value="269">+269</option>
                                                            <option value="27">+27</option>
                                                            <option value="290">+290</option>
                                                            <option value="291">+291</option>
                                                            <option value="297">+297</option>
                                                            <option value="298">+298</option>
                                                            <option value="299">+299</option>
                                                            <option value="30">+30</option>
                                                            <option value="31">+31</option>
                                                            <option value="32">+32</option>
                                                            <option value="33">+33</option>
                                                            <option value="34">+34</option>
                                                            <option value="350">+350</option>
                                                            <option value="351">+351</option>
                                                            <option value="352">+352</option>
                                                            <option value="353">+353</option>
                                                            <option value="354">+354</option>
                                                            <option value="355">+355</option>
                                                            <option value="356">+356</option>
                                                            <option value="357">+357</option>
                                                            <option value="358">+358</option>
                                                            <option value="35818">+35818</option>
                                                            <option value="359">+359</option>
                                                            <option value="36">+36</option>
                                                            <option value="370">+370</option>
                                                            <option value="371">+371</option>
                                                            <option value="372">+372</option>
                                                            <option value="373">+373</option>
                                                            <option value="374">+374</option>
                                                            <option value="375">+375</option>
                                                            <option value="376">+376</option>
                                                            <option value="377">+377</option>
                                                            <option value="378">+378</option>
                                                            <option value="379">+379</option>
                                                            <option value="380">+380</option>
                                                            <option value="381">+381</option>
                                                            <option value="382">+382</option>
                                                            <option value="383">+383</option>
                                                            <option value="385">+385</option>
                                                            <option value="386">+386</option>
                                                            <option value="387">+387</option>
                                                            <option value="389">+389</option>
                                                            <option value="39">+39</option>
                                                            <option value="40">+40</option>
                                                            <option value="41">+41</option>
                                                            <option value="420">+420</option>
                                                            <option value="421">+421</option>
                                                            <option value="423">+423</option>
                                                            <option value="43">+43</option>
                                                            <option value="44">+44</option>
                                                            <option value="441481">+441481</option>
                                                            <option value="441534">+441534</option>
                                                            <option value="441624">+441624</option>
                                                            <option value="45">+45</option>
                                                            <option value="46">+46</option>
                                                            <option value="47">+47</option>
                                                            <option value="47">+47</option>
                                                            <option value="48">+48</option>
                                                            <option value="49">+49</option>
                                                            <option value="500">+500</option>
                                                            <option value="500">+500</option>
                                                            <option value="501">+501</option>
                                                            <option value="502">+502</option>
                                                            <option value="503">+503</option>
                                                            <option value="504">+504</option>
                                                            <option value="505">+505</option>
                                                            <option value="506">+506</option>
                                                            <option value="507">+507</option>
                                                            <option value="508">+508</option>
                                                            <option value="509">+509</option>
                                                            <option value="51">+51</option>
                                                            <option value="52">+52</option>
                                                            <option value="53">+53</option>
                                                            <option value="54">+54</option>
                                                            <option value="55">+55</option>
                                                            <option value="56">+56</option>
                                                            <option value="57">+57</option>
                                                            <option value="58">+58</option>
                                                            <option value="590">+590</option>
                                                            <option value="590">+590</option>
                                                            <option value="590">+590</option>
                                                            <option value="591">+591</option>
                                                            <option value="592">+592</option>
                                                            <option value="593">+593</option>
                                                            <option value="594">+594</option>
                                                            <option value="595">+595</option>
                                                            <option value="596">+596</option>
                                                            <option value="597">+597</option>
                                                            <option value="598">+598</option>
                                                            <option value="599">+599</option>
                                                            <option value="599">+599</option>
                                                            <option value="60">+60</option>
                                                            <option value="61">+61</option>
                                                            <option value="61">+61</option>
                                                            <option value="61">+61</option>
                                                            <option value="62">+62</option>
                                                            <option value="63">+63</option>
                                                            <option value="64">+64</option>
                                                            <option value="65">+65</option>
                                                            <option value="66">+66</option>
                                                            <option value="670">+670</option>
                                                            <option value="672">+672</option>
                                                            <option value="672">+672</option>
                                                            <option value="672">+672</option>
                                                            <option value="673">+673</option>
                                                            <option value="674">+674</option>
                                                            <option value="675">+675</option>
                                                            <option value="676">+676</option>
                                                            <option value="677">+677</option>
                                                            <option value="678">+678</option>
                                                            <option value="679">+679</option>
                                                            <option value="680">+680</option>
                                                            <option value="681">+681</option>
                                                            <option value="682">+682</option>
                                                            <option value="683">+683</option>
                                                            <option value="685">+685</option>
                                                            <option value="686">+686</option>
                                                            <option value="687">+687</option>
                                                            <option value="688">+688</option>
                                                            <option value="689">+689</option>
                                                            <option value="690">+690</option>
                                                            <option value="691">+691</option>
                                                            <option value="692">+692</option>
                                                            <option value="7">+7</option>
                                                            <option value="7">+7</option>
                                                            <option value="81">+81</option>
                                                            <option value="82">+82</option>
                                                            <option value="84">+84</option>
                                                            <option value="850">+850</option>
                                                            <option value="852">+852</option>
                                                            <option value="853">+853</option>
                                                            <option value="855">+855</option>
                                                            <option value="856">+856</option>
                                                            <option value="86">+86</option>
                                                            <option value="870">+870</option>
                                                            <option value="880">+880</option>
                                                            <option value="886">+886</option>
                                                            <option value="90">+90</option>
                                                            <option value="91" selected>+91</option>
                                                            <option value="92">+92</option>
                                                            <option value="93">+93</option>
                                                            <option value="94">+94</option>
                                                            <option value="95">+95</option>
                                                            <option value="960">+960</option>
                                                            <option value="961">+961</option>
                                                            <option value="962">+962</option>
                                                            <option value="963">+963</option>
                                                            <option value="964">+964</option>
                                                            <option value="965">+965</option>
                                                            <option value="966">+966</option>
                                                            <option value="967">+967</option>
                                                            <option value="968">+968</option>
                                                            <option value="970">+970</option>
                                                            <option value="971">+971</option>
                                                            <option value="972">+972</option>
                                                            <option value="973">+973</option>
                                                            <option value="974">+974</option>
                                                            <option value="975">+975</option>
                                                            <option value="976">+976</option>
                                                            <option value="977">+977</option>
                                                            <option value="98">+98</option>
                                                            <option value="992">+992</option>
                                                            <option value="993">+993</option>
                                                            <option value="994">+994</option>
                                                            <option value="995">+995</option>
                                                            <option value="996">+996</option>
                                                            <option value="998">+998</option>
                                                        </select>
                                                        <input type="text" required placeholder="Phone"
                                                            class="form-control phone" dataTypeInput="phone" name="input73"
                                                            value="" maxlength="12"
                                                            onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" /><span
                                                            class="input73_error error"></span>
                                                    </div>
                                                    <div class="form-floating mb-3 "><label for="Email">Email</label><input
                                                            type="email" placeholder="Email*" required class="form-control "
                                                            dataTypeInput="email" name="input74" value="" /><span
                                                            class="input74_error error"></span></div>
                                                    <div
                                                        class="form-floating mb-3 American Plan(Breakfast Lunch Dinner &amp; Room):American Plan(Breakfast Lunch Dinner &amp; Room)">
                                                        <label for="Date of Departure">Date of Departure</label>
                                                        <div class='input-group date' dataTypeInput="datepicker">
                                                            <input type='text' placeholder="Date of Departure"
                                                                class="form-control datepicker " name="input75" value="" />
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>
                                                        <span class="input75_error error"></span>
                                                    </div>


                                                    <div class="form-floating mb-3 "><label for="No of Paxs">No of
                                                            Paxs</label><input type="text" placeholder="No of Paxs"
                                                            class="form-control " dataTypeInput="number" name="input76"
                                                            value=""
                                                            onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" /><span
                                                            class="input76_error error"></span></div>
                                                    <div class="form-floating mb-3 "><label for="Per Person Cost">Per Person
                                                            Cost</label><input type="text" placeholder="Per Person Cost"
                                                            class="form-control " dataTypeInput="inputtext" name="input80"
                                                            value="" /><span class="input80_error error"></span></div>
                                                    <div class="form-floating mb-3 "><label for="Extra Bed">Extra
                                                            Bed</label><input type="text" placeholder="Extra Bed"
                                                            class="form-control " dataTypeInput="inputtext" name="input81"
                                                            value="" /><span class="input81_error error"></span></div>
                                                    <div class="form-floating mb-3 "><label for="W/o Extra Bed">W/o Extra
                                                            Bed</label><input type="text" placeholder="W/o Extra Bed"
                                                            class="form-control " dataTypeInput="inputtext" name="input82"
                                                            value="" /><span class="input82_error error"></span></div>
                                                    <div class="form-floating mb-3 "><label
                                                            for="Single Supplementary">Single Supplementary</label><input
                                                            type="text" placeholder="Single Supplementary"
                                                            class="form-control " dataTypeInput="inputtext" name="input77"
                                                            value="" /><span class="input77_error error"></span></div>
                                                    <input type="hidden" name="hidden_package" value="303"><input
                                                        type="hidden" name="form_slug" value="your_preference"><input
                                                        type="hidden" name="refer_url"
                                                        value="https://overlandescape.tekzini.com/package/gwalior-tour">
                                                    <div class="btn-book-space">
                                                        <button class="btn btn-outline btn-theme btnSubmit">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
        <section class="similar_activity mt-3" v-if="relatedPackagestObj">
            <div class="xl:container xl:mx-auto">
                <div class="text_center">
                    <div class="theme_title mb-0">
                        <div class="title text-2xl">Similar Activity</div>
                    </div>
                </div>
                <div class="slider_box">
                    <div class="swiper featured_slider">
                        <div class="swiper-wrapper">
                        <li class="swiper-slide" v-for="similarProject in relatedPackagestObj">
                            <div class="featured_box">
                                <span class="package_tour_type_text pb-1"> {{similarProject.tour_type? similarProject.tour_type :''}} Package</span>
                                <a class="featured_content " href="https://overlandescape.tekzini.com/activity/alchi-to-khaltsi-rafting" >
                                    <div class="images">
                                    <img :src="similarProject.packageSrc" :alt="similarProject.title" />
                                    </div>
                                </a>
                                <a class="featured_content px-1.5 py-3.5" :href="similarProject.package_slug" >
                                    <div class="title text-sm">
                                    {{similarProject.title}}
                                    </div>
                                    <div class="price">
                                    <p class="text-xs"> Starting From : <span class="amount heading_sm3">₹{{similarProject.search_price?similarProject.search_price:''}}</span></p>
                                    <small></small>
                                    </div>
                                </a>
                            </div>
                        </li>              
                        </div>
                    </div>
                    <div class="slider_btns">
                        <div class="featured-prev theme-next"><img src="https://overlandescape.tekzini.com/assets/overlandescape/images/next-sm.png" alt="Next Icon"></div>
                        <div class="featured-next theme-prev"><img src="https://overlandescape.tekzini.com/assets/overlandescape/images/prev-sm.png" alt="Prev Icon"></div>
                    </div>
                </div>
                <!-- <div class="text_center mt45">
                    <a href="#" class="btn">View All</a>
                    </div> -->
            </div>
            </section>

        <section class="departure_single">
            <div class="container">
                <div class="text_center">
                    <div class="theme_title mb-5">
                        <div class="title text-2xl	">More Trips Departure</div>
                    </div>
                </div>
                <div class="slider_box mt40">
                    <div class="swiper departure_slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="more_trips">
                                    <div class="images">
                                        <a href="https://overlandescape.tekzini.com/tour-packages/ladakh">
                                            <img src="https://overlandescape.tekzini.com/storage/destinations/thumb/59803120558e1112_01.jpg"
                                                alt="Ladakh" class="img_responsive theme_radius" />
                                            <div class="place_name heading_md1">
                                                <span>Ladakh</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="more_trips">
                                    <div class="images">
                                        <a href="https://overlandescape.tekzini.com/tour-packages/himachal">
                                            <img src="https://overlandescape.tekzini.com/storage/destinations/thumb/598028878075b118_03.jpg"
                                                alt="Himachal" class="img_responsive theme_radius" />
                                            <div class="place_name heading_md1">
                                                <span>Himachal</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="more_trips">
                                    <div class="images">
                                        <a href="https://overlandescape.tekzini.com/tour-packages/kashmir">
                                            <img src="https://overlandescape.tekzini.com/storage/destinations/thumb/59801a0a77710104_450.jpg"
                                                alt="Kashmir" class="img_responsive theme_radius" />
                                            <div class="place_name heading_md1">
                                                <span>Kashmir</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="more_trips">
                                    <div class="images">
                                        <a href="https://overlandescape.tekzini.com/tour-packages/leh">
                                            <img src="https://overlandescape.tekzini.com/assets/overlandescape/images/noimage.jpg"
                                                alt="Leh" class="img_responsive theme_radius" />
                                            <div class="place_name heading_md1">
                                                <span>Leh</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="more_trips">
                                    <div class="images">
                                        <a href="https://overlandescape.tekzini.com/tour-packages/srilanka">
                                            <img src="https://overlandescape.tekzini.com/storage/destinations/thumb/619e0094b21a8196_sri_lanka.jpg"
                                                alt="Srilanka" class="img_responsive theme_radius" />
                                            <div class="place_name heading_md1">
                                                <span>Srilanka</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="more_trips">
                                    <div class="images">
                                        <a href="https://overlandescape.tekzini.com/tour-packages/dubai">
                                            <img src="https://overlandescape.tekzini.com/storage/destinations/thumb/220223054252-Dubai_listing_Banner.jpg"
                                                alt="Dubai" class="img_responsive theme_radius" />
                                            <div class="place_name heading_md1">
                                                <span>Dubai</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slider_btns">
                        <div class="departure-next theme-next"><img
                                src="https://overlandescape.tekzini.com/assets/overlandescape/images/next-sm.png"
                                alt="Next Icon"></div>
                        <div class="departure-prev theme-prev"><img
                                src="https://overlandescape.tekzini.com/assets/overlandescape/images/prev-sm.png"
                                alt="Prev Icon"></div>
                    </div>
                </div>
            </div>
        </section>

        


        <div ref="iternetyRef" style="display:none;">
            <div class="modal-body download-itinerary">
                <div class="modal-header form-floating mb-3 w-full">
                    <h4 class="modal-title text-2xl">Enquiry Form</h4>
                    <p class="text-sm ">Download itinerary for :<span class="text-teal-500  italic">
                            Gwalior Tour</span></p>
                </div>
                <div class="book-space">
                    <div class="bookspace-inner">
                        <form method="POST" action="" id="form_download_itinerary"
                            class="common_main_form needs-validation form_list" novalidate accept-charset="UTF-8"
                            role="form" enctype="multipart/form-data">
                            <div class="ajax_msg"></div><input type="hidden" id="g-recaptcha-response"
                                name="g-recaptcha-response" class="g-recaptcha-response"><input type="hidden" name="action"
                                value="validate_captcha">
                            <div class="formMessage"></div><input type="hidden" name="_token"
                                value="VmKdgQXu5418EoPQiowd9QV5WTyFwSbLKNdrCYIf">
                            <div class="form-floating mb-3 "><label for="Name">Name</label><input type="text"
                                    placeholder="Name*" required class="form-control " dataTypeInput="inputname"
                                    name="input84" value="" /><span class="input84_error error"></span></div>
                            <div class="form-floating mb-3 phone_code"><label for="Phone">Phone</label>
                                <select name="input85_country_code" class="form-select country_code">
                                    <option value="+1-284">++1-284</option>
                                    <option value="+1-340">++1-340</option>
                                    <option value="+1-649">++1-649</option>
                                    <option value="+1-868">++1-868</option>
                                    <option value="0055">+0055</option>
                                    <option value="1">+1</option>
                                    <option value="1">+1</option>
                                    <option value="1">+1</option>
                                    <option value="1242">+1242</option>
                                    <option value="1246">+1246</option>
                                    <option value="1264">+1264</option>
                                    <option value="1268">+1268</option>
                                    <option value="1345">+1345</option>
                                    <option value="1441">+1441</option>
                                    <option value="1473">+1473</option>
                                    <option value="1664">+1664</option>
                                    <option value="1670">+1670</option>
                                    <option value="1671">+1671</option>
                                    <option value="1684">+1684</option>
                                    <option value="1721">+1721</option>
                                    <option value="1758">+1758</option>
                                    <option value="1767">+1767</option>
                                    <option value="1784">+1784</option>
                                    <option value="1787">+1787</option>
                                    <option value="1809">+1809</option>
                                    <option value="1869">+1869</option>
                                    <option value="1876">+1876</option>
                                    <option value="20">+20</option>
                                    <option value="211">+211</option>
                                    <option value="212">+212</option>
                                    <option value="212">+212</option>
                                    <option value="213">+213</option>
                                    <option value="216">+216</option>
                                    <option value="218">+218</option>
                                    <option value="220">+220</option>
                                    <option value="221">+221</option>
                                    <option value="222">+222</option>
                                    <option value="223">+223</option>
                                    <option value="224">+224</option>
                                    <option value="225">+225</option>
                                    <option value="226">+226</option>
                                    <option value="227">+227</option>
                                    <option value="228">+228</option>
                                    <option value="229">+229</option>
                                    <option value="230">+230</option>
                                    <option value="231">+231</option>
                                    <option value="232">+232</option>
                                    <option value="233">+233</option>
                                    <option value="234">+234</option>
                                    <option value="235">+235</option>
                                    <option value="236">+236</option>
                                    <option value="237">+237</option>
                                    <option value="238">+238</option>
                                    <option value="239">+239</option>
                                    <option value="240">+240</option>
                                    <option value="241">+241</option>
                                    <option value="242">+242</option>
                                    <option value="243">+243</option>
                                    <option value="244">+244</option>
                                    <option value="245">+245</option>
                                    <option value="246">+246</option>
                                    <option value="248">+248</option>
                                    <option value="249">+249</option>
                                    <option value="250">+250</option>
                                    <option value="251">+251</option>
                                    <option value="252">+252</option>
                                    <option value="253">+253</option>
                                    <option value="254">+254</option>
                                    <option value="255">+255</option>
                                    <option value="256">+256</option>
                                    <option value="257">+257</option>
                                    <option value="258">+258</option>
                                    <option value="260">+260</option>
                                    <option value="261">+261</option>
                                    <option value="262">+262</option>
                                    <option value="262">+262</option>
                                    <option value="262">+262</option>
                                    <option value="263">+263</option>
                                    <option value="264">+264</option>
                                    <option value="265">+265</option>
                                    <option value="266">+266</option>
                                    <option value="267">+267</option>
                                    <option value="268">+268</option>
                                    <option value="269">+269</option>
                                    <option value="27">+27</option>
                                    <option value="290">+290</option>
                                    <option value="291">+291</option>
                                    <option value="297">+297</option>
                                    <option value="298">+298</option>
                                    <option value="299">+299</option>
                                    <option value="30">+30</option>
                                    <option value="31">+31</option>
                                    <option value="32">+32</option>
                                    <option value="33">+33</option>
                                    <option value="34">+34</option>
                                    <option value="350">+350</option>
                                    <option value="351">+351</option>
                                    <option value="352">+352</option>
                                    <option value="353">+353</option>
                                    <option value="354">+354</option>
                                    <option value="355">+355</option>
                                    <option value="356">+356</option>
                                    <option value="357">+357</option>
                                    <option value="358">+358</option>
                                    <option value="35818">+35818</option>
                                    <option value="359">+359</option>
                                    <option value="36">+36</option>
                                    <option value="370">+370</option>
                                    <option value="371">+371</option>
                                    <option value="372">+372</option>
                                    <option value="373">+373</option>
                                    <option value="374">+374</option>
                                    <option value="375">+375</option>
                                    <option value="376">+376</option>
                                    <option value="377">+377</option>
                                    <option value="378">+378</option>
                                    <option value="379">+379</option>
                                    <option value="380">+380</option>
                                    <option value="381">+381</option>
                                    <option value="382">+382</option>
                                    <option value="383">+383</option>
                                    <option value="385">+385</option>
                                    <option value="386">+386</option>
                                    <option value="387">+387</option>
                                    <option value="389">+389</option>
                                    <option value="39">+39</option>
                                    <option value="40">+40</option>
                                    <option value="41">+41</option>
                                    <option value="420">+420</option>
                                    <option value="421">+421</option>
                                    <option value="423">+423</option>
                                    <option value="43">+43</option>
                                    <option value="44">+44</option>
                                    <option value="441481">+441481</option>
                                    <option value="441534">+441534</option>
                                    <option value="441624">+441624</option>
                                    <option value="45">+45</option>
                                    <option value="46">+46</option>
                                    <option value="47">+47</option>
                                    <option value="47">+47</option>
                                    <option value="48">+48</option>
                                    <option value="49">+49</option>
                                    <option value="500">+500</option>
                                    <option value="500">+500</option>
                                    <option value="501">+501</option>
                                    <option value="502">+502</option>
                                    <option value="503">+503</option>
                                    <option value="504">+504</option>
                                    <option value="505">+505</option>
                                    <option value="506">+506</option>
                                    <option value="507">+507</option>
                                    <option value="508">+508</option>
                                    <option value="509">+509</option>
                                    <option value="51">+51</option>
                                    <option value="52">+52</option>
                                    <option value="53">+53</option>
                                    <option value="54">+54</option>
                                    <option value="55">+55</option>
                                    <option value="56">+56</option>
                                    <option value="57">+57</option>
                                    <option value="58">+58</option>
                                    <option value="590">+590</option>
                                    <option value="590">+590</option>
                                    <option value="590">+590</option>
                                    <option value="591">+591</option>
                                    <option value="592">+592</option>
                                    <option value="593">+593</option>
                                    <option value="594">+594</option>
                                    <option value="595">+595</option>
                                    <option value="596">+596</option>
                                    <option value="597">+597</option>
                                    <option value="598">+598</option>
                                    <option value="599">+599</option>
                                    <option value="599">+599</option>
                                    <option value="60">+60</option>
                                    <option value="61">+61</option>
                                    <option value="61">+61</option>
                                    <option value="61">+61</option>
                                    <option value="62">+62</option>
                                    <option value="63">+63</option>
                                    <option value="64">+64</option>
                                    <option value="65">+65</option>
                                    <option value="66">+66</option>
                                    <option value="670">+670</option>
                                    <option value="672">+672</option>
                                    <option value="672">+672</option>
                                    <option value="672">+672</option>
                                    <option value="673">+673</option>
                                    <option value="674">+674</option>
                                    <option value="675">+675</option>
                                    <option value="676">+676</option>
                                    <option value="677">+677</option>
                                    <option value="678">+678</option>
                                    <option value="679">+679</option>
                                    <option value="680">+680</option>
                                    <option value="681">+681</option>
                                    <option value="682">+682</option>
                                    <option value="683">+683</option>
                                    <option value="685">+685</option>
                                    <option value="686">+686</option>
                                    <option value="687">+687</option>
                                    <option value="688">+688</option>
                                    <option value="689">+689</option>
                                    <option value="690">+690</option>
                                    <option value="691">+691</option>
                                    <option value="692">+692</option>
                                    <option value="7">+7</option>
                                    <option value="7">+7</option>
                                    <option value="81">+81</option>
                                    <option value="82">+82</option>
                                    <option value="84">+84</option>
                                    <option value="850">+850</option>
                                    <option value="852">+852</option>
                                    <option value="853">+853</option>
                                    <option value="855">+855</option>
                                    <option value="856">+856</option>
                                    <option value="86">+86</option>
                                    <option value="870">+870</option>
                                    <option value="880">+880</option>
                                    <option value="886">+886</option>
                                    <option value="90">+90</option>
                                    <option value="91" selected>+91</option>
                                    <option value="92">+92</option>
                                    <option value="93">+93</option>
                                    <option value="94">+94</option>
                                    <option value="95">+95</option>
                                    <option value="960">+960</option>
                                    <option value="961">+961</option>
                                    <option value="962">+962</option>
                                    <option value="963">+963</option>
                                    <option value="964">+964</option>
                                    <option value="965">+965</option>
                                    <option value="966">+966</option>
                                    <option value="967">+967</option>
                                    <option value="968">+968</option>
                                    <option value="970">+970</option>
                                    <option value="971">+971</option>
                                    <option value="972">+972</option>
                                    <option value="973">+973</option>
                                    <option value="974">+974</option>
                                    <option value="975">+975</option>
                                    <option value="976">+976</option>
                                    <option value="977">+977</option>
                                    <option value="98">+98</option>
                                    <option value="992">+992</option>
                                    <option value="993">+993</option>
                                    <option value="994">+994</option>
                                    <option value="995">+995</option>
                                    <option value="996">+996</option>
                                    <option value="998">+998</option>
                                </select>
                                <input type="text" required placeholder="Phone" class="form-control phone"
                                    dataTypeInput="phone" name="input85" value="" maxlength="12"
                                    onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" /><span
                                    class="input85_error error"></span>
                            </div>
                            <div class="form-floating mb-3 "><label for="Email">Email</label><input type="email"
                                    placeholder="Email*" required class="form-control " dataTypeInput="email" name="input86"
                                    value="" /><span class="input86_error error"></span></div>
                            <div class="form-floating mb-3 "><label for="Your residential city">Your
                                    residential city</label><input type="text" placeholder="Your residential city"
                                    class="form-control " dataTypeInput="inputname" name="input87" value="" /><span
                                    class="input87_error error"></span></div>
                            <input type="hidden" name="hidden_package" value="303"><input type="hidden" name="form_slug"
                                value="download_itinerary"><input type="hidden" name="refer_url"
                                value="https://overlandescape.tekzini.com/package/gwalior-tour">
                            <div class="btn-book-space">
                                <button class="btn btn-outline btn-theme btnSubmit">Download
                                    Itinerary</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <template v-if="store.popupType != 'innerHtml' && calenderPop">
            <Popup className="fullCalander_pop_wrap">
                <div class="modal-content">
                    <div class="modal-header">

                        <span class="heading">Select the date of travel</span>
                        <span class="sub_title_pop">The price in calendar represent the starting price
                            per person.</span>
                    </div>
                    <div class="modal-body">
                        <div id="full_calender">
                            <FullCalendar :options="calendarOptions" />
                        </div>
                    </div>
                </div>
            </Popup>
        </template>

        <template v-if="store.popupType != 'innerHtml' && bookingFormPop">
            <Popup className="booking_form_pop_wrap">
                <section class="theme_form allbooking_form">
                    <div class="container">
                        <div class="theme_form_wrap popup-booking">
                            <form id="book_now_form" method="POST" autocomplete="off">
                                <div class="flash-message"></div>
                                <div class="theme_form_inner">
                                    <div class="form_price">
                                        <div class="left_sec w-1/2 pr-5">
                                            <h3 class="text-xl pt-3">Book Now</h3>
                                            <div class="form_list">
                                                <div class="w-1/2 p-2 pb-0 pt-0">
                                                    <div class="form_group">
                                                        <label>
                                                            Your Name<em>*</em>
                                                        </label>
                                                        <input type="text" name="name" id="name" value=""
                                                            class="form_control">
                                                        <span id="name_error" class="validation_error"></span>
                                                    </div>
                                                </div>
                                                <div class="w-1/2 p-2 pb-0 pt-0">
                                                    <div class="form_group phone_code">
                                                        <label>
                                                            Phone<em>*</em>
                                                        </label>
                                                        <select name="country_code" class="form-select country_code">
                                                            <option value="93">+93</option>
                                                            <option value="35818">+35818</option>
                                                            <option value="355">+355</option>
                                                            <option value="213">+213</option>
                                                            <option value="1684">+1684</option>
                                                            <option value="376">+376</option>
                                                            <option value="244">+244</option>
                                                            <option value="1264">+1264</option>
                                                            <option value="672">+672</option>
                                                            <option value="1268">+1268</option>
                                                            <option value="54">+54</option>
                                                            <option value="374">+374</option>
                                                            <option value="297">+297</option>
                                                            <option value="61">+61</option>
                                                            <option value="43">+43</option>
                                                            <option value="994">+994</option>
                                                            <option value="1242">+1242</option>
                                                            <option value="973">+973</option>
                                                            <option value="880">+880</option>
                                                            <option value="1246">+1246</option>
                                                            <option value="375">+375</option>
                                                            <option value="32">+32</option>
                                                            <option value="501">+501</option>
                                                            <option value="229">+229</option>
                                                            <option value="1441">+1441</option>
                                                            <option value="975">+975</option>
                                                            <option value="591">+591</option>
                                                            <option value="387">+387</option>
                                                            <option value="267">+267</option>
                                                            <option value="0055">+0055</option>
                                                            <option value="55">+55</option>
                                                            <option value="246">+246</option>
                                                            <option value="673">+673</option>
                                                            <option value="359">+359</option>
                                                            <option value="226">+226</option>
                                                            <option value="257">+257</option>
                                                            <option value="855">+855</option>
                                                            <option value="237">+237</option>
                                                            <option value="1">+1</option>
                                                            <option value="238">+238</option>
                                                            <option value="1345">+1345</option>
                                                            <option value="236">+236</option>
                                                            <option value="235">+235</option>
                                                            <option value="56">+56</option>
                                                            <option value="86">+86</option>
                                                            <option value="61">+61</option>
                                                            <option value="61">+61</option>
                                                            <option value="57">+57</option>
                                                            <option value="269">+269</option>
                                                            <option value="242">+242</option>
                                                            <option value="243">+243</option>
                                                            <option value="682">+682</option>
                                                            <option value="506">+506</option>
                                                            <option value="225">+225</option>
                                                            <option value="385">+385</option>
                                                            <option value="53">+53</option>
                                                            <option value="357">+357</option>
                                                            <option value="420">+420</option>
                                                            <option value="45">+45</option>
                                                            <option value="253">+253</option>
                                                            <option value="1767">+1767</option>
                                                            <option value="1809">+1809</option>
                                                            <option value="670">+670</option>
                                                            <option value="593">+593</option>
                                                            <option value="20">+20</option>
                                                            <option value="503">+503</option>
                                                            <option value="240">+240</option>
                                                            <option value="291">+291</option>
                                                            <option value="372">+372</option>
                                                            <option value="251">+251</option>
                                                            <option value="500">+500</option>
                                                            <option value="298">+298</option>
                                                            <option value="679">+679</option>
                                                            <option value="358">+358</option>
                                                            <option value="33">+33</option>
                                                            <option value="594">+594</option>
                                                            <option value="689">+689</option>
                                                            <option value="262">+262</option>
                                                            <option value="241">+241</option>
                                                            <option value="220">+220</option>
                                                            <option value="995">+995</option>
                                                            <option value="49">+49</option>
                                                            <option value="233">+233</option>
                                                            <option value="350">+350</option>
                                                            <option value="30">+30</option>
                                                            <option value="299">+299</option>
                                                            <option value="1473">+1473</option>
                                                            <option value="590">+590</option>
                                                            <option value="1671">+1671</option>
                                                            <option value="502">+502</option>
                                                            <option value="441481">+441481</option>
                                                            <option value="224">+224</option>
                                                            <option value="245">+245</option>
                                                            <option value="592">+592</option>
                                                            <option value="509">+509</option>
                                                            <option value="672">+672</option>
                                                            <option value="504">+504</option>
                                                            <option value="852">+852</option>
                                                            <option value="36">+36</option>
                                                            <option value="354">+354</option>
                                                            <option value="91" selected>+91</option>
                                                            <option value="62">+62</option>
                                                            <option value="98">+98</option>
                                                            <option value="964">+964</option>
                                                            <option value="353">+353</option>
                                                            <option value="972">+972</option>
                                                            <option value="39">+39</option>
                                                            <option value="1876">+1876</option>
                                                            <option value="81">+81</option>
                                                            <option value="441534">+441534</option>
                                                            <option value="962">+962</option>
                                                            <option value="7">+7</option>
                                                            <option value="254">+254</option>
                                                            <option value="686">+686</option>
                                                            <option value="850">+850</option>
                                                            <option value="82">+82</option>
                                                            <option value="965">+965</option>
                                                            <option value="996">+996</option>
                                                            <option value="856">+856</option>
                                                            <option value="371">+371</option>
                                                            <option value="961">+961</option>
                                                            <option value="266">+266</option>
                                                            <option value="231">+231</option>
                                                            <option value="218">+218</option>
                                                            <option value="423">+423</option>
                                                            <option value="370">+370</option>
                                                            <option value="352">+352</option>
                                                            <option value="853">+853</option>
                                                            <option value="389">+389</option>
                                                            <option value="261">+261</option>
                                                            <option value="265">+265</option>
                                                            <option value="60">+60</option>
                                                            <option value="960">+960</option>
                                                            <option value="223">+223</option>
                                                            <option value="356">+356</option>
                                                            <option value="441624">+441624</option>
                                                            <option value="692">+692</option>
                                                            <option value="596">+596</option>
                                                            <option value="222">+222</option>
                                                            <option value="230">+230</option>
                                                            <option value="262">+262</option>
                                                            <option value="52">+52</option>
                                                            <option value="691">+691</option>
                                                            <option value="373">+373</option>
                                                            <option value="377">+377</option>
                                                            <option value="976">+976</option>
                                                            <option value="382">+382</option>
                                                            <option value="1664">+1664</option>
                                                            <option value="212">+212</option>
                                                            <option value="258">+258</option>
                                                            <option value="95">+95</option>
                                                            <option value="264">+264</option>
                                                            <option value="674">+674</option>
                                                            <option value="977">+977</option>
                                                            <option value="599">+599</option>
                                                            <option value="31">+31</option>
                                                            <option value="687">+687</option>
                                                            <option value="64">+64</option>
                                                            <option value="505">+505</option>
                                                            <option value="227">+227</option>
                                                            <option value="234">+234</option>
                                                            <option value="683">+683</option>
                                                            <option value="672">+672</option>
                                                            <option value="1670">+1670</option>
                                                            <option value="47">+47</option>
                                                            <option value="968">+968</option>
                                                            <option value="92">+92</option>
                                                            <option value="680">+680</option>
                                                            <option value="970">+970</option>
                                                            <option value="507">+507</option>
                                                            <option value="675">+675</option>
                                                            <option value="595">+595</option>
                                                            <option value="51">+51</option>
                                                            <option value="63">+63</option>
                                                            <option value="870">+870</option>
                                                            <option value="48">+48</option>
                                                            <option value="351">+351</option>
                                                            <option value="1787">+1787</option>
                                                            <option value="974">+974</option>
                                                            <option value="262">+262</option>
                                                            <option value="40">+40</option>
                                                            <option value="7">+7</option>
                                                            <option value="250">+250</option>
                                                            <option value="290">+290</option>
                                                            <option value="1869">+1869</option>
                                                            <option value="1758">+1758</option>
                                                            <option value="508">+508</option>
                                                            <option value="1784">+1784</option>
                                                            <option value="590">+590</option>
                                                            <option value="590">+590</option>
                                                            <option value="685">+685</option>
                                                            <option value="378">+378</option>
                                                            <option value="239">+239</option>
                                                            <option value="966">+966</option>
                                                            <option value="221">+221</option>
                                                            <option value="381">+381</option>
                                                            <option value="248">+248</option>
                                                            <option value="232">+232</option>
                                                            <option value="65">+65</option>
                                                            <option value="421">+421</option>
                                                            <option value="386">+386</option>
                                                            <option value="677">+677</option>
                                                            <option value="252">+252</option>
                                                            <option value="27">+27</option>
                                                            <option value="500">+500</option>
                                                            <option value="211">+211</option>
                                                            <option value="34">+34</option>
                                                            <option value="94">+94</option>
                                                            <option value="249">+249</option>
                                                            <option value="597">+597</option>
                                                            <option value="47">+47</option>
                                                            <option value="268">+268</option>
                                                            <option value="46">+46</option>
                                                            <option value="41">+41</option>
                                                            <option value="963">+963</option>
                                                            <option value="886">+886</option>
                                                            <option value="992">+992</option>
                                                            <option value="255">+255</option>
                                                            <option value="66">+66</option>
                                                            <option value="228">+228</option>
                                                            <option value="690">+690</option>
                                                            <option value="676">+676</option>
                                                            <option value="+1-868">++1-868</option>
                                                            <option value="216">+216</option>
                                                            <option value="90">+90</option>
                                                            <option value="993">+993</option>
                                                            <option value="+1-649">++1-649</option>
                                                            <option value="688">+688</option>
                                                            <option value="256">+256</option>
                                                            <option value="380">+380</option>
                                                            <option value="971">+971</option>
                                                            <option value="44">+44</option>
                                                            <option value="1">+1</option>
                                                            <option value="1">+1</option>
                                                            <option value="598">+598</option>
                                                            <option value="998">+998</option>
                                                            <option value="678">+678</option>
                                                            <option value="379">+379</option>
                                                            <option value="58">+58</option>
                                                            <option value="84">+84</option>
                                                            <option value="+1-284">++1-284</option>
                                                            <option value="+1-340">++1-340</option>
                                                            <option value="681">+681</option>
                                                            <option value="212">+212</option>
                                                            <option value="967">+967</option>
                                                            <option value="260">+260</option>
                                                            <option value="263">+263</option>
                                                            <option value="383">+383</option>
                                                            <option value="599">+599</option>
                                                            <option value="1721">+1721</option>
                                                        </select>
                                                        <input type="text" name="phone" id="phone" value=""
                                                            class="form_control" maxlength="12"
                                                            onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                                        <span id="phone_error" class="validation_error"></span>
                                                    </div>
                                                </div>
                                                <div class="w-1/2 p-2 pb-0 pt-0">
                                                    <div class="form_group">
                                                        <label>
                                                            Email<em>*</em>
                                                        </label>
                                                        <input type="text" name="email" id="email" value=""
                                                            class="form_control">
                                                        <span id="email_error" class="validation_error"></span>
                                                    </div>
                                                </div>
                                                <div class="w-1/2 p-2 pb-0 pt-0">
                                                    <div class="form_group">
                                                        <label>Address 1</label>
                                                        <input type="text" name="address1" id="address1" value=""
                                                            class="form_control">
                                                        <span id="address1_error" class="validation_error"></span>
                                                    </div>
                                                </div>
                                                <div class="w-1/2 p-2 pb-0 pt-0">
                                                    <div class="form_group">
                                                        <label>Address 2</label>
                                                        <input type="text" name="address2" id="address2" value=""
                                                            class="form_control">
                                                        <span id="address2_error" class="validation_error"></span>
                                                    </div>
                                                </div>
                                                <div class="w-1/2 p-2 pb-0 pt-0">
                                                    <div class="form_group">
                                                        <label>City</label>
                                                        <input type="text" name="city" id="city" value=""
                                                            class="form_control">
                                                        <span id="city_error" class="validation_error"></span>
                                                    </div>
                                                </div>
                                                <div class="w-1/2 p-2 pb-0 pt-0">
                                                    <div class="form_group">
                                                        <label>State</label>
                                                        <input type="text" name="state" id="state" value=""
                                                            class="form_control">
                                                        <span id="state_error" class="validation_error"></span>
                                                    </div>
                                                </div>
                                                <div class="w-1/2 p-2 pb-0 pt-0">
                                                    <div class="form_group">
                                                        <label>Zip Code</label>
                                                        <input type="text" name="zip_code" id="zip_code" value=""
                                                            class="form_control" maxlength="10">
                                                        <span id="zip_code_error" class="validation_error"></span>
                                                    </div>
                                                </div>
                                                <div class="w-1/2 p-2 pb-0 pt-0">
                                                    <div class="form_group">
                                                        <label>Country</label>
                                                        <select class="form_control" name="country" id="country">
                                                            <option value="">Please Select</option>
                                                            <option value="1">AFGHANISTAN</option>
                                                            <option value="2">ALAND ISLANDS</option>
                                                            <option value="3">ALBANIA</option>
                                                            <option value="4">ALGERIA</option>
                                                            <option value="5">American Samoa</option>
                                                            <option value="6">Andorra</option>
                                                            <option value="7">Angola</option>
                                                            <option value="8">Anguilla</option>
                                                            <option value="9">Antarctica</option>
                                                            <option value="10">Antigua And Barbuda</option>
                                                            <option value="11">Argentina</option>
                                                            <option value="12">Armenia</option>
                                                            <option value="13">Aruba</option>
                                                            <option value="14">Australia</option>
                                                            <option value="15">Austria</option>
                                                            <option value="16">Azerbaijan</option>
                                                            <option value="17">The Bahamas</option>
                                                            <option value="18">Bahrain</option>
                                                            <option value="19">Bangladesh</option>
                                                            <option value="20">Barbados</option>
                                                            <option value="21">Belarus</option>
                                                            <option value="22">Belgium</option>
                                                            <option value="23">Belize</option>
                                                            <option value="24">Benin</option>
                                                            <option value="25">Bermuda</option>
                                                            <option value="26">Bhutan</option>
                                                            <option value="27">Bolivia</option>
                                                            <option value="28">Bosnia and Herzegovina</option>
                                                            <option value="29">Botswana</option>
                                                            <option value="30">Bouvet Island</option>
                                                            <option value="31">Brazil</option>
                                                            <option value="32">British Indian Ocean Territory</option>
                                                            <option value="33">Brunei</option>
                                                            <option value="34">Bulgaria</option>
                                                            <option value="35">Burkina Faso</option>
                                                            <option value="36">Burundi</option>
                                                            <option value="37">Cambodia</option>
                                                            <option value="38">Cameroon</option>
                                                            <option value="39">Canada</option>
                                                            <option value="40">Cape Verde</option>
                                                            <option value="41">Cayman Islands</option>
                                                            <option value="42">Central African Republic</option>
                                                            <option value="43">Chad</option>
                                                            <option value="44">Chile</option>
                                                            <option value="45">China</option>
                                                            <option value="46">Christmas Island</option>
                                                            <option value="47">Cocos (Keeling) Islands</option>
                                                            <option value="48">Colombia</option>
                                                            <option value="49">Comoros</option>
                                                            <option value="50">Congo</option>
                                                            <option value="51">Democratic Republic of the Congo</option>
                                                            <option value="52">Cook Islands</option>
                                                            <option value="53">Costa Rica</option>
                                                            <option value="54">Cote D &#039;Ivoire (Ivory Coast)</option>
                                                            <option value="55">Croatia</option>
                                                            <option value="56">Cuba</option>
                                                            <option value="57">Cyprus</option>
                                                            <option value="58">Czech Republic</option>
                                                            <option value="59">Denmark</option>
                                                            <option value="60">Djibouti</option>
                                                            <option value="61">Dominica</option>
                                                            <option value="62">Dominican Republic</option>
                                                            <option value="63">East Timor</option>
                                                            <option value="64">Ecuador</option>
                                                            <option value="65">Egypt</option>
                                                            <option value="66">El Salvador</option>
                                                            <option value="67">Equatorial Guinea</option>
                                                            <option value="68">Eritrea</option>
                                                            <option value="69">Estonia</option>
                                                            <option value="70">Ethiopia</option>
                                                            <option value="71">Falkland Islands</option>
                                                            <option value="72">Faroe Islands</option>
                                                            <option value="73">Fiji Islands</option>
                                                            <option value="74">Finland</option>
                                                            <option value="75">France</option>
                                                            <option value="76">French Guiana</option>
                                                            <option value="77">French Polynesia</option>
                                                            <option value="78">French Southern Territories</option>
                                                            <option value="79">Gabon</option>
                                                            <option value="80">Gambia The</option>
                                                            <option value="81">Georgia</option>
                                                            <option value="82">Germany</option>
                                                            <option value="83">Ghana</option>
                                                            <option value="84">Gibraltar</option>
                                                            <option value="85">Greece</option>
                                                            <option value="86">Greenland</option>
                                                            <option value="87">Grenada</option>
                                                            <option value="88">Guadeloupe</option>
                                                            <option value="89">Guam</option>
                                                            <option value="90">Guatemala</option>
                                                            <option value="91">Guernsey and Alderney</option>
                                                            <option value="92">Guinea</option>
                                                            <option value="93">Guinea-Bissau</option>
                                                            <option value="94">Guyana</option>
                                                            <option value="95">Haiti</option>
                                                            <option value="96">Heard Island and McDonald Islands</option>
                                                            <option value="97">Honduras</option>
                                                            <option value="98">Hong Kong S.A.R.</option>
                                                            <option value="99">Hungary</option>
                                                            <option value="100">Iceland</option>
                                                            <option value="101" selected>India</option>
                                                            <option value="102">Indonesia</option>
                                                            <option value="103">Iran</option>
                                                            <option value="104">Iraq</option>
                                                            <option value="105">Ireland</option>
                                                            <option value="106">Israel</option>
                                                            <option value="107">Italy</option>
                                                            <option value="108">Jamaica</option>
                                                            <option value="109">Japan</option>
                                                            <option value="110">Jersey</option>
                                                            <option value="111">Jordan</option>
                                                            <option value="112">Kazakhstan</option>
                                                            <option value="113">Kenya</option>
                                                            <option value="114">Kiribati</option>
                                                            <option value="115">North Korea</option>
                                                            <option value="116">South Korea</option>
                                                            <option value="117">Kuwait</option>
                                                            <option value="118">Kyrgyzstan</option>
                                                            <option value="119">Laos</option>
                                                            <option value="120">Latvia</option>
                                                            <option value="121">Lebanon</option>
                                                            <option value="122">Lesotho</option>
                                                            <option value="123">Liberia</option>
                                                            <option value="124">Libya</option>
                                                            <option value="125">Liechtenstein</option>
                                                            <option value="126">Lithuania</option>
                                                            <option value="127">Luxembourg</option>
                                                            <option value="128">Macau S.A.R.</option>
                                                            <option value="129">Macedonia</option>
                                                            <option value="130">Madagascar</option>
                                                            <option value="131">Malawi</option>
                                                            <option value="132">Malaysia</option>
                                                            <option value="133">Maldives</option>
                                                            <option value="134">Mali</option>
                                                            <option value="135">Malta</option>
                                                            <option value="136">Man (Isle of)</option>
                                                            <option value="137">Marshall Islands</option>
                                                            <option value="138">Martinique</option>
                                                            <option value="139">Mauritania</option>
                                                            <option value="140">Mauritius</option>
                                                            <option value="141">Mayotte</option>
                                                            <option value="142">Mexico</option>
                                                            <option value="143">Micronesia</option>
                                                            <option value="144">Moldova</option>
                                                            <option value="145">Monaco</option>
                                                            <option value="146">Mongolia</option>
                                                            <option value="147">Montenegro</option>
                                                            <option value="148">Montserrat</option>
                                                            <option value="149">Morocco</option>
                                                            <option value="150">Mozambique</option>
                                                            <option value="151">Myanmar</option>
                                                            <option value="152">Namibia</option>
                                                            <option value="153">Nauru</option>
                                                            <option value="154">Nepal</option>
                                                            <option value="155">Bonaire, Sint Eustatius and Saba</option>
                                                            <option value="156">Netherlands</option>
                                                            <option value="157">New Caledonia</option>
                                                            <option value="158">New Zealand</option>
                                                            <option value="159">Nicaragua</option>
                                                            <option value="160">Niger</option>
                                                            <option value="161">Nigeria</option>
                                                            <option value="162">Niue</option>
                                                            <option value="163">Norfolk Island</option>
                                                            <option value="164">Northern Mariana Islands</option>
                                                            <option value="165">Norway</option>
                                                            <option value="166">Oman</option>
                                                            <option value="167">Pakistan</option>
                                                            <option value="168">Palau</option>
                                                            <option value="169">Palestinian Territory Occupied</option>
                                                            <option value="170">Panama</option>
                                                            <option value="171">Papua new Guinea</option>
                                                            <option value="172">Paraguay</option>
                                                            <option value="173">Peru</option>
                                                            <option value="174">Philippines</option>
                                                            <option value="175">Pitcairn Island</option>
                                                            <option value="176">Poland</option>
                                                            <option value="177">Portugal</option>
                                                            <option value="178">Puerto Rico</option>
                                                            <option value="179">Qatar</option>
                                                            <option value="180">Reunion</option>
                                                            <option value="181">Romania</option>
                                                            <option value="182">Russia</option>
                                                            <option value="183">Rwanda</option>
                                                            <option value="184">Saint Helena</option>
                                                            <option value="185">Saint Kitts And Nevis</option>
                                                            <option value="186">Saint Lucia</option>
                                                            <option value="187">Saint Pierre and Miquelon</option>
                                                            <option value="188">Saint Vincent And The Grenadines</option>
                                                            <option value="189">Saint-Barthelemy</option>
                                                            <option value="190">Saint-Martin (French part)</option>
                                                            <option value="191">Samoa</option>
                                                            <option value="192">San Marino</option>
                                                            <option value="193">Sao Tome and Principe</option>
                                                            <option value="194">Saudi Arabia</option>
                                                            <option value="195">Senegal</option>
                                                            <option value="196">Serbia</option>
                                                            <option value="197">Seychelles</option>
                                                            <option value="198">Sierra Leone</option>
                                                            <option value="199">Singapore</option>
                                                            <option value="200">Slovakia</option>
                                                            <option value="201">Slovenia</option>
                                                            <option value="202">Solomon Islands</option>
                                                            <option value="203">Somalia</option>
                                                            <option value="204">South Africa</option>
                                                            <option value="205">South Georgia</option>
                                                            <option value="206">South Sudan</option>
                                                            <option value="207">Spain</option>
                                                            <option value="208">Sri Lanka</option>
                                                            <option value="209">Sudan</option>
                                                            <option value="210">Suriname</option>
                                                            <option value="211">Svalbard And Jan Mayen Islands</option>
                                                            <option value="212">Swaziland</option>
                                                            <option value="213">Sweden</option>
                                                            <option value="214">Switzerland</option>
                                                            <option value="215">Syria</option>
                                                            <option value="216">Taiwan</option>
                                                            <option value="217">Tajikistan</option>
                                                            <option value="218">Tanzania</option>
                                                            <option value="219">Thailand</option>
                                                            <option value="220">Togo</option>
                                                            <option value="221">Tokelau</option>
                                                            <option value="222">Tonga</option>
                                                            <option value="223">Trinidad And Tobago</option>
                                                            <option value="224">Tunisia</option>
                                                            <option value="225">Turkey</option>
                                                            <option value="226">Turkmenistan</option>
                                                            <option value="227">Turks And Caicos Islands</option>
                                                            <option value="228">Tuvalu</option>
                                                            <option value="229">Uganda</option>
                                                            <option value="230">Ukraine</option>
                                                            <option value="231">United Arab Emirates</option>
                                                            <option value="232">United Kingdom</option>
                                                            <option value="233">United States</option>
                                                            <option value="234">United States Minor Outlying Islands
                                                            </option>
                                                            <option value="235">Uruguay</option>
                                                            <option value="236">Uzbekistan</option>
                                                            <option value="237">Vanuatu</option>
                                                            <option value="238">Vatican City State (Holy See)</option>
                                                            <option value="239">Venezuela</option>
                                                            <option value="240">Vietnam</option>
                                                            <option value="241">Virgin Islands (British)</option>
                                                            <option value="242">Virgin Islands (US)</option>
                                                            <option value="243">Wallis And Futuna Islands</option>
                                                            <option value="244">Western Sahara</option>
                                                            <option value="245">Yemen</option>
                                                            <option value="246">Zambia</option>
                                                            <option value="247">Zimbabwe</option>
                                                            <option value="248">Kosovo</option>
                                                            <option value="249">Curaçao</option>
                                                            <option value="250">Sint Maarten (Dutch part)</option>
                                                        </select>
                                                        <span id="country_error" class="validation_error"></span>
                                                    </div>
                                                </div>
                                                <div class="mb-0 w-1/2 p-2 pb-0 pt-0">
                                                    <div class="form_group">
                                                        <label>Comments</label>
                                                        <textarea name="comment" id="comment" class="form_control"
                                                            rows="1"></textarea>
                                                        <span id="comment_error" class="validation_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-0 w-1/2 p-2 pb-0 pt-0">
                                                <div class="form_group">
                                                    <input type="hidden" name="package" value="303">
                                                    <input type="hidden" name="package_type" value="753">
                                                    <input type="hidden" name="action" value="package_booking">
                                                    <button type="submit" class="btn primary-btn"
                                                        name="submit">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="right_sec w-1/2 bg-gray-100 pt-0">
                                            <div>
                                                <h3 class="text-xl">Package Details</h3>
                                                <div class="img-pack pt-1 pl-5 pb-3 inline-block w-full">
                                                    <img src="https://overlandescape.tekzini.com/storage/packages/thumb/020623015347-download-11-310523053111.jpg"
                                                        alt="Gwalior Tour">
                                                    <div class="text-lg leading-tight">
                                                        Gwalior Tour<br>
                                                        <p class="date-bg text-xs inline-block" style="color:#01b3a7;">3
                                                            NIGHTS/ 5 DAYS</p>
                                                    </div>
                                                    <p class="type-bg text-xs bg-orange-700 p-1 inline-block">super deluxe
                                                    </p>
                                                </div>
                                                <div class="sm_price pl-5">
                                                    <hr>
                                                    <h4 class="text-sm font-semibold pt-2">Booking Details</h4>
                                                    <!-- <span class="showPrice">₹500</span> <br>
                                         <small>Per Person on twin sharing</small> -->
                                                    <span class="w-full">
                                                        <div class="book_info_list">
                                                            <strong>Trip Date:</strong>
                                                            <span>Mon, Jul 03rd 2023
                                                            </span>
                                                        </div>
                                                        <div class="book_info_list">
                                                            <strong>Cost Per Person:</strong>
                                                            <span>1 X ₹20 = ₹20</span>
                                                        </div>
                                                        <div class="book_info_list">
                                                            <strong>Extra Bed:</strong>
                                                            <span>1 X ₹20 = ₹20</span>
                                                        </div>
                                                    </span>
                                                    <hr>
                                                    <div class="preview_pricebox book_info_list">
                                                        <strong>Booking Amount :</strong>
                                                        <span class="price">₹1,000</span>
                                                    </div>
                                                    <div class="preview_pricebox book_info_list">
                                                        <strong>Total Amount :</strong>
                                                        <span class="price">₹40</span>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </Popup>
        </template>

        <template v-if="store.popupType != 'innerHtml' && selectPersonPop">
            <Popup>
                <div class="modal-body SelectPersonInput">
                <div class="modal-header form-floating mb-3 w-full">
                    <h4 class="modal-title text-2xl">Select Person</h4>
                <div class="single_package_white">
                    <div class="single_btn">
                        <ul class="flex flex-wrap">
                            <li class="traveller_pricing_inner">
                                <div class="form_group">
                                    <label class="text-xs">Cost Per Person</label>
                                    <div class="custom_select">
                                        <select class="form_control element_choice" data-element-id="129" name="pce129">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="4">4</option>
                                            <option value="6">6</option>
                                        </select>
                                    </div>
                                    <div style="color:#f0562f" class="price_select129">₹60</div>
                                </div>
                            </li>
                            <li class="li_last"><label class="text-xs"> </label><a class="apbtn" href="#" id="applyTravellers">Apply</a></li>
                        </ul>
                    </div>
                    </div>
                </div>
                    </div>
            </Popup>
        </template>

    </Layout>
</template>
 
<script>
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
// https://www.npmjs.com/package/vue-toast-notification
import SearchForm from './components/SearchForm.vue';
import Layout from './components/layout.vue';
import { store } from './store';
import FancyboxWrapper from './components/FancyboxWrapper.vue';
import Popup from './components/popup.vue';
import { showPopup } from './utils/commonFuntions';

import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

const $toast = useToast();

export default {
    created() {
        document.body.classList.add('package-detail-page');
        document.body.classList.add('holiday_package');
    },
    beforeUnmount() {
        document.body.classList.remove('holiday_package');
    },
    data() {
        return {
            store,
            calenderPop: false,
            bookingFormPop: false,
            selectPersonPop: false,
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin],
                initialView: 'dayGridMonth'
            }
        }
    },
    methods: {
        showToast(toastObj) {
            $toast.open(toastObj);
        },
        showItenaryPopup() {
            store.popupType = 'innerHtml';
            store.popupContent = `${this.$refs.iternetyRef.innerHTML}`;
            store.popUpClass = 'download_itnery_pop_wrapper';
            showPopup();
        },
        showSelectPersonsPopup() {
            this.bookingFormPop = false;
            this.calenderPop = false;
            this.selectPersonPop = true;
            store.popupType = 'simple';
            store.popUpClass = 'SelectPersonInput';
            showPopup();
        },
        showClaenderPopup() {
            this.calenderPop = true;
            this.bookingFormPop = false;
            this.selectPersonPop = false;
            store.popupType = 'simple';
            showPopup();
        },
        showBookingPopup() {
            this.bookingFormPop = true;
            this.calenderPop = false;
            this.selectPersonPop = false;
            store.popupType = 'simple';
            showPopup();
        }
    },
    mounted() {
    },
    beforeUnmount() {
        document.body.classList.remove('package-detail-page');
    },
    beforeDestroy() {
    },

    watch: {
    },
    components: {
        Layout,
        SearchForm,
        FancyboxWrapper,
        Popup,
        FullCalendar
    },
    props: ["package","faq_row","destinations","itenaries","relatedPackagestObj"],
};
</script>
 