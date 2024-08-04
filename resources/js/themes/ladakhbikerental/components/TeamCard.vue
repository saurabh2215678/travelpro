<template>
    <TeamCardWrapper class="team_card">
        <div class="team_card_inner" :class="isActive ? 'active' : ''" @click="handleActive(index)">
            <div class="card_front">
                <img :src="data.managerSrc" alt="data.title">
                <div class="content_box">
                    <h4 class="person_name">{{ data.title }}</h4>
                    <p class="designation">{{ data.designation }}</p>
                    <div class="text-center">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <div class="card_back">
                <h4 class="person_name">{{ data.title }}</h4>
                <p class="designation">{{ data.designation }}</p>
                <p class="person_content" v-html="data.detail_profile"></p>
            </div>
        </div>
    </TeamCardWrapper>
</template>
<script>
import styled from 'vue3-styled-components';

const TeamCardWrapper = styled.div`
  perspective: 1000px;
& .team_card_inner {
    position: relative;
    transition: transform 1s;
    transform-style: preserve-3d;
    height: 38.125rem;
    background-color: var(--black40);
    cursor:pointer;
    &.active{
        transform: rotateY(180deg);
    }
}
& .team_card_inner:hover .content_box i{transition:all .5s ease-in-out;}
& .team_card_inner:hover .content_box i{transform: translateX(1rem);}
& .card_front{
    backface-visibility: hidden;
    height: 100%;
    & img{
        height: calc(100% - 6.5rem);
        width: 100%;
        object-fit: cover;
    }
    & .content_box {
        text-align: center;
        padding: 1rem;
        & .person_name {
            font-weight: 600;
            font-size: 1.12rem;
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
    overflow: auto;
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
        text-align: justify;
        padding-right: 0.5rem;
    }
}
`
export default{
    name : 'TeamCard',
    components:{
        TeamCardWrapper
    },
    props:['data','index','isActive','handleActive']
}
</script>