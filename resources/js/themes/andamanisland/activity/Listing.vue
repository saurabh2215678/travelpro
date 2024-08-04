    <template>

      <SearchFormWithBanner
        title="Search Activity"
        type="activity"
      />
 

      <ListingWrapper class="packaging_wrap p_list_view">
        <div class="container">
          <div class="packaging_wrap_inner">

            <div class="close_filter_btn" @click="this.filterOpened = false">
              <i class="fa-solid fa-xmark"></i>
            </div>

            <div class="side_bar">
              <form id="adv_package_search" :action="store.websiteSettings?.ACTIVITY_URL">
              <div class="sidebar-title text-xl text-teal-600 font-bold bg-slate-200 px-3 py-1">Filter</div>
               <!--  <div class="filter_box">
                  <div class="sidetitle text-base font-semibold mx-3">Package Type </div>
                  <div class="checkbox_list px-3">
                    <input type="checkbox" id="filter_tour_type_group" value="group" :checked="this.checkedFunction('filter_tour_type','group')" name="filter_tour_type[]" @change="handleCheckboxChange($event, 'filter_tour_type')" >
                    <label for="filter_tour_type_group">Group Tour</label><br>
                  </div>
                  <div class="checkbox_list px-3">
                    <input type="checkbox" id="filter_tour_type_fixed" value="fixed" :checked="this.checkedFunction('filter_tour_type','fixed')" name="filter_tour_type[]" @change="handleCheckboxChange($event, 'filter_tour_type')" >
                    <label for="filter_tour_type_fixed">Fixed Tour</label><br>
                  </div>
                  <div class="checkbox_list px-3">
                    <input type="checkbox" id="filter_tour_type_open" value="open" :checked="this.checkedFunction('filter_tour_type','open')" name="filter_tour_type[]" @change="handleCheckboxChange($event, 'filter_tour_type')" >
                    <label for="filter_tour_type_open">Open Tour</label><br>
                  </div>
                </div> -->
                <div class="filter_box">
                  <div class="sidetitle text-base font-semibold mx-3">Categories </div>
                  <div class="checkbox_list px-3" v-for="them_cat in themes" :key="them_cat.id">
                    <input type="checkbox" :id="`categories${them_cat.id}`" name="categories[]" :value="them_cat.id" :checked="this.checkedFunction('categories',them_cat.id)" @change="handleCheckboxChange($event, 'categories')" >
                    <label :for="`categories${them_cat.id}`" v-html="them_cat.title"></label><br>
                  </div>
                </div>
                <div class="filter_box">
                  <div class="sidetitle text-base font-semibold mx-3">Location Wise</div>
                  <div class="checkbox_list px-3" v-for="destination in destinations">
                    <input type="checkbox" :id="`destination${destination.id}`" name="destinations[]" :value="destination.id" :checked="this.checkedFunction('destinations',destination.id)" @change="handleCheckboxChange($event, 'destinations')" >
                    <label :for="`destination${destination.id}`">{{destination.name}}</label>
                  </div>      
                </div>
                <div class="filter_box">
                  <div class="sidetitle text-base font-semibold mx-3">Budget per person</div>
                  <div class="checkbox_list px-3" v-for="budget in budgetList">
                    <input type="checkbox" :id="`filter_package_budget${budget.key}`" name="filter_package_budget[]" :value="budget.key" :checked="this.checkedFunction('filter_package_budget',budget.key)" @change="handleCheckboxChange($event, 'filter_package_budget')" >
                    <label :for="`filter_package_budget${budget.key}`">{{budget.value}}</label>
                  </div>
                </div>
                <div class="filter_box">
                  <div class="sidetitle text-base font-semibold mx-3">Month Wise</div>
                  <div class="checkbox_list px-3" v-for="val,key in store.websiteSettings?.months">
                    <input type="checkbox" :id="`filter_month${key}`" name="filter_month[]" :value="key" :checked="this.checkedFunction('filter_month',key)" @change="handleCheckboxChange($event, 'filter_month')" >
                    <label :for="`filter_month${key}`">{{val}}</label>
                  </div>
                </div>
                <ul class="filter_button">
                  <!-- <li><button type="submit" class="btn">Apply</button></li> -->
                  <li><Link :href="store.websiteSettings?.ACTIVITY_URL" class="btn2 ml-2" @click="this.filterOpened = false">Clear</Link></li>
                </ul>
                <input type="hidden" name="sdest" :value="store.searchData?.sdest">
                <input type="hidden" name="sno_of_days" :value="store.searchData?.sno_of_days">
                <input type="hidden" name="smonth" :value="store.searchData?.smonth">         
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

            <div class="packaging_view">
              <h1 class="title text-2xl mb-3 color_theme fw600" v-if="store.seoData?.page_title" v-html="store.seoData?.page_title"></h1>
              <!-- <div id="disc-title" class="text-left" :class="collapsed ? 'collapsed' : ''" v-if="store.seoData?.page_brief || store.seoData?.page_description">
                <div class="description_inner">
                  <p v-if="store.seoData?.page_brief && 1==2" class="text-sm" v-html="store.seoData.page_brief"></p>
                <p v-if="store.seoData?.page_description" class="text-sm" v-html="store.seoData.page_description"></p>
                </div>
              <div class="read_option" @click="collapsed = !collapsed"> 
                  {{ collapsed ? 'Hide Info' : 'More Info' }}
                  <i class="fa-solid fa-arrow-down"></i>
              </div>
              </div> -->

              <div id="disc-title" class="text-left mb-5" :class="collapsed ? 'collapsed' : ''" v-if="hasContent">
                <div class="description_inner">
                  <p v-if="store.seoData?.page_brief" class="text-sm" v-html="store.seoData.page_brief"></p>
                  <p v-if="store.seoData?.page_description" class="text-sm" v-html="store.seoData.page_description"></p>
                </div>
                <template v-if="store.seoData?.page_brief && showMoreButton">
                  <div class="read_option" @click="collapsed = !collapsed"> 
                    {{ collapsed ? 'Hide Info' : 'More Info' }}
                    <i class="fa-solid fa-arrow-down"></i>
                  </div>
                </template>
              </div>
              

              <section class="p-0" v-if="this.isLoading">
                <OneWayPageLoader />
              </section>
              <template v-else>
                <template v-if="this.packageList.data && this.packageList.data.length > 0">
                  <!-- Loop -->
                  
                  <PackagingTop
                    :total_count="total_count" 
                    :handleOnChange="handleOnChange" 
                    :checkedFunction="checkedFunction" 
                  />

                  <div class="packaging_view_box" v-for="packageData in this.packageList?.data" :key="packageData.id">
                    <ActivityListCard :packageData="packageData" />
                  </div>
                  <!-- Loop -->
                  <Pagination :links="this.packageList?.links" />              
                </template>
                <template v-else>
                  <div class="alert_not_found">No activities found matching your search criteria. Please look for an alternate activity or call our travel experts at <a :href="`tel:${store.websiteSettings?.BOOKING_QUERIES_NO}`">{{store.websiteSettings?.BOOKING_QUERIES_NO}}.</a> You may also drop us an email at <a :href="`mailto:${store.websiteSettings?.SALES_EMAIL}`">{{store.websiteSettings?.SALES_EMAIL}}</a></div>
                </template>
              </template>
            </div>
          </div>
        </div>
      </ListingWrapper>
        <ThemeFaq v-if="faqs && faqs.length > 0" :faqs="faqs" />
    </template>
