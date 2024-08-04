import styled from "vue3-styled-components";

export const BlogWrapper = styled.section`
padding: 1.3rem 0 2.6rem;
& .title{
    font-size: 1.54rem;
    font-weight: 600;
    margin-bottom: 1.2rem;
    color: var(--theme-color);
}
& .blog_sec {
    --sidebar-width: 13rem;
    display: flex;
    & .blogLeft {
        width: calc(100% - var(--sidebar-width));
        padding-right: 2rem;
    }
    & .blog_right {
        width: var(--sidebar-width);
        & .blog_categories{
            position: sticky;
            top: calc(var(--header-height) + 2rem);
        }
    }
}
& .pagination-wrapper{
    margin-top: 1.5rem;
     ul.pagination {
       padding-bottom: 1rem;
}

}
& .blogList {
    display: flex;
    flex-wrap: wrap;
    --gap: 0.75rem;
    margin: 0 calc(0px - var(--gap));
    & .blog_card {
        width: 25%;
        padding: 0 var(--gap);
        padding-bottom: var(--gap);
    }
}
@media (max-width: 800px){
    & .blogList {
        --gap: 0.6rem;
        & .blog_card {
            width: 33.33%;
        }
    }
}

@media (max-width: 700px){
    & .blogList .blog_card {
        width: 50%;
    }
}

@media (max-width: 600px){
    & .blog_sec{
        flex-direction: column;
        & .blogLeft{
            width: 100%;
            padding-right: 0;
        }
        & .blog_right{
            width: 100%;
            margin-top: 2rem;
        }
    }
}
`