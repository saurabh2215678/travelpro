<template>
    <SliderSection 
    v-if="this.sectionData && this.sectionData.data && this.sectionData.data.length > 0" 
    :sectionData="this.sectionData"
    :withPrice="true"
    :title="title"
    />
</template>

<script>
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import SliderSection from './SliderSection.vue';

export default {
    name:'HomeThemeSlider',
    created() {
        this.loadThemes();
    },
    data() {
        return {
            store,
            sectionData:{
                'data': [],
                'url': '',
                'links': ''
            },
        }
    },
    methods:{
        loadThemes(){
            var currentObj = this;
            let form_data = {};
            form_data['featured'] = this.featured;
            form_data['limit'] = this.limit;
            axios.post('/ajaxHomeThemes',form_data)  
            .then(function (response) {
                if(response.data.success) {
                    let sectionData = {};
                    sectionData['data'] = response.data?.results?.data;
                    sectionData['url'] = currentObj.viewAllUrl;
                    sectionData['links'] = response.data?.results?.links;
                    currentObj.sectionData = sectionData;
                } else {
                    alert('Something went wrong, please try again.');
                }
            });
        },
    },
    components: {   
        Link,
        SliderSection
    },
    props: ["featured", "limit", "viewAllUrl"],
}
</script>