<template>
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
//import './layout.css';

export default {
    name:"Layout",
    created() {
        this.getMenu();
        let params = new URL(document.location).searchParams;
        let tripType = parseInt(params.get("tripType"));
        store.tripType = tripType;
    },
    /*updated() {
        document.title = store.seoData?.meta_title;
        document.head.querySelector('meta[name="description"]').content = store.seoData?.meta_description;
        document.head.querySelector('meta[name="keywords"]').content = store.seoData?.meta_keyword;
        const timeoutId = setInterval(() => {
            setHeaderHeight();
        }, 10);

        setTimeout(() => {
            clearTimeout(timeoutId);
        }, 2000);
    },*/

    updated() {
        
        var meta_title =
            store.seoData?.meta_title !== "" ? store.seoData?.meta_title : "";
        var meta_description =
            store.seoData?.meta_description !== ""
                ? store.seoData?.meta_description
                : "";
        var meta_keyword =
            store.seoData?.meta_keyword !== ""
                ? store.seoData?.meta_keyword
                : "";

        if (meta_title != "" && meta_title != undefined) {
            document.title = store.seoData?.meta_title;
        }
        if (meta_description != "" && meta_description != undefined) {
            document.head.querySelector('meta[name="description"]').content =
                store.seoData?.meta_description;
        }
        if (meta_keyword != "" && meta_keyword != undefined) {
            document.head.querySelector('meta[name="keywords"]').content =
                store.seoData?.meta_keyword;
        }
    },
    data() {
        return {
            headerMenuList:'',
            footerMenuList:'',
            processing:true,
            store
        }
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
        popup,
    },
    props:[]
}
</script>