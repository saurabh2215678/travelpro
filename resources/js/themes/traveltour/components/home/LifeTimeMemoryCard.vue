<template>
    <card-wrapper>
            <a class="play_btn" href="javascript:void();"><i class="fa-solid fa-play"></i></a>
            <p class="font17">Lifelong memories<br>just few seconds away</p>
            <a href="javascript:void();" class="font10">Plan your trip</a>
    </card-wrapper>
</template>
<script>
import styled from 'vue3-styled-components';

const CardWrapper = styled.div`
    position: absolute;
    border-radius: 8px;
    box-shadow: -21.551px 19.405px 28px 0px rgba(0, 0, 0, 0.08);
    padding: 1.2rem 2rem;
    left: -39%;
    bottom: 4%;
    background-color: var(--white);
    padding-top: 2.2rem;
    z-index: 2;
& .play_btn {
    width: 2.813rem;
    height: 2.813rem;
    border: 2px solid currentColor;
    display: grid;
    place-items: center;
    border-radius: 50%;
    position: absolute;
    top: -1.2rem;
    background-color: var(--white);
    color: var(--theme-color);
}
&>p{
    font-family: 'BenguiatITCbyBT';
    line-height: 1.25;
    padding-bottom: 0.3rem;
}
&>p+a{
    text-decoration: underline;
    font-weight: 500;
}
@media (max-width: 992px){
    padding: 2.4rem 1.1rem 1rem;
}
@media (max-width: 767px){
    left: 0;
}
`;

export default {
    name: 'LifeTimeMemoryCard',
    components: {
        'card-wrapper' : CardWrapper
    }
}
</script>