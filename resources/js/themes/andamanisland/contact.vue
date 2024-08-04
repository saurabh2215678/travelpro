<template>
    <AddressWrapper class="contact_us_address pt-7">
        <div class="container">
            <div class="gray_add_box">
                <template v-if="contactDetails?.CONTACT_RESERVATION_OFFICE || contactDetails?.CONTACT_RESERVATION_OFFICE_IFRAME">
                    <MapItem :address="contactDetails?.CONTACT_RESERVATION_OFFICE" :map="contactDetails?.CONTACT_RESERVATION_OFFICE_IFRAME" />
                </template>
                <template v-if="contactDetails?.CONTACT_MAIN_OFFICE || contactDetails?.CONTACT_MAIN_OFFICE_IFRAME">
                    <hr class="my-5">
                    <MapItem :address="contactDetails?.CONTACT_MAIN_OFFICE" :map="contactDetails?.CONTACT_MAIN_OFFICE_IFRAME" />
                </template>
                <template v-if="contactDetails?.CONTACT_DELHI_OFFICE || contactDetails?.CONTACT_DELHI_OFFICE_IFRAME">
                    <hr class="my-5">
                    <MapItem :address="contactDetails?.CONTACT_DELHI_OFFICE" :map="contactDetails?.CONTACT_DELHI_OFFICE_IFRAME" />
                </template>
            </div>
        </div>
    </AddressWrapper>
    <ContactFormWrapper>
        <div class="container">
            <div class="form_box">
                <h4>Contact Us</h4>
                <formShortCode slug="[contact_us]" class="left_form" />
            </div>
        </div>
    </ContactFormWrapper>
</template>
<script>
import Layout from './components/layout.vue';
import MapItem from './components/contact/MapItem.vue';
import formShortCode from './components/formShortCode.vue';
import { Link } from "@inertiajs/inertia-vue3";
import { store } from './store';
import { AddressWrapper } from './styles/AddressWrapper';
import {ContactFormWrapper} from './styles/ContactFormWrapper';

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
        formShortCode,
        ContactFormWrapper,
        Link
    },
    layout: Layout,

    props : ['contactDetails','banner_image','seo_data']
}
</script>