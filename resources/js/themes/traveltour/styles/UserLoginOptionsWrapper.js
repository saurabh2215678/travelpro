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
& .user_login_list {
    position: absolute;
    top: 100%;
    background-color: var(--white);
    padding: 0.4rem 0;
    box-shadow: 2px 3px 9px #0000003d;
    border-radius: 9px;
    right: 0;
    transform: translateY(4rem);
    pointer-events: none;
    opacity: 0;
    transition: 0.5s;
    & a{
        display: block;
        padding: 0.2rem 1rem;
        min-width: 11rem;
        font-size: 0.85rem;
        &:hover{
            background-color: var(--theme-color60);
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