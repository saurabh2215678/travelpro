import styled from "vue3-styled-components";

export const LoginWrapper = styled.section`
padding: 4rem 0 3rem;
& .loginForm {
    max-width: 25rem;
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
& .forgot_pass {
    font-size: 0.8rem;
    color: #3737b1;
    text-decoration: underline;
    margin-top: 8px;
    display: block;
}
& .submit_btn {
    padding: 1rem 0;
    & .btn {
        color: var(--white);
        background: var(--theme-color);
        border-radius: 5rem;
        padding: 0.4rem 1.5rem;
        font-size: .8rem;
    }
}
& .create_account a {
    color: #2196F3;
    /* text-decoration: underline; */
}
& .create_account a:hover{
    color: var(--theme-color);
    text-decoration: underline;
}

@media (max-width: 767px){
        padding: 6rem 0 3rem;

    
}
`