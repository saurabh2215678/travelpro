import styled from "vue3-styled-components";

export const HomePageBannerSliderWrapper = styled.div`
&>.swiper, &>.swiper>div{
    height: 100%;
}
& .swiper-wrapper .swiper-slide>img{
    width: 100%;
    height: 100%;
    object-fit: cover!important;
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
    top: 30%;
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
& .btn_banner img {
    margin-right: 0.5rem;
    -webkit-filter: invert(1);
    filter: invert(1);
    width: 1rem;
    height: 1rem;
    object-fit: contain;
    margin-left:3.6rem;
}
& .btn_banner span{
    width: auto;
    background: transparent;
    border: 0;
    display: inline-block;
}
& .btn_banner i {
    background: #fff;
    width: 2rem;
    text-align: center;
    height: 2rem;
    line-height: 2rem;
    border-radius: 5rem;
    margin-right: 0.5rem;
}
& .btn_banner:hover i {
    background: var(--theme-color);
}
& .btn_banner {
    display: inline-block;
}
/* & .btn_banner {
    background: #fff;
    border: 2px solid #fff;
    padding: 0.5rem 1rem;
    margin-right: 15px;
    display: inline-block;
    border-radius: 0.5rem;
    :hover{
        background: var(--theme-color);
    }
    span{
        width: auto;
    }
}
& .btn_banner:hover span{
    color: #fff;
    background: transparent;
    border: 0;
    height: auto;
}
& .btn_banner:hover span img {
    filter: brightness(100);
}
& .btn_banner {
    background: #fff;
    border: 2px solid #fff;
    padding: 0.5rem 1rem;
    margin-right: 15px;
    display: inline-block;
    border-radius: 0.5rem;
} */


@media (max-width: 992px){
    & .banner_content{
        padding : 0 1rem;
    }
}

@media (max-width: 767px){
    &.slider_section .swiper {
    display: none;
}
    & .mobile_image {
    display: block;
    a{
        width: 100%;
        .banner_img {
            width: 100%;
        }
    }
}
}
`