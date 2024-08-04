import styled from "vue3-styled-components";

export const ThingsWrapper = styled.section`
& .tings_box{
    display: flex;
    min-height: 22.625rem;
    overflow: hidden;
    border-radius: 11px;
}
& .left_img {
    width: calc(100% - 29.875rem);
    position: relative;
    & img{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
}
& .right_txt {
    width: 29.875rem;
    background-color: var(--theme-color);
    color: var(--white);
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 4rem;

    & h2 {
        text-transform: uppercase;
        line-height: 1;
    }
    & .font54 {
        line-height: 1.2;
        margin-bottom: 1rem;
    }
    & .font18{
        color: var(--white);
    }
    & a{
        border: 1px solid;
        align-self: flex-start;
        padding: 0.6rem 1.8rem;
        font-size: 1rem;
        border-radius: 5px;
        margin-top: 1.5rem;
        &:hover{
            background-color: var(--theme-color-dark);
            color: var(--white);
            border-color: transparent;
        }
    }
}
@media (max-width: 768px){
    & .right_txt {
        padding: 2rem;
        width: 100%;
        & .font26{
            font-size: 1.3rem;
        }
        & .font54{
            margin-bottom: 0.45rem;
            font-size: 2.5rem;
        }
        & .font18{
            font-size: 1.05rem;
            line-height: 1.3;
        }
        & a{
            margin-top: 1rem;
        }
    }
    & .tings_box{
        min-height: initial;
        flex-direction: column;
        & .left_img{
            width: 100%;
            & img{
                position: static;
            }
        }
    }
}
`