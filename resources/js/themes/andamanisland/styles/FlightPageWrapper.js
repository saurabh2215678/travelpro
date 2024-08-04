import styled from "vue3-styled-components";

export const FlightPageWrapper = styled.section`
& .flight-banner {
    background: url(/assets/traveltour/images/banner-search.jpg) no-repeat;
    background-size: cover;
    background-position: center;
    padding: 2.8rem 0;
}
& .head-search {
    background: var(--white800);
    border-radius: 7px;
    padding: 1rem 2rem;
    position: relative;
}
`