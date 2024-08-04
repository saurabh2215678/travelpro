<template>
    <ul class="menu_list searchTop_menu">
    <li><a href="/flight" :class="getActiveClass('flight')"><i class="fa fa-plane"></i> Flight</a></li>
    <li><a href="/cab" :class="getActiveClass('cab')"><i class="fa fa-taxi"></i> Cab</a></li>
    <li><a href="/holidaypackage" :class="getActiveClass('holidaypackage')"><i class="fa fa-suitcase"></i> Holiday Packages</a></li>
    <li><a href="/holidayactivity" :class="getActiveClass('holidayactivity')"><i class="fa-solid fa-person-running"></i> Short Activities</a></li>
    <li><a href="/hotels" :class="getActiveClass('hotels')"><i class="fa-solid fa-hotel"></i> Hotels</a></li>
 </ul>
 </template>
 
 
 <script>
 import {hidePopup} from '../utils/commonFuntions';
 import { store } from '../store.js';
 
 export default {
     name:"FormTopMenu",
     created() {
         console.log('store');
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
             const locationPath = new URL(location.href).pathname.split("/")[1];
             if(locationPath == slug){

                 return 'active';
             }
         }
     },
     components:{
     },
     props: ["className"],
 }
 </script>