import styled from "vue3-styled-components";

export const BlogDetailWrapper = styled.section`
padding: 1.3rem 0 2.6rem;
& .title{
    font-size: 1.54rem;
    font-weight: 600;
    margin-bottom: 1.2rem;
    color: var(--theme-color);
}
& .breadCrumb{
    align-self: flex-start;
    max-width: 36%;
}
& .blog_detail_wrap {
    --sidebar-width: 28rem;
    display: flex; 
    & .blog_detail_left {
        width: calc(100% - var(--sidebar-width));
        padding-right: 1.5rem;
        border-right: 1px solid var(--black100);
    }
    & .blog_detail_right {
        width: var(--sidebar-width);
        padding-left: 1.5rem;
    }
}
& .blog_detail_right{
    & .great_deal_slider{
        margin-bottom: 2rem;
    }
}
& .blog_slider_wrapper{
    margin-top:3rem;
}
& .blog_detail_wrap .blog_detail_left .slider a.featured_content {
    pointer-events: inherit;
    .pack_day_night {
        font-weight: 600;
        color: var(--black700);
        margin: -1.4rem 1rem;
        padding: 0.4rem 1.1rem;
        margin-bottom: 1rem;
        background-color: var(--white);
        border-radius: 4px;
        box-shadow: 0px 0px 22px 0px rgba(0,0,0,0.09);
        position: relative;
}
}
& .blog_detail_wrap .blog_detail_left .slider a.package_info{
    background: #fff;
    padding: 1.25rem;
    padding-top: 0;
    -webkit-box-flex: 1;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    .price {
    color: #3a4652;
    font-weight: 700;
    font-size: 1rem;
    text-align: left;
}
.amount{
    color: var(--theme-color);
}
}
& .slider .swiper-slide .featured_box a.featured_content .title {
    line-height: 1.2;
    font-weight: 600;
    font-size: 1.06rem;
    margin-bottom: 0.5rem;
    color: #3a4652;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
& .slider .featured_slider .swiper-slide > .featured_box {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0px 0px 11px 0px rgba(191,191,191,0.61);
    height: 100%;
    opacity: 1;
    pointer-events: all;
    margin-bottom: 2rem;
   .package_tour_type_text{
    display: none;
    }
    
}
& .blog_detail_left_inner .slider .slider_btns {
    width: auto;
    margin: 0;
    -webkit-transform: translateY(0);
    -ms-transform: translateY(0);
    transform: translateY(0);
    margin-left: 1rem;
    position: absolute;
    top: 10%;
    display: flex;
    justify-content: space-between;
    right: 0px;
    width: calc(100% + -44rem);
    left: inherit;
    margin: 0 auto;
    z-index: 9;
}
& .blog_detail_left_inner .slider .slider_btns>* {
    width: 2.2rem;
    height: 2.2rem;
    background-color: var(--theme-color);
    display: grid;
    place-items: center;
    border-radius: 50%;
    pointer-events: all;
    -webkit-transition: all ease 0.5s;
    transition: all ease 0.5s;
}
& .blog_detail_left_inner .slider .slider_btns>* img {
    width: 0.5rem;
    filter: invert(1);
}

@media (max-width: 865px){
    & .blog_detail_wrap {
        flex-direction: column;
        & .blog_detail_left {
            width: 100%;
            padding-right: 0;
            border-right: none;
        }
        & .blog_detail_right {
            width: 100%;
            padding-left: 0;
            margin-top: 2.4rem;
        }
    }
    & .title{
    font-size: 1.24rem;
}
    & .title_bar {
    display: flex;
    flex-direction: column-reverse;
}
& .breadCrumb {
    max-width: 100%;
}

}
`