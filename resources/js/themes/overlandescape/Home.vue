<template>
    <SearchForm />

    <!-- <HomeThemeSlider :featured="1" :limit="10" title="Packages by Theme" subTitle="" :viewAllUrl="`${store.websiteSettings?.TOUR_CATEGORY_URL}`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="" /> -->

    <HomeThemeSlider :featured="1" :limit="10" title="Packages by Theme" subTitle="" :viewAllUrl="`${TOUR_CATEGORY_URL}`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="" />

    <!-- <HomePackageSlider :isActivity="1" :featured="1" :limit="10" title="Short Activities" subTitle="" :viewAllUrl="`${store.websiteSettings?.ACTIVITY_URL}`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="" /> -->

    <HomePackageSlider :isActivity="1" :featured="1" :limit="10" title="Short Activities" subTitle="" :viewAllUrl="`${ACTIVITY_URL}`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="" />

    <HomePackageSlider :isActivity="0" :featured="1" :limit="10" flagSlug="group-tour-packages" title="Group Tour Packages" subTitle="" :viewAllUrl="`${PACKAGE_URL}?flag=2`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="" />

    <HomePackageSlider :isActivity="0" :featured="1" :limit="10" flagSlug="tailor-made-individual-packages" title="Tailor-made Individual Packages" subTitle="" :viewAllUrl="`${PACKAGE_URL}?flag=1`" viewAllTitle="View All" :itemsPerView="4" themeCategorySlug="" />

    <HomeHotelSlider :featured="1" :limit="10" />

    <section class="content-section way_book_section bggray home_featured">
        <div class="xl:container xl:mx-auto">
            <div>
                <h2 class="title text-2xl mb-6 text-center">Why Choose Us? </h2>
                <div class="way_book_outer">
                    <ul class="way_book grid lg:grid-cols-4 grid-cols-2 gap-4">
                        <li>
                            <div class="why_flex">
                                <div class="box_icon"><span><img src="./assets/images/exp.png" alt="20+ Experience"></span></div>
                                <div class="new_box_title_heading pl-5"><span>20+ </span>Years of Experience</div>
                            </div>
                        </li>
                        <li>
                            <div class="why_flex">
                                <div class="box_icon"><span><img src="./assets/images/team.png" alt="100+ Team of Professionals"></span></div>
                                <div class="new_box_title_heading pl-5"><span>100+ </span>Team of Professionals</div>
                            </div>
                        </li>
                        <li>
                            <div class="why_flex">
                                <div class="box_icon"><span><img src="./assets/images/happy_clients.png" alt="Happy Customers"></span></div>
                                <div class="new_box_title_heading pl-5"><span>1 Million+ </span>Happy Customers</div>
                            </div>
                        </li>
                        <li>
                            <div class="why_flex">
                                <div class="box_icon"><span><img src="./assets/images/package.png" alt="150+ Custom Packages"></span></div>
                                <div class="new_box_title_heading pl-5"><span>150+ </span>Custom Packages </div>
                            </div>
                        </li>
                        <div class="clearfix"></div>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section home_featured">
        <div class="md:container md:mx-auto w_820">
            <div>
                <h2 class="title text-2xl mb-6 text-center">Please fill the form and our expert will get back to you with further details.</h2>
                <div class="book-space">
                    <div class="bookspace-inner">
                        <formShortCode slug="[home_page_form]" class="left_form" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <HomeTestimonialSlider featured="1" limit="10"/>

    <HomeBlogSlider
        featured="1"
        limit="10"
        title="Travel News and Blogs"
        subTitle=""
        className="pb-0"
        categorySlug=""
    />

    <template v-if="teams && teams.length > 0">
        <OurTeamSlider :teams="teams"/>
    </template>

</template>
<script>
import Layout from './components/layout.vue';
import SearchForm from './components/SearchForm.vue';
import { store } from './store';
import StarIcon from './assets/svgs/star.svg';
import SvgIcon from './components/SvgIcon.vue';
import { Link } from "@inertiajs/inertia-vue3";
import formShortCode from './components/formShortCode.vue';
import HomeHotelSlider from './components/home/HomeHotelSlider.vue';
import OurTeamSlider from './components/OurTeamSlider.vue';
import HomeThemeSlider from './components/home/HomeThemeSlider.vue';
import HomePackageSlider from './components/home/HomePackageSlider.vue';
import HomeTestimonialSlider from './components/home/HomeTestimonialSlider.vue';
import HomeBlogSlider from './components/home/HomeBlogSlider.vue';

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
        // console.log(StarIcon);
    },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
    SearchForm,
    formShortCode,
    OurTeamSlider,
    HomeHotelSlider,
    Link,
    'svg-icon': SvgIcon,
    HomeThemeSlider,
    HomePackageSlider,
    HomeTestimonialSlider,
    HomeBlogSlider,
},
    layout: Layout,
    props: ["seo_data", "newsBlogs","teams",'TOUR_CATEGORY_URL','ACTIVITY_URL','PACKAGE_URL'],
};
</script>
