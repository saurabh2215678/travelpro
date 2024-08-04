<template>
    <ul class="menu_list" v-if="! store.websiteSettings">
        <div class="w-1/5 mr-2 skeleton_loading py-6"></div>
        <div class="w-1/5 mr-2 skeleton_loading py-6"></div>
        <div class="w-1/5 mr-2 skeleton_loading py-6"></div>
        <div class="w-1/5 mr-2 skeleton_loading py-6"></div>
        <div class="w-1/5 mr-2 skeleton_loading py-6"></div>
        <div class="w-1/5 mr-2 skeleton_loading py-6"></div>
    </ul>
    <ul class="menu_list searchTop_menu" v-else>
        <li v-if="store.websiteSettings?.FLIGHT_URL"><Link :href="store.websiteSettings?.FLIGHT_URL" :class="getActiveClass('flight')"><i class="fa fa-plane"></i> Flight</Link></li>
        <li v-if="store.websiteSettings?.PACKAGE_URL"><Link :href="store.websiteSettings?.PACKAGE_URL" :class="getActiveClass('package')"><i class="fa fa-suitcase"></i> Holiday Packages</Link></li>
        <li v-if="store.websiteSettings?.ACTIVITY_URL"><Link :href="store.websiteSettings?.ACTIVITY_URL" :class="getActiveClass('activity')"><i class="fa-sharp fa-solid fa-person-hiking"></i> Activities</Link></li>
        <li v-if="store.websiteSettings?.HOTEL_URL"><Link :href="store.websiteSettings?.HOTEL_URL" :class="getActiveClass('hotel')"><i class="fa-solid fa-hotel"></i> Hotels</Link></li>
        <li v-if="store.websiteSettings?.CAB_URL"><Link :href="store.websiteSettings?.CAB_URL" :class="getActiveClass('cab')"><i class="fa fa-taxi"></i> Cab</Link></li>
        <li v-if="store.websiteSettings?.RENTAL_URL"><Link :href="store.websiteSettings?.RENTAL_URL" :class="getActiveClass('rental')"><i class="fa-solid fa-motorcycle"></i> Rental</Link></li> 
    </ul>
</template> 

<script>
import {hidePopup} from '../utils/commonFuntions';
import { store } from '../store.js';
import { Link } from "@inertiajs/inertia-vue3";

export default {
    name:"FormTopMenu",
    created() {
        // console.log('store');
    },
    data() {
        return {
            store,
        }
    },
    methods:{
        closeClick(){
            hidePopup();
        },
        getActiveClass(slug){
            var active = '';
            if(slug == this.activeForm) {
                active = 'active';
            }
            return active;
        }
    },
    components:{
        Link
    },
    props: ["className", "activeForm", "processing"],
}
</script>