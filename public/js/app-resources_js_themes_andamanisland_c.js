"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/app-resources_js_themes_andamanisland_c"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/popup.vue?vue&type=script&lang=js":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/popup.vue?vue&type=script&lang=js ***!
  \********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_commonFuntions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils/commonFuntions */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
/* harmony import */ var _store_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../store.js */ "./resources/js/themes/andamanisland/store.js");


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: "popup",
  created: function created() {
    // console.log('store', store.popupActive);
  },
  data: function data() {
    return {
      store: _store_js__WEBPACK_IMPORTED_MODULE_1__.store
    };
  },
  methods: {
    closeClick: function closeClick() {
      (0,_utils_commonFuntions__WEBPACK_IMPORTED_MODULE_0__.hidePopup)();
    }
  },
  props: ["className"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/BikeListCard.vue?vue&type=script&lang=js":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/BikeListCard.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _store_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../store.js */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _utils_commonFuntions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utils/commonFuntions */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
/* harmony import */ var vue_toast_notification_dist_theme_default_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-toast-notification/dist/theme-default.css */ "./node_modules/vue-toast-notification/dist/theme-default.css");
/* harmony import */ var vue_search_select__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! vue-search-select */ "./node_modules/vue-search-select/dist/VueSearchSelect.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var vue3_styled_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! vue3-styled-components */ "./node_modules/vue3-styled-components/dist/vue-styled-components.es.js");
var _templateObject;
function _taggedTemplateLiteral(e, t) { return t || (t = e.slice(0)), Object.freeze(Object.defineProperties(e, { raw: { value: Object.freeze(t) } })); }






var BikeCardWrapper = vue3_styled_components__WEBPACK_IMPORTED_MODULE_5__["default"].div(_templateObject || (_templateObject = _taggedTemplateLiteral(["\n    & .has_error .error_text{\n        color:red;\n        font-size: 0.86rem;\n    }\n    & .has_error .dropdown:not(.active) {\n        border-color: #ff000047!important;\n    }\n    "])));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  created: function created() {
    this.cityId = this.bike.city_id;
    this.bikeId = this.bike.id;
    var location_id = 0;
    this.locationId = location_id;

    //this.bikePrice = this.bike.price;
    //this.bikeCityPrice(cityId);
  },
  data: function data() {
    return {
      store: _store_js__WEBPACK_IMPORTED_MODULE_0__.store,
      locationId: '',
      cityId: '',
      bikePrice: 0,
      bikeId: '',
      bikeCityModal: {},
      submitted: false
    };
  },
  methods: {
    TimeFormat: _utils_commonFuntions__WEBPACK_IMPORTED_MODULE_1__.TimeFormat,
    showPrice: _utils_commonFuntions__WEBPACK_IMPORTED_MODULE_1__.showPrice,
    DateFormat: _utils_commonFuntions__WEBPACK_IMPORTED_MODULE_1__.DateFormat,
    handleOnChange: function handleOnChange(selectedCity) {
      // let location_id = e.target.value;
      // console.log(e.target);
      // alert(location_id);
      this.locationId = selectedCity.id;
      // alert(this.locationId);
      //this.bikeCityPrice(cityId);
    },
    bikeCityPrice: function bikeCityPrice(cityId) {
      var _this = this;
      this.bike.cities.forEach(function (city, index) {
        if (city.id == cityId) {
          _this.bike.price = city.price;
        }
      });
    },
    bookNow: function bookNow(e) {
      var _this$store$searchDat, _this$store$searchDat2, _this$store$searchDat3, _this$store$searchDat4, _this$store$searchDat5;
      e.preventDefault();
      var currentObj = this;
      currentObj.processing = true;
      currentObj.submitted = true;
      currentObj.errors = {};
      var form_data = {};
      this.bikePrice = this.bike.price;
      this.bikeId = this.bike.id;
      this.cityId = this.bike.city_id;
      this.total_km = this.bike.total_km;
      //let location_id = this.bike.cities[0]?.id;
      // this.locationId = location_id;

      if (this.locationId == 0) {
        // alert('Please select Location');
        return true;
      }
      form_data['action'] = 'rental_booking';
      form_data['location_id'] = this.locationId;
      form_data['city_id'] = this.cityId;
      form_data['bike_id'] = this.bikeId;
      form_data['price'] = this.bikePrice;
      form_data['total_km'] = this.total_km;
      form_data['trip_date'] = (_this$store$searchDat = this.store.searchData) === null || _this$store$searchDat === void 0 ? void 0 : _this$store$searchDat.pickupDate;
      form_data['pickupDate'] = (_this$store$searchDat2 = this.store.searchData) === null || _this$store$searchDat2 === void 0 ? void 0 : _this$store$searchDat2.pickupDate;
      form_data['pickupTime'] = (_this$store$searchDat3 = this.store.searchData) === null || _this$store$searchDat3 === void 0 ? void 0 : _this$store$searchDat3.pickupTime;
      form_data['dropDate'] = (_this$store$searchDat4 = this.store.searchData) === null || _this$store$searchDat4 === void 0 ? void 0 : _this$store$searchDat4.dropDate;
      form_data['dropTime'] = (_this$store$searchDat5 = this.store.searchData) === null || _this$store$searchDat5 === void 0 ? void 0 : _this$store$searchDat5.dropTime;

      // console.log(form_data);

      axios__WEBPACK_IMPORTED_MODULE_4___default().post('/book_now', form_data).then(function (response) {
        currentObj.processing = false;
        if (response.data.success) {
          //alert(response.data.redirect_url);
          // window.location.href = response.data.redirect_url;
          currentObj.$inertia.get(response.data.redirect_url);

          // this.$inertia.get(`/${response.data.redirect_url}`);
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
    parseBookingErrorMessages: function parseBookingErrorMessages(response, formID, boxText) {
      if (response.errors) {
        var errors = response.errors;
        var message = '';
        $.each(errors, function (key, val) {
          $('#' + formID).find("#" + key + "_error").text(val[0]);
        });
      }
    }
  },
  components: {
    ModelListSelect: vue_search_select__WEBPACK_IMPORTED_MODULE_3__.ModelListSelect,
    BikeCardWrapper: BikeCardWrapper
  },
  watch: {
    bikeCityModal: function bikeCityModal(selectedCity) {
      this.handleOnChange(selectedCity);
    }
  },
  props: ["bike"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue?vue&type=script&lang=js":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue?vue&type=script&lang=js ***!
  \************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _store_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../store.js */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var vue_search_select__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! vue-search-select */ "./node_modules/vue-search-select/dist/VueSearchSelect.js");




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  created: function created() {
    // console.log('bike select', this.bike_models);
  },
  data: function data() {
    return {
      store: _store_js__WEBPACK_IMPORTED_MODULE_0__.store,
      modelList: {}
    };
  },
  methods: {
    handleClickfunction: function handleClickfunction(e) {
      this.handleFormSubmit(e);
    },
    handleFormSubmit: function handleFormSubmit(e) {
      e.preventDefault();
    },
    checkedFunction: function checkedFunction(module_name, value) {
      var checked = false;
      var checkedArr = [];
      if (this.search_data) {
        if (module_name == 'locations') {
          checkedArr = this.search_data.locations;
        } else if (module_name == 'types') {
          checkedArr = this.search_data.types;

          //console.log('=====>',checkedArr);
        }
        if (checkedArr) {
          if (checkedArr.indexOf(String(value)) !== -1) {
            checked = true;
          }
        }
      }
      return checked;
    }
  },
  components: {
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_1__.Link,
    ModelListSelect: vue_search_select__WEBPACK_IMPORTED_MODULE_3__.ModelListSelect
  },
  props: ["cities", "locations", "bikeTypes", "search_data", "bike_models"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue?vue&type=script&lang=js":
/*!**************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue?vue&type=script&lang=js ***!
  \**************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _store_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../store.js */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var vue3_styled_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue3-styled-components */ "./node_modules/vue3-styled-components/dist/vue-styled-components.es.js");
/* harmony import */ var v_calendar_dist_style_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! v-calendar/dist/style.css */ "./node_modules/v-calendar/dist/style.css");
/* harmony import */ var v_calendar__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! v-calendar */ "./node_modules/v-calendar/dist/v-calendar.es.js");
/* harmony import */ var _FormTopMenu_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../FormTopMenu.vue */ "./resources/js/themes/andamanisland/components/FormTopMenu.vue");
/* harmony import */ var vue_search_select_dist_VueSearchSelect_css__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! vue-search-select/dist/VueSearchSelect.css */ "./node_modules/vue-search-select/dist/VueSearchSelect.css");
/* harmony import */ var vue_search_select__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! vue-search-select */ "./node_modules/vue-search-select/dist/VueSearchSelect.js");
/* harmony import */ var _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../utils/commonFuntions.js */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
var _templateObject;
function _taggedTemplateLiteral(e, t) { return t || (t = e.slice(0)), Object.freeze(Object.defineProperties(e, { raw: { value: Object.freeze(t) } })); }








var FormWrapper = vue3_styled_components__WEBPACK_IMPORTED_MODULE_1__["default"].form(_templateObject || (_templateObject = _taggedTemplateLiteral(["\n& .default_time_option {\n    display:block!important;\n}\n"])));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  created: function created() {
    var _this = this;
    document.body.classList.add('rental');
    this.cityList = _store_js__WEBPACK_IMPORTED_MODULE_0__.store.cities;
    var urlParams = new URLSearchParams(window.location.search);
    var cityId = urlParams.get('city');
    this.setTimeArray();
    if (cityId) {
      var selectedCity = this.cityList.find(function (o) {
        return o.id === parseInt(cityId);
      });
      this.cityModal = selectedCity;
    } else {
      setTimeout(function () {
        _store_js__WEBPACK_IMPORTED_MODULE_0__.store.searchData.pickupDate;
        if (_store_js__WEBPACK_IMPORTED_MODULE_0__.store.searchData.city) {
          var _selectedCity = _this.cityList.find(function (o) {
            return o.id === _store_js__WEBPACK_IMPORTED_MODULE_0__.store.searchData.city;
          });
          _this.cityModal = _selectedCity;
        }
      }, 300);
    }
  },
  beforeUnmount: function beforeUnmount() {
    //console.log(this.$el)
    document.body.classList.remove('rental');
  },
  data: function data() {
    return {
      visible: false,
      cityList: [],
      cityModal: {},
      store: _store_js__WEBPACK_IMPORTED_MODULE_0__.store,
      pickupDate: '',
      pickupMinDate: (0,_utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_7__.getTomorrowDate)(),
      dropDate: '',
      dropMinDate: new Date(),
      tripTimeArr: [],
      masks: {
        input: 'DD/MM/YYYY'
      }
    };
  },
  methods: {
    getGreaterDate: _utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_7__.getGreaterDate,
    setTimeArray: function setTimeArray() {
      var _store$searchData, _store$searchData2, _store$websiteSetting;
      var selectedPickupdate = (0,_utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_7__.convertToDateObject)(_store_js__WEBPACK_IMPORTED_MODULE_0__.store === null || _store_js__WEBPACK_IMPORTED_MODULE_0__.store === void 0 || (_store$searchData = _store_js__WEBPACK_IMPORTED_MODULE_0__.store.searchData) === null || _store$searchData === void 0 ? void 0 : _store$searchData.pickupDate);
      this.pickupDate = selectedPickupdate;
      var selecteddropdate = (0,_utils_commonFuntions_js__WEBPACK_IMPORTED_MODULE_7__.convertToDateObject)(_store_js__WEBPACK_IMPORTED_MODULE_0__.store === null || _store_js__WEBPACK_IMPORTED_MODULE_0__.store === void 0 || (_store$searchData2 = _store_js__WEBPACK_IMPORTED_MODULE_0__.store.searchData) === null || _store$searchData2 === void 0 ? void 0 : _store$searchData2.dropDate);
      this.dropDate = selecteddropdate;
      this.tripTimeArr = _store_js__WEBPACK_IMPORTED_MODULE_0__.store === null || _store_js__WEBPACK_IMPORTED_MODULE_0__.store === void 0 || (_store$websiteSetting = _store_js__WEBPACK_IMPORTED_MODULE_0__.store.websiteSettings) === null || _store$websiteSetting === void 0 ? void 0 : _store$websiteSetting.timeArr;
    },
    setDropTime: function setDropTime() {
      var dropTimeElement = this.$refs.dropTimeRef;
      var pickupTimeElement = this.$refs.pickupTimeRef;
      var selectedPickupTime = pickupTimeElement.value;
      // Variable to track if the selectedPickupTime option is found
      var foundSelectedPickupTime = false;
      var isSameDate = "".concat(this.pickupDate) == "".concat(this.dropDate);
      console.log('isSameDate', isSameDate);

      // Loop through all the options in the dropTimeElement
      for (var i = 0; i < dropTimeElement.options.length; i++) {
        var option = dropTimeElement.options[i];
        if (foundSelectedPickupTime) {
          // If the selectedPickupTime option is found, display subsequent options
          option.style.display = 'block';
        } else {
          // If the option value matches the selectedPickupTime, set foundSelectedPickupTime to true
          // and hide previous options
          if (option.value === selectedPickupTime) {
            foundSelectedPickupTime = true;
            option.style.display = 'none'; // Hide the selected option
          } else {
            option.style.display = 'none';
          }
        }
      }

      // Check if the selected option is hidden
      if (dropTimeElement.selectedIndex !== -1) {
        var selectedOption = dropTimeElement.options[dropTimeElement.selectedIndex];
        if (selectedOption.style.display === 'none') {
          // If the selected option is hidden, find the first non-hidden option and set it as selected
          for (var _i = 0; _i < dropTimeElement.options.length; _i++) {
            var _option = dropTimeElement.options[_i];
            if (_option.style.display !== 'none' && isSameDate) {
              dropTimeElement.selectedIndex = _i;
              break;
            }
          }
        }
      }
      if (!isSameDate) {
        // Set all options to display block if not same date
        for (var _i2 = 0; _i2 < dropTimeElement.options.length; _i2++) {
          dropTimeElement.options[_i2].style.display = 'block';
        }
      }
    }
  },
  mounted: function mounted() {},
  beforeDestroy: function beforeDestroy() {},
  watch: {
    pickupDate: function pickupDate(_pickupDate) {
      this.dropMinDate = _pickupDate;
      this.dropDate = this.getGreaterDate(_pickupDate, this.dropDate);
      if (this.$refs.dropTimeRef) {
        this.setDropTime();
      }
    },
    dropDate: function dropDate(_dropDate) {
      if (this.$refs.dropTimeRef) {
        this.setDropTime();
      }
    },
    store: {
      handler: function handler(updatedStore) {
        if (updatedStore !== null && updatedStore !== void 0 && updatedStore.searchData && updatedStore !== null && updatedStore !== void 0 && updatedStore.websiteSettings) {
          this.setTimeArray();
        }
      },
      deep: true
    },
    tripTimeArr: function tripTimeArr(updatedTripTime) {
      if (this.$refs.dropTimeRef) {
        var currentObj = this;
        setTimeout(function () {
          currentObj.setDropTime();
        }, 100);
      }
    }
  },
  components: {
    DatePicker: v_calendar__WEBPACK_IMPORTED_MODULE_3__.DatePicker,
    FormTopMenu: _FormTopMenu_vue__WEBPACK_IMPORTED_MODULE_4__["default"],
    ModelListSelect: vue_search_select__WEBPACK_IMPORTED_MODULE_6__.ModelListSelect,
    'form-wrapper': FormWrapper
  },
  props: ["metaTitle", "metaDescription"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue?vue&type=script&lang=js":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue?vue&type=script&lang=js ***!
  \*******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vue3_styled_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue3-styled-components */ "./node_modules/vue3-styled-components/dist/vue-styled-components.es.js");
