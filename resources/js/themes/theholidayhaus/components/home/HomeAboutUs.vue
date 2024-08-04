<template>
    <HomeAboutUsWrapper>
        <div class="container">
            <div class="text-center pb-7">
                <h3 class="font34 fw600 secondary-font theme-color-dark" v-html="data?.widget_name"></h3>
                <p class="font18 fw500">{{data?.section_heading}}</p>
            </div>
            <div class="dvs_box videotxt">
                <div class="dvs_left w-1/2 text-right">
                    <div class="right-txt"> 
                    <!-- <img class="rght_img1" :src="data?.widgetSrc" alt=""> -->
                    <h3 class="font50 fw600 theme-color-dark">{{data?.about_widget_desc}}</h3> 
                    <div class="font16 color_dark700 pt-5 pr-5 pb-5">{{ removeHtmlTags(data?.description) }}</div>
                    <a class="btn btn_theme mr-5" href="/content/about-us">Read More</a>
                </div>
                </div>
                <div class="expimg w-1/2">
                <FancyboxWrapper>
                    <a data-fancybox="" href="https://www.youtube.com/watch?v=8l9q6lq-o_Y" class="fancybox">
                        <img src="../../assets/images/video-img.jpg" alt="img">
                        <!-- <div class="play-btn">
                            <i class="fa-solid fa-play"></i>
                        </div> -->
                    </a>
                </FancyboxWrapper>
            </div>
            </div>
        </div>
    </HomeAboutUsWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../../store';
import SvgRenderer from '../SvgRenderer.vue';
import { removeHtmlTags } from '../../utils/commonFuntions';
import FancyboxWrapper from '../../components/FancyboxWrapper.vue'
import {HomeAboutUsWrapper} from '../../styles/HomeAboutUsWrapper';

export default{
    name: 'HomeAboutUs',
    data() {
        return {
            store
        }
    },
    components: { 
        SvgRenderer,
        Link,
        HomeAboutUsWrapper,
        FancyboxWrapper
    },
    methods:{
        removeHtmlTags
    },
    props: ["data"]
}
</script>