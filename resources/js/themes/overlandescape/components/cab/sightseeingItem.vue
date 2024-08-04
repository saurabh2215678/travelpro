<template>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td><img class="img-ss rounded-full h-20 w-20" :src="row.image_url" alt=""/></td>
                    <td>
                        <h3 class="text-blue-800 text-sm font-semibold">
                            <!-- <a class="flex" href="javascript:void(0)" @click="handlePopup"> -->
                            <a class="flex" :href="row.book_url">{{row.name}}</a>
                        </h3>
                        <p class="text-xs pt-1 max-w-md">{{row.places}}</p>
                    </td>
                    <td align="center" class="text-sm">
                    <span v-if="row.duration"><i class="fa-solid fa-calendar-days text-teal-500" ></i><br>{{row.duration}}</span> 
                    </td>
                    <td align="center" class="text-sm">
                        <i class="fas fa-tachometer-alt text-teal-500" v-if="row.distance"></i><br> 
                        <span v-if="row.distance">{{row.distance}} km</span>
                    </td>
                    <td class="price-list-view">
                        <h4 class="text-sm p-1">Starting From :</h4>
                        <div v-if="row.publish_price && row.search_price && parseFloat(row.publish_price) > parseFloat(row.search_price)" class="cut-price text-lg leading-normal"><span class="text-slate-500 relative">{{showPrice(row.publish_price,true)}}</span></div>
                        <div class="ss_price text-blue-800 text-lg font-semibold">{{showPrice(row.search_price,true)}}</div>
                        <div class="shapeType2 text-lg bg-amber-600 inline-block text-white p-0.5 rounded-sm" v-if="row.sharing == 1" >
                            Shared Cab
                        </div>
                    </td>
                    <td><a :href="row.book_url"><button class="bookbtn rounded-full px-5 py-2"> Show Vehicle Type</button></a> </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="sightseeng" ref="popRef">
        <div class="popu_head">
            
            <div class="sightseen_content_title text-lg font-semibold text-white">
                {{row.name}}
            </div>
        </div>
        <div class="popup_content">

            <div class="seeing_item">
                <!-- <div class="sub-title pb-2 text-lg font-semibold"> {{row.name}} 
                    <span v-if="row.distance">
                    ( {{row.distance}} kms | {{row.duration}} approx.)
                </span></div> -->
                <p class="text-sm leading-6" v-html="row.description">
                    
                </p>
            </div>                 
        </div>
    </div>
</template>

<script>
import {showPopup, hidePopup, showPrice} from '../../utils/commonFuntions.js';
import { store } from '../../store.js';

export default {
    mounted() {
        // console.log(this.row);
    },
    data() {
        store
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
    props:['row']
}

</script>