import styled from "vue3-styled-components";

export const WalletWrapper = styled.section`
& .user_profile_inner {
    column-gap: 3rem;
}
& .title-inner{
    background: #F3F4F6;
    padding: 0.5rem 1rem;
    margin-top: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    span{
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-color);
    }
    p{
        color: #222;
        font-weight: 400;
    }
}
& .wallet-wrapper table.table thead tr th {
    background: #01b3a71f;
    color: #545454;
    border: 0;
    border-top-color: currentcolor;
    border-top-style: none;
    border-top-width: 0;
    padding: 0.75rem;
    text-align: left;
    white-space: nowrap;
}

`