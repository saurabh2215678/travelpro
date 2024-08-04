<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <ListingWrapper>
        <div class="container">

            <div class="flex justify-between mt-7">
                <h3 class="title" v-if="store.seoData?.page_title" v-html="store.seoData?.page_title"></h3>
                <Breadcrumb :data="breadcrumbData"/>
            </div>

            <div class="team_wrapper">
                <TourCategoryCard 
                    v-for="(item, theme_listing) in theme" 
                    :data="item"
                    :index="theme_listing"
                />
            </div>
        </div>
    </ListingWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import Layout from '../components/layout.vue';
import { store } from '../store';
import TourCategoryCard from '../components/TourCategoryCard.vue';
import Breadcrumb from '../components/Breadcrumb.vue';

const ListingWrapper = styled.section`
padding-bottom: 3.5rem;
& .title{
    font-size: 1.54rem;
    font-weight: 600;
    color: var(--theme-color);
}
& .team_wrapper {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -1rem;
    & .team_card {
        width: 25%;
        padding: 0 1rem;
        margin-bottom:2rem;
    }
}
@media (max-width: 767px){ 
    & .team_wrapper {
    & .team_card {
        width: 50%;
    }
    & .team_card img{
        height: 15rem;
    }
}
}
`

const breadcrumbData = [{url: '/', label: 'Home'},{label: 'Tour Category'}];

export default {
    created() {
        store.seoData = this.seo_data;
    },
    name:'ThemeListing',
    data(){
        return{
            store,
            breadcrumbData,
            activeId : -1
        }
    },
    methods:{
        handleActive(index){
            if(index != this.activeId){
                this.activeId = index;
            }else{
                this.activeId = -1;
            }
        }
    },
    components:{
    ListingWrapper,
    SearchFormWithBanner,
    TourCategoryCard,
    Breadcrumb
},
    layout: Layout,
    props:["theme","breadcrumbData","seo_data"],
}
</script>