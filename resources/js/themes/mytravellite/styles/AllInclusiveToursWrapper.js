import styled from "vue3-styled-components";

export const AllInclusiveToursWrapper = styled.section`
padding: 5rem 0;
& h3.text-center{
    line-height: 1.1;
    margin-bottom: 3rem;
}
& .svg_box {
    --size: 4.438rem;
    max-width: var(--size);
    max-height: var(--size);
    width: 100%;
    height: 100%;
    margin-right: 1.25rem;
}
& .box_content p {
    line-height: 1.25;
    margin-top: 0.2rem;
}
@media (max-width: 768px){
    padding: 3rem 0;
    & h3.text-center{
        text-align: left;
        & br{
            display: none;
        }
    }
}
`