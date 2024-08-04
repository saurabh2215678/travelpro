<template>
    <TheHolidayHausWrapper>
        <div class="container">
            <div class="dvs_box">
                <div class="dvs_left text-center">
                    <img class="rght_img1" :src="data?.widgetSrc" alt="">
                    <p class="font23 fw500 theme-color-dark">{{data?.section_heading}}</p>
                    <h3 class="font55 fw600 secondary-font theme-color-dark" v-html="data?.widget_name"></h3>
                    <div class="font21 color_dark700 pb-5">{{ removeHtmlTags(data?.description) }}</div>
                    <a class="btn btn_theme" href="/content/about-us">Read More</a> 
                </div>
            </div>
        </div>
    </TheHolidayHausWrapper>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../../store';
import SvgRenderer from '../SvgRenderer.vue';
import { removeHtmlTags } from '../../utils/commonFuntions';
import {TheHolidayHausWrapper} from '../../styles/TheHolidayHausWrapper';

export default{
    name: 'TheHolidayHaus',
    data() {
        return {
            store
        }
    },
    components: { 
        SvgRenderer,
        Link,
        TheHolidayHausWrapper
    },
    methods:{
        removeHtmlTags
    },
    props: ["data"]
}
</script>../styles/TheHolidayHausWrapper