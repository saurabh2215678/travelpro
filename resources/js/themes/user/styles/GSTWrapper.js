import styled from "vue3-styled-components";

export const GSTWrapper = styled.section`
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
& .btn {
    background: var(--theme-color);
    color: #fff;
    font-size: 0.9rem;
    padding: 0.5rem 1.2rem;
    border-radius: 0.5rem;
    line-height: normal;
    :hover{
        background: var(--secondary-color);
        color: #fff !important;
    }
}
& .btn2 {
    background: var(--secondary-color);
    color: #fff;
    font-size: 0.9rem;
    padding: 0.5rem 1.2rem;
    border-radius: 0.5rem;
    border: 1px solid var(--secondary-color);
    line-height: normal;
    :hover{
        background: var(--theme-color);
        color: #fff !important;
    }
}
& .model {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 999;
    ::after{
        content: '';
        background-color: rgba(0,0,0,.5);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    .model-box {
        display: flex;
        height: 100%;
        width: 100%;
        align-items: center;
        justify-content: center;
}
    .model-box form {
        width: 30rem;
        padding: 1.5rem 2rem;
        background-color: #fff;
        position: relative;
        z-index: 1;
        border-radius: 0.5rem;
}
}
& .cross {
    padding: 0.5rem 0;
    position: absolute;
    right: 1rem;
    top: 0;
    svg {
    height: 1.5rem;
    cursor: pointer;
}
}

`