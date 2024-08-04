"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/app-resources_js_themes_andamanisland_E"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Error.vue?vue&type=script&lang=js":
/*!*********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Error.vue?vue&type=script&lang=js ***!
  \*********************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'Error',
  created: function created() {
    var _this = this;
    var title = (0,vue__WEBPACK_IMPORTED_MODULE_1__.computed)(function () {
      return {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden'
      }[_this.status];
    });
    this.title = title;
    var description = (0,vue__WEBPACK_IMPORTED_MODULE_1__.computed)(function () {
      return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.'
      }[_this.status];
    });
    this.description = description;
  },
  data: function data() {
    return {
      title: '',
      description: ''
    };
  },
  methods: {},
  mounted: function mounted() {},
  beforeUnmount: function beforeUnmount() {},
  components: {
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__.Link
  },
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_2__["default"],
  props: ["status"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Home.vue?vue&type=script&lang=js":
/*!********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Home.vue?vue&type=script&lang=js ***!
  \********************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _components_home_HomeBlogSlider_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/home/HomeBlogSlider.vue */ "./resources/js/themes/andamanisland/components/home/HomeBlogSlider.vue");
/* harmony import */ var _components_home_HomePageBanner_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/home/HomePageBanner.vue */ "./resources/js/themes/andamanisland/components/home/HomePageBanner.vue");
/* harmony import */ var _components_home_AdventureIdeas_vue__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/home/AdventureIdeas.vue */ "./resources/js/themes/andamanisland/components/home/AdventureIdeas.vue");
/* harmony import */ var _components_home_PopularHolidays_vue__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/home/PopularHolidays.vue */ "./resources/js/themes/andamanisland/components/home/PopularHolidays.vue");
/* harmony import */ var _components_home_TrustedProfessional_vue__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./components/home/TrustedProfessional.vue */ "./resources/js/themes/andamanisland/components/home/TrustedProfessional.vue");
/* harmony import */ var _components_home_HomeDestinationGallery_vue__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./components/home/HomeDestinationGallery.vue */ "./resources/js/themes/andamanisland/components/home/HomeDestinationGallery.vue");
/* harmony import */ var _components_home_HomeTestimonialSlider_vue__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./components/home/HomeTestimonialSlider.vue */ "./resources/js/themes/andamanisland/components/home/HomeTestimonialSlider.vue");
/* harmony import */ var _data__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./data */ "./resources/js/themes/andamanisland/data.js");
/* harmony import */ var _components_home_ClientsSection_vue__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./components/home/ClientsSection.vue */ "./resources/js/themes/andamanisland/components/home/ClientsSection.vue");
/* harmony import */ var _components_SearchFormWithBanner_vue__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./components/SearchFormWithBanner.vue */ "./resources/js/themes/andamanisland/components/SearchFormWithBanner.vue");
/* harmony import */ var _components_home_AboutHomePage_vue__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./components/home/AboutHomePage.vue */ "./resources/js/themes/andamanisland/components/home/AboutHomePage.vue");
/* harmony import */ var _components_home_HomeDestinationSlider_vue__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./components/home/HomeDestinationSlider.vue */ "./resources/js/themes/andamanisland/components/home/HomeDestinationSlider.vue");
/* harmony import */ var _components_SliderSection_vue__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./components/SliderSection.vue */ "./resources/js/themes/andamanisland/components/SliderSection.vue");
/* harmony import */ var _components_home_HomePackageSlider_vue__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./components/home/HomePackageSlider.vue */ "./resources/js/themes/andamanisland/components/home/HomePackageSlider.vue");
/* harmony import */ var _components_home_HomeHotelSlider_vue__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./components/home/HomeHotelSlider.vue */ "./resources/js/themes/andamanisland/components/home/HomeHotelSlider.vue");
/* harmony import */ var _components_home_ScubaDivingSection_vue__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./components/home/ScubaDivingSection.vue */ "./resources/js/themes/andamanisland/components/home/ScubaDivingSection.vue");
/* harmony import */ var _components_home_CertifySection_vue__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./components/home/CertifySection.vue */ "./resources/js/themes/andamanisland/components/home/CertifySection.vue");
/* harmony import */ var _components_home_WeddingAtBeach_vue__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ./components/home/WeddingAtBeach.vue */ "./resources/js/themes/andamanisland/components/home/WeddingAtBeach.vue");
/* harmony import */ var _components_home_HomeDestinationSlider2_vue__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! ./components/home/HomeDestinationSlider2.vue */ "./resources/js/themes/andamanisland/components/home/HomeDestinationSlider2.vue");
/* harmony import */ var _components_home_FaqSection_vue__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! ./components/home/FaqSection.vue */ "./resources/js/themes/andamanisland/components/home/FaqSection.vue");
/* harmony import */ var _components_home_HomeExperience_vue__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! ./components/home/HomeExperience.vue */ "./resources/js/themes/andamanisland/components/home/HomeExperience.vue");
/* harmony import */ var _components_home_HotelTabs_vue__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! ./components/home/HotelTabs.vue */ "./resources/js/themes/andamanisland/components/home/HotelTabs.vue");
/* harmony import */ var _components_home_HomeBlogGrid_vue__WEBPACK_IMPORTED_MODULE_25__ = __webpack_require__(/*! ./components/home/HomeBlogGrid.vue */ "./resources/js/themes/andamanisland/components/home/HomeBlogGrid.vue");












// import NewsLetterSection from './components/home/NewsLetterSection.vue';














/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  created: function created() {
    _store__WEBPACK_IMPORTED_MODULE_1__.store.seoData = this.seo_data;
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_1__.store,
      clients: _data__WEBPACK_IMPORTED_MODULE_10__.clients
    };
  },
  methods: {},
  mounted: function mounted() {
    document.body.classList.add('home_page');
  },
  beforeUnmount: function beforeUnmount() {
    document.body.classList.remove('home_page');
  },
  watch: {},
  components: {
    HomeBlogSlider: _components_home_HomeBlogSlider_vue__WEBPACK_IMPORTED_MODULE_3__["default"],
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_2__.Link,
    HomePageBanner: _components_home_HomePageBanner_vue__WEBPACK_IMPORTED_MODULE_4__["default"],
    AdventureIdeas: _components_home_AdventureIdeas_vue__WEBPACK_IMPORTED_MODULE_5__["default"],
    PopularHolidays: _components_home_PopularHolidays_vue__WEBPACK_IMPORTED_MODULE_6__["default"],
    TrustedProfessional: _components_home_TrustedProfessional_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
    HomeDestinationGallery: _components_home_HomeDestinationGallery_vue__WEBPACK_IMPORTED_MODULE_8__["default"],
    HomeTestimonialSlider: _components_home_HomeTestimonialSlider_vue__WEBPACK_IMPORTED_MODULE_9__["default"],
    ClientsSection: _components_home_ClientsSection_vue__WEBPACK_IMPORTED_MODULE_11__["default"],
    // NewsLetterSection,
    SearchFormWithBanner: _components_SearchFormWithBanner_vue__WEBPACK_IMPORTED_MODULE_12__["default"],
    AboutHomePage: _components_home_AboutHomePage_vue__WEBPACK_IMPORTED_MODULE_13__["default"],
    HomeDestinationSlider: _components_home_HomeDestinationSlider_vue__WEBPACK_IMPORTED_MODULE_14__["default"],
    SliderSection: _components_SliderSection_vue__WEBPACK_IMPORTED_MODULE_15__["default"],
    HomePackageSlider: _components_home_HomePackageSlider_vue__WEBPACK_IMPORTED_MODULE_16__["default"],
    HomeHotelSlider: _components_home_HomeHotelSlider_vue__WEBPACK_IMPORTED_MODULE_17__["default"],
    ScubaDivingSection: _components_home_ScubaDivingSection_vue__WEBPACK_IMPORTED_MODULE_18__["default"],
    CertifySection: _components_home_CertifySection_vue__WEBPACK_IMPORTED_MODULE_19__["default"],
    WeddingAtBeach: _components_home_WeddingAtBeach_vue__WEBPACK_IMPORTED_MODULE_20__["default"],
    HomeDestinationSlider2: _components_home_HomeDestinationSlider2_vue__WEBPACK_IMPORTED_MODULE_21__["default"],
    FaqSection: _components_home_FaqSection_vue__WEBPACK_IMPORTED_MODULE_22__["default"],
    HomeExperience: _components_home_HomeExperience_vue__WEBPACK_IMPORTED_MODULE_23__["default"],
    HotelTabs: _components_home_HotelTabs_vue__WEBPACK_IMPORTED_MODULE_24__["default"],
    HomeBlogGrid: _components_home_HomeBlogGrid_vue__WEBPACK_IMPORTED_MODULE_25__["default"]
  },
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
  props: ["seo_data", "faqs"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/agent-signup.vue?vue&type=script&lang=js":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/agent-signup.vue?vue&type=script&lang=js ***!
  \************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/FlashMessage.vue */ "./resources/js/themes/andamanisland/components/FlashMessage.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils/commonFuntions.js */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
/* harmony import */ var _utils_CountryCodes_json__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../utils/CountryCodes.json */ "./resources/js/themes/andamanisland/utils/CountryCodes.json");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _styles_AgentSignupWrapper__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../styles/AgentSignupWrapper */ "./resources/js/themes/andamanisland/styles/AgentSignupWrapper.js");








/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'agent-signup',
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
  created: function created() {
    _store__WEBPACK_IMPORTED_MODULE_3__.store.searchData = this.search_data;
    _store__WEBPACK_IMPORTED_MODULE_3__.store.seoData = this.seo_data;
    this.flashMessages = this.flash_messages;
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_3__.store,
      countryCodes: _utils_CountryCodes_json__WEBPACK_IMPORTED_MODULE_5__,
      formData: {
        'company_name': '',
        'company_brand': '',
        'pan_no': '',
        'pan_image': '',
        'gst_no': '',
        'gst_image': '',
        'company_owner_name': '',
        'agent_logo': '',
        'bookings_per_month': '',
        'no_of_employees': '',
        'agency_age': '',
        'website': '',
        'destinations_sell_most': '',
        'whatsapp_country_code': '',
        'whatsapp_phone': '',
        'region': '',
        'source': '',
        'company_profile': ''
      },
      errors: {},
      flashMessages: [],
      isProcessing: false
    };
  },
  methods: {
    showErrorToast: _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_4__.showErrorToast,
    handleChange: function handleChange(event) {
      var _event$target;
      var currentObj = this;
      if (event !== null && event !== void 0 && (_event$target = event.target) !== null && _event$target !== void 0 && _event$target.name) {
        var name = event.target.name;
        var value = event.target.value;
        if (event.target.type == 'file') {
          value = event.target.files[0];
        }
        var newFormData = currentObj.formData;
        newFormData[name] = value;
        currentObj.formData = newFormData;
      }
      currentObj.errors = {};
      currentObj.flashMessages = [];
    },
    handleFormSubmit: function handleFormSubmit(e) {
      e.preventDefault();
      e.stopPropagation();
      var currentObj = this;
      currentObj.errors = {};
      currentObj.flashMessages = [];
      currentObj.isProcessing = true;
      var formData = new FormData();
      var fd = currentObj.formData;
      if (fd.company_name) {
        formData.append('company_name', fd.company_name);
      }
      if (fd.company_brand) {
        formData.append('company_brand', fd.company_brand);
      }
      if (fd.pan_no) {
        formData.append('pan_no', fd.pan_no);
      }
      if (fd.pan_image) {
        formData.append('pan_image', fd.pan_image);
      }
      if (fd.gst_no) {
        formData.append('gst_no', fd.gst_no);
      }
      if (fd.gst_image) {
        formData.append('gst_image', fd.gst_image);
      }
      if (fd.company_owner_name) {
        formData.append('company_owner_name', fd.company_owner_name);
      }
      if (fd.agent_logo) {
        formData.append('agent_logo', fd.agent_logo);
      }
      if (fd.bookings_per_month) {
        formData.append('bookings_per_month', fd.bookings_per_month);
      }
      if (fd.no_of_employees) {
        formData.append('no_of_employees', fd.no_of_employees);
      }
      if (fd.agency_age) {
        formData.append('agency_age', fd.agency_age);
      }
      if (fd.website) {
        formData.append('website', fd.website);
      }
      if (fd.destinations_sell_most) {
        formData.append('destinations_sell_most', fd.destinations_sell_most);
      }
      if (fd.whatsapp_country_code) {
        formData.append('whatsapp_country_code', fd.whatsapp_country_code);
      }
      if (fd.whatsapp_phone) {
        formData.append('whatsapp_phone', fd.whatsapp_phone);
      }
      if (fd.region) {
        formData.append('region', fd.region);
      }
      if (fd.source) {
        formData.append('source', fd.source);
      }
      if (fd.company_profile) {
        formData.append('company_profile', fd.company_profile);
      }

      // 'company_name':'',
      // 'company_brand':'',
      // 'pan_no':'',
      // 'pan_image':'',
      // 'gst_no':'',
      // 'gst_image':'',
      // 'company_owner_name':'',
      // 'agent_logo':'',
      // 'bookings_per_month':'',
      // 'no_of_employees':'',
      // 'agency_age':'',
      // 'website':'',
      // 'destinations_sell_most':'',
      // 'whatsapp_country_code':'',
      // 'whatsapp_phone':'',
      // 'region':'',
      // 'source':'',
      // 'company_profile':'',

      axios__WEBPACK_IMPORTED_MODULE_6___default().post('/user/agent-signup-2', formData).then(function (resp) {
        currentObj.isProcessing = false;
        var response = resp.data;
        if (response.success) {
          if (response.userInfo) {
            _store__WEBPACK_IMPORTED_MODULE_3__.store.userInfo = response.userInfo;
          }
          if (response.flash_messages) {
            currentObj.flashMessages = response.flash_messages;
          }
          if (response.redirect_url) {
            // currentObj.$inertia.get(response.redirect_url);
            window.location.href = response.redirect_url;
          }
          if (response.location_url) {
            window.location.href = response.location_url;
          }
        } else if (response.message) {
          currentObj.showErrorToast(response.message);
        } else {
          currentObj.showErrorToast('Something went wront, please try again.');
        }
      })["catch"](function (e) {
        currentObj.isProcessing = false;
        var response = e.response.data;
        if (response.errors) {
          currentObj.errors = response.errors;
        }
        if (response.message) {
          currentObj.showErrorToast(response.message);
        }
      });
    }
  },
  components: {
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_1__.Link,
    AgentSignupWrapper: _styles_AgentSignupWrapper__WEBPACK_IMPORTED_MODULE_7__.AgentSignupWrapper,
    FlashMessage: _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  props: ["search_data", "seo_data", "flash_messages", "PROFILE_URL", "source_types", "bookings_every_months", "total_no_of_employees", "agency_durations", "traveler_regions"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/change_password.vue?vue&type=script&lang=js":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/change_password.vue?vue&type=script&lang=js ***!
  \***************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/FlashMessage.vue */ "./resources/js/themes/andamanisland/components/FlashMessage.vue");
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils/commonFuntions.js */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _styles_ChangePasswordWrapper__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../styles/ChangePasswordWrapper */ "./resources/js/themes/andamanisland/styles/ChangePasswordWrapper.js");







