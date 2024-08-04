<template>

    <SearchFormWithBanner
    title="Search Packages"
    />


  <ListingWrapper class="packaging_wrap p_list_view">
    <div class="container">
      <div class="packaging_wrap_inner">
        <div class="close_filter_btn" @click="this.filterOpened = false">
          <i class="fa-solid fa-xmark"></i>
        </div>
        <div class="side_bar">
          <form id="adv_package_search" :action="store.websiteSettings?.PACKAGE_URL">
            <div class="sidebar-title text-xl text-teal-600 font-bold bg-slate-200 px-3 py-2">Filter</div>
            <div class="filter_box">
              <div class="text-base font-semibold px-3">Package Type </div>
              <div class="checkbox_list px-3">
                <input type="checkbox" id="filter_tour_type_group" value="group" :checked="this.checkedFunction('filter_tour_type','group')" name="filter_tour_type[]">
                <label for="filter_tour_type_group">Group Tour</label><br>
              </div>
              <div class="checkbox_list px-3">
                <input type="checkbox" id="filter_tour_type_fixed" value="fixed" :checked="this.checkedFunction('filter_tour_type','fixed')" name="filter_tour_type[]">
                <label for="filter_tour_type_fixed">Fixed Tour</label><br>
              </div>
              <div class="checkbox_list px-3">
                <input type="checkbox" id="filter_tour_type_open" value="open" :checked="this.checkedFunction('filter_tour_type','open')" name="filter_tour_type[]">
                <label for="filter_tour_type_open">Open Tour</label><br>
              </div>
            </div>
            <div class="filter_box">
              <div class="text-base font-semibold px-3">Categories </div>
              <div class="checkbox_list px-3" v-for="them_cat in themes" :key="them_cat.id">
                <input type="checkbox" :id="`categories${them_cat.id}`" name="categories[]" :value="them_cat.id" :checked="this.checkedFunction('categories',them_cat.id)" >
                <label :for="`categories${them_cat.id}`" v-html="them_cat.title"></label><br>
              </div>
            </div>
            <div class="filter_box">
              <div class="text-base font-semibold px-3">Location Wise</div>
              <div class="checkbox_list px-3" v-for="destination in destinations">
                <input type="checkbox" :id="`destination${destination.id}`" name="destinations[]" :value="destination.id" :checked="this.checkedFunction('destinations',destination.id)" >
                <label :for="`destination${destination.id}`">{{destination.name}}</label>
              </div>      
            </div>
            <div class="filter_box">
              <div class="text-base font-semibold px-3">Budget per person</div>
              <div class="checkbox_list px-3" v-for="budget in budgetList">
                <input type="checkbox" :id="`filter_package_budget${budget.key}`" name="filter_package_budget[]" :value="budget.key" :checked="this.checkedFunction('filter_package_budget',budget.key)" >
                <label :for="`filter_package_budget${budget.key}`">{{budget.value}}</label>
              </div>
            </div>
            <div class="filter_box">
              <div class="text-base font-semibold px-3">Month Wise</div>
              <div class="checkbox_list px-3" v-for="val,key in store.websiteSettings?.months">
                <input type="checkbox" :id="`filter_month${key}`" name="filter_month[]" :value="key" :checked="this.checkedFunction('filter_month',key)">
                <label :for="`filter_month${key}`">{{val}}</label>
              </div>
            </div>
            <ul class="filter_button">
              <li><button type="submit" class="btn">Apply</button></li>
              <li><Link :href="store.websiteSettings?.PACKAGE_URL" class="btn2"  @click="this.filterOpened = false">Clear</Link></li>
            </ul>
            <input type="hidden" name="sdest" :value="store.searchData?.sdest">
            <input type="hidden" name="sno_of_days" :value="store.searchData?.sno_of_days">
            <input type="hidden" name="smonth" :value="store.searchData?.smonth">         
          </form>
        </div>
        <div class="open_filter_btn" @click="this.filterOpened = true">
          <i class="fa-solid fa-filter"></i>
        </div>
        <div class="filter_backdrop" @click="this.filterOpened = false"></div>
        <div class="packaging_view">
          <h1 class="title text-2xl mb-3 color_theme fw600" v-if="store.seoData?.page_title" v-html="store.seoData?.page_title"></h1>
          <div id="disc-title" class="text-left mb-5" v-if="store.seoData?.page_brief || store.seoData?.page_description">
            <p v-if="store.seoData?.page_brief" class="text-sm" v-html="store.seoData.page_brief"></p>
            <p v-if="store.seoData?.page_description" class="text-sm" v-html="store.seoData.page_description"></p>
          </div>

          

          <section class="p-0" v-if="this.isLoading">
            <oneWayPageLoader />
          </section>
          <template v-else>
            <template v-if="this.packageList.data && this.packageList.data.length > 0">
              <!-- Loop -->
              <!-- <div class="packaging_top">
                <div class="title font-bold">Showing {{this.total_count}} Packages For You</div>
                 <div class="sort_by">
                  <div class="custom_select">
                    <select class="form_control" name="sort_by_price" id="sort_by_price" @change="handleOnChange($event)">
                      <option value="">Sort By Price :</option>
                      <option value="lth" :selected="this.checkedFunction('sort_by_price','lth')">Low To High</option>
                      <option value="htl" :selected="this.checkedFunction('sort_by_price','htl')">High To Low</option>
                    </select>
                  </div>
                 </div>
               </div> -->
               <PackagingTop 
               :total_count="total_count" 
               :handleOnChange="handleOnChange" 
               :checkedFunction="checkedFunction" 
               />

              <div class="packaging_view_box" v-for="packageData in this.packageList?.data" :key="packageData.id">
                <PackageListCard :packageData="packageData" />
              </div>
              <!-- Loop -->
              <Pagination :links="this.packageList?.links" />              
            </template>
            <template v-else>
              <div class="alert_not_found">No package(s) found matching your search criteria. Please look for an alternate package or call our travel experts at <a :href="`tel:${store.websiteSettings?.BOOKING_QUERIES_NO}`">{{store.websiteSettings?.BOOKING_QUERIES_NO}}.</a> You may also drop us an email at <a :href="`mailto:${store.websiteSettings?.SALES_EMAIL}`">{{store.websiteSettings?.SALES_EMAIL}}</a></div>
            </template>
          </template>
        </div>
      </div>
    </div>
  </ListingWrapper>
