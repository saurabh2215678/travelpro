<template>
    <li class="swiper-slide" v-if="packageData">
        <div class="featured_box">
            <a class="featured_content "
                :href="packageData.url">
                <div class="images">
                    <img :src="packageData.thumbSrc" :alt="packageData.title" />
                    <div class="pack_day_night" v-if="packageData.isActivity == 0 && (packageData.days || packageData.nights)">
                        <span class="" v-if="packageData.nights">{{packageData.nights}}N</span>
                        <span class="" v-if="packageData.days">{{packageData.days}}D</span>
                    </div>
                </div>
            </a>
            <a class="featured_content px-1.5 py-3.5" :href="packageData.url">
                <div class="title text-sm">{{packageData.title}}</div>
                <div class="price" v-if="packageData.price">
                    <p class="text-xs "> Starting From : <span class="amount heading_sm3">{{showPrice(packageData.price)}}
                            /-</span></p>
                    <small></small>
                </div>
            </a>
        </div>
    </li>
</template>
<script>
import {showPrice} from '../../../utils/commonFuntions.js';
import { store } from '../../../store.js';
export default {
    name:"PackageCard",
    created() {
        // console.log('PackageCard.package',this.package);
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
        
    },
    props: ["packageData"],
}
</script>