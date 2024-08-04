<template>
    <SearchForm type="hotel" />
    <section class="hotel_bookinglist">
        <div class="xl:container xl:mx-auto">
            <div class="row">
                <div class="pull-right lg:w-1/4 side_bar">
                    <form id="adv_hotel_search" name="adv_hotel_search">
                        <div class="acco_category checkbox_list">
                            <div class="filter_box">
                                <div class="title pb-2">Property types</div>
                                <div class="acco_category checkbox_list">
                                    <ul class="term-list">
                                    <li class="term-item" v-for="category in categories" :key="category.id">
                                        <input type="checkbox" :id="`category_${category.id}`" name="categories[]" :value="category.id" @click="handleClickfunction" :checked="this.checkedFunction('categories',category.id)" >
                                        <label :for="`category_${category.id}`"> <span>{{category.title}}</span></label>
                                    </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="acco_category checkbox_list">
                            <div class="filter_box">
                                <div class="title pb-2">Amenities</div>
                                <div class="acco_category checkbox_list">
                                    <ul class="term-list">
                                    <li class="term-item" v-for="feature in features" :key="feature.id">
                                        <input type="checkbox" :id="`feature_${feature.id}`" name="features[]" :value="feature.id" @click="handleClickfunction" :checked="this.checkedFunction('features',feature.id)" >
                                        <label :for="`feature_${feature.id}`"> <span> {{feature.title}}</span></label>
                                    </li>
                                    
                                    </ul>
                                </div>
                            </div>
                        </div>
                           <div class="filter_box star-loc">
                            <div class="title pb-2">Hotel Class </div>
                            <div class="traveller_rating">
                                <div class="checkbox_list" v-for="hotel_class in [5,4,3,2,1]">
                                    <input type="checkbox" :id="`class_${hotel_class}`" :value="hotel_class" name="class[]" @click="handleClickfunction" :checked="this.checkedFunction('class',hotel_class)">
                                    <label :for="`class_${hotel_class}`" class="flex items-center">
                                        <ul class="rating_list py-1" :class="'test' + star" :rating="hotel_class">
                                            <StarRating :rating="hotel_class" read-only :show-rating="false"/>
                                            <!-- <li v-for="ii in star">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                    <path
                                                        d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z" />
                                                </svg>
                                            </li> -->
                                        </ul>
                                    </label>
                                </div>
                            </div>
                        </div>
  
  
                       <div class="acco_category checkbox_list">
                            <div class="filter_box">
                                <div class="title pb-2">Traveller Rating</div>
                                <div class="acco_category checkbox_list">
                                    <ul class="term-list">
                                        <li class="term-item" v-for="star in [10,9,8,7,6,5,4]">
                                            <input type="checkbox" :id="`star_rating${star}`" name="stars[]" :value="star" @click="handleClickfunction" :checked="this.checkedFunction('stars',star)" >
                                            <label :for="`star_rating${star}`"><span>{{star}}</span></label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> 

                        <ul class="filter_button">
                           <li><button type="submit" class="btn">Apply</button></li>
                           <li><Link :href="store.websiteSettings?.HOTEL_URL" class="btn2 ml-2">Clear</Link></li>
                       </ul>
                    </form>
                </div>
                <div class="pull-right lg:w-3/4">
                    <div class="text_center mb-5">
                    <div class="theme_title mb-5">
                        <h1 class="title text-2xl" v-if="store.seoData?.page_title">{{store.seoData?.page_title}}</h1>
                        <div class="breadcrumb_full">
                            <div class="container">
                                <ul class="breadcrumb py-2 text-right">
                                <li><Link :href="store.websiteSettings?.FRONTEND_URL">Home</Link></li>
                                <li><Link :href="store.websiteSettings?.HOTEL_URL">Hotels</Link></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    </div>
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
                    
                    <div class="hotel-list-view">
                        <section class="p-0" v-if="this.isLoading">
                            <oneWayPageLoader />
                        </section>
                        <template v-else>
                            <template v-if="this.hotelList.data && this.hotelList.data.length > 0">
                                <div class="hotel-card mb-5" v-for="accommodation in this.hotelList.data" :key="accommodation.id">
                                    <HotelsCardlist :accommodation="accommodation" :checkin="checkin" :checkout="checkout" :adult="adult" :child="child" :infant="infant"
                                    /> 
                                </div>
                                <Pagination :links="this.hotelList?.links" />
                            </template>
                            <template v-else>
                                <div class="alert_not_found">No hotel(s) found matching your search criteria. Please look for an alternate hotel or call our travel experts at <a :href="`tel:${store.websiteSettings?.BOOKING_QUERIES_NO}`">{{store.websiteSettings?.BOOKING_QUERIES_NO}}.</a> You may also drop us an email at <a :href="`mailto:${store.websiteSettings?.SALES_EMAIL}`">{{store.websiteSettings?.SALES_EMAIL}}</a></div>
                            </template>
                        </template>
                         
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import SearchForm from '../components/SearchForm.vue';
import oneWayPageLoader from '../skeletons/oneWayPageLoader.vue';
import HotelsleftFilter from '../components/hotel/HotelsleftFilter.vue';
import HotelsCardlist from '../components/hotel/HotelsCardlist.vue';
import Layout from '../components/layout.vue';
import Pagination from '../components/Pagination.vue';
import { store } from '../store';
import StarRating from 'vue-star-rating'
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";

