import styled from "vue3-styled-components";

export const TestimonialDetialWrapper = styled.section`
--sidebar_width: 15rem;
& .left_sec {
    width: calc(100% - var(--sidebar_width));
    padding-right: 2rem;
    border-right: 1px solid var(--black80);
}
& .right_sec {
    width: var(--sidebar_width);
    padding-left: 1.6rem;
}
& .packages_ul>.swiper-slide{
    height: initial;
    margin-bottom: 1.5rem;
    & .featured_box {
        box-shadow: 4px 6px 9px #00000038;
        & .images img{
            height: 8rem;
            width: 100%;
            object-fit: cover;
        }
    }
}
& .title{
    font-weight: 600;
    color: var(--theme-color);
}
& .left_sec_content .image {
    width: 100%;
    max-height: 28rem;
    object-fit: cover;
}
& .testimonial_desc {
    margin-top: 1.5rem;
    margin-bottom: 2rem;
}
& .testimonial_author_card {
    display: flex;
    align-items: center;
    & img {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 0.8rem;
    }
}
& .writereview>a{
    width: 100%;
    padding: 40px 20px 20px;
    border-radius: 0.3rem;
    border: 0 solid #f1f1f1;
    background: url(../assets/traveltour/img/writereview.jpg) center center no-repeat;
    position: relative;
    background-size: cover;
    & strong{
        font-size: 1.3rem;
        display: block;
        font-weight: 900;
        color: #2c2d6c;
        line-height: 1.375rem;
    }
    & span{
        position: relative;
        z-index: 1;
    }
    &:before{
        content: "";
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,.6);
        display: block;
        position: absolute;
        left: 0;
        top: 0;
    }
    &:after{
        content: "";
        width: 100%;
        height: 100%;
        border: 10px solid rgba(0,0,0,.3);
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
    }
    &:hover:after{
        opacity: 1;
    }
}
& .right_sec{
    & .packages_ul>*{
        margin-bottom : 1rem;
    }
}
& .share_sec{
    &>.share{
        font-size: 1.12rem;
        text-transform: uppercase;
        line-height: 1;
        margin-top: 1.2rem;
    }
}
@media (max-width: 1170px){
    & .left_sec{
        padding: 0 1.8rem;
        padding-left: 1.4rem;
    }
}
@media (max-width: 580px){
    & .left_sec{
        padding: 0;
        width: 100%;
        border: none;
    }
    &>.container>.flex{
        flex-direction: column;
    }
    & .right_sec {
        padding: 0;
        width: 100%;
        margin-top: 1.4rem;
    }
}
`