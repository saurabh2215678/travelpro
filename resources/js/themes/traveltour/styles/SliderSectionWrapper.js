import styled from "vue3-styled-components";

export const SliderSectionWrapper = styled.section`
padding: 3.5rem 0;
overflow: hidden;
.theme_title{
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    .title{
        font-weight: 600;
        color: var(--theme-color);
        font-family: 'PT Serif',serif;
        text-transform: uppercase;
        font-size: 1.6rem;
    }
    & .view_all_btn a{
        padding: 0.313rem 1.563rem;
        text-transform: capitalize;
        background: var(--theme-color);
        color: var(--white);
        border-radius: 5rem;
        font-size: .8rem;
        &:hover{
            background-color: var(--secondary-color);
        }
    }
}
.slider_box {
    position: relative;
    & .swiper{
        overflow: visible;
        & .swiper-slide{
            height: auto;
            &>*{
                opacity: 0;
                transition:0.5s;
            }
            &.swiper-slide-visible>*{
                opacity: 1;
            }
        }
    }
    & .slider_btns {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
        width: calc(100% + 4rem);
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        &>*{
            width: 3rem;
            height: 3rem;
            background-color: var(--theme-color);
            color: var(--white);
            display: grid;
            place-items: center;
            border-radius: 50%;
            font-size: 1rem;
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
& .tour_category_box {
    box-shadow: 2px 5px 12px #0000003b;
    height: 100%;
    border-radius: 0.5rem;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    & .images img{
        display: block;
        transition: .5s;
        width: 100%;
        height: 100%;
        -o-object-fit: cover;
        object-fit: cover;
    }
}
& .featured_content {
    flex-grow: 1;
    padding: 1rem;
    & .title{
        font-weight: 600;
        line-height: 1.4;
        color: var(--theme-color);
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
    }
}
@media (max-width: 1100px){
    padding: 2rem 3rem 4rem;
}
@media (max-width: 767px){
    padding: 2rem 0rem 4rem;
    & .slider_box .slider_btns{
        display: none;
    }
}
`
