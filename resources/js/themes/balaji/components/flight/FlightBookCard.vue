<template>
    <table v-if="this.info.sI">
        <tr>
            <td>
                <div class="mnt_wrap">
                    <div class="flight_detail">
                        <img v-if="this.firstInfo.fD.aI.code" :src="getLogo(this.firstInfo.fD.aI.code)" v-bind:alt="this.firstInfo.fD.aI.name">
                        <div class="fl-t">
                            <p v-if="this.firstInfo.fD.aI.name">{{this.firstInfo.fD.aI.name}}</p>
                            <span>{{getFlightCodes(info)}}</span>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div class="assd_content departure_content">
                    <h4 v-if="this.firstInfo.da.code">{{this.firstInfo.da.code}}</h4>
                    <p>{{DateFormat(this.firstInfo.dt,'HH:mm')}}</p>
                    <p>{{DateFormat(this.firstInfo.dt,'MMM D')}}</p>
                </div>
            </td>
            <td>
                <div class="mmd_sec">
                    <p>{{getFlightStop(info)}}</p>
                    <span class="mmd_arrow"></span>
                    <p>{{timeConvert(getTotalDuration(info.sI))}}</p>
                </div>
            </td>
            <td>
                <div class="assd_content arrival_content">
                    <h4 v-if="this.lastInfo.aa.code">{{this.lastInfo.aa.code}}</h4>
                    <p>{{DateFormat(this.lastInfo.at,'HH:mm')}}</p>
                    <p>{{DateFormat(this.lastInfo.at,'MMM D')??''}}</p>
                </div>
            </td>
            <td>
                <div class="price_view">
                    <p>{{showPrice(getInfoTotalPrice(info, this.id, this.paxInfo))}}</p>
                </div>
            </td>
        </tr>
    </table>
</template>
<script>
import {DateFormat, timeConvert, getLogo, showPrice, getInfoTotalPrice, getTotalDuration} from '../../utils/commonFuntions.js';
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
export default {
    name:"FligtBookBottom",
    created() {
        // console.log('FlightDetails.FligtBookBottom',this.info);
    },
    data() {
        return {
            store,
            info: this.info,
            firstInfo: this.info.sI[0],
            lastInfo: this.info.sI[this.info.sI.length - 1],
        }
    },
    methods:{
        DateFormat,
        timeConvert,
        getLogo,
        showPrice,
        getInfoTotalPrice,
        getTotalDuration,
        getFlightCodes: function(info) { 
            //console.log("ANMOL SI length=", info.sI.length)
            let arr = [];
            let flightCodes = "";
            info.sI.forEach((value, index) => {
            let flightCode = value.fD.aI.code+'-'+value.fD.fN;  
                arr.push(flightCode);
            });
            flightCodes = arr.join(', ');
            return flightCodes;
        },
        getFlightStop : function(info){
            let stop = info.sI.length - 1;
            if(stop == 0) {
                return 'Non-Stop'
            } else {
                return stop+'-Stop(s)';
            }
        },
    },
    components:{
        Link,
    },
    props: ["info","id","paxInfo"],
}
</script>