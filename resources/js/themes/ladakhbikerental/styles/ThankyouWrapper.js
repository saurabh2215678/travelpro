import styled from "vue3-styled-components";
export const ThankyouWrapper = styled.section`
flex-grow: 1;
display: flex;
align-items: center;
padding: 8rem 0 4rem;
& .thankyou-box {
    text-align: center;
    &>img{
        margin: auto;
        margin-bottom: 1rem;
        max-width: 14rem;
    }
    & h3{
        padding: 0;
        margin: 0 0 2rem 0;
        font-size: 2.5rem;
        color: var(--theme-color);
        font-weight: 700;
        text-shadow: 2px 3px 4px #3333337a;
    }
    & .btn{
        line-height: 1;
        background-color: var(--theme-color);
        border-radius: 5rem;
        padding: 0.7rem 2rem;
        font-size: 0.875rem;
        color: var(--white);
        transition: all ease .5s;
        text-transform: capitalize;
        margin-top: 1.25rem;
    }
}
@media (max-width: 540px){
    & .thankyou-box h3 {
        line-height: 1;
        margin-top: 2rem;
    }
}
`