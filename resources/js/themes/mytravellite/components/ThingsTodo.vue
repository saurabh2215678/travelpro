<template>
<ThingsWrapper>
    <div class="container">
        <div class="tings_box">
            <div class="left_img">
                <img :src="data.widgetSrc" alt="">
            </div>
            <div class="right_txt" v-if="this.data">
                <h2 class="fw400">{{data?.widget_name}}</h2>
                <h3 class="font54 fw700">{{data?.section_heading}}</h3>
    
                <p class="font18">{{ removeHtmlTags(data?.description) }}</p>
                <a href="/destination/thailand">For More Info</a>
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