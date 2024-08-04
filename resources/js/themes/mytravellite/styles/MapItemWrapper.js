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
& .location {
    display: flex;
    flex-direction: column;
    justify-content: center;
    /* box-shadow: 0px 0px 11px 0px rgba(191,191,191,0.61); 
    border-radius: 14px;*/
    padding-left: 2rem;
    background: var(--secondary-color);
    color: #fff;
}
& .contact_info p {
    color: #fff;
    font-size: 1rem;
    padding-bottom: 1rem;
}
& .contact_info h5 {
    padding-bottom: 1rem;
    font-size: 1.6rem;
    font-weight: 500;
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
    & .location, & .map_item_right {
        width: 100%;
        padding: 2rem;
    }
}
`