<template>
    <AllInclusiveToursWrapper>
        <div class="container">
            <h3 class="text-center font30 color_dark fw600 mb-8">Why Ride with Ladakh Bike Rental</h3>
            <div class="grid md:gap-4 gap-10 md:grid-cols-5 md:w-10/12 m-auto grid-cols-2">
                <div class="why_ride_sec">
                    <div class="why_icon">
                        <img :src="'../assets/ladakhbikerental/images/update_well.png'" />
                    </div>
                    <div class="box_content">
                        <h5 class="fw600 font17 color_dark">Updated & Well Maintained Motor Bikes</h5>
                    </div>
                </div>

                <div class="why_ride_sec">
                    <div class="why_icon">
                        <img :src="'../assets/ladakhbikerental/images/expirenced.png'" />
                    </div>
                    <div class="box_content">
                        <h5 class="fw600">Experienced<br> & Helpful Staffs</h5>
                        <!-- <p class="font14">Eat to your heart's content Breakfast.<br>Lunch. Dinner.</p> -->
                    </div>
                </div>

                <div class="why_ride_sec">
                    <div class="why_icon">
                        <img :src="'../assets/ladakhbikerental/images/good_value.png'" />
                    </div>
                    <div class="box_content">
                        <h5 class="fw600">Good Value <br>for Your Money</h5>
                        <!-- <p class="font14">
                            Our itineraries include all rail, sea and<br>
                            road transport as part of the itinerary so<br>
                            you can enjoy tension free
                        </p> -->
                    </div>
                </div>

                <div class="why_ride_sec">
                    <div class="why_icon">
                        <img :src="'../assets/ladakhbikerental/images/unique.png'" />
                    </div>
                    <div class="box_content">
                        <h5 class="fw600">Unique & <br> Scenic Routes</h5>
                        <!-- <p class="font14">
                            We have an exclusive team of 350 tour<br>
                            managers specialising in India and<br>
                            World tours
                        </p> -->
                    </div>
                </div>

                <div class="why_ride_sec">
                    <div class="why_icon">
                        <img :src="'../assets/ladakhbikerental/images/flexible.png'" />
                    </div>
                    <div class="box_content">
                        <h5 class="fw600">Flexible & <br>Responsible</h5>
                        <!-- <p class="font14">
                            Our dedicated product & destination<br>
                            research team spends hours curating<br>
                            the best value for money itineraries
                        </p> -->
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