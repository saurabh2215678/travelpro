import styled from "vue3-styled-components";

export const OtpPageWrapper = styled.section`
    padding: 10rem 0 3rem;
    flex-grow: 1;
    display: flex;
    align-items: center;
    & .otpForm {
        max-width: 25rem;
        padding: 2rem;
        margin: 0 auto;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 0 17px 0 rgba(0,0,0,.09);
    }

    & .submit_btn {
        padding: 1rem 0;
        & .btn {
            color: var(--white);
            background: var(--theme-color);
            border-radius: 5rem;
            padding: 0.4rem 1.5rem;
            font-size: .8rem;
            margin-right: 0.45rem;
            transition: all ease 0.5s;
            &:hover{
                background-color: var(--secondary-color);
            }
            &:disabled{
                background-color: var(--black300);
                cursor: no-drop;
            }
        }
    }
    @media (max-width: 767px){
        padding: 6rem 0 3rem;

    
}
`