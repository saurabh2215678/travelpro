import styled from 'vue3-styled-components';
export const SectionWrapper = styled.section`
    position: relative;
    height: 15.5rem;
    margin-top: 0;
    background-color: #b3b3ca;
    & .inner_banner {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%;
        object-fit: cover;
        opacity: 0.79;
    }
    &.homepageBanner {
        padding-bottom: 6.5rem;
        padding-top: 25rem;
        margin-top: 0;
        height: 40.5rem;
        & .inner_banner{
            opacity: 1;
        }
    }
    & .slider_section{
        position: absolute;
        z-index: 4;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        width: 100%;
    }
    
    &>div{
        position: relative;
        z-index: 4;
        & h3{
            display: none;
        }
    }
    &.has_title:before {
        content: attr(data-title);
        color: var(--white);
        z-index: 1;
        position: relative;
        max-width: 81.875rem;
        width: 100%;
        display: block;
        margin: auto;
        padding-left: var(--container-gap);
        padding-right: var(--container-gap);
        margin-bottom: 0.5rem;
        font-size: 1.8rem;
        font-weight: 600;
    }

    & .slider-caption {
        font-size: 2.25rem;
        font-weight: 400;
        color: var(--white);
        line-height: 1.3;
        & .slider_heading {
            line-height: 1.15;
            margin-bottom: 1.1rem;    
            max-width: 44rem;
        }
        & .slider_para {
            font-size: 1.1rem;
            line-height: 1.43;
            max-width: 50rem;
        }
    }
    
    
    @media (max-width: 767px){
        padding: 7rem 1rem 3rem;
        & .slider-caption {
            font-size : 1.85rem;
        }
        & .package_form>.flex,
        & .activity_form>.flex{
            flex-direction: column;
        }
    }
`
