import styled from "vue3-styled-components";

export const ContactFormWrapper = styled.section`
padding-bottom: 4rem;
& .form_box {
    max-width: 45rem;
    margin: auto;
    padding: 2rem;
    box-shadow: 3px 5px 19px #00000030;
    border-radius: 8px;
    & h4{
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--theme-color);
        text-align: center;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }
}
@media (max-width: 992px){
    & .form_box{
        padding: 1.6rem 1rem;
    }
}
`