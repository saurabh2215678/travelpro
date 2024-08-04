  <template>
    <PackagingBoxWrapper>
      <div class="packaging_view_box_top p-3">
        <div class="images">
          <Link :href="packageData.url"><img :src="packageData.thumbSrc" class="theme_radius img_responsive" :alt="packageData.title"></Link>
        </div>
        <div class="packaging_info">
          <div class="package_info_wrap">
            <div class="package_top">
              <div class="title para_lg2">
                <span class="package_tour_type_text">{{packageData.tour_type? packageData.tour_type :''}} Package</span>
                <Link :href="packageData.url">{{packageData.title}}</Link>
              </div>
              <div class="flex flex-wrap flex-start">
                <div class="duration mb-3">{{packageData.package_duration}}</div>
              </div>
              <div class="pac_disc py-2" v-if="packageData?.packageCategories?.length> 0">
                <div class="package_disc flex items-center mt-5 mb-5">
                  <div class="list_row_right">
                    <ul class="activ_list" >
                      <li v-for="pc in packageData.packageCategories" :key="pc.id" v-html="pc.title"></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="price">
              <div class="price_inner mb-2">
                <p class="price_package text-lg text-black">Starting From : </p>
                <span class="amount heading_sm3">{{showPrice(this.booking_price)}}</span>
                <div class="peerson"></div>
              </div>
              <div class="right_side flex flex-wrap my-3">
                <Link :href="packageData.url" class="btn mr-3 text-sm bg_theme">Book Now</Link>
                <Link :href="`${packageData.enquiry_url}&type=${this.typename}`" class="btn theme_clr enquir_now text-sm bg_secondary_theme">Enquire Now</Link>
    
              </div>
            </div>
          </div>

          <div class="cities pb-2" v-if="packageData?.days_nights_city?.length > 0">
            <strong>Places : </strong>
  
            <template v-for="dnc_value in packageData.days_nights_city" :key="dnc_value" >
              <span v-html="dnc_value"></span>                                          
            </template>
          </div>

          <div class="package_type" >
            <div class="customradio " v-for="price in packageData.packagePrices" :key="price.id">
              <input type="radio" :name="`package_type_${packageData.id}`" :value="price.id" :checked="price.is_default == 1" class="ml-2 mr-1" :data-package_id="packageData.id" @click="ajaxPriceDetails($event)">{{price.title}}
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
        this.booking_price = this.packageData?.search_price;
        this.typename = this.packageData?.packageDefaultPrice?.title;
      }
    },
    beforeUnmount() {

    },
    data() {
      return {
        booking_price: 0,
        processing: false,
        typename: '',
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
            var price = response.data.price;
            currentObj.booking_price = price;
            currentObj.typename = response.data.title;
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