/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'ChangePassword',
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_2__["default"],
  created: function created() {
    _store__WEBPACK_IMPORTED_MODULE_3__.store.searchData = this.search_data;
    _store__WEBPACK_IMPORTED_MODULE_3__.store.seoData = this.seo_data;
    this.flashMessages = this.flash_messages;
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_3__.store,
      formData: {
        'password': '',
        'confirm_password': ''
      },
      errors: {},
      flashMessages: [],
      isProcessing: false
    };
  },
  methods: {
    showErrorToast: _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_4__.showErrorToast,
    handleChange: function handleChange(event) {
      var _event$target;
      var currentObj = this;
      if (event !== null && event !== void 0 && (_event$target = event.target) !== null && _event$target !== void 0 && _event$target.name) {
        var name = event.target.name;
        var value = event.target.value;
        var newFormData = currentObj.formData;
        newFormData[name] = value;
        currentObj.formData = newFormData;
      }
      currentObj.errors = {};
      currentObj.flashMessages = [];
    },
    handleFormSubmit: function handleFormSubmit(e) {
      e.preventDefault();
      e.stopPropagation();
      var currentObj = this;
      currentObj.errors = {};
      currentObj.flashMessages = [];
      currentObj.isProcessing = true;
      var formData = currentObj.formData;
      axios__WEBPACK_IMPORTED_MODULE_5___default().post('/account/change-password', formData).then(function (resp) {
        currentObj.isProcessing = false;
        var response = resp.data;
        if (response.success) {
          if (response.userInfo) {
            _store__WEBPACK_IMPORTED_MODULE_3__.store.userInfo = response.userInfo;
          }
          if (response.flash_messages) {
            currentObj.flashMessages = response.flash_messages;
          }
          if (response.redirect_url) {
            currentObj.$inertia.get(response.redirect_url);
          }
          if (response.location_url) {
            window.location.href = response.location_url;
          }
        } else if (response.message) {
          currentObj.showErrorToast(response.message);
        } else {
          currentObj.showErrorToast('Something went wront, please try again.');
        }
      })["catch"](function (e) {
        currentObj.isProcessing = false;
        var response = e.response.data;
        if (response.errors) {
          currentObj.errors = response.errors;
        }
        if (response.message) {
          currentObj.showErrorToast(response.message);
        }
      });
    }
  },
  components: {
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__.Link,
    ChangePasswordWrapper: _styles_ChangePasswordWrapper__WEBPACK_IMPORTED_MODULE_6__.ChangePasswordWrapper,
    FlashMessage: _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  props: ["search_data", "seo_data", "flash_messages"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/forgot_otp.vue?vue&type=script&lang=js":
/*!**********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/forgot_otp.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/FlashMessage.vue */ "./resources/js/themes/andamanisland/components/FlashMessage.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils/commonFuntions.js */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _styles_ForgotOtpWrapper__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../styles/ForgotOtpWrapper */ "./resources/js/themes/andamanisland/styles/ForgotOtpWrapper.js");







/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'ForgotOtp',
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
  created: function created() {
    _store__WEBPACK_IMPORTED_MODULE_3__.store.searchData = this.search_data;
    _store__WEBPACK_IMPORTED_MODULE_3__.store.seoData = this.seo_data;
    this.flashMessages = this.flash_messages;
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_3__.store,
      formData: {
        'otp': ''
      },
      errors: {},
      flashMessages: [],
      isProcessing: false
    };
  },
  methods: {
    showErrorToast: _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_4__.showErrorToast,
    handleChange: function handleChange(event) {
      var _event$target;
      var currentObj = this;
      if (event !== null && event !== void 0 && (_event$target = event.target) !== null && _event$target !== void 0 && _event$target.name) {
        var name = event.target.name;
        var value = event.target.value;
        var newFormData = currentObj.formData;
        newFormData[name] = value;
        currentObj.formData = newFormData;
      }
      currentObj.errors = {};
      currentObj.flashMessages = [];
    },
    handleFormSubmit: function handleFormSubmit(e) {
      e.preventDefault();
      e.stopPropagation();
      var currentObj = this;
      currentObj.errors = {};
      currentObj.flashMessages = [];
      currentObj.isProcessing = true;
      var formData = currentObj.formData;
      axios__WEBPACK_IMPORTED_MODULE_5___default().post('/account/forgot-otp', formData).then(function (resp) {
        currentObj.isProcessing = false;
        var response = resp.data;
        if (response.success) {
          if (response.userInfo) {
            _store__WEBPACK_IMPORTED_MODULE_3__.store.userInfo = response.userInfo;
          }
          if (response.flash_messages) {
            currentObj.flashMessages = response.flash_messages;
          }
          if (response.redirect_url) {
            currentObj.$inertia.get(response.redirect_url);
          }
          if (response.location_url) {
            window.location.href = response.location_url;
          }
        } else if (response.message) {
          currentObj.showErrorToast(response.message);
        } else {
          currentObj.showErrorToast('Something went wront, please try again.');
        }
      })["catch"](function (e) {
        currentObj.isProcessing = false;
        var response = e.response.data;
        if (response.errors) {
          currentObj.errors = response.errors;
        }
        if (response.message) {
          currentObj.showErrorToast(response.message);
        }
      });
    },
    forgotResendOtp: function forgotResendOtp(e) {
      e.preventDefault();
      e.stopPropagation();
      var currentObj = this;
      currentObj.errors = {};
      currentObj.flashMessages = [];
      currentObj.isProcessing = true;
      var formData = currentObj.formData;
      axios__WEBPACK_IMPORTED_MODULE_5___default().post('/account/resend-forgot_otp', formData).then(function (resp) {
        currentObj.isProcessing = false;
        var response = resp.data;
        if (response.success) {
          if (response.userInfo) {
            _store__WEBPACK_IMPORTED_MODULE_3__.store.userInfo = response.userInfo;
          }
          if (response.flash_messages) {
            currentObj.flashMessages = response.flash_messages;
          }
          if (response.redirect_url) {
            currentObj.$inertia.get(response.redirect_url);
          }
          if (response.location_url) {
            window.location.href = response.location_url;
          }
        } else if (response.message) {
          currentObj.showErrorToast(response.message);
        } else {
          currentObj.showErrorToast('Something went wront, please try again.');
        }
      })["catch"](function (e) {
        currentObj.isProcessing = false;
        var response = e.response.data;
        if (response.errors) {
          currentObj.errors = response.errors;
        }
        if (response.message) {
          currentObj.showErrorToast(response.message);
        }
      });
    }
  },
  components: {
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__.Link,
    ForgotOtpWrapper: _styles_ForgotOtpWrapper__WEBPACK_IMPORTED_MODULE_6__.ForgotOtpWrapper,
    FlashMessage: _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  props: ["search_data", "seo_data", "flash_messages"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/forgot_password.vue?vue&type=script&lang=js":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/forgot_password.vue?vue&type=script&lang=js ***!
  \***************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/FlashMessage.vue */ "./resources/js/themes/andamanisland/components/FlashMessage.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils/commonFuntions.js */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _styles_ForgotPasswordWrapper__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../styles/ForgotPasswordWrapper */ "./resources/js/themes/andamanisland/styles/ForgotPasswordWrapper.js");







/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'ForgotPassword',
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
  created: function created() {
    _store__WEBPACK_IMPORTED_MODULE_3__.store.searchData = this.search_data;
    _store__WEBPACK_IMPORTED_MODULE_3__.store.seoData = this.seo_data;
    _store__WEBPACK_IMPORTED_MODULE_3__.store.flashMessages = this.flash_messages;
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_3__.store,
      formData: {
        'forgot_email': ''
      },
      errors: {},
      flashMessages: [],
      isProcessing: false
    };
  },
  methods: {
    showErrorToast: _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_4__.showErrorToast,
    handleChange: function handleChange(event) {
      var _event$target;
      var currentObj = this;
      if (event !== null && event !== void 0 && (_event$target = event.target) !== null && _event$target !== void 0 && _event$target.name) {
        var name = event.target.name;
        var value = event.target.value;
        var newFormData = currentObj.formData;
        newFormData[name] = value;
        currentObj.formData = newFormData;
      }
      currentObj.errors = {};
      currentObj.flashMessages = [];
    },
    handleFormSubmit: function handleFormSubmit(e) {
      e.preventDefault();
      e.stopPropagation();
      var currentObj = this;
      currentObj.errors = {};
      currentObj.flashMessages = [];
      currentObj.isProcessing = true;
      var formData = currentObj.formData;
      axios__WEBPACK_IMPORTED_MODULE_5___default().post('/account/forgot-password', formData).then(function (resp) {
        currentObj.isProcessing = false;
        var response = resp.data;
        if (response.success) {
          if (response.userInfo) {
            _store__WEBPACK_IMPORTED_MODULE_3__.store.userInfo = response.userInfo;
          }
          if (response.flash_messages) {
            currentObj.flashMessages = response.flash_messages;
          }
          if (response.redirect_url) {
            currentObj.$inertia.get(response.redirect_url);
          }
          if (response.location_url) {
            window.location.href = response.location_url;
          }
        } else if (response.message) {
          currentObj.showErrorToast(response.message);
        } else {
          currentObj.showErrorToast('Something went wront, please try again.');
        }
      })["catch"](function (e) {
        currentObj.isProcessing = false;
        var response = e.response.data;
        if (response.errors) {
          currentObj.errors = response.errors;
        }
        if (response.message) {
          currentObj.showErrorToast(response.message);
        }
      });
    }
  },
  components: {
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__.Link,
    ForgotPasswordWrapper: _styles_ForgotPasswordWrapper__WEBPACK_IMPORTED_MODULE_6__.ForgotPasswordWrapper,
    FlashMessage: _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  props: ["search_data", "seo_data", "flash_messages", "LOGIN_URL"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/login.vue?vue&type=script&lang=js":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/login.vue?vue&type=script&lang=js ***!
  \*****************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vue3_styled_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue3-styled-components */ "./node_modules/vue3-styled-components/dist/vue-styled-components.es.js");
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../components/FlashMessage.vue */ "./resources/js/themes/andamanisland/components/FlashMessage.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../utils/commonFuntions.js */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _styles_LoginWrapper__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../styles/LoginWrapper */ "./resources/js/themes/andamanisland/styles/LoginWrapper.js");








/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'Login',
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_2__["default"],
  created: function created() {
    _store__WEBPACK_IMPORTED_MODULE_4__.store.searchData = this.search_data;
    _store__WEBPACK_IMPORTED_MODULE_4__.store.seoData = this.seo_data;
    this.flashMessages = this.flash_messages;
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_4__.store,
      formData: {
        'user_email': '',
        'password': ''
      },
      errors: {},
      flashMessages: [],
      isProcessing: false
    };
  },
  methods: {
    showErrorToast: _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_5__.showErrorToast,
    handleChange: function handleChange(event) {
      var _event$target;
      var currentObj = this;
      if (event !== null && event !== void 0 && (_event$target = event.target) !== null && _event$target !== void 0 && _event$target.name) {
        var name = event.target.name;
        var value = event.target.value;
        var newFormData = currentObj.formData;
        newFormData[name] = value;
        currentObj.formData = newFormData;
      }
      currentObj.errors = {};
      currentObj.flashMessages = [];
    },
    handleFormSubmit: function handleFormSubmit(e) {
      e.preventDefault();
      e.stopPropagation();
      var currentObj = this;
      currentObj.errors = {};
      currentObj.flashMessages = [];
      currentObj.isProcessing = true;
      var formData = currentObj.formData;
      axios__WEBPACK_IMPORTED_MODULE_6___default().post('/account/login', formData).then(function (resp) {
        currentObj.isProcessing = false;
        var response = resp.data;
        if (response.success) {
          if (response.userInfo) {
            _store__WEBPACK_IMPORTED_MODULE_4__.store.userInfo = response.userInfo;
          }
          if (response.flash_messages) {
            currentObj.flashMessages = response.flash_messages;
          }
          if (response.redirect_url) {
            currentObj.$inertia.get(response.redirect_url);
          }
          if (response.location_url) {
            window.location.href = response.location_url;
          }
        } else if (response.message) {
          currentObj.showErrorToast(response.message);
        } else {
          currentObj.showErrorToast('Something went wront, please try again.');
        }
      })["catch"](function (e) {
        currentObj.isProcessing = false;
        var response = e.response.data;
        if (response.errors) {
          currentObj.errors = response.errors;
        }
        if (response.message) {
          currentObj.showErrorToast(response.message);
        }
      });
    }
  },
  components: {
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_1__.Link,
    LoginWrapper: _styles_LoginWrapper__WEBPACK_IMPORTED_MODULE_7__.LoginWrapper,
    FlashMessage: _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_3__["default"]
  },
  props: ["search_data", "seo_data", "flash_messages", "FORGOT_PASSWORD_URL", "GOOGLE_LOGIN_URL", "ACCOUNT_SIGNUP_URL", "AGENT_SIGNUP_URL", "VENDOR_LOGIN_URL"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/otp.vue?vue&type=script&lang=js":
/*!***************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/otp.vue?vue&type=script&lang=js ***!
  \***************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/FlashMessage.vue */ "./resources/js/themes/andamanisland/components/FlashMessage.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../utils/commonFuntions.js */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _styles_OtpPageWrapper__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../styles/OtpPageWrapper */ "./resources/js/themes/andamanisland/styles/OtpPageWrapper.js");






/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'otp',
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
  created: function created() {
    _store__WEBPACK_IMPORTED_MODULE_2__.store.searchData = this.search_data;
    _store__WEBPACK_IMPORTED_MODULE_2__.store.seoData = this.seo_data;
    this.flashMessages = this.flash_messages;
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_2__.store,
      formData: {
        'otp': ''
      },
      errors: {},
      flashMessages: [],
      isProcessing: false
    };
  },
  methods: {
    showErrorToast: _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_3__.showErrorToast,
    handleChange: function handleChange(event) {
      var _event$target;
      var currentObj = this;
      if (event !== null && event !== void 0 && (_event$target = event.target) !== null && _event$target !== void 0 && _event$target.name) {
        var name = event.target.name;
        var value = event.target.value;
        var newFormData = currentObj.formData;
        newFormData[name] = value;
        currentObj.formData = newFormData;
      }
      currentObj.errors = {};
      currentObj.flashMessages = [];
    },
    handleFormSubmit: function handleFormSubmit(e) {
      e.preventDefault();
      e.stopPropagation();
      var currentObj = this;
      currentObj.errors = {};
      currentObj.flashMessages = [];
      currentObj.isProcessing = true;
      var formData = currentObj.formData;
      axios__WEBPACK_IMPORTED_MODULE_4___default().post('/account/otp', formData).then(function (resp) {
        currentObj.isProcessing = false;
        var response = resp.data;
        if (response.success) {
          if (response.userInfo) {
            _store__WEBPACK_IMPORTED_MODULE_2__.store.userInfo = response.userInfo;
          }
          if (response.flash_messages) {
            currentObj.flashMessages = response.flash_messages;
          }
          if (response.redirect_url) {
            currentObj.$inertia.get(response.redirect_url);
          }
          if (response.location_url) {
            window.location.href = response.location_url;
          }
        } else if (response.message) {
          currentObj.showErrorToast(response.message);
        } else {
          currentObj.showErrorToast('Something went wront, please try again.');
        }
      })["catch"](function (e) {
        currentObj.isProcessing = false;
        var response = e.response.data;
        if (response.errors) {
          currentObj.errors = response.errors;
        }
        if (response.message) {
          currentObj.showErrorToast(response.message);
        }
      });
    },
    SignupResendOtp: function SignupResendOtp(e) {
      e.preventDefault();
      e.stopPropagation();
      var currentObj = this;
      currentObj.errors = {};
      currentObj.flashMessages = [];
      currentObj.isProcessing = true;
      var formData = currentObj.formData;
      axios__WEBPACK_IMPORTED_MODULE_4___default().post('/account/resend-otp', formData).then(function (resp) {
        currentObj.isProcessing = false;
        var response = resp.data;
        if (response.success) {
          if (response.flash_messages) {
            currentObj.flashMessages = response.flash_messages;
          }
          if (response.redirect_url) {
            currentObj.$inertia.get(response.redirect_url);
          }
        } else if (response.message) {
          currentObj.showErrorToast(response.message);
        } else {
          currentObj.showErrorToast('Something went wront, please try again.');
        }
      })["catch"](function (e) {
        currentObj.isProcessing = false;
        var response = e.response.data;
        if (response.errors) {
          currentObj.errors = response.errors;
        }
        if (response.message) {
          currentObj.showErrorToast(response.message);
        }
      });
    }
  },
  components: {
    OtpPageWrapper: _styles_OtpPageWrapper__WEBPACK_IMPORTED_MODULE_5__.OtpPageWrapper,
    FlashMessage: _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  props: ["search_data", "seo_data", "flash_messages"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/signup.vue?vue&type=script&lang=js":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/signup.vue?vue&type=script&lang=js ***!
  \******************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/FlashMessage.vue */ "./resources/js/themes/andamanisland/components/FlashMessage.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils/commonFuntions.js */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _styles_SignupWrapper__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../styles/SignupWrapper */ "./resources/js/themes/andamanisland/styles/SignupWrapper.js");





//import countryCodes from '../utils/CountryCodes.json';


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'signup',
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
  created: function created() {
    _store__WEBPACK_IMPORTED_MODULE_3__.store.searchData = this.search_data;
    _store__WEBPACK_IMPORTED_MODULE_3__.store.seoData = this.seo_data;
    this.flashMessages = this.flash_messages;
    if (this.isAgent) {
      var formData = this.formData;
      formData['is_agent'] = this.isAgent;
      this.formData = formData;
    }
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_3__.store,
      countryCodes: [],
      formData: {
        'is_agent': 0,
        'name': this.social_name,
        'country_code': '',
        'phone': '',
        'email': this.social_email,
        'password': '',
        'confirm_password': '',
        'termsuse': 0
      },
      errors: {},
      flashMessages: [],
      isProcessing: false
    };
  },
  methods: {
    showErrorToast: _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_4__.showErrorToast,
    onCountryCodeClick: function onCountryCodeClick(e) {
      var currentObj = this;
      axios__WEBPACK_IMPORTED_MODULE_5___default().post(_store__WEBPACK_IMPORTED_MODULE_3__.store.websiteSettings.COUNTRY_CODE_URL, {
        type: e
      }).then(function (response) {
        currentObj.countryCodes = response.data.options;
      });
    },
    handleChange: function handleChange(event) {
      var _event$target;
      var currentObj = this;
      if (event !== null && event !== void 0 && (_event$target = event.target) !== null && _event$target !== void 0 && _event$target.name) {
        var name = event.target.name;
        var value = event.target.value;
        if (name == 'termsuse') {
          if (event.target.checked) {
            value = 1;
          } else {
            value = 0;
          }
        }
        var newFormData = currentObj.formData;
        newFormData[name] = value;
        currentObj.formData = newFormData;
      }
      currentObj.errors = {};
      currentObj.flashMessages = [];
    },
    handleFormSubmit: function handleFormSubmit(e) {
      e.preventDefault();
      e.stopPropagation();
      var currentObj = this;
      currentObj.errors = {};
      currentObj.flashMessages = [];
      currentObj.isProcessing = true;
      var formData = currentObj.formData;
      axios__WEBPACK_IMPORTED_MODULE_5___default().post(this.SIGNUP_URL, formData).then(function (resp) {
        currentObj.isProcessing = false;
        var response = resp.data;
        if (response.success) {
          if (response.userInfo) {
            _store__WEBPACK_IMPORTED_MODULE_3__.store.userInfo = response.userInfo;
          }
          if (response.flash_messages) {
            currentObj.flashMessages = response.flash_messages;
          }
          if (response.redirect_url) {
            currentObj.$inertia.get(response.redirect_url);
          }
          if (response.location_url) {
            window.location.href = response.location_url;
          }
        } else if (response.message) {
          currentObj.showErrorToast(response.message);
        } else {
          currentObj.showErrorToast('Something went wront, please try again.');
        }
      })["catch"](function (e) {
        currentObj.isProcessing = false;
        var response = e.response.data;
        if (response.errors) {
          currentObj.errors = response.errors;
        }
        if (response.message) {
          currentObj.showErrorToast(response.message);
        }
      });
    }
  },
  components: {
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__.Link,
    SignupWrapper: _styles_SignupWrapper__WEBPACK_IMPORTED_MODULE_6__.SignupWrapper,
    FlashMessage: _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  props: ["search_data", "seo_data", "flash_messages", "SIGNUP_URL", "LOGIN_URL", "GOOGLE_LOGIN_URL", "isAgent", "social_signup", "social_name", "social_email"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/vendorlogin.vue?vue&type=script&lang=js":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/vendorlogin.vue?vue&type=script&lang=js ***!
  \***********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var vue3_styled_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue3-styled-components */ "./node_modules/vue3-styled-components/dist/vue-styled-components.es.js");
/* harmony import */ var _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../components/FlashMessage.vue */ "./resources/js/themes/andamanisland/components/FlashMessage.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../utils/commonFuntions.js */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_6__);
var _templateObject;
function _taggedTemplateLiteral(e, t) { return t || (t = e.slice(0)), Object.freeze(Object.defineProperties(e, { raw: { value: Object.freeze(t) } })); }







var LoginWrapper = vue3_styled_components__WEBPACK_IMPORTED_MODULE_2__["default"].section(_templateObject || (_templateObject = _taggedTemplateLiteral(["\npadding: 10rem 0 3rem;\n& .loginForm {\n    max-width: 25rem;\n    padding: 2rem;\n    margin: 0 auto;\n    border-radius: 8px;\n    background-color: #fff;\n    box-shadow: 0 0 17px 0 rgba(0,0,0,.09);\n    &>a{\n        background: 0 0;\n        color: #5ca1ff;\n        text-transform: none;\n        width: 16rem;\n        display: block;\n        box-shadow: 0px 3px 3px 0px rgba(64,60,67,.16);\n        padding: 10px;\n        text-align: center;\n        border-radius: 5px;\n        margin: 0 auto;\n        border: 1px solid var(--black60);\n        &>img{\n            width: 2rem;\n            display: inline-block;\n            margin-right: 0.3rem;\n        }\n    }\n}\n& .border_line {\n    text-align: center;\n    position: relative;\n    padding-bottom: 0.2rem;\n    border: 0;\n    font-size: .8rem;\n    margin-top: 1rem;\n    &:before,\n    &:after{\n        content: '';\n        position: absolute;\n        width: 3rem;\n        background: #ddd;\n        height: 2px;\n        top: 0.5rem;\n    }\n    &:before{\n        right: 0;\n    }\n    &:after{\n        left: 0;\n    }\n}\n& .forgot_pass {\n    font-size: 0.8rem;\n    color: #3737b1;\n    text-decoration: underline;\n    margin-top: 8px;\n    display: block;\n}\n& .submit_btn {\n    padding: 1rem 0;\n    & .btn {\n        color: var(--white);\n        background: var(--theme-color);\n        border-radius: 5rem;\n        padding: 0.4rem 1.5rem;\n        font-size: .8rem;\n    }\n}\n.create_account a {\n    color: var(--theme-color);\n    text-decoration: underline;\n}\n@media (max-width: 767px){\n        padding: 6rem 0 3rem;\n    \n}\n"])));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'vendorlogin',
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
  created: function created() {
    _store__WEBPACK_IMPORTED_MODULE_4__.store.searchData = this.search_data;
    _store__WEBPACK_IMPORTED_MODULE_4__.store.seoData = this.seo_data;
    this.flashMessages = this.flash_messages;
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_4__.store,
      formData: {
        'username': '',
        'password': '',
        'is_vendor': 1,
        'otp': ''
      },
      errors: {},
      flashMessages: [],
      isProcessing: false,
      activeForm: 'login',
      No_OTP: false
    };
  },
  methods: {
    showErrorToast: _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_5__.showErrorToast,
    showSuccessToast: _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_5__.showSuccessToast,
    handleChange: function handleChange(event) {
      var _event$target;
      var currentObj = this;
      if (event !== null && event !== void 0 && (_event$target = event.target) !== null && _event$target !== void 0 && _event$target.name) {
        var name = event.target.name;
        var value = event.target.value;
        var newFormData = currentObj.formData;
        newFormData[name] = value;
        currentObj.formData = newFormData;
      }
      currentObj.errors = {};
      currentObj.flashMessages = [];
    },
    handleFormSubmit: function handleFormSubmit(e, action) {
      var currentObj = this;
      if (currentObj.No_OTP) {
        return true;
      } else {
        e.preventDefault();
        e.stopPropagation();
        currentObj.errors = {};
        currentObj.flashMessages = [];
        currentObj.isProcessing = true;
        var formData = currentObj.formData;
        axios__WEBPACK_IMPORTED_MODULE_6___default().post(currentObj.VENDOR_AJAX_AUTH_URL, formData).then(function (resp) {
          currentObj.isProcessing = false;
          var response = resp.data;
          if (response.success) {
            if (response.message == 'No_OTP') {
              currentObj.No_OTP = true;
              currentObj.$refs.loginForm.submit();
            } else {
              currentObj.activeForm = 'otp';
              if (action == 'resendOtp') {
                currentObj.showSuccessToast('OTP has been resent successfully.');
              }
            }
            if (response.userInfo) {
              _store__WEBPACK_IMPORTED_MODULE_4__.store.userInfo = response.userInfo;
            }
            if (response.flash_messages) {
              currentObj.flashMessages = response.flash_messages;
            }
            if (response.redirect_url) {
              currentObj.$inertia.get(response.redirect_url);
            }
            if (response.location_url) {
              window.location.href = response.location_url;
            }
          } else if (response.message) {
            currentObj.showErrorToast(response.message);
          } else {
            currentObj.showErrorToast('Something went wront, please try again.');
          }
        })["catch"](function (e) {
          currentObj.isProcessing = false;
          if (e.response && e.response.data) {
            var response = e.response.data;
            if (response.errors) {
              currentObj.errors = response.errors;
            }
            if (response.message) {
              currentObj.showErrorToast(response.message);
            }
          }
        });
      }
    },
    handleOtpSubmit: function handleOtpSubmit(e) {
      e.preventDefault();
      e.stopPropagation();
      var currentObj = this;
      currentObj.errors = {};
      currentObj.flashMessages = [];
      currentObj.isProcessing = true;
      var formData = currentObj.formData;
      axios__WEBPACK_IMPORTED_MODULE_6___default().post(currentObj.VENDOR_LOGIN_OTP_CHECK_URL, formData).then(function (resp) {
        currentObj.isProcessing = false;
        var response = resp.data;
        if (response.success) {
          currentObj.No_OTP = true;
          currentObj.$refs.loginForm.submit();
          if (response.userInfo) {
            _store__WEBPACK_IMPORTED_MODULE_4__.store.userInfo = response.userInfo;
          }
          if (response.flash_messages) {
            currentObj.flashMessages = response.flash_messages;
          }
          if (response.redirect_url) {
            currentObj.$inertia.get(response.redirect_url);
          }
          if (response.location_url) {
            window.location.href = response.location_url;
          }
        } else if (response.message) {
          currentObj.showErrorToast(response.message);
        } else {
          currentObj.showErrorToast('Something went wront, please try again.');
        }
      })["catch"](function (e) {
        currentObj.isProcessing = false;
        if (e.response && e.response.data) {
          var response = e.response.data;
          if (response.errors) {
            currentObj.errors = response.errors;
          }
          if (response.message) {
            currentObj.showErrorToast(response.message);
          }
        }
      });
    },
    changeUser: function changeUser(event) {
      var currentObj = this;
      currentObj.activeForm = 'login';
    }
  },
  components: {
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__.Link,
    LoginWrapper: LoginWrapper,
    FlashMessage: _components_FlashMessage_vue__WEBPACK_IMPORTED_MODULE_3__["default"]
  },
  props: ["search_data", "seo_data", "flash_messages", "csrfToken", "ACTION_URL", "VENDOR_AJAX_AUTH_URL", "VENDOR_LOGIN_OTP_CHECK_URL", "LOGIN_URL", "FORGOT_PASSWORD_URL", "GOOGLE_LOGIN_URL", "ACCOUNT_SIGNUP_URL", "AGENT_SIGNUP_URL", "VENDOR_LOGIN_URL"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Error.vue?vue&type=template&id=2953b7d7":
/*!*************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Error.vue?vue&type=template&id=2953b7d7 ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "content-section not_found_page"
};
var _hoisted_2 = {
  "class": "xl:container xl:mx-auto text-center"
};
var _hoisted_3 = {
  "class": "font-semibold"
};
var _hoisted_4 = {
  "class": "py-3"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Link = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Link");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("section", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", _hoisted_3, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.title), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.description), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
    href: "/",
    "class": "btn theme_clr text-sm rounded-full",
    style: {
      "display": "inline-block"
    }
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Go to Home")];
    }),
    _: 1 /* STABLE */
  })])]);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Home.vue?vue&type=template&id=363c65e0&scoped=true":
