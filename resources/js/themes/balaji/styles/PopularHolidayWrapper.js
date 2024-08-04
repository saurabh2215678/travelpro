import styled from "vue3-styled-components";

export const PopularHolidayWrapper = styled.section`
& .section_title{padding: 5rem 0 2.4rem;}
& .section_content a { 
    height: 20.875rem; 
    width: 100%; 
    position: relative;
    & span {
        position: absolute;
        bottom: 0.8rem;
        left: 0.8rem;
        background-color: var(--white);
        padding: 0.5rem 0.8rem;
        border-radius: 4px;
        font-size: 0.83rem;
        font-weight: 500;
        line-height: 1;
        color: var(--black);
        max-width: calc(100% - 2rem);
    }
    & img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
    }
}

@media (max-width: 767px){
    & .section_title {
        padding-bottom: 0;
    }
}
`