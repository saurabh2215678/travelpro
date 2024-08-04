import { reactive } from 'vue';

export const store = reactive({
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
	tripType: '',
	priceIds: {},
	filter_showIncv: false,
	filter_showNet: false,
	price_range: {},
	stops: [],
    fare_identifier: {},
    airlines: {},
    filter_fareIdentifier: {},
    airline_faretypes: {},
    filter_published_arr: ['PUBLISHED','FLEXI_PLUS','COUPON'],
    filter_sme_arr: ['SME'],
    filter_corporate_arr: ['CORPORATE','CORPORATE_GOMORE'],
    filter_sale_arr: ['SALE'],
    paxInfo_arr : [
        {
            'key': 'ADULT',
            'title': 'Adult',
            'years_title' : '(12 + yrs)'
        },
        {
            'key': 'CHILD',
            'title': 'Child',
            'years_title' : '(2 + yrs)'
        },
        {
            'key': 'INFANT',
            'title': 'Infant',
            'years_title' : '(0-2 yrs)'
        }
    ],

	loadingName: false,
    userInfo: {},

  });