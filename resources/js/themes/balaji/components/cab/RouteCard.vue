<template>
       <tr>
            <td>
                <div class="name_content_box">
                  <img v-if="store.websiteSettings?.CAB_IMAGE_PATH" :src="`${store.websiteSettings?.CAB_IMAGE_PATH+this.info.image}`" alt="image">
                    <img src="../../assets/images/car1.jpg" v-else alt="">
                    <div class="name_content">
                        <h3 class="font20 fw600">
                            {{this.info.name}}
                        </h3>
                        <p class="font13 mb-0">
                            or equivalent
                            <Popper hover arrow placement="right"  v-if="this.info.equivalent">
                                <img class="tooltip_icon" src="../../assets/images/Information-icon_new.png"/>
                                <template #content>
                                    <div class="tooltip_details">
                                        <p class="font12">{{ this.info.equivalent }}</p>
                                    </div>
                                </template>
                            </Popper>
                        </p>
                    </div>
                </div>
            </td>
            <td>
                <div class="prc_info">
                    <i class="fa-regular fa-newspaper"></i>
                    <p class="font12">Includes Toll, State Tax & GST</p>
                </div>
            </td>
            <td>
                <div class="prc_info">
                    <i class="fa-solid fa-award"></i>
                    <p class="font12">Top Rated Cabs & Drivers</p>
                </div>
            </td>
            <!-- <td style="vertical-align: middle;">
                <div class="discount_txt">
                    <h4>₹ 56696</h4>
                    <p class="font14">Save ₹5154</p>
                </div>
            </td> -->
            <td class="price_bg" style="width: 10rem;">
                <h4 class="font22 color_light fw400">₹{{this.info.price}}</h4>
                <!-- <p class="font12">up to 1717 km</p> -->
            </td>
            <td>
                <button class="btn" @click="handleBook">Select</button>
            </td>
        </tr>
</template>
<script>
import {DateFormat, timeConvert, getLogo, showPrice, showErrorToast} from '../../utils/commonFuntions.js';
import { Link } from "@inertiajs/inertia-vue3";
import { store } from '../../store';
import axios from "axios";
import Popper from "vue3-popper";
export default {
    name: "CabCard",
    created() {
        // console.log(this.info);
    },
    data() {
        return {
            showErrorToast,
            info: this.info,
            store,
            buttonLoading: false,
        }
    },
    methods: {
        DateFormat,
        timeConvert,
        getLogo,
        showPrice,
        handleBook() {
        if(this.info.id) {
            store.bookingPassedStep= 0;
            store.bookingCurrentStep = 1;
            store.loadingName = 'iternity'
            this.$inertia.get(`/cab/book/${this.info.cab_route_id}?tripType=${this.tripType}&cab=${this.info.id}&dep=${this.routeInfos[0]['travelDate']}&time=${this.routeInfos[0]['travelTime']}`);
        } else {
            alert('Please select price to Book');
        }
    },
    },
    props: ["info", "id", "routeInfos", "paxInfo", "priceIdName", "totalFlights", "routeIndex", "tripType"],
    components: {
        Link,
        Popper
    }
}
</script>