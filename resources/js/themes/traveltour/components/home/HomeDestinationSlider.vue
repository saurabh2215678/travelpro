<template>
    <HomeDestinationWrapper v-if="this.destinations && this.destinations.length > 0">
        <div class="container">
            <div class="section_title text-center mb-5">
                <p class="title_small">{{ subTitle }}</p>
                <h2 class="title_big">{{ title }}</h2>
                <img src="../../assets/images/plane_blue.png" class="plane_blue" alt="">
            </div>
            <div class="destination-gallery" v-for="item in destinations">
                <div class="dest_left">
                    <div class="dest_card lft_top_dest" v-if="item[0]">
                        <Link :href="item[0].url">
                            <img :src="item[0].imageSrc" alt="">
                            <p>{{ item[0].name }}</p>
                        </Link>
                    </div>
                    <div class="dest_card lft_bottom_dest" v-if="item[3]">
                        <Link :href="item[3].url">
                            <img :src="item[3].imageSrc" :alt="item[3].name">
                            <p>{{ item[3].name }}</p>
                        </Link>
                    </div>
                </div>
                <div class="dest_middle">
                    <div class="dest_card" v-if="item[1]">
                        <Link :href="item[1].url">
                            <img :src="item[1].imageSrc" :alt="item[1].name">
                            <p>{{ item[1].name }}</p>
                        </Link>
                    </div>
                </div>
                <div class="dest_right">
                    <div class="dest_card right_top_dest" v-if="item[2]">
                        <Link :href="item[2].url">
                            <img :src="item[2].imageSrc" alt="">
                            <p>{{ item[2].name }}</p>
                        </Link>
                    </div>
                    <div class="right_bottom_dest">
                        <div class="dest_card" v-if="item[4]">
                            <Link :href="item[4].url">
                                <img :src="item[4].imageSrc" alt="">
                                <p>{{ item[4].name }}</p>
                            </Link>
                        </div>
                        <div class="dest_card" v-if="item[5]">
                            <Link :href="item[5].url">
                                <img :src="item[5].imageSrc" alt="">
                                <p>{{ item[5].name }}</p>
                            </Link>
                        </div>
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
        },

    },
    components:{
        HomeDestinationWrapper,
        Link
    },
    props: ['limit','featured', 'title', 'subTitle', 'viewAllUrl','viewAllTitle', 'destinationType', 'isGallery'],
}
</script>