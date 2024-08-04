<template>
    <RecognizedSection>
        <div class="container">
            <div class="flex flex-wrap partner_sec">
                <div class="md:w-1/2">
                    <h3 class="text-center font17 grey_heading fw600 mb-8">Recognized by:</h3>
                    <ul class="flex flrex-wrap justify-center prtner_flex">
                        <li>
                            <img :src="'../assets/ladakhbikerental/images/recognized_one.jpg'" alt="">
                        </li>
                        <li>
                            <img :src="'../assets/ladakhbikerental/images/recognized_two.jpg'" alt="">
                        </li>
                        <li>
                            <img :src="'../assets/ladakhbikerental/images/recognized_three.jpg'" alt="">
                        </li>
                    </ul>
                </div>
                <div class="md:w-1/2 md-mt-0 md:mt-0 mt-16">
                    <h3 class="text-center font17 grey_heading fw600 mb-8">Work with :</h3>
                    <ul class="flex flrex-wrap justify-center prtner_flex">
                        <li>
                            <img :src="'../assets/ladakhbikerental/images/work_wth.jpg'" alt="">
                        </li>
                        <li>
                            <img :src="'../assets/ladakhbikerental/images/work_wth_three.jpg'" alt="">
                        </li>
                        <li>
                            <img :src="'../assets/ladakhbikerental/images/work_wth_two.jpg'" alt="">
                        </li>
                        <li>
                            <img :src="'../assets/ladakhbikerental/images/work_wth_four.jpg'" alt="">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </RecognizedSection>
</template>
<script>
import {RecognizedSection} from '../../styles/RecognizedSection';
import SvgRenderer from '../SvgRenderer.vue';
export default{
    name : 'AllInclusiveTours',
    components:{
    RecognizedSection,
    SvgRenderer
}
}
</script>