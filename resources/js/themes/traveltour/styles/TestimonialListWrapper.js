import styled from "vue3-styled-components";

export const TestimonialListWrapper = styled.section`
padding: 3rem 0;
& .customer_img {
    width: 4.5rem;
    height: 4.5rem;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 1rem;
    & img{
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
}
& .top_title{
    font-size: 1.5rem;
    font-weight: 600;
    font-family: 'PT Serif',serif;
    color: var(--theme-color);
    text-transform: uppercase;
    margin-bottom: 1.8rem;
    &:after{
        content: "";
        display: block;
        background-color: var(--secondary-color);
        height: 3px;
        width: 40px;
    }
}
& .testimonial_list_outer{
    /* column-count: 2; 
    margin-bottom: -2rem;*/
    & .testimonial-item {
        margin-bottom: 2.2rem;
        -webkit-column-break-inside: avoid;
        page-break-inside: avoid;
        break-inside: avoid;
        & .bg_tesi_box {
            padding: 2rem 2.5rem;
            border-radius: 0.9rem;
            box-shadow: 1px 6px 16px #0000001c;
            font-size: 0.85rem;
            border: 1px solid var(--black60);
        }
        & .quotes{
            font-size: 3rem;
            color: var(--theme-color);
        }
    }
}
@media (max-width: 767px){
    & .testimonial_list_outer{
        column-count: 1;
    }
}
`