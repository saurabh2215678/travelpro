"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/app-resources_js_themes_andamanisland_h"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/hotels/Details.vue?vue&type=script&lang=js":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/hotels/Details.vue?vue&type=script&lang=js ***!
  \******************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vue_toast_notification__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-toast-notification */ "./node_modules/vue-toast-notification/dist/index.js");
/* harmony import */ var vue_toast_notification__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_toast_notification__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_toast_notification_dist_theme_default_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-toast-notification/dist/theme-default.css */ "./node_modules/vue-toast-notification/dist/theme-default.css");
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var _components_SearchForm_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../components/SearchForm.vue */ "./resources/js/themes/andamanisland/components/SearchForm.vue");
/* harmony import */ var _components_hotel_HotelsDetailsSlider_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../components/hotel/HotelsDetailsSlider.vue */ "./resources/js/themes/andamanisland/components/hotel/HotelsDetailsSlider.vue");
/* harmony import */ var _components_hotel_HotelsDetailsOverview_vue__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../components/hotel/HotelsDetailsOverview.vue */ "./resources/js/themes/andamanisland/components/hotel/HotelsDetailsOverview.vue");
/* harmony import */ var _components_hotel_RelatedHotels_vue__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../components/hotel/RelatedHotels.vue */ "./resources/js/themes/andamanisland/components/hotel/RelatedHotels.vue");
/* harmony import */ var _components_hotel_HotelsRoomType_vue__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../components/hotel/HotelsRoomType.vue */ "./resources/js/themes/andamanisland/components/hotel/HotelsRoomType.vue");
/* harmony import */ var _components_home_HomePackageSlider_vue__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../components/home/HomePackageSlider.vue */ "./resources/js/themes/andamanisland/components/home/HomePackageSlider.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _components_FancyboxWrapper_vue__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../components/FancyboxWrapper.vue */ "./resources/js/themes/andamanisland/components/FancyboxWrapper.vue");
/* harmony import */ var _components_SearchFormWithBanner_vue__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../components/SearchFormWithBanner.vue */ "./resources/js/themes/andamanisland/components/SearchFormWithBanner.vue");
/* harmony import */ var _components_hotel_HotelDetailGallerySection_vue__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ../components/hotel/HotelDetailGallerySection.vue */ "./resources/js/themes/andamanisland/components/hotel/HotelDetailGallerySection.vue");
/* harmony import */ var _components_Breadcrumb_vue__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ../components/Breadcrumb.vue */ "./resources/js/themes/andamanisland/components/Breadcrumb.vue");
/* harmony import */ var _styles_HotelDetailPageWrapper__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ../styles/HotelDetailPageWrapper */ "./resources/js/themes/andamanisland/styles/HotelDetailPageWrapper.js");


// https://www.npmjs.com/package/vue-toast-notification














var $toast = (0,vue_toast_notification__WEBPACK_IMPORTED_MODULE_0__.useToast)();
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'HotelDetail',
  created: function created() {
    document.body.classList.add('hotel_class');
    _store__WEBPACK_IMPORTED_MODULE_9__.store.searchData = this.search_data;
    _store__WEBPACK_IMPORTED_MODULE_9__.store.seoData = this.seo_data;
  },
  beforeUnmount: function beforeUnmount() {
    document.body.classList.remove('hotel_class');
    $('.left_area').empty();
    $('.right_area').empty();
    $('.bottom_area').empty();
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_9__.store
    };
  },
  methods: {
    showToast: function showToast(toastObj) {
      $toast.open(toastObj);
    }
  },
  mounted: function mounted() {
    $('.grid_gallery li:nth-child(1) .gallery_wrapper').appendTo('.left_area');
    $('.grid_gallery li:nth-child(2) .gallery_wrapper, .grid_gallery li:nth-child(3) .gallery_wrapper, .grid_gallery li:nth-child(4) .gallery_wrapper, .grid_gallery li:nth-child(5) .gallery_wrapper').appendTo('.right_area');
    $('.grid_gallery li:nth-child(n+6) .gallery_images').appendTo('.bottom_area');
    // console.log('running');
  },
  beforeDestroy: function beforeDestroy() {
    $('.left_area').empty();
    $('.right_area').empty();
    $('.bottom_area').empty();
  },
  watch: {},
  components: {
    SearchForm: _components_SearchForm_vue__WEBPACK_IMPORTED_MODULE_3__["default"],
    FancyboxWrapper: _components_FancyboxWrapper_vue__WEBPACK_IMPORTED_MODULE_11__["default"],
    HotelsDetailsSlider: _components_hotel_HotelsDetailsSlider_vue__WEBPACK_IMPORTED_MODULE_4__["default"],
    HotelsDetailsOverview: _components_hotel_HotelsDetailsOverview_vue__WEBPACK_IMPORTED_MODULE_5__["default"],
    HotelsRoomType: _components_hotel_HotelsRoomType_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
    HomePackageSlider: _components_home_HomePackageSlider_vue__WEBPACK_IMPORTED_MODULE_8__["default"],
    RelatedHotels: _components_hotel_RelatedHotels_vue__WEBPACK_IMPORTED_MODULE_6__["default"],
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_10__.Link,
    SearchFormWithBanner: _components_SearchFormWithBanner_vue__WEBPACK_IMPORTED_MODULE_12__["default"],
    HotelDetailGallerySection: _components_hotel_HotelDetailGallerySection_vue__WEBPACK_IMPORTED_MODULE_13__["default"],
    Breadcrumb: _components_Breadcrumb_vue__WEBPACK_IMPORTED_MODULE_14__["default"],
    HotelDetailPageWrapper: _styles_HotelDetailPageWrapper__WEBPACK_IMPORTED_MODULE_15__.HotelDetailPageWrapper
  },
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_2__["default"],
  props: ["search_data", "seo_data", "breadcrumbData", "accommodation", "accomodation_info", "checkin", "checkout", "inventory", "adult", "child", "infant", "relatedAccommodations", "destination", "faq_row"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/hotels/Listing.vue?vue&type=script&lang=js":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/hotels/Listing.vue?vue&type=script&lang=js ***!
  \******************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_SearchForm_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/SearchForm.vue */ "./resources/js/themes/andamanisland/components/SearchForm.vue");
/* harmony import */ var _skeletons_oneWayPageLoader_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../skeletons/oneWayPageLoader.vue */ "./resources/js/themes/andamanisland/skeletons/oneWayPageLoader.vue");
/* harmony import */ var _components_hotel_HotelsleftFilter_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/hotel/HotelsleftFilter.vue */ "./resources/js/themes/andamanisland/components/hotel/HotelsleftFilter.vue");
/* harmony import */ var _components_hotel_HotelsCardlist_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../components/hotel/HotelsCardlist.vue */ "./resources/js/themes/andamanisland/components/hotel/HotelsCardlist.vue");
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var _components_Pagination_vue__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../components/Pagination.vue */ "./resources/js/themes/andamanisland/components/Pagination.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var vue_star_rating__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! vue-star-rating */ "./node_modules/vue-star-rating/dist/VueStarRating.common.js");
/* harmony import */ var vue_star_rating__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(vue_star_rating__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _components_SearchFormWithBanner_vue__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../components/SearchFormWithBanner.vue */ "./resources/js/themes/andamanisland/components/SearchFormWithBanner.vue");
/* harmony import */ var _styles_HotelListingStyle__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../styles/HotelListingStyle */ "./resources/js/themes/andamanisland/styles/HotelListingStyle.js");
/* harmony import */ var lottie_vuejs_src_LottieAnimation_vue__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! lottie-vuejs/src/LottieAnimation.vue */ "./node_modules/lottie-vuejs/src/LottieAnimation.vue");