/*!************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Home.vue?vue&type=template&id=363c65e0&scoped=true ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _withScopeId = function _withScopeId(n) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.pushScopeId)("data-v-363c65e0"), n = n(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.popScopeId)(), n;
};
var _hoisted_1 = {
  "class": "home_bg"
};
var _hoisted_2 = {
  "class": "blog_bg"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _$data$store$websiteS, _$data$store$websiteS2, _$data$store$websiteS3, _$data$store$websiteS4, _$data$store$websiteS5;
  var _component_SearchFormWithBanner = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("SearchFormWithBanner");
  var _component_AboutHomePage = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("AboutHomePage");
  var _component_HomeDestinationGallery = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HomeDestinationGallery");
  var _component_HomePackageSlider = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HomePackageSlider");
  var _component_ScubaDivingSection = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ScubaDivingSection");
  var _component_HotelTabs = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HotelTabs");
  var _component_HomeTestimonialSlider = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HomeTestimonialSlider");
  var _component_CertifySection = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("CertifySection");
  var _component_WeddingAtBeach = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("WeddingAtBeach");
  var _component_HomeBlogSlider = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HomeBlogSlider");
  var _component_HomeBlogGrid = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HomeBlogGrid");
  var _component_FaqSection = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("FaqSection");
  var _component_HomeExperience = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("HomeExperience");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_SearchFormWithBanner, {
    isHomepage: true
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_AboutHomePage), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HomeDestinationGallery, {
    title: "Top Islands in Andaman & Nicobar",
    limit: 6
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HomePackageSlider, {
    isActivity: 0,
    featured: 1,
    limit: 10,
    title: "Andaman Luxury Packages",
    subTitle: "We have some exciting Andaman luxury packages that you can explore with your friends and family.",
    viewAllUrl: "".concat((_$data$store$websiteS = $data.store.websiteSettings) === null || _$data$store$websiteS === void 0 ? void 0 : _$data$store$websiteS.TOUR_CATEGORY_DETAIL_URL, "/andaman-luxury-packages"),
    viewAllTitle: "View All",
    itemsPerView: 4,
    themeCategorySlug: "andaman-luxury-packages"
  }, null, 8 /* PROPS */, ["viewAllUrl"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HomePackageSlider, {
    isActivity: 0,
    featured: 1,
    limit: 10,
    title: "Economical Andaman Tour Packages",
    subTitle: "Choose from a wide range of economical Andaman tour packages & enjoy the beautiful state of sun, sand & sea.",
    viewAllUrl: "".concat((_$data$store$websiteS2 = $data.store.websiteSettings) === null || _$data$store$websiteS2 === void 0 ? void 0 : _$data$store$websiteS2.TOUR_CATEGORY_DETAIL_URL, "/economical-andaman-tour-packages"),
    viewAllTitle: "View All",
    itemsPerView: 4,
    themeCategorySlug: "economical-andaman-tour-packages"
  }, null, 8 /* PROPS */, ["viewAllUrl"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HomePackageSlider, {
    isActivity: 0,
    featured: 1,
    limit: 10,
    title: "Andaman Honeymoon Packages",
    subTitle: "If you are planning to visit the Andaman for spending your honeymoon, then we present to you the best Andaman Honeymoon Packages.",
    viewAllUrl: "".concat((_$data$store$websiteS3 = $data.store.websiteSettings) === null || _$data$store$websiteS3 === void 0 ? void 0 : _$data$store$websiteS3.TOUR_CATEGORY_DETAIL_URL, "/andaman-honeymoon-packages"),
    viewAllTitle: "View All",
    itemsPerView: 4,
    themeCategorySlug: "andaman-honeymoon-packages"
  }, null, 8 /* PROPS */, ["viewAllUrl"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HomePackageSlider, {
    isActivity: 1,
    featured: 1,
    limit: 10,
    title: "Romantic Candle Light Dinner In Andaman Islands",
    subTitle: "Make your evening unique and unforgettable with your loved one by having a romantic candlelight meal on Havelock Island's gorgeous beaches.",
    viewAllUrl: "".concat((_$data$store$websiteS4 = $data.store.websiteSettings) === null || _$data$store$websiteS4 === void 0 ? void 0 : _$data$store$websiteS4.TOUR_CATEGORY_DETAIL_URL, "/romantic-candle-light-dinner-in-andaman-islands"),
    viewAllTitle: "View All",
    itemsPerView: 4,
    themeCategorySlug: "romantic-candle-light-dinner-in-andaman-islands"
  }, null, 8 /* PROPS */, ["viewAllUrl"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HomePackageSlider, {
    isActivity: 1,
    featured: 1,
    limit: 10,
    title: "Andaman Water Activities Tour Packages",
    subTitle: "Choose from a wide range of economical Andaman tour packages & enjoy the beautiful state of sun, sand & sea.",
    viewAllUrl: "".concat((_$data$store$websiteS5 = $data.store.websiteSettings) === null || _$data$store$websiteS5 === void 0 ? void 0 : _$data$store$websiteS5.TOUR_CATEGORY_DETAIL_URL, "/water-sports-in-andaman-islands"),
    viewAllTitle: "View All",
    itemsPerView: 4,
    themeCategorySlug: "water-sports-in-andaman-islands"
  }, null, 8 /* PROPS */, ["viewAllUrl"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <HomePackageSlider :isActivity=\"1\" :featured=\"1\" :limit=\"10\" title=\"Andaman Water Sports\" :viewAllUrl=\"`${store.websiteSettings?.TOUR_CATEGORY_DETAIL_URL}/andaman-water-sports`\" viewAllTitle=\"View All\" :itemsPerView=\"4\" themeCategorySlug=\"water-sports-in-andaman-islands\" /> ")]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ScubaDivingSection), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HotelTabs), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HomeTestimonialSlider, {
    featured: "1",
    limit: "10"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_CertifySection), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_WeddingAtBeach), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <HomeDestinationSlider2/> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HomeBlogSlider, {
    featured: "1",
    limit: "4",
    title: "Andaman Islands Travel Guide",
    subTitle: "Whether you're looking for a romantic getaway, a family-friendly adventure, or a solo journey to explore the world, a travel agency can provide you with a custom-tailored itinerary that exceeds your expectations.",
    className: "pb-0",
    categorySlug: "travel-guide"
  })]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <HomeBlogSlider featured=\"1\" limit=\"4\" title=\"Our Latest Blog\" className=\"pt-0\" /> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HomeBlogGrid, {
    featured: "1",
    limit: "5",
    title: "Blogs & News",
    subTitle: "Roaming Tales"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_FaqSection, {
    identitfer: "home",
    title: "Andaman Islands FAQs",
    faqs_sections: $props.faqs
  }, null, 8 /* PROPS */, ["faqs_sections"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <ClientsSection :clients=\"clients\"/> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_HomeExperience)], 64 /* STABLE_FRAGMENT */);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/agent-signup.vue?vue&type=template&id=00bbc2e3":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/agent-signup.vue?vue&type=template&id=00bbc2e3 ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");
/* harmony import */ var _assets_images_google_logo_png__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../assets/images/google_logo.png */ "./resources/js/themes/andamanisland/assets/images/google_logo.png");


