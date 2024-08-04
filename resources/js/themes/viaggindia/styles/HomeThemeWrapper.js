import styled from 'vue3-styled-components';

export const HomeThemeWrapper = styled.section`
    &.theme_sec{padding: 7.188rem 0;background:url(../images/theme_bg.jpg);background-size:cover;background-position: bottom;}
    & .tour_category_box .images{height:28.125rem;}
    & .tour_category_box .images img{height:100%;object-fit:cover;border-radius:10px;}
    & .tour_category_box{position:relative;width:100%;}
    & .tour_category_box .featured_content {position:absolute;bottom:0;width:100%;background-image: linear-gradient(0deg,#0000009e,#00000073,transparent);border-bottom-right-radius: 10px;
    border-bottom-left-radius: 10px;}
    & .tour_category_box .featured_content h5{font-size: 22px;font-family: 'Jost',sans-serif;color: rgb(255, 255, 255);font-weight: 500;}


& .slider_btns {
    display: flex; -webkit-box-align: center; align-items: center; margin-left: auto; justify-content: space-between; position: absolute; width: calc(100% + 12rem); left: -6rem; top: 50%; transform: translateY(-50%);
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



    @media (max-width: 767px){
        &.theme_sec{padding: 3.188rem 0;}
        & .tour_category_box {
            .images{height: 22.875rem;}
            .featured_content {
                h5{font-size: 17px;padding: 1rem;}
            }
        }
    }


` 