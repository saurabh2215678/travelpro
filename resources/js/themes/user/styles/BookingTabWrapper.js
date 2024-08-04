import styled from "vue3-styled-components";

export const BookingTabWrapper = styled.div`
& .excontent .heading2 {
    background: #F3F4F6;
    padding: 0.5rem 1rem;
    margin-top: 0;
    font-size: 1.5rem;
    font-weight: 600;
}
& .tab_btns button.active {
    background: var(--secondary-color);
    font-weight: 700;
}
& .tab_btns button {
    background: var(--theme-color);
    padding: 0.5rem 2rem;
    margin-top: 1rem;
    color: #fff;
    margin-right: 0.2rem;
    border-radius: 4px 4px 0 0;
 :hover{
    background: var(--secondary-color);
 }
}
`