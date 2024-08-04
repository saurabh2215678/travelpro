<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <TestimonialDetialWrapper class="testimonial_detail">
        <div class="xl:container xl:mx-auto">
            <div class="breadcrumb_inner">
                <!-- <h1 class="title text-2xl mb-1 color_theme fw600">{{ testimonialDetails.name }}</h1> -->
                <Breadcrumb :data="breadcrumbData"/>
            </div>
            
            <div class="flex mb-12">
                <div class="left_sec">
                    <h1 class="title text-2xl mb-3"> {{ testimonialDetails.name }}</h1>

                    <TestimonialImageSlider 
                        v-if="testimonialDetails.images && testimonialDetails.images.length > 0" 
                        :images="testimonialDetails.images"
                    />

                    <div class="left_sec_content">
                        <div class="testimonial_desc" v-html="testimonialDetails.description">
                        </div>
                        <div class="testimonial_author_card">
                            <img :src="testimonialDetails.testimonialSrc" alt="testimonialDetails.name">
                            <p>{{ testimonialDetails.name }}</p>
                        </div>

                        <div class="share_sec">
                            <div class="share pb-3 font-bold">Share</div>
                            <SocialShare :shareUrl="shareUrl"/>
                        </div>
                    </div>
                </div>
                <div class="right_sec">
                    <template v-if="this.explore_package && this.explore_package.length > 0">
                        <h4 class="title text-xl mb-2">Explore Great Deals</h4>
                        <ul class="packages_ul">
                            <!-- <PackageCard v-for="item in this.explore_package" :packageData="item" /> -->
                            <PackageWithTypeCard v-for="item in this.explore_package" :data="item"/>
                        </ul>
                    </template>
                    <div class="writereview">
                        <Link :href="this.WRITE_YOUR_TESTIMONIAL_URL">
                            <span>WRITE <strong>YOUR TESTIMONIAL</strong></span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </TestimonialDetialWrapper>

    <TestimonialSliderSection :data="related_testimonials" v-if="related_testimonials && related_testimonials.length > 0" />
</template>
<script>
import Layout from '../components/layout.vue';
import PackageCard from '../components/package/PackageCard.vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import { Link } from "@inertiajs/inertia-vue3";
import TestimonialSliderSection from '../components/testimonial/TestimonialSliderSection.vue';
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import { store } from '../store';
import TestimonialImageSlider from '../components/testimonial/TestimonialImageSlider.vue';
import PackageWithTypeCard from '../components/PackageWithTypeCard.vue';
import SocialShare from '../components/SocialShare.vue';
import { TestimonialDetialWrapper } from '../styles/TestimonialDetialWrapper';

export default {
    created() {
        console.log('explore_package==', this.explore_package)
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
    },
    data() {
        return {
            store,
            shareUrl : ''
        }
    },
    mounted(){
        this.shareUrl = window.location.href
    },
    components:{
        PackageCard,
        Breadcrumb,
        Link,
        TestimonialSliderSection,
        SearchFormWithBanner,
        TestimonialImageSlider,
        PackageWithTypeCard,
        SocialShare,
        TestimonialDetialWrapper
    },
    layout: Layout,
    props:["testimonialDetails","explore_package","related_testimonials","breadcrumbData","search_data","seo_data","WRITE_YOUR_TESTIMONIAL_URL"],
}
</script>