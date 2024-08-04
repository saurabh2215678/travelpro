<template>
    <PayOnlineWrapper class="contact_us_address pt-7">
        <div class="container">
            <div class="text-right mb-4">
                <Breadcrumb :data="breadcrumbData"/>
            </div>

            <div class="flex contact_inr">
                <div class="w-1/2 left_sec">
                    <img src="./assets/images/pay_online_img.jpg" class="w-full" alt="">
                    <div class="booking_problem_img">
                        <p>Booking Queries? Call: <strong>{{store.websiteSettings?.BOOKING_QUERIES_NO}}</strong></p>
                    </div>
                </div>
                <div class="w-1/2">
                    <PayOnlineForm :seoData="seo_data" :countries="countries"  />
                </div>
            </div>
        </div>
    </PayOnlineWrapper>

</template>
<script>
import Layout from './components/layout.vue';
import Breadcrumb from './components/Breadcrumb.vue';
import PayOnlineForm from './components/PayOnlineForm.vue';
import { store } from './store';  
import { PayOnlineWrapper } from './styles/PayOnlineWrapper';

export default {
    name:'PayOnline',
    created(){
    // console.log('page store', this.store);
    },
    data() {
        return {
            store,
            breadcrumbData : [
                {url: '/', label: 'Home'},
                {label: 'Online Payment'},
            ]
        }
    },
    components:{
        PayOnlineWrapper,
        Breadcrumb,
        PayOnlineForm
    },
    layout: Layout,
    props: ["countries","seo_data"],
}
</script>