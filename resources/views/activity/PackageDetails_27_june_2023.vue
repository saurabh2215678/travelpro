<template>

    <Layout :metaTitle="metaTitle" :metaDescription="metaDescription">
        <SearchForm />
        <section class="package-details-wrap ">
   <div class="xl:container xl:mx-auto">
      <div class="flex justify-between package_wrap_inner">
         <div class="left_view w-1/2 pr-7">
            <img src="http://127.0.0.1:8000/assets/overlandescape/images/noimage.jpg" class="img_responsive" alt="">
         </div>
         <div class="right_view w-1/2">
            <div class="right_price_box">
               <div class="scac flex items-center">
                  <ul class="form_list w-full float-left">
                     <li class="w-2/4">
                        <div class="package_type">
                           <label class="text-sm">Package Type</label>
                           <div class="custom_select">
                              <select class="form_control" name="package_type">
                                 <option value="726" selected="">Luxury</option>
                                 <option value="727">Super Deluxe</option>
                                 <option value="728">Deluxe</option>
                                 <option value="729">Standard</option>
                                 <option value="725">Premium</option>
                              </select>
                           </div>
                        </div>
                     </li>
                     <li style="display:block;" class="w-2/4">
                        <div class="form_group icon_calender inline-block  ">
                           <!-- <label class="text-sm">Select Trip Date</label> -->
                           <label class="text-sm"></label>
                           <!-- <input type="text" name="trip_date" id="trip_date" value="" class="form_control calendar" autocomplete="off"> -->
                           <input type="hidden" name="trip_slot" id="trip_slot" value="">
                           <label id="trip_slot_title" class="text-xs"></label>
                           <span id="trip_date_error" class="validation_error"></span>
                        </div>
                     </li>
                  </ul>
               </div>
               <div class="single_package_day">
                  <fieldset class="scheduler-border booking_fields w-full">
                     <legend>Select Travellers</legend>
                     <div class="single_package_white">
                        <div class="single_btn">
                           <ul class="flex flex-wrap justify-between">
                              <li class="traveller_pricing_inner">
                                 <div class="form_group">
                                    <label class="text-xs">Cost Per Person </label>
                                    <div class="custom_select">
                                       <select class="form_control element_choice" name="">
                                          <option value="0">0</option>
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="4">4</option>
                                          <option value="6">6</option>
                                       </select>
                                    </div>
                                    <div style="color:#f0562f" class="price_select129">₹12,000</div>
                                 </div>
                              </li>
                              <li class="traveller_pricing_inner">
                                 <div class="form_group">
                                    <label class="text-xs">Single Supplementary </label>
                                    <div class="custom_select">
                                       <select class="form_control element_choice" data-element-id="" name="">
                                          <option value="0">0</option>
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                          <option value="4">4</option>
                                          <option value="5">5</option>
                                          <option value="6">6</option>
                                       </select>
                                    </div>
                                    <div style="color:#f0562f" class="price_select132"></div>
                                 </div>
                              </li>
                              <li class="traveller_pricing_inner">
                                 <div class="form_group">
                                    <label class="text-xs">W/o Extra Bed </label>
                                    <div class="custom_select">
                                       <select class="form_control element_choice" data-element-id="" name="">
                                          <option value="0">0</option>
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                          <option value="4">4</option>
                                          <option value="5">5</option>
                                          <option value="6">6</option>
                                       </select>
                                    </div>
                                    <div style="color:#f0562f" class="priceselect"></div>
                                 </div>
                              </li>
                              <li class="traveller_pricing_inner">
                                 <div class="form_group">
                                    <label class="text-xs">Extra Bed </label>
                                    <div class="custom_select">
                                       <select class="form_control element_choice" data-element-id="" name="">
                                          <option value="0">0</option>
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                          <option value="4">4</option>
                                          <option value="5">5</option>
                                          <option value="6">6</option>
                                       </select>
                                    </div>
                                    <div style="color:#f0562f" class="price_select130"></div>
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </fieldset>
                  <ul class="btn_group mt-2 customize_btn">
                  </ul>
                  <div class="note_text text-sm mb-3"><strong>Note* : </strong> More than 6 persons please contact.</div>
               </div>
               <div class="booknow_btn">
                  <div class="price_box_inner pl-2">
                     <p class="text-lg font-semibold">Total Price:</p>
                     <span class="heading_sm3" id="final_pay_price">₹12,000</span>
                  </div>
                  <ul class="btn_group btn_listing">
                     <li>
                        <input type="hidden" name="action" value="booking">
                        <input type="hidden" name="package" value="">
                        <input type="submit" class="primary-btn" name="submit" value="Book Online">
                     </li>
                     <li class="text-center">
                        <a href="#" class="btn2 secondary-btn">Enquire Now</a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
      
      <div class="inclusions_share flex flex-wrap">
         <div class="left_side mb-5 pr-8 w-6/12 icon-wishlist relative">
            <ul class="inclusions inclusions_list">
               <li data-text="Hotel">
                  <img class="w-8" src="https://overlandescape.tekzini.com/storage/inclusion/thumb/220523064235-hotel.png">
                  Hotel            
               </li>
               <li data-text="Meals">
                  <img class="w-8" src="https://overlandescape.tekzini.com/storage/inclusion/thumb/220523064505-meals.png">
                  Meals            
               </li>
               <li data-text="Sightseeing">
                  <img class="w-8" src="https://overlandescape.tekzini.com/storage/inclusion/thumb/220523065155-sightseeing.png">
                  Sightseeing            
               </li>
               <li data-text="Transportation">
                  <img class="w-8" src="https://overlandescape.tekzini.com/storage/inclusion/thumb/220523065342-transportation.png">
                  Transportation            
               </li>
            </ul>
            
         </div>
         <div class="share-section w-6/12">
            <div class="flex py-0 pt-3 pl-1.5 items-center">
               <div class="bg_share">
                  <p class="mr-3">Share It On:</p>
                  <div class="footer_bottom_right">
                     <ul class="social_icon">
                        <li class="facebook"><a href="http://www.facebook.com/sharer.php?u=https://overlandescape.tekzini.com/package/highlights-of-ladakh-winter-package" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                        <li class="twitter"><a href="http://twitter.com/share?url=https://overlandescape.tekzini.com/package/highlights-of-ladakh-winter-package" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
                        <li class="whatsapp"><a href="https://web.whatsapp.com/send?text=https://overlandescape.tekzini.com/package/highlights-of-ladakh-winter-package" target="_blank"><i class="fa-brands fa-whatsapp"></i></a></li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
