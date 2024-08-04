<template>
<ThingsWrapper>
    <div class="container">
        <div class="tings_box">
            <div class="left_img md:w-1/2 w-full">
                <img :src="data?.widgetSrc" alt="">
            </div>
            <div class="right_txt md:w-1/2 w-full" v-if="this.data">
                <!-- <h2 class="fw400">{{data?.widget_name}}</h2> -->
                <h3 class="font25 color_dark fw700">{{data?.section_heading}}</h3>
    
                <p class="font18">{{ removeHtmlTags(data?.description) }}</p>
                <a class="font18 fw600 btn_theme" href="/destination/rajasthan"><span><img :src="'../assets/ladakhbikerental/images/arrow.png'" alt=""></span> Learn More</a>
            </div>
        </div>
    </div>
</ThingsWrapper>
</template>
<script>
import {ThingsWrapper} from '../styles/ThingsWrapper'
import { removeHtmlTags } from '../utils/commonFuntions';
export default{
    name: 'ThingsTodo',
    components:{
        ThingsWrapper
    },
    methods:{
        removeHtmlTags
    },
    props: ["data"]
} 
</script>