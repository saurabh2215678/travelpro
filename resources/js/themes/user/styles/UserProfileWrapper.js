import styled from "vue3-styled-components";

export const UserProfileWrapper = styled.section`
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
& .edit_btn {
    background: var(--theme-color);
    color: #fff;
    padding: 0.3rem 1.2rem;
    border-radius: 0.5rem;
    :hover{
        background: var(--secondary-color);
    }
}
& .profile_info_list {
    margin-top: 0.2rem;
    background: #ececec;
    padding: 1rem;
}

`