</div>

   </div>
  </section>

  <section>
     <div class="xl:container xl:mx-auto">
         <div class="flex flex-wrap">
           <div class="left w-3/4">
            <!--Activities List-->
            <div class="package_disc flex items-center mt-5 mb-5">
               <h2 class="text-base font-semibold">Activities</h2>
               <div class="list_row_right">
                  <ul class="activ_list">
                     <li class="ml-3 mb-2">Holiday Package</li>
                     <li class="ml-3 mb-2">Popular Tours</li>
                     <li class="ml-3 mb-2">Family Holiday Tour Packages</li>
                  </ul>
               </div>
            </div>
            <!--- ======== Description  ======== -->
            <div class="package_disc overview_box py-3.5 px-5">
               <div class="text-2xl font-semibold pb-2">Overview</div>
               <div class="text-sm">"Highlights of Ladakh Package" is to explore two  districts of Ladakh ( Leh &amp; Kargil). Kargil, also known for war zone between India 
               &amp; Pakistan in May- July 1999. En-route to Kargil  you will see some of the beautiful villages of Sham region &amp; monasteries along 
               with the Indus River flowing on your left side. After exploring three days to the West of Ladakh, next you will explore  bit of North and
               East of Ladakh with High Mountain Terrain like Khardong La and the mesmerising Pangong Lake.
            </div>
            </div>
            <!--- ======== Package Days Itinerary ======== -->
            <div class="package_day secpad mt-5">
                  <div class="container-full">
                    <div class="text-2xl font-semibold pb-2">Package Itinerary</div>
                  <div class="faqlist faqlist_in">
                   <ul>
                     <li class="faq_main" :class="activeItem == 'tab1' ? 'active' : ''">
                        <div class="ml-14">
                           <div class="faq_title" @click="this.activeItem == 'tab1' ? this.activeItem = 'tab0' : this.activeItem = 'tab1'">Leh Airport Pickup (4 Kms | 15 minutes approx )</div>
                           <div class="theme_tags mb-5">
                              <span class="tags">Leh</span>
                              <span class="tags"> Local Sightseeing</span>
                              <span class="tags"> Acclimatized </span>
                           </div>
                           <div class="day_curcle"><span>Day 1</span></div>
                           <div class="faq_data">
                              <div class="faq_data_wrap">
                                 <div class="faq_service heading6 mb-0">
                                    <div class="title2 mb-3 text-base">Services Included</div>
                                    <ul class="include_list inclusions" style="clear: both;display: block;width: 100%;height: 80px;">
                                       <li data-text="Hotel" style="float: left;text-align: center;margin-right: 15px;" class="active">
                                          <img style="width:50px;" src="http://127.0.0.1:8000/assets/overlandescape/images/ico3.png">
                                          <span>Hotel<span>
                                          </span></span>
                                       </li>
                                       <li data-text="Meals" style="float: left;text-align: center;margin-right: 15px;">
                                          <img style="width:50px;" src="http://127.0.0.1:8000/assets/overlandescape/images/ico3.png">
                                          <span>Meals<span>
                                          </span></span>
                                       </li>
                                       <li data-text="Transfers" style="float: left;text-align: center;margin-right: 15px;">
                                          <img style="width:50px;" src="http://127.0.0.1:8000/assets/overlandescape/images/ico3.png">
                                          <span>Transfers<span>
                                          </span></span>
                                       </li>
                                    </ul>
                                 </div>
                                 <div class="heading6 font-semibold">Leh</div>
                                 <div class="right-content-itinerary">
                                    <p>Arrive at Leh airport, meet  our representative and transport to your reserved accommodation. Check in  to the hotel and get proper rest to acclimatize. Later, in the evening visit the Leh Market, Leh Palace and Shanti Stupa. O/N Leh.</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </li>

                     <li class="faq_main" :class="activeItem == 'tab2' ? 'active' : ''">
                        <div class="ml-14">
                           <div class="faq_title" @click="this.activeItem == 'tab2' ? this.activeItem = 'tab0' : this.activeItem = 'tab2'">Leh Airport Pickup (4 Kms | 15 minutes approx )</div>
                           <div class="theme_tags mb-5">
                              <span class="tags">Leh</span>
                              <span class="tags"> Local Sightseeing</span>
                              <span class="tags"> Acclimatized </span>
                           </div>
                           <div class="day_curcle"><span>Day 1</span></div>
                           <div class="faq_data">
                              <div class="faq_data_wrap">
                                 <div class="faq_service heading6 mb-0">
                                    <div class="title2 mb-3 text-base">Services Included</div>
                                    <ul class="include_list inclusions" style="clear: both;display: block;width: 100%;height: 80px;">
                                       <li data-text="Hotel" style="float: left;text-align: center;margin-right: 15px;" class="active">
                                          <img style="width:50px;" src="http://127.0.0.1:8000/assets/overlandescape/images/ico3.png">
                                          <span>Hotel<span>
                                          </span></span>
                                       </li>
                                       <li data-text="Meals" style="float: left;text-align: center;margin-right: 15px;">
                                          <img style="width:50px;" src="http://127.0.0.1:8000/assets/overlandescape/images/ico3.png">
                                          <span>Meals<span>
                                          </span></span>
                                       </li>
                                       <li data-text="Transfers" style="float: left;text-align: center;margin-right: 15px;">
                                          <img style="width:50px;" src="http://127.0.0.1:8000/assets/overlandescape/images/ico3.png">
                                          <span>Transfers<span>
                                          </span></span>
                                       </li>
                                    </ul>
                                 </div>
                                 <div class="heading6 font-semibold">Leh</div>
                                 <div class="right-content-itinerary">
                                    <p>Arrive at Leh airport, meet  our representative and transport to your reserved accommodation. Check in  to the hotel and get proper rest to acclimatize. Later, in the evening visit the Leh Market, Leh Palace and Shanti Stupa. O/N Leh.</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </li>
                     
                  </ul>
                  </div>
               </div>
             </div>
             <!--- ======== Package Days Itinerary ======== -->
             <div class="package_accommodations">
              <div class="text-2xl font-semibold pb-2">Accommodation</div>
              <div class="text-sm pb-1"> <strong>Note:-</strong> We Will provide you these or similar accomodation depending on availability </div>

            </div>





