import styled from "vue3-styled-components";

export const ContactFormWrapper = styled.section`
padding-bottom: 4rem;
& .form_box {
    max-width: 51rem;
    margin: auto;
    padding: 2rem;
    box-shadow: 0px 0px 11px 0px rgba(191,191,191,0.61);
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
& .form-floating .form-control {
    font-size: 1rem;
    height: calc(1.2em + 0.75rem + 10px);
    box-shadow: 0 0 7px inset #e0e0e0;
}
& .book-space select.form-select {
    color: #222222;
    font-size: 1rem;
    height: calc(1.2em + 0.75rem + 10px);
    box-shadow: 0 0 7px inset #e0e0e0;
}
@media (max-width: 992px){
    & .form_box{
        padding: 1.6rem 1rem;
    }
}
@media (max-width: 767px){
    & .book-space select.form-select.country_code {
    width: 6rem;
}
& .phone_code .form-control.phone {
    width: calc(100% - 6.5rem);
}

}
`