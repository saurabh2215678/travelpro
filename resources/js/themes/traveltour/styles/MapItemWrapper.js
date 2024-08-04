import styled from "vue3-styled-components";

export const MapItemWrapper = styled.div`
display: flex;
& iframe{
    min-height: 100%;
}
@media (max-width: 992px){
    flex-direction: column;
    & .map_item_left{
        margin-bottom: 1.1rem;
    }
}
`