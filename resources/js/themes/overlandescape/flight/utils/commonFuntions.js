import { store } from '../store';
import moment from 'moment';
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
const $toast = useToast();

export const goToStep = (stepId) =>{
    store.bookingCurrentStep = stepId;
}

export const DateFormat = (date,format='dd/mm/YYYY') =>{
    return moment(date).format(format);
}

export const timeConvert = (m) => {
	var num = m;
	var hours = (num / 60);
	var rhours = Math.floor(hours);
	var minutes = (hours - rhours) * 60;
	var rminutes = Math.round(minutes);
    if(rminutes < 10) {
        rminutes = '0'+rminutes;
    }
	return rhours + "h" + " " + rminutes + " m";
}

export const getLogo = (logoname) => {
	return "/assets/AirlinesLogo/" + logoname + ".png";
}

export const showPrice = (price) => {
    return '₹'+ Number(price).toLocaleString(undefined,{minimumFractionDigits: 2});
}

export const isNumeric = (value) => {
    return Number.isInteger(value);
}

export const showErrorToast = (message,duration=10) => {
	duration = duration * 1000;
    $toast.open({type:'error', message:message, position: 'top', duration:duration});
}


export const getTotalDuration = (flights) => {
    let totalTime = 0;
    let duration = 0;
    if(flights && flights.length > 0) {
        flights.forEach((value, index) => {
            duration = value.duration;
            if(value.cT) {
                duration = duration + value.cT;
            }
            totalTime = totalTime + duration;
        });
    }
    return totalTime;
}

export const showCabinClass = (cabinClass) => {
	let cabinClassName = cabinClass;
	if(cabinClass=='PREMIUM_ECONOMY') {
		cabinClassName = 'Premium Economy';
	} else if(cabinClass=='BUSINESS') {
		cabinClassName = 'Business';
	} else if(cabinClass=='FIRST') {
		cabinClassName = 'First';
	} else if(cabinClass=='ECONOMY') {
		cabinClassName = 'Economy';
	}
	return cabinClassName;
}


export const airBaggageDesc = (info, code, field='desc') => {
    let value = code;
    if(info && info.tripInfos && info.tripInfos.length > 0) {
    	info.tripInfos.forEach((tripInfo)=>{
    		tripInfo.sI.forEach((flight)=>{
                if(flight.ssrInfo && flight.ssrInfo.BAGGAGE && flight.ssrInfo.BAGGAGE.length > 0) {
        			flight.ssrInfo.BAGGAGE.forEach((row)=>{
        				if(row.code == code) {
        					// console.log('airBaggageDesc=',row);
        					// desc = 'Excess Baggage - '+row.desc+' @ '+showPrice(row.amount);
        					value = row[field];
        				}
        			});
                }
    		});
    	});
    }
    return value;
}

export const airMealDesc = (info, code, field='desc') => {
    let value = code;
    if(info && info.tripInfos && info.tripInfos.length > 0) {
    	info.tripInfos.forEach((tripInfo)=>{
    		tripInfo.sI.forEach((flight)=>{
                if(flight.ssrInfo && flight.ssrInfo.MEAL && flight.ssrInfo.MEAL.length > 0) {
        			flight.ssrInfo.MEAL.forEach((row)=>{
        				if(row.code == code) {
        					// console.log('airMealDesc=',row);
        					// desc = row.desc+' @ '+showPrice(row.amount);
        					value = row[field];
        				}
        			});
                }
    		});
    	});
    }
    return value;
}
export const isDomestic = (searchQuery) => {
    var isDomestic = true;
    if(searchQuery) {
        if(searchQuery.isDomestic) {
            isDomestic = true;
        } else {
            isDomestic = false;
        }
        return isDomestic;
    }
    if(store.routeInfos && store.routeInfos.length > 0) {
        store.routeInfos.forEach((routeInfo, index)=>{
            if(routeInfo.fromCityOrAirport.country != 'India') {
                isDomestic = false;
            }
            if(routeInfo.toCityOrAirport.country != 'India') {
                isDomestic = false;
            }
        })
    }
    return isDomestic;
}

