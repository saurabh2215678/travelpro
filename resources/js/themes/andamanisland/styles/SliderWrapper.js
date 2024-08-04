import styled from "vue3-styled-components";

export const SliderWrapper = styled.div`
& .slider_btns {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
    width: calc(100% + 2rem);
    display: flex;
    justify-content: space-between;
    pointer-events: none;
    &>*{
        width: 2rem;
        height: 2rem;
        background-color: var(--theme-color);
        color: var(--white);
        display: grid;
        place-items: center;
        border-radius: 50%;
        font-size: 0.8rem;
        pointer-events: all;
        transition: all ease 0.5s;
        &:hover{
            background-color: var(--secondary-color);
        }
        &> i{
            margin-top: 0.2rem;
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
`