<template>
    <PackageSliderCardWrapper class="PackageSliderCard">
        <Link v-if="data?.url && data?.thumbSrc" :href="data.url" class="package_image_box">
            <img :src="data?.thumbSrc" :alt="data?.title">
        </Link>
        <div class="package_info">
            <div class="pack_day_night" v-if="data?.days && data?.nights">
                <span>{{ data.nights }} Nights -</span> <span>{{ data.days }} Days</span>
            </div>
            <Link v-if="data?.url && data?.title" :href="data.url">
                <h3 class="package_title">{{ data?.title }}</h3>
            </Link>
            <!-- <div class="rating_box" >
                <StarRating 
                    v-if="!hiderating"
                    :rating="4.5" 
                    :increment="0.5"
                    active-color="#ffb100"
                    border-color="#ffb100"
                    inactive-color="#ffffff"
                    :rounded-corners="true"
                    read-only 
                />
                <span class="counter" v-if="!hiderating">138 Ratings</span>
            </div> -->
            <div class="bottom_options">
                <Link v-if="data?.url" :href="data.url" class="book_btn">Book Now</Link>
                <span v-if="data?.search_price" class="package_price"><span>From</span> ₹{{ data.search_price }}</span>
            </div>
        </div>
    </PackageSliderCardWrapper>
</template>
<script>
import {PackageSliderCardWrapper} from '../../styles/PackageSliderCardWrapper'
import StarRating from 'vue-star-rating'
import { Link } from "@inertiajs/inertia-vue3";

export default{
    name:'PackageSliderCard',
    created() {
        console.log('ppckdata>', this.data);
    },
    components:{
        PackageSliderCardWrapper,
        Link,
        StarRating
    }, 
    props:['data', 'hiderating']
}
</script>