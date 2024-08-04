<template>
    <cardBox>
        <li>
            <div class="flex flex-row items-center">

                <div class="img-list-view w-1/3 m-2">
                    <HotelCardSlider :images="accommodation.accommodationImages"/>
                </div>

                <div class="hotel-content-list-view w-1/2 p-3">
                    <div class="title text-xl">
                        <Link class="view-more text-xl font-semibold mt-1" :href="accommodation.url" >{{accommodation.name}}</Link></div>
                        <div class="star-loc">
                            <ul class="rating_list py-1" rating="1">
                                <StarRating :rating="accommodation.star_classification" read-only :show-rating="false" tooltip="Star ratings are provided by the property to reflect the comfort, facilities, and amenities you can expect."/>
                            </ul>
                            <div class="addrmap text-sm">
                                <i class="fa-solid fa-location-dot"></i> {{accommodation.place_name}}
                            </div>
                            <div class="facilities_list py-2" v-if="accommodation?.accommodation_facilities_arr && accommodation.accommodation_facilities_arr?.length > 0"> 
                                <div class="facility_title">Facilities : </div>
                                <span class="hotel_facilities">
    							    <p class="text-sm">
                                        <span class="" v-for="item in accommodation.accommodation_facilities_arr">
                                            {{ item.name }}
                                        </span>
                                    </p>
                                </span> 
							</div>
                            <div class="facilities_list py-2" v-if="accommodation.accommodation_features_arr?.length > 0"> 
                                <div class="facility_title">Accommodation Features : </div>
                            <span class="hotel_facilities">
							 <p class="text-sm">
                                <span class="" v-for="item in accommodation.accommodation_features_arr">
                                        {{ item.name }}
                                    </span>
                            </p>
                            </span> 
							</div>
                        </div>
                        <div class="hotel-facilities text-sm mt-1">
                            {{accommodation.brief}}
                        </div>
                    </div>
                    <div class="price-list-view w-1/4 p-3 text-right border-l"> 
                        <div class="rating-txt mb-3">
                            <ul class="flex items-center justify-end">
                                <li class="font-bold leading-5">{{this.showHotelRatingLabel(accommodation.rating)}} <br><span class="text-xs font-normal">{{accommodation.total_reviews}} reviews</span></li>
                                <li tooltip="Traveller Rating">{{accommodation.rating}}</li>
                            </ul>
                        </div>
                        <template v-if="accommodation.search_price && parseFloat(accommodation.search_price) > 0">
                            <ul>
                                <div v-if="accommodation.publish_price && parseFloat(accommodation.publish_price) > parseFloat(accommodation.search_price)" class="cut-price text-lg leading-normal"><span class="text-slate-500 relative">{{this.showPrice(accommodation.publish_price)}}</span></div>
                                <div class="main-price text-xl py-1">{{this.showPrice(accommodation.search_price)}}</div>
                            </ul>
                            <Link class="view-more text-sm mt-1 p-2"
                            :href="accommodation.url">Book Now</Link>
                        </template>
                    </div>
                </div>
            </li>
            <li class="last-card bg-slate-100 p-1 flex items-center"></li>
    </cardBox>
    
</template>

<script>
    import { store } from '../../store.js';
    import StarRating from 'vue-star-rating'
    import { Link } from '@inertiajs/inertia-vue3';
    import styled from 'vue3-styled-components';
    import {showPrice, showHotelRatingLabel} from '../../utils/commonFuntions';
    import HotelCardSlider from './HotelCardSlider.vue';


    const cardBox = styled.ul`
    & .swiper-button-prev:after,
    & .swiper-button-next:after{
        color: var(--theme-color);
    }

    & .swiper-slide img{
        background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
        border-radius: 5px;
        background-size: 200% 100%;
        animation: 1.5s shine linear infinite;
    }
    `

    export default {
        created() {
            console.log('sectionData ==', this.accommodation);
        },
        data() {
            return {
                store,
            }
        },

        methods:{
            showPrice,
            showHotelRatingLabel,
        },
        components: {
            StarRating,
            cardBox,
            Link,
            HotelCardSlider
        },
        props: ["accommodation"],
    }
    </script>