</template>

<script>
import axios from "axios";
import { Link } from "@inertiajs/inertia-vue3";
import oneWayPageLoader from '../skeletons/oneWayPageLoader.vue';
import Layout from '../components/layout.vue';
import SearchForm from '../components/SearchForm.vue';
import Pagination from '../components/Pagination.vue';
import PackageListCard from '../components/package/PackageListCard.vue';
import { store } from '../store';
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import PackagingTop from '../components/package/PackagingTop.vue';
import { ListingWrapper } from "../styles/PackageListingStyle";


export default {
  created() {
    store.searchData = this.search_data;
    store.seoData = this.seo_data;
    this.loadPackageData();
  },
  beforeUnmount() {
    window.removeEventListener('scroll', this.handleScroll);
  },
  data() {
    return {
      store,
      allPackageList:{
        "data": [],
        "links": "",
      },
      packageList:{
        "data": [],
        "links": "",
      },
      total_count: 0,
      isLoading:true,
      isScrolled:false,
      filterOpened : false
    }
  },
  methods: {
    handleScroll (event) {
      let currentObj = this;
      if(!this.isScrolled) {
        currentObj.isScrolled = true;
        setTimeout(function(){
          currentObj.packageList = currentObj.allPackageList;
        },200);
      }
    },
    checkedFunction(module_name,value) {
      let checked = false;
      let checkedArr = [];
      if(store.searchData) {
        if(module_name == 'filter_tour_type') {
          checkedArr = store.searchData.filter_tour_type;
        } else if(module_name == 'categories') {
          checkedArr = store.searchData.categories;
        } else if(module_name == 'destinations') {
          checkedArr = store.searchData.destinations;
        } else if(module_name == 'filter_package_budget') {
          checkedArr = store.searchData.filter_package_budget;
        } else if(module_name == 'filter_month') {
          checkedArr = store.searchData.filter_month;
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
    loadPackageData(){
      this.isLoading = true; 
      var currentObj = this;
      window.removeEventListener('scroll', currentObj.handleScroll);
      var type = 'Package';
      let form_data = store?.searchData;
      form_data['type'] = type;
      axios.post('/package/ajaxSearchPackage',form_data)  
      .then(function (response) {
        currentObj.isLoading = false;
        window.addEventListener('scroll', currentObj.handleScroll);
        if(response.data.success) {
          // currentObj.packageList = response.data?.packageList;
          currentObj.allPackageList = response.data?.results;
          var packageList = response.data?.results;
          if(packageList && packageList.data && packageList.data.length > 5) {
            var packageData = packageList.data;
            var newPackageData = packageList.data.slice(0, 5);
            var newPackageList = {};
            newPackageList['data'] = newPackageData;
            newPackageList['links'] = packageList.links;
            currentObj.packageList = newPackageList;
          } else {
            currentObj.packageList = packageList;
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
    handleOnChange(e){
      var selectValue = e.target.value;
      this.store.searchData['sort_by_price'] = selectValue ;

      var form_data = store.searchData;
      this.$inertia.get(store.websiteSettings?.PACKAGE_URL, form_data);   
    },
    handleFormSubmit(e){
      // e.preventDefault();
      // this.isSearched = true;
      //    this.loading = true;
      //    this.$inertia.get(`/search-packages`, new FormData($('#adv_package_search')[0]));
    },
     
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
    PackageListCard,
    Pagination,
    Link,
    oneWayPageLoader,
    SearchFormWithBanner,
    ListingWrapper,
    PackagingTop
  },
  layout: Layout,
  props: ["themes", "destinations", "budgetList", "search_data", "seo_data"],
};
</script>