export default {
    created() {
        store.searchData = this.search_data;
        store.seoData = this.seo_data;
        this.loadAccommodationData();
    },
    beforeUnmount() {
        window.removeEventListener('scroll', this.handleScroll);
   },
    data() {
        return {
            store,
            allHotelList:{
                "data": [],
                "links": "",
            },
            hotelList:{
                "data": [],
                "links": "",
            },
            total_links: 0,
            categories_arr:[],
            isLoading:false,
            isScrolled:false,
            collapsed : false
        }
    },
    methods: {
        handleScroll (event) {
            let currentObj = this;
            if(!this.isScrolled) {
                currentObj.isScrolled = true;
                setTimeout(function(){
                    currentObj.hotelList = currentObj.allHotelList;
                },200);
            }
        },
        checkedFunction(module_name,value) {
          let checked = false;
          let checkedArr = [];
          if(store.searchData) {
            if(module_name == 'features') {
              checkedArr = store.searchData.features;
            } else if(module_name == 'categories') {
              checkedArr = store.searchData.categories;
            } else if(module_name == 'destinations') {
              checkedArr = store.searchData.destinations;
            } else if(module_name == 'stars') {
              checkedArr = store.searchData.stars;
            } else if(module_name == 'class') {
              checkedArr = store.searchData.class;
            }else if(module_name == 'sort_by_price') {
              checkedArr = store.searchData.sort_by_price;
            }
            if(checkedArr) {
              if(checkedArr.indexOf(String(value)) !== -1) {
                checked = true;
              }
            }
          }
          return checked;
        },
        handleClickfunction(e) {
            // e.preventDefault();
            // var name = e.target.name;
            // var value = e.target.value;
            // this.isSearched = true;
            // var form_data =  {
            //     categories:categories,
            // }
            // this.loading = true;
            // this.$inertia.get(store.websiteSettings?.HOTEL_URL, form_data);
            this.handleFormSubmit(e);
        },
        loadAccommodationData() {
          this.isLoading = true; 
          var currentObj = this;
          window.removeEventListener('scroll', currentObj.handleScroll);
          var type = 'Package';
          let form_data = store?.searchData;
          form_data['type'] = type;
          axios.post('/hotel/ajaxAccommodationList',form_data)  
          .then(function (response) {
            currentObj.isLoading = false;   
            window.addEventListener('scroll', currentObj.handleScroll);
            if(response.data.success) {
              currentObj.allHotelList = response.data?.hotelList;
              var hotelList = response.data?.hotelList;
              if(hotelList && hotelList.data && hotelList.data.length > 5) {
                var hotelData = hotelList.data;
                var newHotelData = hotelList.data.slice(0, 5);
                var newHotelList = {};
                newHotelList['data'] = newHotelData;
                newHotelList['links'] = hotelList.links;
                currentObj.hotelList = newHotelList;
              } else {
                currentObj.hotelList = hotelList;
                currentObj.isScrolled = true;
              }
              currentObj.total_count = response.data?.total_count;
            } else {
              alert('Something went wrong, please try again.');
            }
            // currentObj.processing = false;
            // console.log('running in then');
          });
        },
        handleFormSubmit(e){

            // alert('ERROR');
            e.preventDefault();
            $('#adv_hotel_search').submit();
            // this.isSearched = true;
            //    var form_data =  {
            //     filter_tour_type: this.filterTourType,
            //     categories: this.categoriesArr,
            //     filter_package_budget: this.filterPackageBudget,
            //     filter_month: this.filterMonth,
            //     text: this.textKey,
            //     sno_of_days: this.snoOfDays,
            //     smonth: this.sMonth,

            //  } 

            // let form_data = $('#adv_hotel_search').serializeArray();
            // console.log('form_data=',form_data);
            // return false;

               // this.loading = true;
               // this.$inertia.get(store.websiteSettings?.HOTEL_URL, form_data);
        
         },
         goback(){
            store.loadingName = "searchForm";
            window.history.back();
        }
    },
    mounted () {
    },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
        SearchForm,
        HotelsleftFilter,
        HotelsCardlist,
        Pagination,
        StarRating,
        Link,
        oneWayPageLoader,
    },
    layout: Layout,
    props: ["checkin","checkout","adult","child","infant","categories","features","search_data", "seo_data"],
};
</script>