<template>
   <!-- <link rel="stylesheet" href="http://localhost:8000/css/overland.css"> -->
   <Layout :metaTitle="metaTitle" :metaDescription="metaDescription">
        <SearchForm />
         <section class="packaging_wrap p_list_view ">
            <div class="xl:container xl:mx-auto">
               <div class="packaging_wrap_inner">
                  <div class="side_bar">
                     <form action="#" method="GET" id="adv_package_search" name="adv_package_search">
                        <div class="cross">
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                              <path d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"></path>
                           </svg>
                        </div>
                        <div class="sidebar-title text-xl text-teal-600 font-bold bg-slate-200 px-3 py-1">Filter</div>
                        <div class="filter_box">
                           <div class="text-base font-semibold pb-1">Package Type </div>
                           <div class="checkbox_list">
                              <input type="checkbox" id="filter_tour_type_group" value="group" name="filter_tour_type[]">
                              <label for="filter_tour_type_group">Group Tour</label><br>
                           </div>
                           <div class="checkbox_list">
                              <input type="checkbox" id="filter_tour_type_fixed" value="fixed" name="filter_tour_type[]">
                              <label for="filter_tour_type_fixed">Fixed Tour</label><br>
                           </div>
                           <div class="checkbox_list">
                              <input type="checkbox" id="filter_tour_type_open" value="open" name="filter_tour_type[]">
                              <label for="filter_tour_type_open">Open Tour</label><br>
                           </div>
                        </div>
                        <div class="filter_box">
                           <div class="text-base font-semibold pb-1">Categories </div>
                           <div class="checkbox_list" v-for="them_cat in themes">
                              <input type="checkbox" id="categories7" name="categories[]" :value="them_cat.id">
                              <label for="categories7">{{them_cat.title}}</label><br>
                           </div>
                        </div>
                        <div class="filter_box">
                           <div class="text-base font-semibold pb-1">Location Wise</div>
                           <div class="checkbox_list" v-for="destination in destinations">
                              <input type="checkbox" id="destination1" name="destinations[]" :value="destination.id">
                              <label for="destination1">{{destination.name}}</label>
                           </div>      
                        </div>


                        <div class="filter_box">
                           <div class="text-base font-semibold pb-1">Budget per person</div>
                           <div class="checkbox_list" v-for="budget in budgetList">
                              <input type="checkbox" id="filter_package_budget100-10_000" name="filter_package_budget[]" :value="budget.key">
                              <label for="filter_package_budget100-10_000">{{budget.value}}</label>
                           </div>
                          
                        </div>
                        <div class="filter_box">
                           <div class="text-base font-semibold pb-1">Month Wise</div>
                           <div class="checkbox_list" v-for="monthData in month_arr">
                              <input type="checkbox" id="filter_month2023-06" name="filter_month[]" :value="monthData.month">
                              <label for="filter_month2023-06">{{monthData.value}}</label>
                           </div>
                          
                        </div>
                        <ul class="filter_button">
                           <li><button type="submit" class="btn">Apply</button></li>
                           <li><a href="#" class="btn2 ml-2">Clear</a></li>
                        </ul>
                        <input type="hidden" name="sdest" value="">
                        <input type="hidden" name="sno_of_days" value="">
                        <input type="hidden" name="smonth" value="">         
                     </form>
                  </div>
                 
                 <div class="packaging_view">
                     <h1 class="title text-2xl mb-3">Activities Listing</h1>
                     <div class="packaging_top">
                        <div class="title font-bold">Showing 108 Activities For You</div>
                        <div class="sort_by">
                           <div class="custom_select">
                              <select class="form_control" name="sort_by_price" id="sort_by_price">
                                 <option value="">Sort By Price :</option>
                                 <option value="lth">Low To High</option>
                                 <option value="htl">High To Low</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="packaging_view_box" v-for="packageData in packageList.data" :key="packageData.id">
                        <div class="packaging_view_box_top p-3">
                           <div class="wishlist_btn">
                              <span id="favid-303" class="pkg_fav pkg_fav_303 pkg_fav ">
                                 <img class="wishlist" src="https://overlandescape.tekzini.com/assets/overlandescape/images/wishlist.png" alt="Wishlist">
                                 <img class="wishlist_active" src="https://overlandescape.tekzini.com/assets/overlandescape/images/wishlist-active.png" alt="Wishlist">
                              </span>
                           </div>
                           <div class="images">
                              <a href="#"><img src="http://127.0.0.1:8000/assets/overlandescape/images/noimage.jpg" class="theme_radius img_responsive" alt="Highlights of Ladakh Winter Package"></a>
                           </div>
                           <div class="packaging_info">
                              <div class="package_top">
                                 <div class="title para_lg2">
                                    <span class="package_tour_type_text">{{packageData.tour_type? packageData.tour_type :''}} Package</span>
                                    <a href="#">{{packageData.title}}</a>
                                 </div>
                                 <div class="flex flex-wrap flex-start">
                                    <div class="duration mb-3 pr-5 text-sm"><i class="fa-regular fa-clock"></i> {{packageData.package_duration}}</div>
                                    <div class="location mb-3 text-sm"><i class="fa-solid fa-location-dot"></i> {{packageData.packageDestination_name}}</div>
                                 </div>
                                 <div class="pac_disc py-2">
                                    <div class="package_disc flex items-center mt-5 mb-5">
                                       <div class="list_row_right">
                                          <ul class="activ_list" >
                                             <li v-for="pc in packageData.packageCategories" :key="pc.id">{{pc.title}}</li>
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="price">
                                 <div class="price_inner mb-2">
                                    <p class="price_package text-lg text-black">Starting From : </p>
                                    <span class="amount heading_sm3">₹{{packageData.search_price?packageData.search_price:''}}</span>
                                    <div class="peerson"></div>
                                 </div>
                                 <div class="right_side flex flex-wrap my-3">
                                    <Link :href="packageData.package_slug" class="btn mr-3 text-sm rounded-full">Book Now</Link>
                                    <a href="#" class="btn theme_clr enquir_now text-sm rounded-full">Enquire Now</a>
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="cities pb-2">
                                 <strong>Places : </strong>
                                  <template v-for="dnc_value in packageData.days_nights_city" :key="dnc_value" >
                                       <span v-html="dnc_value"></span>                                          
                                 </template>
                              </div>
                              <div class="clearfix"></div>
                              <div class="package_type" >
                                 <div class="customradio " v-for="price in packageData.packagePrices" :key="price.id">
                                    <input type="radio" name="package_type_274" value="{{price.id}}" class="mr-2" data-package_id="274">{{price.title}}
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="packaging_view_footer">
                           <div class="packaging_view_footer_inner">
                              <div class="left_side">
                                 <div class="inclusions_box">
                                    <ul class="inclusions">
                                       <li v-for="package_inclusion in packageData.package_inclusions" data-text="{{package_inclusion.value}}">
                                          <img :src="package_inclusion.image">
                                          {{package_inclusion.value}}                                       
                                       </li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="pagination-wrapper">
                        <nav>
                           <ul class="pagination">
                              <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                                 <span class="page-link" aria-hidden="true">‹</span>
                              </li>
                              <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                              <li class="page-item"><a class="page-link" href="#">2</a></li>
                              <li class="page-item"><a class="page-link" href="#">3</a></li>
                              <li class="page-item"><a class="page-link" href="#" rel="next" aria-label="Next »"><i class="fa fa-chevron-right"></i></a>
                              </li>
                           </ul>
                        </nav>
                     </div>
                  </div>
               </div>
            </div>
         </section>
    </Layout>
</template>

<script>
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
// https://www.npmjs.com/package/vue-toast-notification
import SearchForm from './components/SearchForm.vue';
import Layout from './components/layout.vue';
import { store } from './store';
import { Link } from "@inertiajs/inertia-vue3";




const $toast = useToast();

export default {
    created() {
      document.body.classList.add('holiday_package');
    },
    beforeUnmount() {
      document.body.classList.remove('holiday_package');
   },
    data() {
        return {
            test : "test",
            store,
        }
    },
    methods: {
        showToast(toastObj){
            $toast.open(toastObj);
        }
    },
    mounted () {
    },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
        Layout,
        SearchForm,
        Link
    },
    props: ["packageList","themes","destinations","budgetList","month_arr"],
};
</script>