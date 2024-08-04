<template>
    <Head>
        <title v-if="metaTitle">{{metaTitle}}</title>
        <meta v-if="metaDescription" head-key="description" name="description" :content="metaDescription" />
        <link v-if="this.websiteSettings.FAVICON_LOGO" rel="icon" :href="this.websiteSettings.FAVICON_LOGO" type="image/x-icon">
    </Head>
    <Header :headerMenuList="this.headerMenuList" :websiteSettings="this.websiteSettings" :processing="processing" />
        <slot/>
        <template v-if="store.popupType == 'innerHtml'">
            <popup :active="store.popupActive" :hidePopup="store.hidePopup" :showPopup="store.showPopup" :className="store.popUpClass">
               <div v-html="store.popupContent"></div>
            </popup>
        </template>
    <Footer :footerMenuList="this.footerMenuList" :websiteSettings="this.websiteSettings" />
</template>
<script>
import { Head } from '@inertiajs/inertia-vue3'
import Header from './header.vue';
import Footer from './footer.vue';
import {showErrorToast} from '../utils/commonFuntions.js';
import { store } from '../store';
import axios from "axios";
import popup from './popup.vue';

export default {
    name:"Layout",
    created() {
        this.getMenu();
        window.headerMenu();
        document.body.classList.add('car-module');

        let params = new URL(document.location).searchParams;
        let tripType = parseInt(params.get("tripType"));
        store.tripType = tripType;
    },
    updated() {
        window.headerMenu();
    },
    data(){
        return{
            headerMenuList:'',
            footerMenuList:'',            
            websiteSettings:'',            
            processing:false,   
            store         
        }
    },
    methods: {
        showErrorToast,
        getMenu() {
            let currentObj = this;
            currentObj.processing = true;
            axios.get('/cab/get_menu', {

            })
            .then(function (response) {  
                // currentObj.output = response.data;
                if(response.data.success) {
                    currentObj.headerMenuList = response.data.headerMenuList;
                    currentObj.footerMenuList = response.data.footerMenuList;
                    currentObj.websiteSettings = response.data.websiteSettings;
                    store.websiteSettings = response.data.websiteSettings;
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
    props:["metaTitle", "metaDescription"]
}
</script>