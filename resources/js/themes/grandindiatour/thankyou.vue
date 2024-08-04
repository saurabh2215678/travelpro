<template>
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
                    <div v-else>Thank You for contacting us.</div>
                </h3>
                <br>
                <h2>One of our team member will get in touch with you shortly.</h2>
                <a class="btn" href="/">Go To Home</a>
                <!-- <Link class="btn" href="/">Go To Home</Link> -->
            </div>
        </div>
    </ThankyouWrapper>
</template>

<script>
import Layout from './components/layout.vue';
import {ThankyouWrapper} from './styles/ThankyouWrapper';
import { store } from './store';
import { Link } from '@inertiajs/inertia-vue3';

export default {
    created(){
        // console.log('thankyou page', this.store);
    },
    data(){
        return{
            store
        }
    },
    components: {
    ThankyouWrapper,
    Link
},
    layout: Layout,
    props: ["success_message"],
};
</script>