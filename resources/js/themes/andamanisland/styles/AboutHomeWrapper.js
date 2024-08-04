import styled from "vue3-styled-components";

export const AboutHomeWrapper = styled.section`
padding: 4.5rem 0;
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
& .about_content > div > div {
    visibility: inherit;
    height: auto;
}
& .about_content .read_option i {
    transform: rotate(180deg);
}
& .about_content.collapsed .read_option i {
    transform: inherit;
}

    & .read_option { 
        display: inline-block; 
        color: #333333; 
        margin-top: 0.5rem; 
        cursor: pointer;
        text-transform: uppercase;
        text-decoration: underline;
        & i{
            margin-left: 0.4rem;
        }
    }

& .title_left_top{color: var(--orange); display: block;}
& .title_left {color: #0c2f64; display: block;}
@media (max-width: 767px){
    padding: 2.5rem 0;
    & .title_left {font-size: 2rem;}
}
`