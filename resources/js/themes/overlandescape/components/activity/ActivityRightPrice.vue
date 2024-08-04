<template>
    <div class="right_price_box">
        <form id="bookNowForm">
            <div class="par_cost pb-2 text-sm" v-if="parseInt(this.final_pay_price)">From <span>{{showPrice(this.startingPrice,true)}}</span> per person</div>
            <div class="scac flex items-center">
                <ul class="form_list w-full float-left">
                    <li class="w-2/4">
                        <div class="package_type" v-if="package?.packagePrices && package?.packagePrices?.length > 0">
                            <label class="text-sm">Package Type</label>
                            <div class="custom_select">
                                <select class="form_control" name="package_type" @change="ajaxPriceDetails($event)" >
                                    <option v-for="price in package.packagePrices" :key="price.id" :value="price.id" :selected="price.id == this.price_id" >{{price.title}}</option>
                                </select>
                            </div>
                        </div>
                    </li>                
                </ul>
            </div>
            <div class="single_package_day">
                <ul class="form_list w-full float-left mt-2 mb-3">
                    <li class="w-2/4" v-if="this.package_tour_type != 'open' && this.isOnlineBooking('activity_listing') && store?.myEvents?.length > 0">
                        <div class="form_group icon_calender inline-block">
                            <label class="text-sm">Trip Date</label>
                            <div class="departure_form">
                                <input @click="showCalenderPopup" type="text" name="trip_date" id="trip_date" :value="this.trip_date" class="form_control" autocomplete="off" data-popup-btn="departure-date" />
                                <input type="hidden" name="trip_slot" id="trip_slot" value="">
                                <label id="trip_slot_title" class="text-xs"></label>
                                <span id="trip_date_error" class="validation_error"></span>
                            </div>
                        </div>
                    </li>
                    <li class="w-2/4" v-if="package?.packagePrices && package?.packagePrices?.length > 0">
                        <label class="text-sm font-bold">Number of Persons</label>
                        <fieldset class="scheduler-border booking_fields">
                            <legend class="select_trav font-normal" @click="toggleTraveller">Select Travellers</legend>
                            <span id="travellers_error" class="validation_error"></span>
                            <div class="single_package_white" :class="selectTraveller ? 'active' : ''">
                                <div class="single_btn">
                                    <ul class="flex flex-wrap">
                                        <template v-for="element in this.priceCategoryElements">
                                            <li class="traveller_pricing_inner" v-if="this.checkElementAvailable(element)" :key="element.id">
                                                <div class="form_group">
                                                    <label class="text-xs">{{element.input_label}}</label>
                                                    <div class="custom_select" v-if="element.input_type == 'select'">
                                                        <select class="form_control element_choice" :data-element-id="element.id" :name="`pce${element.id}`" @change="handleTraveller" >
                                                            <option :value="val" v-for="val in this.decodeValue(element.input_choices)" :selected="this.checkElementItemSelected(element,val)" >{{val}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form_group" v-else>
                                                        <input type="text" class="form_control element_choice custominput" :data-element-id="element.id" :name="`pce${element.id}`" />
                                                    </div>
                                                    <div style="color:#f0562f" :class="`price_select${element.id}`">0</div>
                                                </div>
                                            </li>
                                        </template>
                                        <li class="li_last"><label class="text-xs"> </label><a class="apbtn" href="#" id="applyTravellers" @click="this.handleApplyTravellers" >Apply</a></li>
                                    </ul>
                                </div>
                            </div>
                        </fieldset>
                    </li>
                </ul>
            </div>

            <div class="slot_time" v-if="package?.packagePrices && package?.packagePrices?.length > 0 && store?.myEvents?.length > 0">
                <a class="apply_slot" href="#" id="applyTripTravellers" @click="checkSlotAvailability">Check Availability</a>
                <div class="time_list mb-2">
                    <label class="pb-0">Please select the slot</label>
                    <ul id="cont"></ul>
                </div>
            </div>

            <div class="booknow_btn mt-3">
                <div class="price_box" v-if="parseInt(this.final_pay_price) > 0">
                    <p class="text-lg font-semibold">Total Price:</p>
                    <span :class="`heading_sm3 ${this.old_price_class}`" id="final_pay_price">{{showPrice(this.final_pay_price,true)}}</span>
                    <span class="heading_sm3" id="agent_price" v-if="parseInt(this.agent_price) > 0">{{showPrice(this.agent_price,true)}}</span>
                </div>
                <div class="price_box">
                    <input type="hidden" name="booking_price" id="booking_price" :value="this.package_booking_price">
                    <p class="text-lg font-semibold" v-if="parseInt(this.booking_price) > 0">Booking Price:</p>
                    <span class="heading_sm3" v-if="parseInt(this.booking_price) > 0">{{showPrice(this.booking_price,true)}}</span>
                </div>
                <ul class="btn_group listing_btn">
                    <li class="req_price" v-if="parseInt(this.final_pay_price) <= 0">Price On Request</li>
                    <li>
                        <input type="hidden" name="action" value="package_booking">
                        <input type="hidden" name="package" :value="package.id">
                        <input type="button" class="btn w-full" name="submit" value="Book Online" @click="bookNow" v-if="this.isOnlineBooking('activity_listing') && store?.myEvents?.length > 0">
                    </li>
                    <li class="text-center">
                        <Link :href="`${package.enquiry_url}&type=${typename}`" class="btn2">Enquire Now</Link>
                    </li>

                    <li class="text-center">
                    </li>
                </ul>
            </div>
            <div class="note_text text-sm mb-3 pt-2" v-if="package?.packagePrices && package?.packagePrices?.length > 0"><strong>Note* : </strong> More than 6
            persons please contact.</div>

            <template v-if="store.popupType != 'innerHtml' && this.calenderPop">
                <Popup className="fullCalander_pop_wrap">
                    <div class="modal-content">
                        <div class="modal-header">

                            <span class="heading">Select the Date of Travel</span>
                            <span class="sub_title_pop">The price in calendar represent the starting price
                            per person.</span>
                        </div>
                        <div class="modal-body">
                            <div id="full_calender">
                                <FullCalendar :options="this.calendarOptions" />
                            </div>
                        </div>
                    </div>
                </Popup>
            </template>
        </form>
    </div>
</template>
<script>
import SearchForm from '../../components/SearchForm.vue';
import Layout from '../../components/layout.vue';
import { store } from '../../store';
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import Popup from '../../components/popup.vue';
import {showPopup, showPrice, isOnlineBooking} from '../../utils/commonFuntions';
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

export default {
    name: "ActivityRightPrice",
    created() {
        document.body.classList.add('package-detail-page');
        // console.log('package=',this.package);
        if(this.package) {
            this.priceCategoryElements = this.package.priceCategoryElements;
            /*if(this.package.packageDefaultPrice) {
                var price_id = this.package.packageDefaultPrice.id;
                this.loadPriceData(price_id);
                this.loadAccommodationData(price_id);
                this.price_id = price_id;
            }*/
            if(this.defaultPriceId) {
                var price_id = this.defaultPriceId;
                this.price_id = price_id;
                this.loadPriceData(price_id);
                this.loadAccommodationData(price_id);
            }

            var packagePrices = this.package.packagePrices;
            var priceInfoListing = {};
            if(packagePrices) {
                $.each(packagePrices, function(index,pp){
                    priceInfoListing[pp.id] = {
                        "id": pp.id,
                        "package_id": pp.package_id,
                        "title": pp.title,
                        "discount_type": pp.discount_type,
                        "discount": pp.discount,
                        "booking_price": pp.booking_price,
                        "final_amount": pp.final_amount,
                        "category_choices_record": JSON.parse(pp.category_choices_record,true),
                        "show_choices_record": JSON.parse(pp.show_choices_record,true),
                        "is_default": pp.is_default
                    }
                });
            }
            this.priceInfoListing = priceInfoListing;
            this.package_tour_type = this.package.package_tour_type;
        }
    },
    beforeUnmount() {
        document.body.classList.remove('package-detail-page');
    },
    data() {
        return {
            test : "test",
            store,
            package_booking_price:0,
            package_total_price:0,
            packageSelectedPrice: [],
            typename: '',
            package: this.package,
            travellerObj : {},
            price_id : '',
            trip_date : '',
            priceInfoListing : {},
            priceCategoryElements : [],
            package_tour_type : '',
            selectPersonPop : false,
            selectTraveller : false,
            calendarOptions: {
                plugins: [ dayGridPlugin, interactionPlugin ],
                initialView: 'dayGridMonth'
            },
            discountData: {
            },
            old_price_class:'',
            final_pay_price:0,
            agent_price:0,
            booking_price:0
        }
    },
    methods: {
        showPrice,
        isOnlineBooking,        
        toggleTraveller(){
            this.selectTraveller = !this.selectTraveller;
        },
        
        handleTraveller(e){
            var travellername = e.target.name;
            var travellervalue = e.target.value;
            var travellerprice = e.target.getAttribute('price');
            this.travellerObj[travellername] = {'value' : travellervalue, 'price' : travellerprice };
        // console.log(this.travellerObj);
        this.packagePriceCalculation();
        },
        handleApplyTravellers(e){
            e.preventDefault();
            e.stopPropagation();
            this.selectPersonPop = false;
            this.selectTraveller = !this.selectTraveller;
            this.packagePriceCalculation();
        },
        showToast(toastObj){
            $toast.open(toastObj);
        },
        decodeValue(value) {
        // console.log('decodeValue=',value);
        return JSON.parse(value);
        },
        ajaxPriceDetails(event) {

            var currentObj = this;
            var price_id = event.target.value
            this.loadPriceData(price_id);
            this.loadAccommodationData(price_id);
        },
        loadPriceData(price_id) {
            var currentObj = this;
            currentObj.price_id = price_id;
            currentObj.processing = true;
            currentObj.errors = {};
            axios.post('/package/'+this.package.id+'/ajaxPriceDetails', {
                price_id: price_id, search_data: store.searchData,
            })  
            .then(function (resp) {
                    let response = resp.data;
                    store.myEvents = response.myEvents;
                    if(response && response.success) {

                        if(response.packageSelectedPrice) {
                            let packageSelectedPrice = response.packageSelectedPrice;
                            currentObj.packageSelectedPrice = packageSelectedPrice;
                            currentObj.typename = packageSelectedPrice.title;
                            currentObj.package_total_price = packageSelectedPrice.final_amount;
                            currentObj.package_booking_price = packageSelectedPrice.booking_price;
                            currentObj.startingPrice = response.startingPrice;
                            currentObj.trip_date = response.trip_date;
                            if(response.trip_date) {
                                setTimeout(function(){
                                    currentObj.checkSlotAvailability();
                                },300);
                            }
                        }
                        if(response.discount_data) {
                            currentObj.discountData = response.discount_data;
                        }
                        setTimeout(function(){
                            currentObj.packagePriceCalculation();
                        },300);

                        let calendarOptions = {
                            plugins: [dayGridPlugin, interactionPlugin],
                            initialView: 'dayGridMonth',
                            header: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth'
                            },
                            defaultDate: response.defaultDate,
                            firstDay: 1,
                            selectable: true,
                            selectMirror: true,
                            validRange: {
                                start: response.startDate
                            },
                            eventClick: function(info) {
            // console.log('eventClick.info=',info);
            var title = info.event.title;
            if(title!='') {
                var st = info.event.start;
                var slot_key = '';
                var slot_title = '';
                if(info.event.extendedProps) {
                    if(info.event.extendedProps.slot_key) {
                        slot_key = info.event.extendedProps.slot_key;
                        slot_title = info.event.extendedProps.slot_title;
                    }
                }
                var rams = st.toString();
                var dasy = rams.split(" ");
                var mnths = {Jan:"01", Feb:"02", Mar:"03", Apr:"04", May:"05", Jun:"06",Jul:"07", Aug:"08", Sep:"09", Oct:"10", Nov:"11", Dec:"12"};

                var my_month = mnths[dasy[1]];
                var my_day   = dasy[2];
                var my_year  = dasy[3];
                var start_date= my_day+'/'+my_month+'/'+my_year;
                // document.getElementById('trip_date').value = start_date;
                currentObj.trip_date = start_date;
                document.getElementById('trip_slot').value = slot_key;
                if(slot_title) {
                    document.getElementById('trip_slot_title').innerHTML = 'Trip Slot: '+slot_title;
                } else {
                    document.getElementById('trip_slot_title').innerHTML = '';
                }
                // calendar.unselect();
                document.getElementById("trip_date_error").innerHTML="";
                // var button = document.getElementById("hmodel-close");
                // button.click();
                $('[data-popup-modal]').removeClass('active');
                $('.backdrop').removeClass('active');
                currentObj.hideCalenderPopup();
                // currentObj.calenderPop = false;
            }
            },
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: response.myEvents
            };
            currentObj.calendarOptions = calendarOptions;
                    } else {
                        alert('Something went wrong, please try again.');
                    }
                    currentObj.processing = false;
                    // console.log('running in then');
                });
        },
        loadAccommodationData(price_id){
            var currentObj = this;
            currentObj.processing = true;
            currentObj.errors = {};
            axios.post('/package/'+this.package.id+'/ajaxPackagePriceAccommodations', {
                price_id: price_id,
            })
            .then(function (response) {  
                if(response.data.success) {
                // alert(response.data);
                var accommodations_days = response.data.accommodations_days;
                store.accommodations_days = accommodations_days;

                } else {
                    alert('Something went wrong, please try again.');
                }
                currentObj.processing = false;
                // console.log('running in then');
            });
        },
        bookNow() {
            var currentObj = this;
            currentObj.processing = true;
            currentObj.errors = {};
            axios.post('/book_now', new FormData($('#bookNowForm')[0]))
            .then(function (response) {
                currentObj.processing = false;
                if(response.data.success) {
                    // window.location.href = response.data.redirect_url;
                    currentObj.$inertia.get(response.data.redirect_url);
                } else {
                    alert('Something went wrong, please try again.');
                }
            }).catch(function (e) {
                var response = e.response.data;
                if(response) {
                    currentObj.parseBookingErrorMessages(response, 'myForm', 'Book Online');
                }
            });
        },
        parseBookingErrorMessages(response, formID, boxText) {
            if(response.errors) {
                var errors = response.errors;
                var message='';
                $.each(errors, function (key, val) {
                    $('#'+formID).find("#" + key + "_error").text(val[0]);
                });
            }
        },
        checkElementAvailable(element) {
            // console.log('element=',element);
            var available = false;
            if(this.packageSelectedPrice && this.packageSelectedPrice.show_choices_record && element) {
                var show_choices_record = JSON.parse(this.packageSelectedPrice.show_choices_record);
            // alert('pce'+element.id+'_null');
            // console.log('show_choices_record=',show_choices_record);
            var name = '';
            if(show_choices_record['pce'+element.id] && show_choices_record['pce'+element.id]['default']) {
                name = show_choices_record['pce'+element.id]['default'];
            }
            if(name) {
                available = true;
                if(name == 'pce'+element.id+'_null') {
                    available = false;
                }
            }
            // $show_total_price = true;
            }
            return available;
        },
        checkElementItemSelected(element,val) {
            // console.log('element=',element);
            var selected = false;
            if(this.packageSelectedPrice && this.packageSelectedPrice.show_choices_record && element) {
                var show_choices_record = JSON.parse(this.packageSelectedPrice.show_choices_record);
            // alert('pce'+element.id+'_null');
            // console.log('show_choices_record=',show_choices_record);
                var name = '';
                if(show_choices_record['pce'+element.id] && show_choices_record['pce'+element.id]['default']) {
                    name = show_choices_record['pce'+element.id]['default'];
                }
                // alert(name+'=='+val);
                if(name && parseInt(name.replace('pce'+element.id+'_',''))==parseInt(val)) {
                    selected = true;
                }
            }
            return selected;
        },
        packagePriceCalculation() {
            let currentObj = this;
            var priceInfoListing = this.priceInfoListing;
            var selectedService = $('.right_side').find("select[name=package_type]").val();
            // console.log('priceInfoListing=',priceInfoListing[selectedService]);
            if(typeof priceInfoListing[selectedService] !== 'undefined'){
        var selected_choice_record = priceInfoListing[selectedService].category_choices_record;
        var selected_default_record = priceInfoListing[selectedService].show_choices_record;
        var selected_discount_type = priceInfoListing[selectedService].discount_type;
        var selected_discount = priceInfoListing[selectedService].discount;


        var total_pax = 0;
        var total_final_val = 0;
        var total_original_val = 0;
        $('.right_side').find(".element_choice").each(function( index ) {
            var __this = $(this);
            var elementId = __this.attr('data-element-id');
            var elementName = __this.attr('name');
            var slVal = 0;
            var selected_price_value = 0;
            var original_price_val = 0;
            if($('.right_side').find('select[name="'+elementName+'"]').length) {
                slVal = $('.right_side').find('select[name="'+elementName+'"]').val();
                if(slVal && selected_choice_record[elementName]) {
                    selected_price_value = selected_choice_record[elementName][slVal];
                } else {
                    selected_price_value = 0;
                }
            }else{
                slVal = $('.right_side').find('input[name="'+elementName+'"]').val();
                selected_price_value = 0;
            }
            if(parseInt(slVal) > 0) {
                total_pax = total_pax + parseInt(slVal);
            }

            if (typeof selected_default_record[elementName] !== 'undefined') {
                var selected_price_rr = selected_default_record[elementName]['default'];
            } else {
                var selected_price_rr = '';
            }
            var is_calculate=1;
            if(selected_price_rr==elementName+'_null'){
                __this.closest('.selectvalue').hide();
                is_calculate=0;
            }else{
                __this.closest('.selectvalue').show();
                is_calculate=1;
            }

            if(is_calculate==1) {
                var selected_single_price_value = selected_price_value;
                selected_price_value = original_price_val = selected_price_value * slVal;
                if(selected_discount_type == 2) {
                    $('.right_side').find('#discount_type').val('percent');
                    $('.right_side').find('#discount').val(selected_discount);
                    selected_price_value = parseInt(selected_price_value) - ((parseInt(selected_price_value) * parseInt(selected_discount))/100);
                }
                total_original_val = parseInt(total_original_val) + parseInt(original_price_val);
                total_final_val = parseInt(total_final_val) + parseInt(selected_price_value);

                if(parseInt(selected_single_price_value) > 0){
                    $('.right_side').find('.price_select'+elementId).html(currentObj.showPrice(selected_single_price_value,true));
                } else {
                    $('.right_side').find('.price_select'+elementId).html('');
                }
                if(original_price_val != selected_price_value){
                    $('.right_side').find('.price_select'+elementId).html('<s>'+currentObj.showPrice(original_price_val,true)+'</s> '+currentObj.showPrice(selected_price_value,true));
                }
            }
        });

        currentObj.final_pay_price = total_final_val;
        // $('.right_side').find('#final_pay_price').html(showPrice(total_final_val));

        var agent_price = 0;
                    var discount_amount = 0;
                    if(this.discountData && this.discountData.discount_type && this.discountData.discount_value && total_pax > 0) {
                        var discount_type = this.discountData.discount_type;
                        var discount_value = this.discountData.discount_value;
                        if(discount_type == 'percentage'){
                            discount_amount = ((parseInt(total_final_val) * parseInt(discount_value))/100);
                        } else if(discount_type == 'flat') {
                            discount_amount = discount_value*total_pax;
                        }
                    }

                    if(parseInt(discount_amount) > 0) {
                        agent_price = parseInt(total_final_val) - parseInt(discount_amount);
                        currentObj.agent_price = agent_price;
                        // $('.right_side').find('#agent_price').html(showPrice(agent_price));
                        let old_price_class = '';
                        if(parseInt(agent_price) > 0) {
                            old_price_class = 'old_price';
                        }
                        currentObj.old_price_class = old_price_class;
                    } else {
                        currentObj.agent_price = 0;
                        currentObj.old_price_class = '';
                    }

                    let total_booking_price = 0;
                    if(total_pax > 0) {
                        var booking_price = $('.right_side').find('#booking_price').val();
                        if(parseInt(booking_price) > 0) {
                            total_booking_price = total_pax * parseInt(booking_price);
                        }
                    }
                    currentObj.booking_price = total_booking_price;
                    // $('.right_side').find('#booking_price').parent().find('.heading_sm3').html(showPrice(total_booking_price));
            }
                this.travellerSelection();
                this.resetTripSlot();
        },
        travellerSelection() {
            var total_pax = 0;
            $('.right_side').find(".element_choice").each(function( index ) {
                var __this = $(this);
                var elementName = __this.attr('name');
                var slVal = 0;
                if($('.right_side').find('select[name="'+elementName+'"]').length){
                    slVal = $('.right_side').find('select[name="'+elementName+'"]').val();
                }else{
                    slVal = $('.right_side').find('input[name="'+elementName+'"]').val();
                }
                if(parseInt(slVal) > 0) {
                    total_pax = total_pax + parseInt(slVal);
                }
            });
            var select_trav_html = '';
            if(total_pax > 0) {
                select_trav_html = total_pax+' Travellers';
            } else {
                select_trav_html = 'Select Travellers';
            }
            $('.right_side').find(".select_trav").html(select_trav_html);
        },
        resetTripSlot() {
            $('.right_side .time_list').removeClass('active');
            document.getElementById('trip_slot').value = '';
            $('.right_side input[name=submit]').attr('disabled',true);
        },
        checkSlotAvailability(e) {
            if(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            $('.right_side #trip_date_error').html('');
            $('.right_side #travellers_error').html('');
            var z = true;
            var total_pax = 0;
            $('.right_side').find(".element_choice").each(function( index ) {
                var __this = $(this);
                var elementName = __this.attr('name');
                var slVal = 0;
                if($('.right_side').find('select[name="'+elementName+'"]').length){
                    slVal = $('.right_side').find('select[name="'+elementName+'"]').val();
                }else{
                    slVal = $('.right_side').find('input[name="'+elementName+'"]').val();
                }
                if(parseInt(slVal) > 0) {
                    total_pax = total_pax + parseInt(slVal);
                }
            });
            if(total_pax == 0) {
                z=false;
                $('.right_side #travellers_error').html('Travellers is required !');
            }

            var trip_date = $(".right_side #trip_date").val().trim();
            if(trip_date=="") {
                $('.right_side #trip_date_error').html('Date is required !');
                z=false;
            }
            if(trip_date!="") {
                if(this.isValidDate(trip_date)) {
                    var z = true;
                } else {
                    $('.right_side #trip_date_error').html('Date format is invalid !');
                    z=false;
                }
            }
            var price_id = $("select[name=package_type] :selected").val();
            if(!price_id) {
                alert('Please select package type');
                z=false;
            }

            if(!z) {
                return false;
            }

            axios.post('/package/'+this.package.id+'/ajaxPriceSlots', {
                price_id: price_id,
                trip_date: trip_date,
                total_pax: total_pax,
            })  
            .then(function (resp) {
                let response = resp.data 
                //console.log(response); 
                if(response.success) {
                    $('.right_side #cont').empty();
                    if(response.success){
                        $('.right_side .time_list').addClass('active');
                        if(response.results && response.results.length > 0) {
                            $.each(response.results,function(index,row){
                                $('.right_side #cont').append('<li data-id="'+row.key+'">'+row.title+'</li>');
                            });
                            if(response.results.length == 1) {
                                setTimeout(function(){
                                    $('.right_side #cont li').trigger('click');
                                },300);
                            }
                        } else {
                            $('.right_side #cont').append('<li data-id="">No slots available!</li>');
                        }
                    }

                } else {
                    if(response.message) {
                        alert(response.message);
                    } else {
                        alert('Something went wrong, please try again.');
                    }
                }
            });
            return false;
        },
        isValidDate(date) {
            var temp = date.split('/');
            var d = new Date(temp[1] + '/' + temp[0] + '/' + temp[2]);
            return (d && (d.getMonth() + 1) == temp[1] && d.getDate() == Number(temp[0]) && d.getFullYear() == Number(temp[2]));
        }

    },
    mounted () {
    },
    beforeDestroy () {
    },
    watch:{
    },
    components: {
        SearchForm,
        FullCalendar,
        Popup,
        Link
    },
    layout: Layout,
    props: ["package","defaultPriceId","faqs","destinations","itenaries", "calenderPop", "showCalenderPopup", "hideCalenderPopup"],
};

$(document).on('click','.right_side #cont li',function(e){
    $('.right_side #cont li').removeClass('active');
    var slot_key = $(this).attr('data-id');
    if(slot_key) {
        $(this).addClass('active');
        document.getElementById('trip_slot').value = slot_key;
        $('.right_side input[name=submit]').attr('disabled',false);
    } else {
        document.getElementById('trip_slot').value = '';
        $('.right_side input[name=submit]').attr('disabled',true);
    }
});
</script>