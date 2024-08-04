import styled from "vue3-styled-components";

export const AgentSignupWrapper = styled.section`
padding: 3rem 0;
& .signupForm {
    max-width: 35rem;
    padding: 2rem;
    margin: 0 auto;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 0 17px 0 rgba(0,0,0,.09);
    &>a{
        background: 0 0;
        color: #5ca1ff;
        text-transform: none;
        width: 16rem;
        display: block;
        box-shadow: 0px 3px 3px 0px rgba(64,60,67,.16);
        padding: 10px;
        text-align: center;
        border-radius: 5px;
        margin: 0 auto;
        border: 1px solid var(--black60);
        &>img{
            width: 2rem;
            display: inline-block;
            margin-right: 0.3rem;
        }
    }
}
& .border_line {
    text-align: center;
    position: relative;
    padding-bottom: 0.2rem;
    border: 0;
    font-size: .8rem;
    margin-top: 1rem;
    &:before,
    &:after{
        content: '';
        position: absolute;
        width: 3rem;
        background: #ddd;
        height: 2px;
        top: 0.5rem;
    }
    &:before{
        right: 0;
    }
    &:after{
        left: 0;
    }
}
& .create_account {
    padding: 0 0.35rem;
    width: 100%;
    & a {
        color: var(--theme-color);
        text-decoration: underline;
    }
}
& .phone_group {
    display: flex;
    flex-wrap: wrap;
    & label {
        width: 100%;
        line-height: 1.7;
    }
    & select{
        width: 5rem;
    }
    & input{
        width: calc(100% - 5.4rem);
        margin-left: auto;
    }
}
& .singup_form_inner {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -1rem;
}
& .form_group {
    padding: 0 0.35rem;
    margin-bottom: 0.6rem;
}
& .termsuse{
    padding: 0 0.35rem;
    margin-top: 1rem;
}
& .submit_btn {
    padding: 1rem 0.35rem;
    width: 100%;
    & .btn {
        color: var(--white);
        background: var(--theme-color);
        border-radius: 5rem;
        padding: 0.4rem 1.5rem;
        font-size: .8rem;
    }
}
`