var _hoisted_1 = {
  "class": "container"
};
var _hoisted_2 = {
  "class": "signupForm"
};
var _hoisted_3 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h1", {
  "class": "text-2xl text-center mb-4 fw500"
}, "Travel Agent Signup", -1 /* HOISTED */);
var _hoisted_4 = ["href"];
var _hoisted_5 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: _assets_images_google_logo_png__WEBPACK_IMPORTED_MODULE_1__["default"],
  alt: ""
}, null, -1 /* HOISTED */);
var _hoisted_6 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "border_line"
}, "Or continue with username / email", -1 /* HOISTED */);
var _hoisted_7 = {
  "class": "signup_form"
};
var _hoisted_8 = {
  "class": "singup_form_inner"
};
var _hoisted_9 = {
  "class": "form_group w-1/2"
};
var _hoisted_10 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "company_name",
  "class": "text-sm"
}, "Company Registered Name", -1 /* HOISTED */);
var _hoisted_11 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_12 = {
  "class": "form_group w-1/2"
};
var _hoisted_13 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "company_brand",
  "class": "text-sm"
}, "Company Brand/Trade Name", -1 /* HOISTED */);
var _hoisted_14 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_15 = {
  "class": "form_group w-1/2"
};
var _hoisted_16 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "pan_no",
  "class": "text-sm"
}, "PAN Number", -1 /* HOISTED */);
var _hoisted_17 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_18 = {
  "class": "form_group w-1/2"
};
var _hoisted_19 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "pan_image",
  "class": "text-sm"
}, "Upload PAN Card", -1 /* HOISTED */);
var _hoisted_20 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_21 = {
  "class": "form_group w-1/2"
};
var _hoisted_22 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "gst_no",
  "class": "text-sm"
}, "GST Number", -1 /* HOISTED */);
var _hoisted_23 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_24 = {
  "class": "form_group w-1/2"
};
var _hoisted_25 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "gst_image",
  "class": "text-sm"
}, "Upload GST", -1 /* HOISTED */);
var _hoisted_26 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_27 = {
  "class": "form_group w-1/2"
};
var _hoisted_28 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "company_owner_name",
  "class": "text-sm"
}, "Company Owner Name", -1 /* HOISTED */);
var _hoisted_29 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_30 = {
  "class": "form_group w-1/2"
};
var _hoisted_31 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "agent_logo",
  "class": "text-sm"
}, "Agent Logo", -1 /* HOISTED */);
var _hoisted_32 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_33 = {
  "class": "form_group w-1/2"
};
var _hoisted_34 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "bookings_per_month",
  "class": "text-sm"
}, "Bookings Per Month", -1 /* HOISTED */);
var _hoisted_35 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
  value: ""
}, "--Select--", -1 /* HOISTED */);
var _hoisted_36 = ["value", "selected"];
var _hoisted_37 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_38 = {
  "class": "form_group w-1/2"
};
var _hoisted_39 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "no_of_employees",
  "class": "text-sm"
}, "Number of Employees?", -1 /* HOISTED */);
var _hoisted_40 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
  value: ""
}, "--Select--", -1 /* HOISTED */);
var _hoisted_41 = ["value", "selected"];
var _hoisted_42 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_43 = {
  "class": "form_group w-1/2"
};
var _hoisted_44 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "agency_age",
  "class": "text-sm"
}, "How old is you agency?", -1 /* HOISTED */);
var _hoisted_45 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
  value: ""
}, "--Select--", -1 /* HOISTED */);
var _hoisted_46 = ["value", "selected"];
var _hoisted_47 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_48 = {
  "class": "form_group w-1/2"
};
var _hoisted_49 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "website",
  "class": "text-sm"
}, "Website", -1 /* HOISTED */);
var _hoisted_50 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_51 = {
  "class": "form_group w-1/2"
};
var _hoisted_52 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "destinations_sell_most",
  "class": "text-sm"
}, "Destinations you sell the most", -1 /* HOISTED */);
var _hoisted_53 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_54 = {
  "class": "form_group w-1/2 phone_group"
};
var _hoisted_55 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "whatsapp_phone",
  "class": "text-sm"
}, "Whatsapp Number", -1 /* HOISTED */);
var _hoisted_56 = ["value", "selected"];
var _hoisted_57 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_58 = {
  "class": "form_group w-1/2"
};
var _hoisted_59 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "region",
  "class": "text-sm"
}, "Your current travelers are from which region? (for international user)", -1 /* HOISTED */);
var _hoisted_60 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
  value: ""
}, "--Select--", -1 /* HOISTED */);
var _hoisted_61 = ["value", "selected"];
var _hoisted_62 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_63 = {
  "class": "form_group w-1/2"
};
var _hoisted_64 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "source",
  "class": "text-sm"
}, "Where did you hear about us?", -1 /* HOISTED */);
var _hoisted_65 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
  value: ""
}, "--Select--", -1 /* HOISTED */);
var _hoisted_66 = ["value", "selected"];
var _hoisted_67 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_68 = {
  "class": "form_group w-full"
};
var _hoisted_69 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "company_profile",
  "class": "text-sm"
}, "Company Profile (Specialization, year of registration, company address, annual revenue)", -1 /* HOISTED */);
var _hoisted_70 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_71 = {
  "class": "form_group submit_btn"
};
var _hoisted_72 = {
  key: 0,
  type: "button",
  "class": "btn",
  disabled: ""
};
var _hoisted_73 = {
  key: 1,
  type: "submit",
  "class": "btn",
  name: "submit"
};
var _hoisted_74 = {
  "class": "create_account text-sm mb-2"
};
var _hoisted_75 = ["href"];
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_FlashMessage = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("FlashMessage");
  var _component_AgentSignupWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("AgentSignupWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_AgentSignupWrapper, {
    "class": "signup_page_section"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [_hoisted_3, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: _ctx.GOOGLE_LOGIN_URL
      }, [_hoisted_5, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Continue with Google ")], 8 /* PROPS */, _hoisted_4), _hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_FlashMessage, {
        flashMessages: _this.flashMessages
      }, null, 8 /* PROPS */, ["flashMessages"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
        onSubmit: _cache[18] || (_cache[18] = function () {
          return $options.handleFormSubmit && $options.handleFormSubmit.apply($options, arguments);
        })
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_9, [_hoisted_10, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "company_name",
        "class": "form_control",
        onChange: _cache[0] || (_cache[0] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["company_name"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_11, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['company_name'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [_hoisted_13, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "company_brand",
        "class": "form_control",
        onChange: _cache[1] || (_cache[1] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["company_brand"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_14, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['company_brand'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, [_hoisted_16, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "pan_no",
        "class": "form_control",
        onChange: _cache[2] || (_cache[2] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["pan_no"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_17, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['pan_no'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_18, [_hoisted_19, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "file",
        name: "pan_image",
        "class": "form_control",
        onChange: _cache[3] || (_cache[3] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["pan_image"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_20, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['pan_image'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_21, [_hoisted_22, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "gst_no",
        "class": "form_control",
        onChange: _cache[4] || (_cache[4] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["gst_no"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_23, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['gst_no'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_24, [_hoisted_25, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "file",
        name: "gst_image",
        "class": "form_control",
        onChange: _cache[5] || (_cache[5] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["gst_image"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_26, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['gst_image'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_27, [_hoisted_28, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "company_owner_name",
        "class": "form_control",
        onChange: _cache[6] || (_cache[6] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["company_owner_name"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_29, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['company_owner_name'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_30, [_hoisted_31, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "file",
        name: "agent_logo",
        "class": "form_control",
        onChange: _cache[7] || (_cache[7] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["agent_logo"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_32, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['agent_logo'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_33, [_hoisted_34, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
        "class": "form_control",
        name: "bookings_per_month",
        onChange: _cache[8] || (_cache[8] = function ($event) {
          return $options.handleChange($event);
        })
      }, [_hoisted_35, ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.bookings_every_months, function (val, key) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("option", {
          value: key,
          selected: key == _this.formData.bookings_per_month
        }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(val), 9 /* TEXT, PROPS */, _hoisted_36);
      }), 256 /* UNKEYED_FRAGMENT */))], 32 /* NEED_HYDRATION */), $data.errors["bookings_per_month"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_37, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['bookings_per_month'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_38, [_hoisted_39, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
        "class": "form_control",
        name: "no_of_employees",
        onChange: _cache[9] || (_cache[9] = function ($event) {
          return $options.handleChange($event);
        })
      }, [_hoisted_40, ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.total_no_of_employees, function (val, key) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("option", {
          value: key,
          selected: key == _this.formData.no_of_employees
        }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(val), 9 /* TEXT, PROPS */, _hoisted_41);
      }), 256 /* UNKEYED_FRAGMENT */))], 32 /* NEED_HYDRATION */), $data.errors["no_of_employees"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_42, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['no_of_employees'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_43, [_hoisted_44, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
        "class": "form_control",
        name: "agency_age",
        onChange: _cache[10] || (_cache[10] = function ($event) {
          return $options.handleChange($event);
        })
      }, [_hoisted_45, ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.agency_durations, function (val, key) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("option", {
          value: key,
          selected: key == _this.formData.agency_age
        }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(val), 9 /* TEXT, PROPS */, _hoisted_46);
      }), 256 /* UNKEYED_FRAGMENT */))], 32 /* NEED_HYDRATION */), $data.errors["agency_age"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_47, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['agency_age'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_48, [_hoisted_49, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "website",
        "class": "form_control",
        autocomplete: "off",
        onChange: _cache[11] || (_cache[11] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["website"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_50, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['website'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_51, [_hoisted_52, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "destinations_sell_most",
        "class": "form_control",
        autocomplete: "off",
        onChange: _cache[12] || (_cache[12] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["destinations_sell_most"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_53, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['destinations_sell_most'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_54, [_hoisted_55, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
        "class": "form_control",
        name: "whatsapp_country_code",
        onChange: _cache[13] || (_cache[13] = function ($event) {
          return $options.handleChange($event);
        })
      }, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($data.countryCodes, function (country) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("option", {
          value: country.dial_code,
          selected: country.dial_code == '+91'
        }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(country.dial_code), 9 /* TEXT, PROPS */, _hoisted_56);
      }), 256 /* UNKEYED_FRAGMENT */))], 32 /* NEED_HYDRATION */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "whatsapp_phone",
        "class": "form_control",
        onkeyup: "if (/\\D/g.test(this.value)) this.value = this.value.replace(/\\D/g,'')",
        maxlength: "12",
        onChange: _cache[14] || (_cache[14] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["whatsapp_phone"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_57, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['whatsapp_phone'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_58, [_hoisted_59, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
        "class": "form_control",
        name: "region",
        onChange: _cache[15] || (_cache[15] = function ($event) {
          return $options.handleChange($event);
        })
      }, [_hoisted_60, ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.traveler_regions, function (val, key) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("option", {
          value: key,
          selected: key == _this.formData.region
        }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(val), 9 /* TEXT, PROPS */, _hoisted_61);
      }), 256 /* UNKEYED_FRAGMENT */))], 32 /* NEED_HYDRATION */), $data.errors["region"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_62, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['region'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_63, [_hoisted_64, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
        "class": "form_control",
        name: "source",
        onChange: _cache[16] || (_cache[16] = function ($event) {
          return $options.handleChange($event);
        })
      }, [_hoisted_65, ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.source_types, function (val, key) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("option", {
          value: key,
          selected: key == _this.formData.source
        }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(val), 9 /* TEXT, PROPS */, _hoisted_66);
      }), 256 /* UNKEYED_FRAGMENT */))], 32 /* NEED_HYDRATION */), $data.errors["source"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_67, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['source'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_68, [_hoisted_69, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "company_profile",
        "class": "form_control",
        onChange: _cache[17] || (_cache[17] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["company_profile"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_70, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['company_profile'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_71, [_this.isProcessing ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_72, "Processing")) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_73, "Submit"))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_74, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Skip as a customer "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: $props.PROFILE_URL
      }, "Click here!", 8 /* PROPS */, _hoisted_75)])])])], 32 /* NEED_HYDRATION */)])])];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/change_password.vue?vue&type=template&id=40875897":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/change_password.vue?vue&type=template&id=40875897 ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************/
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
  "class": "change_Password_sec"
};
var _hoisted_3 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h1", {
  "class": "text-2xl text-center mb-4 fw500"
}, "Change Password", -1 /* HOISTED */);
var _hoisted_4 = {
  "class": "login_form"
};
var _hoisted_5 = {
  "class": "form_group"
};
var _hoisted_6 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "text-sm"
}, "New Password", -1 /* HOISTED */);
var _hoisted_7 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_8 = {
  "class": "form_group"
};
var _hoisted_9 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "text-sm"
}, "Confirm Password", -1 /* HOISTED */);
var _hoisted_10 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_11 = {
  "class": "form_group submit_btn"
};
var _hoisted_12 = {
  key: 0,
  type: "button",
  "class": "btn",
  disabled: ""
};
var _hoisted_13 = {
  key: 1,
  type: "submit",
  "class": "btn",
  name: "submit"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_FlashMessage = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("FlashMessage");
  var _component_ChangePasswordWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ChangePasswordWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_ChangePasswordWrapper, {
    "class": "change_password_page"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [_hoisted_3, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_FlashMessage, {
        flashMessages: _this.flashMessages
      }, null, 8 /* PROPS */, ["flashMessages"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
        onSubmit: _cache[2] || (_cache[2] = function () {
          return $options.handleFormSubmit && $options.handleFormSubmit.apply($options, arguments);
        })
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [_hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "password",
        name: "password",
        placeholder: "",
        "class": "form_control bg-transparent",
        autocomplete: "off",
        onChange: _cache[0] || (_cache[0] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["password"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_7, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['password'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [_hoisted_9, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "password",
        name: "confirm_password",
        placeholder: "",
        "class": "form_control bg-transparent",
        autocomplete: "off",
        onChange: _cache[1] || (_cache[1] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["confirm_password"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_10, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['confirm_password'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_11, [_this.isProcessing ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_12, "Processing...")) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_13, "Submit"))])])], 32 /* NEED_HYDRATION */)])])];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/forgot_otp.vue?vue&type=template&id=bd2e5d5c":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/forgot_otp.vue?vue&type=template&id=bd2e5d5c ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************/
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
  "class": "forgotOtpForm"
};
var _hoisted_3 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h1", {
  "class": "text-2xl text-center mb-4 fw500"
}, "Forgot Password OTP", -1 /* HOISTED */);
var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", {
  "class": "para-lg bold font15 my-2"
}, "Please Enter the OTP to Verify your Account", -1 /* HOISTED */);
var _hoisted_5 = {
  "class": "digit-group mb16",
  "data-group-name": "digits",
  "data-autosubmit": "false"
};
var _hoisted_6 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_7 = {
  "class": "form_group submit_btn"
};
var _hoisted_8 = {
  key: 0,
  type: "button",
  "class": "btn",
  disabled: ""
};
var _hoisted_9 = {
  key: 1,
  type: "submit",
  "class": "btn"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_FlashMessage = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("FlashMessage");
  var _component_ForgotOtpWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ForgotOtpWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_ForgotOtpWrapper, {
    "class": "forgot_otp_page"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [_hoisted_3, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_FlashMessage, {
        flashMessages: _this.flashMessages
      }, null, 8 /* PROPS */, ["flashMessages"]), _hoisted_4, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
        onSubmit: _cache[2] || (_cache[2] = function () {
          return $options.handleFormSubmit && $options.handleFormSubmit.apply($options, arguments);
        })
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        "class": "form_control bg-transparent",
        type: "text",
        placeholder: "X X X X X",
        name: "otp",
        onChange: _cache[0] || (_cache[0] = function ($event) {
          return $options.handleChange($event);
        }),
        maxlength: "5"
      }, null, 32 /* NEED_HYDRATION */), $data.errors["otp"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['otp'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [_this.isProcessing ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_8, "Processing...")) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_9, "Validate OTP")), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
        type: "button",
        "class": "btn",
        onClick: _cache[1] || (_cache[1] = function () {
          return $options.forgotResendOtp && $options.forgotResendOtp.apply($options, arguments);
        })
      }, "Resend OTP")])], 32 /* NEED_HYDRATION */)])])];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/forgot_password.vue?vue&type=template&id=44a87c04":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/forgot_password.vue?vue&type=template&id=44a87c04 ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************/
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
  "class": "forgotPasswordForm"
};
var _hoisted_3 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h1", {
  "class": "text-2xl text-center mb-4 fw500"
}, "Forgot Password", -1 /* HOISTED */);
var _hoisted_4 = {
  "class": "forgot_password_form"
};
var _hoisted_5 = {
  "class": "form_group"
};
var _hoisted_6 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "text-sm"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Email"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("em", null, "*")], -1 /* HOISTED */);
var _hoisted_7 = ["value"];
var _hoisted_8 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_9 = {
  "class": "form_group submit_btn"
};
var _hoisted_10 = {
  key: 0,
  type: "button",
  "class": "btn",
  disabled: ""
};
var _hoisted_11 = {
  key: 1,
  type: "submit",
  "class": "btn",
  name: "submit"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_FlashMessage = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("FlashMessage");
  var _component_Link = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Link");
  var _component_ForgotPasswordWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ForgotPasswordWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_ForgotPasswordWrapper, {
    "class": "forgot_password_page"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      var _$data$formData;
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [_hoisted_3, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_FlashMessage, {
        flashMessages: _this.flashMessages
      }, null, 8 /* PROPS */, ["flashMessages"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
        onSubmit: _cache[1] || (_cache[1] = function () {
          return $options.handleFormSubmit && $options.handleFormSubmit.apply($options, arguments);
        })
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [_hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "forgot_email",
        "class": "form_control",
        autocomplete: "off",
        value: (_$data$formData = $data.formData) === null || _$data$formData === void 0 ? void 0 : _$data$formData.forgot_email,
        onChange: _cache[0] || (_cache[0] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_7), $data.errors["forgot_email"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_8, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['forgot_email'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_9, [_this.isProcessing ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_10, "Processing...")) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_11, "Submit")), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
        href: $props.LOGIN_URL,
        "class": "btn"
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Cancel")];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["href"])])])], 32 /* NEED_HYDRATION */)])])];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/login.vue?vue&type=template&id=6a47df14":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/login.vue?vue&type=template&id=6a47df14 ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");
/* harmony import */ var _assets_images_google_logo_png__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../assets/images/google_logo.png */ "./resources/js/themes/andamanisland/assets/images/google_logo.png");


var _hoisted_1 = {
  "class": "container"
};
var _hoisted_2 = {
  "class": "loginForm"
};
var _hoisted_3 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h1", {
  "class": "text-2xl text-center mb-4 fw500"
}, "Login", -1 /* HOISTED */);
var _hoisted_4 = ["href"];
var _hoisted_5 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: _assets_images_google_logo_png__WEBPACK_IMPORTED_MODULE_1__["default"],
  alt: ""
}, null, -1 /* HOISTED */);
var _hoisted_6 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "border_line"
}, "Or continue with username / email", -1 /* HOISTED */);
var _hoisted_7 = {
  "class": "login_form"
};
var _hoisted_8 = {
  "class": "form_group"
};
var _hoisted_9 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "text-sm"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Email"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("em", null, "*")], -1 /* HOISTED */);
var _hoisted_10 = ["value"];
var _hoisted_11 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_12 = {
  "class": "form_group"
};
var _hoisted_13 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "text-sm"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Password"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("em", null, "*")], -1 /* HOISTED */);
var _hoisted_14 = ["value"];
var _hoisted_15 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_16 = {
  "class": "form_group submit_btn"
};
var _hoisted_17 = {
  key: 0,
  type: "button",
  "class": "btn",
  disabled: ""
};
var _hoisted_18 = {
  key: 1,
  type: "submit",
  "class": "btn",
  name: "submit"
};
var _hoisted_19 = {
  key: 0,
  "class": "create_account text-sm"
};
var _hoisted_20 = {
  key: 1,
  "class": "create_account text-sm"
};
var _hoisted_21 = {
  key: 2,
  "class": "create_account text-sm"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_FlashMessage = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("FlashMessage");
  var _component_Link = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Link");
  var _component_LoginWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("LoginWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_LoginWrapper, {
    "class": "login_page_section"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      var _$data$formData, _$data$formData2;
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [_hoisted_3, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: $props.GOOGLE_LOGIN_URL
      }, [_hoisted_5, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Continue with Google ")], 8 /* PROPS */, _hoisted_4), _hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
        onSubmit: _cache[2] || (_cache[2] = function () {
          return $options.handleFormSubmit && $options.handleFormSubmit.apply($options, arguments);
        })
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_FlashMessage, {
        flashMessages: _this.flashMessages
      }, null, 8 /* PROPS */, ["flashMessages"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [_hoisted_9, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "user_email",
        "class": "form_control",
        autocomplete: "off",
        value: (_$data$formData = $data.formData) === null || _$data$formData === void 0 ? void 0 : _$data$formData.user_email,
        onChange: _cache[0] || (_cache[0] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_10), $data.errors["user_email"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_11, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['user_email'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [_hoisted_13, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "password",
        name: "password",
        "class": "form_control",
        autocomplete: "off",
        value: (_$data$formData2 = $data.formData) === null || _$data$formData2 === void 0 ? void 0 : _$data$formData2.password,
        onChange: _cache[1] || (_cache[1] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_14), $data.errors["password"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_15, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['password'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
        "class": "forgot_pass text-xs pt-2",
        href: $props.FORGOT_PASSWORD_URL
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Forgot Password?")];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["href"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_16, [_this.isProcessing ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_17, "Processing...")) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_18, "Submit"))]), $props.ACCOUNT_SIGNUP_URL ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_19, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" You have not an account "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
        href: $props.ACCOUNT_SIGNUP_URL
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Signup Now")];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["href"])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props.AGENT_SIGNUP_URL ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_20, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Signup as Agent "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
        href: $props.AGENT_SIGNUP_URL
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Signup Now")];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["href"])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props.VENDOR_LOGIN_URL ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_21, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Signin as Vendor "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
        href: $props.VENDOR_LOGIN_URL
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Signin")];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["href"])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])], 32 /* NEED_HYDRATION */)])])];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/otp.vue?vue&type=template&id=5d3e5878":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/otp.vue?vue&type=template&id=5d3e5878 ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************/
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
  "class": "otpForm"
};
var _hoisted_3 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h1", {
  "class": "text-2xl text-center mb-4 fw500"
}, "OTP Verify", -1 /* HOISTED */);
var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", {
  "class": "para-lg bold font15 my-2"
}, "Please Enter the OTP to Verify your Account", -1 /* HOISTED */);
var _hoisted_5 = {
  "class": "digit-group",
  "data-group-name": "digits",
  "data-autosubmit": "false"
};
var _hoisted_6 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_7 = {
  "class": "form_group submit_btn"
};
var _hoisted_8 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
  type: "submit",
  "class": "btn"
}, "Validate OTP", -1 /* HOISTED */);
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_FlashMessage = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("FlashMessage");
  var _component_OtpPageWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("OtpPageWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_OtpPageWrapper, {
    "class": "otp_page_section"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [_hoisted_3, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_FlashMessage, {
        flashMessages: _this.flashMessages
      }, null, 8 /* PROPS */, ["flashMessages"]), _hoisted_4, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
        onSubmit: _cache[2] || (_cache[2] = function () {
          return $options.handleFormSubmit && $options.handleFormSubmit.apply($options, arguments);
        })
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        "class": "form_control bg-transparent",
        type: "text",
        placeholder: "X X X X X",
        name: "otp",
        maxlength: "5",
        onChange: _cache[0] || (_cache[0] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["otp"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['otp'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [_hoisted_8, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
        type: "button",
        "class": "btn",
        onClick: _cache[1] || (_cache[1] = function () {
          return $options.SignupResendOtp && $options.SignupResendOtp.apply($options, arguments);
        })
      }, "Resend OTP")])], 32 /* NEED_HYDRATION */)])])];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/signup.vue?vue&type=template&id=4600294a":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/signup.vue?vue&type=template&id=4600294a ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");