var _templateObject;
function _taggedTemplateLiteral(e, t) { return t || (t = e.slice(0)), Object.freeze(Object.defineProperties(e, { raw: { value: Object.freeze(t) } })); }

var TestiImageWrapper = vue3_styled_components__WEBPACK_IMPORTED_MODULE_0__["default"].div(_templateObject || (_templateObject = _taggedTemplateLiteral(["\n& .swiper-button-lock{\n    display:none;\n}\n"])));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: "InnerImageSlider",
  mounted: function mounted() {
    var sliderElem = this.$refs.sliderRef;
    var sliderNextBtn = this.$refs.sliderNextRef;
    var sliderPrevBtn = this.$refs.sliderPrevRef;
    var slidesCount = 1;
    var spacebetween = 0;
    new Swiper(sliderElem, {
      slidesPerView: slidesCount,
      spaceBetween: spacebetween,
      loop: false,
      speed: 1000,
      navigation: {
        nextEl: sliderPrevBtn,
        prevEl: sliderNextBtn
      },
      breakpoints: {
        0: {
          slidesPerView: 1
        },
        640: {
          slidesPerView: 1
        },
        768: {
          slidesPerView: 1
        },
        1024: {
          slidesPerView: slidesCount
        }
      },
      watchSlidesVisibility: true,
      watchSlidesProgress: true
    });
  },
  components: {
    TestiImageWrapper: TestiImageWrapper
  },
  props: ['images']
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue?vue&type=script&lang=js":
/*!******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue?vue&type=script&lang=js ***!
  \******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _styles_TestimonialCardWrapper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../styles/TestimonialCardWrapper */ "./resources/js/themes/andamanisland/styles/TestimonialCardWrapper.js");


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'TestimonialCard',
  created: function created() {
    // console.log('testimonials', this.data);
  },
  components: {
    TestimonialCardWrapper: _styles_TestimonialCardWrapper__WEBPACK_IMPORTED_MODULE_1__.TestimonialCardWrapper,
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__.Link
  },
  props: ['data']
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue?vue&type=script&lang=js":
/*!******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue?vue&type=script&lang=js ***!
  \******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _utils_commonFuntions__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../utils/commonFuntions */ "./resources/js/themes/andamanisland/utils/commonFuntions.js");
/* harmony import */ var _mayasabha_ckeditor4_vue3__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @mayasabha/ckeditor4-vue3 */ "./node_modules/@mayasabha/ckeditor4-vue3/dist/ckeditor.js");
/* harmony import */ var _mayasabha_ckeditor4_vue3__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_mayasabha_ckeditor4_vue3__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _styles_TestimonialFormWrapper__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../styles/TestimonialFormWrapper */ "./resources/js/themes/andamanisland/styles/TestimonialFormWrapper.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }






/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  created: function created() {
    if (this.userObject && this.userObject.name) {
      this.userData = this.userObject;
    }
    _store__WEBPACK_IMPORTED_MODULE_2__.store.seoData = this.seo_data;
    //console.log('name_title_arr=',this.nameTitleArr);
  },
  beforeUnmount: function beforeUnmount() {
    document.body.classList.remove('/testimonial/add');
  },
  data: function data() {
    return {
      editorData: '',
      userData: {}
    };
  },
  methods: {
    showErrorToast: _utils_commonFuntions__WEBPACK_IMPORTED_MODULE_3__.showErrorToast,
    handleForOtherChange: function handleForOtherChange(e) {
      e.preventDefault();
      this.forOtherChecked = !this.forOtherChecked;
    },
    handleChange: function handleChange(event) {
      var _event$target;
      var UserData = this.userData;
      if (event !== null && event !== void 0 && (_event$target = event.target) !== null && _event$target !== void 0 && _event$target.name) {
        var name = event.target.name;
        UserData[name] = event.target.value;
        this.UserData = UserData;
      } else {
        this.UserData = _objectSpread(_objectSpread({}, this.UserData), event);
      }
    },
    handleFormSubmit: function handleFormSubmit(e) {
      e.preventDefault();
      e.stopPropagation();
      var currentObj = this;
      var form = e.target;
      var formID = 'testimonial_add_form';
      $('#' + formID).find('.ajax_msg').html('');
      $('#' + formID).find('.validation_error').html('');
      $('#' + formID).find('.error').html('');
      $('#' + formID).find('.btnSubmit').html('Please wait...');
      $('#' + formID).find('.btnSubmit').attr("disabled", true);
      axios__WEBPACK_IMPORTED_MODULE_1___default().post('/testimonial/add', new FormData($('#' + formID)[0])).then(function (resp) {
        var response = resp.data;
        if (response.success) {
          window.location.href = response.redirect_url;
        } else if (response.message) {
          currentObj.showErrorToast(response.message);
        } else {
          currentObj.showErrorToast('Something went wront, please try again.');
        }
        $('#' + formID).find('.btnSubmit').html('Submit');
        $('#' + formID).find('.btnSubmit').attr("disabled", true);
      })["catch"](function (e) {
        var response = e.response.data;
        if (response) {
          currentObj.parseBookingNowErrorMessages(response, formID, 'Submit');
        }
      });
    },
    parseBookingNowErrorMessages: function parseBookingNowErrorMessages(response, formID, boxText) {
      var currentObj = this;
      $('#' + formID).find('.btnSubmit').html('Submit');
      $('#' + formID).find('.btnSubmit').attr("disabled", false);
      if (response.errors) {
        var errors = response.errors;
        var message = '';
        $.each(errors, function (key, val) {
          $('#' + formID).find("#" + key + "_error").text(val[0]);
        });
      }
      if (response.message) {
        currentObj.showErrorToast(response.message);
      }
    }
  },
  mounted: function mounted() {},
  beforeDestroy: function beforeDestroy() {},
  watch: {},
  components: {
    TestimonialFormWrapper: _styles_TestimonialFormWrapper__WEBPACK_IMPORTED_MODULE_5__.TestimonialFormWrapper,
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_0__.Link,
    ckeditor: _mayasabha_ckeditor4_vue3__WEBPACK_IMPORTED_MODULE_4__.component
  },
  props: ["testimonialData", "nameTitleArr"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue?vue&type=script&lang=js":
/*!*************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue?vue&type=script&lang=js ***!
  \*************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _styles_TestimonialImagesWrapper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../styles/TestimonialImagesWrapper */ "./resources/js/themes/andamanisland/styles/TestimonialImagesWrapper.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'TestimonialImageSlider',
  mounted: function mounted() {
    var sliderElem = this.$refs.sliderRef;
    var sliderNextBtn = this.$refs.sliderNextRef;
    var sliderPrevBtn = this.$refs.sliderPrevRef;
    var slidesCount = 1;
    var spacebetween = 0;
    new Swiper(sliderElem, {
      slidesPerView: slidesCount,
      spaceBetween: spacebetween,
      loop: false,
      speed: 1000,
      navigation: {
        nextEl: sliderPrevBtn,
        prevEl: sliderNextBtn
      },
      breakpoints: {
        0: {
          slidesPerView: 1
        },
        640: {
          slidesPerView: 1
        },
        768: {
          slidesPerView: 1
        },
        1024: {
          slidesPerView: slidesCount
        }
      },
      watchSlidesVisibility: true,
      watchSlidesProgress: true
    });
  },
  components: {
    TestimonialImagesWrapper: _styles_TestimonialImagesWrapper__WEBPACK_IMPORTED_MODULE_0__.TestimonialImagesWrapper
  },
  props: ['images']
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue?vue&type=script&lang=js":
/*!***************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue?vue&type=script&lang=js ***!
  \***************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vue3_styled_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue3-styled-components */ "./node_modules/vue3-styled-components/dist/vue-styled-components.es.js");
/* harmony import */ var _TestimonialCard_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TestimonialCard.vue */ "./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue");
var _templateObject;
function _taggedTemplateLiteral(e, t) { return t || (t = e.slice(0)), Object.freeze(Object.defineProperties(e, { raw: { value: Object.freeze(t) } })); }


var SectionWrapper = vue3_styled_components__WEBPACK_IMPORTED_MODULE_0__["default"].section(_templateObject || (_templateObject = _taggedTemplateLiteral(["\npadding: 1rem 0 4rem;\noverflow: hidden;\n& .title{\n    font-size: 1.45rem;\n    font-weight: 600;\n    color: var(--theme-color);\n    margin-bottom: 1.3rem;\n    text-transform: uppercase;\n}\n& .slider_box {\n    position: relative;\n    & .swiper{\n        overflow:visible;\n        & .swiper-slide{\n            height: auto;\n           &>*{\n            opacity: 0;\n            transition :0.5s;\n           }\n           &.swiper-slide-visible>*{\n                opacity: 1;\n            }\n        }\n    }\n    & .slider_btns {\n        position: absolute;\n        top: 50%;\n        left: 50%;\n        transform: translate(-50%, -50%);\n        z-index: 1;\n        width: calc(100% + 3rem);\n        display: flex;\n        justify-content: space-between;\n        pointer-events: none;\n        &>*{\n            width: 2.5rem;\n            height: 2.5rem;\n            background-color: var(--theme-color);\n            color: var(--white);\n            display: grid;\n            place-items: center;\n            border-radius: 50%;\n            font-size: 0.85rem;\n            pointer-events: all;\n            transition: all ease 0.5s;\n            &:hover{\n                background-color: var(--secondary-color);\n            }\n        }\n        &>.swiper-button-disabled{\n            background-color: #c8c8c8;\n            cursor: no-drop;\n        }\n        &>.swiper-button-lock{\n            display:none;\n        }\n    }\n}\n"])));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'TestimonialSliderSection',
  components: {
    SectionWrapper: SectionWrapper,
    TestimonialCard: _TestimonialCard_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  mounted: function mounted() {
    var sliderElem = this.$refs.sliderRef;
    var sliderNextBtn = this.$refs.sliderNextRef;
    var sliderPrevBtn = this.$refs.sliderPrevRef;
    var slidesCount = 5;
    var spacebetween = 15;
    new Swiper(sliderElem, {
      slidesPerView: slidesCount,
      spaceBetween: spacebetween,
      loop: false,
      speed: 1000,
      navigation: {
        nextEl: sliderPrevBtn,
        prevEl: sliderNextBtn
      },
      breakpoints: {
        0: {
          slidesPerView: 1.5
        },
        640: {
          slidesPerView: 2
        },
        768: {
          slidesPerView: 2
        },
        1024: {
          slidesPerView: slidesCount
        }
      },
      watchSlidesVisibility: true,
      watchSlidesProgress: true
    });
  },
  props: ["data"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue?vue&type=script&lang=js":
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue?vue&type=script&lang=js ***!
  \********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _store_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../store.js */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _InnerImageSlider_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./InnerImageSlider.vue */ "./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue");
/* harmony import */ var _styles_TestimonialWrapper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../styles/TestimonialWrapper */ "./resources/js/themes/andamanisland/styles/TestimonialWrapper.js");




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'TestimonialSlider',
  created: function created() {
    // console.log('inside testinmonial component', this.testimonials);
  },
  data: function data() {
    return {
      store: _store_js__WEBPACK_IMPORTED_MODULE_0__.store
    };
  },
  methods: {},
  mounted: function mounted() {
    var sliderElem = this.$refs.sliderRef;
    var sliderNextBtn = this.$refs.sliderNextRef;
    var sliderPrevBtn = this.$refs.sliderPrevRef;
    var slidesCount = 1;
    var spacebetween = 15;
    new Swiper(sliderElem, {
      slidesPerView: slidesCount,
      spaceBetween: spacebetween,
      loop: false,
      speed: 1000,
      navigation: {
        nextEl: sliderPrevBtn,
        prevEl: sliderNextBtn
      },
      breakpoints: {
        0: {
          slidesPerView: 1,
          autoHeight: true
        },
        640: {
          slidesPerView: 1,
          autoHeight: true
        },
        883: {
          slidesPerView: 2,
          autoHeight: false
        },
        1024: {
          slidesPerView: slidesCount,
          autoHeight: false
        }
      },
      watchSlidesVisibility: true,
      watchSlidesProgress: true
    });
  },
  components: {
    TestimonialWrapper: _styles_TestimonialWrapper__WEBPACK_IMPORTED_MODULE_3__.TestimonialWrapper,
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_1__.Link,
    InnerImageSlider: _InnerImageSlider_vue__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  props: ["testimonials"]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/contact.vue?vue&type=script&lang=js":
/*!***********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/contact.vue?vue&type=script&lang=js ***!
  \***********************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_layout_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/layout.vue */ "./resources/js/themes/andamanisland/components/layout.vue");
/* harmony import */ var _components_contact_MapItem_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/contact/MapItem.vue */ "./resources/js/themes/andamanisland/components/contact/MapItem.vue");
/* harmony import */ var _components_formShortCode_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/formShortCode.vue */ "./resources/js/themes/andamanisland/components/formShortCode.vue");
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./store */ "./resources/js/themes/andamanisland/store.js");
/* harmony import */ var _styles_AddressWrapper__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./styles/AddressWrapper */ "./resources/js/themes/andamanisland/styles/AddressWrapper.js");
/* harmony import */ var _styles_ContactFormWrapper__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./styles/ContactFormWrapper */ "./resources/js/themes/andamanisland/styles/ContactFormWrapper.js");







