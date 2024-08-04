  <template>
    <ActivityBoxWrapper>
      <div class="packaging_view_box_top p-3">
        <div class="wishlist_btn" v-if="store.userInfo?.email" >
          <span :id="`favid-${packageData.id}`" :class="`pkg_fav pkg_fav_${packageData.id} ${this.packageLiked}`">
            <img class="wishlist" src="../../assets/images/wishlist1.png" alt="Wishlist">
            <img class="wishlist_active" src="../../assets/images/wishlist-active1.png" alt="Wishlist">
          </span>
        </div> 
  
        <div class="images">
          <Link :href="packageData.url"><img :src="packageData.thumbSrc" class="theme_radius img_responsive" :alt="packageData.title"></Link>
        </div>

        <div class="packaging_info">
          <div class="package_info_wrap">
            <div class="package_top">
              <div class="title para_lg2">
                <!-- <span class="package_tour_type_text">{{packageData.tour_type? packageData.tour_type :''}} Package</span> -->
                <Link :href="packageData.url">{{packageData.title}}</Link>
              </div>

              <div class="flex flex-wrap flex-start">
                <div class="duration mb-1 mr-2"><i class="fa-regular fa-clock"></i> {{packageData.package_duration}}</div>
                <div class="location mb-1 text-sm" v-if="packageData.packageDestination_name"><i class="fa-solid fa-location-dot"></i> {{packageData.packageDestination_name}}</div>
              </div>
              <div class="price_inner mb-2">
                <p class="price_package text-lg text-black">Starting From : </p>
                <div v-if="this.publish_price && this.search_price && parseFloat(this.publish_price) > parseFloat(this.search_price)" class="cut-price text-lg leading-normal"><span class="text-slate-500 relative">{{showPrice(this.publish_price,true)}}</span></div>
                <span class="amount heading_sm3">{{showPrice(this.search_price,true)}}</span>
                <div class="peerson"></div>
              </div>

              <div class="pac_disc py-2">
                <div class="package_disc flex items-center mt-3 mb-3">
                  <div class="list_row_right">
                    <ul class="activ_list" >
                      <li v-for="pc in packageData.packageCategories" :key="pc.id" v-html="pc.title"></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="cities pb-2">
            <strong>Places : </strong>
            <template v-for="dnc_value in packageData.days_nights_city" :key="dnc_value" >
              <span v-html="dnc_value"></span>                                          
            </template>
          </div>
          
          <div class="package_type" >
            <div class="customradio pr-2" v-for="price in packageData.packagePrices" :key="price.id">
              <input type="radio" :name="`package_type_${packageData.id}`" :value="price.id" :checked="price.is_default == 1" class="ml-2 mr-1" :data-package_id="packageData.id" @click="ajaxPriceDetails($event)">{{price.title}}
            </div>
          </div>
        </div>

      </div>
      <div class="clear"></div>
      <div class="packaging_view_footer">
        <div class="packaging_view_footer_inner">
          <div class="left_side">
            <div class="inclusions_box">
              <ul class="inclusions">
                <li v-for="package_inclusion in packageData.package_inclusions" data-text="{{package_inclusion.value}}">
                  <img :alt="package_inclusion.value" :src="package_inclusion.image">
                  {{package_inclusion.value}}                                       
                </li>
              </ul>
            </div>
          </div>
          <div class="right_side">
            <div class="flex flex-wrap my-3">
                <Link :href="this.bookingUrl" class="btn mr-3 text-sm bg_theme mb-2">Book Now</Link>
                <Link :href="`${packageData.enquiry_url}&type=${this.typename}`" class="btn theme_clr enquir_now text-sm bg_secondary_theme mb-2">Enquire Now</Link>
              </div>
          </div>
        </div>
      </div>
    </ActivityBoxWrapper>
  </template>
<script>
  import { Link } from "@inertiajs/inertia-vue3";
  import axios from "axios";
  import { store } from '../../store';
  import {showPrice, DateFormat} from '../../utils/commonFuntions.js';
  import {ActivityBoxWrapper} from '../../styles/ActivityBoxWrapper';

  export default {
    name: "ActivityListCard",
    created() {
      if(this.packageData) {
        this.publish_price = this.packageData?.publish_price;
        this.search_price = this.packageData?.search_price;
        this.typename = this.packageData?.packageDefaultPrice?.title;
        this.bookingUrl = this.packageData?.url;
      }

      if(store.userInfo && store.userInfo.package_fab) {
        if(this.packageData.id && store.userInfo.package_fab[this.packageData.id]) {
          this.packageLiked = 'pkg_fav_clicked liked_btn';
        }

      }
    },
    data() {
      return {
        store,
        publish_price: 0,
        search_price: 0,
        processing: false,
        typename: '',
        bookingUrl: '',
        packageLiked: '',
      }
    },
    methods: {
      showPrice,
      DateFormat,
      ajaxPriceDetails(event) {
        var currentObj = this;
        var typeId = event.target.value;
        var pkgId = event.target.getAttribute('data-package_id');
        this.loadPriceData(typeId,pkgId);
      },
      loadPriceData(typeId,pkgId) {
        var currentObj = this;
        currentObj.processing = true;
        currentObj.errors = {};
        axios.post('/package/getPackageTypePrice', {
          pkgId: pkgId,
          typeId: typeId,
        })  
        .then(function (response) {  
          if(response.data.success) {
            currentObj.publish_price = response.data.publish_price;
            currentObj.search_price = response.data.search_price;
            currentObj.typename = response.data.title;
            currentObj.bookingUrl = currentObj.packageData.url+'?type='+response.data.title;
          } else {
            alert('Something went wrong, please try again.');
          }
          currentObj.processing = false;
        });
      }
    },
    components: {
      Link,
      ActivityBoxWrapper
    },
    props: ["packageData"],
  };
  </script>