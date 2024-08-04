import styled from "vue3-styled-components";

export const HotelSliderBoxWrapper = styled.div`
& .theme_title {
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    & .title{
        font-size: 1.35rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    margin-bottom: 0.4rem;
}
& .option_right { 
    display: flex; 
    align-items: center; 
    &>a{
        background-color: var(--theme-color);
        color: var(--white);
        padding: 0.3rem 1.1rem;
        border-radius: 5rem;
        font-size: 0.85rem;
        margin-right: 0.5rem;
    }
    & .slider_btns {
        display: flex;
        &>* {
            width: 2.34rem;
            height: 2.34rem;
            display: grid;
            place-items: center;
            background-color: var(--secondary-color);
            font-size: 0.72rem;
            color: var(--white);
            border-radius: 50%;
            transition: 0.5s;
            cursor: pointer;
            &:first-child{
                margin-right: 0.3rem;
            }
            &:hover{
                background-color: var(--secondary-color-dark);
            }
            &.swiper-button-disabled{
                background-color: var(--white);
                box-shadow: -2px 7px 15px #0000001a;
                color: var(--black500);
                cursor: no-drop;
            }
            &.swiper-button-lock{
                display: none;
            }
        }
    }
}

& .hotel_box {
    display: block;
    & .images {
        height: 9rem;
        overflow: hidden;
        & img{
            height: 100%;
            width: 100%;
            transition: .5s;
            object-fit: cover;
        }
    }
    &:hover .images img{
        transform: scale(1.1);
    }
    & .hotel_content {
        & .title {
            font-weight: 700;
            color: var(--theme-color);
        }
        & p{
            display: flex;
            font-size: 0.875rem;
            color: #5e5e63;
            align-items: center;
            & img{
                margin-right: 0.3rem;
            }
        }
    }
}
`