/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'contactus',
  created: function created() {
    _store__WEBPACK_IMPORTED_MODULE_4__.store.seoData = this.seo_data;
    console.log('seo_data==', this.seo_data);
  },
  data: function data() {
    return {
      store: _store__WEBPACK_IMPORTED_MODULE_4__.store
    };
  },
  components: {
    MapItem: _components_contact_MapItem_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
    AddressWrapper: _styles_AddressWrapper__WEBPACK_IMPORTED_MODULE_5__.AddressWrapper,
    formShortCode: _components_formShortCode_vue__WEBPACK_IMPORTED_MODULE_2__["default"],
    ContactFormWrapper: _styles_ContactFormWrapper__WEBPACK_IMPORTED_MODULE_6__.ContactFormWrapper,
    Link: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_3__.Link
  },
  layout: _components_layout_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
  props: ['contactDetails', 'banner_image', 'seo_data']
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/popup.vue?vue&type=template&id=54ad6458":
/*!************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/popup.vue?vue&type=template&id=54ad6458 ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "popup_wrap"
};
var _hoisted_2 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-solid fa-xmark"
}, null, -1 /* HOISTED */);
var _hoisted_3 = [_hoisted_2];
function render(_ctx, _cache, $props, $setup, $data, $options) {
  return $data.store.popupActive ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
    key: 0,
    "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(["popup_main_box", $props.className])
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "backdrop",
    onClick: _cache[0] || (_cache[0] = function () {
      return $options.closeClick && $options.closeClick.apply($options, arguments);
    })
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    "class": "close_popup text-white/[.3] text-xl",
    onClick: _cache[1] || (_cache[1] = function () {
      return $options.closeClick && $options.closeClick.apply($options, arguments);
    })
  }, [].concat(_hoisted_3)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderSlot)(_ctx.$slots, "default")])], 2 /* CLASS */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/BikeListCard.vue?vue&type=template&id=66be8522":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/BikeListCard.vue?vue&type=template&id=66be8522 ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");
/* harmony import */ var _assets_images_d_arrow_png__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../assets/images/d-arrow.png */ "./resources/js/themes/andamanisland/assets/images/d-arrow.png");


var _hoisted_1 = {
  "class": "topimg"
};
var _hoisted_2 = ["src"];
var _hoisted_3 = {
  "class": "title text-lg text-center text-slate-800 bg-slate-100 p-1"
};
var _hoisted_4 = {
  "class": "buttom_content p-3"
};
var _hoisted_5 = {
  "class": "searchlocation pb-3"
};
var _hoisted_6 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", null, "Available at", -1 /* HOISTED */);
var _hoisted_7 = {
  key: 0,
  "class": "error_text"
};
var _hoisted_8 = {
  "class": "timing pb-5"
};
var _hoisted_9 = {
  "class": "flex justify-between items-center"
};
var _hoisted_10 = {
  "class": "datelist text-sm"
};
var _hoisted_11 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
  src: _assets_images_d_arrow_png__WEBPACK_IMPORTED_MODULE_1__["default"]
})], -1 /* HOISTED */);
var _hoisted_12 = {
  "class": "datelist text-sm"
};
var _hoisted_13 = {
  "class": "pricebook"
};
var _hoisted_14 = {
  "class": "flex flex-row justify-between items-center"
};
var _hoisted_15 = {
  "class": "pricebold"
};
var _hoisted_16 = {
  "class": "text-sm"
};
var _hoisted_17 = {
  "class": "sightseen-bookbtn"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_ModelListSelect = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ModelListSelect");
  var _component_BikeCardWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("BikeCardWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_BikeCardWrapper, {
    "class": "border rounded"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      var _$data$store$searchDa, _$data$store$searchDa2, _$data$store$searchDa3, _$data$store$searchDa4, _$data$store$searchDa5, _$data$store$searchDa6;
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
        src: $props.bike.src
      }, null, 8 /* PROPS */, _hoisted_2), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props.bike.name), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
        "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(["select-wrapper", !$data.locationId && $data.submitted ? 'has_error' : ''])
      }, [_hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <select class=\"select\" @change=\"handleOnChange($event)\">\r\n                            <option value=\"\">Location</option>\r\n                            <option v-if=\"bike.cities\" v-for=\"city in bike.cities\" :value=\"city.id\">{{city.name}}</option>\r\n                        </select> "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ModelListSelect, {
        list: $props.bike.cities,
        modelValue: $data.bikeCityModal,
        "onUpdate:modelValue": _cache[0] || (_cache[0] = function ($event) {
          return $data.bikeCityModal = $event;
        }),
        placeholder: "Location",
        "option-value": "id",
        "option-text": "name",
        onChange: _cache[1] || (_cache[1] = function ($event) {
          return $options.handleOnChange($event);
        })
      }, null, 8 /* PROPS */, ["list", "modelValue"]), !$data.locationId && $data.submitted ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_7, "Please select Location")) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)], 2 /* CLASS */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_10, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($options.TimeFormat(((_$data$store$searchDa = $data.store.searchData) === null || _$data$store$searchDa === void 0 ? void 0 : _$data$store$searchDa.dropDate) + ' ' + ((_$data$store$searchDa2 = $data.store.searchData) === null || _$data$store$searchDa2 === void 0 ? void 0 : _$data$store$searchDa2.dropTime))), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($options.DateFormat((_$data$store$searchDa3 = $data.store.searchData) === null || _$data$store$searchDa3 === void 0 ? void 0 : _$data$store$searchDa3.dropDate)), 1 /* TEXT */)]), _hoisted_11, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($options.TimeFormat(((_$data$store$searchDa4 = $data.store.searchData) === null || _$data$store$searchDa4 === void 0 ? void 0 : _$data$store$searchDa4.pickupDate) + ' ' + ((_$data$store$searchDa5 = $data.store.searchData) === null || _$data$store$searchDa5 === void 0 ? void 0 : _$data$store$searchDa5.pickupTime))), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($options.DateFormat((_$data$store$searchDa6 = $data.store.searchData) === null || _$data$store$searchDa6 === void 0 ? void 0 : _$data$store$searchDa6.pickupDate)), 1 /* TEXT */)])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_13, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("em", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($options.showPrice(_this.bike.price)), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", _hoisted_16, "(" + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props.bike.total_km) + " km included)", 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_17, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: "#",
        onClick: _cache[2] || (_cache[2] = function () {
          return $options.bookNow && $options.bookNow.apply($options, arguments);
        })
      }, "Book")])])])])];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue?vue&type=template&id=eb0fc1da":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue?vue&type=template&id=eb0fc1da ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  id: "adv_hotel_search",
  name: "adv_hotel_search"
};
var _hoisted_2 = ["value"];
var _hoisted_3 = ["value"];
var _hoisted_4 = ["value"];
var _hoisted_5 = ["value"];
var _hoisted_6 = ["value"];
var _hoisted_7 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "subtitle text-xl my-3 mb-5"
}, "Filter", -1 /* HOISTED */);
var _hoisted_8 = {
  "class": "search-box"
};
var _hoisted_9 = ["value"];
var _hoisted_10 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
  "class": "search-button"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fas fa-search"
})], -1 /* HOISTED */);
var _hoisted_11 = {
  key: 0,
  "class": "acco_category checkbox_list"
};
var _hoisted_12 = {
  "class": "filter_box"
};
var _hoisted_13 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "pb-2 font-semibold text-lg"
}, "Location", -1 /* HOISTED */);
var _hoisted_14 = {
  "class": "acco_category checkbox_list"
};
var _hoisted_15 = {
  "class": "term-list"
};
var _hoisted_16 = {
  "class": "term-item"
};
var _hoisted_17 = ["id", "value", "checked"];
var _hoisted_18 = ["for"];
var _hoisted_19 = {
  "class": "acco_category checkbox_list"
};
var _hoisted_20 = {
  "class": "filter_box"
};
var _hoisted_21 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "text-lg font-semibold pb-2"
}, "Vehicle Type", -1 /* HOISTED */);
var _hoisted_22 = {
  "class": "acco_category checkbox_list"
};
var _hoisted_23 = {
  "class": "term-list"
};
var _hoisted_24 = {
  "class": "term-item"
};
var _hoisted_25 = ["id", "value", "checked"];
var _hoisted_26 = ["for"];
var _hoisted_27 = {
  "class": "acco_category checkbox_list"
};
var _hoisted_28 = {
  "class": "filter_box"
};
var _hoisted_29 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "text-lg font-semibold pb-2"
}, "Search By Bike Model", -1 /* HOISTED */);
var _hoisted_30 = ["value"];
var _hoisted_31 = {
  "class": "search-box"
};
var _hoisted_32 = {
  "class": "acco_category checkbox_list mt-5 text-center"
};
var _hoisted_33 = {
  "class": "list_btn_round"
};
var _hoisted_34 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
  "class": "primary-btn",
  type: "submit"
}, "Apply Filter", -1 /* HOISTED */);
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this$store$searchDat,
    _this$store$searchDat2,
    _this$store$searchDat3,
    _this$store$searchDat4,
    _this$store$searchDat5,
    _this = this,
    _$data$store$websiteS;
  var _component_ModelListSelect = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ModelListSelect");
  var _component_Link = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Link");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("form", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "hidden",
    name: "city",
    value: (_this$store$searchDat = this.store.searchData) === null || _this$store$searchDat === void 0 ? void 0 : _this$store$searchDat.city
  }, null, 8 /* PROPS */, _hoisted_2), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "hidden",
    name: "pickupDate",
    value: (_this$store$searchDat2 = this.store.searchData) === null || _this$store$searchDat2 === void 0 ? void 0 : _this$store$searchDat2.pickupDate
  }, null, 8 /* PROPS */, _hoisted_3), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "hidden",
    name: "pickupTime",
    value: (_this$store$searchDat3 = this.store.searchData) === null || _this$store$searchDat3 === void 0 ? void 0 : _this$store$searchDat3.pickupTime
  }, null, 8 /* PROPS */, _hoisted_4), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "hidden",
    name: "dropDate",
    value: (_this$store$searchDat4 = this.store.searchData) === null || _this$store$searchDat4 === void 0 ? void 0 : _this$store$searchDat4.dropDate
  }, null, 8 /* PROPS */, _hoisted_5), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "hidden",
    name: "dropTime",
    value: (_this$store$searchDat5 = this.store.searchData) === null || _this$store$searchDat5 === void 0 ? void 0 : _this$store$searchDat5.dropTime
  }, null, 8 /* PROPS */, _hoisted_6), _hoisted_7, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "text",
    "class": "search-input",
    name: "key",
    value: this.search_data.key,
    placeholder: "Search.."
  }, null, 8 /* PROPS */, _hoisted_9), _hoisted_10]), $props.locations && $props.locations.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_11, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [_hoisted_13, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", _hoisted_15, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.locations, function (location) {
    return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_16, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
      type: "checkbox",
      id: "location_".concat(location.id),
      name: "locations[]",
      value: location.id,
      checked: _this.checkedFunction('locations', location.id)
    }, null, 8 /* PROPS */, _hoisted_17), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
      "for": "location_".concat(location.id)
    }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(location.name), 1 /* TEXT */)], 8 /* PROPS */, _hoisted_18)]);
  }), 256 /* UNKEYED_FRAGMENT */))])])])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_19, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_20, [_hoisted_21, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_22, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", _hoisted_23, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.bikeTypes, function (bikeType, key) {
    return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("li", _hoisted_24, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
      type: "checkbox",
      id: "type_".concat(key),
      name: "types[]",
      value: key,
      checked: _this.checkedFunction('types', key)
    }, null, 8 /* PROPS */, _hoisted_25), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
      "for": "type_".concat(key)
    }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(bikeType), 1 /* TEXT */)], 8 /* PROPS */, _hoisted_26)]);
  }), 256 /* UNKEYED_FRAGMENT */))])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_27, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_28, [_hoisted_29, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "hidden",
    name: "model",
    value: this.modelList.modal
  }, null, 8 /* PROPS */, _hoisted_30), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_31, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <input type=\"text\" class=\"search-input\" name=\"model\" :value=\"this.search_data.model\" placeholder=\"Search..\" />\r\n                        <button class=\"search-button\">\r\n                            <i class=\"fas fa-search\"></i>\r\n                        </button>\r\n "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ModelListSelect, {
    list: this.bike_models,
    modelValue: $data.modelList,
    "onUpdate:modelValue": _cache[0] || (_cache[0] = function ($event) {
      return $data.modelList = $event;
    }),
    placeholder: "Model",
    "option-value": "modal",
    "option-text": "modal"
  }, null, 8 /* PROPS */, ["list", "modelValue"])])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_32, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_33, [_hoisted_34, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
    href: (_$data$store$websiteS = $data.store.websiteSettings) === null || _$data$store$websiteS === void 0 ? void 0 : _$data$store$websiteS.RENTAL_URL,
    "class": "secondary-btn ml-2"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("Clear")];
    }),
    _: 1 /* STABLE */
  }, 8 /* PROPS */, ["href"])])])]);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue?vue&type=template&id=3ddb43ba":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue?vue&type=template&id=3ddb43ba ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", {
  "class": "text-lg font-bold pb-1 pt-1 ml-2"
}, "Rental Search", -1 /* HOISTED */);
var _hoisted_2 = {
  "class": "ssb-wrap"
};
var _hoisted_3 = ["value", "onClick"];
var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "ssb_errors"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(""), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("")], -1 /* HOISTED */);
var _hoisted_5 = {
  "class": "time_wrap"
};
var _hoisted_6 = {
  "class": "passenger-economy"
};
var _hoisted_7 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-regular fa-clock"
}, null, -1 /* HOISTED */);
var _hoisted_8 = {
  "class": "passenger-txt"
};
var _hoisted_9 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
  value: ""
}, " Time ", -1 /* HOISTED */);
var _hoisted_10 = ["value", "selected"];
var _hoisted_11 = {
  "class": "ssb-wrap"
};
var _hoisted_12 = ["value", "onClick"];
var _hoisted_13 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "ssb_errors"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(""), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("")], -1 /* HOISTED */);
var _hoisted_14 = {
  "class": "time_wrap"
};
var _hoisted_15 = {
  "class": "passenger-economy"
};
var _hoisted_16 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-regular fa-clock"
}, null, -1 /* HOISTED */);
var _hoisted_17 = {
  "class": "passenger-txt"
};
var _hoisted_18 = {
  "class": "pickup-time",
  name: "dropTime",
  ref: "dropTimeRef"
};
var _hoisted_19 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
  value: "",
  "class": "default_time_option"
}, " Time ", -1 /* HOISTED */);
var _hoisted_20 = ["value", "selected"];
var _hoisted_21 = {
  "class": "select_from_wrap location_input ml-2"
};
var _hoisted_22 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-solid fa-location-dot absolute top-3 left-2 z-50 opacity-50 mr-1"
}, null, -1 /* HOISTED */);
var _hoisted_23 = ["value"];
var _hoisted_24 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "search_btn"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
  type: "submit"
}, "Search")], -1 /* HOISTED */);
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_DatePicker = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("DatePicker");
  var _component_ModelListSelect = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ModelListSelect");
  var _component_form_wrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("form-wrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [_hoisted_1, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_form_wrapper, {
    "class": "flight_form rental_search_form"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_DatePicker, {
        columns: "2",
        modelValue: $data.pickupDate,
        "onUpdate:modelValue": _cache[0] || (_cache[0] = function ($event) {
          return $data.pickupDate = $event;
        }),
        mode: "date",
        "class": "date_wrap",
        "min-date": _this.pickupMinDate,
        masks: $data.masks
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function (_ref) {
          var inputValue = _ref.inputValue,
            inputEvents = _ref.inputEvents,
            togglePopover = _ref.togglePopover;
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
            name: "pickupDate",
            "class": "date_input",
            value: inputValue,
            onClick: togglePopover,
            placeholder: "Pickup Date",
            autocomplete: "off"
          }, null, 8 /* PROPS */, _hoisted_3)];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["modelValue", "min-date", "masks"]), _hoisted_4]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_6, [_hoisted_7, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
        "class": "pickup-time",
        name: "pickupTime",
        onChange: _cache[1] || (_cache[1] = function ($event) {
          return _this.setDropTime();
        }),
        ref: "pickupTimeRef"
      }, [_hoisted_9, _this.tripTimeArr && _this.tripTimeArr.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
        key: 0
      }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)(_this.tripTimeArr, function (tripTimeRow, index) {
        var _$data$store$searchDa;
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("option", {
          value: tripTimeRow.key,
          selected: tripTimeRow.key == ((_$data$store$searchDa = $data.store.searchData) === null || _$data$store$searchDa === void 0 ? void 0 : _$data$store$searchDa.pickupTime)
        }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(tripTimeRow.title), 9 /* TEXT, PROPS */, _hoisted_10);
      }), 256 /* UNKEYED_FRAGMENT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)], 544 /* NEED_HYDRATION, NEED_PATCH */)])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_11, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_DatePicker, {
        columns: "2",
        modelValue: $data.dropDate,
        "onUpdate:modelValue": _cache[2] || (_cache[2] = function ($event) {
          return $data.dropDate = $event;
        }),
        mode: "date",
        "class": "date_wrap",
        "min-date": _this.dropMinDate,
        masks: $data.masks
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function (_ref2) {
          var inputValue = _ref2.inputValue,
            inputEvents = _ref2.inputEvents,
            togglePopover = _ref2.togglePopover;
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
            name: "dropDate",
            "class": "date_input",
            value: inputValue,
            onClick: togglePopover,
            placeholder: "Dropoff",
            autocomplete: "off"
          }, null, 8 /* PROPS */, _hoisted_12)];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["modelValue", "min-date", "masks"]), _hoisted_13]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, [_hoisted_16, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_17, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", _hoisted_18, [_hoisted_19, _this.tripTimeArr && _this.tripTimeArr.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
        key: 0
      }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)(_this.tripTimeArr, function (tripTimeRow, index) {
        var _$data$store$searchDa2;
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("option", {
          value: tripTimeRow.key,
          selected: tripTimeRow.key == ((_$data$store$searchDa2 = $data.store.searchData) === null || _$data$store$searchDa2 === void 0 ? void 0 : _$data$store$searchDa2.dropTime)
        }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(tripTimeRow.title), 9 /* TEXT, PROPS */, _hoisted_20);
      }), 256 /* UNKEYED_FRAGMENT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)], 512 /* NEED_PATCH */)])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_21, [_hoisted_22, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "hidden",
        name: "city",
        value: $data.cityModal.id
      }, null, 8 /* PROPS */, _hoisted_23), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ModelListSelect, {
        list: $data.cityList,
        modelValue: $data.cityModal,
        "onUpdate:modelValue": _cache[3] || (_cache[3] = function ($event) {
          return $data.cityModal = $event;
        }),
        placeholder: "Where To ?",
        "option-value": "id",
        "option-text": "name"
      }, null, 8 /* PROPS */, ["list", "modelValue"])]), _hoisted_24];
    }),
    _: 1 /* STABLE */
  })], 64 /* STABLE_FRAGMENT */);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue?vue&type=template&id=09315e7e":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue?vue&type=template&id=09315e7e ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "swiper tesiimg_slider",
  ref: "sliderRef"
};
var _hoisted_2 = {
  "class": "swiper-wrapper"
};
var _hoisted_3 = {
  "class": "swiper-slide"
};
var _hoisted_4 = ["src", "alt"];
var _hoisted_5 = {
  "class": "swiper-button-next",
  ref: "sliderNextRef"
};
var _hoisted_6 = {
  "class": "swiper-button-prev",
  ref: "sliderPrevRef"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_TestiImageWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("TestiImageWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_TestiImageWrapper, {
    "class": "testimage"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.images, function (image) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
          src: image.src,
          alt: image.alt
        }, null, 8 /* PROPS */, _hoisted_4)]);
      }), 256 /* UNKEYED_FRAGMENT */))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, null, 512 /* NEED_PATCH */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_6, null, 512 /* NEED_PATCH */)], 512 /* NEED_PATCH */)];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue?vue&type=template&id=3ce9f667":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue?vue&type=template&id=3ce9f667 ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = ["alt", "src"];
