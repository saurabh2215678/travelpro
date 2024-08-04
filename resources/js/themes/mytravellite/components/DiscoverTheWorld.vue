<template>
    <DiscoverTheWorldWrapper>
        <div class="container">
            <div class="dvs_box">
                <div class="dvs_left">
                    <h3 class="font26 fw600" v-html="data?.widget_name"></h3>
                    <p class="font15">{{data?.section_heading}}</p>

                    <h4 class="font16 color_dark700 fw700">{{ removeHtmlTags(data?.description) }}</h4>
                    <div class="flex btm_itemn">
                        <Link href="/contact-us" class="btn btn_theme">Enquire Now</Link>
                        <span>or</span>
                        <a :href="`tel:${store.websiteSettings?.SALES_PHONE}`" class="telph"><SvgRenderer svgPath="../assets/svgs/telephone.svg"/> {{ store.websiteSettings?.SALES_PHONE }}</a>
                    </div>
                </div>
                <div class="dvs_right">
                    <img class="rght_img1" :src="data?.widgetSrc" alt="">
                    <img class="rght_img2" :src="data?.widgetSrc2" alt="">
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