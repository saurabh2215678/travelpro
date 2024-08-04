import styled from "vue3-styled-components";

export const HomeTestimonialWrapper = styled.section`
    position: relative;
    padding: 6.6rem 0;
    background: #7e7e7e;
    border: 0;
    overflow: hidden;
    background-image: url(../assets/andamanisland/images/testimonial-bg.png);
    background-size: cover;
    & p{
        color: #fff;
    }
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
    padding-right: 3.5rem;
    line-height: 1.35;
    margin-bottom: 2.8rem;
}
& .testimonial_item_bottom {
    display: flex;
    align-items: flex-start;
    margin-top: auto;
}
& .profile_left {
    width: 3.313rem;
    height: 3.313rem;
    margin-right: 1.2rem;
    margin-top: 0.3rem;
    border-radius: 0.625rem;
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
    left: 0;
    bottom: 0;
    z-index: 1;
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
    width: 1.5rem;
    background-color: var(--orange);
}
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
        bottom: 1rem;
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
            & .testimonial_item_bottom{
                margin-top: auto;
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
    padding: 1.65rem;
    border-radius: 15px;
    background-color: var(--white);
    height: 100%;
    display: flex;
    flex-direction: column;
}
& .quote-img {
    width: 3.25rem;
    margin-left: auto;
    margin-bottom: -0.15rem;
}
& .swiper-slide {
    height: auto;
}
    /* NEW CSS END */

@media (max-width: 767px){
    padding: 3.6rem 0;
    & .heading2{
        font-size: 2rem;
    }
}
`;