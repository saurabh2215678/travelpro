<template>
    <section class="home_hotel bggray home_featured section5" v-if="this.hotelList.data && this.hotelList.data.length > 0">
        <div class="xl:container xl:mx-auto">
            <div class="theme_title mb-6">
                <div class="title text-2xl">Our Domestic and International Hotels</div>
                <div class="view_all_btn"><Link :href="store.websiteSettings?.HOTEL_URL">View All</Link>
                </div>
            </div>
            <div class="p_relative">
                <div class="swiper hotel_slider">
                    <ul class="hotel_list swiper-wrapper">
                        <li class="swiper-slide" v-for="hotel in this.hotelList.data">
                            <Link class="hotel_box" :href="hotel.url">
                                <div class="images">
                                    <img
                                    :src="hotel.thumbSrc"
                                    :alt="hotel.name">
                                </div>
                                <div class="hotel_content py-4 px-2">
                                    <div class="title text-sm">{{hotel.name}}</div>
                                    <div class="star-block">
                                        <p class="para_md inline-block"><img
                                        src="../../assets/images/map_hotel.png"
                                        alt="Map Hotel">{{hotel.place_name}}</p>
                                        <div class="main-price text-sm py-1 text-teal-600 font-semibold" v-if="hotel.search_price && parseFloat(hotel.search_price) > 0"> <span class="font-normal text-black text-xs"> Starting From : </span> {{showPrice(hotel.search_price,true)}}</div>
                                        <ul class="rating_list py-1" :rating="hotel.star_classification"> 
                                            <StarRating v-model:rating="hotel.star_classification" read-only :show-rating="false"/>
                                        </ul>
                                    </div>
                                </div>
                                
                            </Link>
                        </li>           
                    </ul>
                </div>
                <div class="slider_btns">
                    <div class="hotel-prev theme-prev">
                        <img
                        src="../../assets/images/next.png" alt="Next Icon">
                    </div>
                    <div class="hotel-next theme-next">
                        <img
                        src="../../assets/images/prev.png" alt="Prev Icon">
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>


<script>
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import StarRating from 'vue-star-rating'
import {showPrice} from '../../utils/commonFuntions';
import axios from "axios";

export default {
    name:'HomeHotelSlider',
    created() {
        //console.log("testing=",hotel.search_price);
        this.loadHotels();
    },
    data() {
        return {
            store,
            hotelList:{
                "data": [],
                "links": "",
            },
            total_links: 0,
        }
    },
    methods:{
        showPrice,
        loadHotels(){
          var currentObj = this;
          var type = 'Hotel';
          let form_data = store?.searchData;
          form_data['type'] = type;
          form_data['featured'] = this.featured;
          form_data['limit'] = this.limit;
          axios.post('/hotel/ajaxAccommodationList',form_data)  
          .then(function (response) {  
            if(response.data.success) {
              currentObj.hotelList = response.data.hotelList;
            } else {
              alert('Something went wrong, please try again.');
            }
            // currentObj.processing = false;
            // console.log('running in then');
          });
        },
    },
    components: {
        StarRating,      
        Link
    },
    props: ["featured", "limit"],
}
</script>