import styled from "vue3-styled-components";

export const DestinationWrapper = styled.section`
overflow:hidden;
padding-bottom: 1.7rem;
padding-top: 2rem;
&>.container{
    & .activity_wrapper{
        position: relative;
    }
    & .theme_title{
        display: flex;
        & .view_all_btn{
            margin-left: auto;
            &  a{
                background-color: var(--theme-color);
                padding: 0.36rem 1rem;
                color: var(--white);
                border-radius: 5rem;
                font-size: 0.9rem;
            }
        }
    }
    & .top_title{
        font-size: 1.5rem;
        font-weight: 600;
        font-family: 'PT Serif',serif;
        color: var(--theme-color);
        text-transform: uppercase;
        &:after{
            content: "";
            display: block;
            background-color: var(--secondary-color);
            height: 3px;
            width: 40px;
        }
    }
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
            width: 3.625rem;
            height: 3.625rem;
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

`;