var _hoisted_2 = {
  "class": "testimonial_bottom"
};
var _hoisted_3 = {
  "class": "testimonial_author"
};
var _hoisted_4 = ["src", "alt"];
var _hoisted_5 = ["innerHTML"];
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Link = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Link");
  var _component_TestimonialCardWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("TestimonialCardWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_TestimonialCardWrapper, {
    "class": "testimonial_card"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
        href: $props.data.url
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
            alt: $props.data.name,
            src: $props.data.testimonialThumbSrc
          }, null, 8 /* PROPS */, _hoisted_1), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
            src: $props.data.imageSrc,
            alt: $props.data.name
          }, null, 8 /* PROPS */, _hoisted_4), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)($props.data.name), 1 /* TEXT */)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
            "class": "card_info",
            innerHTML: $props.data.description
          }, null, 8 /* PROPS */, _hoisted_5)])];
        }),
        _: 1 /* STABLE */
      }, 8 /* PROPS */, ["href"])];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue?vue&type=template&id=59a38e1b":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue?vue&type=template&id=59a38e1b ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "lg:w-1/3 md:w-1/2 w-full"
};
var _hoisted_2 = {
  "class": "form-floating mb-3 w-full"
};
var _hoisted_3 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "Name"
}, "Title*", -1 /* HOISTED */);
var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
  value: ""
}, "Select Title *", -1 /* HOISTED */);
var _hoisted_5 = ["value"];
var _hoisted_6 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  id: "title_error",
  "class": "validation_error"
}, null, -1 /* HOISTED */);
var _hoisted_7 = {
  "class": "lg:w-1/3 md:w-1/2 w-full"
};
var _hoisted_8 = {
  "class": "form-floating mb-3 w-full"
};
var _hoisted_9 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "Name"
}, "Name*", -1 /* HOISTED */);
var _hoisted_10 = ["value"];
var _hoisted_11 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  id: "name_error",
  "class": "validation_error"
}, null, -1 /* HOISTED */);
var _hoisted_12 = {
  "class": "lg:w-1/3 md:w-1/2 w-full"
};
var _hoisted_13 = {
  "class": "form-floating mb-3 w-full"
};
var _hoisted_14 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "Name"
}, "Email*", -1 /* HOISTED */);
var _hoisted_15 = ["value"];
var _hoisted_16 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  id: "email_error",
  "class": "validation_error"
}, null, -1 /* HOISTED */);
var _hoisted_17 = {
  "class": "lg:w-1/3 md:w-1/2 w-full"
};
var _hoisted_18 = {
  "class": "form-floating mb-3 w-full"
};
var _hoisted_19 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "Name"
}, "Company Name", -1 /* HOISTED */);
var _hoisted_20 = ["value"];
var _hoisted_21 = {
  "class": "lg:w-1/3 md:w-1/2 w-full"
};
var _hoisted_22 = {
  "class": "form-floating mb-3 w-full"
};
var _hoisted_23 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "Name"
}, "Position in company", -1 /* HOISTED */);
var _hoisted_24 = ["value"];
var _hoisted_25 = {
  "class": "lg:w-1/3 md:w-1/2 w-full"
};
var _hoisted_26 = {
  "class": "form-floating mb-3 w-full"
};
var _hoisted_27 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "Name"
}, "Website", -1 /* HOISTED */);
var _hoisted_28 = ["value"];
var _hoisted_29 = {
  "class": "form-floating mb-3 w-full"
};
var _hoisted_30 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
  "for": "description",
  "class": "control-label"
}, "Content*", -1 /* HOISTED */);
var _hoisted_31 = ["value"];
var _hoisted_32 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
  id: "description_error",
  "class": "validation_error"
}, null, -1 /* HOISTED */);
var _hoisted_33 = {
  "class": "submit_btn form-floating mb-3 mt-3"
};
var _hoisted_34 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
  type: "hidden",
  name: "package_id",
  id: "package_id",
  value: "0"
}, null, -1 /* HOISTED */);
var _hoisted_35 = ["value"];
var _hoisted_36 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
  type: "submit",
  "class": "custom-btn btnSubmit",
  name: "submit"
}, "Submit", -1 /* HOISTED */);
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _this = this;
  var _component_ckeditor = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ckeditor");
  var _component_TestimonialFormWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("TestimonialFormWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_TestimonialFormWrapper, null, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      var _this$userData, _this$userData2, _this$userData3, _this$userData4, _this$userData5;
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
        id: "testimonial_add_form",
        method: "POST",
        autocomplete: "off",
        onSubmit: _cache[7] || (_cache[7] = function () {
          return $options.handleFormSubmit && $options.handleFormSubmit.apply($options, arguments);
        }),
        "class": "flex flex-wrap"
      }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [_hoisted_3, $props.nameTitleArr ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("select", {
        key: 0,
        "class": "form-control valid",
        name: "title",
        id: "title",
        onChange: _cache[0] || (_cache[0] = function ($event) {
          return $options.handleChange($event);
        })
      }, [_hoisted_4, ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.nameTitleArr, function (val, key) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("option", {
          value: key
        }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(val), 9 /* TEXT, PROPS */, _hoisted_5);
      }), 256 /* UNKEYED_FRAGMENT */))], 32 /* NEED_HYDRATION */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), _hoisted_6])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [_hoisted_9, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        id: "name",
        placeholder: "Name*",
        "class": "form-control",
        datatypeinput: "inputname",
        name: "name",
        value: (_this$userData = _this.userData) === null || _this$userData === void 0 ? void 0 : _this$userData.name,
        onChange: _cache[1] || (_cache[1] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_10), _hoisted_11])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_12, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_13, [_hoisted_14, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        id: "email",
        placeholder: "Email*",
        "class": "form-control",
        name: "email",
        value: (_this$userData2 = _this.userData) === null || _this$userData2 === void 0 ? void 0 : _this$userData2.email,
        onChange: _cache[2] || (_cache[2] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_15), _hoisted_16])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_17, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_18, [_hoisted_19, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        id: "company_name",
        placeholder: "Company Name",
        "class": "form-control",
        name: "company_name",
        value: (_this$userData3 = _this.userData) === null || _this$userData3 === void 0 ? void 0 : _this$userData3.company_name,
        onChange: _cache[3] || (_cache[3] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_20)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_21, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_22, [_hoisted_23, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        id: "position_in_company",
        placeholder: "Position in company",
        "class": "form-control",
        name: "position_in_company",
        value: (_this$userData4 = _this.userData) === null || _this$userData4 === void 0 ? void 0 : _this$userData4.position_in_company,
        onChange: _cache[4] || (_cache[4] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_24)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_25, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_26, [_hoisted_27, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "text",
        id: "website",
        placeholder: "Website",
        "class": "form-control",
        name: "website",
        value: (_this$userData5 = _this.userData) === null || _this$userData5 === void 0 ? void 0 : _this$userData5.website,
        onChange: _cache[5] || (_cache[5] = function ($event) {
          return $options.handleChange($event);
        })
      }, null, 40 /* PROPS, NEED_HYDRATION */, _hoisted_28)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_29, [_hoisted_30, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ckeditor, {
        modelValue: $data.editorData,
        "onUpdate:modelValue": _cache[6] || (_cache[6] = function ($event) {
          return $data.editorData = $event;
        })
      }, null, 8 /* PROPS */, ["modelValue"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("textarea", {
        name: "description",
        "class": "form-control",
        value: $data.editorData,
        style: {
          "display": "none"
        }
      }, null, 8 /* PROPS */, _hoisted_31), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" <textarea name=\"description\" @change=\"handleChange($event)\" style=\"height: 140px;\" class=\"form-control ckeditor\"></textarea>  "), _hoisted_32]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_33, [_hoisted_34, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
        type: "hidden",
        name: "action",
        value: _ctx.action
      }, null, 8 /* PROPS */, _hoisted_35), _hoisted_36])], 32 /* NEED_HYDRATION */)];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue?vue&type=template&id=d31765d6":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue?vue&type=template&id=d31765d6 ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "testimonial_slider swiper",
  ref: "sliderRef"
};
var _hoisted_2 = {
  "class": "swiper-wrapper"
};
var _hoisted_3 = {
  "class": "swiper-slide"
};
var _hoisted_4 = ["src"];
var _hoisted_5 = {
  "class": "slider_btns"
};
var _hoisted_6 = {
  "class": "slider_btn_next",
  ref: "sliderNextRef"
};
var _hoisted_7 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-solid fa-chevron-left"
}, null, -1 /* HOISTED */);
var _hoisted_8 = [_hoisted_7];
var _hoisted_9 = {
  "class": "slider_btn_prev",
  ref: "sliderPrevRef"
};
var _hoisted_10 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-solid fa-chevron-right"
}, null, -1 /* HOISTED */);
var _hoisted_11 = [_hoisted_10];
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_TestimonialImagesWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("TestimonialImagesWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_TestimonialImagesWrapper, {
    "class": "slider_box"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.images, function (image) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
          src: image.src
        }, null, 8 /* PROPS */, _hoisted_4)]);
      }), 256 /* UNKEYED_FRAGMENT */))])], 512 /* NEED_PATCH */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_6, [].concat(_hoisted_8), 512 /* NEED_PATCH */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_9, [].concat(_hoisted_11), 512 /* NEED_PATCH */)])];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue?vue&type=template&id=3fc15506":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue?vue&type=template&id=3fc15506 ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
  key: 0,
  "class": "title"
};
var _hoisted_3 = {
  "class": "slider_box"
};
var _hoisted_4 = {
  "class": "testimonial_slider swiper",
  ref: "sliderRef"
};
var _hoisted_5 = {
  "class": "swiper-wrapper"
};
var _hoisted_6 = {
  "class": "swiper-slide"
};
var _hoisted_7 = {
  "class": "slider_btns"
};
var _hoisted_8 = {
  "class": "slider_btn_next",
  ref: "sliderNextRef"
};
var _hoisted_9 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-solid fa-chevron-left"
}, null, -1 /* HOISTED */);
var _hoisted_10 = [_hoisted_9];
var _hoisted_11 = {
  "class": "slider_btn_prev",
  ref: "sliderPrevRef"
};
var _hoisted_12 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-solid fa-chevron-right"
}, null, -1 /* HOISTED */);
var _hoisted_13 = [_hoisted_12];
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_TestimonialCard = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("TestimonialCard");
  var _component_SectionWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("SectionWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_SectionWrapper, null, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [$props.data.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("h3", _hoisted_2, "Similar Testimonials")) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.data, function (item) {
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_6, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_TestimonialCard, {
          data: item
        }, null, 8 /* PROPS */, ["data"])]);
      }), 256 /* UNKEYED_FRAGMENT */))])], 512 /* NEED_PATCH */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [].concat(_hoisted_10), 512 /* NEED_PATCH */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_11, [].concat(_hoisted_13), 512 /* NEED_PATCH */)])])])];
    }),
    _: 1 /* STABLE */
  });
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue?vue&type=template&id=d0f7bfd0":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue?vue&type=template&id=d0f7bfd0 ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "container"
};
var _hoisted_2 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "theme_title"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "text-xl font-semibold pb-3"
}, "Testimonials")], -1 /* HOISTED */);
var _hoisted_3 = {
  "class": "slider_box"
};
var _hoisted_4 = {
  "class": "swiper testimonial_slider",
  ref: "sliderRef"
};
var _hoisted_5 = {
  "class": "swiper-wrapper"
};
var _hoisted_6 = {
  "class": "swiper-slide"
};
var _hoisted_7 = {
  "class": "testimonial_box testcont ml-5"
};
var _hoisted_8 = {
  "class": "testi_heading pb-1 text-2xl Lato"
};
var _hoisted_9 = ["href"];
var _hoisted_10 = ["innerHTML"];
var _hoisted_11 = {
  "class": "client_info"
};
var _hoisted_12 = ["href"];
var _hoisted_13 = {
  "class": "client_name pt-2"
};
var _hoisted_14 = {
  "class": "h-50"
};
var _hoisted_15 = ["src", "alt"];
var _hoisted_16 = {
  "class": "name para_lg2"
};
var _hoisted_17 = {
  "class": "slider_btns"
};
var _hoisted_18 = {
  "class": "cat-next theme-next",
  ref: "sliderNextRef"
};
var _hoisted_19 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-solid fa-chevron-left"
}, null, -1 /* HOISTED */);
var _hoisted_20 = [_hoisted_19];
var _hoisted_21 = {
  "class": "cat-prev theme-prev",
  ref: "sliderPrevRef"
};
var _hoisted_22 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("i", {
  "class": "fa-solid fa-chevron-right"
}, null, -1 /* HOISTED */);
var _hoisted_23 = [_hoisted_22];
var _hoisted_24 = {
  "class": "view_all_btn flex justify-center"
};
var _hoisted_25 = ["href"];
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_InnerImageSlider = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("InnerImageSlider");
  var _component_TestimonialWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("TestimonialWrapper");
  return $props.testimonials ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_TestimonialWrapper, {
    key: 0,
    id: "review",
    "class": "home_testimonial home_featured mt-7 pt-2 py-3.5 px-2 border border-gray-300 rounded"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      var _$data$store$websiteS2;
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [_hoisted_2, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)($props.testimonials, function (testimonial) {
        var _$data$store$websiteS;
        return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_6, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_InnerImageSlider, {
          images: testimonial.images
        }, null, 8 /* PROPS */, ["images"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_8, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
          href: (_$data$store$websiteS = $data.store.websiteSettings) === null || _$data$store$websiteS === void 0 ? void 0 : _$data$store$websiteS.TESTIMONIAL_URL
        }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(testimonial.title), 9 /* TEXT, PROPS */, _hoisted_9)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", {
          innerHTML: testimonial.description
        }, null, 8 /* PROPS */, _hoisted_10), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_11, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
          href: testimonial.url
        }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_13, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("img", {
          "class": "h-10",
          src: testimonial.thumbSrc,
          alt: testimonial.name
        }, null, 8 /* PROPS */, _hoisted_15)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_16, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(testimonial.name), 1 /* TEXT */)])], 8 /* PROPS */, _hoisted_12)])])]);
      }), 256 /* UNKEYED_FRAGMENT */))])], 512 /* NEED_PATCH */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_17, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_18, [].concat(_hoisted_20), 512 /* NEED_PATCH */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_21, [].concat(_hoisted_23), 512 /* NEED_PATCH */)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_24, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("a", {
        href: (_$data$store$websiteS2 = $data.store.websiteSettings) === null || _$data$store$websiteS2 === void 0 ? void 0 : _$data$store$websiteS2.TESTIMONIAL_URL
      }, "View All", 8 /* PROPS */, _hoisted_25)])])];
    }),
    _: 1 /* STABLE */
  })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/contact.vue?vue&type=template&id=4674a44f":
