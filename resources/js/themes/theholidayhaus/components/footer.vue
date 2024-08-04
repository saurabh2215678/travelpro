<template>
    <FooterWrapper>
        <div class="footer_bottom">
            <div class="container-fluid">
                <NewsLetterSection/>
                <div class="footer_bottom_inner">
                    <div class="sec sec_first w-1/4"> 
                        <div class="sec_inner">
                            <!-- <div class="text-xl font-bold pb-3">Water Sports</div>
                            <div class="footer_menu" v-html="footerMenuList"></div> -->  
                            <div class="footer_logo">
                                <Link :href="store.websiteSettings?.FRONTEND_URL">
                                    <img :src="store.websiteSettings?.FRONTEND_LOGO">
                                </Link>
                                <img class="footer-logo2 pt-3" src="../assets/images/footer-logo2.png" />
                            </div>
                            <div class="sec_inner mt-5">
                            <ul class="footer_contact">
                                <li v-if="store.websiteSettings?.SITE_ADDRESS">
                                    <span class="flex"> <p class="font19 pl-2" v-html="store.websiteSettings?.SITE_ADDRESS"></p></span>
                                </li>
                                <li v-if="store?.websiteSettings?.SITE_PHONE1" v-html="store?.websiteSettings?.SITE_PHONE1" class="top_phone font19"></li>
                                <li v-if="store.websiteSettings?.SALES_EMAIL">
                                    <span class="flex"> <p class="font19 pl-2"><a :href="`mailto:${store.websiteSettings?.SALES_EMAIL}`">{{ store.websiteSettings?.SALES_EMAIL }}</a></p></span>
                                </li>
                            </ul>
                        </div>
                            <!-- <p class="ftr_breif">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been.</p> -->
                            <ul class="social">
                                <li v-if="store.websiteSettings?.FACEBOOK" ><a :href="store.websiteSettings?.FACEBOOK"><img src="../assets/images/sc1.png" style="width: 1.75rem;" /></a></li>
                                <li v-if="store.websiteSettings?.TWITTER" ><a :href="store.websiteSettings?.TWITTER"><img src="../assets/images/sc2.png" style="width: 1.625rem;" /></a></li>
                                <li v-if="store.websiteSettings?.INSTAGRAM" ><a :href="store.websiteSettings?.INSTAGRAM"><img src="../assets/images/sc3.png" style="width: 1.563rem;"  /></a></li>
                                <li v-if="store.websiteSettings?.LINKEDIN" ><a :href="store.websiteSettings?.LINKEDIN"><img src="../assets/images/sc4.png" style="width: 1.563rem;" /></a></li>
                            </ul>

                        </div>
                        <div class="copyright_wrapper">
                        <div class="copy-right pb-1"><p class="font14">Copyright © 2024 theholidayhaus.in</p> </div>  
                       <div class="designtxt font14">Design By: <a href="https://www.indiainternets.com/" rel="nofollow" target="_blank">Indiainternets</a></div>
                    </div>
                    </div>
                    <div class="sec sec_second w-3/4">
                        <div class="sec_inner">
                            <div class="footer_menu" v-html="footerMenuList"></div>
                        </div>
                    </div>
                    <!-- <div class="sec sec_third w-1/4">
                        <div class="sec_inner"> 
                            <h4>Contact Us</h4>
                            <ul class="footer_contact">
                                <li v-if="store.websiteSettings?.SITE_ADDRESS">
                                    <span class="flex"><i class="fa-solid fa-location-dot mt-1"></i> <p class="font15 pl-2" v-html="store.websiteSettings?.SITE_ADDRESS"></p></span>
                                </li>
                                <li v-if="store?.websiteSettings?.SITE_PHONE1" v-html="store?.websiteSettings?.SITE_PHONE1" class="top_phone font15"></li>
                                <li v-if="store.websiteSettings?.SALES_EMAIL">
                                    <span class="flex"><i class="fa-regular fa-envelope mt-1"></i> <p class="font15 pl-2"><a :href="`mailto:${store.websiteSettings?.SALES_EMAIL}`">{{ store.websiteSettings?.SALES_EMAIL }}</a></p></span>
                                </li>
                            </ul>
                        </div>
                    </div> -->
                    <!-- <div class="sec sec_forth w-1/4">
                        <div class="sec_inner">
                            <h4>Call Back Request</h4>
                            <formShortCode slug="[call_back_request]" class="left_form" />
                            <div class="social_inner pt-3 flex items-center">
                            <div class="pr-2">Follow Us: </div>
                            <ul class="social">
                                <li v-if="store.websiteSettings?.FACEBOOK" ><a :href="store.websiteSettings?.FACEBOOK" target="_blank"><i class="fa-brands fa-facebook"></i></a></li>
                                <li v-if="store.websiteSettings?.TWITTER" ><a :href="store.websiteSettings?.TWITTER" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
                                <li v-if="store.websiteSettings?.INSTAGRAM" ><a :href="store.websiteSettings?.INSTAGRAM" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                                <li v-if="store.websiteSettings?.LINKEDIN" ><a :href="store.websiteSettings?.LINKEDIN" target="_blank"><i class="fa-brands fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                        </div>
                    </div> -->
                </div>
            </div>  
        </div>
        <a href="https://api.whatsapp.com/send?phone=919932080051" class="whatsapp-box" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
        <div class="footerfix">
        <ul>
            <li class="callfix"></li>
            <li class="callfix"><a href="tel:+91 9932080051"><i class="fa-solid fa-phone"></i> Call Us</a></li> 
            <li class="mailfix"><a href="mailto:sales@mytravellites.com"><i class="fa-solid fa-envelope"></i> Mail Us</a></li> 
        </ul>
    </div>
    </FooterWrapper>
    <!-- <div class="copyright inline-block w-full p-3 text-sm text-white">
         <div class="container"> 
            <div class="w-1/2 float-left"> 
            © 2023 All Right Reserved By Mytravellite
            </div>  
            <div class="w-1/2 float-right text-right">Design By: <a href="https://www.indiainternets.com/" rel="nofollow" target="_blank">Indiainternets</a></div>  
          </div>
    </div> -->
    
    