<!--- ======== Policy ======== -->
            <div class="policy-accordion pt-5">
                  <div class="container-full">
                     <h3 class="text-2xl font-semibold pb-2">Policy</h3>
                     <div class="faqlist">
                        <ul>
                           <li class="list_accordion faq_main " :class="PolicyactiveItem == 'tab2' ? 'active' : ''">
                                 <div class="faq_title heading6" @click="this.PolicyactiveItem == 'tab2' ? this.PolicyactiveItem = 'tab0' : this.PolicyactiveItem = 'tab2'">Inclusions</div>
                                 <div class="faq_data">
                                    <div class="faq_data_wrap">
                                    <p>Accommodation on Double/ Twin sharing basis in above mentioned hotels.</p>
                                    <p>Meal Plan MAPAI (Breakfast and Dinner).</p>
                                    <p>To &amp; Fro Airport Transfers.</p>
                                    <p>Transportation by Innova Crysta with heater facility (point to point) in Premium and Luxury properties.</p>
                                    <p>Transportation by Innova/Xylo with heater facility (point to point) in Super Deluxe,Deluxe &amp; Standard properties.</p>
                                    <p>Transportation by Tempo Traveler with heater facility for 6, 8 &amp; 10 pax as per itinerary (point to point)</p>
                                    <p>Innerline permit, Ladakh Entry &amp; national park fees</p>
                                    <p>Oxygen &amp; oximeter in each vehicle for K. Top and Pangong Lake.</p>
                                    <p>5% GST &amp; any other applicable taxes.</p>
                                 </div>
                              </div>
                           </li>
                        </ul>
                        <ul>
                           <li class="list_accordion faq_main ">
                                 <div class="faq_title heading6" @click="this.PolicyactiveItem == 'tab2' ? this.PolicyactiveItem = 'tab0' : this.PolicyactiveItem = 'tab2'">Exclusions</div>
                                 <div class="faq_data">
                                    <div class="faq_data_wrap">
                                    <p>Any Airfare</p>
                                    <p>Any Kind of Personal Expenses or Optional Tours / Extra Meals Ordered.</p>
                                    <p>Monument &amp; Museum entry Charges.</p>
                                    <p>Anything not specifically mentioned under the head “Prices include”.</p>
                                    <p>Tips, Laundry, Phone Calls etc.</p>
                                    <p>Any Kind of Drinks (Alcoholic, Mineral, Aerated)</p>
                                    <p>Cost incidental to any change in the itinerary / stay on account of flight cancellation due to bad weather, ill health, roadblocks or any factors beyond control.</p>
                                    <p>Cost cannot be clubbed with any other package.</p>
                                 </div>
                              </div>
                           </li>
                        </ul>
                        <ul>
                           <li class="list_accordion faq_main ">
                                 <div class="faq_title heading6" @click="this.PolicyactiveItem == 'tab2' ? this.PolicyactiveItem = 'tab0' : this.PolicyactiveItem = 'tab2'">Cancellation Policy</div>
                                 <div class="faq_data">
                                    <div class="faq_data_wrap">
                                    <p>10% cancellation charges if cancelled before 30 days prior to the tour.</p>
                                    <p>25% cancellation charges if cancelled within 20 days prior to the tour.</p>
                                    <p>50% cancellation charges if cancelled within 10 days prior to the tour.</p>
                                    <p>No refund for tour starting within 10 days, after starting or middle of the tour.</p>
                                 </div>
                              </div>
                           </li>
                        </ul>
                        <ul>
                           <li class="list_accordion faq_main ">
                                 <div class="faq_title heading6" @click="this.PolicyactiveItem == 'tab2' ? this.PolicyactiveItem = 'tab0' : this.PolicyactiveItem = 'tab2'">Terms and Conditions</div>
                                 <div class="faq_data">
                                    <div class="faq_data_wrap">
                                    <p>50% Advance to confirm the reservation.</p>
                                    <p>Balance amount to be deposited 20 Days before tour departure.</p>
                                    <p>Full Confirmation of Hotels will be provided on full payment.</p>
                                    <p>25% cancellation charges if cancelled within 20 days prior to the departure of the tour.</p>
                                    <p>50% cancellation charges if cancelled within 10 days prior to the departure of the tour.</p>
                                    <p>No refund for tour starting less than 10 days of tour or after starting or middle of the tour.</p>
                                    <p>Vehicles provided as per tour plan above (Point to Point Basis)</p>
                                    <p>Transits between two destinations depend upon weather and road conditions.</p>
                                    <p>No refund on any unused transportation / any services in any circumstances.</p>
                                 </div>
                              </div>
                           </li>
                        </ul>
                        <ul>
                           <li class="list_accordion faq_main ">
                                 <div class="faq_title heading6" @click="this.PolicyactiveItem == 'tab2' ? this.PolicyactiveItem = 'tab0' : this.PolicyactiveItem = 'tab2'">Supplementary Other Charges</div>
                                 <div class="faq_data">
                                    <div class="faq_data_wrap">
                                    <p>Cultural Program
                                       Rs.11,000 per program. 
                                    </p>
                                    <p>Professional
                                       Tourist Guide Rs. 2200/- Per Day.
                                    </p>
                                    <p>1 Liter Package Water Bottle: INR 30
                                       per Bottle.
                                    </p>
                                 </div>
                              </div>
                           </li>
                        </ul>
                        
                     </div>
                  </div>
               </div>

              


           </div>
           <div class="right w-1/4">
