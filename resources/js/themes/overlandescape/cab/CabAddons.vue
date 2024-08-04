<template>
    <Layout :metaTitle="metaTitle" :metaDescription="metaDescription">
        <div class="xl:container xl:mx-auto">
            <div class="step_forms cab_additems">
                <div class="stp_wrap">
                    <div class="stp_tp_head">
                        <h3>Add On Items</h3>
                        <Link @click="goback" >Back to Search</Link>
                    </div>
                    <AddonsForm :goBack="goback" :routeInfo="routeInfo" :countryData="countryData" :tripType="tripType" :addons="addons" />
                </div>
            </div>
        </div>
    </Layout>
</template>
<style>
.headerType2 .topmain-header {
    background: linear-gradient(to bottom, #3c3a9a, #07c0a9);
}
</style>
<script>

import { store } from '../store';
import Layout from '../components/layout.vue';
import { Link } from "@inertiajs/inertia-vue3";
import AddonsForm from '../components/cab/AddonsForm.vue';
import {DateFormat,showTimeTitle,showPrice} from '../utils/commonFuntions.js';

export default {
    created() {
        document.getElementsByTagName('body')[0].classList.add('cab-book')
        document.body.classList.add('headerType2');
        store.fav_offer = 0;
    },
    data() {
        return {
            test : "test",
            store,
            routeInfo: this.routeInfo,
            tabId : 1
        }
    },
    methods: {
        DateFormat,
        showTimeTitle,
        showPrice,
        goback(){
            store.loadingName = "searchForm";
            window.history.back();
        },
        async handleCouponChange(e) {
            var name = e.target.name;
            var value = e.target.value;
            store.fav_offer = value;
        },
    },
    mounted () {
    },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
    Layout,
    Link,
    AddonsForm
},
    props: ["routeInfo", "countryData", "tripType", "metaTitle", "metaDescription", "coupons", "addons"],
};
  
</script>