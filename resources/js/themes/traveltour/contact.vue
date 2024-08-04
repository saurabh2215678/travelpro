<template>
    <AddressWrapper class="contact_us_address">
        <div class="container">
            <div class="gray_add_box">
                <MapItem/>
                <hr class="my-5">
                <MapItem/>
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
import { AddressWrapper } from './styles/AddressWrapper';
import {ContactFormWrapper} from './styles/ContactFormWrapper';

export default {
    name:'contactus',
    components:{
        MapItem,
        AddressWrapper,
        formShortCode,
        ContactFormWrapper,
        Link
    },
    layout: Layout,
}
</script>