nfjkukyuk
           </div>
           
         </div>
     </div>
      
      </section>

      <section class="home_testimonial">
         <!-- ==  Testimonial slider  ==== -->
      <div class="xl:container xl:mx-auto pt-5">
         <div class="testimonial">
            <h3 class="text-2xl font-semibold pb-2">Testimonial</h3>
               <div class="slider_box">
                  <div class="swiper testimonial_slider">
                     <div class="swiper-wrapper">
                        <div class="swiper-slide">
                           <div class="testimonial_box">
                              <div class="client_info">
                              <a href="">
                                 <div class="client_img">
                                    <img src="http://127.0.0.1:8000/assets/overlandescape/images/ico3.png" alt="">
                                 </div>
                                 <div class="client_name">
                                    <div class="name para_lg2 w-full p-3">Alish</div>
                                    <div class="city">US</div>
                                 </div>
                              </a>
                              </div>
                              <p>It's been a week since we've returned to the hustle and bustle of Hong Kong and we haven't stopped raving and reminiscing about the fabulous time we had trekking from Raru to Shang Sumdo. We just wanted you to know that we have really enjoyed having Namgyal as our guide. His enthusiasm to share his knowledge of the wildlife and the local culture has enriched the experience of our trek tenfold.</p>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="testimonial_box">
                              <div class="client_info">
                              <a href="">
                                 <div class="client_img">
                                    <img src="http://127.0.0.1:8000/assets/overlandescape/images/ico3.png" alt="">
                                 </div>
                                 <div class="client_name">
                                    <div class="name para_lg2 w-full p-3">Alish</div>
                                    <div class="city">US</div>
                                 </div>
                              </a>
                              </div>
                              <p>It's been a week since we've returned to the hustle and bustle of Hong Kong and we haven't stopped raving and reminiscing about the fabulous time we had trekking from Raru to Shang Sumdo. We just wanted you to know that we have really enjoyed having Namgyal as our guide. His enthusiasm to share his knowledge of the wildlife and the local culture has enriched the experience of our trek tenfold.</p>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="testimonial_box">
                              <div class="client_info">
                              <a href="">
                                 <div class="client_img">
                                    <img src="http://127.0.0.1:8000/assets/overlandescape/images/ico3.png" alt="">
                                 </div>
                                 <div class="client_name">
                                    <div class="name para_lg2 w-full p-3">Alish</div>
                                    <div class="city">US</div>
                                 </div>
                              </a>
                              </div>
                              <p>It's been a week since we've returned to the hustle and bustle of Hong Kong and we haven't stopped raving and reminiscing about the fabulous time we had trekking from Raru to Shang Sumdo. We just wanted you to know that we have really enjoyed having Namgyal as our guide. His enthusiasm to share his knowledge of the wildlife and the local culture has enriched the experience of our trek tenfold.</p>
                           </div>
                        </div>
                        <div class="swiper-slide">
                           <div class="testimonial_box">
                              <div class="client_info">
                              <a href="">
                                 <div class="client_img">
                                    <img src="http://127.0.0.1:8000/assets/overlandescape/images/ico3.png" alt="">
                                 </div>
                                 <div class="client_name">
                                    <div class="name para_lg2 w-full p-3">Alish</div>
                                    <div class="city">US</div>
                                 </div>
                              </a>
                              </div>
                              <p>It's been a week since we've returned to the hustle and bustle of Hong Kong and we haven't stopped raving and reminiscing about the fabulous time we had trekking from Raru to Shang Sumdo. We just wanted you to know that we have really enjoyed having Namgyal as our guide. His enthusiasm to share his knowledge of the wildlife and the local culture has enriched the experience of our trek tenfold.</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
       <div class="slider_btns">
         <div class="testimonial-prev theme-prev"><img src="" alt="Next Icon"></div>
         <div class="testimonial-next theme-next"><img src="" alt="Prev Icon"></div>
      </div>
    </div>
      </section>

  </Layout>
</template>

<script>
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
// https://www.npmjs.com/package/vue-toast-notification
import SearchForm from './components/SearchForm.vue';
import Layout from './components/layout.vue';
import { store } from './store';

const $toast = useToast();

export default {
    created() {
      document.body.classList.add('package-detail-page');
    },
    data() {
        return {
            //test : "test",
            store,
            activeItem: '',
            PolicyactiveItem: '',
        }
    },
    methods: {
        showToast(toastObj){
            $toast.open(toastObj);
        }
    },
    mounted () {
    },
    beforeUnmount() {
      document.body.classList.remove('package-detail-page');
      },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
        Layout,
        SearchForm
    },
    props: ["destinationLists", "airportLists", "sightseeningDestinationLists","originSightSeenRouteData", "routeList", "tourCategories",  "activityPackages", "featuredPackages", "nonFeaturedPackages","metaTitle" , "metaDescription","FLIGHT_URL","CAB_URL","HOTEL_URL","PACKAGE_URL","ACTIVITY_URL"],
};
</script>
