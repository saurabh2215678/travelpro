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
                if(flight.ssrInfo.BAGGAGE) {
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
                if(flight.ssrInfo.MEAL) {
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

export const getInfoTotalPrice = (info, price_id) => {
    var totalPrice = 0;
    if(info && info.totalPriceList && price_id) {
        info.totalPriceList.forEach((value, index) => {
            if(value.id == price_id) {
                if(value.fd.ADULT && value.fd.ADULT.fC.TF) {
                    totalPrice = totalPrice + value.fd.ADULT.fC.TF;
                }
                if(value.fd.CHILD && value.fd.CHILD.fC.TF) {
                    totalPrice = totalPrice + value.fd.CHILD.fC.TF;
                }
                if(value.fd.INFANT && value.fd.INFANT.fC.TF) {
                    totalPrice = totalPrice + value.fd.INFANT.fC.TF;
                }
            }
        });
    }
    return totalPrice;
}

export const validateEmail = (email) => {
	return String(email)
	  .toLowerCase()
	  .match(
		/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
	  );
  };

export const validatePhone = (phone) => {
	return String(phone)
		.toLowerCase()
		.match(
		/^[+]*[(]{0,1}[0-9]{1,3}[)]{0,1}[-\s\./0-9]*$/g
		);
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
	}else{
		document.body.classList.remove('popupOpen');
	}
}