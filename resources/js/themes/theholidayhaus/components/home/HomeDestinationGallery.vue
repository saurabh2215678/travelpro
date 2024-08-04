<template>
    <HomeDestinationWrapper v-if="this.destinations && this.destinations.length > 0">
        <div class="container">
            <div class="section_title text-center mb-5">
                <p class="title_small">{{ subTitle }}</p>
                <h2 class="title_big">{{ title }}</h2>
            </div>
            <div class="destination-gallery" v-for="item in destinations">
                <div class="dest_left">
                    <DestinationCard v-if="item[0]" :item="item[0]" className="lft_top_dest"  />
                    <DestinationCard v-if="item[3]" :item="item[3]" className="lft_bottom_dest" />
                </div>
                <div class="dest_middle">
                    <DestinationCard v-if="item[1]" :item="item[1]" />
                </div>
                <div class="dest_right">
                    <DestinationCard v-if="item[2]" :item="item[2]" className="right_top_dest" />
                    
                    <div class="right_bottom_dest">
                        <DestinationCard v-if="item[4]" :item="item[4]"/>
                        <DestinationCard v-if="item[5]" :item="item[5]"/>
                    </div>
                </div>
            </div>
        </div>
    </HomeDestinationWrapper>
</template>
<script>
import { store } from '../../store';
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import { HomeDestinationWrapper } from '../../styles/HomeDestinationWrapper';
import DestinationCard from './DestinationCard.vue';

export default {
    name:"DestinationsSection",
    created() {
        this.loadDestinations();
    },
    data() {
        return {
            store,
            destinations : []
        }
    },
    methods:{
        loadDestinations(){
            var currentObj = this;
            let form_data = {};
            form_data['destination_type'] = this.destinationType;
            form_data['featured'] = this.featured;
            form_data['limit'] = this.limit;
            axios.post('/destination/ajaxDestinationList',form_data)  
            .then(function (response) {
                if(response.data.success) {
                    let destinationsData = response.data?.results;
                    // console.log('dest_data', destinationsData);
                    const chunkSize = 6;
                    const newDataArray = [];
                    for (let i = 0; i < destinationsData.data.length; i += chunkSize) {
                        newDataArray.push(destinationsData.data.slice(i, i + chunkSize));
                    }
                    currentObj.destinations = [...newDataArray];
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        }
    },
    components:{
    HomeDestinationWrapper,
    Link,
    DestinationCard
},
    props: ['limit','featured', 'title', 'subTitle', 'viewAllUrl','viewAllTitle', 'destinationType', 'isGallery'],
}
</script>