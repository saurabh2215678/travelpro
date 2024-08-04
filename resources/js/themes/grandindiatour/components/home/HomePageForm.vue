<template>
    <HomePageFormWrapper :action="store.websiteSettings?.PACKAGE_URL" method="GET" name="searchForm" class="package_form" id="search_packages_form" @submit="validateSearchPackageForm">
        <img src="../../assets/images/bg_design1.png" alt="" class="form_left">
        <div class="wrap_left">
            <div class="selectoption">
               <i class="card_icon"><img src="../../assets/images/location_icon.png" alt=""></i>
               <div class="opt_wrap">
                   <label for="search_packages">Location</label>
                   <input type="text" name="text" class="form-control" :value="store.searchData?.text" id="search_packages" autocomplete="off" placeholder="Where are you going?" v-on:keyup="searchPackages($event)" @click="loadPackages('')">
                   <div class="search_packages">
                      <ul id="search_activities_ul">
                      </ul>
                   </div>
               </div>
            </div>
        </div>
        <img src="../../assets/images/plane_box.png" alt="" class="plane_box">
        <div class="wrap_right">
            <div class="selectoption">
               <i class="card_icon"><img src="../../assets/images/calender_icon.png" alt=""></i>
               <div class="opt_wrap">
                   <label>Number of Days</label>
                   <select name="sno_of_days">
                      <option value="">Select Days</option>
                      <template v-for="val,key in store.websiteSettings?.noOfDays">
                        <option :value="key" :selected="store.searchData?.sno_of_days == key" >{{val}}</option>
                      </template>
                   </select>
               </div>
            </div>
            <div class="selectoption">
               <i class="card_icon"><img src="../../assets/images/calender_icon.png" alt=""></i>
               <div class="opt_wrap">
                   <label>Select Month</label>
                   <select name="smonth">
                      <option value="">Select Month</option>
                      <template v-for="val,key in store.websiteSettings?.months">
                        <option :value="key" :selected="store.searchData?.smonth == key" >{{val}}</option>
                      </template>
                      <option value="not_decision">Not decided</option>
                   </select>
               </div>
            </div>
        </div>
        <div class="searchbtn">
            <input type="hidden" name="slug" :value="store.searchData?.search_slug">
            <button type="submit"><span>Search</span><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
        <img src="../../assets/images/bg_design2.png" alt="" class="form_right">
    </HomePageFormWrapper>
</template>
<script>

import { store } from '../../store';
import { HomePageFormWrapper } from '../../styles/HomePageFormWrapper';

export default {
    name:'HomePageForm',
    data() {
        return {
            store,
        }
    },
    components:{
        HomePageFormWrapper
    },
    props: ['validateSearchPackageForm', 'searchPackages', 'loadPackages'],
}
</script>