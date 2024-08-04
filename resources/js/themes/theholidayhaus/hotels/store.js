import { reactive } from 'vue';

export const store = reactive({
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
    filter_published_arr: ['PUBLISHED','FLEXI_PLUS','COUPON'],
    filter_sme_arr: ['SME'],
    filter_corporate_arr: ['CORPORATE','CORPORATE_GOMORE'],
    filter_sale_arr: ['SALE'],
    paxInfo_arr : [
        {
            'key': 'ADULT',
            'title': 'ADULT',
            'years_title' : '(12 + yrs)'
        },
        {
            'key': 'CHILD',
            'title': 'CHILD',
            'years_title' : '(2 + yrs)'
        },
        {
            'key': 'INFANT',
            'title': 'INFANT',
            'years_title' : '(0-2 yrs)'
        }
    ],

	loadingName: false,
    tripTimeArr : [
        {
            "key": "00:00",
            "title": "12:00 AM"
        },
        {
            "key": "00:15",
            "title": "12:15 AM"
        },
        {
            "key": "00:30",
            "title": "12:30 AM"
        },
        {
            "key": "00:45",
            "title": "12:45 AM"
        },
        {
            "key": "01:00",
            "title": "01:00 AM"
        },
        {
            "key": "01:15",
            "title": "01:15 AM"
        },
        {
            "key": "01:30",
            "title": "01:30 AM"
        },
        {
            "key": "01:45",
            "title": "01:45 AM"
        },
        {
            "key": "02:00",
            "title": "02:00 AM"
        },
        {
            "key": "02:15",
            "title": "02:15 AM"
        },
        {
            "key": "02:30",
            "title": "02:30 AM"
        },
        {
            "key": "02:45",
            "title": "02:45 AM"
        },
        {
            "key": "03:00",
            "title": "03:00 AM"
        },
        {
            "key": "03:15",
            "title": "03:15 AM"
        },
        {
            "key": "03:30",
            "title": "03:30 AM"
        },
        {
            "key": "03:45",
            "title": "03:45 AM"
        },
        {
            "key": "04:00",
            "title": "04:00 AM"
        },
        {
            "key": "04:15",
            "title": "04:15 AM"
        },
        {
            "key": "04:30",
            "title": "04:30 AM"
        },
        {
            "key": "04:45",
            "title": "04:45 AM"
        },
        {
            "key": "05:00",
            "title": "05:00 AM"
        },
        {
            "key": "05:15",
            "title": "05:15 AM"
        },
        {
            "key": "05:30",
            "title": "05:30 AM"
        },
        {
            "key": "05:45",
            "title": "05:45 AM"
        },
        {
            "key": "06:00",
            "title": "06:00 AM"
        },
        {
            "key": "06:15",
            "title": "06:15 AM"
        },
        {
            "key": "06:30",
            "title": "06:30 AM"
        },
        {
            "key": "06:45",
            "title": "06:45 AM"
        },
        {
            "key": "07:00",
            "title": "07:00 AM"
        },
        {
            "key": "07:15",
            "title": "07:15 AM"
        },
        {
            "key": "07:30",
            "title": "07:30 AM"
        },
        {
            "key": "07:45",
            "title": "07:45 AM"
        },
        {
            "key": "08:00",
            "title": "08:00 AM"
        },
        {
            "key": "08:15",
            "title": "08:15 AM"
        },
        {
            "key": "08:30",
            "title": "08:30 AM"
        },
        {
            "key": "08:45",
            "title": "08:45 AM"
        },
        {
            "key": "09:00",
            "title": "09:00 AM"
        },
        {
            "key": "09:15",
            "title": "09:15 AM"
        },
        {
            "key": "09:30",
            "title": "09:30 AM"
        },
        {
            "key": "09:45",
            "title": "09:45 AM"
        },
        {
            "key": "10:00",
            "title": "10:00 AM"
        },
        {
            "key": "10:15",
            "title": "10:15 AM"
        },
        {
            "key": "10:30",
            "title": "10:30 AM"
        },
        {
            "key": "10:45",
            "title": "10:45 AM"
        },
        {
            "key": "11:00",
            "title": "11:00 AM"
        },
        {
            "key": "11:15",
            "title": "11:15 AM"
        },
        {
            "key": "11:30",
            "title": "11:30 AM"
        },
        {
            "key": "11:45",
            "title": "11:45 AM"
        },
        {
            "key": "12:00",
            "title": "12:00 PM"
        },
        {
            "key": "12:15",
            "title": "12:15 PM"
        },
        {
            "key": "12:30",
            "title": "12:30 PM"
        },
        {
            "key": "12:45",
            "title": "12:45 PM"
        },
        {
            "key": "13:00",
            "title": "01:00 PM"
        },
        {
            "key": "13:15",
            "title": "01:15 PM"
        },
        {
            "key": "13:30",
            "title": "01:30 PM"
        },
        {
            "key": "13:45",
            "title": "01:45 PM"
        },
        {
            "key": "14:00",
            "title": "02:00 PM"
        },
        {
            "key": "14:15",
            "title": "02:15 PM"
        },
        {
            "key": "14:30",
            "title": "02:30 PM"
        },
        {
            "key": "14:45",
            "title": "02:45 PM"
        },
        {
            "key": "15:00",
            "title": "03:00 PM"
        },
        {
            "key": "15:15",
            "title": "03:15 PM"
        },
        {
            "key": "15:30",
            "title": "03:30 PM"
        },
        {
            "key": "15:45",
            "title": "03:45 PM"
        },
        {
            "key": "16:00",
            "title": "04:00 PM"
        },
        {
            "key": "16:15",
            "title": "04:15 PM"
        },
        {
            "key": "16:30",
            "title": "04:30 PM"
        },
        {
            "key": "16:45",
            "title": "04:45 PM"
        },
        {
            "key": "17:00",
            "title": "05:00 PM"
        },
        {
            "key": "17:15",
            "title": "05:15 PM"
        },
        {
            "key": "17:30",
            "title": "05:30 PM"
        },
        {
            "key": "17:45",
            "title": "05:45 PM"
        },
        {
            "key": "18:00",
            "title": "06:00 PM"
        },
        {
            "key": "18:15",
            "title": "06:15 PM"
        },
        {
            "key": "18:30",
            "title": "06:30 PM"
        },
        {
            "key": "18:45",
            "title": "06:45 PM"
        },
        {
            "key": "19:00",
            "title": "07:00 PM"
        },
        {
            "key": "19:15",
            "title": "07:15 PM"
        },
        {
            "key": "19:30",
            "title": "07:30 PM"
        },
        {
            "key": "19:45",
            "title": "07:45 PM"
        },
        {
            "key": "20:00",
            "title": "08:00 PM"
        },
        {
            "key": "20:15",
            "title": "08:15 PM"
        },
        {
            "key": "20:30",
            "title": "08:30 PM"
        },
        {
            "key": "20:45",
            "title": "08:45 PM"
        },
        {
            "key": "21:00",
            "title": "09:00 PM"
        },
        {
            "key": "21:15",
            "title": "09:15 PM"
        },
        {
            "key": "21:30",
            "title": "09:30 PM"
        },
        {
            "key": "21:45",
            "title": "09:45 PM"
        },
        {
            "key": "22:00",
            "title": "10:00 PM"
        },
        {
            "key": "22:15",
            "title": "10:15 PM"
        },
        {
            "key": "22:30",
            "title": "10:30 PM"
        },
        {
            "key": "22:45",
            "title": "10:45 PM"
        },
        {
            "key": "23:00",
            "title": "11:00 PM"
        },
        {
            "key": "23:15",
            "title": "11:15 PM"
        },
        {
            "key": "23:30",
            "title": "11:30 PM"
        },
        {
            "key": "23:45",
            "title": "11:45 PM"
        },       

    ],

    popupActive:false,
    popupContent:'<p>hello world</p>',
    popUpClass: '',
    popupType: '' // use `innerHtml` to insert content from dynamic content

  });