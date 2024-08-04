import styled from "vue3-styled-components";

export const HomeSearchWrapper = styled.section`
    background-color: var(--black30);
    padding: 6rem 0;
& .home_search_top {
    text-align: center;
    padding-bottom: 2.6rem;
}
& .section_title .title_small{
    text-transform: uppercase; 
    letter-spacing: 4px;
}
& .search_packages {
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
            &:hover{
                background-color:var(--theme-color40);
            }
            & i{
                margin-right: 0.5rem;
                color: var(--theme-color800);
            }
        }
    }
}
@media (max-width: 992px){
    padding: 4rem 0;
    & .home_search_top {
        padding-bottom: 1rem;
    }
}
@media (max-width: 767px){
    padding: 2.5rem 0;
    & .section_title .title_small{
        letter-spacing: 3px;
    }
}
`;