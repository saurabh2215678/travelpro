import styled from "vue3-styled-components";

export const AboutHomeWrapper = styled.section`
padding: 3rem 0 7rem;
& .about_content.collapsed>p {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
}
& .about_content.collapsed > div > div {
    visibility: hidden;
    height: 0;
}
& .about_page_title .btn{padding: 0.8rem 1.5rem;}
& .about_content > div > div {
    visibility: inherit;
    height: auto;
}
& .about_content {
    position: relative;
    &:after{
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(0deg, #f7f7f7 14%, transparent);
        z-index: 1;
    }
    &:not(.collapsed):after{
        display: none;
    }
    & .read_option i {
        transform: rotate(180deg);
    }
}
& .about_content.collapsed .read_option i {
    transform: inherit;
}

    & .read_option { 
        display: inline-block;
        color: var(--secondary-color);
        margin-top: 0.5rem;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.938rem;
        position: relative;
        z-index: 2;
        & i{
            margin-left: 0.4rem;
        }
    }

& .title_left_top{color: var(--orange); display: block;}
& .title_left {font-weight: 700;}
@media (max-width: 767px){
    padding: 2.5rem 0;
    & .title_left {font-size: 2rem;}
    & .about_page_title h3 {
    line-height: normal;
}

}
`