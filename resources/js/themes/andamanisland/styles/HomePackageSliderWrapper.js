import styled from "vue3-styled-components";

export const HomePackageSliderWrapper = styled.section`
overflow: hidden;
padding: 1.9rem 0;
& .PackageSliderCard{
    opacity: 0;
    transition: 0.5s;
    pointer-events: none;
}
& .swiper{
    overflow: visible;
    & .swiper-slide{
        height: auto;
        &  .PackageSliderCard{
            height: 100%;
        }
        &.swiper-slide-visible .PackageSliderCard{
            opacity: 1;
            pointer-events: all;
        }
    }
}
& .slider_btns {
    display: flex;
    display: none;
    align-items: center;
    margin-left: auto;
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
& .slider_pagination {
    padding: 2rem 0 0;
    margin: auto;
    transform: none!important;
    overflow: visible;
    & span{
        width: 12px;
        height: 12px;
        opacity: 0;
        transition: all ease 0.5s!important;
        &.swiper-pagination-bullet-active{
            background-color: var(--orange);
            opacity: 1;
        }
        &.swiper-pagination-bullet-active-next,
        &.swiper-pagination-bullet-active-next-next,
        &.swiper-pagination-bullet-active-prev-prev,
        &.swiper-pagination-bullet-active-prev{
            opacity: 0.45;
        }
    }
}
& .title_wrapper {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding-bottom: 1.5rem;
    & .title_left{
        & .title{
            color: #0c2f64;
        }
        & .subtitle{
            color: #282828;
        }
    }
    & .title_right {
        display: flex;
        align-items: center;
        padding-top: 0.8rem;
        & .view_all {
            padding: 0.5rem 2.3rem;
            background: var(--orange);
            color: var(--white);
            text-transform: uppercase;
            &:hover{
                background: var(--orange-dark);
            }
        }
    }
}
@media (max-width: 767px){
    & .title_wrapper {
        flex-direction: column;
        align-items: flex-end;
        & .title {
            font-size: 1.75rem;
            line-height: 1.1;
            margin-bottom: 0.5rem;
        }
    }
}
`