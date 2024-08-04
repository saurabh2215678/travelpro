import styled from "vue3-styled-components";

export const DestinationBoxWrapper = styled.div`
& .title_box {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    & .title{
        color: var(--white);
        font-size: 1.7rem;
        font-weight: 600;
    }
}

& .slider_btns {
    display: flex;
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


& .destination_inner {
    border-radius: 10px;
    display: block;
    width: 100%;
    height: 18.75rem;
    position: relative;
    overflow: hidden;
    background: #0077c1;
    &:before{
        content: '';
        border-radius: 5px;
        background: var(--secondary-color);
        opacity: .6;
        width: 90%;
        height: 36px;
        left: 5%;
        top: 100%;
        margin-top: -20%;
        position: absolute;
        z-index: 1;
        -webkit-transition: .3s;
        -o-transition: .3s;
        transition: .3s;
    }
    & img{
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    &:hover:before{
        height: 92%; 
        top: 4%;
        margin-top: 0;
    }
}

& .destination_info {
    position: absolute;
    top: 87%;
    width: 80%;
    left: 10%;
    text-align: left;
    z-index: 3;
    color: #0d0d10;
    -webkit-transition: .3s;
    -o-transition: .3s;
    transition: .3s;
    & h3{
        font-weight: 500;
        font-size: 1.125rem;
        position: relative;
        bottom: 0px;
        transition: 0.3s;
    }
}

`