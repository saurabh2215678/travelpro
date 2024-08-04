<template>
    <NewsletterWrapper>
        <img :src="'../assets/ladakhbikerental/images/fotter-bg.jpg'" alt="" class="w-full footer_img">
        <div class="container">
            <h3 class="title fw600 font30 color_dark">Our Newsletter</h3>
            <p class="mb-4 sub_title font17 color_secondary">Sign on to the Ladakh bike rental newsletter for our latest news,updates & annnoucements.</p>
            <div class="grid gap-2 md:grid-cols-4">
                <div class="form-floating mb-3 w-full">
                    <input type="text" placeholder="Name*" required="" class="form-control " datatypeinput="inputname" name="input4" value="">
                </div> 
                <div class="form-floating mb-3 w-full">
                    <input type="text" placeholder="Email*" required="" class="form-control " datatypeinput="inputname" name="input4" value="">
                </div> 
                <div class="form-floating mb-3 w-full">
                    <input type="text" placeholder="Whatsapp No.*" required="" class="form-control " datatypeinput="inputname" name="input4" value="">
                </div> 
                <div class="form-floating mb-3 w-full">
                    <button class="submit_btn fw600 font18">Subscribe</button>
                </div>  
            </div>
        </div>
    </NewsletterWrapper>
</template>
<script>
import {NewsletterWrapper} from '../../styles/NewsletterWrapper';
import SvgRenderer from '../SvgRenderer.vue';
export default{
    name : 'AllInclusiveTours',
    components:{
    NewsletterWrapper,
    SvgRenderer
}
}
</script>