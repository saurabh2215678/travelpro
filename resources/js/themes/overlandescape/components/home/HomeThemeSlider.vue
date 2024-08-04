<template>
    <template v-if="loading">
        <SliderSectionLoader/>
    </template>
    <template v-else>
        <SliderSection 
        v-if="this.sectionData && this.sectionData.data && this.sectionData.data.length > 0" 
        :sectionData="this.sectionData"
        :withPrice="true"
        :title="title"
        />
    </template>
</template>

<script>
import { store } from '../../store.js';
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import SliderSection from './SliderSection.vue';
import SliderSectionLoader from '../../skeletons/SliderSectionLoader.vue';

export default {
    name:'HomeThemeSlider',
    created() {
        this.loadThemes();
    },
    data() {
        return {
            store,
            loading : true,
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
            currentObj.loading = true;
            let form_data = {};
            form_data['featured'] = this.featured;
            form_data['limit'] = this.limit;
            axios.post('/ajaxHomeThemes',form_data)  
            .then(function (response) {
                if(response.data.success) {
                    let sectionData = {};
                    sectionData['data'] = response.data?.results?.data;
                    //sectionData['url'] = response.data?.results?.url;
                    sectionData['url'] = currentObj?.viewAllUrl;
                    // sectionData['url'] = store.websiteSettings?.TOUR_CATEGORY_URL;
                    sectionData['links'] = response.data?.results?.links;
                    currentObj.sectionData = sectionData;
                    currentObj.loading = false;
                } else {
                    currentObj.loading = false;
                    alert('Something went wrong, please try again.');
                }
            });
        },
    },
    components: {
    Link,
    SliderSection,
    SliderSectionLoader
},
    props: ["featured", "limit", "viewAllUrl", "title"],
}
</script>