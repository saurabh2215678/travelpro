<template>
    <SearchFormWithBanner
        type="hotel"
    />
    <ListingWrapper class="hotel_wrap p_list_view">
        <div class="container">
            <div class="hotel_wrap_inner">
                <div class="close_filter_btn" @click="this.filterOpened = false">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <div class="side_bar">
                    <form id="adv_hotel_search" name="adv_hotel_search">

                        <div class="filter_box">
                            <div class="sidetitle text-base font-semibold mx-3">Property types</div>
                            <div class="checkbox_list px-3" v-for="category in categories" :key="category.id">
                                <input type="checkbox" :id="`category_${category.id}`" name="categories[]" :value="category.id" :checked="this.checkedFunction('categories',category.id)" @change="handleCheckboxChange($event, 'categories')">
                                <label :for="`category_${category.id}`" > <span>{{category.title}}</span></label><br>
                            </div>
                        </div>

                        <div class="filter_box">
                            <div class="sidetitle text-base font-semibold mx-3">Amenities</div>
                            <div class="checkbox_list px-3" v-for="feature in features" :key="feature.id">
                                <input type="checkbox" :id="`feature_${feature.id}`" name="features[]" :value="feature.id" :checked="this.checkedFunction('features',feature.id)" @change="handleCheckboxChange($event, 'features')">
                                <label :for="`feature_${feature.id}`" > <span> {{feature.title}}</span></label><br>
                            </div>
                        </div>
                        
                        <div class="filter_box">
                            <div class="sidetitle text-base font-semibold mx-3">Hotel Class</div>
                            <div class="checkbox_list px-3 flex" v-for="hotel_class in [5,4,3,2,1]">
                                <input type="checkbox" :id="`class_${hotel_class}`" :value="hotel_class" name="class[]" :checked="this.checkedFunction('class',hotel_class)" @change="handleCheckboxChange($event, 'class')">
                                <label :for="`class_${hotel_class}`" class="flex items-center">
                                    <ul class="rating_list py-1" :class="'test' + star" :rating="hotel_class">
                                        <StarRating :rating="hotel_class" read-only :show-rating="false"/>
                                    </ul>
                                </label><br>
                            </div>
                        </div>

                        <div class="filter_box">
                            <div class="sidetitle text-base font-semibold mx-3">Traveller Rating</div>
                            <div class="checkbox_list px-3" v-for="star in [10,9,8,7,6,5,4]">
                                <input type="checkbox" :id="`star_rating${star}`" name="stars[]" :value="star" :checked="this.checkedFunction('stars',star)" @change="handleCheckboxChange($event, 'stars')">
                                <label :for="`star_rating${star}`"><span>{{star}}</span></label>
                            </div>
                        </div>

                        <ul class="filter_button">
                           <!-- <li><button type="submit" class="btn">Apply</button></li> -->
                            <li><Link :href="store.websiteSettings?.HOTEL_URL" class="btn2" @click="this.filterOpened = false">Clear</Link></li>
                       </ul>
                    </form>
                </div>

                <div class="pageLoader" :class="loading ? 'active' : ''">
                  <LottieAnimation
                  path="../assets/lottieFiles/loader.json"
                  :loop="true"
                  :autoPlay="true"
                  :speed="1"
                  :width="256"
                  :height="256"
                  />
              </div>
                <div class="open_filter_btn" @click="this.filterOpened = true">
                    <i class="fa-solid fa-filter"></i>
                </div>
                <div class="filter_backdrop" @click="this.filterOpened = false"></div>

                <div class="hotel_view">
                    <h1 class="title text-2xl mb-3 color_theme fw600" v-if="store.seoData?.page_title" v-html="store.seoData?.page_title"></h1>
                    <div id="disc-title" class="text-left mb-5" :class="collapsed ? 'collapsed' : ''" v-if="store.seoData?.page_brief || store.seoData?.page_description">
                        <div class="description_inner">
                          <div v-if="store.seoData?.page_brief" class="text-sm" v-html="store.seoData.page_brief"></div>
                          <div v-if="store.seoData?.page_description" class="text-sm" v-html="store.seoData.page_description"></div>
                        </div>
                      <div class="read_option" @click="collapsed = !collapsed"> 
                        {{ collapsed ? 'Hide Info' : 'More Info' }}
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    </div> 

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
    </ListingWrapper>
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
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import { ListingWrapper } from "../styles/HotelListingStyle";
import LottieAnimation from "lottie-vuejs/src/LottieAnimation.vue";


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
            filterOpened : false,
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
            //this.handleFormSubmit(e);
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

    handleCheckboxChange (e, name) {
      this.loading = true;
      let searchData = store.searchData;
      var name_arr = [];
      if(searchData[name]) {
        name_arr = searchData[name];
      }

      if(e.target.checked){
        name_arr.push(e.target.value);
      } else {
        const index = name_arr.indexOf(e.target.value);
        if (index > -1) { // only splice array when item is found
          name_arr.splice(index, 1); // 2nd parameter means remove one item only
        }
      }
      store.searchData[name] = name_arr;
      setTimeout(this.filterSearchResult,300);
    },
    filterSearchResult () {
      var form_data = store.searchData;
      this.$inertia.get(store.websiteSettings?.HOTEL_URL, form_data);      
    },
        handleFormSubmit(e){

            // alert('ERROR');
            //e.preventDefault();
            //$('#adv_hotel_search').submit();
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
        filterOpened: {
        handler: function(filterOpened){
            if(filterOpened){
            document.body.classList.add('sidebar_opened');
            }else{
            document.body.classList.remove('sidebar_opened');
            }
        }
        }
    },
    components: {
        SearchForm,
        HotelsleftFilter,
        HotelsCardlist,
        Pagination,
        StarRating,
        Link,
        oneWayPageLoader,
        SearchFormWithBanner,
        ListingWrapper,
        LottieAnimation
    },
    layout: Layout,
    props: ["seo_data", "search_data", "checkin", "checkout", "adult", "child", "infant", "categories", "features"]
};
</script>