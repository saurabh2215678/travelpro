<template>
    <AddressWrapper class="contact_us_address pt-0">
        <div class="st_banner">
            <img src="./assets/images/inner-banner2.jpg" class="inner_banner2 w-full" alt="">
            <!-- <div class="container">
                <div class="header_content">
               <h1>Contact Us</h1>
               <p>We will be more than happy to assist you. Please feel free to make a call with your queries or fill out the contact form and we'll get back to you as soon as possible!!</p>
                </div>
            </div> -->
            
            <!-- <div class="uk-overlay-primary  uk-position-cover "></div> -->
        </div>
        <div class="container">
            <div class="gray_add_box">
                <div class="flex justify-between mt-7 mb-5">
                    <h1 class="title text-2xl">Contact Us</h1>
                </div>
                <template v-if="contactDetails?.CONTACT_MAIN_OFFICE || contactDetails?.CONTACT_MAIN_OFFICE_IFRAME">
                    <MapItem :address="contactDetails?.CONTACT_MAIN_OFFICE" :map="contactDetails?.CONTACT_MAIN_OFFICE_IFRAME" />
                </template>
            </div>
        </div>
    </AddressWrapper>
</template>
<script>
import Layout from './components/layout.vue';
import MapItem from './components/contact/MapItem.vue';
import { Link } from "@inertiajs/inertia-vue3";
import { store } from './store';
import { AddressWrapper } from './styles/AddressWrapper';

export default {
    name:'contactus',
    created(){
        store.seoData = this.seo_data;
        console.log('seo_data==', this.seo_data);
    },
    data(){
        return{
            store
        }
    },
    components:{
        MapItem,
        AddressWrapper,
        Link,
    },
    layout: Layout,

    props : ['contactDetails','banner_image','seo_data']
}
</script>