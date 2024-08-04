import styled from "vue3-styled-components";

export const WeddingAtBeachWrapper = styled.section`
padding: 3.125rem 0;
margin-top: 0;
position: relative;
overflow: initial;
margin-bottom: 5.625rem;
& .container {
    position: relative;
    display: flex;
}
& .dbeachtext {
    width: calc(100% - 42.5rem);
    padding-right: 4rem;
    & .contentsbeach {
        & .font19 {
            color: var(--orange);
            margin-bottom: 2rem;
        }
        & .font52 {
            line-height: 1.1;
            font-weight: 600;
        }
        & .font18 {
            color: #333333;
            padding-right: 2rem;
            line-height: 1.35;
            margin-top: 1rem;
            margin-bottom: 3rem;
        }
    }
    & .exploreme {
        background-color: var(--orange);
        padding: 0.8rem 2.5rem;
        font-size: 1.125rem;
        color: #fff;
    }
}
& .beachimg {
    width: 42.5rem;
}
@media (max-width: 767px){
    padding: 0;
    margin-top: 0;
    margin-bottom: 4.625rem;
    & .dbeachtext {
        width: 100%;
        padding: 0;
        & .contentsbeach {
            & .font19{
                margin-bottom: 1rem;
            }
            & .font52{
                font-size: 2rem;
            }
        }
    }
    & .beachimg{
        display: none;
    }
}
`