/* harmony import */ var _assets_images_google_logo_png__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../assets/images/google_logo.png */ "./resources/js/themes/andamanisland/assets/images/google_logo.png");


var _hoisted_1 = {
  "class": "container"
};
var _hoisted_2 = {
  "class": "signupForm"
};
var _hoisted_3 = {
  key: 0,
  "class": "text-2xl text-center mb-4 fw500"
};
var _hoisted_4 = {
  key: 1,
  "class": "text-2xl text-center mb-4 fw500"
};
var _hoisted_5 = ["href"];
var _hoisted_6 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: _assets_images_google_logo_png__WEBPACK_IMPORTED_MODULE_1__["default"],
  alt: ""
}, null, -1 /* HOISTED */);
var _hoisted_7 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "border_line"
}, "Or continue with username / email", -1 /* HOISTED */);
var _hoisted_8 = {
  "class": "signup_form"
};
var _hoisted_9 = {
  "class": "singup_form_inner"
};
var _hoisted_10 = {
  "class": "form_group w-full"
};
var _hoisted_11 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "name",
  "class": "text-sm"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Name"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("em", null, "*")], -1 /* HOISTED */);
var _hoisted_12 = ["value"];
var _hoisted_13 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_14 = {
  "class": "form_group w-1/2 phone_group"
};
var _hoisted_15 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "phone",
  "class": "text-sm"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Phone"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("em", null, "*")], -1 /* HOISTED */);
