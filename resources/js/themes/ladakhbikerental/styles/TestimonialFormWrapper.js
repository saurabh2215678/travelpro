import styled from "vue3-styled-components";

export const TestimonialFormWrapper = styled.div`
& .form-floating label{
    display: none;
}
& .validation_error:empty{display:none;}
& .form-floating .form-control{
    display: block;
    margin-top: 0.5rem;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: .8rem;
    font-weight: 400;
    line-height: 1.5;
    color: #222;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
    height: calc(0.9em + 0.75rem + 10px);
}
& .form-floating{
    padding: 0 10px;
}
& .custom-btn{
    background-color: var(--theme-color);
    padding: 0.5rem 2rem;
    border-radius: 50px;
    color: var(--white);
    font-size: .8rem;
}
@media (max-width: 620px){
    & .form-floating {
        padding: 0 3px;
        & .form-control{
            margin-top: 0;
        }
    }
}
`;