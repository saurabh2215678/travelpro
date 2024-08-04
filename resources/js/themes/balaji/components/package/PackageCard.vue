<template>
    <li class="swiper-slide" v-if="packageData">
        <div class="featured_box">
            <Link class="featured_content" :href="packageData.url">
                <div class="images">
                    <img :src="packageData.thumbSrc" :alt="packageData.title" />
                    <div class="pack_day_night" v-if="packageData.isActivity == 0 && (packageData.days || packageData.nights)">
                        <span class="" v-if="packageData.nights">{{packageData.nights}}N</span>
                        <span class="" v-if="packageData.days">{{packageData.days}}D</span>
                    </div>
                </div>
            </Link>
            <Link class="pack_content" :href="packageData.url">
                <div class="days_flex">
                    <div>{{packageData.days}} Days</div> /
                    <div>{{packageData.nights}} Nights</div>
                </div>
                <div class="pack_title">{{packageData.title}}</div>
                <div class="price" v-if="packageData.search_price && packageData.search_price > 0">
                    <p class="text-xs "> Starting From : <span class="amount heading_sm3">{{showPrice(packageData.search_price,true)}}
                            /-</span></p>
                    <small></small>
                </div>
            </Link>
        </div>
    </li>
</template>
<script>
import {showPrice} from '../../utils/commonFuntions';
import { store } from '../../store';
import { Link } from "@inertiajs/inertia-vue3";
export default {
    name:"PackageCard",
    created() {
        // console.log('packageDataAAAAAAAAAA',this.packageData);
    },
    data() {
        return {
            store,
        }
    },
    methods:{        
        showPrice,
    },
    components:{
        Link,
    },
    props: ["packageData", "isContent"],
}
</script>