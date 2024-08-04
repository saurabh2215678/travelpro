import styled from "vue3-styled-components";

export const TestimonialPageWrapper = styled.section`
padding: 3.5rem 0;
& .title{
    font-size: 1.54rem;
    font-weight: 600;
    color: var(--theme-color);
}
& .gray_add_box{
    background-color: #f1f1f1;
    padding: 2rem;
}
& .testimonialList {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -0.7rem;
    & .testimonial_card{
        width: 25%;
        padding: 0.7rem;
    }
}
& .write_testimonial_btn {
    text-align: center;
    margin-top: 2rem;
    & a {
        background-color: var(--theme-color);
        padding: 0.7rem 1.5rem;
        color: var(--white);
        border-radius: 8px;
    }
}
@media (max-width: 767px){
    & .testimonialList .testimonial_card{
        width: 33.33%;
    }
}

@media (max-width: 620px){
    & .testimonialList .testimonial_card{
        width: 50%;
    }
}
`