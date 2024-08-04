import styled from "vue3-styled-components";

export const TestimonialAddWrapper = styled.section`
& .title{
    font-size: 1.54rem;
    font-weight: 600;
    margin-bottom: 1.2rem;
    color: var(--theme-color);
}
@media (max-width: 430px){
    & .page_top{
        flex-direction: column-reverse;
    }
    & .breadCrumb{
        margin-left: auto;
    }
}
`;