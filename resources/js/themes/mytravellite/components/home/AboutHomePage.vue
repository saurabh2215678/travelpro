<template>
    <AboutHomeWrapper class="about_home_page" v-if="store.seoData?.page_title">
        <div class="container">
            <div class="about_page_wrapper">
                <div class="about_page_title" v-if="store.seoData?.page_title">
                    <h3 class="font26 fw600 mb-2">{{store.seoData?.page_title}}</h3>
                </div>
                <div class="about_content" :class="collapsed ? 'collapsed' : ''">
                    <div v-if="store.seoData?.page_brief || store.seoData?.page_description">
                        <p v-html="store.seoData.page_brief"></p>
                        <div v-html="store.seoData.page_description"></div>
                    </div>
                    <div class="read_option" @click="collapsed = !collapsed"> 
                        {{ collapsed ? 'Read More' : 'Read Less' }}
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                </div>
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