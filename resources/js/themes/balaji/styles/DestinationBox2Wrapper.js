import styled from "vue3-styled-components";

export const DestinationBox2Wrapper = styled.div`
& .title_box {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    & .title{
        font-size: 1.875rem;
        font-weight: 700;
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


& .destination_view {
    padding: 0px 5px 5px;
    width: 100%;
    float: left;
}
& .destimg {
    position: relative;
    height: 17.2rem;
    display: flex;
    & img{
        width: 100%;
        object-fit: cover;
        object-position: center;
    }
}
& .package_info{
    text-align: left;
    position: relative;
    background: #fff;
    box-shadow: 0 3px 3px #ccc;
    width: 100%;
    float: left;
    z-index: 9;
    padding: 0;
    min-height: auto;
    & a{
        font-size: 16px;
        color: #111113;
        padding: 18px;
        display: block;
        font-weight: 600;
        line-height: 1.1;
        & span{
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }
    }
}
`