</template>

  
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../store';
import formShortCode from '../components/formShortCode.vue';
import NewsLetterSection from '../components/home/NewsLetterSection.vue'; 
import {FooterWrapper} from '../styles/FooterWrapper';
// import Popup from '../components/popup.vue';

export default {
    name:"Footer",
    created(){
       
    },
    data() {
        return {
            store,
        }
    },
    methods: {
        // openPopup() {
        //     store.popupActive = true;
        // }
    },
    mounted(){
        // this.openPopup();
    },
    updated() {

    //     var domesticPackagesLink = document.querySelector('#item_id_101 > a');
    //     var domesticPackagesListItem = document.getElementById('item_id_101');
    //     domesticPackagesLink.addEventListener('click', function(event) {
    //         event.preventDefault();
    //         domesticPackagesListItem.classList.toggle('active');
    //     });
    //     //// 2 ////
    //     var domesticPackagesLink = document.querySelector('#item_id_114 > a');
    //     var domesticPackagesListItem2 = document.getElementById('item_id_114');
    //     domesticPackagesLink.addEventListener('click', function(event) {
    //         event.preventDefault();
    //         domesticPackagesListItem2.classList.toggle('active');
    //     });
    //    //// 3 ////
    //     var domesticPackagesLink = document.querySelector('#item_id_130 > a');
    //     var domesticPackagesListItem3 = document.getElementById('item_id_130');
    //     domesticPackagesLink.addEventListener('click', function(event) {
    //         event.preventDefault();
    //         domesticPackagesListItem3.classList.toggle('active');
    //     });

    // Element 1
            var domesticPackagesLink1 = document.querySelector('#item_id_101 > a');
            var domesticPackagesListItem1 = document.getElementById('item_id_101');
            if (domesticPackagesLink1 && domesticPackagesListItem1) {
                domesticPackagesLink1.addEventListener('click', function(event) {
                    event.preventDefault();
                    domesticPackagesListItem1.classList.toggle('active');
                });
            } else {
                console.error("Element with ID 'item_id_101' or its child anchor element not found.");
            }

            // Element 2
            var domesticPackagesLink2 = document.querySelector('#item_id_114 > a');
            var domesticPackagesListItem2 = document.getElementById('item_id_114');
            if (domesticPackagesLink2 && domesticPackagesListItem2) {
                domesticPackagesLink2.addEventListener('click', function(event) {
                    event.preventDefault();
                    domesticPackagesListItem2.classList.toggle('active');
                });
            } else {
                console.error("Element with ID 'item_id_114' or its child anchor element not found.");
            }

            // Element 3
            var domesticPackagesLink3 = document.querySelector('#item_id_130 > a');
            var domesticPackagesListItem3 = document.getElementById('item_id_130');
            if (domesticPackagesLink3 && domesticPackagesListItem3) {
                domesticPackagesLink3.addEventListener('click', function(event) {
                    event.preventDefault();
                    domesticPackagesListItem3.classList.toggle('active');
                });
            } else {
                console.error("Element with ID 'item_id_130' or its child anchor element not found.");
            }



        // console.log('footer menu =', this.footerMenuList);
    },
    components:{
        Link,
        FooterWrapper,
        formShortCode,
        NewsLetterSection,
        //Popup
    },
    props:["footerMenuList"]
}
</script>