<template>
<div style="background:var(--theme-color);width:100%;height:6.875rem;"></div>
    <ThankyouWrapper class="thankyou_page_wrapper">
        <div class="container">
            <div class="thankyou-box">
                <img 
                    v-if="store?.websiteSettings?.FRONTEND_LOGO" 
                    :src="store?.websiteSettings?.FRONTEND_LOGO" 
                    class="img-fluid" 
                    :alt="store?.websiteSettings?.SYSTEM_TITLE + 'Logo'"
                >
                <img 
                    v-else
                    :src="'./assets/andamanisland/images/footer_logo.png'" 
                    class="img-fluid" 
                    :alt="store?.websiteSettings?.SYSTEM_TITLE + 'Logo'"
                >
    
                <h3>
                    <div v-if="success_message">{{success_message}}</div>
                    <div v-else>{{showLangTitle('ThankYouForContactingUs')}}</div>
                    
                </h3>
                <br>
                <h2>Uno dei membri del nostro team ti contatterà a breve.</h2>
                <Link class="btn" href="/">{{showLangTitle('GoToHome')}}</Link>
            </div>
        </div>
    </ThankyouWrapper>
</template>

<script>
import Layout from './components/layout.vue';
import {ThankyouWrapper} from './styles/ThankyouWrapper';
import { store } from './store';
import { Link } from '@inertiajs/inertia-vue3';
import {showLangTitle} from './utils/commonFuntions.js';

export default {
    created(){
        store.seoData = this.seo_data;
        console.log('thankyou page', this.store);
    },
    data(){
        return{
            store
        }
    },
    methods: {
      showLangTitle,
    },
    components: {
    ThankyouWrapper,
    Link
},
    layout: Layout,
    props: ["success_message","seo_data"],


};
</script>