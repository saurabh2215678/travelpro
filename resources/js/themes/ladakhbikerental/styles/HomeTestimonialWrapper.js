import styled from "vue3-styled-components";

export const HomeTestimonialWrapper = styled.section`
    position: relative;
    padding:0 0 6.25rem 0;
    border: 0;
    overflow: hidden;
    background-size: cover;
    z-index: 1;
    & .testisec {
        width: 100%;
        position: relative;
        padding-bottom: 3rem;
    }
    & .heading2 {
        font-size: 3rem;
        font-weight: 600;
        margin-top: 0;
        margin-bottom: 1.25rem;
        color: #fff;
        text-align: left;
        line-height: 1.3;
    }
    & .tbox{
        border-radius: 5px;
        padding: 1.875rem 3.75rem;
        border: 1px solid #fff;
        height: 17.5rem;
        transition: 0.5s;
        opacity: 0;
        pointer-events: none;
        & .testimage {
            float: left;
            width: 8.125rem;
            height: 8.125rem;
            border-radius: 50%;
            float: left;
            overflow: hidden;
            line-height: 15.625rem;
            & img{
                display: block;
                width: 100%;
                -webkit-transform-style: preserve-3d;
                margin-left: auto;
                margin-right: auto;
                object-position: center center;
                object-fit: cover;
            }
        }
        & .testiname {
            width: calc(100% - 8.75rem);
            float: right;
            margin-top: 0.938rem;
            & .tnames {
                width: 100%;
                float: left;
                & .nametext {
                    width: 100%;
                    float: left;
                    & span{
                        color: #fff;
                        font-size: 1.25rem;
                        font-weight: 600;
                        display: inline-block;
                    }
                }
                
            }
            & .test-title {
                font-size: 1.125rem;
                font-weight: 700;
                min-height: 4.375;
                & a{
                    color: #fff;
                    overflow: hidden;
                    & span{
                        display: -webkit-box;
                        -webkit-line-clamp: 2; /* number of lines to show */
                                line-clamp: 2; 
                        -webkit-box-orient: vertical;
                    }
                }
            }
        }
        & .testcont {
            position: relative;
            margin-top: 0.938rem;
            padding: 0 1.875rem;
            width: 100%;
            float: left;
            & .testi_cont{
                color: #fff;
                opacity: 0.9;
            }
            &:after{
                content: "";
                background: url(../assets/andamanisland/images/dublecomma.png) no-repeat;
                width: 1.688rem;
                height: 1.438rem;
                display: block;
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    }

    & .swiper-slide-visible .tbox {
        opacity: 1;
    }



    & .testi_item, 
    & .testi_item p {
        color: #000;
    }


    /* NEW CSS */
& .testimonial_sub_title {
    max-width: 43.125rem;
    font-size: 1.125rem;
    line-height: 1.35;
    margin-bottom: 3.6rem;
}
& .bg_testimonial{
    width: 100%;
}
& .testimonial_main_box {
    width: 43%;
    position: absolute;
    bottom: 0;
    background-color: var(--white);
    padding: 3rem;
}
& .testimonial_top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    &>img {
        width: 3.688rem;
    }
}
& .testimonial_head_content h3 {
    font-family: 'PT Serif', serif;
    color: var(--black800);
    margin-bottom: 1.3rem;
    margin-top: 0.2rem;
}
& .testi_item>p {
    color: var(--black);
    /* padding-right: 3.5rem; */
    line-height: 2;
    margin-top: 1rem;
    /* font-style: italic; */
}
& .testimonial_item_bottom {
    display: flex;
    align-items: center;
    width: calc(100% - 3.5rem);
}
& .profile_left {
    width: 4.688rem;
    height: 4.688rem;
    margin-right: 1.2rem;
    margin-top: 0.3rem;
    border-radius: 50%;
    overflow: hidden;
    & img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
}
& .testimonial_nav_dots, 
& .testimonial_nav_dots.swiper-pagination-bullets.swiper-pagination-horizontal {
    position: absolute;
    width: fit-content;
    bottom: 0;
    z-index: 1;
    left: 50%;
    transform: translateX(-50%);
}
& .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet, 
& .swiper-pagination-bullet {
    background-color: var(--white);
    opacity: 1;
    border-radius: 5rem;
    margin: 0 3px;
    transition: 0.5s;
}
& .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active {
    width: 30px!important;height: 10px!important;display: inline-block;background: var(--theme-color)!important;border: 1px solid var(--theme-color);border-radius:15px;
}
& .testi_head {
    text-align: center;
    margin-bottom: 2rem;
}
& .testi_foot {
    margin-top: 3rem;
}

& .profile_right{
    width: calc(100% - 3.85rem);
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
}



& .swiper-pagination-bullet{width: 30px;height: 10px;display: inline-block;background: transparent!important;border: 1px solid var(--theme-color);border-radius:15px;}
& .btn_theme{margin-top:1.563rem;}

@media (max-width: 1024px){
    & .testimonial_main_box{
        width: 50%;
    }
    & .testimonial_head_content h3{
        line-height: 1.2;
        margin-bottom: 1rem;
    }
    & .testimonial_top>img{
        width: 2rem;
    }
}
@media (max-width: 767px){
    & .bg_testimonial {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        object-fit: cover;
        object-position: right;
    }
    & .testimonial_main_box {
        position: relative;
        width: 100%;
        background-color: var(--white900);
        padding: 1.5rem;
        padding-bottom: 3rem;
    }
    & .testimonial_nav_dots, 
    & .testimonial_nav_dots.swiper-pagination-bullets.swiper-pagination-horizontal{
        bottom: -3rem;
        left: 50%;
        width: 100%;
        transform: translateX(-50%);
        text-align: center;
    }
    & .swiper-slide {
        height: auto;
        & .testi_item {
            height: 100%;
            display: flex;
            flex-direction: column;
            &>p{
                word-break: break-all;
                margin-bottom: 1rem;
            }
            
        }
    }
    & .profile_right .font23{
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
                line-clamp: 1; 
        -webkit-box-orient: vertical;
    }
    & .testimonial_head_content h3{
        font-size: 2.1rem;
    }
}
& .testi_item{
    /* padding: 1.65rem;
    border-radius: 15px;
    background-color: var(--white);
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid #dfdfdf;
    box-shadow: -4px 7px 12px #0000002b;
    transition: 0.5s;
    opacity: 0;
    pointer-events: none; */
    & .profile_right h4{
        transition: 0.5s;
    }
    /* &:hover{
        transform: translateY(-10px);
        box-shadow: 0px 17px 22px var(--theme-color-dark100);
        background-color: var(--theme-color10);
        & .profile_right p{
            color : var(--theme-color);
        }
    } */
}
& .swiper-slide-visible .testi_item{
    opacity: 1;
    pointer-events: all;
    width:100%;
}
& .quote-img {
    width: 2.625rem;
    margin-left: auto;
    margin-bottom: -2.625rem;
    filter: brightness(0);
    opacity: 0.1;
}
& .swiper-slide {
    height: auto;
}

& .slider_btns {
    display: flex;
    align-items: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 5;
    width: calc(100% + 10rem);
    justify-content: space-between;
    pointer-events: none;
    &>*{
        width: 2.58rem;
        height: 2.58rem;
        background-color: var(--theme-color);
        display: grid;
        place-items: center;
        border-radius: 50%;
        color: var(--white);
        pointer-events: all;
        &.swiper-button-disabled{
            opacity: 1;
            cursor: no-drop;
            background-color: var(--white);
            box-shadow: 0 0 7px #0000001a;
            color: var(--black300);
        }
        &.swiper-button-lock{
            display: none;
        }
    }
    & .slider_btn_prev {
        margin-left: 0.35rem;
    }
}
/* & .testimonial_slider_box{
    overflow: visible;
} */
& .testimonial_slider_wrap {
    position: relative;
}
    /* NEW CSS END */
@media (max-width: 1200px){
    & .testisec {
        width: calc(100% - 5rem);
        margin: auto;
    }
}

@media (max-width: 1100px){
    & .testisec {
        width: 100%;
    }
    & .slider_btns {
        display: none;
    }
}
@media (max-width: 767px){
    padding: 3.6rem 0;
    & .heading2{
        font-size: 2rem;
    }
    & .testisec {
        padding-bottom: 0;
    }

    & .swiper-pagination-bullet{width:24px;}
    & .btn_theme{margin-top:0;}
}
`;