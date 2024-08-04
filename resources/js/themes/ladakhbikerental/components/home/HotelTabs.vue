<template>
    <HotelTabsWrapper>
        <div class="container">
            <div class="excontent">
                <div class="heading2"><strong>Hotels</strong>Top Hotels</div>
                <p>A one-stop destination for people wishing to book holidays, packages and hotels for family holidays, business trips and honeymoon tour packages in Andaman Islands.</p>
            </div>
            <div class="tab_btns">
                <button :class="activetab == 1 ? 'active':''" @click="handleActive(1)">Top Hotels in Port Blair</button>
                <button :class="activetab == 2 ? 'active': ''" @click="handleActive(2)">Top Hotels in Havelock</button>
                <button :class="activetab == 3 ? 'active': ''" @click="handleActive(3)">Top Hotels in Neil Island</button>
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
    </HotelTabsWrapper>
</template>
<script>
import {HotelTabsWrapper} from '../../styles/HotelTabsWrapper'
import HomeHotelSlider from './HomeHotelSlider.vue';
export default{
    name : 'HotelTabs',
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
    HotelTabsWrapper,
    HomeHotelSlider
}
}
</script>