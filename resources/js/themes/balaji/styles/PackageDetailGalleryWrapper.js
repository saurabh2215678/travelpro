import styled from "vue3-styled-components";

export const DetailGalleryWrapper = styled.div`
& .gallery_box {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    grid-template-rows: repeat(2, 20rem);
    grid-gap: 0.5rem;

    & .gallery_item {
        &>a{
            display: block;
            width: 100%;
            height: 100%;
            border-radius: 5px;
            overflow: hidden;
            & img{
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        }
        &:nth-child(1){
            grid-column-start: 1;
            grid-column-end: 7;
            grid-row-start: 1;
            grid-row-end: 3;
        }
        &:nth-child(2){
            grid-column-start: 7;
            grid-column-end: 9;
            grid-row-start: 1;
        }
        &:nth-child(3){
            grid-column-start: 7;
            grid-column-end: 9;
            grid-row-start: 2;
        }
        &:nth-child(4){
            grid-column-start: 9;
            grid-column-end: 11;
            grid-row-start: 1;
        }
        &:nth-child(5){
            grid-column-start: 9;
            grid-column-end: 11;
            grid-row-start: 2;
        }
        &:nth-child(n + 6) {
            display: none;
        }
    }
}

@media (max-width: 767px){
    & .gallery_box {
    grid-template-rows: repeat(2, 8rem);
}
}
`