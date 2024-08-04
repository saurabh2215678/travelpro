<template>
    <li class="swiper-slide" v-if="CabRouteData">
        <div class="featured_box">
            <a class="featured_content" :href="CabRouteData.url">
                <div class="images">
                    <img :src="CabRouteData.image_url" :alt="CabRouteData.name" />
                    <div class="pack_day_night" v-if="CabRouteData.isActivity == 0 && (CabRouteData.days || CabRouteData.nights)">
                        <span class="" v-if="CabRouteData.nights">{{CabRouteData.nights}}N</span>
                        <span class="" v-if="CabRouteData.days">{{CabRouteData.days}}D</span>
                    </div>
                </div>
            </a>
            <div class="shapeType3 text-xs" v-if="CabRouteData.sharing == 1"><span>Shared Cab</span></div>
            <div class="shapeType1 text-xs" v-if="CabRouteData.duration">{{CabRouteData.duration}}</div>
            <div class="sightseen-list-content">
            <span class="featured_content cursor-pointer pt-2 w-full" @click="handlePopup">
                <div class="title text-sm"><!--<img src="../../assets/images/cab-sharing.png" style="width: 1.2rem;float: left;margin-right: 0.5rem; margin-top: 0.2rem;" v-if="CabRouteData.sharing == 1">-->{{CabRouteData.name}}</div>
                <div class="price price-list-view" v-if="CabRouteData.search_price">
                    <p class="text-xs bg-slate-100 items-center py-1 px-2">
                        Starting From :
                        <div v-if="CabRouteData.publish_price && parseFloat(CabRouteData.publish_price) > parseFloat(CabRouteData.search_price)" class="cut-price text-lg leading-normal"><span class="text-slate-500 relative">{{showPrice(CabRouteData.publish_price,true)}}</span></div>
                        <span class="amount heading_sm3">{{showPrice(CabRouteData.search_price,true)}}</span>
                    </p>
                    <small></small>
                </div>
            </span>

            

            <div class="sightseen-disc">
                <p class="leading-5 text-xs"> {{CabRouteData.places}}</p>
            </div>
            <div class="sightseen-bookbtn">
                <a :href="CabRouteData.book_url">Book Now</a>
            </div>
            </div>
        </div>


        <div class="sightseeng" ref="popRef">
            <div class="popu_head">
               
                <div class="sightseen_content_title text-lg font-semibold text-white">
                    {{CabRouteData.name}}
                </div>
            </div>
          <div class="popup_content">
                <div class="seeing_item">
                    <!-- <div class="sub-title pb-2 text-lg font-semibold"> {{CabRouteData.name}} 
                        <span v-if="CabRouteData.distance">
                        ( {{CabRouteData.distance}} kms | {{CabRouteData.duration}} approx.)
                    </span></div> -->
                    <p class="text-sm leading-6" v-html="CabRouteData.description">
                        
                    </p>
                </div>                 
          </div>
        </div>
    </li>

</template>


<script>
import {showPrice, showPopup, hidePopup} from '../../../utils/commonFuntions.js';
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
    methods : {
        showPrice,
        handlePopup(){
            store.popupContent = `${this.$refs.popRef.innerHTML}`;
            store.popupType = "innerHtml";
            showPopup();
        },
         closeClick(){
            hidePopup();
        },
    },
    components:{
    },
    props: ["CabRouteData"],
}
</script>