export const getSupplierMarkupPrice = (BF, searchQuery, getTotal, fareIdentifier) => {
    var markup_type = '';
    var markup_value = 0;
    var markup_price = 0;
    if(store.websiteSettings && store.websiteSettings.supplier_markup && store.websiteSettings.supplier_markup.length > 0) {
        store.websiteSettings.supplier_markup.forEach((markup, index)=>{
            if(fareIdentifier && (fareIdentifier == 'IIAIR_OFFER_FARE_WITH_PNR' || fareIdentifier == 'IIAIR_OFFER_FARE_WITHOUT_PNR') ) {
                if(markup.code == 'domestic' && isDomestic(searchQuery) == true) {
                    markup_type = markup.agent_markup_type;
                    markup_value = markup.agent_markup_value;
                } else if(markup.code == 'international' && isDomestic(searchQuery) == false) {
                    markup_type = markup.agent_markup_type;
                    markup_value = markup.agent_markup_value;
                }
            } else if(fareIdentifier && (fareIdentifier == 'OFFER_FARE_WITH_PNR' || fareIdentifier == 'OFFER_FARE_WITHOUT_PNR') ) {
                if(markup.code == 'domestic' && isDomestic(searchQuery) == true) {
                    markup_type = markup.offer_markup_type;
                    markup_value = markup.offer_markup_value;
                } else if(markup.code == 'international' && isDomestic(searchQuery) == false) {
                    markup_type = markup.offer_markup_type;
                    markup_value = markup.offer_markup_value;
                }
            } else {
                if(markup.code == 'domestic' && isDomestic(searchQuery) == true) {
                    markup_type = markup.online_markup_type;
                    markup_value = markup.online_markup_value;
                } else if(markup.code == 'international' && isDomestic(searchQuery) == false) {
                    markup_type = markup.online_markup_type;
                    markup_value = markup.online_markup_value;
                }
            }
        });
    }
    if(markup_type == 'fixed') {
        if(searchQuery && searchQuery.paxInfo && getTotal==1) {
            var totalPax = searchQuery.paxInfo.ADULT + searchQuery.paxInfo.CHILD + searchQuery.paxInfo.INFANT;
            markup_price = markup_value * totalPax;
        } else {
            markup_price = markup_value;
        }
    } else if(markup_type == 'percent') {
        markup_price = ((BF*markup_value)/100);
    }
    return markup_price;
}

export const getAgentMarkupPrice = (BF, searchQuery, getTotal, fareIdentifier) => {
    var markup_type = '';
    var markup_value = 0;
    var markup_price = 0;
    if(store.websiteSettings && store.websiteSettings.agent_markup && store.websiteSettings.agent_markup.length > 0) {
        store.websiteSettings.agent_markup.forEach((markup, index)=>{
            if(fareIdentifier && (fareIdentifier == 'OFFER_FARE_WITH_PNR' || fareIdentifier == 'OFFER_FARE_WITHOUT_PNR' || fareIdentifier == 'IIAIR_OFFER_FARE_WITH_PNR' || fareIdentifier == 'IIAIR_OFFER_FARE_WITHOUT_PNR') ) {
                if(markup.code == 'domestic' && isDomestic(searchQuery) == true) {
                    markup_type = markup.offer_markup_type;
                    markup_value = markup.offer_markup_value;
                } else if(markup.code == 'international' && isDomestic(searchQuery) == false) {
                    markup_type = markup.offer_markup_type;
                    markup_value = markup.offer_markup_value;
                }
            } else {
                if(markup.code == 'domestic' && isDomestic(searchQuery) == true) {
                    markup_type = markup.online_markup_type;
                    markup_value = markup.online_markup_value;
                } else if(markup.code == 'international' && isDomestic(searchQuery) == false) {
                    markup_type = markup.online_markup_type;
                    markup_value = markup.online_markup_value;
                }
            }
        });
    }
    if(markup_type == 'fixed') {
        if(searchQuery && searchQuery.paxInfo && getTotal==1) {
            var totalPax = searchQuery.paxInfo.ADULT + searchQuery.paxInfo.CHILD + searchQuery.paxInfo.INFANT;
            markup_price = (markup_value * totalPax);
        } else {
            markup_price = markup_value;
        }
    } else if(markup_type == 'percent') {
        markup_price = ((BF*markup_value)/100);
    }
    return markup_price;
}


