<template>
    <HomeHotelSliderWrapper class="home_hotel" v-if="this.hotelList?.data && this.hotelList.data?.length > 0">
        <div class="container">
            <HotelSliderBox :hotelList="hotelList.data" :title="title" :viewAllUrl="this.viewAllUrl" />
        </div>
    </HomeHotelSliderWrapper>
</template>


<script>
import { store } from '../../store.js';
import axios from "axios";
import { HomeHotelSliderWrapper } from '../../styles/HomeHotelSliderWrapper';
import HotelSliderBox from './HotelSliderBox.vue';

export default {
    name:'HomeHotelSlider',
    created() {
        this.loadHotels();
    },
    data() {
        return {
            store,
            hotelList:{
                "data": [],
                "links": "",
            },
            total_links: 0,
            viewAllUrl: store.websiteSettings?.HOTEL_URL,
        }
    },
    methods:{
        loadHotels(){
          var currentObj = this;

          if(currentObj.destinationSlug && store.websiteSettings?.HOTEL_URL) {
            currentObj.viewAllUrl = `${store.websiteSettings.HOTEL_URL}?destination=${currentObj.destinationSlug}`;
          }
          var type = 'Hotel';
          let form_data = store?.searchData;
          form_data['type'] = type;
          form_data['featured'] = 1;
          form_data['limit'] = 10;
          form_data['destination_slug'] = currentObj.destinationSlug;
          axios.post('/hotel/ajaxAccommodationList',form_data)  
          .then(function (response) {  
            if(response.data.success) {
              currentObj.hotelList = response.data?.hotelList;
              currentObj.total_count = response.data?.total_count;
            } else {
              alert('Something went wrong, please try again.');
            }
            // currentObj.processing = false;
            // console.log('running in then');
          });
        },
    },
    components: {
        HomeHotelSliderWrapper,
        HotelSliderBox
    },
    props: ['title', 'destinationSlug'],
}
</script>