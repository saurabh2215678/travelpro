import styled from "vue3-styled-components";

export const TestimonialCardWrapper = styled.div`
&>a{
    box-shadow: 5px 9px 12px #00000030;
    border-radius: 8px;
    overflow: hidden;
    display: block;
    height: 100%;
    &>img{
        width: 100%;
        height: 15rem;
        object-fit: cover;
        background-color: #999999;
    }
    & .card_info {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        font-size: 0.85rem;
    }
    & .testimonial_author {
        display: flex;
        align-items: center;
        margin: 0 0 0.7rem;
        & img {
            width: 2rem;
            height: 2rem;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 0.8rem;
        }
        & p {
            font-weight: 600;
            color: var(--theme-color);
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            line-clamp: 1;
            -webkit-box-orient: vertical;
        }
    }
}
& .testimonial_bottom {
    padding: 1.3rem 1.5rem 1rem;
}
@media (max-width: 620px){
    &>a>img{
        height: 11rem;
    } 
}
`