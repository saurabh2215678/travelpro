<template>
    <RoomTypeWrapper id="content_rooms" class="room_type">
        <ul class="">
            <li class="flex">
                <div class="compact-room-img overflow-hidden w-5/12">
                    <div class="">
                        <div class="room-img-title bg-blue-200 p-2 font-semibold">Room Type</div>
                    </div>
                </div>

                <div class="room-options w-2/12">
                    <div class="">
                        <div class="room-img-title bg-blue-200 p-2 font-semibold">Sleeps</div>
                    </div>
                </div>
                <div class="room-options w-5/12">
                    <div class="repeater flex justify-between flex-row">
                        <div class="plan_options w-2/4">
                            <div class="flexa">
                                <div class="room-options-title bg-blue-200 font-semibold p-2">Room Options</div>
                            </div>
                        </div>
                        <div class="plan_price w-2/4">
                            <div
                            class="room-options-title bg-blue-200 font-semibold border-x-0 border-white border-x p-2">
                        Price</div>
                    </div>
                </div>
            </div>
        </li>

        <!-- Loop -->
        <li class="flex" v-for="room in accommodation.accommodationRooms">
            <div class="compact-room-img overflow-hidden w-5/12">
                <ul class="hide mobile-show">
                    <li class="flex">
                        <div class="compact-room-img overflow-hidden w-5/12">
                            <div class="room-img-title bg-blue-200 p-2 font-semibold">Room Type</div>
                        </div>
                    </li>
                </ul>
                <div class="border2">
                    <h4 class="text-sm m-3 mb-0 p-1 px-2 text-white bg-yellow-700 inline-block">{{room.room_type_name}}
                    </h4>

                    <template v-if="room.room_images && room.room_images.length > 0">
                        <!-- <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper roomgallery2 m-3 mb-0 mt-0" ref="sliderRef">
                            <div class="swiper-wrapper"> 
                                <div class="swiper-slide" v-for="room_image in room.room_images" >
                                    <a :data-fancybox="room.room_type_name"
                                        :href="room_image.src">
                                        <img
                                        :src="room_image.thumbSrc"
                                        :alt="room_image.title" />
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-button-next"></div>
                               <div class="swiper-button-prev"></div>
                        </div>
    
                        <div thumbsSlider="" class="swiper roomgallery m-2 mt-0" ref="sliderThumbRef">
                            <div class="swiper-wrapper" >
                                <div class="swiper-slide" v-for="room_image in room.room_images">
                                    <img
                                    :src="room_image.thumbSrc"
                                    :alt="room_image.title" />
                                </div>
                            </div>
                        </div> -->


                        <HotelCardSlider :images="room.room_images" />

                    </template>


                    
                    <div class="text-sm leading-normal p-5 pt-3 mt-2">
                        <ul class="room-feature pb-1" v-if="room.room_features && room.room_features.length > 0">
                            <li class="font-semibold" v-for="room_feature in room.room_features">{{room_feature.name}}</li>
                        </ul>
                        <hr>
                        <div class="" v-html="room.brief"></div>
                        <div class="" v-html="room.video_link"></div>
                    </div>
                </div>
            </div>
            <ul class="hide mobile-show">
                <li class="flex">
                    <div class="compact-room-img overflow-hidden w-5/12">
                        <div class="room-img-title bg-blue-200 p-2 font-semibold">Sleeps</div>
                    </div>
                </li>
            </ul>

