<template>
    <TopMenuWrapper class="menu_list" v-if="! store.websiteSettings">
        <div class="w-1/5 mr-2 skeleton_loading py-6"></div>
        <div class="w-1/5 mr-2 skeleton_loading py-6"></div>
        <div class="w-1/5 mr-2 skeleton_loading py-6"></div>
        <div class="w-1/5 mr-2 skeleton_loading py-6"></div>
        <div class="w-1/5 mr-2 skeleton_loading py-6"></div>
        <div class="w-1/5 mr-2 skeleton_loading py-6"></div>
    </TopMenuWrapper>
    <TopMenuWrapper class="menu_list searchTop_menu" v-else>
        <li v-if="store.websiteSettings?.FLIGHT_URL"><Link :href="store.websiteSettings?.FLIGHT_URL" :class="getActiveClass('flight')"><i class="fa fa-plane"></i> Flight</Link></li>
        <li v-if="store.websiteSettings?.PACKAGE_URL"><Link :href="store.websiteSettings?.PACKAGE_URL" :class="getActiveClass('package')"><i class="fa fa-suitcase"></i> Holiday Packages</Link></li>
        <li v-if="store.websiteSettings?.ACTIVITY_URL"><Link :href="store.websiteSettings?.ACTIVITY_URL" :class="getActiveClass('activity')"><i class="fa-sharp fa-solid fa-person-hiking"></i> Activities</Link></li>
        <li v-if="store.websiteSettings?.HOTEL_URL"><Link :href="store.websiteSettings?.HOTEL_URL" :class="getActiveClass('hotel')"><i class="fa-solid fa-hotel"></i> Hotels</Link></li>
        <li v-if="store.websiteSettings?.CAB_URL"><Link :href="store.websiteSettings?.CAB_URL" :class="getActiveClass('cab')"><i class="fa fa-taxi"></i> Cab</Link></li>
        <li v-if="store.websiteSettings?.RENTAL_URL"><Link :href="store.websiteSettings?.RENTAL_URL" :class="getActiveClass('rental')"><i class="fa-solid fa-motorcycle"></i> Rental</Link></li> 
    </TopMenuWrapper>
</template> 

<script>
import {hidePopup} from '../utils/commonFuntions';
import { store } from '../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import styled from 'vue3-styled-components';

const TopMenuWrapper = styled.ul`
    display: flex;
    width: 75%;
    margin: auto;
    box-shadow: 1px 4px 8px #00000030;
    border-radius: 8px;
&.menu_list.searchTop_menu>li{
    flex-grow: 1;
    &>a {
        padding: 0.5rem 1rem 0.15rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 0.93rem;
        & i { font-size: 1.5rem; margin-bottom: 0.2rem; color: var(--theme-color); }
        &:hover {
            background-color: var(--theme-color30);
        }
    }
}
&.menu_list.searchTop_menu>li:first-child a{
    border-radius: 8px 0 0 8px;
}
`

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
        Link,
        TopMenuWrapper
    },
    props: ["className", "activeForm", "processing"],
}
</script>