/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  created: function created() {
    _store__WEBPACK_IMPORTED_MODULE_6__.store.searchData = this.search_data;
    _store__WEBPACK_IMPORTED_MODULE_6__.store.seoData = this.seo_data;
    this.loadAccommodationData();
  },
  beforeUnmount: function beforeUnmount() {
    window.removeEventListener('scroll', this.handleScroll);
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_6__.store,
      allHotelList: {
        "data": [],
        "links": ""
      },
      hotelList: {
        "data": [],
        "links": ""
      },
      total_links: 0,
      categories_arr: [],
      isLoading: false,
      isScrolled: false,
      filterOpened: false,
      collapsed: false
    };
  },
  methods: {
    handleScroll: function handleScroll(event) {
      var currentObj = this;
      if (!this.isScrolled) {
        currentObj.isScrolled = true;
        setTimeout(function () {
          currentObj.hotelList = currentObj.allHotelList;
        }, 200);
      }
    },
    checkedFunction: function checkedFunction(module_name, value) {
      var checked = false;
      var checkedArr = [];
      if (_store__WEBPACK_IMPORTED_MODULE_6__.store.searchData) {
        if (module_name == 'features') {
          checkedArr = _store__WEBPACK_IMPORTED_MODULE_6__.store.searchData.features;
        } else if (module_name == 'categories') {
          checkedArr = _store__WEBPACK_IMPORTED_MODULE_6__.store.searchData.categories;
        } else if (module_name == 'destinations') {
          checkedArr = _store__WEBPACK_IMPORTED_MODULE_6__.store.searchData.destinations;
        } else if (module_name == 'stars') {
          checkedArr = _store__WEBPACK_IMPORTED_MODULE_6__.store.searchData.stars;
        } else if (module_name == 'class') {
          checkedArr = _store__WEBPACK_IMPORTED_MODULE_6__.store.searchData["class"];
        } else if (module_name == 'sort_by_price') {
          checkedArr = _store__WEBPACK_IMPORTED_MODULE_6__.store.searchData.sort_by_price;
        }
        if (checkedArr) {
          if (checkedArr.indexOf(String(value)) !== -1) {
            checked = true;
          }
        }
      }
      return checked;
    },
    handleClickfunction: function handleClickfunction(e) {
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
    loadAccommodationData: function loadAccommodationData() {
      this.isLoading = true;
      var currentObj = this;
      window.removeEventListener('scroll', currentObj.handleScroll);
      var type = 'Package';
      var form_data = _store__WEBPACK_IMPORTED_MODULE_6__.store === null || _store__WEBPACK_IMPORTED_MODULE_6__.store === void 0 ? void 0 : _store__WEBPACK_IMPORTED_MODULE_6__.store.searchData;
      form_data['type'] = type;
      axios__WEBPACK_IMPORTED_MODULE_9___default().post('/hotel/ajaxAccommodationList', form_data).then(function (response) {
        currentObj.isLoading = false;
        window.addEventListener('scroll', currentObj.handleScroll);
        if (response.data.success) {
          var _response$data, _response$data2, _response$data3;
          currentObj.allHotelList = (_response$data = response.data) === null || _response$data === void 0 ? void 0 : _response$data.hotelList;
          var hotelList = (_response$data2 = response.data) === null || _response$data2 === void 0 ? void 0 : _response$data2.hotelList;
          if (hotelList && hotelList.data && hotelList.data.length > 5) {
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
          currentObj.total_count = (_response$data3 = response.data) === null || _response$data3 === void 0 ? void 0 : _response$data3.total_count;
        } else {
          alert('Something went wrong, please try again.');
        }
        // currentObj.processing = false;
        // console.log('running in then');
      });
    },
    handleCheckboxChange: function handleCheckboxChange(e, name) {
      this.loading = true;
      var searchData = _store__WEBPACK_IMPORTED_MODULE_6__.store.searchData;
      var name_arr = [];
      if (searchData[name]) {
        name_arr = searchData[name];
      }
      if (e.target.checked) {
        name_arr.push(e.target.value);
      } else {
        var index = name_arr.indexOf(e.target.value);
        if (index > -1) {
          // only splice array when item is found
          name_arr.splice(index, 1); // 2nd parameter means remove one item only
        }
      }
      _store__WEBPACK_IMPORTED_MODULE_6__.store.searchData[name] = name_arr;
      setTimeout(this.filterSearchResult, 300);
    },
    filterSearchResult: function filterSearchResult() {
      var _store$websiteSetting;
      var form_data = _store__WEBPACK_IMPORTED_MODULE_6__.store.searchData;
      this.$inertia.get((_store$websiteSetting = _store__WEBPACK_IMPORTED_MODULE_6__.store.websiteSettings) === null || _store$websiteSetting === void 0 ? void 0 : _store$websiteSetting.HOTEL_URL, form_data);
    },
    handleFormSubmit: function handleFormSubmit(e) {

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
    goback: function goback() {
      _store__WEBPACK_IMPORTED_MODULE_6__.store.loadingName = "searchForm";
      window.history.back();
    }
  },
  mounted: function mounted() {},
  beforeDestroy: function beforeDestroy() {},
  watch: {
    filterOpened: {
      handler: function handler(filterOpened) {
        if (filterOpened) {
          document.body.classList.add('sidebar_opened');
        } else {
          document.body.classList.remove('sidebar_opened');
        }
      }
    }
  },
  components: {
    SearchForm: _components_SearchForm_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    HotelsleftFilter: _components_hotel_HotelsleftFilter_vue__WEBPACK_IMPORTED_MODULE_2__["default"],
    HotelsCardlist: _components_hotel_HotelsCardlist_vue__WEBPACK_IMPORTED_MODULE_3__["default"],
    Pagination: _components_Pagination_vue__WEBPACK_IMPORTED_MODULE_5__["default"],
    StarRating: (vue_star_rating__WEBPACK_IMPORTED_MODULE_7___default()),
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_8__.Link,
    oneWayPageLoader: _skeletons_oneWayPageLoader_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
    SearchFormWithBanner: _components_SearchFormWithBanner_vue__WEBPACK_IMPORTED_MODULE_10__["default"],
    ListingWrapper: _styles_HotelListingStyle__WEBPACK_IMPORTED_MODULE_11__.ListingWrapper,
    LottieAnimation: lottie_vuejs_src_LottieAnimation_vue__WEBPACK_IMPORTED_MODULE_12__["default"]
  },
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_4__["default"],
  props: ["seo_data", "search_data", "checkin", "checkout", "adult", "child", "infant", "categories", "features"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/packages/Details.vue?vue&type=script&lang=js":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/packages/Details.vue?vue&type=script&lang=js ***!
  \********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_SearchForm_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/SearchForm.vue */ "./resources/js/themes/andamanisland/components/SearchForm.vue");
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _components_FancyboxWrapper_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../components/FancyboxWrapper.vue */ "./resources/js/themes/andamanisland/components/FancyboxWrapper.vue");
/* harmony import */ var _components_formShortCode_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../components/formShortCode.vue */ "./resources/js/themes/andamanisland/components/formShortCode.vue");
/* harmony import */ var _components_popup_vue__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../components/popup.vue */ "./resources/js/themes/andamanisland/components/popup.vue");
/* harmony import */ var _components_package_PackageRightPrice_vue__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../components/package/PackageRightPrice.vue */ "./resources/js/themes/andamanisland/components/package/PackageRightPrice.vue");
/* harmony import */ var _components_package_PackageAccommodation_vue__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../components/package/PackageAccommodation.vue */ "./resources/js/themes/andamanisland/components/package/PackageAccommodation.vue");
/* harmony import */ var _utils_commonFuntions__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../utils/commonFuntions */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
/* harmony import */ var _components_testimonial_testimonialSlider_vue__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../components/testimonial/testimonialSlider.vue */ "./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue");
/* harmony import */ var _components_SearchFormWithBanner_vue__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../components/SearchFormWithBanner.vue */ "./resources/js/themes/andamanisland/components/SearchFormWithBanner.vue");
/* harmony import */ var _detailStyles__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./detailStyles */ "./resources/js/themes/andamanisland/packages/detailStyles.js");
/* harmony import */ var _components_SliderSection_vue__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../components/SliderSection.vue */ "./resources/js/themes/andamanisland/components/SliderSection.vue");
/* harmony import */ var vue3_styled_components__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! vue3-styled-components */ "./node_modules/vue3-styled-components/dist/vue-styled-components.es.js");
/* harmony import */ var _components_package_PackageImageGallery_vue__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ../components/package/PackageImageGallery.vue */ "./resources/js/themes/andamanisland/components/package/PackageImageGallery.vue");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_15___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_15__);
var _templateObject, _templateObject2;
function _taggedTemplateLiteral(e, t) { return t || (t = e.slice(0)), Object.freeze(Object.defineProperties(e, { raw: { value: Object.freeze(t) } })); }
















