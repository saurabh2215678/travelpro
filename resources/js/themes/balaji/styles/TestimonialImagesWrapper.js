import styled from "vue3-styled-components";

export const TestimonialImagesWrapper = styled.div`
&.slider_box {
    position: relative;
    & .swiper{
        & .swiper-slide{
            height: auto;
        }
    }
    & .slider_btns {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
        width: calc(100% + 3rem);
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        &>*{
            width: 2.5rem;
            height: 2.5rem;
            background-color: var(--theme-color);
            color: var(--white);
            display: grid;
            place-items: center;
            border-radius: 50%;
            font-size: 0.85rem;
            pointer-events: all;
            transition: all ease 0.5s;
            &:hover{
                background-color: var(--secondary-color);
            }
        }
        &>.swiper-button-disabled{
            background-color: #c8c8c8;
            cursor: no-drop;
        }
        &>.swiper-button-lock{
            display:none;
        }
    }
}
& .swiper-slide img{
    width : 100%;
    height: 37rem;
    object-fit: cover;
}
@media (max-width: 767px){
    & .swiper-slide img{
        height: 24rem;
    }
}
@media (max-width: 580px){
    &.slider_box .slider_btns{
        display: none;
    }
}
`