import styled from "vue3-styled-components";

export const TrendingThemeWrapper = styled.div`
position: relative;
& .trending_theme_bg{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top;
}
& .HomePackageSlider{
    position: relative;
    padding: 5rem 0;
    & .title_wrapper .title_left .title,
    & .title_wrapper .title_left .subtitle{
        color: var(--white);
    }
    & .title_wrapper .title_right .view_all{
        padding: 0.6rem 1.8rem;
        font-size: 1rem;
        border-radius: 5px;
        background-color: transparent;
        border: 1px solid;
        &:hover{
            background-color: var(--theme-color-dark);
            color: var(--white);
            border-color: transparent;
        }
    }
    & .PackageSliderCard{
        box-shadow: none;
    }
}

`