var DestinationWrapper = vue3_styled_components__WEBPACK_IMPORTED_MODULE_13__["default"].section(_templateObject || (_templateObject = _taggedTemplateLiteral(["\n    /* background-image: url(../images/vision-bg.jpg);\n    background-size: cover; \n    padding-bottom: 3rem;*/\n    padding-top: 1rem;\n\n    @media (max-width: 767px){\n        padding-bottom: 0;\n    }\n"])));
var DetailGalleryWrapper = vue3_styled_components__WEBPACK_IMPORTED_MODULE_13__["default"].section(_templateObject2 || (_templateObject2 = _taggedTemplateLiteral(["\nmargin-top:5rem;\nposition: relative;\n&:before{\n    position: absolute;\n    height: 6.25rem;\n    width: 100%;\n    left: 0;\n    top: 0;\n    content: \"\";\n    z-index: 2;\n    background-image: linear-gradient(to bottom, #fff 0%, rgba(0,0,0,0) 100%);\n}\n"])));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  created: function created() {
    document.body.classList.add('package-detail-page');
    document.body.classList.add('holiday_package');
    _store__WEBPACK_IMPORTED_MODULE_2__.store.searchData = this.search_data;
    _store__WEBPACK_IMPORTED_MODULE_2__.store.seoData = this.seo_data;
    this.package_itenaries = this.itenaries;
    //this.package_total_price = this.packageSelectedPrice?.final_amount;
    //this.package_booking_price = this.packageSelectedPrice?.booking_price;
    console.log('similarPackages==', this.similarPackages);
    // console.log('package_inclusions==', this.package_inclusions);

    var newSectionData = this.destinations;
    newSectionData.forEach(function (item) {
      return item.thumbSrc = item.image;
    });
    this.destinationsSectionData = {
      data: newSectionData
    };
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_2__.store,
      package_itenaries: [],
      package_booking_price: 0,
      package_total_price: 0,
      itenaryPopup: false,
      calenderPop: false,
      readMore: false,
      destinationsSectionData: {}
    };
  },
  methods: {
    packagePolicy: function packagePolicy() {
      var package_policy = false;
      var packageData = this["package"];
      if (packageData.inclusions || packageData.exclusion || packageData.payment_policy || packageData.cancellation_policy || packageData.terms_conditions || packageData.PackageInfo && packageData.PackageInfo.length > 0 || this.faq_row && this.faq_row.length > 0 || packageData.video_link) {
        package_policy = true;
      }
      return package_policy;
    },
    handleReadMore: function handleReadMore() {
      this.readMore = !this.readMore;
    },
    showItenaryPopup: function showItenaryPopup() {
      var currentObj = this;
      currentObj.processing = true;
      currentObj.errors = {};
      var formData = new FormData($('#bookNowForm')[0]);
      if (this["package"].package_tour_type == 'open') {
        formData.append('is_download_itinerary', 1);
      }
      axios__WEBPACK_IMPORTED_MODULE_15___default().post('/book_now', formData).then(function (response) {
        currentObj.processing = false;
        if (response.data.success) {
          // window.location.href = response.data.redirect_url;
          // currentObj.$inertia.get(response.data.redirect_url);
          _store__WEBPACK_IMPORTED_MODULE_2__.store.popupType = 'simple';
          currentObj.itenaryPopup = true;
          currentObj.calenderPop = false;
          (0,_utils_commonFuntions__WEBPACK_IMPORTED_MODULE_8__.showPopup)();
        } else {
          alert('Something went wrong, please try again.');
        }
      })["catch"](function (e) {
        var response = e.response.data;
        if (response) {
          currentObj.parseBookingErrorMessages(response, 'myForm', 'Book Online');
        }
      });
    },
    showCalenderPopup: function showCalenderPopup() {
      this.calenderPop = true;
      this.itenaryPopup = false;
      _store__WEBPACK_IMPORTED_MODULE_2__.store.popupType = 'simple';
      (0,_utils_commonFuntions__WEBPACK_IMPORTED_MODULE_8__.showPopup)();
    },
    hideCalenderPopup: function hideCalenderPopup() {
      this.calenderPop = false;
      this.itenaryPopup = false;
      (0,_utils_commonFuntions__WEBPACK_IMPORTED_MODULE_8__.hidePopup)();
    }
  },
  mounted: function mounted() {
    // ====== REMOVE SPACE IN P TAG =======
    var faqData = document.querySelectorAll('.faq_data p');
    faqData.forEach(function (element) {
      var innerText = element.textContent.trim();
      if (innerText === '') {
        element.style.display = 'none';
      }
    });
  },
  beforeUnmount: function beforeUnmount() {
    document.body.classList.remove('package-detail-page');
    document.body.classList.remove('holiday_package');
  },
  beforeDestroy: function beforeDestroy() {},
  watch: {},
  components: {
    SearchForm: _components_SearchForm_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    FancyboxWrapper: _components_FancyboxWrapper_vue__WEBPACK_IMPORTED_MODULE_3__["default"],
    Popup: _components_popup_vue__WEBPACK_IMPORTED_MODULE_5__["default"],
    PackageRightPrice: _components_package_PackageRightPrice_vue__WEBPACK_IMPORTED_MODULE_6__["default"],
    PackageAccommodation: _components_package_PackageAccommodation_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
    formShortCode: _components_formShortCode_vue__WEBPACK_IMPORTED_MODULE_4__["default"],
    testimonialSlider: _components_testimonial_testimonialSlider_vue__WEBPACK_IMPORTED_MODULE_9__["default"],
    SearchFormWithBanner: _components_SearchFormWithBanner_vue__WEBPACK_IMPORTED_MODULE_10__["default"],
    SinglePackageWrapper: _detailStyles__WEBPACK_IMPORTED_MODULE_11__.SinglePackageWrapper,
    SliderSection: _components_SliderSection_vue__WEBPACK_IMPORTED_MODULE_12__["default"],
    DestinationWrapper: DestinationWrapper,
    PackageImageSlider: _components_package_PackageImageGallery_vue__WEBPACK_IMPORTED_MODULE_14__["default"],
    DetailGalleryWrapper: DetailGalleryWrapper
  },
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
  props: ["search_data", "seo_data", "package", "default_price_id", "faq_row", "destinations", "itenaries", "sharing_links", "testimonials", "similarPackages"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/hotels/Details.vue?vue&type=template&id=56ab7d1a":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/hotels/Details.vue?vue&type=template&id=56ab7d1a ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "container text-right pt-5 pb-3"
};
var _hoisted_2 = {
  "class": "pt-0 pb-0"
};
var _hoisted_3 = {
  "class": "container"
};
var _hoisted_4 = {
  "class": "flex flex-wrap"
};
var _hoisted_5 = {
  "class": "left_side w-full"
};
var _hoisted_6 = {
  "class": "left_side_inner"
};
var _hoisted_7 = {
  key: 1,
  "class": "flex-wrap flex bg-white mt-5 mb-5 w-full",
  id: "content_location"
};
var _hoisted_8 = {
  "class": "tablediv w-full"
};
var _hoisted_9 = {
  "class": "map-detail"
};
var _hoisted_10 = ["src"];
var _hoisted_11 = {
  "class": "pt-0 pb-0"
};
var _hoisted_12 = {
  "class": "container"
};
var _hoisted_13 = {
  "class": "flex-wrap"
};
var _hoisted_14 = {
  "class": "accommodation accordion pt-5"
};
var _hoisted_15 = {
  "class": "container1"
};
var _hoisted_16 = {
  "class": "faqlist"
};
var _hoisted_17 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("br", null, null, -1 /* HOISTED */);
var _hoisted_18 = {
  "class": "theme_title mb-3"
};
var _hoisted_19 = {
  "class": "text-xl font-bold"
};
var _hoisted_20 = {
  "class": "faq_main"
};
var _hoisted_21 = {
  "class": "faq_title heading6"
};
var _hoisted_22 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("strong", null, "Q ", -1 /* HOISTED */);
var _hoisted_23 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-solid fa-angle-down float-right"
}, null, -1 /* HOISTED */);
var _hoisted_24 = {
  "class": "faq_data",
  style: {}
};
var _hoisted_25 = ["innerHTML"];
var _hoisted_26 = {
  key: 0,
  "class": "hotelpolicy",
  id: "content_policies"
};
var _hoisted_27 = {
  "class": "container"
};
var _hoisted_28 = {
  "class": "hotelpolicy_row"
};
var _hoisted_29 = {
  "class": "border border-slate-20 p-3"
};
var _hoisted_30 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "title text-2xl"
}, "Property Policies", -1 /* HOISTED */);
var _hoisted_31 = ["innerHTML"];
var _hoisted_32 = {
  "class": "home_bg"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_SearchFormWithBanner = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("SearchFormWithBanner");
  var _component_Breadcrumb = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Breadcrumb");
  var _component_HotelDetailGallerySection = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HotelDetailGallerySection");
  var _component_HotelsDetailsOverview = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HotelsDetailsOverview");
  var _component_HotelsRoomType = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HotelsRoomType");
  var _component_RelatedHotels = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("RelatedHotels");
  var _component_HomePackageSlider = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HomePackageSlider");
  var _component_FancyboxWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("FancyboxWrapper");
  var _component_HotelDetailPageWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HotelDetailPageWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_SearchFormWithBanner, {
    type: "hotel"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Breadcrumb, {
    data: $props.breadcrumbData
  }, null, 8 /* PROPS */, ["data"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HotelDetailGallerySection, {
    accommodation: $props.accommodation
  }, null, 8 /* PROPS */, ["accommodation"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HotelDetailPageWrapper, null, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_FancyboxWrapper, null, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          var _$data$store$websiteS, _$data$store$websiteS2;
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("section", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_6, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HotelsDetailsOverview, {
            accommodation: $props.accommodation,
            accomodation_info: $props.accomodation_info
          }, null, 8 /* PROPS */, ["accommodation", "accomodation_info"]), $props.accommodation.accommodationRooms && $props.accommodation.accommodationRooms.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_HotelsRoomType, {
            key: 0,
            accommodation: $props.accommodation,
            checkin: $props.checkin,
            checkout: $props.checkout,
            inventory: $props.inventory,
            adult: $props.adult,
            child: $props.child,
            infant: $props.infant
          }, null, 8 /* PROPS */, ["accommodation", "checkin", "checkout", "inventory", "adult", "child", "infant"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props.accommodation.map_src ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("iframe", {
            src: $props.accommodation.map_src,
            style: {
              "border": "0"
            },
            allowfullscreen: "",
            width: "100%",
            height: "390",
            frameborder: "0"
          }, null, 8 /* PROPS */, _hoisted_10)])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("section", _hoisted_11, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_13, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_16, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Frequently Asked Questions "), $props.faq_row && $props.faq_row.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
            key: 0
          }, [_hoisted_17, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_18, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", _hoisted_19, "FAQs About " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props.accommodation.name), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", null, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.faq_row, function (faq) {
            return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_20, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_21, [_hoisted_22, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)((0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(faq.question) + " ", 1 /* TEXT */), _hoisted_23]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_24, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", {
              innerHTML: faq.answer
            }, null, 8 /* PROPS */, _hoisted_25)])])]);
          }), 256 /* UNKEYED_FRAGMENT */))])], 64 /* STABLE_FRAGMENT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Frequently Asked Questions Closed ")])])])])])]), $props.accommodation.policies ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("section", _hoisted_26, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_27, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_28, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_29, [_hoisted_30, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
            "class": "text-sm pt-2",
            innerHTML: $props.accommodation.policies
          }, null, 8 /* PROPS */, _hoisted_31)])])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props.relatedAccommodations[0] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_RelatedHotels, {
            key: 1,
            relatedAccommodations: $props.relatedAccommodations,
            destination: $props.destination
          }, null, 8 /* PROPS */, ["relatedAccommodations", "destination"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_32, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HomePackageSlider, {
            isActivity: 1,
            featured: 1,
            limit: 10,
            title: "Romantic Candle Light Dinner In Andaman Islands",
            subTitle: "Make your evening unique and unforgettable with your loved one by having a romantic candlelight meal on Havelock Island's gorgeous beaches.",
            viewAllUrl: "".concat((_$data$store$websiteS = $data.store.websiteSettings) === null || _$data$store$websiteS === void 0 ? void 0 : _$data$store$websiteS.TOUR_CATEGORY_DETAIL_URL, "/romantic-candle-light-dinner-in-andaman-islands"),
            viewAllTitle: "View All",
            itemsPerView: 4,
            themeCategorySlug: "romantic-candle-light-dinner-in-andaman-islands"
          }, null, 8 /* PROPS */, ["viewAllUrl"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HomePackageSlider, {
            isActivity: 1,
            featured: 1,
            limit: 10,
            title: "Andaman Water Activities Tour Packages",
            subTitle: "Choose from a wide range of economical Andaman tour packages & enjoy the beautiful state of sun, sand & sea.",
            viewAllUrl: "".concat((_$data$store$websiteS2 = $data.store.websiteSettings) === null || _$data$store$websiteS2 === void 0 ? void 0 : _$data$store$websiteS2.TOUR_CATEGORY_DETAIL_URL, "/water-sports-in-andaman-islands"),
            viewAllTitle: "View All",
            itemsPerView: 4,
            themeCategorySlug: "water-sports-in-andaman-islands"
          }, null, 8 /* PROPS */, ["viewAllUrl"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <HomePackageSlider :isActivity=\"1\" :featured=\"1\" :limit=\"10\" title=\"Andaman Water Sports\" :viewAllUrl=\"`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/andaman-water-sports`\" viewAllTitle=\"View All\" :itemsPerView=\"4\" themeCategorySlug=\"water-sports-in-andaman-islands\" /> ")])];
        }),
        _: 1 /* STABLE */
      })];
    }),
    _: 1 /* STABLE */
  })], 64 /* STABLE_FRAGMENT */);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/hotels/Listing.vue?vue&type=template&id=712818b5":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/hotels/Listing.vue?vue&type=template&id=712818b5 ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "container"
};
var _hoisted_2 = {
  "class": "hotel_wrap_inner"
};
var _hoisted_3 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-solid fa-xmark"
}, null, -1 /* HOISTED */);
var _hoisted_4 = [_hoisted_3];
var _hoisted_5 = {
  "class": "side_bar"
};
var _hoisted_6 = {
  id: "adv_hotel_search",
  name: "adv_hotel_search"
};
var _hoisted_7 = {
  "class": "filter_box"
};
var _hoisted_8 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "sidetitle text-base font-semibold mx-3"
}, "Property types", -1 /* HOISTED */);
var _hoisted_9 = ["id", "value", "checked"];
var _hoisted_10 = ["for"];
var _hoisted_11 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("br", null, null, -1 /* HOISTED */);
var _hoisted_12 = {
  "class": "filter_box"
};
var _hoisted_13 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "sidetitle text-base font-semibold mx-3"
}, "Amenities", -1 /* HOISTED */);
var _hoisted_14 = ["id", "value", "checked"];
var _hoisted_15 = ["for"];
var _hoisted_16 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("br", null, null, -1 /* HOISTED */);
var _hoisted_17 = {
  "class": "filter_box"
};
var _hoisted_18 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "sidetitle text-base font-semibold mx-3"
}, "Hotel Class", -1 /* HOISTED */);
var _hoisted_19 = {
  "class": "checkbox_list px-3 flex"
};
var _hoisted_20 = ["id", "value", "checked"];
var _hoisted_21 = ["for"];
var _hoisted_22 = ["rating"];
var _hoisted_23 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("br", null, null, -1 /* HOISTED */);
var _hoisted_24 = {
  "class": "filter_box"
};
var _hoisted_25 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "sidetitle text-base font-semibold mx-3"
}, "Traveller Rating", -1 /* HOISTED */);
var _hoisted_26 = {
  "class": "checkbox_list px-3"
};
var _hoisted_27 = ["id", "value", "checked"];
var _hoisted_28 = ["for"];
var _hoisted_29 = {
  "class": "filter_button"
};
var _hoisted_30 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-solid fa-filter"
}, null, -1 /* HOISTED */);
var _hoisted_31 = [_hoisted_30];
var _hoisted_32 = {
  "class": "hotel_view"
};
var _hoisted_33 = ["innerHTML"];
var _hoisted_34 = {
  "class": "description_inner"
};
var _hoisted_35 = ["innerHTML"];
var _hoisted_36 = ["innerHTML"];
var _hoisted_37 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-solid fa-arrow-down"
}, null, -1 /* HOISTED */);
var _hoisted_38 = {
  key: 2,
  "class": "p-0"
};
var _hoisted_39 = {
  key: 1,
  "class": "alert_not_found"
};
var _hoisted_40 = ["href"];
var _hoisted_41 = ["href"];
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_SearchFormWithBanner = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("SearchFormWithBanner");
  var _component_StarRating = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("StarRating");
  var _component_Link = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Link");
  var _component_LottieAnimation = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("LottieAnimation");
  var _component_oneWayPageLoader = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("oneWayPageLoader");
  var _component_HotelsCardlist = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HotelsCardlist");
  var _component_Pagination = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Pagination");
  var _component_ListingWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ListingWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_SearchFormWithBanner, {
    type: "hotel"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ListingWrapper, {
    "class": "hotel_wrap p_list_view"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      var _$data$store$websiteS, _$data$store$seoData, _$data$store$seoData2, _$data$store$seoData3, _$data$store$seoData4, _$data$store$seoData5, _$data$store$seoData6, _this$hotelList, _$data$store$websiteS2, _$data$store$websiteS3, _$data$store$websiteS4, _$data$store$websiteS5;
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
        "class": "close_filter_btn",
        onClick: _cache[0] || (_cache[0] = function ($event) {
          return _this.filterOpened = false;
        })
      }, [].concat(_hoisted_4)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", _hoisted_6, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [_hoisted_8, ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.categories, function (category) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
          "class": "checkbox_list px-3",
          key: category.id
        }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
          type: "checkbox",
          id: "category_".concat(category.id),
          name: "categories[]",
          value: category.id,
          checked: _this.checkedFunction('categories', category.id),
          onChange: _cache[1] || (_cache[1] = function ($event) {
            return $options.handleCheckboxChange($event, 'categories');
          })
        }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_9), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
          "for": "category_".concat(category.id)
        }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(category.title), 1 /* TEXT */)], 8 /* PROPS */, _hoisted_10), _hoisted_11]);
      }), 128 /* KEYED_FRAGMENT */))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [_hoisted_13, ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.features, function (feature) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
          "class": "checkbox_list px-3",
          key: feature.id
        }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
          type: "checkbox",
          id: "feature_".concat(feature.id),
          name: "features[]",
          value: feature.id,
          checked: _this.checkedFunction('features', feature.id),
          onChange: _cache[2] || (_cache[2] = function ($event) {
            return $options.handleCheckboxChange($event, 'features');
          })
        }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_14), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
          "for": "feature_".concat(feature.id)
        }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(feature.title), 1 /* TEXT */)], 8 /* PROPS */, _hoisted_15), _hoisted_16]);
      }), 128 /* KEYED_FRAGMENT */))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_17, [_hoisted_18, ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)([5, 4, 3, 2, 1], function (hotel_class) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_19, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
          type: "checkbox",
          id: "class_".concat(hotel_class),
          value: hotel_class,
          name: "class[]",
          checked: _this.checkedFunction('class', hotel_class),
          onChange: _cache[3] || (_cache[3] = function ($event) {
            return $options.handleCheckboxChange($event, 'class');
          })
        }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_20), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
          "for": "class_".concat(hotel_class),
          "class": "flex items-center"
        }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", {
          "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(["rating_list py-1", 'test' + _ctx.star]),
          rating: hotel_class
        }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_StarRating, {
          rating: hotel_class,
          "read-only": "",
          "show-rating": false
        }, null, 8 /* PROPS */, ["rating"])], 10 /* CLASS, PROPS */, _hoisted_22)], 8 /* PROPS */, _hoisted_21), _hoisted_23]);
      }), 64 /* STABLE_FRAGMENT */))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_24, [_hoisted_25, ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)([10, 9, 8, 7, 6, 5, 4], function (star) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_26, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
          type: "checkbox",
          id: "star_rating".concat(star),
          name: "stars[]",
          value: star,
          checked: _this.checkedFunction('stars', star),
          onChange: _cache[4] || (_cache[4] = function ($event) {
            return $options.handleCheckboxChange($event, 'stars');
          })
        }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_27), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
          "for": "star_rating".concat(star)
        }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(star), 1 /* TEXT */)], 8 /* PROPS */, _hoisted_28)]);
      }), 64 /* STABLE_FRAGMENT */))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", _hoisted_29, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <li><button type=\"submit\" class=\"btn\">Apply</button></li> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("li", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
        href: (_$data$store$websiteS = $data.store.websiteSettings) === null || _$data$store$websiteS === void 0 ? void 0 : _$data$store$websiteS.HOTEL_URL,
        "class": "btn2",
        onClick: _cache[5] || (_cache[5] = function ($event) {
          return _this.filterOpened = false;
        })
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Clear")];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["href"])])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
        "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(["pageLoader", _ctx.loading ? 'active' : ''])
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_LottieAnimation, {
        path: "../assets/lottieFiles/loader.json",
        loop: true,
        autoPlay: true,
        speed: 1,
        width: 256,
        height: 256
      })], 2 /* CLASS */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
        "class": "open_filter_btn",
        onClick: _cache[6] || (_cache[6] = function ($event) {
          return _this.filterOpened = true;
        })
      }, [].concat(_hoisted_31)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
        "class": "filter_backdrop",
        onClick: _cache[7] || (_cache[7] = function ($event) {
          return _this.filterOpened = false;
        })
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_32, [(_$data$store$seoData = $data.store.seoData) !== null && _$data$store$seoData !== void 0 && _$data$store$seoData.page_title ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("h1", {
        key: 0,
        "class": "title text-2xl mb-3 color_theme fw600",
        innerHTML: (_$data$store$seoData2 = $data.store.seoData) === null || _$data$store$seoData2 === void 0 ? void 0 : _$data$store$seoData2.page_title
      }, null, 8 /* PROPS */, _hoisted_33)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (_$data$store$seoData3 = $data.store.seoData) !== null && _$data$store$seoData3 !== void 0 && _$data$store$seoData3.page_brief || (_$data$store$seoData4 = $data.store.seoData) !== null && _$data$store$seoData4 !== void 0 && _$data$store$seoData4.page_description ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
        key: 1,
        id: "disc-title",
        "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(["text-left mb-5", $data.collapsed ? 'collapsed' : ''])
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_34, [(_$data$store$seoData5 = $data.store.seoData) !== null && _$data$store$seoData5 !== void 0 && _$data$store$seoData5.page_brief ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
        key: 0,
        "class": "text-sm",
        innerHTML: $data.store.seoData.page_brief
      }, null, 8 /* PROPS */, _hoisted_35)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (_$data$store$seoData6 = $data.store.seoData) !== null && _$data$store$seoData6 !== void 0 && _$data$store$seoData6.page_description ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
        key: 1,
        "class": "text-sm",
        innerHTML: $data.store.seoData.page_description
      }, null, 8 /* PROPS */, _hoisted_36)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
        "class": "read_option",
        onClick: _cache[8] || (_cache[8] = function ($event) {
          return $data.collapsed = !$data.collapsed;
        })
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)((0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.collapsed ? 'Hide Info' : 'More Info') + " ", 1 /* TEXT */), _hoisted_37])], 2 /* CLASS */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), _this.isLoading ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("section", _hoisted_38, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_oneWayPageLoader)])) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
        key: 3
      }, [_this.hotelList.data && _this.hotelList.data.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
        key: 0
      }, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)(_this.hotelList.data, function (accommodation) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
          "class": "hotel-card mb-5",
          key: accommodation.id
        }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HotelsCardlist, {
          accommodation: accommodation,
          checkin: $props.checkin,
          checkout: $props.checkout,
          adult: $props.adult,
          child: $props.child,
          infant: $props.infant
        }, null, 8 /* PROPS */, ["accommodation", "checkin", "checkout", "adult", "child", "infant"])]);
      }), 128 /* KEYED_FRAGMENT */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Pagination, {
        links: (_this$hotelList = _this.hotelList) === null || _this$hotelList === void 0 ? void 0 : _this$hotelList.links
      }, null, 8 /* PROPS */, ["links"])], 64 /* STABLE_FRAGMENT */)) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_39, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("No hotel(s) found matching your search criteria. Please look for an alternate hotel or call our travel experts at "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: "tel:".concat((_$data$store$websiteS2 = $data.store.websiteSettings) === null || _$data$store$websiteS2 === void 0 ? void 0 : _$data$store$websiteS2.BOOKING_QUERIES_NO)
      }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)((_$data$store$websiteS3 = $data.store.websiteSettings) === null || _$data$store$websiteS3 === void 0 ? void 0 : _$data$store$websiteS3.BOOKING_QUERIES_NO) + ".", 9 /* TEXT, PROPS */, _hoisted_40), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" You may also drop us an email at "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: "mailto:".concat((_$data$store$websiteS4 = $data.store.websiteSettings) === null || _$data$store$websiteS4 === void 0 ? void 0 : _$data$store$websiteS4.SALES_EMAIL)
      }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)((_$data$store$websiteS5 = $data.store.websiteSettings) === null || _$data$store$websiteS5 === void 0 ? void 0 : _$data$store$websiteS5.SALES_EMAIL), 9 /* TEXT, PROPS */, _hoisted_41)]))], 64 /* STABLE_FRAGMENT */))])])])];
    }),
    _: 1 /* STABLE */
  })], 64 /* STABLE_FRAGMENT */);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/packages/Details.vue?vue&type=template&id=36048de1":
