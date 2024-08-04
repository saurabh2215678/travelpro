<template>
    <footer>
        <div class="footer_bottom">
            <div class="xl:container xl:mx-auto">
                <div class="footer_bottom_inner pb-6">
                    <div class="footer_bottom_left">
                        <div class="footer_logo w-36">
                            <a :href="store.websiteSettings?.FRONTEND_URL">
                            <img :src="store.websiteSettings?.FRONTEND_LOGO">
                        </a>
                    </div>
                    <ul class="social_icon my-5">
                        <!-- <li><p class="text-sm">Follow Us: </p></li> -->
                        <li class="fb" v-if="store.websiteSettings?.FACEBOOK"><a :href="store.websiteSettings?.FACEBOOK" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path></svg></a></li>
                        <li class="twitter" v-if="store.websiteSettings?.TWITTER"><a :href="store.websiteSettings?.TWITTER" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path></svg></a></li>

                        <li class="instagram" v-if="store.websiteSettings?.INSTAGRAM"><a :href="store.websiteSettings?.INSTAGRAM" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg></a></li>

                        <li class="linkedin" v-if="store.websiteSettings?.LINKEDIN"><a :href="store.websiteSettings?.LINKEDIN" rel="nofollow" target="_blank" hreflang="en"><svg style="height: 28px;" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="24" cy="24" r="20" fill="#0077B5"/><path fill-rule="evenodd" clip-rule="evenodd" d="M18.7747 14.2839C18.7747 15.529 17.8267 16.5366 16.3442 16.5366C14.9194 16.5366 13.9713 15.529 14.0007 14.2839C13.9713 12.9783 14.9193 12 16.3726 12C17.8267 12 18.7463 12.9783 18.7747 14.2839ZM14.1199 32.8191V18.3162H18.6271V32.8181H14.1199V32.8191Z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="M22.2393 22.9446C22.2393 21.1357 22.1797 19.5935 22.1201 18.3182H26.0351L26.2432 20.305H26.3322C26.9254 19.3854 28.4079 17.9927 30.8101 17.9927C33.7752 17.9927 35.9995 19.9502 35.9995 24.219V32.821H31.4922V24.7838C31.4922 22.9144 30.8404 21.6399 29.2093 21.6399C27.9633 21.6399 27.2224 22.4999 26.9263 23.3297C26.8071 23.6268 26.7484 24.0412 26.7484 24.4574V32.821H22.2411V22.9446H22.2393Z" fill="white"/></svg></a></li>

                        <li class="youtube" v-if="store.websiteSettings?.YOUTUBE"><a :href="store.websiteSettings?.YOUTUBE" rel="nofollow" target="_blank" hreflang="en" style=" background: red;"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 461.001 461.001" xml:space="preserve"><g><path style="fill:#ffffff;" d="M365.257,67.393H95.744C42.866,67.393,0,110.259,0,163.137v134.728c0,52.878,42.866,95.744,95.744,95.744h269.513c52.878,0,95.744-42.866,95.744-95.744V163.137C461.001,110.259,418.135,67.393,365.257,67.393z M300.506,237.056l-126.06,60.123c-3.359,1.602-7.239-0.847-7.239-4.568V168.607c0-3.774,3.982-6.22,7.348-4.514l126.06,63.881C304.363,229.873,304.298,235.248,300.506,237.056z"/></g></svg></a></li>
                    </ul>
                    <!-- <div class="copyright text-sm">  Copyright © {{store.websiteSettings?.SYSTEM_YEAR}} {{store.websiteSettings?.SYSTEM_TITLE}} </div>
                        <div class="design_by text-sm">Design By : <a href="#"> Indiainternets</a> </div> -->
                    </div>
                    <div class="footer_bottom_right w_40">
                        <div class="footer_menu" v-html="footerMenuList"></div>
                    </div>

                    <div class="footer_bottom_right">
                        <div class="footer_heading text-lg mb-5">Contact Us</div>
                        <ul class="links_list" v-if="store.websiteSettings?.FRONTEND_ASSETS_URL">
                            <li class="pb-2.5">
                                <div class="footer_icon mr-2.5">
                                    <img :src="`${store.websiteSettings?.FRONTEND_ASSETS_URL}/./img/map-icon.png`" style="width:32px;">
                                </div>
                                <div class="footer_add">
                                    <p class="addrs" v-html="store.websiteSettings?.SITE_ADDRESS"></p>
                                </div>
                            </li>
                            <li class="pb-2.5">
                                <div class="footer_icon mr-2.5">
                                    <img :src="`${store.websiteSettings?.FRONTEND_ASSETS_URL}/./img/phone-icon.png`">
                                </div>
                                <div class="footer_add">
                                    <a href="tel:+91 8491947053"> 8491947053</a>
                                </div>
                            </li>
                            <li class="pb-2.5">
                                <div class="footer_icon mr-2.5">
                                    <img :src="`${store.websiteSettings?.FRONTEND_ASSETS_URL}/img/email-icon.png`"> 
                                </div>
                                <div class="footer_add">
                                    <a :href="`mailto:${store.websiteSettings?.SALES_EMAIL}`">{{store.websiteSettings?.SALES_EMAIL}}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="footer_brand py-5 text-center">
                    <div class="copyright text-sm">  Copyright © {{store.websiteSettings?.SYSTEM_YEAR}} {{store.websiteSettings?.SYSTEM_TITLE}} </div>
                <!-- <ul v-if="store.websiteSettings?.FRONTEND_ASSETS_URL">
                    <li><img :src="`${store.websiteSettings?.FRONTEND_ASSETS_URL}/img/brand_left.png`"> </li>
                    <li><img :src="`${store.websiteSettings?.FRONTEND_ASSETS_URL}/img/members.png`" alt="Members" class="img-responsive"></li>
                </ul> -->
            </div>
        </div>
    </div>
</footer>
</template>
<script>
    import { Link } from "@inertiajs/inertia-vue3";
    import { store } from '../store';
    export default {
        name:"Footer",
        data() {
            return {
                store
            }
        },
        components:{
            Link
        },
        props:["footerMenuList"]
    }
</script>