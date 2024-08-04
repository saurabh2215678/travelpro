import styled from "vue3-styled-components";

export const HomePageBannerSliderWrapper = styled.div`
&>.swiper, &>.swiper>div{
    height: 100%;
}
& .swiper-wrapper .swiper-slide img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}
& .bottom_image{
    content: "";
    width: 100%;
    height: 66px;
    background: url(../assets/andamanisland/images/banner-bottom-shadow.png) center top;
    position: absolute;
    left: 0;
    bottom: 0;
    z-index: 9;
    display: none;
}
& .banner_content {
    position: absolute;
    top: 50%;
    transform: translateY(-110px);
    width: 100%;
    & a:hover{
        color: var(--white);
    }
}
&.slider_section .swiper-pagination .swiper-pagination-bullet {
    position: relative;
    width: 1rem;
    height: 1rem;
    ::before{
        content: '';
        background: #fff;
        border: 1px solid #185c96;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        position: absolute;
        top: 0;
        left: 0;
    }
    ::after{
        content: '';
        background: #185c96;
        width: 45%;
        height: 45%;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
    }
}

@media (max-width: 992px){
    & .banner_content{
        padding : 0 1rem;
    }
}
@media (max-width: 767px){
    & .slider-caption .slider_heading {
    font-size: 3rem;
}


}

`