<template>
    <HotelSliderCardWrapper class="HotelSliderCard">

        <Link v-if="data?.url && data?.thumbSrc" :href="data.url" class="package_image_box">
            <img :src="data?.thumbSrc" :alt="data?.name">
        </Link>
        <div class="package_info">
            <Link v-if="data?.url && data?.name" :href="data.url">
                <h3 class="package_title">{{ data?.name }}</h3>
            </Link>
            <p class="para_md inline-block" v-if="data?.place_name">
                <img src="../../assets/images/map_hotel.png" alt="Map Hotel">
                {{data?.place_name}}
            </p>
            <!-- <div class="main-price text-sm py-1 text-teal-600 font-semibold" v-if="data.search_price && parseFloat(data.search_price) > 0">{{showPrice(data.search_price,true)}}</div> -->
            <div class="rating_box" >
                <StarRating
                    v-model:rating="data.star_classification" 
                    :increment="0.5"
                    active-color="#ffb100"
                    border-color="#ffb100"
                    :rounded-corners="true"
                    read-only 
                />
                <span class="counter">{{ data.star_classification }} Ratings</span>
            </div>
            <div class="bottom_options">
                <Link v-if="data?.url" :href="data.url" class="book_btn">Book Now</Link>
                <span v-if="data.search_price && parseFloat(data.search_price) > 0" class="package_price"><span>From</span>{{showPrice(data.search_price,true)}}</span>
            </div>
        </div>

    </HotelSliderCardWrapper>
</template>
<script>
import {HotelSliderCardWrapper} from '../../styles/HotelSliderCardWrapper'
import StarRating from 'vue-star-rating'
import { Link } from "@inertiajs/inertia-vue3";
import {showPrice} from '../../utils/commonFuntions';

export default{
    name:'HotelSliderCard',
    created() {
        // console.log('htldata>', this.data);
    },
    methods:{
        showPrice
    },
    components:{
        HotelSliderCardWrapper,
        Link,
        StarRating
    }, 
    props:['data']
}
</script>