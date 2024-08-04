import styled from "vue3-styled-components";

export const DestinationBox2Wrapper = styled.div`
& .title_box {
    display: flex;
    align-items: center;
    /* margin-bottom: 0.5rem; */
    & .title{
        font-size: 1.875rem;
        font-weight: 700;
    }
}

& .slider_btns {
    display: flex; -webkit-box-align: center; align-items: center; margin-left: auto; justify-content: space-between; position: absolute; width: calc(100% + 12rem); left: -6rem; top: 50%; transform: translateY(-50%);
    &>*{
        width: 4.5rem;
        height: 4.5rem;
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


& .desti_border{position:absolute;left:0;top:0;width:100%;height:100%;}

& .destination_view {
    padding: 0px 5px 5px;
    width: 100%;
    float: left;
}
& .destimg {
    position: relative;
    height: 20.5rem;
    display: flex;
    & img.main_img{
        width: 100%;
        object-fit: cover;
        object-position: center;
        -webkit-mask-image: url(images/desti_img.png);
        -webkit-mask-size: 100%;
        -webkit-mask-repeat: no-repeat;
    }
}
& .package_info{
    text-align: left;
    position: relative;
    /* background: #fff; */
    /* box-shadow: 0 3px 3px #ccc; */
    width: 100%;
    float: left;
    z-index: 9;
    padding: 0;
    min-height: auto;
    & a{
        padding: 1rem 0;
        display: block;
        line-height: 1.1;
        font-size: 20px;
        font-family: 'Jost',sans-serif;
        color: rgb(55, 20, 12);
        font-weight: bold;
        text-transform: capitalize;
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