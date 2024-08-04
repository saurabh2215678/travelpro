import styled from "vue3-styled-components";

export const SearchCountryFormWrapper = styled.section`
& .ssb-wrap {
    padding: 0;
}
& .book_flight_form form.flight_form {
    gap: 10px;
}
& .passenger-adults, & .passenger-child {
    display: flex;
    align-items: center;
    font-size: 0.8rem;
    border: 1px solid rgba(34, 36, 38, .15);
    border-radius: 5px;
    padding: 0.5rem 0.8rem;
    color: var(--black600);
    max-height: 2.63rem;
    position: relative;
}

& .passenger-adults i, & .passenger-child i {
    font-size: 1.2rem;
}
& .passenger-adults select, & .passenger-child select {
    padding: 0px 20px 0 10px;
}
& .passenger-adults span.err {
    position: absolute;
    left: 0;
    top: 40px;
    min-width: 175px;
}

`