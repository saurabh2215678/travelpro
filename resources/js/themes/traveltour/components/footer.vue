<template>
    <footerWrapper>
        <div class="footer_bottom">
            <div class="container">
                <div class="footer_bottom_inner">
                    <div class="sec sec_first">
                        <div class="sec_inner">

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
                    </div>
                    <div class="sec sec_second">
                        <div class="sec_inner">
                            <div class="footer_menu" v-html="footerMenuList"></div>
                        </div>
                    </div>
                    <div class="sec sec_third" style="display: none;">
                        <div class="sec_inner">
                            <div class="footer_menu">
                                <ul class="theme-nav">
                                    <li class="" id="item_id_37">
                                        <a href="javascript:void(0);">About</a>
                                        <ul class="sub-menu">
                                            <li class="" id="item_id_21">
                                                <a href="javascript:void(0);">About us</a>
                                            </li>
                                            <li class="" id="item_id_22">
                                                <a href="javascript:void(0);">Privacy Policy</a>
                                            </li>
                                            <li class="" id="item_id_24">
                                                <a href="javascript:void(0);">Testimonials</a>
                                            </li>
                                            <li class="" id="item_id_23">
                                                <a href="javascript:void(0);">Terms & Conditions</a>
                                            </li>
                                            <li class="" id="item_id_25">
                                                <a href="javascript:void(0);">Contact us</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="sec sec_forth">
                        <div class="sec_inner">
                            <h4>Contact Us</h4>
                            <ul class="footer_contact">
                                <li v-if="store.websiteSettings?.SITE_ADDRESS">
                                    <img src="../assets/images/contact3.png" />
                                    <p class="font17" v-html="store.websiteSettings?.SITE_ADDRESS"></p>
                                </li>
                                <li>
                                    <img src="../assets/images/contact2.png" />
                                    <p class="font17"><a href="tel:+91 987 654 3210">+91 987 654 3210</a></p>
                                </li>
                                <li v-if="store.websiteSettings?.SALES_EMAIL">
                                    <img src="../assets/images/contact1.png" />
                                    <p class="font17"><a :href="`mailto:${store.websiteSettings?.SALES_EMAIL}`">{{ store.websiteSettings?.SALES_EMAIL }}</a></p>
                                </li>
                            </ul>
                        </div>
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
    background-color: var(--theme-color);
    color: var(--white);
    padding: 4.5rem 0;
& .footer_bottom_inner {
    display: flex;
}
& .sec_first {
    width: 25%;
    padding-top: 1.3rem;
}
& .sec_second,
& .sec_third{
    width: 48%;
    & .theme-nav{
        display: flex;
        &>li{
            flex-grow: 1;
            padding: 0 1.5rem;
        }
    }
}
& .sec_forth {
    width: 27%;
}
& .social {
    display: flex;
    align-items: center;
}
& .footer_logo {
    margin-bottom: 1.4rem;
}
& .ftr_breif {
    font-size: 0.938rem;
    max-width: 18.375rem;
    margin-bottom: 1.25rem;
    line-height: 1.8;
}
& .social a {
    display: flex;
    margin-right: 2rem;
}
& .theme-nav>li{
    & .sub-menu a{
        font-size: 1.063rem;
        font-weight: 500;
        margin-bottom: 0.4rem;
        &:hover{
            color: var(--white);
        }
    }
}
& .sec_forth h4,
& .theme-nav>li>a{
    font-size: 1.625rem;
    font-weight: 600;
    margin-bottom: 2.4rem;
    &:hover{
        color: var(--white);
    }
}
& .footer_contact li {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    &:last-child{
        margin-bottom: 0;
    }
    & p{
        align-self: center;
        & a:hover{
            color: var(--white);
        }
    }
    & img {
        width: 2.375rem;
        margin-right: 0.85rem;
    }
}
@media (max-width: 767px){
    padding: 2rem 0;
    & .footer_bottom_inner{
        flex-direction: column;
    }
    & .sec_first, 
    & .sec_second, 
    & .sec_third,
    & .sec_forth{
        width: 100%;
    }
    & .sec_first {
        margin-bottom: 2rem;
    }
    & .sec_second .theme-nav {
        flex-wrap: wrap;
        &>li{
            padding: 0;
            margin-bottom: 1.45rem;
        }
    }
    & .sec_forth h4,
    & .theme-nav>li>a{
        margin-bottom: 1rem;
    }
    & .footer_contact li p{
        font-size: 1.1rem;
        line-height: 1.5;
    }
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

    },
    components:{
        Link,
        footerWrapper
    },
    props:["footerMenuList"]
}
</script>