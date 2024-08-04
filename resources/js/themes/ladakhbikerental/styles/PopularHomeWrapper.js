import styled from "vue3-styled-components";

export const PopularHomeWrapper = styled.div`
    position: relative;
    /* padding-top: 10rem; */
    /* margin-top: -11rem; */
    & .mounten {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        max-height: 100%;
        object-fit: cover;
        object-position: top center;
    }
@media (max-width: 768px){
    padding-top: 3rem;
    margin-top: -4rem;
}
`