/*!***************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/contact.vue?vue&type=template&id=4674a44f ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************/
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
  "class": "gray_add_box"
};
var _hoisted_3 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("hr", {
  "class": "my-5"
}, null, -1 /* HOISTED */);
var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("hr", {
  "class": "my-5"
}, null, -1 /* HOISTED */);
var _hoisted_5 = {
  "class": "container"
};
var _hoisted_6 = {
  "class": "form_box"
};
var _hoisted_7 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", null, "Contact Us", -1 /* HOISTED */);
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_MapItem = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("MapItem");
  var _component_AddressWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("AddressWrapper");
  var _component_formShortCode = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("formShortCode");
  var _component_ContactFormWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ContactFormWrapper");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_AddressWrapper, {
    "class": "contact_us_address pt-7"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      var _$props$contactDetail, _$props$contactDetail2, _$props$contactDetail3, _$props$contactDetail4, _$props$contactDetail5, _$props$contactDetail6, _$props$contactDetail7, _$props$contactDetail8, _$props$contactDetail9, _$props$contactDetail10, _$props$contactDetail11, _$props$contactDetail12;
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(_$props$contactDetail = $props.contactDetails) !== null && _$props$contactDetail !== void 0 && _$props$contactDetail.CONTACT_RESERVATION_OFFICE || (_$props$contactDetail2 = $props.contactDetails) !== null && _$props$contactDetail2 !== void 0 && _$props$contactDetail2.CONTACT_RESERVATION_OFFICE_IFRAME ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_MapItem, {
        key: 0,
        address: (_$props$contactDetail3 = $props.contactDetails) === null || _$props$contactDetail3 === void 0 ? void 0 : _$props$contactDetail3.CONTACT_RESERVATION_OFFICE,
        map: (_$props$contactDetail4 = $props.contactDetails) === null || _$props$contactDetail4 === void 0 ? void 0 : _$props$contactDetail4.CONTACT_RESERVATION_OFFICE_IFRAME
      }, null, 8 /* PROPS */, ["address", "map"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (_$props$contactDetail5 = $props.contactDetails) !== null && _$props$contactDetail5 !== void 0 && _$props$contactDetail5.CONTACT_MAIN_OFFICE || (_$props$contactDetail6 = $props.contactDetails) !== null && _$props$contactDetail6 !== void 0 && _$props$contactDetail6.CONTACT_MAIN_OFFICE_IFRAME ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
        key: 1
      }, [_hoisted_3, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_MapItem, {
        address: (_$props$contactDetail7 = $props.contactDetails) === null || _$props$contactDetail7 === void 0 ? void 0 : _$props$contactDetail7.CONTACT_MAIN_OFFICE,
        map: (_$props$contactDetail8 = $props.contactDetails) === null || _$props$contactDetail8 === void 0 ? void 0 : _$props$contactDetail8.CONTACT_MAIN_OFFICE_IFRAME
      }, null, 8 /* PROPS */, ["address", "map"])], 64 /* STABLE_FRAGMENT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (_$props$contactDetail9 = $props.contactDetails) !== null && _$props$contactDetail9 !== void 0 && _$props$contactDetail9.CONTACT_DELHI_OFFICE || (_$props$contactDetail10 = $props.contactDetails) !== null && _$props$contactDetail10 !== void 0 && _$props$contactDetail10.CONTACT_DELHI_OFFICE_IFRAME ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, {
        key: 2
      }, [_hoisted_4, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_MapItem, {
        address: (_$props$contactDetail11 = $props.contactDetails) === null || _$props$contactDetail11 === void 0 ? void 0 : _$props$contactDetail11.CONTACT_DELHI_OFFICE,
        map: (_$props$contactDetail12 = $props.contactDetails) === null || _$props$contactDetail12 === void 0 ? void 0 : _$props$contactDetail12.CONTACT_DELHI_OFFICE_IFRAME
      }, null, 8 /* PROPS */, ["address", "map"])], 64 /* STABLE_FRAGMENT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])])];
    }),
    _: 1 /* STABLE */
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_ContactFormWrapper, null, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_5, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_6, [_hoisted_7, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_formShortCode, {
        slug: "[contact_us]",
        "class": "left_form"
      })])])];
    }),
    _: 1 /* STABLE */
  })], 64 /* STABLE_FRAGMENT */);
}

/***/ }),

/***/ "./resources/js/themes/andamanisland/data.js":
/*!***************************************************!*\
  !*** ./resources/js/themes/andamanisland/data.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   airportLists: () => (/* binding */ airportLists),
