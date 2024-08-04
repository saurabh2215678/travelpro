<template>
    <SearchFormWithBanner
    title="Search For Place"
    />
    <ListingWrapper>
        <div class="container">

            <div class="flex justify-between mt-7">
                <h3 class="title">{{ page_title }}</h3>
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
import { store } from '../store';
import {dummyTeam} from '../data';
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
        width: 25%;
        padding: 1rem;
    }
}
@media (max-width: 767px){ 
    & .team_wrapper {
    & .team_card {
        width: 50%;
    }
    & .team_card img{
        height: calc(100% - 10.104rem);
    }
}
}
`

const breadcrumbData = [{url: '/', label: 'Home'},{label: 'Team'}];

export default {
    created() {
        store.seoData = this.seo_data;
    },
    name:'TeamListing',
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