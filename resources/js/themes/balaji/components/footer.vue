<template>
    <a id="to_top_scrollup"></a>
    <footerWrapper>
        <div class="footer_bottom">
            <div class="xl:container xl:mx-auto">
                <div class="footer_bottom_inner pb-6">

                <div class="footer_bottom_right ">
                    <div class="footer_menu" v-html="footerMenuList"></div>
                </div>

                <div class="footer_bottom_left">
                        <!-- <div class="footer_logo">
                            <Link :href="store.websiteSettings?.FRONTEND_URL">
                            <img :src="store.websiteSettings?.FRONTEND_LOGO">
                        </Link>
                    </div> -->
                    <p class="follow_us">Follow Us: </p>
                    <ul class="social_icon">
                        <li class="fb" v-if="store.websiteSettings?.FACEBOOK"><a :href="store.websiteSettings?.FACEBOOK" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path></svg></a></li>

                        <li class="twitter" v-if="store.websiteSettings?.TWITTER"><a :href="store.websiteSettings?.TWITTER" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path></svg></a></li>

                        <li class="instagrams" v-if="store.websiteSettings?.INSTAGRAM"><a :href="store.websiteSettings?.INSTAGRAM" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a></li>

                        <li class="linkedins" v-if="store.websiteSettings?.LINKEDIN"><a :href="store.websiteSettings?.LINKEDIN" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg></a></li>

                        <!-- <li class="printerst"><a href="/"><i class="fa-brands fa-pinterest-p"></i></a></li>  -->
                    </ul>

                    <div class="copyright text-sm"> © {{store.websiteSettings?.SYSTEM_YEAR}} {{store.websiteSettings?.SYSTEM_TITLE}} All Rights Reserved.</div>
                    <div class="design_by text-sm">Design By : <a href="https://www.indiainternets.com/" target="_blank"> Indiainternets</a> </div>

                <a href="tel:+918934011111" class="whatsapp-box" target="_blank"><i class="fa-solid fa-phone-volume"></i></a>
                </div>

                <div class="footer_bottom_right"> 
                    <div class="footer_heading text-lg mb-5">Contact Us</div>
                    <ul class="links_list" v-if="store.websiteSettings?.FRONTEND_ASSETS_URL">

                        <li class="pb-2.5" v-if="store.websiteSettings?.SITE_ADDRESS">
                            <div class="footer_icon mr-2.5">
                                <img :src="`${store.websiteSettings?.FRONTEND_ASSETS_URL}/img/location.png`">
                            </div>
                            <div class="footer_add">
                                <p class="text-base" v-html="store.websiteSettings?.SITE_ADDRESS"></p>
                            </div>
                        </li>
                        <li class="pb-2.5" v-if="store.websiteSettings?.SITE_PHONE">
                            <div class="footer_icon mr-2.5">
                                <img :src="`${store.websiteSettings?.FRONTEND_ASSETS_URL}/img/telephone.png`">
                            </div>
                            <div class="footer_add" v-html="store.websiteSettings?.SITE_PHONE">
                            </div>
                        </li>
                        <li class="pb-2.5" v-if="store.websiteSettings?.SITE_EMAIL">
                            <div class="footer_icon mr-2.5">
                                <img :src="`${store.websiteSettings?.FRONTEND_ASSETS_URL}/img/mail.png`"> 
                            </div>
                            <div class="footer_add" v-html="store.websiteSettings?.SITE_EMAIL">
                            </div>
                        </li>
                    </ul>
                   
                </div>


                <div class="footer_bottom_right">
                    <div class="footer_heading text-lg mb-5">Pay Safely With Us</div>
                    <p class="footer_text">The payment is encrypted and transmitted securely with an SSL protocol.</p>
                    <img src="../assets/images/payment_img.jpg" class="paymnt_img">
                </div>
            </div>
            
        </div>
    </div>
</footerWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../store';
import styled from 'vue3-styled-components';

const footerWrapper = styled.footer`
& .footer_bottom_right .theme-nav, .justify-between, .justify_btwn{flex-direction:column;}
& .social_icon li:first-child{padding-left:0;}
& .footer_heading{font-size:17px;font-family: SF-Pro-Display,sans-serif,Twemoji Country Flags;}
& .follow_us{font-weight:bold;}
& .footer_bottom_right .theme-nav > li > a{font-weight:bold;margin-bottom: 1rem;}
& a.whatsapp-box {
    position: fixed;
    z-index: 10;
    color: #fff;
    font-size: 20px;
    bottom: 15px;
    left: 15px;
    width: 50px;
    height: 50px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background-color: var(--theme-color);
    box-shadow: rgb(0 0 0 / 40%) 2px 2px 6px;
}

@media (max-width: 767px){
    & .footer_bottom_left, .footer_bottom_right{margin-bottom: 2rem;} 
}
`

export default {
    name:"Footer",
    data() {
        return {
            store
        }
    },
    mounted() {
        var btn = $('#to_top_scrollup');

        $(window).scroll(function() {
        if ($(window).scrollTop() > 300) {
            btn.addClass('show');
            } 

        else {
            btn.removeClass('show');
            }
        });

        btn.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop:0}, '300');
        });
    },
    components:{
        Link,
        footerWrapper
    },
    props:["footerMenuList"]
}
</script>