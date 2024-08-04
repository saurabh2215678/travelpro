import styled from "vue3-styled-components";

export const HotelDetailGalleryWrapper = styled.section`
& .gallery_box {
    display: grid;
    grid-template-columns: repeat(8,1fr);
    grid-template-rows: repeat(2,13.5rem);
    grid-gap: 0.5rem;
    & li:nth-child(1) {
        grid-column-start: 1;
        grid-column-end: 5;
        grid-row-start: 1;
        grid-row-end: 3;
    }
    & li:nth-child(2) {
        grid-column-start: 5;
        grid-column-end: 7;
        grid-row-start: 1;
    }
    & li:nth-child(3) {
        grid-column-start: 5;
        grid-column-end: 7;
        grid-row-start: 2;
    }
    & li:nth-child(4) {
        grid-column-start: 7;
        grid-column-end: 9;
        grid-row-start: 1;
    }
    & li:nth-child(5) {
        grid-column-start: 7;
        grid-column-end: 9;
        grid-row-start: 2;
    }
    & li:nth-child(n + 6) {
        display: none;
    }
    & li a{
        display: block;
        height: 100%;
        & img{
            height: 100%;
            width: 100%;
            object-fit: cover;
        }
    }
}
@media (max-width: 540px){
    & .gallery_box {
    grid-template-rows: repeat(2,6.5rem);
}

}

`