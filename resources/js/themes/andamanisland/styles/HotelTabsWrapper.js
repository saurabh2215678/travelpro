import styled from "vue3-styled-components";

export const HotelTabsWrapper = styled.section`
    padding-bottom: 5rem;
& .excontent {
    width: 100%;
    text-align: center;
    & .heading2 {
        font-weight: bold;
        margin-top: 0;
        margin-bottom: 1.25rem;
        font-size: 1.875rem;
        & strong {
            display: block;
            font-size: 1.25rem;
            font-weight: 400;
            color: #fe7524;
        }
    }
    & p{
        font-size: 1.125rem;
        max-width: 52.938rem;
        margin: auto;
        line-height: 1.3;
    }
    & a.readbtn {
        background: #ffd800;
        padding: 0.4rem 1.563rem;
        color: #000;
        display: inline-block;
        border-radius: 5rem;
        font-weight: 600;
        font-size: 1rem;
        margin-top: 0.6rem;
    }
}
& .tab_btns {
    text-align: center;
    border-bottom: 1px solid var(--black100);
    margin-top: 3rem;
    & button {
        padding: 1rem 1.6rem;
        font-size: 1.188rem;
        text-transform: uppercase;
        color: #282828;
        border-bottom: 3px solid transparent;
        &.active{
            border-color: var(--theme-color);
        }
    }
}
& .content_item{
    display: none;
    &.active{
        display: block;
    }
}
@media (max-width: 767px){
    & .tab_btns {
        & button{
            padding: 0.5rem 0.25rem;
            font-size: .95rem;
        }
    }

    
}


`