import styled from "vue3-styled-components";

export const PayOnlineFormWrapper = styled.div`
height: 100%;
display: flex;
align-items: center;
align-items: center;
background-color: var(--theme-color30);
justify-content: center;
padding: 2rem;
font-size: 0.94rem;
& .title{
    font-weight: 500;
    color: var(--theme-color);
}
& .form_control {
    display: block;
    width: 100%;
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    background:#fff;
    &:focus{
        outline:none;
    }
}
& .form-group.row {
    display: flex;
}
& .phone_wrap {
    display: flex;
}
& .country_code {
    width: 4.4rem!important;
    margin-right: 0.65rem;
    padding: 0 0.3rem;
    background: #efefef!important;
}
& .validation_error:empty {
    display: none;
}
& .form-group.row>div {
    padding: 0 0.35rem;
    padding-bottom: 0.5rem;
}
& .submit_btn button {
    padding: 0.5rem 1.2rem;
    background-color: var(--theme-color);
    color: var(--white);
    border-radius: 5rem;
    margin-top: 2rem;
    font-size: 0.94rem;
}
& .was-validated .form_control:invalid {
    border-color: #dc3545;
}
& .validation_error {
    font-size: 0.85rem;
    color: #dc3545;
}
& .form-group.row label{display:block!important;}
& .bg_blue{background: #efefef!important;}


`