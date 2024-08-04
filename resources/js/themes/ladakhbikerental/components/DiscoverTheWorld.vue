<template>
    <DiscoverTheWorldWrapper>
        <div class="container">
            <div class="dvs_box">
                <div class="dvs_left md:w-1/2 w-full">
                    <h3 class="font25 color_dark fw700" v-html="data?.widget_name"></h3>
                    <!-- <p class="font15">{{data?.section_heading}}</p> -->

                    <h4 class="font18">{{ removeHtmlTags(data?.description) }}</h4>
                    <div class="flex">
                        <a class="font18 fw600 btn_theme" href="/destination/indian-himalayas"><span><img :src="'../assets/ladakhbikerental/images/arrow.png'" alt=""> </span> Learn More</a>
                        <!-- <span>or</span>
                        <a :href="`tel:${store.websiteSettings?.SALES_PHONE}`" class="telph"><SvgRenderer svgPath="../assets/svgs/telephone.svg"/> {{ store.websiteSettings?.SALES_PHONE }}</a> -->
                    </div>
                </div>
                <div class="dvs_right md:w-1/2 w-full">
                    <img class="rght_img1" :src="data.widgetSrc" alt="">
                    <!-- <img class="rght_img2" :src="data.widgetSrc2" alt=""> -->
                </div>
            </div>
        </div>
    </DiscoverTheWorldWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../store';
import SvgRenderer from './SvgRenderer.vue';
import { removeHtmlTags } from '../utils/commonFuntions';
import {DiscoverTheWorldWrapper} from '../styles/DiscoverTheWorldWrapper';

export default{
    name: 'DiscoverTheWorld',
    data() {
        return {
            store
        }
    },
    components: { 
        SvgRenderer,
        Link,
        DiscoverTheWorldWrapper
    },
    methods:{
        removeHtmlTags
    },
    props: ["data"]
}
</script>