<template>
    <homepagewrapper>

        <HomeBannerSlider />

       <!-- <HomePackageSlider :isActivity="0" themeCategorySlug="pind-daan-tour-packages" :featured="1" :limit="10" title="Pind Daan Tour Packages" :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/pind-daan-tour-packages`" viewAllTitle="View All Pind Daan Tour Packages" sliderClass="pind_daan_slider" :itemsPerView="4" /> -->

        <HomePackageSlider 
        :isActivity="0" :featured="1" :limit="10" title="Popular Tour Packages" subTitle="These popular destinations have a lot to offer" :viewAllUrl="`${store.websiteSettings?.PACKAGE_URL}`" viewAllTitle="View all Packages" :itemsPerView="4" themeCategorySlug="" pagination="off"/>

        <HomePackageSlider :isActivity="0" destinationSlug="varanasi" :featured="1" :limit="10" title="Popular Varanasi Tour Packages" :viewAllUrl="`${store.websiteSettings?.DESTINATION_DETAIL_URL}/varanasi`" viewAllTitle="View All" sliderClass="pack_slider" />

        <section class="content-section way_book_section home_featured">
            <div class="lg:container lg:mx-auto">
                <div>
                    <div class="way_book_outer">
                        <ul class="way_book grid lg:grid-cols-3 grid-cols-3 gap-4">
                            <li>
                                <div class="why_flex">
                                    <div class="box_icon"><span><img src="./assets/images/globe.png" alt="700+ Destinations"></span></div>
                                    <div class="new_box_title_heading pl-5"><span>700+ Destinations</span> Our expert team handpicked all<br>destinations in this site</div>
                                </div>
                            </li>
                            <li>
                                <div class="why_flex">
                                    <div class="box_icon"><span><img src="./assets/images/best_price.png" alt="Happy Customers"></span></div>
                                    <div class="new_box_title_heading pl-5"><span>Best Price Guarantee </span>Price match within 48 hours of<br>order confirmation</div>
                                </div>
                            </li>
                            <li>
                                <div class="why_flex">
                                    <div class="box_icon"><span><img src="./assets/images/top_notch_support.png" alt="150+ Custom Packages"></span></div>
                                    <div class="new_box_title_heading pl-5"><span>Top Notch support </span>We are here to help, before,<br>during, and even after your trip. </div>
                                </div>
                            </li>
                            <div class="clearfix"></div>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="book_now_sec">
            <div class="xl:container xl:mx-auto">
                <div class="travel_section">
                    <h3 class="travel_heading">Single Traveller Partner Guaranteed Tours</h3>
                    <p>Pay twin sharing price even if you join in solo. We guarantee you a room partner</p>
                    <a href="/search-packages" class="book_now_btn">Book Now</a>
                </div>
            </div>
        </section>

        <HomeDestinationSlider :featured="1" :limit="6" :isGallery="true" title="Popular Domestic Destinations" :viewAllUrl="store.websiteSettings?.DESTINATION_URL" viewAllTitle="View All Destinations" />

        <HomeDestinationSlider :featured="1" :limit="10" destinationType="pilgrimage" title="Pilgrimage Destinations in India" :viewAllUrl="`${store.websiteSettings?.DESTINATION_URL}?destination_type=pilgrimage`" viewAllTitle="View All Pilgrimage" />

        <section class="enjoy_deal_sec">
            <div class="xl:container xl:mx-auto">
                <div class="deal_section">
                    <h3 class="deal_heading">Enjoy Summer Deals</h3>
                    <p>Up to <b>40%</b> Discount!</p>
                    <a href="/" class="book_now_btn">Learn More</a>
                    <div class="terms_fn">*Terms applied</div>
                </div>
            </div>
        </section>

        <!-- <template v-if="testimonials && testimonials.length > 0">
            <TestimonialSlider :testimonials="testimonials"/>
        </template> -->

        <HomeTestimonialSlider featured="1" limit="10"/>
    </homepagewrapper>
</template>
<script>
import Layout from './components/layout.vue';
import { store } from './store';
import StarIcon from './assets/svgs/star.svg';
import SvgIcon from './components/SvgIcon.vue';
import { Link } from "@inertiajs/inertia-vue3";
import HomeBannerSlider from './components/home/HomeBannerSlider.vue';
import HomePackageSlider from './components/home/HomePackageSlider.vue';
import HomeDestinationSlider from './components/home/HomeDestinationSlider.vue';
//import TestimonialSlider from './components/testimonialSlider.vue';
import HomeTestimonialSlider from './components/home/HomeTestimonialSlider.vue';
import BlogSlider from './components/BlogSlider.vue';
import OurTeamSlider from './components/OurTeamSlider.vue';
import styled from 'vue3-styled-components';

const homepagewrapper = styled.div`
& section.home_featured .slider_btns{position: static;width:auto;margin:0;transform: translateY(0);margin-left:1rem;}
& section.home_featured .slider_btns .theme-prev{margin-left:.5rem;}
& .swiper-button-disabled{opacity:.5;}
& .tour_category_box .images{height:18rem;border-radius: 20px;}
& .slider_btns div{border: 1px solid #b7b7b7;background: transparent;width:27px;height:27px;}
& .slider_btns div img{width:8px;}
& .featured_box .images{height:18.2rem;border-radius:20px;}
& .new_box_title_heading span{font-family: 'SF-Pro-Display', sans-serif , 'Twemoji Country Flags';font-weight:600;color:#000;}
& .testimonial_slider .testi_heading a{font-size: 18px;font-family: 'SF-Pro-Display', sans-serif , 'Twemoji Country Flags';color: rgb(32, 32, 36);font-weight: bold;}
& .client_info .name{font-size: 18px;font-family: 'SF-Pro-Display', sans-serif , 'Twemoji Country Flags';color: rgb(32, 32, 36);font-weight: bold;padding-left:0;}
& .testimonial_slider .swiper-slide{box-shadow:none;padding:0;}
& section.home_testimonial.home_featured .slider_btns{position: absolute;width: calc(100% + 8rem);left: -4rem;}
& section.home_testimonial.home_featured .slider_btns div{border:0;}
& section.home_testimonial.home_featured .slider_btns img{width:auto;}
& .client_info .client_name{padding: 0;margin-top: 1.5rem;}
& .home_featured .slider_box a.tour_category_box .title{color: #fff;font-size: 14px;font-family: 'SF-Pro-Display', sans-serif , 'Twemoji Country Flags';font-weight:500;}
& section.home_featured{padding-left: 2rem;}
& section.home_featured{padding-right: 2rem;}
& .home_testimonial {padding: 5.313rem 0;}


@media (max-width: 767px){
  & .title_right{justify-content: space-between;}
  & .testimonial_slider .testcont{padding: 2rem;}
  & .testi_text p{font-size:14px;}
  & section.home_testimonial.home_featured .slider_btns{width: calc(100% + 1rem);left: -1.2rem;}
  & section.home_featured{padding-left: 0;}
  & section.home_featured{padding-right: 0;}
  & .travel_section{width:100%;padding-bottom:8rem;}
  & .desti_main ul li{width:100%;}
  & .travel_section{padding-left:1rem;}
  & .travel_section{padding-right:1rem;}
  & .travel_heading{font-size:30px;}
  & .enjoy_deal_sec{background-position: bottom;}
  & .deal_section{padding-left:1rem;}
  & .deal_section{padding-right:1rem;}
  & section.home_testimonial.home_featured .slider_btns img{width:8px;}
  & section {padding: 3.75rem 0;}
  & .p-0{padding:0;}
  & .pb-0{padding-bottom:0;}
  & .book_now_sec{background-position:right;}

}

`

export default {
    created() {
        store.seoData = this.seo_data;
    },
    data() {
        return {
            store
        }
    },
    methods: {

    },
    mounted () {

    },
    beforeDestroy () {

    },
    watch:{

    },
    components: {
        //TestimonialSlider,
        HomeTestimonialSlider,
        BlogSlider,
        OurTeamSlider,
        HomeBannerSlider,
        HomePackageSlider,
        HomeDestinationSlider,
        Link,
        'svg-icon': SvgIcon,
        homepagewrapper
    },
    layout: Layout,
    props: ["testimonials", "seo_data"],
};
</script>