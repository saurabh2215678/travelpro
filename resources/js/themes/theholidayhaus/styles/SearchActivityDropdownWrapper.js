import styled from "vue3-styled-components";

export const SearchActivityDropdownWrapper = styled.div`
    position: absolute;
    top: 100%;
    width: 100%;
    background-color: var(--white);
    box-shadow: -2px 8px 25px #00000042;
    z-index: 4;
    border-radius: 10px;
    overflow: hidden;
    &>ul {
        max-height: 9rem;
        overflow: auto;
        padding: 0.5rem 0;
        &:empty{
            display: none!important;
        }
        & li{
            cursor: pointer;
            padding: 0.3rem 1rem;
            display: flex;
            align-items: center;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            line-clamp: 1;
            -webkit-box-orient: vertical;
            &:hover{
                background-color: var(--theme-color40);
            }
            & .icon_img{
                width: 1.1rem;
                margin-right: 0.5rem;
                display: none;
            }
            & i{
                margin-right: 0.5rem;
                color: var(--theme-color800);
            }
        }
    }
    @media (max-width: 650px){
        &>ul{
            max-height: 14rem;
        }
    }
`