/* harmony export */   blogCategories: () => (/* binding */ blogCategories),
/* harmony export */   clients: () => (/* binding */ clients),
/* harmony export */   cmsData: () => (/* binding */ cmsData),
/* harmony export */   destinations: () => (/* binding */ destinations),
/* harmony export */   dummyBlogs: () => (/* binding */ dummyBlogs),
/* harmony export */   dummyFaqs: () => (/* binding */ dummyFaqs),
/* harmony export */   dummyPackageData: () => (/* binding */ dummyPackageData),
/* harmony export */   dummyTeam: () => (/* binding */ dummyTeam),
/* harmony export */   dummyTestimonials: () => (/* binding */ dummyTestimonials),
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
var destinations = {
  data: [{
    title: 'Ladakh',
    url: '#',
    thumbSrc: './assets/traveltour/images/destination1.jpg'
  }, {
    title: 'Rajasthan',
    url: '#',
    thumbSrc: './assets/traveltour/images/destination2.jpg'
  }, {
    title: 'Goa',
    url: '#',
    thumbSrc: './assets/traveltour/images/destination3.jpg'
  }, {
    title: 'Kerala',
    url: '#',
    thumbSrc: './assets/traveltour/images/destination4.jpg'
  }, {
    title: 'Varanasi',
    url: '#',
    thumbSrc: './assets/traveltour/images/destination5.jpg'
  }, {
    title: 'Himachal Pradesh',
    url: '#',
    thumbSrc: './assets/traveltour/images/destination6.jpg'
  }],
  url: "#"
};
var clients = [{
  image: './assets/traveltour/images/client1.png'
}, {
  image: './assets/traveltour/images/client2.png'
}, {
  image: './assets/traveltour/images/client3.png'
}, {
  image: './assets/traveltour/images/client4.png'
}, {
  image: './assets/traveltour/images/client5.png'
}, {
  image: './assets/traveltour/images/client6.png'
}, {
  image: './assets/traveltour/images/client1.png'
}, {
  image: './assets/traveltour/images/client2.png'
}, {
  image: './assets/traveltour/images/client3.png'
}, {
  image: './assets/traveltour/images/client4.png'
}, {
  image: './assets/traveltour/images/client5.png'
}, {
  image: './assets/traveltour/images/client6.png'
}, {
  image: './assets/traveltour/images/client1.png'
}, {
  image: './assets/traveltour/images/client2.png'
}, {
  image: './assets/traveltour/images/client3.png'
}, {
  image: './assets/traveltour/images/client4.png'
}, {
  image: './assets/traveltour/images/client5.png'
}, {
  image: './assets/traveltour/images/client6.png'
}, {
  image: './assets/traveltour/images/client1.png'
}, {
  image: './assets/traveltour/images/client2.png'
}, {
  image: './assets/traveltour/images/client3.png'
}, {
  image: './assets/traveltour/images/client4.png'
}, {
  image: './assets/traveltour/images/client5.png'
}, {
  image: './assets/traveltour/images/client6.png'
}];
var dummyPackageData = [{
  url: '#',
  thumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Dummy 1',
  search_price: 1000,
  package_type: 'Flexi package',
  days: 4,
  nights: 3
}, {
  url: '#',
  thumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Dummy 2',
  search_price: 1000,
  package_type: 'Flexi package',
  days: 4,
  nights: 3
}, {
  url: '#',
  thumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Dummy 3',
  search_price: 1000,
  package_type: 'Flexi package',
  days: 4,
  nights: 3
}, {
  url: '#',
  thumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Dummy 4',
  search_price: 1000,
  package_type: 'Flexi package',
  days: 4,
  nights: 3
}];
var dummyBlogs = [{
  url: '/blog/detail/testing-usewr',
  blogthumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  image: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Test Blog',
  author: 'Test Author',
  date: '22 Jun 2023',
  categories: [{
    id: 1,
    name: 'cat1',
    url: '/blog/culture'
  }, {
    id: 2,
    name: 'cat2',
    url: '/blog/culture'
  }],
  socialLinks: [{
    fb: 'https://www.facebook.com/',
    twitter: 'https://twitter.com/',
    whatsapp: 'https://web.whatsapp.com'
  }],
  description: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
}, {
  url: '/blog/detail/testing-usewr',
  blogthumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  image: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Test Blog',
  author: 'Test Author',
  date: '22 Jun 2023',
  categories: [{
    id: 1,
    name: 'cat1'
  }, {
    id: 2,
    name: 'cat2'
  }],
  socialLinks: [{
    fb: 'https://www.facebook.com/',
    twitter: 'https://twitter.com/',
    whatsapp: 'https://web.whatsapp.com'
  }],
  description: 'Test Description'
}, {
  url: '/blog/detail/testing-usewr',
  blogthumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  image: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Test Blog',
  author: 'Test Author',
  date: '22 Jun 2023',
  categories: [{
    id: 1,
    name: 'cat1'
  }, {
    id: 2,
    name: 'cat2'
  }],
  socialLinks: [{
    fb: 'https://www.facebook.com/',
    twitter: 'https://twitter.com/',
    whatsapp: 'https://web.whatsapp.com'
  }],
  description: 'Test Description'
}, {
  url: '/blog/detail/testing-usewr',
  blogthumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  image: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Test Blog',
  author: 'Test Author',
  date: '22 Jun 2023',
  categories: [{
    id: 1,
    name: 'cat1'
  }, {
    id: 2,
    name: 'cat2'
  }],
  socialLinks: [{
    fb: 'https://www.facebook.com/',
    twitter: 'https://twitter.com/',
    whatsapp: 'https://web.whatsapp.com'
  }],
  description: 'Test Description'
}, {
  url: '/blog/detail/testing-usewr',
  blogthumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  image: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Test Blog',
  author: 'Test Author',
  date: '22 Jun 2023',
  categories: [{
    id: 1,
    name: 'cat1'
  }, {
    id: 2,
    name: 'cat2'
  }],
  socialLinks: [{
    fb: 'https://www.facebook.com/',
    twitter: 'https://twitter.com/',
    whatsapp: 'https://web.whatsapp.com'
  }],
  description: 'Test Description'
}, {
  url: '/blog/detail/testing-usewr',
  blogthumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  image: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Test Blog',
  author: 'Test Author',
  date: '22 Jun 2023',
  categories: [{
    id: 1,
    name: 'cat1'
  }, {
    id: 2,
    name: 'cat2'
  }],
  socialLinks: [{
    fb: 'https://www.facebook.com/',
    twitter: 'https://twitter.com/',
    whatsapp: 'https://web.whatsapp.com'
  }],
  description: 'Test Description'
}, {
  url: '/blog/detail/testing-usewr',
  blogthumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  image: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Test Blog',
  author: 'Test Author',
  date: '22 Jun 2023',
  categories: [{
    id: 1,
    name: 'cat1'
  }, {
    id: 2,
    name: 'cat2'
  }],
  socialLinks: [{
    fb: 'https://www.facebook.com/',
    twitter: 'https://twitter.com/',
    whatsapp: 'https://web.whatsapp.com'
  }],
  description: 'Test Description'
}, {
  url: '/blog/detail/testing-usewr',
  blogthumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  image: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Test Blog',
  author: 'Test Author',
  date: '22 Jun 2023',
  categories: [{
    id: 1,
    name: 'cat1'
  }, {
    id: 2,
    name: 'cat2'
  }],
  socialLinks: [{
    fb: 'https://www.facebook.com/',
    twitter: 'https://twitter.com/',
    whatsapp: 'https://web.whatsapp.com'
  }],
  description: 'Test Description'
}, {
  url: '/blog/detail/testing-usewr',
  blogthumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  image: '/assets/traveltour/images/noimagebig.jpg',
  title: 'Test Blog',
  author: 'Test Author',
  date: '22 Jun 2023',
  categories: [{
    id: 1,
    name: 'cat1'
  }, {
    id: 2,
    name: 'cat2'
  }],
  socialLinks: [{
    fb: 'https://www.facebook.com/',
    twitter: 'https://twitter.com/',
    whatsapp: 'https://web.whatsapp.com'
  }],
  description: 'Test Description'
}];
var dummyTestimonials = [{
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}, {
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}, {
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}, {
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}, {
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}, {
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}, {
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}, {
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}, {
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}, {
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}, {
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}, {
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}, {
  url: '/testimonial/test',
  testimonialThumbSrc: '/assets/traveltour/images/noimagebig.jpg',
  imageSrc: '/assets/traveltour/images/noimagebig.jpg',
  name: 'test',
  description: 'We were a group of three and wanted a bit of everything in Ladakh - adventure, luxury stay, , relaxed activities and a taste of nature. The trip provided us all of it within just 6 days!'
}];
var blogCategories = [{
  url: '/blog/culture',
  title: 'Category1'
}, {
  url: '/blog/culture',
  title: 'Category2'
}, {
  url: '/blog/culture',
  title: 'Category3'
}, {
  url: '/blog/culture',
  title: 'Category4'
}, {
  url: '/blog/culture',
  title: 'Category5'
}, {
  url: '/blog/culture',
  title: 'Category6'
}, {
  url: '/blog/culture',
  title: 'Category7'
}, {
  url: '/blog/culture',
  title: 'Category8'
}];
var dummyTeam = [{
  image: '/assets/traveltour/images/noimagebig.jpg',
  name: 'Employe 1',
  designation: 'Co FOunder',
  detail: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
}, {
  image: '/assets/traveltour/images/noimagebig.jpg',
  name: 'Employe 2',
  designation: 'Co FOunder',
  detail: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
}, {
  image: '/assets/traveltour/images/noimagebig.jpg',
  name: 'Employe 3',
  designation: 'Co FOunder',
  detail: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
}, {
  image: '/assets/traveltour/images/noimagebig.jpg',
  name: 'Employe 4',
  designation: 'Co FOunder',
  detail: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
}, {
  image: '/assets/traveltour/images/noimagebig.jpg',
  name: 'Employe 5',
  designation: 'Co FOunder',
  detail: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
}, {
  image: '/assets/traveltour/images/noimagebig.jpg',
  name: 'Employe 6',
  designation: 'Co FOunder',
  detail: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
}, {
  image: '/assets/traveltour/images/noimagebig.jpg',
  name: 'Employe 7',
  designation: 'Co FOunder',
  detail: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
}, {
  image: '/assets/traveltour/images/noimagebig.jpg',
  name: 'Employe 8',
  designation: 'Co FOunder',
  detail: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
}, {
  image: '/assets/traveltour/images/noimagebig.jpg',
  name: 'Employe 9',
  designation: 'Co FOunder',
  detail: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
}, {
  image: '/assets/traveltour/images/noimagebig.jpg',
  name: 'Employe 10',
  designation: 'Co FOunder',
  detail: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
}, {
  image: '/assets/traveltour/images/noimagebig.jpg',
  name: 'Employe 11',
  designation: 'Co FOunder',
  detail: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
}, {
  image: '/assets/traveltour/images/noimagebig.jpg',
  name: 'Employe 12',
  designation: 'Co FOunder',
  detail: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"
}];
var cmsData = {
  image: '/assets/traveltour/images/blog-and-news.jpg',
  content: "<p></p><p>Overland Escape was established in August 1999 and now is one of the leading travel companies responsible for marketing Ladakh worldwide and India as well, thereby contributing in a positive way in developing economy of Ladakh. In order to ensure that Ladakh is marketed in an inspirational and relevant way we have been working vigorously with our partners both in India and overseas. Ever since its inception, Overland Escape has cultivated the friendship and cooperation in the field of travel related services with its valuable clients. We offer custom-made tour packages of India, Nepal, Bhutan and Tibet at competitive prices and have established ourselves as a travel company with interesting inbound and outbound tour itineraries. We are backed up by a quality management system.</p><p>We at Overland believe in protecting the character of the destination we work on i.e. preserving the different cultures and traditions around us which inspires us to remain passionate about travelling and helping others to experience the destinations that we treasure. Protecting the natural environment through the promotion and support of various conservation efforts is also our priority. One of the initiatives of Overland in this field is the annual garbage cleaning trek which is held every year around September.</p><p>Training and employing local personnel is what we believe in, thus enhancing the dimension of your visit and supporting the economy of the local communities. We are also a reliable partner aiming to help improve the efficiency, productivity, standing and success of our esteemed customers. <br><br></p><p></p>\n        \n    <p>Overland Escape was established in August 1999 and now is one of the leading travel companies responsible for marketing Ladakh worldwide and India as well, thereby contributing in a positive way in developing economy of Ladakh. In order to ensure that Ladakh is marketed in an inspirational and relevant way we have been working vigorously with our partners both in India and overseas. Ever since its inception, Overland Escape has cultivated the friendship and cooperation in the field of travel related services with its valuable clients. We offer custom-made tour packages of India, Nepal, Bhutan and Tibet at competitive prices and have established ourselves as a travel company with interesting inbound and outbound tour itineraries. We are backed up by a quality management system.</p>\n\n    <p>We at Overland believe in protecting the character of the destination we work on i.e. preserving the different cultures and traditions around us which inspires us to remain passionate about travelling and helping others to experience the destinations that we treasure. Protecting the natural environment through the promotion and support of various conservation efforts is also our priority. One of the initiatives of Overland in this field is the annual garbage cleaning trek which is held every year around September.</p>\n\n    <p>Training and employing local personnel is what we believe in, thus enhancing the dimension of your visit and supporting the economy of the local communities. We are also a reliable partner aiming to help improve the efficiency, productivity, standing and success of our esteemed customers.<br>\n    &nbsp;</p>"
};
var dummyFaqs = [{
  id: 1,
  question: 'Where is Andaman and Nicobar Islands?',
  answer: "\n    <p>A union territory of India, the Andaman and Nicobar Islands consist of as many as 572 islands. Of these, only 38 are inhabited. The island is at the juncture of the Bay of Bengal and the Andaman Sea. Nearly 150 kilometers north of Aceh in Indonesia, the territory is separated from Myanmar and Thailand by the Andaman Sea. Two island groups are comprised in it\u2014 the Andaman Islands (partly) and the Nicobar Islands. The 150-kilometer wide Ten Degree Channel (on the 10\xB0N parallel) separates them.</p>\n    <p>Port Blair is the capital of the Andaman and Nicobar Islands. The total land area of the islands is around 8,249 square kilometers. It is divided into three districts:</p>\n    <ol>\n      <li>The Nicobar District with Car Nicobar as its capital</li>\n      <li>The South Andaman district with Port Blair as its capital</li>\n      <li>The North and Middle Andaman district with Mayabunder as its capital</li>\n    </ol>\n    "
}, {
  id: 2,
  question: 'Which is the best island to visit in Andaman?',
  answer: "\n    <p>Geographically, the Andamans are located in the east of the mainland of India. Featuring secluded beaches bordering the azure cobalt ocean, the Andaman and Nicobar Islands are blessed with the best of nature. The islands of this union territory of India are proofs of God\u2019s vivid imagination. The golden and white sands of the beaches meeting the turquoise waters of the ocean and the green shades of lush forests creating scenery in the archipelago is beautiful beyond anything else!</p>\n    <p>The Andaman and Nicobar Islands comprise gorgeous islands. The best among them are as follows:</p>\n    <ul>\n       <li><a href=\"https://www.andamanisland.in/destination/havelock-island\">Havelock Island</a></li>\n       <li><a href=\"https://www.andamanisland.in/blog/detail/jolly-buoy-island-red-skin-island-at-port-blair\">Jolly Buoy Island</a></li>\n       <li><a href=\"https://www.andamanisland.in/destination/neil-island\">Neil Island</a></li>\n       <li><a href=\"https://www.andamanisland.in/blog/detail/how-to-reach-ross-island\">Ross Island</a></li>\n       <li><a href=\"https://www.andamanisland.in/blog/detail/ross-island-north-bay-island-at-port-blair\">North Bay Island</a></li>\n       <li><a href=\"https://www.andamanisland.in/content/ross-smith-island\">Ross and Smith Island</a></li>\n       <li><a href=\"https://www.andamanisland.in/blog/detail/cinque-island-at-port-blair\">Cinque Island</a></li>\n       <li><a href=\"https://www.andamanisland.in/destination/baratang-island\">Baratang Island</a></li>\n       <li><a href=\"https://www.andamanisland.in/blog/detail/jolly-buoy-island-red-skin-island-at-port-blair\">Red Skin Island</a></li>\n       <li><a href=\"https://www.andamanisland.in/blog/detail/barren-island-tour\">Barren Island</a></li>\n       <li>Inglis Island</li>\n       <li><a href=\"https://www.andamanisland.in/blog/detail/parrot-island-at-baratang-island\">Parrot Island</a></li>\n       <li><a href=\"https://www.andamanisland.in/destination/mayabunder-island\">Mayabunder Island</a></li>\n       <li><a href=\"https://www.andamanisland.in/destination/diglipur-island\">Diglipur Island</a></li>\n    </ul>\n    "
}, {
  id: 3,
  question: 'Which is the best island to visit in Andaman?',
  answer: "\n    <p>You can visit the Andaman and Nicobar Islands all around the year. Having its own charm in each and every season, there is a lot to explore in the island destination. The Andamans are popular for the green-blue waters, pure beauty, along with the mental peace it provides to the travellers. A majority of the tourists visit Andaman during the months from October to May. At this time, the downpour has its own charm. The natural beauty is absolutely mesmerising. The calming waters and lush greenery further accentuate the beauty of the tropical island.</p>\n    <p>You can pick any season to visit the beautiful Andaman and Nicobar Islands:</p>\n    <ul>\n      <li>Summer- April to June</li>\n      <li>Monsoon- July to September</li>\n      <li>Winter- October to March</li>\n    </ul>\n    <p>Just make sure that you have packed accordingly.</p>\n    "
}, {
  id: 4,
  question: 'Which is the best island to visit in Andaman?',
  answer: "\n    <p style=\"box-sizing: border-box; margin: 0px 0px 15px; font-size: 17px; color: #666666; line-height: 28px; font-family: Lato, sans-serif; outline: 0px !important;\"><span style=\"font-size: 12pt;\">Andaman Island is the perfect destination for those who love nature and adventure. There are a number of places to see here that are sure to take your breath away. However, it is also very important that you enjoy your stay here as well. Some people prefer a beachfront resort property while others want to stay in a hotel located in the main market. Requirements of all kinds of guests can be easily fulfilled in the Andamans as it has grand and lavish beachfront properties as well as budget hotels across all the tourist destinations.</span></p>\n    <p style=\"box-sizing: border-box; margin: 0px 0px 15px; font-size: 17px; color: #666666; line-height: 28px; font-family: Lato, sans-serif; outline: 0px !important;\"><span style=\"font-size: 12pt;\">If you want to stay at a beachfront resort, then we would highly recommend <a href=\"https://www.andamanisland.in/hotel/havelock-island-beach-resort\">Havelock Island Beach Resort</a> as it is well-equipped with all the modern amenities and so much more! Its location is excellent as well. You can spend quality time at its private beach.</span></p>\n    "
}];


