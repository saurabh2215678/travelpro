<template>
    <div class="hotel-wrapper-content-tab mb-3">
        <ul class="tabs group border border-slate-20">
            <li><a href="javascript:void(0)" class="active nav" data-id="content_overview">Overview</a></li>

            <li v-if="accommodation.accommodation_features_arr && accommodation.accommodation_features_arr.length > 0"><a href="#content_features">Highlights</a></li>

            <li v-if="accommodation.accommodation_facilities_arr && accommodation.accommodation_facilities_arr.length > 0"><a href="#content_facilities">Facilities</a></li>

            <li v-if="accommodation.accommodationRooms && accommodation.accommodationRooms.length > 0"><a href="#content_rooms">Rooms</a></li>

            <li v-if="accommodation.map_src"><a href="#content_location">Location</a></li>

            <li v-if="accommodation.policies"><a href="#content_policies">Policies</a></li>
        </ul>

        <div id="content">
            <div class="content content_overview">
                <div class="detail_title mb-5 border border-slate-20">
                    <div class="flex py-1 px-5 pt-3">
                        <div class="w-4/5">
                        <div class="text-2xl flex font-bold hotel_name">
                            <h1> {{accommodation.name}}</h1>
                            <ul class="rating_list pl-3 ml-1" :rating="this.accommodation.star_classification"> 
                                <StarRating :rating="this.accommodation.star_classification" read-only :show-rating="false" tooltip="Star ratings are provided by the property to reflect the comfort, facilities, and amenities you can expect."/>
                            </ul>
                        </div>
                        <div class="title hotel_location">
                            <span class="hotel_name2 pl-3 text-sm">{{accommodation.place_name}}</span>
                        </div>
                    </div>
                    <div class="w-1/5">
                        <div class="rating-txt mb-3">
                              <ul class="flex items-center justify-end">
                                 <li class="font-bold leading-5">{{this.showHotelRatingLabel(accommodation.rating)}} <br><span class="text-xs font-normal">{{accommodation.total_reviews}} reviews</span></li>
                                 <li tooltip="Traveller Rating">{{accommodation.rating}}</li>
                              </ul>
                        </div>
                    </div>

                    </div>
                    <div class="hotel-brief" v-if="accommodation.brief">
                        <p class="text-sm" v-html="accommodation.brief"></p>
                    </div>
                </div>

                <div class="content content_73" style="display: block;" v-for="info in accomodation_info">
                    <ul class="description_box detail_info111">
                        <li>
                            <div class="cosec w-2/3 float-left">
                                <div class="heading1">
                                {{info.title}} </div>
                                <div class="text-sm">{{info.brief}}</div>
                            </div>
                            <div class="w-1/3 float-right">
                                <a data-fancybox="Description" rel="group" class="fancybox w-1/3"
                                :href="info.image">
                                <div class="imgs"><img
                                    :src="info.image"
                                    :alt="info.brief" /></div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
</div>

<!-- <div id="content_features" v-if="accommodation.accommodation_features_arr && accommodation.accommodation_features_arr.length > 0">
    <div class="facility-highlights-item">
        <div class="highlights_facility_top mb-3 border border-slate-20 p-3">
            <h3 class="font-semibold pb-3">Highlights</h3>
            <div class="highlights_warapper" :class="{ active: isActive }">
                <div class="arrow_btn cursor-pointer" @click="toggleActive"><i class="fa-solid fa-angle-down"></i></div>
                <ul class="flex flex-wrap highlights_facility">
                    <li v-for="feature in accommodation.accommodation_features_arr">
                        <img :src="feature.src" />
                        <span class="text-gray-600">{{feature.name}}</span>
                    </li>
                </ul>
        </div>
        </div>                              
    </div>
</div> -->
<HotelHighlights :featuresArray="accommodation.accommodation_features_arr"/>

<div id="content_facilities" v-if="accommodation.accommodation_facilities_arr && accommodation.accommodation_facilities_arr.length > 0">
    <div class="highlights_facility_bottom border border-slate-20 p-3">
        <h3 class="font-semibold pb-3">Facilities</h3>
        <ul class="flex">
            <li v-for="facility in accommodation.accommodation_facilities_arr"><svg width="1em" height="1em" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24" class="SvgIconstyled__SvgIconStyled-sc-1i6f60b-0 RBeKP">
                <path
                d="M21.453 4.487l1.094 1.026a.5.5 0 0 1 .023.707L10.412 19.188a1.25 1.25 0 0 1-1.692.122l-.104-.093-7.146-7.146a.5.5 0 0 1 0-.707l1.06-1.061a.5.5 0 0 1 .707 0l6.234 6.234L20.746 4.51a.5.5 0 0 1 .707-.023z">
            </path>
        </svg> {{facility.name}}</li>
    </ul>
</div>
</div>

</div>
</div>
</template>


<script>
import { store } from '../../store.js';
import StarRating from 'vue-star-rating'
import {showPrice, showHotelRatingLabel} from '../../utils/commonFuntions';
import HotelHighlights from './hotelHighlights.vue';

export default {
    created() {

    },
    data() {
        return {
            store
        }
    },
    methods:{
        showPrice,
        showHotelRatingLabel
    },
    components: {
    StarRating,
    HotelHighlights
},
            
    props: ["className","accommodation","accomodation_info"],
}
</script>