export const getAgentDiscountPrice = (supplier_markup, searchQuery, getTotal, fareIdentifier) => {
    var discount_type = '';
    var discount_value = 0;
    var discount_price = 0;
    if(store.websiteSettings && store.websiteSettings.agent_discount && store.websiteSettings.agent_discount.length > 0) {
        store.websiteSettings.agent_discount.forEach((discount, index)=>{
            if(fareIdentifier && (fareIdentifier == 'IIAIR_OFFER_FARE_WITH_PNR' || fareIdentifier == 'IIAIR_OFFER_FARE_WITHOUT_PNR') ) {
                if(discount.code == 'domestic' && isDomestic(searchQuery) == true) {
                    discount_type = discount.agent_discount_type;
                    discount_value = discount.agent_discount_value;
                } else if(discount.code == 'international' && isDomestic(searchQuery) == false) {
                    discount_type = discount.agent_discount_type;
                    discount_value = discount.agent_discount_value;
                }
            } else if(fareIdentifier && (fareIdentifier == 'OFFER_FARE_WITH_PNR' || fareIdentifier == 'OFFER_FARE_WITHOUT_PNR') ) {
                if(discount.code == 'domestic' && isDomestic(searchQuery) == true) {
                    discount_type = discount.offer_discount_type;
                    discount_value = discount.offer_discount_value;
                } else if(discount.code == 'international' && isDomestic(searchQuery) == false) {
                    discount_type = discount.offer_discount_type;
                    discount_value = discount.offer_discount_value;
                }
            } else {
                if(discount.code == 'domestic' && isDomestic(searchQuery) == true) {
                    discount_type = discount.online_discount_type;
                    discount_value = discount.online_discount_value;
                } else if(discount.code == 'international' && isDomestic(searchQuery) == false) {
                    discount_type = discount.online_discount_type;
                    discount_value = discount.online_discount_value;
                }
            }
        });
    }
    if(discount_type == 'fixed') {
        if(searchQuery && searchQuery.paxInfo && getTotal==1) {
            var totalPax = searchQuery.paxInfo.ADULT + searchQuery.paxInfo.CHILD + searchQuery.paxInfo.INFANT;
            discount_price = (discount_value * totalPax);
        } else {
            discount_price = discount_value;
        }
    } else if(discount_type == 'percent') {
        discount_price = ((supplier_markup*discount_value)/100);
    }
    return discount_price;
}


