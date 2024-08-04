<template> 
    <FooterWrapper>
        <div class="footer_bottom">
            <div class="container">
                <div class="footer_contact_top">
                    <h4>SEGUICI SU :</h4>
                    <div class="social_inner flex items-center">
                        <ul class="social">
                            <li v-if="store.websiteSettings?.WHATSAPP" ><a :href="store.websiteSettings?.WHATSAPP" target="_blank"><i class="fa-brands fa-whatsapp"></i></a></li>
                            <li v-if="store.websiteSettings?.FACEBOOK" ><a :href="store.websiteSettings?.FACEBOOK" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li v-if="store.websiteSettings?.TWITTER" ><a :href="store.websiteSettings?.TWITTER" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
                            <li v-if="store.websiteSettings?.INSTAGRAM" ><a :href="store.websiteSettings?.INSTAGRAM"><i class="fa-brands fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="sec_inner">
               
                <!-- <NewsLetterSection/> -->
                <div class="footer_bottom_inner">
                    <!-- <div class="sec sec_first">
                        <div class="sec_inner">
                            <div class="text-xl font-bold pb-3">Water Sports</div>
                            <div class="footer_menu" v-html="footerMenuList"></div>
                            <div class="footer_logo">
                                <Link :href="store.websiteSettings?.FRONTEND_URL">
                                    <img :src="store.websiteSettings?.FRONTEND_LOGO">
                                </Link>
                            </div>
                            <p class="ftr_breif">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been.</p>
                            <ul class="social">
                                <li v-if="store.websiteSettings?.FACEBOOK" ><a :href="store.websiteSettings?.FACEBOOK"><img src="../assets/images/sc1.png" style="width: 1.75rem;" /></a></li>
                                <li v-if="store.websiteSettings?.TWITTER" ><a :href="store.websiteSettings?.TWITTER"><img src="../assets/images/sc2.png" style="width: 1.625rem;" /></a></li>
                                <li v-if="store.websiteSettings?.INSTAGRAM" ><a :href="store.websiteSettings?.INSTAGRAM"><img src="../assets/images/sc3.png" style="width: 1.563rem;"  /></a></li>
                                <li v-if="store.websiteSettings?.LINKEDIN" ><a :href="store.websiteSettings?.LINKEDIN"><img src="../assets/images/sc4.png" style="width: 1.563rem;" /></a></li>
                            </ul>

                        </div>
                    </div> -->
                    <div class="sec sec_second w-9/12">
                        <div class="sec_inner">
                            <div class="footer_menu" v-html="footerMenuList"></div>
                        </div>
                    </div>
                        <div class="sec sec_third w-80">
                            <div class="sec_inner">
                                <div class="footer_logo">
                                    <Link :href="store.websiteSettings?.FRONTEND_URL">
                                        <img :src="store.websiteSettings?.FRONTEND_LOGO">
                                    </Link>
                                </div>
                                <ul class="footer_contact"> 
                                    <li v-if="store.websiteSettings?.SITE_ADDRESS">
                                        <span class="flex items-center"><i class="fa-solid fa-location-dot mt-1"></i> <p class="pl-2" v-html="store.websiteSettings?.SITE_ADDRESS"></p></span>
                                    </li>
                                    <li v-if="store?.websiteSettings?.SITE_PHONE1"  class="top_phone1 font15"><span class="flex items-center"><i class="fa-solid fa-phone"></i> <p class="pl-2"><a :href="`tel:${store.websiteSettings?.SITE_PHONE1}`"> {{ store.websiteSettings?.SITE_PHONE1 }}</a></p></span></li>
                                    <li v-if="store.websiteSettings?.SALES_EMAIL">
                                        <span class="flex items-center"><i class="fa-regular fa-envelope mt-1"></i> <p class="pl-2"><a :href="`mailto:${store.websiteSettings?.SALES_EMAIL}`">{{ store.websiteSettings?.SALES_EMAIL }}</a></p></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div> 

            <div class="footer_accept">
                <div class="copyr inline-block w-full">
                    <div class="container"> 
                        <div class="w-1/2 float-left"> 
                        {{showLangTitle('Copyright')}} © 2023 Prem Viaggi
                        </div>  
                        <div class="w-1/2 float-right text-right">{{showLangTitle('DesignBy')}}: <a href="https://www.indiainternets.com/" rel="nofollow" target="_blank">{{showLangTitle('Indiainternets')}}</a></div>  
                    </div>
                </div>  
            </div>
        </div>
        <a href="https://api.whatsapp.com/send?phone=919650626369" class="whatsapp-box" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
        <div class="footerfix">
        <ul>
            <li class="callfix"></li>
            <li class="callfix"><a href="tel:+919650626369"><i class="fa-solid fa-phone"></i> Call Us</a></li> 
            <li class="mailfix"><a href="mailto:contact@andamanisland.in"><i class="fa-solid fa-envelope"></i> Mail Us</a></li> 
        </ul>
    </div>
    </FooterWrapper>
    
    
     
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../store';
import formShortCode from '../components/formShortCode.vue';
import NewsLetterSection from '../components/home/NewsLetterSection.vue';
import {FooterWrapper} from '../styles/FooterWrapper';
import {showLangTitle} from '../utils/commonFuntions';


export default {
    name:"Footer",
    created(){
       
    },
    methods: {
        showLangTitle,
    },
    data() {
        return {
            store
        }
    },
    updated() {
        // console.log('footer menu =', this.footerMenuList);
    },
    components:{
        Link,
        FooterWrapper,
        formShortCode,
        NewsLetterSection
    },
    props:["footerMenuList"]
}
</script>