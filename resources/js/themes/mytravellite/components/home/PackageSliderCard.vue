<template>
    <PackageSliderCardWrapper class="PackageSliderCard">
        <Link v-if="data?.url && data?.thumbSrc" :href="data.url" class="package_image_box">
           <!--  <div class="tour_data">26 Tours</div> -->
            <img :src="data?.thumbSrc" :alt="data?.title">
        </Link>
        <div class="package_info">
            <Link v-if="data?.url && data?.title" :href="data.url">
                <h3 class="package_title">{{ data?.title }}</h3>
            </Link>

            <div class="pack_day_night" v-if="data?.days && data?.nights">
                <span>{{ data.nights }} Nights -</span> <span>{{ data.days }} Days</span>
            </div>
           
            <div class="bottom_options">
                <span v-if="data?.publish_price" class="package_price"><span>From</span> {{ showPrice(data.publish_price,true) }}</span>
                <Link v-if="data?.url" :href="data.url" class="book_btn">Know more</Link>
            </div>
        </div>
    </PackageSliderCardWrapper>
</template>
<script>
import {PackageSliderCardWrapper} from '../../styles/PackageSliderCardWrapper'
import StarRating from 'vue-star-rating'
import {showPrice} from '../../utils/commonFuntions';
import { Link } from "@inertiajs/inertia-vue3";

export default{
    name:'PackageSliderCard',
    created() {
         console.log('ppckdata>', this.data);
    },
    methods: {
        showPrice,
    },
    components:{
        PackageSliderCardWrapper,
        Link,
        StarRating
    }, 
    props:['data', 'hiderating']
}
</script>