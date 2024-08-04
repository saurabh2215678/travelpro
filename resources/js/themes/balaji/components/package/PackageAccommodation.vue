<template>
    <div id="package_accommodations" class="mt-9">
       <div id="hotel_accom" class="hotel_accordion mt-5" >
          <h3 class="no_line text-2xl pb-1 font-bold">Accommodation</h3>
          <p class="pb-1"> <strong>Note:-</strong> We Will provide you these or similar accomodation depending on
             availability 
          </p>
          <template  v-for="accommodations_days in store.accommodations_days">
             <button class="accordion active">
                <div class="pull-left"><b></b></div>
                <div class="pull-right day_font">
                   <b v-html="accommodations_days.itenary_data"></b>
                </div>
             </button>
             <div class="hotel_package_detail" >
                <div class="slider_box">
                   <div class="swiper hotel_inner_slider">
                      <div class="swiper-wrapper" v-if="accommodations_days && accommodations_days.accommodation_data">
                         <div class="swiper-slide" v-for="accommodation in accommodations_days.accommodation_data">
                            <div class="hodel_detail_list">
                               <div class="hotel_info hotel_info_typeid385 " style="">
                                  <div class="hotel_info_box itenery_info">
                                     <div class="itn_img">
                                        <img :src="accommodation.thumbSrc" :alt="accommodation.name">
                                     </div>
                                     <div class="box-content" :style="accommodations_days.accommodation_data.length == 1 ? `position: absolute` : ''">
                                        <FancyboxWrapper>
                                           <h6 class="dest_title"><a class="fancy_popup fancybox.iframe" data-fancybox="" data-type="iframe"
                                              :href="accommodation.popup_url"
                                              rel="nofollow">{{accommodation.name}}</a></h6>
                                        </FancyboxWrapper>
                                        <div class="star-loc">
                                           <ul class="rating_list py-1 ml-0" rating="1">
                                             <StarRating :rating="accommodation.star_classification" read-only :show-rating="false"/>
                                           </ul>
                                           <div class="hotel_destination text-sm"><img class="map-i" src="../../assets/images/map.png">{{accommodation.place_name}} 
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
                   </div>
                   <div class="slider_btns">
                      <div class="hotel-inner-prev"><img src="../../assets/images/next.png" alt="Next Icon"></div>
                      <div class="hotel-inner-next"><img src="../../assets/images/prev.png" alt="Next Icon"></div>
                   </div>
                </div>
             </div>
          </template>
       </div>
    </div>
 </template>
 <script>
    import {useToast} from 'vue-toast-notification';
    import 'vue-toast-notification/dist/theme-default.css';
    // https://www.npmjs.com/package/vue-toast-notification
    import SearchForm from '../../components/SearchForm.vue';
    import Layout from '../../components/layout.vue';
    import FancyboxWrapper from '../../components/FancyboxWrapper.vue';
    import { store } from '../../store';
    import StarRating from 'vue-star-rating'
    import { Link } from "@inertiajs/inertia-vue3";
    import axios from "axios";
    
    const $toast = useToast();
    
    export default {
        name: "PackageAccommodation",
        created() {
            document.body.classList.add('package-detail-page');
            if(this.packageSelectedPrice){
    
                this.package_total_price = this.packageSelectedPrice.final_amount;
                this.package_booking_price = this.packageSelectedPrice.booking_price;
                var price_id =this.packageSelectedPrice.id;
                this.loadPriceData(price_id);
            }
        },
        beforeUnmount() {
            document.body.classList.remove('package-detail-page');
        },
        data() {
            return {
                test : "test",
                store,
                package_booking_price:0,
                package_total_price:0,
                packageSelectedPrice:this.packageSelectedPrice,
                package:this.package,
                // rating: this.accommodation.star_classification,
                travellerObj : {}
            }
        },
        methods: {
            handleTraveller(e){
                var travellername = e.target.name;
                var travellervalue = e.target.value;
                var travellerprice = e.target.getAttribute('price');
    
                this.travellerObj[travellername] = {'value' : travellervalue, 'price' : travellerprice };
    
                // console.log(this.travellerObj);
            },
            showToast(toastObj){
                $toast.open(toastObj);
            },
            decodeValue(value) {
                return JSON.parse(value);
            },
        },
        mounted () {
        },
        beforeDestroy () {
        },
        watch:{
        },
        components: {
            SearchForm,
            FancyboxWrapper,
            StarRating,
            Link
        },
        layout: Layout,
        props: ["package","packageSelectedPrice","faq_row","destinations","itenaries"],
    };
 </script>