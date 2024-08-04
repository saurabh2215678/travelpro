<template>
    <ScubaDivingSectionWrapper>
        <div class="container">
            <div class="excontent">
                <div class="heading2"><strong>Explore the unexplored</strong>Andaman Islands</div>
                <p>When in the Andamans, happiness is closer than you think! Famous for its friendly people and heavenly tropical islands, the most gorgeous union territory of India is the quintessential Southeast Asian paradise.</p>

                <!-- <Link href="javascriptvaoi:(0)" class="readbtn">Read More</Link> -->
            </div>


            <div class="expimg">
                <FancyboxWrapper>
                    <a data-fancybox="" href="https://www.youtube.com/watch?v=8l9q6lq-o_Y" class="fancybox">
                        <img src="../../assets/images/video_bg.png" alt="img">
                        <div class="play-btn">
                            <i class="fa-solid fa-play"></i>
                        </div>
                    </a>
                </FancyboxWrapper>
            </div>
        </div>
    </ScubaDivingSectionWrapper>
</template>
<script>
import {ScubaDivingSectionWrapper} from '../../styles/ScubaDivingSectionWrapper';
import FancyboxWrapper from '../FancyboxWrapper.vue';
import { Link } from "@inertiajs/inertia-vue3";
export default {
    name : 'ScubaDivingSection',
    components:{
        ScubaDivingSectionWrapper,
        FancyboxWrapper,
        Link
    }

}
</script>