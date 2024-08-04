import styled from "vue3-styled-components";

export const PackageWithTypeCardWrapper = styled.div`
.swiper-slide &.blog_card {
    height: 100%;
    & .blog_card_inner{
        height: 100%;
    }
}
&>a{
    display:block;
}
& .package_type {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--theme-color);
}
&>a>img{
    height: 13rem;
    width: 100%;
    object-fit: cover;
}
& .package_title {
    font-weight: 600;
    color: var(--theme-color);
    margin-top: 0.5rem;
}
& .package_price {
    font-size: 0.8rem;
    & strong{
        color: var(--theme-color);
    }
}
`