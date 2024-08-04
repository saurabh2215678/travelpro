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
}
`