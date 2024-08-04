<template>
    <div class="onewayloader">
        <div class="flight_res_sec">
            <div class="xl:container xl:mx-auto container mx-auto">
                <div class="flight_res_wrap">
                    <div class="flight_res_left">
                        <!-- <div class="w-full p-4 mb-3 skeleton_loading"></div>
                        <div class="w-full p-4 mb-3 skeleton_loading"></div>
                        <div class="w-full p-4 mb-3 skeleton_loading"></div>
                        <div class="w-full p-4 mb-3 skeleton_loading"></div>
                        <div class="w-full p-4 mb-3 skeleton_loading"></div>
                        <div class="w-full p-4 mb-3 skeleton_loading"></div>
                        <div class="w-full p-4 mb-3 skeleton_loading"></div> -->
                        <div class="price_silder">
                            <h3 class="font14 fw600">Price</h3>
                            <div class="static_range_slider">
                                <span class="static_range_left_item"><i>0</i></span>
                                <span class="static_range_progress"></span>
                                <span class="static_range_right_item"><i>100000</i></span>
                            </div>
                        </div>
                        <div class="side_loading">
                            <h3 class="font14 fw600 pb-1">Stops</h3>
                        </div>
                        <div class="side_loading">
                            <h3 class="font14 fw600 pb-1">Departure Time</h3>
                        </div>
                        <div class="side_loading">
                            <h3 class="font14 fw600 pb-1">Arrival Time</h3>
                        </div>
                        <div class="side_loading">
                            <h3 class="font14 fw600 pb-1">Airlines</h3>
                        </div>
                        
                    </div>
                    <div class="flight_res_right">
                        <div class="w-full p-16 mb-3 skeleton_loading"></div>
                        <div class="w-full p-16 mb-3 skeleton_loading"></div>
                        <div class="w-full p-16 mb-3 skeleton_loading"></div>
                        <div class="w-full p-16 mb-3 skeleton_loading"></div>
                        <div class="w-full p-16 mb-3 skeleton_loading"></div>
                        <div class="w-full p-16 mb-3 skeleton_loading"></div>
                        <div class="w-full p-16 mb-3 skeleton_loading"></div>
                        <div class="w-full p-16 mb-3 skeleton_loading"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:"OneWayPageLoader",
    data(){
        return{
            
        }
    },
    components:{
    },
    props:[]
}
</script>