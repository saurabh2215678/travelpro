import styled from "vue3-styled-components";

export const formWrapper = styled.div`
& .common_main_form{
    display: flex;
    flex-wrap: wrap;
}
& .book-space select.form-select.country_code {
    width: 4.5rem;
    padding: 0.4rem;
}
& .phone_code {
    & label {
        display: block;
        width: 100%;
    }
    & .form-control.phone {
        width: calc(100% - 5rem);
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
    background-color: var(--theme-color); color: #fff; padding: 0.6rem 1.6rem; font-size: 1.063rem; border: 1px solid transparent; position: relative;
    :after{content: ''; position: absolute; top: 0; left: 0; width: 0; height: 100%; background-color: rgba(255, 255, 255, 0.4); transition: none;}
}

& .btn-book-space .btnReset:hover{
    :after{
        width: 100%;background-color: rgba(255, 255, 255, 0);transition: all 0.6s ease-in-out;
    }
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