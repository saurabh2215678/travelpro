import styled from "vue3-styled-components";

export const HomePageFormWrapper = styled.form`
    position:relative;
    gap: 2.875rem;
    display: flex;
    align-items: center;
    padding: 0 2.875rem;
& .wrap_left, 
& .wrap_right {
    flex-grow: 1;
    background-color: var(--white);
    border: 1px solid var(--black200);
    padding: 1.5rem;
    border-radius: 1.34rem;
}
& .searchbtn>button {
    width: 6.75rem;
    height: 6.75rem;
    background-color: var(--theme-color);
    border-radius: 2.063rem;
    color: var(--white);
    font-size: 2.6rem;
    transition : 0.5s;
    &:hover {
        background-color : var(--secondary-color);
    }
    & span{
        display : none;
    }
}
& .plane_box {
    width: 2.5rem;
    height: 2.5rem;
    background-color: var(--white);
    padding: 0.5rem;
    border: 1px solid var(--black200);
    border-radius: 50%;
    margin: 0 -2.5rem;
}
& .opt_wrap {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    padding-left: 0.85rem;
    position: relative;
}

& .selectoption {
    display: flex;
    flex-grow: 1;
}

& .wrap_right {
    display: flex;
}
& .opt_wrap>label {
    font-weight: 700;
    font-size: 1.188rem;
    color: var(--black800);
}
& select {
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
}
& select:focus, 
& select:focus-visible, 
& input:focus, 
& input:focus-visible{
    outline:none;
}
& .card_icon {
    width: 3rem;
    height: 3.875rem;
    background-color: var(--black100);
    border-radius: 5rem;
    display: grid;
    place-items: center;
}
& .form_left, 
& .form_right {
    position: absolute;
}
& .form_left{
    left: -8rem;
    bottom: 5rem;
    width: 8.188rem;
}
& .form_right{
    right: -3rem; 
    top: -3rem;
    width: 3.375rem;
}
@media (max-width: 1100px){
    & .form_right{
        display:none;
    }
}
@media (max-width: 992px){
    flex-direction: column;
    gap: 1.3rem;
    & .wrap_left,
    & .wrap_right{
        width: 100%;
    }
    & .plane_box{
        display: none;
    }
    & .searchbtn{
        width: 100%;
        &>button {
            height: auto;
            border-radius: 0.7rem;
            padding: 0.5rem 0.5rem;
            width: 100%;
            font-size: 1.5rem;
            & span{
                display: block;
            }
            & i{
                display: none;
            }
        }
    }
}
@media (max-width: 767px){
    padding: 0;
    & .wrap_right {
        flex-direction: column;
        & .selectoption:first-child{
            margin-bottom : 2rem;
        }
    }
    & .wrap_left, 
    & .wrap_right{
        padding: 0.8rem;
        border-radius: 10px;
    }
}
`;