<script>
import axios from "axios";
import { Link } from "@inertiajs/inertia-vue3";
import Layout from '../components/layout.vue';
import SearchFormWithBanner from "../components/SearchFormWithBanner.vue";
import ThemeFaq from '../components/activity/ThemeFaq.vue';
import Pagination from '../components/Pagination.vue';
import ActivityListCard from '../components/activity/ActivityListCard.vue';
import { store } from '../store';
import { ListingWrapper } from "../styles/PackageListingStyle";
import OneWayPageLoader from "../skeletons/oneWayPageLoader.vue";
import PackagingTop from "../components/package/PackagingTop.vue";
import LottieAnimation from "lottie-vuejs/src/LottieAnimation.vue";

export default {
  created() {
    //console.log("faqs ===", this.faqs);

    document.body.classList.add('holiday_package');
    store.searchData = this.search_data;
    store.seoData = this.seo_data;
    this.loadPackageData();
  },
  beforeUnmount() {
    document.body.classList.remove('holiday_package');
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
      var type = 'Activity';
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
      });
    },
    handleOnChange(e){
      this.loading = true;
      var selectName = e.target.name;
      var selectValue = e.target.value;
      // this.store.searchData[selectName] = selectValue;
      // var form_data = store.searchData;
      // this.$inertia.get(store.websiteSettings?.ACTIVITY_URL, form_data);
      store.searchData[selectName] = selectValue;
      setTimeout(this.filterSearchResult,300);
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
      this.$inertia.get(store.websiteSettings?.ACTIVITY_URL, form_data);      
    },
    handleFormSubmit(e){
      // e.preventDefault();
      // this.isSearched = true;
      //    this.loading = true;
      //    this.$inertia.get(`/search-packages`, new FormData($('#adv_package_search')[0]));
    },
    goback(){
        store.loadingName = "searchForm";
        window.history.back();
    }
  },
  computed: {
    hasContent() {
      return this.store.seoData?.page_brief || this.store.seoData?.page_description;
    },
    showMoreButton() {
      const pageBriefLength = this.store.seoData?.page_brief ? this.store.seoData.page_brief.length : 0;
      // Adjust the threshold value as needed
      const threshold = 450; // for example, show the button if content length is more than 50 characters
      return pageBriefLength > threshold;
    }
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
    SearchFormWithBanner,
    ActivityListCard,
    Pagination,
    Link,
    ListingWrapper,
    OneWayPageLoader,
    PackagingTop,
    LottieAnimation,
    ThemeFaq
},
  layout: Layout,
  props: ["themes", "destinations", "budgetList", "search_data", "seo_data","faqs"],
};
</script>