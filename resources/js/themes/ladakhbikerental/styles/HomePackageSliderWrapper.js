import styled from "vue3-styled-components";

export const HomePackageSliderWrapper = styled.section`
overflow: hidden;
padding: 6.25rem 0 0 0;
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
            position: relative;
            :before{content:"";position:absolute;width:100%;height:100%;background-color:#0000007d;top:0;left:0;pointer-events:none;}
        }
        &.swiper-slide-visible .PackageSliderCard{
            opacity: 1;
            pointer-events: all;
        }
    }
}
& .swiper .swiper-slide .PackageSliderCard:hover a.package_image_box:before {
    border-color: #f5a44a;
    border-width: 3px;
    content: '';
    display: block;
    height: 25px;
    pointer-events: none;
    position: absolute;
    width: 25px;
    z-index: 9;
    margin: 10px;
    transition: opacity .4s linear;
    border-bottom: 0;
    border-right: 0;
}
& .swiper .swiper-slide .PackageSliderCard:hover a.package_image_box:after {
    border-color: #f5a44a;
    border-width: 3px;
    content: '';
    display: block;
    height: 25px;
    pointer-events: none;
    position: absolute;
    width: 25px;
    z-index: 9;
    margin: 10px;
    transition: opacity .4s linear;
    border-top: 0;
    border-left: 0;
    bottom: 0;
    right: 0;
}
& .btn_theme{margin:3.75rem auto 0;}
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
            background-color: var(--theme-color);
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
    position: relative;
    & .title_left{
        
        & .subtitle{
            font-weight: 500;
        }
    }
    & .title_right {
        display: flex;
        align-items: center;
        padding-top: 0.8rem;
        & .view_all {
            padding: 0.5rem 2.3rem;
            background: var(--theme-color);
            color: var(--white);
            border-radius: 5px;
            &:hover{
                background: var(--theme-color-dark);
            }
        }
    }
}
@media (max-width: 767px){
    & .title_wrapper {
        flex-direction: column;
        align-items: flex-start;
        & .title {
            font-size: 1.75rem;
            line-height: 1.1;
            margin-bottom: 0.5rem;
        }
    }
}
`