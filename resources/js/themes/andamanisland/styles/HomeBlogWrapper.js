import styled from "vue3-styled-components";

export const HomeBlogWrapper = styled.section`
padding: 5.3rem 0 4rem;
    margin-bottom: 3rem;

& .title_box {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    & .title{
        /* font-size: 2.5rem;
        font-weight: 600; */
        margin-bottom: 0.35rem;
    }
    & .sub-title{
        font-size: 1.125rem;
        color: #333333;
        margin-bottom: 2.5rem;
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
& .blogbox{
    position: relative;
    width: 100%;
    display: block;
}
& .blogimg{
    height: 17.375rem;
    position: relative;
    display: block;
    & a,
    & img {
        width: 100%;
    }
}
& .scontent {
    padding: 1rem 1.3rem;
    background-color: var(--white);
    position: relative;
    padding-top: 2.5rem;
    & .blogdate{
        font-size: 0.875rem;
        position: absolute;
        top: -0.7rem;
        width: 80%;
        font-weight: 600;
        color: var(--black700);
        padding: 0.4rem 1.1rem;
        background-color: var(--white);
        border-radius: 4px;
        box-shadow: 0px 0px 22px 0px rgba(0,0,0,0.09);
        &:before{
            content: "";
            width: 18px;
            height: 16px;
            background: url(../assets/andamanisland/images/dateicons.png) center center no-repeat;
            display: inline-block;
            margin-right: 5px;
            margin-top: -3px;
            vertical-align: middle;
        }
    }
    & .btitle {
        font-size: 15px;
        margin-bottom: 5px;
        font-weight: 600;
        min-height: 38px;
        line-height: 1.3;
        color: #111113;
        white-space: normal;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        & a{
            color: #111113;
            overflow: hidden;
            font-size: 1rem;
            color: #3a4652;
            display: -webkit-box;
            -webkit-line-clamp: 2;
                    line-clamp: 2; 
            -webkit-box-orient: vertical;
        }
    }
}
& .blog_slider .swiper-slide {
    height: initial;
}
& .blogbox {
    position: relative;
    width: 100%;
    display: block;
    height: 100%;
    background-color: #fff;
}
@media (max-width: 767px){
    padding: 2.3rem 0 4rem;
    margin-bottom: 0;
    & .title_box {
        flex-direction: column;
        & .title{
            font-size: 1.5rem;
        }
        & .sub-title{
            margin-bottom: 0;
        }
    }
}
`