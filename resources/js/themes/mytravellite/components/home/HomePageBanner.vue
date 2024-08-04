<template>
    <BannerWrapper>
        <div class="container">
            <div class="banner_left">
                <img class="top_book_img" src="../../assets/images/banner-icon-01.png" alt="">
                <img class="bottom_camera_img" src="../../assets/images/banner-icon-02.png" alt="">
                <div class="content">
                    <p class="font18 fw300 color_dark800">Explore the World</p>
                    <h1>Find Your Perfect<br>Tour At <span>Travel!</span></h1>
                    <p class="font17 color_dark900">Would you explore nature paradise in the world, let’t find the<br>best destination in world with us.</p>
                </div>
            </div>
            <div class="banner_right">
                <img class="banner_main_img" src="../../assets/images/banner_main_img.png" alt="">
                <img class="banner_bg_img" src="../../assets/images/banner_main_bg.png" alt="">
                <img class="banner_icon1" src="../../assets/images/banner-car.png" alt="">
                <img class="banner_icon2" src="../../assets/images/banner-plan.png" alt="">
                <LifeTimeMemoryCard/>
            </div>
        </div>
    </BannerWrapper>
</template>
<script>
import styled from 'vue3-styled-components';
import LifeTimeMemoryCard from './LifeTimeMemoryCard.vue';

const BannerWrapper = styled.section`
padding: 3rem 0;
padding-top: 10rem;
& .top_book_img, 
& .bottom_camera_img {
    position: absolute;
    /* display: none; */
}
& .banner_left,
& .banner_right { position: relative; }
& .banner_left .content{padding: 7rem 0 8.5rem; max-width: 43rem;}
&>.container{display: flex;}
& .banner_right>img { position: absolute; }
& .banner_right>img.banner_main_img { position: relative; aspect-ratio: 471/593; width: 29vw; max-width: 24.75rem; z-index: 1; }
& .banner_left {display: flex; align-items: center; }
& .content h1 { font-size: 4.75rem; font-family: 'Lobster Two', cursive; font-weight: 800; letter-spacing: 0.05px; line-height: 1; padding: 1rem 0 1.5rem; color: var(--theme-color); }
& .content h1 span {color: var(--secondary-color);}
& .banner_right { margin-left: auto; align-self: flex-end; }
& .banner_bg_img { bottom: 3%; left: 50%; transform: translateX(-50%); max-width: initial; width: 130%;}
& .banner_icon1 { top: -5%; right: -20%; width: 6.5rem;}
& .banner_icon2 { bottom: 38%; left: -71%; width: 7.813rem; }
& .top_book_img { top: 0; left: -18%; width: 4.5rem; }
& .bottom_camera_img { bottom: 0; left: -6rem; width : 5.75rem; }
@media (max-width: 1170px){
    overflow: hidden;
    & .banner_right>img.banner_main_img{
        max-width: 23rem;
    }
}
@media (max-width: 1100px){
& .banner_left .content{
    padding: 5.34rem 0;
}
& .banner_icon1{
    display : none;
}
& .banner_right>img.banner_main_img{
    width: 24vw;
}
}
@media (max-width: 992px){
    & .banner_left .content{
        max-width: 32rem;
        padding: 2.34rem 0;
        & h1{
            font-size: 3.35rem;
        }
    }
    & .banner_icon2{
        width: 5.813rem;
    }
    & .banner_right>img.banner_main_img {
        margin-top: 2rem;
    }
    }
}
@media (max-width: 767px){
    & .bottom_camera_img, 
    & .top_book_img{
        display: none;
    }
    &>.container{flex-direction: column;}
    & .banner_left {
        padding-bottom: 1rem;
        & .content{
            padding: 0;
            & h1{
                font-size: 2.6rem;
            }
            & p br{
                display: none;
            }
        }
    }
    & .banner_right{
        width: fit-content;
        padding-left: 7rem;
        position: relative;
        margin: auto;
        &>img.banner_main_img {
            width: 100%;
            max-width: 19rem;
            margin-left: auto;
            margin-top: 0;
        }
    }
    & .banner_bg_img {
        width: 100%;
        max-width: 24rem;
        left: auto;
        right: -2.3rem;
        transform: none;
    }
    & .banner_icon2{
        left: 0;
        bottom: 13rem;
    }
}
`;

export default {
    name: 'HomePageBaneer',
    components: {
    BannerWrapper,
    LifeTimeMemoryCard
}
}
</script>