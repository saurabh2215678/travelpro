<template>
    <cardBox>
        <li>
            <div class="flex flex-row items-center">
                <div class="img-list-view w-1/3 m-2" ref="imgList">
                    <div class="swiper hotelViewgallery2">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide" v-for="accommodationImage in accommodation.accommodationImages">
                                <img
                                :src="accommodationImage.thumbSrc"
                                :alt="accommodationImage.title" />
                            </div> 
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                    <div thumbsSlider="" class="swiper hotelViewgallery" style="margin-left:0;">
                        <div class="swiper-wrapper" v-if="accommodation.accommodationImages && accommodation.accommodationImages.length > 1">
                            <div class="swiper-slide" v-for="accommodationImage in accommodation.accommodationImages">
                                <img
                                :src="accommodationImage.thumbSrc"
                                :alt="accommodationImage.title" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hotel-content-list-view w-1/2 p-3">
                    <div class="title text-xl">
                        <Link class="view-more text-xl mt-1 pb-2" :href="accommodation.url" >{{accommodation.name}}</Link></div>
                        <div class="star-loc">
                            <ul class="rating_list py-1" rating="1">
                                <StarRating :rating="accommodation.star_classification" read-only :show-rating="false" tooltip="Star ratings are provided by the property to reflect the comfort, facilities, and amenities you can expect."/>
                            </ul>
                            <div class="addrmap text-sm">
                                <i class="fa-solid fa-location-dot"></i> {{accommodation.place_name}}
                            </div>
                        </div>
                        <div class="hotel-facilities text-sm mt-1">
                            <!-- <ul class="flex">
                                <li v-for="accommodation_feature in accommodation.accommodation_features_arr">
                                    <img
                                    :src="accommodation_feature.src">
                                    <span>{{accommodation_feature.name}}</span>
                                </li>
                            </ul> -->
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
                                <div v-if="accommodation.publish_price && parseFloat(accommodation.publish_price) > parseFloat(accommodation.search_price)" class="cut-price text-lg leading-normal"><span class="text-slate-500 relative">{{showPrice(accommodation.publish_price,true)}}</span></div>
                                <div class="main-price text-xl py-1 text-teal-600 font-semibold">{{showPrice(accommodation.search_price,true)}}</div>
                            </ul>
                            <Link class="view-more text-sm mt-1 p-2"
                            :href="accommodation.url">Book Now</Link>
                        </template>
                    </div>
                </div>
            </li>
            <li class="last-card bg-slate-100 p-3 flex items-center"></li>
    </cardBox>
    
</template>

<script>
    import { store } from '../../store.js';
    import StarRating from 'vue-star-rating'
    import { Link } from '@inertiajs/inertia-vue3';
    import styled from 'vue3-styled-components';
    import {showPrice, showHotelRatingLabel} from '../../utils/commonFuntions';


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
        },
        data() {
            return {
                store,
            }
        },
        mounted(){
            this.setSliders();
        },
        methods:{
            showPrice,
            showHotelRatingLabel,
            setSliders(){
                var thumbSlider = this.$refs.imgList.querySelector('.hotelViewgallery');
                var mainSlider = this.$refs.imgList.querySelector('.hotelViewgallery2');
                var nextButton = this.$refs.imgList.querySelector('.swiper-button-next');
                var prevButton = this.$refs.imgList.querySelector('.swiper-button-prev');

                var thumbSwiper = new Swiper(thumbSlider, {
                    loop: false,
                    protect: true,
                    spaceBetween: 3,
                    slidesPerView: 4,
                    freeMode: true,
                    watchSlidesProgress: true,
                    });
                var mainSwiper = new Swiper(mainSlider, {
                    loop: false,
                    protect: true,
                    spaceBetween: 5,
                    navigation: {
                        nextEl: nextButton,
                        prevEl: prevButton,
                    },
                    thumbs: {
                        swiper: thumbSwiper,
                    },
                });
            }
        },
        components: {
            StarRating,
            cardBox,
            Link
        },
        props: ["accommodation"],
    }
    </script>