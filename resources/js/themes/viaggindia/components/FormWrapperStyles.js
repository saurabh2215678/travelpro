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
    line-height: normal;
    padding: 0.6rem 3rem;
    background-color: var(--theme-color);
    margin: 0 5px;
    font-size: 1.125rem;
    font-family: 'Jost',sans-serif;
    font-weight: bold;
    border-radius: 40px;
}
& [datatypeinput] .input-group-addon{
    padding: 0 12px;
}
& .btn-book-space {
    text-align: left;
    width: 100%;
    margin-top: 0.6rem;
}
& .form-floating .form-control{background:transparent;border-color: #000;height:2.5rem;}
& .book-space select.form-select{background:transparent!important;border-color: #000;height:2.5rem;}
`