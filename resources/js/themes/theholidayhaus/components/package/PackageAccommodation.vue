<template>
    <div id="package_accommodations" class="mt-9 py-3.5 px-5 border border-gray-300 rounded bg-white">
       <div id="hotel_accom" class="hotel_accordion">
          <h3 class="no_line text-xl pb-1 font-semibold">Accommodation</h3>
          <p class="pb-1"> <strong>Note:-</strong> We Will provide you these or similar accomodation depending on
             availability 
          </p>
          <template  v-for="accommodations_days in store.accommodations_days">
             <button class="accordion active">
                <div class="pull-left"><b></b></div>
                <div class="pull-right day_font">
                   <b v-html="accommodations_days.itenary_data"></b>
                </div>
             </button>

             <PackageAcomodationSlider :accommodations_days="accommodations_days"/>
             
          </template>
       </div>
    </div>
 </template>
 <script>
    import {useToast} from 'vue-toast-notification';
    import 'vue-toast-notification/dist/theme-default.css';
    // https://www.npmjs.com/package/vue-toast-notification
    import SearchForm from '../../components/SearchForm.vue';
    import Layout from '../../components/layout.vue';
    import FancyboxWrapper from '../../components/FancyboxWrapper.vue';
    import { store } from '../../store';
    import StarRating from 'vue-star-rating'
    import { Link } from "@inertiajs/inertia-vue3";
    import axios from "axios";
    import PackageAcomodationSlider from './PackageAcomodationSlider.vue';
    
    const $toast = useToast();
    
    export default {
        name: "PackageAccommodation",
        created() {
            document.body.classList.add('package-detail-page');
            if(this.packageSelectedPrice){
    
                this.package_total_price = this.packageSelectedPrice.final_amount;
                this.package_booking_price = this.packageSelectedPrice.booking_price;
                var price_id =this.packageSelectedPrice.id;
                this.loadPriceData(price_id);
            }
        },
        beforeUnmount() {
            document.body.classList.remove('package-detail-page');
        },
        data() {
            return {
                test : "test",
                store,
                package_booking_price:0,
                package_total_price:0,
                packageSelectedPrice:this.packageSelectedPrice,
                package:this.package,
                // rating: this.accommodation.star_classification,
                travellerObj : {}
            }
        },
        methods: {
            handleTraveller(e){
                var travellername = e.target.name;
                var travellervalue = e.target.value;
                var travellerprice = e.target.getAttribute('price');
    
                this.travellerObj[travellername] = {'value' : travellervalue, 'price' : travellerprice };
    
                // console.log(this.travellerObj);
            },
            showToast(toastObj){
                $toast.open(toastObj);
            },
            decodeValue(value) {
                return JSON.parse(value);
            },
        },
        mounted () {
        },
        beforeDestroy () {
        },
        watch:{
        },
        components: {
    SearchForm,
    FancyboxWrapper,
    StarRating,
    Link,
    PackageAcomodationSlider
},
        layout: Layout,
        props: ["package","packageSelectedPrice","faq_row","destinations","itenaries"],
    };
 </script>