<div class="room-options relative w-2/12">
    <div class="plan_price">
        <div class="p-7 pt-3 pb-0">
            <ul class="hotel_adult_child_icon">
                <!-- Adult -->

                <li v-for="n in room.max_adult_bed">
                    <svg style="width: 22px;margin: 0 auto; enable-background:new 0 0 23 37;" version="1.1" id="Layer_1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 23 37" 
                    xml:space="preserve">     
                    <path class="st0"
                    d="M5.72,16.51c0,0.13,0,0.24,0,0.36c0,3.43,0,5.98,0,9.41c0,0.54-0.11,1.04-0.5,1.44 c-0.48,0.5-1.08,0.71-1.74,0.47c-0.7-0.24-1.07-0.77-1.11-1.52c-0.02-0.42-0.02-0.85-0.02-1.27c0-3.61,0.01-6.33,0.01-9.94 c0-1.03,0.25-1.99,0.84-2.84c0.95-1.37,2.26-2.18,3.93-2.21c2.91-0.05,5.82-0.03,8.74-0.04c2.69,0,4.41,1.78,4.92,3.63 c0.15,0.53,0.21,1.11,0.21,1.66c0.02,3.69,0.01,6.49,0.01,10.18c0,0.36-0.01,0.73-0.06,1.09c-0.1,0.74-0.87,1.35-1.64,1.34 c-0.79-0.01-1.51-0.62-1.59-1.39c-0.06-0.57-0.04-1.14-0.04-1.71c0-3.07-0.01-5.25,0-8.32c0-0.3-0.1-0.36-0.37-0.35 c-0.59,0.03-0.6,0.02-0.6,0.62c0,2.12-0.01,6.19-0.01,13.99c0,4.93,0-1.95,0.01,2.98c0,0.96-0.36,1.7-1.21,2.17 c-1.35,0.76-3.1-0.14-3.27-1.68c-0.03-0.26-0.05-0.53-0.05-0.79c0-5.57,0,0.67,0-4.9c0-0.16,0-3.15,0-3.32c-0.35,0-0.67,0-1.02,0 c0,0.15,0,0.27,0,0.4c0,5.76,0,2.54,0,8.29c0,1.25-0.96,2.23-2.23,2.28c-1.16,0.04-2.22-0.88-2.26-2.07 c-0.06-1.59-0.04-3.19-0.04-4.78c0-10.82,0.01-5.11,0.01-10.27c0-0.57,0.01-2.14,0-2.71c0-0.07-0.11-0.19-0.18-0.19 C6.22,16.49,5.99,16.51,5.72,16.51z" />
                    <path class="st0"
                    d="M7.81,5.67c-0.03-2.06,1.61-3.86,3.87-3.88c2.15-0.01,3.87,1.78,3.84,3.95c-0.03,2.14-1.79,3.83-3.95,3.8 C9.51,9.51,7.79,7.74,7.81,5.67z" />
                </svg>
            </li>

            <li  v-for="n in room.max_child_no"><svg style="width: 20px;margin: 0 auto; enable-background:new 0 0 15.59 19.08;" version="1.1" id="Layer_1"
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 15.59 19.08" 
                xml:space="preserve">

                <path class="st0"
                d="M5.02,12.92c0-1.54,0-0.48,0-2.03c0-0.22-0.07-0.31-0.23-0.26c-0.06,0.02-0.11,0.07-0.15,0.12 c-0.27,0.32-0.54,0.65-0.81,0.98c-0.21,0.25-0.41,0.49-0.62,0.73c-0.42,0.46-1.06,0.5-1.53,0.11c-0.45-0.37-0.54-1.04-0.17-1.52 c0.33-0.43,0.68-0.84,1.03-1.26C3.01,9.23,3.48,8.65,3.98,8.1c0.69-0.76,1.56-1.18,2.59-1.22C7.2,6.85,7.83,6.85,8.46,6.87 c1.13,0.04,2.08,0.46,2.81,1.35c0.77,0.93,1.54,1.85,2.31,2.77c0.25,0.29,0.33,0.62,0.25,1c-0.08,0.38-0.33,0.64-0.68,0.78 c-0.39,0.15-0.77,0.1-1.08-0.18c-0.18-0.16-0.34-0.36-0.5-0.55c-0.35-0.42-0.7-0.84-1.05-1.26c-0.02-0.03-0.04-0.07-0.07-0.08 c-0.07-0.03-0.15-0.07-0.23-0.05c-0.04,0.01-0.09,0.1-0.1,0.15c-0.02,0.09-0.01,0.18-0.01,0.28c0,3.01,0,3.42,0,6.43 c0,0.23-0.05,0.44-0.16,0.64c-0.21,0.35-0.69,0.6-1.04,0.54c-0.55-0.1-0.9-0.41-1-0.92c-0.02-0.11-0.03-0.23-0.03-0.34 c0-1.65,0-3.3,0-4.96c0-0.19-0.06-0.28-0.22-0.33c-0.15-0.04-0.27,0-0.35,0.13c-0.04,0.06-0.05,0.16-0.05,0.23c0,1.66,0,3.31,0,4.97 c0,0.37-0.11,0.7-0.4,0.94c-0.36,0.3-0.77,0.36-1.2,0.16c-0.41-0.2-0.62-0.54-0.63-0.99c-0.01-1.36-0.01-2.73-0.01-4.09 C5.01,13.29,5.02,13.11,5.02,12.92C5.02,12.92,5.02,12.92,5.02,12.92z" />
                <path class="st0"
                d="M5,3.62c0.02-1.3,0.99-2.53,2.56-2.52c1.55,0.01,2.55,1.2,2.55,2.63C10.1,4.96,9.03,6.26,7.44,6.2 C6.12,6.14,5.01,5.06,5,3.62z" />
            </svg></li>
            <li><p class="text-xs text-sky-500"> Existing bed(s) can accommodate 3(Adult) and 2(Child)</p></li>
        </ul>
    </div>
</div>
</div>
<ul class="hide mobile-show">
    <li class="flex">
        <div class="compact-room-img overflow-hidden w-1/2">
            <div class="room-img-title bg-blue-200 p-2 font-semibold">Room Options</div>
        </div>
        <div class="compact-room-img overflow-hidden w-1/2">
            <div class="room-img-title bg-blue-200 p-2 font-semibold">Price</div>
        </div>
    </li>
