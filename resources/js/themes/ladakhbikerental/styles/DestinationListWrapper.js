import styled from "vue3-styled-components";

export const DestinationListWrapper = styled.section`
& .theme-title {
    margin-top: 2rem;
    & .title {
        font-size: 1.6rem;
        font-weight: 600;
        color: var(--theme-color);
        margin-bottom: 0.4rem;
    }
}
& .theme_listing .tour_info p {
    color: #fff;
}
@media (max-width: 767px){
    &.destination-cat .theme_listing a.tour_category_box {
        height: 15rem;
        & .text{
            padding: 1.3rem 1rem;
            & span {
                font-size: 1.3rem;
            }
        }
    }
    & .tour_info p {
        font-size: 0.85rem;
    }
}
@media (max-width: 700px){
    &.recommendation_places.destination-cat .theme_listing{
        margin: 0 -0.4rem;
        margin-top: 1rem;
        & li{
            width: 50%;
            padding: 0 0.4rem;
            margin-bottom: 0.8rem;
        }
    }
}
`