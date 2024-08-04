<template>
    <TeamCardWrapper class="team_card">
        <div class="card_front">
            <Link :href="data.cat_url">
            <img :src="data.managerSrc" alt="data.title">
            <div class="content_box">
                <h4 class="person_name" v-html="data.title"></h4>
            </div>
             </Link>
             <!-- <div class="fintout-more text-center">
                <Link class="bg-transparent border-blue-900 border rounded-full p-1 pl-3 pr-3 text-sm" :href="data.cat_url"> Find out more</Link>
            </div> -->
        </div>  
    </TeamCardWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import { Link } from "@inertiajs/inertia-vue3";


const TeamCardWrapper = styled.div`
  perspective: 1000px;
& .team_card_inner {
    position: relative;
    transition: transform 1s;
    transform-style: preserve-3d;
    height: 28.125rem;
    background-color: var(--black40);
    cursor:pointer;
    &.active{
        transform: rotateY(180deg);
    }
}
& .card_front{
    backface-visibility: hidden;
    &>a{height:100%;width:100%;}
    & img{
        height: 20rem;
        width: 100%;
        object-fit: cover;
    }
    & .content_box {
        text-align: center;
        padding: 1rem;
        & .person_name {
            font-weight: 600;
            font-size: 1rem;
        }
        & .designation {
            font-size: 0.95rem;
        }
    }
}
& .card_back {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    padding: 1.4rem 1.25rem 1.875rem;
    background: #f1f1f1;
    backface-visibility: hidden;
    transform: rotateY(180deg);
    text-align: center;
    & .person_name {
        font-weight: 600;
        font-size: 1.12rem;
    }
    & .designation {
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }
    & .person_content {
        font-size: 0.854rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 16;
        line-clamp: 16;
        -webkit-box-orient: vertical;
    }
}
`
export default{
    name : 'TourCategoryCard',
    components:{
        TeamCardWrapper,
        Link
    },
    props:['data','index']
}
</script>