var _hoisted_16 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
  value: "+91"
}, "+91", -1 /* HOISTED */);
var _hoisted_17 = ["value", "selected"];
var _hoisted_18 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_19 = {
  "class": "form_group w-1/2"
};
var _hoisted_20 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "email",
  "class": "text-sm"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Email"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("em", null, "*")], -1 /* HOISTED */);
var _hoisted_21 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("br", null, null, -1 /* HOISTED */);
var _hoisted_22 = {
  key: 2,
  "class": "validation_error"
};
var _hoisted_23 = {
  "class": "form_group w-1/2"
};
var _hoisted_24 = {
  "for": "password",
  "class": "text-sm"
};
var _hoisted_25 = {
  key: 1
};
var _hoisted_26 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_27 = {
  "class": "form_group w-1/2"
};
var _hoisted_28 = {
  "for": "confirm_password",
  "class": "text-sm"
};
var _hoisted_29 = {
  key: 1
};
var _hoisted_30 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_31 = {
  "class": "create_account termsuse text-sm"
};
var _hoisted_32 = {
  "for": "termsuse",
  "class": "font-semibold text-xs"
};
var _hoisted_33 = ["href"];
var _hoisted_34 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_35 = {
  "class": "form_group submit_btn"
};
var _hoisted_36 = {
  key: 0,
  type: "button",
  "class": "btn",
  disabled: ""
};
var _hoisted_37 = ["disabled"];
var _hoisted_38 = {
  "class": "create_account text-sm mb-2"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_FlashMessage = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("FlashMessage");
  var _component_Link = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Link");
  var _component_SignupWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("SignupWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_SignupWrapper, {
    "class": "signup_page_section"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      var _$data$formData, _$data$store$websiteS;
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [_this.isAgent == 1 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("h1", _hoisted_3, "Travel Agent Signup")) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("h1", _hoisted_4, "Signup")), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: $props.GOOGLE_LOGIN_URL
      }, [_hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Continue with Google ")], 8 /* PROPS */, _hoisted_5), _hoisted_7, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_FlashMessage, {
        flashMessages: _this.flashMessages
      }, null, 8 /* PROPS */, ["flashMessages"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
        onSubmit: _cache[8] || (_cache[8] = function () {
          return $options.handleFormSubmit && $options.handleFormSubmit.apply($options, arguments);
        })
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_10, [_hoisted_11, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "name",
        "class": "form_control",
        value: (_$data$formData = $data.formData) === null || _$data$formData === void 0 ? void 0 : _$data$formData.name,
        onChange: _cache[0] || (_cache[0] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_12), $data.errors["name"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_13, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['name'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [_hoisted_15, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
        "class": "form_control",
        name: "country_code",
        onChange: _cache[1] || (_cache[1] = function ($event) {
          return $options.handleChange($event);
        }),
        onFocus: _cache[2] || (_cache[2] = function ($event) {
          return $options.onCountryCodeClick('country_code');
        })
      }, [_hoisted_16, _this.countryCodes && _this.countryCodes.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
        key: 0
      }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)(_this.countryCodes, function (country) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("option", {
          value: country.dial_code,
          selected: country.dial_code == '+91'
        }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(country.dial_code), 9 /* TEXT, PROPS */, _hoisted_17);
      }), 256 /* UNKEYED_FRAGMENT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)], 32 /* NEED_HYDRATION */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "phone",
        "class": "form_control",
        onkeyup: "if (/\\D/g.test(this.value)) this.value = this.value.replace(/\\D/g,'')",
        maxlength: "12",
        onChange: _cache[3] || (_cache[3] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["phone"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_18, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['phone'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_19, [_hoisted_20, $props.social_signup == 1 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
        key: 0
      }, [_hoisted_21, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)((0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props.social_email), 1 /* TEXT */)], 64 /* STABLE_FRAGMENT */)) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("input", {
        key: 1,
        type: "text",
        name: "email",
        "class": "form_control",
        autocomplete: "off",
        onChange: _cache[4] || (_cache[4] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */)), $data.errors["email"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_22, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['email'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_23, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", _hoisted_24, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Password "), $props.social_signup == 1 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
        key: 0
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" (optional) ")], 64 /* STABLE_FRAGMENT */)) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("em", _hoisted_25, "*"))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "password",
        name: "password",
        "class": "form_control",
        autocomplete: "new-password",
        onChange: _cache[5] || (_cache[5] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["password"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_26, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['password'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_27, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", _hoisted_28, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Confirm Password "), $props.social_signup == 1 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
        key: 0
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" (optional) ")], 64 /* STABLE_FRAGMENT */)) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("em", _hoisted_29, "*"))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "password",
        name: "confirm_password",
        "class": "form_control",
        autocomplete: "off",
        onChange: _cache[6] || (_cache[6] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), $data.errors["confirm_password"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_30, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['confirm_password'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_31, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "checkbox",
        name: "termsuse",
        value: "termsuse",
        onChange: _cache[7] || (_cache[7] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 32 /* NEED_HYDRATION */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", _hoisted_32, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" By signing up, you agree to the Terms of Service and "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: (_$data$store$websiteS = $data.store.websiteSettings) === null || _$data$store$websiteS === void 0 ? void 0 : _$data$store$websiteS.PRIVACY_LINK
      }, "Privacy Policy,", 8 /* PROPS */, _hoisted_33), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" including Cookie Use.")]), $data.errors["termsuse"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_34, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['termsuse'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_35, [_this.isProcessing ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_36, "Processing...")) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", {
        key: 1,
        type: "submit",
        "class": "btn",
        name: "submit",
        disabled: _this.formData.termsuse == 0
      }, "Submit", 8 /* PROPS */, _hoisted_37))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_38, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Already have an account? "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
        href: $props.LOGIN_URL
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Login Now")];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["href"])])])])], 32 /* NEED_HYDRATION */)])])];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/vendorlogin.vue?vue&type=template&id=6c46106e":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/vendorlogin.vue?vue&type=template&id=6c46106e ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");
/* harmony import */ var _assets_images_google_logo_png__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../assets/images/google_logo.png */ "./resources/js/themes/andamanisland/assets/images/google_logo.png");


