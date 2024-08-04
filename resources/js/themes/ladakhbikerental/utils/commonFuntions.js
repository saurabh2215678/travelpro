import { store } from '../store';
import moment from 'moment';
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';
const $toast = useToast();

export const validateEmail = (email) => {
    return String(email)
    .toLowerCase()
    .match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
};

export const validatePhone = (phone) => {
    return String(phone)
    .toLowerCase()
    .match(/^[+]*[(]{0,1}[0-9]{1,3}[)]{0,1}[-\s\./0-9]*$/g);
};

export const isEmpty = (obj) => {
    return Object.keys(obj).length === 0;
}

export const showTimeTitle = (time) => {
    var timeTitle = time;
    store.tripTimeArr.forEach((row)=>{
        if(row.key==time) {
            timeTitle = row.title;
        }
    });
    return timeTitle;
}

export const hidePopup = () => {
    store.popupActive = false;
    document.body.classList.remove('popupOpen');
}

export const showPopup = () => {
    store.popupActive = true;
    document.body.classList.add('popupOpen');
}

export const togglePopup = () => {
    store.popupActive = !store.popupActive;
    if(store.popupActive){
        document.body.classList.add('popupOpen');
    } else {
        document.body.classList.remove('popupOpen');
    }
}

//Flight Functions
export const goToStep = (stepId) =>{
    store.bookingCurrentStep = stepId;
}

export const DateFormat = (date,format='D/M/Y') =>{
    return moment(date).format(format);
}

export const DateTimeFormat = (date,format='D/M/Y hh:mm A') =>{
    return moment(date).format(format);
}

export const TimeFormat = (date,format='hh:mm A') =>{
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

/*export const showPrice = (price,hideDecimal) => {
    if(hideDecimal) {
        return '₹'+ Number(price).toLocaleString(undefined,{minimumFractionDigits: 0});
    } else {
        return '₹'+ Number(price).toLocaleString(undefined,{minimumFractionDigits: 2});
    }
}*/

export const showPrice = (price,hideDecimal) => {
    if(isNaN(price)) return price; // If the value is not a number, return it as is
    if (hideDecimal) {
        price = Math.round(price);
    }
    return '$'+ price.toLocaleString('en-US');
}

export const isNumeric = (value) => {
    return Number.isInteger(value);
}

export const showErrorToast = (message,duration=10) => {
    duration = duration * 1000;
    $toast.open({type:'error', message:message, position: 'top', duration:duration});
}

export const showSuccessToast = (message,duration=10) => {
    duration = duration * 1000;
    $toast.open({type:'success', message:message, position: 'top', duration:duration});
}

export const getTotalDuration = (flights) => {
    let totalTime = 0;
    let duration = 0;
    flights.forEach((value, index) => {
        duration = value.duration;
        if(value.cT) {
            duration = duration + value.cT;
        }
        totalTime = totalTime + duration;
    });
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
    if(info && info.tripInfos) {
        info.tripInfos.forEach((tripInfo)=>{
            tripInfo.sI.forEach((flight)=>{
                if(flight.ssrInfo && flight.ssrInfo.BAGGAGE) {
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
    if(info && info.tripInfos) {
        info.tripInfos.forEach((tripInfo)=>{
            tripInfo.sI.forEach((flight)=>{
                if(flight.ssrInfo && flight.ssrInfo.MEAL) {
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
    if(store.routeInfos) {
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
    if(store.websiteSettings && store.websiteSettings.supplier_markup) {
        store.websiteSettings.supplier_markup.forEach((markup, index)=>{
            if(fareIdentifier && (fareIdentifier == 'OFFER_FARE_WITH_PNR' || fareIdentifier == 'OFFER_FARE_WITHOUT_PNR') ) {
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
    if(store.websiteSettings && store.websiteSettings.agent_markup) {
        store.websiteSettings.agent_markup.forEach((markup, index)=>{
            if(fareIdentifier && (fareIdentifier == 'OFFER_FARE_WITH_PNR' || fareIdentifier == 'OFFER_FARE_WITHOUT_PNR') ) {
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
    if(store.websiteSettings && store.websiteSettings.agent_discount) {
        store.websiteSettings.agent_discount.forEach((discount, index)=>{
            if(fareIdentifier && (fareIdentifier == 'OFFER_FARE_WITH_PNR' || fareIdentifier == 'OFFER_FARE_WITHOUT_PNR') ) {
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
    if(info && info.totalPriceList && price_id) {
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
    if(arr.length > 0){
        totalSeatLeft = Math.min.apply(Math, arr);
    }
    return totalSeatLeft;
}

export const getSeatColor = (info) => {
    let arr = [];
    let totalSeatLeft = '';
    info.totalPriceList.forEach((priceList, index)=>{
        arr.push(priceList.fd.ADULT.sR);
    });
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

export function convertToDateObject(dateString) {
  // Split the date string into year, month, and day parts
  const [year, month, day] = dateString.split('-').map(Number);

  // Create a new Date object with the extracted values (months are zero-based in JavaScript)
  const dateObject = new Date(year, month - 1, day);

  return dateObject;
}

export const isOnlineBooking = (moduleName) => {
    var online_booking = false;
    if(store.websiteSettings && store.websiteSettings.onlineBooking) {
        if(moduleName) {
            if(store.websiteSettings.onlineBooking[moduleName]) {
                online_booking = store.websiteSettings.onlineBooking[moduleName];
            }
        } else {
            let obj = store.websiteSettings.onlineBooking;
            Object.keys(obj).forEach(function(key) {
                if(obj[key] == 1) {
                    online_booking = true;
                }
            });
        }
    }
    return online_booking;
}

export const showHotelRatingLabel = (rating) => {
    var ratingLabel = 'Average';
    rating = parseFloat(rating);
    if(rating >= 8) {
        ratingLabel = 'Excellent';
    } else if(rating >= 7) {
        ratingLabel = 'Very Good';
    } else if(rating >= 6) {
        ratingLabel = 'Good';
    }
    return ratingLabel;
}

export function getGreaterDate(mindate, date) {
    // Convert the dates to milliseconds since the Unix Epoch
    const mindateMs = new Date(mindate).getTime();
    const dateMs = new Date(date).getTime();
  
    // Compare the dates and return the greater one
    return (mindateMs > dateMs) ? mindate : date;
  }

export function getTomorrowDate() {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);
    return tomorrow;
}


let headerHeight = 0;

export function setHeaderHeight() {
    const newHeaderHeight = $('header').outerHeight();
    if (headerHeight !== newHeaderHeight) {
        headerHeight = newHeaderHeight;
        document.documentElement.style.setProperty('--header-height', `${headerHeight}px`);
    }
}

export function slideUp(element){
    $(element).slideUp();
}
export function slideDown(element){
    $(element).slideDown();
}
export function slideToggle(element){
    $(element).slideToggle();
}
export function removeHtmlTags(input) {
    return input.replace(/<[^>]*>/g, '');
}