<template>
    <BookingTabWrapper>
        <div class="tab_container">
            <div class="excontent">
                <div class="heading2">My Booking</div>
            </div>
            <div class="tab_btns">
                <button :class="activetab == 1 ? 'active':''" @click="handleActive(1)">All</button>
                <button :class="activetab == 2 ? 'active': ''" @click="handleActive(2)">Flight</button>
                <button :class="activetab == 3 ? 'active': ''" @click="handleActive(3)">Cab</button>
                <button :class="activetab == 4 ? 'active': ''" @click="handleActive(4)">Package</button>
                <button :class="activetab == 5 ? 'active': ''" @click="handleActive(5)">Activity</button>
                <button :class="activetab == 6 ? 'active': ''" @click="handleActive(6)">Hotel</button>
                <button :class="activetab == 7 ? 'active': ''" @click="handleActive(7)">Rental</button>
            </div>
        </div>
        <div class="tab_content">
            <div class="content_item" :class="activetab == 1 ? 'active': ''">
                <HomeHotelSlider destinationSlug="port-blair"/>
            </div>

            <div class="content_item" :class="activetab == 2 ? 'active': ''">
                <HomeHotelSlider destinationSlug="havelock-island"/>
            </div>

            <div class="content_item" :class="activetab == 3 ? 'active': ''">
                <HomeHotelSlider destinationSlug="neil-island"/>
            </div>
        </div>
    </BookingTabWrapper>
</template>
<script>
import {BookingTabWrapper} from '../styles/BookingTabWrapper'
export default{
    name : 'BookingTab',
    data(){
        return{
            activetab : 1
        }
    },
    methods: {
        handleActive(index){
            this.activetab = index;
        }
    },
    components:{
    BookingTabWrapper,
}
}
</script>