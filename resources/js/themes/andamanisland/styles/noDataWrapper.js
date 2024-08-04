import styled from 'vue3-styled-components';

export const NoDataWrapper = styled.section`
& .no_data_inner{
    border: 1px dashed var(--black200);
    border-radius: 8px;
    padding: 2rem;
    background-color: var(--theme-color20);
    &>svg{
        width: 6rem;
    }
}
& .text{
    text-align: center; 
    font-size: 1.1rem;
    & a{
        color: var(--theme-color);
    }
}

@media (max-width: 650px){
    & .no_data_inner>svg{
        width: 4rem;
        height: auto;
    }
}

`