/*!************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/packages/Details.vue?vue&type=template&id=36048de1 ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");
/* harmony import */ var _assets_images_flight_gif__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../assets/images/flight.gif */ "./resources/js/themes/andamanisland/assets/images/flight.gif");


var _hoisted_1 = {
  "class": "w-full overflow-hidden"
};
var _hoisted_2 = {
  "class": "row"
};
var _hoisted_3 = {
  "class": "container"
};
var _hoisted_4 = {
  "class": "flex flex-wrap pt-16 flex_package right_side"
};
var _hoisted_5 = {
  "class": "w-4/6 pr-9 mo_full"
};
var _hoisted_6 = {
  "class": "download_it flex justify-end"
};
var _hoisted_7 = {
  "class": "py-3.5 px-5 border border-gray-300 rounded"
};
var _hoisted_8 = {
  "class": "theme_title py-3"
};
var _hoisted_9 = {
  "class": "title text-2xl"
};
var _hoisted_10 = {
  "class": "day_night_detail"
};
var _hoisted_11 = {
  "class": "day_night text-base"
};
var _hoisted_12 = {
  "class": "city-list-block"
};
var _hoisted_13 = {
  "class": "full_field mb-3"
};
var _hoisted_14 = {
  "class": "text-sm"
};
var _hoisted_15 = {
  "class": "package_tour_type_text"
};
var _hoisted_16 = ["innerHTML"];
var _hoisted_17 = {
  "class": "left_side mb-5 pr-8 icon-wishlist relative"
};
var _hoisted_18 = {
  "class": "inclusions inclusions_list pt-2"
};
var _hoisted_19 = ["data-text"];
var _hoisted_20 = ["alt", "src"];
var _hoisted_21 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
  href: "javascript:void(0)",
  "class": "more_btn"
}, " more... ", -1 /* HOISTED */);
var _hoisted_22 = {
  key: 0,
  "class": "package_disc overview_box mt-5 mb-5 py-3.5 px-5 border border-gray-300 rounded"
};
var _hoisted_23 = {
  "class": "package_disc flex items-center mt-5 mb-5"
};
var _hoisted_24 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h2", {
  "class": "text-base font-semibold mr-3"
}, "Activities", -1 /* HOISTED */);
var _hoisted_25 = {
  "class": "list_row_right"
};
var _hoisted_26 = {
  "class": "activ_list"
};
var _hoisted_27 = ["innerHTML"];
var _hoisted_28 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "text-2xl font-semibold pb-3"
}, "Overview", -1 /* HOISTED */);
var _hoisted_29 = ["innerHTML"];
var _hoisted_30 = ["innerHTML"];
var _hoisted_31 = {
  key: 1,
  "class": "bg-slate-100 mt-3 p-4"
};
var _hoisted_32 = {
  "class": "text-xl font-semibold pt-2"
};
var _hoisted_33 = {
  "class": "para_lg2"
};
var _hoisted_34 = {
  "class": "flight_list"
};
var _hoisted_35 = {
  "class": "mt-0 rowid-24"
};
var _hoisted_36 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: _assets_images_flight_gif__WEBPACK_IMPORTED_MODULE_1__["default"],
  alt: "Air India",
  "class": "logo"
}, null, -1 /* HOISTED */);
var _hoisted_37 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa fa-long-arrow-right"
}, null, -1 /* HOISTED */);
var _hoisted_38 = {
  "class": "listSec"
};
var _hoisted_39 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("li", {
  "class": "active"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
  href: "#packageIt"
}, "Itinerary")], -1 /* HOISTED */);
var _hoisted_40 = {
  key: 0
};
var _hoisted_41 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
  href: "#package_accommodations"
}, "Accommodation", -1 /* HOISTED */);
var _hoisted_42 = [_hoisted_41];
var _hoisted_43 = {
  key: 1
};
var _hoisted_44 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
  href: "#review"
}, "Review", -1 /* HOISTED */);
var _hoisted_45 = [_hoisted_44];
var _hoisted_46 = {
  key: 2,
  id: "packageIt",
  "class": "package_day secpad py-3.5 px-5 border border-gray-300 rounded"
};
var _hoisted_47 = {
  "class": "container-full"
};
var _hoisted_48 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", {
  "class": "text-xl font-semibold pb-2"
}, "Package Itinerary", -1 /* HOISTED */);
var _hoisted_49 = {
  "class": "faqlist faqlist_in"
};
var _hoisted_50 = {
  "class": "faq_main"
};
var _hoisted_51 = {
  "class": "ml-14"
};
var _hoisted_52 = {
  "class": "faq_title"
};
var _hoisted_53 = {
  key: 0,
  "class": "theme_tags mb-3"
};
var _hoisted_54 = {
  "class": "tags mr-2"
};
var _hoisted_55 = {
  key: 1,
  "class": "pb-2"
};
var _hoisted_56 = {
  "class": "day_curcle"
};
var _hoisted_57 = {
  "class": "faq_data"
};
var _hoisted_58 = {
  key: 0,
  "class": "faq_service package_disc overview_box pb-3 mb-3"
};
var _hoisted_59 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "title2 mb-1 text-base font-semibold"
}, "Services Included", -1 /* HOISTED */);
var _hoisted_60 = {
  "class": "include_list inclusions",
  style: {
    "clear": "both",
    "display": "block",
    "width": "100%"
  }
};
var _hoisted_61 = ["data-text"];
var _hoisted_62 = ["src"];
var _hoisted_63 = {
  key: 1,
  "class": "text-lg font-semibold"
};
var _hoisted_64 = ["innerHTML"];
var _hoisted_65 = {
  key: 2,
  "class": "left-img-itinerary pt-2"
};
var _hoisted_66 = ["src", "alt"];
var _hoisted_67 = {
  key: 4,
  "class": "accommodation accordion mt-5 mb-5 pt-5 py-3.5 px-5 border border-gray-300 rounded"
};
var _hoisted_68 = {
  "class": "container1"
};
var _hoisted_69 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", {
  "class": "text-xl font-semibold mb-3"
}, "Policy", -1 /* HOISTED */);
var _hoisted_70 = {
  "class": "faqlist"
};
var _hoisted_71 = {
  key: 0,
  "class": "faq_main"
};
var _hoisted_72 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "faq_title heading6"
}, "Inclusions", -1 /* HOISTED */);
var _hoisted_73 = {
  "class": "faq_data"
};
var _hoisted_74 = ["innerHTML"];
var _hoisted_75 = {
  key: 1,
  "class": "faq_main"
};
var _hoisted_76 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "faq_title heading6"
}, "Exclusions", -1 /* HOISTED */);
var _hoisted_77 = {
  "class": "faq_data"
};
var _hoisted_78 = ["innerHTML"];
var _hoisted_79 = {
  key: 2,
  "class": "faq_main"
};
var _hoisted_80 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "faq_title heading6"
}, "Payment Policy", -1 /* HOISTED */);
var _hoisted_81 = {
  "class": "faq_data",
  style: {}
};
var _hoisted_82 = ["innerHTML"];
var _hoisted_83 = {
  key: 3,
  "class": "faq_main"
};
var _hoisted_84 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "faq_title heading6"
}, "Cancellation Policy", -1 /* HOISTED */);
var _hoisted_85 = {
  "class": "faq_data",
  style: {}
};
var _hoisted_86 = ["innerHTML"];
var _hoisted_87 = {
  key: 4,
  "class": "faq_main"
};
var _hoisted_88 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "faq_title heading6"
}, "Terms and Conditions", -1 /* HOISTED */);
var _hoisted_89 = {
  "class": "faq_data",
  style: {}
};
var _hoisted_90 = ["innerHTML"];
var _hoisted_91 = {
  key: 0,
  "class": "faq_main"
};
var _hoisted_92 = {
  "class": "faq_title heading6"
};
var _hoisted_93 = {
  "class": "faq_data",
  style: {}
};
var _hoisted_94 = ["innerHTML"];
var _hoisted_95 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("br", null, null, -1 /* HOISTED */);
var _hoisted_96 = {
  "class": "theme_title mb-3"
};
var _hoisted_97 = {
  "class": "text-xl font-bold"
};
var _hoisted_98 = {
  "class": "faq_main"
};
var _hoisted_99 = {
  "class": "faq_title heading6"
};
var _hoisted_100 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("strong", null, "Q ", -1 /* HOISTED */);
var _hoisted_101 = {
  "class": "faq_data",
  style: {}
};
var _hoisted_102 = ["innerHTML"];
var _hoisted_103 = ["innerHTML"];
var _hoisted_104 = {
  "class": "share-section mt-5 w-full bg-gray-100"
};
var _hoisted_105 = {
  "class": "flex py-0 pl-1.5 items-center"
};
var _hoisted_106 = {
  "class": "bg_share"
};
var _hoisted_107 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", {
  "class": "mr-3"
}, "Share It On:", -1 /* HOISTED */);
var _hoisted_108 = {
  "class": "footer_bottom_right"
};
var _hoisted_109 = {
  "class": "social_icon"
};
var _hoisted_110 = {
  key: 0,
  "class": "facebook"
};
var _hoisted_111 = ["href"];
var _hoisted_112 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-brands fa-facebook-f"
}, null, -1 /* HOISTED */);
var _hoisted_113 = {
  key: 1,
  "class": "twitter"
};
var _hoisted_114 = ["href"];
var _hoisted_115 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-brands fa-twitter"
}, null, -1 /* HOISTED */);
var _hoisted_116 = {
  key: 2,
  "class": "whatsapp"
};
var _hoisted_117 = ["href"];
var _hoisted_118 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-brands fa-whatsapp"
}, null, -1 /* HOISTED */);
var _hoisted_119 = {
  key: 3,
  "class": "instagram"
};
var _hoisted_120 = ["href"];
var _hoisted_121 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-brands fa-instagram"
}, null, -1 /* HOISTED */);
var _hoisted_122 = {
  key: 0,
  "class": "w-4/12 mt-5 mo_full"
};
var _hoisted_123 = {
  "class": "form_box-new single_package_form",
  id: "packageEnquire_form"
};
var _hoisted_124 = {
  "class": "form_box_inner"
};
var _hoisted_125 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", {
  "class": "text-xl font-semibold pb-2"
}, "Your Preference", -1 /* HOISTED */);
var _hoisted_126 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "title"
}, "Why Choose Us?"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", null, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("li", null, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-img"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: "https://www.andamanisland.in/assets/site1/images/icon/Experience.png",
  "class": "img-responsive",
  alt: "Experience"
})]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-para"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, "Experience"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", null, "Located in the Andaman Islands and having great love and knowledge for it, we have gained specialisation in the Andaman tourism industry. We have over 16 years of experience in the field and this makes us the best travel expert who can give you the best recommendations.")])]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("li", null, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-img"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: "https://www.andamanisland.in/assets/site1/images/icon/Tour-Packages.png",
  "class": "img-responsive",
  alt: "Tour Packages"
})]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-para"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, "Tour Packages"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", null, "Whatever your budget is, our offered customized Andamans tour packages will help you explore this beautiful destination without burning a hole in your pocket. We offer first-class services to all kinds of travellers—luxury/budget/solo/family/romantic, etc.")])]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("li", null, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-img"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: "https://www.andamanisland.in/assets/site1/images/icon/Customer-Support-24X7.png",
  "class": "img-responsive",
  alt: "Customer Support 24X7"
})]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-para"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, "Customer Support 24X7"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", null, "We aim at offering the most affordable tour packages so that everyone can have the best time at the pristine land of the Andaman Islands. Our staff comprising reservations, sales, and tour experts are locals of the Andamans. Thus, you are sure to get the best suggestions for your vacation. Moreover, our customer support service is available 24X7.")])]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("li", null, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-img"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: "https://www.andamanisland.in/assets/site1/images/icon/Offers-&-Discounts.png",
  "class": "img-responsive",
  alt: "Offers & Discounts"
})]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-para"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, "Offers & Discounts"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", null, "Holding vast expertise in promoting Andaman Island tourism, our committed representatives makes sure that you get the best deals on all your bookings. We also guarantee you the best discounts on your stay as we have tie-ups with the most amazing hotels and resorts across the Andaman Islands along with ourselves owning lavish properties as well.")])]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("li", null, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-img"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: "https://www.andamanisland.in/assets/site1/images/icon/Service-Guarantee.png",
  "class": "img-responsive",
  alt: "Service Guarantee"
})]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-para"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, "Service Guarantee"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", null, "With the excellent services, our dedicated hospitality team members make you feel completely comfortable, thereby offering you holidays of a lifetime.")])]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("li", null, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-img"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: "https://www.andamanisland.in/assets/site1/images/icon/Our-Experts.png",
  "class": "img-responsive",
  alt: "Our Experts"
})]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-para"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, "Our Experts"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", null, "The members of our operations team are local residents of the Andaman Islands. Therefore, they are proficient in handling all your queries before, during, and after the tour in the best way possible.")])]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("li", null, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-img"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: "https://www.andamanisland.in/assets/site1/images/icon/Special-Request-Services.png",
  "class": "img-responsive",
  alt: "Special Request Services"
})]), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "wcu-para"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, "Special Request Services"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", null, "When it comes to your special requests like midnight cake cutting with music and wine, candlelight dinner at the beach, champaign by the poolside, or anything else, we make sure that no stone is left unturned to give you what you have asked for.")])])])], -1 /* HOISTED */);
var _hoisted_127 = {
  "class": "modal-body download-itinerary"
};
var _hoisted_128 = {
  "class": "modal-header form-floating mb-3 w-full"
};
var _hoisted_129 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", {
  "class": "modal-title text-2xl"
}, "Enquiry Form", -1 /* HOISTED */);
var _hoisted_130 = {
  "class": "text-sm"
};
var _hoisted_131 = {
  "class": "text-teal-500 italic"
};
var _hoisted_132 = {
  "class": "book-space"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_PackageImageSlider = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("PackageImageSlider");
  var _component_FancyboxWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("FancyboxWrapper");
  var _component_DetailGalleryWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("DetailGalleryWrapper");
  var _component_PackageRightPrice = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("PackageRightPrice");
  var _component_PackageAccommodation = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("PackageAccommodation");
  var _component_testimonialSlider = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("testimonialSlider");
  var _component_formShortCode = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("formShortCode");
  var _component_SinglePackageWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("SinglePackageWrapper");
  var _component_SliderSection = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("SliderSection");
  var _component_DestinationWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("DestinationWrapper");
  var _component_Popup = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Popup");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <SearchFormWithBanner\r\n    title=\"Search For Packages\"\r\n    /> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_DetailGalleryWrapper, null, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_FancyboxWrapper, {
        className: "left_price_box"
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <PackageImageSlider :data=\"package.images\"/> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_PackageImageSlider, {
            data: $props["package"].images
          }, null, 8 /* PROPS */, ["data"])];
        }),
        _: 1 /* STABLE */
      })];
    }),
    _: 1 /* STABLE */
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_SinglePackageWrapper, {
    "class": "single_package_details py-0 mb-5"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <div class=\"row\">\r\n                <div class=\"container\">\r\n                    <div class=\"right_side\">\r\n                        <div id=\"myForm\">\r\n                            <div class=\"theme_title mt-7\">\r\n                                <h1 class=\"title text-2xl\">\r\n                                    <p class=\"day_night_detail\"> \r\n                                        <strong class=\"day_night text-base\">{{package.package_duration}}</strong>\r\n                                    </p> \r\n                                    {{package.title}}\r\n                                </h1>\r\n                            </div>\r\n                            <div class=\"form_box\">\r\n                                <div class=\"city-list-block\">\r\n                                    <ul>\r\n                                        <li class=\"full_field mb-3\">\r\n                                            <p class=\"text-sm\">\r\n                                                <span class=\"package_tour_type_text\">{{package.package_tour_type_text}}</span> \r\n                                                <template v-for=\"dnc_value in package.days_nights_city\" :key=\"dnc_value\" >\r\n                                                    <span v-html=\"dnc_value\"></span>\r\n                                                </template>\r\n                                            </p>\r\n                                        </li>\r\n                                    </ul>\r\n                                    <div id=\"element\" class=\"btn btn-default show-modal mt-5\" @click=\"showItenaryPopup\">Download Itinerary</div>\r\n                                </div>\r\n                                <div class=\"flex items-center flex-wrap justify_btwn\">\r\n                                    <PackageRightPrice  \r\n                                    :package=\"package\" \r\n                                    :packageSelectedPrice=\"packageSelectedPrice\" \r\n                                    :faq_row=\"faq_row\" \r\n                                    :destinations=\"destinations\" \r\n                                    :itenaries=\"itenaries\" \r\n                                    :calenderPop=\"this.calenderPop\"\r\n                                    :showCalenderPopup=\"this.showCalenderPopup\"\r\n                                    :hideCalenderPopup=\"this.hideCalenderPopup\"\r\n                                    />\r\n\r\n                                    <FancyboxWrapper className=\"left_price_box\">\r\n                                        <PackageImageSlider :data=\"package.images\"/>\r\n                                    </FancyboxWrapper>\r\n\r\n                                </div>\r\n                            </div>\r\n\r\n                            <div class=\"inclusions_share flex flex-wrap\">\r\n                                <div class=\"left_side mb-5 pr-8 w-6/12 icon-wishlist relative\">\r\n                                    <ul class=\"inclusions inclusions_list pt-2\">\r\n                                        <li :data-text=\"package_inclusion.value\" v-for=\"package_inclusion in package.package_inclusions\">\r\n                                            <img :src=\"package_inclusion.image\" />\r\n                                            {{package_inclusion.value}}\r\n                                        </li>\r\n                                    </ul>\r\n                                    <a href=\"javascript:void(0)\" class=\"more_btn\"> more... </a>\r\n                                </div>\r\n                                <div class=\"share-section w-6/12\">\r\n                                    <div class=\"flex py-0 pt-3 pl-1.5 items-center\">\r\n                                        <div class=\"bg_share\">\r\n                                            <p class=\"mr-3\">Share It On:</p>\r\n                                            <div class=\"footer_bottom_right\">\r\n                                                <ul class=\"social_icon\">\r\n                                                    <li class=\"facebook\" v-if=\"sharing_links.facebook\"><a\r\n                                                        :href=\"sharing_links.facebook\"\r\n                                                        target=\"_blank\"><i class=\"fa-brands fa-facebook-f\"></i></a>\r\n                                                    </li>\r\n                                                    <li class=\"twitter\" v-if=\"sharing_links.twitter\"><a\r\n                                                        :href=\"sharing_links.twitter\"\r\n                                                        target=\"_blank\"><i class=\"fa-brands fa-twitter\"></i></a>\r\n                                                    </li>\r\n                                                    <li class=\"whatsapp\" v-if=\"sharing_links.whatsapp\"><a\r\n                                                        :href=\"sharing_links.whatsapp\"\r\n                                                        target=\"_blank\"><i class=\"fa-brands fa-whatsapp\"></i></a>\r\n                                                    </li>\r\n                                                    <li class=\"instagram\" v-if=\"sharing_links.instagram\"><a\r\n                                                        :href=\"sharing_links.instagram\"\r\n                                                        target=\"_blank\"><i class=\"fa-brands fa-instagram\"></i></a>\r\n                                                    </li>\r\n                                                </ul>\r\n                                            </div>\r\n                                        </div>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_6, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
        id: "element",
        "class": "btn btn-default show-modal mt-5",
        onClick: _cache[0] || (_cache[0] = function () {
          return $options.showItenaryPopup && $options.showItenaryPopup.apply($options, arguments);
        })
      }, "Download Itinerary")]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h1", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", _hoisted_10, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("strong", _hoisted_11, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props["package"].package_duration), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props["package"].title), 1 /* TEXT */)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("li", _hoisted_13, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", _hoisted_14, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", _hoisted_15, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props["package"].package_tour_type_text), 1 /* TEXT */), ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props["package"].days_nights_city, function (dnc_value) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", {
          key: dnc_value,
          innerHTML: dnc_value
        }, null, 8 /* PROPS */, _hoisted_16);
      }), 128 /* KEYED_FRAGMENT */))])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_17, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", _hoisted_18, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props["package"].package_inclusions, function (package_inclusion) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", {
          "data-text": package_inclusion.value
        }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
          alt: package_inclusion.value,
          src: package_inclusion.image
        }, null, 8 /* PROPS */, _hoisted_20), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(package_inclusion.value), 1 /* TEXT */)], 8 /* PROPS */, _hoisted_19);
      }), 256 /* UNKEYED_FRAGMENT */))]), _hoisted_21]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_PackageRightPrice, {
        "package": $props["package"],
        defaultPriceId: $props.default_price_id,
        faq_row: $props.faq_row,
        destinations: $props.destinations,
        itenaries: $props.itenaries,
        calenderPop: _this.calenderPop,
        showCalenderPopup: _this.showCalenderPopup,
        hideCalenderPopup: _this.hideCalenderPopup
      }, null, 8 /* PROPS */, ["package", "defaultPriceId", "faq_row", "destinations", "itenaries", "calenderPop", "showCalenderPopup", "hideCalenderPopup"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("- ======== Description  ======== "), $props["package"].brief ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_22, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_23, [_hoisted_24, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_25, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", _hoisted_26, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props["package"].packageCategories, function (pc) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", {
          key: pc.id,
          innerHTML: pc.title
        }, null, 8 /* PROPS */, _hoisted_27);
      }), 128 /* KEYED_FRAGMENT */))])])]), _hoisted_28, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", {
        innerHTML: $props["package"].brief
      }, null, 8 /* PROPS */, _hoisted_29), _this.readMore ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("p", {
        key: 0,
        innerHTML: $props["package"].description
      }, null, 8 /* PROPS */, _hoisted_30)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props["package"].description ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", {
        key: 1,
        id: "s_more",
        onClick: _cache[1] || (_cache[1] = function () {
          return $options.handleReadMore && $options.handleReadMore.apply($options, arguments);
        })
      }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_this.readMore ? 'Read Less' : 'Read More'), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("- ======== Flight  ======== "), $props["package"].PackageFlights && $props["package"].PackageFlights.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_31, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_32, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props["package"].PackageFlights.length) + " Flights in the package", 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", _hoisted_33, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", _hoisted_34, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props["package"].PackageFlights, function (flight) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_35, [_hoisted_36, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)((0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(flight.airline_name) + " | " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(flight.flight_number) + " | " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(flight.flight_departure) + " ", 1 /* TEXT */), _hoisted_37, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(flight.flight_arrival), 1 /* TEXT */)]);
      }), 256 /* UNKEYED_FRAGMENT */))])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("- ======== Package Days  ======== "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_38, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", null, [_hoisted_39, $data.store.accommodations_days && $data.store.accommodations_days.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_40, [].concat(_hoisted_42))) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), _this.testimonials && _this.testimonials.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_43, [].concat(_hoisted_45))) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])]), _this.package_itenaries && _this.package_itenaries.length > 0 && $props["package"].is_activity == 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_46, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_47, [_hoisted_48, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_49, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", null, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)(_this.package_itenaries, function (itenary) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_50, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_51, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_52, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(itenary.itenary_title), 1 /* TEXT */), itenary.itenaryTags && itenary.itenaryTags.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_53, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)(itenary.itenaryTags, function (itag) {
          return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_54, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(itag), 1 /* TEXT */);
        }), 256 /* UNKEYED_FRAGMENT */))])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), itenary.meal_option_arr && itenary.meal_option_arr.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("p", _hoisted_55, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Meal : "), ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)(itenary.meal_option_arr, function (meal_option_val, index) {
          return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)((0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(index > 0 ? ', ' : '') + " " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(meal_option_val), 1 /* TEXT */)], 64 /* STABLE_FRAGMENT */);
        }), 256 /* UNKEYED_FRAGMENT */))])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_56, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(itenary.itenary_day_title), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_57, [itenary.itenary_inclusions_arr ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_58, [_hoisted_59, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", _hoisted_60, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)(itenary.itenary_inclusions_arr, function (inclusion) {
          return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", {
            "data-text": inclusion.title,
            style: {
              "float": "left",
              "text-align": "center",
              "margin-right": "15px"
            }
          }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
            style: {},
            src: inclusion.image
          }, null, 8 /* PROPS */, _hoisted_62), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(inclusion.title), 1 /* TEXT */)], 8 /* PROPS */, _hoisted_61);
        }), 256 /* UNKEYED_FRAGMENT */))])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), itenary.itenary_location ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_63, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(itenary.itenary_location), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
          "class": "right-content-itinerary",
          innerHTML: itenary.itenary_description
        }, null, 8 /* PROPS */, _hoisted_64), itenary.itenarySrc ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_65, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
          src: itenary.itenarySrc,
          alt: itenary.itenary_title
        }, null, 8 /* PROPS */, _hoisted_66)])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])]);
      }), 256 /* UNKEYED_FRAGMENT */))])])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("- ======== Accommodation Hotel ======== "), $data.store.accommodations_days && $data.store.accommodations_days.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_PackageAccommodation, {
        key: 3
      })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("- ======== Accommodation  Old ======== "), _this.packagePolicy() ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_67, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_68, [_hoisted_69, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_70, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", null, [$props["package"].inclusions ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_71, [_hoisted_72, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_73, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
        innerHTML: $props["package"].inclusions
      }, null, 8 /* PROPS */, _hoisted_74)])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props["package"].exclusions ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_75, [_hoisted_76, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_77, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
        innerHTML: $props["package"].exclusions
      }, null, 8 /* PROPS */, _hoisted_78)])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props["package"].payment_policy ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_79, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_80, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_81, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
        innerHTML: $props["package"].payment_policy
      }, null, 8 /* PROPS */, _hoisted_82)])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props["package"].cancellation_policy ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_83, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_84, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_85, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
        innerHTML: $props["package"].cancellation_policy
      }, null, 8 /* PROPS */, _hoisted_86)])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props["package"].terms_conditions ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_87, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_hoisted_88, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_89, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
        innerHTML: $props["package"].terms_conditions
      }, null, 8 /* PROPS */, _hoisted_90)])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props["package"].PackageInfo && $props["package"].PackageInfo.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
        key: 5
      }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props["package"].PackageInfo, function (info) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [info.title && info.description ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_91, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_92, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(info.title), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_93, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
          innerHTML: info.description
        }, null, 8 /* PROPS */, _hoisted_94)])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)], 64 /* STABLE_FRAGMENT */);
      }), 256 /* UNKEYED_FRAGMENT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Frequently Asked Questions "), $props.faq_row && $props.faq_row.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
        key: 0
      }, [_hoisted_95, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_96, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", _hoisted_97, "FAQs About " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props["package"].title), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", null, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.faq_row, function (faq) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_98, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_99, [_hoisted_100, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)((0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(faq.question), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_101, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", {
          innerHTML: faq.answer
        }, null, 8 /* PROPS */, _hoisted_102)])])]);
      }), 256 /* UNKEYED_FRAGMENT */))])], 64 /* STABLE_FRAGMENT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Frequently Asked Questions Closed "), $props["package"].video_link ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
        key: 1,
        innerHTML: $props["package"].video_link
      }, null, 8 /* PROPS */, _hoisted_103)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <div class=\"btn_groups\">\r\n                                <a href=\"#packageEnquire_form\" class=\"btn btn_enquire\">Enquire Now</a>\r\n                            </div>  "), _this.testimonials && _this.testimonials.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_testimonialSlider, {
        key: 5,
        testimonials: _this.testimonials
      }, null, 8 /* PROPS */, ["testimonials"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_104, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_105, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_106, [_hoisted_107, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_108, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", _hoisted_109, [$props.sharing_links.facebook ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_110, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: $props.sharing_links.facebook,
        target: "_blank"
      }, [_hoisted_112, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Share")], 8 /* PROPS */, _hoisted_111)])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props.sharing_links.twitter ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_113, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: $props.sharing_links.twitter,
        target: "_blank"
      }, [_hoisted_115, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Tweet")], 8 /* PROPS */, _hoisted_114)])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props.sharing_links.whatsapp ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_116, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: $props.sharing_links.whatsapp,
        target: "_blank"
      }, [_hoisted_118, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Whatsapp")], 8 /* PROPS */, _hoisted_117)])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props.sharing_links.instagram ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_119, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: $props.sharing_links.instagram,
        target: "_blank"
      }, [_hoisted_121, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Instagram")], 8 /* PROPS */, _hoisted_120)])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])])])])]), _this["package"] && _this["package"].id ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_122, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_123, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_124, [_hoisted_125, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_formShortCode, {
        slug: "[your_preference]",
        "class": "left_form",
        hidden: {
          'package': _this["package"].id
        }
      }, null, 8 /* PROPS */, ["hidden"])])]), _hoisted_126])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])])])];
    }),
    _: 1 /* STABLE */
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_SinglePackageWrapper, null, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_this.similarPackages && _this.similarPackages.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_SliderSection, {
        key: 0,
        sectionData: {
          data: _this.similarPackages
        },
        withPrice: true,
        title: "Similar Packages"
      }, null, 8 /* PROPS */, ["sectionData"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)];
    }),
    _: 1 /* STABLE */
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_DestinationWrapper, null, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      var _this$destinationsSec;
      return [_this.destinationsSectionData && ((_this$destinationsSec = _this.destinationsSectionData) === null || _this$destinationsSec === void 0 ? void 0 : _this$destinationsSec.data.length) > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_SliderSection, {
        key: 0,
        sectionData: _this.destinationsSectionData,
        title: "More Trips Departure",
        className: "more_trips_departure",
        slidePerView: 4,
        spacebetween: 30
      }, null, 8 /* PROPS */, ["sectionData"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)];
    }),
    _: 1 /* STABLE */
  }), $data.store.popupType != 'innerHtml' && this.itenaryPopup ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Popup, {
    key: 0,
    className: "download_itnery_pop_wrapper"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_127, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_128, [_hoisted_129, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", _hoisted_130, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Download itinerary for :"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", _hoisted_131, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props["package"].title), 1 /* TEXT */)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_132, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_formShortCode, {
        slug: "[download_itinerary]",
        "class": "form_list",
        hidden: {
          'package': _this["package"].id
        },
        name: "download_itinerary"
      }, null, 8 /* PROPS */, ["hidden"])])])];
    }),
    _: 1 /* STABLE */
  })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)], 64 /* STABLE_FRAGMENT */);
}

