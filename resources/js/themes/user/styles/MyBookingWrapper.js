import styled from "vue3-styled-components";

export const MyBookingWrapper = styled.section`
& .user_profile_inner {
    column-gap: 3rem;
}

& .booking-lists thead tr th {
    border-right: 1px solid #dee2e6;
    background: var(--theme-color);
    color: #fff;
    text-align: left;
    font-size: 12px;
    line-height: normal;
}
& table tr td .user_paynow_btn{
    background: var(--theme-color);
    font-size: 0.75rem;
    white-space: nowrap;
    :hover{
        background: var(--secondary-color);
    }
}
& .totel_markup tr td {
    font-size: 0.8rem;
    padding: 0.6rem;
}


`