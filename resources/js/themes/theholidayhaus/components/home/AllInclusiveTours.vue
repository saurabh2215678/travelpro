<template>
    <AllInclusiveToursWrapper>
        <img class="theme_bg2" src="../../assets/images/whytravel-bg.jpg" />
        <div class="container">
            <div class="relative">
            <div class="text-center pb-11">
            <h3 class="fw700 mb-3 font40 secondary-font">Why Travel with The Holiday Haus?</h3>
            <p class="text-white font18">Are not Able To Find The Right Tour That Satisfies Your Travel Needs? Get Expert Advice from The Holiday Haus</p>
            </div>
            <div class="grid gap-6 md:grid-cols-4 mt-3">
                <div class="flex items-center">
                    <div class="svg_box">
                    <img class="iconimg" src="../../assets/images/Awesome-Hikers.png" alt="">
                    </div>
                    <div class="box_content">
                        <h5 class="fw600 font39 text-white">2000+</h5>
                        <p class="font16 text-white">Awesome Hikers</p>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="svg_box">
                    <img class="iconimg" src="../../assets/images/Stunning-Places.png" alt="">
                    </div>
                    <div class="box_content">
                        <h5 class="fw600 font39 text-white">30+</h5>
                        <p class="font16 text-white">Stunning Places</p>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="svg_box">
                    <img class="iconimg" src="../../assets/images/Miles-Hike.png" alt="">
                    </div>
                    <div class="box_content">
                        <h5 class="fw600 font39 text-white">1000+</h5>
                        <p class="font16 text-white">Miles to Hike</p>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="svg_box">
                    <img class="iconimg" src="../../assets/images/days-service.png" alt="">
                    </div>
                    <div class="box_content">
                        <h5 class="fw600 font39 text-white">2500</h5>
                        <p class="font16 text-white">Days in Service</p>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </AllInclusiveToursWrapper>
</template>
<script>
import {AllInclusiveToursWrapper} from '../../styles/AllInclusiveToursWrapper';
import SvgRenderer from '../SvgRenderer.vue';
export default{
    name : 'AllInclusiveTours',
    components:{
    AllInclusiveToursWrapper,
    SvgRenderer
}
}
</script>