</ul>
<div class="room-options last-border relative w-5/12" v-if="room.plan_data">

    <div class="repeater flex justify-between flex-row border-b-2 border-blue-100" v-for="room_plan in room.plan_data">
        <div class="plan_options w-2/4">
            <div class="room-options-type font-bold text-sm p-7 pt-3 pb-0"> {{room_plan.plan_name}}</div>
            <div class="room-service-type text-sm p-9 pt-0">
                <ul class="leading-normal list-disc pl-4 mt-2">
                    <li v-for="option in room_plan.options">
                        <i class="fa fa-times-circle-o red" v-if="inclusion.is_available"></i>
                        <i class="fa fa-times-circle-o red" v-else ></i>
                        &amp; {{option}}
                    </li>
                </ul>

            </div>
        </div>

       <!--  <div class="plan_price w-2/4 py-3">
            <div class="room-price price-list-view font-semibold text-lg p-2 pt-3 pb-1">
                <div v-if="room.publish_price && room.publish_price > room_plan.inventory_data.price" class="cut-price text-lg leading-normal"><span class="text-slate-500 relative">{{this.showPrice(room.publish_price)}}</span></div>
                <div class="main-price text-xl py-1 text-teal-600 font-semibold" v-if="room_plan.inventory_data && room_plan.inventory_data.price" >{{this.showPrice(room_plan.inventory_data.price)}}</div>
                <div class="main-price text-xl py-1 text-teal-600 font-semibold" v-else>{{this.showPrice(room_plan.price)}}</div>
            </div>
            <template v-if="this.isOnlineBooking('hotel_listing')">
                <div class="view_all_btn book-room-btn text-sm" v-if="room_plan.inventory_data.success == true && room_plan.inventory_data.price && parseFloat(room_plan.inventory_data.price) > 0">
                    <a
                    href="#" :inventoryId="room_plan.inventory_data.inventory_id" @click="handleSelectRoom">Select Room</a>
                </div>
                <div class="room-price font-semibold text-sm p-2 pt-3 pb-1" v-else>
                    We're <font color="red">Sold Out</font> for your dates
                </div>
            </template>
        </div> -->

        <div class="plan_price w-2/4 py-3" v-if="room_plan.inventory_data.success == true && room_plan.inventory_data.price && parseFloat(room_plan.inventory_data.price) > 0" >
            <div class="room-price price-list-view font-semibold text-lg p-2 pt-3 pb-1">
                <div v-if="parseFloat(room_plan.inventory_data.display_price) > parseFloat(room_plan.inventory_data.price)" class="cut-price text-lg leading-normal"><span class="text-slate-500 relative">{{showPrice(room_plan.inventory_data.display_price)}}</span></div>
                <div class="main-price text-xl py-1 text-teal-600 font-semibold">{{showPrice(room_plan.inventory_data.price)}}</div>
            </div>
            <div class="view_all_btn book-room-btn text-sm"><a
                href="#" :inventoryId="room_plan.inventory_data.inventory_id" @click="handleSelectRoom">Select Room</a></div>
        </div>
        <div class="plan_price w-2/4 py-3" v-else>
            <div class="room-price font-semibold text-sm p-2 pt-3 pb-1">
                We're <font color="red">Sold Out</font> for your dates
            </div>
        </div>
    </div>


    </div>
    <div class="room-options last-border relative w-5/12" v-else>

        <div class="plan_options w-2/4">
            <div class="flexa">
                <div class="room-options-type font-bold p-7 pt-3 pb-1"></div>
                <div class="room-service-type text-sm p-9 pt-0">

                    <ul class="leading-normal list-disc ml-2 text-sm">
                        <li></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</li>

<!--End loop  -->

</ul>
</RoomTypeWrapper>
</template>


<script>
import { store } from '../../store.js';
import axios from "axios";
import {showPrice, isOnlineBooking} from '../../utils/commonFuntions';
import {RoomTypeWrapper} from '../../styles/RoomTypeWrapperStyle';
import HotelCardSlider from './HotelCardSlider.vue';

export default {
    name:'HotelsRoomType',
    created() {

    },
    data() {
        return {
            store,
        }
    },
    methods:{
        showPrice,
        isOnlineBooking,
        handleSelectRoom (e) {
            e.preventDefault();
            e.stopPropagation();
            var currentObj = this;
            let inventory_id = e.target.getAttribute('inventoryId');
            var inventory = this.inventory;//"{{$inventory}}";
            var adult = this.adult;//"{{$adult}}";
            var child = this.child;//"{{$child}}";
            var infant = this.infant;//"{{$infant}}";
            var checkin = this.checkin;//"{{date('d/m/Y',strtotime($checkin))}}";
            var checkout = this.checkout;//"{{date('d/m/Y',strtotime($checkout))}}";
            if(inventory_id) {
                var action = 'hotel_booking';
                currentObj.processing = true;
                currentObj.errors = {};
                axios.post('/book_now', {
                    inventory_id, action, checkin, checkout, inventory, adult, child, infant
                })
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

                }
            });
            } else {
                alert('Please select inventory!')
            }
            return false;   
            },
    },
   
    components: {
        RoomTypeWrapper,
        HotelCardSlider
    },
      props: ["className","accommodation", "inventory", "adult", "child", "infant", "checkin", "checkout"],
}
</script>