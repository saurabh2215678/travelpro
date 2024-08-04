import styled from "vue3-styled-components";

export const HomeAboutUsWrapper = styled.section`
padding: 2.56rem 0 7rem;
background: url(/images/Vector-Smart-Object.png), no-repeat;
background-repeat: no-repeat;
background-position: bottom left;
background-size: 400px;

& .videotxt {
    display: flex;
    justify-content: center;
    align-items: center;
    img.rght_img1{
        margin: 0 auto;
    }
    h3 {
    background: var(--secondary-color);
    display: inline;
}
.right-txt {
    max-width: 28rem;
    margin-left: auto;
}
}

& .dvs_left {
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
        margin-top: 35px;
        & h4.font16{
            margin-top: 1rem;
        }
    }
    & .videotxt h3 {
    font-size: 45px;
}
& .videotxt .right-txt .font16 {
    font-size: 20px;
}
& .videotxt{
    flex-direction: column-reverse;
}
& .videotxt .right-txt {
    padding-bottom: 15px;
    max-width: 100%;
}
& .expimg {
    width: 100%;
    a {
    width: 100%;
    img {
    width: 100%;
}
}
}


}

`