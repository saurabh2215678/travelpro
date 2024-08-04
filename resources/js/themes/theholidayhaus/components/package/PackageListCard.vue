  <template>
    <PackagingBoxWrapper>
      <div class="packaging_view_box_top p-5">
        <div class="images">
          <Link :href="packageData.url"><img :src="packageData.thumbSrc" class="theme_radius img_responsive" :alt="packageData.title"></Link>
        </div>
        <div class="packaging_info">
          <div class="package_info_wrap">
            <div class="package_top">
              <div class="title para_lg2">
                <Link :href="packageData.url">{{packageData.title}}</Link>
              </div>
              <div class="flex flex-wrap flex-start">
                <div class="duration my-2">{{packageData.package_duration}}</div>
              </div>
              <span class="package_tour_type_text">{{packageData.tour_type? packageData.tour_type :''}} Package</span>
              <div class="pac_disc py-2" v-if="packageData?.packageCategories?.length> 0">
                <div class="package_disc flex items-center mt-2 mb-2">
                  <div class="list_row_right">
                    <ul class="activ_list" >
                      <li v-for="pc in packageData.packageCategories" :key="pc.id" v-html="pc.title"></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="price">
              <div class="price_inner mb-2" v-if="packageData.packagePrices && packageData.packagePrices.length > 0">
                <p class="price_package text-lg text-black">Starting From : </p>
                <div v-if="this.publish_price && this.search_price && parseFloat(this.publish_price) > parseFloat(this.search_price)" class="cut-price text-lg leading-normal"><span class="text-slate-500 relative">{{showPrice(this.publish_price,true)}}</span></div>

                <span class="amount heading_sm3">{{showPrice(this.search_price,true)}}</span>
                <div class="peerson"></div>
              </div>
              <div class="price_inner mb-2" v-else>
                  <div class="req_price" v-if="packageData.packagePrices && packageData.packagePrices.length <= 0">Price On Request</div>
              </div>
              <div class="right_side">
              <div class="flex flex-wrap justify-end mb-3">
                  <Link :href="this.bookingUrl" class="btn text-sm bg_theme mb-2" v-if="this.publish_price && this.search_price && packageData.packagePrices.length > 0">Book Now</Link>
                  <Link :href="`${packageData.enquiry_url}&type=${this.typename}`" class="btn theme_clr enquir_now text-sm bg_secondary_theme mb-2">Enquire Now</Link>
                </div>
            </div>
             </div>
          </div>

          <div class="cities py-2" v-if="packageData?.days_nights_city?.length > 0">
            <strong>Location : </strong>
            <template v-for="dnc_value in packageData.days_nights_city" :key="dnc_value" >
              <span v-html="dnc_value"></span>                                          
            </template>
          </div>

          <div class="package_type">
            <div class="customradio pr-2" v-for="price in packageData.packagePrices" :key="price.id">
              <input type="radio" :name="`package_type_${packageData.id}`" :value="price.id" :checked="price.is_default == 1" class="mr-1" :data-package_id="packageData.id" @click="ajaxPriceDetails($event)">{{price.title}}
            </div>
          </div>
          <div class="packaging_view_footer mt-5">
        <div class="packaging_view_footer_inner">
          <div class="left_side" v-if="packageData.package_inclusions && packageData.package_inclusions?.length > 0">
            <div class="inclusions_box">
              <ul class="inclusions">
                <li v-for="package_inclusion in packageData.package_inclusions" data-text="{{package_inclusion.value}}">
                  <img :alt="package_inclusion.value" :src="package_inclusion.image">
                  {{package_inclusion.value}}                                       
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
        </div>
      </div>
      
    </PackagingBoxWrapper>
  </template>
  <script>
  import { Link } from "@inertiajs/inertia-vue3";
  import axios from "axios";
  import {showPrice} from '../../utils/commonFuntions.js';
  import { PackagingBoxWrapper } from "../../styles/PackagingBoxWrapper";
  export default {
    name: "PackageListCard",
    created() {
      if(this.packageData) {
        this.publish_price = this.packageData?.publish_price;
        this.search_price = this.packageData?.search_price;
        this.typename = this.packageData?.packageDefaultPrice?.title;
        this.bookingUrl = this.packageData?.url;
      }
    },
    beforeUnmount() {

    },
    data() {
      return {
        publish_price: 0,
        search_price: 0,
        processing: false,
        typename: '',
        bookingUrl: '',
      }
    },
    methods: {
      showPrice,
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
    mounted () {
    },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
      Link,
      PackagingBoxWrapper
    },
    props: ["packageData"],
  };
  </script>