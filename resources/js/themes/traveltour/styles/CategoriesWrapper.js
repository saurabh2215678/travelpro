import styled from "vue3-styled-components";

export const CategoriesWrapper = styled.div`
box-shadow: 4px 3px 13px #00000030;
border-radius: 8px;
& h4 {
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--theme-color);
    padding: 0.6rem 1rem;
    border-bottom: 1px dashed var(--black100);
}
& ul{
    padding-bottom:1.2rem;
    max-height: calc(100vh - 12rem);
    overflow: auto;
    padding-top: 0.5rem;
    & li a {
        display: block;
        padding: 0.2rem 1.1rem;
        font-size: 0.9rem;
        &:hover{
            background-color: var(--theme-color50);
        }
    }
}
& *::-webkit-scrollbar {
    width: 6px;
}

& *::-webkit-scrollbar-track {
background: #f1f1f1; 
}

& *::-webkit-scrollbar-thumb {
background: #cecece; 
}


& *::-webkit-scrollbar-thumb:hover {
background: var(--theme-color); 
}
@media (max-width: 600px){
    & ul{
        display: flex;
        flex-wrap: wrap;
    }
}
`
