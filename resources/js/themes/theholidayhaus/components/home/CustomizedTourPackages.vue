<template>
<CustomizedTourWrapper>
    <!-- <img src="../assets/images/video-img.jpg" alt="img"> -->
    <img class="trending_theme_bg" src="../../assets/images/static-banner.jpg" />
    <div class="container">
        <div class="tings_box">
            <!-- <div class="left_img">
                <img :src="data.widgetSrc" alt="">
            </div> -->
            <div class="right_txt w-1/2" v-if="this.data">
                <h3 class="font40 fw600 secondary-font pb-5">{{data?.section_heading}}</h3>
                <p class="font18 pb-3">{{ removeHtmlTags(data?.about_widget_desc) }} </p>
                <div class="font27 mt-7">{{ removeHtmlTags(data?.description) }} </div>
                <a class="btn btn_theme" :href="this.viewAllUrl">Plan Your Trip Now</a> 
            </div>
        </div>
    </div>
</CustomizedTourWrapper>
</template>

<script>
import { CustomizedTourWrapper } from '../../styles/CustomizedTourWrapper';
import { removeHtmlTags } from '../../utils/commonFuntions';
export default{
    name: 'CustomizedTourPackages',
    
    methods:{
        removeHtmlTags
    },
    components:{
        CustomizedTourWrapper
    },
    props: ["data","viewAllUrl"]
} 
</script>