import styled from "vue3-styled-components";

export const TestimonialWrapper = styled.section`
padding: 1rem 0;
/* background-color: var(--theme-color70); */
& .theme_title{
    display: flex;
    justify-content: space-between;
    & .title {
        font-weight: 600;
    }
    & a{
        padding: 0.313rem 1.563rem;
        text-transform: capitalize;
        background: var(--theme-color);
        color: var(--white);
        border-radius: 5rem;
        display: inline-block;
        font-size: .8rem;
    }
}
& .view_all_btn a{
    background: var(--secondary-color);
    padding: 0.5rem 1.2rem;
    color: var(--white);
    :hover{
        background: var(--theme-color);
        color: var(--white);
    }
}
& .testimonial_slider .swiper-slide {
    background: var(--white);
    border-radius: 0.7rem;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -2px rgba(0,0,0,.1);
    display: flex;
    flex-wrap: wrap;
}
& .testimonial_slider .testimage {
    width: 25%;
}
& .testimonial_slider .testcont {
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 100%;
    /* width: 55%; */
}
& .testimage .swiper-button-prev,
& .testimage .swiper-button-next {
    align-items: center;
    background-color: var(--theme-color);
    border-radius: 5rem;
    box-shadow: 0 2px 5px 1px rgba(64,60,67,.16);
    display: flex;
    height: 1.875rem;
    justify-content: center;
    text-align: center;
    width: 1.875rem;
    &.swiper-button-lock{
        display:none;
    }
}
& .testimage .swiper-button-prev{
    left: 0;
    right: auto;
}
& .testimage .swiper-button-next{
    right: 0;
    left: auto;
}
& .testimage .swiper-button-next:after,
& .testimage .swiper-button-prev:after {
    color: var(--white);
    font-size: 0.875rem;
}
& .testimage .swiper-slide img{
    /* width: 100%; */
    width: 10rem;
    height: 10rem;
    -o-object-fit: cover;
    object-fit: cover;
    border-radius: 5rem;
}
& .testcont{
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 55%;
    & .testi_heading{
        padding-bottom: 5px;
    }
}
& .testi_heading a{
    color: var(--theme-color);
    font-size: 1.2rem;
    font-weight: 600;
    line-height: normal;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
}
& .client_info .client_name {
    display: flex;
    align-items: center;
    & img{
        border-radius: 100%;
        width: 1.875rem;
        height: 1.875rem;
        -o-object-fit: cover;
        object-fit: cover;
    }
    & .name {
        color: var(--secondary-color-dark);
        font-size: 1rem;
        font-weight: 400;
        padding-left: 15px;
    }
}
& .slider_box {
    position: relative;
    border: 1px solid #ccc;
    padding: 1.25rem;
& .swiper{
    & .swiper-slide{
        height: auto;
    }
}
& .slider_btns {
    position: absolute;
    top: 0%;
    right: 0%;
    transform: translate(0%, -90%);
    z-index: 1;
    width: calc(4% + 4rem);
    display: flex;
    justify-content: space-between;
    pointer-events: none;
    &>*{
        width: 2.5rem;
        height: 2.5rem;
        background-color: var(--theme-color);
        color: var(--white);
        display: grid;
        place-items: center;
        border-radius: 50%;
        font-size: 1rem;
        pointer-events: all;
        transition: all ease 0.5s;
        &:hover{
            background-color: var(--secondary-color);
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
}

&.home_testimonial .view_all_btn{
    margin-top: 1rem;
}


& .slider_box>section{padding-bottom:1rem;}



@media (max-width: 1100px){
    padding: 3rem;
}
@media (max-width: 882px){
    padding: 3rem 0;
    & .slider_box .slider_btns{
        display: none;
    }
}
@media (max-width: 767px){
    &  .testimonial_slider {
    & .testimage{
        width: 100%;
        & .swiper-slide img{
            height: 10rem;
        }
    }
    & .testcont{
        width: 100%;
        margin: 0;
        padding: 0 0.6rem;
    }
}
}
@media (max-width: 464px){

}
`