<template>
    <SearchForm  type="rental"/>
    <section class="hotel_bookinglist" v-if="bikeList && bikeList.length > 0">
        <div class="xl:container xl:mx-auto">
            <div class="row">
                <div class="pull-right lg:w-1/4 side_bar">
                    <BikeleftFilter :cities="cities" :bikeTypes="bikeTypes" :search_data="search_data" :locations="locations" :bike_models="this.bike_models" />
                </div>
                <div class="pull-right lg:w-3/4">
                    <div class="text_center mb-2 px-4">
                        <div class="sort_by w-40 mb-3">
                        <div class="custom_select">
                            <select class="form_control" name="sort_by_price" id="sort_by_price"  @change="handleOnChange($event)">
                                <option value="">Sort By Price :</option>
                                <option value="lth">Low To High</option>
                                <option value="htl">High To Low</option>
                            </select>
                        </div>
                        </div>
                        <div class="theme_title mb-3">
                            <div class="text-sm text-teal-600">*All prices are exclusive of taxes and fuel. Images used for representation
                                purposes only, actual color may vary.</div>
                        </div>
                        <h1 class="title text-2xl mb-3" v-if="store.seoData?.page_title" v-html="store.seoData?.page_title"></h1>
                        <div id="disc-title" class="text-left" :class="collapsed ? 'collapsed' : ''" v-if="store.seoData?.page_brief || store.seoData?.page_description">
                            <div class="description_inner">
                              <div v-if="store.seoData?.page_brief" class="text-brief text-sm" v-html="store.seoData.page_brief"></div>
                              <div v-if="store.seoData?.page_description" class="text-desc text-sm" v-html="store.seoData.page_description"></div>
                          </div>
                          <div class="read_option" @click="collapsed = !collapsed"> 
                              {{ collapsed ? 'Hide Info' : 'More Info' }}
                              <i class="fa-solid fa-arrow-down"></i>
                          </div>
                      </div>
                    </div>
                    <div class="bike-list-view flex flex-wrap">
                        <template v-if="bikeList && bikeList.length > 0">
                             <div class="bikecard_list w-1/3" v-for="bike in bikeList">   
                                <BikeListCard :bike="bike" /> 
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>
       <section class="p-0" v-else-if="this.isLoading">
            <oneWayPageLoader />
       </section>
       <section class="p-0" v-else>
            <div class="no_flight_found">
                <p class="errorMessage">
                    <strong>Sorry,</strong>
                    <p>We couldn't find any bikes for you.</p>
                    <button class="btn" @click="goback">Search again</button>
                </p>
                <div class="loading_img">
                    <img src="../assets/images/no-cab-found.jpg" class="img-fluid" alt="">
                </div>
            </div>
        </section>
        <RentalFaq v-if="faqs && faqs.length > 0" :faqs="faqs" :seoData="seo_data" /> 
</template>
<script>
import SearchForm from '../components/SearchForm.vue';
import oneWayPageLoader from '../skeletons/oneWayPageLoader.vue';
import RentalSearchForm from '../components/rental/RentalSearchForm.vue';
import BikeleftFilter from '../components/rental/BikeleftFilter.vue';
import BikeListCard from '../components/rental/BikeListCard.vue';
import RentalFaq from '../components/rental/RentalFaq.vue';
import HotelsCardlist from '../components/hotel/HotelsCardlist.vue';
import Layout from '../components/layout.vue';
import { store } from '../store';
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";

export default {
    created() {
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
        store.cities = this.cities;

        this.loadBikeData();
    },
    beforeUnmount() {

    },
    data() {
        return {
            test: "test",
            store,
            bikeList:{},
            isLoading:false,
            collapsed : false,
        }
    },
    methods: {
        loadBikeData(){
          this.isLoading = true;  
          var currentObj = this;
          let form_data = store?.searchData;
          axios.post('/rental/ajaxSearchBike',form_data)  
          .then(function (response) { 

            currentObj.isLoading = false; 
            if(response.data.success) {

              currentObj.bikeList = response.data?.bikeList;
              currentObj.total_count = response.data?.total_count;
            } else {
              alert('Something went wrong, please try again.');
            }
            // currentObj.processing = false;
            // console.log('running in then');
          });
        },
        handleOnChange(e){
            let currentObj = this;
            var selectValue = e.target.value;
            if(selectValue == 'htl'){
                let bikeList = currentObj.bikeList;
                let newBikeList =  bikeList.sort((bike_a, bike_b) =>{
                    var price_a = parseInt(bike_a.search_price);
                    var price_b = parseInt(bike_b.search_price);
                    return price_b-price_a;
                });
                currentObj.bikeList = newBikeList;
            }else{  
                let bikeList = currentObj.bikeList;
                let newBikeList =  bikeList.sort((bike_a, bike_b) =>{
                    var price_a = parseInt(bike_a.search_price);
                    var price_b = parseInt(bike_b.search_price);
                    return price_a-price_b;
                });
                currentObj.bikeList = newBikeList;
            }
        },
         goback(){
            store.loadingName = "searchForm";
            window.history.back();
        }
    },
   
    handleClickfunction(e) {
          
        this.handleFormSubmit(e);
    },
    handleFormSubmit(e){

            // alert('ERROR');
        e.preventDefault();
        $('#adv_hotel_search').submit();
    },
    mounted() {
    },
    beforeDestroy() {
    },
    watch: {
    },
    components: {
        SearchForm,
        RentalFaq,
        RentalSearchForm,
        BikeleftFilter,
        BikeListCard,
        oneWayPageLoader,
        Link
    },
    layout: Layout,
    props: ["cities","locations","bikeTypes", "search_data", "seo_data","bike_models","faqs"],
};
</script>