/***/ }),

/***/ "./resources/js/themes/andamanisland/components/popup.vue":
/*!****************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/popup.vue ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _popup_vue_vue_type_template_id_54ad6458__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./popup.vue?vue&type=template&id=54ad6458 */ "./resources/js/themes/andamanisland/components/popup.vue?vue&type=template&id=54ad6458");
/* harmony import */ var _popup_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./popup.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/components/popup.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_popup_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_popup_vue_vue_type_template_id_54ad6458__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/components/popup.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/rental/BikeListCard.vue":
/*!******************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/rental/BikeListCard.vue ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _BikeListCard_vue_vue_type_template_id_66be8522__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./BikeListCard.vue?vue&type=template&id=66be8522 */ "./resources/js/themes/andamanisland/components/rental/BikeListCard.vue?vue&type=template&id=66be8522");
/* harmony import */ var _BikeListCard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./BikeListCard.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/components/rental/BikeListCard.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_BikeListCard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_BikeListCard_vue_vue_type_template_id_66be8522__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/components/rental/BikeListCard.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue":
/*!********************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _BikeleftFilter_vue_vue_type_template_id_eb0fc1da__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./BikeleftFilter.vue?vue&type=template&id=eb0fc1da */ "./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue?vue&type=template&id=eb0fc1da");
/* harmony import */ var _BikeleftFilter_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./BikeleftFilter.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_BikeleftFilter_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_BikeleftFilter_vue_vue_type_template_id_eb0fc1da__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue":
/*!**********************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _RentalSearchForm_vue_vue_type_template_id_3ddb43ba__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RentalSearchForm.vue?vue&type=template&id=3ddb43ba */ "./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue?vue&type=template&id=3ddb43ba");
/* harmony import */ var _RentalSearchForm_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RentalSearchForm.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_RentalSearchForm_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_RentalSearchForm_vue_vue_type_template_id_3ddb43ba__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue":
/*!***************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue ***!
  \***************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _InnerImageSlider_vue_vue_type_template_id_09315e7e__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./InnerImageSlider.vue?vue&type=template&id=09315e7e */ "./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue?vue&type=template&id=09315e7e");
/* harmony import */ var _InnerImageSlider_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./InnerImageSlider.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_InnerImageSlider_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_InnerImageSlider_vue_vue_type_template_id_09315e7e__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue":
/*!**************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _TestimonialCard_vue_vue_type_template_id_3ce9f667__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TestimonialCard.vue?vue&type=template&id=3ce9f667 */ "./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue?vue&type=template&id=3ce9f667");
/* harmony import */ var _TestimonialCard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TestimonialCard.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_TestimonialCard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_TestimonialCard_vue_vue_type_template_id_3ce9f667__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue":
/*!**************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _TestimonialForm_vue_vue_type_template_id_59a38e1b__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TestimonialForm.vue?vue&type=template&id=59a38e1b */ "./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue?vue&type=template&id=59a38e1b");
/* harmony import */ var _TestimonialForm_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TestimonialForm.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_TestimonialForm_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_TestimonialForm_vue_vue_type_template_id_59a38e1b__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue":
/*!*********************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue ***!
  \*********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _TestimonialImageSlider_vue_vue_type_template_id_d31765d6__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TestimonialImageSlider.vue?vue&type=template&id=d31765d6 */ "./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue?vue&type=template&id=d31765d6");
/* harmony import */ var _TestimonialImageSlider_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TestimonialImageSlider.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_TestimonialImageSlider_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_TestimonialImageSlider_vue_vue_type_template_id_d31765d6__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue":
/*!***********************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue ***!
  \***********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _TestimonialSliderSection_vue_vue_type_template_id_3fc15506__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TestimonialSliderSection.vue?vue&type=template&id=3fc15506 */ "./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue?vue&type=template&id=3fc15506");
/* harmony import */ var _TestimonialSliderSection_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TestimonialSliderSection.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_TestimonialSliderSection_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_TestimonialSliderSection_vue_vue_type_template_id_3fc15506__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue":
/*!****************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _testimonialSlider_vue_vue_type_template_id_d0f7bfd0__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./testimonialSlider.vue?vue&type=template&id=d0f7bfd0 */ "./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue?vue&type=template&id=d0f7bfd0");
/* harmony import */ var _testimonialSlider_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./testimonialSlider.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_testimonialSlider_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_testimonialSlider_vue_vue_type_template_id_d0f7bfd0__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/contact.vue":
/*!*******************************************************!*\
  !*** ./resources/js/themes/andamanisland/contact.vue ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _contact_vue_vue_type_template_id_4674a44f__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./contact.vue?vue&type=template&id=4674a44f */ "./resources/js/themes/andamanisland/contact.vue?vue&type=template&id=4674a44f");
/* harmony import */ var _contact_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./contact.vue?vue&type=script&lang=js */ "./resources/js/themes/andamanisland/contact.vue?vue&type=script&lang=js");
/* harmony import */ var E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,E_travelpro_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_contact_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_contact_vue_vue_type_template_id_4674a44f__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/themes/andamanisland/contact.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/popup.vue?vue&type=script&lang=js":
/*!****************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/popup.vue?vue&type=script&lang=js ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_popup_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_popup_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./popup.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/popup.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/rental/BikeListCard.vue?vue&type=script&lang=js":
/*!******************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/rental/BikeListCard.vue?vue&type=script&lang=js ***!
  \******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_BikeListCard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_BikeListCard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./BikeListCard.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/BikeListCard.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue?vue&type=script&lang=js":
/*!********************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue?vue&type=script&lang=js ***!
  \********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_BikeleftFilter_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_BikeleftFilter_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./BikeleftFilter.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue?vue&type=script&lang=js":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_RentalSearchForm_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_RentalSearchForm_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./RentalSearchForm.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue?vue&type=script&lang=js":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue?vue&type=script&lang=js ***!
  \***************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_InnerImageSlider_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_InnerImageSlider_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./InnerImageSlider.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue?vue&type=script&lang=js":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue?vue&type=script&lang=js ***!
  \**************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialCard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialCard_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./TestimonialCard.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue?vue&type=script&lang=js":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue?vue&type=script&lang=js ***!
  \**************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialForm_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialForm_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./TestimonialForm.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue?vue&type=script&lang=js":
/*!*********************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue?vue&type=script&lang=js ***!
  \*********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialImageSlider_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialImageSlider_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./TestimonialImageSlider.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue?vue&type=script&lang=js":
/*!***********************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue?vue&type=script&lang=js ***!
  \***********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialSliderSection_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialSliderSection_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./TestimonialSliderSection.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue?vue&type=script&lang=js":
/*!****************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue?vue&type=script&lang=js ***!
  \****************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_testimonialSlider_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_testimonialSlider_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./testimonialSlider.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/contact.vue?vue&type=script&lang=js":
/*!*******************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/contact.vue?vue&type=script&lang=js ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_contact_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_contact_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./contact.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/contact.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/themes/andamanisland/components/popup.vue?vue&type=template&id=54ad6458":
/*!**********************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/popup.vue?vue&type=template&id=54ad6458 ***!
  \**********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_popup_vue_vue_type_template_id_54ad6458__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_popup_vue_vue_type_template_id_54ad6458__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./popup.vue?vue&type=template&id=54ad6458 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/popup.vue?vue&type=template&id=54ad6458");


/***/ }),

/***/ "./resources/js/themes/andamanisland/components/rental/BikeListCard.vue?vue&type=template&id=66be8522":
/*!************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/rental/BikeListCard.vue?vue&type=template&id=66be8522 ***!
  \************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_BikeListCard_vue_vue_type_template_id_66be8522__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_BikeListCard_vue_vue_type_template_id_66be8522__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./BikeListCard.vue?vue&type=template&id=66be8522 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/BikeListCard.vue?vue&type=template&id=66be8522");


/***/ }),

/***/ "./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue?vue&type=template&id=eb0fc1da":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue?vue&type=template&id=eb0fc1da ***!
  \**************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_BikeleftFilter_vue_vue_type_template_id_eb0fc1da__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_BikeleftFilter_vue_vue_type_template_id_eb0fc1da__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./BikeleftFilter.vue?vue&type=template&id=eb0fc1da */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/BikeleftFilter.vue?vue&type=template&id=eb0fc1da");


/***/ }),

/***/ "./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue?vue&type=template&id=3ddb43ba":
/*!****************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue?vue&type=template&id=3ddb43ba ***!
  \****************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_RentalSearchForm_vue_vue_type_template_id_3ddb43ba__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_RentalSearchForm_vue_vue_type_template_id_3ddb43ba__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./RentalSearchForm.vue?vue&type=template&id=3ddb43ba */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/rental/RentalSearchForm.vue?vue&type=template&id=3ddb43ba");


/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue?vue&type=template&id=09315e7e":
/*!*********************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue?vue&type=template&id=09315e7e ***!
  \*********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_InnerImageSlider_vue_vue_type_template_id_09315e7e__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_InnerImageSlider_vue_vue_type_template_id_09315e7e__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./InnerImageSlider.vue?vue&type=template&id=09315e7e */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/InnerImageSlider.vue?vue&type=template&id=09315e7e");


/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue?vue&type=template&id=3ce9f667":
/*!********************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue?vue&type=template&id=3ce9f667 ***!
  \********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialCard_vue_vue_type_template_id_3ce9f667__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialCard_vue_vue_type_template_id_3ce9f667__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./TestimonialCard.vue?vue&type=template&id=3ce9f667 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialCard.vue?vue&type=template&id=3ce9f667");


/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue?vue&type=template&id=59a38e1b":
/*!********************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue?vue&type=template&id=59a38e1b ***!
  \********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialForm_vue_vue_type_template_id_59a38e1b__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialForm_vue_vue_type_template_id_59a38e1b__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./TestimonialForm.vue?vue&type=template&id=59a38e1b */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialForm.vue?vue&type=template&id=59a38e1b");


/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue?vue&type=template&id=d31765d6":
/*!***************************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue?vue&type=template&id=d31765d6 ***!
  \***************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialImageSlider_vue_vue_type_template_id_d31765d6__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialImageSlider_vue_vue_type_template_id_d31765d6__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./TestimonialImageSlider.vue?vue&type=template&id=d31765d6 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialImageSlider.vue?vue&type=template&id=d31765d6");


/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue?vue&type=template&id=3fc15506":
/*!*****************************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue?vue&type=template&id=3fc15506 ***!
  \*****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialSliderSection_vue_vue_type_template_id_3fc15506__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TestimonialSliderSection_vue_vue_type_template_id_3fc15506__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./TestimonialSliderSection.vue?vue&type=template&id=3fc15506 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/TestimonialSliderSection.vue?vue&type=template&id=3fc15506");


/***/ }),

/***/ "./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue?vue&type=template&id=d0f7bfd0":
/*!**********************************************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue?vue&type=template&id=d0f7bfd0 ***!
  \**********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_testimonialSlider_vue_vue_type_template_id_d0f7bfd0__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_testimonialSlider_vue_vue_type_template_id_d0f7bfd0__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./testimonialSlider.vue?vue&type=template&id=d0f7bfd0 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/components/testimonial/testimonialSlider.vue?vue&type=template&id=d0f7bfd0");


/***/ }),

/***/ "./resources/js/themes/andamanisland/contact.vue?vue&type=template&id=4674a44f":
/*!*************************************************************************************!*\
  !*** ./resources/js/themes/andamanisland/contact.vue?vue&type=template&id=4674a44f ***!
  \*************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_contact_vue_vue_type_template_id_4674a44f__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_contact_vue_vue_type_template_id_4674a44f__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./contact.vue?vue&type=template&id=4674a44f */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/themes/andamanisland/contact.vue?vue&type=template&id=4674a44f");


/***/ }),

/***/ "./resources/js/themes/andamanisland/countrycodes.json":
/*!*************************************************************!*\
  !*** ./resources/js/themes/andamanisland/countrycodes.json ***!
  \*************************************************************/
