<template>
    <Head>
        <title v-if="store.seoData?.meta_title">{{store.seoData?.meta_title}}</title>
        <meta v-if="store.seoData?.meta_description" head-key="description" name="description" :content="store.seoData?.meta_description" />
        <meta v-if="store.seoData?.meta_keyword" head-key="keywords" name="keywords" :content="store.seoData?.meta_keyword" />
        <link v-if="store.websiteSettings?.FAVICON_LOGO" rel="icon" :href="store.websiteSettings?.FAVICON_LOGO" type="image/x-icon">
    </Head>
    <Header :headerMenuList="this.headerMenuList" :processing="this.processing" />
    <slot/>
    <template v-if="store.popupType == 'innerHtml'">
        <popup :active="store.popupActive" :hidePopup="store.hidePopup" :showPopup="store.showPopup" :className="store.popUpClass">
            <div v-html="store.popupContent"></div>
        </popup>
    </template>
    <Footer :footerMenuList="this.footerMenuList" />
</template>
<script>
import { Head } from '@inertiajs/inertia-vue3'
import Header from './header.vue';
import Footer from './footer.vue';
import {showErrorToast, setHeaderHeight} from '../utils/commonFuntions.js';
import { store } from '../store';
import axios from "axios";
import popup from './popup.vue';
import './layout.css';

export default {
    name:"Layout",
    created() {
        this.getMenu();

        let params = new URL(document.location).searchParams;
        let tripType = parseInt(params.get("tripType"));
        store.tripType = tripType;
    },
    data() {
        return {
            headerMenuList:'',
            footerMenuList:'',
            processing:true,
            store
        }
    },
    updated() {
        const timeoutId = setInterval(() => {
            setHeaderHeight();
        }, 10);

        setTimeout(() => {
            clearTimeout(timeoutId);
        }, 2000);
    },
    methods: {
        showErrorToast,
        getMenu() {
            let currentObj = this;
            currentObj.processing = true;
            axios.get('/get_menu', {

            })
            .then(function (response) {
                if(response.data && response.data.success) {
                    currentObj.headerMenuList = response.data.headerMenuList;
                    currentObj.footerMenuList = response.data.footerMenuList;
                    store.websiteSettings = response.data.websiteSettings;
                    store.airline_faretypes = response.data.airline_faretypes;
                    store.userInfo = response.data.userInfo;
                } else {
                    if(response.data.redirect_url) {
                        window.location.href = response.data.redirect_url;
                    } else if(response.data.message) {
                        currentObj.showErrorToast(response.data.message);
                    }
                }
                currentObj.processing = false;
            })  
            .catch(function (error) {
                if(error.response.data.redirect_url) {
                    window.location.href = response.data.redirect_url;
                } else if(error.response.data.message) {
                    currentObj.showErrorToast(error.response.data.message);
                }
            });            
        }
    },
    components:{
        Header,
        Footer,
        popup
    },
    props:[]
}
</script>