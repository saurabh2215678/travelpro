<template>
    <AboutHomeWrapper class="about_home_page" v-if="store.seoData?.page_title">
        <!-- <img class="trending_theme_bg" src="../../assets/images/static-banner.jpg" /> -->
        <div class="container">
            <div class="about_page_wrapper">
                <div class="about_page_title text-center">
                    <h3 class="font35 fw700 secondary-font mb-7">Are not able to find the right tour that satisfies your travel needs?</h3>
                    <a class="btn btn_theme py-3" href="/contact-us">Get Expert Advice from The Holiday Haus</a> 
                </div> 
                <!-- <div class="about_content" :class="collapsed ? 'collapsed' : ''">
                    <div v-if="store.seoData?.page_brief || store.seoData?.page_description">
                        <p v-html="store.seoData.page_brief"></p>
                        <div v-html="store.seoData.page_description"></div>
                    </div>
                    <div class="read_option" @click="collapsed = !collapsed"> 
                        {{ collapsed ? 'Read More' : 'Read Less' }}
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                </div> -->
            </div>
        </div>
    </AboutHomeWrapper>
</template>
<script>
import {AboutHomeWrapper} from '../../styles/AboutHomeWrapper';
import { store } from '../../store';
export default{
    name : 'AboutHomePage',
    data(){
        return {
            store,
            collapsed : true
        }
    },
    components: {
        AboutHomeWrapper
    }
}
</script>