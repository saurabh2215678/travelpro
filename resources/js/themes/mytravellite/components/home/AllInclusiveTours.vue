<template>
    <AllInclusiveToursWrapper>
        <div class="container">
            <h3 class="text-center fw600 mb-8">All inclusive tours,<br>Pack Your Bags, Adventure Awaits!</h3>
            <div class="grid gap-6 md:grid-cols-3">
                <div class="flex">
                    <div class="svg_box">
                        <SvgRenderer svgPath="../assets/svgs/accommodation.svg"/>
                    </div>
                    <div class="box_content">
                        <h5 class="fw600">Accommodation</h5>
                        <p class="font14">Comfortable & convenient hotels cherry<br> picked by our hotel management team</p>
                    </div>
                </div>

                <div class="flex">
                    <div class="svg_box">
                        <SvgRenderer svgPath="../assets/svgs/all-meals.svg"/>
                    </div>
                    <div class="box_content">
                        <h5 class="fw600">All meals</h5>
                        <p class="font14">Eat to your heart's content Breakfast.<br>Lunch. Dinner.</p>
                    </div>
                </div>

                <div class="flex">
                    <div class="svg_box">
                        <SvgRenderer svgPath="../assets/svgs/on-tour-transport.svg"/>
                    </div>
                    <div class="box_content">
                        <h5 class="fw600">On-tour transport</h5>
                        <p class="font14">
                            Our itineraries include all rail, sea and<br>
                            road transport as part of the itinerary so<br>
                            you can enjoy tension free
                        </p>
                    </div>
                </div>

                <div class="flex">
                    <div class="svg_box">
                        <SvgRenderer svgPath="../assets/svgs/tour-managers.svg"/>
                    </div>
                    <div class="box_content">
                        <h5 class="fw600">Tour managers</h5>
                        <p class="font14">
                            We have an exclusive team of 350 tour<br>
                            managers specialising in India and<br>
                            World tours
                        </p>
                    </div>
                </div>

                <div class="flex">
                    <div class="svg_box">
                        <SvgRenderer svgPath="../assets/svgs/best-value-itinerary.svg"/>
                    </div>
                    <div class="box_content">
                        <h5 class="fw600">Best value itinerary</h5>
                        <p class="font14">
                            Our dedicated product & destination<br>
                            research team spends hours curating<br>
                            the best value for money itineraries
                        </p>
                    </div>
                </div>
               
                <div class="flex">
                    <div class="svg_box">
                        <SvgRenderer svgPath="../assets/svgs/to-and-fro-airfare.svg"/>
                    </div>
                    <div class="box_content">
                        <h5 class="fw600">To and fro airfare</h5>
                        <p class="font14">
                            My Travel Lite are inclusive of airfare<br>
                            from many hubs within India unless you<br>
                            pick the joining-leaving option
                        </p>
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