export const getInfoTotalPrice = (info, price_id, paxInfo) => {
    let totalPrice = 0;
    let totalAdultPrice = 0;
    let totalChildPrice = 0;
    let totalInfantPrice = 0;
    let fareIdentifier = 0;
    let supplier_markup = 0;
    let agent_markup = 0;
    let agent_discount = 0;
    if(info && info.totalPriceList && info.totalPriceList.length > 0 && price_id) {
        info.totalPriceList.forEach((priceList, index) => {
            if(priceList.id == price_id) {
                fareIdentifier = priceList.fareIdentifier;
                if(paxInfo && priceList.fd) {
                    if(paxInfo.ADULT > 0) {
                        totalAdultPrice = priceList.fd.ADULT.fC.TF * paxInfo.ADULT;
                        supplier_markup = (getSupplierMarkupPrice(priceList.fd.ADULT.fC.BF,null,null,fareIdentifier)*paxInfo.ADULT)
                        agent_markup = (getAgentMarkupPrice(priceList.fd.ADULT.fC.BF,null,null,fareIdentifier)*paxInfo.ADULT);
                        agent_discount = (getAgentDiscountPrice(supplier_markup,null,null,fareIdentifier)*paxInfo.ADULT);
                        totalAdultPrice += (supplier_markup+agent_markup-agent_discount);
                    }
                    if(paxInfo.CHILD > 0) {
                        totalChildPrice = priceList.fd.CHILD.fC.TF * paxInfo.CHILD;
                        supplier_markup = (getSupplierMarkupPrice(priceList.fd.CHILD.fC.BF,null,null,fareIdentifier)*paxInfo.CHILD);
                        agent_markup = (getAgentMarkupPrice(priceList.fd.CHILD.fC.BF,null,null,fareIdentifier)*paxInfo.CHILD);
                        agent_discount = (getAgentDiscountPrice(supplier_markup,null,null,fareIdentifier)*paxInfo.CHILD);
                        totalChildPrice += (supplier_markup+agent_markup-agent_discount);
                    }
                    if(paxInfo.INFANT > 0) {
                        totalInfantPrice = priceList.fd.INFANT.fC.TF * paxInfo.INFANT;
                        supplier_markup = (getSupplierMarkupPrice(priceList.fd.INFANT.fC.BF,null,null,fareIdentifier)*paxInfo.INFANT);
                        agent_markup = (getAgentMarkupPrice(priceList.fd.INFANT.fC.BF,null,null,fareIdentifier)*paxInfo.INFANT);
                        agent_discount = (getAgentDiscountPrice(supplier_markup,null,null,fareIdentifier)*paxInfo.INFANT);
                        totalInfantPrice += (supplier_markup+agent_markup-agent_discount);
                    }
                }
            }
        });
    }
    totalPrice = totalAdultPrice + totalChildPrice + totalInfantPrice;
    return totalPrice;
}

export const getSeatLeft = (info, price_id) => {
    let arr = [];
    let totalSeatLeft = '';
    if(info && info.totalPriceList && info.totalPriceList.length > 0 && price_id) {
        info.totalPriceList.forEach((priceList, index)=>{
            if(priceList.id == price_id) {
                if(priceList.fd.ADULT && priceList.fd.ADULT.sR) {
                    arr.push(priceList.fd.ADULT.sR);    
                }
                if(priceList.fd.CHILD && priceList.fd.CHILD.sR) {
                    arr.push(priceList.fd.CHILD.sR);    
                }
            }
        });
    }
    if(arr.length > 0){
        totalSeatLeft = Math.min.apply(Math, arr);
    }
    return totalSeatLeft;
}

export const getSeatColor = (info) => {
    let arr = [];
    let totalSeatLeft = '';
    if(info && info.totalPriceList && info.totalPriceList.length > 0) {
        info.totalPriceList.forEach((priceList, index)=>{
            arr.push(priceList.fd.ADULT.sR);
        });
    }
    if(arr.length > 0){
        totalSeatLeft = Math.min.apply(Math, arr);
    }
    if(totalSeatLeft < 5 && totalSeatLeft >= 1){
        return 'red';
    }
    if(totalSeatLeft >= 5){
        return 'yellow';
    }
    return 'black';
}

export const showFareTypeColor = (fareIdentifier) => {
    if(store.airline_faretypes && store.airline_faretypes[fareIdentifier]) {
        return store.airline_faretypes[fareIdentifier]['color'];
    }
}

export const showFareTypeUi = (fareIdentifier) => {
    if(store.airline_faretypes && store.airline_faretypes[fareIdentifier]) {
        return store.airline_faretypes[fareIdentifier]['ui'];
    }
}