/***/ ((module) => {

module.exports = /*#__PURE__*/JSON.parse('[{"name":"Afghanistan","dial_code":"+93","code":"AF"},{"name":"Aland Islands","dial_code":"+358","code":"AX"},{"name":"Albania","dial_code":"+355","code":"AL"},{"name":"Algeria","dial_code":"+213","code":"DZ"},{"name":"AmericanSamoa","dial_code":"+1684","code":"AS"},{"name":"Andorra","dial_code":"+376","code":"AD"},{"name":"Angola","dial_code":"+244","code":"AO"},{"name":"Anguilla","dial_code":"+1264","code":"AI"},{"name":"Antarctica","dial_code":"+672","code":"AQ"},{"name":"Antigua and Barbuda","dial_code":"+1268","code":"AG"},{"name":"Argentina","dial_code":"+54","code":"AR"},{"name":"Armenia","dial_code":"+374","code":"AM"},{"name":"Aruba","dial_code":"+297","code":"AW"},{"name":"Australia","dial_code":"+61","code":"AU"},{"name":"Austria","dial_code":"+43","code":"AT"},{"name":"Azerbaijan","dial_code":"+994","code":"AZ"},{"name":"Bahamas","dial_code":"+1242","code":"BS"},{"name":"Bahrain","dial_code":"+973","code":"BH"},{"name":"Bangladesh","dial_code":"+880","code":"BD"},{"name":"Barbados","dial_code":"+1246","code":"BB"},{"name":"Belarus","dial_code":"+375","code":"BY"},{"name":"Belgium","dial_code":"+32","code":"BE"},{"name":"Belize","dial_code":"+501","code":"BZ"},{"name":"Benin","dial_code":"+229","code":"BJ"},{"name":"Bermuda","dial_code":"+1441","code":"BM"},{"name":"Bhutan","dial_code":"+975","code":"BT"},{"name":"Bolivia, Plurinational State of","dial_code":"+591","code":"BO"},{"name":"Bosnia and Herzegovina","dial_code":"+387","code":"BA"},{"name":"Botswana","dial_code":"+267","code":"BW"},{"name":"Brazil","dial_code":"+55","code":"BR"},{"name":"British Indian Ocean Territory","dial_code":"+246","code":"IO"},{"name":"Brunei Darussalam","dial_code":"+673","code":"BN"},{"name":"Bulgaria","dial_code":"+359","code":"BG"},{"name":"Burkina Faso","dial_code":"+226","code":"BF"},{"name":"Burundi","dial_code":"+257","code":"BI"},{"name":"Cambodia","dial_code":"+855","code":"KH"},{"name":"Cameroon","dial_code":"+237","code":"CM"},{"name":"Canada","dial_code":"+1","code":"CA"},{"name":"Cape Verde","dial_code":"+238","code":"CV"},{"name":"Cayman Islands","dial_code":"+ 345","code":"KY"},{"name":"Central African Republic","dial_code":"+236","code":"CF"},{"name":"Chad","dial_code":"+235","code":"TD"},{"name":"Chile","dial_code":"+56","code":"CL"},{"name":"China","dial_code":"+86","code":"CN"},{"name":"Christmas Island","dial_code":"+61","code":"CX"},{"name":"Cocos (Keeling) Islands","dial_code":"+61","code":"CC"},{"name":"Colombia","dial_code":"+57","code":"CO"},{"name":"Comoros","dial_code":"+269","code":"KM"},{"name":"Congo","dial_code":"+242","code":"CG"},{"name":"Congo, The Democratic Republic of the Congo","dial_code":"+243","code":"CD"},{"name":"Cook Islands","dial_code":"+682","code":"CK"},{"name":"Costa Rica","dial_code":"+506","code":"CR"},{"name":"Cote d\'Ivoire","dial_code":"+225","code":"CI"},{"name":"Croatia","dial_code":"+385","code":"HR"},{"name":"Cuba","dial_code":"+53","code":"CU"},{"name":"Cyprus","dial_code":"+357","code":"CY"},{"name":"Czech Republic","dial_code":"+420","code":"CZ"},{"name":"Denmark","dial_code":"+45","code":"DK"},{"name":"Djibouti","dial_code":"+253","code":"DJ"},{"name":"Dominica","dial_code":"+1767","code":"DM"},{"name":"Dominican Republic","dial_code":"+1849","code":"DO"},{"name":"Ecuador","dial_code":"+593","code":"EC"},{"name":"Egypt","dial_code":"+20","code":"EG"},{"name":"El Salvador","dial_code":"+503","code":"SV"},{"name":"Equatorial Guinea","dial_code":"+240","code":"GQ"},{"name":"Eritrea","dial_code":"+291","code":"ER"},{"name":"Estonia","dial_code":"+372","code":"EE"},{"name":"Ethiopia","dial_code":"+251","code":"ET"},{"name":"Falkland Islands (Malvinas)","dial_code":"+500","code":"FK"},{"name":"Faroe Islands","dial_code":"+298","code":"FO"},{"name":"Fiji","dial_code":"+679","code":"FJ"},{"name":"Finland","dial_code":"+358","code":"FI"},{"name":"France","dial_code":"+33","code":"FR"},{"name":"French Guiana","dial_code":"+594","code":"GF"},{"name":"French Polynesia","dial_code":"+689","code":"PF"},{"name":"Gabon","dial_code":"+241","code":"GA"},{"name":"Gambia","dial_code":"+220","code":"GM"},{"name":"Georgia","dial_code":"+995","code":"GE"},{"name":"Germany","dial_code":"+49","code":"DE"},{"name":"Ghana","dial_code":"+233","code":"GH"},{"name":"Gibraltar","dial_code":"+350","code":"GI"},{"name":"Greece","dial_code":"+30","code":"GR"},{"name":"Greenland","dial_code":"+299","code":"GL"},{"name":"Grenada","dial_code":"+1473","code":"GD"},{"name":"Guadeloupe","dial_code":"+590","code":"GP"},{"name":"Guam","dial_code":"+1671","code":"GU"},{"name":"Guatemala","dial_code":"+502","code":"GT"},{"name":"Guernsey","dial_code":"+44","code":"GG"},{"name":"Guinea","dial_code":"+224","code":"GN"},{"name":"Guinea-Bissau","dial_code":"+245","code":"GW"},{"name":"Guyana","dial_code":"+595","code":"GY"},{"name":"Haiti","dial_code":"+509","code":"HT"},{"name":"Holy See (Vatican City State)","dial_code":"+379","code":"VA"},{"name":"Honduras","dial_code":"+504","code":"HN"},{"name":"Hong Kong","dial_code":"+852","code":"HK"},{"name":"Hungary","dial_code":"+36","code":"HU"},{"name":"Iceland","dial_code":"+354","code":"IS"},{"name":"India","dial_code":"+91","code":"IN"},{"name":"Indonesia","dial_code":"+62","code":"ID"},{"name":"Iran, Islamic Republic of Persian Gulf","dial_code":"+98","code":"IR"},{"name":"Iraq","dial_code":"+964","code":"IQ"},{"name":"Ireland","dial_code":"+353","code":"IE"},{"name":"Isle of Man","dial_code":"+44","code":"IM"},{"name":"Israel","dial_code":"+972","code":"IL"},{"name":"Italy","dial_code":"+39","code":"IT"},{"name":"Jamaica","dial_code":"+1876","code":"JM"},{"name":"Japan","dial_code":"+81","code":"JP"},{"name":"Jersey","dial_code":"+44","code":"JE"},{"name":"Jordan","dial_code":"+962","code":"JO"},{"name":"Kazakhstan","dial_code":"+77","code":"KZ"},{"name":"Kenya","dial_code":"+254","code":"KE"},{"name":"Kiribati","dial_code":"+686","code":"KI"},{"name":"Korea, Democratic People\'s Republic of Korea","dial_code":"+850","code":"KP"},{"name":"Korea, Republic of South Korea","dial_code":"+82","code":"KR"},{"name":"Kuwait","dial_code":"+965","code":"KW"},{"name":"Kyrgyzstan","dial_code":"+996","code":"KG"},{"name":"Laos","dial_code":"+856","code":"LA"},{"name":"Latvia","dial_code":"+371","code":"LV"},{"name":"Lebanon","dial_code":"+961","code":"LB"},{"name":"Lesotho","dial_code":"+266","code":"LS"},{"name":"Liberia","dial_code":"+231","code":"LR"},{"name":"Libyan Arab Jamahiriya","dial_code":"+218","code":"LY"},{"name":"Liechtenstein","dial_code":"+423","code":"LI"},{"name":"Lithuania","dial_code":"+370","code":"LT"},{"name":"Luxembourg","dial_code":"+352","code":"LU"},{"name":"Macao","dial_code":"+853","code":"MO"},{"name":"Macedonia","dial_code":"+389","code":"MK"},{"name":"Madagascar","dial_code":"+261","code":"MG"},{"name":"Malawi","dial_code":"+265","code":"MW"},{"name":"Malaysia","dial_code":"+60","code":"MY"},{"name":"Maldives","dial_code":"+960","code":"MV"},{"name":"Mali","dial_code":"+223","code":"ML"},{"name":"Malta","dial_code":"+356","code":"MT"},{"name":"Marshall Islands","dial_code":"+692","code":"MH"},{"name":"Martinique","dial_code":"+596","code":"MQ"},{"name":"Mauritania","dial_code":"+222","code":"MR"},{"name":"Mauritius","dial_code":"+230","code":"MU"},{"name":"Mayotte","dial_code":"+262","code":"YT"},{"name":"Mexico","dial_code":"+52","code":"MX"},{"name":"Micronesia, Federated States of Micronesia","dial_code":"+691","code":"FM"},{"name":"Moldova","dial_code":"+373","code":"MD"},{"name":"Monaco","dial_code":"+377","code":"MC"},{"name":"Mongolia","dial_code":"+976","code":"MN"},{"name":"Montenegro","dial_code":"+382","code":"ME"},{"name":"Montserrat","dial_code":"+1664","code":"MS"},{"name":"Morocco","dial_code":"+212","code":"MA"},{"name":"Mozambique","dial_code":"+258","code":"MZ"},{"name":"Myanmar","dial_code":"+95","code":"MM"},{"name":"Namibia","dial_code":"+264","code":"NA"},{"name":"Nauru","dial_code":"+674","code":"NR"},{"name":"Nepal","dial_code":"+977","code":"NP"},{"name":"Netherlands","dial_code":"+31","code":"NL"},{"name":"Netherlands Antilles","dial_code":"+599","code":"AN"},{"name":"New Caledonia","dial_code":"+687","code":"NC"},{"name":"New Zealand","dial_code":"+64","code":"NZ"},{"name":"Nicaragua","dial_code":"+505","code":"NI"},{"name":"Niger","dial_code":"+227","code":"NE"},{"name":"Nigeria","dial_code":"+234","code":"NG"},{"name":"Niue","dial_code":"+683","code":"NU"},{"name":"Norfolk Island","dial_code":"+672","code":"NF"},{"name":"Northern Mariana Islands","dial_code":"+1670","code":"MP"},{"name":"Norway","dial_code":"+47","code":"NO"},{"name":"Oman","dial_code":"+968","code":"OM"},{"name":"Pakistan","dial_code":"+92","code":"PK"},{"name":"Palau","dial_code":"+680","code":"PW"},{"name":"Palestinian Territory, Occupied","dial_code":"+970","code":"PS"},{"name":"Panama","dial_code":"+507","code":"PA"},{"name":"Papua New Guinea","dial_code":"+675","code":"PG"},{"name":"Paraguay","dial_code":"+595","code":"PY"},{"name":"Peru","dial_code":"+51","code":"PE"},{"name":"Philippines","dial_code":"+63","code":"PH"},{"name":"Pitcairn","dial_code":"+872","code":"PN"},{"name":"Poland","dial_code":"+48","code":"PL"},{"name":"Portugal","dial_code":"+351","code":"PT"},{"name":"Puerto Rico","dial_code":"+1939","code":"PR"},{"name":"Qatar","dial_code":"+974","code":"QA"},{"name":"Romania","dial_code":"+40","code":"RO"},{"name":"Russia","dial_code":"+7","code":"RU"},{"name":"Rwanda","dial_code":"+250","code":"RW"},{"name":"Reunion","dial_code":"+262","code":"RE"},{"name":"Saint Barthelemy","dial_code":"+590","code":"BL"},{"name":"Saint Helena, Ascension and Tristan Da Cunha","dial_code":"+290","code":"SH"},{"name":"Saint Kitts and Nevis","dial_code":"+1869","code":"KN"},{"name":"Saint Lucia","dial_code":"+1758","code":"LC"},{"name":"Saint Martin","dial_code":"+590","code":"MF"},{"name":"Saint Pierre and Miquelon","dial_code":"+508","code":"PM"},{"name":"Saint Vincent and the Grenadines","dial_code":"+1784","code":"VC"},{"name":"Samoa","dial_code":"+685","code":"WS"},{"name":"San Marino","dial_code":"+378","code":"SM"},{"name":"Sao Tome and Principe","dial_code":"+239","code":"ST"},{"name":"Saudi Arabia","dial_code":"+966","code":"SA"},{"name":"Senegal","dial_code":"+221","code":"SN"},{"name":"Serbia","dial_code":"+381","code":"RS"},{"name":"Seychelles","dial_code":"+248","code":"SC"},{"name":"Sierra Leone","dial_code":"+232","code":"SL"},{"name":"Singapore","dial_code":"+65","code":"SG"},{"name":"Slovakia","dial_code":"+421","code":"SK"},{"name":"Slovenia","dial_code":"+386","code":"SI"},{"name":"Solomon Islands","dial_code":"+677","code":"SB"},{"name":"Somalia","dial_code":"+252","code":"SO"},{"name":"South Africa","dial_code":"+27","code":"ZA"},{"name":"South Sudan","dial_code":"+211","code":"SS"},{"name":"South Georgia and the South Sandwich Islands","dial_code":"+500","code":"GS"},{"name":"Spain","dial_code":"+34","code":"ES"},{"name":"Sri Lanka","dial_code":"+94","code":"LK"},{"name":"Sudan","dial_code":"+249","code":"SD"},{"name":"Suriname","dial_code":"+597","code":"SR"},{"name":"Svalbard and Jan Mayen","dial_code":"+47","code":"SJ"},{"name":"Swaziland","dial_code":"+268","code":"SZ"},{"name":"Sweden","dial_code":"+46","code":"SE"},{"name":"Switzerland","dial_code":"+41","code":"CH"},{"name":"Syrian Arab Republic","dial_code":"+963","code":"SY"},{"name":"Taiwan","dial_code":"+886","code":"TW"},{"name":"Tajikistan","dial_code":"+992","code":"TJ"},{"name":"Tanzania, United Republic of Tanzania","dial_code":"+255","code":"TZ"},{"name":"Thailand","dial_code":"+66","code":"TH"},{"name":"Timor-Leste","dial_code":"+670","code":"TL"},{"name":"Togo","dial_code":"+228","code":"TG"},{"name":"Tokelau","dial_code":"+690","code":"TK"},{"name":"Tonga","dial_code":"+676","code":"TO"},{"name":"Trinidad and Tobago","dial_code":"+1868","code":"TT"},{"name":"Tunisia","dial_code":"+216","code":"TN"},{"name":"Turkey","dial_code":"+90","code":"TR"},{"name":"Turkmenistan","dial_code":"+993","code":"TM"},{"name":"Turks and Caicos Islands","dial_code":"+1649","code":"TC"},{"name":"Tuvalu","dial_code":"+688","code":"TV"},{"name":"Uganda","dial_code":"+256","code":"UG"},{"name":"Ukraine","dial_code":"+380","code":"UA"},{"name":"United Arab Emirates","dial_code":"+971","code":"AE"},{"name":"United Kingdom","dial_code":"+44","code":"GB"},{"name":"United States","dial_code":"+1","code":"US"},{"name":"Uruguay","dial_code":"+598","code":"UY"},{"name":"Uzbekistan","dial_code":"+998","code":"UZ"},{"name":"Vanuatu","dial_code":"+678","code":"VU"},{"name":"Venezuela, Bolivarian Republic of Venezuela","dial_code":"+58","code":"VE"},{"name":"Vietnam","dial_code":"+84","code":"VN"},{"name":"Virgin Islands, British","dial_code":"+1284","code":"VG"},{"name":"Virgin Islands, U.S.","dial_code":"+1340","code":"VI"},{"name":"Wallis and Futuna","dial_code":"+681","code":"WF"},{"name":"Yemen","dial_code":"+967","code":"YE"},{"name":"Zambia","dial_code":"+260","code":"ZM"},{"name":"Zimbabwe","dial_code":"+263","code":"ZW"}]');

/***/ })

}]);