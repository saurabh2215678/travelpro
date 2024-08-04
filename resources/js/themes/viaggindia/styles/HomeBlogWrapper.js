import styled from "vue3-styled-components";

export const HomeBlogWrapper = styled.section`
padding: 5.3rem 0 4rem;
    margin-bottom: 3rem;

& .title_box {
    /* display: flex;
    align-items: center; */
    & .title{
        font-size: 2.5rem;
        font-weight: 600;
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
    -webkit-box-align: center;
    align-items: center;
    margin-left: auto;
    -webkit-box-pack: justify;
    justify-content: center;
    position: absolute;
    width: calc(100% + 12rem);
    left: -6rem;
    top: 50%;
    transform: translateY(-50%);
    justify-content: space-between;
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
& .blogbox{
    position: relative;
    width: 100%;
    display: block;
}
& .blogimg{
    height: 18.75rem;
    position: relative;
    display: block;
    border-radius: 20px;
    & a,
    & img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
}
& .scontent {
    padding: 1rem 1.3rem;
    /* background-color: var(--white); */
    position: relative;
    padding-top: 2.5rem;
    
    & .blogdate{
        width: 100%;
        font-size: 0.875rem;
        font-family: 'Jost',sans-serif;
        color: rgba(40, 40, 42, 0.549);
        font-weight: bold;
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
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            font-size: 23px;
            font-family: 'Jost',sans-serif;
            color: rgb(19, 19, 21);
            font-weight: bold;
            line-height: 1;
            margin-top: 0.938rem;
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
    border: 3px solid var(--theme-color);
    border-radius: 23px;
}


& .read_more_btn{font-size: 0.875rem;font-family: 'Jost',sans-serif;color: rgb(173, 8, 61);text-decoration: underline;line-height: 2.5;font-weight:600;}
& .blog_brief p{font-size: 1.063rem;font-family: 'Jost',sans-serif;color: rgb(77, 76, 82);line-height: 1.529;margin: 0.5rem 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;}

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