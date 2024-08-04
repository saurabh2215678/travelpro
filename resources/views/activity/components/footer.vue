<template>
    <footer>
        <div class="footer_bottom">
            <div class="xl:container xl:mx-auto">
                <div class="footer_bottom_inner pb-6">
                    <div class="footer_bottom_left">
                        <div class="footer_logo">
                            <a :href="websiteSettings.FRONTEND_URL">
                                <img :src="websiteSettings.FRONTEND_LOGO">
                            </a>
                        </div>
                        <ul class="social_icon my-10">
                            <li><p class="text-sm">Follow Us: </p></li>
                            <li class="fb" v-if="websiteSettings.FACEBOOK"><a :href="websiteSettings.FACEBOOK" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path></svg></a></li>

                            <li class="twitter" v-if="websiteSettings.TWITTER"><a :href="websiteSettings.TWITTER" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path></svg></a></li>
                        </ul>
                        <div class="copyright text-sm">  Copyright © {{websiteSettings.SYSTEM_YEAR}} {{websiteSettings.SYSTEM_TITLE}} </div>
                        <div class="design_by text-sm">Design By : <a href="#"> Indiainternets</a> </div>
                    </div>
                    <div class="footer_bottom_right w_40">
                        <div class="footer_menu" v-html="footerMenuList"></div>
                    </div>
                    
                    <div class="footer_bottom_right">
                        <div class="footer_heading text-lg mb-5">Contact Us</div>
                        <ul class="links_list" v-if="websiteSettings.FRONTEND_ASSETS_URL">
                            <li class="pb-2.5">
                                <div class="footer_icon mr-2.5">
                                <img :src="`${websiteSettings.FRONTEND_ASSETS_URL}/img/location.png`">
                                </div>
                                <div class="footer_add">
                                <p class="text-base" v-html="websiteSettings.SITE_ADDRESS"></p>
                                </div>
                            </li>
                            <li class="pb-2.5">
                                <div class="footer_icon mr-2.5">
                                <img :src="`${websiteSettings.FRONTEND_ASSETS_URL}/img/telephone.png`">
                                </div>
                                <div class="footer_add">
                                <p class="text-lg">Ticket Booking or Enquiry:</p>
                                <a href="tel:+91 8491947052">+91 8491947052</a> / 
                                <a href="tel:+91 8491947053"> 8491947053</a>
                                </div>
                            </li>
                            <li class="pb-2.5">
                                <div class="footer_icon mr-2.5">
                                <img :src="`${websiteSettings.FRONTEND_ASSETS_URL}/img/telephone.png`">
                                </div>
                            <div class="footer_add">
                                <p class="text-lg">Hotel Booking or Enquiry:</p>
                                <a href="tel:+91 9858394405">+91 9858394405</a> / 
                                <a href="tel:+91 8491947044"> 8491947044</a>
                                </div>
                            </li>
                            <li class="pb-2.5">
                                <div class="footer_icon mr-2.5">
                                <img :src="`${websiteSettings.FRONTEND_ASSETS_URL}/img/telephone.png`">
                                </div>
                                <div class="footer_add">
                                <p class="text-lg">Tour &amp; Trek Booking or Enquiry: </p>
                                <a href="tel:+ 91 8491947039">8491947039</a> / 
                                <a href="tel:+ 8491947040"> 8491947040</a>
                                </div>
                            </li>
                            <li class="pb-2.5">
                                <div class="footer_icon mr-2.5">
                                <img :src="`${websiteSettings.FRONTEND_ASSETS_URL}/img/mail.png`"> 
                                </div>
                                <div class="footer_add">
                                <a :href="`mailto:${websiteSettings.SALES_EMAIL}`">{{websiteSettings.SALES_EMAIL}}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="footer_brand py-5">
                <ul v-if="websiteSettings.FRONTEND_ASSETS_URL">
                    <li><img :src="`${websiteSettings.FRONTEND_ASSETS_URL}/img/brand_left.png`"> </li>
                    <li><img :src="`${websiteSettings.FRONTEND_ASSETS_URL}/img/members.png`" alt="Members" class="img-responsive"></li>
                </ul>
                </div>
            </div>
        </div>
    </footer>
</template>
<script>
export default {
    name:"Footer",
    data(){
        return{
            
        }
    },
    components:{
    },
    props:["footerMenuList","websiteSettings"]
}
</script>