import styled from "vue3-styled-components";


export const DestinationWrapper = styled.section`
overflow:hidden;
padding-bottom: 1.7rem;
&>.container{
    position: relative;
    & .slider_btns {
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
        left: 50%;
        width: calc(100% + 2rem);
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        z-index: 1;
        & .slider_btn{
            width: 2.625rem;
            height: 2.625rem;
            background-color: var(--secondary-color);
            border-radius: 50%;
            display: grid;
            place-items: center;
            color: var(--white);
            font-size: 1.2rem;
            transition: 0.5s;
            pointer-events: all;
            &:hover{
                background-color: var(--secondary-color-dark);
            }
            &.swiper-button-disabled{
                background-color: hsl(0deg 0% 87.4%);
                color: var(--black400);
                cursor: no-drop;
            }
            &.swiper-button-lock{
                display:none;
            }
        }
    }
    & .swiper{
        overflow: visible;
        & .swiper-slide{
            pointer-events: none;
            height: auto;
            &>div{
                opacity: 0;
                transition :0.5s
            }
        }
        & .swiper-slide-visible{
            pointer-events: all;
            &>div{
                opacity: 1;
            }
        }
    }
}
@media (max-width: 1070px){
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}
@media (max-width: 767px){
    padding-left: 0;
    padding-right: 0;
    &>.container .slider_btns{
        display: none;
    }
}
`;