/***/ }),

/***/ "./resources/js/themes/andamanisland/hotels/data.js":
/*!**********************************************************!*\
  !*** ./resources/js/themes/andamanisland/hotels/data.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   airportLists: () => (/* binding */ airportLists),
/* harmony export */   form_data: () => (/* binding */ form_data),
/* harmony export */   paxInfo: () => (/* binding */ paxInfo),
/* harmony export */   routeInfos: () => (/* binding */ routeInfos),
/* harmony export */   searchResult: () => (/* binding */ searchResult)
/* harmony export */ });
var searchResult = {
  tripInfos: {
    "ONWARD": []
  }
};
var paxInfo = {
  "ADULT": "1"
};
var routeInfos = [];
var airportLists = [];
var form_data = {};


/***/ }),

/***/ "./resources/js/themes/andamanisland/hotels/store.js":
/*!***********************************************************!*\
  !*** ./resources/js/themes/andamanisland/hotels/store.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   store: () => (/* binding */ store)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var store = (0,vue__WEBPACK_IMPORTED_MODULE_0__.reactive)({
  activePage: -1,
  activeTab: -1,
  activeRouteIndex: 0,
  bookingPassedStep: 0,
  bookingCurrentStep: 1,
  passengers: {},
  contact_country_code: '+91',
  contact_mobile: '',
  contact_email: '',
  gst_number: '',
  gst_company: '',
  gst_email: '',
  gst_phone: '',
  gst_address: '',
  tripType: 0,
  priceIds: {},
  filter_showIncv: false,
  filter_showNet: false,
  price_range: {},
  stops: [],
  fare_identifier: {},
  airlines: {},
  filter_fareIdentifier: {},
  filter_published_arr: ['PUBLISHED', 'FLEXI_PLUS', 'COUPON'],
  filter_sme_arr: ['SME'],
  filter_corporate_arr: ['CORPORATE', 'CORPORATE_GOMORE'],
  filter_sale_arr: ['SALE'],
  paxInfo_arr: [{
    'key': 'ADULT',
    'title': 'ADULT',
    'years_title': '(12 + yrs)'
  }, {
    'key': 'CHILD',
    'title': 'CHILD',
    'years_title': '(2 + yrs)'
  }, {
    'key': 'INFANT',
    'title': 'INFANT',
    'years_title': '(0-2 yrs)'
  }],
  loadingName: false,
  tripTimeArr: [{
    "key": "00:00",
    "title": "12:00 AM"
  }, {
    "key": "00:15",
    "title": "12:15 AM"
  }, {
    "key": "00:30",
    "title": "12:30 AM"
  }, {
    "key": "00:45",
    "title": "12:45 AM"
  }, {
    "key": "01:00",
    "title": "01:00 AM"
  }, {
    "key": "01:15",
    "title": "01:15 AM"
  }, {
    "key": "01:30",
    "title": "01:30 AM"
  }, {
    "key": "01:45",
    "title": "01:45 AM"
  }, {
    "key": "02:00",
    "title": "02:00 AM"
  }, {
    "key": "02:15",
    "title": "02:15 AM"
  }, {
    "key": "02:30",
    "title": "02:30 AM"
  }, {
    "key": "02:45",
    "title": "02:45 AM"
  }, {
    "key": "03:00",
    "title": "03:00 AM"
  }, {
    "key": "03:15",
    "title": "03:15 AM"
  }, {
    "key": "03:30",
    "title": "03:30 AM"
  }, {
    "key": "03:45",
    "title": "03:45 AM"
  }, {
    "key": "04:00",
    "title": "04:00 AM"
  }, {
    "key": "04:15",
    "title": "04:15 AM"
  }, {
    "key": "04:30",
    "title": "04:30 AM"
  }, {
    "key": "04:45",
    "title": "04:45 AM"
  }, {
    "key": "05:00",
    "title": "05:00 AM"
  }, {
    "key": "05:15",
    "title": "05:15 AM"
  }, {
    "key": "05:30",
    "title": "05:30 AM"
  }, {
    "key": "05:45",
    "title": "05:45 AM"
  }, {
    "key": "06:00",
    "title": "06:00 AM"
  }, {
    "key": "06:15",
    "title": "06:15 AM"
  }, {
    "key": "06:30",
    "title": "06:30 AM"
  }, {
    "key": "06:45",
    "title": "06:45 AM"
  }, {
    "key": "07:00",
    "title": "07:00 AM"
  }, {
    "key": "07:15",
    "title": "07:15 AM"
  }, {
    "key": "07:30",
    "title": "07:30 AM"
  }, {
    "key": "07:45",
    "title": "07:45 AM"
  }, {
    "key": "08:00",
    "title": "08:00 AM"
  }, {
    "key": "08:15",
    "title": "08:15 AM"
  }, {
    "key": "08:30",
    "title": "08:30 AM"
  }, {
    "key": "08:45",
    "title": "08:45 AM"
  }, {
    "key": "09:00",
    "title": "09:00 AM"
  }, {
    "key": "09:15",
    "title": "09:15 AM"
  }, {
    "key": "09:30",
    "title": "09:30 AM"
  }, {
    "key": "09:45",
    "title": "09:45 AM"
  }, {
    "key": "10:00",
    "title": "10:00 AM"
  }, {
    "key": "10:15",
    "title": "10:15 AM"
  }, {
    "key": "10:30",
    "title": "10:30 AM"
  }, {
    "key": "10:45",
    "title": "10:45 AM"
  }, {
    "key": "11:00",
    "title": "11:00 AM"
  }, {
    "key": "11:15",
    "title": "11:15 AM"
  }, {
    "key": "11:30",
    "title": "11:30 AM"
  }, {
    "key": "11:45",
    "title": "11:45 AM"
  }, {
    "key": "12:00",
    "title": "12:00 PM"
  }, {
    "key": "12:15",
    "title": "12:15 PM"
  }, {
    "key": "12:30",
    "title": "12:30 PM"
  }, {
    "key": "12:45",
    "title": "12:45 PM"
  }, {
    "key": "13:00",
    "title": "01:00 PM"
  }, {
    "key": "13:15",
    "title": "01:15 PM"
  }, {
    "key": "13:30",
    "title": "01:30 PM"
  }, {
    "key": "13:45",
    "title": "01:45 PM"
  }, {
    "key": "14:00",
    "title": "02:00 PM"
  }, {
    "key": "14:15",
    "title": "02:15 PM"
  }, {
    "key": "14:30",
    "title": "02:30 PM"
  }, {
    "key": "14:45",
    "title": "02:45 PM"
  }, {
    "key": "15:00",
    "title": "03:00 PM"
  }, {
    "key": "15:15",
    "title": "03:15 PM"
  }, {
    "key": "15:30",
    "title": "03:30 PM"
  }, {
    "key": "15:45",
    "title": "03:45 PM"
  }, {
    "key": "16:00",
    "title": "04:00 PM"
  }, {
    "key": "16:15",
    "title": "04:15 PM"
  }, {
    "key": "16:30",
    "title": "04:30 PM"
  }, {
    "key": "16:45",
    "title": "04:45 PM"
  }, {
    "key": "17:00",
    "title": "05:00 PM"
  }, {
    "key": "17:15",
    "title": "05:15 PM"
  }, {
    "key": "17:30",
    "title": "05:30 PM"
  }, {
    "key": "17:45",
    "title": "05:45 PM"
  }, {
    "key": "18:00",
    "title": "06:00 PM"
  }, {
    "key": "18:15",
    "title": "06:15 PM"
  }, {
    "key": "18:30",
    "title": "06:30 PM"
  }, {
    "key": "18:45",
    "title": "06:45 PM"
  }, {
    "key": "19:00",
    "title": "07:00 PM"
  }, {
    "key": "19:15",
    "title": "07:15 PM"
  }, {
    "key": "19:30",
    "title": "07:30 PM"
  }, {
    "key": "19:45",
    "title": "07:45 PM"
  }, {
    "key": "20:00",
    "title": "08:00 PM"
  }, {
    "key": "20:15",
    "title": "08:15 PM"
  }, {
    "key": "20:30",
    "title": "08:30 PM"
  }, {
    "key": "20:45",
    "title": "08:45 PM"
  }, {
    "key": "21:00",
    "title": "09:00 PM"
  }, {
    "key": "21:15",
    "title": "09:15 PM"
  }, {
    "key": "21:30",
    "title": "09:30 PM"
  }, {
    "key": "21:45",
    "title": "09:45 PM"
  }, {
    "key": "22:00",
    "title": "10:00 PM"
  }, {
    "key": "22:15",
    "title": "10:15 PM"
  }, {
    "key": "22:30",
    "title": "10:30 PM"
  }, {
    "key": "22:45",
    "title": "10:45 PM"
  }, {
    "key": "23:00",
    "title": "11:00 PM"
  }, {
    "key": "23:15",
    "title": "11:15 PM"
  }, {
    "key": "23:30",
    "title": "11:30 PM"
  }, {
    "key": "23:45",
    "title": "11:45 PM"
  }],
  popupActive: false,
  popupContent: '<p>hello world</p>',
  popUpClass: '',
  popupType: '' // use `innerHtml` to insert content from dynamic content
});

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/packages/Details.vue?vue&type=style&index=0&id=36048de1&lang=css":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/packages/Details.vue?vue&type=style&index=0&id=36048de1&lang=css ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\nhtml {\r\n  scroll-behavior: smooth;\r\n  scroll-padding-top: 80px;\n}\r\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/packages/Details.vue?vue&type=style&index=0&id=36048de1&lang=css":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/packages/Details.vue?vue&type=style&index=0&id=36048de1&lang=css ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_10_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_10_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Details_vue_vue_type_style_index_0_id_36048de1_lang_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!../../../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Details.vue?vue&type=style&index=0&id=36048de1&lang=css */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/packages/Details.vue?vue&type=style&index=0&id=36048de1&lang=css");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_10_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_10_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Details_vue_vue_type_style_index_0_id_36048de1_lang_css__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_10_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_10_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Details_vue_vue_type_style_index_0_id_36048de1_lang_css__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/themes/andamanisland/hotels/Details.vue":
/*!**************************************************************!*\
  !*** ./resources/js/themes/andamanisland/hotels/Details.vue ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Details_vue_vue_type_template_id_56ab7d1a__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Details.vue?vue&type=template&id=56ab7d1a */ "./resources/js/themes/andamanisland/hotels/Details.vue?vue&type=template&id=56ab7d1a");