var _hoisted_1 = {
  "class": "container"
};
var _hoisted_2 = {
  "class": "loginForm"
};
var _hoisted_3 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h1", {
  "class": "text-2xl text-center mb-4 fw500"
}, "Vendor Login", -1 /* HOISTED */);
var _hoisted_4 = ["href"];
var _hoisted_5 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: _assets_images_google_logo_png__WEBPACK_IMPORTED_MODULE_1__["default"],
  alt: ""
}, null, -1 /* HOISTED */);
var _hoisted_6 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "border_line"
}, "Or continue with username / email", -1 /* HOISTED */);
var _hoisted_7 = ["action"];
var _hoisted_8 = ["value"];
var _hoisted_9 = {
  "class": "login_form"
};
var _hoisted_10 = {
  "class": "form_group"
};
var _hoisted_11 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "text-sm"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Email"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("em", null, "*")], -1 /* HOISTED */);
var _hoisted_12 = ["value"];
var _hoisted_13 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_14 = {
  "class": "form_group"
};
var _hoisted_15 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "text-sm"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Password"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("em", null, "*")], -1 /* HOISTED */);
var _hoisted_16 = ["value"];
var _hoisted_17 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_18 = ["href"];
var _hoisted_19 = {
  "class": "form_group submit_btn"
};
var _hoisted_20 = {
  key: 0,
  type: "button",
  "class": "btn",
  disabled: ""
};
var _hoisted_21 = {
  key: 1,
  type: "submit",
  "class": "btn",
  name: "submit"
};
var _hoisted_22 = {
  key: 0,
  "class": "create_account text-sm"
};
var _hoisted_23 = {
  key: 1,
  "class": "create_account text-sm"
};
var _hoisted_24 = {
  key: 2,
  "class": "create_account text-sm"
};
var _hoisted_25 = {
  "class": "form-group"
};
var _hoisted_26 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "class": "col-md-12 control-label",
  style: {
    "padding-right": "0.3rem"
  }
}, "Enter the OTP sent to ", -1 /* HOISTED */);
var _hoisted_27 = {
  style: {
    "padding": "0",
    "color": "#01b3a7"
  },
  "class": "col-md-12 control-label adminEmail"
};
var _hoisted_28 = {
  "class": "w-full flex justify-between flex-row py-2"
};
var _hoisted_29 = {
  "class": "w-4/6",
  style: {
    "padding": "0"
  }
};
var _hoisted_30 = ["value"];
var _hoisted_31 = {
  key: 0,
  "class": "validation_error"
};
var _hoisted_32 = {
  "class": "w-2/6",
  style: {
    "padding-left": "0.5rem"
  }
};
var _hoisted_33 = {
  key: 0,
  type: "button",
  "class": "loginbtn text-sm",
  disabled: ""
};
var _hoisted_34 = {
  key: 1,
  type: "submit",
  "class": "loginbtn text-sm"
};
var _hoisted_35 = {
  "class": "w-full flex justify-between flex-row"
};
var _hoisted_36 = {
  "class": "forgot text-xs pt-2",
  style: {
    "margin-top": "0px"
  }
};
var _hoisted_37 = {
  key: 0,
  href: "javascript:void(0);"
};
var _hoisted_38 = {
  "class": "forgot text-xs pt-2"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_FlashMessage = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("FlashMessage");
  var _component_Link = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Link");
  var _component_LoginWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("LoginWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_LoginWrapper, {
    "class": "login_page_section"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      var _$data$formData, _$data$formData2, _$data$formData3;
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [_hoisted_3, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: $props.GOOGLE_LOGIN_URL
      }, [_hoisted_5, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Continue with Google ")], 8 /* PROPS */, _hoisted_4), _hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
        action: $props.ACTION_URL,
        method: "POST",
        onSubmit: _cache[2] || (_cache[2] = function ($event) {
          return $options.handleFormSubmit($event);
        }),
        ref: "loginForm",
        style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)("display:".concat(_this.activeForm == 'login' ? 'block' : 'none'))
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "hidden",
        name: "_token",
        value: $props.csrfToken
      }, null, 8 /* PROPS */, _hoisted_8), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_FlashMessage, {
        flashMessages: _this.flashMessages
      }, null, 8 /* PROPS */, ["flashMessages"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_10, [_hoisted_11, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        name: "username",
        "class": "form_control",
        autocomplete: "off",
        value: (_$data$formData = $data.formData) === null || _$data$formData === void 0 ? void 0 : _$data$formData.username,
        onChange: _cache[0] || (_cache[0] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_12), $data.errors["username"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_13, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['username'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [_hoisted_15, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "password",
        name: "password",
        "class": "form_control",
        autocomplete: "off",
        value: (_$data$formData2 = $data.formData) === null || _$data$formData2 === void 0 ? void 0 : _$data$formData2.password,
        onChange: _cache[1] || (_cache[1] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_16), $data.errors["password"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_17, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['password'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        "class": "forgot_pass text-xs pt-2",
        href: $props.FORGOT_PASSWORD_URL
      }, "Forgot Password?", 8 /* PROPS */, _hoisted_18), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_19, [_this.isProcessing ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_20, "Processing...")) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_21, "Submit"))]), $props.ACCOUNT_SIGNUP_URL ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_22, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" You have not an account "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
        href: $props.ACCOUNT_SIGNUP_URL
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Signup Now")];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["href"])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props.AGENT_SIGNUP_URL ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_23, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Signup as Agent "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
        href: $props.AGENT_SIGNUP_URL
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Signup Now")];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["href"])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), $props.LOGIN_URL ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_24, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" You have an account "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
        href: $props.LOGIN_URL
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Signin")];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["href"])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])], 44 /* STYLE, PROPS, NEED_HYDRATION */, _hoisted_7), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
        onSubmit: _cache[6] || (_cache[6] = function () {
          return $options.handleOtpSubmit && $options.handleOtpSubmit.apply($options, arguments);
        }),
        ref: "otpForm",
        style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)("display:".concat(_this.activeForm == 'otp' ? 'block' : 'none'))
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_25, [_hoisted_26, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", _hoisted_27, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_this.formData.username), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_28, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_29, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        "class": "form-control",
        name: "otp",
        onkeyup: "if (/\\D/g.test(this.value)) this.value = this.value.replace(/\\D/g,'')",
        maxlength: "5",
        autocomplete: "off",
        value: (_$data$formData3 = $data.formData) === null || _$data$formData3 === void 0 ? void 0 : _$data$formData3.otp,
        onChange: _cache[3] || (_cache[3] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_30), $data.errors["otp"] ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_31, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($data.errors['otp'][0]), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_32, [_this.isProcessing ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_33, "Processing...")) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("button", _hoisted_34, "Validate OTP"))])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_35, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", _hoisted_36, [_this.isProcessing ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("a", _hoisted_37, "Processing...")) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("a", {
        key: 1,
        onClick: _cache[4] || (_cache[4] = function ($event) {
          return _this.handleFormSubmit($event, 'resendOtp');
        })
      }, "Resend OTP"))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", _hoisted_38, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Click here to "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        onClick: _cache[5] || (_cache[5] = function () {
          return _this.changeUser && _this.changeUser.apply(_this, arguments);
        })
      }, "Change User")])])])], 36 /* STYLE, NEED_HYDRATION */)])])];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Home.vue?vue&type=style&index=0&id=363c65e0&scoped=true&lang=css":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Home.vue?vue&type=style&index=0&id=363c65e0&scoped=true&lang=css ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.home_bg[data-v-363c65e0]{\r\n    padding: 2.6rem 0;\r\n    margin-top: 2rem;\r\n    background-color: #f3faff;\n}\n.blog_bg[data-v-363c65e0]{\r\n    background-color: #f5f5f5;\n}\r\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Home.vue?vue&type=style&index=0&id=363c65e0&scoped=true&lang=css":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Home.vue?vue&type=style&index=0&id=363c65e0&scoped=true&lang=css ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_10_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_10_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Home_vue_vue_type_style_index_0_id_363c65e0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!../../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Home.vue?vue&type=style&index=0&id=363c65e0&scoped=true&lang=css */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Home.vue?vue&type=style&index=0&id=363c65e0&scoped=true&lang=css");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_10_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_10_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Home_vue_vue_type_style_index_0_id_363c65e0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_10_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_10_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Home_vue_vue_type_style_index_0_id_363c65e0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/themes/andamanisland/Error.vue":
