import styled from "vue3-styled-components";

export const DiscoverTheWorldWrapper = styled.section`
padding: 5.56rem 0;
& .dvs_box {
    display: flex;
}

& .dvs_left {
    width: calc(50% - 3rem);
    & .font26{
        line-height: 1;
        margin-bottom: 1rem;
    }
    & p{
        color: var(--black);
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

& .dvs_right {
    width: calc(50% + 3rem);
    display: flex;
    align-items: center;
    justify-content: center;
    padding-bottom: 3rem;
    & .rght_img1 {
        margin-right: 1.6rem;
    }

    & .rght_img2 {
        margin-bottom: -5rem;
    }
    &>img {
        width: 18.625rem;
    }
}
@media (max-width: 992px){
    & .dvs_right>img{
        width: calc(50% - 1rem);
    }
}
@media (max-width: 768px){
    padding: 3rem 0 4.5rem;
    & .dvs_right{
        display: none;
    }
    & .dvs_left {
        width: 100%;
        & h4.font16{
            margin-top: 1rem;
        }
    }
}

`