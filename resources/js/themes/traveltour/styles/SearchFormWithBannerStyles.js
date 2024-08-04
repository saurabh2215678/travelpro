import styled from 'vue3-styled-components';
export const SectionWrapper = styled.section`
    position: relative;
    padding: 3rem;
    background-color: var(--black);
    & .inner_banner {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.79;
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
    @media (max-width: 650px){
        padding: 3rem 1rem;
        & .package_form>.flex,
        & .activity_form>.flex{
            flex-direction: column;
        }
    }
`
