<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <ListingWrapper>
        <div class="container">

            <div class="flex justify-between mt-7 cms_hdng">
                <h1 class="title">{{ store?.seoData?.page_title }}</h1>
                <Breadcrumb :data="breadcrumbData"/>
            </div>

            <div class="team_wrapper">
                <TeamCard 
                    v-for="(item, index) in team" 
                    :data="item"
                    :index="index"
                    :isActive="index == activeId" 
                    :handleActive="handleActive" 
                />
            </div>
        </div>
    </ListingWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import SearchFormWithBanner from '../components/SearchFormWithBanner.vue';
import Layout from '../components/layout.vue';
import {dummyTeam} from '../data';
import { store } from '../store.js';
import TeamCard from '../components/TeamCard.vue';
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
        width: 33.33%;
        padding: 1rem;
    }
}
@media (max-width: 767px){ 
    & .team_wrapper {
    & .team_card {
        width: 100%;
    }
    & .team_card img{
        height: calc(100% - 6.104rem);
    }
}
}
`

const breadcrumbData = [{url: '/', label: 'Home'},{label: 'Team'}];

export default {
    name:'TeamListing',
    created() {
        store.seoData = this.seo_data;
        //console.log("dddad",store.seoData);
    },
    data(){
        return{
            store,
            team : dummyTeam,
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
    TeamCard,
    Breadcrumb
},
    layout: Layout,
    props:["team","breadcrumbData","seo_data"],
}
</script>