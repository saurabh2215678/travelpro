import styled from "vue3-styled-components";

export const AddressWrapper = styled.section`
    padding: 8rem 0 3.5rem;
    /* & .gray_add_box{
        background-color: #f1f1f1;
        padding: 2rem;
    } */
    @media (max-width: 992px){
        padding-top: 1rem;
        & .gray_add_box{
            padding: 1.1;
        }
    }
`