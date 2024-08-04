<template>
    <template v-if="action=='hotel_booking'">
        <div class="col_details">
            <h3 class="text-xl">Hotel Details</h3>
            <div class="img-pack pt-1 pb-3 inline-block w-full">
                <img :src="accommodation.thumbSrc" :alt="accommodation.name">
                <div class="text-lg w-full font-semibold">{{accommodation.name}}<br>
                    <p class="date-bg text-xs inline-block" style="color:#0078c2;" v-if="inventory_data.room_id">{{inventory_data.AccommodationRoom?.room_type_name}}</p>
                    <br>
                    <p class="type-bg text-xs bg-orange-700 p-1 inline-block text-white" v-if="inventory_data.plan_id">{{inventory_data.AccommodationPlan?.plan_name}}</p>
                    <p class="type-bg text-sm p-1 inline-block" v-else>&nbsp;</p>
                </div>
                
            </div>
            <div class="sm_price">
                <hr>
                <h4 class="text-sm font-semibold pt-2">Booking Details</h4>
                <ul class="w-full">
                    <li class="book_info_list" v-if="booking_data && booking_data.checkin">
                        <span class="flex">Checkin Date<em>:</em></span>
                        <span>{{DateFormat(booking_data.checkin)}}</span>
                    </li>

                    <li class="book_info_list" v-if="booking_data.checkout">
                        <span class="flex">Checkout Date<em>:</em></span>
                        <span>
                            {{DateFormat(booking_data.checkout)}}
                        </span>
                    </li>

                    <li class="book_info_list" v-if="booking_data.inventory">
                        <span class="flex">Room(s)<em>:</em></span>
                        <span>
                            {{booking_data.inventory}}
                        </span>
                    </li>

                    <li class="book_info_list" v-if="booking_data.adult">
                        <span class="flex">Adult<em>:</em></span>
                        <span>
                            {{booking_data.adult}}
                        </span>
                    </li>
                    <li class="book_info_list" v-if="booking_data.child">
                        <span class="flex">Child<em>:</em></span>
                        <span>
                            {{booking_data.child}}
                        </span>
                    </li>
                    <li class="book_info_list" v-if="booking_data.infant">
                        <span class="flex">Infant<em>:</em></span>
                        <span>
                            {{booking_data.infant}}
                        </span>
                    </li>
                <hr>
                <template v-if="booking_data.discount_amount">
                    <div class="book_info_list"><span class="flex">Sub Amount <em>:</em></span><span class=""> {{showPrice(booking_data.sub_total_amount)}}</span></div>
                    <div class="book_info_list"><span class="flex">Discount <em>:</em></span><span class=""> {{showPrice(booking_data.discount_amount)}}</span></div>
                </template>
                <li class="book_info_list" v-if="booking_data.booking_price > 0 && booking_data.total_pax > 0">
                    <span>Booking Amount<em>:</em></span>
                    <span>{{showPrice(booking_data.booking_price*booking_data.total_pax)}}</span>
                </li>
                <li class="book_info_list">
                    <span>Total Amount<em>:</em></span>
                    <span class="bold">{{showPrice(booking_data.total_amount)}}</span>
                </li>
            </ul>
            </div>
        </div>        
    </template>
    <template v-else-if="action=='rental_booking'">
        <div class="col_details">
            <h3 class="text-xl">Rental Details</h3>
            <div class="img-pack pt-1 pb-3 inline-block w-full">
                <img :src="booking_data.bike_data.src" :alt="booking_data.bike_data.name">
                <div class="text-lg">{{booking_data.bike_data.name}}<br>
                </div>
            </div>
            <div class="sm_price">
                <hr>
                <h4 class="text-sm font-semibold pt-2">Booking Details</h4>
                <ul class="w-full">
                    <li class="book_info_list" v-if="booking_data && booking_data.pickupDate">
                        <span class="flex">City name<em>:</em></span>
                        <span>{{booking_data.city_data?.name}}</span>
                    </li>
                    <li class="book_info_list" v-if="booking_data && booking_data.pickupDate">
                        <span class="flex">Pickup Location<em>:</em></span>
                        <span>{{booking_data.location_data?.name}}</span>
                    </li>
                    <li class="book_info_list" v-if="booking_data && booking_data.pickupDate">
                        <span class="flex">Pickup Date<em>:</em></span>
                        <span>{{DateTimeFormat(booking_data.pickupDate)}}</span>
                    </li>

                    <li class="book_info_list" v-if="booking_data.dropDate">
                        <span class="flex">Dropoff Date<em>:</em></span>
                        <span>
                            {{DateTimeFormat(booking_data.dropDate)}}
                        </span>
                    </li>
                     <li class="book_info_list" v-if="booking_data.dropDate">
                        <span class="flex">KMs <em>:</em></span>
                        <span>
                            {{booking_data.total_km}} km
                        </span>
                    </li>
                    <hr>
                    <template v-if="booking_data.discount_amount">
                        <li class="book_info_list"><span class="flex">Sub Amount <em>:</em></span><span class=""> {{showPrice(booking_data.sub_total_amount)}}</span></li>
                        <li class="book_info_list"><span class="flex">Discount <em>:</em></span><span class=""> {{showPrice(booking_data.discount_amount)}}</span></li>
                    </template>
                    <li class="book_info_list" v-if="booking_data.total_amount">
                        <span>Booking Amount<em>:</em></span>
                        <span>{{showPrice(booking_data.total_amount)}}</span>
                    </li>
                </ul>
                
            </div>
        </div>        
    </template>
    <template v-else>
        <h3 class="title text-lg font-bold">Package Details</h3>
        <div class="img-pack pt-1 pb-3 inline-block w-full">
            <img :src="package.thumbSrc" :alt="package.title">
            <div class="text-base leading-tight w-full font-semibold">
                {{package.title}}<br>
                <p class="date-bg text-xs inline-block" style="color:#0078c2;">{{package.package_duration}}</p>
                <br>
                <p class="type-bg text-xs bg-orange-700 p-1 inline-block text-white">{{packagePrice.title}}</p>
            </div>
            
        </div>
        <div class="sm_price">
            <hr>
            <h4 class="text-sm font-semibold pt-3 pb-3">Booking Details</h4>
            <ul class="w-full">
                <li class="book_info_list">
                    <span class="flex">Trip Date<em>:</em></span>
                    <span>
                        {{booking_data.trip_date}}
                        <template v-if="booking_data.trip_slot">
                            {{showTimeTitle(booking_data.trip_slot)}}
                        </template>

                    </span>
                </li>
                <template v-for="traveller in booking_data.travellers">
                <li class="book_info_list">
                    <span>{{traveller.pce_label}}<em>:</em></span>
                    <span>{{traveller.pce_val}} X {{showPrice(traveller.pce_price)}} = {{showPrice(traveller.sub_total)}}</span>
                </li>                    
                </template>

                <template v-if="booking_data.discount_amount">
                    <li class="book_info_list"><span class="flex">Sub Amount <em>:</em></span><span class=""> {{showPrice(booking_data.sub_total_amount)}}</span></li>
                    <li class="book_info_list"><span class="flex">Discount <em>:</em></span><span class=""> {{showPrice(booking_data.discount_amount)}}</span></li>
                </template>
                <li class="book_info_list" v-if="booking_data.booking_price > 0 && booking_data.total_pax > 0">
                    <span>Booking Amount<em>:</em></span>
                    <span>{{showPrice(booking_data.booking_price*booking_data.total_pax)}}</span>
                </li>
                <li class="book_info_list">
                    <span>Total Amount<em>:</em></span>
                    <span class="bold">{{showPrice(booking_data.total_amount)}}</span>
                </li>
            </ul>
        </div>        
    </template>
    
</template>
<script>
import { store } from '../store.js';
import {showPrice, DateFormat,DateTimeFormat, showTimeTitle} from '../utils/commonFuntions';
export default {
    name:"BookingSummary",
    created() {
        // $discount_amount = 0;
        // if($is_agent == 1) {
        //   $group_id = (auth()->user()) ? auth()->user()->group_id : '';
        //   $discount_category_id = $accommodation->discount_category_id??'';
        //   $discount_amount = CustomHelper::getDiscountPrice('hotel_listing',$discount_category_id,$total_amount,$group_id);
        // }
        // $total_amount = $total_amount - $discount_amount;
    },
    data() {
        return {
            store,
            total_amount:0,
            booking_price:0,
            total_pax:0,
            discount_amount:0,
        }
    },
    methods:{
        showPrice,
        DateFormat,
        DateTimeFormat,
        showTimeTitle,
    },
    watch:{

    },
    props: ["action","booking_data","package","packagePrice", "accommodation","inventory_data","accomodation_info", "userObject", "checkin", "checkout", "inventory", "adult", "child", "infant"],
}
</script>