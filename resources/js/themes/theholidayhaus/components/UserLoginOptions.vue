<template>
    <UserLoginOptionsWrapper class="user_login">
        <div class="flex loging_box">
            <SvgRenderer svgPath="../assets/svgs/user.svg"/>
            <span class="ml-2">Hello {{store.userInfo.name}}</span>
        </div>

        <!-- <span v-if="this.isOnlineBooking()" class="text-xs w-full whitespace-nowrap balance"> 
            Balance : {{this.showPrice(store.userInfo.balance)}}
        </span> -->

        <ul class="user_login_list">
            <li v-if="this.isOnlineBooking()"><a :href="store.userInfo.MY_BOOKING_URL">My Booking</a> </li>
            <li v-if="this.isOnlineBooking()"><a :href="store.userInfo.MY_WALLET_URL">My Wallet</a> </li>
            <li><a :href="store.userInfo.MY_PROFILE_URL">My Profile</a> </li>
            <li><a :href="store.userInfo.MY_FAVOURITE_URL">My Favorite</a> </li>
            <li><a :href="store.userInfo.LOGOUT_URL">Logout</a> </li>
        </ul>
    </UserLoginOptionsWrapper>
</template>
<script>
import { UserLoginOptionsWrapper } from '../styles/UserLoginOptionsWrapper';
import { store } from '../store';
import {showPrice, isOnlineBooking} from '../utils/commonFuntions';
import SvgRenderer from './SvgRenderer.vue';

export default {
    name:"UserLoginOptions",
    data() {
        return {
            store
        }
    },
    methods: {
        showPrice,
        isOnlineBooking,
    },
    components:{
    UserLoginOptionsWrapper,
    SvgRenderer
}
}
</script>