/* harmony import */ var _Details_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Details.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/hotels/Details.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_Details_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Details_vue_vue_type_template_id_56ab7d1a__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/hotels/Details.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/hotels/Listing.vue":
/*!**************************************************************!*\
  !*** ./resources/js/themes/andamanisland/hotels/Listing.vue ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Listing_vue_vue_type_template_id_712818b5__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Listing.vue?vue&type=template&id=712818b5 */ "./resources/js/themes/andamanisland/hotels/Listing.vue?vue&type=template&id=712818b5");
/* harmony import */ var _Listing_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Listing.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/hotels/Listing.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_Listing_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Listing_vue_vue_type_template_id_712818b5__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/hotels/Listing.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/packages/Details.vue":
/*!****************************************************************!*\
  !*** ./resources/js/themes/andamanisland/packages/Details.vue ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Details_vue_vue_type_template_id_36048de1__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Details.vue?vue&type=template&id=36048de1 */ "./resources/js/themes/andamanisland/packages/Details.vue?vue&type=template&id=36048de1");
/* harmony import */ var _Details_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Details.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/packages/Details.vue?vue&type=script&lang=js");
/* harmony import */ var _Details_vue_vue_type_style_index_0_id_36048de1_lang_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Details.vue?vue&type=style&index=0&id=36048de1&lang=css */ "./resources/js/themes/andamanisland/packages/Details.vue?vue&type=style&index=0&id=36048de1&lang=css");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;


