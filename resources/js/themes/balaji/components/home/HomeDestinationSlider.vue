<template>
    <template v-if="isGallery">
        <section class="home_featured latoFont pb-0" v-if="this.destinations && this.destinations.data && this.destinations.data.length > 0">
            <div class="xl:container xl:mx-auto">
                <div class="dest_main_flex">
                    <div class="theme_title mb-6">
                        <div class="title" v-html="this.title"></div>
                        <div class="view_all_btn"><Link :href="this.viewAllUrl" v-html="this.viewAllTitle"></Link></div>
                    </div>

                    <div class="desti_main">
                        <ul>
                            <li v-for="destination in this.destinations.data">
                                <Link :href="destination.url" class="desti_a">
                                    <div class="dest_img">
                                        <img :src="destination.imageSrc">
                                    </div>
                                    <div class="desti_content" v-html="destination.name"></div>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </template>
    <template v-else>
        <section class="home_featured latoFont" v-if="this.destinations && this.destinations.data && this.destinations.data.length > 0">
            <div class="xl:container xl:mx-auto">
                <div class="desti_slider_flex">
                    <sliderItem
                    :destinations="this.destinations"
                    :title="this.title"
                    :viewAllTitle ="this.viewAllTitle"
                    :viewAllUrl ="this.viewAllUrl"
                    />
                </div>
            </div>
        </section>
    </template>
</template>
<script>
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import sliderItem from './sliderItem.vue';

export default {
    name:'HomeDestinationSlider',
    created() {
        this.loadDestinations();
    },
    data() {
        return {
            store,
            destinations:{
                'data': [],
                'links': ''
            },
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
                    currentObj.destinations = response.data?.results;
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        },

    },
    components: {
        Link,
        sliderItem
    },
    props: ['limit','featured', 'title', 'viewAllUrl','viewAllTitle', 'destinationType', 'isGallery'],
}
</script>