/*!*****************************************************!*\
  !*** ./resources/js/themes/andamanisland/Error.vue ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Error_vue_vue_type_template_id_2953b7d7__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Error.vue?vue&type=template&id=2953b7d7 */ "./resources/js/themes/andamanisland/Error.vue?vue&type=template&id=2953b7d7");
/* harmony import */ var _Error_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Error.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/Error.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_Error_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Error_vue_vue_type_template_id_2953b7d7__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/Error.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/Home.vue":
/*!****************************************************!*\
  !*** ./resources/js/themes/andamanisland/Home.vue ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Home_vue_vue_type_template_id_363c65e0_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Home.vue?vue&type=template&id=363c65e0&scoped=true */ "./resources/js/themes/andamanisland/Home.vue?vue&type=template&id=363c65e0&scoped=true");
/* harmony import */ var _Home_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Home.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/Home.vue?vue&type=script&lang=js");
/* harmony import */ var _Home_vue_vue_type_style_index_0_id_363c65e0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Home.vue?vue&type=style&index=0&id=363c65e0&scoped=true&lang=css */ "./resources/js/themes/andamanisland/Home.vue?vue&type=style&index=0&id=363c65e0&scoped=true&lang=css");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;


const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__["default"])(_Home_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Home_vue_vue_type_template_id_363c65e0_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render],['__scopeId',"data-v-363c65e0"],['__file',"resources/js/themes/andamanisland/Home.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/agent-signup.vue":
/*!********************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/agent-signup.vue ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _agent_signup_vue_vue_type_template_id_00bbc2e3__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./agent-signup.vue?vue&type=template&id=00bbc2e3 */ "./resources/js/themes/andamanisland/account/agent-signup.vue?vue&type=template&id=00bbc2e3");
/* harmony import */ var _agent_signup_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./agent-signup.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/account/agent-signup.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_agent_signup_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_agent_signup_vue_vue_type_template_id_00bbc2e3__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/account/agent-signup.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/change_password.vue":
/*!***********************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/change_password.vue ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _change_password_vue_vue_type_template_id_40875897__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./change_password.vue?vue&type=template&id=40875897 */ "./resources/js/themes/andamanisland/account/change_password.vue?vue&type=template&id=40875897");
/* harmony import */ var _change_password_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./change_password.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/account/change_password.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_change_password_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_change_password_vue_vue_type_template_id_40875897__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/account/change_password.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/forgot_otp.vue":
/*!******************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/forgot_otp.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _forgot_otp_vue_vue_type_template_id_bd2e5d5c__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./forgot_otp.vue?vue&type=template&id=bd2e5d5c */ "./resources/js/themes/andamanisland/account/forgot_otp.vue?vue&type=template&id=bd2e5d5c");
/* harmony import */ var _forgot_otp_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./forgot_otp.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/account/forgot_otp.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_forgot_otp_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_forgot_otp_vue_vue_type_template_id_bd2e5d5c__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/account/forgot_otp.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/forgot_password.vue":
/*!***********************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/forgot_password.vue ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _forgot_password_vue_vue_type_template_id_44a87c04__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./forgot_password.vue?vue&type=template&id=44a87c04 */ "./resources/js/themes/andamanisland/account/forgot_password.vue?vue&type=template&id=44a87c04");
/* harmony import */ var _forgot_password_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./forgot_password.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/account/forgot_password.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_forgot_password_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_forgot_password_vue_vue_type_template_id_44a87c04__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/account/forgot_password.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/login.vue":
/*!*************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/login.vue ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _login_vue_vue_type_template_id_6a47df14__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./login.vue?vue&type=template&id=6a47df14 */ "./resources/js/themes/andamanisland/account/login.vue?vue&type=template&id=6a47df14");
/* harmony import */ var _login_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./login.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/account/login.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_login_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_login_vue_vue_type_template_id_6a47df14__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/account/login.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/otp.vue":
/*!***********************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/otp.vue ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _otp_vue_vue_type_template_id_5d3e5878__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./otp.vue?vue&type=template&id=5d3e5878 */ "./resources/js/themes/andamanisland/account/otp.vue?vue&type=template&id=5d3e5878");
/* harmony import */ var _otp_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./otp.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/account/otp.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_otp_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_otp_vue_vue_type_template_id_5d3e5878__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/account/otp.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/signup.vue":
/*!**************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/signup.vue ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _signup_vue_vue_type_template_id_4600294a__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./signup.vue?vue&type=template&id=4600294a */ "./resources/js/themes/andamanisland/account/signup.vue?vue&type=template&id=4600294a");
/* harmony import */ var _signup_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./signup.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/account/signup.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_signup_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_signup_vue_vue_type_template_id_4600294a__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/account/signup.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/vendorlogin.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/vendorlogin.vue ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _vendorlogin_vue_vue_type_template_id_6c46106e__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./vendorlogin.vue?vue&type=template&id=6c46106e */ "./resources/js/themes/andamanisland/account/vendorlogin.vue?vue&type=template&id=6c46106e");
/* harmony import */ var _vendorlogin_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./vendorlogin.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/account/vendorlogin.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_vendorlogin_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_vendorlogin_vue_vue_type_template_id_6c46106e__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/account/vendorlogin.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/Error.vue?vue&type=script&lang=js":
/*!*****************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/Error.vue?vue&type=script&lang=js ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Error_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Error_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Error.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Error.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/Home.vue?vue&type=script&lang=js":
/*!****************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/Home.vue?vue&type=script&lang=js ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Home_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Home_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Home.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Home.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/agent-signup.vue?vue&type=script&lang=js":
/*!********************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/agent-signup.vue?vue&type=script&lang=js ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_agent_signup_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_agent_signup_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./agent-signup.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/agent-signup.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/change_password.vue?vue&type=script&lang=js":
/*!***********************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/change_password.vue?vue&type=script&lang=js ***!
  \***********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_change_password_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_change_password_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./change_password.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/change_password.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/forgot_otp.vue?vue&type=script&lang=js":
/*!******************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/forgot_otp.vue?vue&type=script&lang=js ***!
  \******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_forgot_otp_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_forgot_otp_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./forgot_otp.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/forgot_otp.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/forgot_password.vue?vue&type=script&lang=js":
/*!***********************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/forgot_password.vue?vue&type=script&lang=js ***!
  \***********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_forgot_password_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_forgot_password_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./forgot_password.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/forgot_password.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/login.vue?vue&type=script&lang=js":
/*!*************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/login.vue?vue&type=script&lang=js ***!
  \*************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_login_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_login_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./login.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/login.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/otp.vue?vue&type=script&lang=js":
/*!***********************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/otp.vue?vue&type=script&lang=js ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_otp_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_otp_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./otp.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/otp.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/signup.vue?vue&type=script&lang=js":
/*!**************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/signup.vue?vue&type=script&lang=js ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_signup_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_signup_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./signup.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/signup.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/account/vendorlogin.vue?vue&type=script&lang=js":
/*!*******************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/vendorlogin.vue?vue&type=script&lang=js ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_vendorlogin_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_vendorlogin_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vendorlogin.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/vendorlogin.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/Error.vue?vue&type=template&id=2953b7d7":
/*!***********************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/Error.vue?vue&type=template&id=2953b7d7 ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Error_vue_vue_type_template_id_2953b7d7__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Error_vue_vue_type_template_id_2953b7d7__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Error.vue?vue&type=template&id=2953b7d7 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Error.vue?vue&type=template&id=2953b7d7");


/***/ }),

/***/ "./resources/js/themes/andamanisland/Home.vue?vue&type=template&id=363c65e0&scoped=true":
/*!**********************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/Home.vue?vue&type=template&id=363c65e0&scoped=true ***!
  \**********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Home_vue_vue_type_template_id_363c65e0_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Home_vue_vue_type_template_id_363c65e0_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Home.vue?vue&type=template&id=363c65e0&scoped=true */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Home.vue?vue&type=template&id=363c65e0&scoped=true");


/***/ }),

/***/ "./resources/js/themes/andamanisland/account/agent-signup.vue?vue&type=template&id=00bbc2e3":
/*!**************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/agent-signup.vue?vue&type=template&id=00bbc2e3 ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_agent_signup_vue_vue_type_template_id_00bbc2e3__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_agent_signup_vue_vue_type_template_id_00bbc2e3__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./agent-signup.vue?vue&type=template&id=00bbc2e3 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/agent-signup.vue?vue&type=template&id=00bbc2e3");


/***/ }),

/***/ "./resources/js/themes/andamanisland/account/change_password.vue?vue&type=template&id=40875897":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/change_password.vue?vue&type=template&id=40875897 ***!
  \*****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_change_password_vue_vue_type_template_id_40875897__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_change_password_vue_vue_type_template_id_40875897__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./change_password.vue?vue&type=template&id=40875897 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/change_password.vue?vue&type=template&id=40875897");


/***/ }),

/***/ "./resources/js/themes/andamanisland/account/forgot_otp.vue?vue&type=template&id=bd2e5d5c":
/*!************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/forgot_otp.vue?vue&type=template&id=bd2e5d5c ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_forgot_otp_vue_vue_type_template_id_bd2e5d5c__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_forgot_otp_vue_vue_type_template_id_bd2e5d5c__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./forgot_otp.vue?vue&type=template&id=bd2e5d5c */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/forgot_otp.vue?vue&type=template&id=bd2e5d5c");


/***/ }),

/***/ "./resources/js/themes/andamanisland/account/forgot_password.vue?vue&type=template&id=44a87c04":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/forgot_password.vue?vue&type=template&id=44a87c04 ***!
  \*****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_forgot_password_vue_vue_type_template_id_44a87c04__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_forgot_password_vue_vue_type_template_id_44a87c04__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./forgot_password.vue?vue&type=template&id=44a87c04 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/forgot_password.vue?vue&type=template&id=44a87c04");


/***/ }),

/***/ "./resources/js/themes/andamanisland/account/login.vue?vue&type=template&id=6a47df14":
/*!*******************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/login.vue?vue&type=template&id=6a47df14 ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_login_vue_vue_type_template_id_6a47df14__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_login_vue_vue_type_template_id_6a47df14__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./login.vue?vue&type=template&id=6a47df14 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/login.vue?vue&type=template&id=6a47df14");


/***/ }),

/***/ "./resources/js/themes/andamanisland/account/otp.vue?vue&type=template&id=5d3e5878":
/*!*****************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/otp.vue?vue&type=template&id=5d3e5878 ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_otp_vue_vue_type_template_id_5d3e5878__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_otp_vue_vue_type_template_id_5d3e5878__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./otp.vue?vue&type=template&id=5d3e5878 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/otp.vue?vue&type=template&id=5d3e5878");


/***/ }),

/***/ "./resources/js/themes/andamanisland/account/signup.vue?vue&type=template&id=4600294a":
/*!********************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/signup.vue?vue&type=template&id=4600294a ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_signup_vue_vue_type_template_id_4600294a__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_signup_vue_vue_type_template_id_4600294a__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./signup.vue?vue&type=template&id=4600294a */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/signup.vue?vue&type=template&id=4600294a");


/***/ }),

/***/ "./resources/js/themes/andamanisland/account/vendorlogin.vue?vue&type=template&id=6c46106e":
/*!*************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/account/vendorlogin.vue?vue&type=template&id=6c46106e ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_vendorlogin_vue_vue_type_template_id_6c46106e__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_vendorlogin_vue_vue_type_template_id_6c46106e__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./vendorlogin.vue?vue&type=template&id=6c46106e */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/account/vendorlogin.vue?vue&type=template&id=6c46106e");


/***/ }),

/***/ "./resources/js/themes/andamanisland/Home.vue?vue&type=style&index=0&id=363c65e0&scoped=true&lang=css":
/*!************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/Home.vue?vue&type=style&index=0&id=363c65e0&scoped=true&lang=css ***!
  \************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_10_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_10_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Home_vue_vue_type_style_index_0_id_363c65e0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!../../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Home.vue?vue&type=style&index=0&id=363c65e0&scoped=true&lang=css */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-10.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-10.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/Home.vue?vue&type=style&index=0&id=363c65e0&scoped=true&lang=css");


/***/ })

}]);