import styled from "vue3-styled-components";

export const formWrapper = styled.div`
& .common_main_form{
    display: flex;
    flex-wrap: wrap;
}
& .book-space select.form-select.country_code {
    width: 4rem;
    padding: 0.4rem;
}
& .phone_code {
    & label {
        display: block;
        width: 100%;
    }
    & .form-control.phone {
        width: calc(100% - 4.5rem);
    }
}
& .form-floating {
    padding: 0 0.3rem;
    margin: 0;
    flex-grow: 1;
    padding-bottom: 0.5rem;
}
& .form-floating .form-control::placeholder {
  color: #222;
}
& .btn-book-space .btnSubmit, 
& .btn-book-space .btnReset {
    border: 0;
    border-radius: 4px;
    color: #fff;
    font-size: .8rem;
    line-height: normal;
    padding: 0.5rem 1rem;
    background-color: var(--theme-color);
    margin: 0 5px;
}
& [datatypeinput] .input-group-addon{
    padding: 0 12px;
}
& .btn-book-space {
    text-align: center;
    width: 100%;
    margin-top: 0.6rem;
}
`