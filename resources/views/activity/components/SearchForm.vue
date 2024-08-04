<template>
   <div class="flight-banner searchBook_form">
      <div class="flight_page">
    <div class="xl:container xl:mx-auto mx-auto">
      
      <div class="head-search">
      <FormTopMenu />
      <form action="" method="GET" name="searchForm" class="" id="search_packages_form">
   <h3 class="text-lg font-bold pb-1 pt-1">Search Holiday Packages</h3>
   <div class="flex gap-2">
      <div class="selectoption pr-0.5 md:w-1/2 max-md:w-full">
         <i class="mapicon"></i>
         <input type="text" name="text" class="form-control" value="" id="search_packages" autocomplete="off" placeholder="Search for a place or package">
         <div class="search_packages">
            <ul id="search_activities_ul" style="display: none;">
               <li data-slug="d617"><i class="fa fa-map-marker"></i>shimla</li>
               <li data-slug="d3"><i class="fa fa-map-marker"></i>Kashmir</li>
            </ul>
         </div>
      </div>
      <div class="selectoption pr-0.5 md:w-1/3 max-md:w-full">
         <i class="themepackicon"></i>
         <select name="sno_of_days" class="pl-6">
            <option value="">Number of days</option>
            <option value="1-4">1-4 Days</option>
            <option value="5-8">5-8 Days</option>
            <option value="9-15">9-15 Days</option>
            <option value="16-30">16-30 Days</option>
         </select>
      </div>
      <div class="selectoption pr-0.5 md:w-1/3 max-md:w-full">
         <i class="calendericon"></i>
         <select name="smonth" class="pl-8">
            <option value="">Select Month</option>
            <option value="2023-06">Jun 2023</option>
            <option value="2023-07">Jul 2023</option>
            <option value="2023-08">Aug 2023</option>
            <option value="2023-09">Sep 2023</option>
            <option value="2023-10">Oct 2023</option>
            <option value="2023-11">Nov 2023</option>
            <option value="2023-12">Dec 2023</option>
            <option value="2024-01">Jan 2024</option>
            <option value="2024-02">Feb 2024</option>
            <option value="2024-03">Mar 2024</option>
            <option value="2024-04">Apr 2024</option>
            <option value="2024-05">May 2024</option>
            <option value="not_decision">Not decided</option>
         </select>
      </div>
      <div class="searchbtn">
         <input type="hidden" name="slug" value="">
         <input type="submit" class="btn btn-primary" value="Search"> 

      </div>
   </div>
</form>
</div>
</div>
</div>
</div>
</template>


<script>
import {hidePopup} from '../utils/commonFuntions';
import { store } from '../store.js';
import FormTopMenu from '../components/FormTopMenu.vue';

export default {
    name:"popup",
    created() {
        console.log('store', store.popupActive);
    },
    data() {
        return {
            store
        }
    },
    methods:{
        closeClick(){
            hidePopup();
            
        },

    },
    components: {
      FormTopMenu
    },

    props: ["className"],
}
</script>