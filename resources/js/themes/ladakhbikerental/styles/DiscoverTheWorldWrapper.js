import styled from "vue3-styled-components";

export const DiscoverTheWorldWrapper = styled.section`
/* padding: 0 0 5.56rem 0; */
& .dvs_box {
    display: flex;
}

& .dvs_left {
    background: var(--white);padding: 3rem 4rem;box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    & .font26{
        line-height: 1;
        margin-bottom: 1rem;
    }
    & h3{
        border-bottom: 2px solid var(--theme-color);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        width: fit-content;
    }
    & h4.font16 {
        margin-top: 3.5rem;
    }
    & .telph {
        display: flex;
        align-items: center;
        white-space: nowrap;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--secondary-color);
        & svg {
            width: 1.1rem;
            margin-right: 0.5rem;
        }
    }
    & .btm_itemn {
        align-items: center;
        margin-top: 1.12rem;
        &>span {
            margin: 0 1rem;
            font-size: 1.1rem;
            font-weight: 600;
        }
    }

}
& .btn_theme{margin-top:3.75rem;}

& .dvs_right {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    display: flex;
    /* align-items: center;
    justify-content: center;
    padding-bottom: 3rem; */
    /* & .rght_img1 {
        margin-right: 1.6rem;
    } */

    & .rght_img2 {
        margin-bottom: -5rem;
    }
    &>img {
        width: 100%;
        height: 100%;
        max-height:500px;
        object-fit: cover!important;
    }
}
@media (max-width: 992px){
    & .dvs_right>img{
        /* width: calc(50% - 1rem); */
        height: 23.75rem;
    }
}
@media (max-width: 768px){
    padding: 3rem 0 4.5rem;
    /* & .dvs_right{
        display: none;
    } */
    & .dvs_left {
        width: 100%;
        padding: 1.5rem;
        order: 2;
        & h4.font16{
            margin-top: 1rem;
        }
    }
    & .dvs_box{display:flex;flex-direction:column;margin-top:2.5rem;}
    & .container{padding:0;}
}

`