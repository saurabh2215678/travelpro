import styled from "vue3-styled-components";

export const HotelCardSliderWrapper = styled.div`
& .slider_main{
    position: relative;
}
& .slider_btns {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
    &>*{
        width: 2.25rem;
        height: 2.25rem;
        background-color: var(--white);
        display: grid;
        place-items: center;
        border-radius: 50%;
        border: 1px solid #d1cdcb;
        color: var(--theme-color);
        &.swiper-button-disabled{
            opacity: 0.4;
            cursor: no-drop;
        }
        &.swiper-button-lock{
            display: none;
        }
    }
    & .slider_btn_prev {
        margin-left: 0.35rem;
    }
    
}
&.HotelCardSlider .slider_main .swiper .swiper-wrapper .swiper-slide img{
    height: 12rem;
    width: 100%;
    object-fit: cover;
    border-top-left-radius: 0;
}
& .slider_thumb.roomgallery img {
    height: 4rem;
    margin-top: 0.3rem;
    object-fit: cover;
    width: 100%;
    cursor: pointer;
}


`