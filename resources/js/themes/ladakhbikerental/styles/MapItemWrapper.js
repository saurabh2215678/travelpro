import styled from "vue3-styled-components";

export const MapItemWrapper = styled.div`
display: flex;
& .location h4 {
    font-size: 2rem;
    font-weight: 600;
}
& .location ul.footer_contact li {
    padding: 0.5rem 0;
}
& .location .contact_info{
    display: flex;
    flex-direction: column;
    justify-content: center;
    /* box-shadow: 0px 0px 11px 0px rgba(191,191,191,0.61); */
    padding-left: 2rem;
    /* border-radius: 14px; */
    height: 100%;
    background: #f6f6f6;
}

& iframe{
    min-height: 100%;
}
@media (max-width: 992px){
    flex-direction: column;
    & .map_item_left{
        margin-bottom: 1.1rem;
    }
}
@media (max-width: 767px){
    padding-top:0rem;
    & .location, & .map_item_right {
        width: 100%;
    }
    & .location .contact_info{
    padding: 2rem;
}

}
`