const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__["default"])(_Details_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Details_vue_vue_type_template_id_36048de1__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/packages/Details.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/hotels/Details.vue?vue&type=script&lang=js":
/*!**************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/hotels/Details.vue?vue&type=script&lang=js ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Details_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Details_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Details.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/hotels/Details.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/hotels/Listing.vue?vue&type=script&lang=js":
/*!**************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/hotels/Listing.vue?vue&type=script&lang=js ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Listing_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Listing_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Listing.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/hotels/Listing.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/packages/Details.vue?vue&type=script&lang=js":
/*!****************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/packages/Details.vue?vue&type=script&lang=js ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Details_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Details_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Details.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/packages/Details.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/hotels/Details.vue?vue&type=template&id=56ab7d1a":
/*!********************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/hotels/Details.vue?vue&type=template&id=56ab7d1a ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Details_vue_vue_type_template_id_56ab7d1a__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Details_vue_vue_type_template_id_56ab7d1a__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Details.vue?vue&type=template&id=56ab7d1a */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/hotels/Details.vue?vue&type=template&id=56ab7d1a");


/***/ }),

/***/ "./resources/js/themes/andamanisland/hotels/Listing.vue?vue&type=template&id=712818b5":
/*!********************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/hotels/Listing.vue?vue&type=template&id=712818b5 ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Listing_vue_vue_type_template_id_712818b5__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Listing_vue_vue_type_template_id_712818b5__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Listing.vue?vue&type=template&id=712818b5 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/hotels/Listing.vue?vue&type=template&id=712818b5");


/***/ }),

/***/ "./resources/js/themes/andamanisland/packages/Details.vue?vue&type=template&id=36048de1":
/*!**********************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/packages/Details.vue?vue&type=template&id=36048de1 ***!
  \**********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Details_vue_vue_type_template_id_36048de1__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Details_vue_vue_type_template_id_36048de1__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Details.vue?vue&type=template&id=36048de1 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/packages/Details.vue?vue&type=template&id=36048de1");


/***/ }),

/***/ "./resources/js/themes/andamanisland/packages/Details.vue?vue&type=style&index=0&id=36048de1&lang=css":
/*!************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/packages/Details.vue?vue&type=style&index=0&id=36048de1&lang=css ***!
  \************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_10_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_10_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Details_vue_vue_type_style_index_0_id_36048de1_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader/dist/cjs.js!../../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!../../../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Details.vue?vue&type=style&index=0&id=36048de1&lang=css */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/packages/Details.vue?vue&type=style&index=0&id=36048de1&lang=css");


/***/ })

}]);