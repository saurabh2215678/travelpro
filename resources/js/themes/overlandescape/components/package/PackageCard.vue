<template>
    <li class="swiper-slide" v-if="packageData">
        <div class="featured_box">
            <Link class="featured_content" :href="packageData.url">
                <div class="images">
                    <img :src="packageData.thumbSrc" :alt="packageData.title" />
                    <div class="pack_day_night" v-if="packageData.is_activity == 0 && (packageData.days || packageData.nights)">
                        <span class="" v-if="packageData.nights">{{packageData.nights}}N</span>
                        <span class="" v-if="packageData.days">{{packageData.days}}D</span>
                    </div>
                </div>
            </Link>
            <Link class="featured_content px-1.5 py-3.5" :href="packageData.url">
                <div class="title text-sm">{{packageData.title}}</div>
                <div class="price" v-if="packageData.search_price">
                    <p class="text-xs "> Starting From : 
                        <div v-if="packageData.publish_price && parseFloat(packageData.publish_price) > parseFloat(packageData.search_price)" class="cut-price text-lg leading-normal"><span class="text-slate-500 relative">{{showPrice(packageData.publish_price,true)}}</span></div>

                        <span class="amount heading_sm3">{{showPrice(packageData.search_price,true)}}</span>
                    </p>
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
         //console.log('PackageCard.package',this.packageData);
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
    props: ["packageData"],
}
</script>