const { default: styled } = require("vue3-styled-components");

export const UserLoginOptionsWrapper = styled.div`
position: relative;
max-width: 9rem;
cursor: pointer;
&>.flex{
    & .text-xs{
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
    }
}
&.user_login .user_login_list {
    position: absolute;
    top: 100%;
    background-color: var(--white);
    /* padding: 0.4rem 0; */
    box-shadow: 4px 6px 12px #0000003b;
    /* border-radius: 9px; */
    right: 0;
    transform: translateY(4rem);
    pointer-events: none;
    opacity: 0;
    transition: 0.5s;
    z-index: 99;
    & a{
        display: block;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        min-width: 12rem;
        background: transparent;
        font-weight: normal !important;
        border: 0;
        color: inherit;
        &::before{
        content: "";
        display: block;
        position: absolute;
        left: 50%;
        right: 50%;
        bottom: 0;
        height: 2px;
        background: #ffd800;
        -webkit-transition: 0.3s;
        transition: 0.3s;
        }
        &:hover{
            background-color: var(--theme-color60);
            border-radius: 0;
        }
    }
}
&:hover{
    & .user_login_list {
        transform: translateY(0);
        opacity: 1;
        pointer-events: all;
    }
}
`