import styled from "vue3-styled-components";

export const BlogDetailCardWrapper = styled.div`
&>img{
    width: 100%;
    min-height: 8rem;
    max-height: 28rem;
    object-fit: cover;
    border-radius: 8px;
}
& .author_and_date {
    display: flex;
    margin-top: 1.25rem;
    margin-bottom: 1rem;
    & .date_sec,
    & .author_sec{
        display: flex;
        align-items: center;
        margin-right: 1.4rem;
        & i{
            margin-right: 0.6rem;
            font-size: 0.85rem;
            color: var(--theme-color);
        }
    }
}
& .blog_categories{
    margin-bottom: 0.5rem;
    &>span {
        font-weight: 600;
        margin-right: 0.5rem;
    }
    &>a {
        margin-right: 0.5rem;
        padding: 0.12rem 0.8rem;
        background-color: var(--theme-color200);
        color: var(--theme-color);
        font-size: 0.75rem;
        border-radius: 5rem;
        font-weight: 600;
    }
}
& .blog_desc p{
     margin-bottom: 1rem;
    }
& .share_sec{
    &>.share{
        font-size: 1.12rem;
        text-transform: uppercase;
        line-height: 1;
        margin-top: 1.2rem;
    }
}

`;